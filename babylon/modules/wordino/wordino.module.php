<?php



class WordinoModule extends AbstractModule {
	
	const ACCESSLEVEL_FREE = 1;
	const ACCESSLEVEL_TRAINER = 2;
	const ACCESSLEVEL_ADMIN = 99;
	
	const ACCESSRIGHTKEY_ENGLISH = 'wordtrainer_english';
	const ACCESSRIGHTKEY_FINNISH = 'wordtrainer_finnish';
	const ACCESSRIGHTKEY_RUSSIAN = 'wordtrainer_russian';
	const ACCESSRIGHTKEY_TRAINING = 'wordtrainer_training';
	
	const ACCESSKEY_WORDINO_ADMIN = 'accesskey_wordino_admin';
	const ACCESSKEY_WORDINO_PLAYER = 'accesskey_wordino_player';
	
	const MENUKEY_WORDINO = 'menukey_wordino';
	
	
	public function getDefaultName() {
		return "Wordino";
	}
	


	public function getDimensions() {
		$dimensions = array();
		$dimension[Dimension::DIMENSION_LANGUAGE] = new Dimension(Dimension::DIMENSION_LANGUAGE, "Kieli", "Kielet", "worder_languages");
		$dimension[Dimension::DIMENSION_GRAMMAR] = new Dimension(Dimension::DIMENSION_GRAMMAR, "Kielioppi", "Kieliopit", "worder_grammars");
		return $dimensions;
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
		
		$accessrights[WordinoModule::ACCESSKEY_WORDINO_ADMIN] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[WordinoModule::ACCESSKEY_WORDINO_PLAYER] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		
		//$accesslevels = array();
		//$accesslevels[WordinoModule::ACCESSLEVEL_ADMIN] = "wordtrainer_accesslevel_all";			// Kaikki oikeudet
		//$accessrights[WordinoModule::ACCESSRIGHTKEY_TRAINING] = $accesslevels;
		
		return $accessrights;
	}
	
	
	
	public function getMenu($accessrights) {
	
		$menuitems = array();
		//$accesslevel = getAccessLevel(WordinoModule::ACCESSRIGHTKEY_TRAINING);
		$accesslevel = $accessrights[WordinoModule::ACCESSKEY_WORDINO_PLAYER];
		if ($accesslevel > 0) {
			$menuitems[] = new Menu("Simulator","wordino/game","play",Menu::MENUKEY_TOP,WordinoModule::MENUKEY_WORDINO,5000);
			$menuitems[] = new Menu("Pelaajat","wordino/players","showplayers",WordinoModule::MENUKEY_WORDINO,null,5100);
			$menuitems[] = new Menu("Play","wordino/game","play",WordinoModule::MENUKEY_WORDINO,null,5100);
			$menuitems[] = new Menu("Playerstate","wordino/playerstate","playerstate",WordinoModule::MENUKEY_WORDINO,null,5200);
			//$menuitems[] = new Menu("Kieliasetukset","wordtrainer/wordtrainersettings","showsettings",Menu::MENUKEY_ADMIN,null,450);
		}
		
		return $menuitems;
	}
	
	
	
	public function hasAccessRight($action) {
	
		return true;
		
		//echo "<br>WordtrainerModule hasAccessRight " . $action;
		$accesslevel = getAccessLevel(WordinoModule::ACCESSRIGHTKEY_TRAINING);
		//echo "<br>WordtrainerModule accesslevel - " . $accesslevel;
		if ($accesslevel == 0) {
			echo "<br>Accesslevel false";
			return false;
		}
	
		switch($action) {
			case "gameview/index":
				return true;
				break;
			case "players/index":
				return true;
				break;
			case "playerstate/index":
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