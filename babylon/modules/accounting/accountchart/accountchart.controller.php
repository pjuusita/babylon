<?php


/**
 * Chart of Accounts = tilikartta
 * 
 * Tilikarttanakyma ja sen editointi. Tulostusmahdollisuus.
 * 
 * Valikkoina ainakin 'kaikki tilit', 'poistuneet tilit', 'aktiiviset tilit'. Tama pitaa hoitaa
 * jollakin filtteri tyyppisella ratkaisulla.
 * 
 * Tileilla pitaa olla asetettuna omia tietoja, kuten valittavana erilaisia kayttajalle laskujen
 * jaotteluun kaytettavia kenttia esimerkiksi (nama voivat olla eri kayttajaryhmille erilaisia).
 * Laskutuksen kasittely voi olla ehka myas jokaiselle kayttajalle erikseen asetettava 'profiili'
 * 
 *
 */
class AccountchartController extends AbstractController {

	
	
	public function getCSSFiles() {
		return array('menu.css','testcss.php','chosen.css');
	}
	
	
	public function getJSFiles() {
		return array('chosen.jquery.js','prism.js');
	}
	
	
	public function indexAction() {
		//$this->showaccountchartAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACCOUNTCHART ACTIONS
	//******************************************************************************************************
	
	
	public function showaccountchartAction () {
		
		updateActionPath("Tilikartta");
		$this->registry->accounts = Table::loadHierarchy('accounting_accounts','parentID','ORDER BY Sortorder');

		$this->registry->allaccounts = Table::load('accounting_accounts');
		foreach($this->registry->allaccounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		$this->registry->accounttypes = Table::load('accounting_accounttypes');
		
		$this->registry->template->show('accounting/accountchart','accountchart');
	}
	
	
	
	
	
	
	public function showaccountAction () {
		
		
		$accountID = $_GET['id'];
		$this->registry->account = Table::loadRow('accounting_accounts',$accountID);
		updateActionPath("Tili " . $this->registry->account->name);
		$this->registry->accounttypes = Table::load('accounting_accounttypes');
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		
		$this->registry->costpools = Table::load('accounting_costpools');
		$this->registry->expenditures = Table::load('accounting_expenditures', "WHERE AccountID=" . $accountID);
		
		
		$this->registry->template->show('accounting/accountchart','account');
	}
	
	
	
	public function insertaccountAction () {
		
		$values = array();
		$values['Number'] =  $_GET['number'];
		$values['ParentID'] =  $_GET['parentID'];
		$values['Name'] =  $_GET['name'];
		$values['AccounttypeID'] =  $_GET['accounttypeID'];
		
		$accountID = Table::addRow("accounting_accounts", $values, true);
		
		redirecttotal('accounting/accountchart/showaccountchart');
	}
	
	
	

	public function insertexpenditureAction () {
	
		$values = array();
		$values['Name'] =  $_GET['name'];
		$values['AccountID'] =  $_GET['accountID'];
	
		$accountID = Table::addRow("accounting_expenditures", $values, true);
	
		redirecttotal('accounting/accountchart/showaccount&id=' . $_GET['accountID']);
	}
	
	
	
	/**
	 * Tämä palauttaa listan saatavilla olevista esimerkki tilikartoista. Sisäisesti
	 * nämä ovat kirjanpitoja, joiden tilikartta palautetaan. KirjanpitoID:t ovat 
	 * sisäisesti piilotettuja, tieto kulkee hardkoodatun ID-numeron perusteella
	 * funktiolle getDefaultAccountChart. Palautetaan nimi ja id-numero
	 * 
	 */
	public function getDefaultAccountCharts() {
		
	}
	
	
	/**
	 * Palautetaan id-numeron mukainen accountchart.
	 * 
	 */
	public function getDefaultAccountChart() {
		// TODO: ota mallia database.getTables, jota käytetän json.com kautta
		// TODO: kutsut esimerkiksi install classissa 	
	}
	
	
	public function updateaccountAction () {

		$values = array();
		$accountID = $_GET['id'];
		$values['Number'] =  $_GET['number'];
		$values['ParentID'] =  $_GET['parentID'];
		$values['Name'] =  $_GET['name'];
		$values['AccounttypeID'] =  $_GET['accounttypeID'];
		
		$success = Table::updateRow("accounting_accounts", $values, $accountID, true);
		redirecttotal('accounting/accountchart/showaccount&id=' . $accountID);
	}
	

	
	public function moveAccountup($accountID,$parentID) {
		
		/*
		global $mysqli;
		
		$previous=null;
		$current=null;
		
		$rows = Table::load("accounting_accounts", " WHERE ParentID='" . $parentID . "' AND Removedate='0000-00-00 00:00:00' ORDER BY Precedence DESC;");
		
		foreach($rows as $index => $account) {
			//echo "<br>vertailu - " . $account->getID() . " vs. " . $accountID;
			if ($account->getID() == $accountID) {
				//echo "<br>tasmaa";
				$current = $account;
			}
			if ($current != null) {
				//echo "<br>previousset";
				$previous = $account;
			}
		}
		if ($current == null) echo "<br>currntnull";
		if ($previous == null) echo "<br>prevnull";
		
		//echo "<br>current - " . $current->getID() . ", previous - " . $previous->getID();
		if ($previous == null) return 'rivi on jo ylimpana';
		
		// naa pitais siirtaa classesin alle
		$sql = "UPDATE accounting_accounts SET Precedence='".$previous->precedence."' WHERE AccountID='".$current->accountID."'";
		$result = $mysqli->query($sql);
		if (!$result) return 'editAccount failed: ' . $mysqli->connect_error;
		$sql = "UPDATE accounting_accounts SET Precedence='".$current->precedence."' WHERE AccountID='".$previous->accountID."'";
		$result = $mysqli->query($sql);
		if (!$result) return 'editAccount failed: ' . $mysqli->connect_error;
		return true;
		*/
	}
	
	
	// tama ilmeisesti kopioitu vanhasta account luokasta, paivittamatta
	// tälle on mielestäni jokin olemassaoleva metodi, jossain käytetty vastaavaa toimintoa, ehkä worderissä
	// ehkä worder.groups. moverorderup
	public static function moveAccountdown($accountID,$parentID) {
		
		/*
		global $mysqli;
		
		$sql = "SELECT * FROM accounts WHERE ParentID='" . $parentID . "' AND Removedate='0000-00-00 00:00:00' ORDER BY Precedence DESC";
		$result = $mysqli->query($sql);
		if (!$result) die('loadAccount failed: ' . $mysqli->connect_error);
	
		$previous=null;
		$current=null;
		while($row = $result->fetch_array()) {
			$account = new Account($row);
			if ($account->getID() == $accountID) {
				$current=$account;
			}
			if ($current == null) {
				$previous=$account;
			}
		}
		if ($previous == null) return 'rivi on jo alimpana';
		$sql = "UPDATE accounts SET Precedence='".$previous->precedence."' WHERE AccountID='".$current->accountID."'";
		$result = $mysqli->query($sql);
		if (!$result) return 'editAccount failed: ' . $mysqli->connect_error;
		$sql = "UPDATE accounts SET Precedence='".$current->precedence."' WHERE AccountID='".$previous->accountID."'";
		$result = $mysqli->query($sql);
		if (!$result) return 'editAccount failed: ' . $mysqli->connect_error;
		return true;
		*/
	}
	
	
	public function moveaccountAction () {
		
		/*
		$suunta=$_GET['move'];
		$tili = Table::loadRow("accounting_accounts", $_GET['id']);
		if ($suunta == 'up') {
			$success = $this->moveAccountup($tili->accountID,$tili->parentID);
		} else {
			$success = $this->moveAccountdown($tili->accountID,$tili->parentID);				
		}
		
		if ($success === true) {
			addMessage('siirretty onnistuneesti.');
		} else {
			addErrorMessage($success);
		}
		redirecttotal('accounting/accountchart/showaccountchart');
		*/
	}
	
	
	// Pete: rename removeacttount
	public function removeaccountAction () {

		/*
		$id=$_GET['id'];
		$success = Table::deleteRow(" AccountID='" . $id . "'");
		if ($success === true) {
			addMessage('Poistettu onnistuneesti.');
		} else {
			addErrorMessage("Tuntematon tietokantavirhe: ".$success);
		}

		redirecttotal('accounting/accountchart/showaccountchart');
		*/
	}
}

?>