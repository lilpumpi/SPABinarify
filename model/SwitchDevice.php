<?php
// file: model/Switch.php

require_once(__DIR__."/../core/ValidationException.php");

class SwitchDevice {

    private $id;
	private $public_id;
    private $private_id;
    private $name;
    private $description;
    private $owner;
    private $auto_off_time;
    private $last_time;

	public function __construct($id=NULL, $public_id=NULL, $private_id=NULL, $name=NULL, $description=NULL, User $owner=NULL, $auto_off_time=NULL, $last_time=NULL) {
		$this->id = $id;
        $this->public_id = $public_id;
		$this->private_id = $private_id;
		$this->name = $name;
		$this->description = $description;
		$this->owner = $owner;
        $this->auto_off_time = $auto_off_time;
        $this->last_time = $last_time;
	}

	//Ids
    public function getId() {
		return $this->id;
	}

	public function getPublicId() {
		return $this->public_id;
	}

	public function setPublicId($public_id) {
		$this->public_id = $public_id;
	}

    public function getPrivateId() {
		return $this->private_id;
	}

	public function setPrivateId($private_id) {
		return $this->private_id = $private_id;
	}



    //Name
	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}


    //Description
	public function getDescription() {
		return $this->description;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	
    //Owner
	public function getOwner() {
		return $this->owner;
	}

	public function setOwner(User $owner) {
		$this->owner = $owner;
	}

	
    //Auto Off Time
	public function getAutoOffTime() {
		return $this->auto_off_time;
	}

	public function setAutoOffTime($auto_off_time) {
		$this->auto_off_time = $auto_off_time;
	}


    //Last Time
	public function getLastTime() {
		return $this->last_time;
	}

	public function setLastTime($last_time) {
		$this->last_time = $last_time;
	}


	//Status
	public function getStatus(){
        // Obtener la fecha y hora actual
        $currentTime = new DateTime();

        // Calcular la fecha y hora en la que se debería apagar el switch
        $autoOffTime = $this->auto_off_time; // Duración en minutos para que se apague
        $lastTime = new DateTime($this->last_time); // Fecha y hora en la que se encendió el switch
        $autoOffTime = new DateInterval("PT{$autoOffTime}M");
        $autoOffTime = $lastTime->add($autoOffTime);

        // Comparar la fecha y hora actual con la fecha y hora en la que se debería apagar el switch
        if ($currentTime > $autoOffTime) {
            return false; // El switch está apagado
        } else {
            return true; // El switch está encendido
        }
    }


	
    //Validation
	public function checkIsValidForCreate() {
		$errors = array();
		if (strlen(trim($this->name)) == 0 ) {
			$errors["name"] = "title is mandatory";
		}
		if (strlen(trim($this->description)) == 0 ) {
			$errors["description"] = "description is mandatory";
		}
		if ($this->owner == NULL ) {
			$errors["owner"] = "owner is mandatory";
		}

		if (sizeof($errors) > 0){
			throw new ValidationException($errors, "switch is not valid");
		}
	}


	public function checkIsValidForUpdate() {
		$errors = array();

		if (!isset($this->id)) {
			$errors["id"] = "id is mandatory";
		}

		try{
			$this->checkIsValidForCreate();
		}catch(ValidationException $ex) {
			foreach ($ex->getErrors() as $key=>$error) {
				$errors[$key] = $error;
			}
		}
		if (sizeof($errors) > 0) {
			throw new ValidationException($errors, "post is not valid");
		}
	}
}
