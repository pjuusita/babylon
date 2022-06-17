<?php


class WordapiController extends AbstractController {
	
	const grammarID = 1;
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','worder.css','yritys.css','prism.css','chosen.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','chosen.jquery.js','prism.js');
	}
		
	
	public function indexAction() {
		$this->registry->template->show('system/error','unknown');
	}
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function getsourcelanguagesAction() {
		//echo "<br>GetSourceLanguages";	

		$languages = Table::load("worder_languages","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND Active=1", false);
		foreach($languages as $index => $language) {
			//echo "<br> - " . $language->name;
		}
		//echo "<br><br>";
		$this->printLanguages($languages);
	}
	
	
	
	
	
	
	public function getlessonlistAction() {
		
		$comments = false;
				
		if (isset($_GET['sID'])) $sourceLanguageID = $_GET['sID'];
		if (isset($_GET['tID'])) $targetLanguageID = $_GET['tID'];
		
		$lessons = Table::load("worder_lessons","WHERE LanguageID=" . $targetLanguageID . " AND GrammarID=" . $_SESSION['grammarID'], $comments);
		if ($comments) echo "<br>GrammarID - " . $_SESSION['grammarID'];
		if ($comments) echo "<br>";
		foreach($lessons as $index => $lesson) {
			//echo "<br>Lesson - " . $lesson->name;
		}
		
		// lessoneille pitää ladata requirementit ja rewardit
		// millon lessonit päivitetään käyttöliittymässä? ladataan aina kaikki lessonit tällä
		
		echo "{";
		echo "\"lessons\":[";
		$first = true;
		foreach($lessons as $index => $lesson) {
			if ($first == false) echo ",";
			else $first = false;
			echo "{";
			echo "\"id\":\"" . $lesson->lessonID . "\",";
			echo "\"name\":\"" . parseMultilangString($lesson->name, $targetLanguageID) . "\",";
			echo "\"reqs\":\"0\"";
			echo "}";
		}
		echo "]";
		echo "}";
	}
	
	

	public function getsentencesetsAction() {
	
		if (isset($_GET['sID'])) $sourceLanguageID = $_GET['sID'];
		if (isset($_GET['tID'])) $targetLanguageID = $_GET['tID'];
	
		$sentencesets = Table::load("worder_sentencesets","WHERE (LanguageID=" . $targetLanguageID . " OR LanguageID=" . $sourceLanguageID . ") AND GrammarID=" . $_SESSION['grammarID'], false);
	
		// lessoneille pitää ladata requirementit ja rewardit
		// millon lessonit päivitetään käyttöliittymässä? ladataan aina kaikki lessonit tällä
	
		echo "{";
		echo "\"sentencesets\":[";
		$first = true;
		foreach($sentencesets as $index => $set) {
			if ($first == false) echo ",";
			else $first = false;
			echo "{";
			echo "\"id\":\"" . $set->setID . "\",";
			echo "\"name\":\"" . $set->name . "\",";
			echo "\"languageID\":\"" . $set->languageID . "\"";
			echo "}";
		}
		echo "]";
		echo "}";
	}
	
	

	public function getsentencesAction() {
	
		if (isset($_GET['sID'])) $sentencesetID = $_GET['sID'];
		
		$sentencesets = Table::load("worder_sentencesetlinks","WHERE SetID=" . $sentencesetID . " AND GrammarID=" . $_SESSION['grammarID'], false);

		$sentencelist = array();
		foreach($sentencesets as $index => $link) {
			$sentencelist[$link->sentenceID] = $link->sentenceID;
		}
		$sentences = Table::loadWhereInArray('worder_sentences', 'SentenceID', $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		echo "{";
		echo "\"sentences\":[";
		$first = true;
		foreach($sentences as $index => $sentence) {
			if ($first == false) echo ",";
			else $first = false;
			echo "{";
			echo "\"id\":\"" . $sentence->sentenceID . "\",";
			echo "\"sentence\":\"" . $sentence->sentence . "\",";
			echo "\"languageID\":\"" . $sentence->languageID . "\",";
			echo "\"correctness\":\"" . $sentence->correctness . "\"";
			echo "}";
		}
		echo "]";
		echo "}";
	}
	
	
	
	
	// wordclasses	-- ehkä ainoastaan käytössä olevat
	// wordclassfeatures
	// features		-- ehkä ainoastaan käytössä olevat
	// arguments	-- ehkä ainoastaan käytössä olevat
	// components	-- ehkä ainoastaan käytössä olevat
	
	public function getessentialsAction() {

		//$sourceLanguageID = 29;
		//if (isset($_GET['sID'])) $sourceLanguageID = $_GET['sID'];
		
		//$targetLanguageID = 28;
		//if (isset($_GET['sID'])) $targetLanguageID = $_GET['tID'];
		
		$languages = Table::load("worder_languages","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND Active=1", false);
		
		$wordclasses = Table::load("worder_wordclasses","WHERE GrammarID=" . $_SESSION['grammarID'], false);
		
		//$wordclassfeatures = Table::load("worder_wordclassfeatures","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND (LanguageID="  . $sourceLanguageID . " OR LanguageID=" . $targetLanguageID . ")", false);
		$wordclassfeatures = Table::load("worder_wordclassfeatures","WHERE GrammarID=" . $_SESSION['grammarID'], false);
				
		//$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND (LanguageID="  . $sourceLanguageID . " OR LanguageID=" . $targetLanguageID . " OR LanguageID=0)", false);
		//$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=0", false);
		$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID'], false);
		
		$components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		echo "{";
		echo "\"languages\":[";
		$first = true;
		foreach($languages as $index => $language) {
			if ($first == false) echo ",";
			else $first = false;
			echo "{";
			echo "\"id\":\"" . $language->languageID . "\",";
			echo "\"name\":\"" . $language->name . "\",";
			echo "\"source\":\"1\",";
			echo "\"target\":\"1\",";
			echo "\"abbreviation\":\"" . $language->shortname . "\"";
			echo "}";
		}
		echo "],";
		
		//echo "{";
		echo "\"wordclasses\":[";
		$first = true;
		foreach($wordclasses as $index => $wordclass) {
			if ($first == false) echo ",";
			else $first = false;
			echo "{";
			echo "\"id\":\"" . $wordclass->wordclassID . "\",";
			echo "\"name\":\"" . $wordclass->name . "\",";
			echo "\"abbreviation\":\"" . $wordclass->name . "\"";
			echo "}";
		}
		echo "],";

		echo "\"features\":[";
		$first = true;
		foreach($features as $index => $feature) {
			if (isset($languages[$feature->languageID])) {
				if ($first == false) echo ",";
				else $first = false;
				echo "{";
				echo "\"id\":\"" . $feature->featureID . "\",";
				echo "\"parentID\":\"" . $feature->parentID . "\",";
				echo "\"name\":\"" . $feature->name . "\",";
				echo "\"abbreviation\":\"" . $feature->abbreviation . "\",";
				echo "\"languageID\":\"" . $feature->languageID . "\",";
				echo "\"semanticlinkID\":\"" . $feature->semanticlinkID . "\"";
				echo "}";
			}
		}
		echo "],";
		
		echo "\"wordclassfeatures\":[";
		$first = true;
		foreach($wordclassfeatures as $index => $link) {
			if (isset($languages[$feature->languageID])) {
				if ($first == false) echo ",";
				else $first = false;
				echo "{";
				echo "\"id\":\"" . $link->rowID . "\",";
				echo "\"wordclassID\":\"" . $link->wordclassID . "\",";
				echo "\"featureID\":\"" . $link->featureID . "\",";
				echo "\"languageID\":\"" . $link->languageID . "\",";
				echo "\"defaultvalueID\":\"" . $link->defaultvalueID . "\",";
				echo "\"wordbookformID\":\"" . $link->wordbookformID . "\",";
				echo "\"inflectional\":\"" . $link->inflectional . "\",";
				echo "\"semanticdefaultID\":\"" . $link->semanticdefaultID . "\"";
				echo "}";
			}
		}
		echo "],";
		
		
		echo "\"components\":[";
		$first = true;
		foreach($components as $index => $component) {
			if ($first == false) echo ",";
			else $first = false;
			echo "{";
			echo "\"id\":\"" . $component->componentID . "\",";
			echo "\"name\":\"" . $component->name . "\",";
			echo "\"abbreviation\":\"" . $component->abbreviation . "\"";
			echo "}";
		}
		echo "],";
		
		echo "\"arguments\":[";
		$first = true;
		foreach($arguments as $index => $argument) {
			if (isset($languages[$feature->languageID])) {
				if ($first == false) echo ",";
				else $first = false;
				echo "{";
				echo "\"id\":\"" . $argument->argumentID . "\",";
				echo "\"wordclassID\":\"" . $argument->wordclassID . "\",";
				echo "\"name\":\"" . $argument->name . "\",";
				echo "\"abbreviation\":\"" . $argument->name . "\",";
				echo "\"wordclassvalueID\":\"" . $argument->wordclassvalueID . "\",";
				echo "\"featurevalueID\":\"" . $argument->featurevalueID . "\",";
				echo "\"typeID\":\"" . $argument->typeID . "\",";
				echo "\"languageID\":\"" . $argument->languageID . "\"";
				echo "}";
			}
		}
		echo "]";
		
		
		echo "}";
		
	}
	
	public function getlessonAction() {
		
		$comments = false;
		if (!isset($_GET['lessonID'])) { 
			echo "<br>LessonID missing";
			exit;
		}
		$lessonID = $_GET['lessonID'];
		
		if (isset($_GET['sID'])) $sourceLanguageID = $_GET['sID'];
		if (isset($_GET['tID'])) $targetLanguageID = $_GET['tID'];
		
		
		$lesson = Table::loadRow("worder_lessons", $lessonID, $comments);
		$conceptlinks = Table::load("worder_lessonconcepts", "WHERE LessonID=" . $lessonID, $comments);
		//echo "<br>conceptlinks size - " . count($conceptlinks);
		
		$conceptlist = array();
		foreach($conceptlinks as $index => $link) {
			$conceptlist[$link->conceptID] = $link->conceptID;
			//echo "<br> - conceptID found - " . $link->conceptID;
		}
		//$concepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$objectivelinks = Table::load("worder_lessonobjectivelinks", "WHERE LessonID=" . $lessonID, $comments);
		$objectivelist = array();
		foreach($objectivelinks as $index => $link) {
			$objectivelist[$link->objectiveID] = $link->objectiveID;
			//echo "<br> - objectivelinks found - " . $link->objectiveID;
		}
		//$objectives = Table::loadWhereInArray('worder_objectives', 'ConceptID', $objectivelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		// get objectives recursively?
		
		$lessonlinks = Table::load('worder_lessonlinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID, $comments);
		//echo "<br>Lessonlink size - " . count($lessonlinks);
		$prelessons = array();
		foreach($lessonlinks as $index => $lessonlink) {
			if ($lessonlink->parentID > 0) {
				$prelessons[$lessonlink->parentID] = $lessonlink->parentID;
				//echo "<br> - prelesson found - " . $lessonlink->lessonID;
			}
		}
		
		$levels = Table::load('worder_lessonlevels', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID, $comments);
		
		
		
		
		if ($comments) {
			echo "<br><br>";
		}
		echo "{";
		echo "\"lessonID\":";
		echo "\"" . $lesson->lessonID . "\"";
		echo ",";
		echo "\"lessonname\":";
		echo "\"" . parseMultilangString($lesson->name, $targetLanguageID) . "\"";
		echo ",";
		echo "\"prelessons\":[";
		$first = true;
		foreach($prelessons as $index => $parentID) {
			if ($first == false) echo ",";
			else $first = false;
			echo "\"" . $parentID . "\"";
		}
		echo "],";
		echo "\"concepts\":[";
		$first = true;
		foreach($conceptlist as $index => $conceptID) {
			if ($first == false) echo ",";
			else $first = false;
			echo "\"" . $conceptID . "\"";
			}
		echo "],";
		echo "\"objectives\":[";
		$first = true;
		foreach($objectivelist as $index => $objectiveID) {
			if ($first == false) echo ",";
			else $first = false;
			echo "\"" . $objectiveID . "\"";
		}
		echo "],";
		echo "\"levels\":[";
		$first = true;
		foreach($levels as $index => $level) {
			if ($first == false) {
				echo ",";
			} else {
				$first = false;
			}
			echo " {";
			echo "		\"level\":\"" . $level->level . "\",";
			echo "		\"experience\":\"" . $level->experience . "\",";
			echo "		\"stage1weight\":\"" . $level->stage1weight . "\",";
			echo "		\"stage2weight\":\"" . $level->stage2weight . "\",";
			echo "		\"stage3weight\":\"" . $level->stage3weight . "\",";
			echo "		\"stage4weight\":\"" . $level->stage4weight . "\",";
			echo "		\"stage1recap\":\"" . $level->stage1recap . "\",";
			echo "		\"stage2recap\":\"" . $level->stage2recap . "\",";
			echo "		\"stage3recap\":\"" . $level->stage3recap . "\",";
			echo "		\"stage4recap\":\"" . $level->stage4recap . "\",";
			echo "		\"stage1newcount\":\"" . $level->stage1newcount . "\",";
			echo "		\"stage2newcount\":\"" . $level->stage2newcount . "\",";
			echo "		\"stage3newcount\":\"" . $level->stage3newcount . "\",";
			echo "		\"stage4newcount\":\"" . $level->stage4newcount . "\",";
			echo "		\"paidcontent\":\"" . $level->paidcontent. "\"";
			echo " }";
		}
		echo "]";
		
		echo "}";
	}
	
	
	
	public function getrulesJSONAction() {
	
		$sourceSetID = 84;
		$targetSetID = 84;
		$sourceID = $_GET['sID'];
		$targetID = $_GET['tID'];
				
		echo "<br>Jeejee1 - " . $sourceID . " - " . $targetID;
		
		$grammarID = $_SESSION['grammarID'];
		//$sourceSetID = $_GET['sourcesetID'];
		//$targetID = $_GET['targetID'];
		//$targetSetID = $_GET['targetsetID'];
	
		if ($sourceID == $targetID) {
			echo "<br>Jeejee2x - " . $sourceID . " - " . $targetID;
			$rules1 = RulesController::getRulesFull($grammarID, $sourceID, 'analyse', $sourceSetID);
			echo "<br>Jeejee3 - " . $sourceID . " - " . $targetID;
			$rules2 = RulesController::getRulesFull($grammarID, $sourceID, 'generate', $targetSetID);
			$rules3 = array();
			$rules4 = array();
		} else {
			echo "<br>Jeejee2y - " . $sourceID . " - " . $targetID;
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
	
		echo "aaaa[";
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
	
	
	public function getconceptsAction() {
	
		$comments = false;
		if (!isset($_GET['list'])) {
			echo "<br>conceptlist missing";
			exit;
		}

		if (isset($_GET['sID'])) {
			$sourceLanguageID = $_GET['sID'];
		} else{
			echo "<br>sourceID missing";
			exit;
		}
		
		if (isset($_GET['tID'])) {
			$targetLanguageID = $_GET['tID'];
		} else {
			echo "<br>conceptlist missing";
			exit;
		}
		
		
		$conceptstr = $_GET['list'];
		$conceptarray = explode(":", $conceptstr);
		$conceptlist = array();
		foreach($conceptarray as $index => $conceptID) {
			$conceptlist[$conceptID] = $conceptID;
		}
		
		$concepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		//$lessons = Table::load("worder_lessons","WHERE LanguageID=29 AND GrammarID=" . $_SESSION['grammarID'], false);
		$wordlinks = Table::loadWhereInArray('worder_conceptwordlinks', 'ConceptID', $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		
	
		echo "{";
		echo "\"concepts\":[";
		$first = true;
		foreach($concepts as $index => $concept) {
			if ($first == false) echo ",";
			else $first = false;
			echo "{";
			echo "\"id\":\"" . $concept->conceptID . "\",";
			echo "\"name\":\"" . $concept->name . "\",";
			
			echo "\"components\": [";
			$componentlist = explode("|",$concept->components);		
			$first1 = true;
			foreach($componentlist as $index2 => $componentstr) {
				if ($componentstr != "") {
					$compoitems = explode(":", $componentstr);
					//echo "<br> -- " . $compoitems[0];
					if ($compoitems[0] != "") {
						if ($first1 == false) echo ",";
						echo "\"" . $compoitems[0] . "\"";
						$first1 = false;
					}
				}
			}
			echo "],";
			
			echo "\"arguments\": [";
			$argumentlist = explode("|",$concept->arguments);
			$first2 = true;
			foreach($argumentlist as $index2 => $argumentstr) {
				if ($argumentstr != "") {
					$argumentitems = explode(":", $argumentstr);
					//echo "<br> -- " . $compoitems[0];
					if ($first2 == false) echo ",";
					echo "{";
					echo "\"argumentID\":\"" . $argumentitems[0] . "\",";
					echo "\"componentID\":\"" . $argumentitems[1]. "\"";
					echo "}";
					$first2 = false;
				}
			}
			echo "],";
			
			
			echo "\"sourcewords\": [";
			$first3 = true;
			foreach($wordlinks as $index2 => $wordlink) {
				//echo "<br><br>" . $wordlink->languageID . " vs. " . $sourceLanguageID;
				if (($concept->conceptID == $wordlink->conceptID) && ($wordlink->languageID == $sourceLanguageID) && ($wordlink->defaultword == 1)) {
					//echo "<br>Match...<br>";
					if ($first3 == false) echo ",";
					echo "\"" . $wordlink->wordID . "\"";
					$first3 = false;
				}
			}
			foreach($wordlinks as $index2 => $wordlink) {
				if (($concept->conceptID == $wordlink->conceptID) && ($wordlink->languageID == $sourceLanguageID) && ($wordlink->defaultword == 0)) {
					if ($first3 == false) echo ",";
					echo "\"" . $wordlink->wordID . "\"";
					$first3 = false;
				}
			}
			echo "],";
				
			echo "\"targetwords\": [";
			$first3 = true;
			foreach($wordlinks as $index2 => $wordlink) {
				if (($concept->conceptID == $wordlink->conceptID) && ($wordlink->languageID == $targetLanguageID) && ($wordlink->defaultword == 1)) {
					if ($first3 == false) echo ",";
					echo "\"" . $wordlink->wordID . "\"";
					$first3 = false;
				}
			}
			foreach($wordlinks as $index2 => $wordlink) {
				if (($concept->conceptID == $wordlink->conceptID) && ($wordlink->languageID == $targetLanguageID) && ($wordlink->defaultword == 0)) {
					if ($first3 == false) echo ",";
					echo "\"" . $wordlink->wordID . "\"";
					$first3 = false;
				}
			}
			echo "]";
			
			
			echo "}";
		}
		echo "]";
		echo "}";
	}

	
	
	
	public function getwordsAction() {
	
		$comments = false;
		if (!isset($_GET['list'])) {
			echo "<br>wordlist missing";
			exit;
		}
	
		if (isset($_GET['sID'])) {
			$sourceLanguageID = $_GET['sID'];
		} else{
			echo "<br>sourceID missing";
			exit;
		}
	
		if (isset($_GET['tID'])) {
			$targetLanguageID = $_GET['tID'];
		} else {
			echo "<br>conceptlist missing";
			exit;
		}
	
	
		$wordstr = $_GET['list'];
		$wordarray = explode(":", $wordstr);
		$wordlist = array();
		foreach($wordarray as $index => $wordID) {
			$wordlist[$wordID] = $wordID;
		}
	
		$words = Table::loadWhereInArray('worder_words', 'WordID', $wordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		//$lessons = Table::load("worder_lessons","WHERE LanguageID=29 AND GrammarID=" . $_SESSION['grammarID'], false);
		//$wordlinks = Table::loadWhereInArray('worder_conceptwordlinks', 'ConceptID', $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		
		
	
	
		echo "{";
		echo "\"words\":[";
		$first = true;
		foreach($words as $index => $word) {
			if ($first == false) echo ",";
			else $first = false;
			echo "{";
			echo "\"id\":\"" . $word->wordID . "\",";
			echo "\"lemma\":\"" . $word->lemma . "\",";
			echo "\"wordclassID\":\"" . $word->wordclassID . "\",";
			echo "\"conceptID\":\"" . $word->conceptID . "\",";
			echo "\"languageID\":\"" . $word->languageID . "\",";
			echo "\"features\": [";
			$featurelist = explode("|",$word->features);
			$first2 = true;
			foreach($featurelist as $index2 => $featurestr) {
				if ($featurestr != "") {
					$featureitems = explode(":", $featurestr);
					if ($first2 == false) echo ",";
					echo "{";
					echo "\"featureID\":\"" . $featureitems[0] . "\",";
					echo "\"featurevalueID\":\"" . $featureitems[1]. "\"";
					echo "}";
					$first2 = false;
				}
			}
			echo "],";
			
			echo "\"forms\": [";
			
			$wordforms = Table::load("worder_wordforms","WHERE WordID=" . $word->wordID . " AND GrammarID=" . $_SESSION['grammarID'], false);
				
			$first2 = true;
			foreach($wordforms as $index2 => $form) {
				if ($form->wordform != "") {
					if ($first2 == false) echo ",";
					echo "{";
					echo "\"form\":\"" . $form->wordform . "\",";
					//echo "<br><br>" . $form->features . "<br>";
					echo "\"features\": [";
					$first3 = true;
					//$featurelist = explode(":", $form->features);
					foreach($form->features as $index3 => $featureID) {
						if ($featureID != "") {
							if ($first3 == false) echo ",";
							echo "\"" . $featureID . "\"";
							$first3 = false;
						}
					}
					echo "]";
					echo "}";
					$first2 = false;
				}
			}
			echo "]";
				
			
			echo "}";
			/*
			
			echo "\"arguments\": [";
			$argumentlist = explode("|",$concept->arguments);
			$first2 = true;
			foreach($argumentlist as $index2 => $argumentstr) {
				if ($argumentstr != "") {
					$argumentitems = explode(":", $argumentstr);
					//echo "<br> -- " . $compoitems[0];
					if ($first2 == false) echo ",";
					echo "{";
					echo "\"argumentID\":\"" . $argumentitems[0] . "\",";
					echo "\"componentID\":\"" . $argumentitems[1]. "\"";
					echo "}";
					$first2 = false;
				}
			}
			echo "],";
				
				
			echo "\"sourcewords\": [";
			$first3 = true;
			foreach($wordlinks as $index2 => $wordlink) {
				//echo "<br><br>" . $wordlink->languageID . " vs. " . $sourceLanguageID;
				if (($concept->conceptID == $wordlink->conceptID) && ($wordlink->languageID == $sourceLanguageID) && ($wordlink->defaultword == 1)) {
					//echo "<br>Match...<br>";
					if ($first3 == false) echo ",";
					echo "\"" . $wordlink->wordID . "\"";
					$first3 = false;
				}
			}
			foreach($wordlinks as $index2 => $wordlink) {
				if (($concept->conceptID == $wordlink->conceptID) && ($wordlink->languageID == $sourceLanguageID) && ($wordlink->defaultword == 0)) {
					if ($first3 == false) echo ",";
					echo "\"" . $wordlink->wordID . "\"";
					$first3 = false;
				}
			}
			echo "],";
	
			echo "\"targetwords\": [";
			$first3 = true;
			foreach($wordlinks as $index2 => $wordlink) {
				if (($concept->conceptID == $wordlink->conceptID) && ($wordlink->languageID == $targetLanguageID) && ($wordlink->defaultword == 1)) {
					if ($first3 == false) echo ",";
					echo "\"" . $wordlink->wordID . "\"";
					$first3 = false;
				}
			}
			foreach($wordlinks as $index2 => $wordlink) {
				if (($concept->conceptID == $wordlink->conceptID) && ($wordlink->languageID == $targetLanguageID) && ($wordlink->defaultword == 0)) {
					if ($first3 == false) echo ",";
					echo "\"" . $wordlink->wordID . "\"";
					$first3 = false;
				}
			}
			echo "]";
			
				
			echo "}";
				*/
	
		}
		echo "]";
		echo "}";
		
	}
	
	



	public function getwordformsAction() {
	
		$comments = false;
		if (!isset($_GET['word'])) {
			echo "<br>word missing";
			exit;
		}
		$wordform = $_GET['word'];
		
		
		if (isset($_GET['sID'])) {
			$sourceLanguageID = $_GET['sID'];
		} else{
			echo "<br>sourceID missing";
			exit;
		}
	
		if (isset($_GET['tID'])) {
			$targetLanguageID = $_GET['tID'];
		} else {
			echo "<br>tID missing";
			exit;
		}
	
	
		$wordstr = $_GET['list'];
		$wordarray = explode(":", $wordstr);
		$wordlist = array();
		foreach($wordarray as $index => $wordID) {
			$wordlist[$wordID] = $wordID;
		}
	
		$wordformlist = Table::load('worder_wordforms', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND Wordform='" . $wordform . "'");
		
		//$lessons = Table::load("worder_lessons","WHERE LanguageID=29 AND GrammarID=" . $_SESSION['grammarID'], false);
		//$wordlinks = Table::loadWhereInArray('worder_conceptwordlinks', 'ConceptID', $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		
		$wordlist = array();
	
		foreach($wordformlist as $index => $wordform) {
			//echo "<br>Word - " . $wordform->wordID;
			$wordlist[$wordform->wordID] = $wordform->wordID;
			//$wordforms = Table::load('worder_wordforms', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID='" . $word->wordID . "'", true);
			//foreach($wordforms as $index => $form) {
			//	echo "<br> - " . $form->wordform;
			//}			
		}
	
		
		$words = Table::loadWhereInArray('worder_words', 'WordID', $wordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		echo "{";
		echo "\"words\":[";
		$first = true;
		foreach($words as $index => $word) {
			if ($first == false) echo ",";
			else $first = false;
			echo "{";
			echo "\"id\":\"" . $word->wordID . "\",";
			echo "\"lemma\":\"" . $word->lemma . "\",";
			echo "\"wordclassID\":\"" . $word->wordclassID . "\",";
			echo "\"conceptID\":\"" . $word->conceptID . "\",";
			echo "\"languageID\":\"" . $word->languageID . "\",";
			echo "\"features\": [";
			$featurelist = explode("|",$word->features);
			$first2 = true;
			foreach($featurelist as $index2 => $featurestr) {
				if ($featurestr != "") {
					$featureitems = explode(":", $featurestr);
					if ($first2 == false) echo ",";
					echo "{";
					echo "\"featureID\":\"" . $featureitems[0] . "\",";
					echo "\"featurevalueID\":\"" . $featureitems[1]. "\"";
					echo "}";
					$first2 = false;
				}
			}
			echo "],";
				
			echo "\"forms\": [";
				
			$wordforms = Table::load("worder_wordforms","WHERE WordID=" . $word->wordID . " AND GrammarID=" . $_SESSION['grammarID'], false);
	
			$first2 = true;
			foreach($wordforms as $index2 => $form) {
				if ($form->wordform != "") {
					if ($first2 == false) echo ",";
					echo "{";
					echo "\"form\":\"" . $form->wordform . "\",";
					//echo "<br><br>" . $form->features . "<br>";
					echo "\"features\": [";
					$first3 = true;
					//$featurelist = explode(":", $form->features);
					foreach($form->features as $index3 => $featureID) {
						if ($featureID != "") {
							if ($first3 == false) echo ",";
							echo "\"" . $featureID . "\"";
							$first3 = false;
						}
					}
					echo "]";
					echo "}";
					$first2 = false;
				}
			}
			echo "]";
	
				
			echo "}";
		}
		echo "]";
		echo "}";
	}
	
	public function getobjectivesAction() {
	
		$comments = false;
		if (!isset($_GET['list'])) {
			echo "<br>objectivelist missing";
			exit;
		}
		$objectivestr = $_GET['list'];
		$objectivearray = explode(":", $objectivestr);
		$objectivelist = array();
		foreach($objectivearray as $index => $objectiveID) {
			$objectivelist[$objectiveID] = $objectiveID;
		}
		
		$objectives = Table::loadWhereInArray('worder_objectives', 'ObjectiveID', $objectivelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		$prerequisitelinks = Table::loadWhereInArray('worder_objectiveprerequisites', 'ObjectiveID', $objectivelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		// TODO: tätä hakua voisi ehkä hieman rajoittaa, target ja source languageID:illä ainakin...
		$features = Table::load("worder_features", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$completions = Table::loadWhereInArray('worder_objectivecompletions', 'ObjectiveID', $objectivelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		//$lessons = Table::load("worder_lessons","WHERE LanguageID=29 AND GrammarID=" . $_SESSION['grammarID'], false);
	
	
		echo "{";
		echo "\"objectives\":[";
		$first = true;
		foreach($objectives as $index => $objective) {
			if ($first == false) echo ",";
			else $first = false;
			echo "{";
			echo "\"id\":\"" . $objective->objectiveID . "\",";
			echo "\"name\":\"" . $objective->name . "\",";
			echo "\"preobjectives\": [";
			
			$first1 = true;
			foreach($prerequisitelinks as $index2 => $link) {
				if ($link->objectiveID == $objective->objectiveID) {
					if ($first1 == false) echo ",";
					echo "\"" . $link->prerequisiteID . "\"";
					$first1 = false;
				}
			}
			echo "], ";

			echo "\"components\": [";
			$first2 = true;
			
			//echo "<br><br>" . $objective->components . "<br><br>";
			$componentlist = explode("|",$objective->components);
			foreach($componentlist as $index3 => $subcomponentstr) {
				if ($first2 == false) echo ",";
				if ($subcomponentstr != "") {
					//echo "<br> - subcomponent - " . $subcomponentstr;
					echo " { \"components\" :  [";
					$subcomponentlist = explode(",", $subcomponentstr);
					$first3 = true;
					foreach($subcomponentlist as $index4 => $component) {
						if ($first3 == false) echo ",";
						if ($component != "") {
							echo "\"" . $component . "\"";
						}	
						$first3 = false;
					}
					echo "] }";
				}
				$first2 = false;
			}
			echo "],";
				
			//echo "<br><br>";
			//echo "<br>featurestr = " . $objective->features;
			
			echo "\"requirements\": [";
			$first3 = true;
			$featurelist = explode("|", $objective->features);
			foreach($featurelist as $index => $featurestr) {
				$featureitems = explode(":", $featurestr);
				
				if ($featureitems[3] == "1") {	// Kyseessä on requirement

					$feature = $features[$featureitems[0]];
					$languageID = $feature->languageID;
					$featurevalueID = $featureitems[1];
					$inflectional = $featureitems[2];
					$requirement = $featureitems[3];
						
					if ($first3 == false) echo ",";
					echo "{";
					echo "\"languageID\":\"" . $languageID . "\",";
					echo "\"featureID\":\"" . $feature->featureID . "\",";
					echo "\"featurevalueID\":\"" . $featurevalueID . "\",";
					echo "\"inflectional\":\"" . $inflectional . "\"";
					echo "}";
					$first3 = false;
				} 
			}
			echo "],";
				

			echo "\"generatefeatures\": [";
			$first3 = true;
			$featurelist = explode("|", $objective->features);
			foreach($featurelist as $index => $featurestr) {
				$featureitems = explode(":", $featurestr);
			
				if ($featureitems[3] == "0") {	// Kyseessä on requirement
			
					$feature = $features[$featureitems[0]];
					$languageID = $feature->languageID;
					$featurevalueID = $featureitems[1];
					$inflectional = $featureitems[2];
					$requirement = $featureitems[3];
			
					if ($first3 == false) echo ",";
					echo "{";
					echo "\"languageID\":\"" . $languageID . "\",";
					echo "\"featureID\":\"" . $feature->featureID . "\",";
					echo "\"featurevalueID\":\"" . $featurevalueID . "\",";
					echo "\"inflectional\":\"" . $inflectional . "\"";
					echo "}";
					$first3 = false;
				}
			}
			echo "],";
			
			

			echo "\"inflectionsets\": [";
				
			$first4 = true;
			$inflectionsetlist = explode("|", $objective->inflectionsets);
			foreach($inflectionsetlist as $index => $inflectionsetID) {
				if ($inflectionsetID != "") {
					if ($first4 == false) echo ",";
					echo "\"" . $inflectionsetID . "\"";
					$first4 = false;
				}
			}
			echo "],";
				
			
			
			echo "\"completions\": [";
			$first5 = true;
			foreach($completions as $index => $completion) {
				if ($completion->objectiveID == $objective->objectiveID) {
					if ($first5 == false) echo ",";
					echo "{";
					echo "\"objectiveID\":\"" . $completion->parentobjectiveID . "\",";
					echo "\"inflectionsetID\":\"" . $completion->inflectionsetID . "\"";
					echo "}";
					$first5 = false;
				}
			}
			echo "],";
				
		
			echo "\"arguments\": [";
			$first6 = true;
			$argumentlist = explode("|", $objective->arguments);
			foreach($argumentlist as $index => $argumentstr) {
				
				if ($argumentstr != "") {
					$argumentitems = explode(":", $argumentstr);
						
					$argumentID = $argumentitems[0];
					$wordclassID = $argumentitems[1];
					$objectiveID = $argumentitems[2];
					$level = $argumentitems[3];
						
					if ($first6 == false) echo ",";
					echo "{";
					echo "\"argumentID\":\"" . $argumentID . "\",";
					echo "\"wordclassID\":\"" . $wordclassID . "\",";
					echo "\"objectiveID\":\"" . $objectiveID . "\",";
					echo "\"level\":\"" . $level . "\"";
					echo "}";
					$first6 = false;
				}
			}
			echo "]";
				
				
			
			/*
			echo "\"preobjectives\": [";
			$componentlist = explode("|",$concept->components);
			$first1 = true;
			foreach($componentlist as $index2 => $componentstr) {
				if ($componentstr != "") {
					$compoitems = explode(":", $componentstr);
					//echo "<br> -- " . $compoitems[0];
					if ($compoitems[0] != "") {
						if ($first1 == false) echo ",";
						echo "\"" . $compoitems[0] . "\"";
						$first1 = false;
					}
				}
			}
			echo "],";
			
			echo "\"arguments\": [";
			$argumentlist = explode("|",$concept->arguments);
			$first2 = true;
			foreach($argumentlist as $index2 => $argumentstr) {
				if ($argumentstr != "") {
					$argumentitems = explode(":", $argumentstr);
					//echo "<br> -- " . $compoitems[0];
					if ($first2 == false) echo ",";
					echo "{";
					echo "\"argumentID\":\"" . $argumentitems[0] . "\",";
					echo "\"componentID\":\"" . $argumentitems[1]. "\"";
					echo "}";
					$first2 = false;
				}
			}
			echo "]";
			*/
			echo "}";
		}
		echo "]";
		echo "}";
	}
	
	// TODO not implemented
	// - palautetaan käyttöliittymään setikoni, kuvatiedosto
	// - kuvatiedosto voi olla isompikin, joka sisältää kaikki ikonit
	public function getlessoniconAction() {
		
	}
	
	
	private function printLanguages($languages) {
		echo "{";
		echo "\"sourcelanguages\":[";
		$first = true;
		foreach($languages as $index => $language) {
			if ($first == false) echo ","; 
			else $first = false;
			echo "{";
			echo "\"id\":\"" . $language->languageID . "\",";
			echo "\"name\":\"" . $language->name . "\"";
			echo "}";
		}
		echo "],";
		echo "\"targetlanguages\":[";
		$first = true;
		foreach($languages as $index => $language) {
			if ($first == false) echo ","; 
			else $first = false;
			echo "{";
			echo "\"id\":\"" . $language->languageID . "\",";
			echo "\"name\":\"" . $language->name . "\"";
			echo "}";
		}
		echo "]";
		echo "}";
	}
}
?>