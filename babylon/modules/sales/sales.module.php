<?php


class SalesModule extends AbstractModule {
	
	const ACCESSLEVEL_READ = 1;
	const ACCESSLEVEL_WRITE = 2;
	const ACCESSLEVEL_WRITEREMOVE = 3;
	const ACCESSLEVEL_PRODUCTMANAGEMENT = 9;
	const ACCESSLEVEL_ALL = 99;
	
	const ACCESSRIGHTKEY_INVOICING = 'invoicing_accesskey';
	const ACCESSRIGHTKEY_PRODUCTMANAGEMENT = 'invoicing_productmanagement';
	
	const MENUKEY_INVOICING = 'menukey_invoicing';
	
	
	
	const CLIENTTYPE_COMPANY = 1;
	const CLIENTTYPE_PERSON = 2;
	const CLIENTTYPE_GENERALSALE = 3;
	
	
	public function getDefaultName() {
		return "Kirjanpito";
	}
	



	public function getDimensions() {
		$dimensions = array();
		$dimension[Dimension::DIMENSION_COMPANY] = new Dimension(Dimension::DIMENSION_COMPANY, "Yritys", "Yritykset", "system_companies");
		$dimension[Dimension::DIMENSION_BRANCH] = new Dimension(Dimension::DIMENSION_BRANCH, "Toimiala", "Toimialat", "system_branches");
		$dimension[Dimension::DIMENSION_OFFICE] = new Dimension(Dimension::DIMENSION_OFFICE, "Toimipiste", "Toimipisteet", "system_offices");
		$dimension[Dimension::DIMENSION_DEPARTMENT] = new Dimension(Dimension::DIMENSION_DEPARTMENT, "Osasto", "Osastot", "system_departments");
		
		//$dimensions['system_companies'] = "system_term_subsidiary";
		//$dimensions['system_industries'] = "system_term_industry";
		//$dimensions['system_offices'] = "system_term_office";
		//$dimensions['system_departments'] = "system_terms_department";
		return $dimensions;
	}
		
	
	public function getAccessRights() {
	
		$accessrights = array();
		$accessrights[SalesModule::ACCESSRIGHTKEY_INVOICING] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		return $accessrights;
	}
	
	

	public static function getBookkeepingPeriod() {
	
	
		if (isset($_GET['periodID'])) {
			$periodID = $_GET['periodID'];
			setModuleSessionVar('periodID', $periodID);
			return $periodID;
		} else {
			$periodID = getModuleSessionVar('periodID');
			if (($periodID == null) || ($periodID == '')) {
				//echo "<br>No period selected";
				$periods = Table::load('accounting_periods');
				foreach($periods as $index => $period) {
					setModuleSessionVar('periodID', $period->periodID);
					return $period->periodID;
					//echo "<br> -- period found - " . $period->name;
				}
			} else {
				//echo "<br>not null period *" . $periodID . "*";
			}
			return $periodID;
		}
	
	}
	
	
	
	public function getMenu($accessrights) {
	
		$menuitems = array();
		$accesslevel = $accessrights[SalesModule::ACCESSRIGHTKEY_INVOICING];
		$menuindex = 0;
		if ($accesslevel > 0) {
			$menuitems[] = new Menu("Myynti","sales/invoices","showinvoices",Menu::MENUKEY_TOP,SalesModule::MENUKEY_INVOICING,450);
			//$menuitems[] = new Menu("Myyntireskontra","sales/showunpaid","showinvoices",SalesModule::MENUKEY_INVOICING,null,450);
			$menuitems[] = new Menu("Myyntilaskut","sales/invoices","showunpaid",SalesModule::MENUKEY_INVOICING,null,450);
			//$menuitems[] = new Menu("Asiakasrekisteri","sales/clients","showclients",SalesModule::MENUKEY_INVOICING,null,450);
			$menuitems[] = new Menu("Tuotteet","sales/products","showproducts",SalesModule::MENUKEY_INVOICING,null,450);
			
			// Vaihtoehtoisest sopimukset
			//$menuitems[] = new Menu("Toistuvat laskut","sales/contracts","showcontracts",SalesModule::MENUKEY_INVOICING,null,450);
			$menuitems[] = new Menu("Myyntiasetukset","sales/salessettings","showsettings",Menu::MENUKEY_ADMIN,null,450); 
		}
		return $menuitems;
	}
	
	
	
