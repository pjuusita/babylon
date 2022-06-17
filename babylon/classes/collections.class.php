<?php



class Collections {
	
	const ENTRY_LINKTYPE_PURCHASEROW = 10;
	const ENTRY_LINKTYPE_INVOICEROW = 11;
	const ENTRY_LINKTYPE_PURCHASEINVOICE = 12;
	const ENTRY_LINKTYPE_SALESINVOICE = 13;
	
	const ADDRESSTYPE_POSTAL = 1;
	const ADDRESSTYPE_LOCATION = 2;
	
	public static function getAddresstypes() {
		$addresstypes = array();
		$addresstypes[Collections::ADDRESSTYPE_POSTAL] = "Postiosoite";
		$addresstypes[Collections::ADDRESSTYPE_LOCATION] = "Käyntiosoite";
		return $addresstypes;
	}
	
	const INVOICESTATE_OPEN = 0;
	const INVOICESTATE_ACCEPTED = 1;
	const INVOICESTATE_WAITING = 2;
	const INVOICESTATE_PARTLYPAID = 3;
	const INVOICESTATE_PAID = 4;
	
	
	public static function getInvoiceStates() {
		$invoicestates = array();
		$invoicestates[Collections::INVOICESTATE_OPEN] = "Avoin";
		$invoicestates[Collections::INVOICESTATE_ACCEPTED] = "Hyväksytty";
		$invoicestates[Collections::INVOICESTATE_WAITING] = "Odottaa maksua";
		$invoicestates[Collections::INVOICESTATE_PARTLYPAID] = "Osittain maksettu";
		$invoicestates[Collections::INVOICESTATE_PAID] = "Maksettu";
		return $invoicestates;
	}
	
	

	const PURCHASESTATE_OPEN = 0;
	const PURCHASESTATE_ACCEPTED = 1;
	//const PURCHASESTATE_WAITINGPAYMENT = 2;
	const PURCHASESTATE_PARTLYPAID = 2;
	const PURCHASESTATE_PAID = 3;
	const PURCHASESTATE_CONFIRMED = 4;
	
	
	public static function getPurchaseStates() {
		$invoicestates = array();
		$invoicestates[Collections::PURCHASESTATE_OPEN] = "Avoin";
		$invoicestates[Collections::PURCHASESTATE_ACCEPTED] = "Hyväksytty";
		//$invoicestates[PURCHASESTATE_WAITINGPAYMENT] = "Odottaa maksua";
		$invoicestates[Collections::PURCHASESTATE_PARTLYPAID] = "Osittain maksettu";
		$invoicestates[Collections::PURCHASESTATE_PAID] = "Maksettu";
		$invoicestates[Collections::PURCHASESTATE_CONFIRMED] = "Kohdistettu tiliotteelta";
		return $invoicestates;
	}
	
	
	const BANKSTATEMENTSTATE_0 = 0;
	const BANKSTATEMENTSTATE_1 = 1;
	const BANKSTATEMENTSTATE_2 = 2;
	const BANKSTATEMENTSTATE_3 = 3;
	const BANKSTATEMENTSTATE_LINKED = 4;
	
	public static function getBankStatementStates() {
		$invoicestates = array();
		$invoicestates[Collections::BANKSTATEMENTSTATE_0] = "Avoin1";
		$invoicestates[Collections::BANKSTATEMENTSTATE_1] = "Käsittelyssä";	// ??
		$invoicestates[Collections::BANKSTATEMENTSTATE_2] = "Avoin3";	// ??
		$invoicestates[Collections::BANKSTATEMENTSTATE_3] = "Avoin4";	// ??
		$invoicestates[Collections::BANKSTATEMENTSTATE_LINKED] = "Linkitetty";
		return $invoicestates;
	}
	
	
	const PAYCHECKSTATE_OPEN = 0;
	const PAYCHECKSTATE_CHECKED = 1;
	const PAYCHECKSTATE_PAID = 3;
	const PAYCHECKSTATE_LINKED = 4;
	
	
	public static function getPaycheckStates() {
		$paycheckstates = array();
		$paycheckstates[Collections::PAYCHECKSTATE_OPEN] = "Avoin";
		$paycheckstates[Collections::PAYCHECKSTATE_CHECKED] = "Hyväksytty";
		$paycheckstates[Collections::PAYCHECKSTATE_PAID] = "Maksettu";
		$paycheckstates[Collections::PAYCHECKSTATE_LINKED] = "Maksettu, ja kohdistettu";
		return $paycheckstates;
	}
	

	


