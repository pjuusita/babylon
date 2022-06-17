<?php

/**
 *  
 *  toiseen keskeneraiseen projektiin liittyvaa koodia, merkkasin systemiksi niin ei tartte saataa kummemmin
 *  
 */
class WorderModule extends AbstractModule {

	
	//const ACCESSKEY_WORDER_SETTINGS = 'worder_accesskey_settings';
	const ACCESSKEY_WORDER_GRAMMARS = 'worder_accesskey_grammars';
	const ACCESSKEY_WORDER_CATEGORIES = 'worder_accesskey_categories';
	const ACCESSKEY_WORDER_CONCEPTS = 'worder_accesskey_concepts';
	const ACCESSKEY_WORDER_LEXICON= 'worder_accesskey_lexicon';
	const ACCESSKEY_WORDER_RULES = 'worder_accesskey_rules';
	const ACCESSKEY_WORDER_LESSONS = 'worder_accesskey_lessons';
	const ACCESSKEY_WORDER_SENTENCES = 'worder_accesskey_sentences';
	const ACCESSKEY_WORDER_AUDIO = 'worder_accesskey_audio';
	
	
	const MENUKEY_CATEGORIES = 'menukey_categories';
	const MENUKEY_CONCEPTS = 'menukey_concepts';
	const MENUKEY_LEXICON = 'menukey_lexicon';
	const MENUKEY_RULES = 'menukey_rules';
	const MENUKEY_LESSONS = 'menukey_lessons';
	const MENUKEY_SENTENCES = 'menukey_sentences';
	const MENUKEY_AUDIO = 'menukey_audio';
	
	const MENUKEY_COMPONENTS = 'menukey_components';
	
	


	public function getDimensions() {
		$dimensions = array();
		$dimensions[Dimension::DIMENSION_LANGUAGE] = new Dimension(Dimension::DIMENSION_LANGUAGE, "Kieli", "Kielet", "worder_languages");
		$dimensions[Dimension::DIMENSION_GRAMMAR] = new Dimension(Dimension::DIMENSION_GRAMMAR, "Kielioppi", "Kieliopit", "worder_grammars");
		return $dimensions;
	}
	
	
	
	public function getAccessRights() {
		
		$accessrights = array ();
		//$accessrights[WorderModule::ACCESSKEY_WORDER_SETTINGS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[WorderModule::ACCESSKEY_WORDER_GRAMMARS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[WorderModule::ACCESSKEY_WORDER_CATEGORIES] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[WorderModule::ACCESSKEY_WORDER_CONCEPTS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[WorderModule::ACCESSKEY_WORDER_LEXICON] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[WorderModule::ACCESSKEY_WORDER_RULES] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[WorderModule::ACCESSKEY_WORDER_LESSONS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[WorderModule::ACCESSKEY_WORDER_SENTENCES] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[WorderModule::ACCESSKEY_WORDER_AUDIO] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		return $accessrights;
	}
	

	public function getDefaultName() {
		return "[1]Worder[2]Worder";
	}
	
	
	// Tämä on kopioitu accounting.module.php
	public function generateSettingsView($registry) {
	
		$comments = true;
	
		$this->registry = $registry;
		$controllerpath = "accounting/bookkeepingsettings";
		$controllername = "bookkeepingsettings";
		$actionname = "loadsettings";
		$actionfile = "bookkeepingsettings";
	
		$file = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . $controllerpath . DIRECTORY_SEPARATOR . $controllername . '.controller.php';
	
		if ($comments) echo "<br>file: "  . $file;
	
		if (is_readable($file) == false) {
			echo $file;
			die ('<br>404 Not Found - ' . $file);
		}
	
		include $file;
		$class = ucfirst($controllername) . 'Controller';
		$controller = new $class($registry);
		$action =  $actionname . 'Action';
	
		if (is_callable(array($controller, $actionname . 'Action')) == false) {
			echo "<br>Action not callable: " . $actionname . "";
			exit;
		}
		$controller->$action();
	
		$file = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . $controllerpath . DIRECTORY_SEPARATOR . $controllername . '.controller.php';
	
		if ($comments) echo "<br>Settingsfile - " . $registry->settingsfile;
		$modulefile = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . $controllerpath . DIRECTORY_SEPARATOR .$actionfile . ".php";
		if ($comments) echo "<br>finalfile - " . $modulefile;
		include $modulefile;
	}
	
	

