<?php
/**
 * Affichage message confirmation modifications
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
<div class="alert alert-success" role="alert">
    <?php
    foreach ($_REQUEST['modification'] as $modification) {
        echo '<p>' . htmlspecialchars($modification) . '</p>';
    }
    ?>
</div>