<?php


class ObjectivesController extends AbstractController {
	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','worder.css','yritys.css','prism.css','chosen.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','chosen.jquery.js','prism.js');
	}
		
	
	public function indexAction() {
		//$this->showobjectivesAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	

	public function showobjectivesAction() {
	
		$languageID = getModuleSessionVar('languageID',0);
		$this->registry->languageID = $languageID;
		//echo "<br>LanguageID - " . $languageID;
	
		updateActionPath("Objectives");
	
	
		$this->registry->languages = Table::load('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		if ($languageID == 0) {
			foreach($this->registry->languages as $index => $language) {
				$languageID = $language->languageID;
				$this->registry->languageID = $languageID;
				break;
			}
		}
		
		$objectives = Table::load('worder_objectives','WHERE GrammarID=' . $_SESSION['grammarID'] . ' AND LanguageID=' . $languageID .  " ORDER BY Sortorder", false);
		//$this->registry->objectives = Table::loadHierarchy('worder_objectives','parentID', "WHERE GrammarID=" . $_SESSION['grammarID'] . ' AND LanguageID=' . $languageID .  " ORDER BY Sortorder", false);
		$parentlessobjectives = array();
		foreach($objectives as $index => $objective) {
			$parentlessobjectives[$objective->objectiveID] = 1;
		}
			
		$objectivelinks = Table::load('worder_objectivelinks','WHERE GrammarID=' . $_SESSION['grammarID'] . " ORDER BY Sortorder");
		
		$rootitems = array();
		foreach($objectivelinks as $index => $link) {
			//echo "<br>objectiveID - " . $link->objectiveID . ", parentID - " . $link->parentID;
			if ($link->parentID == 0) {
				$objective = $objectives[$link->objectiveID];
				unset($parentlessobjectives[$link->objectiveID]);
				$rootitems[] = $objective;
			} else {
				$objective = $objectives[$link->objectiveID];
				unset($parentlessobjectives[$link->objectiveID]);
				$parent = $objectives[$link->parentID];
				$parent->addChild($objective);
			}
		}
		foreach($parentlessobjectives as $objectiveID => $value) {
			$objective = $objectives[$objectiveID];
			$rootitems[] = $objective;
		}
		$this->registry->hierarchy = $rootitems;
		
		$this->registry->template->show('worder/objectives','objectives');
	}
	
	
	
	public function showobjectivelistAction() {
	
		$languageID = getModuleSessionVar('languageID',0);
		$this->registry->languageID = $languageID;
	
		updateActionPath("Objectivelist");
	
	
		$this->registry->languages = Table::load('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		if ($languageID == 0) {
			foreach($this->registry->languages as $index => $language) {
				$languageID = $language->languageID;
				$this->registry->languageID = $languageID;
				break;
			}
		}
	
		$this->registry->objectives = Table::load('worder_objectives','WHERE GrammarID=' . $_SESSION['grammarID'] . ' AND LanguageID=' . $languageID .  " ORDER BY Sortorder", false);
		$this->registry->template->show('worder/objectives','objectivelist');
	}
	
	
	

	public function showobjectiveAction() {
	
		$objectiveID = $_GET['id'];
		//echo "<br>objectiveID - " . $objectiveID;
		
		$this->registry->objectives = Table::load("worder_objectives", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->objective = $this->registry->objectives[$objectiveID];
		
		//$this->registry->objective = Table::loadRow('worder_objectives', "WHERE ObjectiveID=" . $objectiveID . " AND GrammarID=" . $_SESSION['grammarID']);
		if (isset($this->registry->objectives[$objectiveID])) {
			$this->registry->objective = $this->registry->objectives[$objectiveID];
			updateActionPath($this->registry->objective->name);
		} else {
			$errors = array();
			$errors[] = "Haluttua objectivea ei löytynyt";
			$this->registry->errors = $errors;
			$this->registry->template->show('system/error','errorpage');
			return;
		}
		
				
		$this->registry->sourcelessonID = 0;
		if (isset($_GET['sourcelessonID'])) $this->registry->sourcelessonID = $_GET['sourcelessonID'];
		
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->language = $this->registry->languages[$this->registry->objective->languageID];
		$this->registry->states = Table::load("worder_states", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->stages = $this->getLessonStages();
		
		$this->registry->components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->inflectionsets = Table::load("worder_inflectionsets", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->features = Table::load("worder_features", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->parents = Table::load("worder_objectivelinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID);
		$this->registry->objectivecompletions = Table::load("worder_objectivecompletions", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID);
		
		$this->registry->sentencelinks = Table::load("worder_lessonsentencelinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $this->registry->objective->objectiveID);
		$sentencelinks = array();
		if ($this->registry->sentencelinks == null) {
			$this->registry->sentences = array();
		} else {
			foreach($this->registry->sentencelinks as $index => $link) {
				//echo "<br>Sentencelist - " . $link->sentenceID;
				$sentencelinks[$link->sentenceID] = $link->sentenceID;
			}
			$this->registry->sentences = Table::loadWhereInArray("worder_sentences", "sentenceID", $sentencelinks, "WHERE GrammarID=" . $_SESSION['grammarID']);
		}
		foreach($this->registry->sentences as $index => $sentence) {
			foreach($this->registry->sentencelinks as $sindex => $link) {
				if ($link->sentenceID == $sentence->sentenceID) {
					$sentence->lessonID = $link->lessonID;
					$sentence->linkID = $link->rowID;
				}
			}
		}

		$lessonlinks = Table::load("worder_lessonobjectivelinks", "WHERE ObjectiveID=" . $this->registry->objective->objectiveID . " AND GrammarID=" . $_SESSION['grammarID']);
		$lessonlist = array();
		foreach($lessonlinks as $index => $link) {
			$lessonlist[$link->lessonID] = $link->lessonID;
		}
		$lessons = Table::loadWhereInArray("worder_lessons", "lessonID", $lessonlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		foreach($lessonlinks as $index => $link) {
			$lesson = $lessons[$link->lessonID];
			$lesson->stage = $link->stage;
			$lesson->rowID = $link->rowID;
		}
		
		
		$lessondata = Table::loadWhereInArray("worder_lessondata", "lessonID", $lessonlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		$activelanguages = array();
		$selectedrulesets = array();
		foreach($lessondata as $index => $data) {
			$activelanguages[$data->languageID] = $data->languageID;
			$selectedrulesets[$data->rulesetID] = $data->rulesetID;
		}
		$this->registry->activelanguages = $activelanguages;
		
		
		
		$sentencechecks = array();
		$sentencechecks = Table::load("worder_objectivesentencechecks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID);
		foreach($sentencechecks as $index => $checkline) {
			$sentence = $this->registry->sentences[$checkline->sentenceID];
			$var = 'color'.$checkline->languageID;
			$sentence->$var = '#90ee90';
		}
		
		$wordclassfeatures = Table::load("worder_wordclassfeatures", "WHERE WordclassID=" . $this->registry->objective->wordclassID . " AND LanguageID=" .  $this->registry->objective->languageID . " AND GrammarID=" . $_SESSION['grammarID']);
		$selectedfeatures = array();
		foreach($wordclassfeatures as $index => $wordclassfeature) {
			//echo "<br>Wordclassfeature - " . $wordclassfeature->featureID;
			$feature = $this->registry->features[$wordclassfeature->featureID];
			$feature->inflectional = $wordclassfeature->inflectional;
			$selectedfeatures[$feature->featureID] = $feature;
		}
		$this->registry->wordclassfeatures = $selectedfeatures;

		$this->registry->objectivearguments = Table::load("worder_objectivearguments", "WHERE ObjectiveID=" . $this->registry->objective->objectiveID . " AND GrammarID=" . $_SESSION['grammarID']);
		$featurerequirementslist = explode("|", $this->registry->objective->featurerequirements);
		$features = array();
		
		$this->registry->featurerequirements = Table::load("worder_objectivefeaturerequirements", "WHERE ObjectiveID=" . $this->registry->objective->objectiveID . " AND GrammarID=" . $_SESSION['grammarID']);
		$this->registry->generatefeatures = Table::load("worder_objectivegeneratefeatures", "WHERE ObjectiveID=" . $this->registry->objective->objectiveID . " AND GrammarID=" . $_SESSION['grammarID']);
				
		$featurelist = explode("|", $this->registry->objective->features);
		$features = array();
		foreach($featurelist as $index => $featurestr) {
			//echo "<br>Featurestr - " . $featurestr;
			if ($featurestr != "") {
				$parts = explode(":",$featurestr);
				$featureID = $parts[0];
				$valueID = $parts[1];
				$inflectional = "Inflectional";
				if ($parts[2] == 0) $inflectional = "wordclass";					
				$row = new Row();
				$row->featureID = $featureID;
				$row->valueID = $valueID;
				$row->rowID = $featureID;
				$row->inflectional = $inflectional;
				$row->requirementID = $parts[3];
				
				$feature = $this->registry->features[$featureID];
				$row->languageID = $feature->languageID;
				
				$features[] = $row;
			}
		}
		$this->registry->objectivefeatures = $features;
				
		$componentlist = explode("|", $this->registry->objective->components);
		$reqcomponents = array();
		$counter = 1;
		foreach($componentlist as $index => $componentstr) {
			if ($componentstr != "") {
				$componentlist2 = explode(",", $componentstr);
				$row = new Row();
				$row->rowID = $counter;
				foreach($componentlist2 as $index2 => $componentID) {
					$component = $this->registry->components[$componentID];
					if ($row->name != "") {
						$row->name = $row->name . " AND " . $component->name;
					} else {
						$row->name = "" . $component->name;
					}
					$row->componentID = $componentID;
				}
				$reqcomponents[$counter] = $row;
				$counter++;
			}
		}
		$this->registry->componentrequirements = $reqcomponents;
		
		
		$inflectionsetlist = explode("|", $this->registry->objective->inflectionsets);
		$inflectionsets = array();
		foreach($inflectionsetlist as $index => $inflectionsetID) {
			if ($inflectionsetID != "") {
				$inflectionsets[$inflectionsetID] = $this->registry->inflectionsets[$inflectionsetID];
			}
		}
		$this->registry->lessoninflectionsets = $inflectionsets;
		
		
		$prerequisitelinks = Table::load("worder_objectiveprerequisites", "WHERE ObjectiveID=" . $this->registry->objective->objectiveID . " AND GrammarID=" . $_SESSION['grammarID']);
		$prerequisites = array();
		foreach($prerequisitelinks as $index => $link) {
			//$preobjective = Table::loadRow('worder_objectives', "WHERE ObjectiveID=" . $link->prerequisiteID . " AND GrammarID=" . $_SESSION['grammarID']);
			//$prerequisites[$link->prerequisiteID] = $preobjective;
			$objective = $this->registry->objectives[$link->prerequisiteID];
			$objective->argumentID = $link->argumentID;
			$prerequisites[$link->prerequisiteID] = $objective;
		}
		$this->registry->prerequisites = $prerequisites;
		
		
		

		
		$objectivelessonchecks = Table::load("worder_objectivelessonchecks", "WHERE ObjectiveID=" . $this->registry->objective->objectiveID . " AND GrammarID=" . $_SESSION['grammarID']);
		foreach($objectivelessonchecks as $index => $checkline) {
			//echo "<br>lessonID - " . $checkline->lessonID;
			$lesson = $lessons[$checkline->lessonID];
			$var = 'color'.$checkline->languageID;
			//echo "<br>color - " . $var . " --- " . $lesson->lessonID;
			$lesson->$var = '#90ee90';
		}
		
		
		$this->registry->lessons = $lessons;
		$this->registry->prerequisites = $prerequisites;
		
		
		
		
		// Haetaan kaikki muut objektiivit, joissa ko. objective on argumenttina. Tämä on raskas haku
		// joka voidaan myöhemmin tehdä ehkä omaksi taulukseen.
		//$objectives = Table::load("worder_objectives", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$selectedobjectives = array();
		foreach($this->registry->objectives as $tempobjectiveID => $objective) {
			$arguments = explode("|", $objective->arguments);
			foreach($arguments as $index => $itemstr) {
				if ($itemstr != "") {
					$items = explode(":", $itemstr);
					if ($items[2] == $objectiveID) {
						$objective->linktype = 1;
						$selectedobjectives[$objective->objectiveID] = $objective;
					}
				}
			}
		}
		
		$objectivelinks = Table::load('worder_objectivearguments','WHERE GrammarID=' . $_SESSION['grammarID'] . " AND ValueobjectiveID=" . $objectiveID);
		$selectedobjectives = array();
		foreach($objectivelinks as $index => $objectivelink) {
			$newobjective = $this->registry->objectives[$objectivelink->objectiveID];
			$selectedobjectives[$newobjective->objectiveID] = $newobjective;
			$newobjective->argumentID = $objectivelink->argumentID;
		}
		$this->registry->linkedobjectives = $selectedobjectives;
		
		
		$linktypes = array();
		$row = new Row();
		$row->linktype = 1;
		$row->name = "Argument";
		$linktypes[1] = $row;
		$row = new Row();
		$row->linktype = 2;
		$row->name = "Child";
		$linktypes[2] = $row;
		$row->linktype = 3;
		$row = new Row();
		$row->name = "D-Child";
		$linktypes[3] = $row;
		$this->registry->linktypes = $linktypes;
		
		$this->registry->template->show('worder/objectives','objective');
	}
	
	
	
	
	public function insertsentencetoobjectiveAction() {
	
		$sentence = $_GET['sentence'];
		$objectiveID = $_GET['objectiveID'];
		$lessonID =  $_GET['lessonID'];
		$languageID =  $_GET['languageID'];
		
		$sentences = Table::load("worder_sentences", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND Sentence='" . $sentence . "'");

		if (count($sentences) > 0) {

			if (count($sentences) == 1) {
				foreach($sentences as $index => $sentence) {
					
					$sentencelinks = Table::load("worder_lessonsentencelinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID . " AND SentenceID=" . $sentence->sentenceID . " AND ObjectiveID=" . $objectiveID);

					if (count($sentencelinks) == 0) {
						$values = array();
						$values['LessonID'] = $lessonID;
						$values['LanguageID'] = $languageID;
						$values['SentenceID'] = $sentence->sentenceID;
						$values['ObjectiveID'] = $objectiveID;
						$values['GrammarID'] = $_SESSION['grammarID'];
						$rowID = Table::addRow("worder_lessonsentencelinks", $values, false);
					} else {
						echo "<br>Already linked";
						exit;
					}
				}
			} else {
				echo "<br>Useampi sentence found - " . $sentence;
				exit;
			}
			
			
		} else {
			$values = array();
			$values['Sentence'] = $sentence;
			$values['GrammarID'] = $_SESSION['grammarID'];
			$values['SourceID'] = 0;
			$values['Correctness'] = 1;
			$values['LanguageID'] = $languageID;
			$sentenceID = Table::addRow("worder_sentences", $values, false);
			
			$values = array();
			$values['LessonID'] = $lessonID;
			$values['LanguageID'] = $languageID;
			$values['SentenceID'] = $sentenceID;
			$values['ObjectiveID'] = $objectiveID;
			$values['GrammarID'] = $_SESSION['grammarID'];
			$rowID = Table::addRow("worder_lessonsentencelinks", $values, false);
		}
		
		
		redirecttotal('worder/objectives/showobjective&id=' . $objectiveID,null);
	}
	
	
	
	

	public function getLessonStages() {
	
		$rows = array();
	
		$row = new Row();
		$row->stage = 1;
		$row->name = "Stage-1";
		$rows[1] = $row;
	
		$row = new Row();
		$row->stage = 2;
		$row->name = "Stage-2";
		$rows[2] = $row;
	
		$row = new Row();
		$row->stage = 3;
		$row->name = "Stage-3";
		$rows[3] = $row;
	
		$row = new Row();
		$row->stage = 4;
		$row->name = "Stage-4";
		$rows[4] = $row;
	
		return $rows;
	}
	
	

	public function updateobjectiveAction() {
	
		$comments = false;
		$objectiveID = $_GET['id'];
	
		$str = str_replace('_plus_', '+',$_GET['name']);
		if ($comments) echo "<br>Plussaa - " . $str;
	
		$values = array();
		$values['Name'] = $str;
		//$values['LanguageID'] = $_GET['languageID'];  // Fixed
		$values['WordclassID'] = $_GET['wordclassID'];
		$values['ParentID'] = $_GET['parentID'];
		$values['Mincount'] = $_GET['mincount'];
		$values['StateID'] = $_GET['stateID'];
		$values['Stage'] = $_GET['stage'];
		$values['Description'] = $_GET['description'];
		$success = Table::updateRow("worder_objectives", $values, $objectiveID, $comments);
	
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID,null);
	}
	
	
	
	

	public function createobjectiveAction() {

		if (isset($_GET['lessonID'])) {
			$lessonID = $_GET['lessonID'];
			$lesson = Table::loadRow("worder_lessons", $lessonID);
		} else {
			$lesson = null;
		}
		
		// haetaan ensimmäinen state.
		$states = Table::load('worder_states', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$stateID = 0;
		foreach($states as $index => $state) {
			$stateID = $state->stateID;
			break;
		}
		
		if ($comments) echo "<br>Plussaa - " . $str;
		
		
		$values = array();
		$str = str_replace('_plus_', '+',$_GET['name']);
		$values['Name'] = $str;

		$languageID = 0;
		if ($lesson == null) {
			if (isset($_GET['languageID'])) {
				$languageID = $_GET['languageID'];
			}
		} else {
			$languageID = $lesson->languageID;
		}
		$values['LanguageID'] = $languageID;
		$values['WordclassID'] = $_GET['wordclassID'];
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['StateID'] = $stateID;
		$objectiveID = Table::addRow('worder_objectives', $values);
		
		if ($lesson != null) {
			$values = array();
			$values['LessonID'] = $lessonID;
			$values['LanguageID'] = $languageID;
			$values['ObjectiveID'] = $objectiveID;
			$values['GrammarID'] = $_SESSION['grammarID'];
			$rowID = Table::addRow("worder_lessonobjectivelinks", $values);
		}
		redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	

	public function insertobjectivefeaturerequirementAction() {
	
		$comments = false;
		$objectiveID = $_GET['objectiveID'];
		//$wordclassID = $_GET['wordclassID'];
		$languageID = $_GET['languageID'];
		$featureID = $_GET['featureID'];
		$valueID = $_GET['valueID'];
		
		$objective = Table::loadRow("worder_objectives", $objectiveID);
		
		$values = array();
		$values['LanguageID'] = $languageID;
		$values['WordclassID'] = $objective->wordclassID;
		$values['FeatureID'] = $featureID;
		$values['ValueID'] = $valueID;
		$values['ObjectiveID'] = $objectiveID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$success = Table::addRow("worder_objectivefeaturerequirements", $values);
		
		ObjectivesController::UpdateObjectiveFeatureRequirements($objectiveID, $comments);
		/*
		$requirements = Table::load("worder_objectivefeaturerequirements", "WHERE ObjectiveID=" . $objectiveID, $comments);
		$newfeatures = array();
		foreach($requirements as $index => $requirement) {
			$newfeature = $requirement->featureID . ":" . $requirement->valueID . ":" . $requirement->languageID;
			if ($comments) echo "<br>newfeature - " . $newfeature;
			$newfeatures[] = $newfeature;
		}		
		$requirementsstr = implode("|", $newfeatures);
		if ($comments) echo "<br>Requirements - " . $requirementsstr;
		$values = array();
		$values['Featurerequirements'] = $requirementsstr;
		$success = Table::updateRow("worder_objectives", $values, $objectiveID);
		*/		
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID,null);
	}
	
	
	private static function UpdateObjectiveFeatureRequirements($objectiveID, $comments = false) {

		$requirements = Table::load("worder_objectivefeaturerequirements", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID, $comments);
		$newfeatures = array();
		foreach($requirements as $index => $requirement) {
			$newfeature = $requirement->featureID . ":" . $requirement->valueID . ":" . $requirement->languageID;
			if ($comments) echo "<br>newfeature - " . $newfeature;
			$newfeatures[] = $newfeature;
		}
		$requirementsstr = implode("|", $newfeatures);
		if ($comments) echo "<br>Requirements - " . $requirementsstr;
		$values = array();
		$values['Featurerequirements'] = $requirementsstr;
		$success = Table::updateRow("worder_objectives", $values, $objectiveID);
		
	}
	
	

	private static function UpdateObjectiveArguments($objectiveID, $comments = false) {
	
		$arguments = Table::load("worder_objectivearguments", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID, $comments);
		$newarguments = array();
		foreach($arguments as $index => $argument) {
			$argumentstr = $argument->argumentID . ":" . $argument->wordclassID . ":" . $argument->valueobjectiveID;
			if ($comments) echo "<br>newfeature - " . $newfeature;
			$newarguments[] = $argumentstr;
		}
		$fullargumentstr = implode("|", $newarguments);
		if ($comments) echo "<br>Arguments - " . $fullargumentstr;
		$values = array();
		$values['Arguments'] = $fullargumentstr;
		$success = Table::updateRow("worder_objectives", $values, $objectiveID);
	
	}
	

	public function insertobjectivegeneratefeatureAction() {
	
		$comments = false;
		$objectiveID = $_GET['objectiveID'];
		$featureID = $_GET['featureID'];
		$valueID = $_GET['valueID'];
	
		$objective = Table::loadRow("worder_objectives", $objectiveID);
	
		$values = array();
		$values['WordclassID'] = $objective->wordclassID;
		$values['FeatureID'] = $featureID;
		$values['ValueID'] = $valueID;
		$values['ObjectiveID'] = $objectiveID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$success = Table::addRow("worder_objectivegeneratefeatures", $values);
			
		ObjectivesController::UpdateGenerateFeatures($objectiveID, $comments);
		
		/*
		$requirements = Table::load("worder_objectivegeneratefeatures", "WHERE ObjectiveID=" . $objectiveID, $comments);
		$newfeatures = array();
		foreach($requirements as $index => $requirement) {
			$newfeature = $requirement->featureID . ":" . $requirement->valueID;
			if ($comments) echo "<br>newfeature - " . $newfeature;
			$newfeatures[] = $newfeature;
		}
		$featuresstr = implode("|", $newfeatures);
		if ($comments) echo "<br>Generatefeatures - " . $featuresstr;
		$values = array();
		$values['Generatefeatures'] = $featuresstr;
		$success = Table::updateRow("worder_objectives", $values, $objectiveID);
		*/
		
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID,null);
	}
	
	
	private static function UpdateGenerateFeatures($objectiveID, $comments = false) {
		
		$requirements = Table::load("worder_objectivegeneratefeatures", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID, $comments);
		$newfeatures = array();
		foreach($requirements as $index => $requirement) {
			$newfeature = $requirement->featureID . ":" . $requirement->valueID;
			if ($comments) echo "<br>newfeature - " . $newfeature;
			$newfeatures[] = $newfeature;
		}
		$featuresstr = implode("|", $newfeatures);
		if ($comments) echo "<br>Generatefeatures - " . $featuresstr;
		$values = array();
		$values['Generatefeatures'] = $featuresstr;
		$success = Table::updateRow("worder_objectives", $values, $objectiveID);
	}
	
	

	public function insertobjectivefeatureAction() {
	
		$comments = false;
		$objectiveID = $_GET['objectiveID'];
		$featureID = $_GET['featureID'];
		$valueID = $_GET['valueID'];
		$languageID = $_GET['languageID'];
		$requirementID = $_GET['requirementID'];
		
		$objective = Table::loadRow("worder_objectives", $objectiveID);
	
		$wordclassfeature = Table::loadRow("worder_wordclassfeatures", "WHERE WordclassID=" . $objective->wordclassID . " AND LanguageID=" . $languageID . " AND GrammarID=" . $_SESSION['grammarID'] . " AND FeatureID=" . $featureID, $comments);
		
		if ($comments) echo "<br>Wordclassfeature - " . $wordclassfeature->rowID;
		if ($comments) echo "<br>Wordclassfeature - " . $wordclassfeature->inflectional;
		
		$features = explode("|", $objective->features);
		$newfeature = $featureID . ":" . $valueID . ":" . $wordclassfeature->inflectional . ":" . $requirementID;
		$newfeatures = array();
		foreach($features as $index => $value) {
			//if ($value != "") {
			//	$parts = explode(":", $value);
			//	if ($parts[0] != $featureID) {
			if ($value != "") {
					$newfeatures[] = $value;
			}
			
			//}
		}
		$newfeatures[] = $newfeature;
		$featurestr = implode("|", $newfeatures);
		
		$values = array();
		$values['Features'] = $featurestr;
		//echo "<br>Featurestr - " . $featurestr;
		$success = Table::updateRow("worder_objectives", $values, $objectiveID);
	
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID,null);
	
	}
	


	public function removeobjectivefeatureAction() {
	
		$removeID = $_GET['id'];
		$objectiveID = $_GET['objectiveID'];
		$objective = Table::loadRow("worder_objectives", $objectiveID);
	
		$features = explode("|", $objective->features);
		$newfeatures = array();
		foreach($features as $index => $value) {
			if ($value != "") {
				$parts = explode(":",$value);
				$featureID = $parts[0];
				$valueID = $parts[1];
	
				if ($featureID == $removeID) {
					// ei lisätä poistettavaa featureID:tä
					//echo "<br>Removefeture found - " . $featureID;
				} else {
					$newfeatures[] = $value;
				}
			}
		}
		$featurestr = implode("|", $newfeatures);
	
		$values = array();
		$values['Features'] = $featurestr;
		//echo "<br>Featurestr - " . $featurestr;
		$success = Table::updateRow("worder_objectives", $values, $objectiveID, true);
	
		redirecttotal('worder/objectives/showobjective&id=' . $objectiveID,null);
	}
	
	
	

	public function removeobjectivefeaturerequirementAction() {
	
		$comments = false;
		$rowID = $_GET['id'];
		$objectiveID = $_GET['objectiveID'];
		$objective = Table::loadRow("worder_objectives", $objectiveID);
	
		echo "<br>RowID - " . $rowID;
		$success = Table::deleteRow('worder_objectivefeaturerequirements',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);
		
		// Päivitetään objectiven featurerequirements kenttä lataamalla kaikki taulusta...
		ObjectivesController::UpdateObjectiveFeatureRequirements($objectiveID, $comments);
		
		/*
		$requirements = Table::load("worder_objectivefeaturerequirements", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID);
		$newfeatures = array();
		foreach($requirements as $index => $requirement) {
			$newfeature = $requirement->featureID . ":" . $requirement->valueID . ":" . $requirement->languageID;
			$newfeatures[] = $newfeature;
		}
		$requirementsstr = implode("|", $newfeatures);
		//echo "<br>New features - " . $requirementsstr;
		$values = array();
		$values['Featurerequirements'] = $requirementsstr;
		$success = Table::updateRow("worder_objectives", $values, $objectiveID);
		*/
		
		redirecttotal('worder/objectives/showobjective&id=' . $objectiveID,null);
	}
	
	
	

	public function removeobjectivegeneratefeatureAction() {
	
		$comments = false;
		$rowID = $_GET['id'];
		$objectiveID = $_GET['objectiveID'];
		$objective = Table::loadRow("worder_objectives", $objectiveID);
	
		if ($comments) echo "<br>RowID - " . $rowID;
		$success = Table::deleteRow('worder_objectivegeneratefeatures',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);
	
		// Päivitetään objectiven featurerequirements kenttä lataamalla kaikki taulusta...
		ObjectivesController::UpdateGenerateFeatures($objectiveID, $comments);
		
		/*
		$requirements = Table::load("worder_objectivegeneratefeatures", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID);
		$newfeatures = array();
		foreach($requirements as $index => $requirement) {
			$newfeature = $requirement->featureID . ":" . $requirement->valueID;
			$newfeatures[] = $newfeature;
		}
		$requirementsstr = implode("|", $newfeatures);
		//echo "<br>New features - " . $requirementsstr;
		$values = array();
		$values['Generatefeatures'] = $requirementsstr;
		$success = Table::updateRow("worder_objectives", $values, $objectiveID);
		*/
	
		redirecttotal('worder/objectives/showobjective&id=' . $objectiveID,null);
	}
	
	
	
	public function removesentencefromobjectiveAction() {
	
		$comments = false;
		$sentenceID = $_GET['id'];
		$objectiveID = $_GET['objectiveID'];
	
		$success = Table::deleteRow('worder_lessonsentencelinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $sentenceID . " AND ObjectiveID=" . $objectiveID);
		
		// Poistetaan tsekkaukset tsekkitaulusta
		$success = Table::deleteRow('worder_objectivesentencechecks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $sentenceID . " AND ObjectiveID=" . $objectiveID);
		

		// TODO: kyseinen sentenceID pitäisi poistaa myös worder_sentences-taulusta, mutta pitää ensin tsekata
		//  	 onko ko. lauseelle muita linkkejä muissa tauluissa. Viitetauluja ainakin seuraavat:
		//			- worder_lessonsentencelinks
		//			- muitakin varmaan on...
		
		
		//redirecttotal('worder/objectives/showobjective&id=' . $objectiveID,null);
	}
	
	

	public function insertorcomponentAction() {
	
		$comments = false;
		$objectiveID = $_GET['objectiveID'];
		$componentID = $_GET['componentID'];
		$objective = Table::loadRow("worder_objectives", $objectiveID,$comments);
		
		$components = explode("|", $objective->components);
		$newcomponents = array();
		$newcomponents[$componentID] = $componentID;
		foreach($components as $index => $componentID) {
			if ($componentID != "") {
				$newcomponents[$componentID] = $componentID;
			}
		}
		
		$values = array();
		$values['Components'] = implode("|", $newcomponents);
		$success = Table::updateRow("worder_objectives", $values, $objectiveID,$comments);
	
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	

	public function insertandcomponentAction() {
	
		$comments = false;
		$objectiveID = $_GET['objectiveID'];
		$parentID = $_GET['parentID'];
		$componentID = $_GET['componentID'];
		$objective = Table::loadRow("worder_objectives", $objectiveID,$comments);
		
		if ($comments) echo "<br>Origvalues - " . $objective->components;
		$componentlines = explode("|", $objective->components);
		$newcomponents = array();
		$counter = 0;
		foreach($componentlines as $index => $componentline) {
			if ($componentline != "") {
				if ($counter == $parentID) {
					$componentline = $componentline . "," . $componentID;
				}
				$newcomponents[] = $componentline;				
				$counter++;
			} 
		}
		if ($counter == 0) {
			$newcomponents[] = $componentID;
		}
		if ($comments) echo "<br>Newvalues - " . implode("|", $newcomponents);

		$values = array();
		$values['Components'] = implode("|", $newcomponents);
		$success = Table::updateRow("worder_objectives", $values, $objectiveID,$comments);
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	

	public function removecomponentrequirementAction() {
	
		$comments = false;
		$removeindex = $_GET['id'];
		$objectiveID = $_GET['objectiveID'];
		$objective = Table::loadRow("worder_objectives", $objectiveID, $comments);
	
		if ($comments) echo "<br>components - " . $objective->components;
		$componentlist = explode("|", $objective->components);
		$newcomponents = array();
		$counter = 1;
		$newcounter = 1;
		foreach($componentlist as $index =>  $compstr) {
			if ($compstr != "") {
				if ($counter != $removeindex) {
					$newcomponents[$newcounter] = $compstr;
					$newcounter++;
				}
			}
			$counter++;
		}
		if ($comments) echo "<br>components - " . $objective->components;
		
		$values = array();
		$values['Components'] = implode("|", $newcomponents);
		if ($comments) echo "<br>newcomponents - " . implode("|", $newcomponents);
		$success = Table::updateRow("worder_objectives", $values, $objectiveID, $comments);
	
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	
	

	public function insertinflectionsetAction() {
	
		$comments = false;
		$objectiveID = $_GET['objectiveID'];
		$inflectionsetID = $_GET['inflectionsetID'];
		$objective = Table::loadRow("worder_objectives", $objectiveID,$comments);
	
		$inflectionsets = explode("|", $objective->inflectionsets);
		$newinflectionsets = array();
		$newinflectionsets[$inflectionsetID] = $inflectionsetID;
		foreach($inflectionsets as $index => $inflectionsetID) {
			if ($inflectionsetID != "") {
				$newinflectionsets[$inflectionsetID] = $inflectionsetID;
			}
		}
	
		$values = array();
		$values['Inflectionsets'] = implode("|", $newinflectionsets);
		$success = Table::updateRow("worder_objectives", $values, $objectiveID, $comments);
	
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	
	
	public function removeobjectiveinflectionsetAction() {
	
		$comments = false;
		$inflectionsetID = $_GET['id'];
		$objectiveID = $_GET['objectiveID'];
		$objective = Table::loadRow("worder_objectives", $objectiveID, $comments);
	
		$inflectionsets = explode("|", $objective->inflectionsets);
		$newinflectionsets = array();
		foreach($inflectionsets as $index => $infsetID) {
			if ($inflectionsetID != "") {
				if ($inflectionsetID != $infsetID) {
					$newinflectionsets[$infsetID] = $infsetID;
				}
			}
		}
	
		$values = array();
		$values['Inflectionsets'] = implode("|", $newinflectionsets);
		$success = Table::updateRow("worder_objectives", $values, $objectiveID, $comments);
	
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	
	
	public function insertobjectiveprerequisiteAction() {
	
		$comments = false;
		$objectiveID = $_GET['objectiveID'];
		$prerequisiteID = $_GET['prerequisiteID'];
		
		$requisites = Table::load("worder_objectiveprerequisites", "WHERE ObjectiveID=" . $objectiveID . " AND PrerequisiteID=" . $prerequisiteID);

		if (count($requisites) > 0) {
			echo "<br>Ei voida lisätä, prerequisite on jo olemassa";
			exit;
		}
		
		$values = array();
		$values['ObjectiveID'] = $objectiveID;
		$values['PrerequisiteID'] = $prerequisiteID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['ArgumentlinkID'] = 0;
		$values['ArgumentID'] = 0;
		$rowID = Table::addRow("worder_objectiveprerequisites", $values);
		
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	
	

	public function insertparentobjectiveAction() {
	
		$comments = false;
		$objectiveID = $_GET['objectiveID'];
		$parentID = $_GET['parentID'];
	
		$values = array();
		$values['ObjectiveID'] = $objectiveID;
		$values['ParentID'] = $parentID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_objectivelinks", $values);
	
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	
	
	public function checkobjectivelessonAction() {
	
		$comments = false;
		$objectiveID = $_GET['objectiveID'];
		$lessonID = $_GET['lessonID'];
		$languageID = $_GET['languageID'];
		
		$values = array();
		$values['ObjectiveID'] = $objectiveID;
		$values['LessonID'] = $lessonID;
		$values['LanguageID'] = $languageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['Checked'] = 1;
		$values['Checkdate'] = date('Y-m-d H:i:s');;
		$rowID = Table::addRow("worder_objectivelessonchecks", $values);
			
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	

	public function checkobjectivesentenceAction() {
	
		$comments = false;
		$objectiveID = $_GET['objectiveID'];
		$targetlanguageID = $_GET['languageID'];
	
		$linkID = $_GET['linkID'];
		$link = Table::loadRow("worder_lessonsentencelinks", $linkID);
		if ($comments) echo "<br>Current sentence - " . $link->sentenceID;
		
		$values = array();
		$values['ObjectiveID'] = $objectiveID;
		$values['SentenceID'] = $link->sentenceID;
		$values['LanguageID'] = $targetlanguageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['Checked'] = 1;
		$values['Checkdate'] = date('Y-m-d H:i:s');;
		$rowID = Table::addRow("worder_objectivesentencechecks", $values);
		
		// TODO: lisätään myös sentencetranslationlinks tauluun ao. lauseen käännös samalla
		$fullstr = $_GET['str'];
		if ($comments) echo "<br>str - " . $fullstr;
		
		if ($link->languageID == $targetlanguageID) {
			if ($comments) echo "<br>Käännös samalle kielelle, käännöstä ei lisätä..";
		} else {
			if ($comments) echo "<br>Lisätään käännös..";
			
			$sentencelist = explode(':', $fullstr);
			foreach($sentencelist as $ax => $str) {
				$sentences = Table::load("worder_sentences", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $targetlanguageID . " AND Sentence='" . $str . "'");
				$translationlinks = Table::load("worder_sentencetranslationlinks", "WHERE SentenceID=" . $link->sentenceID);
				if (count($sentences) > 0) {
					if ($comments) echo "<br>Olemassaoleva käännös löytyi...";
					$found = false;
					foreach($sentences as $index => $sentence) {
						if ($comments) echo "<br> - aiempi lause: " . $sentence->sentenceID;
						foreach($translationlinks as $index2 => $translink) {
							if ($translink->targetsentenceID == $sentence->sentenceID) {
								if ($comments) echo "<br> - - translation already exists - linkID:" . $translink->rowID . ", sentence: " . $sentence->sentence . ", sentenceID:" . $sentence->sentenceID;
								$found = true;
							}
						}
					}
					if ($found == false) {
						if ($comments) echo "<br> - - linkkiä ei löytynyt, lisätään uusi translation";
						$values = array();
						$values['SentenceID'] = $link->sentenceID;
						$values['LanguageID'] = $link->languageID;
						$values['TargetlanguageID'] = $targetlanguageID;
						$values['TargetsentenceID'] = $sentence->sentenceID;
						$values['GrammarID'] = $_SESSION['grammarID'];
						$rowID = Table::addRow("worder_sentencetranslationlinks", $values);
						
						// TODO: pitäisi lisätä myös vastalinkki, mikäli sellaista ei vielä ole
						$negtranslationlinks = Table::load("worder_sentencetranslationlinks", "WHERE SentenceID=" . $sentence->sentenceID);
						$negfound = false;
						foreach($negtranslationlinks as $index3 => $negtranslink) {
							if ($negtranslink->targetsentenceID == $link->sentenceID) {
								if ($comments) echo "<br> - - translation already exists - linkID:" . $translink->rowID . ", sentence: " . $sentence->sentence . ", sentenceID:" . $sentence->sentenceID;
								$negfound = true;
							}
						}
						
						if ($negfound == false) {
							if ($comments) echo "<br> - - neg linkkiä ei löytynyt, lisätään uusi translation";
							$values = array();
							$values['SentenceID'] = $sentence->sentenceID;
							$values['LanguageID'] = $targetlanguageID;
							$values['TargetlanguageID'] = $link->languageID;
							$values['TargetsentenceID'] = $link->sentenceID;
							$values['GrammarID'] = $_SESSION['grammarID'];
							$rowID = Table::addRow("worder_sentencetranslationlinks", $values);
						}	
					} else {
						// Tarkistetaan löytyykö vastalinkit, ja lisätään jos ei...
						
						if ($comments) echo "<br> - - tsekataan onko vastalinkkejä";
						$negtranslationlinks = Table::load("worder_sentencetranslationlinks", "WHERE SentenceID=" . $sentence->sentenceID);
						$negfound = false;
						foreach($negtranslationlinks as $index3 => $negtranslink) {
							if ($negtranslink->targetsentenceID == $link->sentenceID) {
								if ($comments) echo "<br> - - vastalinkki translation already exists - linkID:" . $translink->rowID . ", sentence: " . $sentence->sentence . ", sentenceID:" . $sentence->sentenceID;
								$negfound = true;
							}
						}
						
						if ($negfound == false) {
							if ($comments) echo "<br> - - neg linkkiä ei löytynyt, lisätään uusi translation";
							$values = array();
							$values['SentenceID'] = $sentence->sentenceID;
							$values['LanguageID'] = $targetlanguageID;
							$values['TargetlanguageID'] = $link->languageID;
							$values['TargetsentenceID'] = $link->sentenceID;
							$values['GrammarID'] = $_SESSION['grammarID'];
							$rowID = Table::addRow("worder_sentencetranslationlinks", $values);
						}
						
					}
				} else {
					if ($comments) echo "<br>Käännöstä ei löytynyt, lisätään sentence";
				
					$values = array();
					$values['Sentence'] = $str;
					$values['GrammarID'] = $_SESSION['grammarID'];
					$values['SourceID'] = 0;
					$values['Correctness'] = 1;
					$values['LanguageID'] = $targetlanguageID;
					$addedSentenceID = Table::addRow("worder_sentences", $values, false);
				
					$values = array();
					$values['SentenceID'] = $link->sentenceID;
					$values['LanguageID'] = $link->languageID;
					$values['TargetsentenceID'] = $addedSentenceID;
					$values['TargetlanguageID'] = $targetlanguageID;
					$values['GrammarID'] = $_SESSION['grammarID'];
					$rowID = Table::addRow("worder_sentencetranslationlinks", $values);
					
					$values = array();
					$values['SentenceID'] = $addedSentenceID;
					$values['LanguageID'] = $targetlanguageID;
					$values['TargetsentenceID'] = $link->sentenceID;
					$values['TargetlanguageID'] = $link->languageID;
					$values['GrammarID'] = $_SESSION['grammarID'];
					$rowID = Table::addRow("worder_sentencetranslationlinks", $values);
				}
			}
		}
		
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	
	

	public function uncheckobjectivesentenceAction() {
	
		$comments = false;
		$objectiveID = $_GET['objectiveID'];
		$languageID = $_GET['languageID'];
		
		$linkID = $_GET['linkID'];
		$link = Table::loadRow("worder_lessonsentencelinks", $linkID);
		
		echo "<br>Link->sentenceID - " . $link->sentenceID;
		echo "<br>objectiveID - " . $objectiveID;
		echo "<br>languageID - " . $languageID;
		
		$success = Table::deleteRowsWhere('worder_objectivesentencechecks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $link->sentenceID . " AND ObjectiveID=" . $objectiveID . " AND LanguageID=" . $languageID);
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	
	
	
	

	public function uncheckobjectivelessonAction() {
	
		$comments = false;
		$objectiveID = $_GET['objectiveID'];
		$lessonID = $_GET['lessonID'];
		$languageID = $_GET['languageID'];
		$success = Table::deleteRowsWhere('worder_objectivelessonchecks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID . " AND LessonID=" . $lessonID . " AND LanguageID=" . $languageID);
		
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	
	
	public function insertcompletionAction() {
	
		// TODO: parentobjectiven pitäisi olla parents listassa ilmeisesti, pitäisi ehkä tarkistaa
		// TODO: itseään ei saisi lisätä parentobjectiveksi
		
		$comments = true;
		$objectiveID = $_GET['objectiveID'];
		$parentID = $_GET['parentobjectiveID'];
		$inflectionsetID = $_GET['inflectionsetID'];
		
		$values = array();
		$values['ObjectiveID'] = $objectiveID;
		$values['ParentobjectiveID'] = $parentID;
		$values['InflectionsetID'] = $inflectionsetID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_objectivecompletions", $values, $comments);
	
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	
	// TODO: ei saisi poistaa jos argumentID > 0, on linkitettu argumentteihin...
	public function removeobjectiveprerequisiteAction() {
	
		$comments = false;
		$prerequisiteID = $_GET['id'];
		$objectiveID = $_GET['objectiveID'];
		
		$prerequisites = Table::load("worder_objectiveprerequisites","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID . " AND PrerequisiteID=" . $prerequisiteID);
		$found = false;
		foreach($prerequisites as $index => $pre) {
			if (($pre->argumentID > 0) || ($pre->argumentlinkID > 0)) {
				$found = true;
			}
		}
		if ($found == true) {
			echo "<br>Ei voida poistaa, objektiivilla on argumenttina";
			exit;
		}
		
		$success = Table::deleteRowsWhere('worder_objectiveprerequisites',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID . " AND PrerequisiteID=" . $prerequisiteID);
		
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	
	
	
	public function removeparentobjectiveAction() {
	
		$comments = false;
		$parentID = $_GET['id'];
		$objectiveID = $_GET['objectiveID'];
		$success = Table::deleteRowsWhere('worder_objectivelinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID . " AND ParentID=" . $parentID, $comments);
	
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	

	/*
	public function updateobjectiveargumentAction() {
		
		$objectiveID = $_GET['objectiveID'];
		$argumentID = $_GET['argumentID'];
		$valueobjectiveID = $_GET['valueobjectiveID'];
		$rowID = $_GET['id'];
		
		$objectiveargument = Table::loadRow("worder_objectivearguments", $rowID);
		
		if (($objectiveargument->valueobjectiveID != $valueobjectiveID) || ($objectiveargument->argumentID != $argumentID)) {
			$success = Table::deleteRowsWhere('worder_objectiveprerequisites',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID . " AND PrerequisiteID=" . $objectiveargument->prerequisiteID . " AND ArgumentID=" . $objectiveargument->argumentID, $comments);
			
			$values = array();
			$values['ObjectiveID'] = $objectiveID;
			$values['PrerequisiteID'] = $valueobjectiveID;
			$values['ArgumentID'] = $argumentID;
			$values['GrammarID'] = $_SESSION['grammarID'];
			$rowID = Table::addRow("worder_objectiveprerequisites", $values, $comments);
		}
		
		
		$values = array();
		$values['ArgumentID'] = $argumentID;
		$values['ValueobjectiveID'] = $valueobjectiveID;
		$success = Table::updateRow("worder_objectivearguments", $values, $rowID, true);
		
		ObjectivesController::updateObjectiveArguments($objectiveID);
		
		redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	*/
	
	
	
	
	public function updatelessonlinkstageAction() {
	
		$objectiveID = $_GET['objectiveID'];
		$stage = $_GET['stage'];
		$rowID = $_GET['id'];
	
		$values = array();
		$values['Stage'] = $stage;
		$success = Table::updateRow("worder_lessonobjectivelinks", $values, $rowID, true);
	
		redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	
	
	
	public function removeobjectiveargumentAction() {
		
		$comments = true;
		$objectiveID = $_GET['objectiveID'];
		$rowID = $_GET['id'];
		$objectiveargument = Table::loadRow("worder_objectivearguments", $rowID);
		
		// poistetaan objectiveprerequisitessistä argumentti
		$success = Table::deleteRowsWhere('worder_objectiveprerequisites',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID . " AND PrerequisiteID=" . $objectiveargument->valueobjectiveID . " AND ArgumentID=" . $objectiveargument->argumentID, $comments);
		
		$success = Table::deleteRow('worder_objectivearguments',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);
		
		ObjectivesController::UpdateObjectiveArguments($objectiveID, $comments);
		redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	
	/*
	// Tätä käytettiin tiertablessa, ei enää käytössä
	public function removeobjectiveargumentfeatureAction() {
	
		$identifierpair = explode("-", $_GET['id']);
		$argumentID = $identifierpair[0];
		$featureID = $identifierpair[1];
		$objectiveID = $_GET['objectiveID'];
	
		$objective = Table::loadRow("worder_objectives", $objectiveID);
	
		$argumentlist = explode("|", $objective->arguments);
		$newarguments = array();
	
	
		foreach($argumentlist as $index => $argumentstr) {
			//echo "<br>Argumentstr - " . $argumentstr;
			if ($argumentstr != "") {
				$argumentparts = explode(":",$argumentstr);
				if ($argumentID == $argumentparts[0]) {
						
					$newfeatures = array();
					$featureparts = explode(",",$argumentparts[4]);
					foreach ($featureparts as $iindex2 => $featurepairstr) {
						if ($featurepairstr != "") {
							$featurepairs = explode("-", $featurepairstr);
							if ($featurepairs[0] == $featureID) {
								//echo "<br>Feature found - " . $featurepairs[0];
							} else {
								$newfeatures[] = $featurepairstr;
							}
						}
					}
					$finalfeaturestr = implode(",", $newfeatures);
					$argumentparts[4] = $finalfeaturestr;
					$newarguments[] = implode(":", $argumentparts);
				} else {
					$newarguments[] = $argumentstr;
				}
			}
		}
	
	
		$argumentstr = implode("|", $newarguments);
	
		$values = array();
		$values['Arguments'] = $argumentstr;
		//echo "<br>Argumentstr - " . $argumentstr;
		$success = Table::updateRow("worder_objectives", $values, $objectiveID, true);
	
		redirecttotal('worder/objectives/showobjective&id=' . $objectiveID,null);
	}
	*/
	
	
	
	

	public function removeobjectiveAction() {
		
		$objectiveID = $_GET['objectiveID'];

		$rows = Table::load('worder_lessonsentencelinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID);
		if (count($rows) > 0) {
			echo "<br>worder_lessonsentencelinks found, ei voida poistaa";
			exit;
		}
		
		$rows = Table::load('worder_lessonobjectivelinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID, true);
		if (count($rows) > 0) {
			echo "<br>worder_lessonobjectivelinks found, ei voida poistaa";
			exit;
		}
		
		$rows = Table::load('worder_objectivearguments', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID . " OR ValueobjectiveID=" . $objectiveID);
		if (count($rows) > 0) {
			echo "<br>worder_objectivearguments found, ei voida poistaa";
			exit;
		}
		
		
		$rows = Table::load('worder_objectiveprerequisites', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND (ObjectiveID=" . $objectiveID . " OR PrerequisiteID=" . $objectiveID . ")");
		if (count($rows) > 0) {
			echo "<br>worder_objectiveprerequisites-viitteitä löytyi, ei voida poistaa";
			exit;
		}
		
		$rows = Table::load('worder_objectivelinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND (ObjectiveID=" . $objectiveID . " OR ParentID=" . $objectiveID . ")");
		if (count($rows) > 0) {
			echo "<br>worder_objectivelinks-viitteitä löytyi, ei voida poistaa";
			exit;
		}
		
		$rows = Table::load('worder_objectivecompletions', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND (ObjectiveID=" . $objectiveID . " OR ParentobjectiveID=" . $objectiveID . ")");
		if (count($rows) > 0) {
			echo "<br>worder_objectivecompletions-viitteitä löytyi, ei voida poistaa";
			exit;
		}
		
		$rows = Table::load('worder_objectivegeneratefeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID);
		if (count($rows) > 0) {
			echo "<br>worder_objectivegeneratefeatures-viitteitä löytyi, ei voida poistaa";
			exit;
		}
		
		$rows = Table::load('worder_objectivefeaturerequirements', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID);
		if (count($rows) > 0) {
			echo "<br>worder_objectivefeaturerequirements-viitteitä löytyi, ei voida poistaa";
			exit;
		}
		
		// TODO: pitänee katsoa myös toisten objectiivien arguments-osiosta...
		
		$success = Table::deleteRowsWhere('worder_objectives',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID);
		//echo "<br>Not executed, test";
		redirecttotal('worder/objectives/showobjectives', null);
	}
	
	
	


	public function removecompletionAction() {
	
		$comments = true;
		$rowID = $_GET['id'];
		$objectiveID = $_GET['objectiveID'];
		$success = Table::deleteRowsWhere('worder_objectivecompletions',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID . " AND RowID=" . $rowID, $comments);
	
		if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	
	
	
	

	public function insertobjectiveargumentAction() {
	
		$objectiveID = $_GET['objectiveID'];
		$argumentID = $_GET['argumentID'];
		$argumentobjectiveID = $_GET['argumentobjectiveID'];
		
		/*
		$level = 1;
		if (isset($_GET['level'])) {
			$level = $_GET['level'];
		}
		*/
		
		
		
		$objective = Table::loadRow("worder_objectives", $objectiveID);
		$argumentobjective = Table::loadRow("worder_objectives", $argumentobjectiveID);
		
		
		$values = array();
		$values['ObjectiveID'] = $objectiveID;
		$values['ArgumentID'] = $argumentID;
		$values['ValueobjectiveID'] = $argumentobjectiveID;
		$values['LanguageID'] = $objective->languageID;
		$values['WordclassID'] = $argumentobjective->wordclassID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$success = Table::addRow("worder_objectivearguments", $values);
		
		$values = array();
		$values['ObjectiveID'] = $objectiveID;
		$values['PrerequisiteID'] = $argumentobjectiveID;
		$values['ArgumentID'] = $argumentID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_objectiveprerequisites", $values, $comments);
		
		
		ObjectivesController::UpdateObjectiveArguments($objectiveID, $comments);
		
		/*
		$arguments = explode("|", $objective->arguments);
		$newargument = $argumentID . ":" . $argumentobjective->wordclassID . ":" . $argumentobjectiveID . ":" . $level . ":";
		$newarguments = array();
		foreach($arguments as $index => $value) {
			if ($value != "") {
				$parts = explode(":", $value);
				if ($parts[0] != $argumentID) {
					$newarguments[$parts[0] ] = $value;
				}
			}
		}
		$newarguments[] = $newargument;
		$argumentstr = implode("|", $newarguments);
	
	
		$values = array();
		$values['Arguments'] = $argumentstr;
		$success = Table::updateRow("worder_objectives", $values, $objectiveID, true);
		*/
			
		redirecttotal('worder/objectives/showobjective&id=' . $objectiveID,null);
	}
	
	
	/*
	// Käytettiin argumentin subobjectiveiden TierTable-versiossa, se on poistettu
	public function insertargumentfeatureAction() {
	
		$objectiveID = $_GET['objectiveID'];
		$argumentID = $_GET['argumentID'];
		$featureID = $_GET['featureID'];
		$valueID = $_GET['valueID'];
	
		$objective = Table::loadRow("worder_objectives", $objectiveID);
	
		$arguments = explode("|", $objective->arguments);
		$newarguments = array();
		foreach($arguments as $index => $argumentstr) {
				
			if ($argumentstr != "") {
				$parts = explode(":", $argumentstr);
				if ($parts[0] == $argumentID) {
					$features = $parts[4];
					//echo "<br>Features - " . $features;
					if ($features == "") {
						$argumentstr = $argumentstr . $featureID . "-" . $valueID;
					} else {
						$argumentstr = $argumentstr . "," . $featureID . "-" . $valueID;
					}
					//echo "<br>argumentstr - " . $argumentstr;
					$newarguments[] = $argumentstr;
				} else {
					//echo "<br>Argument no match - " . $argumentID . " - " . $parts[0];
					$newarguments[] = $argumentstr;
				}
			}
		}
		$argumentstr = implode("|", $newarguments);
	
	
		$values = array();
		$values['Arguments'] = $argumentstr;
		//echo "<br>New arguments - " . $argumentstr;
		$success = Table::updateRow("worder_objectives", $values, $objectiveID, true);
	
		redirecttotal('worder/objectives/showobjective&id=' . $objectiveID,null);
	}
	*/
	
	
	
	
	
	public function copyobjectiveAction() {
	
		$comments = false;
		$objectiveID = $_GET['objectiveID'];
		$objective =  Table::loadRow("worder_objectives","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID);
	
		$newObjectiveID = Table::addRow('worder_objectives',$objective, $comments);
	
		// Lisätään samat parentit
		$rows = Table::load('worder_objectivelinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID);
		if (count($rows) > 0) {
			foreach($rows as $index => $row) {
				$values = array();
				$values['ObjectiveID'] = $newObjectiveID;
				$values['ParentID'] = $row->parentID;
				$values['GrammarID'] = $row->grammarID;
				Table::addRow('worder_lessonobjectivelinks',$values, $comments);
			}
		}
		
		// lisätään samat prerequirementit
		$rows = Table::load('worder_objectiveprerequisites', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID);
		if (count($rows) > 0) {
			foreach($rows as $index => $row) {
				$values = array();
				$values['ObjectiveID'] = $newObjectiveID;
				$values['PrerequisiteID'] = $row->prerequisiteID;
				$values['GrammarID'] = $row->grammarID;
				Table::addRow('worder_objectiveprerequisites',$values, $comments);
			}
		}
		
		$arguments = Table::load("worder_objectivearguments", "WHERE ObjectiveID=" . $objectiveID . " AND GrammarID=" . $_SESSION['grammarID']);
		if (count($arguments) > 0) {
			foreach($arguments as $index => $argument) {
				$values = array();
				$values['ObjectiveID'] = $newObjectiveID;
				$values['ArgumentID'] = $argument->argumentID;
				$values['ValueobjectiveID'] = $argument->valueobjectiveID;
				$values['LanguageID'] = $argument->langaugeID;
				$values['WordclassID'] = $argument->wordclassID;
				$values['GrammarID'] = $argument->grammarID;
				Table::addRow('worder_objectivearguments',$values, $comments);
			}
		}
		ObjectivesController::UpdateObjectiveArguments($objectiveID, $comments);
		
		
		$featurerequirements = Table::load("worder_objectivefeaturerequirements", "WHERE ObjectiveID=" . $objectiveID . " AND GrammarID=" . $_SESSION['grammarID']);
		if (count($featurerequirements) > 0) {
			foreach($featurerequirements as $index => $requirement) {
				$values = array();
				$values['ObjectiveID'] = $newObjectiveID;
				$values['GrammarID'] = $requirement->grammarID;
				$values['FeatureID'] = $requirement->featureID;
				$values['ValueID'] = $requirement->valueID;
				$values['WordclassID'] = $requirement->wordclassID;
				$values['LanguageID'] = $requirement->langaugeID;
				Table::addRow('worder_objectivefeaturerequirements',$values, $comments);
			}
		}
		ObjectivesController::UpdateObjectiveFeatureRequirements($objectiveID, $comments);
		
		$generatefeatures = Table::load("worder_objectivegeneratefeatures", "WHERE ObjectiveID=" . $objectiveID . " AND GrammarID=" . $_SESSION['grammarID']);
		if (count($generatefeatures) > 0) {
			foreach($generatefeatures as $index => $generatefeature) {
				$values = array();
				$values['FeatureID'] = $generatefeature->featureID;
				$values['ValueID'] = $generatefeature->valueID;
				$values['GrammarID'] = $generatefeature->grammarID;
				$values['ObjectiveID'] = $newObjectiveID;
				$values['WordclassID'] = $generatefeature->wordclassID;
				Table::addRow('worder_objectivegeneratefeatures',$values, $comments);
			}
		}
		ObjectivesController::UpdateGenerateFeatures($objectiveID, $comments);
		
		$lessonlinks = Table::load("worder_lessonobjectivelinks", "WHERE ObjectiveID=" . $objectiveID . " AND GrammarID=" . $_SESSION['grammarID']);
		if (count($lessonlinks) > 0) {
			foreach($lessonlinks as $index => $link) {
				$values = array();
				$values['LessonID'] = $link->lessonID;
				$values['ObjectiveID'] = $newObjectiveID;
				$values['GrammarID'] = $link->grammarID;
				$values['LanguageID'] = $link->languageID;
				Table::addRow('worder_lessonobjectivelinks',$values, $comments);
			}
		}
		
		// State asetetaan oletustilaksi, riippumatta kopioitavan lähde objectiven statesta
		$states = Table::load("worder_states");
		$defaultstate = null;
		foreach($states as $index => $state) {
			if ($defaultstate == null) $defaultstate = $state;
			if ($state->defaultstate == 1) $defaultstate = $state;			
		}
		if ($defaultstate != null) {
			$values = array();
			$values['StateID'] = $defaultstate->stateID;
			$success = Table::updateRow("worder_objectives", $values, $newObjectiveID);
		}
		
		if(!$comments) redirecttotal('worder/objectives/showobjective&id='. $newObjectiveID);
	}
	
	
	
}
?>