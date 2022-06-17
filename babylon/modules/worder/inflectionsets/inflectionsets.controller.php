<?php


class InflectionsetsController extends AbstractController {
	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','worder.css','yritys.css','prism.css','chosen.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','chosen.jquery.js','prism.js');
	}
		
	
	public function indexAction() {
		//$this->showinflectionsetsAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	

	public function showinflectionsetsAction() {
	
		$languageID = getModuleSessionVar('languageID',0);
		$this->registry->languageID = $languageID;
		//echo "<br>LanguageID - " . $languageID;
	
		updateActionPath("Inflectionsets");
	
	
		$this->registry->languages = Table::load('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		if ($languageID == 0) {
			foreach($this->registry->languages as $index => $language) {
				$languageID = $language->languageID;
				$this->registry->languageID = $languageID;
				break;
			}
		}
	
		$features = Table::load('worder_features','WHERE GrammarID=' . $_SESSION['grammarID'] . ' AND LanguageID=' . $languageID, false);
		$inflectionsets = Table::load('worder_inflectionsets','WHERE GrammarID=' . $_SESSION['grammarID'] . ' AND LanguageID=' . $languageID .  " ORDER BY Sortorder", false);
		$inflectionsetitems = Table::load('worder_inflectionsetitems','WHERE GrammarID=' . $_SESSION['grammarID'] . ' AND LanguageID=' . $languageID, false);
		$itemcounts = array();
		$selectedfeaturevalues = array();
		$wordcounts = array();
	
		foreach($inflectionsets as $rowID => $inflectionset) {
			$wordcounts[$inflectionset->inflectionsetID] = 0;
		}
	
		foreach($inflectionsetitems as $rowID => $link) {
				
			if (isset($itemcounts[$link->inflectionsetID])) {
				$itemcounts[$link->inflectionsetID] = $itemcounts[$link->inflectionsetID] + 1;
			} else {
				$itemcounts[$link->inflectionsetID] = 1;
			}
			$wordcounts[$link->inflectionsetID] = 0;
				
			if (isset($selectedfeaturevalues[$link->featureID])) {
				$array = $selectedfeaturevalues[$link->featureID];
				$array[$link->inflectionsetID]  = $link->inflectionsetID;
				$selectedfeaturevalues[$link->featureID] = $array;
				$feature = $features[$link->featureID];
				//echo "<br>SelectedFeature - " . $link->featureID . " - " . $feature->name;
			} else {
				$array = array();
				$array[$link->inflectionsetID]  = 0;
				$wordcounts[$link->inflectionsetID] = 0;
				$selectedfeaturevalues[$link->featureID] = $array;
				$feature = $features[$link->featureID];
				//echo "<br>SelectedFeature - " . $link->featureID . " - " . $feature->name;
			}
		}
	
	
		$wordfeaturelinks = Table::load('worder_wordfeaturelinks','WHERE GrammarID=' . $_SESSION['grammarID'], false);
		//echo "<br>Featurelinks count - " . count($wordfeaturelinks);
		$counter = 0;
		foreach($wordfeaturelinks as $rowID => $link) {
			if (isset($selectedfeaturevalues[$link->valueID])) {
				//echo "<br>FeatureID Found - " . $link->featureID;
				foreach($selectedfeaturevalues[$link->valueID] as $selectedfeatureID => $o2) {
					//echo "<br> -- to selectedfeatureID - " . $selectedfeatureID;
					$wordcounts[$selectedfeatureID] = $wordcounts[$selectedfeatureID] + 1;
					$counter++;
					//if ($counter > 100) break;
				}
			}
			$counter++;
			//if ($counter > 100) break;
		}
	
		foreach($inflectionsets as $index => $inflectionset) {
			if (isset($itemcounts[$inflectionset->inflectionsetID])) {
				$inflectionset->itemcount = $itemcounts[$inflectionset->inflectionsetID];
			} else {
				$inflectionset->itemcount = 0;
			}
			if (isset($wordcounts[$inflectionset->inflectionsetID])) {
				if ($wordcounts[$inflectionset->inflectionsetID] > 10) {
					$inflectionset->wordcount = $wordcounts[$inflectionset->inflectionsetID] . " -- ";
				} else {
					$inflectionset->wordcount = $wordcounts[$inflectionset->inflectionsetID];
				}
			} else {
				$inflectionset->wordcount = 0;
			}
		}
		$this->registry->inflectionsets = $inflectionsets;
	
		$rootitems = array();
		foreach($this->registry->inflectionsets as $index => $inflectionset) {
			//$wordgroup = $this->registry->features[$link->wordgroupID];
			if ($inflectionset->parentID == 0) {
				$rootitems[] = $inflectionset;
	
			} else {
				$parent = $this->registry->inflectionsets[$inflectionset->parentID];
				$parent->addChild($inflectionset);
			}
		}
		$this->registry->hierarchy = $rootitems;
	
	
		$this->registry->template->show('worder/inflectionsets','inflectionsets');
	}
	
	
	
	public function showinflectionsetAction() {
	
		$inflectionsetID = $_GET['id'];
		updateActionPath("Inflectionset");
	
		$this->registry->inflectionset = Table::loadRow('worder_inflectionsets',$inflectionsetID);
		$this->registry->wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->languages = Table::load('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		$this->registry->features = Table::load('worder_features', "WHERE GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Name");
		$this->registry->inflectionsets = Table::load('worder_inflectionsets','WHERE GrammarID=' . $_SESSION['grammarID'] . ' AND LanguageID=' . $this->registry->inflectionset->languageID);
	
	
		$inflectionsetitems = Table::load('worder_inflectionsetitems','WHERE GrammarID=' . $_SESSION['grammarID'] . ' AND InflectionsetID=' . $inflectionsetID);
	
		$selectedfeatures = array();
		$items = array();
		foreach($inflectionsetitems as $index => $link) {
			$feature = $this->registry->features[$link->featureID];
			$link->feature = $feature->name;
			$featurestr = null;
			foreach($link->features as $index => $valuefeatureID) {
				$valuefeature = $this->registry->features[$valuefeatureID];
				if ($featurestr == null) {
					$featurestr = $valuefeature->abbreviation;
				} else {
					$featurestr = $featurestr . "," . $valuefeature->abbreviation;
				}
			}
			$link->featurestr = $featurestr;
			$items[] = $link;
			$selectedfeatures[$link->featureID] = $feature->name;
		}
		$this->registry->inflectionsetitems = $items;
			
		$selectedwordlist = Table::loadWhereInArray('worder_wordfeaturelinks','valueID', $selectedfeatures, "WHERE SystemID=" . $_SESSION['systemID']);
		$selectedlinks = array();
		foreach($selectedwordlist as $index => $wordlink) {
			//echo "<br>Wordlink - " . $wordlink->wordID;
			$selectedlinks[$wordlink->wordID] = $wordlink->wordID;
		}
	
		$this->registry->words = Table::loadWhereInArray('worder_words','wordID', $selectedlinks, "WHERE SystemID=" . $_SESSION['systemID']);
	
	
		//$featurelinks = Table::load('worder_wordfeaturelinks','WHERE GrammarID=' . $_SESSION['grammarID'] . ' AND LanguageID=' . $languageID .  " ORDER BY Sortorder", false);
		//$inflectionsetitems = Table::load('worder_inflectionsetitems','WHERE GrammarID=' . $_SESSION['grammarID'] . ' AND LanguageID=' . $languageID);
		//$concepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $foundconcepts, "WHERE GrammarID=" . $_SESSION['grammarID']);
		//$this->registry->hierarchy = Table::loadHierarchy('worder_inflectionsets','parentID',"WHERE GrammarID=" . $_SESSION['grammarID'] . ' AND LanguageID=' . $languageID .  " ORDER BY Sortorder");
	
	
	
		$this->registry->template->show('worder/inflectionsets','inflectionset');
	}
	
	
	
	public function showinflectionsetitemAction() {
	
		$rowID = $_GET['id'];
	
		$this->registry->features = Table::load('worder_features', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->inflectionsetitem = Table::loadRow('worder_inflectionsetitems','WHERE GrammarID=' . $_SESSION['grammarID'] . ' AND RowID=' . $rowID);
		$languageID = $this->registry->inflectionsetitem->languageID;
		$wordclassID = $this->registry->inflectionsetitem->wordclassID;
		$selectedfeatures = array();
		//echo "<br>Features - " . $this->registry->inflectionsetitem->features;
		foreach ($this->registry->inflectionsetitem->features as $index => $value) {
			//echo "<br>selectedfeatures - " . $index . " - " . $value;
			$selectedfeatures[$value] = $value;
		}
		$this->registry->languages = Table::load('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		$wordclassfeatures = Table::load('worder_wordclassfeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $wordclassID . " AND LanguageID=" . $languageID);
	
		$parentfeatures = array();
		$subvalues = array();
		$featurestruct = new Row();
		foreach($wordclassfeatures as $index => $wordclassfeature) {
			$feature = $this->registry->features[$wordclassfeature->featureID];
			//echo "<br>Found parent feature - " . $feature->name;
			$subvaluelist = array();
			foreach($this->registry->features as $index2 => $childfeature) {
				if ($childfeature->parentID == $feature->featureID) {
					//echo "<br>child found - " . $childfeature->name;
					$subvaluelist[$childfeature->featureID] = $childfeature;
					if (isset($selectedfeatures[$childfeature->featureID])) {
						$name = "feature-" . $feature->featureID;
						$featurestruct->$name = $childfeature->featureID;
					}
				}
			}
			$parentfeatures[$feature->featureID] = $feature;
			$subvalues[$feature->featureID] = $subvaluelist;
		}
		$this->registry->wordclassfeatures = $parentfeatures;
		$this->registry->featurevalues = $subvalues;
		$featurestruct->inflectionsetitemID = $this->registry->inflectionsetitem->rowID;
		$this->registry->featureitem = $featurestruct;
	
		$this->registry->template->show('worder/inflectionsets','inflectionsetitem');
	}
	
	
	
	public function updateinflectionsetitemfeaturesAction() {
	
		$comments = false;
		$inflectionsetitemID = $_GET['id'];
		$inflectionsetitem = Table::loadRow('worder_inflectionsetitems','WHERE GrammarID=' . $_SESSION['grammarID'] . ' AND RowID=' . $inflectionsetitemID);
		$wordclassID = $inflectionsetitem->wordclassID;
		$languageID = $inflectionsetitem->languageID;
		$wordclassfeatures = Table::load('worder_wordclassfeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $wordclassID . " AND LanguageID=" . $languageID);
		$features = Table::load('worder_features', "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		$values = array();
		foreach($wordclassfeatures as $index => $wordclassfeature) {
			$parentfeature = $features[$wordclassfeature->featureID];
			if ($comments) echo "<br><br>Checking - " . $parentfeature->name . " - " . $wordclassfeature->defaultvalueID;
			$name = "feature-" . $wordclassfeature->featureID;
			if (isset($_GET[$name])) {
				$value = $_GET[$name];
				if ($value != "") {
					if ($comments) echo "<br>Value found - " . $value;
					if ($value == $wordclassfeature->defaultvalueID) {
						if ($comments) echo "<br>This is default value";
						$values[] = $value;
					} else {
						if ($comments) echo "<br> -- inservalue - " . $value;
						$values[] = $value;
					}
				}
			}
		}
		if ($comments) echo "<br>Final value = " . implode(":",$values);
		if ($comments) echo "<br><br>";
	
		$insertvalues = array();
		$insertvalues['Features'] = implode(":",$values);
		$success = Table::updateRow("worder_inflectionsetitems", $insertvalues, $inflectionsetitemID);
		if (!$comments) redirecttotal('worder/inflectionsets/showinflectionsetitem&id=' . $inflectionsetitemID, null);
	}
	
	
	
	
	
	public function updateinflectionsetAction() {
	
		$inflectionsetID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['ParentID'] = $_GET['parentID'];
		$values['Description'] = $_GET['description'];
		$success = Table::updateRow("worder_inflectionsets", $values, $inflectionsetID);
		redirecttotal('worder/inflectionsets/showinflectionset&id=' . $inflectionsetID, null);
	}
	
	
	
	
	public function updateinflectionsetitemAction() {
	
		$rowID = $_GET['id'];
		$values = array();
		$values['FeatureID'] = $_GET['featureID'];
		$values['WordclassID'] = $_GET['wordclassID'];
		$success = Table::updateRow("worder_inflectionsetitems", $values, $rowID);
		redirecttotal('worder/inflectionsets/showinflectionsetitem&id=' . $rowID, null);
	}
	
	
	
	
	
	public function removeinflectionsetAction() {
	
		$inflectionsetID = $_GET['inflectionsetID'];
		$items = Table::load('worder_inflectionsetitems', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND InflectionsetID=" . $inflectionsetID);
	
		if (count($items) > 0) {
			echo "<br>Features found in inflectionset, remove not possible";
			exit;
		}
		$success = Table::deleteRowsWhere('worder_inflectionsets',' WHERE InflectionsetID=' . $inflectionsetID . ' AND GrammarID=' . $_SESSION['grammarID']);
		redirecttotal('worder/inflectionsets/showinflectionsets', null);
	}
	
	
	
	public function inflectionsetupAction() {
	
		$comments = false;
	
		if ($comments) echo "<br>inflectionsetupAction";
	
		$inflectionsetID = $_GET['id'];
		$inflectionset = Table::loadRow('worder_inflectionsets','WHERE GrammarID=' . $_SESSION['grammarID'] . ' AND InflectionsetID=' . $inflectionsetID);
		$parentID = $inflectionset->parentID;
	
		if ($comments) echo "<br>ParentID - " . $parentID;
	
		$inflectionsets = Table::load('worder_inflectionsets', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ParentID=" . $inflectionset->parentID .  " ORDER BY Sortorder",$comments);
	
		$previousID = -1;
		$previoussort = -1;
		$currentsort = -1;
		$currentlinkID = -1;
		$previouslinkID = -1;
		foreach($inflectionsets as $index => $link) {
			if ($link->inflectionsetID != $inflectionsetID) {
				echo "<br>previous - " . $link->inflectionsetID;
				$previousID = $link->inflectionsetID;
				$previoussort = $link->sortorder;
				$previouslinkID = $link->inflectionsetID;
			} else {
				echo "<br>current - " . $link->inflectionsetID;
				$currentlinkID = $link->inflectionsetID;
				$currentsort = $link->sortorder;
				break;
			}
		}
			
		if ($previousID == -1) {
			if ($comments) echo "<br>Already up";
			if (!$comments) redirecttotal('worder/inflectionsets/showinflectionsets',null);
			exit;
		}
			
		if ($comments) echo "<br>PreviousID - " . $previousID . " - " . $previoussort;
			
		$values = array();
		$values['Sortorder'] = $previoussort;
		if ($comments) echo "<br>UPDATE - " . $currentlinkID . " - " . $previoussort;
		$success = Table::updateRow("worder_inflectionsets", $values, $currentlinkID, true);
			
		$values = array();
		$values['Sortorder'] = $currentsort;
		if ($comments) echo "<br>UPDATE - " . $previouslinkID . " - " . $currentsort;
		$success = Table::updateRow("worder_inflectionsets", $values, $previouslinkID, true);
	
		if (!$comments) redirecttotal('worder/inflectionsets/showinflectionsets',null);
	}
	
	
	
	public function inflectionsetdownAction() {
	
		$comments = false;
	
		if ($comments) echo "<br>inflectionsetdownAction";
	
		$inflectionsetID = $_GET['id'];
		$inflectionset = Table::loadRow('worder_inflectionsets','WHERE GrammarID=' . $_SESSION['grammarID'] . ' AND InflectionsetID=' . $inflectionsetID);
		$parentID = $inflectionset->parentID;
	
		if ($comments) echo "<br>ParentID - " . $parentID;
	
		$inflectionsets = Table::load('worder_inflectionsets', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ParentID=" . $inflectionset->parentID .  " ORDER BY Sortorder DESC",$comments);
	
		$previousID = -1;
		$previoussort = -1;
		$currentsort = -1;
		$currentlinkID = -1;
		$previouslinkID = -1;
		foreach($inflectionsets as $index => $link) {
			if ($link->inflectionsetID != $inflectionsetID) {
				echo "<br>previous - " . $link->inflectionsetID;
				$previousID = $link->inflectionsetID;
				$previoussort = $link->sortorder;
				$previouslinkID = $link->inflectionsetID;
			} else {
				echo "<br>current - " . $link->inflectionsetID;
				$currentlinkID = $link->inflectionsetID;
				$currentsort = $link->sortorder;
				break;
			}
		}
			
		if ($previousID == -1) {
			if ($comments) echo "<br>Already up";
			if (!$comments) redirecttotal('worder/inflectionsets/showinflectionsets',null);
			exit;
		}
			
		if ($comments) echo "<br>PreviousID - " . $previousID . " - " . $previoussort;
			
		$values = array();
		$values['Sortorder'] = $previoussort;
		if ($comments) echo "<br>UPDATE - " . $currentlinkID . " - " . $previoussort;
		$success = Table::updateRow("worder_inflectionsets", $values, $currentlinkID, true);
			
		$values = array();
		$values['Sortorder'] = $currentsort;
		if ($comments) echo "<br>UPDATE - " . $previouslinkID . " - " . $currentsort;
		$success = Table::updateRow("worder_inflectionsets", $values, $previouslinkID, true);
	
		if (!$comments) redirecttotal('worder/inflectionsets/showinflectionsets',null);
	}
	
	
	
	public function removeinflectionsetitemAction() {
	
		$inflectionsetID = $_GET['inflectionsetID'];
		$rowID = $_GET['id'];
	
		$success = Table::deleteRowsWhere('worder_inflectionsetitems',' WHERE RowID=' . $rowID . ' AND InflectionsetID=' . $inflectionsetID . ' AND GrammarID=' . $_SESSION['grammarID']);
		redirecttotal('worder/inflectionsets/showinflectionset&id=' . $inflectionsetID, null);
	}
	
	
	public function insertinflectionsetAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['ParentID'] = $_GET['inflectionsetID'];
		$values['LanguageID'] = $_GET['languageID'];
		$values['GrammarID'] = $_SESSION['grammarID'];
		$inflectionsetID = Table::addRow("worder_inflectionsets", $values, true);
	
		redirecttotal('worder/inflectionsets/showinflectionsets',null);
	}
	
	
	
	public function insertinflectionsetitemAction() {
	
		$inflectionsetID = $_GET['inflectionsetID'];
		$inflectionset = Table::loadRow('worder_inflectionsets',$inflectionsetID);
	
		$values = array();
		$values['InflectionsetID'] = $inflectionsetID;
		$values['ParentfeatureID'] = $_GET['featureID'];
		$values['FeatureID'] = $_GET['valueID'];
		$values['WordclassID'] = $_GET['wordclassID'];
		$values['LanguageID'] = $inflectionset->languageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$inflectionsetitemID = Table::addRow("worder_inflectionsetitems", $values, false);
	
		redirecttotal('worder/inflectionsets/showinflectionset&id=' . $inflectionsetID,null);
	}
	
	
	
	
	
	public function insertconceptAction() {
	
		$comments = false;
	
		$conceptID = $_GET['conceptID'];
		$lessonID = $_GET['lessonID'];
	
		$values = array();
		$values['ConceptID'] = $conceptID;
		$values['LessonID'] = $lessonID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_lessonconcepts", $values, $comments);
	
		if ($comments == false) redirecttotal('worder/lessons/showlesson&id=' . $lessonID,null);
	}
	
	
	
	public function removewordfromlessonAction() {
		$lessonID = $_GET['lessonID'];
		$conceptID = $_GET['id'];
		$success = Table::deleteRowsWhere('worder_lessonconcepts',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID . " AND ConceptID=" . $conceptID, true);
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
	}
	
	
	
	public function insertsentenceAction() {
	
		$lessonID =  $_GET['lessonID'];
		$sentenceID =  $_GET['sentenceID'];
		$lesson = Table::loadRow("worder_lessons", $lessonID);
	
		$values = array();
		$values['LessonID'] = $lessonID;
		$values['LanguageID'] = $lesson->languageID;
		$values['SentenceID'] = $sentenceID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_lessonsentencelinks", $values, false);
	
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID,null);
	
	}
	
	
	

}
?>