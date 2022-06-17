<?php


class BankstatementeventsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		//$this->showbankstatementeventsAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function getEventtypes() {
		
		$eventtypes = array();
		
		$eventtype = new Row();
		$eventtype->name = "Linkitys ostolaskuun";
		$eventtype->eventtypeID = 1;
		$eventtypes[1] = $eventtype;

		$eventtype = new Row();
		$eventtype->name = "Linkitys palkkalaskelmaan";
		$eventtype->eventtypeID = 2;
		$eventtypes[2] = $eventtype;
		
		$eventtype = new Row();
		$eventtype->name = "Linkitys myyntilaskuun";
		$eventtype->eventtypeID = 3;
		$eventtypes[3] = $eventtype;
		
		$eventtype = new Row();
		$eventtype->name = "Linkitys kirjanpitotiliin";
		$eventtype->eventtypeID = 4;
		$eventtypes[4] = $eventtype;
		
		return $eventtypes;
		
		//$eventtypes[1] = "Linkitys ostolaskuun";				// ostolaskusta saadaan käytetty ostovelkatili
		//$eventtypes[2] = "Linkitys palkkalaskelmaan";		// palkkalaskelman tositteesta saadaan palkkavelkojen tili
		//$eventtypes[3] = "Linkitys myyntilaskuun";			// myyntilaskun tositteelta saadaan myyntisaamisten tili
		//$eventtypes[4] = "Linkitys kirjanpitotiliin";		// kirjanpitotili valittava erikseen
	}
	


	public function showbankstatementeventsAction() {
	
		$this->registry->bankstatementevents = Table::load('accounting_bankstatementevents', ' ORDER BY Name');
		$this->registry->accounts = Table::load('accounting_accounts', ' ORDER BY Name, Number');
		$this->registry->eventtypes = $this->getEventtypes();

		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		
		foreach($this->registry->bankstatementevents as $index => $bankstatementevent) {
			if ($bankstatementevent->eventtypeID == 1) {
				$bankstatementevent->explanation = "Linkitys ostolaskuun";
			}
			if ($bankstatementevent->eventtypeID == 2) {
				$bankstatementevent->explanation = "Linkitys palkanlaskentaan";
			}
			if ($bankstatementevent->eventtypeID == 3) {
				$bankstatementevent->explanation = "Linkitys myyntilaskuun";
			}
			if ($bankstatementevent->eventtypeID == 4) {
				$account = $this->registry->accounts[$bankstatementevent->accountID];
				$bankstatementevent->explanation = $account->fullname;
			}
		}
		
		$this->registry->template->show('accounting/bankstatementevents','bankstatementevents');
	}
	
	
	
	public function insertbankstatementeventAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['EventtypeID'] = $_GET['eventtypeID'];
		$values['AccountID'] = $_GET['accountID'];
		$success = Table::addRow("accounting_bankstatementevents", $values, false);
	
		redirecttotal('accounting/bankstatementevents/showbankstatementevents',null);
	}
	
	
	public function updatebankstatementeventAction() {
	
		$id = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['EventtypeID'] = $_GET['eventtypeID'];
		$values['AccountID'] = $_GET['accountID'];
		$success = Table::updateRow("accounting_bankstatementevents", $values, $id);
	
		redirecttotal('accounting/bankstatementevents/showbankstatementevents',null);
	}
	
	
	// removebankstatementevent
	
}

?>
