<?php


class ContractsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		//$this->showclientsAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showclientsAction() {
	
		$comments = false;
		
		$this->registry->companies = Table::load('crm_companies');
		$this->registry->contracts = Table::load('system_credentials', "WHERE SystemID=" . $_SESSION['systemID'], $comments);
		$this->registry->modules = Table::load('system_modules', "WHERE SystemID=" . $_SESSION['systemID']);
		
		
		//$this->registry->template->show('enterprise/contracts','contracts');
	}
	

	public function showcontractAction() {
		
		$contractID = $_GET['id'];
		
		$this->registry->contract = Table::loadRow('system_credentials', $contractID);
		$this->registry->modules = Table::load('system_modules', "WHERE SystemID=" . $this->registry->contract->clientsystemID);
		$this->registry->usergroups = Table::loadRow('system_usergroups', "WHERE SystemID=" . $this->registry->contract->clientsystemID);
		
		$this->registry->template->show('enterprise/contracts','contract');
	}
	

	// Tämä tarkoittaa aina sitä, että uusi aineisto (järjestelmä) lisätään
	// jos ollaan liittämässä oikeuksia jo aiemmin luotuun aineistoon, niin tämä pitää tehdä eri tavalla
	// TODO: Tämähän pitää ehkä olla install tyyppinen toiminto? Onkohan tämä kopioitukin jostain sieltä
	// ...
	public function insertcontractAction() {
		
		$currentSystemID = $_SESSION['systemID'];
		$comments = true;
		echo "<br>Current systemID - " . $_SESSION['systemID'];
		
		
		$companyID = $_GET['companyID'];
		$company = Table::loadRow('crm_companies', $companyID);
		$system = Table::loadRow('system_systems', $currentSystemID);
		
		echo "<br>Company - " . $company->name;
		$namestr = $company->name;
		$namestr = str_replace(" ", "", $namestr);
		$fileurl = 'https://www.babelsoft.fi/demo/admin.php?rt=admin/service/requestnewsystem&systemID=' . $currentSystemID . '&name=' . $namestr;
		echo "<br>Fileurl - " . $fileurl;
		$newSystemID = file_get_contents($fileurl);
		echo "<br>NewSystemID - " . $newSystemID;
		
		
		$values = array();
		$values['SystemID'] = $newSystemID;
		$values['Name'] = $company->name;
		$values['Databasename'] = $system->databasename;
		$values['Frontpage'] = "system/frontpage/index";
		$values['Parentsystem'] = $currentSystemID;
		$systemID = Table::addRowWithKey("system_systems",$values);
		
		
		// luodaan oletus user group, joka on admin
		$values = array();
		$values['Name'] = "Pääkäyttäjä";
		$values['ParentID'] = 0;
		$values['Usergroupkey'] = 'admin';
		$values['SystemID'] = $newSystemID;
		$usergroupID = Table::addRow("system_usergroups",$values);
		if ($comments) echo "<br>UsergroupID added - " . $usergroupID;
		
		Install::synchronizeClientModulesTable($newSystemID);
		$modules = Table::load('system_modules', "WHERE SystemID=" . $newSystemID, true);
		if ($comments) echo "<br> -- modulecount - " . count($modules);
		foreach($modules as $index => $module) {
			if ($comments) echo "<br> -- loopmodule - " . $module->modulename;
			if ($module->modulename == 'system') {
				if ($comments) echo "<br>Adding system user rights";
				Install::addAdminUserrights($newSystemID, $module->moduleID, $usergroupID, $comments);
			}
			if ($module->modulename == 'admin') {
				if ($comments) echo "<br>Adding admin user rights";
				Install::addAdminUserrights($newSystemID, $module->moduleID, $usergroupID, $comments);
			}
		}
		
		$insertarray = array();
		$insertarray['Name'] = 'system_settings_appname';
		$insertarray['Value'] = $company->name;
		$insertarray['SystemID'] = $newSystemID;
		$success = Table::addRow('system_settings',$insertarray,$comments);
			
		Install::createSystemMenu($newSystemID, $usergroupID, $comments);
		
		$insertarray = array();
		$insertarray['Name'] = $company->name;
		$insertarray['SystemID'] = $_SESSION['systemID'];
		$insertarray['ClientsystemID'] = $newSystemID;
		$insertarray['UsergroupID'] = $usergroupID;
		$insertarray['Usergroupname'] = "Pääkäyttäjä";
		$insertarray['UserID'] = 0;
		$success = Table::addRow('system_credentials',$insertarray,$comments);
		
		
	}
	
	
	public function insertmoduleAction() {
	
		$comments = true;
		
		echo "<br>contractID - " . $_GET['contractID'];
		echo "<br>moduleID - " . $_GET['moduleID'];
		
		$contractID = $_GET['contractID'];
		$moduleID = $_GET['moduleID'];
		$contract = Table::loadRow('system_credentials', $contractID);
		echo "<br>systemID - " . $contract->clientsystemID;
		
		
		// Synkronoidaan ehkä ensin taulut, sekä module taulu, että ko. modulin taulu (sitähän ei välttämättä edes ole)
		Install::synchronizeClientModulesTable($contract->clientsystemID);
		
		// asetetaan kyseinen moduli aktiiviseksi järjestelmään
		Install::installModule($contract->clientsystemID, $moduleID, $comments);
		
		// Käydään kaikki usergroupit lävitse, ja annetaan kaikki oikeudet admin-groupeille
		// luodaan käyttöoikeudet ainakin pääkäyttäjälle kyseiseen moduliin (pitäisi olla valittavana kaikki käyttäjäryhmät, read/write)
		// luodaan uudet menut kaikille käyttäjäryhmille (vain niille, joilla on oikeudet uuteen moduliin)
		echo "<br>Updating usergroups";
		$usergroups = Table::load('system_usergroups', "WHERE SystemID=" . $contract->clientsystemID);
		echo "<br>Updating usergroups - " . count($usergroups);
		foreach($usergroups as $index => $usergroup) {
			if ($usergroup->usergroupkey == 'admin') {
				Install::addAdminUserrights($contract->clientsystemID, $moduleID, $usergroup->usergroupID, $comments);
				Install::createSystemMenu($contract->clientsystemID, $usergroup->usergroupID, $comments);
			}
		}
	}
	
	
	public function selectsystemAction() {
	
		$comments = false;
		$systemID = $_GET['systemID'];

		if ($systemID == $_SESSION['mastersystemID']) {
			//echo "<br>Back to master";
			// usergroup pitää hakea user-taulusta
			
			// TODO: pitää tsekata onko kyseisellä käyttäjällä käyttöoikeudet kyseiseen järjestelmään.
			
			
			echo "<br>userID  - " . $_SESSION['userID'];
			$user = Table::loadRow('system_users', "WHERE SystemID=" . $_SESSION['mastersystemID'] . " AND UserID=" . $_SESSION['userID'], $comments);
			//echo "<br>userID  - " . $_SESSION['userID'];
			$_SESSION['usergroupID'] = $_SESSION['masterusergroupID'];
			//echo "<br>usergroupID  - " . $user->usergroupID;
			$_SESSION['systemID'] = $systemID;
		} else {
			//echo "<br>mastersystemID  - " . $_SESSION['mastersystemID'];
			//echo "<br>new systemID  - " . $systemID;
			$contract = Table::loadRow('system_credentials', "WHERE SystemID=" . $_SESSION['mastersystemID'] . " AND ClientsystemID=" . $systemID);
			if ($contract == null) {
				echo "<br>No credentials";
			} else {
				$usergroupdID = $contract->usergroupID;
				if ($comments) echo "<br>UsergroupID a123 - " . $usergroupdID;
			}
			$_SESSION['usergroupID'] = $usergroupdID;
			$_SESSION['systemID'] = $systemID;
		}
		
		$_SESSION['activemenuid'] = 0;
		$frontpage = $_SESSION['frontpage'];
		echo "<br>Login entrypoint - " . $frontpage;
		if (!$comments) redirecttotal($frontpage); // TODO: tämä pitää hakea asetuksista
		
		// aseta mastersystemID
		//		- muuta enterprise menu hakemaan tiedot mastersystemID:n avulla valikko 
		//		- menu haetaan systemID:n perusteella
		// pitää asettaa aktiivinen systemID
		// pitää asettaa aktiivinen groupID
		// siirrytään clientsystemin etusivulle (tai jos jossain muualla on asetettu last-page, ehkä pitää asettaa muuttujaan)
		
		
		
	}
	
	
	public function selectcompanyAction() {
		$comments = false;
		$companyID = $_GET['companyID'];
		$_SESSION['companyID'] = $companyID;
		$frontpage = $_SESSION['frontpage'];
		if (!$comments) redirecttotal($frontpage); // TODO: tämä pitää hakea asetuksista
	}
}

?>
