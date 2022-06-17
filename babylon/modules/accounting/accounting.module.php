<?php

/**
 * Tannne pitaisi tulla instanssi luokasta, jonka avulla kerrotaan kaikki tarpeellinen
 * taman modulin sisallasta. Erityisesti kerrotaan mita tietokantatauluja tarvitaan seka
 * mika niiden rakenne on.
 *
 * Tata luokkaa kaytetaan automaattisessa ajossa, jonka avulla tsekataan onko tietokanta
 * ja koodi synkassa.
 *
 * Mahdollisesti taman avulla voidaan muuttaa osaa tietokannoista reaali sisallasta row tauluksi
 * ja painvastoin.
 *
 * Settings sectionissa pitaa pystya ainakin asettamaan maksutavat (payment methods). Mahdollisesti
 * maksutapaan pitaa liittaa myas muita asetuksia, jotka kertovat miten kyseista maksutapaa
 * tulee kasitella raporteissa ja muualla. Mahdollisesti maksutpaa  pitaa myas sitoa johonkin tiliin,
 * jolloin voidaan seurata sen saldoa. Pankkisiirto (laskuista) nakyy vain se osa laskuista joka
 * on maksamatta. Pitaa olla myas merkittyna se, milloin kirjanpitoa on viimeksi paivitetty.
 *
 * Toisaalta etusivulla voisi olla nimenomaan hyadyllista seurata nykyisen kuukauden kehitysta, mutta
 * tama on accounting (laskentatoimi) modulin heinia
 *
 * TODO: Mietitään myöhemmin toimintoa, jossa ehkä jaetaan Taloushallinto moduli kahteen osaan, toinen
 * olisi accounting (taloushallinto) ja toinen olisi bookkeeping (kirjanpito). Mennään nyt toistaiseksi
 * kuitenkin tällä, kun jako on hieman työläs operaatio. Tietokantataulut ovat kuitenkin pääasiassa
 * myös kirjanpidossa käytettävät, eli tietokannan osalta suuria muutoksia ei taida tulla (accounting 
 * moduli on kirjanpitomodulin ennakkoehto). Hieman on epäselvää tarvitaanko tätä jaottelua ylipäätään,
 * mutta ideana kai olisi, että käyttöoikeudet yms. saataisiin tyylikkäästi selkäemmäksi. Kirjanpito
 * osio olisi ehkä enemmän taloushallinnon sisäiseen käyttöön, ja itse taloushallinto osio olisi
 * enemmänkin sitten johdon käyttöön.
 *
 */

/**
 * Tänne tarvittaisiin mahdollisesti oma käyttäjäryhmän lisäys, taloushallinto
 */
class AccountingModule extends AbstractModule {
	
	
	const ACCESSLEVEL_READ = 1;
	const ACCESSLEVEL_WRITE = 2;
	const ACCESSLEVEL_WRITEREMOVE = 3;
	const ACCESSLEVEL_ALL = 99;
	
	const ACCESSKEY_ACCOUNTING = 'accounting_accesskey';
	
	const ACCESSKEY_ACCOUNTING_SETTINGS = 'accesskey_accounting_settings';
	const ACCESSKEY_ACCOUNTING_PURCHASES = 'accesskey_accounting_purchases';				// Ostolaskujen syöttö ja käsittely
	const ACCESSKEY_ACCOUNTING_PURCHASEENTRIES = 'accesskey_accounting_purchaseentries';	// Ostolaskujen tiliöinti
	const ACCESSKEY_ACCOUNTING_PURCHASEACCEPT = 'accesskey_accounting_purchaseaccept';		// Ostolaskujen hyväksyntä
	const ACCESSKEY_ACCOUNTING_BOOKKEEPING = 'accesskey_accounting_bookkeeping';			// Kirjanpito
	const ACCESSKEY_ACCOUNTING_USERS = 'accesskey_accounting_users';						// Taloushallintokäyttäjien hallinta
	
	// TODO: pankkiyhteys pitää varmaan luoda omana modulinaan
	//const ACCESSKEY_ACCOUNTING_BANKCONNECTION_STATEMENTS = 'accounting_accesskey';
	//const ACCESSKEY_ACCOUNTING_BANKCONNECTION_PAYMENTS = 'accounting_accesskey';
	
