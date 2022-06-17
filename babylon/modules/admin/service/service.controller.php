<?php

/**
 * Settingscontrollerissa hallitaan jarjestelman asetuksia. Periaatteessa jokaista kaytassa olevaa modulia varten on oma
 * sectioninsa, jokaiselta modulilta tulisi siis saada tarpeelliset asetukset.
 * 
 * Perusasetuksia on muutamia, ainakin ulkoasuun ja logoon liittyvia asetuksia, mutta periaatteessa namakin voisivat tulla
 * suoraan kaytettavissa olevista oletusmoduleista. Lahinna kai niin, etta system module on automaattisesti kaytassa, myas admin
 * moduli on todennakaisesti automaattisesti kaytassa. Osa moduleista on tosin pelkastaan hallinta kayttaan, esim. database.
 * 
 *
 */
class ServiceController extends AbstractController {


	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
	
		
	public function indexAction() {

		/*
		Install::synchronizeModulesTable($_SESSION['systemID']);
		$this->registry->modules = Table::load('system_modules', 'WHERE SystemID=' . $_SESSION['systemID']);
		$this->registry->dimensions = Table::load('system_dimensions', 'WHERE SystemID=' . $_SESSION['systemID']);
		$settings = Table::loadRow('system_settings',"WHERE name='system_settings_appname'");
		$this->registry->appname = $settings->value;
		$this->registry->template->show('admin/service','modules');
		*/
		$this->registry->template->show('system/error','unknown');
	}

	
	public function showservicesAction() {
	
		updateActionPath("Palvelunhallinta");
		
		Install::synchronizeModulesTable($_SESSION['systemID']);
		$this->registry->modules = Table::load('system_modules', 'WHERE SystemID=' . $_SESSION['systemID']);
		$this->registry->dimensions = Table::load('system_dimensions', 'WHERE SystemID=' . $_SESSION['systemID']);
		$settings = Table::loadRow('system_settings',"WHERE name='system_settings_appname'");
		$this->registry->appname = $settings->value;
		$this->registry->template->show('admin/service','modules');
	}
	
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showmoduleAction() {
	
		$moduleID = $_GET['id'];
		$this->registry->module = Table::loadRow('system_modules', $moduleID);
		
		$this->registry->template->show('admin/service','module');
	}
	
	
	public function modulecrawlerAction() {
		
		$this->registry->modules = Table::load('system_modules');
		
		// system_roles on tarpeeton, vastaava toiminta system_usergroups-taulussa
		//$this->registry->profileitems = Table::load('system_roles');
		
		$this->registry->template->show('admin/service','modulecrawler');
	}
		
	
	
	// tätä pitää kutsua vasta sitten kun taulut on luotu, system-tauluja ei välttämättä aluksi ole
	// TODO: Voisi varmaan olla staattinen
	// TODO: poista vanhat arvot, ei saisi synnyttää tuplia
	private function updateResourceTexts($comments = false) {

		$comments = true;
		if ($comments) echo "<br>Updating resourcetexts...";
		
		$tableurl = 'https://www.babelsoft.fi/' . SITEPATH . '/json.php?rt=system/database/getresourcetexts';
		if ($comments) echo "<br>Tableurl - " . $tableurl;
		
		
		$existingtexts = Table::load("system_resourcetext");
		$doubles = array();
		foreach($existingtexts as $index => $resourcetext) {
			$key = $resourcetext->moduleID . "-" . $resourcetext->resourcekey;
			$doubles[$key] = 1;
		}
		ini_set("allow_url_fopen", 1);
		$json = file_get_contents($tableurl);
		$resourcetextlist = json_decode($json);
		foreach($resourcetextlist as $stringID => $resourcetext) {
			//echo "<br>String - " . $resourcetext->resourcekey . " - " . $resourcetext->value . " - " . $resourcetext->moduleID;
			$values = array();
			
			$key = $resourcetext->moduleID . "-" . $resourcetext->resourcekey;
			if (isset($doubles[$key])) {
				//echo "<br>Resourcetext " . $key . " already exists...";				
			} else {
				$values['Resourcekey'] = $resourcetext->resourcekey;
				$values['Value'] = $resourcetext->value;
				$values['ModuleID'] = $resourcetext->moduleID;
				$rowID = Table::addRow("system_resourcetext", $values);
			}
		}
		if ($comments) echo "<br>Resouce texts updated";
	}
	
	


