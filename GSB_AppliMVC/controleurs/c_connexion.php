<?php

/**
 * Controleur pour la gestion de la connexion
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
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if (!$uc) {
    $uc = 'demandeconnexion';
}
switch ($action) {
    default:
        include 'vues/v_connexion.php';
        break;
    case 'demandeConnexion':
        include 'vues/v_connexion.php';
        break;
    case 'valideConnexion':
// Récupère les informations saisies dans la page connexion
        $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
        $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);
// Test pour voir si un visiteur correspond au login et mdp
        $visiteur = $pdo->getInfosVisiteur($login, $mdp);
        if (!is_array($visiteur)) {
// Test pour voir si un comptable correspond au login et mdp
            $visiteur = $pdo->getInfosComptable($login, $mdp);
            if (!is_array($visiteur)) {
                // Si aucun visiteur et aucun comptable ne correspond alors affichage erreur		
                include 'vues/v_enteteMini.php';
                ajouterErreur('Login ou mot de passe incorrect');
                include 'vues/v_erreurs.php';

                include 'vues/v_connexion.php';
            } else {
                // Connexion sous le statut comptable		
                $_SESSION['statut'] = 'comptable';
                $id = $visiteur['id'];
                $nom = $visiteur['nom'];
                $prenom = $visiteur['prenom'];
                connecter($id, $nom, $prenom);
                $mois = getMois(date('d/m/Y'));
                $lesIdVisiteur = $pdo->getListeIdVisiteur();
                /* foreach ($lesIdVisiteur as $visiteur)
                  {
                  $visiteurATester = $visiteur['id'];
                  if ($pdo->estPremierFraisMois($visiteurATester, $mois))
                  {
                  $pdo->clotureFicheFraisMoisFini($visiteurATester, $mois);
                  }
                  } */
                header('Location: index.php');
                exit;
            }
        } else {
            // Connexion sous le statut visiteur
            $_SESSION['statut'] = 'visiteur';
            $id = $visiteur['id'];
            $nom = $visiteur['nom'];
            $prenom = $visiteur['prenom'];
            connecter($id, $nom, $prenom);
            header('Location: index.php');
            exit;
        }
        break;
}