	const MENUKEY_ACCOUNTING = 'menukey_accounting';
	const MENUKEY_BOOKKEEPING = 'menukey_bookkeeping';
	const INVOICETYPE_COMPANY = 1;
	const INVOICETYPE_PERSON = 2;
	const INVOICETYPE_GENERALSALE = 3;
	const LINKTYPE_PURCHASEINVOICE_PAYMENT = 1;
	const LINKTYPE_PURCHASEINVOICE_OVERPAYMENT = 2;
	const ACCESSRIGHTKEY_PURCHASES = 'purchases_accesskey';
	const MENUKEY_PURCHASES = 'menukey_purchases';
	const MENUKEY_PAYMENTS = 'menukey_payments';
	
	
	
	public function getDimensions() {
		$dimensions = array();
		$dimensions[Dimension::DIMENSION_COMPANY] = new Dimension(Dimension::DIMENSION_COMPANY, "Yritys", "Yritykset", "system_companies");
		$dimensions[Dimension::DIMENSION_BRANCH] = new Dimension(Dimension::DIMENSION_BRANCH, "Toimiala", "Toimialat", "system_branches");
		$dimensions[Dimension::DIMENSION_OFFICE] = new Dimension(Dimension::DIMENSION_OFFICE, "Toimipiste", "Toimipisteet", "system_offices");
		$dimensions[Dimension::DIMENSION_DEPARTMENT] = new Dimension(Dimension::DIMENSION_DEPARTMENT, "Osasto", "Osastot", "system_departments");
		return $dimensions;
	}
		
	
	public function getDefaultName() {
		return "Kirjanpito";
	}
	
	
	public function generateSettingsView($registry) {
		
		$comments = false;
		
		$this->registry = $registry;
		$controllerpath = "accounting/bookkeepingsettings";
		$controllername = "bookkeepingsettings";
		$actionname = "loadsettings";
		$actionfile = "bookkeepingsettings";
		
		$file = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . $controllerpath . DIRECTORY_SEPARATOR . $controllername . '.controller.php';
		
		if ($comments) echo "<br>file: "  . $file;
		
		if (is_readable($file) == false) {
			echo $file;
			die ('<br>404 Not Found - ' . $file);
		}
		
		include $file;
		$class = ucfirst($controllername) . 'Controller';
		$controller = new $class($registry);
		$action =  $actionname . 'Action';

		if (is_callable(array($controller, $actionname . 'Action')) == false) {
			echo "<br>Action not callable: " . $actionname . "";
			exit;
		}
		$controller->$action();
		
		$file = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . $controllerpath . DIRECTORY_SEPARATOR . $controllername . '.controller.php';
		
		if ($comments) echo "<br>Settingsfile - " . $registry->settingsfile;
		$modulefile = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . $controllerpath . DIRECTORY_SEPARATOR .$actionfile . ".php";
		if ($comments) echo "<br>finalfile - " . $modulefile;
		include $modulefile;
	}

	
	public function getAccessRights() {
		
		$accessrights = array ();
		$accessrights[AccountingModule::ACCESSKEY_ACCOUNTING_SETTINGS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[AccountingModule::ACCESSKEY_ACCOUNTING_PURCHASES] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[AccountingModule::ACCESSKEY_ACCOUNTING_PURCHASEENTRIES] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[AccountingModule::ACCESSKEY_ACCOUNTING_PURCHASEACCEPT] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[AccountingModule::ACCESSKEY_ACCOUNTING_BOOKKEEPING] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[AccountingModule::ACCESSKEY_ACCOUNTING_USERS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		return $accessrights;
	}
	
	
	
	public static function getBookkeepingPeriod() {
		if (isset ( $_GET ['periodID'] )) {
			$periodID = $_GET ['periodID'];
			setModuleSessionVar ( 'periodID', $periodID );
			return $periodID;
		} else {
			$periodID = getModuleSessionVar ( 'periodID' );
			if (($periodID == null) || ($periodID == '')) {
				// echo "<br>No period selected";
				$periods = Table::load ( 'accounting_periods' );
				foreach ( $periods as $index => $period ) {
					setModuleSessionVar ( 'periodID', $period->periodID );
					return $period->periodID;
					// echo "<br> -- period found - " . $period->name;
				}
			} else {
				// echo "<br>not null period *" . $periodID . "*";
			}
			return $periodID;
		}
	}
	
	
	/*
	 * public function getMenu($accessrights) {
	 *
	 * $menuitems = array();
	 * $accesslevel = $accessrights[AccountingModule::ACCESSKEY_ACCOUNTING];
	 * $menuindex = 0;
	 * if ($accesslevel > 0) {
	 * $menuitems[] = new Menu("Maksuliikenne","accounting/accountingsettings","showsettings",Menu::MENUKEY_ADMIN,null,450);
	 * }
	 * return $menuitems;
	 * }
	 */
	public function getMenu($accessrights) {
		$menuitems = array ();
		
		if ($accessrights[AccountingModule::ACCESSKEY_ACCOUNTING_PURCHASES] > 0) {
			$menuitems [] = new Menu ( "Ostot", "accounting/purchases", "showpurchases", Menu::MENUKEY_TOP, AccountingModule::MENUKEY_PURCHASES, 450 );
			$menuitems [] = new Menu ( "Ostolaskut", "accounting/purchases", "showpurchases", AccountingModule::MENUKEY_PURCHASES, null, 450 );
			$menuitems [] = new Menu ( "Ostomäärät", "accounting/suppliers", "showsuplyvolumes", AccountingModule::MENUKEY_PURCHASES, null, 453 );
			$menuitems [] = new Menu ( "Toimittajat", "accounting/suppliers", "showsuppliers", AccountingModule::MENUKEY_PURCHASES, null, 454);
		}
		
		if ($accessrights[AccountingModule::ACCESSKEY_ACCOUNTING_PURCHASEENTRIES] > 0) {
			// Ei vaikuta menuun, otetaan huomioon ostolasku-näkymällä
		}
		
		if ($accessrights[AccountingModule::ACCESSKEY_ACCOUNTING_PURCHASEACCEPT] > 0) {
			// TODO: Ostolaskujen hyväksynnällä ei ole omaa menuaan, ympätään ehkä etusivulle	
		}
		
		if ($accessrights[AccountingModule::ACCESSKEY_ACCOUNTING_BOOKKEEPING] > 0) {
			$menuitems [] = new Menu ( "Kirjanpito", "accounting/bookkeeping", "showbookkeeping", Menu::MENUKEY_TOP, AccountingModule::MENUKEY_ACCOUNTING, 450 );
			$menuitems [] = new Menu ( "Tulos", "accounting/incomestatement", "showincomestatement", AccountingModule::MENUKEY_ACCOUNTING, null, 454 );
			$menuitems [] = new Menu ( "Tase", "accounting/balancesheet", "showbalancesheet", AccountingModule::MENUKEY_ACCOUNTING, null, 456 );
			$menuitems [] = new Menu ( "Tositteet", "accounting/receipts", "showreceipts", AccountingModule::MENUKEY_ACCOUNTING, null, 458 );
			$menuitems [] = new Menu ( "Saldot", "accounting/accountbalances", "showaccountbalance", AccountingModule::MENUKEY_ACCOUNTING, null, 460 );
			$menuitems [] = new Menu ( "Tiliotteet", "accounting/bankstatements", "showbankstatements", AccountingModule::MENUKEY_ACCOUNTING, null, 462 );
			$menuitems [] = new Menu ( "Kohdistus", "accounting/alignment", "alignment", AccountingModule::MENUKEY_ACCOUNTING, null, 464 );
			$menuitems [] = new Menu ( "Avaustosite", "accounting/receipts", "showopeningreceipt", AccountingModule::MENUKEY_ACCOUNTING, null, 468 );
			$menuitems [] = new Menu ( "Kustannuspaikat", "accounting/costpools", "showcostpools", AccountingModule::MENUKEY_ACCOUNTING, null, 472 );
			$menuitems [] = new Menu ( "Omaisuus", "accounting/assets", "showassets", AccountingModule::MENUKEY_ACCOUNTING, null, 472 );
			$menuitems [] = new Menu ( "Lainat", "accounting/liabilities", "showliabilities", AccountingModule::MENUKEY_ACCOUNTING, null, 472 );
			$menuitems [] = new Menu ( "ALV-ilmoitus", "accounting/vatreport", "showvatreport", AccountingModule::MENUKEY_ACCOUNTING, null, 474 );
			$menuitems [] = new Menu ( "Tilikartta", "accounting/accountchart", "showaccountchart", AccountingModule::MENUKEY_ACCOUNTING, null, 480 );
		}
		
		// TODO: tätä menua ei saisi luoda uudelleen, jos käytäjällä on erikseen admin oikeudet...
		//		 tämän menuitemin pitäisi näyttää käyttäjäryhmät pudotusvalikossa ainoastaan taloushallintokäyttäjät
		//		 ja ilmeisesti kaikki taloushallintokäyttäjän alikäyttäjät
		if ($accessrights[AccountingModule::ACCESSKEY_ACCOUNTING_USERS] > 0) {
			$menuitems[] = new Menu("Käyttäjät","admin/users","showusers",Menu::MENUKEY_ADMIN, null, 9910);
		}
		
		if ($accessrights[AccountingModule::ACCESSKEY_ACCOUNTING_SETTINGS] > 0) {
			$menuitems[] = new Menu ( "Ostoasetukset", "accounting/accountingsettings", "showsettings", Menu::MENUKEY_ADMIN, null, 450 );
			$menuitems[] = new Menu ( "Kirjanpitoasetukset", "accounting/bookkeepingsettings", "showsettings", Menu::MENUKEY_ADMIN, null, 450 );
			$menuitems[] = new Menu ( "Rahaliikenneasetukset", "accounting/accountingsettings","showsettings",Menu::MENUKEY_ADMIN,null,450);
		}
		return $menuitems;
	}
	
	
	public function hasAccessRight($action) {
		
		return true;
		
		//$accesslevel = getAccessLevel(AccountingModule::ACCESSKEY_ACCOUNTING);
		//if ($accesslevel == 0)
		//	return false;
		
		switch ($action) {
			case "accountingsettings/showsettings" :
				return true;
				break;
			case "accountingsettings/showsettings" :
				return true;
				break;
			case "accountingsettings/updatepaymentmethod" :
				return true;
				break;
			case "accountingsettings/insertvat" :
				return true;
				break;
			case "accountingsettings/insertpaymentmethod" :
				return true;
				break;
			case "accountingsettings/insertbankaccount" :
				return true;
				break;
			case "accountingsettings/updatebankaccount" :
				return true;
				break;
			case "accountingsettings/updatepaymentcard" :
				return true;
				break;
			case "accountingsettings/insertpaymentcard" :
				return true;
				break;
			case "bookkeepingsettings/showsettings" :
				return true;
				break;
			case "bookkeepingsettings/updatedimension" :
				return true;
				break;
			case "bookkeepingsettings/insertperiod" :
				return true;
				break;
			case "bookkeepingsettings/insertreceiptset" :
				return true;
				break;
			case "bookkeepingsettings/insertvat" :
				return true;
				break;
			case "bookkeepingsettings/insertdimension" :
				return true;
				break;
			case "bookkeepingsettings/updatesettings" :
				return true;
				break;
			//case "bookkeepingsettings/insertcostpooltype" :
			//	return true;
			//	break;
			//case "bookkeepingsettings/insertcostpooltypeaccount" :
			//	return true;
			//	break;
			case "bookkeepingsettings/updatecostpooltype" :
				return true;
				break;
			case "bookkeepingsettings/updatecostpooltypeaccount" :
				return true;
				break;
			case "accountchart/showaccountchart" :
				return true;
				break;
			case "accountchart/showaccount" :
				return true;
				break;
			case "accountchart/insertaccount" :
				return true;
				break;
			case "accountchart/updateaccount" :
				return true;
				break;
			case "receipts/showreceipts" :
				return true;
				break;
			case "receipts/copyreceipt" :
				return true;
				break;
			case "receipts/showreceipt" :
				return true;
				break;
			case "receipts/insertreceipt" :
				return true;
				break;
			case "receipts/showopeningreceipt" :
				return true;
				break;
			case "receipts/insertentry" :
				return true;
				break;
			case "receipts/insertpaymententry" :
				return true;
				break;
			case "receipts/removeentry" :
				return true;
				break;
			case "receipts/removereceipt" :
				return true;
				break;
			case "receipts/updateentry" :
				return true;
				break;
			case "receipts/updatereceipt" :
				return true;
				break;
			case "costpools/showcostpools" :
				return true;
				break;
			case "costpools/showcostpool" :
				return true;
				break;
			case "costpools/insertcostpool" :
				return true;
				break;
			case "costpools/updatecostpool" :
				return true;
				break;
			case "costpools/removecostpool" :
				return true;
				break;
			case "costpools/insertcostpoolaccount" :
				return true;
				break;
			case "costpools/updatecostpoolaccount" :
				return true;
				break;
			case "accountbalances/showsummary" :
				return true;
				break;
			case "accountbalances/showaccountbalance" :
				return true;
				break;
			case "accountbalances/showaccountbalances" :
				return true;
				break;
			case "entries/showentries" :
				return true;
				break;
			case "entries/insertentry" :
				return true;
				break;
			case "bankstatements/insertreceipt" :
				return true;
				break;
			case "bankstatements/insertbankstatementrow" :
				return true;
				break;
			case "bankstatements/linkreceipt" :
				return true;
				break;
			case "bankstatements/showbankstatements" :
				return true;
				break;
			case "bankstatements/showbankstatement" :
				return true;
				break;
			case "bankstatements/bankstatementlinking" :
				return true;
				break;
			case "bankstatements/updatebankstatement" :
				return true;
				break;
			case "bankstatements/updatebankstatementrow" :
				return true;
				break;
			case "bankstatements/insertbankstatement" :
				return true;
				break;
			case "bankstatementevents/showbankstatementevents" :
				return true;
				break;
			case "bankstatementevents/insertbankstatementevent" :
				return true;
				break;
			case "bankstatementevents/updatebankstatementevent" :
				return true;
				break;
			case "bankstatementevents/removebankstatementevent" :
				return true;
				break;
			case "bankstatements/insertentry" :
				return true;
				break;
			case "bankstatements/updateentry" :
				return true;
				break;
			case "bankstatements/linksalesinvoice" :
				return true;
				break;
			case "bankstatements/linkpurchase" :
				return true;
				break;
			case "bankstatements/linksalesinvoicetoopenbalance" :
				return true;
				break;
			case "alignment/alignment" :
				return true;
				break;
			case "incomestatement/showincomestatement" :
				return true;
				break;
			case "incomestatement/incomestatementpdf" :
				return true;
				break;
			case "vatreport/showvatreport" :
				return true;
				break;
			case "balancesheet/showbalancesheet" :
				return true;
				break;
			case "balancesheet/getEntriesJSON" :
				return true;
				break;
			case "purchases/showpurchases" :
				return true;
				break;
			case "purchases/updatepurchase" :
				return true;
				break;
			case "purchases/showpurchase" :
				return true;
				break;
			case "purchases/acceptpurchase" :
				return true;
				break;
			case "purchases/insertentry" :
				return true;
				break;
			case "purchases/removepurchase" :
				return true;
				break;
			case "purchases/updateentry" :
				return true;
				break;
			case "purchases/removeentry" :
				return true;
				break;
			case "purchases/removepayment" :
				return true;
				break;
			case "purchases/returntoopen" :
				return true;
				break;
			case "purchases/insertpurchaserow" :
				return true;
				break;
			case "purchases/insertpurchase" :
				return true;
				break;
			case "purchases/getcostpoolvatJSON" :
				return true;
				break;
			case "purchases/copypurchase" :
				return true;
				break;
			case "purchases/insertpurchasewithrows" :
				return true;
				break;
			case "purchases/removepurchaserow" :
				return true;
				break;
			case "purchases/markaspayed" :
				return true;
				break;
			case "purchases/upload" :
				return true;
				break;
			case "purchases/download" :
				return true;
				break;
			case "purchases/removeattachment" :
				return true;
				break;
			case "purchases/updatepurchaserow" :
				return true;
				break;
			case "suppliers/getsupplierJSON" :
				return true;
				break;
			case "suppliers/insertsupplier" :
				return true;
				break;
			case "suppliers/updatesupplier" :
				return true;
				break;
			case "suppliers/showsuppliers" :
				return true;
				break;
			case "suppliers/showsupplier" :
				return true;
				break;
			case "suppliers/showsupplier" :
				return true;
				break;
			case "suppliers/insertdefaultrow" :
				return true;
				break;
			case "suppliers/updatedefaultrow" :
				return true;
				break;
			case "suppliers/updatedefaultrow" :
				return true;
				break;
			case "suppliers/getdefaultrows" :
				return true;
				break;
			case "payments/showcashflow" :
				return true;
				break;
			case "payments/showoutgoingpayments" :
				return true;
				break;
			case "payments/showincomingpayments" :
				return true;
				break;
			case "alignment/deductreceivables" :
				return true;
				break;
			case "alignment/linkpurchasetostatementrow" :
				return true;
				break;
			case "alignment/linksalesinvoicetostatementrow" :
				return true;
				break;
			case "alignment/insertbankstatementreceipt" :
				return true;
				break;
			case "balancesheet/showbalancesheet2" :
				return true;
				break;
		}
		return false;
	}
	
	
	public function hasAccess($accesskey) {
		return false;
	}
}

?>