	public function updateserviceAction() {
	
		$comments = false;
		
		if ($comments) echo "<br>UpdateService";
		$moduleID = $_GET['id'];
		$this->registry->module = Table::loadRow('system_modules', $moduleID);
		$dimensions = Table::load('system_dimensions', "WHERE ModuleID=" . $moduleID, true);
		if ($comments) echo "<br>Dimension count - " . count($dimensions);
		foreach($dimensions as $index => $dimension) {
			if ($comments) echo "<br>Dimension - " . $dimension->dimensionID;
			$var = "active" . $dimension->dimensionID;
			if (isset($_GET[$var])) {
				if ($comments) echo "<br>Dimension found - " . $var;
				$active = $_GET[$var];
				if ($comments) echo "<br>Active - " . $active;
				$values = array();
				$values['Active'] = $active;
				$success = Table::updateRow("system_dimensions", $values, $dimension->rowID, false);
			} else {
				if ($comments) echo "<br>Dimension not found - " . $var;
			}
		}
		if (!$comments) redirecttotal('admin/service/showservices', null);
	}
	
	
	
	
	
	// Tätä ei ehkä käytetä missään
	private function updateModuleDimensions($moduleID, $comments = false) {
	
		$comments = true;
		if ($comments) echo "<br>Updating resourcetexts...";
	
		$tableurl = 'https://www.babelsoft.fi/' . SITEPATH . '/json.php?rt=system/database/getresourcetexts';
		if ($comments) echo "<br>Tableurl - " . $tableurl;
	
	
		$existingtexts = Table::load("system_resourcetext");
		$doubles = array();
		foreach($existingtexts as $index => $resourcetext) {
			$key = $resourcetext->moduleID . "-" . $resourcetext->resourcekey;
			$doubles[$key] = 1;
		}
		ini_set("allow_url_fopen", 1);
		$json = file_get_contents($tableurl);
		$resourcetextlist = json_decode($json);
		foreach($resourcetextlist as $stringID => $resourcetext) {
			//echo "<br>String - " . $resourcetext->resourcekey . " - " . $resourcetext->value . " - " . $resourcetext->moduleID;
			$values = array();
				
			$key = $resourcetext->moduleID . "-" . $resourcetext->resourcekey;
			if (isset($doubles[$key])) {
				//echo "<br>Resourcetext " . $key . " already exists...";
			} else {
				$values['Resourcekey'] = $resourcetext->resourcekey;
				$values['Value'] = $resourcetext->value;
				$values['ModuleID'] = $resourcetext->moduleID;
				$rowID = Table::addRow("system_resourcetext", $values);
			}
		}
		if ($comments) echo "<br>Resouce texts updated";
	}
	
	
	/**
	 * TODO: Tähän pitäisi mahdollisesti ottaa parametrina käyttäänotetun modulin laajuus, mutta tämä pitää ehkä hoitaa toisin
	 *   (a) taulut ovat aina sisällälliseltä rakenteeltaan laajimmat mahdolliset aina,
	 *   		- Laajuus muuttaa käyttäliittymän näkyvyyksiä, eli osaa kentistä ei näytetä jos muita moduleita ei ole mukana
	 *   		- tässä saattaa tulla ongelmia sen suhteen, että geneeriset käyttäliittymät näyttävät kaikki kannan sarakkeet
	 *   		  tähän pitää tehdä jokin lisäratkaisu. Tähän palataan, ei ole nyt ensimmäinen ongelma
	 *   (b) laajuus tulee omana alimodulina, joka käyttää parentin kantoja (ja tarvittaessa tekee itse lisää)
	 *
	 * @param integer $moduleID
	 */
	public function activatemoduleAction() {
	
		// Pitäisi katsoa onko käyttäjällä oikeus asentaa...
	
		$comments = false;
		
		$moduleID = $_GET['moduleID'];
		$usergroupID = $_SESSION['usergroupID'];
		
		if ($comments) echo "<br>activatemoduleAction...";
		if ($comments) echo "<br>install module - " . $moduleID;
		if ($comments) echo "<br>usergroupID - " . $usergroupID;
		
		Install::installModule($_SESSION['systemID'], $moduleID,$comments);
		Install::addAdminUserrights($_SESSION['systemID'], $moduleID, $usergroupID, $comments);
		Install::createSystemMenu($_SESSION['systemID'], $usergroupID, $comments);
		$this->updateResourceTexts($comments);
		
		if ($comments) echo "<br>finished.";
		if ($comments == false) {
			redirecttotal('system/modules/showservices', null);
		}
	}
	
	
	public function disablemoduleAction() {
		
		$comments = true;
		
		$moduleID = $_GET['id'];
		$usergroupID = $_SESSION['usergroupID'];
		$systemID = $_SESSION['systemID'];
		
		$values = array();
		$values['Active'] = 0;
		if ($comments) echo "<br>Update Systemmodules - " . $moduleID;
		Table::updateRow("system_modules", $values, "WHERE ModuleID=" . $moduleID . " AND SystemID=" . $systemID, $comments);
		
		Install::createSystemMenu($_SESSION['systemID'], $usergroupID, $comments);
		
		if ($comments) echo "<br>finished.";
		if ($comments == false) {
			redirecttotal('system/modules/showservices', null);
		}
	}
	

