<?php


class RulesController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->showrulesAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	
	public function showrulesAction() {
	
		updateActionPath("Rules");
		$languageID = getSessionVar('languageID', 0);
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		if (!isset($this->registry->languages[$languageID])) {
			foreach($this->registry->languages as $index => $language) {
				//echo "<br>Foundlang - " . $language->languageID;
				$languageID = $language->languageID;
				getSessionVar('languageID',$languageID);
				break;
			}
		}
		$this->registry->languageID = $languageID;
		
		$this->registry->wordclasses = Table::load('worder_wordclasses',"WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->arguments = Table::load('worder_arguments', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->positions = array ( '0' => '0', '-1' => '-1', '+1' => '+1' );
		$this->registry->statuses = RulesController::getRuleStatusCollection();
		
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->rules = Table::loadHierarchy('worder_rules','parentID', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND Analyse!=2 ORDER BY Sortorder", false, true);
		$this->registry->resultrules = Table::loadHierarchy('worder_rules','parentID', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND Analyse=2 ORDER BY Sortorder", false, true);
		
		$this->registry->template->show('worder/rules','rules');
	}
	
	
	
	public function showrulesetsAction() {
		
		if (!isModuleSessionVarSetted('languageID')) {
			$languageID = 0;
			foreach($this->registry->languages as $index => $language) {
				$languageID = $language->languageID;
				break;
			}
			setModuleSessionVar('languageID',$languageID);
			$this->registry->languageID = $languageID;
		} else {
			$this->registry->languageID = getModuleSessionVar('languageID', 0);
		}
		
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		
		updateActionPath("RuleSets");
		
		
		
		if ($this->registry->languageID == 0) {
			//echo "<br>LanguageID is 0";
			$this->registry->rulesets = Table::load("worder_rulesets", "WHERE LanguageID=0 AND GrammarID=" . $_SESSION['grammarID']);
			$this->registry->sentencesets = Table::load("worder_sentencesets", "WHERE GrammarID=" . $_SESSION['grammarID']);
		} else {
			//echo "<br>LanguageID is not nolla - " . $this->registry->languageID;
			$this->registry->rulesets = Table::load("worder_rulesets", "WHERE LanguageID=" . $this->registry->languageID  ." AND GrammarID=" . $_SESSION['grammarID']);
			$this->registry->sentencesets = Table::load("worder_sentencesets", "WHERE GrammarID=" . $_SESSION['grammarID']);
			//$this->registry->sentencesets = Table::load("worder_sentencesets", "WHERE LanguageID=" . $this->registry->languageID . " AND GrammarID=" . $_SESSION['grammarID']);
		}
		$this->registry->template->show('worder/rules','rulesets');
	}
	

	public function showrulesetAction() {
		$setID = $_GET['id'];
		$this->registry->ruleset = Table::loadRow("worder_rulesets", "WHERE SetID=" . $setID . " AND GrammarID=" . $_SESSION['grammarID']);
		updateActionPath($this->registry->ruleset->name);
		$rules = Table::load('worder_rules', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->links = Table::load('worder_rulesetlinks',"WHERE SetID=" . $setID . " AND GrammarID=" . $_SESSION['grammarID']);
		$this->registry->languages = Table::load('worder_languages',"WHERE GrammarID=" . $_SESSION['grammarID']);
		
		foreach($rules as $index => $rule) {
			$rule->selected = 0;
		}
		foreach($this->registry->links as $index => $link) {
			if (!isset($rules[$link->ruleID])) {
				echo "<br>Unknown rule - " . $link->ruleID;
			}
			$rules[$link->ruleID]->selected = 1;
		}
		$this->registry->rules = $rules;
		$this->registry->template->show('worder/rules','ruleset');
	}
	
	// Asetetaan sessionmuuttujiin generatetaulun checkboxien arvot
	public function checkruleJSONAction() {

		$ruleID = $_GET['ruleID'];
		$setID = $_GET['setID'];
		
		$rule = Table::loadRow("worder_rules", "WHERE RuleID=" . $ruleID . " AND GrammarID=" . $_SESSION['grammarID']);
		
		$values = array();
		$values['RuleID'] = $ruleID;
		$values['SetID'] = $setID;
		$values['LanguageID'] = $rule->languageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$setID = Table::addRow("worder_rulesetlinks", $values, false);
		
		echo "1";
		return;
	}
	
	
	public function uncheckruleJSONAction() {
	
		$ruleID = $_GET['ruleID'];
		$setID = $_GET['setID'];
		$success = Table::deleteRow('worder_rulesetlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID . " AND RuleID=" . $ruleID);
		echo "1";
		return;
	}
	
	
	public function insertrulesetAction() {
	
		$name =  $_GET['name'];
        $languageID = $_GET['languageID'];
		
		$values = array();
		$values['Name'] = $name;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['LanguageID'] = $languageID;
		$values['SentencesetID'] = 0;
		$setID = Table::addRow("worder_rulesets", $values, false);
	
		redirecttotal('worder/rules/showrulesets', null);
	}
	
	
	
	public function showruleAction() {
		
		include_once('./modules/worder/_classes/rule.class.php');
		
		$ruleID = $_GET['id'];
		$rule = Table::loadRow("worder_rules","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		updateActionPath("Rule-" . $ruleID);
		
		if ($rule == null) {
			echo "<br>Rule not found - " . $ruleID;
			exit;
		}
		
		$this->registry->rule = $rule;
		
		$this->registry->positions = RulesController::getArgumentPositionCollection();
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->statuses = RulesController::getRuleStatusCollection();
		$this->registry->operators = Rule::getRuleOperators();
		$this->registry->wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclass = $this->registry->wordclasses[$this->registry->rule->wordclassID];
		
		$this->registry->features = Table::load('worder_features',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $rule->languageID);
		$this->registry->rules = Table::load('worder_rules',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $rule->languageID);
		$this->registry->arguments = Table::load('worder_arguments', " WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $this->registry->rule->wordclassID . " OR WordclassID=0");
		$this->registry->components = Table::load('worder_components', "WHERE GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Name");
		$this->registry->semanticfeatures = Table::load('worder_features',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=0");
		$this->registry->wordclassfeatures = Table::load('worder_wordclassfeatures',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $rule->languageID);
		$this->registry->ruleterms= Table::load('worder_ruleterms', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $this->registry->rule->ruleID);
		$this->registry->featureagreements = Table::load('worder_rulefeatureagreements', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $this->registry->rule->ruleID);
			
		$this->registry->sentencelinks = Table::load("worder_rulesentencelinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID . " AND LanguageID=" . $rule->languageID);
		$sentencelinks = array();
		if ($this->registry->sentencelinks == null) {
			$this->registry->sentences = array();
		} else {
			foreach($this->registry->sentencelinks as $index => $link) {
				$sentencelinks[$link->sentenceID] = $link->sentenceID;
			}
			//echo "<br>Sentencelinks - " . count($sentencelinks);
			$language = $this->registry->languages[$rule->languageID];
			$this->registry->sentences = Table::loadWhereInArray("worder_sentences", "SentenceID", $sentencelinks, "WHERE GrammarID=" . $_SESSION['grammarID']);
		}
		
		$this->registry->featureconstraints = Table::load('worder_rulefeatureconstraints', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $this->registry->rule->ruleID);
		$this->registry->componentrequirements = Table::load('worder_rulecomponentrequirements', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $this->registry->rule->ruleID);
		$this->registry->resultfeatures = Table::load('worder_ruleresultfeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $this->registry->rule->ruleID);
		
		$rulesetlinks = Table::load('worder_rulesetlinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $this->registry->rule->ruleID);
		$rulesetlist = array();
		foreach($rulesetlinks as $index => $link) {
			$rulesetlist[$link->setID] = $link->setID;
		}
		$this->registry->sentencesets = Table::loadWhereInArray("worder_sentencesets", "RulesetID", $rulesetlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->template->show('worder/rules','rule');
	}
	
	


	public function showresultruleAction() {
	
		include_once('./modules/worder/_classes/rule.class.php');
	
		updateActionPath("ResultRule");
		$ruleID = $_GET['id'];
		$rule = Table::loadRow("worder_rules","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
	
		if ($rule == null) {
			echo "<br>Rule not found - " . $ruleID;
			exit;
		}
	
		$this->registry->rule = $rule;
	
		$this->registry->positions = RulesController::getArgumentPositionCollection();
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->statuses = RulesController::getRuleStatusCollection();
		$this->registry->operators = Rule::getRuleOperators();
		$this->registry->wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclass = $this->registry->wordclasses[$this->registry->rule->wordclassID];
	
		$this->registry->features = Table::load('worder_features',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $rule->languageID);
		$this->registry->rules = Table::load('worder_rules',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $rule->languageID);
		$this->registry->arguments = Table::load('worder_arguments', " WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $this->registry->rule->wordclassID . " OR WordclassID=0");
		$this->registry->components = Table::load('worder_components', "WHERE GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Name");
		$this->registry->semanticfeatures = Table::load('worder_features',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=0");
		$this->registry->wordclassfeatures = Table::load('worder_wordclassfeatures',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $rule->languageID);
		$this->registry->ruleterms= Table::load('worder_ruleterms', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $this->registry->rule->ruleID);
		$this->registry->featureagreements = Table::load('worder_rulefeatureagreements', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $this->registry->rule->ruleID);
			
		$this->registry->sentencelinks = Table::load("worder_rulesentencelinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID . " AND LanguageID=" . $rule->languageID);
		$sentencelinks = array();
		if ($this->registry->sentencelinks == null) {
			$this->registry->sentences = array();
		} else {
			foreach($this->registry->sentencelinks as $index => $link) {
				$sentencelinks[$link->sentenceID] = $link->sentenceID;
			}
			//echo "<br>Sentencelinks - " . count($sentencelinks);
			$language = $this->registry->languages[$rule->languageID];
			$this->registry->sentences = Table::loadWhereInArray("worder_sentences", "SentenceID", $sentencelinks, "WHERE GrammarID=" . $_SESSION['grammarID']);
		}
		$this->registry->featureconstraints = Table::load('worder_rulefeatureconstraints', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $this->registry->rule->ruleID);
		$this->registry->componentrequirements = Table::load('worder_rulecomponentrequirements', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $this->registry->rule->ruleID);
		$this->registry->resultfeatures = Table::load('worder_ruleresultfeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $this->registry->rule->ruleID);
		$this->registry->template->show('worder/rules','resultrule');
	}
	
	
	
	public function getlanguagerulesetsJSONAction() {

		$languageID = $_GET['languageID'];
		$rulesets = Table::load('worder_rulesets', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		
		echo "[";
		$first = true;
		foreach($rulesets as $index => $ruleset) {
			if ($first == true) $first = false; else echo ",";
			echo " {";
			echo "	  \"setID\":\"" . $ruleset->setID . "\",";
			echo "	  \"name\":\"" . $ruleset->name . "\"";
			echo " }\n";
		}
		echo "]";
	}
	
	
	public function getArgumentSelectionJSONAction() {
	
		$wordclassID = $_GET['wordclassID'];
		//echo "<br>wordclass - " . $wordclassID;
		$arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $wordclassID . " AND LanguageID=0");
		//echo "<br>arguments - " . count($arguments);
		$array = array();
		foreach($arguments as $index => $argument) {
			$array[$argument->argumentID] = $argument->name;
			//echo "<br>" . $argument->argumentID . " - " . $argument->name;
		}
		echo json_encode($array);		
	}

	
	
	public function getWordclassFeatureSelectionJSONAction() {

		$wordclassID = $_GET['wordclassID'];
		$languageID = $_GET['languageID'];
		
		//echo "<br>Wordclass - " . $wordclassID;
		//echo "<br>Language - " . $languageID;
		
		$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);
		$wordclassfeatures = Table::load("worder_arguments"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $wordclassID . " AND LanguageID=" . $languageID);
		
		$array = array();
		foreach($wordclassfeatures as $index => $item) {
			$feature = $features[$item->featureID];
			$array[$item->featureID] = $feature->name;
		}
		echo json_encode($array);
	}
	

	public function getFeatureValueSelectionJSONAction() {

		$featureID = $_GET['featureID'];
	
		$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);
	
		$array = array();
		foreach($features as $index => $feature) {
			if ($feature->parentID == $featureID) $array[$feature->featureID] = $feature->name;
		}
		echo json_encode($array);
	}
	
	
	public function getComponentSelectionJSONAction() {
	
		$components= Table::load('worder_components', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$array = array();
		foreach($components as $componentID => $component) {
			$array[$component->componentID] = $component->abbreviation;
		}
		echo json_encode($array);
	}
	
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function updateruleAction() {

		$type = $_GET['type'];
		$ruleID = $_GET['id'];
		$languageID = $_GET['languageID'];
		$name = $_GET['Name'];
		$description = $_GET['Description'];
		$parentID = $_GET['parentID'];
		$status = $_GET['Status'];
		$conceptID = 0;
		if (isset($_GET['conceptID'])) $conceptID = $_GET['conceptID'];
		$wordclassID = $_GET['wordclassID'];
		
		$insertarray = array();
		$insertarray['LanguageID'] = $languageID;
		$insertarray['Name'] = $name;
		$insertarray['ParentID'] = $parentID;
		$insertarray['ConceptID'] = $conceptID;
		$insertarray['WordclassID'] = $wordclassID;
		$insertarray['Description'] = $description;
		$insertarray['Generate'] = $_GET['generate'];
		$insertarray['Analyse'] = $_GET['analyse'];
		$insertarray['Status'] = $status;
		
		$success = Table::updateRow('worder_rules',$insertarray, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
	
		if ($type == 2) {
			redirecttotal('worder/rules/showresultrule&id=' . $ruleID ,null);
		} else {
			redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
		}
	}

	
	public function updatefeatureconstraintAction() {
	
		$comments = false;
		
		$rowID = $_GET['id'];
		$ruleID = $_GET['ruleID'];
		$position = $_GET['position'];
		$featureID = $_GET['featureID'];
		$featurevalueID = $_GET['featurevalueID'];
		$operator = $_GET['operator'];
		
		$insertarray = array();
		$insertarray['RuleID'] = $ruleID;
		$insertarray['Position'] = $position;
		$insertarray['FeatureID'] = $featureID;
		$insertarray['FeaturevalueID'] = $featurevalueID;
		$insertarray['Operator'] = $operator;
		
		$success = Table::updateRow('worder_rulefeatureconstraints',$insertarray, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID,$comments);
	
		if ($comments == false) {
			redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
			exit();
		} else {
			echo "finnish.";
			exit;
		}
	}
	
	
	


	public function updateresultfeatureAction() {
		
		$comments = false;
			
		$rowID = $_GET['id'];
		$ruleID = $_GET['ruleID'];
		$featureID = $_GET['featureID'];
		$valueID = $_GET['valueID'];
	
		$insertarray = array();
		$insertarray['RuleID'] = $ruleID;
		$insertarray['FeatureID'] = $featureID;
		$insertarray['ValueID'] = $valueID;
		
		$success = Table::updateRow('worder_ruleresultfeatures',$insertarray, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID, $comments);
		//$success = Table::addRow('worder_ruleresultfeatures',$insertarray, $comments);
	
		if ($comments) 	echo "finnish.";
		if (!$comments) redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
	}
	
	
	
	

	public function updatecomponentrequirementAction() {
	
		$comments = false;
	
		$ruleID = $_GET['ruleID'];
		$rowID = $_GET['id'];
		$position = $_GET['position'];
		$componentID = $_GET['componentID'];
		$presenceID = $_GET['presence'];
	
		$updatearray = array();
		$updatearray['Position'] = $position;
		$updatearray['ComponentID'] = $componentID;
		$updatearray['Presence'] = $presenceID;
	
		$success = Table::updateRow('worder_rulecomponentrequirements', $updatearray, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID, $comments);
	
		if ($comments) 	echo "finnish.";
		if (!$comments) redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
	}
	
	

	public function updatefeatureagreementAction() {
	
		$comments = true;
		
		$rowID = $_GET['id'];
		$ruleID = $_GET['ruleID'];
		$featureID = $_GET['featureID'];
		$position1 = $_GET['position1'];
		$position2 = $_GET['position2'];
	
		$updatearray = array();
		$updatearray['RuleID'] = $ruleID;
		$updatearray['FeatureID'] = $featureID;
		$updatearray['Position1'] = $position1;
		$updatearray['Position2'] = $position2;
	
		$success = Table::updateRow('worder_rulefeatureagreements', $updatearray, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID, $comments);
		
		if ($comments == false) {
			redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
			exit();
		} else {
			echo "finnish.";
			exit;
		}
	}
	
	

	public function updatetermAction() {
	
		$comments = false;
	
		$ruleID = $_GET['ruleID'];
		$rowID = $_GET['id'];
		$wordclassID = $_GET['wordclassID'];
		$argumentID = $_GET['argumentID'];
		$conceptID = 0;
		if (isset($_GET['conceptID'])) {
			$conceptID = $_GET['conceptID'];
			if ($conceptID == '') $conceptID = 0;
		}
		$argumensallowed = null;
		if (isset($_GET['argumentsallowed'])) {
			$argumensallowed = $_GET['argumentsallowed'];
		}
		$position = $_GET['position'];
	
		$updatearray = array();
		$updatearray['WordclassID'] = $wordclassID;
		$updatearray['ArgumentID'] = $argumentID;
		$updatearray['Position'] = $position;
		$updatearray['Argumentsallowed'] = $argumensallowed;
		$updatearray['ConceptID'] = $conceptID;
	
		$success = Table::updateRow('worder_ruleterms', $updatearray,"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID, $comments);
	
		if (!$comments) {
			redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
		}
	
	}
	
	
	public function updaterulesetAction() {
	
		$comments = false;
	
		$setID = $_GET['id'];
	
		$updatearray = array();
		$updatearray['Name'] = $_GET['name'];
		$updatearray['LanguageID'] = $_GET['languageID'];
	
		$success = Table::updateRow('worder_rulesets', $updatearray,"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID, $comments);
		if (!$comments) {
			redirecttotal('worder/rules/showruleset&id=' . $setID ,null);
		}
	}
	
	
	
	
	
	public function updateunsetsAction() {
	
		$comments = false;
	
		$ruleID = $_GET['ruleID'];
		$rowID = $_GET['id'];
		$argumentID = 0;
		if (isset($_GET['argumentID'])) $argumentID = $_GET['argumentID'];
		$featureID = 0;
		if (isset($_GET['featureID'])) $argumentID = $_GET['featureID'];
		$position = $_GET['position'];
	
		$updatearray = array();
		$updatearray['Position'] = $position;
		$updatearray['ArgumentID'] = $argumentID;
		$updatearray['FeatureID'] = $featureID;
	
		$success = Table::updateRow('worder_ruleunsets', $updatearray,"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID, $comments);
	
		if (!$comments) {
			redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
		}
	
	}
	
	
	public static function getArgumentPositionCollection() {
		$positions = array ( '-1' => 'before (-1)', '1' => 'after (+1)' );
		return $positions;
	}
	
	

	public static function getRuleStatusCollection() {
		$statuslist = array ( '0' => 'Disabled', '1' => 'Active' );
		//$positions = array ( '0' => 'Not done', '1' => 'development', '2' => 'Testing', '5' => 'Ready' );
		return $statuslist;
	}
	
	

	public function insertruleAction() {
	
		$languageID = $_GET['languageID'];
		$wordclassID = $_GET['wordclassID'];
		$parentID = $_GET['parentID'];
		
		//$argumentID = $_GET['argumentID'];
		//$argumentposition = $_GET['argumentposition'];
		$name = $_GET['name'];
		$description = $_GET['description'];
		
		$insertarray = array();
		$insertarray['LanguageID'] = $languageID;
		$insertarray['WordclassID'] = $wordclassID;
		//$insertarray['ArgumentID'] = $argumentID;
		//$insertarray['Argumentposition'] = $argumentposition;
		$insertarray['Name'] = $name;
		$insertarray['Description'] = $description;
		$insertarray['GrammarID'] = $_SESSION['grammarID'];
		$insertarray['Status'] = 1;
		$insertarray['ParentID'] = $parentID;
		$insertarray['Generate'] = 1;
		$insertarray['Analyse'] = 1;
		
		$newID = Table::addRow('worder_rules',$insertarray, true);
		
		if ($newID != null) {
			redirecttotal('worder/rules/showrule&id=' . $newID ,null);
			exit();
		}
		
		echo "finnish.";
		exit;
	}

	

	public function insertresultruleAction() {
	
		$languageID = $_GET['languageID'];
		$wordclassID = $_GET['wordclassID'];
		$name = $_GET['name'];
	
		$insertarray = array();
		$insertarray['LanguageID'] = $languageID;
		$insertarray['WordclassID'] = $wordclassID;
		$insertarray['Name'] = $name;
		$insertarray['GrammarID'] = $_SESSION['grammarID'];
		$insertarray['Status'] = 1;
		$insertarray['Generate'] = 2;
		$insertarray['Analyse'] = 2;
		
		$newID = Table::addRow('worder_rules',$insertarray, true);
	
		if ($newID != null) {
			redirecttotal('worder/rules/showresultrule&id=' . $newID ,null);
			exit();
		}
	
		echo "finnish.";
		exit;
	}
	
	
	


	public function insertgenerateresultAction() {
	
		$comments = false;
	
		$ruleID = $_GET['ruleID'];
		$featureID = $_GET['featureID'];
		$valueID = $_GET['valueID'];
		$position = $_GET['position'];
		if ($position == '') $position = null;
	
		$insertarray = array();
		$insertarray['RuleID'] = $ruleID;
		$insertarray['FeatureID'] = $featureID;
		$insertarray['ValueID'] = $valueID;
		$insertarray['Position'] = $position;
		$insertarray['GrammarID'] = $_SESSION['grammarID'];
	
		$success = Table::addRow('worder_ruleresultfeatures',$insertarray, $comments);
	
		if ($comments) 	echo "finnish.";
		if (!$comments) redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
	}
	
	
	public function insertsentenceAction() {
	
		$ruleID = $_GET['ruleID'];
		$rule =  Table::loadRow("worder_rules","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		$languageID = $rule->languageID;
			
		$values = array();
		$values['Sentence'] = $_GET['sentence'];
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['Correctness'] = $_GET['correctness'];
		$sentenceID = Table::addRow("worder_sentences", $values, false);

		$values = array();
		$values['LanguageID'] = $languageID;
		$values['RuleID'] = $ruleID;
		$values['SentenceID'] = $sentenceID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$success = Table::addRow("worder_rulesentencelinks", $values, false);
	
		redirecttotal('worder/rules/showrule&id=' . $ruleID, null);
	}
	
	

	public function moveruleAction() {
	
		$ruleID = $_GET['id'];
		$comments = false;
		$orderby = "";
		if (isset($_GET['dir'])) {
			$direction = $_GET['dir'];
			if ($direction == 'down') $orderby = "DESC";
		}
		$rule = Table::loadRow("worder_rules", "WHERE RuleID=" . $ruleID . " AND GrammarID=" . $_SESSION['grammarID']);
		if ($comments) echo "<br>Parentti - "  . $rule->parentID;
	
		$rules = Table::load("worder_rules", "WHERE ParentID=" . $rule->parentID . " AND GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Sortorder " . $orderby);
	
		if ($comments) echo "<br>count - " . count($rules);
		$current = null;
		$previous = null;
		foreach($rules as $index => $rule) {
	
			if ($comments) echo "<br>Loop - " . $rule->name;
	
			if ($rule->ruleID == $ruleID) {
				$current = $rule;
				if ($previous == null) {
					if ($comments) echo "<br>Already first";
					$previous = null;
					break;
				} else {
					//$previousID = $objective->rowID;
					break;
				}
			}
			$previous = $rule;
		}
	
		if ($comments) echo "<br>Previous - " . $previous->name;
		if ($comments) echo "<br>Current - " . $current->name;
	
		if (($previous != null) && ($current != null)) {
	
			global $mysqli;
	
	
			$sql = "UPDATE worder_rules SET Sortorder='" . $previous->sortorder . "' WHERE RuleID=" . $current->ruleID . "";
			//echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) echo "<br>Error: " . $mysqli->error;
	
			$sql = "UPDATE worder_rules SET Sortorder='" . $current->sortorder . "' WHERE RuleID=" . $previous->ruleID  . "";
			//echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) echo "<br>Error: " . $mysqli->error;
		}
	
		if (!$comments) redirecttotal('worder/rules/showrules', null);
	}
	
	

	// Lisätään olemassaoleva lause
	public function addexistingssentenceAction() {
		
		$sentenceID = $_GET['sentenceID'];
		$ruleID = $_GET['ruleID'];
		$rule =  Table::loadRow("worder_rules","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID, true);
		
		$values = array();
		$values['RuleID'] = $rule->ruleID;
		$values['LanguageID'] = $rule->languageID;
		$values['SentenceID'] = $sentenceID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$sentenceID = Table::addRow("worder_rulesentencelinks", $values, true);
		
		redirecttotal('worder/rules/showrule&id=' . $ruleID, null);
	}
	

	public function insertconceptAction() {
	
		$comments = false;
	
		$ruleID = $_GET['ruleID'];
		$conceptID = $_GET['conceptID'];
		$position = $_GET['position'];
	
		if ($comments) echo "<br>ruleID - " . $ruleID;
		if ($comments) echo "<br>ConceptID - " . $conceptID;
		if ($comments) echo "<br>position - " . $position;
	
	
		$insertarray = array();
		$insertarray['ConceptID'] = $conceptID;
		$success = Table::updateRow('worder_ruleterms',$insertarray, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID . " AND Position=" . $position);
	
		//$success = Table::addRow('worder_ruleresultfeatures',$insertarray, $comments);
	
		if ($comments) 	echo "finnish.";
		if (!$comments) redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
	}
	
	
	


	public function insertcomponentrequirementAction() {
	
		$comments = false;
	
		$ruleID = $_GET['ruleID'];
		$position = $_GET['position'];
		$componentID = $_GET['componentID'];
		$presenceID = $_GET['presence'];
	
		$insertarray = array();
		$insertarray['RuleID'] = $ruleID;
		$insertarray['Position'] = $position;
		$insertarray['ComponentID'] = $componentID;
		$insertarray['Presence'] = $presenceID;
		$insertarray['GrammarID'] = $_SESSION['grammarID'];
	
		$success = Table::addRow('worder_rulecomponentrequirements',$insertarray, $comments);
	
		if ($comments) 	echo "finnish.";
		if (!$comments) redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
	}
	
	
	
	
	public function inserttermAction() {
	
		$comments = false;
		
		$ruleID = $_GET['ruleID'];
		$wordclassID = $_GET['wordclassID'];
		$argumentID = $_GET['argumentID'];
		$position = $_GET['position'];
		$argumensallowed = $_GET['argumensallowed'];
		
		// tsekataan onko kyseisessä positiossa jo ennestään rulea
		$existingterm = Table::load("worder_ruleterms", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID . " AND Position=" . $position);
		if ($existingterm == null) {

			$insertarray = array();
			$insertarray['RuleID'] = $ruleID;
			$insertarray['WordclassID'] = $wordclassID;
			$insertarray['ArgumentID'] = $argumentID;
			$insertarray['Position'] = $position;
			$insertarray['Argumentsallowed'] = $argumensallowed;
			$insertarray['ConceptID'] = 0;
			$insertarray['GrammarID'] = $_SESSION['grammarID'];
			$success = Table::addRow('worder_ruleterms',$insertarray, true);
		} else {
			echo "<br>Rule already has term in position " . $position;
			exit;
		}
		
		if (!$comments) redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
	}
	



	public function insertleftsideconstraintAction() {
	
		$comments = false;
	
		$ruleID = $_GET['ruleID'];
		$featureID = $_GET['featureID'];
		$valueID = $_GET['valueID'];
		$operator = 1;
		$operator = $_GET['operator'];
		$position = -1;
	
		$insertarray = array();
		$insertarray['RuleID'] = $ruleID;
		$insertarray['FeatureID'] = $featureID;
		$insertarray['FeaturevalueID'] = $valueID;
		$insertarray['GrammarID'] = $_SESSION['grammarID'];
		$insertarray['Position'] = $position;
		$insertarray['Operator'] = $operator;
	
		$success = Table::addRow('worder_rulefeatureconstraints',$insertarray, $comments);
	
		if ($comments == false) {
			redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
			exit();
		} else {
			echo "finnish.";
			exit;
		}
	}
	
	

	public function insertfeatureconstraintAction() {
	
		$comments = false;
	
		$ruleID = $_GET['ruleID'];
		$position = $_GET['position'];
		$featureID = $_GET['featureID'];
		$valueID = $_GET['featurevalueID'];
		if (isset($_GET['operator'])) {
			$operator = $_GET['operator'];
		} else {
			$operator = 1;
		}
	
		$insertarray = array();
		$insertarray['RuleID'] = $ruleID;
		$insertarray['Position'] = $position;
		$insertarray['FeatureID'] = $featureID;
		$insertarray['FeaturevalueID'] = $valueID;
		$insertarray['GrammarID'] = $_SESSION['grammarID'];
		$insertarray['Operator'] = $operator;
	
		$success = Table::addRow('worder_rulefeatureconstraints',$insertarray, $comments);
	
		if ($comments == false) {
			redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
			exit();
		} else {
			echo "finnish.";
			exit;
		}
	}
	
	

	public function insertresultfeatureAction() {
	
		$comments = false;
	
		$type = 1;
		if (isset($_GET['type'])) $type = $_GET['type'];
		$ruleID = $_GET['ruleID'];
		$featureID = $_GET['featureID'];
		$valueID = $_GET['valueID'];

		$position = 0;
		if (isset($_GET['position'])) $position = $_GET['position'];
		if ($position == '') $position = null;
	
		$insertarray = array();
		$insertarray['RuleID'] = $ruleID;
		$insertarray['FeatureID'] = $featureID;
		$insertarray['ValueID'] = $valueID;
		$insertarray['Position'] = $position;
		$insertarray['GrammarID'] = $_SESSION['grammarID'];
	
		$success = Table::addRow('worder_ruleresultfeatures',$insertarray, $comments);
	
		if ($comments) 	echo "finnish.";
		if (!$comments) {
			if ($type == 2) {
				redirecttotal('worder/rules/showresultrule&id=' . $ruleID ,null);
			} else {
				redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
			}
		}
	}
	
	
	

	public function insertfeatureagreementAction() {
	
		$ruleID = $_GET['ruleID'];
		$featureID = $_GET['featureID'];
		$position1 = $_GET['position1'];
		$position2 = $_GET['position2'];
	
		$insertarray = array();
		$insertarray['RuleID'] = $ruleID;
		$insertarray['FeatureID'] = $featureID;
		$insertarray['Position1'] = $position1;
		$insertarray['Position2'] = $position2;
		$insertarray['GrammarID'] = $_SESSION['grammarID'];
	
		$success = Table::addRow('worder_rulefeatureagreements',$insertarray, true);
	
	
		if ($success != null) {
			redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
			exit();
		}
	
	
		echo "finnish.";
		exit;
	}
	
	
	

	public function insertunsetAction() {
	
		$comments = false;
	
		$ruleID = $_GET['ruleID'];
		$argumentID = $_GET['argumentID'];
		$featureID = $_GET['featureID'];
		$position = $_GET['position'];
		
		$insertarray = array();
		$insertarray['RuleID'] = $ruleID;
		$insertarray['ArgumentID'] = $argumentID;
		$insertarray['FeatureID'] = $featureID;
		$insertarray['Position'] = $position;
		$insertarray['GrammarID'] = $_SESSION['grammarID'];
		$success = Table::addRow('worder_ruleunsets',$insertarray, $comments);
	
		if (!$comments) redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
	}
	
	



	public function copyruleAction() {
		
		$comments = false;
		$ruleID = $_GET['id'];
		$rule =  Table::loadRow("worder_rules","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		
		$newRuleID = Table::addRow('worder_rules',$rule, $comments);

		// Kopioidaan ruleterms
		$ruleterms = Table::load('worder_ruleterms', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		foreach($ruleterms as $rowID => $term) {
			$insertarray = array();
			$term->ruleID = $newRuleID;
			$success = Table::addRow('worder_ruleterms',$term, $comments);
		}
		
		// Kopioidaan rulefeatureconstraints
		$featureconstraints = Table::load('worder_rulefeatureconstraints', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		foreach($featureconstraints as $rowID => $constraint) {
			$constraint->ruleID = $newRuleID;
			$success = Table::addRow('worder_rulefeatureconstraints',$constraint, $comments);
		}
		
		// Kopioidaan rulecomponentrequirements
		$componentrequirements = Table::load('worder_rulecomponentrequirements', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		foreach($componentrequirements as $rowID => $componentrequirement) {
			$componentrequirement->ruleID = $newRuleID;
			$success = Table::addRow('worder_rulecomponentrequirements',$componentrequirement, $comments);
		}
		
		// Kopioidaan  worder_rulefeatureagreements
		$agreements = Table::load('worder_rulefeatureagreements', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		foreach($agreements as $rowID => $agreement) {
			$agreement->ruleID = $newRuleID;
			$success = Table::addRow('worder_rulefeatureagreements',$agreement, $comments);
		}
		
		// Kopioidaan ruleresultfeatures
		$results = Table::load('worder_ruleresultfeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		foreach($results as $rowID => $result) {
			$result->ruleID = $newRuleID;
			$success = Table::addRow('worder_ruleresultfeatures',$result, $comments);
		}
		
		// Kopioidaan ruleunsets (TODO: Onkohan tämä taulu enää oikeasti käytössä?)
		$unsets = Table::load('worder_ruleunsets', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		foreach($unsets as $rowID => $unset) {
			$unset->ruleID = $newRuleID;
			$success = Table::addRow('worder_ruleunsets',$unset, $comments);
		}
		
		redirecttotal('worder/rules/showrule&id='. $newRuleID);
	}
	
	
	
	
	public function removetermAction() {
		
		// TODO: Tsekkaa löytyykö kyseinen termi joistakin agreementseista, contraintisista tai componentrequirementsista
		
		$ruleID = $_GET['ruleID'];
		$rowID = $_GET['id'];
		$success = Table::deleteRow('worder_ruleterms',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);
		redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
	}
	

	public function removerulesentenceAction() {
		$ruleID = $_GET['ruleID'];
		$sentenceID = $_GET['id'];
		$success = Table::deleteRow('worder_rulesentencelinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $sentenceID . " AND RuleID=" . $ruleID);
		redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
	}
	

	public function removeagreementAction() {
	
		$ruleID = $_GET['ruleID'];
		$rowID = $_GET['id'];
		$success = Table::deleteRow('worder_rulefeatureagreements',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);
		redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
	}
	
	
	

	public function removeconstraintAction() {
	
		$ruleID = $_GET['ruleID'];
		$rowID = $_GET['id'];
		$success = Table::deleteRow('worder_rulefeatureconstraints',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);
		redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
	}
	
	

	public function removecomponentrequirementAction() {
	
		$ruleID = $_GET['ruleID'];
		$rowID = $_GET['id'];
		$success = Table::deleteRow('worder_rulecomponentrequirements',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);
		redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
	}
	
	


	public function removeresultfeatureAction() {
	
		$ruleID = $_GET['ruleID'];
		$rowID = $_GET['id'];
		$success = Table::deleteRow('worder_ruleresultfeatures',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);
		redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
	}
	
	

	public function removeunsetAction() {
	
		$ruleID = $_GET['ruleID'];
		$rowID = $_GET['id'];
		$success = Table::deleteRow('worder_ruleunsets',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);
		redirecttotal('worder/rules/showrule&id=' . $ruleID ,null);
	}
	
	
	
	

	public function removeruleAction() {
		$ruleID = $_GET['id'];
		
		$linksfound = false;
		
		$terms = Table::load('worder_ruleterms', "WHERE RuleID=" . $ruleID);
		if (count($terms) > 0) {
			echo "<br>Ruleterms found - " . count($terms);
			//$linksfound = true;
		}
		
		$rows = Table::load('worder_rulefeatureconstraints', "WHERE RuleID=" . $ruleID);
		if (count($rows) > 0) {
			echo "<br>worder_rulefeatureconstraints found - " . count($terms);
			//$linksfound = true;
		}
		
		$rows = Table::load('worder_rulefeatureagreements', "WHERE RuleID=" . $ruleID);
		if (count($rows) > 0) {
			echo "<br>worder_rulefeatureagreements found - " . count($terms);
			//$linksfound = true;
		}
		
		$rows = Table::load('worder_rulecomponentrequirements', "WHERE RuleID=" . $ruleID);
		if (count($rows) > 0) {
			echo "<br>worder_rulecomponentrequirements found - " . count($terms);
			//$linksfound = true;
		}
		
		$rows = Table::load('worder_ruleresultfeatures', "WHERE RuleID=" . $ruleID);
		if (count($rows) > 0) {
			echo "<br>worder_ruleresultfeatures found - " . count($terms);
			//$linksfound = true;
		}
		
		$rows = Table::load('worder_ruleunsets', "WHERE RuleID=" . $ruleID);
		if (count($rows) > 0) {
			echo "<br>worder_ruleunsets found - " . count($terms);
			$linksfound = true;
		}
		
		
		// TODO: Nämä voisi poistaa suoraan
		$rulesetlist = array();
		$rulesetlinks = Table::load('worder_rulesetlinks', "WHERE RuleID=" . $ruleID);
		if (count($rulesetlinks) > 0) {
			echo "<br>worder_rulesetlinks found - " . count($terms);
			
			foreach($rulesetlinks as $index => $link) {
				echo "<br> - RuleSet - " . $link->setID;
				$rulesetlist[$link->setID] = $link->setID;
			}
			
			$linksfound = true;
		}
		
		// TODO: Nämä voisi poistaa suoraan
		$terms = Table::load('worder_lessonrules', "WHERE RuleID=" . $ruleID);
		if (count($terms) > 0) {
			echo "<br>worder_lessonrules found - " . count($terms);
			$linksfound = true;
		}
		
		// TODO: Nämä voisi poistaa suoraan
		$terms = Table::load('worder_rulesentencelinks', "WHERE RuleID=" . $ruleID);
		if (count($terms) > 0) {
			echo "<br>worder_rulesentencelinks found - " . count($terms);
			$linksfound = true;
		}
		
		if ($linksfound == false) {
			
			// $terms = Table::load('worder_ruleterms', "WHERE RuleID=" . $ruleID);
			$success = Table::deleteRow('worder_ruleterms',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
			
			// $rows = Table::load('worder_rulefeatureconstraints', "WHERE RuleID=" . $ruleID);
			$success = Table::deleteRow('worder_rulefeatureconstraints',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
			
			// $rows = Table::load('worder_ruleresultfeatures', "WHERE RuleID=" . $ruleID);
			$success = Table::deleteRow('worder_ruleresultfeatures',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
			
			// $rows = Table::load('worder_rulefeatureagreements', "WHERE RuleID=" . $ruleID);
			$success = Table::deleteRow('worder_rulefeatureagreements',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
			
			// $rows = Table::load('worder_rulecomponentrequirements', "WHERE RuleID=" . $ruleID);
			$success = Table::deleteRow('worder_rulecomponentrequirements',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
						
			$success = Table::deleteRow('worder_rules',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
			redirecttotal('worder/rules/showrules',null);
		} else {
			echo "<br>Remove not possible";
		}
		
		// TODO: poista kyseiseen ruleen liittyvät rivit muista tauluista..
		
		//$success = Table::deleteRowsWhere('worder_rulesetlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
	}
	
	

	public function showreferencesAction() {
		$ruleID = $_GET['id'];
	
		$linksfound = false;
	
		$terms = Table::load('worder_ruleterms', "WHERE RuleID=" . $ruleID);
		if (count($terms) > 0) {
			echo "<br>Ruleterms found - " . count($terms);
			$linksfound = true;
		}
	
		$terms = Table::load('worder_rulefeatureconstraints', "WHERE RuleID=" . $ruleID);
		if (count($terms) > 0) {
			echo "<br>worder_rulefeatureconstraints found - " . count($terms);
			$linksfound = true;
		}
	
		$terms = Table::load('worder_rulefeatureagreements', "WHERE RuleID=" . $ruleID);
		if (count($terms) > 0) {
			echo "<br>worder_rulefeatureagreements found - " . count($terms);
			$linksfound = true;
		}
	
		$terms = Table::load('worder_rulecomponentrequirements', "WHERE RuleID=" . $ruleID);
		if (count($terms) > 0) {
			echo "<br>worder_rulecomponentrequirements found - " . count($terms);
			$linksfound = true;
		}
	
		$terms = Table::load('worder_ruleresultfeatures', "WHERE RuleID=" . $ruleID);
		if (count($terms) > 0) {
			echo "<br>worder_ruleresultfeatures found - " . count($terms);
			$linksfound = true;
		}
	
		$terms = Table::load('worder_ruleunsets', "WHERE RuleID=" . $ruleID);
		if (count($terms) > 0) {
			echo "<br>worder_ruleunsets found - " . count($terms);
			$linksfound = true;
		}
	
	
		$rulesetlist = array();
		$rulesetlinks = Table::load('worder_rulesetlinks', "WHERE RuleID=" . $ruleID);
		if (count($rulesetlinks) > 0) {
			echo "<br>worder_rulesetlinks found - " . count($terms);
			foreach($rulesetlinks as $index => $link) {
				echo "<br> - RuleSet - " . $link->setID;
				$rulesetlist[$link->setID] = $link->setID;
			}
			$linksfound = true;
		}
	


		$sentencesets = Table::loadWhereInArray("worder_sentencesets", "RulesetID", $rulesetlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		foreach($sentencesets as $setID => $set) {
			echo "<br>Sentence Set - " . $set->setID . " - " . $set->name . ", ruleset: " . $set->rulesetID;
		}
		
		
		// TODO: Nämä voisi poistaa suoraan
		$terms = Table::load('worder_lessonrules', "WHERE RuleID=" . $ruleID);
		if (count($terms) > 0) {
			echo "<br>worder_lessonrules found - " . count($terms);
			$linksfound = true;
		}
	
		// TODO: Nämä voisi poistaa suoraan
		$terms = Table::load('worder_rulesentencelinks', "WHERE RuleID=" . $ruleID);
		if (count($terms) > 0) {
			echo "<br>worder_rulesentencelinks found - " . count($terms);
			foreach($rulesetlinks as $index => $link) {
				echo "<br> - SentenceID - " . $link->sentenceID;
			}
			$linksfound = true;
		}
		
		
		if ($linksfound == false) {
			echo "<br>no links found";
		} else {
			echo "<br>Remove not possible";
		}
	}


	public function getruleJSONAction() {
	
		$comments = false;
	
		include_once('./modules/worder/_classes/featurestructure.class.php');
		include_once('./modules/worder/_classes/rule.class.php');
		
		$ruleID = $_GET['ruleID'];
	
		$dbrule = Table::loadRow('worder_rules',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		$wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);
		$arguments = Table::load("worder_arguments", "WHERE (GrammarID=" . $_SESSION['grammarID'] . " OR GrammarID=0)");
		$components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		FeatureStructure::$wordclasses = $wordclasses;
		FeatureStructure::$features = $features;
		FeatureStructure::$arguments = $arguments;
		FeatureStructure::$components = $components;
	
		$ruleterms = Table::load('worder_ruleterms', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		$featureagreements = Table::load('worder_rulefeatureagreements', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		$featureconstraints = Table::load('worder_rulefeatureconstraints', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		$componentrequirements = Table::load('worder_rulecomponentrequirements', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		$resultfeatures = Table::load('worder_ruleresultfeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		$rule = new Rule($dbrule->name, $dbrule->wordclassID, $dbrule->analyse, $dbrule->conceptID);
	
		if ($dbrule->conceptID > 0) {
			$concept = Table::loadRow("worder_concepts", $dbrule->conceptID);
			$argustrings = explode('|', $concept->arguments);
			//echo "<br>Argustr = " . $concept->arguments;
			foreach($argustrings as $index => $value) {
				$argvalue = explode(':', $value);
				$rule->addConceptArgument($argvalue[0], $argvalue[1], $argvalue[2]);
			}
			$rule->setConceptName($concept->name);
		}
		
		if ($ruleterms != null) {
			foreach($ruleterms as $index => $ruleterm) {
				$conceptname = '';
				if ($ruleterm->conceptID > 0) {
					$concept =  Table::loadRow("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $ruleterm->conceptID);
					$conceptname = $concept->name;
				}
				$rule->addTerm($ruleterm->position, $ruleterm->argumentID, $ruleterm->wordclassID, $ruleterm->argumentsallowed, $ruleterm->conceptID, $conceptname);
			}
		}
	
		if ($featureagreements != null) {
			foreach($featureagreements as $index => $featureagreement) {
				$rule->addFeatureAgreement($featureagreement->position1, $featureagreement->position2, $featureagreement->featureID);
			}
		}
	
		if ($featureconstraints != null) {
			foreach($featureconstraints as $index => $featureconstraint) {
				if ($comments ) echo "<br>addConstraint - " . $featureconstraint->position . " - " . $featureconstraint->featureID . " - " . $featureconstraint->featurevalueID . " - " . $featureconstraint->operator;
				$rule->addConstraint($featureconstraint->position, $featureconstraint->featureID, $featureconstraint->featurevalueID, $featureconstraint->operator);
			}
		}
	
		if ($componentrequirements != null) {
			foreach($componentrequirements as $index => $componentrequirement) {
				$rule->addComponent($componentrequirement->position, $componentrequirement->componentID, $componentrequirement->presence, $componentrequirement->operator);
			}
		}
	
		if ($resultfeatures != null) {
			foreach($resultfeatures as $index => $resultfeature) {
				$rule->addResultFeature($resultfeature->featureID, $resultfeature->valueID, $resultfeature->position);
			}
		}
	
		$unsets = Table::load('worder_ruleunsets', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
		if ($unsets != null) {
			foreach($unsets as $index => $unset) {
				if ($unset->featureID > 0) {
					$rule->addUnsetFeature($unset->position, $unset->featureID);
				} else {
					$rule->addUnsetArgument($unset->position, $unset->argumentID);
				}
			}
		}
		$str = $rule->toJSON($wordclasses,$arguments,$features, $components);
		echo "" . $str;
	}
	
	
	
	// TODO: Tämä pitäisi korvata integer versiolla
	public function getresultrulesJSONAction() {
	
		include_once('./modules/worder/_classes/featurestructure.class.php');
		include_once('./modules/worder/_classes/rule.class.php');
	
		$languageID = $_GET['languageID'];
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$rulestructs = Table::load("worder_rules","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND Analyse=2 AND LanguageID=" . $languageID . " AND Status>0 ORDER BY Sortorder", false);
	
		$wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);
		$arguments = Table::load("worder_arguments", "WHERE (GrammarID=" . $_SESSION['grammarID'] . " OR GrammarID=0)");
		$components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		FeatureStructure::$wordclasses = $wordclasses;
		FeatureStructure::$features = $features;
		FeatureStructure::$components = $components;
		FeatureStructure::$arguments = $arguments;
	
	
		$ruleterms = Table::load('worder_ruleterms', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$featureagreements = Table::load('worder_rulefeatureagreements', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$featureconstraints = Table::load('worder_rulefeatureconstraints', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$componentrequirements = Table::load('worder_rulecomponentrequirements', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$resultfeatures = Table::load('worder_ruleresultfeatures', "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		$rules = array();
		foreach($rulestructs as $index => $rulestruct) {
	
			$rule = new Rule($rulestruct->name, $rulestruct->wordclassID, $rulestruct->analyse, $rulestruct->conceptID);
			if ($rulestruct->conceptID > 0) {
				$concept = Table::loadRow("worder_concepts", $rulestruct->conceptID);
				$argustrings = explode('|', $concept->arguments);
				foreach($argustrings as $index => $value) {
					$argvalue = explode(':', $value);
					$rule->addConceptArgument($argvalue[0], $argvalue[1], $argvalue[2]);
				}
				$rule->setConceptName($concept->name);
			}
			//echo "<br>rule - " . $rulestruct->name . ", " . $rulestruct->wordclassID . ", ruleID:" . $rulestruct->ruleID;
	
			if ($ruleterms != null) {
				foreach($ruleterms as $index => $ruleterm) {
					if ($ruleterm->ruleID == $rulestruct->ruleID) {
						$conceptname = "";
						if ($ruleterm->conceptID > 0) {
							$concept =  Table::loadRow("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $ruleterm->conceptID);
							$conceptname = $concept->name;
						}
						$rule->addTerm($ruleterm->position, $ruleterm->argumentID, $ruleterm->wordclassID, $ruleterm->argumentsallowed, $ruleterm->conceptID, $conceptname);
						//$rule->addTerm($ruleterm->position, $ruleterm->argumentID, $ruleterm->wordclassID, $ruleterm->argumentsallowed);
						//echo "<br>Addterm - position:" . $ruleterm->position . "," . $ruleterm->argumentID . "," . $ruleterm->wordclassID;
					}
				}
			}
	
			if ($featureagreements != null) {
				foreach($featureagreements as $index => $featureagreement) {
					if ($rulestruct->ruleID == $featureagreement->ruleID) {
						//echo "<br>addFeatureAgreement - position:" . $featureagreement->position1 . "," . $featureagreement->position2 . "," . $featureagreement->featureID;
						$rule->addFeatureAgreement($featureagreement->position1, $featureagreement->position2, $featureagreement->featureID);
					}
				}
			}
	
			if ($featureconstraints != null) {
				foreach($featureconstraints as $index => $featureconstraint) {
					if ($rulestruct->ruleID == $featureconstraint->ruleID) {
						//echo "<br>ruleconstraint - " . $ruleterm->ruleID . " - " . $featureconstraint->ruleID;
						//echo "<br>addConstraint - position:" . $featureconstraint->position . "," . $featureconstraint->featureID . "," . $featureconstraint->featurevalueID;
						$rule->addConstraint($featureconstraint->position, $featureconstraint->featureID, $featureconstraint->featurevalueID, $featureconstraint->operator);
					}
				}
			}
	
			if ($componentrequirements != null) {
				foreach($componentrequirements as $index => $componentrequirement) {
					if ($rulestruct->ruleID == $componentrequirement->ruleID) {
						$rule->addComponent($componentrequirement->position, $componentrequirement->componentID, $componentrequirement->presence, $componentrequirement->operator);
					}
				}
			}
	
			if ($resultfeatures != null) {
				foreach($resultfeatures as $index => $resultfeature) {
					if ($rulestruct->ruleID == $resultfeature->ruleID) {
						$rule->addResultFeature($resultfeature->featureID, $resultfeature->valueID, $resultfeature->position);
					}
				}
			}
			$rules[] = $rule;
		}
	
	
		echo "[";
		$first = true;
		foreach($rules as $index => $rule) {
			if ($first == true) {
				$first = false;
			} else {
				echo ",";
			}
			$str = $rule->toJSON($wordclasses, $arguments, $features, $components);
			echo $str;
		}
		echo "]";
	}
	
	
	public function removerulesetAction() {
	
		$setID =  $_GET['setID'];
		$set = Table::loadRow("worder_rulesets", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID);
	
		if ($set->sentencesetID > 0) {
				
			$columns = array();
			$columns['RulesetID'] = 0;
			$success = Table::updateRow("worder_sentencesets", $columns, $set->sentencesetID);
		}
	
	
		$links = Table::load("worder_rulesetlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID, false);
		if (count($links) == 0) {
			$success = Table::deleteRow('worder_rulesets',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID, false);
		} else {
			$success = Table::deleteRowsWhere('worder_rulesetlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID);
			$success = Table::deleteRow('worder_rulesets',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID, false);
		}
	
		// TODO: add success message
		// TODO: pitäisi lähettää myös poistetun rulesetin languageID, koska filtterin pitää näyttää poistetun kielen rulesetit
		redirecttotal('worder/rules/showrulesets', null);
	}
	
	
	
	public function getrulesfullJSONAction() {
	
		$grammarID = $_SESSION['grammarID'];
		$languageID = $_GET['languageID'];
		$direction = $_GET['direction'];
		//$setID = $_GET['setID'];
		if (isset($_GET['sourcesetID'])) {
			$setID = $_GET['sourcesetID'];
		}
		if (isset($_GET['targetsetID'])) {
			$setID = $_GET['targetsetID'];
		}
		$rules = RulesController::getRulesFull($grammarID, $languageID, $direction, $setID);
		
		echo "[";
		$first = true;
		foreach($rules as $index => $rule) {
			if ($first == true) {
				$first = false;
			} else {
				echo ",";
			}
			$str = $rule->toIntegerJSON(FeatureStructure::$wordclasses, FeatureStructure::$arguments, FeatureStructure::$features, FeatureStructure::$components);
			//$str = $rule->toJSON(FeatureStructure::$wordclasses, FeatureStructure::$arguments, FeatureStructure::$features, FeatureStructure::$components);
			echo $str;
		}
		echo "]";
	}
	
	
	/**
	 * WordAPI käyttää tätä kutsua. Hakee generate- ja analyse-rulet. Palauttaa JSON-taulukon.
	 * 
	 */
	public function getrulesJSONAction() {
		
		$sourceSetID = 84;
		$targetSetID = 84;
		$sourceID = $_GET['sID'];
		$targetID = $_GET['tID'];
		
		$grammarID = $_SESSION['grammarID'];
		//$sourceID = $_GET['sourceID'];
		//$sourceSetID = $_GET['sourcesetID'];
		//$targetID = $_GET['targetID'];
		//$targetSetID = $_GET['targetsetID'];
		
		if ($sourceID == $targetID) {
			$rules1 = RulesController::getRulesFull($grammarID, $sourceID, 'analyse', $sourceSetID);
			$rules2 = RulesController::getRulesFull($grammarID, $sourceID, 'generate', $targetSetID);
			$rules3 = array();
			$rules4 = array();
		} else {
			$rules1 = RulesController::getRulesFull($grammarID, $sourceID, 'analyse', $sourceSetID);
			$rules2 = RulesController::getRulesFull($grammarID, $sourceID, 'generate', $targetSetID);
			$rules3 = RulesController::getRulesFull($grammarID, $targetID, 'analyse', $sourceSetID);
			$rules4 = RulesController::getRulesFull($grammarID, $targetID, 'generate', $targetSetID);
		}
		
		$rules = array();
		foreach($rules1 as $index => $rule) $rules[$rule->ruleID] = $rule;
		foreach($rules2 as $index => $rule) $rules[$rule->ruleID] = $rule;
		foreach($rules3 as $index => $rule) $rules[$rule->ruleID] = $rule;
		foreach($rules4 as $index => $rule) $rules[$rule->ruleID] = $rule;
		
		echo "{ \"rules\":";
		echo "[";
		$first = true;
		foreach($rules as $index => $rule) {
			if ($first == true) {
				$first = false;
			} else {
				echo ",";
			}
			$str = $rule->toIntegerJSON(FeatureStructure::$wordclasses, FeatureStructure::$arguments, FeatureStructure::$features, FeatureStructure::$components);
			//$str = $rule->toJSON(FeatureStructure::$wordclasses, FeatureStructure::$arguments, FeatureStructure::$features, FeatureStructure::$components);
			echo $str;
			//break;
		}
		echo "]";
		echo "}";
	}
	

	public static function getRulesFull($grammarID, $languageID, $direction, $setID) {
	
		include_once('./modules/worder/_classes/featurestructure.class.php');
		include_once('./modules/worder/_classes/rule.class.php');
	
		$comments = false;
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID);
	
		if ($direction == 'analyse') { // analyse
			$rulestructstemp= Table::load("worder_rules","WHERE GrammarID=" . $grammarID . " AND Analyse=1 AND LanguageID=" . $languageID . " AND Status>0 ORDER BY Sortorder DESC", $comments);
		}
		if ($direction == 'generate') { // generate
			$rulestructstemp = Table::load("worder_rules","WHERE GrammarID=" . $grammarID . " AND Generate=1 AND LanguageID=" . $languageID . " AND Status>0 ORDER BY Sortorder DESC", $comments);
		}
		if ($comments) echo "<br>rulecount - " . count($rulestructs);
		if ($comments) echo "<br><br>";
	
		$rulestructs = array();
		if ($setID == 0) {
			$rulestructs = $rulestructstemp;
		} else {
			$rulelinks = Table::load("worder_rulesetlinks","WHERE GrammarID=" . $grammarID . " AND SetID=" . $setID, $comments);
			foreach($rulelinks as $index => $link) {
				if (isset($rulestructstemp[$link->ruleID])) {
					$rulestructs[$link->ruleID] = $rulestructstemp[$link->ruleID];
				}
			}			
		}
		
		$wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $grammarID);
		$features = Table::load("worder_features","WHERE GrammarID=" . $grammarID);
		$arguments = Table::load("worder_arguments", "WHERE (GrammarID=" . $grammarID . " OR GrammarID=0)");
		$components = Table::load("worder_components", "WHERE GrammarID=" . $grammarID);
	
		FeatureStructure::$wordclasses = $wordclasses;
		FeatureStructure::$features = $features;
		FeatureStructure::$components = $components;
		FeatureStructure::$arguments = $arguments;
	
	
		$ruleterms = Table::load('worder_ruleterms', "WHERE GrammarID=" . $grammarID);
		$featureagreements = Table::load('worder_rulefeatureagreements', "WHERE GrammarID=" . $grammarID);
		$featureconstraints = Table::load('worder_rulefeatureconstraints', "WHERE GrammarID=" . $grammarID);
		$componentrequirements = Table::load('worder_rulecomponentrequirements', "WHERE GrammarID=" . $grammarID);
		$resultfeatures = Table::load('worder_ruleresultfeatures', "WHERE GrammarID=" . $grammarID);
	
		$rules = array();
		foreach($rulestructs as $index => $rulestruct) {
	
			$rule = new Rule($rulestruct->name, $rulestruct->wordclassID, $rulestruct->analyse, $rulestruct->conceptID);
			if ($rulestruct->conceptID > 0) {
				$concept = Table::loadRow("worder_concepts", $rulestruct->conceptID);
				$argustrings = explode('|', $concept->arguments);
				//echo "<br>Arguments - " . $concept->arguments;
				//echo "<br><br>";
				foreach($argustrings as $index => $value) {
					$argvalue = explode(':', $value);
					$rule->addConceptArgument($argvalue[0], $argvalue[1], $argvalue[2]);
				}
				$rule->setConceptName($concept->name);
			}
			
			$rule->setRuleID($rulestruct->ruleID);
			$rule->analyse = $rulestruct->analyse;
			$rule->generate = $rulestruct->generate;
			$rule->languageID = $rulestruct->languageID;
				
			//echo "<br>rule - " . $rulestruct->name . ", " . $rulestruct->wordclassID . ", ruleID:" . $rulestruct->ruleID;
	
			if ($ruleterms != null) {
				foreach($ruleterms as $index => $ruleterm) {
					if ($ruleterm->ruleID == $rulestruct->ruleID) {
						$conceptname = "";
						if ($ruleterm->conceptID > 0) {
							$concept =  Table::loadRow("worder_concepts","WHERE GrammarID=" . $grammarID . " AND ConceptID=" . $ruleterm->conceptID);
							$conceptname = $concept->name;
						}
						$rule->addTerm($ruleterm->position, $ruleterm->argumentID, $ruleterm->wordclassID, $ruleterm->argumentsallowed, $ruleterm->conceptID, $conceptname);
						//$rule->addTerm($ruleterm->position, $ruleterm->argumentID, $ruleterm->wordclassID, $ruleterm->argumentsallowed);
						//echo "<br>Addterm - position:" . $ruleterm->position . "," . $ruleterm->argumentID . "," . $ruleterm->wordclassID;
					}
				}
			}
	
			if ($featureagreements != null) {
				foreach($featureagreements as $index => $featureagreement) {
					if ($rulestruct->ruleID == $featureagreement->ruleID) {
						//echo "<br>addFeatureAgreement - position:" . $featureagreement->position1 . "," . $featureagreement->position2 . "," . $featureagreement->featureID;
						$rule->addFeatureAgreement($featureagreement->position1, $featureagreement->position2, $featureagreement->featureID);
					}
				}
			}
	
			if ($featureconstraints != null) {
				foreach($featureconstraints as $index => $featureconstraint) {
					if ($rulestruct->ruleID == $featureconstraint->ruleID) {
						//echo "<br>ruleconstraint - " . $ruleterm->ruleID . " - " . $featureconstraint->ruleID;
						//echo "<br>addConstraint - position:" . $featureconstraint->position . "," . $featureconstraint->featureID . "," . $featureconstraint->featurevalueID;
						$rule->addConstraint($featureconstraint->position, $featureconstraint->featureID, $featureconstraint->featurevalueID, $featureconstraint->operator);
					}
				}
			}
	
			if ($componentrequirements != null) {
				foreach($componentrequirements as $index => $componentrequirement) {
					if ($rulestruct->ruleID == $componentrequirement->ruleID) {
						$rule->addComponent($componentrequirement->position, $componentrequirement->componentID, $componentrequirement->presence, $componentrequirement->operator);
					}
				}
			}
	
			if ($resultfeatures != null) {
				foreach($resultfeatures as $index => $resultfeature) {
					if ($rulestruct->ruleID == $resultfeature->ruleID) {
						$rule->addResultFeature($resultfeature->featureID, $resultfeature->valueID, $resultfeature->position);
					}
				}
			}
			$rules[] = $rule;
		}
		return $rules;
	}
	
	
}
