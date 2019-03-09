<?php
/**
 * Gestion de la connexion
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
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
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);	
    $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);	
    $visiteur = $pdo->getInfosVisiteur($login, $mdp);	
    if (!is_array($visiteur)) 
	{		
		$visiteur = $pdo->getInfosComptable($login, $mdp);		
		if (!is_array($visiteur)) 
		{	
                        include 'vues/v_enteteMini.php';
			ajouterErreur('Login ou mot de passe incorrect');		
			include 'vues/v_erreurs.php';
                        
			include 'vues/v_connexion.php';			
		} 
		else 
		{
			$_SESSION['statut'] = 'comptable';
			$id = $visiteur['id'];
			$nom = $visiteur['nom'];
			$prenom = $visiteur['prenom'];
			connecter($id, $nom, $prenom);
			$mois = getMois(date('d/m/Y'));
			$lesIdVisiteur = $pdo->getListeIdVisiteur();
			/*foreach ($lesIdVisiteur as $visiteur)
			{
				$visiteurATester = $visiteur['id'];
				if ($pdo->estPremierFraisMois($visiteurATester, $mois))
				{
					$pdo->clotureFicheFraisMoisFini($visiteurATester, $mois);
				}
			}*/
			header('Location: index.php');
			exit;
		}
	} 
	else
	{
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