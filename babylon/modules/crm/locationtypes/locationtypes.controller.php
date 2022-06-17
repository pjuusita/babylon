<?php

// Siirretty clienttitles module tanne uudelle nimella


class LocationtypesController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css', 'main.css','mytheme/jquery-ui.css','yritys.css','petestyle.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
	
	public function indexAction() {
		//$this->showlocationtypesAction();
		$this->registry->template->show('system/error','unknown');
	}


	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	

	public function showlocationtypesAction () {

		$this->registry->locationtypes = Table::load('crm_locationtypes');
		$this->registry->template->show('crm/locationtypes','locationtypes');
	}

	
	
	

	public function insertlocationtypeAction() {
		$comments = false;
		$values = array();
		$values['Name'] = $_GET['name'];
		$rowID = Table::addRow("crm_locationtypes", $values, $comments);
		if ($comments == false) redirecttotal('crm/locationtypes/showlocationtypes',null);
	}

	
}

?>
