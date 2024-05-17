<?php
// file: model/SwitchMapper.php
require_once(__DIR__."/../core/PDOConnection.php");

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/SwitchDevice.php");


class SwitchMapper {

	/**
	* Reference to the PDO connection
	* @var PDO
	*/
	private $db;

	public function __construct() {
		$this->db = PDOConnection::getInstance();
	}

	/**
	* Retrieves all switches from a user
	* @throws PDOException if a database error occurs
	*/
    public function findAll($userId) {
		$stmt = $this->db->prepare("SELECT * FROM switches WHERE owner = ?");
		$stmt->execute(array($userId));
		$switches_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$switches = array();

		foreach ($switches_db as $switch) {
			$owner = new User($switch["owner"]);
			array_push($switches, new SwitchDevice($switch["id"], $switch["public_id"], $switch["private_id"], $switch["nombre"], $switch["descripcion"], $owner, $switch["auto_off_time"], $switch["last_time"]));
		}

		return $switches;
	}

	/**
	* Loads a Post from the database given its id
	*
	* Note: Comments are not added to the Post
	*
	* @throws PDOException if a database error occurs
	* @return Post The Post instances (without comments). NULL
	* if the Post is not found
	*/
	public function findById($switchId){
		$stmt = $this->db->prepare("SELECT * FROM switches WHERE id=?");
		$stmt->execute(array($switchId));
		$switch = $stmt->fetch(PDO::FETCH_ASSOC);

		if($switch != null) {
			return new SwitchDevice(
			$switch["id"],
            $switch["public_id"],
            $switch["private_id"],
            $switch["nombre"],
            $switch["descripcion"],
			new User($switch["owner"]),
            $switch["auto_off_time"],
            $switch["last_time"]);
		} else {
			return NULL;
		}
	}

	//Devuelve el id a partir de la UUID publica
	public function findByPublicId($publicId){
		$stmt = $this->db->prepare("SELECT * FROM switches WHERE public_id=?");
		$stmt->execute(array($publicId));
		$switch = $stmt->fetch(PDO::FETCH_ASSOC);

		if($switch != null) {
			return new SwitchDevice(
			$switch["id"],
            $switch["public_id"],
            $switch["private_id"],
            $switch["nombre"],
            $switch["descripcion"],
			new User($switch["owner"]),
            $switch["auto_off_time"],
            $switch["last_time"]);
		} else {
			return NULL;
		}
	}

	//Devuelve el id a partir de la UUID privada
	public function findByPrivateId($privateId){
		$stmt = $this->db->prepare("SELECT * FROM switches WHERE private_id=?");
		$stmt->execute(array($privateId));
		$switch = $stmt->fetch(PDO::FETCH_ASSOC);

		if($switch != null) {
			return new SwitchDevice(
			$switch["id"],
            $switch["public_id"],
            $switch["private_id"],
            $switch["nombre"],
            $switch["descripcion"],
			new User($switch["owner"]),
            $switch["auto_off_time"],
            $switch["last_time"]);
		} else {
			return NULL;
		}
	}



    /**
    * Saves a Switch into the database
    */
    public function save(SwitchDevice $switch) {
        $stmt = $this->db->prepare("INSERT INTO switches(public_id, private_id, nombre, descripcion, owner, auto_off_time) values (?,?,?,?,?,?)");
        $stmt->execute(array($switch->getPublicId(), $switch->getPrivateId(), $switch->getName(), $switch->getDescription(), $switch->getOwner()->getUsername(), $switch->getAutoOffTime()));
        return $this->db->lastInsertId();
    }

	//Actualiza un Switch, solo sirve para cambiar el estado
	public function update(SwitchDevice $switch) {
		$stmt = $this->db->prepare("UPDATE switches SET auto_off_time = ?, last_time = ? WHERE id = ?");
		$stmt->execute(array($switch->getAutoOffTime(), $switch->getLastTime(), $switch->getId()));
		return $stmt->rowCount(); // Devuelve el número de filas afectadas por la actualización
	}

    /**
    * Deletes a Switch into the database
    */
    public function delete(SwitchDevice $switch) {

		//First delete suscriptions
		$stmt = $this->db->prepare("DELETE from suscripciones WHERE switch_id=?");
        $stmt->execute(array($switch->getId()));

		//Delete switch
        $stmt = $this->db->prepare("DELETE from switches WHERE id=?");
        $stmt->execute(array($switch->getId()));

    }

}