	const PAYMENTSOURCE_PURCHASES = 1;
	
	
	
	const PURCHASETYPE_CASHRECEIPT = 1;
	const PURCHASETYPE_CARD = 2;
	const PURCHASETYPE_BANKACCOUNT = 3;
	const PURCHASETYPE_INVOICE = 4;
	const PURCHASETYPE_PERSON = 5;
	
	public static function getPurchaseTypes() {
		$purchasetypes = array();
		$purchasetypes[Collections::PURCHASETYPE_CASHRECEIPT] = "Käteiskuitti";
		$purchasetypes[Collections::PURCHASETYPE_CARD] = "Korttiosto";
		//$purchasetypes[Collections::PURCHASETYPE_BANKACCOUNT] = "Maksettu pankkitililtä";
		$purchasetypes[Collections::PURCHASETYPE_BANKACCOUNT] = "Nettiosto";	// Maksettu suoraa pankkitililtä
		$purchasetypes[Collections::PURCHASETYPE_INVOICE] = "Ostolasku";
		$purchasetypes[Collections::PURCHASETYPE_PERSON] = "Yksityishenkilön maksama osto";
		return $purchasetypes;
	}
	
		
	const PAYMENTTYPE_CASH = 1;
	const PAYMENTTYPE_DEPTHS = 2;
	const PAYMENTTYPE_BANKACCOUNT = 3;
	const PAYMENTTYPE_SALARY = 4;
	const PAYMENTTYPE_UNKNOWN = 99;		// käytetään kun lasku merkitään manuaalisesti maksetuksi
	
	public static function getPaymentTypes() {
		$paymenttypes = array();
		$paymenttypes[Collections::PAYMENTTYPE_CASH] = "Odottaa maksua käteiskassasta";
		$paymenttypes[Collections::PAYMENTTYPE_DEPTHS] = "Odottaa siirtoa velkoihin";
		$paymenttypes[Collections::PAYMENTTYPE_BANKACCOUNT] = "Odottaa maksua pankkitililtä";
		$paymenttypes[Collections::PAYMENTTYPE_SALARY] = "Maksetaan seuraavassa palkassa";
		$paymenttypes[Collections::PAYMENTTYPE_UNKNOWN] = "Merkitty maksetuksi";	// merkitään 
		
		/*
		$paymenttypes[Collections::PAYMENTTYPE_CASH] = "Käteiskassa";
		$paymenttypes[Collections::PAYMENTTYPE_BANKACCOUNT] = "Pankkitili";
		$paymenttypes[Collections::PAYMENTTYPE_CARD] = "Pankkikortti";
		$paymenttypes[Collections::PAYMENTTYPE_PERSON] = "Henkilön oma maksu";
		*/
		return $paymenttypes;
	}
	
