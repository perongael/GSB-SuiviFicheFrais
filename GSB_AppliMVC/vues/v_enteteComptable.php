<?php
/**
 * Affichage de l'entête pour un utilisateur au statut comptable
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB 
 * @author    Gaël Peron
 * @copyright 2018 - 2019 
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */
 namespace gsb;
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
            <?php
            $uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
            if ($estConnecte) {
                ?>
                <div class="header">
                    <div class="row vertical-align">
                        <div class="col-md-4">
                            <h1>
                                <img src="./images/logo.jpg" class="img-responsive" 
                                     alt="Laboratoire Galaxy-Swiss Bourdin" 
                                     title="Laboratoire Galaxy-Swiss Bourdin">
                            </h1>
                        </div>
                        <div class="col-md-8">
                            <ul class="nav nav-pills pull-right" role="tablist">
                                <li <?php if (!$uc || $_SESSION['chemin'] == 'accueil') { ?>class="active" <?php } ?>>
                                    <a href="index.php">
                                        <span class="glyphicon glyphicon-home"></span>
                                        Accueil
                                    </a>
                                </li>
                                <li <?php if ($_SESSION['chemin'] == 'validerFrais') { ?>class="active"<?php } ?>>
                                    <a href="index.php?uc=validerFrais&action=selectionnerVisiteurEtMois&chemin=validerFrais">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                        Validation des fiches de frais
                                    </a>
                                </li>
                                <li <?php if ($_SESSION['chemin'] == 'suivrePaiementFichesFrais') { ?>class="active"<?php } ?>>
                                    <a href="index.php?uc=validerFrais&action=suiviPaiementFicheFrais&chemin=suivrePaiementFichesFrais">
                                        <span class="glyphicon glyphicon-list-alt"></span>
                                        Suivre le paiement des fiches de frais
                                    </a>
                                </li>
                                <li 
                                    <?php if ($_SESSION['chemin'] == 'deconnexion') { ?>class="active"<?php } ?>>
                                    <a href="index.php?uc=deconnexion&action=demandeDeconnexion">
                                        <span class="glyphicon glyphicon-log-out"></span>
                                        Déconnexion
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php
            } 