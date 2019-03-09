<?php
/**
 * Gestion de l'affichage des frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
 
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING); 

$idVisiteur = $_SESSION['idVisiteur'];


switch ($action) {
	
	
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
	
			
	
case 'voirFicheFrais':  	

    $_SESSION['leMoisChoisi'] = filter_input(INPUT_POST, 'listeMois', FILTER_SANITIZE_STRING);	
    $_SESSION['idVisiteurChoisi'] = filter_input(INPUT_POST, 'listeVisiteur', FILTER_SANITIZE_STRING);   	
	include 'controleurs/c_voirFicheFrais.php';
	break; 

	
case 'voirFicheFraisSuiviDePaiement': 	

    $_SESSION['leMoisChoisi'] = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_STRING);	
    $_SESSION['idVisiteurChoisi'] = filter_input(INPUT_GET, 'visiteur', FILTER_SANITIZE_STRING);
	$_SESSION['dejaImprimer'] = filter_input(INPUT_GET, 'dejaimprimer', FILTER_SANITIZE_STRING);  	
	include 'controleurs/c_voirFicheFrais.php';
	break; 
	
	
case 'choixSuiviFicheFrais':	

	include 'vues/v_choixComptableSuiviFicheFrais.php';	
    break;
	
	
case 'suiviPaiementFicheFrais':

	$valide = 'VA';
	$misEnPaiement = 'CL';
    $listeFicheFrais = $pdo->getListeFicheFraisAValiderOuMiseEnPaiement($valide, $misEnPaiement);
	include 'vues/v_suiviFicheFrais.php';	
    break;    
}