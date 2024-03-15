<?php
//file: controller/PostController.php

require_once(__DIR__."/../model/Suscription.php");
require_once(__DIR__."/../model/SuscriptionMapper.php");
require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/SwitchMapper.php");

require_once(__DIR__."/../core/ViewManager.php");
require_once(__DIR__."/../controller/BaseController.php");

/**
* Class SuscriptionController
*
* Controller to make a CRUDL of Switchs entities
*/
class SuscriptionsController extends BaseController {

	/**
	* Reference to the SuscriptionMapper to interact
	* with the database
	*
	* @var SuscriptionMapper
	*/
	private $suscriptionMapper;
	private $switchMapper;

	public function __construct() {
		parent::__construct();

		$this->suscriptionMapper = new SuscriptionMapper();
		$this->switchMapper = new SwitchMapper();
	}

	/**
	* Action to list switches
	*
	* Loads all the switches from a user.
	*/
	public function index() {

		// obtain the data from the database
		$suscriptions = $this->suscriptionMapper->findAll($this->currentUser->getUsername());

		// put the array containing Post object to the view
		$this->view->setVariable("suscriptions", $suscriptions);

		// render the view (/view/suscrptions/index.php)
		$this->view->render("suscriptions", "index");
	}

	/**
	* Action to view a given post
	*
	* This action should only be called via GET
	*
	*/
	public function view(){
		if (!isset($_GET["id"])) {
			throw new Exception("id is mandatory");
		}

		$suscriptionId = $_GET["id"];

		// find the Switch object in the database
		$suscription = $this->suscriptionsMapper->findById($suscriptionId);

		if ($suscription == NULL) {
			throw new Exception("no such suscription with id: ".$suscriptionId);
		}

		// put the Suscription object to the view
		$this->view->setVariable("suscription", $suscription);

		// render the view (/view/posts/view.php)
		$this->view->render("suscriptions", "view");

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

		$suscription = new Suscription();

		if (isset($_POST["submit"])) { // reaching via HTTP Post...

			// populate the Switch object with data form the form
			$suscription->setUser($this->currentUser);
			$suscription->setSwitch($this->switchMapper->findById($_POST["id"]));

			try {
				// validate Switch object
				$suscription->checkIsValidForCreate(); // if it fails, ValidationException

				// save the Post object into the database
				$this->suscriptionMapper->save($suscription);

				// POST-REDIRECT-GET
				// Everything OK, we will redirect the user to the list of switchs
				// We want to see a message after redirection, so we establish
				// a "flash" message (which is simply a Session variable) to be
				// get in the view after redirection.
				$this->view->setFlash(sprintf(i18n("Suscription to switch \"%s\" successfully."), $suscription->getSwitch()->getName()));

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
		$this->view->setVariable("suscripcion", $suscription);

		// render the view (/view/switchs/add.php)
		//$this->view->render("suscripcion", "add");

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
			throw new Exception("Not in session. Deleting suscription requires login");
		}
		
		// Get the Post object from the database
		$suscriptionId = $_POST["id"];
		$suscription = $this->suscriptionMapper->findById($suscriptionId);

		// Does the post exist?
		if ($suscription == NULL) {
			throw new Exception("no such suscription with id: ".$suscriptionId);
		}

		// Check if the Switch author is the currentUser (in Session)
		if ($suscription->getUser() != $this->currentUser) {
			throw new Exception("Suscriptor is not the logged user");
		}

		// Delete the Post object from the database
		$this->suscriptionMapper->delete($suscription);

		// POST-REDIRECT-GET
		// Everything OK, we will redirect the user to the list of posts
		// We want to see a message after redirection, so we establish
		// a "flash" message (which is simply a Session variable) to be
		// get in the view after redirection.
		$this->view->setFlash(sprintf(i18n("Unsuscription to \"%s\" successfully."),$suscription->getSwitch()->getName()));

		// perform the redirection. More or less:
		// header("Location: index.php?controller=posts&action=index")
		// die();
		$this->view->redirect("suscriptions", "index");

	}

}
