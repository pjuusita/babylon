<?php


class SettingsController extends AbstractController {

	

	
	public function getCSSFiles() {
		//return array('menu.css','testcss.php');
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}

	
	public function indexAction() {
		//$this->showsettingsAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showsettingsAction() {
		
		$filter = 0;
		if (isset($_GET['ryhmaID'])) $filter = $_GET['ryhmaID'];
		
		$this->registry->loadParams();
		
		$this->registry->worktitles= Table::load('hr_worktitles');
		$this->registry->workergroups = Table::load('hr_workergroups');
		$this->registry->workerstates = Table::load('hr_workerstates');
		
		$this->registry->template->show('hr/settings','settings');
	}
	
	
	public function showcompanyAction() {
		
		$locationID = $_GET['id'];
		$this->registry->location = Table::loadRow('crm_locations',$locationID);
		$this->registry->companies = Table::loadRow('crm_companies');
		$this->registry->locationtypes = Table::loadRow('crm_locationtypes');
		
		$this->registry->template->show('crm/companies','location');
	}
	
	
	
	public function updatelocationAction() {
		
		$success='';
		$columns = array();
		foreach($_GET as $index => $value) {
			if (($index != 'id') && ($index != 'rt')) {
				$columns[$index]=$value;
			} elseif ($index == 'id') {
				$id = $value;
			}
		}
		$success = Table::updateRow('crm_locations', $columns, $id, false);
		redirecttotal('crm/companies/showlocation&id=' . $id, null);
	}
	
	
	
	public function insertlocationAction() {

		$name = $_GET['name'];
		$businesscode = $_GET['businesscode'];
		$groupID = $_GET['groupID'];

		$values = array();
		$values['Name'] = $name;
		$values['Businesscode'] = $businesscode;
		$values['GroupID'] = $groupID;
		
		
		$companyID = Table::addRow("crm_companies", $values, true);
	}
	
	
	public function deletelocationAction () {

		$this->registry->loadParams();
		$yritysID=$_GET['yritysID'];
	}

	
}

?>
