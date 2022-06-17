<?php

/**
 * Settingscontrollerissa hallitaan jarjestelman asetuksia. Periaatteessa jokaista kaytassa olevaa modulia varten on oma
 * sectioninsa, jokaiselta modulilta tulisi siis saada tarpeelliset asetukset.
 * 
 * Perusasetuksia on muutamia, ainakin ulkoasuun ja logoon liittyvia asetuksia, mutta periaatteessa namakin voisivat tulla
 * suoraan kaytettavissa olevista oletusmoduleista. Lahinna kai niin, etta system module on automaattisesti kaytassa, myas admin
 * moduli on todennakaisesti automaattisesti kaytassa. Osa moduleista on tosin pelkastaan hallinta kayttaan, esim. database.
 * 
 *
 */
class AccountingsettingsController extends AbstractController {

	
	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
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
	
		$this->registry->receiptsets = Table::load('accounting_receiptsets');
		$this->registry->vats = Table::load('system_vats');
		$this->registry->accounts = Table::load('accounting_accounts');
		$this->registry->bankaccounts = Table::load('accounting_bankaccounts');
		$this->registry->paymentmethods = Table::load('accounting_paymentmethods');
		$this->registry->paymentcards = Table::load('accounting_paymentcards');
		$this->registry->workers= Table::load('hr_workers');
		$this->registry->duedateselection = Collections::getDueDateUsageSelection();
		$this->registry->duedateselectionshort = Collections::getDueDateUsageSelectionShort();
		
		foreach($this->registry->workers as $index => $account) {
			$account->fullname = $account->lastname. " " . $account->firstname;
		}
			
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		
		$this->registry->template->show('accounting/accountingsettings','accountingsettings');
	}
	
	
	
	public function insertpaymentmethodAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['AccountID'] = $_GET['accountID'];
		$success = Table::addRow("accounting_paymentmethods", $values, true);
	
		redirecttotal('accounting/accountingsettings/showsettings',null);
	}
	
	

	public function insertpaymentcardAction() {
	
		$values = array();
		$values['BankaccountID'] = $_GET['bankaccountID'];
		$values['Number'] = $_GET['number'];
		$values['CreditaccountID'] = $_GET['creditaccountID'];
		$values['WorkerID'] = $_GET['workerID'];
		$success = Table::addRow("accounting_paymentcards", $values, true);
		redirecttotal('accounting/accountingsettings/showsettings',null);
	}
	
	

	public function insertbankaccountAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Iban'] = $_GET['iban'];
		$values['AccountID'] = $_GET['accountID'];
		$success = Table::addRow("accounting_bankaccounts", $values, true);
	
		redirecttotal('accounting/accountingsettings/showsettings',null);
	}
	
	

	public function updatebankaccountAction() {
	
		$bankaccountID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Iban'] = $_GET['iban'];
		$values['AccountID'] = $_GET['accountID'];
		$values['PayablesaccountID'] = $_GET['payablesaccountID'];
		$values['ReceivablesaccountID'] = $_GET['receivablesaccountID'];
		$success = Table::updateRow('accounting_bankaccounts', $values, $bankaccountID, false);
		
		redirecttotal('accounting/accountingsettings/showsettings',null);
	}
	
	
	public function updatepaymentcardAction() {
	
		$cardID = $_GET['id'];
		$values = array();
		$values['BankaccountID'] = $_GET['bankaccountID'];
		$values['Number'] = $_GET['number'];
		$values['CreditaccountID'] = $_GET['creditaccountID'];
		$values['WorkerID'] = $_GET['workerID'];
		$success = Table::updateRow('accounting_paymentcards', $values, $cardID, false);
	
		redirecttotal('accounting/accountingsettings/showsettings',null);
	}
	
	
	

	public function updatepaymentmethodAction() {
	
		$paymentmethodID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['AccountID'] = $_GET['accountID'];
		$values['Duedateusage'] = $_GET['duedateusage'];
		$success = Table::updateRow('accounting_paymentmethods', $values, $paymentmethodID, false);
	
		redirecttotal('accounting/accountingsettings/showsettings',null);
	}
	
	
	
	public function removepaymentmethodAction() {
	
		// Tarkista onko tätä payment methodia käytössä jossain...
		
		// Disabloidaanko tämä ainoastaan, vai estetäänkö poisto kokonaan jos on asetettu
		
		echo "<br>Not implemented";
		exit();
		
		redirecttotal('accounting/accountingsettings/showsettings',null);
	}
	
	
	
	

	public function insertvatAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Percent'] = str_replace(",", ".", $_GET['percent']);
		$success = Table::addRow("system_vats", $values, true);
	
		redirecttotal('accounting/accountingsettings/showsettings',null);
	}
	

}

?>
