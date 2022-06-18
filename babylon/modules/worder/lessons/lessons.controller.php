<?php


class LessonsController extends AbstractController {
	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','worder.css','yritys.css','prism.css','chosen.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','chosen.jquery.js','prism.js');
	}
		
	
	public function indexAction() {
		//$this->showlessonsAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showlessonsAction() {
		
		updateActionPath("Lessons");
		$languageID = getModuleSessionVar('languageID',0);
		$this->registry->languages = Table::load('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		if ($languageID == 0) {
			foreach($this->registry->languages as $index => $language) {
				$languageID = $language->languageID;
				break;
			}
		}
		$this->registry->languageID = $languageID;
		$this->registry->lessons = Table::load('worder_lessons','WHERE GrammarID=' . $_SESSION['grammarID'] . ' AND LanguageID=' . $languageID . ' ORDER BY Sortorder');
		$showcounts = getModuleSessionVar('lessoncounts', 0);
		
		
		$taskID = getModuleSessionVar('taskID',0);
		$generatorID = getModuleSessionVar('generatorID',0);
		if (isset($_GET['languageID'])) {
			$generatorID = 0;
			setModuleSessionVar('generatorID',0);
		}
		$this->registry->taskID = $taskID;
		$this->registry->generatorID = $generatorID;
		
		$tableID = Table::getTableID("worder_lessons");
		$allminitasks = Table::load("tasks_minitasks", "WHERE TargettableID=" . $tableID );
		$selectedminitasks = array();
		$tasklist = array();
		$generatorlist = array();
		foreach($allminitasks as $minitaskID => $minitask) {
			//echo "<br>minitask found - " . $minitask->minitaskID . ", taskID: " . $minitask->taskID. ", taskID: " . $minitask->taskID . ", lessonID:" . $minitask->targetID;
			if (isset($this->registry->lessons[$minitask->targetID])) {
				//echo "<br>Selected minitask found - " . $minitask->minitaskID . ", taskID: " . $minitask->taskID;
				//$selectedminitasks[$minitaskID] = $minitask;
				//$tasklist[$minitask->taskID] = $minitask->taskID;
				$generatorlist[$minitask->generatorID] = $minitask->generatorID;
				
				if ($minitask->generatorID == $generatorID) {
					$lesson = $this->registry->lessons[$minitask->targetID];
					$lesson->taskstate = $minitask->state;
				}
			}
		}
		$alltasks = Table::load("tasks_tasks", "WHERE TargettableID=" . $tableID );
		foreach($alltasks as $minitaskID => $task) {
			//echo "<br>task found - taskID: " . $task->taskID . ", lessonID:" . $task->targetID;
			if (isset($this->registry->lessons[$task->targetID])) {
				//echo "<br>Selected task found - taskID: " . $task->taskID;
				$generatorlist[$minitask->generatorID] = $minitask->generatorID;
				//$tasklist[$task->taskID] = $task->taskID;
				if ($task->generatorID == $generatorID) {
					//echo "<br> -- targetID - " . $task->targetID;
					$lesson = $this->registry->lessons[$task->targetID];
					$lesson->taskstate = $task->stateID;
				}
			}
		}
		$this->registry->generators = Table::loadWhereInArray('tasks_generators', 'GeneratorID', $generatorlist);
		
		//echo "<br>generators count - " . count($this->registry->generators);	
		
		
		$activelanguages = getModuleSessionVar('activelanguages','');
		if ($activelanguages == '') {
			$activelanguages = $languageID;
			$langlist = array();
			$langlist[$languageID] = $languageID;
			$this->registry->activelanguages = $langlist;
		
		} else {
			$langlist = explode(":", $activelanguages);
			$this->registry->activelanguages = $langlist;
		}
		
		
		
		if ($showcounts == 0) {
			$this->registry->showcounts = false;
		} else {
			$this->registry->showcounts = true;
			$foundconcepts = array();
			$wordtotal = 0;
			$conceptlinks = Table::load('worder_lessonconcepts', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
			foreach($conceptlinks as $index => $link) {
				if (!isset($this->registry->lessons[$link->lessonID])) {
					echo "<br>No lesson found - lessonID:" . $link->lessonID . ", conceptID=" . $link->conceptID;
				} else {
					$lesson = $this->registry->lessons[$link->lessonID];
					if ($lesson->wordcount == null) {
						$lesson->wordcount = 1;
					} else {
						$lesson->wordcount = $lesson->wordcount + 1;
					}
					$foundconcepts[$link->conceptID] = $link->conceptID;
					$wordtotal++;
				}
			}
			$concepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $foundconcepts, "WHERE GrammarID=" . $_SESSION['grammarID']);
			
			foreach($conceptlinks as $index => $link) {
				if (!isset($this->registry->lessons[$link->lessonID])) {
					echo "<br>No lesson foudn - " . $link->lessonID;
				} else {
					$lesson = $this->registry->lessons[$link->lessonID];
					$concept = $concepts[$link->conceptID];
			
					if ($concept->wordclassID == 1) {
						if ($lesson->subscount == null) {
							$lesson->subscount = 1;
						} else {
							$lesson->subscount = $lesson->subscount + 1;
						}
					} else if ($concept->wordclassID == 3) {
						if ($lesson->adjcount == null) {
							$lesson->adjcount = 1;
						} else {
							$lesson->adjcount = $lesson->adjcount + 1;
						}
					} else if ($concept->wordclassID == 2) {
						if ($lesson->verbcount == null) {
							$lesson->verbcount = 1;
						} else {
							$lesson->verbcount = $lesson->verbcount + 1;
						}
					} else {
						if ($lesson->othercount == null) {
							$lesson->othercount = 1;
						} else {
							$lesson->othercount = $lesson->othercount + 1;
						}
					}
				}
			}
			
			if ($languageID == 0) {
				$objectivelinks = Table::load('worder_lessonobjectivelinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Sortorder");
			} else {
				$objectivelinks = Table::load('worder_lessonobjectivelinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " ORDER BY Sortorder");
			}
			
			foreach($objectivelinks as $index => $link) {
				$lesson = $this->registry->lessons[$link->lessonID];
				$lesson->objectivecount = $lesson->objectivecount + 1;
			}
			
			
			$finishedcounter = 0;
			$unfinishedcounter = 0;
			$activecount = 0;
			foreach($this->registry->lessons as $index => $lesson) {
				$lesson->totalcount = $lesson->subscount + $lesson->adjcount + $lesson->verbcount + $lesson->othercount;
				if (($lesson->verbcount > 0) && ($lesson->totalcount > 10)) {
					if ($lesson->objectivecount > 10) {
						//$lesson->color = 'lightgreen';
					} else {
						
					}
				} else {
					//$lesson->color = 'pink';
				}
				
				if (($lesson->verbcount > 1) && ($lesson->adjcount > 0) && ($lesson->subscount > 9)) {
					//$lesson->color = 'lightgreen';
					$finishedcounter++;
				} else {
					//$lesson->color = 'pink';
					$unfinishedcounter++;
				}
				if ($lesson->active == 1) $activecount++;
			}
			$this->registry->finished = $finishedcounter;
			$this->registry->unfinished = $unfinishedcounter;
			$this->registry->activecount = $activecount;
		}
		
		
		$this->registry->template->show('worder/lessons','lessons');
	}
	
	
	



	public function showlexiconlessonsAction() {
	
		updateActionPath("LessonLexicon");
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
	
		$this->registry->wordclassID = getSessionVar('wordclassID', 0);
	
		$languageID = 1;
		$this->registry->languageID = $languageID;
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$concepts = Table::load("worder_concepts", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $this->registry->wordclassID);
		$this->registry->lessons = Table::load("worder_lessons", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->lessonconcepts = Table::load("worder_lessonconcepts", "WHERE GrammarID=" . $_SESSION['grammarID']);

		foreach($this->registry->lessonconcepts as $index => $link) {
			$conceptID = $link->conceptID;
			if (isset($concepts[$conceptID])) {
				//echo "<br>conceptID found - " . $conceptID . " - " . $link->lessonID;
				$concept = $concepts[$conceptID];
				$concept->lessonID = $link->lessonID;
				$concepts[$conceptID] = $concept;
			} 
		}
		
		/*
		foreach($concepts as $index => $concept) {
			if (isset($concept->lessonID)) {
				//$concept = $concepts[$conceptID];
				//$concept->lessonID = $link->lessonID;
			} else {
				$concept->lessonID = 0;
			}
		}
		*/
		
		$this->registry->concepts = $concepts;
		
		$this->registry->template->show('worder/lessons','wordlessons');
	}
	
	
	
	

	public function showhierarchyAction() {
	
		$languageID = getModuleSessionVar('languageID',0);
		$this->registry->languageID = $languageID;
		
		$lessonplanID = getModuleSessionVar('lessonplanID',0);
		$activelanguages = getModuleSessionVar('activelanguages','');
		
		if ($activelanguages == '') {
			$activelanguages = $languageID;
			$langlist = array();
			$langlist[$languageID] = $languageID;
			$this->registry->activelanguages = $langlist;
		} else {
			$langlist = explode(":", $activelanguages);
			$this->registry->activelanguages = $langlist;
		}
		$this->registry->lessonplans = Table::load('worder_lessonplans', "WHERE GrammarID=" . $_SESSION['grammarID']);
		if ($lessonplanID == 0) {
			foreach($this->registry->lessonplans as $index => $lessonplan) {
				$lessonplanID = $lessonplan->lessonplanID;
				break;
			}
		}
		
		$this->registry->lessons = Table::load('worder_lessons','WHERE GrammarID=' . $_SESSION['grammarID'] . ' 	ORDER BY Level, Sortorder');
		$this->registry->languages = Table::load('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$lessonlinks = Table::load('worder_lessonlinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonplanID=" . $lessonplanID . " AND LanguageID=" . $languageID . " ORDER BY Sortorder");

		//echo "<br>Lessonlinks - " . count($lessonlinks) . ", lessonplanID=" . $lessonplanID;
		
		$rootarray = array();
		foreach($lessonlinks as $linkID => $link) {
			if ($link->parentID == 0) {
				//echo "<br>Parent Found - " . $parentlesson->name . " vs.  " . $lesson->name;
				$lesson = $this->registry->lessons[$link->lessonID];
				$lesson->used = 1;
				$lesson->identifier = "0-" . $link->lessonID;
				$rootarray[] = $lesson; 
			} else {
				$parentlesson = $this->registry->lessons[$link->parentID];
				$lesson = $this->registry->lessons[$link->lessonID];
				//echo "<br>Found - " . $parentlesson->name . " vs.  " . $lesson->name;
				
				$lesson->used = 1;
				$lesson->identifier = $parentlesson->lessonID . "-" . $link->lessonID;
				$parentlesson->addChild($lesson);
			}
		}
		
		$freelessons = array();
		foreach($this->registry->lessons as $index => $lesson) {
			if ($lesson->used == 0) {
				$freelessons[$index] = $lesson;
			}
		}
		
		$this->registry->hierarchy = $rootarray;
		$this->registry->freelessons = $freelessons;
		
		$this->registry->lessonplanID = $lessonplanID;
		$this->registry->template->show('worder/lessons','lessonhierarchy');
	}
	

	public function showlessonAction() {
		
		$comments = false;
		$languageID = getModuleSessionVar('languageID',0);
		if ($languageID == 0) {
			//echo "<br>LanguageID = 0";
			$languageID = 1;
			setModuleSessionVar('languageID',1);
		}
		
		/*
		$activelanguages = getModuleSessionVar('activelanguages','');
		//echo "<br>languages - '" . $activelanguages . "'";
		
		if ($activelanguages == '') {
			$activelanguages = $languageID;
			$langlist = array();
			$langlist[$languageID] = $languageID;
			$this->registry->activelanguages = $langlist;
	
		} else {
			$langlist = explode(":", $activelanguages);
			$this->registry->activelanguages = $langlist;
		}
		*/
		
		$lessonID = $_GET['id'];
		$this->registry->lesson = Table::loadRow('worder_lessons',$lessonID);
		$this->registry->languages = Table::load('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->lessondata = Table::load('worder_lessondata',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
		$activelanguages = array();
		$rulesetlist = array();
		foreach($this->registry->lessondata as $index => $lessondata) {
			//$activelanguage = $this->registry->languages[$lessondata->languageID];
			$activelanguages[$lessondata->languageID] = $lessondata->languageID;
			$rulesetlist[$lessondata->rulesetID] = $lessondata->rulesetID;
		}
		$this->registry->activelanguages = $activelanguages;
		$this->registry->rulesets = Table::loadWhereInArray('worder_rulesets','setID',$rulesetlist, "WHERE GrammarID=" . $_SESSION['grammarID'], $comments);
		
		updateActionPath(parseMultilangString($this->registry->lesson->name,1));
		$this->registry->names = parseMultilangArray($this->registry->lesson->name, 1);
		$this->registry->wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->arguments = Table::load('worder_arguments', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->rules = Table::load('worder_rules', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$this->registry->wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->colors = Table::load("system_colors");
		$this->registry->states = Table::load("worder_states", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		updateActionPath(parseMultilangString($this->registry->lesson->name, $languageID));
		foreach($this->registry->names as $index => $name) {
			$var = "name" . $index;
			$this->registry->lesson->$var = $name;
		}
	
		$this->registry->taskstates = Table::load('tasks_states');
		
		/*
		// tämä ladataan systemissä...
		$tableID = Table::getTableID('worder_lessons');
		$this->registry->minitasks = Table::load('tasks_minitasks', "WHERE TargettableID=" . $tableID . " AND TargetID=" . $lessonID);
		foreach($this->registry->minitasks as $index => $minitask) {
			echo "<br>Minitasks - " . $minitask->minitaskID;
		}
		*/
		
	
	
	
		//$this->registry->inflectionsets = Table::load('worder_inflectionsets', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->components = Table::load('worder_components', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$features = Table::load('worder_features', "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		//$this->registry->lessonrequirements = Table::load('worder_lessonrequirements', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);

		$objectives = Table::load('worder_objectives', "WHERE GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Sortorder");
		foreach($objectives as $objectiveID => $objective) {
			if ($objective->stateID == 0) {
				echo "<br>Objective state missing - objectiveID: " . $objective->objectiveID;
			} else {
				$state = $this->registry->states[$objective->stateID];
				$objective->colorID = $state->colorID;
			}
		}
		$this->registry->objectives = $objectives;
		$objectivelinks = Table::load('worder_lessonobjectivelinks', "WHERE LessonID=" . $this->registry->lesson->lessonID. " AND GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Sortorder");
		$lessonobjectivelist = array();
		
		
		$lessonobjectives = array();
		$lessonobjectivelist = array();
		foreach($objectivelinks as $index => $objectivelink) {
			$lessonobjective = $this->registry->objectives[$objectivelink->objectiveID];
			$lessonobjective->rowID = $objectivelink->rowID;
			$lessonobjective->stage = $objectivelink->stage;
			$lessonobjective->argumentcount = 0;
			$lessonobjectives[$lessonobjective->objectiveID] = $lessonobjective;
			$lessonobjectivelist[$objectivelink->objectiveID] = $objectivelink->objectiveID;
		}
		
		
		// Ladataan objectiveargumentsit, niin saadaan tietoon mitä sub-objectiveja on olemassa
		$objectivearguments = Table::loadWhereInArray("worder_objectivearguments", "ObjectiveID", $lessonobjectivelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		foreach($objectivearguments as $index => $objectiveargument) {
			//echo "<br>Objective " . $objectiveargument->objectiveID;
			
			
			$parentobjective = $lessonobjectives[$objectiveargument->objectiveID];
			$parentobjective->argumentcount = $parentobjective->argumentcount + 1;
			
			if (isset($lessonobjectives[$objectiveargument->valueobjectiveID])) {
				//echo "<br>Objective argument found - " . $objectiveargument->valueobjectiveID . " in objective " . $objectiveargument->objectiveID;
				$lessonobjective = $lessonobjectives[$objectiveargument->valueobjectiveID];
				if ($lessonobjective->parents == null) {
					$lessonobjective->parents = $objectiveargument->objectiveID;
				} else {
					$lessonobjective->parents = $lessonobjective->parents  ."," . $objectiveargument->objectiveID;
				}
			} else {
				//echo "<br>Objective argument missing - " . $objectiveargument->valueobjectiveID . " in objective " . $objectiveargument->objectiveID;
				//$lessonobjective = $lessonobjectives[$objectiveargument->valueobjectiveID];
				
				if ($parentobjective->parents == null) {
					$parentobjective->parents = "<font style=\"color:red\">" . $objectiveargument->valueobjectiveID . "</font>";
				} else {
					$parentobjective->parents = $parentobjective->parents  .",<font style=\"color:red\">" . $objectiveargument->valueobjectiveID . "</font>";
				}
			}
		}
		$this->registry->lessonobjectives = $lessonobjectives;
		
		
		
		$lessonobjectivechecks = Table::load("worder_objectivelessonchecks", "WHERE LessonID=" . $this->registry->lesson->lessonID . " AND GrammarID=" . $_SESSION['grammarID']);
		foreach($lessonobjectivechecks as $index => $checkline) {
			//echo "<br>lessonID - " . $checkline->lessonID;
			$objective = $objectives[$checkline->objectiveID];
			$var = 'color'.$checkline->languageID;
			//echo "<br>color - " . $var . " --- " . $lesson->lessonID;
			$objective->$var = '#90ee90';
		}
		
		
		$this->registry->lessonwords = Table::load('worder_lessonconcepts', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID . " ORDER BY Sortorder");
		$conceptlist = array();
		foreach($this->registry->lessonwords as $index => $value) {
			$conceptlist[$value->conceptID] = $value->conceptID;
		}
		$loadedconcepts = Table::loadWhereInArray('worder_concepts','conceptID',$conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		$tempconcepts = array();
		foreach($this->registry->lessonwords as $index => $lessonword) {
			$concept = $loadedconcepts[$lessonword->conceptID];
			$tempconcepts[$lessonword->conceptID] = $concept;
			$concept->sortorder = $lessonword->sortorder;
		}
		$this->registry->concepts = $tempconcepts;
		
		$this->registry->wordlinks = Table::loadWhereInArray('worder_conceptwordlinks','conceptID',$conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND Defaultword=1", $comments);
		$wordlist = array();
		foreach($this->registry->wordlinks as $index => $link) {
			$wordlist[$link->wordID] = $link->wordID;
		}
		$this->registry->words = Table::loadWhereInArray('worder_words','wordID',$wordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		$words = array();
		foreach($this->registry->words as $index => $word) {
			$words[$word->wordID] = $word;
			//echo "<br>Word - " . $word->wordID;
		}
	
		foreach($this->registry->wordlinks as $index => $link) {
			$word = $words[$link->wordID];
			$concept = $this->registry->concepts[$link->conceptID];
			$language = $this->registry->languages[$link->languageID];
			$name = $language->name;
			$concept->$name = $word->lemma;
			$concept->lemma = $word->lemma;
			if ($this->registry->lesson->languageID == $link->languageID) {
				$concept->wordID = $word->wordID;
			}
		}
	
		
		// - Tarvitaan kaikki wordfeaturelinksit lessonin sanoilta
		$this->registry->wordfeatures = Table::loadWhereInArray('worder_wordfeaturelinks','wordID',$wordlist, "WHERE GrammarID=" . $_SESSION['grammarID'], $comments);
		if ($comments) echo "<br>Wordfeaturecount - " . count($this->registry->wordfeatures);		
		
		// - Tarvitaan kaikki componentit lessonin käsitteiltä
		$this->registry->componentlinks = Table::loadWhereInArray('worder_conceptcomponentlinks','conceptID',$conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->sentencelinks = Table::load("worder_lessonsentencelinks", "WHERE LessonID=" . $lessonID);
		$sentencelinks = array();
		if ($this->registry->sentencelinks == null) {
			$this->registry->sentences = array();
		} else {
			foreach($this->registry->sentencelinks as $index => $link) {
				$sentencelinks[$link->sentenceID] = $link->sentenceID;
			}
			$language = $this->registry->languages[$this->registry->lesson->languageID];
			$this->registry->sentences = Table::loadWhereInArray("worder_sentences", "sentenceID", $sentencelinks, "WHERE GrammarID=" . $_SESSION['grammarID']);
		}
		$objectivelist = array();
		foreach($this->registry->sentences as $index => $sentence) {
			foreach($this->registry->sentencelinks as $sindex => $link) {
				if ($link->sentenceID == $sentence->sentenceID) {
					$sentence->objectiveID = $link->objectiveID;
					$objectivelist[$sentence->objectiveID] = $sentence->objectiveID;
					$sentence->lessonID = $link->lessonID;
					$sentence->linkID = $link->rowID;
				}
			}
		}
		

		$sentencechecks = array();
		//$sentencechecks = Table::load("worder_objectivesentencechecks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
		$sentencechecks = Table::loadWhereInArray("worder_objectivesentencechecks", "objectiveID", $objectivelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		foreach($sentencechecks as $index => $checkline) {
			if (isset($this->registry->sentences[$checkline->sentenceID])) {
				$sentence = $this->registry->sentences[$checkline->sentenceID];
				$var = 'color'.$checkline->languageID;
				$sentence->$var = '#90ee90';
			}
		}
		
		
		
		$this->registry->stages = $this->getLessonStages();
		
		
		
		// Haetaan kaikki parentit, rekursiivisesti... vastaava toiminto on toteutettu conceptiin
		$this->registry->lessons = Table::load('worder_lessons','WHERE GrammarID=' . $_SESSION['grammarID'] . ' ORDER BY Level, Sortorder');
		$lessonplanID = getModuleSessionVar('lessonplanID',0);
		//if ($lessonplanID == 0) echo "<br>LessonplanID on nolla";
		//if ($lessonplanID == null) echo "<br>LessonplanID on null";
		
		$lessonlinks = Table::load('worder_lessonlinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
		//$lessonlinks = Table::load('worder_lessonlinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonplanID=" . $lessonplanID . " AND LessonID=" . $lessonID);

		$templessons = array();
		foreach($lessonlinks as $index => $lessonlink) {
			if ($lessonlink->parentID > 0) {
				$lesson = Table::loadRow('worder_lessons',$lessonlink->parentID);
				$templessons[$lesson->lessonID] = $lesson;
				//echo "<br>Prelesson - " . $lessonlink->parentID . " - " . $lesson->name;
			}
		}
		$this->registry->prelessons = $templessons;
		
		$this->registry->levels = Table::load("worder_lessonlevels", "WHERE LessonID=" . $lessonID . " AND GrammarID=" . $_SESSION['grammarID']);
		$this->registry->difficultylevels = $this->getDifficultylevels();
		
		$this->registry->template->show('worder/lessons','lesson');
	}
	
	
	
	// siirrä tänne lessondata-lesson.name päivityskoodi
	private function updateLessonName($lessonID) {
		
		$lessondata = Table::load("worder_lessondata", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
		$namelist = array();
		foreach($lessondata as $index => $dataline) {
			$namelist[$dataline->languageID] = $dataline->name;
		}
		
		$values = array();
		$values['Name'] = createMultilangString($namelist);
		$success = Table::updateRow("worder_lessons", $values, $lessonID);
		
		return $success;
	}
	
	
	public function getObjectiveLinkTypes() {
	
		$rows = array();
	
		$row = new Row();
		$row->typeID = 1;
		$row->name = "Hierarchy parent";
		$rows[1] = $row;
	
		$row = new Row();
		$row->typeID = 2;
		$row->name = "Prerequisite";
		$rows[2] = $row;
	
		$row = new Row();
		$row->typeID = 3;
		$row->name = "S3";
		$rows[3] = $row;
	
		$row = new Row();
		$row->typeID = 4;
		$row->name = "S4";
		$rows[4] = $row;
	
		return $rows;
	}
	
	
	
	
	
	public function getLessonStages() {
		
		$rows = array();
		
		$row = new Row();
		$row->stage = 1;
		$row->name = "S1";
		$rows[1] = $row;
		
		$row = new Row();
		$row->stage = 2;
		$row->name = "S2";
		$rows[2] = $row;
		
		$row = new Row();
		$row->stage = 3;
		$row->name = "S3";
		$rows[3] = $row;
		
		$row = new Row();
		$row->stage = 4;
		$row->name = "S4";
		$rows[4] = $row;
		
		return $rows;
	}


	
	public function updateactivelanguagesAction() {
		
		$languages = Table::load('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$langstr = "";
		foreach($languages as $index => $lang) {
			if (isset($_GET['language-' . $lang->languageID])) {
				if ($_GET['language-' . $lang->languageID] == '1') {
					if ($langstr == "") {
						$langstr = $lang->languageID;
					} else {
						$langstr = $langstr . ":" . $lang->languageID;
					}				
				}
			}
		}
		setModuleSessionVar('activelanguages', $langstr);
		redirecttotal('worder/lessons/showlessons',null);
	}

	// Voisi yhdistää lesson up ja down yhdeksi funktioksi
	public function lessonupAction() {
		
		$comments = false;
		
		if ($comments) echo "<br>lessondownAction";
		
		$identifier = explode('-',$_GET['id']);
		$parentID = $identifier[0];
		$lessonID = $identifier[1];
		$lessonplanID = getModuleSessionVar('lessonplanID',0);
		
		if ($comments) echo "<br>ParentID - " . $parentID;
		if ($comments) echo "<br>LessonID - " . $lessonID;
		if ($comments) echo "<br>LessonplanID - " . $lessonplanID;
		
		$lessonlinks = Table::load('worder_lessonlinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ParentID=" . $parentID . " AND LessonplanID=" . $lessonplanID . " ORDER BY Sortorder");
		
		$previousID = -1;
		$previoussort = -1;
		$currentsort = -1;
		$currentlinkID = -1;
		$previouslinkID = -1;
		foreach($lessonlinks as $index => $link) {
			if ($link->lessonID != $lessonID) {
				$previousID = $link->lessonID;
				$previoussort = $link->sortorder;
				$previouslinkID = $link->linkID;
			} else {
				$currentlinkID = $link->linkID;
				$currentsort = $link->sortorder;
				break;
			}
		}
			
		if ($previousID == -1) {
			if ($comments) echo "<br>Already up";
			if (!$comments) redirecttotal('worder/lessons/showhierarchy',null);
			exit;
		}
			
		if ($comments) echo "<br>PreviousID - " . $previousID . " - " . $previoussort;
			
		$values = array();
		$values['Sortorder'] = $previoussort;
		if ($comments) echo "<br>UPDATE - " . $currentlinkID . " - " . $previoussort;
		$success = Table::updateRow("worder_lessonlinks", $values, $currentlinkID, true);
			
		$values = array();
		$values['Sortorder'] = $currentsort;
		if ($comments) echo "<br>UPDATE - " . $previouslinkID . " - " . $currentsort;
		$success = Table::updateRow("worder_lessonlinks", $values, $previouslinkID, true);
		
		if (!$comments) redirecttotal('worder/lessons/showhierarchy',null);
	}
	
	
	public function lessondragdropAction() {
	
		$comments = true;
	
		if ($comments) echo "<br>lessondragdropAction";
	
		$languageID = $_GET['languageID'];
		$lessonID = $_GET['lessonID'];
		$previousID = $_GET['previousID'];
		
		$lessons = Table::load('worder_lessons',"WHERE LanguageID=" . $languageID . " AND GrammarID=" . $_SESSION['grammarID'] . ' ORDER BY Sortorder', $comments);

		$previousfound = null;
		$lessonfound = null;
		$changelist = array();		// id, sortID
		$previous = null;
		$firstnext = null;
		$end = false;
		
		if ($comments) echo "<br>Lessoncount - " . count($lessons);
		foreach($lessons as $index => $lesson) {
			
			if ($comments) echo "<br>Processing lesson - " . $lesson->lessonID;
				
			if ($lessonfound != null) {
				if ($comments) echo "<br> -- lesson is found";
				if ($firstnext == null) {
					if ($lesson->lessonID == $previousID) {		// peräkkäiset
						if ($comments) echo "<br> -- -- lessonfound AA";
						$changelist[$lesson->lessonID] = $lessonfound->sortorder;
						$changelist[$lessonfound->lessonID] = $lesson->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- lessonfound BB";
						$firstnext = $lesson;
						$changelist[$lesson->lessonID] = $lessonfound->sortorder;
					}
				} else {
					if ($lesson->lessonID == $previousID) {
						if ($comments) echo "<br> -- -- lessonfound CC";
						$changelist[$lesson->lessonID] = $previous->sortorder;
						$changelist[$lessonfound->lessonID] = $lesson->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- lessonfound DD";
						$changelist[$lesson->lessonID] = $previous->sortorder;
					}
				}
			}
				
			if ($previousfound != null) {
				if ($comments) echo "<br> -- previous is found";
				if ($firstnext == null) {
					if ($lesson->lessonID == $lessonID) {		// peräkkäiset
						if ($comments) echo "<br> -- -- previousfound EE";
						$changelist[$lesson->lessonID] = $previousfound->sortorder;
						$changelist[$previousfound->lessonID] = $lesson->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- previousfound FF --> " . $previous->lessonID . " = " . $lesson->sortorder;
						$firstnext = $lesson;
						$changelist[$previous->lessonID] = $lesson->sortorder;
					}
				} else {
					if ($lesson->lessonID == $lessonID) {		// siirrettävä löytynyt
						if ($comments) echo "<br> -- -- previousfound GG --> " . $previousfound->lessonID . " = " . $lesson->sortorder;
						$changelist[$previous->lessonID] = $lesson->sortorder;
						//$changelist[$previousfound->lessonID] = $firstnext->sortorder;
						if ($comments) echo "<br> -- -- previousfound GG --> " . $lessonID . " = " . $previousfound->sortorder;
						$changelist[$lesson->lessonID] = $previousfound->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- previousfound HH - " . $previous->sortorder;
						$changelist[$previous->lessonID] = $lesson->sortorder;
					}
				}
			}
				
			
			if (($previousfound == null) && ($lessonfound == null)) {
				if ($comments) echo "<br> -- none yet found...";
				if ($lesson->lessonID == $lessonID) {
					$lessonfound = $lesson;
					if ($comments) echo "<br> -- lessonfound start - " . $lesson->lessonID;
				}
				if ($lesson->lessonID == $previousID) {
					$previousfound = $lesson;
					if ($comments) echo "<br> -- previousfound start - " . $lesson->lessonID;
				}
			}
			
			$previous = $lesson;
			if ($comments) echo "<br> -- -- nextprev  - " . $previous->lessonID;
			if ($end == true) break;
		}
		
		if ($comments) echo "<br><br>Found...";
		foreach($changelist as $lessonID => $sortorder) {
			if ($comments) echo "<br>" . $lessonID . " -- " . $sortorder;
		}
		
		if ($end == true) {			// sekä lessonID, että previousID löytynyt, päivitetään
			if ($comments) echo "<br>End true, update";
			
			foreach($changelist as $lessonID => $sortorder) {
				if ($comments) echo "<br>" . $lessonID . " -- " . $sortorder;
				$values = array();
				$values['Sortorder'] = $sortorder;
				$success = Table::updateRow("worder_lessons", $values, $lessonID);
			}
		} else {
			if ($comments) echo "<br>Endi on false";
		}
		if ($comments) echo "<br>Finnish";
		if (!$comments) redirecttotal('worder/lessons/showlessons',null);
	}
	
	
	public function lessonworddragdropAction() {
	
		$comments = false;
	
		if ($comments) echo "<br>lessonworddragdropAction";
	
		$languageID = $_GET['languageID'];
		$lessonID = $_GET['lessonID'];
		$conceptID = $_GET['conceptID'];
		$previousID = $_GET['previousID'];
	
		$concepts = Table::load('worder_lessonconcepts', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID . " ORDER BY Sortorder");
	
		$previousfound = null;
		$conceptfound = null;
		$changelist = array();		// id, sortID
		$previous = null;
		$firstnext = null;
		$end = false;
		$rowlookup= array();
	
		if ($comments) echo "<br>conceptcount - " . count($concepts);
		foreach($concepts as $index => $concept) {
			$rowlookup[$concept->conceptID] = $concept->rowID;
							
			if ($comments) echo "<br>Processing concept - " . $concept->conceptID;
	
			if ($conceptfound != null) {
				if ($comments) echo "<br> -- concept is found";
				if ($firstnext == null) {
					if ($concept->conceptID == $previousID) {		// peräkkäiset
						if ($comments) echo "<br> -- -- conceptfound AA";
						$changelist[$concept->conceptID] = $conceptfound->sortorder;
						$changelist[$conceptfound->conceptID] = $concept->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- conceptfound BB";
						$firstnext = $concept;
						$changelist[$concept->conceptID] = $conceptfound->sortorder;
					}
				} else {
					if ($concept->conceptID == $previousID) {
						if ($comments) echo "<br> -- -- conceptfound CC";
						$changelist[$concept->conceptID] = $previous->sortorder;
						$changelist[$conceptfound->conceptID] = $concept->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- conceptfound DD";
						$changelist[$concept->conceptID] = $previous->sortorder;
					}
				}
			}
	
			if ($previousfound != null) {
				if ($comments) echo "<br> -- previous is found";
				if ($firstnext == null) {
					if ($concept->conceptID == $conceptID) {		// peräkkäiset
						if ($comments) echo "<br> -- -- previousfound EE";
						$changelist[$concept->conceptID] = $previousfound->sortorder;
						$changelist[$previousfound->conceptID] = $concept->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- previousfound FF --> " . $previous->conceptID . " = " . $concept->sortorder;
						$firstnext = $concept;
						$changelist[$previous->conceptID] = $concept->sortorder;
					}
				} else {
					if ($concept->conceptID == $conceptID) {		// siirrettävä löytynyt
						if ($comments) echo "<br> -- -- previousfound GG --> " . $previousfound->conceptID . " = " . $concept->sortorder;
						$changelist[$previous->conceptID] = $concept->sortorder;
						//$changelist[$previousfound->conceptID] = $firstnext->sortorder;
						if ($comments) echo "<br> -- -- previousfound GG --> " . $conceptID . " = " . $previousfound->sortorder;
						$changelist[$concept->conceptID] = $previousfound->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- previousfound HH";
						$changelist[$concept->conceptID] = $previous->sortorder;
					}
				}
			}
	
				
			if (($previousfound == null) && ($conceptfound == null)) {
				if ($comments) echo "<br> -- none yet found...";
				if ($concept->conceptID == $conceptID) {
					$conceptfound = $concept;
					if ($comments) echo "<br> -- conceptfound start - " . $concept->conceptID;
				}
				if ($concept->conceptID == $previousID) {
					$previousfound = $concept;
					if ($comments) echo "<br> -- previousfound start - " . $concept->conceptID;
				}
			}
				
			$previous = $concept;
			if ($end == true) break;
		}
	
		if ($comments) echo "<br><br>Found...";
		foreach($changelist as $conceptID => $sortorder) {
			if ($comments) echo "<br>" . $conceptID . " -- " . $sortorder;
		}
	
		if ($end == true) {			// sekä conceptID, että previousID löytynyt, päivitetään
			if ($comments) echo "<br>End true, update";

			foreach($changelist as $conceptID => $sortorder) {
				//if ($comments) echo "<br>" . $conceptID . " -- " . $sortorder;
				$values = array();
				$rowID = $rowlookup[$conceptID];
				if ($comments) echo "<br>" . $conceptID . " -- " . $sortorder . " (rowID:" . $rowID . ")";
				$values['Sortorder'] = $sortorder;
				$success = Table::updateRow("worder_lessonconcepts", $values, $rowID);
			}
		} else {
			if ($comments) echo "<br>Endi on false";
		}
		
		if ($comments) echo "<br>Finnish";
		if (!$comments) redirecttotal('worder/lessons/showlesson&id=' . $lessonID,null);
	}
	
	
	
	/**
	 * Olettaa, että items taulu on aiemmassa järjestyksessä, josta sortvariablen mukaan muodostetaan 
	 * päivitettävät itemit lista. Palauttaa päivitettävän arrayn, jossa id-numero avaimena ja uusi sort-arvo
	 * arvona.
	 * 
	 * 
	 * @param int $items
	 * @param int $keyvariable
	 * @param int $sortvariable
	 * @param int $currentID
	 * @param int $previousID
	 */
	private static function createSortUpdateArray($items, $keyvariable, $sortvariable, $currentID, $previousID) {
		
		
		// palauttaa update taulun array[keyvariable] = $sortvariable...
	}
	
		
	public function lessonobjectivedragdropAction() {
	
		$comments = true;
	
		if ($comments) echo "<br>lessonworddragdropAction";
	
		$languageID = $_GET['languageID'];
		$lessonID = $_GET['lessonID'];
		$currentID = $_GET['currentID'];
		$previousID = $_GET['previousID'];

		$items = Table::load('worder_lessonobjectivelinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID . " ORDER BY Sortorder");
		
		$previousfound = null;
		$founditem = null;
		$changelist = array();		// id, sortID
		$previous = null;
		$firstnext = null;
		$end = false;
		$rowlookup = array();
	
		//$keyvariable = 'rowID';
		//$sortvariable = 'sortorder';
				
		if ($comments) echo "<br>item - " . count($items);
		foreach($items as $index => $current) {
			//$rowlookup[$item->rowID] = $item->sortorder;
				
			if ($comments) echo "<br>Processing item - " . $current->rowID;
	
			if ($founditem != null) {
				if ($comments) echo "<br> -- item is found";
				if ($firstnext == null) {
					if ($current->rowID == $previousID) {		// peräkkäiset
						if ($comments) echo "<br> -- -- itemfound AA";
						$changelist[$current->rowID] = $founditem->sortorder;
						$changelist[$founditem->rowID] = $current->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- itemfound BB";
						$firstnext = $current;
						$changelist[$current->rowID] = $founditem->sortorder;
					}
				} else {
					if ($current->rowID == $previousID) {
						if ($comments) echo "<br> -- -- itemfound CC";
						$changelist[$current->rowID] = $previous->sortorder;
						$changelist[$founditem->rowID] = $current->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- itemfound DD";
						$changelist[$current->rowID] = $previous->sortorder;
					}
				}
			}
	
			if ($previousfound != null) {
				if ($comments) echo "<br> -- previous is found";
				if ($firstnext == null) {
					if ($current->rowID == $currentID) {		// peräkkäiset
						if ($comments) echo "<br> -- -- previousfound EE";
						$changelist[$current->rowID] = $previousfound->sortorder;
						$changelist[$previousfound->rowID] = $current->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- previousfound FF --> " . $previous->rowID . " = " . $current->sortorder;
						$firstnext = $current;
						$changelist[$current->rowID] = $previous->sortorder;
						$changelist[$previous->rowID] = $current->sortorder;
					}
				} else {
					if ($current->rowID == $currentID) {		// siirrettävä löytynyt
						if ($comments) echo "<br> -- -- previousfound GG --> " . $previousfound->rowID . " = " . $current->sortorder;
						if ($comments) echo "<br> -- -- previousfound GG --> " . $currentID . " = " . $previousfound->sortorder;
						$changelist[$previous->rowID] = $current->sortorder;
						$changelist[$current->rowID] = $previousfound->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- previousfound HH";
						$changelist[$current->rowID] = $previous->sortorder;
					}
				}
			}
	
	
			if (($previousfound == null) && ($founditem == null)) {
				if ($comments) echo "<br> -- none yet found...";
				if ($current->rowID == $currentID) {
					$founditem = $current;
					if ($comments) echo "<br> -- itemfound start - " . $current->rowID;
				}
				if ($current->rowID == $previousID) {
					$previousfound = $current;
					if ($comments) echo "<br> -- previousfound start - " . $current->rowID;
				}
			}
	
			$previous = $current;
			if ($end == true) break;
		}
	
		if ($comments) echo "<br><br>Found...";
		foreach($changelist as $rowID => $sortorder) {
			if ($comments) echo "<br>" . $rowID . " -- " . $sortorder;
		}
	
		if ($end == true) {			// sekä conceptID, että previousID löytynyt, päivitetään
			if ($comments) echo "<br>End true, update";
	
			foreach($changelist as $rowID => $sortorder) {
				//if ($comments) echo "<br>" . $conceptID . " -- " . $sortorder;
				$values = array();
				if ($comments) echo "<br>Update - " . $rowID . " -- " . $sortorder;
				$values['Sortorder'] = $sortorder;
				$success = Table::updateRow("worder_lessonobjectivelinks", $values, $rowID);
			}
		} else {
			if ($comments) echo "<br>Endi on false";
		}
		
		if ($comments) echo "<br>Finnish";
		//if (!$comments) redirecttotal('worder/lessons/showlesson&id=' . $lessonID,null);
	}
	
	
	
	public function wordsresortAction() {
		
		$lessonID = $_GET['lessonID'];
		$comments = false;
		
		$lessonconceptlinks = Table::load("worder_lessonconcepts", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID . " ORDER BY RowID");
		$sortlist = array();
		foreach($lessonconceptlinks as $index => $link) {
			$sortlist[$link->rowID] = $link->rowID;
		}
		
		global $mysqli;
		foreach($sortlist as $index => $rowID) {
			$sql = "UPDATE worder_lessonconcepts SET Sortorder='" . $rowID . "' WHERE RowID=" . $rowID . "";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
		}
		if (!$comments) redirecttotal('worder/lessons/showlesson&id=' . $lessonID,null);
	}
	
	
	
	public function lessondownAction() {
		
		$comments = false;
		if ($comments) echo "<br>lessondownAction";
		
		$identifier = explode('-',$_GET['id']);
		$parentID = $identifier[0];
		$lessonID = $identifier[1];
		$lessonplanID = getModuleSessionVar('lessonplanID',0);
		
		if ($comments) echo "<br>ParentID - " . $parentID;
		if ($comments) echo "<br>LessonID - " . $lessonID;
		if ($comments) echo "<br>LessonplanID - " . $lessonplanID;
		
		$lessonlinks = Table::load('worder_lessonlinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ParentID=" . $parentID . " AND LessonplanID=" . $lessonplanID . " ORDER BY Sortorder DESC");
		
		$previousID = -1;
		$previoussort = -1;
		$currentsort = -1;
		$currentlinkID = -1;
		$previouslinkID = -1;
		foreach($lessonlinks as $index => $link) {
			if ($link->lessonID != $lessonID) {
				$previousID = $link->lessonID;
				$previoussort = $link->sortorder;
				$previouslinkID = $link->linkID;
			} else {
				$currentlinkID = $link->linkID;
				$currentsort = $link->sortorder;
				break;
			}
		}
			
		if ($previousID == -1) {
			if ($comments) echo "<br>Already up";
			if (!$comments) redirecttotal('worder/lessons/showhierarchy',null);
			exit;
		}
			
		//echo "<br>PreviousID - " . $previousID . " - " . $previoussort;
			
		$values = array();
		$values['Sortorder'] = $previoussort;
		if ($comments) echo "<br>UPDATE - " . $currentlinkID . " - " . $previoussort;
		$success = Table::updateRow("worder_lessonlinks", $values, $currentlinkID, true);
			
		$values = array();
		$values['Sortorder'] = $currentsort;
		if ($comments) echo "<br>UPDATE - " . $previouslinkID . " - " . $currentsort;
		$success = Table::updateRow("worder_lessonlinks", $values, $previouslinkID, true);
		
		if (!$comments) redirecttotal('worder/lessons/showhierarchy',null);
	}
	

	public function insertlessonprerequisiteAction() {

		$comments = false;
		
		if ($comments) echo "<br>ParentID - " . $_GET['parentID'];
		if ($comments) echo "<br>LessonID - " . $_GET['lessonID'];
		$lessonplanID = getModuleSessionVar('lessonplanID',0);
		$languageID = getModuleSessionVar('languageID',0);
		
		$lessonID = $_GET['lessonID'];
		
		$values['LessonID'] = $lessonID;
		$values['ParentID'] = $_GET['parentID'];
		$values['LessonplanID'] = $lessonplanID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['LanguageID'] = $languageID;
		$success = Table::addRow("worder_lessonlinks", $values);
		
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
	}
	
	
	
	
	
	
	
	
	public function deactivatelanguageAction() {
		
		$languageID = $_GET['languageID'];
		$lessonID = 0;
		if (isset($_GET['lessonID'])) $lessonID = $_GET['lessonID'];
		
		$activelanguages = getModuleSessionVar('activelanguages','');
		//echo "<br>session - " . $activelanguages;
		if ($activelanguages != '') {
			$langs = explode(":",$activelanguages);
			$langstr = "";
			$found = false;
			$first = false;
			foreach($langs as $index => $value) {
				//echo "<br>" . $index . " -> " . $value;
				if ($value == $languageID) {
					$found = true;
				} else {
					if ($value != "") {
						if ($first == false) {
							$langstr = $value;
							$first = true;
						} else {
							$langstr = $langstr . ":" . $value;
						}
					}
				}
			}
		}
		setModuleSessionVar('activelanguages', $langstr);
		//echo "<br>Language str - " . $langstr;		
		if ($lessonID == 0) {
			redirecttotal('worder/lessons/showlessons',null);
		} else {
			redirecttotal('worder/lessons/showlesson&id=' . $lessonID,null);
		}
	}
	
	
	public function activatelanguageAction() {
		
		$languageID = $_GET['languageID'];
		$lessonID = $_GET['lessonID'];
		
		
		$activelanguages = getModuleSessionVar('activelanguages','');
		//echo "<br>session - " . $activelanguages;
		if ($activelanguages != '') {
			$langs = explode(":",$activelanguages);
			$langstr = "";
			$found = false;
			$first = false;
			foreach($langs as $index => $value) {
				//echo "<br>" . $index . " -> " . $value;
				if ($first == false) {
					//echo "<br>First";
					$langstr = $value;
				} else {
					//echo "<br>second";
					$langstr = $langstr . ":" . $value;
				}
				if ($value == $languageID) $found = true;
				$first = true;
			}
			if ($found == false) {
				//echo "<br>Foundfalse";
				if ($first == false) {
					$langstr = $languageID;
				} else {
					$langstr = $langstr . ":" . $languageID;
				}
			}
		} else {
			$langstr = $languageID;
		}
		setModuleSessionVar('activelanguages', $langstr);
		//echo "<br>Language str - " . $langstr;
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID,null);
	}

	

	public function removesentenceAction() {

		$lessonID = $_GET['lessonID'];
		$sentenceID = $_GET['id'];

		$links = Table::load('worder_lessonsentencelinks',' WHERE LessonID=' . $lessonID . ' AND SentenceID=' . $sentenceID);
		
		foreach($links as $index => $link) {
			$success = Table::deleteRow("worder_lessonsentencelinks", $link->rowID);
			$success = Table::deleteRowsWhere("worder_objectivesentencechecks","WHERE SentenceID=" . $sentenceID. " AND ObjectiveID=" . $link->objectiveID);
		}
		
		//$success = Table::deleteRowsWhere('worder_lessonsentencelinks',' WHERE LessonID=' . $lessonID . ' AND SentenceID=' . $sentenceID);
		// TODO: pitäisi poistaa myös checkit taulusta objectivesentencechecks...
		//$success = Table::deleteRowsWhere('worder_lessonsentencelinks',' WHERE LessonID=' . $lessonID . ' AND SentenceID=' . $sentenceID);
		// TODO: mietinnässä pitäisikö itse objective uncheckata myös, aina kun muutoksia... 
		
		
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
	}
	

	

	public function insertlessonAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['LanguageID'] = $_GET['languageID'];
		$values['Level'] = 50;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$lessonID = Table::addRow("worder_lessons", $values);
		
		
		
		redirecttotal('worder/lessons/showlessons',null);
	}
	
		

	public function insertlessondataAction() {
	
		$lessonID =  $_GET['lessonID'];
		$languageID =  $_GET['languageID'];
		$name =  $_GET['name'];
		$rulesetID =  $_GET['setID'];
	
		$lessondata = Table::load("worder_lessondata", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID . " AND LanguageID=" . $languageID);
		if (count($lessondata) > 0) {
			echo "<br>Lessonissa on jo lisättynä language " . $languageID;
			exit;
		}
	
		$values = array();
		$values['LessonID'] = $lessonID;
		$values['Name'] = $name;
		$values['LanguageID'] = $languageID;
		$values['RulesetID'] = $rulesetID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_lessondata", $values, false);
	
		$this->updateLessonName($lessonID);
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
		
		/*
		$lessondata = Table::load("worder_lessondata", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
		$namelist = array();
		foreach($lessondata as $index => $dataline) {
			$namelist[$dataline->languageID] = $dataline->name;
		}
		
		// Pitäisi päivittää lessonin multilangstring name...
		//$lesson = Table::loadRow("worder_lessons", $lessonID);
		//$namelist = parseMultilangArray($lesson->name,1);
		//$namelist[$languageID] = $name;
		$values = array();
		$values['Name'] = createMultilangString($namelist);
		echo "<br>newmultilang - " . createMultilangString($namelist);
		$success = Table::updateRow("worder_lessons", $values, $lessonID);
		*/
		//redirecttotal('worder/lessons/showlesson&id=' . $lessonID,null);
	
	}
	

	
	// Tämä on sama kuin objectiveconroller.checklessonobjective, vain redirecti on eri. pitäisikö yhdistää?
	public function checklessonobjectiveAction() {
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
		if (!$comments) redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
	}
		

	// Tämä on sama kuin objectiveconroller.uncheckobjectivelesson, vain redirecti on eri. pitäisikö yhdistää?
	public function unchecklessonobjectiveAction() {
		$comments = false;
		$objectiveID = $_GET['objectiveID'];
		$lessonID = $_GET['lessonID'];
		$languageID = $_GET['languageID'];
		$success = Table::deleteRowsWhere('worder_objectivelessonchecks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objectiveID . " AND LessonID=" . $lessonID . " AND LanguageID=" . $languageID);
		if (!$comments) redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
	}
	

	// vastaava versio objectivecontrollerissa
	// tämä voitaisiin kyllä hoitaa rowID-parametrillakin varmaan
	public function unchecklessonsentenceAction() {
	
		$comments = false;
		$languageID = $_GET['languageID'];
		$lessonID = $_GET['lessonID'];
				
		$linkID = $_GET['linkID'];
		$link = Table::loadRow("worder_lessonsentencelinks", $linkID);
	
		echo "<br>Link->sentenceID - " . $link->sentenceID;
		echo "<br>lessonID - " . $lessonID;
		echo "<br>languageID - " . $languageID;
	
		$success = Table::deleteRowsWhere('worder_objectivesentencechecks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $link->sentenceID . " AND ObjectiveID=" . $link->objectiveID . " AND LanguageID=" . $languageID);
		if (!$comments) redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
	}
	
	
	

	public function checklessonsentenceAction() {
	
		$comments = false;
		$lessonID = $_GET['lessonID'];
		$languageID = $_GET['languageID'];
	
		$linkID = $_GET['linkID'];
		$link = Table::loadRow("worder_lessonsentencelinks", $linkID);
	
		$values = array();
		$values['ObjectiveID'] = $link->objectiveID;
		$values['SentenceID'] = $link->sentenceID;
		$values['LanguageID'] = $languageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['Checked'] = 1;
		$values['Checkdate'] = date('Y-m-d H:i:s');;
		$rowID = Table::addRow("worder_objectivesentencechecks", $values);
		
		if (!$comments) redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
	}
	
	
	
	

	public function updatelessondataAction() {
	
		$lessonID = $_GET['lessonID'];
		$rowID = $_GET['id'];
		$values['Name'] = $_GET['name'];
		$success = Table::updateRow("worder_lessondata", $values, $rowID);
		
		$this->updateLessonName($lessonID);
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
	}
	
	
	
	
	
	public function insertlessonrequirementAction() {
	
		$lessonID = $_GET['lessonID'];
		$values = array();
		$values['LessonID'] = $lessonID;
		$values['LanguageID'] = $_GET['languageID'];
		$values['InflectionsetID'] = $_GET['inflectionsetID'];
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_lessonrequirements", $values);
	
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID,null);
	}
	
	
	
	
	
	

	public function insertlessonlevelAction() {
	
		$lessonID = $_GET['lessonID'];
		$values = array();
		$values['LessonID'] = $lessonID;
		$values['Level'] = $_GET['level'];
		$values['Experience'] = $_GET['experience'];
		$values['GrammarID'] = $_SESSION['grammarID'];
		
		$values['Stage1weight'] = $_GET['stage1weight'];
		$values['Stage2weight'] = $_GET['stage2weight'];
		$values['Stage3weight'] = $_GET['stage3weight'];
		$values['Stage4weight'] = $_GET['stage4weight'];
		
		$values['Stage1recap'] = $_GET['stage1recap'];
		$values['Stage2recap'] = $_GET['stage2recap'];
		$values['Stage3recap'] = $_GET['stage3recap'];
		$values['Stage4recap'] = $_GET['stage4recap'];
		
		$values['Stage1newcount'] = $_GET['stage1newcount'];
		$values['Stage2newcount'] = $_GET['stage2newcount'];
		$values['Stage3newcount'] = $_GET['stage3newcount'];
		$values['Stage4newcount'] = $_GET['stage4newcount'];
		
		$values['Paidcontent'] = $_GET['paidcontent'];
		
		$rowID = Table::addRow("worder_lessonlevels", $values);
		
		
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID,null);
	}
	
	
	private function getDifficultylevels() {
		$rows = array();
		$row = new Row();
		$row->level = 1;
		$row->name = "Very easy";
		$rows[1] = $row;
		
		$row = new Row();
		$row->level = 2;
		$row->name = "Easy";
		$rows[2] = $row;
		
		$row = new Row();
		$row->level = 3;
		$row->name = "Normal";
		$rows[3] = $row;

		$row = new Row();
		$row->level = 4;
		$row->name = "Hard";
		$rows[4] = $row;
		
		$row = new Row();
		$row->level = 5;
		$row->name = "Very hard";
		$rows[5] = $row;
		
		return $rows;
	}
	

	public function updatelessonlevelAction() {
	
		$rowID = $_GET['id'];
		$lessonID = $_GET['lessonID'];
		
		$values = array();
		$values['Level'] = $_GET['level'];
		$values['Experience'] = $_GET['experience'];
		
		$values['Stage1weight'] = $_GET['stage1weight'];
		$values['Stage2weight'] = $_GET['stage2weight'];
		$values['Stage3weight'] = $_GET['stage3weight'];
		$values['Stage4weight'] = $_GET['stage4weight'];
		
		$values['Stage1recap'] = $_GET['stage1recap'];
		$values['Stage2recap'] = $_GET['stage2recap'];
		$values['Stage3recap'] = $_GET['stage3recap'];
		$values['Stage4recap'] = $_GET['stage4recap'];
		
		$values['Stage1newcount'] = $_GET['stage1newcount'];
		$values['Stage2newcount'] = $_GET['stage2newcount'];
		$values['Stage3newcount'] = $_GET['stage3newcount'];
		$values['Stage4newcount'] = $_GET['stage4newcount'];
		
		$values['Paidcontent'] = $_GET['paidcontent'];
		
		$success = Table::updateRow("worder_lessonlevels", $values, $rowID, true);
	
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
	}
	
	
	
	
	
	
	public function insertconceptAction() {
	
		$comments = false;
		
		$conceptID = $_GET['conceptID'];
		$lessonID = $_GET['lessonID'];
	
		$lesson = Table::loadRow('worder_lessons',$lessonID);
		
		
		$values = array();
		$values['ConceptID'] = $conceptID;
		$values['LessonID'] = $lessonID;
		$values['LanguageID'] = $lesson->languageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_lessonconcepts", $values, $comments);
		
		if ($comments == false) redirecttotal('worder/lessons/showlesson&id=' . $lessonID,null);
	}
	

	
	public function removewordfromlessonAction() {
		$lessonID = $_GET['lessonID'];
		$conceptID = $_GET['id'];
		$success = Table::deleteRowsWhere('worder_lessonconcepts',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID . " AND ConceptID=" . $conceptID);
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
	}
	

	
	public function removelessonAction() {
		$lessonID = $_GET['lessonID'];

		$lessonlinks = Table::load('worder_lessonlinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
		if (count($lessonlinks) > 0) {
			echo "<br>Lessonlinkkejä found, ei voida poistaa";
			exit;
		}		

		$lessonlinks = Table::load('worder_lessonlinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
		if (count($lessonlinks) > 0) {
			echo "<br>Lessonlinkkejä found, ei voida poistaa";
			exit;
		}
		
		$lessonrequirements = Table::load('worder_lessonrequirements', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
		if (count($lessonrequirements) > 0) {
			echo "<br>lessonrequirements found, ei voida poistaa";
			exit;
		}
		
		$lessonobjectives = Table::load('worder_lessonobjectivelinks', "WHERE LessonID=" . $lessonID . " AND GrammarID=" . $_SESSION['grammarID']);
		if (count($lessonobjectives) > 0) {
			echo "<br>lessonobjectivelinks found, ei voida poistaa";
			exit;
		}
		
		$lessonrules = Table::load('worder_lessonrules', "WHERE GrammarID=" . $_SESSION['grammarID']);
		if (count($lessonrules) > 0) {
			echo "<br>lessonrules found, ei voida poistaa";
			exit;
		}
		
		$lessonwords = Table::load('worder_lessonconcepts', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
		if (count($lessonwords) > 0) {
			echo "<br>lessonconcepts found, ei voida poistaa";
			exit;
		}
		

		$lessonlevels = Table::load('worder_lessonlevels', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
		if (count($lessonlevels) > 0) {
			echo "<br>lessonlevels found, ei voida poistaa";
			exit;
		}

		
		$lessondata = Table::load('worder_lessondata', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
		if (count($lessondata) > 0) {
			echo "<br>lessondata found, ei voida poistaa";
			exit;
		}
		
		
		$sentencelinks = Table::load("worder_lessonsentencelinks", "WHERE LessonID=" . $lessonID . " AND GrammarID=" . $_SESSION['grammarID']);
		if (count($sentencelinks) > 0) {
			echo "<br>lesson sentencelinks  found, ei voida poistaa";
			exit;
		}
		
		
		
		
		$success = Table::deleteRowsWhere('worder_lessons',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
		redirecttotal('worder/lessons/showlessons', null);
	}
	
	
	public function removeprerequisitelessonAction() {
		$lessonID = $_GET['lessonID'];
		$prerequisiteID = $_GET['id'];
		$success = Table::deleteRowsWhere('worder_lessonlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID . " AND ParentID=" . $prerequisiteID);
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
	}
		
		
		
	public function removelessonobjectiveAction() {
		
		$objectiveID = $_GET['id'];
		$lessonID = $_GET['lessonID'];
		$success = Table::deleteRowsWhere('worder_lessonobjectivelinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID . " AND ObjectiveID=" . $objectiveID);
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
	}
	

	public function updatelessonAction() {
	
		$lessonID = $_GET['id'];
		$values = array();
		
		/*
		if (isset($_GET['name'])) $values['Name'] = $_GET['name'];
		
		$languages = Table::load('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$namestr = "";
		foreach($languages as $languageID => $language) {
			$var = "name" . $languageID;
			if (isset($_GET[$var])) {
				$namestr = $namestr . "[" . $languageID . "]" . $_GET[$var];
			}
		}
		$values['Name'] = $namestr;		
		*/
		
		$values['Description'] = $_GET['description'];
		$values['Shortdesc'] = $_GET['shortdesc'];
		//$values['Level'] = $_GET['level'];
		//$values['Active'] = $_GET['active'];
		//$values['RulesetID'] = $_GET['rulesetID'];
		//$values['Difficultylevel'] = $_GET['difficultylevel'];
		
		$success = Table::updateRow("worder_lessons", $values, $lessonID, true);
	
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
	
	

	
	
	public function removelessondataAction() {
		
		$lessonID = $_GET['lessonID'];
		$rowID = $_GET['id'];
		$success = Table::deleteRowsWhere('worder_lessondata',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID . " AND RowID=" . $rowID);
		//redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
		
		$this->updateLessonName($lessonID);
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
		
		/*
		$lessondata = Table::load("worder_lessondata", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
		$namelist = array();
		foreach($lessondata as $index => $dataline) {
			$namelist[$dataline->languageID] = $dataline->name;
		}
		$values = array();
		$values['Name'] = createMultilangString($namelist);
		$success = Table::updateRow("worder_lessons", $values, $lessonID);
		*/
		
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID,null);
	}



	public function addlessonobjectiveAction() {
		
		$lessonID = $_GET['lessonID'];
		$lesson = Table::loadRow("worder_lessons", $lessonID);
		
		$values = array();
		$values['LessonID'] = $lessonID;
		$values['LanguageID'] = $lesson->languageID;
		$values['ObjectiveID'] = $_GET['objectiveID'];
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_lessonobjectivelinks", $values);
		
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID, null);
	}
	
	
	
	public function generateobjectivesentenceAction() {
		
		$comments = false;
		
		if (isset($_GET['comments'])) $comments = true;
		
		include_once('./modules/worder/_classes/featurestructure.class.php');
		
		$languageID = 1;
		if (isset($_GET['languageID'])) $languageID = $_GET['languageID'];
		
		
		if ($comments) echo "<br>Generating Objective Sentence";
		$objectiveID = $_GET['objectiveID'];
		if ($comments) echo "<br> - objectiveID = " . $objectiveID;
		$lessonID = $_GET['lessonID'];
		if ($comments) echo "<br> - LessonID = " . $lessonID;
		
		$objective = Table::loadRow("worder_objectives", $objectiveID);
		if ($comments) echo "<br>";
		if ($comments) echo "<br>ObjectiveID = " . $objective->objectiveID;
		
		
		// if (count($objectivearguments) > 0) {
		$lesson = Table::loadRow("worder_lessons", $lessonID);
		$arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);			// TODO: target ja source kielen featuret ehkä pelkästään
		$components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID']);
		// onkohan objektive aina kielikohtainen? todennäköisesti kyllä.		
		$wordclassfeatures = Table::load('worder_wordclassfeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
				
		FeatureStructure::$wordclasses = $wordclasses;
		FeatureStructure::$features = $features;
		FeatureStructure::$components = $components;
		FeatureStructure::$arguments = $arguments;
		
		$semanticlinks = array();
		foreach($features as $index => $feature) {
			if ($feature->languageID == $languageID) {
				$semanticlinks[$feature->semanticlinkID] = $feature->featureID;
			}
		}
		
		// Haetaan lessonin conceptit
		if (isset($_GET['include'])) {
			$lessonlinks = Table::load("worder_lessonlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
			$lessonlist = array();
			$lessonlist[$lessonID] = $lessonID;
			foreach($lessonlinks as $index => $link) {
				$lessonlist[$link->parentID] = $link->parentID;
			}
			$lessonconceptlinks = Table::loadWhereInArray("worder_lessonconcepts", 'lessonID', $lessonlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		} else {
			$lessonconceptlinks = Table::load("worder_lessonconcepts", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);
		}
		$conceptlist = array();
		foreach($lessonconceptlinks as $index => $link) {
			$conceptlist[$link->conceptID] = $link->conceptID;
		}
		$concepts = Table::loadWhereInArray('worder_concepts','conceptID',$conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);

		// Haetaan kaikille concepts oletus word
		$wordlinks = Table::loadWhereInArray('worder_conceptwordlinks','conceptID',$conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND Defaultword=1");
		$wordlist = array();
		$conceptwordlist = array();
		$wordconceptlist = array();
		foreach($wordlinks as $index => $link) {
			$wordlist[$link->wordID] = $link->wordID;
			$conceptwordlist[$link->conceptID] = $link->wordID;
			$wordconceptlist[$link->wordID] = $link->conceptID;
			if ($comments) echo "<br>Conceptwordlinks: conceptID=" . $link->conceptID . ", wordID=" . $link->wordID;
		}
		$words = Table::loadWhereInArray('worder_words','wordID',$wordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		foreach($words as $wordID => $word) {
			if ($comments) echo "<br>Loop words - " . $word->lemma;
			if ($word->features != "") {
				$featurelist = explode("|", $word->features);
				$wordfeatures = array();
				foreach($featurelist as $index => $featurestr) {
					$parts = explode(":", $featurestr);
					$wordfeatures[$parts[0]] = $parts[1];
					if ($comments) {
						$feature = $features[$parts[0]];
						$value = $features[$parts[1]];
						if ($comments) echo "<br> -- feature found - " . $feature->name . " (" . $parts[0] . ") - " . $value->name . " (" . $parts[1] . ")";
					}
				}
				$word->featurelist = $wordfeatures;
			} else {
				// wordillä ei ole lainkaan featureita				
			}
		}
		
		$selectedconcepts = array();
		// Filtteröidään ensin sanaluokan mukaan
		if ($comments) echo "<br><br>";
		if ($comments) echo "<br>Filtteröidään sanaluokan mukaan...";
		foreach($concepts as $index => $concept) {
			if (isset($conceptwordlist[$concept->conceptID])) {
				$wordID = $conceptwordlist[$concept->conceptID];
				$word = $words[$wordID];
				$concept->lemma = $word->lemma;
				$concept->wordID = $word->wordID;
				$concept->word = $word;
				if ($concept->wordclassID == $objective->wordclassID) {
					$selectedconcepts[$concept->conceptID] = $concept;
					if ($comments) echo "<br> -- compatible concept found - " . $concept->name;
				}
			}
		}
		
		
		// Heataan wordeille featuret... Voidaan rajoittaa ehkä Objectiven featureen, mutta niitä ei tiedetä...
		// $objectiveitems = Table::load('worder_inflectionsetitems', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND InflectionsetID=" . $objective->objectiveID);
		// TODO: inflectionsetitemssiä ei tarkisteta missään
		if ($comments) echo "<br><br>";
		if ($comments) echo "<br>Filtteröidään inflectionsets mukaan...";
		$requiredinflectionsets = $this->getObjectiveInflectionsets($objective);
		$selectedconcepts2 = array();
		if (count($requiredinflectionsets) > 0) {
			
			$inflectionsetitems = Table::loadWhereInArray('worder_inflectionsetitems','inflectionsetID',$requiredinflectionsets, "WHERE GrammarID=" . $_SESSION['grammarID']);
			
			foreach($selectedconcepts as $conceptID => $concept) {
				
				$word = $words[$concept->wordID];
				if ($comments) echo "<br> -- checking word - " . $word->lemma. " (wordID:" . $word->wordID . ")";
				$wordaccepted = false;
				foreach($requiredinflectionsets as $index => $inflectionsetID) {
					if ($comments) echo "<br> -- -- Required inflectionsetID - " . $inflectionsetID;
					foreach($inflectionsetitems as $rowID => $inflectionsetitem) {
						//echo "<br> -- -- -- features - " . $word->features;
						if ($inflectionsetitem->inflectionsetID == $inflectionsetID) {
							$inflectionsetitemfeature = $features[$inflectionsetitem->parentfeatureID];
							$inflectionsetitemfeaturevalue = $features[$inflectionsetitem->featureID];
							//echo "<br> -- -- -- inflectionsetitem - " . $inflectionsetitemfeature->name . " (" . $inflectionsetitem->parentfeatureID . ") - " . $inflectionsetitemfeaturevalue->name . " (" . $inflectionsetitem->featureID . ")";
							
							if (isset($word->featurelist[$inflectionsetitem->parentfeatureID])) {
								$parentfeature = $features[$inflectionsetitem->parentfeatureID];
								//echo "<br> -- -- -- -- feature found " . $parentfeature->name;
								if ($word->featurelist[$inflectionsetitem->parentfeatureID] == $inflectionsetitem->featureID) {
									//echo "<br> -- -- -- -- feature found in word, accepted";
									$wordaccepted = true;
								}
							}
						}
						if ($wordaccepted == true) break;
					}
					if ($wordaccepted == true) break;
				}
				if ($wordaccepted == false) {
					//$conceptID = $wordconceptlist[$word->wordID];
					//echo "<br> -- add - " . $conceptID;
					//unset($selectedconcepts[$conceptID]);
				} else {
					$selectedconcepts2[$concept->conceptID] = $concept;
					// echo "<br> -- inflectionset compatible word found - " . $word->lemma;					
				}
			}
		} else {
			if ($comments) echo "<br>No inflectionset requirements, acccept all";
			$selectedconcepts2 = $selectedconcepts;
		}
		
		if ($comments) echo "<br><br>Selectedconcepts, after inflectionsets check ...";
		foreach($selectedconcepts2 as $conceptID => $concept) {
			if ($comments) echo "<br> -- " . $concept->name . " - " . $concept->lemma;
		}

		// Filtteröidään sallitut conceptit componenttien avulla
		// - componentti on objectiveen hardkoodattu sanojen valintaa rajoittava kriteeri
		// - muutettu niin, että useammasta componentista riittää, että yksi löytyy
		if ($comments) echo "<br><br>";
		if ($comments) echo "<br>Filtteröidään componenttien mukaan 1...";
		$requiredcomponents = $this->getObjectiveComponents($objective);
		$componentwords = array();
		$selectedconcepts3 = array();
		if ((count($selectedconcepts2) > 0) && (count($requiredcomponents) > 0)) {
			foreach($selectedconcepts2 as $conceptID => $concept) {
				if ($comments) echo "<br> -- checking component concept - " . $concept->name;
				$componentitems = explode("|", $concept->components);
				$allcomponentsfound = true;
				foreach($requiredcomponents as $index => $componentstring) {
					$requiredcomponentlist = explode(",", $componentstring);
					$allcomponentsfound = true;
					foreach($requiredcomponentlist as $requiredindex => $requiredcomponentID) {
						if ($comments) echo "<br> -- requiredlist component - " . $requiredindex . " - "  . $requiredcomponentID;
						if ($comments) $component = $components[$requiredcomponentID];
						if ($comments) echo "<br> -- required component - " . $component->name;
						$componentfound = false;
						foreach($componentitems as $index => $compstr) {
							if ($compstr != "") {
								$parts = explode(":", $compstr);
								if ($parts[0] == $requiredcomponentID) {
									if ($comments) echo "<br> -- -- Compatible component found - " . $concept->name .  " - " . $component->name;
									$componentfound = true;
								}
							}
						}
						if ($componentfound == false) {
							if ($comments) echo "<br> -- -- No component found - " . $concept->name .  " - " . $component->name . ", not selected";
							$allcomponentsfound = false;
							break;
						}
					}
					if ($componentfound == false) {		// minkä tahansa hyväksyvässä tämä on true
						$allcomponentsfound = false;
					}
					if ($allcomponentsfound == true) {
						if ($comments) echo "<br> -- allcomponents found - true";
						break;
					}
				}
				
				if ($allcomponentsfound == false) {			// minkä tahansa hyväksyvässä, tässä on componentfound
					//unset($selectedconcepts[$concept->conceptID]);
				} else {
					if ($comments) echo "<br> -- adding to selected";
					$selectedconcepts3[$concept->conceptID] = $concept;
				}
			}
		} else {
			$selectedconcepts3 = $selectedconcepts2;
		}
		
		if ($comments) echo "<br><br>Selectedconcepts, after components check ...";
		foreach($selectedconcepts3 as $conceptID => $concept) {
			if ($comments) echo "<br> -- " . $concept->name . " - " . $concept->lemma;
		}

		
		// Filtteröidään sallitut conceptit wordclass featurerequirementin avulla
		// - feature on löydyttävä ja sen arvon on täsmättävä
		// - Pelkästään wordclassfeaturet, jotka löydetään wordiltä. generate hoitaa inflectional featuresit
		if ($comments) echo "<br><br>";
		if ($comments) echo "<br>Filtteröidään wordclassfeatureiden mukaan...";
		$requiredwordclassfeatures = $this->getObjectiveFeatureRequirements($objective, $languageID, $features);
		if ($comments) echo "<br>... count - " . count($requiredwordclassfeatures);
		
		$componentwords = array();
		$selectedconcepts4 = array();
		foreach($selectedconcepts3 as $conceptID => $concept) {
			$word = $words[$concept->wordID];
			if ($comments) echo "<br> -- checking wordclassfeature concept - " . $concept->name . " - " . $word->lemma;
			$featureitems = explode("|", $word->features);
			if ($comments) echo "<br> -- -- featureitems - " . $word->features;
				
			$allaccepted = true;
			if ($comments) echo "<br>Requirement count - " . count($requiredwordclassfeatures);
			foreach($requiredwordclassfeatures as $neededfeatureID => $neededvalueID) {
				$neededfeature = $features[$neededfeatureID];
				$neededvalue = $features[$neededvalueID];
				if ($comments) echo "<br> -- -- required component - " . $neededfeature->name . " = " . $neededvalue->name;
				$featurefound = false;
				$compatible = true;
				foreach($featureitems as $index => $featurestr) {
					
					$parts = explode(":", $featurestr);
					$featureID = $parts[0];
					$valueID = $parts[1];
					
					if ($featureID == $neededfeatureID) {
						if ($valueID == $neededvalueID) {
							if ($comments) echo "<br> -- -- -- Compatible feature found";
							$featurefound = true;
						} else {
							$feature = $features[$featureID];
							$value = $features[$valueID];
							if ($comments) echo "<br> -- -- -- Incompatible feature found - " . $feature->name .  " - " . $value->name;
							$compatible = false;
						}
					} else {
						$feature = $features[$featureID];
						if ($comments) echo "<br> -- -- featurefound, not needed - " . $feature->name;						
					}
				}
				if ($featurefound == false) {
					$allaccepted = false;
					//break;
				}
				if ($compatible == false) {
					$allaccepted = false;
					//break;
				}
			}
			if ($allaccepted == false) {
				if ($comments) echo "<br> -- -- -- -- not all accepted";
				//unset($selectedconcepts[$concept->conceptID]);
			} else {
				$selectedconcepts4[$concept->conceptID] = $concept;
				if ($comments) echo "<br> -- -- -- -- *** accepted";
			}
		}
		
		if ($comments) {
			echo "<br><br>Selectedconcepts, after wordclassfeature check ...";
			$counter = 1;
			foreach($selectedconcepts4 as $conceptID => $concept) {
				echo "<br>" . $counter . " -- " . $concept->name . " - " . $concept->lemma;
				$counter++;
			}
		}
		
		
		if ($comments) echo "<br><br>";
		if ($comments) echo "<br>Filtteröidään argumenttien mukaan...";
		$selectedconcepts5 = array();
		$objectivearguments = $this->getObjectiveArguments($objective);
		if ($comments) echo "<br>Objectivearguments = " . $objective->arguments;
		foreach($selectedconcepts4 as $conceptID => $concept) {
			$conceptarguments = $this->getConceptArguments($concept);
			$allfound = true;
			foreach($objectivearguments as $index => $link) {
				$obarg = $arguments[$link->argumentID];
				if ($comments) echo "<br> -- checking objectiveargument " . $obarg->name;
					
				$neededargumentID = $link->argumentID;
				$found = false;
				foreach($conceptarguments as $index3 => $conceptargument) {
					$conarg = $arguments[$conceptargument->argumentID];
					if ($comments) echo "<br> -- -- checking conceptargument " . $conarg->name;
					if ($conceptargument->argumentID == $neededargumentID) {
						if ($comments) echo "<br> -- -- -- compatible argument found";
						$found = true;
					} else {
						$conarg1 = $arguments[$conceptargument->argumentID];
						$conarg2 = $arguments[$conceptargument->argumentID];
						
						if ($comments) echo "<br> -- -- -- compatible not argument found - " . $conceptargument->argumentID . " - " . $neededargumentID . " - " . $conarg1->name . " - " . $conarg2->name;
					}
				}
				if ($found == false) {
					$allfound = false;
				}
			}
			if ($allfound == false) {
				$neededargumentID = $arguments[$neededargumentID];
				if ($comments) echo "<br> -- concept not compatible because not argument " . $neededargumentID->name . " found in " . $concept->name;
			} else {
				$selectedconcepts5[] = $concept;
			}
		}
			

		if ($comments) {
			echo "<br><br>Selectedconcepts, after argument check ...";
			$counter = 1;
			foreach($selectedconcepts5 as $conceptID => $concept) {
				echo "<br>" . $counter . " -- " . $concept->name . " - " . $concept->lemma;
				$counter++;
			}
		}
		
		
		//echo "<br><br>Finished (test breakpoint)";
		//exit;
		
		if ($comments) echo "<br>";
		
		
		$compatibleconcepts = array();
		foreach($selectedconcepts5 as $conceptID => $concept) {
			$selectedwordID = $concept->wordID;
			$word = $words[$concept->wordID];
			$compatiblewords[$wordID] = $word;
			$compatibleconcepts[$conceptID] = $concept;
		}
		
		
		
		
		// Nyt meillä on compatiblewords-listassa kaikki wordit, jotka täyttävät objectiven kriteerit. 
		//  - seuraavaksi pitäisi löytää vaihtoehdot kaikkiin argumentteihin...
		$foundfeaturesfinal = array();
		
		foreach($compatibleconcepts as $conceptID => $concept) {
		
			if ($comments) echo "<br><br>Generating conceptg fs ... - " . $concept->name . " - " . $concept->lemma . " - " . $concept->conceptID;
				
			
			
			$currentfeature = new FeatureStructure($concept->name . "/" . $concept->lemma, $concept->wordclassID);
			$currentfeature->setConceptID($concept->conceptID);
					
			// Asetetaan aluksi kaikki featuret oletusarvoihin
			foreach ($wordclassfeatures as $rowID => $wordclassfeature) {
				if ($wordclassfeature->wordclassID == $concept->wordclassID) {
					if ($wordclassfeature->defaultvalueID > 0) {
						$currentfeature->addFeature($wordclassfeature->featureID, $wordclassfeature->defaultvalueID);
						//if ($comments) echo "<br> -- adding wordclassdefaultfeature - " . $wordclassfeature->featureID . " - " . $wordclassfeature->defaultvalueID;
					}
				}
			}
			
			
			// Asetetaan kyseisen conceptin wordfeaturet...
			$word = $concept->word;
			$featurelist = explode("|", $word->features);
			if ($comments) echo "<br> --- word features - " . $word->features;
			foreach($featurelist as $ii => $featurestr) {
				if ($featurestr != "") {
					$parts = explode(":",$featurestr);
					
					$featureA = $features[$parts[0]];
					if ($featureA->languageID == $languageID) {
						if ($comments) echo "<br> -- -- parts - " . $parts[0] . " vs. " . $parts[1];
						$currentfeature->addFeature($parts[0], $parts[1]);
					} else {
						if ($comments) echo "<br> -- -- non language feature - " . $parts[0] . " vs. " . $parts[1];
					}
				}
			}
			
			if ($comments) echo "<br>....compoenents adding";
			$componentlist = explode("|", $concept->components);
			if ($comments) echo "<br> --- concept components - " . $word->components;
			foreach($componentlist as $ii => $componentstr) {
				if ($componentstr != "") {
					$parts = explode(":",$componentstr);
					
					$component = $components[$parts[0]];
					if ($comments) echo "<br> -- -- component - " . $parts[0] . " vs. " . $component->name;
					$currentfeature->addComponent($parts[0]);
				}
			}
				
			// Käydään lävitse objectivessa olevat featuret joilla generointi suoritetaan
			//	- nämä ylikirjoittavat mahdolliset wordin faeturet...
			$generatefeatures = explode("|", $objective->generatefeatures);
			if ($comments) echo "<br> -- Generating features - " . $objective->generatefeatures . " (objectiveID:" . $objective->objectiveID . ")";
			foreach($generatefeatures as $index => $featurestr) {
				if ($featurestr != "") {
					$parts = explode(":", $featurestr);
					//echo "<br>Part[0] - " . $parts[0];
					if ($comments) echo "<br> -- -- adding objective feature - " . $parts[0] . " vs. " . $parts[1];
					
					$semanticfeatureID = $parts[0];
					$semanticvalueID = $parts[1];
					
					$featureID = $semanticlinks[$semanticfeatureID];
					$valueID = $semanticlinks[$semanticvalueID];
						
					if ($comments) {
						$featureA = $features[$semanticfeatureID];
						$featureV = $features[$semanticvalueID];
						echo "<br> -- -- -- adding objective feature - " . $featureA->name . " -> " . $featureV->name;
						$featureA = $features[$featureID];
						$featureV = $features[$valueID];
						echo "<br> -- -- -- xx adding objective feature - " . $featureA->name . " -> " . $featureV->name;
					}
					$currentfeature->addFeature($featureID, $valueID);
				}
			}
				
			
			
			/*
			$objectivefeatures = explode("|", $objective->features);
			foreach($objectivefeatures as $index => $featurestr) {
				if ($featurestr != "") {
					$parts = explode(":", $featurestr);
					//echo "<br>Part[0] - " . $parts[0];
					if ($comments) echo "<br> -- -- adding objective feature - " . $parts[0] . " vs. " . $parts[1];
						
					$featureA = $features[$parts[0]];
					if ($comments) echo "<br> -- -- -- adding objective feature - " . $featureA->name . " vs. " . $parts[1];
					if ($featureA->languageID == $languageID) {
						if ($comments) echo "<br> -- -- -- language match";
						$currentfeature->addFeature($parts[0], $parts[1]);
					} else {
						if ($comments) echo "<br> -- -- -- language doesn't match";
					}
				}
			}
			*/
			
			
			if ($comments) echo "<br><br>Generating all arguments... - argumentcount: " . count($objectivearguments);
			$foundstructures = array();
			
			if (count($objectivearguments) == 0) {
				$foundstructures[] = $currentfeature;
			} else {
				foreach($objectivearguments as $index => $link) {
					if ($comments) print_r($link);
					$argument = $arguments[$link->argumentID];

					
					
					if ($comments) echo "<br><br><br>";
					if ($comments) echo "<br>targetObjective - " . $argument->name . " - " . $link->objectiveID;

					// Pitää tsekata ottaako asianomainen concepti annetun argumentin parametrikseen...
					
					$argumentobjective = Table::loadRow("worder_objectives", $link->objectiveID);
						
					$featurelist = $this->generateArgumentsRecursively($argumentobjective, $languageID, $concepts, $arguments, $components, $wordclassfeatures, $features, $semanticlinks, 1, $link->level, $comments);
					if (count($featurelist) > 0) {
						//$foundfeatures[$link->argumentID] = $featurelist;

						if ($comments) echo "<br>Foundargumentfeaturestructus - " . count($featurelist);
						
						$foundcounter = 0;
						foreach($foundstructures as $index => $value) {
							$foundcounter++;
						}
						
						if ($comments) echo "<br><br>-------- Foundcounter ... " . count($foundstructures);
						if ($foundcounter == 0) {
							if ($comments) echo "<br> -- no previous found...";
							foreach($featurelist as $index => $argumentfs) {
								$copyfs = $currentfeature->getCopy();
								if ($comments) echo "<br> --- copyfeatures";
								$copyfs->setArgument($link->argumentID, $argumentfs);
								$foundstructures[] = $copyfs;
							}
						} else {
							if ($comments) echo "<br> -- previous found - " . count($foundstructures);
							$additionalfeatures = array();
							foreach($foundstructures as $index => $foundfs) {
								$counter = 0;
								foreach($featurelist as $index => $argumentfs) {
									if ($counter == 0) {
										$foundfs->setArgument($link->argumentID, $argumentfs);
										$counter++;
									} else {
										$copyfs = $foundfs->getCopy();
										$copyfs->setArgument($link->argumentID, $argumentfs);
										$additionalfeatures[] = $copyfs;
									}
								}
							}
							foreach($additionalfeatures as $index => $additionalfs) {
								$foundstructures[] = $additionalfs;
							}
						}
						//break;
						if ($comments) echo "<br><br>-------- Foundcounter ... " . count($foundstructures);
						if ($comments) echo "<br>";
						
					} else {
						if ($comments) echo "<br>No compatible arguments found for " . $argument->name;
						//exit;
					}
					
				}
			}
			
			foreach($foundstructures as $index => $fs) {
				//if (count($foundfeaturesfinal) == 0) {
					$foundfeaturesfinal[] = $fs;
				//}
			}
		}	
		
		if ($comments) {
			echo "<br><br>-------- Complete";
			$counter = 0;
			foreach($foundfeaturesfinal as $index => $featurestructure) {
				echo "<br><br> - " . $counter;
				$featurestructure->printFeatureStructureRecursive();
				$counter++;
			}
			echo "<br><br>----------------------------------------";
			echo "<br>foundcount - " . count($foundfeaturesfinal);
		}
		//exit;
		
		/*
		
		$featurestructures = array();		// tämä tulee sisältämään generoidut feature structuret.
		foreach($foundfeatures as $argumentID => $featurestructs) {
			echo "<br>arguments found - " . $argumentID;
			foreach($featurestructs as $index => $value) {
				echo "<br> -- found featurestruct - " . $index;
			}
		}
		*/
		
		//$languageID = 1;
		//$rules = Table::load("worder_rules","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . 1);
		
		$lessondata = Table::load("worder_lessondata", "WHERE GrammarID="  .$_SESSION['grammarID'] . " AND LessonID=" . $lessonID .  " AND LanguageID=" . $languageID);
		$rulesetID = 0;
		foreach($lessondata as $index => $dataline) {
			$rulesetID = $dataline->rulesetID;
		}
		
		if ($comments) echo "<br>LanguageRules --- " . $languageID;
		if ($comments) echo "<br>RulesetID --- " . $rulesetID;
		$rules = $this->loadRules($languageID, $rulesetID, $wordclasses, $features, null, $components);
		if ($comments) {
			echo "<br>rulecount - " . count($rules);
		}
		
		
		if ($comments) echo "<br><br>";
		//$comments = false;
		include_once('./modules/worder/_classes/syntaxgenerator.class.php');

		/*
		foreach($featurestructures as $index => $featurestructure) {
				
			if ($comments) echo "<br><br>";
			if ($comments) $featurestructure->printFeatureStructure();
		
		}
		if ($comments) echo "<br><br>----------------------------------------";
			
		exit;
		*/
		
		
		$resultstrings = array();
		$counter = 0;
		
		foreach($foundfeaturesfinal as $index => $featurestructure) {
			//if ($comments) echo "<br><br>aaa - " . $counter;
			
			//if ($counter > -1) {
			if ($counter > -1) {
				//if ($comments) echo "<br><br>aaa";
				//if ($comments) $featurestructure->printFeatureStructureRecursive();
					
				if ($comments) echo "<br><br>";
				if ($comments) echo "<br><br>Change to target language - " . $languageID;
				$featurestructure->changeSemanticFeaturesToLanguageFeatures($languageID);
				// TODO: Tähän pitäisi lisätä myös wordfeatureiden asetus...
				
				if ($comments) $featurestructure->printFeatureStructureRecursive();
				
				if ($comments) echo "<br><br>";
				$sentencewords = SyntaxGenerator::generate($featurestructure, $languageID, $rules, "", $comments);
				if ($comments) echo "<br><br>generate finished - languageID: " . $languageID;
				$resultstr = null;
				foreach($sentencewords as $ind2 => $sentence) {
					$resultstrings[] = $sentence;
				
					if ($resultstr == null) {
						$resultstr = $sentence;
					} else {
						$resultstr = $resultstr . ", " . $sentence;
					}
				}
				if ($comments) echo "<br>" . $resultstr;
				if ($comments) echo "<br>finished.";
			}
			
			$counter++;
			//if ($counter > 1) break;
			
			//if ($comments == 1) break;
		}
		
		if ($comments) echo "<br><br>Finished.";
				
		if ($comments) echo "<br><br>";
		
		echo "[";
		$first = true;
		$doublestrings = array();
		foreach($resultstrings as $index => $str) {
			if (isset($doublestrings[$str])) {
				// ei tulosteta tuplia				
			} else {
				if ($first == true) {
					$first = false;
				} else {
					echo ",";
				}
				echo "	\"" . $str . "\"";
				$doublestrings[$str] = 1;
			}
		}
		echo " ]";
	}

		
	public function generateArgumentsRecursively($objective, $languageID, $concepts, $arguments, $components, $wordclassfeatures, $features, $semanticlinks, $currentlevel, $maxlevel, $comments) {
		
		if ($comments) echo "<br><br>--------------------------------------------------";
		if ($comments) echo "<br>--------------------------------------------------";
		if ($comments) echo "<br>Arguments creation... currentlevel: " . $currentlevel . ", maxlevel:" . $maxlevel . " - " . $objective->name;
		
		
		$selectedconcepts = array();
		
		// Filtteröidään ensin sanaluokan mukaan
		if ($comments) echo "<br><br>";
		if ($comments) echo "<br>Filtteröidään sanaluokan mukaan...";
		$counter = 1;
		foreach($concepts as $index => $concept) {
			if ($concept->wordclassID == $objective->wordclassID) {
				$selectedconcepts[$concept->conceptID] = $concept;
				if ($comments) echo "<br>" . $counter . " -- compatible concept found - " . $concept->name;
				$counter++;
			}
		}
		
		
		// Heataan wordeille featuret... Voidaan rajoittaa ehkä Objectiven featureen, mutta niitä ei tiedetä...
		// $objectiveitems = Table::load('worder_inflectionsetitems', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND InflectionsetID=" . $objective->objectiveID);
		// TODO: inflectionsetitemssiä ei tarkisteta missään
		if ($comments) echo "<br><br>";
		if ($comments) echo "<br>Filtteröidään inflectionsets mukaan...";
		$requiredinflectionsets = $this->getObjectiveInflectionsets($objective);
		$selectedconcepts2 = array();
		if (count($requiredinflectionsets) > 0) {
				
			$inflectionsetitems = Table::loadWhereInArray('worder_inflectionsetitems','inflectionsetID',$requiredinflectionsets, "WHERE GrammarID=" . $_SESSION['grammarID']);
				
			foreach($selectedconcepts as $conceptID => $concept) {
		
				$word = $concept->word;
				if ($comments) echo "<br> -- checking word - " . $word->lemma. " (wordID:" . $word->wordID . ")";
				$wordaccepted = false;
				foreach($requiredinflectionsets as $index => $inflectionsetID) {
					if ($comments) echo "<br> -- -- Required inflectionsetID - " . $inflectionsetID;
					foreach($inflectionsetitems as $rowID => $inflectionsetitem) {
						//echo "<br> -- -- -- features - " . $word->features;
						if ($inflectionsetitem->inflectionsetID == $inflectionsetID) {
							$inflectionsetitemfeature = $features[$inflectionsetitem->parentfeatureID];
							$inflectionsetitemfeaturevalue = $features[$inflectionsetitem->featureID];
							//echo "<br> -- -- -- inflectionsetitem - " . $inflectionsetitemfeature->name . " (" . $inflectionsetitem->parentfeatureID . ") - " . $inflectionsetitemfeaturevalue->name . " (" . $inflectionsetitem->featureID . ")";
								
							if (isset($word->featurelist[$inflectionsetitem->parentfeatureID])) {
								$parentfeature = $features[$inflectionsetitem->parentfeatureID];
								//echo "<br> -- -- -- -- feature found " . $parentfeature->name;
								if ($word->featurelist[$inflectionsetitem->parentfeatureID] == $inflectionsetitem->featureID) {
									//echo "<br> -- -- -- -- feature found in word, accepted";
									$wordaccepted = true;
								}
							}
						}
						if ($wordaccepted == true) break;
					}
					if ($wordaccepted == true) break;
				}
				if ($wordaccepted == false) {
					//$conceptID = $wordconceptlist[$word->wordID];
					//echo "<br> -- add - " . $conceptID;
					//unset($selectedconcepts[$conceptID]);
				} else {
					$selectedconcepts2[$concept->conceptID] = $concept;
					// echo "<br> -- inflectionset compatible word found - " . $word->lemma;
				}
			}
		} else {
			if ($comments) echo "<br>No inflectionset requirements, acccept all";
			$selectedconcepts2 = $selectedconcepts;
		}
		
		if ($comments) {
			echo "<br><br>Selectedconcepts, after inflectionsets check ...";
			$counter = 0;
			foreach($selectedconcepts2 as $conceptID => $concept) {
				if ($comments) echo "<br>" . $counter . " -- " . $concept->name . " - " . $concept->lemma;
				$counter++;
			}
		}
		
		// Filtteröidään sallitut conceptit componenttien avulla
		// - componentti on objectiveen hardkoodattu sanojen valintaa rajoittava kriteeri
		// - muutettu niin, että useammasta componentista riittää, että yksi löytyy
		if ($comments) echo "<br><br>";
		if ($comments) echo "<br>Filtteröidään componenttien mukaan 2...";
		$requiredcomponents = $this->getObjectiveComponents($objective);
		$componentwords = array();
		$selectedconcepts3 = array();
		if ((count($selectedconcepts2) > 0) && (count($requiredcomponents) > 0)) {
			foreach($selectedconcepts2 as $conceptID => $concept) {
				if ($comments) echo "<br> -- checking component concept - " . $concept->name;
				$componentitems = explode("|", $concept->components);
				foreach($requiredcomponents as $index => $componentstring) {
					$requiredcomponentlist = explode(",", $componentstring);
					$allcomponentsfound = true;	
					foreach($requiredcomponentlist as $requiredindex => $requiredcomponentID) {
						if ($comments) echo "<br> -- requiredlist component - " . $requiredindex . " - "  . $requiredcomponentID;
						if ($comments) $component = $components[$requiredcomponentID];
						if ($comments) echo "<br> -- required component - " . $component->name;
						$componentfound = false;
						foreach($componentitems as $index => $compstr) {
							if ($compstr != "") {
								$parts = explode(":", $compstr);
								if ($parts[0] == $requiredcomponentID) {
									if ($comments) echo "<br> -- -- Compatible component found - " . $concept->name .  " - " . $component->name;
									$componentfound = true;
								}
							}
						}
						if ($componentfound == false) {
							if ($comments) echo "<br> -- -- No component found - " . $concept->name .  " - " . $component->name . ", not selected";
							$allcomponentsfound = false;
							break;
						}
					}
					if ($componentfound == false) {		// minkä tahansa hyväksyvässä tämä on true
						$allcomponentsfound = false;
					}
					if ($allcomponentsfound == true) {
						if ($comments) echo "<br> -- allcomponents found - true";
						break;
					}
				}
				if ($allcomponentsfound == false) {
					//unset($selectedconcepts[$concept->conceptID]);
					if ($comments) echo "<br> -- not compatible";
						
				} else {
					if ($comments) echo "<br> -- adding to selected";
					$selectedconcepts3[$concept->conceptID] = $concept;
				}
			}
		} else {
			$selectedconcepts3 = $selectedconcepts2;
		}
		
		if ($comments) echo "<br><br>Selectedconcepts, after components check ...";
		foreach($selectedconcepts3 as $conceptID => $concept) {
			if ($comments) echo "<br> -- " . $concept->name . " - " . $concept->lemma;
		}
		
		
		// Filtteröidään sallitut conceptit wordclass featurerequirementin avulla
		// - feature on löydyttävä ja sen arvon on täsmättävä
		// - Pelkästään wordclassfeaturet, jotka löydetään wordiltä. generate hoitaa inflectional featuresit
		if ($comments) echo "<br><br>";
		if ($comments) echo "<br>Filtteröidään wordclassfeatureiden mukaan 333 ...";
		$requiredwordclassfeatures = $this->getObjectiveFeatureRequirements($objective, $languageID, $features);
		if ($comments) echo "<br>... requirementcount - " . count($requiredwordclassfeatures);
		$componentwords = array();
		$selectedconcepts4 = array();
		foreach($selectedconcepts3 as $conceptID => $concept) {
			$word = $concept->word;
			if ($comments) echo "<br> -- checking wordclassfeature concept - " . $concept->name . " - " . $word->lemma;
			$featureitems = explode("|", $word->features);
			if ($comments) echo "<br> -- -- featureitems - " . $word->features;
		
			$allaccepted = true;
			foreach($requiredwordclassfeatures as $neededfeatureID => $neededvalueID) {
				$neededfeature = $features[$neededfeatureID];
				$neededvalue = $features[$neededvalueID];
				if ($comments) echo "<br> -- -- required component - " . $neededfeature->name . " = " . $neededvalue->name;
				$featurefound = false;
				$compatible = true;
				foreach($featureitems as $index => $featurestr) {
						
					$parts = explode(":", $featurestr);
					$featureID = $parts[0];
					$valueID = $parts[1];
						
					if ($featureID == $neededfeatureID) {
						if ($valueID == $neededvalueID) {
							if ($comments) echo "<br> -- -- -- Compatible feature found";
							$featurefound = true;
						} else {
							$feature = $features[$featureID];
							$value = $features[$valueID];
							if ($comments) echo "<br> -- -- -- Incompatible feature found - " . $feature->name .  " - " . $value->name;
							$compatible = false;
						}
					} else {
						$feature = $features[$featureID];
						if ($comments) echo "<br> -- -- featurefound, not needed - " . $feature->name;
					}
				}
				if ($featurefound == false) {
					$allaccepted = false;
					//break;
				}
				if ($compatible == false) {
					$allaccepted = false;
					//break;
				}
			}
			if ($allaccepted == false) {
				if ($comments) echo "<br> -- -- -- -- not all accepted";
				//unset($selectedconcepts[$concept->conceptID]);
			} else {
				$selectedconcepts4[$concept->conceptID] = $concept;
				if ($comments) echo "<br> -- -- -- -- *** accepted";
			}
		}
		
		if ($comments) {
			echo "<br><br>Selectedconcepts, after wordclassfeature check ...";
			$counter = 0;
			foreach($selectedconcepts4 as $conceptID => $concept) {
				echo "<br>" . $counter . " -- " . $concept->name . " - " . $concept->lemma;
				$counter++;
			}
			echo "<br><br>";
		}
		
		
		$foundarguments = array();
		if ($currentlevel < $maxlevel) {
			echo "<br>More recursion needed.... ";
			exit;
			// Kelataan lisää aliargumentteja...
			// asetetaan löydetyt foundarguments-listaan...	
			// - voidaanko olettaa, että kaikki conceptit ottavat samat argumentit? Ei varmaankaan... pitää ainakin tarkistaa...		
		} else {
			if ($comments) echo "<br>No more features needed, recursion level reached";
		}
		
		$resultfeaturestructures = array();
		foreach($selectedconcepts4 as $conceptID => $concept) {
			
			$featurestructure = new FeatureStructure($concept->name . "/" . $concept->lemma, $concept->wordclassID);
			$featurestructure->setConceptID($concept->conceptID);
				
			// Asetetaan aluksi kaikki featuret oletusarvoihin
			foreach ($wordclassfeatures as $rowID => $wordclassfeature) {
				if ($wordclassfeature->wordclassID == $concept->wordclassID) {
					if ($wordclassfeature->defaultvalueID > 0) {
						$featurestructure->addFeature($wordclassfeature->featureID, $wordclassfeature->defaultvalueID);
						//if ($comments) echo "<br> -- adding wordclassdefaultfeature - " . $wordclassfeature->featureID . " - " . $wordclassfeature->defaultvalueID;
					}
				}
			}
			
			
			// Asetetaan kyseisen conceptin wordfeaturet...
			$word = $concept->word;
			$featurelist = explode("|", $word->features);
			if ($comments) echo "<br> --- word features - " . $word->features;
			foreach($featurelist as $ii => $featurestr) {
				if ($featurestr != "") {
					$parts = explode(":",$featurestr);
					if ($comments) echo "<br> -- -- parts - " . $parts[0] . " vs. " . $parts[1];
					$featurestructure->addFeature($parts[0], $parts[1]);
				}
			}
				
			
			// Asetetaan kyseisen conceptin componentit...
			$word = $concept->word;
			$componentlist = explode("|", $concept->components);
			if ($comments) echo "<br> --- word components - " . $word->components;
			foreach($componentlist as $i1 => $componentstr) {
				if ($componentstr != "") {
					$parts = explode(":",$componentstr);
					if ($comments) echo "<br> -- -- parts - " . $parts[0] . " vs. " . $parts[1];
					$featurestructure->addComponent($parts[0]);
				}
			}
						
			
			
			$generatefeatures = explode("|", $objective->generatefeatures);
			if ($comments) echo "<br> -- Generating features - " . $objective->generatefeatures  . " (objectiveID:" . $objective->objectiveID . ")";;
			foreach($generatefeatures as $index => $featurestr) {
				if ($featurestr != "") {
					$parts = explode(":", $featurestr);
					//echo "<br>Part[0] - " . $parts[0];
					if ($comments) echo "<br> -- -- adding objective feature - " . $parts[0] . " vs. " . $parts[1];
						
					$semanticfeatureID = $parts[0];
					$semanticvalueID = $parts[1];
						
					$featureID = $semanticlinks[$semanticfeatureID];
					$valueID = $semanticlinks[$semanticvalueID];
			
					if ($comments) {
						$featureA = $features[$semanticfeatureID];
						$featureV = $features[$semanticvalueID];
						echo "<br> -- -- -- adding objective feature - " . $featureA->name . " -> " . $featureV->name;
						$featureA = $features[$featureID];
						$featureV = $features[$valueID];
						echo "<br> -- -- -- xx adding objective feature - " . $featureA->name . " -> " . $featureV->name;
					}
					$featurestructure->addFeature($featureID, $valueID);
				}
			}
			
			// Käydään lävitse objectivessa olevat featuret joilla generointi suoritetaan
			//	- nämä ylikirjoittavat mahdolliset wordin faeturet...
			/*
			$objectivefeatures = explode("|", $objective->features);
			foreach($objectivefeatures as $index => $featurestr) {
				if ($featurestr != "") {
					$parts = explode(":", $featurestr);
					//echo "<br>Part[0] - " . $parts[0];
					
					$featureA = $features[$parts[0]];
					if ($comments) echo "<br> -- -- adding objective feature - " . $parts[0] . " vs. " . $parts[1];
					if ($featureA->languageID == $languageID) {
						if ($comments) echo "<br> -- -- -- language match";
						$featurestructure->addFeature($parts[0], $parts[1]);
					} else {
						if ($comments) echo "<br> -- -- -- language doesn't match";
					}
					
				}
			}
			*/
			
			
			// TODO: Tähän pitää tehdä varmaan vielä recursive... mallia riveiltä 1607-1683
			
			
			$resultfeaturestructures[] = $featurestructure;
		}
		
		return $resultfeaturestructures;
	}
	
	
	public function insertlessonsentenceAction() {
		
		$sentence = $_GET['sentence'];
		$objectiveID = $_GET['objectiveID'];
		$lessonID =  $_GET['lessonID'];
		$lesson = Table::loadRow("worder_lessons", $lessonID);
		
		$objective = Table::loadRow("worder_objectives", $objectiveID);
		
		
		//echo "<br>sentence - " . $sentence;
		//echo "<br>lessonID - " . $lessonID;
		//echo "<br>objectiveID - " . $objectiveID;
		
		$values = array();
		$values['Sentence'] = $sentence;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['SourceID'] = $_SESSION['sourceID'];
		$values['Correctness'] = 1;
		$values['LanguageID'] = $lesson->languageID;
		$sentenceID = Table::addRow("worder_sentences", $values, false);

		$values = array();
		$values['LessonID'] = $lessonID;
		$values['LanguageID'] = $lesson->languageID;
		$values['SentenceID'] = $sentenceID;
		$values['ObjectiveID'] = $objective->objectiveID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_lessonsentencelinks", $values, false);
		
		redirecttotal('worder/lessons/showlesson&id=' . $lessonID,null);
	}
	
	
	// Tämä palauttaa halutun pintamuodon annetulle featurestructurelle (josta saadaan featurevaluet)
	// Ei löytynyt instansseja, voitaneen poistaa, mutta annetaan nyt hetken aikaa möllöttää kun ei muista tarvitaanko tätä jossain
	/*
	public function getwordform($fs, $comments) {
	
		$comments = false;
		$comments2 = false;
		if (isset($_GET['comments'])) {
			$comments = true;
			$comments2 = true;
		}
		$languageID = $_GET['languageID'];
		$list = $_GET['list'];
	
		$features = Table::load("worder_features", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$wordclassfeatures = Table::load("worder_wordclassfeatures", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$formlist = explode('|',$list);
		$resultlist = array();
		$counter = 0;
		foreach($formlist as $resultindex => $value) {
				
			$items = explode(',', $value);
			if ($comments2) echo "<br>ConceptID = " . $items[0];
			$conceptID = $items[0];
				
			$concept = Table::loadRow("worder_concepts", "WHERE ConceptID=" . $conceptID . " AND GrammarID=" . $_SESSION['grammarID'], $comments);
			if ($comments2) echo "<br>Concept = " . $concept->name;
				
			$wordlinks = Table::load("worder_conceptwordlinks", "WHERE ConceptID=" . $conceptID . " AND LanguageID = " . $languageID . " AND GrammarID=" . $_SESSION['grammarID']);
	
			foreach($wordlinks as $linkID => $link) {
				$words = Table::load("worder_words", "WHERE WordID=" . $link->wordID . " AND LanguageID = " . $languageID . " AND GrammarID=" . $_SESSION['grammarID']);
				foreach($words as $wordID => $word) {
					//echo "<br>Word = " . $word->lemma;
				}
			}
				
			if ($comments2) echo "<br>Word = " . $word->lemma . " (" . $word->wordID . ")";
	
			$searchfeatures = array();
			foreach($wordclassfeatures as $wcfindex => $wordclassfeature) {
				if ($wordclassfeature->wordclassID == $concept->wordclassID) {
					//echo "<br> -- this wordclassID - " . $concept->wordclassID;
					//echo "<br> -- this featureID found - " . $wordclassfeature->featureID ;
					if ($wordclassfeature->inflectional == 1) {
						$feature = $features[$wordclassfeature->featureID];
						if ($comments2) echo "<br> -- needed feature - " . $feature->name;
						if ($wordclassfeature->defaultvalueID == '0') {
						} else {
							$searchfeatures[$feature->featureID] = $wordclassfeature->defaultvalueID;
						}
					} else {
						//echo "<br> -- inflectional == 0 -- ";
					}
				}
			}
				
			if (count($items) > 1) {
				$counter = 0;
				for($i = 1;$i<count($items);$i++) {
					$pairstr = $items[$i];
					$pair = explode(':', $pairstr);
					if ($comments2) echo "<br> -- feature - " . $pair[0] . " = " . $pair[1];
						
					$featureName = $pair[0];
					$featureValue = $pair[1];
						
					$parent = null;
					$valuefeature = null;
						
					foreach($features as $featureID => $feature) {
						if ($featureName == $feature->name) {
							if ($comments2) echo "<br> -- -- feature name found - " . $feature->name . " - " . $feature->featureID . " - " . $feature->inflectional;
							$parent = $feature;
						}
						if ($featureValue == $feature->abbreviation) {
							if ($comments2) echo "<br> -- -- feature value found - " . $feature->abbreviation . " - " . $feature->featureID . " - " . $feature->inflectional;
							$valuefeature = $feature;
						}
					}
						
					if ($parent != null) {
						if ($comments2) echo "<br>Searching feature - " . $valuefeature->featureID;
						//echo "<br>"
						foreach($wordclassfeatures as $wcfindex => $wordclassfeature) {
							if ($wordclassfeature->wordclassID == $concept->wordclassID) {
								if ($comments2) echo "<br> -- this wordclassID - " . $concept->wordclassID;
								if (($wordclassfeature->featureID == $parent->featureID)) {
										
									if ($comments2) echo "<br> -- this featureID found - " . $wordclassfeature->featureID ;
									if ($wordclassfeature->inflectional == 1) {
										if ($comments2) echo "<br> -- inflectional == 1 -- ";
										$searchfeatures[$parent->featureID] = $valuefeature->featureID;
									} else {
										if ($comments2) echo "<br> -- inflectional == 0 -- ";
									}
									break;
								}
							}
						}
					}
						
					$counter++;
					if ($counter > 10) break;
				}
			}
				
			if ($comments2) echo "<br>";
			foreach($searchfeatures as $sindex => $value) {
				if ($comments2) echo "<br> -- searchfeature " . $sindex . " = " . $value;
			}
				
			if ($comments2) echo "<br>";
			$forms = Table::load("worder_wordforms", "WHERE WordID=" . $word->wordID . " AND LanguageID=" . $languageID . " AND GrammarID=" . $_SESSION['grammarID']);
			foreach($forms as $findex => $wordform) {
				$counter = 0;
				foreach($searchfeatures as $sid => $svalue) {
					foreach($wordform->features as $xID => $xValue) {
						if ($comments2) echo "<br> -- form: " . $wordform->wordform . " - " . $xID . " - " . $xValue;
						if ($xValue == $svalue) {
							$counter++;
							break;
						}
					}
				}
				if ($counter == count($searchfeatures)) {
					if ($comments) echo "<br> " . $resultindex . " ------ selected form: " . $wordform->wordform;
					if (isset($resultlist[$resultindex])) {
	
					} else {
						$newlist = array();
						$newlist[] = $wordform->wordform;
						$resultlist[$resultindex] = $newlist;
					}
				}
			}
			if ($comments2) echo "<br><br>";
		}
	
		$resultstr = "";
		foreach($resultlist as $index => $valuelist) {
			$first = true;
			foreach($valuelist as $vindex => $value) {
				if ($first == true) {
					if ($resultstr != "") $resultstr = $resultstr . " ";
					$resultstr = $resultstr . $value;
					$first = false;
				} else {
					$resultstr = $resultstr . "|" . $value;
				}
			}
		}
		if ($comments) echo "<br>";
	
		echo "{";
		echo "	\"result\":\"" . $resultstr . "\"";
		echo "}";
	}
	*/
	
	
	private function loadRules($languageID, $rulesetID, $wordclasses = null, $features = null, $arguments = null, $components = null) {

		$comments = false;
		
		include_once('./modules/worder/_classes/featurestructure.class.php');
		include_once('./modules/worder/_classes/rule.class.php');
		
		if ($comments) echo "<br>RulesetID - " . $rulesetID;
		$rulesetlinks = Table::load("worder_rulesetlinks", "WHERE SetID=" . $rulesetID, $comments);
		$rulelist = array();
		foreach($rulesetlinks as $index => $link) {
			$rulelist[$link->ruleID] = $link->ruleID;
		}
		if ($comments) {
			echo "<br>Rulesetlist - " . count($rulelist);
			foreach($rulelist as $index => $rule) {	
				echo "<br> - " . $index;
			}
			echo "<br>-----";
		}
		
		$rulestructs = Table::loadWhereInArray("worder_rules", "RuleID", $rulelist, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND Generate=1", $comments);
		//$rulestructs = Table::load("worder_rules","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND Status>0 AND Generator=0 ORDER BY Sortorder", false);
		if ($comments) {
			foreach($rulestructs as $index => $rule) {
						echo "<br> - Rulename - " . $rule->ruleID . " - " . $rule->name;
			}
		}
				
		if ($wordclasses == null) {
			$wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
		}
		if ($features == null) {
			$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);
		}
		if ($arguments == null) {
			$arguments = Table::load("worder_arguments", "WHERE (GrammarID=" . $_SESSION['grammarID'] . " OR GrammarID=0)");
		}
		if ($components == null) {
			$components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID']);
		}
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
			$rule->ruleID = $rulestruct->ruleID;
			$rule->languageID = $rulestruct->languageID;
			
			//$rule->setRuleID($rulestruct->ruleID);	
			//echo "<br>rule - " . $rulestruct->name . ", " . $rulestruct->wordclassID . ", ruleID:" . $rulestruct->ruleID;
		
			// Ladataan rulen conceptargumentsit, nämä taitaa kyllä löytyä rulestructista...
			if ($rulestruct->conceptID > 0) {
				$concept = Table::loadRow("worder_concepts", $rulestruct->conceptID);
				$argustrings = explode('|', $concept->arguments);
				foreach($argustrings as $index => $value) {
					$argvalue = explode(':', $value);
					$rule->addConceptArgument($argvalue[0], $argvalue[1], $argvalue[2]);
				}
				$rule->setConceptName($concept->name);
			}
			
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
			$rule->setRuleID($rulestruct->ruleID);
			$rules[$rulestruct->ruleID] = $rule;
		}
		return $rules;
	}
	

	private function getObjectiveArguments($objective) {

		$arguments = array();
		if ($objective->arguments == null) return array();
		if ($objective->arguments == "") return array();
		
		$argumentlist = explode("|", $objective->arguments);
		foreach($argumentlist as $index => $argumentstr) {
			$parts = explode(":",$argumentstr);
			$row = new Row();
			$row->argumentID = $parts[0];
			$row->wordclassID = $parts[1];
			$row->objectiveID = $parts[2];
			//$row->level = $parts[3];
			//$featurestr = $parts[4];
			//if ($parts[4] != "") {
			//}
			$arguments[$parts[0]] = $row;
		}
		return $arguments;
	}
	
	
	private function getObjectiveComponents($objective) {
	
		$components = array();
		$componentlist = explode("|", $objective->components);
		foreach($componentlist as $index => $componentID) {
			if ($componentID != "") {
				$components[$componentID] = $componentID;
			}
		}
		return $components;
	}
	


	private function getObjectiveFeatureRequirements($objective, $languageID, $features) {
	
		$wordclassfeatures = array();
		if ($objective->featurerequirements != "") {
			$featurelist = explode("|", $objective->featurerequirements);
			foreach($featurelist as $index => $featurestr) {
				$parts = explode(":",$featurestr);
				$feature = $features[$parts[0]];
				if ($feature->languageID == $languageID) {
					$wordclassfeatures[$parts[0]] = $parts[1];
				}
			}
		}
		return $wordclassfeatures;
	}
	
	

	private function getObjectiveInflectionsets($objective) {
	
		$inflectionsets = array();
		$inflectionsetlist = explode("|", $objective->inflectionsets);
		foreach($inflectionsetlist as $index => $inflectionsetID) {
			if ($inflectionsetID != "") {
				$inflectionsets[$inflectionsetID] = $inflectionsetID;
			}
		}
		return $inflectionsets;
	}
	

	private function getConceptArguments($concept) {
	
		$arguments = array();
		if (($concept->arguments != null) && ($concept->arguments != "")) {
			$argumentlist = explode("|", $concept->arguments);
			foreach($argumentlist as $index => $argumentstr) {
				//echo "<br>argumentstr - " . $argumentstr;
				$parts = explode(":",$argumentstr);
				$row = new Row();
				$row->argumentID = $parts[0];
				$row->componentID = $parts[1];
				$arguments[$parts[0]] = $row;
			}
		}
		return $arguments;
	}
	
	
	
	
	
	private function getConceptComponents($concept) {
	
		$components = array();
		$componentlist = explode("|", $concept->components);
		foreach($componentlist as $index => $componentstr) {
			//echo "<br>componentstr - " . $componentstr;
			if ($componentstr != "") {
				$parts = explode(":",$componentstr);
				$components[$parts[0]] = $parts[0];
			}
		}
		return $components;
	}
	
	
	
	public function moveobjectiveAction() {
	
		$comments = false;
		$rowID = $_GET['id'];
		$lessonID = $_GET['lessonID'];
	
		$orderby = "";
		if (isset($_GET['dir'])) {
			$direction = $_GET['dir'];
			if ($direction == 'down') $orderby = "DESC";
		}
	
		$objectivelinks = Table::load("worder_lessonobjectivelinks", "WHERE LessonID=" . $lessonID . " AND GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Sortorder " . $orderby, $comments);
	
		if ($comments) echo "<br>count - " . count($objectivelinks);
		$current = null;
		$previous = null;
		foreach($objectivelinks as $index => $objectivelink) {
			
			if ($comments) echo "<br>Objectivelink - " . $objectivelink->rowID;

			if ($objectivelink->rowID == $rowID) {
				$current = $objectivelink;
				if ($previous == null) {
					if ($comments) echo "<br>Already first";
					$previous = null;
					break;
				} else {
					//$previousID = $objective->rowID;
					break;
				}
			}
			if ($comments) echo "<br>current - " . $objectivelink->rowID;
			$previous = $objectivelink;
		}
		
		if (($previous != null) && ($current != null)) {
			
			global $mysqli;
			
			
			$sql = "UPDATE worder_lessonobjectivelinks SET Sortorder='" . $previous->sortorder . "' WHERE RowID=" . $current->rowID . "";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) echo "<br>Error: " . $mysqli->error;
			
			$sql = "UPDATE worder_lessonobjectivelinks SET Sortorder='" . $current->sortorder . "' WHERE RowID=" . $previous->rowID . "";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) echo "<br>Error: " . $mysqli->error;
		}
		
		if (!$comments) redirecttotal('worder/lessons/showlesson&id=' . $lessonID,null);
	}
	
	

	public function moveconceptAction() {
	
		$conceptID = $_GET['id'];
		$lessonID = $_GET['lessonID'];
		$comments = false;
		$orderby = "";
		if (isset($_GET['dir'])) {
			$direction = $_GET['dir'];
			if ($direction == 'down') $orderby = "DESC";
		}
		$links = Table::load("worder_lessonconcepts", "WHERE LessonID=" . $lessonID . " AND GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Sortorder " . $orderby);
	
		if ($comments) echo "<br>count - " . count($links);
		$current = null;
		$previous = null;
		foreach($links as $index => $link) {
	
			if ($comments) echo "<br>Loop - " . $link->rowID;
	
			if ($link->conceptID == $conceptID) {
				$current = $link;
				if ($previous == null) {
					if ($comments) echo "<br>Already first";
					$previous = null;
					break;
				} else {
					break;
				}
			}
			$previous = $link;
		}
	
		if ($comments) {
			if ($previous != null) {
				if ($comments) echo "<br>Previous - " . $previous->rowID;
			} else {
				if ($comments) echo "<br>Previous - null";
			}
			if ($current != null) {
				if ($comments) echo "<br>Current - " . $current->rowID;
			} else {
				if ($comments) echo "<br>Current - null";
			}
		}
	
		if (($previous != null) && ($current != null)) {
	
			global $mysqli;
	
	
			$sql = "UPDATE worder_lessonconcepts SET Sortorder='" . $previous->sortorder . "' WHERE RowID=" . $current->rowID . "";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) echo "<br>Error: " . $mysqli->error;
	
			$sql = "UPDATE worder_lessonconcepts SET Sortorder='" . $current->sortorder . "' WHERE RowID=" . $previous->rowID  . "";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) echo "<br>Error: " . $mysqli->error;
		}
	
		if (!$comments) redirecttotal('worder/lessons/showlesson&id=' . $lessonID);
	}
	
	
	

	public function lessonresortAction() {
	
		$comments = true;

		$languageID = $_GET['languageID'];
		$lessonconceptlinks = Table::load("worder_lessons", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " ORDER BY Sortorder");
		$sortlist = array();
		$lessonlist = array();
		$counter = 0;
		foreach($lessonconceptlinks as $index => $link) {
			$sortlist[$counter] = $link->lessonID;
			$lessonlist[$counter] = $link->lessonID;
			$counter++;
		}

		ksort($sortlist);
		
		global $mysqli;
		
		$counter = 0;
		foreach($sortlist as $index => $sortorderID) {
			$lessonID = $lessonlist[$counter];
			$sql = "UPDATE worder_lessons SET Sortorder='" . $sortorderID . "' WHERE LessonID=" . $lessonID . "";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			$counter++;
		}
		if (!$comments) redirecttotal('worder/lessons/showlessons',null);
	}
	

}
?>
