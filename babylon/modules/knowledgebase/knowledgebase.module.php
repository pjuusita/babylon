<?php


class KnowledgebaseModule extends AbstractModule {
	
	
	const ACCESSRIGHTKEY_KNOWLEDGEBASEVISIBILITY = 'knowledgebase_accesskey_visibility';
	const ACCESSRIGHTKEY_FUNCTIONSMANAGEMENT = 'knowledgebase_accesskey_functionsmanagement';
	const ACCESSRIGHTKEY_PROPOSITIONGENERATOR = 'knowledgebase_accesskey_propositiongenerators';
	const ACCESSRIGHTKEY_RULESMANAGEMENT = 'knowledgebase_accesskey_rulesmanagement';
	const ACCESSRIGHTKEY_ADMIN = 'knowledgebase_accesskey_admin';
	
	// muutosoikeudet kaikkiin systeemin projekteihin, tämä on yleensä vain system adminilla
	const ACCESSKEY_MANAGEALL = 100;	

	// näkyvyys kaikkiin projekteihin, mikäli tätä ei ole, niin näkyvissä on vain omat projektit...
	const ACCESSKEY_VIEWALL = 101;		
	
	
	const MENUKEY_KNOWLEDGEBASE = 'menukey_knowledgebase';
	
	
	public function getDefaultName() {
		return "Knowledge Base";
	}
	
	

	public function getAccessRights() {
	
		$accessrights = array();
		$accessrights[KnowledgebaseModule::ACCESSRIGHTKEY_KNOWLEDGEBASEVISIBILITY] = AbstractModule::ACCESSKEYTYPE_READWRITE;;
		$accessrights[KnowledgebaseModule::ACCESSRIGHTKEY_FUNCTIONSMANAGEMENT] = AbstractModule::ACCESSKEYTYPE_READWRITE;;
		$accessrights[KnowledgebaseModule::ACCESSRIGHTKEY_PROPOSITIONGENERATOR] = AbstractModule::ACCESSKEYTYPE_READWRITE;;
		$accessrights[KnowledgebaseModule::ACCESSRIGHTKEY_RULESMANAGEMENT] = AbstractModule::ACCESSKEYTYPE_READWRITE;;
		$accessrights[KnowledgebaseModule::ACCESSRIGHTKEY_ADMIN] = AbstractModule::ACCESSKEYTYPE_READWRITE;;
		
		return $accessrights;
	}
	
	
	
	public function getMenu($userrights) {
		
		$menuitems = array();
		$menuitems[] = new Menu("Knowledge Base","knowledgebase/propositions","showpropositions",Menu::MENUKEY_TOP,KnowledgebaseModule::MENUKEY_KNOWLEDGEBASE,1193);
		$menuitems[] = new Menu("Propositions","knowledgebase/propositions","showpropositions",KnowledgebaseModule::MENUKEY_KNOWLEDGEBASE,null,1196);
		$menuitems[] = new Menu("Functions","knowledgebase/functions","showfunctions",KnowledgebaseModule::MENUKEY_KNOWLEDGEBASE,null,1197);
		$menuitems[] = new Menu("Inference Rules","knowledgebase/rules","showrules",KnowledgebaseModule::MENUKEY_KNOWLEDGEBASE,null,1198);
		$menuitems[] = new Menu("Proposition Generator","knowledgebase/generators","showgenerators",KnowledgebaseModule::MENUKEY_KNOWLEDGEBASE,null,1199);
		return $menuitems;
	}
	

	
	public function hasAccessRight($action) {
		
		return true;
		
		
		switch($action) {
			case "knowledgebase/showpropositions":
				return true;
				break;
			case "knowledgebase/showgenerators":
				return true;
				break;
			default:
				if ($comments) echo "<br>Project index access default";
				return false;	// routerille tiedoksi, että actionia ei löytyny, pitää logittaa virhe
				break;
		}
		return false;
	}
	
	
	
	public function hasAccess($accesskey) {

		return true;
		
		switch($accesskey) {
			case KnowledgebaseModule::ACCESSKEY_MANAGEALL:
				$accesslevel = getAccessLevel(KnowledgebaseModule::ACCESSRIGHTKEY_KNOWLEDGEBASEVISIBILITY);
				if ($accesslevel == AbstractModule::ACCESSRIGHT_READ) return true;
				if ($accesslevel == AbstractModule::ACCESSRIGHT_ALL) return true;
				break;
			default:
				if ($comments) echo "<br>Project accesskey default";
				return false;	// routerille tiedoksi, että accesskey ei löytyny, pitää logittaa virhe
				break;
		}
		
		return false;
	}
	
	
	
}


?>