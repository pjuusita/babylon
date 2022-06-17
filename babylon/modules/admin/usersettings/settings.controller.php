<?php

/**
 *	Tänne tulee käyttäjäkohtaiset asetukset. 
 *		- Kielivalinta
 *		- Salasanan vaihto
 *		- Etunimi, Sukunimi, käyttäjätunnus, puhelinnumero (ei muokattavat)
 *
 *
 */
class UserSettingsController extends AbstractController {

	
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
	
	/*
	public function showsettingsAction() {
	
	
		$settings = Table::load('system_settings');
	
		//var_dump($this->registry->settings);
	
		$row = new Row();
		$row->systemID = 1;
		foreach($settings as $index => $row) {
			//echo "<br>name - " . $row->name;
			if ($row->name == 'system_settings_appname') {
				//echo "<br>Found";
				$row->systemname = $row->value;
			}
		}
		$this->registry->generalsettings = $row;
	
		$this->registry->template->show('system/management','systemsettings');
	}
	*/
	
	
	/*
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
		redirecttotal('system/management/showsettings', null);
	}
	*/
	
	// Poistettu toistaiseksi vanhentuneenä käytöstä
	// TODO: kieliasetukset pitä lisätä tänne jossainvälissä
	/*
	public function showsettingsAction() {

		
		
		$languages = Table::load('system_languages');
		$this->registry->languages = $languages;
		
		//echo "<br>Languages - "  . count($languages);
		
		//$this->registry->languages = array();
		
		$activelanguages = array();
		if (isset($allsettings['activelanguages'])) {
			$this->registry->activelanguages = $allsettings['activelanguages'];
			unset($allsettings['activelanguages']);
			//echo " - activelanguages - " . $this->registry->settings->activelanguages;
			//$languagesstr = explode(":", $allsettings['activelanguages']);
			//foreach($languagesstr as $index => $value) {
			//	$activelanguages[$value] = $languages[$value]->languagename;
			//}
		} else {
			$this->registry->activelanguages = "";
			//echo "<br>activelanguages not setted";
		}
		
		$this->registry->settings = $allsettings;
		
		
		$this->registry->template->show('admin/settings','settings');
		
	}
	*/
	
	
	/*
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
	
	
	/*
	public function updatesettingsAction () {
		
		$selectedlanguage = $_GET['Deflanguage'];
		
		$success = Table::updateRow('system_settings', 'Value', $selectedlanguage, "Name='defaultlanguage'");  // 15.11.2018 - updateRow muutettu
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
	}
	*/
}

?>