	public function hasAccess($accesskey) {
		return false;
	}
	
	
	public function getMenu($accessrights) {
		
		$menuitems = array ();
		if ($accessrights == null) return $menuitems;
		
		if ($accessrights[WorderModule::ACCESSKEY_WORDER_GRAMMARS] > 0) {
			$menuitems[] = new Menu("Grammars","worder/grammars","showgrammars",Menu::MENUKEY_TOP,null,1000);
		}
		
		if ($accessrights[WorderModule::ACCESSKEY_WORDER_CATEGORIES] > 0) {
			$menuitems[] = new Menu("Categories","worder/wordclasses","showwordclasslist",Menu::MENUKEY_TOP,WorderModule::MENUKEY_CATEGORIES,1100);
			$menuitems[] = new Menu("Word classes","worder/wordclasses","showwordclasslist",WorderModule::MENUKEY_CATEGORIES,null,1110);
			$menuitems[] = new Menu("Features","worder/features","showfeatures",WorderModule::MENUKEY_CATEGORIES,null,1120);
			$menuitems[] = new Menu("Components","worder/components","showcomponents",WorderModule::MENUKEY_CATEGORIES,null,1130);
			$menuitems[] = new Menu("Arguments","worder/arguments","showarguments",WorderModule::MENUKEY_CATEGORIES,null,1140);
			$menuitems[] = new Menu("Inflectionsets","worder/inflectionsets","showinflectionsets",WorderModule::MENUKEY_CATEGORIES,null,1140);
		}
		
		if ($accessrights[WorderModule::ACCESSKEY_WORDER_CONCEPTS] > 0) {
			$menuitems[] = new Menu("Concepts","worder/concepts","showhierarchy",Menu::MENUKEY_TOP,WorderModule::MENUKEY_CONCEPTS,1200);
			$menuitems[] = new Menu("List","worder/concepts","showconcepts",WorderModule::MENUKEY_CONCEPTS,null,1210);
			$menuitems[] = new Menu("Hierarchy","worder/concepts","showhierarchy",WorderModule::MENUKEY_CONCEPTS,null,1220);
		}
		
		if ($accessrights[WorderModule::ACCESSKEY_WORDER_LEXICON] > 0) {
			$menuitems[] = new Menu("Lexicon","worder/words","showwords",Menu::MENUKEY_TOP,WorderModule::MENUKEY_LEXICON,1300);
			$menuitems[] = new Menu("Taivutusmuodot","worder/words","inflectedforms",WorderModule::MENUKEY_LEXICON,null,1310);
			$menuitems[] = new Menu("Taivutusluokat","worder/words","declensions",WorderModule::MENUKEY_LEXICON,null,1320);
			$menuitems[] = new Menu("Pluralcheck","worder/words","pluralcheck",WorderModule::MENUKEY_LEXICON,null,1330);
		}
		
		if ($accessrights[WorderModule::ACCESSKEY_WORDER_RULES] > 0) {
			$menuitems[] = new Menu("Rules","worder/rules","showrules",Menu::MENUKEY_TOP,WorderModule::MENUKEY_RULES,1400);
			$menuitems[] = new Menu("Rules","worder/rules","showrules",WorderModule::MENUKEY_RULES,null,1420);
			$menuitems[] = new Menu("Translate","worder/translate","translate",WorderModule::MENUKEY_RULES,null,1430);
			$menuitems[] = new Menu("Sentencegenerator","worder/sentences","generator",WorderModule::MENUKEY_RULES,null,1430);
			$menuitems[] = new Menu("RuleSets","worder/rules","showrulesets",WorderModule::MENUKEY_RULES,null,1450);
		}
		
		if ($accessrights[WorderModule::ACCESSKEY_WORDER_LESSONS] > 0) {
			$menuitems[] = new Menu("Lessons","worder/lessons","showlessons",Menu::MENUKEY_TOP,WorderModule::MENUKEY_LESSONS,1500);
			$menuitems[] = new Menu("List","worder/lessons","showlessons",WorderModule::MENUKEY_LESSONS,null,1510);
			$menuitems[] = new Menu("Lexiconlessons","worder/lessons","showlexiconlessons",WorderModule::MENUKEY_LESSONS,null,1510);
			$menuitems[] = new Menu("Hierarchy","worder/lessons","showhierarchy",WorderModule::MENUKEY_LESSONS,null,1520);
			$menuitems[] = new Menu("Objectives","worder/objectives","showobjectives",WorderModule::MENUKEY_LESSONS,null,1530);
		}
		if ($accessrights[WorderModule::ACCESSKEY_WORDER_SENTENCES] > 0) {
			$menuitems[] = new Menu("Sentences","worder/sentences","showsentences",Menu::MENUKEY_TOP,WorderModule::MENUKEY_SENTENCES,1550);
			$menuitems[] = new Menu("Sentences","worder/sentences","showsentences",WorderModule::MENUKEY_SENTENCES,null,1552);
			//$menuitems[] = new Menu("Check Sentences","worder/sentences","showsentences",WorderModule::MENUKEY_SENTENCES,null,1552);
			
			// mikäs allaolevan menuitemin tarkoitus on?
			$menuitems[] = new Menu("Translations","worder/sentences","showsentences",WorderModule::MENUKEY_SENTENCES,null,1554);
			$menuitems[] = new Menu("Testtemplates","worder/sentences","showtemplates",WorderModule::MENUKEY_SENTENCES,null,1552);
			$menuitems[] = new Menu("Sets","worder/sentences","showsentencesets",WorderModule::MENUKEY_SENTENCES,null,1556);
		}
		if ($accessrights[WorderModule::ACCESSKEY_WORDER_AUDIO] > 0) {
			$menuitems[] = new Menu("Audio","worder/audio","showeditaudio",Menu::MENUKEY_TOP,WorderModule::MENUKEY_AUDIO,1600);

			$menuitems[] = new Menu("Sentences","worder/audio","showsentences",WorderModule::MENUKEY_AUDIO,null,1610);
			$menuitems[] = new Menu("Wordcapture","worder/audio","showwordcapture",WorderModule::MENUKEY_AUDIO,null,1613);
			$menuitems[] = new Menu("Capture","worder/audio","showaudiocapture",WorderModule::MENUKEY_AUDIO,null,1620);
			$menuitems[] = new Menu("Clips","worder/audio","showclips",WorderModule::MENUKEY_AUDIO,null,1630);
			$menuitems[] = new Menu("ClipSets","worder/audio","showclipsets",WorderModule::MENUKEY_AUDIO,null,1640);
			$menuitems[] = new Menu("Clip Editor","worder/audio","showeditaudio",WorderModule::MENUKEY_AUDIO,null,1650);
			$menuitems[] = new Menu("Synthesizer","worder/audio","showsynthesizer",WorderModule::MENUKEY_AUDIO,null,1660);
		}
		return $menuitems;
	}
	
	
	

	public function hasAccessRight($action) {
	
		return true;
	
		switch($action) {
			case "lessons/showlessons":
				return true;
				break;
			case "lessons/showlesson":
				return true;
				break;
			case "lessons/insertlesson":
				return true;
				break;
			case "lessons/updatelesson":
				return true;
				break;
		}
		return false;
	}
	
	
	
	
}


?>