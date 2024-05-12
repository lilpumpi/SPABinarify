<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");

require_once(__DIR__."/../model/SwitchDevice.php");
require_once(__DIR__."/../model/SwitchMapper.php");

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
class SwitchRest extends BaseRest {
	private $switchMapper;

	public function __construct() {
		parent::__construct();

		$this->switchMapper = new SwitchMapper();
	}



	public function getSwitches() {
		$currentUser = parent::authenticateUser();
		$switches = $this->switchMapper->findAll($currentUser->getUsername());

		// json_encode Post objects.
		// since Post objects have private fields, the PHP json_encode will not
		// encode them, so we will create an intermediate array using getters and
		// encode it finally
		$switches_array = array();
		foreach($switches as $switch) {
			array_push($switches_array, array(
				"id" => $switch->getId(),
				"public_id" => $switch->getPublicId(),
				"private_id" => $switch->getPrivateId(),
				"nombre" => $switch->getName(),
				"descripcion" => $switch->getDescription(),
				"owner" => $switch->getOwner()->getUsername(),
				"auto_off_time" => $switch->getAutoOffTime(),
				"last_time" => $switch->getLastTime(),
				"status" => $switch->getStatus()
			));
		}

		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($switches_array));
	}



	public function createSwitch($data) {
		$currentUser = parent::authenticateUser();
		$switch = new SwitchDevice();

		if (isset($data->private_id) && isset($data->public_id) && isset($data->name) && isset($data->description) && isset($data->auto_off_time)) {
			$switch->setPrivateId($data->private_id);
			$switch->setPublicId($data->public_id);
			$switch->setName($data->name);
			$switch->setDescription($data->description);
			$switch->setAutoOffTime($data->auto_off_time);
			$switch->setOwner($currentUser);
		}

		try {
			// validate Switch object
			$switch->checkIsValidForCreate(); // if it fails, ValidationException

			// save the Switch object into the database
			$switchId = $this->switchMapper->save($switch);

			// response OK. Also send post in content
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			header('Location: '.$_SERVER['REQUEST_URI']."/".$switchId);
			header('Content-Type: application/json');

			echo(json_encode(array(
				"id" => $switch->getId(),
				"public_id" => $switch->getPublicId(),
				"private_id" => $switch->getPrivateId(),
				"nombre" => $switch->getName(),
				"descripcion" => $switch->getDescription(),
				"owner" => $switch->getOwner()->getUsername(),
				"auto_off_time" => $switch->getAutoOffTime(),
				"last_time" => $switch->getLastTime(),
				"status" => $switch->getStatus()
			)));

		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}



	public function readSwitch($switchId) {
		// find the Switch object in the database
		$switch = $this->switchMapper->findById($switchId);
		if ($switch == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Switch with id ".$switchId." not found");
			return;
		}

		$switch_array = array(
			"id" => $switch->getId(),
			"public_id" => $switch->getPublicId(),
			"private_id" => $switch->getPrivateId(),
			"nombre" => $switch->getName(),
			"descripcion" => $switch->getDescription(),
			"owner" => $switch->getOwner()->getUsername(),
			"auto_off_time" => $switch->getAutoOffTime(),
			"last_time" => $switch->getLastTime(),
			"status" => $switch->getStatus() 
		);

		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($switch_array));
	}



	public function updateSwitch($switchId, $data) {
		$currentUser = parent::authenticateUser();

		// find the Switch object in the database
		$switch = $this->switchMapper->findById($switchId);
		if ($switch == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Switch with id ".$switchId." not found");
			return;
		}

		// Check if the Switch owner is the currentUser (in Session)
		if ($switch->getOwner() != $currentUser) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not the owner of this switch");
			return;
		}
		
		$switch->setAutoOffTime($data->auto_off_time);
		$switch->setLastTime($data->last_time);

		try {
			// validate Swtich object
			$switch->checkIsValidForUpdate(); // if it fails, ValidationException
			$this->switchMapper->update($switch);
			echo("Switch " .$switchId. " updated succefully");
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		}catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}



	public function deleteSwitch($switchId) {
		$currentUser = parent::authenticateUser();

		// find the Switch object in the database
		$switch = $this->switchMapper->findById($switchId);
		if ($switch == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Switch with id ".$switchId." not found");
			return;
		}

		// Check if the Switch owner is the currentUser (in Session)
		if ($switch->getOwner() != $currentUser) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not the owner of this switch");
			return;
		}

		$this->switchMapper->delete($switch);
		header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
	}


}

// URI-MAPPING for this Rest endpoint
$switchRest = new SwitchRest();
URIDispatcher::getInstance()
->map("GET",	"/switch", array($switchRest,"getSwitches"))
->map("GET",	"/switch/$1", array($switchRest,"readSwitch"))
->map("POST",	"/switch", array($switchRest,"createSwitch"))
->map("PUT",	"/switch/$1", array($switchRest,"updateSwitch"))
->map("DELETE",	"/switch/$1", array($switchRest,"deleteSwitch"));
