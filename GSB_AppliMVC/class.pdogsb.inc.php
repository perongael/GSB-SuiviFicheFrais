<?php
/**
 * Classe d'accès aux données.
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL - CNED <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */
/**
 * Classe d'accès aux données.
 *
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO
 * $monPdoGsb qui contiendra l'unique instance de la classe
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   Release: 1.0
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */

class PdoGsb {

    private static $serveur = 'mysql:host=localhost';
    private static $bdd = 'dbname=gsb_frais';
    private static $user = 'userGsb';
    private static $mdp = 'secret';
    private static $monPdo;
    private static $monPdoGsb = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct() {
        PdoGsb::$monPdo = new PDO(PdoGsb::$serveur . ';' . PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
        PdoGsb::$monPdo->query('SET CHARACTER SET utf8');
    }

    /**
     * Méthode destructeur appelée dès qu'il n'y a plus de référence sur un
     * objet donné, ou dans n'importe quel ordre pendant la séquence d'arrêt.
     */
    public function __destruct() {
        PdoGsb::$monPdo = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
     *
     * @return l'unique objet de la classe PdoGsb
     */
    public static function getPdoGsb() {
        if (PdoGsb::$monPdoGsb == null) {
            PdoGsb::$monPdoGsb = new PdoGsb();
        }
        return PdoGsb::$monPdoGsb;
    }

    /**
     * Retourne les informations d'un visiteur
     *
     * @param String $login Login du visiteur
     * @param String $mdp   Mot de passe du visiteur
     *
     * @return l'id, le nom et le prénom sous la forme d'un tableau associatif
     */
    public function getInfosVisiteur($login, $mdp) {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT visiteur.id AS id, visiteur.nom AS nom, '
                . 'visiteur.prenom AS prenom '
                . 'FROM visiteur '
                . 'WHERE visiteur.login = :unLogin AND visiteur.mdp = :unMdp'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMdp', $mdp, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }
	
	/**
     * Retourne les informations d'un comptable
     *
     * @param String $login Login du comptable
     * @param String $mdp   Mot de passe du comptable
     *
     * @return l'id, le nom et le prénom sous la forme d'un tableau associatif
     */
    public function getInfosComptable($login, $mdp) {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT comptable.id AS id, comptable.nom AS nom, '
                . 'comptable.prenom AS prenom '
                . 'FROM comptable '
                . 'WHERE comptable.login = :unLogin AND comptable.mdp = :unMdp'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMdp', $mdp, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }

    /**
     * Retourne les informations d'un visiteur concerné par l'id en argument
     *
     * @param String $id Id du visiteur     
     *
     * @return l'id, le nom et le prénom sous la forme d'un tableau associatif
     */
    public function getInfosVisiteurParId($id) {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT visiteur.id AS id, visiteur.nom AS nom, '
                . 'visiteur.prenom AS prenom '
                . 'FROM visiteur '
                . 'WHERE visiteur.id = :unId'
        );
        $requetePrepare->bindParam(':unId', $id, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }
	
    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * hors forfait concernées par les deux arguments.
     * La boucle foreach ne peut être utilisée ici car on procède
     * à une modification de la structure itérée - transformation du champ date-
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return tous les champs des lignes de frais hors forfait sous la forme
     * d'un tableau associatif
     */
    public function getLesFraisHorsForfait($idVisiteur, $mois) {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT * FROM lignefraishorsforfait '
                . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraishorsforfait.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetchAll();
        for ($i = 0; $i < count($lesLignes); $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

    /**
     * Retourne le nombre de justificatif d'un visiteur pour un mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return le nombre entier de justificatifs
     */
    public function getNbjustificatifs($idVisiteur, $mois) {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT fichefrais.nbjustificatifs as nb FROM fichefrais '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne['nb'];
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * au forfait concernées par les deux arguments
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return l'id, le libelle et la quantité sous la forme d'un tableau
     * associatif
     */
    public function getLesFraisForfait($idVisiteur, $mois) {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'SELECT fraisforfait.id as idfrais, '
                . 'fraisforfait.libelle as libelle, '
                . 'fraisforfait.montant as montant, '
                . 'lignefraisforfait.quantite as quantite '
                . 'FROM lignefraisforfait '
                . 'INNER JOIN fraisforfait '
                . 'ON fraisforfait.id = lignefraisforfait.idfraisforfait '
                . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraisforfait.mois = :unMois '
                . 'ORDER BY lignefraisforfait.idfraisforfait'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne tous les id de la table FraisForfait
     *
     * @return un tableau associatif
     */
    public function getLesIdFrais() {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT fraisforfait.id as idfrais '
                . 'FROM fraisforfait ORDER BY fraisforfait.id'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Met à jour la table ligneFraisForfait
     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param Array  $lesFrais   tableau associatif de clé idFrais et
     *                           de valeur la quantité pour ce frais
     *
     * @return null
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais) {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $requetePrepare = PdoGSB::$monPdo->prepare(
                    'UPDATE lignefraisforfait '
                    . 'SET lignefraisforfait.quantite = :uneQte '
                    . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                    . 'AND lignefraisforfait.mois = :unMois '
                    . 'AND lignefraisforfait.idfraisforfait = :idFrais'
            );
            $requetePrepare->bindParam(':uneQte', $qte, PDO::PARAM_INT);
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFrais, PDO::PARAM_STR);
            $requetePrepare->execute();
        }
    }
	
	/**
     * Met à jour la puissance du véhicule utilisé dans une fiche de frais
     * Met à jour la table fichefrais pour un visiteur et
     * un mois donné en enregistrant la nouvelle puissance véhicule
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $puissance  Nouvelle puissance à enregistrer
     *
     * @return null
     */
    public function majPuissanceVehicule($idVisiteur, $mois, $puissance) {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'UPDATE fichefrais '
                . 'SET fichefrais.vehiculeutilise = :unePuissance '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unePuissance', $puissance, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Met à jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concerné
     *
     * @param String  $idVisiteur      ID du visiteur
     * @param String  $mois            Mois sous la forme aaaamm
     * @param Integer $nbJustificatifs Nombre de justificatifs
     *
     * @return null
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs) {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'UPDATE fichefrais '
                . 'SET nbjustificatifs = :unNbJustificatifs '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unNbJustificatifs', $nbJustificatifs, PDO::PARAM_INT);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return vrai ou faux
     */
    public function estPremierFraisMois($idVisiteur, $mois) {
        $boolReturn = false;
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT fichefrais.mois FROM fichefrais '
                . 'WHERE fichefrais.mois = :unMois '
                . 'AND fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        if (!$requetePrepare->fetch()) {
            $boolReturn = true;
        }
        return $boolReturn;
    }

    /**
     * Retourne le dernier mois en cours d'un visiteur
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return le mois sous la forme aaaamm
     */
    public function dernierMoisSaisi($idVisiteur) {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT MAX(mois) as dernierMois '
                . 'FROM fichefrais '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    /**
     * Crée une nouvelle fiche de frais et les lignes de frais au forfait
     * pour un visiteur et un mois donnés
     *
     * Récupère le dernier mois en cours de traitement, met à 'CL' son champs
     * idEtat, crée une nouvelle fiche de frais avec un idEtat à 'CR' et crée
     * les lignes de frais forfait de quantités nulles
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return null
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois) {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'INSERT INTO fichefrais (idvisiteur,mois,nbjustificatifs,'
                . 'montantvalide,datemodif,idetat) '
                . "VALUES (:unIdVisiteur,:unMois,0,0,now(),'CR')"
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesIdFrais = $this->getLesIdFrais();
        foreach ($lesIdFrais as $unIdFrais) {
            $requetePrepare = PdoGsb::$monPdo->prepare(
                    'INSERT INTO lignefraisforfait (idvisiteur,mois,'
                    . 'idfraisforfait,quantite) '
                    . 'VALUES(:unIdVisiteur, :unMois, :idFrais, 0)'
            );
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(
                    ':idFrais', $unIdFrais['idfrais'], PDO::PARAM_STR
            );
            $requetePrepare->execute();
        }
    }
	
	 /**
     * Cloture la fiche de frais d'un visiteur si le mois de la fiche est terminé
     *
     * @param String $idVisiteur ID du visiteur
	 * @param String $mois mois à tester
     *
     * @return null
     */
    public function clotureFicheFraisMoisFini($idVisiteur, $mois) {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
    }

    /**
     * Crée un nouveau frais hors forfait pour un visiteur un mois donné
     * à partir des informations fournies en paramètre
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $libelle    Libellé du frais
     * @param String $date       Date du frais au format français jj//mm/aaaa
     * @param Float  $montant    Montant du frais
     *
     * @return null
     */
    public function creeNouveauFraisHorsForfait(
    $idVisiteur, $mois, $libelle, $date, $montant
    ) {
        $dateFr = dateFrancaisVersAnglais($date);
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'INSERT INTO lignefraishorsforfait '
                . 'VALUES (null, :unIdVisiteur,:unMois, :unLibelle, :uneDateFr,'
                . ':unMontant, false) '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->execute();
    }

    /**
     * Supprime le frais hors forfait dont l'id est passé en argument
     *
     * @param String $idFrais ID du frais
     *
     * @return null
     */
    public function supprimerFraisHorsForfait($idFrais) {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'DELETE FROM lignefraishorsforfait '
                . 'WHERE lignefraishorsforfait.id = :unIdFrais'
        );
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Supprime le frais hors forfait dont l'id est passé en argument
     *
     * @param String $idFrais ID du frais
     *
     * @return null
     */
    public function refuserFraisHorsForfait($idfrais, $libelle) {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'UPDATE lignefraishorsforfait '
                . 'SET lignefraishorsforfait.libelle = :unlibelle, lignefraishorsforfait.refuser = true '
                . 'WHERE lignefraishorsforfait.id = :idFrais'
        );
        $requetePrepare->bindParam(':unlibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':idFrais', $idfrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
	
	/**
     * Accepte le frais hors forfait dont l'id est passé en argument
     *
     * @param String $idFrais ID du frais
     *
     * @return null
     */
    public function accepterFraisHorsForfait($idfrais, $libelle) {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'UPDATE lignefraishorsforfait '
                . 'SET lignefraishorsforfait.libelle = :unlibelle, lignefraishorsforfait.refuser = false '
                . 'WHERE lignefraishorsforfait.id = :idFrais'
        );
        $requetePrepare->bindParam(':unlibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':idFrais', $idfrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
	
	/**
     * Verifie si le frais hors forfait dont l'id est passé en argument est refusé ou non
     *
     * @param String $idFrais ID du frais
     *
     * @return le statut du frais
     */
    public function verifierSiFraisRefuser($idfrais) {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'SELECT lignefraishorsforfait.refuser as statut FROM lignefraishorsforfait  '
                . 'WHERE lignefraishorsforfait.id = :idFrais '
        );
        $requetePrepare->bindParam(':idFrais', $idfrais, PDO::PARAM_STR);
        $requetePrepare->execute();
        $refus = $requetePrepare->fetch();
        return $refus['statut'];
    }

    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs
     *         l'année et le mois correspondant
     */
    public function getLesMoisDisponibles($idVisiteur) {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'SELECT fichefrais.mois AS mois FROM fichefrais '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }

    /**
     * Retourne l'nsemble des visiteurs
     *    
     * 
     */
    public function getListeVisiteur() {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'SELECT visiteur.id AS id, visiteur.nom AS nom, visiteur.prenom AS prenom FROM visiteur '
                . 'ORDER BY visiteur.nom asc'
        );
        $requetePrepare->execute();
        $listeVisiteur = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $id = $laLigne['id'];
            $nom = $laLigne['nom'];
            $prenom = $laLigne['prenom'];
            $listeVisiteur[] = array(
                'id' => $id,
                'nom' => $nom,
                'prenom' => $prenom,
            );
        }
        return $listeVisiteur;
    }

    /**
     * Retourne l'ensemble des mois
     */
    public function getLensembleDesMois() {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'SELECT DISTINCT fichefrais.mois AS mois FROM fichefrais '
                . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->execute();
        $listeEnsembleDesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $listeEnsembleDesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $listeEnsembleDesMois;
    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un
     * mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return un tableau avec des champs de jointure entre une fiche de frais
     *         et la ligne d'état
     */
    public function getLesInfosFicheFrais($idVisiteur, $mois) {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'SELECT fichefrais.idetat as idEtat, '
                . 'fichefrais.datemodif as dateModif,'
                . 'fichefrais.nbjustificatifs as nbJustificatifs, '
                . 'fichefrais.montantvalide as montantValide, '
                . 'fichefrais.dejaimprimer as dejaimprimer, '
                . 'etat.libelle as libEtat '
                . 'FROM fichefrais '
                . 'INNER JOIN etat ON fichefrais.idetat = etat.id '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne;
    }

    /**
     * Modifie l'état et la date de modification d'une fiche de frais.
     * Modifie le champ idEtat et met la date de modif à aujourd'hui.
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $etat       Nouvel état de la fiche de frais
     *
     * @return null
     */
    public function majEtatFicheFrais($idVisiteur, $mois, $etat) {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'UPDATE fichefrais '
                . 'SET idetat = :unEtat, datemodif = now() '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unEtat', $etat, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
	
	 /**
     * Modifie le champ impression d'une fiche de frais.
     * Modifie le champ impression pour éviter plus d'une génération de pdf par fiche.
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $etatImpression  Nouvel état du champ impression
     *
     * @return null
     */
    public function majImpressionFicheFrais($idVisiteur, $mois, $etatImpression) {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'UPDATE fichefrais '
                . 'SET fichefrais.dejaimprimer = :unEtat '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unEtat', $etatImpression, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
	
	 /**
     * Retourne la liste des fiches de frais ayant pour état validé ou mis en paiement
     *
     * @param String $valide 	etat validé
     * @param String $misEnPaiement		etat mis en paiement
     *
     * @return un tableau avec l'ensemble des fiche de frais ayant pour été validé 
	 * 		   ou mis en paiement
     */
    public function getListeFicheFraisAValiderOuMiseEnPaiement($valide, $misEnPaiement) {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT fichefrais.idvisiteur as idVisiteur, '
                . 'fichefrais.mois as mois, '
                . 'fichefrais.idetat as idEtat, '
                . 'visiteur.nom as nomVisiteur, '
                . 'visiteur.prenom as prenomVisiteur, '
                . 'etat.libelle as libEtat '
                . 'FROM fichefrais '
                . 'INNER JOIN visiteur ON fichefrais.idvisiteur = visiteur.id '
                . 'INNER JOIN etat ON fichefrais.idetat = etat.id '
                . 'WHERE fichefrais.idetat = :valide '
                . 'OR fichefrais.idetat = :misEnPaiement '
                . 'ORDER BY fichefrais.idvisiteur, fichefrais.mois'
        );
        $requetePrepare->bindParam(':valide', $valide, PDO::PARAM_STR);
        $requetePrepare->bindParam(':misEnPaiement', $misEnPaiement, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesFichesFrais = array();
        $lesFichesFrais = $requetePrepare->fetchAll();
        return $lesFichesFrais;
    }
	
	/**
     * Calucle le montant validé d'une fiche de frais pour un visiteur à un mois précis
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return null
     */
    public function majMontantValideFicheFrais($idVisiteur, $mois) {
        $montantTotal = 0;
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT lignefraisforfait.quantite as quantitefrais, '
                . 'lignefraisforfait.idfraisforfait as id, '
                . 'fraisforfait.montant as montantfrais '
                . 'FROM lignefraisforfait '
                . 'INNER JOIN fraisforfait ON lignefraisforfait.idfraisforfait = fraisforfait.id '
                . 'WHERE lignefraisforfait.idvisiteur = :visiteur '
                . 'AND lignefraisforfait.mois = :date'
        );
        $requetePrepare->bindParam(':visiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':date', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesFraisForfait = array();
        $lesFraisForfait = $requetePrepare->fetchAll();


        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT fichefrais.vehiculeutilise as vehiculeutilise, '
                . 'vehicule.prix as coutpuissance '
                . 'FROM fichefrais '
                . 'INNER JOIN vehicule ON fichefrais.vehiculeutilise = vehicule.id '
                . 'WHERE fichefrais.idvisiteur = :visiteur '
                . 'AND fichefrais.mois = :date'
        );
        $requetePrepare->bindParam(':visiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':date', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $coutvehicule = $requetePrepare->fetch();
        foreach ($lesFraisForfait as $unFraisForfait) {
            $quantite = $unFraisForfait['quantitefrais'];
            if ($unFraisForfait['id'] == "KM") {
                $montant = $coutvehicule['coutpuissance'];
            } else {
                $montant = $unFraisForfait['montantfrais'];
            }
            $montantTotal = $montantTotal + ($montant * $quantite);
        };
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT lignefraishorsforfait.montant as montantfraishorsforfait '
                . 'FROM lignefraishorsforfait '
                . 'WHERE lignefraishorsforfait.idvisiteur = :visiteur '
                . 'AND lignefraishorsforfait.mois = :date '
                . 'AND lignefraishorsforfait.refuser = 0 '
                . 'OR lignefraishorsforfait.idvisiteur = :visiteur '
                . 'AND lignefraishorsforfait.mois = :date '
                . 'AND lignefraishorsforfait.refuser IS NULL'
        );
        $requetePrepare->bindParam(':visiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':date', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesFraisHorsForfait = array();
        $lesFraisHorsForfait = $requetePrepare->fetchAll();
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $montant = $unFraisHorsForfait['montantfraishorsforfait'];
            $montantTotal = $montantTotal + $montant;
        };
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'UPDATE fichefrais '
                . 'SET fichefrais.montantvalide = :montanttotalvalide, fichefrais.datemodif = now() '
                . 'WHERE fichefrais.idvisiteur = :visiteur '
                . 'AND fichefrais.mois = :date '
        );
        $requetePrepare->bindParam(':visiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':date', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':montanttotalvalide', $montantTotal, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
	
	 /**
     * Retourne la liste des identifiants de l'ensemble des visiteurs
     * 
     * @return un tableau avec l'ensemble des id des visiteurs
     */
    public function getListeIdVisiteur() {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'SELECT visiteur.id '
                . 'FROM visiteur'
        );
        $requetePrepare->execute();
        $lesIdVisiteur = array();
        $lesIdVisiteur = $requetePrepare->fetchAll();
        return $lesIdVisiteur;
    }
	
	 /**
     * Retourne la puissance d'un véhicule pour une fiche de frais en fonction
	 *			d'un id visiteur et d'un mois
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return la puissance du véhicule concerné
     */
    public function getPuissanceVehicule($idVisiteur, $mois) {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'SELECT fichefrais.vehiculeutilise '
                . 'FROM fichefrais '
                . 'WHERE fichefrais.idvisiteur = :visiteur '
                . 'AND fichefrais.mois = :date '
        );
        $requetePrepare->bindParam(':visiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':date', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $leVehicule = $requetePrepare->fetch();
        return $leVehicule;
    }
	
	 /**
     * Retourne la liste des puissances véhicules
     * 
     * @return un tableau avec l'ensemble des puissances véhicule
     */
    public function getPuissanceAllVehicule() {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'SELECT * '
                . 'FROM vehicule'
        );
        $requetePrepare->execute();
        $lstPuissanceVehicule = array();
        $lstPuissanceVehicule = $requetePrepare->fetchAll();
        return $lstPuissanceVehicule;
    }
	
	 /**
     * Retourne le type de véhicule utilisé par un visiteur pour une fiche de frais
	 *			en fonction d'un id visiteur et d'un mois
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return la puissance du véhicule concerné
     */
    public function getVoitureUtilise($idVisiteur, $mois) {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'SELECT fichefrais.vehiculeutilise AS vehicule, '
                . 'vehicule.prix as coutpuissance, '
                . 'vehicule.designation as designation '
                . 'FROM fichefrais '
                . 'INNER JOIN vehicule ON fichefrais.vehiculeutilise = vehicule.id '
                . 'WHERE fichefrais.idvisiteur = :visiteur '
                . 'AND fichefrais.mois = :date '
        );
        $requetePrepare->bindParam(':visiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':date', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $leVehicule = $requetePrepare->fetch();
        return $leVehicule;
    }
}
