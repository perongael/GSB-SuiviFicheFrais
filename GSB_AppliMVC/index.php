<?php
/**
 * Index du projet GSB
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB 
 * @author    Peron Gaël
 * @copyright 2018 - 2019 
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
require_once 'includes/fct.inc.php';

require_once 'includes/class.pdogsb.inc.php';

session_start();

$pdo = PdoGsb::getPdoGsb();

$estConnecte = estConnecte();

$_SESSION['chemin'] = filter_input(INPUT_GET, 'chemin', FILTER_SANITIZE_STRING);

$uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);

if ($uc != 'imprimerFicheFrais' && $uc != 'connexion')
{
	require 'controleurs/c_entete.php';
}
if ($uc && !$estConnecte) 
{
    $uc = 'connexion';
} 
elseif (empty($uc))
{
    $uc = 'accueil';
	$_SESSION['chemin'] = 'accueil';
	}
switch ($uc) {
    case 'connexion':
            include 'controleurs/c_connexion.php';
            break;
    case 'accueil':
            include 'controleurs/c_accueil.php';
            break;
    case 'gererFrais':
            include 'controleurs/c_gererFrais.php';
            break;
    case 'etatFrais':
            include 'controleurs/c_etatFrais.php';
            break;
    case 'validerFrais':
            include 'controleurs/c_validerFrais.php';
            break;
    case 'voirFicheFrais':
            include 'controleurs/c_voirFicheFrais.php';
            break;
    case 'imprimerFicheFrais':
            include 'controleurs/c_imprimer.php';
            break;
    case 'deconnexion':
            include 'controleurs/c_deconnexion.php';
            break;
}
if ($uc != 'imprimerFicheFrais')
{
	require 'vues/v_pied.php';
}