<?php

function sortYrityksetByNimi($yritysA, $yritysB) {
	$A=strtolower($yritysA->nimi);
	$B=strtolower($yritysB->nimi);
	if ($A > $B) return 1;
	if ($A < $B) return -1;
	return 0;
}

function sortYrityksetByYtunnus($yritysA, $yritysB) {
	if ($yritysA->ytunnus > $yritysB->ytunnus) return 1;
	if ($yritysA->ytunnus < $yritysB->ytunnus) return -1;
	return 0;
}

function sortYrityksetByJakeluosoite($yritysA, $yritysB) {
	if ($yritysA->jakeluosoite > $yritysB->jakeluosoite) return 1;
	if ($yritysA->jakeluosoite < $yritysB->jakeluosoite) return -1;
	return 0;
}

function sortYrityksetByPostinumero($yritysA, $yritysB) {
	if ($yritysA->postinumero > $yritysB->postinumero) return 1;
	if ($yritysA->postinumero < $yritysB->postinumero) return -1;
	return 0;
}
function sortYrityksetByAsiakasryhma($yritysA, $yritysB) {
	if ($yritysA->asiakasryhmäID > $yritysB->asiakasryhmäID) return 1;
	if ($yritysA->asiakasryhmäID < $yritysB->asiakasryhmäID) return -1;
	return 0;
}



class CompaniesController extends AbstractController {

	
	
	public function getCSSFiles() {
		//return array('menu.css','testcss.php');
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}

		
	public function indexAction() {
		//$this->showcompaniesAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function testupdateAction() {
		
		$success = true;
		
		echo "[{\"success\":\"" . $success . "\"}]";
	}

	public function showcompaniesAction() {
		
		updateActionPath("Yritykset");
		
		$filter = 0;
		if (isset($_GET['ryhmaID'])) $filter = $_GET['ryhmaID'];
		
		$this->registry->loadParams();
		
		//$table = Table::getTable("admin_docelements");
		//$this->registry->columns = $table->getColumns();
		$this->registry->companies = Table::load('crm_companies');
		$this->registry->groups = Table::load('crm_groups');
		$this->registry->countries = Table::load('system_countries');
		
		//updateActionTitle("Asiakasyrityskset");
		$this->registry->template->show('crm/companies','companies');
	}
	
	
	public function showcompanyAction() {
		
		$companyID = $_GET['id'];
		
		$this->registry->company = Table::loadRow('crm_companies',$companyID, false);
		
		$this->registry->groups = Table::load('crm_groups'); 								
		$this->registry->countries = Table::load('system_countries'); 								
		$this->registry->invoicingmodes = Table::load('crm_invoicingmodes');
		$this->registry->invoiceaddresses = Table::load('crm_invoiceaddresses', "WHERE CompanyID=" . $companyID);
		$this->registry->einvoiceaddresses = Table::load('crm_einvoiceaddresses', "WHERE CompanyID=" . $companyID);
		
		$this->registry->template->show('crm/companies','company');
	}
	
	
	
	
	
	public function updateinvoiceaddressAction() {
	
		$companyID = $_GET['companyID'];
		$invoiceaddressID = $_GET['id'];
		$streetaddress = $_GET['streetaddress'];
		$city = $_GET['city'];
		$postalcode = $_GET['postalcode'];
		$email = $_GET['email'];
		
		$values = array();
		$values['Streetaddress'] = $streetaddress;
		$values['City'] = $city;
		$values['Postalcode'] = $postalcode;
		$values['Email'] = $email;
		
		$success = Table::updateRow("crm_invoiceaddresses", $values, $invoiceaddressID, false);
		redirecttotal('crm/companies/showcompany&id=' . $companyID, null);
	}
	


	public function insertinvoiceaddressAction() {
	
		$companyID = $_GET['id'];
		$streetaddress = $_GET['streetaddress'];
		$city = $_GET['city'];
		$postalcode = $_GET['postalcode'];
		$email = $_GET['email'];
		
		$values = array();
		$values['CompanyID'] = $companyID;
		$values['Streetaddress'] = $streetaddress;
		$values['City'] = $city;
		$values['Postalcode'] = $postalcode;
		$values['Email'] = $email;
		
		$success = Table::addRow("crm_invoiceaddresses", $values, true);
		//redirecttotal('crm/companies/showcompany&id=' . $companyID, null);
	}
	
	
	
	
	public function updatecompanyAction() {
		
		$success='';
		$columns = array();
		foreach($_GET as $index => $value) {
			if (($index != 'id') && ($index != 'rt')) {
				$columns[$index]=$value;
			} elseif ($index == 'id') {
				$id = $value;
			}
		}
		$success = Table::updateRow('crm_companies', $columns, $id, false);
		redirecttotal('crm/companies/showcompany&id=' . $id, null);
	}
	
	
	
