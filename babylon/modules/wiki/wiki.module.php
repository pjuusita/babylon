<?php


class WikiModule extends AbstractModule {
	
	const ACCESSLEVEL_ALL = 10;

	
	
	const ACCESSRIGHTKEY_WIKIVISIBILITY = 'wiki_accesskey_taskvisibility';
	const ACCESSRIGHTKEY_WIKIMANAGEMENT = 'wiki_accesskey_projectmanagement';
	
	const MENUKEY_WIKI = 'menukey_wiki';
	
	
	public function getDefaultName() {
		return "Wiki";
	}
	
	

	public function getAccessRights() {
		$accessrights = array();
		$accessrights[WikiModule::ACCESSRIGHTKEY_WIKIVISIBILITY] = AbstractModule::ACCESSKEYTYPE_READWRITE;;
		$accessrights[WikiModule::ACCESSRIGHTKEY_WIKIMANAGEMENT] = AbstractModule::ACCESSKEYTYPE_READWRITE;;
		return $accessrights;
	}
	
	
	
	public function getMenu($accessrights) {
		
		$menuitems = array();
		//if (isset($accessrights[ACCESSRIGHTKEY_TASKVISIBILITY])) {
			//$accesslevel = $accessrights[PurchasesModule::ACCESSRIGHTKEY_TASKVISIBILITY];
			//$menuindex = 0;
			//if ($accesslevel > 0) {
				$menuitems[] = new Menu("Dokumentit","wiki/documents","showstartpage",Menu::MENUKEY_TOP,WikiModule::MENUKEY_WIKI,1100);
				$menuitems[] = new Menu("Dokumentit","wiki/documents","showstartpage",WikiModule::MENUKEY_WIKI,null,1110);
				$menuitems[] = new Menu("Elementit","wiki/elements","showelements",WikiModule::MENUKEY_WIKI,null,1120);
			//}
		//}
		return $menuitems;
	}
	
	
	// System-module sisältää ainoastaan toimintoja, jotka on aina käytettävissä kaikille
	// käyttäjien hallinta sensitiivisille operaatioille pitää hoitaa tapauskohtaisesti
	// yleensä nämä toiminnot ovat taustalla toimivia operaatioita, kuten tiedoston lataamista
	// sessiomuuttujien asetusta, menu operaatioita jne. Ei käyttöoikeustsekkauksia normaali
	// modulin tapaan
	public function hasAccessRight($action) {
		
		$comments = false;
		
		switch($action) {
			case "elements/showelement":
				return true;
				break;
			case "documents/insertelement":
				return true;
				break;
			case "elements/updateelement":
				return true;
				break;
			case "documents/showstartpage":
				return true;
				break;
			case "documents/showdocument":
				return true;
				break;
			case "documents/updatedocument":
				return true;
				break;
			case "documents/insertdocument":
				return true;
				break;
			default:
				if ($comments) echo "<br>WikiModule index access default";
				return false;	// routerille tiedoksi, että actionia ei löytyny, pitää logittaa virhe
				break;
		}
		return false;
	}
	
	
	
	public function hasAccess($accesskey) {
		return true;
	}
	
	
	
}


?>