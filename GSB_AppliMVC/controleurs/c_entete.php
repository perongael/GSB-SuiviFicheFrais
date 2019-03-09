<?php
/**
 * Gestion de l'entete
 *
 * @author    Gael
 */
if ($estConnecte == false) {
	$_SESSION['statut'] = 'nonDefini';
}
if ($_SESSION['statut'] == 'visiteur') {
	require 'vues/v_enteteVisiteur.php';
}
if ($_SESSION['statut'] == 'comptable') {
	require 'vues/v_enteteComptable.php';
}
if ($_SESSION['statut'] == 'nonDefini') {
	require 'vues/v_enteteMini.php';
} 