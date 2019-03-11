<?php
/**
 * Affichage de la liste des visiteurs et des mois pour selection par le comptable
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
<h2>Selection de la fiche de frais souhaitée</h2>
<br/>
<br/>
<div class="row">
    <div class="col-md-4">
        <h3>Sélectionner les différentes informations : </h3>
    </div>
    <div class="col-md-4">

        <form action="index.php?uc=validerFrais&action=voirMoisVisiteur" 
              method="post" role="form">


            <div class="form-group">
                <label for="listeVisiteur" accesskey="n">Visiteur : </label>
                <select id="listeVisiteur" name="listeVisiteur" class="form-control">
                    <?php
                    foreach ($listeVisiteur as $unVisiteur) {
                        $id = $unVisiteur['id'];
                        $nom = $unVisiteur['nom'];
                        $prenom = $unVisiteur['prenom'];
                        ?>
                        <option value="<?php echo $id ?>">
                            <?php echo $nom . ' ' . $prenom . ' - id : ' . $id ?> </option>
                        <?php
                    }
                    ?>   
                </select>
                <br/>
                <br/>
                <label for="listeMois" accesskey="n">Mois : </label>
                <select id="listeMois" name="listeMois" class="form-control">
                    <?php
                    foreach ($listeMoisVisiteur as $unMois) {
                        $mois = $unMois['mois'];
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        if ($mois == $moisASelectionner) {
                            ?>
                            <option selected value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        }
                    }
                    ?>  
                </select>					
            </div>
            <br/>		
            <input id="ok" type="submit" value="Valider" class="btn btn-success" 
                   role="button">
            <input id="annuler" type="reset" value="Effacer" class="btn btn-danger" 
                   role="button">
        </form>
    </div>
</div>

