<?php

/**
 * Affichage de l'entête minnimu
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
 
// Test si l'utilisateur n'est pas connecté et affichage de l'entête
if (!$estConnecte) {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta charset="UTF-8">
            <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title> 
            <meta name="description" content="">
            <meta name="author" content="">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="./styles/bootstrap/bootstrap.css" rel="stylesheet">
            <link href="./styles/style.css" rel="stylesheet">
        </head>	
        <body>
            <div class="container">              
                <h1>
                    <img src="./images/logo.jpg"
                         class="img-responsive center-block"
                         alt="Laboratoire Galaxy-Swiss Bourdin"
                         title="Laboratoire Galaxy-Swiss Bourdin">
                </h1> 
                <?php
            }     