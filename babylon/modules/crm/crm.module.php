<?php

/**
 *  Laajuus/scope liittyy ehka asiakashallinnan monipuolisuuteen. Mini versiossa
 *  yrityksilla on ainoastaan yksi osoite. Laajemmissa versioissa asiakkaalla voi 
 *  olla useampia toimipaikkoja, kustannuspaikkoja (tyamaa, halli, tehdas tms.) ja/tai
 *  tytaryhtiaita.
 *  
 *  Mini versiossa yrityksilla voi myas olla ainoastaan yksi laskutusosoite, laajemmassa
 *  versiossa voi olla useampia laskutusosoitteita.
 * 
 *
 */

class CrmModule extends AbstractModule {
	
	const ACCESSLEVEL_READ = 1;
	const ACCESSLEVEL_WRITE = 2;
	const ACCESSLEVEL_WRITEREMOVE = 3;
	const ACCESSLEVEL_ALL = 10;
	
	const ACCESSRIGHTKEY_CRM = 'crm_accesskey';
	
	
	const CLIENTTYPE_COMPANY = 1;
	const CLIENTTYPE_PERSON = 2;
	
	const ACCESSKEY_CRM_SETTINGS = 'accesskey_crm_settings';
	const ACCESSKEY_CRM_CLIENTS = 'accesskey_crm_clients';				
	
	
	public function getDefaultName() {
		return "Asiakasrekisteri";
	}
	

	public function getAccessRights() {
	
		$accessrights = array();
		
		$accessrights[CrmModule::ACCESSKEY_CRM_SETTINGS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[CrmModule::ACCESSKEY_CRM_CLIENTS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		
		
		//$accesslevels = array();
		//$accesslevels[CrmModule::ACCESSLEVEL_READ] = "crm_accesslevel_read";  // vain lukuoikeudet
		//$accesslevels[CrmModule::ACCESSLEVEL_WRITE] = "crm_accesslevel_write";	// Muokkaus ja  lisäys
		//$accesslevels[CrmModule::ACCESSLEVEL_WRITEREMOVE] = "crm_accesslevel_writeremove"; // Muokkaus, lisäys ja poisto
		//$accesslevels[CrmModule::ACCESSLEVEL_ALL] = "crm_accesslevel_all";			// Kaikki oikeudet
		//$accessrights[CrmModule::ACCESSRIGHTKEY_CRM] = $accesslevels;
		
		return $accessrights;
	}
	
	

	public function getMenu($accessrights) {
	
		$menuitems = array();
		$accesslevel = $accessrights[CrmModule::ACCESSKEY_CRM_SETTINGS];
		$menuindex = 0;
		if ($accesslevel > 0) {
			$menuitems[] = new Menu("Asiakasrekisteri","crm/companies","showcompanies",Menu::MENUKEY_TOP, "menukey_crm",200);
			$menuitems[] = new Menu("Yritykset","crm/companies","showcompanies","menukey_crm", null,210);
			$menuitems[] = new Menu("Henkilöt","crm/clients","showclients","menukey_crm",null,220);
			$menuitems[] = new Menu("Työkohteet","crm/locations","showlocations","menukey_crm",null,230);
			//$menuitems[] = new Menu("Asiakasasetukset","crm/settings","showsettings",Menu::MENUKEY_ADMIN,null,450);
		}
		return $menuitems;
	}
	
	
	
	/**
	 * Tämä funktio olettaaa, että modulin accessRightsit on jo asetettu frameworkin toimesta...
	 * Tänne ei pitäisi tulla edes jos kyseistä modulia ei ole käytössä.
	 *
	 * @param unknown $action
	 * @param unknown $scope
	 */
	public function hasAccessRight($action) {
	
		return true;
		
		$accesslevel = getAccessLevel(CrmModule::ACCESSRIGHTKEY_CRM);
		//echo "<br>CrmModule.hasAccessRight - " . $action . " - " . $accesslevel;
		if ($accesslevel == 0) return false;
		//echo "<br>CrmModule.hasAccessRight - " . $action . " - " . $accesslevel;
	
		switch($action) {
			case "companies/showcompanies":
				return true;
				break;
			case "companies/showcompany":
				return true;
				break;
			case "companies/showcompany":
				return true;
				break;
			case "companies/insertinvoiceaddress":
				return true;
				break;
			case "companies/updateinvoiceaddress":
				return true;
				break;
			case "companies/updatecompany":
				return true;
				break;
			case "companies/insertcompany":
				return true;
				break;
			case "clients/showclient":
				return true;
				break;
			case "clients/showclients":
				return true;
				break;
			case "clients/removeclient":
				return true;
				break;
			case "clients/insertclient":
				//if ($accesslevel != CrmModule::ACCESSRIGHT_READ) 
				return true;
				break;
		case "locations/showlocations":
				return true;
				break;
			case "locations/showlocation":
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