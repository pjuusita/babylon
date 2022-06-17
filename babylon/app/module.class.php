<?php

/**
 * Moduleiden yläluokka.
 *
 * Käytetään moduleiden hallintaan. Tuotantonnossa loppukäyttäjä voi aktivoida eri moduuleita tarpeensa mukaan.
 * Devauksessa, testauksessa ja käytässä omia moduuleita jotka eivät saatavilla loppukäyttäjälle. Osa moduleista
 * on keskenään riippuvaisia.
 *
 * @author Petri Uusitalo
 * @copyright Babelsoft Oy, 2016
 *
 */

abstract class AbstractModule {
	
	
	
	
	// pitäisikä nämä muuttaa defineksi ja/tai siirtää init-php:hen
	// moduletypes
	const SYSTEM = 1;		// this is system spesific module, basic functionality for framework, not actually module at all (for example login-module)
	const BASE = 2;			// this is system spesific module which are included for all systems
	const ADDON = 3;		// this type is basic selectable module for clients to use and add
	const CUSTOM = 4;		// this type is used when creating special modules targeted for specific client or not all clients
	const DEVELOPMENT = 5;	// this type is used for testing and development
	
	// stage values
	const NOT_ACTIVE = 1;	// module is not active, reason unknown
	//const DEVELOPMENT = 2;	// module is in develobment stage
	const TESTING = 2;		// module is in testing / development
	const BETA = 3;			// module is beta
	const DEPRECATED = 4; 	// this module is active for old clients but not available for new clients
	const PRODUCTION = 5;	// this module is active and usable for clients
	
	const MINIMAL = 1;
	const BASIC = 2;
	
	// Tarkoitettu oikeuksiin joissa on kyllä tai ei vaihteohto
	const ACCESSKEYTYPE_YESNO = 0;
	const ACCESSKEYTYPE_READWRITECUSTOM = 2;
	
	const ACCESSRIGHT_NO = 0;
	const ACCESSRIGHT_YES = 10;
	
	// Tarkoitettu oikeuksiin, joissa on mahdollisuus lukuoikeuksiin erikseen
	const ACCESSKEYTYPE_READWRITE = 1;
	const ACCESSRIGHT_NONE = 0;
	const ACCESSRIGHT_READ = 1;
	//const ACCESSRIGHT_WRITE = 2;
	const ACCESSRIGHT_ALL = 10;
	
	const ACCESSRIGHT_CUSTOM = 9;
	
	
	const VISIBILITY_USER = 0;		
	const VISIBILITY_SELECTED = 1;
	const VISIBILITY_ALL = 10;
	
	
	
	//const STAGES = array( NOT_ACTIVE => "NOT_ACTIVE", TESTING => "TESTING", DEPRECATED => "DEPRECATED", BETA => "BETA", fsdfd => "DEPRECATED", PRODUCTION => "PRODUCTION");
	
	/**
	 *  Modulin vaihe: NOT_ACTIVE, DEVELOPMENT, TESTING, DEPRECATED, PRODUCTION
	 * 
	 */
	//abstract function getStage();
	
	/*
	static function getModuleTypeString($value) {
		if ($value == AbstractModule::SYSTEM) return "SYSTEM (" . AbstractModule::SYSTEM. ")";
		if ($value == AbstractModule::BASE) return "BASE (" . AbstractModule::BASE . ")";
		if ($value == AbstractModule::ADDON) return "ADDON (" . AbstractModule::ADDON. ")";
		if ($value == AbstractModule::CUSTOM) return "CUSTOM (" . AbstractModule::CUSTOM. ")";
		if ($value == AbstractModule::DEVELOPMENT) return "DEVELOPMENT (" . AbstractModule::DEVELOPMENT. ")";
	}
	
	
	static function getStageString($value) {
		if ($value == AbstractModule::NOT_ACTIVE) return "NOT_ACTIVE (" . AbstractModule::NOT_ACTIVE. ")";
		if ($value == AbstractModule::TESTING) return "TESTING (" . AbstractModule::TESTING. ")";
		if ($value == AbstractModule::DEPRECATED) return "DEPRECATED (" . AbstractModule::DEPRECATED. ")";
		if ($value == AbstractModule::BETA) return "BETA (" . AbstractModule::BETA. ")";
		if ($value == AbstractModule::PRODUCTION) return "PRODUCTION (" . AbstractModule::PRODUCTION. ")";
	}
	*/
	
	
	/**
	 *  Modulin tyyppi: SYSTEM, BASE, ADDON, CUSTOM, DEVELOPMENT 
	 * 
	 */
	//abstract function getModuleType();
	

	
	/**
	 * Returns integer value which defines how many different scopes are available. For example if there is thee different
	 * scopes they are: minimal, basic, wide. If there is two different: minimal, wide. If one: basic
	 * 
	 */
	public function scopeSelection() {
		return array("1" => "Standard");
	}
	

	/**
	 * Returns default name for this module, return string in multilang format
	 *
	 */
	abstract function getDefaultName();

	
	
	public function getDimensions() {
		return array();
	}
	
	
	abstract function getAccessRights();
	
	
	abstract function getMenu($userrights);
	
	
	abstract function hasAccessRight($action);
	
	
	public function generateSettingsView($registry) {
		return null;
	}
	
	
	/**
	 * 
	 * 
	 * @param unknown $action
	 * @param unknown $profiles
	 * @param unknown $scope
	 */
	abstract function hasAccess($accesskey);	
	

	/**
	 * Palauttaa listan alimoduleista. This is needed because all submodules has AbstractModule implementation which defines
	 * its content (reguirements and bindings).
	 * 
	 * 
	 */
	//abstract function getSubModules($scope);
	
	
	
	//abstract function getControllers($scope);
	
	
	/**
	 * This function returns array of all other modules current module needs. 
	 * 
	 * NOTE: It could be that this is not strictly reguired, because are needed values comes from
	 * database, but for linking to actions outside this module this could be needed.
	 * 
	 */
	//abstract function getModuleRequirements($scope);
	
	
	/**
	 * This function return array of all databasetables it needs
	 * 
	 */
	//abstract function getDatabaseTableRequirements($scope);	
	
	
	/**
	 * This function return UISection where all available settings are managed
	 * 
	 */
	//abstract function getSettingsSection($scope);
	
	
	/**
	 * This function returns all tables with structure. This function is used when new module is activated
	 * and database is not readily available in activedatabase. In some cases existing databases might need
	 * additional columns. 
	 * 
	 * 
	 */	
	//abstract function getDefaultTableStructure($scope, $tablename);

	
	/**
	 * In some cases we need also default content for database, when module is activated for the first time. 
	 * Problem remains if some of these existing values are previously removed, this function returns value 
	 * only in case when new table is created, it doesn't force content, for mandatory content function getOblicatoryContent
	 * is used. Default table content should include all forced content.
	 * 
	 */
	//abstract function getDefaultTableContent($scope, $tablename);
	
	
	/**
	 * This function is used when validating table content. Also when creating new table. Forced content is table rows which
	 * are critical for system functionality.
	 * 
	 * Typically forced content is content which should not be possible to remove.
	 * 
	 */
	//abstract function getForcedContent($scope, $tablename);
	
}


?>