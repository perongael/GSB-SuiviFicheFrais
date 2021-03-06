<?php

/**
 * Controleur pour la gestion des frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB 
 * @author    Peron Gaël
 * @copyright 2018 - 2019 
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */
 namespace gsb; 
$idVisiteur = $_SESSION['idVisiteur'];
$mois = getMois(date('d/m/Y'));
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
switch ($action) {
//Creer nouvelle fiche de frais si le visiteur n'en possede pas pour le mois passé en argument
    case 'saisirFrais':  
        if ($pdo->estPremierFraisMois($idVisiteur, $mois)) {
            $pdo->creeNouvellesLignesFrais($idVisiteur, $mois);
        }
        break;
//Verifie les données des frais au forfait et les mets à jour si ils sont conformes
    case 'validerMajFraisForfait':
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        $puissance = filter_input(INPUT_POST, 'puissanceVehicule', FILTER_SANITIZE_STRING);
        if ($_SESSION['statut'] == 'comptable') {
            $idVisiteur = $_SESSION['idVisiteurChoisi'];
            $mois = $_SESSION['leMoisChoisi'];
        }
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
            $pdo->majPuissanceVehicule($idVisiteur, $mois, $puissance);
            ajouterModification('Les éléments ont étés mis à jour');
            include 'vues/v_modifications.php';
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }
        break;
//Vérifie les infos d'un frais hors forfait et enregistre si c'est bon
    case 'validerCreationFrais':
        $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_STRING);
        $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
        $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
        valideInfosFrais($dateFrais, $libelle, $montant);
        if (nbErreurs() != 0) {
            include 'vues/v_erreurs.php';
        } else {
            $pdo->creeNouveauFraisHorsForfait(
                    $idVisiteur, $mois, $libelle, $dateFrais, $montant
            );
        }
        break;
//Supprime un frais hors forfait de la base de données
    case 'supprimerFrais':
        $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
        $pdo->supprimerFraisHorsForfait($idFrais);
        break;
//Refuse un frais hors forfait de la base de données en ajoutant REFUSE à son libelle
//et en changeant son statut
    case 'refuserFrais':
        $idfrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
        $libelletest = filter_input(INPUT_GET, 'libelle', FILTER_SANITIZE_STRING);
        $refus = $pdo->verifierSiFraisRefuser($idfrais);
        if ($refus == true) {
            ?>
            <script language="Javascript">
                window.alert("Ce frais a déjà été refusé par le service comptable");
            </script> 
            <?php
        } else {
            $libelle = "REFUSE - " . $libelletest;
            $tailleLibelle = strlen($libelle);
            if ($tailleLibelle > 100) {
                $libelle = substr($libelle, 0, 100);
            }
            $pdo->refuserFraisHorsForfait($idfrais, $libelle);
        }
        break;
//Accepte un frais hors forfait ayant été refusé au préalable
    case 'accepterFrais':
        $idfrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
        $libelletest = filter_input(INPUT_GET, 'libelle', FILTER_SANITIZE_STRING);
        $libelle = substr($libelletest, 8);
        $pdo->accepterFraisHorsForfait($idfrais, $libelle);
        break;
//Reporte un frais hors forfait au mois suivant, avec création fiche de frais si nécessaire
    case 'reporterFrais':
        $idfrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
        $libelletest = filter_input(INPUT_GET, 'libelle', FILTER_SANITIZE_STRING);
        $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
        $montant = filter_input(INPUT_GET, 'montant', FILTER_SANITIZE_STRING);
        $idVisiteur = $_SESSION['idVisiteurChoisi'];
        $annee = substr($_SESSION['leMoisChoisi'], 0, 4);
        $mois = substr($_SESSION['leMoisChoisi'], 4, 2);
        $dateModifier = $annee . '-' . $mois . '-' . '01';
        $moisDestination = date('Y-m-d', strtotime('+1 month', strtotime($dateModifier)));
        $dateDestinationFrancais = dateAnglaisVersFrancais($moisDestination);
        $moisDestination = getMois($dateDestinationFrancais);
        if (!$pdo->estPremierFraisMois($idVisiteur, $moisDestination)) {
            //Juste ajouter frais hors forfait
            $pdo->creeNouveauFraisHorsForfait($idVisiteur, $moisDestination, $libelletest, $date, $montant);
        } else {
            //Créer nouvelle fiche et ajouter frais hors forfait			
            $pdo->creeNouvellesLignesFrais($idVisiteur, $moisDestination);
            $pdo->creeNouveauFraisHorsForfait($idVisiteur, $moisDestination, $libelletest, $date, $montant);
        }
        $pdo->supprimerFraisHorsForfait($idfrais);
        break;
//Valide une fiche de frais et change son état à mis en paiement
    case 'validerFicheFraisEtPassageEnMiseEnPaiement':
        $idVisiteur = $_SESSION['idVisiteurChoisi'];
        $mois = $_SESSION['leMoisChoisi'];
        $etat = 'VA';
        $test = $pdo->majMontantValideFicheFrais($idVisiteur, $mois);
        $pdo->majEtatFicheFrais($idVisiteur, $mois, $etat);
        break;
//Confirme le remboursement d'une fiche de frais et change son état à remboursé
    case 'confirmationRemboursementFicheFrais':
        $idVisiteur = $_SESSION['idVisiteurChoisi'];
        $mois = $_SESSION['leMoisChoisi'];
        $etat = 'RB';
        $pdo->majEtatFicheFrais($idVisiteur, $mois, $etat);
        break;
//Modifie le nb de justificatif reçu pour une fiche de frais en verifiant si la valeur est correct
    case 'majAJourNbJustificatifs':
        $nbJusti = filter_input(INPUT_POST, 'nbJustificatifs', FILTER_DEFAULT, FILTER_VALIDATE_FLOAT);
        $idVisiteur = $_SESSION['idVisiteurChoisi'];
        $mois = $_SESSION['leMoisChoisi'];
        if (estEntierPositif($nbJusti)) {
            $pdo->majNbJustificatifs($idVisiteur, $mois, $nbJusti);
            ajouterModification('Le nombre de justificatifs des frais hors-forfait a été mis à jour');
            include 'vues/v_modifications.php';
        } else {
            ajouterErreur('Le nombre de justificatifs doit être un  entier numériques');
            include 'vues/v_erreurs.php';
        }
        break;
}
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
$puissanceVehicule = $pdo->getPuissanceVehicule($idVisiteur, $mois);
$lstPuissanceVehicule = $pdo->getPuissanceAllVehicule();
if ($_SESSION['statut'] == 'comptable') {
    require 'controleurs/c_voirFicheFrais.php';
} else {
    require 'vues/v_listeFraisForfait.php';
    require 'vues/v_listeFraisHorsForfait.php';
}