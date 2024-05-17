<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");

require_once(__DIR__."/../model/SwitchDevice.php");
require_once(__DIR__."/../model/SwitchMapper.php");

require_once(__DIR__."/../model/Suscription.php");
require_once(__DIR__."/../model/SuscriptionMapper.php");

require_once(__DIR__."/BaseRest.php");

/**
* Class SwitchRest
*
* It contains operations for creating, retrieving, updating, deleting and
* listing posts, as well as to create comments to posts.
*
* Methods gives responses following Restful standards. Methods of this class
* are intended to be mapped as callbacks using the URIDispatcher class.
*
*/
class SuscriptionRest extends BaseRest {
	private $switchMapper;

	public function __construct() {
		parent::__construct();

		$this->switchMapper = new SwitchMapper();
        $this->suscriptionMapper = new SuscriptionMapper();
	}


    public function getSuscriptions() {
		$currentUser = parent::authenticateUser();
		$suscriptions = $this->suscriptionMapper->findAll($currentUser->getUsername());

		// json_encode Post objects.
		// since Post objects have private fields, the PHP json_encode will not
		// encode them, so we will create an intermediate array using getters and
		// encode it finally
		$suscriptions_array = array();
		foreach($suscriptions as $suscription) {
			array_push($suscriptions_array, array(
				"id" => $suscription->getId(),
				"switch_id" => $suscription->getSwitch()->getId(),
				"switch_description" => $suscription->getSwitch()->getDescription(),
				"switch_name" => $suscription->getSwitch()->getName(),
				"switch_owner" => $suscription->getSwitch()->getOwner()->getUsername(),
				"switch_auto_off_time" => $suscription->getSwitch()->getAutoOffTime(),
				"switch_last_time" => $suscription->getSwitch()->getLastTime(),
				"switch_status" => $suscription->getSwitch()->getStatus(),
				"username" => $suscription->getUser()->getUsername(),
			));
		}

		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($suscriptions_array));
	}


	public function createSuscription($data) {
		$currentUser = parent::authenticateUser();
        $suscription = new Suscription();

		if (isset($data->id)) {
			$switch = $this->switchMapper->findById($data->id);

            $suscription->setUser($currentUser);
            $suscription->setSwitch($switch);
		}

		try {
			// validate Switch object
			$suscription->checkIsValidForCreate(); // if it fails, ValidationException

			// save the Switch object into the database
			$suscriptionId = $this->suscriptionMapper->save($suscription);

			// response OK. Also send post in content
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			header('Location: '.$_SERVER['REQUEST_URI']."/".$suscriptionId);
			header('Content-Type: application/json');

			echo(json_encode(array(
				"id" => $suscription->getId(),
				"switch_id" => $suscription->getSwitch()->getId(),
				"switch_name" => $suscription->getSwitch()->getName(),
				"owner" => $suscription->getUser()->getUsername()
			)));

		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

    //Lee una suscripcion a partir de un switch y un usuario
    public function readSuscription($switchId) {
		$currentUser = parent::authenticateUser();
		//Conseguimos la suscripcion a partir de un switch y un usuario
		$suscription = $this->suscriptionMapper->isSuscribed($currentUser->getUsername(), $switchId);
		if ($suscription == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Suscription not found");
			return;
		}

		$suscription_array = array(
			"id" => $suscription->getId(),
			"switch_id" => $suscription->getSwitch()->getPublicId(),
			"username" => $suscription->getUser()->getUsername()
		);

		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($suscription_array));
	}

	
    public function deleteSuscription($suscriptionId) {
		$currentUser = parent::authenticateUser();

		// find the Switch object in the database
		$suscription = $this->suscriptionMapper->findById($suscriptionId);
		if ($suscription == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Switch with id ".$suscriptionId." not found");
			return;
		}

		// Check if the user suscription or Switch owner is the currentUser
		if ($suscription->getSwitch()->getOwner() != $currentUser && $suscription->getUser() != $currentUser) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you cant delete this suscription");
			return;
		}

		$this->suscriptionMapper->delete($suscription);
		header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
	}

}

// URI-MAPPING for this Rest endpoint
$suscriptionRest = new SuscriptionRest();
URIDispatcher::getInstance()
->map("GET",	"/suscription", array($suscriptionRest,"getSuscriptions"))
->map("GET",	"/suscription/$1", array($suscriptionRest,"readSuscription"))
->map("POST",	"/suscription", array($suscriptionRest,"createSuscription"))
->map("PUT",	"/suscription/$1", array($suscriptionRest,"updateSuscription"))
->map("DELETE",	"/suscription/$1", array($suscriptionRest,"deleteSuscription"));
