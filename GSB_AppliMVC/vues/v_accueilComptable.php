<?php
/**
 * Affichage de l'accueil pour un comptable
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
<div id="accueil">
    <h2>
        Gestion des frais<small> - Comptable : 
<?php
echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']
?></small>
    </h2>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-bookmark"></span>
                    Navigation
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
					
                        <a href="index.php?uc=validerFrais&action=selectionnerVisiteurEtMois&chemin=validerFrais"
                           class="btn btn-success btn-lg" role="button">
                            <span class="glyphicon glyphicon-pencil"></span>
                            <br>Validation des fiches de frais des visiteurs</a>
							
                        <a href="index.php?uc=validerFrais&action=suiviPaiementFicheFrais&chemin=suivrePaiementFichesFrais"
                           class="btn btn-primary btn-lg" role="button">
                            <span class="glyphicon glyphicon-list-alt"></span>
                            <br>Suivre le paiement des fiches de frais</a>							
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>