	public function insertcompanyAction() {

		$name = $_GET['name'];
		$businesscode = $_GET['businesscode'];
		
		$groupID = 0;
		if (isset($_GET['groupID'])) $groupID = $_GET['groupID'];

		$values = array();
		$values['Name'] = $name;
		$values['Businesscode'] = $businesscode;
		$values['GroupID'] = $groupID;
		
		
		$companyID = Table::addRow("crm_companies", $values, true);
		//redirecttotal('crm/companies/index&id=' . $companyID, null);
	}
	
		
	public function deleteyritysAction () {

		$this->registry->loadParams();
		$yritysID=$_GET['yritysID'];
		
		// Vahentunut, korvattu Row-luokalla
		/*
		if (Yritys::removeYritys($yritysID)) {
			addMessage("Yritys poistettu onnistuneesti");
			redirecttotal('crm/companies/showcompanies');
		} else {
			addErrorMessage("Poisto epäonnistui!");				
			redirecttotal('crm/companies/showcompanies');
		}
		*/
		
	}
	
	
	public function changeasiakasryhmaAction () {
		
		$yritysID=$_GET['yritysid'];
		$asiakasryhma=$_GET['asiakasryhma'];
		
		//$success = Yritys::saveAsiakasryhmä($yritysID,$asiakasryhma);
		if ($success == true) {
			echo "[{\"success\":\"" . $success . "\"}]";
		} else {
			echo "[{\"success\":\"" . $success . "\"}]";
		}
	}
	
	public function changetoimialaluokkaAction () {
	
		$yritysID=$_GET['yritysid'];
		$toimialaluokkaID=$_GET['toimialaluokkaid'];
	
		//$success = Yritys::saveToimialaluokka($yritysID,$toimialaluokkaID);
		if ($success == true) {
			echo "[{\"success\":\"" . $success . "\"}]";
		} else {
			echo "[{\"success\":\"" . $success . "\"}]";
		}
	}
	
	public function changeyritystiedotAction () {
		
		$yritysID=$_GET['yritysid'];
		$nimi=$_GET['nimi'];
		$ytunnus=$_GET['ytunnus'];
		$jakeluosoite=$_GET['jakeluosoite'];
		$postinumero=$_GET['postinumero'];
		
		//$success = Yritys::saveYritystiedot($yritysID,$nimi,$ytunnus,$jakeluosoite,$postinumero);
		if ($success == true) {
			echo "[{\"success\":\"true\",\"nimi\":\"" . $nimi . "\",\"ytunnus\":\"" . $ytunnus . "\",\"jakeluosoite\":\"" . $jakeluosoite . "\",\"postinumero\":\"" . $postinumero . "\"}]";
		} else {
			echo "[{\"success\":\"" . $success . "\"}]";
		}		
	}
	
/*
	public function updatecompanyAction () {
		
		$success='';
		$columns = array();
		foreach($_GET as $index => $value) {
			if (($index != 'id') && ($index != 'rt')) {
				$columns[$index]=$value;
			} elseif ($index == 'id') {
				$id=$value;
			}
		}
		$success = Table::updateRow('crm_clientcompanies10', $columns, $id);
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
		
		*/
		/*
		$success='';
		$columns=array();
		foreach($_GET as $index => $value) {
//						$success=$success.$index.'= '.$value.' - ';
			if (($index != 'id') && ($index != 'rt')) {
				$columns[$index]=$value;
			} elseif ($index == 'id') {
				$id=$value;
			}
		}
		//		$success=count($_GET);
		$success = Yritys::updateYritys($id,$columns);
		$success = Table::updateRow('crm_clientcompanies11',$columns, $id);
		
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
		*/
//	}
	
	
	/*
	public function insertcompanyAction () {

		$success='';
		$columns=array();
		
		foreach($_GET as $index => $value) {
			if ($index != 'rt') {
				$columns[$index]=$value;
			}
		}
		//$success = Yritys::insertYritys($columns);
		
		if (is_numeric($success)) {
	
			echo "[{\"success\":\"true\",\"id\":\"".$success."\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
	}
	*/
	
	
	public function existAction () {
	
//		$yritysID=$_GET['yritysid'];
//		$columnname=$_GET['columnname'];
//		$columnvalue=$_GET['columnvalue'];
	
//		$success = Yritys::exist($columnname,$columnvalue,$yritysID);
//		if ($success == true) {
//			echo "[{\"success\":\"true\"}]";
//		} else {
//			echo "[{\"success\":\"" . $success . "\"}]";
//		}

//		echo json_encode(array('jee'=>1));
//			echo "[{\"jee\":\"true\"}]";
		exit; 
		
	}
	

	public function loadtabAction() {

		$userID = $_GET['id'];
		$activetab = $_GET['activetab'];
		$this->registry->tabdata = Table::loadRow('system_users', $userID);
		$this->registry->activetab = $activetab;
		$this->registry->template->show('crm/companies','companies');
	
	}
}

?>
