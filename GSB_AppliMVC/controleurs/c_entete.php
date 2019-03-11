<?php

/**
 * Controleur pour la gestion de l'affichage de l'entête
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