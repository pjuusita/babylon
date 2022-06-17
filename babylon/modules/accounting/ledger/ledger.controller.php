<?php

/**
 * General Ledger - Paakirja
 * 
 * Taman avulla voidaan tarkastella paakirjaa. Englannin kielessa ledger sanaa kaytetaan myas reskontrasta,
 * eli taman avulla voidaan kylla esittaa mahdollisesti myas osto ja myyntireskontra, naita varten on kuitenkin
 * tarkoitus tehda omat sivunsta, ehka tyyliin ostolaskujen seuranta, myyntilaskujen seuranta.
 * 
 *
 */
class LedgerController extends AbstractController {

	
	
	public function getCSSFiles() {
		return array('menu.css','testcss.php','mytheme/jquery-ui.css','prism.css','fileuploader.css','chosentest.css');
	}

	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js','fileuploader.js','timespancolumn.class.js','timeselectcolumn.class.js','changeabledropdowncontentcolumn.class.js','changedropdowncontentcolumn.class.js','timesheetdatecolumn.class.js','timesheetdatepickercolumn.class.js','hourcalculator.class.js','timesheetemptycolumn.class.js','timesheetsplitcolumn.class.js','timesheetremovecolumn.class.js','timesheetbuttonfunctions.class.js','timesheetremovalvalidations.class.js','timesheetfixedcolumn.class.js','timesheetrowidentifier.class.js','timesheetinputcolumn.class.js','timesheetvalidations.class.js','timesheetauxilaryfunctions.class.js');
	}


	public function indexAction() {
		//$this->showledgerAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	
	

	public function showledgerAction () {
		if (isset($_GET['selectedaccount'])) {
			$this->registry->selectedaccount=$_GET['selectedaccount'];
		} else {
			$this->registry->selectedaccount=0;
		}
		$this->registry->accounts = Table::load('accounting_accounts');
	
		$accountnumbers = array();
		$accountnames = array();
		foreach($this->registry->accounts as $index => $account) {
			$accountnumbers[$account->accountID] = $account->number;
			$accountnames[$account->accountID] = $account->name;
		}
		$this->registry->accountnumbers = $accountnumbers;
		$this->registry->accountnames = $accountnames;
	
	
		$this->registry->journalentries = Table::load('accounting_journalentries');
		$this->registry->accountentries = Table::load('accounting_accountentries');
		//$this->registry->suppliers = Table::loadKeyValueArray('supply_suppliers', 'SupplierID', 'Name');
	
		foreach($this->registry->journalentries as $index => $journalentry) {
			foreach($this->registry->accountentries as $index => $accountentry) {
				if ($journalentry->entryID == $accountentry->journalentryID) {
					//echo "<br>Add childi";
					$journalentry->addChild($accountentry);
				} else {
					//echo "<br>Not tasmaa - " . $journalentry->entryID . " vs. " . $accountentry->journalentryID;
				}
			}
		}
	
		$this->registry->template->show('accounting/journal','journal');
	}
	
	
	
	
}
?>