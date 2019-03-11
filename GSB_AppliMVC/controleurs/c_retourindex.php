<?php

/**
 * Controleur pour le retour à l'index
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
header('Location: index.php?uc=validerFrais&action=voirFicheFraisSuiviDePaiement');
end();
?>