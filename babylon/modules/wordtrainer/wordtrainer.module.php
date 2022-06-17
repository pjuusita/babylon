<?php



class WordtrainerModule extends AbstractModule {
	
	const ACCESSLEVEL_FREE = 1;
	const ACCESSLEVEL_TRAINER = 2;
	const ACCESSLEVEL_ADMIN = 99;
	
	const ACCESSRIGHTKEY_ENGLISH = 'wordtrainer_english';
	const ACCESSRIGHTKEY_FINNISH = 'wordtrainer_finnish';
	const ACCESSRIGHTKEY_RUSSIAN = 'wordtrainer_russian';
	const ACCESSRIGHTKEY_TRAINING = 'wordtrainer_training';
	
	const MENUKEY_TRAINING = 'menukey_wordtrainer_training';
	
	const ACCESSKEY_WORDTRAINER_ADMIN = 'accesskey_wordtrainer_admin';
	const ACCESSKEY_WORDTRAINER_TRAINING = 'accesskey_wordtrainer_training';
	
	
	public function getDefaultName() {
		return "Wordtrainer";
	}
	
	
	public function getAccessRights() {
	
		$accessrights = array();
		
		/*
		$accesslevels = array();
		$accesslevels[WordtrainerModule::ACCESSLEVEL_FREE] = "wordtrainer_accesslevel_fre";			// Kaikki oikeudet
		$accesslevels[WordtrainerModule::ACCESSLEVEL_TRAINER] = "wordtrainer_accesslevel_trainer";			// Kaikki oikeudet
		$accesslevels[WordtrainerModule::ACCESSLEVEL_ADMIN] = "wordtrainer_accesslevel_admin";			// Kaikki oikeudet
		$accessrights[WordtrainerModule::ACCESSRIGHTKEY_ENGLISH] = $accesslevels;
		
		$accesslevels = array();
		$accesslevels[WordtrainerModule::ACCESSLEVEL_FREE] = "wordtrainer_accesslevel_fre";			// Kaikki oikeudet
		$accesslevels[WordtrainerModule::ACCESSLEVEL_TRAINER] = "wordtrainer_accesslevel_trainer";			// Kaikki oikeudet
		$accesslevels[WordtrainerModule::ACCESSLEVEL_ADMIN] = "wordtrainer_accesslevel_admin";			// Kaikki oikeudet
		$accessrights[WordtrainerModule::ACCESSRIGHTKEY_FINNISH] = $accesslevels;
		
		$accesslevels = array();
		$accesslevels[WordtrainerModule::ACCESSLEVEL_FREE] = "wordtrainer_accesslevel_fre";			// Kaikki oikeudet
		$accesslevels[WordtrainerModule::ACCESSLEVEL_TRAINER] = "wordtrainer_accesslevel_trainer";			// Kaikki oikeudet
		$accesslevels[WordtrainerModule::ACCESSLEVEL_ADMIN] = "wordtrainer_accesslevel_admin";			// Kaikki oikeudet
		$accessrights[WordtrainerModule::ACCESSRIGHTKEY_RUSSIAN] = $accesslevels;
		*/
		
		$accessrights = array();
		//$accesslevels = array();
		//$accesslevels[WordtrainerModule::ACCESSLEVEL_ADMIN] = "wordtrainer_accesslevel_all";			// Kaikki oikeudet
		//$accessrights[WordtrainerModule::ACCESSRIGHTKEY_TRAINING] = $accesslevels;
		
		$accessrights[WordtrainerModule::ACCESSKEY_WORDTRAINER_ADMIN] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[WordtrainerModule::ACCESSKEY_WORDTRAINER_TRAINING] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		
		
		return $accessrights;
	}
	
	
	
	public function getMenu($accessrights) {
	
		$menuitems = array();
		$accesslevel = $accessrights[WordtrainerModule::ACCESSRIGHTKEY_TRAINING];
		if ($accesslevel > 0) {
			$menuitems[] = new Menu("Treenaus","wordtrainer/training","showsettings",Menu::MENUKEY_TOP,WordtrainerModule::MENUKEY_TRAINING,450);
			$menuitems[] = new Menu("Sanasto","wordtrainer/words","showsettings",Menu::MENUKEY_TOP,null,450);
			$menuitems[] = new Menu("Sanasetit","wordtrainer/wordsets","showsettings",Menu::MENUKEY_TOP,null,450);
			$menuitems[] = new Menu("Kieliasetukset","wordtrainer/wordtrainersettings","showsettings",Menu::MENUKEY_ADMIN,null,450);
		}
		
		return $menuitems;
	}
	
	
	
	public function hasAccessRight($action) {
	
		//echo "<br>WordtrainerModule hasAccessRight " . $action;
		$accesslevel = getAccessLevel(WordtrainerModule::ACCESSRIGHTKEY_TRAINING);
		//echo "<br>WordtrainerModule accesslevel - " . $accesslevel;
		if ($accesslevel == 0) {
			echo "<br>Accesslevel false";
			return false;
		}
	
		switch($action) {
			case "wordtrainersettings/showsettings":
				return true;
				break;
			case "wordtrainersettings/updatesettings":
				return true;
				break;
			case "wordtrainersettings/insertlanguage":
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