	public function reinstallmoduleAction() {
	
		// Pitäisi katsoa onko käyttäjällä oikeus asentaa...
	
		$comments = true;
	
		$moduleID = $_GET['id'];
		$usergroupID = $_SESSION['usergroupID'];
	
		if ($comments) echo "<br>reinstallmoduleAction...";
		if ($comments) echo "<br>install module - " . $moduleID;
		if ($comments) echo "<br>usergroupID - " . $usergroupID;
	
		Install::installModule($_SESSION['systemID'], $moduleID,$comments);
		$this->updateResourceTexts($comments);
		Install::addAdminUserrights($_SESSION['systemID'], $moduleID, $usergroupID, $comments);
		Install::createSystemMenu($_SESSION['systemID'], $usergroupID, $comments);
		
		if ($comments) echo "<br>finished.";
		if ($comments == false) {
			redirecttotal('system/modules/showservices', null);
		}
	}
	
	

	public function requestnewsystemAction() {

		global $mysqli;
		
		$masterSystemID = $_GET['systemID'];
		$sql = "SELECT * FROM systems WHERE SystemID='" . $masterSystemID . "'";
		//echo "<br>SQL1 - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) {
			die('Select failed: ' . $mysqli->connect_error);
		}	
		$row = $result->fetch_array();
		if ($row == null) {
			die('Select failed: ' . $mysqli->connect_error);
		}
		
		$name = $row['Name'];
		if (isset($_GET['name'])) {
			$name = $_GET['name'];	
		}
		$databasename = $row['Databasename'];
		
