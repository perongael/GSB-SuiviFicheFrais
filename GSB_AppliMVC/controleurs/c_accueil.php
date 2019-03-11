<?php

/**
 * Controleur pour orienter l'utilisateur en fonction de son statut
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
// Test si l'utilisateur est connecté
if ($estConnecte) {
// Si l'utilisateur est est un visiteur il redirige vers l'accueil visiteur
    if ($_SESSION['statut'] == 'visiteur') {
        include 'vues/v_accueilVisiteur.php';
    }
// Si l'utilisateur est est un comptable il redirige vers l'accueil comptable
    if ($_SESSION['statut'] == 'comptable') {
        include 'vues/v_accueilComptable.php';
    }
// Si l'utilisateur n'est pas connecté redirection vers la page de connexion
} else {	
    include 'vues/v_connexion.php';	
}
