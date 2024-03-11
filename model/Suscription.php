<?php
// file: model/Suscription.php

require_once(__DIR__."/../core/ValidationException.php");

class Suscription {

    private $id;
	private $user;
    private $switch;

	public function __construct($id=NULL, User $user=NULL, SwitchDevice $switch=NULL) {
		$this->id = $id;
        $this->user = $user;
		$this->switch = $switch;
	}

	//Ids
    public function getId() {
		return $this->id;
	}

	//User
    public function getUser() {
		return $this->user;
	}

	public function setUser(User $user) {
		$this->user = $user;
	}

    //Switch
	public function getSwitch() {
		return $this->switch;
	}

	public function setSwitch(SwitchDevice $switch) {
		$this->switch = $switch;
	}


	
    //Validation
	public function checkIsValidForCreate() {
		$errors = array();
		if (strlen(trim($this->getUser()->getUsername())) == 0 ) {
			$errors["user"] = "user is mandatory";
		}
		if (strlen(trim($this->getSwitch()->getId())) == 0 ) {
			$errors["switch"] = "switch is mandatory";
		}

		if (sizeof($errors) > 0){
			throw new ValidationException($errors, "suscription is not valid");
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
			throw new ValidationException($errors, "suscription is not valid");
		}
	}
}