	public static function getPaymentPaidTypes() {
		$paymenttypes = array();
		$paymenttypes[Collections::PAYMENTTYPE_CASH] = "Maksettu käteiskassasta";
		$paymenttypes[Collections::PAYMENTTYPE_DEPTHS] = "Siirretty ostovelkoihin";
		$paymenttypes[Collections::PAYMENTTYPE_BANKACCOUNT] = "Maksettu pankkitililtä";
		$paymenttypes[Collections::PAYMENTTYPE_SALARY] = "Maksettu palkassa -- linkki tähän";
		return $paymenttypes;
	}
	
	
	const PAYMENTSTATUS_OPEN = 0;				// Avoin maksu
	const PAYMENTSTATUS_ACCEPTED = 1;				// Merkitty maksetuksi, ei kohdistettu tiliotteelta
	const PAYMENTSTATUS_PAID = 3;				// Merkitty maksetuksi, ei kohdistettu tiliotteelta
	const PAYMENTSTATUS_CONFIRMED = 4;			// Kohdistettu tiliotteelta
	
	
	public static function getPaymentStatuses() {
		$paymentstatuses = array();
		$paymentstatuses[Collections::PAYMENTSTATUS_OPEN] = "Ei maksettu";
		$paymentstatuses[Collections::PAYMENTSTATUS_ACCEPTED] = "Hyväksytty";
		$paymentstatuses[Collections::PAYMENTSTATUS_PAID] = "Maksettu";
		$paymentstatuses[Collections::PAYMENTSTATUS_CONFIRMED] = "Kohdistettu tiliotteelta";
		return $paymentstatuses;
	}
	
	
	const PAYBACKTYPE_CASH = 1;
	const PAYBACKTYPE_DEPTHS = 2;
	const PAYBACKTYPE_PAYMENT = 3;
	const PAYBACKTYPE_SALARY = 4;
	
	public static function getPaybackTypes() {
		$paybacktypes = array();
		$paybacktypes[Collections::PAYBACKTYPE_CASH] = "Maksetaan käteisellä";
		$paybacktypes[Collections::PAYBACKTYPE_DEPTHS] = "Siirretään velkoihin";
		$paybacktypes[Collections::PAYBACKTYPE_PAYMENT] = "Maksetaan pankkitililtä";
		$paybacktypes[Collections::PAYBACKTYPE_SALARY] = "Maksetaan seuraavassa palkassa";
		return $paybacktypes;
	}
	

	const DUEDATEUSAGE_NODUEDATE = 0;			// Ei eräpäivää, esim. käteislaskut ja pannkikorttiostot, eräpäiväksi asetetaan ostopäivä
	const DUEDATEUSAGE_CURRENTDATE = 1;			// käytetään eräpäivänä oletuksena ostolaskun päiväystä, ei muokata lisäys ikkunalla 
	const DUEDATEUSAGE_FROMSUPPLIER = 2;		// Oletuksena eräpäivä napataan toimittajan maksuajan perusteella
	
	public static function getDueDateUsageSelection() {
		$paybacktypes = array();
		$paybacktypes[Collections::DUEDATEUSAGE_NODUEDATE] = "Ei käytössä";
		$paybacktypes[Collections::DUEDATEUSAGE_CURRENTDATE] = "Ostohetki";
		$paybacktypes[Collections::DUEDATEUSAGE_FROMSUPPLIER] = "Asiakkaan maksuaika";
		return $paybacktypes;
	}
	
	public static function getDueDateUsageSelectionShort() {
		$paybacktypes = array();
		$paybacktypes[Collections::DUEDATEUSAGE_NODUEDATE] = "-";
		$paybacktypes[Collections::DUEDATEUSAGE_CURRENTDATE] = "X";
		$paybacktypes[Collections::DUEDATEUSAGE_FROMSUPPLIER] = "Toim.";
		return $paybacktypes;
	}
	
	
	
	/**
	 * Tätä käytetään accounting_entries.linktypeID kertomaan mihin laskuun kyseinen entry on linkitetty.
	 * Linkittäminen tarkoittaa sitä, mikä on kyseisen linkitetyn objektin saamis- tai velkatilin maksurivi.
	 * Tätä voidaan käyttää ainakin pankkitilivientien tapauksessa, mutta varmaan myös muiden saamistilien
	 * kanssa.
	 *
	 */
	public function getEntryLinkTypes() {
	
		$entrylinktypes = array();
		$entrylinktypes[0] = "";
		$entrylinktypes[1] = "Myyntilaskun maksusuoritus";
		$entrylinktypes[2] = "Liikamaksu";
		$entrylinktypes[3] = "Pankkitili panot";
		$entrylinktypes[4] = "Saamisvienti maksettu";					// linkki ehkä receiptID:hen
		$entrylinktypes[5] = "Ylimaksu, yksityisasiakas";				// linkki ehkä clientID:hen
		$entrylinktypes[6] = "Ylimaksu, yritysasiakas";					// linkki ehkä companyID:hen
		$entrylinktypes[7] = "Maskupalautus, yksityisasiakas";			// linkki ehkä clientID
		$entrylinktypes[8] = "Maskupalautus, yritysasiakas";			// linkki ehkä companyID
		
		return $entrylinktypes;
	}
	
	

