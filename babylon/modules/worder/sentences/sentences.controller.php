<?php


class SentencesController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->showsentencesAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	
	

	public function showsentencesAction() {

		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->grammars = Table::load("worder_grammars", "WHERE UserID=" . $_SESSION['userID']);
		$this->registry->rulesets = Table::load("worder_rulesets", "WHERE GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Name");
				
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
		if (!isModuleSessionVarSetted('rulesetID')) {
			setModuleSessionVar('rulesetID',0);
		}
		$this->registry->setID = getModuleSessionVar('setID', 0);
		$this->registry->rulesetID = getModuleSessionVar('rulesetID', 0);
		updateActionPath("Sentences");
		
		
		if (isset($_GET['languageID'])) {
			$this->registry->setID = 0;
		}
	
		$activelanguages = getModuleSessionVar('activelanguages','');
		if ($activelanguages == '') {
			if ($this->registry->languageID > 0) {
				$activelanguages = $this->registry->languageID;
				$langlist = array();
				$langlist[$this->registry->languageID] = $this->registry->languageID;
				$this->registry->activelanguages = $langlist;
			} else {
				$langlist = array();
				$this->registry->activelanguages = $langlist;
			}
		} else {
			$langlist = explode(":", $activelanguages);
			$this->registry->activelanguages = $langlist;
		}
		
		$activerulesets = getModuleSessionVar('activerulesets','');
		if ($activerulesets == '') {
			$found = false;
			if ($this->registry->languageID > 0) {
				echo "<br>LanguageID on 0";
				$rulesetlist = array();
				$rulesetlist[] = 0;
				foreach($this->registry->activelanguages as $index => $languageID) {
					if (isset($_GET['rulesetID-'.$languageID])) {
						echo "<br>rulesetti löytyi ... " . $_GET['rulesetID-'.$languageID];
						$found = true;
						$rulesetlist[$index] = $_GET['rulesetID-'.$languageID];
					} else {
						$rulesetlist[$index] = 0;
					}
				}
				$this->registry->activerulesets = $rulesetlist;
			} else {
				$rulesetlist = array();
				foreach($this->registry->activelanguages as $index => $languageID) {
					$rulesetlist[$index] = 0;
					if (isset($_GET['rulesetID-'.$languageID])) {
						echo "<br>rulesetti löytyi ... " . $_GET['rulesetID-'.$languageID];
						$rulesetlist[$index] = $_GET['rulesetID-'.$languageID];
						$found = true;
					}
				}
				$this->registry->activerulesets = $rulesetlist;
			}
			if ($found == true) {
				setModuleSessionVar('activerulesets',implode(":", $rulesetlist));
			}
		} else {
			$rulesetlist = explode(":", $activerulesets);
			$found = false;
			foreach($this->registry->activelanguages as $index => $languageID) {
				if (isset($_GET['rulesetID-'.$languageID])) {
					$rulesetlist[$index] = $_GET['rulesetID-'.$languageID];
					$found = true;
				}
			}
			$this->registry->activerulesets = $rulesetlist;
			if ($found == true) {
				setModuleSessionVar('activerulesets',implode(":", $rulesetlist));
			}
		}

		
		$sentencelist = array();
		$this->registry->sets = Table::load("worder_sentencesets", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID . " ORDER BY Name");
		$this->registry->set = null;
		if ($this->registry->setID > 0) {
			$this->registry->set = $this->registry->sets[$this->registry->setID];
		}
		
		
		if ($this->registry->setID == 0) {
			$this->registry->sentences = Table::load("worder_sentences", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
			$finalsentences = array();
		} else {
			$links = Table::load("worder_sentencesetlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $this->registry->setID . " ORDER BY Sortorder", false);
			foreach($links as $index => $link) {
				$sentencelist[$link->sentenceID] = $link->sentenceID;
			}
			$tempsentences = Table::loadWhereInArray("worder_sentences", "sentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);

			$finalsentences = array();
			foreach($links as $index => $link) {
				if (isset($tempsentences[$link->sentenceID])) {
					$finalsentences[$link->sentenceID] = $tempsentences[$link->sentenceID];
				}
			}
		}
		
		// aputaulun luonti rulesetlooku by languageID
		$rulesetlookup = array();
		foreach($this->registry->activelanguages as $index => $languageID) {
			$rID = $this->registry->activerulesets[$index];
			$rulesetlookup[$languageID] = $rID;
		}
		
		$sentencechecks = Table::load("worder_sentencechecks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $this->registry->setID . " AND SourcerulesetID=" . $this->registry->rulesetID);
		foreach($sentencechecks as $index => $checkline) {
			if ($checkline->languageID == $checkline->languageID) {
				
				$neededrulesetID = $rulesetlookup[$checkline->languageID];
				if ($checkline->targetrulesetID == $neededrulesetID) {
					$sentence = $finalsentences[$checkline->sentenceID];
					$var = 'color'.$checkline->languageID;
					$sentence->$var = '#90ee90';
				}
			}
		}
		
		
		$translationlinks = Table::loadWhereInArray("worder_sentencetranslationlinks", "sentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		if (count($translationlinks) > 0) {
			$sentencelist = array();
			foreach($translationlinks as $index => $link) {
				$sentencelist[$link->targetsentenceID] = $link->targetsentenceID;
			}
			$translations = Table::loadWhereInArray("worder_sentences", "sentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
			foreach($translationlinks as $index => $link) {
				$sentence = $finalsentences[$link->sentenceID];
				$var = "sentence" . $link->targetlanguageID;
				$value = $translations[$link->targetsentenceID]->sentence;
				$oldvalue = $sentence->$var;
	
				if ($oldvalue == "") {
					$sentence->$var = $value;
				} else {
					$sentence->$var = $oldvalue . "," . $value;
				}
			}
		} else {
			$this->registry->translations = array();
		}
		$this->registry->sentences = $finalsentences;
			
		$this->registry->template->show('worder/sentences','sentences');
	}
	
	

	public function showsentencesoldAction() {
	
	
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
			
		if (!isModuleSessionVarSetted('languageID')) {
			$languageID = 0;
			foreach($this->registry->languages as $index => $language) {
				$languageID = $language->languageID;
				break;
			}
			setModuleSessionVar('languageID',$languageID);
			$this->registry->languageID = $languageID;
			//echo "<br>act - " . $languageID . " - " . $this->registry->languageID;
		} else {
			$this->registry->languageID = getModuleSessionVar('languageID', 0);
			//echo "<br>act2 - - " . $this->registry->languageID;
		}
	
		if (!isModuleSessionVarSetted('rulesetID')) {
			setModuleSessionVar('rulesetID',0);
		}
		$this->registry->rulesetID = getModuleSessionVar('rulesetID', 0);
	
	
		$this->registry->setID = getModuleSessionVar('setID', 0);
		$this->registry->grammars = Table::load("worder_grammars", "WHERE UserID=" . $_SESSION['userID']);
		$this->registry->rulesets = Table::load("worder_rulesets", "WHERE GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Name");
	
		updateActionPath("Sentences");
	
	
		if (isset($_GET['languageID'])) {
			$this->registry->setID = 0;
		}
	
		$activelanguages = getModuleSessionVar('activelanguages','');
		//echo "<br>activelanguages - '" . $activelanguages . "'";
		if ($activelanguages == '') {
			if ($this->registry->languageID > 0) {
				$activelanguages = $this->registry->languageID;
				$langlist = array();
				$langlist[$this->registry->languageID] = $this->registry->languageID;
				$this->registry->activelanguages = $langlist;
			} else {
				$langlist = array();
				$this->registry->activelanguages = $langlist;
			}
			//echo "<br>Language - " . $this->registry->languageID;
		} else {
			$langlist = explode(":", $activelanguages);
			$this->registry->activelanguages = $langlist;
		}
		//echo "<br>";
		//var_dump($this->registry->activelanguages);
	
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		if (!isset($this->registry->languages[$this->registry->languageID])) {
			$this->registry->languageID = current($this->registry->languages)->languageID;
			setSessionVar('languageID', $this->registry->languageID);
		}
	
		//echo "<br>SetID = " . $this->registry->setID;
		$sentencelist = array();
		$this->registry->sets = Table::load("worder_sentencesets", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID . " ORDER BY Name");
		$this->registry->set = null;
		if ($this->registry->setID > 0) {
			$this->registry->set = $this->registry->sets[$this->registry->setID];
		} else {
				
		}
	
	
		if ($this->registry->setID == 0) {
			$this->registry->sentences = Table::load("worder_sentences", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
			$finalsentences = array();
		} else {
			$links = Table::load("worder_sentencesetlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $this->registry->setID . " ORDER BY Sortorder", false);
			foreach($links as $index => $link) {
				$sentencelist[$link->sentenceID] = $link->sentenceID;
			}
			$tempsentences = Table::loadWhereInArray("worder_sentences", "sentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
	
			$finalsentences = array();
			foreach($links as $index => $link) {
				if (isset($tempsentences[$link->sentenceID])) {
					$finalsentences[$link->sentenceID] = $tempsentences[$link->sentenceID];
				}
			}
		}
	
	
		$translationlinks = Table::loadWhereInArray("worder_sentencetranslationlinks", "sentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		if (count($translationlinks) > 0) {
			$sentencelist = array();
			foreach($translationlinks as $index => $link) {
				$sentencelist[$link->targetsentenceID] = $link->targetsentenceID;
				//echo "<br> - " . $link->targetsentenceID;
			}
			$translations = Table::loadWhereInArray("worder_sentences", "sentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
	
			foreach($translationlinks as $index => $link) {
				$sentence = $finalsentences[$link->sentenceID];
				$var = "sentence" . $link->targetlanguageID;
				$value = $translations[$link->targetsentenceID]->sentence;
				$oldvalue = $sentence->$var;
				//echo "<br>Pair already..." . $oldvalue;
	
				if ($oldvalue == "") {
					//echo "<br> -- empty";
					$sentence->$var = $value;
				} else {
					$sentence->$var = $oldvalue . "," . $value;
				}
				/*
				 if (isset($sentence->$var)) {
				 $sentence->$var = $sentence->$var . "," . $value;
				 } else {
				 $sentence->$var = $value;
				 }
				 */
				//echo "<br>Translation pair -- " . $sentence->sentence . " vs. " . $value . ", var:" . $var;
			}
	
		} else {
			$this->registry->translations = array();
		}
		$this->registry->sentences = $finalsentences;
			
		$this->registry->template->show('worder/sentences','sentences');
	}
	
	
	// TODO: tämä lienee vanhentunut, voidaan poistaa myös sentences.php --> korvataan sentences2.php, tai poistetaan nimetään uudelleen
	/*
	public function showsentencesOldAction() {
		
		
		updateActionPath("Sentences");
		$this->registry->setID = getModuleSessionVar('setID', 0);
		$this->registry->languageID = getModuleSessionVar('languageID', 0);
		$this->registry->grammars = Table::load("worder_grammars", "WHERE UserID=" . $_SESSION['userID']);
		
		//echo "<br>LanguageID - " . $this->registry->languageID;
		//echo "<br>setID - " . $this->registry->setID;
		$languageID = $this->registry->languageID;
		
		if (isset($_GET['languageID'])) {
			$this->registry->setID = 0;
		}
		if (isset($_GET['setID'])) {
			//echo "<br>Setted setID";
			$this->registry->languageID = 0;
			$languageID = 0;
			getModuleSessionVar('languageID', 0);
		}
		
		$activelanguages = getModuleSessionVar('activelanguages','');
		if ($activelanguages == '') {
			if ($languageID > 0) {
				$activelanguages = $languageID;
				$langlist = array();
				$langlist[$languageID] = $languageID;
				$this->registry->activelanguages = $langlist;
			} else {
				$langlist = array();
				$this->registry->activelanguages = $langlist;
			}
		} else {
			$langlist = explode(":", $activelanguages);
			$this->registry->activelanguages = $langlist;
		}
		
		
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		if ($this->registry->languageID > 0) {
			if (!isset($this->registry->languages[$this->registry->languageID])) {
				$this->registry->languageID = current($this->registry->languages)->languageID;
				setSessionVar('languageID', $this->registry->languageID);
			}
		}

		//echo "<br>SetID = " . $this->registry->setID;
		//$this->registry->sets = Table::load("worder_sentencesets", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
		$this->registry->sets = Table::load("worder_sentencesets", "WHERE GrammarID=" . $_SESSION['grammarID']);
		//echo "<br>Set count = " . count($this->registry->sets);
		//echo "<br>LanguageID - " . $this->registry->languageID;
		
		if ($this->registry->setID == 0) {
			$this->registry->sentences = Table::load("worder_sentences", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
		} else {
			$links = Table::load("worder_sentencesetlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $this->registry->setID, false);
			$sentencelist = array();
			foreach($links as $index => $link) {
				$sentencelist[$link->sentenceID] = $link->sentenceID;
			}
			$this->registry->sentences = Table::loadWhereInArray("worder_sentences", "sentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		}
		
		$this->registry->template->show('worder/sentences','sentences');
	}
	*/
	

	

	
	public function movesentenceAction() {
	
		$comments = false;
		$sentenceID = $_GET['id'];
		$setID = $_GET['setID'];
	
		$orderby = "";
		if (isset($_GET['dir'])) {
			$direction = $_GET['dir'];
			if ($direction == 'up') $orderby = "DESC";
		}
	
		if ($comments) echo "<br>Sentence setID - " . $setID;


		$sentences = Table::load('worder_sentencesetlinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID . " ORDER BY Sortorder " . $orderby, true);
		if ($comments) echo "<br> -- rowcount - " . count($sentences);
		$currentrowID = null;
		$currentrow = null;
		$found = false;
		foreach($sentences as $index => $row) {
			$nextID = $row->linkID;
			$next = $row;
			if ($found == true) break;
			if ($row->sentenceID == $sentenceID) {
				$currentrowID = $row->linkID;
				$currentrow = $row;
				$found = true;
			}
			if ($comments) echo "<br> -- linkID:" . $row->linkID . " (" . $row->sentenceID . "), nextID:" . $nextID . " (" . $next->sentenceID . ")";
		}
		
		if ($nextID != $currentrowID) {
			if ($comments) echo "<br>nextid - " . $nextID . " currentID - " . $currentrowID;
		
			$values = array();
			$values['Sortorder'] = $next->sortorder;
			$updaterow = Table::updateRow("worder_sentencesetlinks", $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LinkID=" . $currentrowID);
		
			$values = array();
			$values['Sortorder'] = $currentrow->sortorder;
			$updaterow = Table::updateRow("worder_sentencesetlinks", $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LinkID=" . $nextID);
		}
		if (!$comments) redirecttotal('worder/sentences/showsentenceset&id=' . $setID,null);
	}
	
	
	
	public function showsentencesetsAction() {
		
		updateActionPath("SentenceSets");
		
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
		updateActionPath("SentenceSets");
		
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->rulesets = Table::load("worder_rulesets", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		if ($this->registry->languageID == 0) {
			$this->registry->sets = Table::load("worder_sentencesets", "WHERE GrammarID=" . $_SESSION['grammarID']);
		} else {
			$this->registry->sets = Table::loadHierarchy('worder_sentencesets','parentID', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID . " ORDER BY Name DESC", false, true);
			//$this->registry->sets = Table::load("worder_sentencesets", "WHERE LanguageID=" . $this->registry->languageID . " AND GrammarID=" . $_SESSION['grammarID']);
		}
		$this->registry->template->show('worder/sentences','sentencesets');
	}
	
	
	

	public function showtemplatesAction() {
	
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
		updateActionPath("TestTemplates");
	
			
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		if ($this->registry->languageID == 0) {
			$this->registry->testtemplates = Table::load("worder_sentencetemplates", "WHERE GrammarID=" . $_SESSION['grammarID']);
		} else {
			$this->registry->testtemplates = Table::load("worder_sentencetemplates", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
			//$this->registry->testtemplates = Table::loadHierarchy('worder_sentencetemplates','parentID', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID, true);
		}
		foreach($this->registry->testtemplates as $index => $template) {
			$template->name = "" . $template->prefixstring . " XXXX " . $template->postfixstring;
		}
		$this->registry->template->show('worder/sentences','testtemplates');
	}
	
	
	
	public function showtemplateAction() {
	
		$templateID = $_GET['id'];

		$template = Table::loadRow("worder_sentencetemplates", $templateID);
		$template->name = "" . $template->prefixstring . " XXXX " . $template->postfixstring;
		$this->registry->testtemplate = $template;
		
		$this->registry->concepts = Table::load("worder_concepts", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $template->wordclassID);
		$this->registry->wordlinks = Table::load("worder_conceptwordlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $template->languageID);
		//$this->registry->words = Table::load("worder_concepts", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $template->languageID . " AND WordclassID=" . $template->wordclassID);
		$this->registry->wordforms = Table::load("worder_wordforms", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $template->languageID . " AND WordclassID=" . $template->wordclassID . " AND Features='" . $template->features . "'");
		$this->registry->answers = Table::load("worder_templateanswers", " WHERE GrammarID=" . $_SESSION['grammarID'] . " AND TemplateID=" . $template->templateID);


		$wordsbyconcept = array();
		
		$wordforms = array();
		$answers = array();
		foreach($this->registry->wordforms as $index => $form) {
			$wordforms[$form->wordID] = $form->wordform;
		}
		foreach($this->registry->answers as $index => $answer) {
			$answers[$answer->conceptID] = $answer->answer;
		}
		
		
		$finalconcepts = array();
		foreach($this->registry->wordlinks as $index => $link) {
			
			if (isset($this->registry->concepts[$link->conceptID])) {
				$concept = $this->registry->concepts[$link->conceptID];
				if (isset($wordforms[$link->wordID])) {
					$form = $wordforms[$link->wordID];
				} else {
					$form = "unknonw-" . $link->conceptID;
					//echo "<br>Unknown wordforms for concept: " . $link->conceptID;
				}
				if (isset($answers[$link->conceptID])) {
					$answer = $answers[$link->conceptID];
				} else {
					$answer = 0;
				}
				
				$row = new Row();
				$row->name = $template->prefixstring . " " . $form . " " . $template->postfixstring;
				$row->answer = $answer;
				$row->conceptID = $link->conceptID;
				$finalconcepts[] = $row;
			}
		}
		$this->registry->conceptrows = $finalconcepts;
		$this->registry->template->show('worder/sentences','testtemplate');
	}
	
	
	// Tätä käytetään tietokantascriptissä - CreateEnglishNounArticles, voidaan poistaa kun hoidettu
	public function addtemplateanswerAction() {
	
		$templateID = $_GET['templateID'];
		$answer = $_GET['answer'];
		$conceptID = $_GET['conceptID'];
		
		$values = array();
		$values['TemplateID'] = $templateID;
		$values['ConceptID'] = $conceptID;
		$values['Answer'] = $answer;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_templateanswers", $values);
	
		echo "1";
	}
	
	
	
	public function showsentencesetAction() {
	
		$setID = $_GET['id'];
		$this->registry->set = Table::loadRow("worder_sentencesets", $setID);
		updateActionPath($this->registry->set->name);
		$languageID = $this->registry->set->languageID;
		$this->registry->sentencesets = Table::load("worder_sentencesets", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->set->languageID);
		
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
		
		
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		if (!isset($this->registry->languages[$this->registry->languageID])) {
			$this->registry->languageID = current($this->registry->languages)->languageID;
			setSessionVar('languageID', $this->registry->languageID);
		}
		
		
		
		
		$links = Table::load("worder_sentencesetlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID . " ORDER BY Sortorder", false);
		$sentencelist = array();
		foreach($links as $index => $link) {
			$sentencelist[$link->sentenceID] = $link->sentenceID;
		}
		$sentences = Table::loadWhereInArray("worder_sentences", "sentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);

		$orderedsentences = array();
		foreach($links as $index => $link) {
			$sentence = $sentences[$link->sentenceID];
			$orderedsentences[$sentence->sentenceID] = $sentence;
		}
		$this->registry->sentences = $orderedsentences;
		
		if ($this->registry->set->rulesetID > 0) {
			$setID = $this->registry->set->rulesetID;
			
			$this->registry->ruleset = Table::loadRow("worder_rulesets", "WHERE SetID=" . $setID . " AND GrammarID=" . $_SESSION['grammarID']);
			$rules = Table::load('worder_rules', "WHERE GrammarID=" . $_SESSION['grammarID']);
			$this->registry->links = Table::load('worder_rulesetlinks',"WHERE SetID=" . $setID . " AND GrammarID=" . $_SESSION['grammarID']);
			
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
		}
		$this->registry->template->show('worder/sentences','sentenceset');
	}
	
	
	
	public function showsentenceAction() {
		
		updateActionPath("Sentence");
		
		$sentenceID = $_GET['id'];
		$sentence = Table::loadRow("worder_sentences", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $sentenceID);
		$this->registry->sentence = $sentence;
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);

		$translationlinks = Table::load("worder_sentencetranslationlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $sentenceID);
		if (count($translationlinks) > 0) {
			$sentencelist = array();
			foreach($translationlinks as $index => $link) {
				$sentencelist[$link->targetsentenceID] = $link->targetsentenceID;	
				//echo "<br> - " . $link->targetsentenceID;				
			} 
			$this->registry->translations = Table::loadWhereInArray("worder_sentences", "sentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
			
		} else {
			$this->registry->translations = array();
		}
		

		$audiofiles = Table::load('worder_audiofiles', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $sentenceID, false);
		$this->registry->audiofiles = $audiofiles;
		
		$this->registry->template->show('worder/sentences','sentence');
	}

	
	public function copysetAction() {
		
		$setID = $_GET['setID'];
		$grammarID = $_GET['grammarID'];
		$languageID = $_GET['languageID'];
		
		$set = Table::loadRow("worder_sentencesets", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID);
		echo "<br>Set: " . $set->name;
		
		$sentencelinks = Table::load("worder_sentencesetlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID, true);
		
		$sentencelist = array();
		
		echo "<br>Link count: " . count($sentencelinks);
		foreach($sentencelinks as $index => $sentencelink) {
			echo "<br>Link: " . $sentencelink->sentenceID;
			$sentencelist[$sentencelink->sentenceID] = $sentencelink->sentenceID;
		}
		$sentences = Table::loadWhereInArray("worder_sentences","SentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		echo "<br><br>";
		foreach($sentences as $index => $sentence) {
			echo "<br> - Sentence - " . $sentence->sentence;
		}

		
		echo "<br> - TargetLanguageID - " . $languageID;
		
		$set = Table::loadRow("worder_sentencesets", $setID);
		$values = array();
		$values['Name'] = $set->name;
		$values['GrammarID'] = $grammarID;
		$values['RulesetID'] = 0;
		$values['LanguageID'] = $languageID;
		$newSetID = Table::addRow("worder_sentencesets", $values, false);
		
		echo "<br>Set - " . $set->setID;
		foreach($sentences as $index => $sentence) {
			echo "<br> - Sentence - " . $sentence->sentence;
			$values = array();
			$values['Sentence'] = $sentence->sentence;
			$values['GrammarID'] = $grammarID;
			$values['LanguageID'] = $languageID;
			$values['Correctness'] = $sentence->correctness;
			$sentenceID = Table::addRow("worder_sentences", $values, false);
			
			$values = array();
			$values['SetID'] = $newSetID;
			$values['SentenceID'] = $sentenceID;
			$values['LanguageID'] = $languageID;
			$values['GrammarID'] = $grammarID;
			$success = Table::addRow("worder_sentencesetlinks", $values, false);
		}
		
	}
	
	
	public function insertsentenceAction() {
	
		$comments = false;
		
		$languageID =  $_GET['languageID'];
		$sentence =  $_GET['sentence'];
		
		$values = array();
		$values['LanguageID'] = $languageID;
		$values['Sentence'] = $sentence;
		$values['GrammarID'] = $_SESSION['grammarID'];
		if (isset($_GET['correctness'])) {
			$values['Correctness'] = $_GET['correctness'];
		} else {
			$values['Correctness'] = 1;
		}
		$sentenceID = Table::addRow("worder_sentences", $values, $comments);
		

		if (isset($_GET['setID'])) {
			$setID =  $_GET['setID'];
			$values = array();
			$values['SentenceID'] = $sentenceID;
			$values['SetID'] = $setID;
			$values['LanguageID'] = $languageID;
			$values['GrammarID'] = $_SESSION['grammarID'];
			$success = Table::addRow("worder_sentencesetlinks", $values, $comments);
			//if (!$comments) redirecttotal('worder/sentences/showsentences', null);
		}

		// tänne tullaan insert translation tyyppisestä toiminnosta...
		if (isset($_GET['sentenceID'])) {
			$sourceSentenceID  =  $_GET['sentenceID'];
			$sentence = Table::loadRow("worder_sentences", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $sourceSentenceID);
			
			$values = array();
			$values['SentenceID'] = $sourceSentenceID;
			$values['LanguageID'] = $sentence->languageID;
			$values['TargetlanguageID'] = $languageID;
			$values['TargetsentenceID'] = $sentenceID;
			$values['GrammarID'] = $_SESSION['grammarID'];
			$success = Table::addRow("worder_sentencetranslationlinks", $values, $comments);
			//if (!$comments) redirecttotal('worder/sentences/showsentence&sentenceID=' . $sourceSentenceID, null);
		}
		
		echo "<br>source..." . $_GET['source'];
		if (isset($_GET['source'])) {
			$source = $_GET['source'];
			if ($source == 'set') redirecttotal('worder/sentences/showsentenceset&id=' . $values['SetID'], null);
			if ($source == 'sentences') redirecttotal('worder/sentences/showsentences', null);
		}
		echo "<br>Finnish...";
	}
	
	
	public function addrulesetforsentencesetAction() {
		
		$sentencesetID = $_GET['setID'];
		$set = Table::loadRow("worder_sentencesets", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $sentencesetID);
		
		$values = array();
		$values['Name'] = $set->name;
		$values['SentencesetID'] = $sentencesetID;
		$values['LanguageID'] = $set->languageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rulesetID = Table::addRow("worder_rulesets", $values, false);

		$sentenceID = $_GET['id'];
		$columns = array();
		$columns['RulesetID'] = $rulesetID;
		$success = Table::updateRow("worder_sentencesets", $columns, $sentencesetID);
		
		redirecttotal('worder/sentences/showsentenceset&id=' . $sentencesetID, null);
	}
	
	
	
	public function insertsentenceJSONAction() {
	
		$comments = false;
		$sourceSentenceID =  $_GET['sentenceID'];
		$languageID =  $_GET['languageID'];
		$sentencestr =  $_GET['sentence'];
	
		$texts = explode(",", $sentencestr);
		
		foreach($texts as $index => $text) {
			
			$sentence = Table::loadRow("worder_sentences", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND Sentence='" . $text . "'", $comments);
			
			if ($sentence != null) {
				if ($comments) echo "<br>sentencefound.. - " . $sentence->sentenceID;
				if ($comments) echo "<br><br>";
					
				$values = array();
				$values['SentenceID'] = $sourceSentenceID;
				$values['LanguageID'] = $sentence->languageID;
				$values['TargetlanguageID'] = $languageID;
				$values['TargetsentenceID'] = $sentence->sentenceID;
				$values['GrammarID'] = $_SESSION['grammarID'];
				$success = Table::addRow("worder_sentencetranslationlinks", $values, $comments);
			
			} else {
				if ($comments) echo "<br>sentencefound not found .. - " . $sentence;
			
				$values = array();
				$values['LanguageID'] = $languageID;
				$values['Sentence'] = $text;
				$values['GrammarID'] = $_SESSION['grammarID'];
				if (isset($_GET['correctness'])) {
					$values['Correctness'] = $_GET['correctness'];
				} else {
					$values['Correctness'] = 1;
				}
				$targetSentenceID = Table::addRow("worder_sentences", $values, $comments);
					
				$sentence = Table::loadRow("worder_sentences", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $sourceSentenceID);
				$values = array();
				$values['SentenceID'] = $sourceSentenceID;
				$values['LanguageID'] = $sentence->languageID;
				$values['TargetlanguageID'] = $languageID;
				$values['TargetsentenceID'] = $targetSentenceID;
				$values['GrammarID'] = $_SESSION['grammarID'];
				$success = Table::addRow("worder_sentencetranslationlinks", $values, $comments);
			}
		}
		echo "1";
	}
	
	
	
	/**
	 * Poistaa parametrinä olevan translationin linkityksen toisena parametrina olevasta lauseesta. Poistetaan
	 * myös vastalinkki.
	 * 
	 */
	public function removetranslationAction() {
		
		$sentenceID = $_GET['sentenceID'];
		$targetsentenceID = $_GET['id'];
				
		$success = Table::deleteRowsWhere('worder_sentencetranslationlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $sentenceID . " AND TargetsentenceID=" . $targetsentenceID);
		$success = Table::deleteRowsWhere('worder_sentencetranslationlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $targetsentenceID . " AND TargetsentenceID=" . $sentenceID);
		
		redirecttotal('worder/sentences/showsentence&id=' . $sentenceID, null);
	}
	

	public function removesentenceAction() {

		$sentenceID = $_GET['id'];
		$linksfound = false;
	
		$terms = Table::load('worder_sentencesetlinks', "WHERE SentenceID=" . $sentenceID);
		if (count($terms) > 0) {
			echo "<br>worder_sentencesetlinks found - " . count($terms);
			$linksfound = true;
		}
	
		$terms = Table::load('worder_sentencelinks', "WHERE SentenceID=" . $sentenceID);
		if (count($terms) > 0) {
			echo "<br>worder_sentencelinks found - " . count($terms);
			$linksfound = true;
		}
		
		$terms = Table::load('worder_lessonsentencelinks', "WHERE SentenceID=" . $sentenceID);
		if (count($terms) > 0) {
			echo "<br>worder_lessonsentencelinks found - " . count($terms);
			$linksfound = true;
		}
	
		$terms = Table::load('worder_rulesentencelinks', "WHERE SentenceID=" . $sentenceID);
		if (count($terms) > 0) {
			echo "<br>worder_rulesentencelinks found - " . count($terms);
			$linksfound = true;
		}
	
		$terms = Table::load('worder_sentencetranslationlinks', "WHERE SentenceID=" . $sentenceID);
		if (count($terms) > 0) {
			echo "<br>worder_audiofiles found - " . count($terms);
			$linksfound = true;
		}
		
		if ($linksfound == false) {
			$success = Table::deleteRow('worder_sentences',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $sentenceID);
			echo "<br>Remove success";
			//redirecttotal('worder/rules/showrules',null);
		} else {
			echo "<br>Remove not possible";
		}
	
	
		// TODO: poista kyseiseen ruleen liittyvät rivit muista tauluista..
	
		//$success = Table::deleteRowsWhere('worder_rulesetlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RuleID=" . $ruleID);
	
	
	
	
	
	}
	
	
	public function removesentencefromsetAction() {

		$sentenceID =  $_GET['id'];
		$setID =  $_GET['setID'];
		$success = Table::deleteRow('worder_sentencesetlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID . " AND SentenceID=" . $sentenceID);
		redirecttotal('worder/sentences/showsentenceset&id=' . $setID ,null);
	}
	

	public function removesentencesetAction() {
		
		$setID =  $_GET['setID'];
		$set = Table::loadRow("worder_sentencesets", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID);
		
		if ($set->rulesetID > 0) {
			
			$language = Table::loadRow("worder_languages", $set->languageID);
			$ruleset = Table::loadRow("worder_rulesets", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $set->rulesetID);
			
			$columns = array();
			$columns['Name'] = $language->shortname . " - " . $ruleset->name;
			$columns['SentencesetID'] = 0;
			$success = Table::updateRow("worder_rulesets", $columns, $set->rulesetID);
		}
		
		
		$links = Table::load("worder_sentencesetlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID, false);
		if (count($links) == 0) {
			$success = Table::deleteRow('worder_sentencesets',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID, false);
		} else {
			$success = Table::deleteRowsWhere('worder_sentencesetlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID);
			$success = Table::deleteRow('worder_sentencesets',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID, false);
		}

		// TODO: add success message
		redirecttotal('worder/sentences/showsentencesets', null);
	}
	
	
	public function updatesentenceAction() {
	
		$sentenceID = $_GET['id'];
		$columns = array();
		$columns['Sentence'] = $_GET['sentence'];
		$columns['Correctness'] = $_GET['correctness'];
		$columns['Comment'] = $_GET['comment'];
		$success = Table::updateRow("worder_sentences", $columns, $sentenceID);
		redirecttotal('worder/sentences/showsentence&id=' . $sentenceID, null);
	}
	
	
	public function checksentenceAction() {
		
		$sentenceID = $_GET['id'];
		$setID = $_GET['setID'];
		$languageID = $_GET['languageID'];
		$sourcesetID = $_GET['sourcesetID'];
		$targetsetID = $_GET['targetsetID'];
		$columns = array();
		
		$columns['SentenceID'] = $sentenceID;
		$columns['SetID'] = $setID;
		$columns['GrammarID'] = $_SESSION['grammarID'];
		$columns['LanguageID'] = $languageID;
		$columns['SourcerulesetID'] = $sourcesetID;
		$columns['TargetrulesetID'] = $targetsetID;
		$columns['Checked'] = 1;
		$columns['Checkdate'] = $currentdate = date('Y-m-d H:i:s');;

		$success = Table::addRow("worder_sentencechecks", $columns);
		if ($success) {
			echo "1";
		} else {
			echo "zzz";
		}
	}
	
	
	

	public function unchecksentenceAction() {
	
		$sentenceID = $_GET['id'];
		$setID = $_GET['setID'];
		$languageID = $_GET['languageID'];
		$sourcesetID = $_GET['sourcesetID'];
		$targetsetID = $_GET['targetsetID'];
		$columns = array();
	
		$columns['SentenceID'] = $sentenceID;
		$columns['SetID'] = $setID;
		$columns['GrammarID'] = $_SESSION['grammarID'];
		$columns['LanguageID'] = $languageID;
		$columns['SourcerulesetID'] = $sourcesetID;
		$columns['TargetrulesetID'] = $targetsetID;
		$columns['Checked'] = 1;
		$columns['Checkdate'] = $currentdate = date('Y-m-d H:i:s');;
	
		$success = Table::deleteRow('worder_sentencechecks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $sentenceID . " AND SetID=" . $setID . " AND LanguageID=" . $languageID . " AND SourcerulesetID=" . $sourcesetID  . " AND TargetrulesetID=" . $targetsetID  );
		
		//$success = Table::addRow("worder_sentencechecks", $columns);
		echo "2";
	}
	
	
	public function checksentenceoldAction() {
		$sentenceID = $_GET['id'];
		$columns = array();
		$columns['Checkdate'] = $currentdate = date('Y-m-d H:i:s');;
		$success = Table::updateRow("worder_sentences", $columns, $sentenceID);
		if ($success) {
			echo "1";
		} else {
			echo "zzz";
		}
	}
	
	
	

	public function unchecksetsentencesAction() {
		
		$setID = $_GET['setID'];
		
		echo "<br>unchecksetsentencesAction";
		
		//$this->registry->sentences = Table::load("worder_sentences", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
		
		$links = Table::load("worder_sentencesetlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $setID, true);
		$sentencelist = array();
		foreach($links as $index => $link) {
			$sentenceID = $link->sentenceID;
			$columns = array();
			$columns['Checkdate'] = "0000-00-00 00:00:00";
			$success = Table::updateRow("worder_sentences", $columns, $sentenceID);
		}
		redirecttotal('worder/sentences/showSentences', null);
	}
	
	
	public function updatesentencesetAction() {
	
		$comments = false;
		
		$setID = $_GET['id'];
		$set = Table::loadRow("worder_sentencesets", $setID);
		
		
		$columns = array();
		$columns['Name'] = $_GET['name'];
		$columns['LanguageID'] = $_GET['languageID'];
		$columns['Description'] = $_GET['description'];
		$columns['ParentID'] = $_GET['parentID'];
		$success = Table::updateRow("worder_sentencesets", $columns, $setID, $comments);
		
		if ($set->rulesetID > 0) {
			$columns = array();
			$columns['Name'] = $_GET['name'];
			$success = Table::updateRow("worder_rulesets", $columns, $set->rulesetID, $comments);
		}
		
		if (!$comments) redirecttotal('worder/sentences/showsentenceset&id=' . $setID, null);
	}
	
	
	
	
	
	public function addexistingsentenceAction() {
	
		$sentenceID =  $_GET['sentenceID'];
		$setID =  $_GET['setID'];

		$set = Table::loadRow("worder_sentencesets", $setID);
		
		$values = array();
		$values['SetID'] = $setID;
		$values['SentenceID'] = $sentenceID;
		$values['LanguageID'] = $set->languageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$success = Table::addRow("worder_sentencesetlinks", $values, false);
		
		redirecttotal('worder/sentences/showsentences', null);
	}
	
	

	public function insertsentencesetAction() {
	
		$languageID = $_GET['languageID'];
		$name =  $_GET['name'];
	
		$values = array();
		$values['Name'] = $name;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['LanguageID'] = $languageID;
		$values['RulesetID'] = 0;
		$setID = Table::addRow("worder_sentencesets", $values, false);
	
		redirecttotal('worder/sentences/showsentencesets', null);
	}
	
	

	public function searchsentencesAction() {
	
		$search = $_GET['search'];
		$languageID = $_GET['languageID'];
		
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$sentences = Table::load("worder_sentences","WHERE GrammarID=" . $_SESSION['grammarID'] .  " AND LOWER(Sentence) LIKE '%" . $search . "%'", false, false);
		
		//foreach($sentences as $index => $sentence) {
			//echo "<br>Language - " . $sentence->sentence;
		//}

		echo "[";
		$first = true;
		foreach($sentences as $index => $sentence) {
			if ($first == true) $first = false; else echo ",";
		
			echo " {";
			echo "	  \"sentenceID\":\"" . $sentence->sentenceID . "\",";
			echo "	  \"sentence\":\"" . $sentence->sentence . "\"";
			echo " }\n";
		}
		echo "]";
	}
	
	
	
	public function translateobjectivesentenceJSONAction() {
		
		$comments = false;
		if (isset($_GET['comments'])) {
			$comments = true;
		}
		
		$targetLanguageID = $_GET['targetlanguageID'];
		$lessonsentencelinkID = $_GET['linkID'];
		
		$link = Table::loadRow("worder_lessonsentencelinks", $lessonsentencelinkID);
		$lessonID = $link->lessonID;
		
		$datalines = Table::load("worder_lessondata", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LessonID=" . $lessonID);

		$targetsetID = 0;
		$sourcesetID = 0;
		foreach($datalines as $index => $data) {
			if ($data->languageID == $targetLanguageID) $targetsetID = $data->rulesetID;
			if ($data->languageID == $link->languageID) $sourcesetID = $data->rulesetID;
		}
		
		$this->translatesentence($link->sentenceID, $targetLanguageID, $sourcesetID, $targetsetID, $comments);
	}
	

	public function translatesentenceJSON2Action() {
	
		$comments = false;
		if (isset($_GET['comments'])) {
			$comments = true;
		}
		$targetLanguageID = $_GET['targetlanguageID'];
		$sentenceID = $_GET['id'];
		$targetsetID = $_GET['targetsetID'];
		$sourcesetID = $_GET['sourcesetID'];
				
		if ($targetsetID == 0) {
			echo "<br>Error - Target RuleSet missing";
			return;
		}
		
		$this->translatesentence($sentenceID, $targetLanguageID, $sourcesetID, $targetsetID, $comments);
	}
	
	
	public function translatesentenceJSONAction() {

		$comments = false;
		if (isset($_GET['comments'])) {
			$comments = true;
		}
		$targetLanguageID = $_GET['targetlanguageID'];
		$sentenceID = $_GET['id'];
		$rulesetID = $_GET['rulesetID'];
		
		$this->translatesentence($sentenceID, $targetLanguageID, $rulesetID, $rulesetID, $comments);
	}
	
	
	
	private function translatesentence($sentenceID, $targetLanguageID, $analyserulesetID, $generaterulesetID, $comments) {

		if ($comments) echo "<br>SentenceID - "  . $sentenceID;
		
		$sentence = Table::loadRow("worder_sentences", $sentenceID);
		if ($sentence->correctness == 1) {
			if ($comments) echo "<br>Sentence: " . $sentence->sentence;
		} else {
			if ($comments) echo "<br>Sentence: *" . $sentence->sentence;
		}
		$languageID = $sentence->languageID;
		
		include_once('./modules/worder/_classes/featurestructure.class.php');
		include_once('./modules/worder/_classes/rule.class.php');
		include_once('./modules/worder/_classes/syntaxanalyser.class.php');
		
		
		$sentencewords = explode(' ', strtolower($sentence->sentence));
		$sourcesentence = $sentence->sentence;
		
		$wordparam = "";
		$first = true;
		if ($comments) echo "<br>Wordforms";
		foreach ($sentencewords as $index => $wordform) {
			if ($first == true) {
				$first = false;
			} else {
				$wordparam = strtolower($wordparam) . ",";
			}
			if ($comments) echo "<br>Wordfom - " . $index . " -> " . $wordform;
			//$wordparam = $wordparam . "'" . strtolower($wordform) . "'";
			$wordparam = $wordparam . "'" . $wordform . "'";
			//$sentencewords[$index] = strtolower($wordform);
			$sentencewords[$index] = $wordform;
		}
		if ($comments) echo "<br>Formparams - " . $wordparam;
		
		if ($comments) echo "<br>LanguageID - " . $languageID;
		$wordforms = Table::load("worder_wordforms"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND Grammatical=1 AND Wordform IN (" . $wordparam . ")", $comments);
		$foundwords = array();
		// pitäisi listata seuraavaksi kaikki wordID:t jotka löytyy.
		
		// tässä on mahdollista löytyä useampia vaihtoehtoja...
		if ($comments) echo "<br>Found words:";
		foreach ($wordforms as $index => $wordform) {
			if ($comments) echo "<br> -- found forms - " . $index . " - " . $wordform->wordform . " - " . $wordform->wordID;
			$foundwords[$wordform->wordID] = $wordform->wordID;
		}
		
		//if ($comments) echo "<br><br>foundwords count:" . count($foundwords);
		$words = Table::loadWhereInArray("worder_words","WordID", $foundwords, "WHERE GrammarID=" . $_SESSION['grammarID']);
		$conceptlinks = Table::loadWhereInArray("worder_conceptwordlinks", "WordID", $foundwords, "WHERE GrammarID=" . $_SESSION['grammarID'], $comments, " ORDER BY Sortorder");
		$conceptlist = array();
		
		if ($comments) echo "<br><br>Conceptlinks:";
		foreach($conceptlinks as $rowID => $conceptlink) {
			if ($comments) echo "<br>Concepts - " . $conceptlink->wordID . " -- " . $conceptlink->conceptID;
			$conceptlist[$conceptlink->conceptID] = $conceptlink->conceptID;
		}
		
		$loadedconcepts = Table::loadWhereInArray("worder_concepts","ConceptID",$conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID'], $comments);
		
		$concepts = array();
		foreach($conceptlinks as $rowID => $conceptlink) {
			$concept = $loadedconcepts[$conceptlink->conceptID];
			$concepts[$conceptlink->conceptID] = $concept;
		}
		
		
		// Tämä taulu sisältää wordID:lle löytyneet conceptit
		$foundwordconcepts = array();
		
		if ($comments) echo "<br><br>Concepts:";
		foreach($concepts as $rowID => $concept) {
			foreach($conceptlink as $rowID => $conceptlink) {
				if ($conceptlink->conceptID = $concept->conceptID) {
		
				}
			}
		}
		
		$foundwordconcepts = array();
		$wordtable = array();
		$countarray = array();
		$wordindex = 0;
		$errors = false;
		foreach($sentencewords as $index => $sentenceword) {
			$wordarray = array();
			if ($comments) echo "<br> -- processsing sentenceindex - " . $index . " - " . $sentenceword;
			$counter = 0;
			foreach($wordforms as $rowID => $wordform) {
				if ($wordform->wordform == $sentenceword) {
					if ($comments) echo "<br> -- -- wordform found - " . $wordform->wordform . " - wordID: " . $wordform->wordID . " - formID:" . $wordform->rowID . " - word: " . $words[$wordform->wordID]->lemma;
					$wordarray[$counter] = $wordform;
					$counter++;
					$onefound = false;
		
					foreach($conceptlinks as $index2 => $conceptlink) {
						if ($conceptlink->wordID == $wordform->wordID) {
							if ($onefound == true) {
								if ($comments) echo "<br> -- -- -- conceptlink found other - " . $conceptlink->conceptID . " - " . $concepts[$conceptlink->conceptID]->name;
								$copy = $wordform->getCopy();
								$copy->conceptID = $conceptlink->conceptID;
								$wordarray[$counter] = $copy;
								$counter++;
							} else {
								if ($comments) echo "<br> -- -- -- conceptlink found - " . $conceptlink->conceptID . " - " . $concepts[$conceptlink->conceptID]->name;
		
								$copy = $wordform->getCopy();
								$copy->conceptID = $conceptlink->conceptID;
								$wordarray[$counter-1] = $copy;
								$onefound = true;
								//$wordarray[$counter-1] = $wordform;
							}
						}
					}
				}
			}
		
			if ($counter == 0) {
				if ($comments) echo "<br>No lemma found for wordform a1 " . $sentenceword;
				//return AnalyseController::sendError("No wordoform found for '" . $sentenceword . "'");
				//$errors = true;
				echo "{\"resultcount\":\"0\"}";
				return;
				//exit();  // TODO: Pitää palauttaa errortailukko
			}
		
			$wordtable[$wordindex] = $wordarray;
			$countarray[$wordindex] = $counter;
			$wordindex++;
		}
		
		$wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);
		$arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$wordclassefeatures = Table::load('worder_wordclassfeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$targetwordclassefeatures = Table::load('worder_wordclassfeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $targetLanguageID);
		
		FeatureStructure::$wordclasses = $wordclasses;
		SyntaxAnalyser::$wordclasses = $wordclasses;
		FeatureStructure::$features = $features;
		FeatureStructure::$components = $components;
		FeatureStructure::$arguments = $arguments;
		
		//echo "<br><br>Loading rules...";
		
		
		if ($comments) {
			echo "<br>Analyseruleset - " . $analyserulesetID;
			echo "<br>LanguageID - " . $languageID;
		}
		$analyseRules = $this->getRules($_SESSION['grammarID'], $analyserulesetID, $languageID, 'analyse', false);
		
		$combinations = array();
		$currentarray = array();
		
		foreach($wordtable as $index1 => $wordformss) {
			if ($comments) echo "<br>Wordtable - ";
		
			foreach($wordformss as $index2 => $wordformi) {
				if ($comments) echo "," . $wordformi->rowID;
			}
		}
		$this->generateCombinationsRecursively($combinations, $currentarray, 0, $wordtable);
		
		if ($comments) echo "<br>Combinations";
		foreach($combinations as $index => $combination) {
			$sentencearray = array();
			if ($comments) echo "<br>";
			foreach($combination as $xpos => $targetindex) {
				$wordform = $wordtable[$xpos][$targetindex];
				if ($comments) echo "<br>combi - " . $wordform->wordID . ":" . $wordform->conceptID . ":" . $wordform->rowID . " - " . $wordform->wordform;
				//echo "<br>compifeatreues";
				//print_r($wordform->features)
				$sentencearray[] = $wordform;
			}
		}
		if ($comments) echo "<br><br><br>";
		
		$featuresentences = array();
		foreach($combinations as $index => $combination) {
		
			$sentencearray = array();
			foreach($combination as $xpos => $targetindex) {
				$wordform = $wordtable[$xpos][$targetindex];
				$sentencearray[] = $wordform;
			}
			$featuresentences[] = $this->generateFeatureStructureSentence($sentencearray, $words, $concepts, $wordclasses, $arguments, $components, $features, $languageID, $wordclassefeatures, false);
		}
		
		if ($comments) echo "<br><br>";
		$resultsarray = array();
		foreach($featuresentences as $index => $featuresentence) {
			// analyse featuresentence...
			if ($comments) {
				echo "<br> ---------------------------------------<br>";
				echo "<br> Analyse featuresentence<br>";
				echo "<br> ---------------------------------------<br>";
				foreach($featuresentence as $index => $featurestructure) {
					$featurestructure->printFeatureStructure();
					echo "<br><br>";
				}
			}
				
			$results = SyntaxAnalyser::analyse($featuresentence, $analyseRules, $comments);
			if ($results != null) {
				foreach($results as $i2 => $resultfeature) {
					$resultsarray[] = $resultfeature;
				}
			}
		}
		
		if ($comments) echo "<br>Found results: " . count($resultsarray);
		if ($comments) echo "<br><br><br><br>---------------------------------------------------------";
		if ($comments) echo "<br>-------------------------------------------------------------";
		if ($comments) echo "<br>-------------------------------------------------------------";
		if ($comments) echo "<br><br><br><br>Translate...";
		if ($comments) echo "<br>-------------------------------------------------------------";
		if ($comments) echo "<br>-------------------------------------------------------------";
		if ($comments) echo "<br>-------------------------------------------------------------";
		
		
		if (count($resultsarray) > 0) {
			include_once('./modules/worder/_classes/syntaxgenerator.class.php');
				
			SyntaxGenerator::$wordclasses = $wordclasses;
			SyntaxGenerator::$features = $features;
				
				
			$generateRules = $this->getRules($_SESSION['grammarID'], $generaterulesetID, $targetLanguageID, 'generate', false);
				
			$resultstrings = array();
			$counter = 0;
				
			// Haetaan kaikki conceptID:t, vai löytyykö nämä jo ennestään
			$targetconceptlist = array();
			foreach($resultsarray as $index => $featurestructure) {
				$featurestructure->getConceptsRecursively($targetconceptlist);
			}
				
			if ($comments) {
				foreach($targetconceptlist as $index => $conceptID) {
					echo "<br>Targetconcepts - " . $conceptID;
				}
			}
			$foundconceptlinks = Table::loadWhereInArray("worder_conceptwordlinks", "ConceptID", $targetconceptlist, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $targetLanguageID . " AND Defaultword=1", $comments, " ORDER BY Sortorder");
			$wordlist = array();
			$wordsByConceptID = array();
			foreach($foundconceptlinks as $index => $link) {
				if ($comments) echo "<br>Link - " . $link->conceptID . " - " . $link->wordID;
				$wordlist[$link->wordID] = $link->wordID;
				$wordsByConceptID[$link->conceptID] = $link->wordID;
			}
			$words = Table::loadWhereInArray("worder_words","WordID", $wordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
				
				
			foreach($resultsarray as $index => $featurestructure) {
				//if ($comments) echo "<br><br>aaa - " . $counter;
					
				//if ($counter > -1) {
				if ($counter > -1) {
					//if ($comments) echo "<br><br>aaa";
					//if ($comments) $featurestructure->printFeatureStructureRecursive();
		
					if ($comments) echo "<br><br>before to semantic";
					//$featurestructure->changeSemanticFeaturesToLanguageFeatures($targetLanguageID);
					$semanticFS = $featurestructure->getSemanticCopy();
					if ($comments) echo "<br><br>after semantic";
					if ($comments) $semanticFS->printFeatureStructureRecursive();
					$targetFS = $semanticFS->getRecursiveTargetCopy($targetLanguageID);
					if ($comments) echo "<br><br>target fs...";
					if ($comments) $targetFS->printFeatureStructureRecursive();
					FeatureStructure::SetWordFeaturesRecursively($targetFS, $words, $targetwordclassefeatures, $wordsByConceptID, $comments);
					if ($comments) echo "<br><br>after SetWordFeaturesRecursively";
					if ($comments) $targetFS->printFeatureStructureRecursive();
						
					if ($comments) echo "<br><br>original fs";
					if ($comments) $featurestructure->printFeatureStructureRecursive();
		
					if ($comments) echo "<br><br>";
					$sentencewords = SyntaxGenerator::generate($targetFS, $targetLanguageID, $generateRules, "", $comments);
					if ($comments) echo "<br><br>generate finished";
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
			}
				
			if ($comments) echo "<br><br>Finished.";
			if ($comments) echo "<br><br>";
			$doublestrings = array();
			//echo "<br>Resultstringcount - " . count($resultstrings);
			echo "{\"resultcount\":\"1\",";
			echo " \"source\":\"" . $sourcesentence . "\",";
			
			echo "\"results\": [";
			$first = false;
			foreach($resultstrings as $index => $str) {
				if (isset($doublestrings[$str])) {
					// ei tulosteta tuplia
				} else {
					//echo "<br>'" . $str . "'";
					if ($first == false) {
						$first = true;
					} else {
						echo ",";
					}
					echo " { \"value\":\"" . $str . "\"}";
					$doublestrings[$str] = 1;
				}
			}
			echo "]}";
		} else {
			echo "{\"resultcount\":\"0\"}";
			//echo "--no reults--";
		}
	}
	

	
	
	public function translatesentenceoldJSONAction() {
	
		
		$targetLanguageID = $_GET['targetlanguageID'];
		
		$comments = false;
		if (isset($_GET['comments'])) {
			$comments = true;
		}
		$sentenceID = $_GET['id'];
		if ($comments) echo "<br>SentenceID - "  . $sentenceID;
		
		$sentence = Table::loadRow("worder_sentences", $sentenceID);
		if ($sentence->correctness == 1) {
			if ($comments) echo "<br>Sentence: " . $sentence->sentence;
		} else {
			if ($comments) echo "<br>Sentence: *" . $sentence->sentence;
		}
		$languageID = $sentence->languageID;
		
		include_once('./modules/worder/_classes/featurestructure.class.php');
		include_once('./modules/worder/_classes/rule.class.php');
		include_once('./modules/worder/_classes/syntaxanalyser.class.php');
		
		
		$sentencewords = explode(' ', strtolower($sentence->sentence));
		
		$wordparam = "";
		$first = true;
		if ($comments) echo "<br>Wordforms";
		foreach ($sentencewords as $index => $wordform) {
			if ($first == true) {
				$first = false;
			} else {
				$wordparam = strtolower($wordparam) . ",";
			}
			if ($comments) echo "<br>Wordfom - " . $index . " -> " . $wordform;
			//$wordparam = $wordparam . "'" . strtolower($wordform) . "'";
			$wordparam = $wordparam . "'" . $wordform . "'";
			//$sentencewords[$index] = strtolower($wordform);
			$sentencewords[$index] = $wordform;
		}
		if ($comments) echo "<br>Formparams - " . $wordparam;
		
		if ($comments) echo "<br>LanguageID - " . $languageID;
		$wordforms = Table::load("worder_wordforms"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND Grammatical=1 AND Wordform IN (" . $wordparam . ")", $comments);
		$foundwords = array();
		// pitäisi listata seuraavaksi kaikki wordID:t jotka löytyy.
		
		// tässä on mahdollista löytyä useampia vaihtoehtoja...
		if ($comments) echo "<br>Found words:";
		foreach ($wordforms as $index => $wordform) {
			if ($comments) echo "<br> -- found forms - " . $index . " - " . $wordform->wordform . " - " . $wordform->wordID;
			$foundwords[$wordform->wordID] = $wordform->wordID;
		}
		
		//if ($comments) echo "<br><br>foundwords count:" . count($foundwords);
		$words = Table::loadWhereInArray("worder_words","WordID", $foundwords, "WHERE GrammarID=" . $_SESSION['grammarID']);
		$conceptlinks = Table::loadWhereInArray("worder_conceptwordlinks", "WordID", $foundwords, "WHERE GrammarID=" . $_SESSION['grammarID'], $comments, " ORDER BY Sortorder");
		$conceptlist = array();
		
		if ($comments) echo "<br><br>Conceptlinks:";
		foreach($conceptlinks as $rowID => $conceptlink) {
			if ($comments) echo "<br>Concepts - " . $conceptlink->wordID . " -- " . $conceptlink->conceptID;
			$conceptlist[$conceptlink->conceptID] = $conceptlink->conceptID;
		}
		
		$loadedconcepts = Table::loadWhereInArray("worder_concepts","ConceptID",$conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID'], $comments);
		
		$concepts = array();
		foreach($conceptlinks as $rowID => $conceptlink) {
			$concept = $loadedconcepts[$conceptlink->conceptID];
			$concepts[$conceptlink->conceptID] = $concept;
		}
		
		
		// Tämä taulu sisältää wordID:lle löytyneet conceptit
		$foundwordconcepts = array();
		
		if ($comments) echo "<br><br>Concepts:";
		foreach($concepts as $rowID => $concept) {
			foreach($conceptlink as $rowID => $conceptlink) {
				if ($conceptlink->conceptID = $concept->conceptID) {
						
				}
			}
		}
		
		$foundwordconcepts = array();
		$wordtable = array();
		$countarray = array();
		$wordindex = 0;
		$errors = false;
		foreach($sentencewords as $index => $sentenceword) {
			$wordarray = array();
			if ($comments) echo "<br> -- processsing sentenceindex - " . $index . " - " . $sentenceword;
			$counter = 0;
			foreach($wordforms as $rowID => $wordform) {
				if ($wordform->wordform == $sentenceword) {
					if ($comments) echo "<br> -- -- wordform found - " . $wordform->wordform . " - wordID: " . $wordform->wordID . " - formID:" . $wordform->rowID . " - word: " . $words[$wordform->wordID]->lemma;
					$wordarray[$counter] = $wordform;
					$counter++;
					$onefound = false;
						
					foreach($conceptlinks as $index2 => $conceptlink) {
						if ($conceptlink->wordID == $wordform->wordID) {
							if ($onefound == true) {
								if ($comments) echo "<br> -- -- -- conceptlink found other - " . $conceptlink->conceptID . " - " . $concepts[$conceptlink->conceptID]->name;
								$copy = $wordform->getCopy();
								$copy->conceptID = $conceptlink->conceptID;
								$wordarray[$counter] = $copy;
								$counter++;
							} else {
								if ($comments) echo "<br> -- -- -- conceptlink found - " . $conceptlink->conceptID . " - " . $concepts[$conceptlink->conceptID]->name;
		
								$copy = $wordform->getCopy();
								$copy->conceptID = $conceptlink->conceptID;
								$wordarray[$counter-1] = $copy;
								$onefound = true;
								//$wordarray[$counter-1] = $wordform;
							}
						}
					}
				}
			}
				
			if ($counter == 0) {
				if ($comments) echo "<br>No lemma found for wordform a1 " . $sentenceword;
				//return AnalyseController::sendError("No wordoform found for '" . $sentenceword . "'");
				//$errors = true;
				echo "{\"resultcount\":\"0\"}";
				return;
				//exit();  // TODO: Pitää palauttaa errortailukko
			}
				
			$wordtable[$wordindex] = $wordarray;
			$countarray[$wordindex] = $counter;
			$wordindex++;
		}
		
		$wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);
		$arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$wordclassefeatures = Table::load('worder_wordclassfeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$targetwordclassefeatures = Table::load('worder_wordclassfeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $targetLanguageID);
		
		FeatureStructure::$wordclasses = $wordclasses;
		SyntaxAnalyser::$wordclasses = $wordclasses;
		FeatureStructure::$features = $features;
		FeatureStructure::$components = $components;
		FeatureStructure::$arguments = $arguments;
		
		//echo "<br><br>Loading rules...";
		
		
		
		$analyseRules = $this->getRules($_SESSION['grammarID'], $_GET['rulesetID'], $languageID, 'analyse', false);
		
		$combinations = array();
		$currentarray = array();
		
		foreach($wordtable as $index1 => $wordformss) {
			if ($comments) echo "<br>Wordtable - ";
				
			foreach($wordformss as $index2 => $wordformi) {
				if ($comments) echo "," . $wordformi->rowID;
			}
		}
		$this->generateCombinationsRecursively($combinations, $currentarray, 0, $wordtable);
		
		if ($comments) echo "<br>Combinations";
		foreach($combinations as $index => $combination) {
			$sentencearray = array();
			if ($comments) echo "<br>";
			foreach($combination as $xpos => $targetindex) {
				$wordform = $wordtable[$xpos][$targetindex];
				if ($comments) echo "<br>combi - " . $wordform->wordID . ":" . $wordform->conceptID . ":" . $wordform->rowID . " - " . $wordform->wordform;
				//echo "<br>compifeatreues";
				//print_r($wordform->features)
				$sentencearray[] = $wordform;
			}
		}
		if ($comments) echo "<br><br><br>";
		
		$featuresentences = array();
		foreach($combinations as $index => $combination) {
				
			$sentencearray = array();
			foreach($combination as $xpos => $targetindex) {
				$wordform = $wordtable[$xpos][$targetindex];
				$sentencearray[] = $wordform;
			}
			$featuresentences[] = $this->generateFeatureStructureSentence($sentencearray, $words, $concepts, $wordclasses, $arguments, $components, $features, $languageID, $wordclassefeatures, false);
		}
		
		if ($comments) echo "<br><br>";
		$resultsarray = array();
		foreach($featuresentences as $index => $featuresentence) {
			// analyse featuresentence...
			if ($comments) {
				echo "<br> ---------------------------------------<br>";
				echo "<br> Analyse featuresentence<br>";
				echo "<br> ---------------------------------------<br>";
				foreach($featuresentence as $index => $featurestructure) {
					$featurestructure->printFeatureStructure();
					echo "<br><br>";
				}
			}
			
			$results = SyntaxAnalyser::analyse($featuresentence, $analyseRules, $comments);
			if ($results != null) {
				foreach($results as $i2 => $resultfeature) {
					$resultsarray[] = $resultfeature;
				}
			}
		}
		
		if ($comments) echo "<br>Found results: " . count($resultsarray);
		if ($comments) echo "<br><br><br><br>---------------------------------------------------------";
		if ($comments) echo "<br>-------------------------------------------------------------";
		if ($comments) echo "<br>-------------------------------------------------------------";
		if ($comments) echo "<br><br><br><br>Translate...";
		if ($comments) echo "<br>-------------------------------------------------------------";
		if ($comments) echo "<br>-------------------------------------------------------------";
		if ($comments) echo "<br>-------------------------------------------------------------";
		
		
		if (count($resultsarray) > 0) {
			include_once('./modules/worder/_classes/syntaxgenerator.class.php');
			
			SyntaxGenerator::$wordclasses = $wordclasses;
			SyntaxGenerator::$features = $features;
			
			
			$generateRules = $this->getRules($_SESSION['grammarID'], $_GET['rulesetID'], $targetLanguageID, 'generate', false);
			
			$resultstrings = array();
			$counter = 0;
			
			// Haetaan kaikki conceptID:t, vai löytyykö nämä jo ennestään
			$targetconceptlist = array();
			foreach($resultsarray as $index => $featurestructure) {
				$featurestructure->getConceptsRecursively($targetconceptlist);
			}				
			
			if ($comments) {
				foreach($targetconceptlist as $index => $conceptID) {
					echo "<br>Targetconcepts - " . $conceptID;
				}
			}
			$foundconceptlinks = Table::loadWhereInArray("worder_conceptwordlinks", "ConceptID", $targetconceptlist, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $targetLanguageID . " AND Defaultword=1", $comments, " ORDER BY Sortorder");
			$wordlist = array();
			$wordsByConceptID = array();
			foreach($foundconceptlinks as $index => $link) {
				if ($comments) echo "<br>Link - " . $link->conceptID . " - " . $link->wordID;
				$wordlist[$link->wordID] = $link->wordID;
				$wordsByConceptID[$link->conceptID] = $link->wordID;
			}
			$words = Table::loadWhereInArray("worder_words","WordID", $wordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
			
			
			foreach($resultsarray as $index => $featurestructure) {
				//if ($comments) echo "<br><br>aaa - " . $counter;
					
				//if ($counter > -1) {
				if ($counter > -1) {
					//if ($comments) echo "<br><br>aaa";
					//if ($comments) $featurestructure->printFeatureStructureRecursive();
						
					if ($comments) echo "<br><br>before to semantic";
					//$featurestructure->changeSemanticFeaturesToLanguageFeatures($targetLanguageID);
					$semanticFS = $featurestructure->getSemanticCopy();
					if ($comments) echo "<br><br>after semantic";
					if ($comments) $semanticFS->printFeatureStructureRecursive();
					$targetFS = $semanticFS->getRecursiveTargetCopy($targetLanguageID);
					if ($comments) echo "<br><br>target fs...";
					if ($comments) $targetFS->printFeatureStructureRecursive();
					FeatureStructure::SetWordFeaturesRecursively($targetFS, $words, $targetwordclassefeatures, $wordsByConceptID, $comments);
					if ($comments) echo "<br><br>after SetWordFeaturesRecursively";
					if ($comments) $targetFS->printFeatureStructureRecursive();
					
					if ($comments) echo "<br><br>original fs";
					if ($comments) $featurestructure->printFeatureStructureRecursive();
						
					if ($comments) echo "<br><br>";
					$sentencewords = SyntaxGenerator::generate($targetFS, $targetLanguageID, $generateRules, "", $comments);
					if ($comments) echo "<br><br>generate finished";
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
			}
			
			if ($comments) echo "<br><br>Finished.";
			if ($comments) echo "<br><br>";
			$doublestrings = array();
			//echo "<br>Resultstringcount - " . count($resultstrings);
			echo "{\"resultcount\":\"1\",";
				
			echo "\"results\": [";
			$first = false;
			foreach($resultstrings as $index => $str) {
				if (isset($doublestrings[$str])) {
					// ei tulosteta tuplia
				} else {
					//echo "<br>'" . $str . "'";
					if ($first == false) {
						$first = true;
					} else {
						echo ",";
					}
					echo " { \"value\":\"" . $str . "\"}";
					$doublestrings[$str] = 1;
				}
			}
			echo "]}";
		} else {
			echo "{\"resultcount\":\"0\"}";
			//echo "--no reults--";
		}
	}
	
	
	
	// Kopioitus AnalyseController.getRulesFull
	private function getRules($grammarID, $rulesetID, $languageID, $direction, $comments = false) {
	
		
		
		//$comments = true;
		include_once('./modules/worder/_classes/featurestructure.class.php');
		include_once('./modules/worder/_classes/rule.class.php');
		
		if ($direction == 'analyse') { // analyse
			$rulestructstemp = Table::load("worder_rules","WHERE GrammarID=" . $grammarID . " AND (Analyse=1 OR Analyse=2) AND LanguageID=" . $languageID . " AND Status>0 ORDER BY Sortorder", $comments);
		}
		if ($direction == 'generate') { // generate
			$rulestructstemp = Table::load("worder_rules","WHERE GrammarID=" . $grammarID . " AND Generate=1 AND LanguageID=" . $languageID . " AND Status>0 ORDER BY Sortorder", $comments);
		}
		
		$rulestructs = null;
		
		// RulesetID pitäisi antaa parametrina...
		if ($rulesetID > 0) {
			if ($comments) echo "<br>Rulesetti on asetettu...";

			//$setstr = $_GET['rulesetID'];
			//$rulesetID = $_GET['rulesetID'];
			if ($rulesetID == NULL) $rulesetID = 0;
			if ($rulesetID == "") $rulesetID = 0;
			
			
			if ($rulesetID == 0) {
				$rulestructs = $rulestructstemp;
			} else {
				$rulestructs = array();
				$rulelinks = Table::load("worder_rulesetlinks","WHERE GrammarID=" . $grammarID . " AND SetID=" . $rulesetID, $comments);
				foreach($rulelinks as $index => $link) {
					if (isset($rulestructstemp[$link->ruleID])) {
						$rulestructs[$link->ruleID] = $rulestructstemp[$link->ruleID];
					}
				}
			}
			
			foreach($rulestructstemp as $index => $temprule) {
				if (($temprule->generate == 2) || ($temprule->analyse == 2)) {
					$rulestructs[$temprule->ruleID] = $temprule;
				}
			}
		} else {
			//$rulestructs = $rulestructstemp;
		}
		if ($comments) echo "<br>Rulesetti on asetettu... " . count($rulestructs);
		
		
		$ruleterms = Table::load('worder_ruleterms', "WHERE GrammarID=" . $grammarID);
		$featureagreements = Table::load('worder_rulefeatureagreements', "WHERE GrammarID=" . $grammarID);
		$featureconstraints = Table::load('worder_rulefeatureconstraints', "WHERE GrammarID=" . $grammarID);
		$componentrequirements = Table::load('worder_rulecomponentrequirements', "WHERE GrammarID=" . $grammarID);
		$resultfeatures = Table::load('worder_ruleresultfeatures', "WHERE GrammarID=" . $grammarID);
	
		$rules = array();
		foreach($rulestructs as $index => $rulestruct) {
					
			$rule = new Rule($rulestruct->name, $rulestruct->wordclassID, $rulestruct->analyse, $rulestruct->conceptID);
			$rule->languageID = $rulestruct->languageID;
			if ($rulestruct->conceptID > 0) {
				$concept = Table::loadRow("worder_concepts", $rulestruct->conceptID);
				$argustrings = explode('|', $concept->arguments);
				foreach($argustrings as $index => $value) {
					$argvalue = explode(':', $value);
					$rule->addConceptArgument($argvalue[0], $argvalue[1], $argvalue[2]);
				}
				$rule->setConceptName($concept->name);
			}
			
			$rule->setRuleID($rulestruct->ruleID);
			if ($comments) echo "<br>rule - " . $rulestruct->name . ", " . $rulestruct->wordclassID . ", ruleID:" . $rulestruct->ruleID;
	
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
						if ($comments) echo "<br>Addterm - position:" . $ruleterm->position . "," . $ruleterm->argumentID . "," . $ruleterm->wordclassID;
					}
				}
			}
	
			if ($featureagreements != null) {
				foreach($featureagreements as $index => $featureagreement) {
					if ($rulestruct->ruleID == $featureagreement->ruleID) {
						if ($comments) echo "<br>addFeatureAgreement - position:" . $featureagreement->position1 . "," . $featureagreement->position2 . "," . $featureagreement->featureID;
						$rule->addFeatureAgreement($featureagreement->position1, $featureagreement->position2, $featureagreement->featureID);
					}
				}
			}
	
			if ($featureconstraints != null) {
				foreach($featureconstraints as $index => $featureconstraint) {
					if ($rulestruct->ruleID == $featureconstraint->ruleID) {
						if ($comments) echo "<br>ruleconstraint - " . $ruleterm->ruleID . " - " . $featureconstraint->ruleID;
						if ($comments) echo "<br>addConstraint - position:" . $featureconstraint->position . "," . $featureconstraint->featureID . "," . $featureconstraint->featurevalueID;
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
			
			//echo "<br>Loading resultfeatures ... " . $rulestruct->name . " - ruleID:" . $rulestruct->ruleID;
			if ($resultfeatures != null) {
				foreach($resultfeatures as $index => $resultfeature) {
					if ($rulestruct->ruleID == $resultfeature->ruleID) {
						//echo "<br> resultfeatures founr ... " . $resultfeature->featureID . " - " . $resultfeature->valueID . " - " . $resultfeature->position;
						$rule->addResultFeature($resultfeature->featureID, $resultfeature->valueID, $resultfeature->position);
					}
				}
			}
			$rules[] = $rule;
		}
		
		if ($comments) echo "<br>Rulessi found - " . count($rules);
		return $rules;
	}
	
	
	// Kopioitus analyse.printeSentence
	private function generateFeatureStructureSentence($sentencearray, &$words, &$concepts, &$wordclasses, &$arguments, &$components, &$features, $languageID, &$wordclassfeatures, $comments = false) {
	
		$featurestructures = array();
		
		foreach($sentencearray as $index => $wordform) {
	
			if ($comments) echo "<br>Processing wordform - " . $wordform->wordform;
			$wordID = $wordform->wordID;
			$word = $words[$wordID];
			$conceptID = $wordform->conceptID;
				
			$concept = null;
			if ($conceptID > 0) {
				$concept = $concepts[$conceptID];
			}
			$wordclassID = $word->wordclassID;
			$wordclass = $wordclasses[$wordclassID];
	
			if ($comments) {
				if ($concept == null) {
					echo "<br><br>Setting Word - " . $wordID . " - no concept";
				} else {
					echo "<br><br>Setting Word - " . $wordID . " - " . $conceptID . " - " . $concept->name;
					echo "<br><br> --- " . $word->lemma. " - " . $concept->name;
				}
			}
	
			$featurestructure = new FeatureStructure($word->lemma,$wordclassID);
			$featurestructure->setLanguageID($languageID);
			$featurestructure->setWordForm($wordform->wordform);
			$featurestructure->setWordID($word->wordID);
			$featurestructure->setFormID($wordform->rowID);
			$featurestructure->setConceptID($conceptID);
				
			// arguments -- ei ole alimmalla tasolla asetettu (normikeississä ainakaan)
			// requirements -- nämä tulee conceptista, pitäisi olla jo nyt ladattuna
				
			if ($concept != null) {
				$requirementlist = explode('|', $concept->arguments);
				$classarguments = array();
				if ($comments) echo "<br>Requirements - " .  $concept->arguments;
					
				//echo "<br>Requirements count - " . count($argumentlist);
				foreach($requirementlist as $requirementindex => $requirementline) {
					if ($requirementline != "") {
						//echo "<br>Argumentline - " . $requirementline;
						$requirementitems = explode(":", $requirementline);
						$argumentID = $requirementitems[0];
						$argument = $arguments[$argumentID];
						$componentID = $requirementitems[1];
						//echo "<br>ComponentID - " . $componentID;
						$component = $components[$componentID];
						if ($comments) echo "<br>adding requirement - " . $argument->name . " - " . $component->name;
						$featurestructure->addArgumentRequirement($argumentID,$componentID);
					}
				}
			}
				
				
			//echo "<br>Features - " .  $wordform->features;
			//echo "<br>Requirements count - " . count($argumentlist);
				
			if ($comments) echo "<br> ----- create features...";
			//echo "<br>";
			//print_r($wordform);
			//echo "<br>";
	
				
			foreach($wordform->features as $featureindex => $valueID) {
				if ($comments) echo "<br>Featurevalue - " . $valueID;
				$value = $features[$valueID];
				if ($value->parentID == 0) {
					if ($comments) echo "<br> -- parentti on nolla";
					$featureID = $valueID;
				} else {
					if ($comments) echo "<br> -- otetaan valueparentti";
					$featureID = $value->parentID;
				}
				$feature = $features[$featureID];
				if ($comments) echo "<br>adding featue - " . $feature->name . " - " . $value->name . " (FeatureID:" . $feature->featureID . ")";
				$featurestructure->addFeature($featureID,$valueID);
			}
				
			if ($comments) echo "<br> -- checking wordclassfeatures " . count($wordclassfeatures);
			foreach($wordclassfeatures as $wcindex => $wordclassfeature) {
	
				if ($wordclassfeature->wordclassID == $wordclass->wordclassID) {
					if ($featurestructure->hasFeature($wordclassfeature->featureID)) {
						$feature = $features[$wordclassfeature->featureID];
						if ($comments) echo "<br> -- checking feature " . $feature->name . " - found, default = " . $wordclassfeature->defaultvalueID;
	
					} else {
						$feature = $features[$wordclassfeature->featureID];
						if ($comments) echo "<br> -- checking feature " . $feature->name . " - not found";
						if ($wordclassfeature->defaultvalueID > 0) {
							$featurestructure->addFeature($wordclassfeature->featureID,$wordclassfeature->defaultvalueID);
							$default =  $features[$wordclassfeature->defaultvalueID];
							if ($comments) echo "<br> -- -- Setting default - " . $default->name;
						}
					}
				} else {
					if ($comments) echo "<br> ---  "  . $wordclassfeature->wordclassID . " - " . $wordclass->wordclassID;
				}
			}
				
			if ($word->features != "") {
				$featurelist = explode("|",$word->features);
				foreach($featurelist as $f1 => $featurecompo) {
					if ($featurecompo != "") {
						$featurecomps = explode(":", $featurecompo);
						$feature = $features[$featurecomps[0]];
						$value = $features[$featurecomps[1]];
						//if ($comments) echo "<br>adding word feature - " . $feature->name . " - " . $value->name;
						$featurestructure->addFeature($feature->featureID,$value->featureID);
					}
				}
			}
				
			if ($concept != null) {
				if ($comments) echo "<br>Components - " . $concept->components;
				if ($concept->components != "") {
					$componentlines = explode("|", $concept->components);
					foreach($componentlines as $componentindex => $componentline) {
						$componentlineitems = explode(":", $componentline);
						if ($componentlineitems[0] != "") {
							$componentID = $componentlineitems[0];
							$component = $components[$componentID];
							if ($comments) echo "<br>adding component - " . $component->name;
							$featurestructure->addComponent($componentID);
						}
					}
				}
			}
			$featurestructures[] = $featurestructure;
			
		}	
		return $featurestructures;
	}
	
	
	

	// Kopioitus analyse.generateCombinationsRecursively
	private function generateCombinationsRecursively(&$resultarray, &$currentarray, $xpos, &$sourcearray) {
	
	
		if ($xpos == count($sourcearray)) {
			$result = array();
			for($index = 0;$index < $xpos; $index++) {
				$result[$index] = $currentarray[$index];
			}
			$resultarray[] = $result;
			return;
		}
	
		for($ypos = 0;$ypos < count($sourcearray[$xpos]); $ypos++) {
			$currentarray[$xpos] = $ypos;
			$this->generateCombinationsRecursively($resultarray, $currentarray, $xpos+1, $sourcearray);
		}
		return;
	}
	


	// Jaetaan lause osiin, tyypillisesti välilyönnillä erottaen
	// mutta myös välimerkit täytyy ottaa ainakin joissain tapauksissa
	// huomioon (ainakin pilkut keskellä lausetta ja kysymysmerkit lauseen lopussa
	// toistaiseksi pistettä lauseen lopussa ei ehkä huomioida.
	// Ongelmana saattaa olla erilaiset pisteen käyttötavat: lyhenteen merkki, järjestysnumerot, päivämäärät jne.
	// päivämäärä pitäisi ainakin detectoida erikseen.
	public function splitsentence($sentence) {
	
		$parts = array();
		$word = "";
	
		$ended = false;
		$ignore = false;
	
		for($i=0; $i<strlen($sentence); $i++) {
				
			$chr = $sentence[$i];
			$word = $word . $chr;
	
			if ($ended == true) {
				echo "<br>Characters after wordend.";
				exit();
			}
				
			if ($ignore == true) {
	
				// Sulkee aiemmin alkaneen sulun. Käytetään sanan erottmena
				if ($chr == ')') {
					$ignore = false;
					if ($word != "") $parts[] = $word;
					$word = "";
				}
			} else {
	
				// Pilkkua ei toistaiseksi käsitellä omana lauseen osana,
				// käsitellään sanavälinä
				if ($chr== ',') {
					if ($word != "") $parts[] = $word;
					$word = "";
				}
					
				// lyhenteitä ja numeraaleja ei toistaiseksi käsitellä
				// pistettä ei toistaiseksi käsitellä muuten kuin sanan lopettavana merkkinä
				// ilmoitetaan toisaiseksi virheenä jos ei ole lauseen viimeinen merkki
				if ($chr == '.') {
					if ($word != "") $parts[] = $word;
					$word = "";
					$ended = true;
				}
					
				// Kysymysmerkki huomioidaan omana lauseen osana, se muuttaa toteamuksen kysymyslauseeksi
				// Pitää olla lauseen viimeinen merkki
				if ($chr == '?') {
					if ($word != "") $parts[] = $word;
					$parts[] = "?";
					$word = "";
				}
					
				// Huuotomerkki huoimioidaan omana lauseenaan sanan lopussa, se muuttaa toteamuslauseen komennoksi
				// tai tervehdys/toivotuslauseen huudahdukseksi / innostuneeksi
				if ($chr == '!') {
					if ($word != "") $parts[] = $word;
					$parts[] = "!";
					$word = "";
				}
	
				// Sulkumerkkien sisällä olevat merkkijono ignoroidaan kunnes saavutaan sulkevalle merkille
				if ($chr == '(') {
					$ignore = true;
				}
	
				// Sulkee aiemmin alkaneen sulun. Käytetään sanan erottmena
				if ($chr == ')') {
					echo "<br>Sulkeva sulkumerkki ennen alkua";
					exit;
				}
	
				// käsitellään sanan erottimena, useampi välilyönti ignoroidaan
				if ($chr == ' ') {
					if ($word != "") $parts[] = $word;
					$word = "";
				}
					
			}
		}
		if ($word != "") $parts[] = $word;
	
		return $parts;
	}
	
	


	public function insertsentencesetsentenceAction() {
	
		$setID =  $_GET['setID'];
		$sentenceID =  $_GET['sentenceID'];
		$sentence = Table::loadRow("worder_sentences", $sentenceID);
	
		$values = array();
		$values['SetID'] = $setID;
		$values['LanguageID'] = $sentence->languageID;
		$values['SentenceID'] = $sentenceID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_sentencesetlinks", $values, false);
	
		redirecttotal('worder/sentences/showsentenceset&id=' . $setID,null);
	
	}
	
	
	
}
