<?php
//file: controller/PostController.php

require_once(__DIR__."/../model/SwitchDevice.php");
require_once(__DIR__."/../model/SwitchMapper.php");
require_once(__DIR__."/../model/SuscriptionMapper.php");
require_once(__DIR__."/../model/User.php");

require_once(__DIR__."/../core/ViewManager.php");
require_once(__DIR__."/../controller/BaseController.php");

/**
* Class SwitchsController
*
* Controller to make a CRUDL of Switchs entities
*/
class SwitchsController extends BaseController {

	/**
	* Reference to the SwitchMapper to interact
	* with the database
	*
	* @var SwitchMapper
	*/
	private $switchMapper;
	private $suscriptionMapper;

	public function __construct() {
		parent::__construct();

		$this->switchMapper = new SwitchMapper();
		$this->suscriptionMapper = new SuscriptionMapper();
	}




	public function dashboard() {

		// obtain the data from the database
		$switches = $this->switchMapper->findAll($this->currentUser->getUsername());

		// put the array containing Post object to the view
		$this->view->setVariable("switchs", $switches);

		// render the view (/view/switches/dashboard.php)
		$this->view->render("switchs", "dashboard");
	}

	/**
	* Action to list switches
	*
	* Loads all the switches from a user.
	*/
	public function index() {

		// obtain the data from the database
		$switches = $this->switchMapper->findAll($this->currentUser->getUsername());

		// put the array containing Post object to the view
		$this->view->setVariable("switchs", $switches);

		// render the view (/view/switches/index.php)
		$this->view->render("switchs", "index");
	}

	/**
	* Action to view a given post
	*
	* This action should only be called via GET
	*
	*/
	public function view(){

		if (isset($_GET["public_id"])) {
			$switchId = $this->switchMapper->findByPublicId($_GET["public_id"]);

		} else if (isset($_GET["private_id"])) {
			$switchId = $this->switchMapper->findByPrivateId($_GET["private_id"]);
		} else{
			throw new Exception("id is mandatory");
		}

		// find the Switch object in the database
		$switch = $this->switchMapper->findById($switchId);

		if ($switch == NULL) {
			throw new Exception("no such switch with id: ".$switchId);
		}

		//Exist suscription?
		$suscription = $this->suscriptionMapper->isSuscribed($this->currentUser->getUsername(), $switchId);
		$this->view->setVariable("suscription", $suscription);

		// put the Switch object to the view
		$this->view->setVariable("switch", $switch);

		// render the view (/view/posts/view.php)
		$this->view->render("switchs", "view");

	}

	/**
	* Action to add a new switch
	*
	* When called via GET, it shows the add form
	* When called via POST, it adds the post to the
	* database
	*
	*/
	public function add() {
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Creating switch requires login");
		}

		$switch = new SwitchDevice();

		if (isset($_POST["submit"])) { // reaching via HTTP Post...

			// populate the Switch object with data form the form
			$switch->setName($_POST["name"]);
			$switch->setDescription($_POST["description"]);
			$switch->setAutoOffTime($_POST["auto_off_time"]);

			// The user of the Switch is the currentUser (user in session)
			$switch->setOwner($this->currentUser);

			try {
				// validate Switch object
				$switch->checkIsValidForCreate(); // if it fails, ValidationException
				//Generamos los las URIS (ids)
				$switch->setPublicId($this->generateUUID());
				$switch->setPrivateId($this->generateUUID());

				// save the Post object into the database
				$this->switchMapper->save($switch);

				// POST-REDIRECT-GET
				// Everything OK, we will redirect the user to the list of switchs
				// We want to see a message after redirection, so we establish
				// a "flash" message (which is simply a Session variable) to be
				// get in the view after redirection.
				$this->view->setFlash(sprintf(i18n("Switch \"%s\" successfully added."),$switch ->getName()));

				// perform the redirection. More or less:
				// header("Location: index.php?controller=posts&action=index")
				// die();
				$this->view->redirect("switchs", "dashboard");

			}catch(ValidationException $ex) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors();
				// And put it to the view as "errors" variable
				$this->view->setVariable("errors", $errors);
			}
		}

		// Put the Switch object visible to the view
		$this->view->setVariable("switch", $switch);

		// render the view (/view/switchs/add.php)
		$this->view->render("switchs", "add");

	}


	/**
	* Action to delete a post
	*
	* This action should only be called via HTTP POST
	*
	* 
	*/
	public function delete() {
		if (!isset($_POST["id"])) {
			throw new Exception("id is mandatory");
		}
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Deleting switchs requires login");
		}
		
		// Get the Post object from the database
		$switchId = $_POST["id"];
		$switch = $this->switchMapper->findById($switchId);

		// Does the post exist?
		if ($switch == NULL) {
			throw new Exception("no such switch with id: ".$switchId);
		}

		// Check if the Switch author is the currentUser (in Session)
		if ($switch->getOwner() != $this->currentUser) {
			throw new Exception("Swith author is not the logged user");
		}

		// Delete the Post object from the database
		$this->switchMapper->delete($switch);

		// POST-REDIRECT-GET
		// Everything OK, we will redirect the user to the list of posts
		// We want to see a message after redirection, so we establish
		// a "flash" message (which is simply a Session variable) to be
		// get in the view after redirection.
		$this->view->setFlash(sprintf(i18n("Switch \"%s\" successfully deleted."),$switch->getName()));

		// perform the redirection. More or less:
		// header("Location: index.php?controller=posts&action=index")
		// die();
		$this->view->redirect("switchs", "index");

	}




	//Funcion para encender o apagar un switch
	public function changeStatus() {
		
		// Verificar si se proporcionó un ID de interruptor
		if (!isset($_POST['id'])) {
			throw new Exception("id is mandatory");
		}

		// Obtener el ID del interruptor y el estado del formulario
		$switchId = $_POST["id"];
		$page = $_REQUEST['redirect']; //Pagina de la que se llamo la funcion para poder volver a la misma
		$switchState = isset($_REQUEST['status']) && $_REQUEST['status'] === 'true' ? true : false;

		// Obtener el interruptor de la base de datos
		$switch = $this->switchMapper->findById($switchId);

		// Does the post exist?
		if ($switch == NULL) {
			throw new Exception("no such switch with id: ".$switchId);
		}

		// Actualizar el estado del interruptor en la base de datos según el estado del formulario
		if ($switchState) {
			//Se enciende el switch
			$switch->setAutoOffTime(2);
			$switch->setLastTime(date('Y-m-d H:i:s'));

		} else {
			//Se apaga el switch
			$switch->setAutoOffTime(0);
		}

		// Guardar los cambios en la base de datos
		$this->switchMapper->update($switch);

		//Redirigir al usuario de vuelta al mismo lugar
		$this->view->redirect("switchs", $page);
		
		
	}
	


	//Funcion para generar las URIs
	function generateUUID() {
		// Genera un UUID versión 4 (aleatorio)
		$data = openssl_random_pseudo_bytes(16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Versión 4
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Variant
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}

}