	public static function getMonths() {
		$monthlist = array();
		$monthlist['01'] = "Tammi";
		$monthlist['02'] = "Helmi";
		$monthlist['03'] = "Maalis";
		$monthlist['04'] = "Huhti";
		$monthlist['05'] = "Touko";
		$monthlist['06'] = "Kesä";
		$monthlist['07'] = "Heinä";
		$monthlist['08'] = "Elo";
		$monthlist['09'] = "Syys";
		$monthlist['10'] = "Loka";
		$monthlist['11'] = "Marras";
		$monthlist['12'] = "Joulu";
		return $monthlist;
	}
	
	
	public static function generatePeriodTimescales($period, &$selectedindex, $currentdate = null, $type = 1) {
	
		$comments = false;
		$selection = array();
		$selectionindex = 0;
		$months = Collections::getMonths();
	
		if ($currentdate == null) {
			$currentdate = date("Y-m-d");
		}
		if ($comments) echo "<br>Current - " . $currentdate;
		if ($comments) echo "<br>" . $period->startdate . " - " . $period->enddate;
			
		$counter = 0;
		$startdate = $period->startdate;
		$quarterstart = $period->startdate;
		$quartercounter = 0;
		$quaretertemp = 0;
	
		while ($startdate  < $period->enddate) {
	
			//echo "<br>Creating - " . $startdate . " - "  . $period->enddate;
			$month = substr($startdate, 5, 2);
			$year = substr($startdate, 0, 4);
			$enddate = date("Y-m-t", strtotime($startdate));
	
	
			if ($comments) echo "<br>aa " . $year . "/" . $month . " --- " . sqlDateToStr($startdate) . " - " . sqlDateToStr($enddate);
			$selectionindex++;
			$row = new Row();
			$row->selectionID = $selectionindex;
			$row->year = $year;
			if ($type == 1) {
				$row->name = $months[$month];
			} else {
				$row->name = $year . "-" . $month;
			}
			$row->startsql = $startdate;
			$row->endsql = $enddate;
			$row->startdate = sqlDateToStr($startdate);
			$row->enddate = sqlDateToStr($enddate);
			$selection[$selectionindex] = $row;
	
			if (($currentdate >= $startdate) && ($currentdate <= $enddate)) {
				if ($comments) echo "<br>*********** current";
				//$selectedindex = $selectionindex;
			}
	
			$quaretertemp++;
			if ($quaretertemp == 3) {
				$quaretertemp = 0;
				$quartercounter++;
				if ($comments) echo "<br>" . $year . "/Q" . $quartercounter . " --- " . sqlDateToStr($quarterstart) . " - " . sqlDateToStr($enddate);
				$quarterstart = $startdate;
	
				$row = new Row();
				$selectionindex++;
				$row->rowID = $selectionindex;
				$row->year = $year;
				$row->name = $year . "/Q" . $quartercounter;
				$row->startsql = $quarterstart;
				$row->endsql = $enddate;
				$row->startdate = sqlDateToStr($quarterstart);
				$row->enddate = sqlDateToStr($enddate);
				//$selection[$selectionindex] = $row;
			}
	
			$monthnumber = intval($month);
			$monthnumber++;
			if ($monthnumber > 12) {
				$monthnumber = 1;
				$year = intval($year);
				$year++;
			}
			if ($monthnumber < 10) $monthstr = "0" . $monthnumber;
			else $monthstr = $monthnumber;
			$startdate = $year . "-" . $monthstr . "-01";
	
			$counter++;
			if ($counter > 100) break;
	
		}
		return $selection;
	}
	
	
	
	
	

