<?php

/**
 * Controleur pour l'affichage des fiches de frais choisie
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
// Test si la fiche de frais existe et l'affiche si c'est le cas
if (!$pdo->estPremierFraisMois($_SESSION['idVisiteurChoisi'], $_SESSION['leMoisChoisi'])) {
    $visiteur = $pdo->getInfosVisiteurParId($_SESSION['idVisiteurChoisi']);
    $nomVisiteur = $visiteur['nom'];
    $prenomVisiteur = $visiteur['prenom'];
    $nomVisiteur = $nomVisiteur . ' ' . $prenomVisiteur . ' ' . $_SESSION['idVisiteurChoisi'];
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($_SESSION['idVisiteurChoisi'], $_SESSION['leMoisChoisi']);
    $lesFraisForfait = $pdo->getLesFraisForfait($_SESSION['idVisiteurChoisi'], $_SESSION['leMoisChoisi']);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($_SESSION['idVisiteurChoisi'], $_SESSION['leMoisChoisi']);
    $numAnnee = substr($_SESSION['leMoisChoisi'], 0, 4);
    $numMois = substr($_SESSION['leMoisChoisi'], 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $idEtat = $lesInfosFicheFrais['idEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    $quantiteFraisForfait = 0;
    $montanttotal = 0;
    $puissanceVehicule = $pdo->getPuissanceVehicule($_SESSION['idVisiteurChoisi'], $_SESSION['leMoisChoisi']);
    $lstPuissanceVehicule = $pdo->getPuissanceAllVehicule();
    foreach ($lstPuissanceVehicule as $unVehicule) {
        if ($unVehicule['id'] == $puissanceVehicule['vehiculeutilise']) {
            $vehiculeVisiteur = $unVehicule['designation'];
        }
    };
    if ($_SESSION['dejaImprimer'] == 'oui') {
        ajouterErreur('Cette fiche de frais a deja fait l\'objet d\'une generation de pdf, l\'orientation Green-IT de l\'entreprise interdit une nouvelle generation.');
        include 'vues/v_erreurs.php';
        $_SESSION['dejaImprimer'] = 'non';
    }
    include 'vues/v_etatFicheFraiChoisi.php';
// Si la fiche de frais n'existe pas, affiche message d'erreur
} else {
    $listeVisiteur = $pdo->getListeVisiteur();
    // Afin de sélectionner par défaut le dernier mois dans la zone de liste
    // on demande toutes les clés, et on prend la première,
    // les mois étant triés décroissants
    $lesVisiteurs = array_keys($listeVisiteur);
    $visiteurASelectionner = $lesVisiteurs[0];
    $listeMoisVisiteur = $pdo->getLensembleDesMois();
    // Afin de sélectionner par défaut le dernier mois dans la zone de liste
    // on demande toutes les clés, et on prend la première,
    // les mois étant triés décroissants
    $lesMois = array_keys($listeMoisVisiteur);
    $moisASelectionner = $lesMois[0];
    ajouterErreur('Pas de fiche de frais pour le visiteur au mois demandé, merci de modifier votre sélection.');
    include 'vues/v_erreurs.php';
    include 'vues/v_listeChoixFicheFrais.php';
}