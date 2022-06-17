<?php

/**
 * Tämä on hieman ristiriitainen module, koska tämän modulen nimissä ei ole yhtään
 * tietokantaa, tämä module käyttää pelkästään system alkuisia tietokantoja.
 *
 * Tämähän voisi käyttää scopen suhteen eri parametria, scope kakkosella pääsisi
 * säätämään teemoja, systemstringejä ja tietokantaa.
 *
 * Perustapauksessa tällä hallitaan ainoastaan käyttäjiä, käyttäjäryhmiä ja palveluita
 *
 * Lisätään asetusmenuun
 * 		- Käyttäjien hallinta
 * 		- Käyttäjäryhmien hallinta
 * 		- Palvelunhallinta (service)
 * 		- Järjestelmän asetukset
 *
 */
class AdminModule extends AbstractModule {
	
	const ACCESSLEVEL_ALL = 10;
	
	const ACCESSRIGHTKEY_USERMANAGEMENT = 'admin_accesskey_usermanagement';
	const ACCESSRIGHTKEY_SERVICEMANAGEMENT = 'admin_accesskey_servicemanagement';
	const ACCESSRIGHTKEY_DATABASEMANAGEMENT = 'admin_accesskey_databasemanagement';
	
	const DIMENSIONTYPE_OFFICE = 1;
	const DIMENSIONTYPE_BRANCH = 2;
	const DIMENSIONTYPE_ACCOUNTING = 3;
	

	public function getDefaultName() {
		return "[1]Settings[2]Asetukset";
	}
	
	
	
	public function getAccessRights() {
	
		$accessrights = array();
	
		//$accesslevels = array();
		//$accesslevels[AdminModule::ACCESSLEVEL_ALL] = "admin_accesslevel_usermanagementall";
		//$accessrights[AdminModule::ACCESSRIGHTKEY_USERMANAGEMENT] = $accesslevels;
		$accessrights[AdminModule::ACCESSRIGHTKEY_USERMANAGEMENT] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		
		//$accesslevels = array();
		//$accesslevels[AdminModule::ACCESSLEVEL_ALL] = "admin_accesslevel_usermanagementall";
		//$accessrights[AdminModule::ACCESSRIGHTKEY_SERVICEMANAGEMENT] = $accesslevels;
		$accessrights[AdminModule::ACCESSRIGHTKEY_SERVICEMANAGEMENT] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		
		//$accesslevels = array();
		//$accesslevels[AdminModule::ACCESSLEVEL_ALL] = "admin_accesslevel_databasemanagement_all";
		//$accessrights[AdminModule::ACCESSRIGHTKEY_DATABASEMANAGEMENT] = $accesslevels;
		$accessrights[AdminModule::ACCESSRIGHTKEY_DATABASEMANAGEMENT] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		
		return $accessrights;
	}
	



	public function getMenu($accessrights) {
		
		$menuitems = array();
		$accesslevel = $accessrights[AdminModule::ACCESSRIGHTKEY_USERMANAGEMENT];
		$menuindex = 0;
		//if ($accesslevel > 0) {
			$menuitems[] = new Menu("Järjestelmäasetukset","admin/settings","showsettings",Menu::MENUKEY_ADMIN, null, 9905);
			//$menuitems[] = new Menu("Yritysasetukset","admin/settings","showcompanysettings",Menu::MENUKEY_ADMIN, null, 9905);
			$menuitems[] = new Menu("Yhteystiedot","admin/contacts","showsettings",Menu::MENUKEY_ADMIN, null, 9910);
		//}
		
		//$accesslevel = $accessrights[AdminModule::ACCESSRIGHTKEY_USERMANAGEMENT];
		//if ($accesslevel > 0) {
			$menuitems[] = new Menu("Käyttäjähallinta","admin/usermanagement","showmanagement",Menu::MENUKEY_ADMIN, null, 9909);
			$menuitems[] = new Menu("Käyttäjät","admin/users","showusers",Menu::MENUKEY_ADMIN, null, 9910);
			$menuitems[] = new Menu("Käyttäjäryhmät","admin/usergroups","showusergroups", Menu::MENUKEY_ADMIN, null, 9920);
			$menuitems[] = new Menu("Palvelunhallinta","admin/service","showservices", Menu::MENUKEY_ADMIN, null, 9930);
		//}
		
		//$accesslevel = $accessrights[AdminModule::ACCESSRIGHTKEY_DATABASEMANAGEMENT];
		//if ($accesslevel > 0) {
			$menuitems[] = new Menu("Tietokannanhallinta","admin/database","showdatabasetables", Menu::MENUKEY_ADMIN, null, 9931);
			$menuitems[] = new Menu("Resurssitekstit","admin/resourcetexts","showresourcetexts", Menu::MENUKEY_ADMIN, null, 9932);
			$menuitems[] = new Menu("Tietokanta-ajot","admin/database","scripts", Menu::MENUKEY_ADMIN, null, 9933);
			$menuitems[] = new Menu("Timelog","admin/timelog","filelist", Menu::MENUKEY_ADMIN, null, 9933);
			$menuitems[] = new Menu("Actions","admin/actions","showactions", Menu::MENUKEY_ADMIN, null, 9936);
		//}
		
		return $menuitems;
	}
	
	
	
	public function hasAccessRight($action) {
		
		// TODO: ainakin database-komennot pitäisi lisätä
		return true;
		
		$usermanagementlevel = getAccessLevel(AdminModule::ACCESSRIGHTKEY_USERMANAGEMENT);
		
		switch($action) {
			case "users/showuser":
				if ($usermanagementlevel == AdminModule::ACCESSLEVEL_ALL) return true;
				break;
			case "usergroups/showusergroup":
				return true;
				if ($usermanagementlevel == AdminModule::ACCESSLEVEL_ALL) return true;
				break;
			case "service/activatemodule":
				if ($usermanagementlevel == AdminModule::ACCESSLEVEL_ALL) return true;
				break;
		}
		return false;
	}
	
	
	public function hasAccess($accesskey) {
		return false;
	}
}


?>