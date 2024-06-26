<?php
// file: model/SwitchMapper.php
require_once(__DIR__."/../core/PDOConnection.php");

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/Suscription.php");
require_once(__DIR__."/../model/SwitchMapper.php");
require_once(__DIR__."/../model/UserMapper.php");


class SuscriptionMapper {

	/**
	* Reference to the PDO connection
	* @var PDO
	*/
	private $db;
	private $switchMapper;

	public function __construct() {
		$this->db = PDOConnection::getInstance();
		$this->switchMapper = new SwitchMapper();
	}

	/**
	* Retrieves all suscriptions from a user
	* @throws PDOException if a database error occurs
	*/
    public function findAll($userId) {
		$stmt = $this->db->prepare("SELECT * FROM suscripciones WHERE username = ?");
		$stmt->execute(array($userId));
		$suscriptions_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$suscriptions = array();

		foreach ($suscriptions_db as $suscription) {
			$switch = $this->switchMapper->findById($suscription["switch_id"]);
			$user = new User($suscription["username"]);
			array_push($suscriptions, new Suscription($suscription["id"], $user, $switch));
		}

		return $suscriptions;
	}

	/**
	* Loads a Suscription from the database given its id
	*
	* Note: Comments are not added to the Post
	*
	* @throws PDOException if a database error occurs
	* @return Post The Post instances (without comments). NULL
	* if the Post is not found
	*/
	public function findById($suscriptionId){
		$stmt = $this->db->prepare("SELECT * FROM suscripciones WHERE id=?");
		$stmt->execute(array($suscriptionId));
		$suscription = $stmt->fetch(PDO::FETCH_ASSOC);

		if($suscription != null) {
			$switch = $this->switchMapper->findById($suscription["switch_id"]);
			return new Suscription($suscription["id"],new User($suscription["username"]), $switch);
		} else {
			return NULL;
		}
	}


	//Devuelve las suscripciones de un switch
	public function findSwitchSuscription($switchId) {
		$stmt = $this->db->prepare("SELECT * FROM suscripciones WHERE switch_id = ?");
		$stmt->execute(array($switchId));
		$suscriptions_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if($suscriptions_db != NULL){
			$suscriptions = array();

			foreach ($suscriptions_db as $suscription) {
				$switch = $this->switchMapper->findById($suscription["switch_id"]);
				$user = new User($suscription["username"]);
				array_push($suscriptions, new Suscription($suscription["id"], $user, $switch));
			}

			return $suscriptions;
		} else{
			return NULL;
		}
	}


    //Ensure that username is suscribed to switch -> Return the suscription
    public function isSuscribed($username, $switch_id){
		$stmt = $this->db->prepare("SELECT * FROM suscripciones WHERE username=? AND switch_id=?");
		$stmt->execute(array($username, $switch_id));
		$suscription = $stmt->fetch(PDO::FETCH_ASSOC);

		if($suscription != null) {
			$switch = $this->switchMapper->findById($suscription["switch_id"]);
			return new Suscription($suscription["id"], new User($suscription["username"]), $switch);
		} else {
			return NULL;
		}
	}


    /**
    * Saves a Switch into the database
    */
    public function save(Suscription $suscription) {
        $stmt = $this->db->prepare("INSERT INTO suscripciones(id, username, switch_id) values (?,?,?)");
        $stmt->execute(array($suscription->getId(), $suscription->getUser()->getUsername(), $suscription->getSwitch()->getId()));
        return $this->db->lastInsertId();
    }

    /**
    * Deletes a Switch into the database
    */
    public function delete(Suscription $suscription) {
        $stmt = $this->db->prepare("DELETE from suscripciones WHERE id=?");
        $stmt->execute(array($suscription->getId()));
    }

}
