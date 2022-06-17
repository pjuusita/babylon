<?php


class EnterpriseModule extends AbstractModule {
	
	const ACCESSLEVEL_READ = 1;
	const ACCESSLEVEL_WRITE = 2;
	const ACCESSLEVEL_WRITEREMOVE = 3;
	const ACCESSLEVEL_PRODUCTMANAGEMENT = 9;
	const ACCESSLEVEL_ALL = 99;
	
	const ACCESSRIGHTKEY_CONTRACTS = 'contracts_accesskey';
	
	const MENUKEY_SERVICEDEALS = 'menukey_servicedeals';
	
	
	
	const CLIENTTYPE_COMPANY = 1;
	const CLIENTTYPE_PERSON = 2;
	const CLIENTTYPE_GENERALSALE = 3;
	
	
	public function getDefaultName() {
		return "Kirjanpito";
	}
	
	


	public function getDimensions() {
		$dimensions = array();
		$dimensions[Dimension::DIMENSION_SUBSYSTEMS] = new Dimension(Dimension::DIMENSION_SUBSYSTEMS, "Asiakkuus", "Asiakkuudet", "system_credentials");
		return $dimensions;
	}
	
	
	
	public function generateSettingsView($registry) {
	
		$comments = false;
		
		$this->registry = $registry;
		$controllerpath = "enterprise/contracts";
		$controllername = "contracts";
		$actionname = "showclients";
		$actionfile = "contracts";
	
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
	
		$accessrights = array();
		//$accesslevels = array();
		//$accesslevels[AbstractModule::ACCESSRIGHT_NONE] = "system_accesslevel_none";
		//$accesslevels[AbstractModule::ACCESSRIGHT_READ] = "crm_accesslevel_read";
		//$accesslevels[AbstractModule::ACCESSRIGHT_WRITE] = "payroll_accesslevel_all";
		$accessrights[EnterpriseModule::ACCESSRIGHTKEY_CONTRACTS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		return $accessrights;
	}
	
	
	
	public function getMenu($accessrights) {
	
		$menuitems = array();
		//$accesslevel = getAccessLevel(EnterpriseModule::ACCESSRIGHTKEY_CONTRACTS);
		$accesslevel = $accessrights(EnterpriseModule::ACCESSRIGHTKEY_CONTRACTS);
		$menuindex = 0;
		if ($accesslevel > 0) {
			$menuitems[] = new Menu("Asiakkuudet","enterprise/contracts","showclients",Menu::MENUKEY_TOP,EnterpriseModule::MENUKEY_SERVICEDEALS,250);
			//$menuitems[] = new Menu("Arkisto","enterprise/history","showclients",EnterpriseModule::MENUKEY_INVOICING,null,250);
		}
		return $menuitems;
	}
	
	
	
	public function hasAccessRight($action) {
	
		return true;
		
		if ($accesslevel == 0) {
			echo "<br>Accesslevel false";
			return false;
		}
	
		switch($action) {
			case "contracts/showcontracts":
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