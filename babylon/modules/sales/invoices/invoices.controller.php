<?php



class InvoicesController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		//$this->showinvoicesAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showinvoicesAction() {
	
		$comments = false;
		updateActionPath("Myyntilaskut");
		
		$oldperiodID = getOldModuleSessionVar('periodID');
		if ($comments) echo "<br>OldperiodID - " . $oldperiodID;
		$periodID = SalesModule::getBookkeepingPeriod();
		if ($comments) echo "<br>PeriodID - " . $periodID;
		
		$this->registry->periodID = $periodID;
		$this->registry->periods = Table::load('accounting_periods',' ORDER BY Startdate');
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) {
				if ($comments) echo "<br>Selected periodID found  - " . $periodID;
				$this->registry->period = $period;
				$startdate = $this->registry->period->startdate;
				$enddate = $this->registry->period->enddate;
			}
			$period->fullname = "Tilikausi " . $period->name;
		}
				
		$selectionID = 0;
		if ($oldperiodID != $periodID) {		// tilikautta on vaihdettu
			$selectionID = 0;
			setModuleSessionVar('selectionID',$selectionID);
			$startdate = $this->registry->period->startdate;
			setModuleSessionVar('periodstartdate',$startdate);
			$enddate = $this->registry->period->enddate;
			setModuleSessionVar('periodenddate',$enddate);
		} else {
			if ($comments) echo "<br>Period not changed";
			$selectionID = getModuleSessionVar('selectionID');
			if ($comments) echo "<br>SelectionID - " . $selectionID;
			$startdate = getModuleSessionVar('periodstartdate');
			if ($comments) echo "<br>startdate - " . $startdate;
			$enddate = getModuleSessionVar('periodenddate');
			if ($comments) echo "<br>enddate - " . $enddate;
		}
		
		
		$selection = Collections::generatePeriodTimescales($this->registry->period, $selectionID);
		//$selection = SalesModule::generatePeriodTimescales($this->registry->period, $selectionID);
		$this->registry->selection = $selection;
		if ($selectionID > 0) {
			$currentselect = $selection[$selectionID];
			$startdate = $currentselect->startsql;
			$enddate = $currentselect->endsql;
		}
		
		if ($comments) echo "<br>dates - " . $startdate . " - " . $enddate;
		$this->registry->selectionID = $selectionID;
		
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		
		
		$this->registry->invoicetypes = SalesModule::getSalesInvoiceTypes();
		$this->registry->clienttypes = SalesModule::getSalesInvoiceClientTypes();
		
		
		$this->registry->saletypes = Table::load('sales_saletypes');
		$this->registry->companies = Table::load('crm_companies');
		$this->registry->clients = Table::load('crm_clients');
		

		
		
		//echo "<br>Invoicetypecount - " . count($this->registry->invoicetypes);
		
		
		
		// Ladataan myyntilaskut ja niiden dimensiot
		$invoices = Table::load('sales_invoices', " WHERE Invoicedate>='" . $startdate . "' AND Invoicedate<='" . $enddate . "' ORDER BY Invoicedate");
		$this->registry->lastdate = $startdate;
		foreach($invoices as $index => $invoice) {
			if ($invoice->invoicedate > $this->registry->lastdate) $this->registry->lastdate = $invoice->invoicedate;
			
			$typefound = false;
			
			if ($invoice->clienttypeID == SalesModule::CLIENTTYPE_COMPANY) {
				$company = $this->registry->companies[$invoice->clientcompanyID];
				$invoice->description = $company->name;
				$typefound = true;
			}
			
			if ($invoice->clienttypeID == SalesModule::CLIENTTYPE_PERSON) {
				$client = $this->registry->clients[$invoice->clientpersonID];
				$invoice->description = $client->lastname . " " . $client->firstname;
				$typefound = true;
			}
				
			if ($invoice->clienttypeID == SalesModule::CLIENTTYPE_GENERALSALE) {
				$saletype = $this->registry->saletypes[$invoice->saletypeID];
				$invoice->description = $saletype->name;
				$typefound = true;
			}
			
			if ($typefound == false) {
				echo "<br>Myyntityyppiä ei löytynyt - invoiceID: " . $invoice->invoiceID;
			}
		}
		$this->registry->invoices = $invoices;
		
		
		$persons = Table::load('crm_clients');
		$contactpersons = array();
		$privateclients = array();
		foreach($persons as $index => $person) {
			$person->name = $person->lastname . " " . $person->firstname;
			if ($person->companyID == 0) {
				$contactpersons[$person->clientID] = $person;
				//echo "<br>Contactperson - " . $person->lastname;
			} else {
				$privateclients[$person->clientID] = $person;
				//echo "<br>Private client - " . $person->lastname;
			}
		}
		$this->registry->contactpersons = $contactpersons;
		$this->registry->privateclients = $privateclients;
		
		
		
		$this->registry->invoicestates = Collections::getInvoiceStates();
		
		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		$dimensionvalues = array();
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$dimensionvalues[$dimension->dimensionID] = Table::load('system_dimensionvalues',' WHERE DimensionID=' . $dimension->dimensionID);
			}
		}
		$this->registry->dimensionvalues = $dimensionvalues;
		
		
		$this->registry->template->show('sales/invoices','invoices');
	}
	
	
	
	public function showunpaidAction() {
		$comments = false;
		
		$oldperiodID = getOldModuleSessionVar('periodID');
		if ($comments) echo "<br>OldperiodID - " . $oldperiodID;
		$periodID = SalesModule::getBookkeepingPeriod();
		if ($comments) echo "<br>PeriodID - " . $periodID;
		
		$this->registry->periodID = $periodID;
		$this->registry->periods = Table::load('accounting_periods',' ORDER BY Startdate');
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) {
				if ($comments) echo "<br>Selected periodID found  - " . $periodID;
				$this->registry->period = $period;
				$startdate = $this->registry->period->startdate;
				$enddate = $this->registry->period->enddate;
			}
			$period->fullname = "Tilikausi " . $period->name;
		}
		
		$selectionID = 0;
		if ($oldperiodID != $periodID) {		// tilikautta on vaihdettu
			$selectionID = 0;
			setModuleSessionVar('selectionID',$selectionID);
			$startdate = $this->registry->period->startdate;
			setModuleSessionVar('periodstartdate',$startdate);
			$enddate = $this->registry->period->enddate;
			setModuleSessionVar('periodenddate',$enddate);
		} else {
			if ($comments) echo "<br>Period not changed";
			$selectionID = getModuleSessionVar('selectionID');
			if ($comments) echo "<br>SelectionID - " . $selectionID;
			$startdate = getModuleSessionVar('periodstartdate');
			if ($comments) echo "<br>startdate - " . $startdate;
			$enddate = getModuleSessionVar('periodenddate');
			if ($comments) echo "<br>enddate - " . $enddate;
		}
		
		
		$selection = SalesModule::generatePeriodTimescales($this->registry->period, $selectionID);
		$this->registry->selection = $selection;
		if ($selectionID > 0) {
			$currentselect = $selection[$selectionID];
			$startdate = $currentselect->startsql;
			$enddate = $currentselect->endsql;
		}
		
		if ($comments) echo "<br>dates - " . $startdate . " - " . $enddate;
		$this->registry->selectionID = $selectionID;
		
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		
		
		$this->registry->invoicetypes = SalesModule::getSalesInvoiceTypes();
		$this->registry->clienttypes = SalesModule::getSalesInvoiceClientTypes();
		
		
		$this->registry->saletypes = Table::load('sales_saletypes');
		$this->registry->companies = Table::load('crm_companies');
		$this->registry->clients = Table::load('crm_clients');
		
		
		
		
		//echo "<br>Invoicetypecount - " . count($this->registry->invoicetypes);
		
		
		
		// Ladataan myyntilaskut ja niiden dimensiot
		$invoices = Table::load('sales_invoices', " WHERE Invoicedate>='" . $startdate . "' AND Invoicedate<='" . $enddate . "' AND State<4 ORDER BY Invoicedate");
		$this->registry->lastdate = $startdate;
		foreach($invoices as $index => $invoice) {
			if ($invoice->invoicedate > $this->registry->lastdate) $this->registry->lastdate = $invoice->invoicedate;
				
			$typefound = false;
				
			if ($invoice->clienttypeID == SalesModule::CLIENTTYPE_COMPANY) {
				$company = $this->registry->companies[$invoice->clientcompanyID];
				$invoice->description = $company->name;
				$typefound = true;
			}
				
			if ($invoice->clienttypeID == SalesModule::CLIENTTYPE_PERSON) {
				$client = $this->registry->clients[$invoice->clientpersonID];
				$invoice->description = $client->lastname . " " . $client->firstname;
				$typefound = true;
			}
		
			if ($invoice->clienttypeID == SalesModule::CLIENTTYPE_GENERALSALE) {
				$saletype = $this->registry->saletypes[$invoice->saletypeID];
				$invoice->description = $saletype->name;
				$typefound = true;
			}
				
			if ($typefound == false) {
				echo "<br>Myyntityyppiä ei löytynyt - invoiceID: " . $invoice->invoiceID;
			}
		}
		$this->registry->invoices = $invoices;
		
		
		$persons = Table::load('crm_clients');
		$contactpersons = array();
		$privateclients = array();
		foreach($persons as $index => $person) {
			$person->name = $person->lastname . " " . $person->firstname;
			if ($person->companyID == 0) {
				$contactpersons[$person->clientID] = $person;
				//echo "<br>Contactperson - " . $person->lastname;
			} else {
				$privateclients[$person->clientID] = $person;
				//echo "<br>Private client - " . $person->lastname;
			}
		}
		$this->registry->contactpersons = $contactpersons;
		$this->registry->privateclients = $privateclients;
		
		
		
		$this->registry->invoicestates = Collections::getInvoiceStates();
		
		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		$dimensionvalues = array();
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$dimensionvalues[$dimension->dimensionID] = Table::load('system_dimensionvalues',' WHERE DimensionID=' . $dimension->dimensionID);
			}
		}
		$this->registry->dimensionvalues = $dimensionvalues;
		
		
		$this->registry->template->show('sales/invoices','unpaidlist');
	}
	

	public function showinvoicesOriginalAction() {
	
		$comments = false;
	
		$oldperiodID = getOldModuleSessionVar('periodID');
		if ($comments) echo "<br>OldperiodID - " . $oldperiodID;
		$periodID = SalesModule::getBookkeepingPeriod();
		if ($comments) echo "<br>PeriodID - " . $periodID;
	
		$this->registry->periodID = $periodID;
		$this->registry->periods = Table::load('accounting_periods',' ORDER BY Startdate');
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) {
				if ($comments) echo "<br>Selected periodID found  - " . $periodID;
				$this->registry->period = $period;
				$startdate = $this->registry->period->startdate;
				$enddate = $this->registry->period->enddate;
			}
			$period->fullname = "Tilikausi " . $period->name;
		}
	
		$selectionID = 0;
		if ($oldperiodID != $periodID) {		// tilikautta on vaihdettu
			$selectionID = 0;
			setModuleSessionVar('selectionID',$selectionID);
			$startdate = $this->registry->period->startdate;
			setModuleSessionVar('periodstartdate',$startdate);
			$enddate = $this->registry->period->enddate;
			setModuleSessionVar('periodenddate',$enddate);
		} else {
			if ($comments) echo "<br>Period not changed";
			$selectionID = getModuleSessionVar('selectionID');
			if ($comments) echo "<br>SelectionID - " . $selectionID;
			$startdate = getModuleSessionVar('periodstartdate');
			if ($comments) echo "<br>startdate - " . $startdate;
			$enddate = getModuleSessionVar('periodenddate');
			if ($comments) echo "<br>enddate - " . $enddate;
		}
	
	
		$selection = SalesModule::generatePeriodTimescales($this->registry->period, $selectionID);
		$this->registry->selection = $selection;
		if ($selectionID > 0) {
			$currentselect = $selection[$selectionID];
			$startdate = $currentselect->startsql;
			$enddate = $currentselect->endsql;
		}
	
		if ($comments) echo "<br>dates - " . $startdate . " - " . $enddate;
		$this->registry->selectionID = $selectionID;
	
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
	
	
		$this->registry->invoicetypes = SalesModule::getSalesInvoiceTypes();
		$this->registry->clienttypes = SalesModule::getSalesInvoiceClientTypes();
	
	
		$this->registry->saletypes = Table::load('sales_saletypes');
		$this->registry->companies = Table::load('crm_companies');
		$this->registry->clients = Table::load('crm_clients');
	
	
	
	
		//echo "<br>Invoicetypecount - " . count($this->registry->invoicetypes);
	
	
	
		// Ladataan myyntilaskut ja niiden dimensiot
		$invoices = Table::load('sales_invoices', " WHERE Invoicedate>='" . $startdate . "' AND Invoicedate<='" . $enddate . "' ORDER BY Invoicedate");
		$this->registry->lastdate = $startdate;
		foreach($invoices as $index => $invoice) {
			if ($invoice->invoicedate > $this->registry->lastdate) $this->registry->lastdate = $invoice->invoicedate;
				
			$typefound = false;
				
			if ($invoice->clienttypeID == SalesModule::CLIENTTYPE_COMPANY) {
				$company = $this->registry->companies[$invoice->clientcompanyID];
				$invoice->description = $company->name;
				$typefound = true;
			}
				
			if ($invoice->clienttypeID == SalesModule::CLIENTTYPE_PERSON) {
				$client = $this->registry->clients[$invoice->clientpersonID];
				$invoice->description = $client->lastname . " " . $client->firstname;
				$typefound = true;
			}
	
			if ($invoice->clienttypeID == SalesModule::CLIENTTYPE_GENERALSALE) {
				$saletype = $this->registry->saletypes[$invoice->saletypeID];
				$invoice->description = $saletype->name;
				$typefound = true;
			}
				
			if ($typefound == false) {
				echo "<br>Myyntityyppiä ei löytynyt - invoiceID: " . $invoice->invoiceID;
			}
		}
		$this->registry->invoices = $invoices;
	
	
		$persons = Table::load('crm_clients');
		$contactpersons = array();
		$privateclients = array();
		foreach($persons as $index => $person) {
			$person->name = $person->lastname . " " . $person->firstname;
			if ($person->companyID == 0) {
				$contactpersons[$person->clientID] = $person;
				//echo "<br>Contactperson - " . $person->lastname;
			} else {
				$privateclients[$person->clientID] = $person;
				//echo "<br>Private client - " . $person->lastname;
			}
		}
		$this->registry->contactpersons = $contactpersons;
		$this->registry->privateclients = $privateclients;
	
	
	
		$this->registry->invoicestates = Collections::getInvoiceStates();
	
		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		$dimensionvalues = array();
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$dimensionvalues[$dimension->dimensionID] = Table::load('system_dimensionvalues',' WHERE DimensionID=' . $dimension->dimensionID);
			}
		}
		$this->registry->dimensionvalues = $dimensionvalues;
	
	
		$this->registry->template->show('sales/invoices','invoices');
	}
	
	
	
	public function showinvoiceAction() {
	
		$comments = false;
		$invoiceID = $_GET['id'];
		updateActionPath("Myyntilasku");
		
		$this->registry->companies = Table::load('crm_companies');			// tätä tarvitaan vasta muokkauksessa, voidaan ladata vasta sitten
		$invoice = Table::loadRow('sales_invoices',$invoiceID);
		
		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		$this->registry->invoice = $invoice;
		
		if (count($this->registry->dimensions) > 0) {
			$dimensionvalues = Table::load('system_dimensionvalues');
			$sorteddimensionvalues = array();
			foreach($dimensionvalues as $index => $dimensionvalue) {
				if (!isset($sorteddimensionvalues[$dimensionvalue->dimensionID])) $sorteddimensionvalues[$dimensionvalue->dimensionID] = array();
				$sorteddimensionvalues[$dimensionvalue->dimensionID][$dimensionvalue->dimensionvalueID] = $dimensionvalue;
			}
			$this->registry->dimensionvalues = $sorteddimensionvalues;
		} else {
			$this->registry->dimensionvalues = array();
		}
		
		if ($this->registry->invoice->receiptID > 0) {
			$this->registry->receipt = Table::loadRow('accounting_receipts',$this->registry->invoice->receiptID);
		}
		
		$this->registry->invoicerows = Table::load('sales_invoicerows'," WHERE InvoiceID=" . $invoiceID);
		$this->registry->invoicetypes = SalesModule::getSalesInvoiceTypes();
		$this->registry->clienttypes = SalesModule::getSalesInvoiceClientTypes();
		$this->registry->vatcodes = Table::load('accounting_vatreportcodes');
		$this->registry->products = Table::load('sales_products');
		$this->registry->invoicestates = Collections::getInvoiceStates();
		$this->registry->accounts = Table::load('accounting_accounts');
		$this->registry->vats = Table::load('system_vats');
		$this->registry->saletypes = Table::load('sales_saletypes');
		
		$persons = Table::load('crm_clients');
		$contactpersons = array();
		$privateclients = array();
		foreach($persons as $index => $person) {
			$person->name = $person->lastname . " " . $person->firstname;
			if ($person->companyID == 0) {
				$privateclients[$person->clientID] = $person;
			} else {
				$contactpersons[$person->clientID] = $person;
			}
		}
		$this->registry->contactpersons = $contactpersons;
		$this->registry->privateclients = $privateclients;
		
		
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		
		if ($this->registry->invoice->state == 0) {		// == 0
				
			//echo "<br>CustomerID - " . $this->registry->invoice->clientcompanyID;
			if ($this->registry->invoice->clientcompanyID > 0) {
				$this->registry->entries = $this->createEntriesFromInvoice($this->registry->invoice, $this->registry->invoicerows, $this->registry->companies[$this->registry->invoice->clientcompanyID], $this->registry->products, $this->registry->accounts, $this->registry->vats);
				foreach($this->registry->entries as $index => $entry) {
					if ($entry->amount < 0) {
						$entry->debet = 0;
						$entry->credit = -1 * $entry->amount;
					} else {
						$entry->debet = $entry->amount;
						$entry->credit = 0;
					}
				}
			} else {
				// Kun asiakasyritystä ei ole asetettu, tulkitaan että kyseessä on kotimaan myynti
				$this->registry->entries = $this->createEntriesFromInvoice($this->registry->invoice, $this->registry->invoicerows, null, $this->registry->products, $this->registry->accounts, $this->registry->vats);
				foreach($this->registry->entries as $index => $entry) {
					if ($entry->amount < 0) {
						$entry->debet = 0;
						$entry->credit = -1 * $entry->amount;
					} else {
						$entry->debet = $entry->amount;
						$entry->credit = 0;
					}
				}			
			}
			
		} else {
			
			//$this->registry->receipt = Table::loadRow("accounting_receipts"," InvoiceID=" . $invoiceID);
			//$this->registry->invoice->receiptID = $this->registry->receipt->receiptID;
			if ($this->registry->invoice->receiptID > 0) {
				$this->registry->entries = Table::load('accounting_entries', "WHERE ReceiptID=" . $this->registry->invoice->receiptID);
				
				foreach($this->registry->entries as $index => $entry) {
					if ($entry->amount < 0) {
						$entry->debet = 0;
						$entry->credit = -1 * $entry->amount;
					} else {
						$entry->debet = $entry->amount;
						$entry->credit = 0;
					}
					//if ($entry->linktypeID == 1) $entry->state = "Avoin";
					//if ($entry->linktypeID == 1) $entry->state = "Avoin";
					//if ($entry->linktypeID == 3) $entry->state = "Osittaismaksu";
					//if ($entry->linktypeID == 4) $entry->state = "Maksettu";
				}
			}
			
		}
	
	
		$this->registry->template->show('sales/invoices','invoice');
	}
	
	
	
	
	public function showsalesjournalAction() {
		
		$comments = false;
		
		$oldperiodID = getOldModuleSessionVar('periodID');
		if ($comments) echo "<br>OldperiodID - " . $oldperiodID;
		$periodID = SalesModule::getBookkeepingPeriod();
		if ($comments) echo "<br>PeriodID - " . $periodID;
		
		$this->registry->periodID = $periodID;
		$this->registry->periods = Table::load('accounting_periods',' ORDER BY Startdate');
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) $this->registry->period = $period;
			$period->fullname = "Tilikausi " . $period->name;
		}
		
		$selectionID = 0;
		if ($oldperiodID != $periodID) {		// tilikautta on vaihdettu
			$selectionID = 0;
			setModuleSessionVar('selectionID',$selectionID);
			$startdate = $this->registry->period->startdate;
			setModuleSessionVar('periodstartdate',$startdate);
			$enddate = $this->registry->period->enddate;
			setModuleSessionVar('periodenddate',$enddate);
		} else {
			if ($comments) echo "<br>Period not changed";
			$selectionID = getModuleSessionVar('selectionID');
			if ($comments) echo "<br>SelectionID - " . $selectionID;
			$startdate = getModuleSessionVar('periodstartdate');
			$enddate = getModuleSessionVar('periodenddate');
		}
		
		$this->registry->companies = Table::load('crm_companies');
		$this->registry->invoices = Table::load('sales_invoices', " WHERE Invoicedate>='" . $startdate . "' AND Invoicedate<='" . $enddate . "' ORDER BY Invoicedate");
		$this->registry->invoicestates = Collections::getInvoiceStates();
		
		$this->registry->template->show('sales/invoices','invoices');
	}
	
	
	

	public function insertinvoiceAction() {
	
		$comments = false;
	
		$clienttypeID = $_GET['clienttypeID'];
	
		$values = array();
		if ($clienttypeID == 1) {		// Yritysmyynti
			$companyID = $_GET['clientcompanyID'];
			$company = Table::loadRow("crm_companies", $companyID);
			$values['ClientcompanyID'] = $companyID;
			$values['Clientcompanyname'] = $company->name;
			$values['Clientname'] = $company->name;
			$contactpersonID = $_GET['contactpersonID'];
			echo "<br>contactpersonID - " . $contactpersonID;
			if ($contactpersonID == null) {
				$values['ContactpersonID'] = 0;
				$values['Contactpersonname'] = "";
			} else {
				$contactperson = Table::loadRow("crm_clients", $contactpersonID);
				$values['ContactpersonID'] = $contactpersonID;
				$values['Contactpersonname'] = $contactperson->lastname . " " . $contactperson->firstname;
			}
	
			// TODO: hae yrityksen tiedot
			// TODO: Lisää uusi kenttä clientname, tämä näkyy laskutuslistalla
			// TODO: Aseta myös yrityksen laskutustiedot (ja pitää valita jos on useampia)
		}
		if ($clienttypeID == 2) {		// yksityisasiakas myynti
			$clientpersonID = $_GET['clientpersonID'];
			if ($clientpersonID == null) {
				$values['ClientpersonID'] = 0;
				$values['Clientname'] = "";
			} else {
				$clientperson = Table::loadRow("crm_clients", $clientpersonID);
				$values['ClientpersonID'] = $clientpersonID;
				$values['Clientname'] = $clientperson->lastname . " " . $clientperson->firstname;
			}
		}
		if ($clienttypeID == 3) {		// Yleinen myynti
			$saletypeID = $_GET['saletypeID'];
			$values['SaletypeID'] = $saletypeID;
		}
	
		$this->registry->dimensions = Table::load('system_dimensions','WHERE Usedinsales=1');
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$variable = "dimension" . $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$columname = "Dimension" . $dimension->dimensionID;
					$values[$columname] = $_GET[$variable];
				}
			}
		}
	
		$invoicedate = $_GET['invoicedate'];
		$values['InvoicetypeID'] = $_GET['invoicetypeID'];
		$values['ClienttypeID'] = $clienttypeID;
		$values['Invoicedate'] = $invoicedate;
		$values['Duedate'] = $invoicedate;
		$values['State'] = 0;
		$invoiceID = Table::addRow("sales_invoices", $values, $comments);
	
		if (!$comments) redirecttotal('sales/invoices/showinvoice&id=' . $invoiceID, null);
	}
	
	
	
	
	
	
	public function insertinvoicerowAction() {
	
		$comments = false;
		if ($comments) setUrlParamComments(true);
	
		$invoiceID = getIntParam('invoiceID');
		$vatID = getIntParam('vatID');
		$productID = getIntParam('productID');
		$rowGrossAmount = getFloatParam('grossamount');
		$rowNetaAount = getFloatParam('netamount');
		$unitPrice = getFloatParam('unitprice');
		$unitAmount = getFloatParam('unitamount');
		$product = Table::loadRow('sales_products',$productID);
		$unitname = "";
		if ($product->unitID != 0) {
			$unit = Table::loadRow('system_units',$product->unitID);
			$unitname = $unit->sign;
		}
		$vatpercent = 0;
	
	
		$dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
	
		// Lisätään receiptID, mikäli sitä ei ole
		$invoice = Table::loadRow('sales_invoices',$invoiceID);
		$receiptID = 0;
		if ($invoice->receiptID == 0) {
				
			$receiptsetID =  Settings::getSetting('accounting_salesreceiptsetID', 0);
			$receiptnumber = $this->getNextReceiptNumber($receiptsetID);
	
			$values = array();
			$this->registry->invoice = $invoice;
			$values['Receiptdate'] = $invoice->invoicedate;
			$values['Receiptnumber'] = $receiptnumber;
			$values['ReceiptsetID'] = $receiptsetID;
			$values['Explanation'] = "Myyntilasku " . $invoiceID;		// TODO: teksti resurssiteksteistä?
			//$values['ReceiverID'] = 0;	// TODO pitäisi asettaa asiakasyritys
			//$values['CostpoolID'] = 0;	// TODO Pitäisi asettaa jokin sopiva kustannuspaikka, myynti? Tämä on lähinnä ostoja varten
			//$values['Grossamount'] = $rowGrossAmount;		// TODO: Tarvitaanko näitä mihinkään? Päivitys työlästä, ainakin uusi rivi pitää ottaa mukaan
			//$values['Netamount'] = $rowNetaAount;			// TODO: Tarvitaanko näitä mihinkään? Päivitys työlästä, ainakin uusi rivi pitää ottaa mukaan
			//$values['Accounted'] = 0;	// TODO: Tämä ehkä pitää päivittää entryistä
			$values['Paymentstatus'] = 0;
			$values['InvoiceID'] = $invoice->invoiceID;
			$receiptID = Table::addRow("accounting_receipts", $values, $comments);
	
			$values = array();
			$values['ReceiptID'] = $receiptID;
			Table::updateRow("sales_invoices", $values, $invoiceID);
		} else {
			$receiptID = $invoice->receiptID;
		}
	
	
	
	
		//$invoicerows = Table::load('sales_invoicerows'," WHERE InvoiceID=" . $invoiceID);
		//$this->updateinvoicesums($invoiceID, $invoicerows);
	
		// Ei luoda vientejä
		//  -- Jos lasku on avoin, luodaan näytettäessä
		//  -- Jos lasku on ei avoin, haetaan kannasta
		//  -- Viennit viedään kantaan laskua siirrettäessä avoimesta eteenpäin
		// TODO: Lisätään samantien vientirivit myös...
	
	
		// Jos kyseessä on ulkomaan myynti? Suomessa?
		//$country = Table::loadRow('system_countries',$company->countryID, $comments);
	
	
		//if ($comments) echo "<br>Invoice - vatID=" . $invoicerow->vatID . ", vatpercent=" . $invoicerow->vatpercent . ", netamount:" . $invoicerow->netamount . ", gross:" . $invoicerow->grossamount . "";
		//if ($comments) echo "<br>Country - countryID=" . $company->countryID . ", name=" . $country->name . ", countrytype:" . $country->countrytype;
		$vatentryID = 0;
		if ($vatID > 0) {
			$vat = Table::loadRow('system_vats',$vatID);
			if ($vat->percent > 0) {
					
					
				// TODO: tämä on hardkoodattu countrytype = 1, eli kotimaan myynti. Toistaiseksi näin kunnes korjataan asiakastyypistä haku
				$country = new Row();
				$country->name = "Toteutus kesken";
				$country->countrytype = 1;
					
				$vatcodeID = $this->deduceVatCodeID($country, $product, $vat, $comments);
				if ($comments) echo "<br>Vatcode found - " . $vatcodeID;
				$vatpayableaccountID = Settings::getSetting('accounting_vatpayablesaccountID');
				if ($comments) echo "<br>alv tilin lisäys - " . $vatpayableaccountID;
					
				$values = array();
				$values['ReceiptID'] = $receiptID;
				$values['AccountID'] = $vatpayableaccountID;
				$values['Entrydate'] = $invoice->invoicedate;
				$values['Amount'] = -1 * ($rowGrossAmount - $rowNetaAount);
				$values['VatcodeID'] = $vatcodeID;
				if (count($dimensions) > 0) {
					foreach($dimensions as $index => $dimension) {
						$variable = 'dimension'.+ $dimension->dimensionID;
						if (isset($_GET[$variable])) {
							$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
						} else {
							$values['Dimension'. $dimension->dimensionID] = $invoice->$variable;
						}
					}
				}
				$vatentryID = Table::addRow("accounting_entries", $values, $comments);
				$vatpercent = $vat->percent;
	
			} else {
				if ($rowGrossAmount != $rowNetaAount) {
					echo "<br>Vatcode nolla: rowGrossAmount != rowNetaAount .... " . $rowGrossAmount . " vs. " . $rowNetaAount;
					exit;
				}
			}
		} else {
			// tsekataanko onko net ja gross samankokoiset, jotain vikaa on jos näin on?
			if ($rowGrossAmount != $rowNetaAount) {
				echo "<br>Vatcode nolla: rowGrossAmount != rowNetaAount .... " . $rowGrossAmount . " vs. " . $rowNetaAount;
				exit;
			}
		}
	
	
		// Myyntivienti - tilinumero napataan tuotteen alta
		$values = array();
		$values['ReceiptID'] = $receiptID;
		$values['AccountID'] =	$product->accountID;
		$values['Amount'] = -1 * $rowNetaAount;
		$values['Entrydate'] = $invoice->invoicedate;
		$values['VatcodeID'] = 0;
		if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
				$variable = 'dimension' . $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
				} else {
					$values['Dimension'. $dimension->dimensionID] = $invoice->$variable;
				}
			}
		}
		$salesaccountID = Table::addRow("accounting_entries", $values, $comments);
	
	
		// Myyntisaamisvienti - tili haetaan yleis-asetuksista
		$recievablescountID = Settings::getSetting('accounting_recievablesaccountID');		// Tähän vaikuttaa ehkä laskun tyyppi?
		$values = array();
		$values['ReceiptID'] = $receiptID;
		$values['AccountID'] =	$recievablescountID;
		$values['Amount'] = $rowGrossAmount;
		$values['Entrydate'] = $invoice->invoicedate;
		$values['VatcodeID'] = 0;
		if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
				$variable = 'dimension' . $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
				} else {
					$values['Dimension'. $dimension->dimensionID] = $invoice->$variable;
				}
			}
		}
		$receivablesentryID = Table::addRow("accounting_entries", $values, $comments);
	
	
		// Lisätään sales_invoicerows
		$values = array();
		$values['ProductID'] = $productID;
		$values['Productname'] = $product->name;
		$values['UnitID'] = $product->unitID;
		$values['Unit'] = $unitname;
		$values['VatID'] = $vatID;
		$values['Vatpercent'] = $vatpercent;
		$values['InvoiceID'] = $invoiceID;
		$values['Unitprice'] = $unitPrice;
		$values['Unitamount'] = $unitAmount;
		$values['Grossamount'] = $rowGrossAmount;
		$values['Netamount'] = $rowNetaAount;
		$values['AccountID'] = $product->accountID;
		$values['Vatamount'] = $rowGrossAmount - $rowNetaAount;
		$values['SalesentryID'] = $salesaccountID;
		$values['ReceivablesentryID'] = $receivablesentryID;
		$values['VatentryID'] = $vatentryID;
		if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
				$variable = 'dimension' . $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
				} else {
					$values['Dimension'. $dimension->dimensionID] = $invoice->$variable;
				}
			}
		}
		$invoicerowID = Table::addRow("sales_invoicerows", $values, $comments);
	
		$invoicerows = Table::load('sales_invoicerows'," WHERE InvoiceID=" . $invoiceID);
		$this->updateinvoicesums($invoiceID, $invoicerows);
		
		if (!$comments) redirecttotal('sales/invoices/showinvoice&id=' . $invoiceID, null);
	}
	
	
	

	public function insertentryAction() {
	
		$comments = true;
	
		$invoiceID = $_GET['invoiceID'];
		$receiptID = $_GET['receiptID'];
		$accountID = $_GET['accountID'];
		$grossamount = floatval(str_replace(",",".",$_GET['amount']));
	
		$receipt = Table::loadRow('accounting_receipts',$receiptID);
		$account = Table::loadRow('accounting_accounts',$accountID);
		
		if (($account->accounttypeID == 2) || ($account->accounttypeID == 4)) {
			$grossamount = $grossamount * -1;				
		}
		
		if ($comments) echo "<br><br>Tehdään kulutilivienti";
		$values = array();
		$values['ReceiptID'] = $receiptID;
		$values['AccountID'] = $accountID;
		$values['Entrydate'] = $receipt->receiptdate;
		$values['Amount'] = $grossamount;
		if ($comments) echo "<br>";
		if ($comments) var_dump($values);
		$success = Table::addRow("accounting_entries", $values, $comments);
		
		if (!$comments) redirecttotal('sales/invoices/showinvoice&id=' . $invoiceID, null);
			
	}
	
	
	
	
	
	
	private function createEntriesFromInvoice($invoice, $invoicerows, $company, $products, $accounts, $vats) {
		
		$comments = false;
		if ($comments) echo "<br>Company - " . $company->companyID . " - " . $company->name . " - " . $company->countryID;		
		
		$vatpayableaccountID = Settings::getSetting('accounting_vatpayablesaccountID');
		$recievablescountID = Settings::getSetting('accounting_recievablesaccountID');
		if ($company != null) {
			$country = Table::loadRow('system_countries',$company->countryID, $comments);
		} else {
			$country = null;
		}
		
		$entries = array();
		
		foreach($invoicerows as $index => $invoicerow) {
			
			if ($invoicerow->productID != 0) {
				$product = $products[$invoicerow->productID];
					
				if ($comments) echo "<br>Invoice - vatID=" . $invoicerow->vatID . ", vatpercent=" . $invoicerow->vatpercent . ", netamount:" . $invoicerow->netamount . ", gross:" . $invoicerow->grossamount . "";
				if ($comments) echo "<br>Country - countryID=" . $company->countryID . ", name=" . $country->name . ", countrytype:" . $country->countrytype;
				
				$vatcodeID = 0;
				$alvvatcodeID = '000';
				
				if (($country == null) || ($country->countrytype == 1)) {
					
					if ($comments) echo "<br>Kotimaan myynti";
					
					$vat = $vats[$invoicerow->vatID];
					if ($vat->percent > 0) {
						$entry = new Row();
						$entry->entryID = 0;
						$entry->entrydate = $invoice->invoicedate;
						$entry->accountID = $vatpayableaccountID;
						$entry->amount = -1 * ($invoicerow->grossamount - $invoicerow->netamount);
						$entry->vatcodeID = $vat->vatcodeID;
						$entries[] = $entry;
					} else {
						$vatcodeID = $vat->vatcodeID;
					}
				} else {

					if ($country->countrytype == 3) {		// EU ulkopuolinen myynti
						if ($comments) echo "<br>Myynti EU ulkopuolelle";
						$vatcodeID = 8;	// M00
					
					} if ($country->countrytype == 2) {		// EU myynti
						if ($comments) echo "<br>Myynti EU:n sisälle";
						if ($product->service == 1) {
							$vatcodeID = 11; // MPEU;
						} else {
							$vatcodeID = 10;  //"MTEU";
						}
							
					} else {
						if ($comments) echo "<br>Unknown countrycode";
					}
					
				}
				
				$product = $products[$invoicerow->productID];
				$account = $accounts[$product->productID];
					
				$entry = new Row();
				$entry->entryID = 0;
				$entry->entrydate = $invoice->invoicedate;
				$entry->accountID = $product->accountID;
				$entry->amount = -1 * $invoicerow->netamount;
				$entry->vatcodeID = $vatcodeID;
				$entries[] = $entry;
					
				$entry = new Row();
				$entry->entryID = 0;
				$entry->entrydate = $invoice->invoicedate;
				$entry->accountID = $recievablescountID;
				$entry->amount = $invoicerow->grossamount;
				$entry->vatcodeID = 0;
				$entries[] = $entry;
			}
		}
		
		
		// Ainakin myyntisaamiset tilin rivi pitää yhdistää...
		$summedentries = array();
		foreach($entries as $index => $entry) {
			$key = $entry->accountID . "-" . $entry->vatcodeID;
			if (isset($summedentries[$key])) {
				$sumentry = $summedentries[$key];
				$sumentry->amount = $sumentry->amount + $entry->amount;
			} else {
				$summedentries[$key] = $entry;
			}
		}
		
		return $summedentries;		
	}
	

	
	

	// Käänteinen alv-tyypin käsittely puuttuu
	// voinee muuttaa staticiksi
	private function deduceVatCodeID($country, $product, $vat, $comments = false) {
		
		$vatcodeID = 0;
		if ($country->countrytype == 3) {		// EU ulkopuolinen myynti
			if ($comments) echo "<br>Myynti EU ulkopuolelle";
			$vatcodeID = 8;	// M00
		
		} if ($country->countrytype == 2) {		// EU myynti
			if ($comments) echo "<br>Myynti EU:n sisälle";
			if ($product->service == 1) {
				$vatcodeID = 11; // MPEU;
			} else {
				$vatcodeID = 10;  //"MTEU";
			}
		} elseif ($country->countrytype == 1) {	// kotimaan myynti
			if ($comments) echo "<br>Kotimaan myynti";
			$vatcodeID = $vat->vatcodeID;
		} else {
			echo "<br>Unknown countrycode in vat deduction";
			$vatcodeID = 0;   // Ei alv-verollinen tili
			//exit;
		}
		return $vatcodeID;
	}
	
	
	


	/**
	 *
	 *
	 */
	public function updateinvoiceAction() {
	
		$comments = false;
	
		$invoiceID = $_GET['id'];
		$invoice = Table::loadRow('sales_invoices', $invoiceID);
		$invoicedate = $_GET['invoicedate'];
	
	
		$values = array();
		$values['InvoicetypeID'] = $_GET['invoicetypeID'];
		$clienttypeID = $_GET['clienttypeID'];
		$values['ClienttypeID'] = $_GET['clienttypeID'];
		if ($clienttypeID == SalesModule::CLIENTTYPE_COMPANY) {
			$values['ClientcompanyID'] = $_GET['clientcompanyID'];
			$values['ContactpersonID'] = $_GET['contactpersonID'];
			$values['ClientpersonID'] = 0;
			$values['SaletypeID'] = 0;
		}
		if ($clienttypeID == SalesModule::CLIENTTYPE_PERSON) {
			$values['ClientcompanyID'] = 0;
			$values['ContactpersonID'] = 0;
			$values['ClientpersonID'] = $_GET['clientpersonID'];
			$values['SaletypeID'] = 0;
		}
		if ($clienttypeID == SalesModule::CLIENTTYPE_GENERALSALE) {
			$values['ClientcompanyID'] = 0;
			$values['ContactpersonID'] = 0;
			$values['ClientpersonID'] = 0;
			$values['SaletypeID'] = $_GET['saletypeID'];
		}
	
		$values['Invoicedate'] = $_GET['invoicedate'];
		$values['Duedate'] = $_GET['duedate'];
		$values['Referencenumber'] = $_GET['referencenumber'];
	
		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$variable = "dimension" . $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$columname = "Dimension" . $dimension->dimensionID;
						
					if (isset($_GET[$variable])) {
						if ($invoice->$variable != $_GET[$variable]) {
							if ($comments) echo "<br>Dimensio muuttunut, päivitetään kaikkiin riveihin ja entryihin";
							$this->updateInvoiceDimension($invoice, $dimension, $_GET[$variable]);
							$values[$columname] = $_GET[$variable];
						}
					} else {
						echo "<br>Dimensiota ei tullut parametrina";
					}
						
				}
			}
		}
		$success = Table::updateRow("sales_invoices", $values, $invoiceID);
	
		if ($invoicedate != $invoice->invoicedate) {
			if ($comments) echo "<br>Päivämäärä muuttunut, päivitetään kaikki";
			$this->updateInvoiceDate($invoice, $invoicedate);
		}
	
	
		// TODO: Jos päivämääärä muuttuu, niin pitää päivittää kaikki entries, receipts ja entrydimensions
		// Tätä varten pitää hakea invoice
	
		// Jos dimensioita ei ole aiemmin asetettu, niin päivitetään kaikkien rivien dimensiot
		$dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
	
		/*
			if (count($dimensions) > 0) {
				
			$invoicedimensionvalues = Table::load('sales_invoicedimensions',' WHERE InvoiceID=' . $invoiceID);
				
			foreach($dimensions as $index => $dimension) {
			$variable = "dimension" . $dimension->dimensionID;
	
			if (isset($_GET[$variable])) {
			echo "<br>Dimension found - " . $dimension->name . " - value:" . $_GET[$variable];
			$dimensionvalueID = $_GET[$variable];
			$found = false;
			foreach($invoicedimensionvalues as $index2 => $invoicedimensionvalue) {
			if ($invoicedimensionvalue->dimensionId == $dimension->dimensionID) {
			$found = true;
			}
			}
				
			if ($found == false) {		// Dimensiota ei ole asetettu laskulle, asetetaan se kaikkiin riveihin
			if ($dimensionvalueID > 0) {
			$this->setDimensionValueForInvoice($invoiceID, $invoicedate, $dimension->dimensionID, $_GET[$variable],$comments);
			}
			}
			}
			}
			}
			*/
		if (!$comments) redirecttotal('sales/invoices/showinvoice&id=' . $invoiceID, null);
	}
	
	
	
	
	/**
	 * 
	 * Tämä toiminta on lähes sama kuin insertentryActionissa, nyt vain updatetetaan lisäyksen sijaan
	 * 
	 */
	public function updateinvoicerowAction() {
	
		$comments = true;
		if ($comments) setUrlParamComments(true);
		
		$invoicerowID = getIntParam('id');
		$invoiceID = getIntParam('invoiceID');
		$productID = getIntParam('productID');
		$vatID = getIntParam('vatID');
		$unitPrice = getFloatParam('unitprice');
		$unitAmount = getFloatParam('unitamount');
		$rowNetaAount = getFloatParam('netamount');
		$rowGrossAmount = getFloatParam('grossamount');
		
		$product = Table::loadRow('sales_products',$productID);
		$unitname = "";
		if ($product->unitID != 0) {
			$unit = Table::loadRow('system_units',$product->unitID);
			$unitname = $unit->sign;
		}
		$vatpercent = 0;
		
		$dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		$invoice = Table::loadRow('sales_invoices',$invoiceID);
		$invoicerow = Table::loadRow('sales_invoicerows',$invoicerowID);
		$receiptID = $invoice->receiptID;
		$vatentryID = $invoicerow->vatentryID;
		
		
		if ($vatID > 0) {
			$vat = Table::loadRow('system_vats',$vatID);
			if ($vat->percent > 0) {
					
					
				// TODO: tämä on hardkoodattu countrytype = 1, eli kotimaan myynti. Toistaiseksi näin kunnes korjataan asiakastyypistä haku
				$country = new Row();
				$country->name = "Toteutus kesken";
				$country->countrytype = 1;
					
				$vatcodeID = $this->deduceVatCodeID($country, $product, $vat, $comments);
				if ($comments) echo "<br>Vatcode found - " . $vatcodeID;
				$vatpayableaccountID = Settings::getSetting('accounting_vatpayablesaccountID');
				if ($comments) echo "<br>alv tilin lisäys - " . $vatpayableaccountID;
					
				
				$values = array();
				$values['ReceiptID'] = $receiptID;
				$values['AccountID'] = $vatpayableaccountID;
				$values['Entrydate'] = $invoice->invoicedate;
				$values['Amount'] = -1 * ($rowGrossAmount - $rowNetaAount);
				$values['VatcodeID'] = $vatcodeID;
				if (count($dimensions) > 0) {
					foreach($dimensions as $index => $dimension) {
						$variable = 'dimension'.+ $dimension->dimensionID;
						if (isset($_GET[$variable])) {
							$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
						} else {
							$values['Dimension'. $dimension->dimensionID] = $invoice->$variable;
						}
					}
				}
				
				// Jos aiempi rivi oli alv-nollaa, niin voi olla, että alv-enryä ei ole aiemmin, lisätään jos ei ole
				if ($invoicerow->vatentryID == 0) {
					$vatentryID = Table::addRow("accounting_entries", $values, $comments);
				} else {
					echo "<br>Update vatentryID";
					Table::updateRow("accounting_entries", $values, $invoicerow->vatentryID);
				}
				$vatpercent = $vat->percent;
		
			} else {
				if ($rowGrossAmount != $rowNetaAount) {
					echo "<br>Vatcode nolla: rowGrossAmount != rowNetaAount .... " . $rowGrossAmount . " vs. " . $rowNetaAount;
					exit;
				}
			}
		} else {
			// tsekataanko onko net ja gross samankokoiset, jotain vikaa on jos näin on?
			if ($rowGrossAmount != $rowNetaAount) {
				echo "<br>Vatcode nolla: rowGrossAmount != rowNetaAount .... " . $rowGrossAmount . " vs. " . $rowNetaAount;
				exit;
			}
		}
		
		

		if (($invoicerow->salesentryID === null) || ($invoicerow->salesentryID == 0)) {
			echo "<br>Sales entry pitäisi löytyä jo ennestään, myyntivienti - " . $invoicerow->salesentryID;
			exit;
		}
		$values = array();
		$values['ReceiptID'] = $receiptID;
		$values['AccountID'] =	$product->accountID;
		$values['Amount'] = -1 * $rowNetaAount;
		$values['Entrydate'] = $invoice->invoicedate;
		$values['VatcodeID'] = 0;
		if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
				$variable = 'dimension' . $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
				} else {
					$values['Dimension'. $dimension->dimensionID] = $invoice->$variable;
				}
			}
		}
		echo "<br>Update salesentryID - " . $invoicerow->salesentryID;
		Table::updateRow("accounting_entries", $values, $invoicerow->salesentryID);
		
		
		// Myyntisaamisvienti - tili haetaan yleis-asetuksista
		if (($invoicerow->receivablesentryID === null) || ($invoicerow->receivablesentryID == 0)) {
			echo "<br>Sales entry pitäisi löytyä jo ennestään, myyntisaamisvienti";
			exit;
		}
		$recievablescountID = Settings::getSetting('accounting_recievablesaccountID');		// Tähän vaikuttaa ehkä laskun tyyppi?
		$values = array();
		$values['ReceiptID'] = $receiptID;
		$values['AccountID'] =	$recievablescountID;
		$values['Amount'] = $rowGrossAmount;
		$values['Entrydate'] = $invoice->invoicedate;
		$values['VatcodeID'] = 0;
		if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
				$variable = 'dimension' . $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
				} else {
					$values['Dimension'. $dimension->dimensionID] = $invoice->$variable;
				}
			}
		}
		echo "<br>Update receivablesentryID";
		Table::updateRow("accounting_entries", $values, $invoicerow->receivablesentryID);
		
		
		
		// päivitetään sales_invoicerows
		$values = array();
		$values['ProductID'] = $productID;
		$values['Productname'] = $product->name;
		$values['UnitID'] = $product->unitID;
		$values['Unit'] = $unitname;
		$values['VatID'] = $vatID;
		$values['Vatpercent'] = $vatpercent;
		$values['InvoiceID'] = $invoiceID;
		$values['Unitprice'] = $unitPrice;
		$values['Unitamount'] = $unitAmount;
		$values['Grossamount'] = $rowGrossAmount;
		$values['Netamount'] = $rowNetaAount;
		$values['AccountID'] = $product->accountID;
		$values['Vatamount'] = $rowGrossAmount - $rowNetaAount;
		$values['SalesentryID'] = $salesaccountID;
		$values['ReceivableentryID'] = $receivablesentryID;
		$values['VatentryID'] = $vatentryID;
		if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
				$variable = 'dimension' . $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
				} else {
					$values['Dimension'. $dimension->dimensionID] = $invoice->$variable;
				}
			}
		}
		Table::updateRow("sales_invoicerows", $values, $invoicerowID);
	
		$invoicerows = Table::load('sales_invoicerows'," WHERE InvoiceID=" . $invoiceID);
		$this->updateinvoicesums($invoiceID, $invoicerows);
		
		redirecttotal('sales/invoices/showinvoice&id=' . $invoiceID, null);
	}
	
	


	public function updateentryAction() {
	
		$invoiceID = $_GET['invoiceID'];
		$receiptID = $_GET['receiptID'];
		$entryID = $_GET['entryID'];
		
		$values = array();
		$values['Entrydate'] = $_GET['entrydate'];
		$values['AccountID'] = $_GET['accountID'];
		$values['VatcodeID'] = $_GET['vatcodeID'];
	
	
		$debet = str_replace(",",".",$_GET['debet']);
		$credit = str_replace(",",".",$_GET['credit']);
	
		$amount = 0;
		if ($debet > $credit) $amount = $debet;
		else $amount = -1 * $credit;
	
		$values['Amount'] = $amount;
	
		$success = Table::updateRow('accounting_entries', $values, $entryID, true);
		//$this->updateReceiptAccounted($receiptID, $comments);
	
		redirecttotal('sales/invoices/showinvoice&id=' . $invoiceID,null);
	}
	
	
	
	
	// Päivittää laskun brutto ja netto kentät
	//
	private function updateinvoicesums($invoiceID, $invoicerows) {
			
		$grossamount = 0;
		$netamount = 0;
		foreach($invoicerows as $index => $invoicerow) {
			$grossamount = $grossamount + $invoicerow->grossamount;
			$netamount = $netamount + $invoicerow->netamount;
		}
		$values = array();
		$values['Grossamount'] = $grossamount;
		$values['Netamount'] = $netamount;
		$values['Unpaidamount'] = $grossamount;		// TODO: Oikeastaan vain silloin kun invoicestate on 0? Tai kelataan entryt läpi
		$success = Table::updateRow("sales_invoices", $values, $invoiceID);
	}

	
	// Päivittää kaikki invoiceen liittyvien rivien ja entryjen päivämäärän.
	private function updateInvoiceDate($invoice, $invoicedate) {
		
		// Päivitetään 
		$values = array();
		$values['Invoicedate'] = $invoicedate;
		$success = Table::updateRowsWhere("sales_invoicerows", $values, " WHERE InvoiceID=" . $invoice->invoiceID);

		if ($invoice->receiptID > 0) {
			
			$values = array();
			$values['Receiptdate'] = $invoicedate;
			$success = Table::updateRow("accounting_receipts", $values, $invoice->receiptID);
			
			$values = array();
			$values['Entrydate'] = $invoicedate;
			$success = Table::updateRowsWhere("accounting_entries", $values, " WHERE ReceiptID=" .  $invoice->receiptID);
		}
	}

	
	
	// Päivittää kaikki invoiceen liittyvien rivien ja entryjen dimensioarvon.
	private function updateInvoiceDimension($invoice, $dimension, $dimensionvalueID) {

		$variable = 'dimension' . $dimension->dimensionID;
		$columname = 'Dimension' . $dimension->dimensionID;
		$values = array();
		$values[$columname] = $dimensionvalueID;
		$success = Table::updateRowsWhere("sales_invoicerows", $values, " WHERE InvoiceID=" . $invoice->invoiceID);
		
		if ($invoice->receiptID > 0) {
				
			$values = array();
			$values[$columname] = $dimensionvalueID;
			$success = Table::updateRowsWhere("accounting_entries", $values, " WHERE ReceiptID=" .  $invoice->receiptID);
		}
	}

	
	
	// Olettaa, että kyseistä dimensiota ei löydi vielä sales_invoicedimensions-taulusta 
	// Tämä voitaneen poistaa tarpeettomana, korvatt updateInvoiceDimension-funktiolla...
	private function setDimensionValueForInvoice($invoiceID, $invoicedate, $dimensionID, $dimensionvalueID, $comments = false) {
		
		// Aseta dimensionvalue invoiceen (mikäli ei ole asetettu)
		/*
		$values = array();
		$values['InvoiceID'] = $invoiceID;
		$values['DimensionID'] = $dimensionID;
		$values['DimensionvalueID'] = $dimensionvalueID;
		$values['Invoicedate'] = $invoicedate;
		$invoiceDimensionRowID = Table::addRow("sales_invoicedimensions", $values, true);
		
		
		// Aseta dimensionvalue invoicerowhun (mikäli ei ole asetettu)
		$invoicerows = Table::load('sales_invoicerows'," WHERE InvoiceID=" . $invoiceID);
		$invoicerowdimensions = Table::load('sales_invoicerowdimensions'," WHERE InvoiceID=" . $invoiceID);
		
		foreach ($invoicerows as $invoicerowID => $invoicerow) {
			$found = false;
			foreach($invoicerowdimensions as $dimensionrowID => $invoicerowdimension) {
				if ($invoicerowdimension->dimensionID == $dimensionID) {
					$found = true;
				}
			}
			
			if ($found == false) {
				$values = array();
				$values['InvoiceID'] = $invoiceID;
				$values['DimensionID'] = $dimensionID;
				$values['DimensionvalueID'] = $dimensionvalueID;
				$values['InvoicerowID'] = $invoicerow->invoicerowID;
				$values['Invoicedate'] = $invoicedate;
				$rowID = Table::addRow("sales_invoicerowdimensions", $values, true);
			}
		}

		
		$receipt = Table::loadRow("accounting_receipts"," InvoiceID=" . $invoiceID);
		if ($receipt == null) {
			echo "<br>Receipt puuttuu. invocieID-" . $invoiceID;
			exit;
		}
		$entries = Table::load('accounting_entries', "WHERE ReceiptID=" . $receipt->receiptID);
		$entrydimensions = Table::load('accounting_entrydimensions', "WHERE ReceiptID=" . $receipt->receiptID);
		
		foreach ($entries as $entryrowID => $entry) {
			$found = false;
			foreach($entrydimensions as $dimensionrowID => $entryrowdimension) {
				if ($entryrowdimension->dimensionID == $dimensionID) {
					$found = true;
				}
			}
			if ($found == false) {
				$values = array();
				$values['EntryID'] = $entry->entryID;
				$values['ReceiptID'] = $receipt->receiptID;
				$values['Entrydate'] = $entry->entrydate;
				$values['DimensionID'] = $dimensionID;
				$values['DimensionvalueID'] = $dimensionvalueID;
				$rowID = Table::addRow("sales_invoicerowdimensions", $values, true);
			}
		}
		*/
	}
	
	
	
	// Delete ei saa olla mahdollinen, mikäli lasku on jo lähetetty
	public function removeinvoicerowAction() {
	
		$invoiceID = $_GET['invoiceID'];
		$invoicerowID = $_GET['id'];

		$invoicerow = Table::loadRow('sales_invoicerows',$invoicerowID);

		if ($invoicerow->salesentryID > 0) Table::deleteRow('accounting_entries',$invoicerow->salesentryID);
		if ($invoicerow->receivablesentryID > 0) Table::deleteRow('accounting_entries',$invoicerow->receivablesentryID);
		if ($invoicerow->vatentryID > 0) Table::deleteRow('accounting_entries',$invoicerow->vatentryID);
		$success = Table::deleteRow('sales_invoicerows',$invoicerowID);
		
		$invoicerows = Table::load('sales_invoicerows'," WHERE InvoiceID=" . $invoiceID);
		$this->updateinvoicesums($invoiceID, $invoicerows);
		
		redirecttotal('sales/invoices/showinvoice&id=' . $invoiceID ,null);
	}

	

	public function removeinvoiceAction() {
	
		$invoiceID = $_GET['invoiceID'];

		$todatabase = true;
		$comments = true;
		
		$invoice = Table::loadRow('sales_invoices', $invoiceID);
		

		$invoiceentries = Table::load('accounting_entries'," WHERE ReceiptID=" . $invoice->receiptID);
		if (count($invoiceentries) == 0) echo "<br>Ei vientejä";
		foreach($invoiceentries as $index => $entry) {
			if ($comments) echo "<br>Deleting accounting_entries - entryID:" . $entry->entryID;
			if ($todatabase == true) {
				$success = Table::deleteRow('accounting_entries',$entry->entryID, true);
				if ($success == false) {
					echo "<br>Exit 2";
					exit;
				}
			}
		}
			
		
		$invoicerows = Table::load('sales_invoicerows'," WHERE InvoiceID=" . $invoiceID);
		if (count($invoicerows) == 0) echo "<br>Ei laskurivejä";
		foreach($invoicerows as $index => $row) {
			if ($comments) echo "<br>Deleting sales_invoicerows - entryID:" . $row->rowID;
			if ($todatabase == true) {
				$success = Table::deleteRow('sales_invoicerows',$row->rowID);
				if ($success == false) {
					echo "<br>Exit 1";
					exit;
				}
			}
		}
		

		if ($invoice->receiptID > 0) {
			if ($todatabase == true) {
				$success = Table::deleteRow('accounting_receipts', $invoice->receiptID, true);
				echo "<br>Success == " . $success;
				if ($success === false) {
					echo "<br>Exit 3";
					exit;
				}
			}
			if ($comments) echo "<br>Deleting accounting_receipts - receiptID:" . $invoice->receiptID;
		} else {
			if ($comments) echo "<br>No receipt to remove - receiptID:" . $invoice->receiptID;
		}
		
		if ($todatabase == true) {
			$success = Table::deleteRow('sales_invoices',$invoiceID);
			if ($success == false) {
				echo "<br>Exit 4";
				exit;
			}
		}
		if ($comments) echo "<br>Deleting sales_invoices - invoiceID:" . $invoiceID;
		
		// TODO: Jos lasku on jo maksettu, niin pitäisi poistaa myös maksulinkitys
		// 		 - tai vaihtoehtoisesti pitää estää maksetun laskun poistaminen kokonaan		
		
		if (!$comments) redirecttotal('sales/invoices/showinvoice&id=' . $invoiceID ,null);
	}
	
	
	// TODO: tämä on ehkä tarpeeton, ainakin tämän saa tehdä vain kirjanpitäjä ja pitäisi ehkä heittää varoitusteksti
	// vientien debet-credit saattaa mennä epäsynkkaan, ei hyvä, ei saisi tällöin mennä kirjanpitoon
	// Annetaan nyt toistaiseksi olla, käyttäjän vastuulla.
	public function removeentryAction() {
	
		$invoiceID = $_GET['invoiceID'];
		$receiptID = $_GET['receiptID'];
		$entryID = $_GET['id'];
	
		//echo "<br>receiptID" . $receiptID;
		//echo "<br>EntryID" . $entryID;
	
		$success = Table::deleteRow("accounting_entries", $entryID, false);
	
		redirecttotal('sales/invoices/showinvoice&id=' . $invoiceID,null);
	}
	
	
	


	// Laskun siirto hyväksytyksi = odottaa lähetystä:
	//  - Pitää luoda receipt ja viennit pitää viedä tietokantaan
	//	- Receipt ilmeisesti pitäisi linkittää myyntilaskuun, ReceiptSourceID, ReceiptSourceTypeID (saadaan ehkä myyntilaskusarjasta)
	//  - Maksuperusteisessa kirjanpidossa tämä pitää tehdä eri järjestyksessä
	//
	public function acceptinvoiceAction() {
	
		$invoiceID = $_GET['invoiceID'];
	
		// Receiptin receiptin ja entrien muodostus poistettu, toimintaa muutettu siten, että ne luodaan automatic heti riviä lisätessä
		// TODO: Tämä mahdollistanee koko laskun kierron mallintamisen prosessina...
		// TODO: Palautettu vientien automaattinen luonti, tätä pitää miettiä vielä
		//			- ehkä pitää toteuttaa niin, että viennit on kannassa ei hyväksyttynä
			
		$invoice = Table::loadRow('sales_invoices',$invoiceID);
		if ($invoice->clientcompanyID > 0) {
			$company = Table::loadRow('crm_companies', $invoice->clientcompanyID);
		} else {
			$company = null;
		}
		$invoicerows = Table::load('sales_invoicerows'," WHERE InvoiceID=" . $invoiceID);
		$products = Table::load('sales_products');
		$accounts = Table::load('accounting_accounts');
		$vats = Table::load('system_vats');
		$entries = $this->createEntriesFromInvoice($invoice, $invoicerows, $company, $products, $accounts, $vats);
		$receiptsetID =  Settings::getSetting('accounting_salesreceiptsetID', 0);
		$receiptnumber = $this->getNextReceiptNumber($receiptsetID);
		
		if ($invoice->receiptID > 0) {
			
			// Tänne tullaan mikäli kyseiselle laskulle on jo aiemmin luotu receiptID
			// ReceiptID saattaa olla luotu aiemmin jos lasku on palautettu avoimeksi
			// TODO: pitäisi tsekata tuhotaanko viennit, jos palautetaan avoimeksi?
			// TODO: toinen vaihtoehto on, että uusien vientirivien sijaan käytetään vanhoja
			//       entries-rivejä mikäli niitä vain on
			

			$values = array();
			$values['State'] = 1;
			$success = Table::updateRow("sales_invoices", $values, $invoiceID, false);
			
			
		} else {

			$values = array();
			$values['Receiptdate'] = $invoice->invoicedate;
			$values['Receiptnumber'] = $receiptnumber;
			$values['ReceiptsetID'] = $receiptsetID;
			$values['Explanation'] = "Myyntilasku " . $invoice->invoiceID;
			$values['ReceiverID'] = 0;	// pitäisi asettaa asiakasyritys
			$values['CostpoolID'] = 0;	// Pitäisi asettaa jokin sopiva kustannuspaikka, myynti? Tämä on lähinnä ostoja varten
			$values['Grossamount'] = $invoice->grossamount;
			$values['Netamount'] = $invoice->netamount;
			$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
			$values['Paymentstatus'] = 0;
			$values['InvoiceID'] = $invoice->invoiceID;
			$newreceiptID = Table::addRow("accounting_receipts", $values, false);
			
			foreach($entries as $index => $entry) {
				$values = array();
				$values['ReceiptID'] = $newreceiptID;
				$values['AccountID'] = $entry->accountID;
				$values['Entrydate'] = $invoice->invoicedate;
				$values['Amount'] = $entry->amount;
				$values['VatcodeID'] = $entry->vatcodeID;
				$success = Table::addRow("accounting_entries", $values, false);
			}
			
			$values = array();
			$values['ReceiptID'] = $newreceiptID;
			$values['State'] = 1;
			$success = Table::updateRow("sales_invoices", $values, $invoiceID, false);
		}
		
		redirecttotal('sales/invoices/showinvoice&id=' . $invoiceID, null);
	}
	
	
	
	// TODO: Tätä pitää vielä miettiä, ei saa poistaa entryjä, koska toimintaa on muutettu niin, että entryt on myös avoimessa
	//		 sitäpaitsi ei poista salesinvoices.receiptID-arvoa, tämä lisätty myöhemmin
	// TODO: Tässä voi myös olla virhetilanne, jossa receipts-taulussa on useampia
	//		 receiptID:itä samall InvoiceID:llä tuplat pitäisi poistaa ainakin.
	// TODO: Edellä mainittu tuplatarkistus pitää lisätä johonkin tarkistusajoon
	public function openinvoiceAction() {
	
		$comments = true;
		$invoiceID = $_GET['invoiceID'];
	
		$receipts = Table::load("accounting_receipts", "WHERE InvoiceID=" . $invoiceID, $comments);
		
		foreach($receipts as $index => $receipt) {
			$entries= Table::load('accounting_entries', "WHERE ReceiptID=" . $receipt->receiptID);
			foreach($entries as $index => $entry) {
				$success = Table::deleteRow("accounting_entries", $entry->entryID, false, $comments);
			}
			$success = Table::deleteRow("accounting_receipts", $receipt->receiptID, false,$comments);
		}
	
		$values = array();
		$values['State'] = 0;
		$values['ReceiptID'] = 0;
		$success = Table::updateRow("sales_invoices", $values, $invoiceID, false);
		if (!$comments) redirecttotal('sales/invoices/showinvoice&id=' . $invoiceID, null);
	}
	
	
	
	public function marksendinvoiceAction() {
	
		$comments = true;
		$invoiceID = $_GET['invoiceID'];
	
		//$receipt = Table::loadRow("accounting_receipts"," InvoiceID=" . $invoiceID,$comments);
	
		//$entries= Table::load('accounting_entries', "WHERE ReceiptID=" . $receipt->receiptID);
		//foreach($entries as $index => $entry) {
		//	$success = Table::deleteRow("accounting_entries", $entry->entryID, false, $comments);
		//}
	
		//$success = Table::deleteRow("accounting_receipts", $receipt->receiptID, false,$comments);
	
		$values = array();
		$values['State'] = 2;
		$success = Table::updateRow("sales_invoices", $values, $invoiceID, false);
		redirecttotal('sales/invoices/showinvoice&id=' . $invoiceID, null);
	}
	
	
	
	public function markpayedinvoiceAction() {
	
		$comments = true;
		$invoiceID = $_GET['invoiceID'];
	
		//$receipt = Table::loadRow("accounting_receipts"," InvoiceID=" . $invoiceID,$comments);
	
		//$entries= Table::load('accounting_entries', "WHERE ReceiptID=" . $receipt->receiptID);
		//foreach($entries as $index => $entry) {
		//	$success = Table::deleteRow("accounting_entries", $entry->entryID, false, $comments);
		//}
	
		//$success = Table::deleteRow("accounting_receipts", $receipt->receiptID, false,$comments);
	
		$values = array();
		$values['State'] = 4;
		$success = Table::updateRow("sales_invoices", $values, $invoiceID, false);
		redirecttotal('sales/invoices/showinvoice&id=' . $invoiceID, null);
	}
	
	
	public function getunaccountedinvoicesJSONAction() {
	
		if (isset($_GET['companyID'])) {
			$companyID = $_GET['companyID'];
			$invoices = Table::load("sales_invoices","WHERE ClientcompanyID=" . $companyID . " AND (State=1 OR State=3 OR State=2)");
			
			echo "[";
			$first = true;
			foreach($invoices as $index => $invoice) {
				if ($first == true) $first = false; else echo ",";
			
				echo " {";
				echo "	  \"invoiceID\":\"" . $invoice->invoiceID . "\",";
				echo "	  \"invoicedate\":\"" . $invoice->invoicedate . "\",";
				echo "	  \"duedate\":\"" . $invoice->duedate . "\",";
				echo "	  \"grossamount\":\"" . $invoice->unpaidamount . "\"";
				echo " }\n";
			}
			echo "]";
			return;
		}
		
		
		if (isset($_GET['clientID'])) {
			$clientID = $_GET['clientID'];
			$invoices = Table::load("sales_invoices","WHERE ClientpersonID=" . $clientID . " AND (State=1 OR State=3 OR State=2)");
				
			echo "[";
			$first = true;
			foreach($invoices as $index => $invoice) {
				if ($first == true) $first = false; else echo ",";
					
				echo " {";
				echo "	  \"invoiceID\":\"" . $invoice->invoiceID . "\",";
				echo "	  \"invoicedate\":\"" . $invoice->invoicedate . "\",";
				echo "	  \"duedate\":\"" . $invoice->duedate . "\",";
				echo "	  \"grossamount\":\"" . $invoice->unpaidamount . "\"";
				echo " }\n";
			}
			echo "]";
			return;
		}

		if (isset($_GET['supplierID'])) {
			$supplierID = $_GET['supplierID'];
			$purchases = Table::load("accounting_purchases","WHERE SupplierID=" . $supplierID . " AND (State=1 OR State=3)");
				
			echo "[";
			$first = true;
			foreach($purchases as $index => $purchase) {
				if ($first == true) $first = false; else echo ",";
					
				echo " {";
				echo "	  \"invoiceID\":\"" . $purchase->purchaseID . "\",";
				echo "	  \"invoicedate\":\"" . $purchase->purchasedate . "\",";
				echo "	  \"duedate\":\"" . $purchase->duedate . "\",";
				echo "	  \"grossamount\":\"" . $purchase->unpaidamount . "\"";
				echo " }\n";
			}
			echo "]";
			return;
		}
	}

	

	public function copyinvoiceAction() {
	
		$comments = false;
	
		$invoiceID = $_GET['invoiceID'];
		$invoice = Table::loadRow('sales_invoices',$invoiceID);
	
		$values['InvoicetypeID'] = $invoice->invoicetypeID;
		$values['ClienttypeID'] = $invoice->clienttypeID;
		$values['ClientcompanyID'] = $_GET['companyID'];
		$values['Invoicedate'] = $_GET['invoicedate'];
		$values['Duedate'] = $_GET['duedate'];
		$values['State'] = 1;
		$values['Referencenumber'] = $invoice->referencenumber;
		// TODO: Pitää varmaan kopioida myös osoitetiedot, mutta päivitetyistä tiedoista
	
		$newinvoiceID = Table::addRow("sales_invoices", $values);
	
	
		$invoicerows = Table::load('sales_invoicerows'," WHERE InvoiceID=" . $invoiceID);
		foreach($invoicerows as $index => $invoicerow) {
			$values = array();
			$values['ProductID'] = $invoicerow->productID;
			$values['Productname'] = $invoicerow->productname;
			$values['UnitID'] = $invoicerow->unitID;
			$values['Unit'] = $invoicerow->unit;
			$values['VatID'] = $invoicerow->vatID;
			$values['Vatpercent'] = $invoicerow->vatpercent;
			$values['InvoiceID'] = $newinvoiceID;
			$values['Unitprice'] = $invoicerow->unitprice;
			$values['Unitamount'] = $invoicerow->unitamount;
			$values['Grossamount'] = $invoicerow->grossamount;
			$values['Netamount'] = $invoicerow->netamount;
			$values['AccountID'] = $invoicerow->accountID;
			$invoicerowID = Table::addRow("sales_invoicerows", $values);
		}
		$invoicerows = Table::load('sales_invoicerows'," WHERE InvoiceID=" . $newinvoiceID);
		$this->updateinvoicesums($newinvoiceID, $invoicerows);
	
	
		$newinvoice = Table::loadRow('sales_invoices',$newinvoiceID);
	
		echo "<br>newinvoicedate - " . $newinvoice->invoicedate;
	
	
		if ($invoice->state > 0) {
			// Kopioidaan myös viennit...
	
			$oldreceipt = Table::loadRow('accounting_receipts','WHERE InvoiceID=' . $invoiceID, $comments);
	
	
			$receiptnumber = $this->getNextReceiptNumber($oldreceipt->receiptsetID);
	
			$values = array();
			$values['Receiptdate'] = $_GET['invoicedate'];
			$values['Receiptnumber'] = $receiptnumber;
			$values['ReceiptsetID'] = $oldreceipt->receiptsetID;
			$values['Explanation'] = "Myyntilasku " . $newinvoiceID;
			$values['ReceiverID'] = 0;	// pitäisi asettaa asiakasyritys
			$values['CostpoolID'] = 0;	// Pitäisi asettaa jokin sopiva kustannuspaikka, myynti? Tämä on lähinnä ostoja varten
			$values['Grossamount'] = $newinvoice->grossamount;
			$values['Netamount'] = $newinvoice->netamount;
			$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
			$values['Paymentstatus'] = 0;
			$values['InvoiceID'] = $newinvoiceID;
			$newreceiptID = Table::addRow("accounting_receipts", $values, $comments);
	
			$sourceentries = Table::load('accounting_entries'," WHERE ReceiptID=" . $oldreceipt->receiptID);
	
			foreach($sourceentries as $index => $sourceentry) {
				$values = array();
				$values['ReceiptID'] = $newreceiptID;
				$values['AccountID'] = $sourceentry->accountID;
				$values['Entrydate'] = $newinvoice->invoicedate;
				$values['Amount'] = $sourceentry->amount;
				$values['VatcodeID'] = $sourceentry->vatcodeID;
				$success = Table::addRow("accounting_entries", $values, $comments);
			}
	
			$values = array();
			$values['ReceiptID'] = $newreceiptID;
			$success = Table::updateRow('sales_invoices', $values, $newinvoiceID, false);
		}
	
	
	
		redirecttotal('sales/invoices/showinvoice&id=' . $newinvoiceID, null);
	}
	
	
	
	// Väliaikainen tarkistus funktio
	public function tempupdateAction() {
	
		echo "<br>Tempupdate";
		
		//$this->updateInvoiceReceiptID();
		//$this->checkinvoicedates();
		//$this->checkpurchasedates();
		
		//$this->updatePurchaseReceiptID();
		
		//$this->updatePayrollReceiptID();
		//$this->updatBankStatementRowReceiptID();
		//$this->updateReceiptDebetAndCredit();

		
		//$this->fixReceiptNumberForPurchasesEntries();
		
		//$this->removeBankaccountRowlinks();
		
		//$this->updateClientSalesInvoices();
		
		//$this->updateIzettleentries();
		
		
		//$this->checkDebetAndCredit();
		
		$this->AddSystemIDColumns();
		
	}
	
	
	
	private function AddSystemIDColumns() {
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		global $mysqli;
		
		
		$newSystemID = 3;
		$moduleID = 0;
		
		$tables = Table::loadTables();
		$counter = 0;
		
		foreach($tables as $index => $tablestruct) {
			$table = Table::getTable($tablestruct->name);
			
			if ($table->systemspecific == 1) {
				echo "<br> - Luodaan column " . $tablestruct->name;
				
				$columns = $table->getColumns();
				$found = false;
				foreach($columns as $columnID => $column) {
					if ($column->name == "SystemID") $found = true;
				} 
				
				if ($found == false) {
					echo "<br> - - - ei löytynyt lisätään columni - " . $column->name;
					
					$addcolumnsql = "ALTER TABLE " . $tablestruct->name . " ADD SystemID INT";
					$result = $mysqli->query($addcolumnsql);
					if (!$result) {
						echo 'CREATE failed: ' . $mysqli->connect_error;
					}
					
					$sql = "INSERT INTO system_columns(TableID, Variablename, Columnname, Name, Type, Obligatory) VALUES ('" . $tablestruct->tableID . "','systemID','SystemID','SystemID'," . Column::COLUMNTYPE_INTEGER . ",1)";
					$result = $mysqli->query($sql);
					if (!$result) {
						echo 'CREATE failed: ' . $mysqli->connect_error;
					}
					$columnID = $mysqli->insert_id;
						
					$updatesql = "UPDATE " . $tablestruct->name . " SET SystemID=" . $newSystemID . "";
					$result = $mysqli->query($updatesql);
						
					echo "<br>Updated - " .  $tablestruct->name;
					$counter++;
					//exit();
				} else {
					echo "<br> - - - columni löytyi - " . $column->name;
					
					$updatesql = "UPDATE " . $tablestruct->name . " SET SystemID=" . $newSystemID . "";
					$result = $mysqli->query($updatesql);
					$counter++;
						
				}
				
				//$addcolumnsql = "ALTER TABLE " . $tablestruct->name . " ADD SystemID INT";
				
				
			} else {
				echo "<br> - Ei päivitetä " . $tablestruct->name;
			}
		}
		echo "<br>Counter - " . $counter;
	}
	

	private function updateIzettleentries() {
	

		echo "<br>update izettle entries<br><br>";
		
		$receipts = Table::load('accounting_receipts');
		$entries = Table::load('accounting_entries', " WHERE AccountID=17 AND Amount < 0");

		
		foreach($entries as $index => $entry) {
			echo "<br> entry:" . $entry->entryID;
			
			$receipt = $receipts[$entry->receiptID];
			echo "<br>--- receipt: " . $receipt->explanation . " - " . $receipt->receiptID;
			
			
			$currententries = Table::load('accounting_entries', " WHERE ReceiptID=" . $entry->receiptID);
			
			echo "<br>UpdateEntries";
			foreach($currententries as $index => $updateentry) {
				$values = array();
				$values['Dimension1'] = 3;
				Table::updateRow("accounting_entries", $values, $updateentry->entryID, false);
				
				echo "<br> ---  updateenry - " . $updateentry->entryID;
			}
			
			$values = array();
			$values['Dimension1'] = 3;
			Table::updateRow("accounting_receipts", $values, $receipt->receiptID, false);
			
			
			/*
			if ($receipt->receiptID != $invoice->receiptID) {
				echo "<br>****** receipt.receiptID != invoice.receiptID - invoiceID:" . $invoice->invoiceID;
			}
			*/
		}
		
		
		
		/*
		foreach($invoices as $index => $invoice) {
			foreach($receipts as $index => $receipt) {
				if ($receipt->invoiceID == $invoice->invoiceID) {
					if ($receipt->receiptdate != $invoice->invoicedate) {
						echo "<br>****** receiptdate != invoicedate - invoiceID:" . $invoice->invoiceID;
					}
		
					if ($receipt->receiptID != $invoice->receiptID) {
						echo "<br>****** receipt.receiptID != invoice.receiptID - invoiceID:" . $invoice->invoiceID;
					}
				}
			}
		}
		*/
		
		
	}
		
	private function updateClientSalesInvoices() {
	
		$oldcompanyID = 8;
		$productID = 18;
		$clientID = 3;
		
		echo "<br>ClientID - " . $clientID;
		echo "<br>productID - " . $productID;
		echo "<br>oldcompanyID - " . $oldcompanyID;
		echo "<br><br>";
		
		$salesinvoices = Table::load('sales_invoices', "WHERE ClientpersonID=" . $clientID);
		$products = Table::load('sales_products');
		
		

		echo "<br><br>";
		if ($oldcompanyID > 0) {
			$bankrows = Table::load('accounting_bankstatementrows', " WHERE CompanyID=" . $oldcompanyID);
			foreach($bankrows as $index => $bankrow) {
				$found = false;
				foreach($salesinvoices as $index => $invoice) {
					if ($invoice->reference == $bankrow->referencenumber) {
						$found = true;
					}
				}
					
				if ($found == false) {
					echo "<br>No bankreference found in invoices - " . $bankrow->rowID . " - " . $bankrow->reference;
					foreach($salesinvoices as $index => $invoice) {
						echo "<br>Invoice tried - " . $invoice->invoiceID . " - " . $invoice->referencenumber;
					}
					exit;
				} else {
			
					echo "<br>Bankstatementrow found - " . $bankrow->rowID . " - " . $bankrow->reference;
					echo "<br>-- zeroing receiptID - " . $bankrow->receiptID;
			
					if ($bankrow->receiptID > 0) {
						$entries = Table::load('accounting_entries', "WHERE ReceiptID=" . $bankrow->receiptID);
						//echo "<br>-- Delete Receipt - " . $bankrow->receiptID;
						Table::deleteRow('accounting_receipts',$bankrow->receiptID);
						echo "<br>-- -- Receipt deleted - " . $bankrow->receiptID . ")";
						foreach($entries as $index => $entry) {
							//echo "<br>-- Delete Entry - " . $entry->entryID;
							Table::deleteRow('accounting_entries',$entry->entryID);
							echo "<br>-- -- Entry deleted - " . $entry->entryID . " (receiptID:" . $entry->receiptID . ")";
						}
					}
						
					$values = array();
					$values['Status'] = 1;
					$values['CompanyID'] = 0;
					$values['ReceiptID'] = 0;
					$values['ClientID'] = $clientID;
					Table::updateRow("accounting_bankstatementrows", $values, $bankrow->rowID, false);
					echo "<br>-- -- Bankstatementrow updated - " . $bankrow->rowID;
				}
			}
		}
		
		echo "<br><br>";
		foreach($salesinvoices as $index => $invoice) {
				
			echo "<br>Invoice - " . $invoice->invoiceID . " - " . $invoice->invoicedate . " - " . $invoice->grossamount . " - " . $invoice->receiptID;

			$invoicerows = Table::load('sales_invoicerows', "WHERE InvoiceID=" . $invoice->invoiceID);
			
			$counter = 0;
			foreach($invoicerows as $index => $invoicerow) {
				if ($counter > 0 ) {
					echo "<br>Useampi rivi myyntilaskulla - " . $invoice->invoiceID;
					exit;
				}
				
				$product = $products[$productID];
				echo "<br> -- Invoicerow - " . $invoicerow->rowID . " - " . $product->name;
				$values = array();
				$values['ProductID'] = $productID;
				$values['Productname'] = $product->name;;
				$values['UnitID'] = 5;
				$values['Unit'] = "e/kk";
				$values['Dimension1'] = 2;
				$values['Dimension2'] = 4;
				Table::updateRow("sales_invoicerows", $values, $invoicerow->rowID, false);
				
				$counter++;
			}
			
			
			//$receipt = Table::loadRow('accounting_receipts', $receipt->receiptID );
			//echo "<br>Receipt - " . $receipt->receiptID . " - " . $receitp->receiptdate;
			$counter = 0;
			$entries = Table::load('accounting_entries', " WHERE ReceiptID=" . $invoice->receiptID);
			foreach($entries as $index => $entry) {
				
				if ($counter > 2) {
					echo "<br>Counteri yli - " . $counter;
					exit;
				}
				
				echo "<br> -- EntryID - " . $entry->entryID. " - " . $product->name;
			
				if ($entry->accountID != 54) {
					echo "<br>-- Update entryID - " . $entry->entryID;
					$values = array();
					$values['AccountID'] = 18;
					$values['VatcodeID'] = 0;
					$values['Dimension1'] = 2;
					$values['Dimension2'] = 4;
					Table::updateRow("accounting_entries", $values, $entry->entryID, false);
					
					$values = array();
					$values['ReceivablesentryID'] = $entry->entryID;
					Table::updateRow("sales_invoicerows", $values, $invoicerow->rowID, false);
					
				} else {
					
					$values = array();
					$values['Dimension1'] = 2;
					$values['Dimension2'] = 4;
					Table::updateRow("accounting_entries", $values, $entry->entryID, false);
						
					
					$values = array();
					$values['SalesentryID'] = $entry->entryID;
					Table::updateRow("sales_invoicerows", $values, $invoicerow->rowID, false);
				
				}
				
				$counter++;
			}
				
			
			//foreach($bankrows as $index => $bankrow) {
			//	echo "<br> -- Invoicerow - bankstatementrowID:" . $bankrow->rowID . " - receiptID: " . $bankrow->receiptID . " - reference:" . $bankrow->reference;
			//}
			
			/*
			$values = array();
			$values['Name'] = $productname;
			$values['AccountID'] = 54;
			$values['Service'] = 1;
			$values['VatID'] = 4;
			$values['UnitID'] = 5;
			*/
			//Table::addRow("sales_products", $values, false);
	
	
		}
		
		
			
	
	}
	
	
	
	
	
	private function createProducts() {
	
		$clients = Table::load('crm_clients');
		
		foreach($clients as $index => $row) {
			
			$productname = "Iltapäiväkerho, " . $row->lastname . " " . $row->firstname;
			echo "<br>client - " . $productname;
			
			$values = array();
			$values['Name'] = $productname;
			$values['AccountID'] = 54;
			$values['Service'] = 1;
			$values['VatID'] = 4;
			$values['UnitID'] = 5;
			//Table::addRow("sales_products", $values, false);
	
				
		}
		
	}
	
	
	private function removeBankaccountRowlinks() {
	
		echo "<br>removeBankaccountRowlinks<br><br>";

		$reference  = "1052";
		$newclientID = 54;
		$todatabase = false;
		
		$rows = Table::load('accounting_bankstatementrows', "WHERE Reference='" . $reference . "'");
		
		
		foreach($rows as $index => $row) {
			echo "<br>RowID - " . $row->rowID . " --- " . $row->reference . ", receiptID = " . $row->receiptID . ", compnayID = " . $row->companyID . ", date = " . $row->entrydate;
			
			echo "<br>Muutetaan companyID - " . $row->companyID . " --> clientID " . $newclientID;
			
			$entries = Table::load('accounting_entries', "WHERE ReceiptID=" . $row->receiptID);
			echo "<br>Delete Receipt - " . $row->receiptID;
			if ($todatabase == true) Table::deleteRow('accounting_receipts',$row->receiptID);
			
				
			foreach($entries as $index => $entry) {
				echo "<br>Delete Entry - " . $entry->entryID;
				if ($todatabase == true) Table::deleteRow('accounting_entries',$entry->entryID);
			}
			
			
			$values = array();
			$values['Status'] = 1;
			$values['CompanyID'] = 0;
			$values['ReceiptID'] = 0;
			$values['ClientID'] = $newclientID;
			if ($todatabase == true) Table::updateRow("accounting_bankstatementrows", $values, $row->rowID, false);
			
		}
		
		
		// Pitäisi myös päivittää ao laskut pankkitilisaamisiin, ja tuote vaihtaa
		
		
		echo "<br><br>Updated rows:";
		foreach($rows as $index => $row) {
			echo "<br>RowID - " . $row->rowID;
		}
		
	}
	
	
	

	private function updatBankStatementRowReceiptID() {
	
		echo "<br>updatBankStatementRowReceiptID<br><br>";
	
		$receipts = Table::load('accounting_receipts', "WHERE BankstatementrowID>0");
		$bankstatementrows = Table::load('accounting_bankstatementrows');
		$counter = 0;
		
		foreach($receipts as $index => $receipt) {
				
			echo "<br>Receipt - " . $receipt->receiptID . " - " . $receipt->receiptdate . " - " . $receipt->purchaseID;
			$foundpurchase = null;
			
			$row = $bankstatementrows[$receipt->bankstatementrowID];
			
			if (isset($bankstatementrows[$receipt->bankstatementrowID])) {
				echo "<br>Found " . $row->bankstatementID . " - " . $row->rowID;
				
				$values = array();
				$values['ReceiptID'] = $receipt->receiptID;
				//$success = Table::updateRow("accounting_bankstatementrows", $values, $row->rowID, false);
				$counter++;
			} else {
				echo "<br>No bankstatementrow found";
			}
		}
		echo "<br>Counter - " . $counter;
	}
	
	

	private function fixReceiptNumberForPurchasesEntries() {
	
		echo "<br>updatepurchaserowvat<br><br>";
	
		$receipts = Table::load('accounting_receipts', "WHERE ReceiptsetID=1 ORDER BY Receiptdate");
		$counter = 0;
		$receiptnumber = 100000;
	
		foreach($receipts as $index => $receipt) {
				
			echo "<br>Receipt - " . $receipt->receiptID . " - " . $receipt->receiptdate;
			$counter++;
	
			$values = array();
			$values['Receiptnumber'] = $receiptnumber;
			//$success = Table::updateRow("accounting_receipts", $values, $receipt->receiptID, false);
				
			$receiptnumber++;
		}
		echo "<br>Counter - " . $counter;
	}
	
	
	
	
	private function fixReceiptNumberForBankEntries() {
	
		echo "<br>updatepurchaserowvat<br><br>";
	
		$receipts = Table::load('accounting_receipts', "WHERE ReceiptsetID=3 ORDER BY Receiptdate");
		$counter = 0;
		$receiptnumber = 300000;
		
		foreach($receipts as $index => $receipt) {
			
			echo "<br>Receipt - " . $receipt->receiptID . " - " . $receipt->receiptdate;
			$counter++;

			$values = array();
			$values['Receiptnumber'] = $receiptnumber;
			$success = Table::updateRow("accounting_receipts", $values, $receipt->receiptID, false);
			
			$receiptnumber++;
		}
		echo "<br>Counter - " . $counter;
	}
	
	
	
	private function updateBankEntriesDimensions() {
	
		echo "<br>updateBankEntriesDimensions<br><br>";
	
		$receipts = Table::load('accounting_receipts', "WHERE ReceiptsetID=3 AND PurchaseID>0 ORDER BY Receiptdate");
				
		$dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		
		$purchases = Table::load('accounting_purchases');
		$counter = 0;
		$receiptnumber = 300000;
	
		foreach($receipts as $index => $receipt) {
				
			echo "<br>Receipt - " . $receipt->receiptID . " - " . $receipt->receiptdate . " - " . $receipt->purchaseID;
			$foundpurchase = null;
			
			foreach($purchases as $index => $purchase) {
				if ($receipt->purchaseID == $purchase->purchaseID) {
					$foundpurchase = $purchase;
				}
			}
			
			if ($foundpurchase == null) {
				echo "<br> ******************** purchase not found - " . $receipt->purchaseID;
			} else {
				echo "<br> -- receiptID to update - " . $receipt->receiptID;
				
				$values = array();
				if (count($dimensions) > 0) {
					foreach($dimensions as $index => $dimension) {
						$variable = "dimension" . $dimension->dimensionID;
						$columname = "Dimension" . $dimension->dimensionID;
						$values[$columname] = $foundpurchase->$variable;
						echo "<br>Dimension to update - " . $columname . " - " . $foundpurchase->$variable;
					}
				}
				$success = Table::updateRow("accounting_receipts", $values, $receipt->receiptID, false);
						
				
			}
			
			//$counter++;
			//$values = array();
			//$values['Receiptnumber'] = $receiptnumber;
			//$success = Table::updateRow("accounting_receipts", $values, $receipt->receiptID, false);
				
			$receiptnumber++;
		}
		echo "<br>Counter - " . $counter;
	}
	
	
	
	
	private function updatepurchaserowvat() {
	
		echo "<br>updatepurchaserowvat<br><br>";
	
		$invoicerows = Table::load('updatepurchaserowvat');
		
		foreach($invoicerows as $index => $invoicerow) {
			if ($invoicerow->vatamount == null) {
				echo "<br>vatamoun null:" . $invoicerow->purchaseID;
				
				if ($invoicerow->vatpercent > 0) {
					echo "<br>-- Vatti pitää päivittää - " . $invoicerow->vatpercent . "%" . " " . $invoicerow->netamount . " vs. " . $invoicerow->grossamount;
					
					$values = array();
					$values['Vatamount'] = $invoicerow->grossamount - $invoicerow->netamount;
					//$success = Table::updateRow("updatepurchaserowvat", $values, $invoicerow->rowID, false);
								
				} else {
					echo "<br>-- Ei tarvitse päivittää, vatprosentti nolla";
					
					$values = array();
					$values['Vatamount'] = 0;
					//$success = Table::updateRow("updatepurchaserowvat", $values, $invoicerow->rowID, false);
								
				}
			}
		}
	
	}
	
	
	
	// Väliaikainen tarkistus funktio
	private function checkinvoicedates() {
	
		echo "<br>checkdates<br><br>";
	
		$invoices = Table::load('sales_invoices');
		$receipts = Table::load('accounting_receipts');
		$entries = Table::load('accounting_entries');
	
		foreach($invoices as $index => $invoice) {
			foreach($receipts as $index => $receipt) {
				if ($receipt->invoiceID == $invoice->invoiceID) {
					if ($receipt->receiptdate != $invoice->invoicedate) {
						echo "<br>****** receiptdate != invoicedate - invoiceID:" . $invoice->invoiceID;
					}
						
					if ($receipt->receiptID != $invoice->receiptID) {
						echo "<br>****** receipt.receiptID != invoice.receiptID - invoiceID:" . $invoice->invoiceID;
					}
				}
			}
		}
	
		foreach($entries as $entryID => $entry) {
				
			if (isset($receipts[$entry->receiptID])) {
				$receipt = $receipts[$entry->receiptID];
				if ($receipt->receiptdate !=  $entry->entrydate) {
					echo "<br>****** aaa receiptdate != entrydate - receiptID:" . $receipt->receiptID . " --- " . $receipt->receiptdate . " - - " . $entry->entrydate;
						
					//$values = array();
					//$values['Entrydate'] = $receipt->receiptdate;
					//$success = Table::updateRow("accounting_entries", $values, $entry->entryID, false);
						
				}
			} else {
				echo "<br>******* receiptID:tä ei löytynyt entrylle -- entryID:" . $entry->entryID;
			}
		}
	}
	
	
	
	private function checkpurchasedates() {
	
		echo "<br>checkdates<br><br>";
	
		$invoices = Table::load('purchase_invoices');
		$receipts = Table::load('accounting_receipts');
		$entries = Table::load('accounting_entries');
	
		foreach($invoices as $index => $invoice) {
			foreach($receipts as $index => $receipt) {
				if ($receipt->invoiceID == $invoice->invoiceID) {
					if ($receipt->receiptdate != $invoice->invoicedate) {
						echo "<br>****** receiptdate != invoicedate - invoiceID:" . $invoice->invoiceID;
					}
	
					if ($receipt->receiptID != $invoice->receiptID) {
						echo "<br>****** receipt.receiptID != invoice.receiptID - invoiceID:" . $invoice->invoiceID;
					}
				}
			}
		}
	
		foreach($entries as $entryID => $entry) {
	
			if (isset($receipts[$entry->receiptID])) {
				$receipt = $receipts[$entry->receiptID];
				if ($receipt->receiptdate !=  $entry->entrydate) {
					echo "<br>****** aaa receiptdate != entrydate - receiptID:" . $receipt->receiptID . " --- " . $receipt->receiptdate . " - - " . $entry->entrydate;
	
					//$values = array();
					//$values['Entrydate'] = $receipt->receiptdate;
					//$success = Table::updateRow("accounting_entries", $values, $entry->entryID, false);
	
				}
			} else {
				echo "<br>******* receiptID:tä ei löytynyt entrylle -- entryID:" . $entry->entryID;
			}
		}
	}
	
	
	
	private function checkentrieswithoutreceiptsdates() {
	
		echo "<br>checkdates<br><br>";
	
		$invoices = Table::load('sales_invoices');
		$receipts = Table::load('accounting_receipts');
		$entries = Table::load('accounting_entries');
	
		foreach($entries as $entryID => $entry) {
	
			if (isset($receipts[$entry->receiptID])) {
				$receipt = $receipts[$entry->receiptID];
				if ($receipt->receiptdate !=  $entry->entrydate) {
					echo "<br>****** aaa receiptdate != entrydate - receiptID:" . $receipt->receiptID . " --- " . $receipt->receiptdate . " - - " . $entry->entrydate;
	
					//$values = array();
					//$values['Entrydate'] = $receipt->receiptdate;
					//$success = Table::updateRow("accounting_entries", $values, $entry->entryID, false);
	
				}
			} else {
				echo "<br>******* receiptID:tä ei löytynyt entrylle -- entryID:" . $entry->entryID;
			}
		}
	}
	
	
	
	// Väliaikainen tarkistus funktio
	private function updatePayrollReceiptID() {
	
		echo "<br>Päivitetään invoice receiptID - updatePayrollReceiptID";
		$paychecks = Table::load('payroll_paychecks');
		$receipts = Table::load('accounting_receipts');
	
		foreach($paychecks as $index => $paycheck) {
			echo "<br>Käsitellä paycheckID - " . $paycheck->paycheckID;
	
			$receiptID = 0;
			$counter = 0;
			$foundReceiptID = 0;
			foreach($receipts as $receiptID => $receipt) {
				if ($receipt->paycheckID == $paycheck->paycheckID) {
					$counter++;
					$foundReceiptID = $receipt->receiptID;
				}
			}
			if ($counter == 1) {
				echo "<br> -- one found update receiptID - " . $foundReceiptID;
	
				$values = array();
				$values['ReceiptID'] = $foundReceiptID;
				//$success = Table::updateRow("payroll_paychecks", $values, $paycheck->paycheckID, false);
			}
			if ($counter > 1) {
				echo "<br>********** tupla receipt - " . $paycheck->paycheckID;
			}
			if ($counter == 0) {
				echo "<br>********** nolla receiptiä löytyi";
			}
		}
	}
	
	
	
	// Väliaikainen tarkistus funktio
	private function updateInvoiceReceiptID() {
	
		echo "<br>Päivitetään invoice receiptID";
		$invoices = Table::load('sales_invoices');
		$receipts = Table::load('accounting_receipts');
	
		foreach($invoices as $index => $invoice) {
			echo "<br>Käsitellä invoicea - " . $invoice->invoiceID;
	
			$receiptID = 0;
			$counter = 0;
			$foundReceiptID = 0;
			foreach($receipts as $receiptID => $receipt) {
				if ($receipt->invoiceID == $invoice->invoiceID) {
					$counter++;
					$foundReceiptID = $receipt->receiptID;
				}
			}
			if ($counter == 1) {
				echo "<br> -- one found update receiptID - " . $foundReceiptID;
	
				$values = array();
				$values['ReceiptID'] = $foundReceiptID;
				$success = Table::updateRow("sales_invoices", $values, $invoice->invoiceID, false);
			}
			if ($counter > 1) {
				echo "<br>********** tupla receipt - " . $invoice->invoiceID;
			}
			if ($counter == 0) {
				echo "<br>********** nolla receiptiä löytyi";
			}
		}
	}
	
	
	// Väliaikainen tarkistus funktio
	private function updatePurchaseReceiptID() {
	
		echo "<br>Päivitetään invoice receiptID";
		$invoices = Table::load('accounting_purchases');
		$receipts = Table::load('accounting_receipts');
		$loopcounter = 0;
		
		foreach($invoices as $index => $invoice) {
			echo "<br>Käsitellä purchaseID - " . $invoice->purchaseID;
	
			$receiptID = 0;
			$counter = 0;
			$foundReceiptID = 0;
			foreach($receipts as $receiptID => $receipt) {
				if (($receipt->purchaseID == $invoice->purchaseID) && (($receipt->bankstatementID == null) || $receipt->bankstatementID == 0)) {
					$counter++;
					$foundReceiptID = $receipt->receiptID;
					//echo "<br>----- found";
				} else {
					//echo "<br>----- not found - " . $receipt->purchaseID . " - " . $invoice->purchaseID . " - " . $receipt->receiptID;
				}
			}
			if ($counter == 1) {
				echo "<br> -- one found update receiptID - " . $foundReceiptID;
	
				$values = array();
				$values['ReceiptID'] = $foundReceiptID;
				$success = Table::updateRow("accounting_purchases", $values, $invoice->purchaseID, false);
			}
			if ($counter > 1) {
				echo "<br>********** tupla receipt - " . $invoice->invoiceID;
			}
			if ($counter == 0) {
				echo "<br>********** nolla receiptiä löytyi";
			}
			$loopcounter++;
			//if ($loopcounter > 10) break;
		}
	}
	
	
	

	private function checkDebetAndCredit() {
	
		echo "<br>Tsekataan receipts debet and credit";
		$receipts = Table::load('accounting_receipts');
		$entries = Table::load('accounting_entries');
	
		$rightcounter = 0;
		$failed = 0;
		foreach($receipts as $index => $receipt) {
			echo "<br>Käsitellä receipt - " . $receipt->receiptID;
	
			$receiptID = 0;
			$counter = 0;
			$foundReceiptID = 0;
			$debet = 0;
			$credit = 0;
			foreach($entries as $entryID => $entry) {
				if ($receipt->receiptID == $entry->receiptID) {
					$counter++;
					$foundReceiptID = $receipt->receiptID;
					if ($entry->amount > 0) {
						$debet = $debet + $entry->amount;
					} else {
						$credit = $credit + -1 * $entry->amount;
					}
				}
			}
			if (round($debet,2) == round($credit,2)) {
				echo "<br>Debet - " . $debet.  ", credit - " . $credit;
			} else {
				echo "<br>Debet - '" . $debet.  "', credit - '" . $credit . "' ***** ei täsmää - " . abs($debet-$credit);
				$failed++;
			}

			/*
			$deltadebet = abs($receipt->debet-$debet);
			$deltacredit = abs($receipt->credit-$credit);
	
			if ((round($receipt->debet,2) != round($debet,2)) || (round($receipt->credit,2) != round($credit,2))) {
				echo "<br>Receipt debet tai credit ei täsmää ******************************* ";
				echo "<br> -- debet - " . $receipt->debet . " vs. " . $debet;
				echo "<br> -- credit - " . $receipt->credit. " vs. " . $credit;
				$values = array();
				$values['Debet'] = $debet;
				$values['Credit'] = $credit;
				$failed++;
					
				//$success = Table::updateRow("accounting_receipts", $values, $receipt->receiptID, false);
			}
				
				
			if ($counter == 0) {
				echo "<br>****** WARNING: Receipt without entries...";
			} else {
				$rightcounter++;
			}
				
			/*
				if ($counter == 1) {
				echo "<br> -- one found update receiptID - " . $foundReceiptID;
	
				$values = array();
				$values['ReceiptID'] = $foundReceiptID;
				$success = Table::updateRow("sales_invoices", $values, $invoice->invoiceID, false);
				}
				if ($counter > 1) {
				echo "<br>********** tupla receipt - " . $invoice->invoiceID;
				}
				if ($counter == 0) {
				echo "<br>********** nolla receiptiä löytyi";
				}
				*/
		}
		
		/*
		echo "<br>finished rightcounter - " . $rightcounter;
		echo "<br>finished rightcounter - " . count($receipts);
		echo "<br>finished failed - " . $failed;
	
		echo "<br>Checking entries parents";
		$foundreceipts = array();
		foreach($entries as $entryID => $entry) {
			if (isset($receipts[$entry->receiptID])) {
				$foundreceipts[$entry->receiptID] = 1;
				$counter++;
			} else {
				echo "<br>***** WARNING: entry without receipt - entryID:" . $entry->entryID . " - receiptID:" . $entry->receiptID;
			}
		}
		echo "<br>Coutner - " . $counter;
		echo "<br>Coutner - " . count($foundreceipts);
		*/
	}
	
	
	private function updateReceiptDebetAndCredit() {
	
		echo "<br>Päivitetään receipts debet and credit";
		$receipts = Table::load('accounting_receipts');
		$entries = Table::load('accounting_entries');
		
		$rightcounter = 0;
		$failed = 0;
		foreach($receipts as $index => $receipt) {
			echo "<br>Käsitellä receipt - " . $receipt->receiptID;
	
			$receiptID = 0;
			$counter = 0;
			$foundReceiptID = 0;
			$debet = 0;
			$credit = 0;
			foreach($entries as $entryID => $entry) {
				if ($receipt->receiptID == $entry->receiptID) {
					$counter++;
					$foundReceiptID = $receipt->receiptID;
					if ($entry->amount > 0) {
						$debet = $debet + $entry->amount;
					} else {
						$credit = $credit + -1 * $entry->amount;
					}
				}
			}
			if (round($debet,2) == round($credit,2)) {
				echo "<br>Debet - " . $debet.  ", credit - " . $credit;
			} else {
				echo "<br>Debet - '" . $debet.  "', credit - '" . $credit . "' ***** ei täsmää - " . abs($debet-$credit);
				$failed++;
			}
			
			$deltadebet = abs($receipt->debet-$debet);
			$deltacredit = abs($receipt->credit-$credit);
				
			if ((round($receipt->debet,2) != round($debet,2)) || (round($receipt->credit,2) != round($credit,2))) {
				echo "<br>Receipt debet tai credit ei täsmää ******************************* ";
				echo "<br> -- debet - " . $receipt->debet . " vs. " . $debet;
				echo "<br> -- credit - " . $receipt->credit. " vs. " . $credit;
				$values = array();
				$values['Debet'] = $debet;
				$values['Credit'] = $credit;
				$failed++;
					
				//$success = Table::updateRow("accounting_receipts", $values, $receipt->receiptID, false);
			}  
			
			
			if ($counter == 0) {
				echo "<br>****** WARNING: Receipt without entries...";
			} else {
				$rightcounter++;
			}
			
			/*
			if ($counter == 1) {
				echo "<br> -- one found update receiptID - " . $foundReceiptID;
	
				$values = array();
				$values['ReceiptID'] = $foundReceiptID;
				$success = Table::updateRow("sales_invoices", $values, $invoice->invoiceID, false);
			}
			if ($counter > 1) {
				echo "<br>********** tupla receipt - " . $invoice->invoiceID;
			}
			if ($counter == 0) {
				echo "<br>********** nolla receiptiä löytyi";
			}
			*/
		}
		echo "<br>finished rightcounter - " . $rightcounter;
		echo "<br>finished rightcounter - " . count($receipts);
		echo "<br>finished failed - " . $failed;
		
		echo "<br>Checking entries parents";
		$foundreceipts = array();
		foreach($entries as $entryID => $entry) {
			if (isset($receipts[$entry->receiptID])) {
				$foundreceipts[$entry->receiptID] = 1;
				$counter++;
			} else {
				echo "<br>***** WARNING: entry without receipt - entryID:" . $entry->entryID . " - receiptID:" . $entry->receiptID;
			}
		}
		echo "<br>Coutner - " . $counter;
		echo "<br>Coutner - " . count($foundreceipts);
	}
	
	
	
	// Kopioitu Receipts->getNextReceiptNumber, pitäisi ehkä siirtää johonkin yleiseen paikkaan, accounting?
	private function getNextReceiptNumber($receiptsetID) {
	
		global $mysqli;
	
		$sql = "SELECT * FROM accounting_receipts WHERE ReceiptsetID=" . $receiptsetID . " ORDER BY Receiptnumber DESC LIMIT 1";
		$result = $mysqli->query($sql);
		$row = $result->fetch_array();
	
		if ($row == null) {
			$receiptset = Table::loadRow("accounting_receiptsets", $receiptsetID);
			$receiptnumber = $receiptset->startnumber;
		} else {
			$receiptnumber = intval($row['Receiptnumber']);
			$receiptnumber++;
		}
		return $receiptnumber;
	}
	
}

?>
