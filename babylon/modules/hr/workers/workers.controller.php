<?php


class WorkersController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','yritys.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
		
	public function indexAction() {
		//$this->showworkersAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showworkersAction () {
		
		$this->registry->workers = Table::load('hr_workers');
		$this->registry->workergroups = Table::load('hr_workergroups');
		$this->registry->worktitles = Table::load('hr_worktitles');
		
		$this->registry->template->show('hr/workers','workers');
	}

	
	public function showworkerAction () {
		$workerID = $_GET['id'];
		$this->registry->worker = Table::loadRow('hr_workers',$workerID);
		//$this->registry->companies = Table::load('crm_companies');
		$this->registry->worktitles = Table::load('hr_worktitles');
		$this->registry->labouragreements = Table::load('hr_labouragreements');
		$this->registry->pensioninsurancetypes = Collections::getPensionInsuranceTypes();
		
		$this->registry->workcontracts = Table::load('hr_workcontracts', 'WHERE WorkerID=' . $workerID . " ORDER BY Startdate DESC");
		$this->registry->taxcards = Table::load('hr_taxcards', 'WHERE WorkerID=' . $workerID);
		$this->registry->salarycategories = Table::load('hr_salarycategories');
		
		$this->registry->paychecks = Table::load('payroll_paychecks', "WHERE WorkerID=" . $workerID . " ORDER BY Bookkeepingdate");
		
		
		/*
		// TODO: salarytypessiä tarvitaan ehkä palkkatietoihin
		$salarytypes = Table::load('hr_salarytypes');
		foreach($salarytypes as $index => $salarytype) {
			$category = $this->registry->salarycategories[$salarytype->salarycategoryID];
			$salarytype->fullname = $salarytype->name . ", " . $category->abbreviation;
		}
		$this->registry->salarytypes = $salarytypes;
		*/
		$this->registry->template->show('hr/workers','worker');
	}
	
	
	public function insertworkerAction() {

		$values = array();
		$values['Firstname'] = $_GET['Firstname'];
		$values['Lastname'] = $_GET['Lastname'];
		$values['Phonenumber'] = $_GET['Phonenumber'];
		$values['Email'] = $_GET['Email'];
		
		$workerID = Table::addRow("hr_workers", $values, true);

		
		redirecttotal('hr/workers/showworker&id=' . $workerID, null);
	}
	
	
	

	public function insertworkcontractAction() {
	
		$workerID = $_GET['workerID'];
			
		$values = array();
		$values['WorkerID'] = $_GET['workerID'];
		$values['PensioninsurancetypeID'] = $_GET['pensioninsurancetypeID'];
		$values['LabouragreementID'] = $_GET['labouragreementID'];
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];
		$success = Table::addRow("hr_workcontracts", $values, false);
		redirecttotal('hr/workers/showworker&id=' . $workerID, null);
	}
	
	

	public function inserttaxcardAction() {
	
		$workerID = $_GET['workerID'];
			
		$values = array();
		$values['WorkerID'] = $_GET['workerID'];
		$values['Percent1'] = $_GET['percent1'];
		$values['Percent2'] = $_GET['percent2'];
		$values['Taxlimit'] = $_GET['taxlimit'];
		$values['Oldsalary'] = $_GET['oldsalary'];
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];
		$values['Sumstartdate'] = $_GET['enddate'];
		
		$success = Table::addRow("hr_taxcards", $values, true);
		redirecttotal('hr/workers/showworker&id=' . $workerID, null);
	}
	
	
	/*
	public function insertdeductionAction() {
	
		$workerID = $_GET['workerID'];
		$salarytypeID = $_GET['salarytypeID'];
		$salartype = Table::loadRow("hr_salarytypes", $salarytypeID);
		
		$values = array();
		$values['WorkerID'] = $workerID;
		$values['SalarytypeID'] = $salarytypeID;
		$values['SalarycategoryID'] = $salartype->salarycategoryID;
		$values['Percent'] = $_GET['percent'];
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];
		
		$success = Table::addRow("payroll_deductionpercents", $values);
		redirecttotal('hr/workers/showworker&id=' . $workerID, null);
	}
	*/
	
	
	

	public function updateworkcontractAction() {
	
		$workcontractID = $_GET['id'];
		$workerID = $_GET['workerID'];
		
		$values = array();
		$values['LabouragreementID'] = $_GET['labouragreementID'];
		$values['PensioninsurancetypeID'] = $_GET['pensioninsurancetypeID'];
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];
		$success = Table::updateRow("hr_workcontracts", $values, $workcontractID);
		redirecttotal('hr/workers/showworker&id=' . $workerID, null);
	}
	
	
	
	
	

	public function updateworkerAction() {
	
		$values = array();
		$clientID = $_GET['id'];
		$values['Firstname'] = $_GET['firstname'];
		$values['Lastname'] = $_GET['lastname'];
		$values['Phonenumber'] = $_GET['phonenumber'];
		$values['Bankaccountnumber'] = $_GET['bankaccountnumber'];
		$values['Email'] = $_GET['email'];
		$values['Identificationnumber'] = $_GET['identificationnumber'];
		$values['Streetaddress'] = $_GET['streetaddress'];
		$values['City'] = $_GET['city'];
		$values['Postalcode'] = $_GET['postalcode'];
		
		$success = Table::updateRow("hr_workers", $values, $clientID);
		redirecttotal('hr/workers/showworker&id=' . $clientID, null);
	}
	
	

	public function updatetaxcardAction() {
	
		$workerID = $_GET['workerID'];
		$taxcardID = $_GET['id'];
		
		$values = array();
		$values['Percent1'] = $_GET['percent1'];
		$values['Percent2'] = $_GET['percent2'];
		$values['Taxlimit'] = $_GET['taxlimit'];
		$values['Oldsalary'] = $_GET['oldsalary'];
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];
		$values['Sumstartdate'] = $_GET['enddate'];
		
		$success = Table::updateRow("hr_taxcards", $values, $taxcardID);
		
	
		redirecttotal('hr/workers/showworker&id=' . $workerID, null);
	}
	

	/*
	public function updatedeductionAction() {
	
		$workerID = $_GET['workerID'];
		$deductionID = $_GET['id'];
		$salarytypeID = $_GET['salarytypeID'];
		$salartype = Table::loadRow("hr_salarytypes", $salarytypeID);
		
		$values = array();
		$values['SalarytypeID'] = $salarytypeID;
		$values['SalarycategoryID'] = $salartype->salarycategoryID;
		$values['Percent'] = $_GET['percent'];
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];
		$success = Table::updateRow("payroll_deductionpercents", $values, $deductionID);
		redirecttotal('hr/workers/showworker&id=' . $workerID, null);
	}
	*/
	
	
	

	public function changeasiakashenkilotiedotAction () {
	
		/*
		
		$AsiakashenkiläID=$_GET['id'];
		$yritys=$_GET['yritys'];
		$sukunimi=$_GET['sukunimi'];
		$etunimi=$_GET['etunimi'];
		$puhnro=$_GET['puhnro'];
		$email=$_GET['email'];
	
		$success = Asiakashenkilot::saveAsiakashenkilotiedot($AsiakashenkiläID,$yritys,$sukunimi,$etunimi,$puhnro,$email);
		*/
		
		$success = "changeasiakashenkilotiedotAction not implemented";
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
	}
	
	
	public function changetitteliAction () {
	
		/*
		$AsiakashenkiläID=$_GET['AsiakashenkiläID'];
		$titteliID=$_GET['titteliid'];
		$success = Asiakashenkilot::saveTitteli($AsiakashenkiläID,$titteliID);
		*/
		
		$success = "changetitteliAction not implemented";
		if ($success === true) {
			echo "[{\"success\":\"" . $success . "\"}]";
		} else {
			echo "[{\"success\":\"" . $success . "\"}]";
		}
	}
	
}

?>