	public function hasAccessRight($action) {
	
		return true;
		
		//echo "<br>SalesModule hasAccessRight " . $action;
		$accesslevel = getAccessLevel(SalesModule::ACCESSRIGHTKEY_INVOICING);
		//echo "<br>SalesModule accesslevel - " . $accesslevel;
		if ($accesslevel == 0) {
			echo "<br>Accesslevel false";
			return false;
		}
	
		switch($action) {
			case "salessettings/showsettings":
				return true;
				break;
			case "salessettings/insertproductgroup":
				return true;
				break;
			case "salessettings/updateproductgroup":
				return true;
				break;
			case "salessettings/removeproductgroup":
				return true;
				break;
			case "salessettings/insertcurrency":
				return true;
				break;
			case "salessettings/updatesettings":
				return true;
				break;
			case "invoices/markpayedinvoice":
				return true;
				break;
			case "invoices/marksendinvoice":
				return true;
				break;
			case "salessettings/updatecurrency":
				return true;
				break;
			case "salessettings/removecurrency":
				return true;
				break;
			case "salessettings/insertunit":
				return true;
				break;
			case "salessettings/updateunit":
				return true;
				break;
			case "salessettings/removeunit":
				return true;
				break;
			case "products/showproducts":
				return true;
				break;
			case "products/showproduct":
				return true;
				break;
			case "products/insertproduct":
				return true;
				break;
			case "products/updateproduct":
				return true;
				break;
			case "invoices/showinvoices":
				return true;
				break;
			case "invoices/showunpaid":
				return true;
				break;
			case "invoices/showinvoice":
				return true;
				break;
			case "invoices/insertinvoice":
				return true;
				break;
			case "invoices/updateinvoice":
				return true;
				break;
			case "invoices/updateentry":
				return true;
				break;
			case "invoices/insertinvoicerow":
				return true;
				break;
			case "invoices/updateinvoicerow":
				return true;
				break;
			case "invoices/removeinvoice":
				return true;
				break;
			case "invoices/acceptinvoice":
				return true;
				break;
			case "invoices/removeentry":
				return true;
				break;
			case "invoices/openinvoice":
				return true;
				break;
			case "invoices/copyinvoice":
				return true;
				break;
			case "invoices/insertentry":
				return true;
				break;
			case "invoices/removeinvoicerow":
				return true;
				break;
			case "invoices/getunaccountedinvoicesJSON":
				return true;
				break;
			case "salessettings/insertsaletype":
				return true;
				break;
			case "salessettings/updatesaletype":
				return true;
				break;
			case "salessettings/removesaletype":
				return true;
				break;
								
				
			case "invoices/tempupdate":
				return true;
				break;
		}	
		
		return false;
	}
	

	
	

	public function getSalesInvoiceTypes() {
	
		$invoicetypes = array();
	
		$invoicetype = new Row();
		$invoicetype->invoicetypeID = 1;
		$invoicetype->name = "Lasku";
		$invoicetypes[1] = $invoicetype;
	
		$invoicetype = new Row();
		$invoicetype->invoicetypeID = 2;
		$invoicetype->name = "Lähete";
		$invoicetypes[2] = $invoicetype;
	
		$invoicetype = new Row();
		$invoicetype->invoicetypeID = 3;
		$invoicetype->name = "Koontilasku";
		$invoicetypes[3] = $invoicetype;
	
		$invoicetype = new Row();
		$invoicetype->invoicetypeID = 4;
		$invoicetype->name = "Toistuva lasku";
		$invoicetypes[4] = $invoicetype;
		
		$invoicetype = new Row();
		$invoicetype->invoicetypeID = 5;
		$invoicetype->name = "Hyvityslasku";
		$invoicetypes[5] = $invoicetype;
		
		return $invoicetypes;
	}
	
	
	// TODO: myyntityyppejä olisi ehkä syytä pystyä itse lisäilemään...
	public function getSalesInvoiceClientTypes() {
	
		$invoicetypes = array();
	
		$invoicetype = new Row();
		$invoicetype->clienttypeID = SalesModule::CLIENTTYPE_COMPANY;
		$invoicetype->name = "Yritysasiakas";
		$invoicetypes[SalesModule::CLIENTTYPE_COMPANY] = $invoicetype;
	
		$invoicetype = new Row();
		$invoicetype->clienttypeID = SalesModule::CLIENTTYPE_PERSON;
		$invoicetype->name = "Kuluttaja-asiakas";
		$invoicetypes[SalesModule::CLIENTTYPE_PERSON] = $invoicetype;
	
		$invoicetype = new Row();
		$invoicetype->clienttypeID = SalesModule::CLIENTTYPE_GENERALSALE;
		$invoicetype->name = "Yleinen myynti";
		$invoicetypes[SalesModule::CLIENTTYPE_GENERALSALE] = $invoicetype;
	
		$invoicetype = new Row();
		$invoicetype->clienttypeID = SalesModule::CLIENTTYPE_GENERALSALE;
		$invoicetype->name = "Käteismyynti myynti";
		$invoicetypes[SalesModule::CLIENTTYPE_GENERALSALE] = $invoicetype;
		
		
		return $invoicetypes;
	}
	
	
	/*	TODO: tätä kutsutaan ainakin invoices.controllerista, tämä on yritetty siirtää collectionssiin
	 * 
	 * 
	 */
	public static function generatePeriodTimescales($period, &$selectedindex, $currentdate = null) {
	
		$comments = false;
		$selection = array();
		$selectionindex = 0;
	
		if ($currentdate == null) {
			$currentdate = date("Y-m-d");
		}
		if ($comments) echo "<br>Current - " . $currentdate;
		//$selectedindex = 0;
	
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
			$row->rowID = $selectionindex;
			$row->year = $year;
			$row->name = $year . "/" . $month;
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

	
	public function hasAccess($accesskey) {
		return false;
	}
	
}


?>