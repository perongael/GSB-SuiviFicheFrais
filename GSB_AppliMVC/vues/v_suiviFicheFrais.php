<?php
/**
 * Affichage de la liste des fiches de frais ayant un état validée ou mis en paiement
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
?>
<hr>
<div class="row">
    <div class="panel panel-primary">
        <div class="panel-heading">Voici la liste des fiches de frais nécessitant une action de votre part</div>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th class="date">Visiteur</th>
                    <th class="libelle">Mois</th>  
                    <th class="montant">Etat de la fiche</th>  
                    <th class="action"></th> 
                </tr>
            </thead>  
            <tbody>
                <?php
                foreach ($listeFicheFrais as $uneFicheFrais) {
                    $idVisiteur = $uneFicheFrais['idVisiteur'];
                    $affichageVisiteur = $uneFicheFrais['prenomVisiteur'] . ' ' . $uneFicheFrais['nomVisiteur'] . ' - ' . $uneFicheFrais['idVisiteur'];
                    $numAnnee = substr($uneFicheFrais['mois'], 0, 4);
                    $numMois = substr($uneFicheFrais['mois'], 4, 2);
                    $mois = $numMois . ' / ' . $numAnnee;
                    $idEtat = $uneFicheFrais['libEtat'];
                    $visiteur = $uneFicheFrais['idVisiteur'] . ' ' . $uneFicheFrais['prenomVisiteur'] . ' ' . $uneFicheFrais['nomVisiteur'];
                    $moisTransmis = $uneFicheFrais['mois'];
                    ?>
                    <tr>
                        <td> <?php echo $affichageVisiteur ?></td>
                        <td> <?php echo $mois ?></td>
                        <td><?php echo $idEtat ?></td>
                        <td style="display:none;"><?php echo $visiteur ?></td>
                        <td style="display:none;"><?php echo $moisTransmis ?></td>
                        <td><a href="index.php?uc=validerFrais&action=voirFicheFraisSuiviDePaiement&chemin=suivrePaiementFichesFrais&mois=<?php echo 
						$moisTransmis ?>&visiteur=<?php echo $idVisiteur ?>">Selectionner la fiche de frais</a></td>
                    </tr>
                    <?php }
                ?> 
            </tbody>  
        </table>
    </div>
</div>	
<button class="btn btn-danger"  type="submit" onclick="window.location.href = 'index.php?uc=accueil&chemin=accueil';">Retour</button>
<br>
<br/>