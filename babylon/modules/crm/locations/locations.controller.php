<?php


class LocationsController extends AbstractController {

	
	public function getCSSFiles() {
		//return array('menu.css','testcss.php');
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}

	
	public function indexAction() {
		//$this->showlocationsAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showlocationsAction() {
		
		$this->registry->locations = Table::load('crm_locations');
		$this->registry->companies = Table::load('crm_companies');
		$this->registry->locationtypes = Table::load('crm_locationtypes');
		$this->registry->template->show('crm/locations','locations');
	}
	
	
	public function showlocationAction() {
		
		$locationID = $_GET['id'];
		$this->registry->location = Table::loadRow('crm_locations',$locationID);
		$this->registry->companies = Table::load('crm_companies');
		$this->registry->locationtypes = Table::load('crm_locationtypes');
		
		$this->registry->template->show('crm/locations','location');
	}
	
	
	
	public function updatelocationAction() {
		
		$locationID = $_GET['id'];
		
		$name = $_GET['name'];
		$streetaddress = $_GET['Streetaddress'];
		$postalcode = $_GET['Postalcode'];
		$city = $_GET['City'];
		$locationtypeID = $_GET['locationtypeID'];
		$companyID = $_GET['companyID'];
		
		$values = array();
		$values['Name'] = $name;
		$values['Streetaddress'] = $streetaddress;
		$values['Postalcode'] = $postalcode;
		$values['City'] = $city;
		$values['LocationtypeID'] = $locationtypeID;
		$values['CompanyID'] = $companyID;
		
		$success = Table::updateRow('crm_locations', $values, $locationID, true);
		redirecttotal('crm/locations/showlocation&id=' . $locationID, null);
	}
	
	
	
	public function insertlocationAction() {

		$name = $_GET['name'];
		$streetaddress = $_GET['Streetaddress'];
		$postalcode = $_GET['Postalcode'];
		$city = $_GET['City'];
		$locationtypeID = $_GET['locationtypeID'];
		$companyID = $_GET['companyID'];
		
		$values = array();
		$values['Name'] = $name;
		$values['Streetaddress'] = $streetaddress;
		$values['Postalcode'] = $postalcode;
		$values['City'] = $city;
		$values['LocationtypeID'] = $locationtypeID;
		$values['CompanyID'] = $companyID;
		
		
		$locationID = Table::addRow("crm_locations", $values, true);
		
	}
	
	
	public function deletelocationAction () {

		$this->registry->loadParams();
		$yritysID=$_GET['yritysID'];
	}

	
}

?>
