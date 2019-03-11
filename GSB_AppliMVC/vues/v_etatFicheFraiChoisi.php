<?php
/**
 * Affichage de la fiche de frais choisie par le comptable
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
<div class="panel panel-primary">
    <div class="panel-heading">Fiche de frais -- <?php echo $nomVisiteur ?> -- <?php echo $numMois . '/' . $numAnnee ?> </div>
    <div class="panel-body">
        <strong><u>Etat :</u></strong> <?php
        switch ($idEtat) {

            case 'CR':
                echo $libEtat
                ?> par le visiteur. Dernière modification le <?php
                echo $dateModif;
                break;

            case 'CL':
				echo $libEtat
				?> pour le visiteur. Dernière modification le <?php 
				echo $dateModif ?> en attente de validation du service comptable <?php;
                break;

            case 'RB':
                echo $libEtat
                ?> depuis le <?php
                echo $dateModif;
                break;

            case 'VA':
                echo $libEtat
                ?> depuis le <?php
                echo $dateModif;
                break;
        }
        ?>			
        <br/>
        <br/>
        <strong><u>Montant validé :</u></strong> <?php echo $montantValide ?>
    </div>
</div>

<?php if ($idEtat == 'CR' || $idEtat == 'CL') { ?>

    <div class="row">  		
        <h3>Eléments forfaitisés</h3>
        <div class="col-md-4">
            <form method="post" 
                  action="index.php?uc=gererFrais&action=validerMajFraisForfait&chemin=<?php echo $_SESSION['chemin'] ?>" 
                  role="form">
                <fieldset>       
    <?php
    foreach ($lesFraisForfait as $unFrais) {
        $idFrais = $unFrais['idfrais'];
        $libelle = htmlspecialchars($unFrais['libelle']);
        $quantite = $unFrais['quantite'];
        ?>
                        <div class="form-group">
                            <label for="idFrais"><?php echo $libelle ?></label>
                            <input type="text" id="idFrais" 
                                   name="lesFrais[<?php echo $idFrais ?>]"
                                   size="10" maxlength="5" 
                                   value="<?php echo $quantite ?>" 
                                   class="form-control">
                        </div>
                                <?php
                                if ($idFrais == "KM") {
                                    ?>
                            <div class="form-group">
                                <label for="idFrais">Puissance véhicule</label>
                                <select id="puissanceVehicule" name="puissanceVehicule" class="form-control">						
                                        <?php
                                        foreach ($lstPuissanceVehicule as $unePuissance) {
                                            $idPuissance = $unePuissance['id'];
                                            $nom = $unePuissance['designation'];
                                            if ($idPuissance == $puissanceVehicule['vehiculeutilise']) {
                                                ?>
                                            <option selected="selected" value="<?php echo $idPuissance ?>">
                                                <?php echo $nom ?> </option>
                                                <?php
                                        } else {
                                            ?>
                                            <option value="<?php echo $idPuissance ?>">
                                            <?php echo $nom ?> </option>
                    <?php
                }
            }
            ?>												
                                </select>
                            </div>	
                            <?php
                        }
                    }
                    ?>
                    <button class="btn btn-warning"  type="submit">Modifier</button>
                    <button class="btn btn-danger" type="reset">Effacer</button>
                </fieldset>
            </form>
        </div>
    </div>	   	   
    <br/>   
    <div class="row">
        <div class="panel panel-info">
            <div class="panel-heading">Descriptif des éléments hors forfait</div>
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th class="date">Date</th>
                        <th class="libelle">Libellé</th>  
                        <th class="montant">Montant</th>  
                        <th class="action">Action</th> 
                    </tr>
                </thead>  
                <tbody>
                    <?php
                    foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                        $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                        $date = $unFraisHorsForfait['date'];
                        $montant = $unFraisHorsForfait['montant'];
                        $id = $unFraisHorsForfait['id'];
                        $statut = $unFraisHorsForfait['refuser'];
                        ?> 					
                        <tr>
                            <td> <?php echo $date ?></td>
                            <td> <?php echo $libelle ?></td>
                            <td><?php echo $montant ?></td>

                                <?php
                                if ($statut == true) {
                                    ?><td><a href="index.php?uc=gererFrais&action=accepterFrais&chemin=<?php echo $_SESSION['chemin'] ?>&idFrais=<?php echo $id ?>&libelle=<?php echo $libelle ?>" 
                                       onclick="return confirm('Voulez-vous vraiment accepter à nouveau ce frais?');">Accepter le frais</a></td><?php
                                } else {
                                    ?><td><a href="index.php?uc=gererFrais&action=refuserFrais&chemin=<?php echo $_SESSION['chemin'] ?>&idFrais=<?php echo $id ?>&libelle=<?php echo $libelle ?>" 
                                       onclick="return confirm('Voulez-vous vraiment refuser ce frais?');">Refuser le frais</a></td><?php
                    }
                    ?> 						   
                            <td><a href="index.php?uc=gererFrais&action=reporterFrais&chemin=<?php echo $_SESSION['chemin'] ?>&idFrais=<?php echo $id ?>&libelle=<?php echo $libelle ?>&montant=<?php echo $montant ?>&date=<?php echo $date ?>" 
                                   onclick="return confirm('Voulez-vous vraiment reporter au mois suivant ce frais?');">Reporter</a></td>
                        </tr>
        <?php
    }
    ?>
                </tbody>  
            </table>
        </div>
    </div>

    <div class="row">  		
        <div class="col-md-4">
            <form method="post" 
                  action="index.php?uc=gererFrais&action=majAJourNbJustificatifs&chemin=validerFrais&chemin=<?php echo $_SESSION['chemin'] ?>" 
                  role="form">
                <fieldset>					
                    <div class="form-group">
                        <label for="nbJustificatifs">Nombre de justificatifs</label>
                        <input type="text" id="nbJustificatifs" 
                               name="nbJustificatifs"
                               size="7" maxlength="5"							   
                               value="<?php echo $nbJustificatifs ?>" 
                               class="form-control">
                    </div>

                    <button class="btn btn-warning"  type="submit">Modifier</button>
                    <button class="btn btn-danger" type="reset">Effacer</button>
                </fieldset>
            </form>
        </div>
    </div>
    <br>
    <br/>


    <?php } else {
    ?>

    <div class="panel panel-info">
        <div class="panel-heading">Eléments forfaitisés</div>
        <table class="table table-bordered table-responsive">
            <tr>
                <?php
                foreach ($lesFraisForfait as $unFraisForfait) {
                    $libelle = $unFraisForfait['libelle'];
                    ?>
                    <th> <?php echo htmlspecialchars($libelle) ?></th>
                    <?php
                }
                ?>
                <th>Puissance véhicule</th>
            </tr>
            <tr>
                <?php
                foreach ($lesFraisForfait as $unFraisForfait) {
                    $quantite = $unFraisForfait['quantite'];
                    ?>
                    <td class="qteForfait"><?php echo $quantite ?> </td>
        <?php
    }
    ?>
                <td class="qteForfait"><?php echo $vehiculeVisiteur ?> </td>
            </tr>
        </table>
    </div>



    <div class="panel panel-info">
        <div class="panel-heading">Descriptif des éléments hors forfait - 
    <?php echo $nbJustificatifs ?> justificatifs reçus</div>
        <table class="table table-bordered table-responsive">
            <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th>                
            </tr>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $date = $unFraisHorsForfait['date'];
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $montant = $unFraisHorsForfait['montant'];
                ?>
                <tr>
                    <td><?php echo $date ?></td>
                    <td><?php echo $libelle ?></td>
                    <td><?php echo $montant ?></td>
                </tr>
        <?php
    }
    ?>
        </table>	
    </div>


    <div class="panel panel-info">
        <div class="panel-heading">Nombre de justificatifs</div>
        <table class="table table-bordered table-responsive">        
            <tr>            
                <td class="nbJustificatifs"><?php echo $nbJustificatifs ?> </td>                
            </tr>
        </table>
    </div>

    <?php
}
?>	
<?php
if ($_SESSION['chemin'] == 'validerFrais') {

    if ($idEtat == 'CL') {
        ?>	
        <input id="Valider" type="submit" value="Valider la fiche de frais" class="btn btn-success" 
               role="button" onclick="window.location.href = 'index.php?uc=gererFrais&action=validerFicheFraisEtPassageEnMiseEnPaiement&chemin=validerFrais';">  
               <?php
           }
           ?>	

    <button class="btn btn-danger"  type="submit" onclick="window.location.href = 'index.php?uc=validerFrais&action=selectionnerVisiteurEtMois&chemin=validerFrais';">Retour</button>
    <?php
}

if ($_SESSION['chemin'] == 'suivrePaiementFichesFrais') {


    if ($idEtat == 'CL') {
        ?>	
        <input id="Valider" type="submit" value="Valider la fiche de frais" class="btn btn-success" 
               role="button" onclick="window.location.href = 'index.php?uc=gererFrais&action=validerFicheFraisEtPassageEnMiseEnPaiement&chemin=suivrePaiementFichesFrais';">	   
               <?php
           }

           if ($idEtat == 'VA') {
               ?>	
        <input id="Valider" type="submit" value="Confirmer le remboursement" class="btn btn-success" 
               role="button" onclick="window.location.href = 'index.php?uc=gererFrais&action=confirmationRemboursementFicheFrais&chemin=suivrePaiementFichesFrais';">	   
        <?php
    }

    if ($idEtat == 'VA' || $idEtat == 'RB') {
        ?>	
        <input id="Valider" type="submit" value="Generer PDF" class="btn btn-info" 
               role="button" onclick="window.location.href = 'index.php?uc=imprimerFicheFrais&chemin=suivrePaiementFichesFrais';">	   
        <?php
    }
    ?>

    <button class="btn btn-danger"  type="submit" onclick="window.location.href = 'index.php?uc=validerFrais&action=suiviPaiementFicheFrais&chemin=suivrePaiementFichesFrais';">Retour</button>
    <?php
}
?>		

<br>
<br/>

