<?php

/**
 * Controleur pour l'affichage des fiches de frais
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
// Recupere les informations sur l'action à réaliser
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$idVisiteur = $_SESSION['idVisiteur'];
switch ($action) {
// Afficher une liste des visiteurs et des mois pour choix de la fiche de frais
    case 'selectionnerVisiteurEtMois':
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
        include 'vues/v_listeChoixFicheFrais.php';
        break;
// Affiche la fiche de frais du visiteur et du mois choisi
    case 'voirFicheFrais':
        $_SESSION['leMoisChoisi'] = filter_input(INPUT_POST, 'listeMois', FILTER_SANITIZE_STRING);
        $_SESSION['idVisiteurChoisi'] = filter_input(INPUT_POST, 'listeVisiteur', FILTER_SANITIZE_STRING);
        include 'controleurs/c_voirFicheFrais.php';
        break;
// Affiche la fiche de frais du visiteur et du mois choisi
    case 'voirFicheFraisSuiviDePaiement':
        $_SESSION['leMoisChoisi'] = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_STRING);
        $_SESSION['idVisiteurChoisi'] = filter_input(INPUT_GET, 'visiteur', FILTER_SANITIZE_STRING);
        $_SESSION['dejaImprimer'] = filter_input(INPUT_GET, 'dejaimprimer', FILTER_SANITIZE_STRING);
        include 'controleurs/c_voirFicheFrais.php';
        break;
// Orientation vers un choix d'action
    case 'choixSuiviFicheFrais':
        include 'vues/v_choixComptableSuiviFicheFrais.php';
        break;
//Affiche les fiches de frais pour le suivi du paiement, n'affiche que les fiches cloturées et validées
    case 'suiviPaiementFicheFrais':
        $valide = 'VA';
        $misEnPaiement = 'CL';
        $listeFicheFrais = $pdo->getListeFicheFraisAValiderOuMiseEnPaiement($valide, $misEnPaiement);
        include 'vues/v_suiviFicheFrais.php';
        break;
}