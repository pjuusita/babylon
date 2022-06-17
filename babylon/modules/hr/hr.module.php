<?php


class HrModule extends AbstractModule {
	

	const ACCESSLEVEL_READ = 1;
	const ACCESSLEVEL_WRITE = 2;
	const ACCESSLEVEL_WRITEREMOVE = 3;
	const ACCESSLEVEL_ALL = 99;
	
	const ACCESSRIGHTKEY_HUMANRESOURCES = 'hr_accesskey';
	const MENUKEY_HUMANRESOURCES = 'menukey_hr';
	
	const ACCESSRIGHTKEY_ACCOUNTING = 'accounting_accesskey';
	

	const ACCESSKEY_HR_SETTINGS = 'accesskey_hr_settings';
	const ACCESSKEY_HR_HUMANRESOUCES = 'accesskey_hr_humanresources';
	
	
	public function getDefaultName() {
		return "Henkilöstö";
	}
	


	public function getDimensions() {
		$dimensions = array();
		$dimensions[Dimension::DIMENSION_COMPANY] = new Dimension(Dimension::DIMENSION_COMPANY, "Yritys", "Yritykset", "system_companies");
		$dimensions[Dimension::DIMENSION_BRANCH] = new Dimension(Dimension::DIMENSION_BRANCH, "Toimiala", "Toimialat", "system_branches");
		$dimensions[Dimension::DIMENSION_OFFICE] = new Dimension(Dimension::DIMENSION_OFFICE, "Toimipiste", "Toimipisteet", "system_offices");
		$dimensions[Dimension::DIMENSION_DEPARTMENT] = new Dimension(Dimension::DIMENSION_DEPARTMENT, "Osasto", "Osastot", "system_departments");
		return $dimensions;
	}
	
	
	
	public function getAccessRights() {
	
		$accessrights = array();
		
		$accessrights[HrModule::ACCESSKEY_HR_SETTINGS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[HrModule::ACCESSKEY_HR_HUMANRESOUCES] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		
		
		//$accesslevels = array();
		//$accesslevels[HrModule::ACCESSLEVEL_ALL] = "hr_accesslevel_all";
		//$accessrights[HrModule::ACCESSRIGHTKEY_HUMANRESOURCES] = $accesslevels;
	
		return $accessrights;
	}
	
	
	// TODO: Tämä on vähän työnalla, ei tietoa mihin tätä edes tarvittaisiin
	// 		 oleelliset toiminnot tulee jo palkanlaskennan mukana
	public function getMenu($accessrights) {
		$menuitems = array();
		//$accesslevel = getAccessLevel(HrModule::ACCESSRIGHTKEY_HUMANRESOURCES);
		
		$accesslevel = $accessrights[HrModule::ACCESSKEY_HR_HUMANRESOUCES];
		$menuindex = 0;
		if ($accesslevel > 0) {
			$menuitems[] = new Menu("Henkilöstö","hr/workers","showworkers",Menu::MENUKEY_TOP, "menukey_hr",300);
			$menuitems[] = new Menu("Työntekijät","hr/workers","showworkers","menukey_hr", null,3100);
			$menuitems[] = new Menu("Henkilöstö","hr/hrsettings","showsettings",Menu::MENUKEY_ADMIN,null,560);
		}
		return $menuitems;
	}
	
	
	
	public function hasAccessRight($action) {
	
		return true;
		
		//echo "<br>HrModule hasAccessRight " . $action;
		$accesslevel = getAccessLevel(HrModule::ACCESSRIGHTKEY_HUMANRESOURCES);
		//echo "<br>HrModule accesslevel - " . $accesslevel;
		if ($accesslevel == 0) {
			echo "<br>Accesslevel false";
			return false;
		}
	
		switch($action) {
			case "workers/showworker":
				return true;
				break;
			case "workers/insertworker":
				return true;
				break;		
			case "workers/insertworkcontract":
				return true;
				break;
			case "workers/updateworkcontract":
				return true;
				break;
			case "workers/inserttaxcard":
				return true;
				break;
			case "workers/updatetaxcard":
				return true;
				break;	
			case "workers/insertdeduction":
				return true;
				break;
			case "workers/updateworker":
				return true;
				break;
			case "workers/updatededuction":
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