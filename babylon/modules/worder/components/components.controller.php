<?php

include_once('./modules/worder/_classes/inheritancemodes.class.php');


class ComponentsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->showcomponentsAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	

	public function showcomponentsAction() {
		
		//$languageID = getSessionVar('languageID', 0);
		//$this->registry->languageID = $languageID;
		updateActionPath("Components");
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		//if ($languageID == 0) {
		//	$this->registry->components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=0");
		//} else {
		//	$this->registry->components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		//}
				
		$this->registry->components = Table::loadHierarchy('worder_components','parentID', "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->template->show('worder/components','components');
	}
	

	public function showcomponentAction() {
	
		$componentID = $_GET['id'];
		$this->registry->components = Table::loadHierarchy('worder_components','parentID', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->component = Table::loadRow("worder_components", "WHERE ComponentID=" . $componentID  ." AND GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		updateActionPath($this->registry->component->abbreviation);
		
		// Haetaan vielä conceptit, jossa tätä componenttia on käytetty...
		$conceptlinks = Table::load("worder_conceptcomponentlinks", "WHERE ComponentID=" . $componentID  ." AND GrammarID=" . $_SESSION['grammarID']);
		$conceptlist = array();
		foreach($conceptlinks as $index => $link) $conceptlist[$link->conceptID] = $link->conceptID;
		$this->registry->concepts = Table::loadWhereInArray("worder_concepts", "ConceptID", $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$argumentlinks = Table::load("worder_conceptargumentlinks", "WHERE ComponentID=" . $componentID  ." AND GrammarID=" . $_SESSION['grammarID']);
		
		//echo "<br>Argumentcount - "  . count($argumentlinks);
		$inheritedconcepts = array();
		$allchilds = array();
		foreach($argumentlinks as $index => $link) {
			if ($link->inheritancemodeID == InheritanceModes::INHERITABLE) {
				$allchilds[$link->conceptID] = $link->conceptID;
				$inheritedconcepts[$link->conceptID] = $link->conceptID;
			}
			if ($link->inheritancemodeID == InheritanceModes::FOR_CHILDS) {
				$inheritedconcepts[$link->conceptID] = $link->conceptID;
			}
			if ($link->inheritancemodeID == InheritanceModes::SINGLE) {
				$allchilds[$link->conceptID] = $link->conceptID;
			}
		}
		foreach($inheritedconcepts as $rowID => $conceptID) {
			//echo "<br>Concept with component - " . $conceptID;
			$childIDs = ComponentsController::getChildIDs($conceptID);
			foreach($childIDs as $childID => $value) {
				$allchilds[$childID] = $childID;
				//echo "<br>- Child concept - "  .$childID;
			}
		}
		$this->registry->argumentconcepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $allchilds, "WHERE GrammarID=" . $_SESSION['grammarID']);
			
		
		
		$this->registry->template->show('worder/components','component');
	}
	
	
	
	/**
	 * TODO: Tämä on aika raskas operaatio
	 *
	 *
	 * @param unknown $conceptID
	 * @return multitype:unknown
	 */
	public static function getChildIDs($conceptID) {
	
		global $mysqli;
	
		$conceptlinks = Table::load("worder_conceptparentlinks","WHERE GrammarID=" . $_SESSION['grammarID']);
	
		$concepts = array();
		$concepts[$conceptID] = $conceptID;
	
		$oldcount = 0;
		$newcount = 1;
		while ($oldcount != $newcount) {
			$oldcount = $newcount;
			foreach($conceptlinks as $index => $conceptlink) {
				//echo "<br>Linkki - " . $conceptlink->conceptID . " - " . $conceptlink->parentID;
				if (isset($concepts[$conceptlink->parentID])) {
					//echo "<br>--child found - " . $conceptlink->conceptID;
					$concepts[$conceptlink->conceptID] = $conceptlink;
				}
			}
			$newcount = count($concepts);
		}
		unset($concepts[$conceptID]);
		//echo "<br>getchhildsids<br>";
		//print_r($concepts);
	
		return $concepts;
	}
	
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	

	public function insertcomponentAction() {
	
		$name =  $_GET['name'];
		$abbreviation =  $_GET['abbreviation'];
		$description =  $_GET['description'];
		$parentID =  $_GET['parentID'];
		//$languageID =  $_GET['languageID'];
		
		$values = array();
		$values['Name'] = $name;
		$values['Abbreviation'] = $abbreviation;
		$values['Description'] = $description;
		//$values['LanguageID'] = $languageID;
		$values['ParentID'] = $parentID;
		$values['LanguageID'] = 0;
		$values['GrammarID'] = $_SESSION['grammarID'];
		
		$success = Table::addRow("worder_components", $values, false);
		
		if ($success === true) {
			addMessage('Lisätty onnistuneesti.');
		} elseif ($success === false) {
			addErrorMessage("Tuntematon tietokantavirhe rrr. - " . $success);
		}
		redirecttotal('worder/components/showcomponents', null);
	}
	
	

	public function updatecomponentAction() {
	
		$componentID = $_GET['id'];
		$name =  $_GET['name'];
		$abbreviation =  $_GET['abbreviation'];
		$description =  $_GET['description'];
		$parentID =  $_GET['parentID'];
		//$languageID =  $_GET['languageID'];
		
		$values = array();
		$values['Name'] = $name;
		$values['Abbreviation'] = $abbreviation;
		$values['Description'] = $description;
		$values['ParentID'] = $parentID;
		//$values['LanguageID'] = 0;
		
		//echo "columns - " . $columns;
		$success = Table::updateRow('worder_components', $values, $componentID, true);
		redirecttotal('worder/components/showcomponent&id=' . $componentID, null);
		
		/*
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
		*/
	}
	

	

	public function removecomponentAction() {
		$componentID = $_GET['id'];
		$success = Table::deleteRow('worder_components',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ComponentID=" . $componentID);
	
		// TODO: poista kyseiseen componenttiin liittyvät rivit muista tauluista tai estä poisto..
	
		redirecttotal('worder/components/showcomponents',null);
	}
	
	
	
}