	const PENSIONINSURANCETYPE_TYEL = 1;			// Palkansaajan työttömyysvakuutusmaksu
	const PENSIONINSURANCETYPE_YEL = 2;			// Palkansaajan työttömyysvakuutusmaksu
	const PENSIONINSURANCETYPE_MYEL = 3;			// Palkansaajan työttömyysvakuutusmaksu
	
	

	public static function getPensionInsuranceTypes() {
		$types = array();
		$types[Collections::PENSIONINSURANCETYPE_TYEL] = "TyEL";
		$types[Collections::PENSIONINSURANCETYPE_YEL] = "YEL";
		$types[Collections::PENSIONINSURANCETYPE_MYEL] = "MyEL";
		return $types;
	}
	
	

	const SALARYCATEGORY_SALARY = 1;		// Palkat ja lisät
	const SALARYCATEGORY_REPAYMENT = 2;		// Verottomat korvaukset
	const SALARYCATEGORY_EXPENSE = 3;		// Palkan sivukulu
	const SALARYCATEGORY_DEDUCTION = 4;		// Vähennys
	const SALARYCATEGORY_INFO = 5;			// Ei laskettava infotieto, päivärahamaksu on tällainen
	
	//const SALARYCATEGORY_		// Luontoisetu
	//const SALARYCATEGORY_		// Lomat ja poissaolot
	//const SALARYCATEGORY_		// verottomat kustannuskorvaukset
	//const SALARYCATEGORY_		// laskennalliset
	
	
	public static function getSalaryCategories() {
		$salarycategories = array();
		$salarycategories[Collections::SALARYCATEGORY_SALARY] = "Palkat ja lisät";
		$salarycategories[Collections::SALARYCATEGORY_REPAYMENT] = "Veroton";
		$salarycategories[Collections::SALARYCATEGORY_EXPENSE] = "Kulu";
		$salarycategories[Collections::SALARYCATEGORY_DEDUCTION] = "Vähennys";
		return $salarycategories;
	}
	
	
	

	const RECEIPTTYPE_NONE = 0;
	const RECEIPTTYPE_PAYABLE = 1;
	const RECEIPTTYPE_RECEIVABLE = 2;
	const RECEIPTTYPE_PAYROLL = 3;
	const RECEIPTTYPE_OTHER = 4;
	
	// Tämä on ehkä korvattu costpooltypellä, siirretty entryyn...
	public static function getReceiptTypes() {
		$types = array();
		$types[Collections::RECEIPTTYPE_NONE] = "Ei kiinnitetty";			// sourceID ei ole asetettu
		$types[Collections::RECEIPTTYPE_PAYABLE] = "Ostovelat";				// purchaseID, pitäisi olla supplierID
		$types[Collections::RECEIPTTYPE_RECEIVABLE] = "Saamiset";				// invoiceID, pitäisi olla clientID
		$types[Collections::RECEIPTTYPE_PAYROLL] = "Velat työntekijöille";	// paycheckID, pitäisi olla personID
		$types[Collections::RECEIPTTYPE_OTHER] = "Lisää tähän";
		return $types;
	}
	
	const COSTPOOLTYPE_NONE = 0;
	const COSTPOOLTYPE_ASSET = 1;
	const COSTPOOLTYPE_WORKER = 2;
	const COSTPOOLTYPE_CLIENT = 3;
	const COSTPOOLTYPE_SUPPLIER = 4;
	const COSTPOOLTYPE_LIABILITY = 5;
	
	
	public static function getCostpoolTypes() {
		$types = array();
		$types[Collections::COSTPOOLTYPE_ASSET] = "Omaisuus";				// purchaseID, pitäisi olla supplierID
		$types[Collections::COSTPOOLTYPE_WORKER] = "Työvoima";				// invoiceID, pitäisi olla clientID
		$types[Collections::COSTPOOLTYPE_CLIENT] = "Asiakkas";	// paycheckID, pitäisi olla personID
		$types[Collections::COSTPOOLTYPE_SUPPLIER] = "Toimittaja";
		$types[Collections::COSTPOOLTYPE_LIABILITY] = "Lainat";
		return $types;
	}
	
	
}