		$sql = "INSERT INTO systems (Name, Databasename, MastersystemID) VALUES ('" . $name . "','" . $databasename . "'," . $masterSystemID . ")";
		//echo "<br>SQL2 - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) {
			if ($comments) echo "<br> - insert rows failed - " . $mysqli->error;
			return "insert rows failed - " . $mysqli->error;
		}
		$newSystemID = $mysqli->insert_id;

		echo "" . $newSystemID;
	}
	

	/**
	 * Tätä kutsutaan ainoastaan järjestelmää ensimmäistä kertaa asennettaessa, kun tietokanta on tyhjä.
	 *
	 * TODO: menun luonti on ainakin puutteellinen, pitäisi asentaa system- ja admin modulet.
	 *
	 */
	public function installAction() {
	
		
		
		$comments = true;
		if ($comments) echo "<br>installing database<br>";
		
		$usergroupID = 99;		// pääkäyttäjän ryhmä
		
		// print_r($_SESSION);
	
		if ($comments) echo "<br>Username - " . $_SESSION['username'];
		if ($comments) echo "<br>Database - " . $_GET['database'];
	
		if ($_SESSION['username'] == '') {
			$username = $_GET['username'];
			$_SESSION['username'] = $username;
		}
		
		$_SESSION['systemID'] = $_GET['systemID'];
		if ($comments) echo "<br>systemID - " . $_GET['systemID'];
		
		$userID = Login::getUserID($_SESSION['username'],$_GET['database']);
		$password = Login::getPassword($_SESSION['username'],$_GET['database']);
		$moduleID = 1;
	
		if ($userID == '') {
			$_SESSION['userID'] = 0;
			$userID = 0;
		} else {
			$_SESSION['userID'] = $userID;
		}
	
		if ($comments) echo "<br>userID - " . $userID;
		if ($comments) echo "<br><br>";
	
		global $mysqli;
		$database = $_GET['database'];
		$_SESSION['database'] = $database;
		$_SESSION['languageID'] = 1;
			
		$mysqli = ConnectDatabase($_SESSION['database']);

		
		$username = $_GET['username'];
		$systemID = $_GET['systemID'];
		$systemname = $_GET['systemname'];
		
		Table::createSystemTables($userID, $username, $systemID, $systemname, $database, false);		// ko. funktio pitäisi ehkä siirtää install-luokkaan. Suurentaa vaan turhaa table-luokkaa
		
		// pitäisi ehkä synkata ensin tablejen ID-numerot transitiontableen...
		// funktio siirretty install-luokkaan
		Install::synchronizeModulesTable($systemID);
		Install::installModule($systemID, $moduleID, $comments);
	
		
		// Tätä ei voida tehdä aiemmin, koska synkronisointi vasta luo user ja usergroups tablet
		// Lisätään pääkäyttäjäryhmä
		$userGroupExists = Table::rowExists('system_usergroups', " UsergroupID=99", true);
		if ($userGroupExists == false) {
			$values = array();
			$values['UsergroupID'] = $usergroupID;
			$values['Name'] = "Pääkäyttäjä";
			$values['ParentID'] = 0;
			$values['Usergroupkey'] = 'admin';
			$usergroupID = Table::addRow("system_usergroups",$values, true, true);
			if ($comments) echo "<br>UsergroupID added - " . $usergroupID;
		} else {
			if ($comments) echo "<br>Usergroup already exists - 99";
		}
		
		
		$userExists = Table::rowExists('system_users', " UserID='" . $userID . "'", true);
		if ($userExists == false) {
			$values = array();
			$values['UserID'] = $userID;
			$values['Username'] = $_SESSION['username'];
			$values['Password'] = $password;
			$values['UsergroupID'] = $usergroupID;
			$userID = Table::addRow("system_users",$values, true, true);
			if ($comments) echo "<br>UsedID added - " . $userID;
		
		} else {
			if ($comments) echo "<br>User already exists- " . $userID;
		}
		
		// 14.12.2019 system modulella ei ole accessRightseja lainkaan, kaikki tulee admin-modulesta, mutta antaa nyt olla
		// tulevaisuuden varalle
		Install::addAdminUserrights($systemID, $moduleID, $usergroupID, $comments);
		
		$adminModuleID = 2;
		Install::installModule($systemID, $adminModuleID, $comments);		// asennetaan myös admin-module oletuksena
		Install::addAdminUserrights($systemID, $adminModuleID, $usergroupID, $comments);
		
		Install::createSystemMenu($systemID, $usergroupID, $comments);
		$this->updateResourceTexts($comments);
		
		
		$_SESSION['activemenuid'] = 0;
		$_SESSION['usergroupID'] = $usergroupID;
		
		

		if ($comments) echo "<br>finished.";
		if ($comments == false) {
			redirecttotal('admin/service/showservices', null);
		} else {
			echo "<br><a href='".getUrl('admin/service/showservices')."'>Järjestelmään</a>";
		}
		
		
		// Seuraavaksi pitäisi kopioida modulin taulujen oletussisältä... (tämä on installmodulen yleinen ongelma
		//  -- Missä ja miten tämä määritellään?
		//  -- Pitkällä aikavälillä tähän pitäisi tehdä tyäkalu, täpätään rivejä käyttäliittymästä...
		//  -- Ne viitteet ovat ongelmallisia...
	
	}
	

	/**
	 * Lisätään tabletransitions-tauluun tableID-arvot. Näiden avulla tiedetään hakea, jos remoten
	 * viiteavaimet viittaavat tableID:hen, niin katsotaan täältä taulusta mihin ID-numeroon se viittaa lokaalissa.
	 *
	 * 14.12.2019 Kopioitu vanhasta install luokasta, tällä oli kai tarkoitus synkronoida tauluID:t mainserverille
	 *
	 * @param integer $moduleID
	 */
	public function synchronizeTableTransitions($moduleID) {
	
		$moduletableID = Table::getTableID('system_modules');
		$tablestableID = Table::getTableID('system_tables');
	
		$tableurl = 'https://www.babelsoft.fi/' . SITEPATH . '/json.php?rt=system/database/gettables&moduleid=' . $moduleID;
		ini_set("allow_url_fopen", 1);
		$json = file_get_contents($tableurl);
		$remotetables = json_decode($json);
	
		foreach($remotetables as $remotetableID => $table) {
			echo "<br>Table - " . $table->tableID . " - " . $table->name;
	
			$localtableID = Table::getTableID($table->name);
	
			$values = array();
			$values['ModuleID'] = $moduleID;
			$values['TableID'] = $tablestableID;
			$values['LocalrowID'] = $localtableID;
			$values['RemoterowID'] = $table->tableID;
		}
	}
	
	
	
}

?>
