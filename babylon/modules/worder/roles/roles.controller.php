<?php


class RolesController extends AbstractController {

	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->showrolesAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	

	public function showrolesAction() {

		$this->registry->roles = Table::load("worder_roles", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->template->show('worder/roles','roles');
	}
	
	
	

	public function shownewroleAction() {
	
		$this->registry->template->show('worder/roles','newrole');
	}
	
	
	
	public function showwordclassAction() {
		
	
		// ...
	}
	
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	

	public function insertroleAction() {
	
		$nimi=$_GET['Name'];
		$description=$_GET['Description'];
		$success=true;
	
		$values = array();
		$values['Description'] = $_GET['description'];
		$values['Name'] = $_GET['name'];
		$values['GrammarID'] = $_GET['grammarID'];
		
		if ($nimi == '') $success=false;
		if ($description == '') $success=false;
	
		if ($success == false) {
			addErrorMessage("Kentät ei saa olla tyhjiä");
			$this->registry->nimi=$nimi;
			$this->registry->description=$description;
			$this->registry->template->show('worder/roles','showrole');
		} else {
				
			$success = Table::addRow('worder_roles',$values);	
			
			if ($success == true) {
				echo "[ {\"success\":\"true\", \"message\":\"Lisätty onnistuneesti\" }]";
			} else {
				$paramstr = "---";
				foreach ($_GET as $index => $value) {
					$paramstr = $paramstr . "," . $index . ":" . $value;
				}
				echo "[{\"success\":\"" . $success . $paramstr . "\"}]";
			}
			
		}
	}
}
