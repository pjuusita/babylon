<?php



class ResourcetextsController extends AbstractController {

	
	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}

	
	public function indexAction() {
		//$this->showsettingsAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	public function showresourcetextsAction() {
		
		updateActionPath('Resurssitekstit');
		
		$moduleID = getModuleSessionVar('moduleID', 0);
		$this->registry->languageID = getModuleSessionVar('languageID', $_SESSION['languageID']);
		
		$this->registry->moduleID = $moduleID;
		$this->registry->modules = Table::load('system_modules');
		$this->registry->languages = Table::load('system_languages');
		if ($moduleID == 0) {
			$this->registry->resourcetexts = Table::load('system_resourcetext');
			$this->registry->module = null;
		} else {
			$this->registry->resourcetexts = Table::load('system_resourcetext', "WHERE ModuleID=" . $moduleID);
			$this->registry->module = $this->registry->modules[$moduleID];
		}
		$this->registry->template->show('admin/resourcetexts','resourcetexts');
	}
	
	

	public function insertresourcetextAction() {
	
		$values = array();
		$values['ModuleID'] = $_GET['moduleID'];
		$values['Resourcekey'] = $_GET['resourcekey'];
		$values['Value'] = $_GET['value'];
		$success = Table::addRow("system_resourcetext", $values);
		redirecttotal('admin/resourcetexts/showresourcetexts');
	}
	
	
	public function updateresourcetextAction() {
	
		$stringID = $_GET['id'];
		$values = array();
		$values['ModuleID'] = $_GET['moduleID'];
		$values['Resourcekey'] = $_GET['resourcekey'];
		$values['Value'] = $_GET['value'];
		$success = Table::updateRow("system_resourcetext", $values, $stringID);
		redirecttotal('admin/resourcetexts/showresourcetexts');
	}

	
	
	
	
	// yhteystiedot, tämä osio on näkyvissä kaikille käyttäjille, täällä on mm. 
	//  - tiimin tiedot
	//  - tiimilista
	//  - tiimien jäsenten yhteystiedot
	//  - vuokrafirman työntekijän tapauksessa täällä on toimipistekohtaiset puhelinnumerot
	//		- luottamusmiehen tiedot jne.
	//  - työterveyshuolto, päivistys, esimies, jne...
	//  - kaikenlaiset yleiset tiedot, vapaasti hallittavana myös sen mukaan kuka näkee
	//  - vakuutustiedot ehkä joissakin tapauksissa, eläkeyhtiö ehkä...
	//  - no tätä pitää miettiä, mutta vaikea tämä on riippuen yrityksen rakenteesta ja tarpeista.
	public function showcontactsettings() {
			
	}
	
	
	/*
	public function showsettingsAction() {
	
		$activemoduleID = getModuleSessionVar('settingsmoduleID', 0);
		$this->registry->selectedmoduleID = $activemoduleID;
		
		$settings = Table::load('system_settings');
	
		$modules = Table::load('system_modules', "WHERE SystemID=" . $_SESSION['systemID'] . " AND Active=1");
		
		$settingmodules = array();
		$tempmodule = new Row();
		$tempmodule->moduleID = 0;
		$tempmodule->name = "Yritys";
		$settingmodules[0] = $tempmodule;
		foreach($modules as $index => $module) {
			if ($module->moduletype == 0) {
				//$module->name = "Yritys";
				//$module->target = "";
				//$settingmodules[] = $module;
			} else {
				//$module->name = "Järjestelmäasetukset";
				$settingmodules[$module->moduleID] = $module;
			}
		}
		
		$this->registry->modules = $settingmodules;
		
		
		if ($activemoduleID == 0) {
			$this->registry->companies = Table::load('system_companies');
			$this->registry->dimensions = Table::load('system_dimensions');
			$this->registry->countries = Table::load('system_countries');
			$this->registry->addresstypes = Collections::getAddresstypes();
			$this->registry->offices = Table::load('system_offices');
			$this->registry->branches = Table::load('system_branches');
			$this->registry->departments = Table::load('system_departments');
			
			$addresses = Table::load('system_addresses');
			
			foreach($addresses as $index => $address) {
				$address->fullpostal = $address->postalcode . " " . $address->city;
			}
			$this->registry->addresses = $addresses;
		} else {
			$module = $this->registry->modules[$activemoduleID];
			$modulefile = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . $module->modulename . DIRECTORY_SEPARATOR . $module->modulename . ".module.php";
			//echo "<br>Modulefile - " . $modulefile;
			include $modulefile;
			$class = ucfirst($module->modulename) . 'Module';
			$this->registry->module = new $class();
		}
		
		

		$this->registry->template->show('admin/settings','settings');
	}
	
	
	
	public function showcompanysettingsAction() {
	
		$this->registry->companies = Table::load('system_companies');
		$this->registry->countries = Table::load('system_countries');
		$this->registry->addresstypes = Collections::getAddresstypes();
		$this->registry->offices = Table::load('system_offices');	
		$this->registry->branches = Table::load('system_branches');
		$this->registry->departments = Table::load('system_departments');
		
		$addresses = Table::load('system_addresses');
		
		foreach($addresses as $index => $address) {
			$address->fullpostal = $address->postalcode . " " . $address->city;
		}
		$this->registry->addresses = $addresses;
		
		$tempdimensions = Table::load('system_dimensions');
		$dimensions = array();
		foreach($this->registry->dimensions as $rowID => $dimension) {
			$dimensions[$dimension->dimensionID] = $dimension;
		}
		$this->registry->dimensions = $dimensions;
		
		$this->registry->template->show('admin/settings','companysettings');
	}
	

	public function updatecompanysettingsAction() {
		
		$companyID = $_GET('id');
		echo "<br>Update companysettings - " . $companyID;
		
	}
	
	
	
	public function updatesettingsAction() {
	
		if (isset($_GET['systemname'])) {
			$settings = Table::load('system_settings');
				
			$rows = Table::load('system_settings'," WHERE name='system_settings_appname'");
			if ($rows == null) {
				echo "<br>nullliiii";
			}
				
			if ($rows == null) {
				$insertarray = array();
				$insertarray['Name'] = 'system_settings_appname';
				$insertarray['Value'] = $_GET['systemname'];
				$success = Table::addRow('system_settings',$insertarray);
			} else {
				$row = Table::loadRow('system_settings',"name='system_settings_appname'");
				$insertarray = array();
				$insertarray['Name'] = 'system_settings_appname';
				$insertarray['Value'] = $_GET['systemname'];
				$success = Table::updateRow('system_settings',$insertarray, $row->settingID);
			}
		}
		if (isset($_GET['savedir'])) {
			$settings = Table::load('system_settings');
		
			$rows = Table::load('system_settings'," WHERE name='system_settings_savedir'");
			if ($rows == null) {
				echo "<br>nullliiii";
			}
		
			if ($rows == null) {
				$insertarray = array();
				$insertarray['Name'] = 'system_settings_savedir';
				$insertarray['Value'] = $_GET['savedir'];
				$success = Table::addRow('system_settings',$insertarray);
			} else {
				$row = Table::loadRow('system_settings',"name='system_settings_savedir'");
				$insertarray = array();
				$insertarray['Name'] = 'system_settings_savedir';
				$insertarray['Value'] = $_GET['savedir'];
				$success = Table::updateRow('system_settings',$insertarray, $row->settingID);
			}
		}
		redirecttotal('admin/settings/showsettings', null);
	}
	
	


	public function insertcompanyAction() {

		$name = $_GET['name'];
		$businesscode = $_GET['businesscode'];
		$countryID = $_GET['countryID'];
	
		$values = array();
		$values['Name'] = $name;
		$values['Businesscode'] = $businesscode;
		
		$success = Table::addRow("system_companies", $values, true);
		redirecttotal('admin/settings/showcompanysettings', null);
	}
	
	
	public function insertofficeAction() {
		
		$comments = true;
		$name = $_GET['name'];
		
		$values = array();
		$values['Name'] = $name;
		$shortname = $_GET['shortname'];
		
		$success = Table::addRow("system_offices", $values, true);
		redirecttotal('admin/settings/showcompanysettings', null);
	}


	public function updateofficeAction() {
	
		$comments = true;
		$officeID = $_GET['id'];
		$name = $_GET['name'];
		$shortname = $_GET['shortname'];
		
		$values = array();
		$values['Name'] = $name;
		$values['Shortname'] = $shortname;
		
		$success = Table::updateRow("system_offices", $values, $officeID);
		//redirecttotal('admin/settings/showcompanysettings', null);
	}
	
	

	public function insertaddressAction() {
	
		$comments = true;
		$values = array();
		
		if (isset($_GET['officeID'])) {
			$values['OfficeID'] = $_GET['officeID'];
		} else {
			$values['OfficeID'] = 0;
			$offices = Table::load('system_offices');
			if ($offices == null) {
				$values['OfficeID'] = 0;
			} else {
				if (count($offices) == 1) {
					foreach ($offices as $index => $office) {}
					$values['OfficeID'] = $office->officeID;
				} else {
					$defaultoffice = null;
					foreach ($offices as $index => $office) {
						if ($office->defaultoffice == 1) {
							$defaultoffice = $office;
						}
					}
					if ($defaultoffice == null) $defaultoffice = $office;
					$values['OfficeID'] = $defaultoffice->officeID;
				}
			}
		}

		// Asetetaan branchID, jos ei ole asetettu asetetaan oletus
		if (isset($_GET['branchID'])) {
			$values['BranchID'] = $_GET['branchID'];
		} else {
			$values['BranchID'] = 0;
			$branches = Table::load('system_branches');
			if ($offices == null) {
				$values['BranchID'] = 0;
			} else {
				if (count($branches) == 1) {
					foreach ($branches as $index => $branch) {}
					$values['BranchID'] = $branch->branchID;
				} else {
					$defaultbranch = null;
					foreach ($branches as $index => $branch) {
						if ($branch->defaulbranch== 1) {
							$defaultbranch = $branch;
						}
					}
					if ($defaultbranch == null) $defaultbranch = $branch;
					$values['BranchID'] = $defaultbranch->branchID;
				}
			}
		}
		
		$countryID  = $_GET['countryID'];
		$country = Table::loadRow('system_countries', $countryID);
		$values['CountryID'] = $countryID;
		$values['Country'] = $country->name;
		$values['Countrycode'] = $country->countrycode;
		
		$values['Addresstype'] = $_GET['addresstype'];
		$values['Postalcode'] = $_GET['postalcode'];
		$values['Streetaddress'] = $_GET['streetaddress'];
		$values['City'] = $_GET['city'];
		
		$success = Table::addRow("system_addresses", $values, true);
		//redirecttotal('admin/settings/showcompanysettings', null);
	}
	
	
	public function insertbranchAction() {
	
		$comments = true;
		$name = $_GET['name'];
		$shortname = $_GET['shortname'];
		
		$values = array();
		$values['Name'] = $name;
		$values['Shortname'] = $name;
		
		$success = Table::addRow("system_branches", $values, true);
		redirecttotal('admin/settings/showcompanysettings', null);
	}
	
	

	public function updatebranchAction() {
	
		$comments = true;
		$branchID = $_GET['id'];
		$name = $_GET['name'];
		$shortname = $_GET['shortname'];
	
		$values = array();
		$values['Name'] = $name;
		$values['Shortname'] = $shortname;
	
		$success = Table::updateRow("system_branches", $values, $branchID);
		redirecttotal('admin/settings/showcompanysettings', null);
	}
	

	public function updatecompanyAction() {
	
		$success='';
		
		$companyID = $_GET['id'];
		$name = $_GET['name'];
		$businesscode = $_GET['businesscode'];
		$countryID = $_GET['countryID'];

		$values = array();
		$values['Name'] = $name;
		$values['Businesscode'] = $businesscode;
		$values['CountryID'] = $countryID;
		
		$success = Table::updateRow('system_companies', $values, $companyID, false);
		redirecttotal('admin/settings/showcompanysettings', null);
	}
	
	
	
	public function updatelanguagesAction () {

		$selectedlanguages = $_GET['Languages'];
		$values = array();
		
		//$success = Table::updateRow('system_settings', 'Value', $selectedlanguages, "Name='activelanguages'");  // 15.11.2018 - updateRow muutettu
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
	}
	*/
}

?>
