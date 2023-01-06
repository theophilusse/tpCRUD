<?php
require_once "Constantes.php";
require_once "metier/Livre.php";
require_once "MediathequeDB.php";

class LivreDB extends MediathequeDB
{
	private $db; // Instance de PDO
	public $lastId;
	//TODO implementer les fonctions
	public function __construct($db)
	{
		$this->db = $db;
	}

	/**
	 * 
	 * fonction d'Insertion de l'objet Livre en base de donnee
	 * @param Livre $l
	 */
	public function ajout(Livre $l)
	{
		$q = $this->db->prepare('INSERT INTO livre(titre,edition,information, auteur) VALUES(:titre,:edition,:information,:auteur)');

		$q->bindValue(':titre', $l->getTitre());
		$q->bindValue(':edition', $l->getEdition());
		$q->bindValue(':information', $l->getInformation());
		$q->bindValue(':auteur', $l->getAuteur());
		$q->execute();
		$this->last_id = $this->db->lastInsertId();
		$q->closeCursor();
		return ($this->last_id);
	}

	/**
	 * 
	 * fonction d'update de l'objet Livre en base de donnee
	 * @param Livre $l
	 */
	public function update(Livre $l)
	{
		$q = $this->db->prepare('UPDATE livre SET titre=:titre,edition=:edition,information=:information,auteur=:auteur WHERE id=:id');
	
		//$this->last_id = $this->db->lastInsertId();
		$q->bindValue(':titre', $l->getTitre());
		$q->bindValue(':edition', $l->getEdition());
		$q->bindValue(':information', $l->getInformation());
		$q->bindValue(':auteur', $l->getAuteur());
		$l->setId();
		$q->bindValue(':id', $l->getId());
		$q->execute();
		$q->closeCursor();
	}
	/**
	 * 
	 * fonction de Suppression de l'objet Livre
	 * @param Livre $l
	 */
	public function suppression($id)
	{
		$q = $this->db->prepare('DELETE FROM livre WHERE id=:id');
		$q->bindValue(':id', $id);
		$q->execute();
		$q->closeCursor();
	}
	/**
	 * 
	 * Fonction qui retourne toutes les livres
	 * @throws Exception
	 */
	public function selectAll()
	{
		$query = 'SELECT id,titre,edition,information,auteur FROM livre';
		$q = $this->db->prepare($query);
		$q->execute();

		$arrAll = $q->fetchAll(PDO::FETCH_ASSOC);

		//si pas de personnes , on leve une exception
		if (empty($arrAll)) {
			throw new Exception(Constantes::EXCEPTION_DB_LIVRE);
		}

		//Clore la requete préparée
		$q->closeCursor();
		$q = NULL;
		//retour du resultat
		return $arrAll;
	}

	// public function selectionTitre($titre){
	// 	$query = 'SELECT titre, `edition`, information, auteur FROM livre  WHERE titre like :titre ';
	// 	$q = $this->db->prepare($query);

	// $q->bindValue(':titre',$titre);
	// 		$q->execute();
	// 	$arrAll = $q->fetch(PDO::FETCH_ASSOC);
	// 	//si pas de personne , on leve une exception


	// 	if(empty($arrAll)){
	// 		throw new Exception(Constantes::EXCEPTION_DB_LIVRE); 

	// 	}

	// 	$q->closeCursor();
	// 	$q = NULL;
	// 	//conversion du resultat de la requete en objet personne
	//  	$res= $this->convertPdoLiv($arrAll);
	// 	//retour du resultat
	// 	return $res;
	// }

	public function selectLivre($id)
	{
		$query = 'SELECT id,titre,edition,information,auteur FROM livre  WHERE id= :id ';
		$q = $this->db->prepare($query);
		$q->bindValue(':id', $id);
		$q->execute();
		$arrAll = $q->fetch(PDO::FETCH_ASSOC);
		//si pas de personne , on leve une exception
		if (empty($arrAll)) {
			throw new Exception(Constantes::EXCEPTION_DB_LIVRE);
		}
		$q->closeCursor();
		$q = NULL;
		//conversion du resultat de la requete en objet personne
		$res = $this->convertPdoLiv($arrAll);
		//retour du resultat
		return ($res);
	}
	/**
	 * 
	 * Fonction qui convertie un PDO Livre en objet Livre
	 * @param $pdoLivr
	 * @throws Exception
	 */
	public function convertPdoLiv($pdoLivr)
	{
		if(empty($pdoLivr)){
			throw new Exception(Constantes::EXCEPTION_DB_CONVERT_LIVR);
		}
		//conversion du pdo en objet
		try {
		$obj=(object)$pdoLivr;
		$i = (int)$obj->id;
		$t = $obj->titre;
		$c = $obj->edition;
		$e = $obj->information;
		$a = $obj->auteur;
		} catch (Exception $e) {
			throw new Exception(Constantes::EXCEPTION_DB_CONVERT_LIVR);
		}
		//conversion de l'objet en objet adresse
		$liv = new Livre($t,$c,$e,$a);
		$liv->setId($i);
		return $liv;
	}
}
