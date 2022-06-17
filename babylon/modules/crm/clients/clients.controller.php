<?php


/**
 * Tämä on hieman harhaanjohtavasti sama kuin sales/clients-taulu, pitäisi jotenkin tuoda esille paremmin jo modulin 
 * nimessä, että kyse on henkilöasiakkaista. ClientPersons?
 * 
 * Henkilö voidaan joko kiinnittää yritykseen, jolloin hänet voidaan kiinnittää laskulle contactpersoniksi, tai mikäli yritys
 * myy tavaraa henkilöasiakkaille, niin tällöin henkilön osoitetiedot pitää kiinnittää itse henkilöön. Osoitteita voi olla
 * useampia. 
 * 
 * Myyntiasetuksista voidaan määärittää tekeekö yritys myyntiä henkilöasiakkaille vai yritysasiakkaille, vai molemmille, ehkä
 * ainoastaan oletusasetuksena, ei ole varmaankaan syytä kiinnittää tätä varta vasten. Tai ehkä pikemminkin niin, että asetuksissa
 * pystytään kieltämään, että myyntiä henkilöasiakkaille ei saa tehdä. 
 * 
 * @author pjuusita
 *
 */

class ClientsController extends AbstractController {


	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','yritys.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	

	
	public function indexAction() {
		//$this->showclientsAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showclientsAction () {
		
		$this->registry->clients = Table::load('crm_clients');
		
		foreach($this->registry->clients as $index => $client) {
			$client->fullname = $client->lastname . " " . $client->firstname;
		}
		
		$companies = Table::load('crm_companies');
		
		$this->registry->companies = $companies;
		
		$this->registry->jobtitles = Table::load('crm_jobtitles');
		
		$this->registry->template->show('crm/clients','clients');
	}

	
	public function showclientAction () {
		$clientpersonID = $_GET['id'];
		$this->registry->client = Table::loadRow('crm_clients',$clientpersonID);
		$this->registry->companies = Table::load('crm_companies');
		$this->registry->jobtitles = Table::load('crm_jobtitles');
		
		$this->registry->invoices = Table::load('sales_invoices', "WHERE ClientpersonID=" . $clientpersonID);
		$this->registry->bankstatementrows = Table::load('accounting_bankstatementrows', "WHERE ClientID=" . $clientpersonID);
		$this->registry->invoicestates = Collections::getInvoiceStates();
		$this->registry->statementrowstatuses = Collections::getBankStatementStates();
		
		$this->registry->template->show('crm/clients','client');
	}
	
	
	public function removeasiakashenkiloAction () {
		
		/*
	
		$AsiakashenkiläID=$_GET['id'];
		$success = Asiakashenkilot::removeAsiakashenkilo($AsiakashenkiläID);
		if ($success === true) {
			addMessage('Henkila poistettu onnistuneesti.');
			$this->registry->asiakashenkilot = Asiakashenkilot::loadAsiakashenkilot();
			$this->registry->yritykset = Yritys::loadYritykset();
			$this->registry->template->show('crm/clients','clientstable');
		} else {
			addErrorMessage($success);
			$this->registry->yritykset = Yritys::loadYritykset();
			$this->registry->asiakashenkilot = Asiakashenkilot::loadAsiakashenkilot();
			$this->registry->asiakashenkilo = Asiakashenkilot::loadAsiakashenkilo($AsiakashenkiläID);
			$this->registry->template->show('crm/clients','clientinfo');
		}
		*/

		$success = "removeasiakashenkiloAction not implemented";
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
	}
	
	
	public function insertclientAction() {

		$values = array();
		$values['Firstname'] = $_GET['Firstname'];
		$values['Lastname'] = $_GET['Lastname'];
		$values['Phonenumber'] = $_GET['Phonenumber'];
		$values['Email'] = $_GET['Email'];
		$values['JobtitleID'] = $_GET['JobtitleID'];
		$values['CompanyID'] = $_GET['CompanyID'];
		
		$clientID = Table::addRow("crm_clients", $values, true);

		
		redirecttotal('crm/clients/showclient&id=' . $clientID, null);
	}
	
	

	public function updateclientAction() {
	
		$values = array();
		$clientID = $_GET['id'];
		$values['Firstname'] = $_GET['Firstname'];
		$values['Lastname'] = $_GET['Lastname'];
		$values['Phonenumber'] = $_GET['Phonenumber'];
		$values['Email'] = $_GET['Email'];
		$values['JobtitleID'] = $_GET['JobtitleID'];
		$values['CompanyID'] = $_GET['CompanyID'];
	
		$success = Table::updateRow("crm_clients", $values, $clientID);
		redirecttotal('crm/clients/showclient&id=' . $clientID, null);
	}
	
	

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
	
	
	

	// TODO pitäisi tarkistaa henkilön viitteet, mikäli jossain on viite, niin vain piilotetaan
	public function removeclientAction() {
		$clientID = $_GET['id'];
		$success = Table::deleteRow('crm_clients',$clientID);
		redirecttotal('crm/clients/showclients', null);
	}
	
}

?>
