<?php


class TranslateController extends AbstractController {

	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->translateAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	
	
	public function translateAction() {

		if (!isset($_SESSION['sourcesetID'])) $_SESSION['sourcesetID'] = 0;
		if (!isset($_SESSION['targetsetID'])) $_SESSION['targetsetID'] = 0;
		if (!isset($_SESSION['sourcelanguageID'])) $_SESSION['sourcelanguageID'] = 1;
		if (!isset($_SESSION['targetlanguageID'])) $_SESSION['targetlanguageID'] = 1;
		
		
		$this->registry->sourcelanguageID = $_SESSION['sourcelanguageID'];
		if (isset($_GET['sourcelanguageID'])) {
			$this->registry->sourcelanguageID = $_GET['sourcelanguageID'];
			$_SESSION['sourcelanguageID'] = $_GET['sourcelanguageID'];
		}
		if (isset($_GET['targetlanguageID'])) {
			$this->registry->targetlanguageID = $_GET['targetlanguageID'];
			$_SESSION['targetlanguageID'] = $_GET['targetlanguageID'];
		}
		$this->registry->targetlanguageID = $_SESSION['targetlanguageID'];
		
		if (isset($_GET['sourcesetID'])) {
			$this->registry->sourcesetID = $_GET['sourcesetID'];
			$_SESSION['sourcesetID'] = $_GET['sourcesetID'];
		}
		if (isset($_GET['targetsetID'])) {
			$this->registry->targetsetID = $_GET['targetsetID'];
			$_SESSION['targetsetID'] = $_GET['targetsetID'];
		}
		$this->registry->sourcesetID = $_SESSION['sourcesetID'];
		$this->registry->targetsetID = $_SESSION['targetsetID'];
		
		/*
		if (isset($_GET['sentence'])) {
			//setModuleSessionVar('activesentence', $_GET['sentence']);
			$_SESSION['activesentence'] = $_GET['sentence'];
		}
		*/
		
		if (isset($_SESSION['activesentence'])) {
			$this->registry->sentence = $_SESSION['activesentence'];
		} else {
			$this->registry->sentence = "";
			$_SESSION['activesentence'] = "";
		}
		//$this->registry->sentence = getModuleSessionVar('activesentence');
		//echo "<br>sourcelangauge - " . $sourcelanguageID;
		//echo "<br>targetlanguageID - " . $targetlanguageID;
		
		updateActionPath("Translate");
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		if (!isset($this->registry->languages[$this->registry->sourcelanguageID])) {
			$this->registry->sourcelanguageID = current($this->registry->languages)->languageID;
			$_SESSION['sourcelanguageID'] = $this->registry->sourcelanguageID;
		}
		if (!isset($this->registry->languages[$this->registry->targetlanguageID])) {
			$this->registry->targetlanguageID = current($this->registry->languages)->languageID;
			$_SESSION['targetlanguageID'] = $this->registry->targetlanguageID;
		}
		
		$this->registry->wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclassarguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->rulesets= Table::load("worder_rulesets", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->lastsentence = getModuleSessionVar('lastsentence');
		$this->registry->analysePauseOnCheckingRule = getModuleSessionVar('analyserPauseOncheckingRule','0');
		$this->registry->analysePauseOnApplyRule = getModuleSessionVar('analyserPauseOnApplyRule','0');
		$this->registry->analysePauseOnReverseRule = getModuleSessionVar('analyserPauseOnReverseRule','0');
		$this->registry->analysePauseOnResultFound = getModuleSessionVar('analyserPauseOnResultFound','0');
		
		$this->registry->generatePauseOnCheckingRule = getModuleSessionVar('generatorPauseOncheckingRule','0');
		$this->registry->generatePauseOnApplyRule = getModuleSessionVar('generatorPauseOnApplyRule','0');
		$this->registry->generatePauseOnReverseRule = getModuleSessionVar('generatorPauseOnReverseRule','0');
		$this->registry->generatePauseOnResultFound = getModuleSessionVar('generatorPauseOnResultFound','0');
		$this->registry->template->show('worder/translate','translate');
		
	}
	
	

	public function updateanalysepauseAction() {
		$item = $_GET['item'];
		$check = $_GET['check'];
		$checkvalue = 0;
		if ($check == true) $checkvalue = 1;
		if ($item == 1) setModuleSessionVar('analyserPauseOncheckingRule',$checkvalue);
		if ($item == 2) setModuleSessionVar('analyserPauseOnApplyRule',$checkvalue);
		if ($item == 3) setModuleSessionVar('analyserPauseOnReverseRule',$checkvalue);
		if ($item == 4) setModuleSessionVar('analyserPauseOnResultFound',$checkvalue);
		echo "1";
		return;
	}
	

	// Asetetaan sessionmuuttujiin generatetaulun checkboxien arvot
	public function updategeneratepauseAction() {
		$item = $_GET['item'];
		$check = $_GET['check'];
		$checkvalue = 0;
		if ($check == true) $checkvalue = 1;
		if ($item == 1) setModuleSessionVar('generatorPauseOncheckingRule',$checkvalue);
		if ($item == 2) setModuleSessionVar('generatorPauseOnApplyRule',$checkvalue);
		if ($item == 3) setModuleSessionVar('generatorPauseOnReverseRule',$checkvalue);
		if ($item == 4) setModuleSessionVar('generatorPauseOnResultFound',$checkvalue);
		echo "1";
		return;
	}
	
	

	public function getwordclassfeaturesJSONAction() {
	
		$comments = false;
		if (isset($_GET['comments'])) $comments = true;
		$languageID = $_GET['languageID'];
		$conceptstr = $_GET['concepts'];
		$conceptlist = explode(':', $conceptstr);
		$conceptsarray = array();
		foreach($conceptlist as $index => $conceptID) {
			$conceptarray[$conceptID] = $conceptID;
		}
	
		//$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
	
		
		// Tämä ottaa linkitettynä ainoastaan oletussanan, eli käsitteen useampia sanavariantteja ei oteta...
		// En tiedä onko ihan tarpeenkaan, mutta käyttöliittymän translaten generate tuottaa väärän tuloksen, ehkä
		// tilanne ei ole sama backendissä suoritettavassa tapauksessa (tätä ei kutsuta muualta kuin translaten generatessa) ...
		$conceptlinks = Table::loadWhereInArray("worder_conceptwordlinks", "ConceptID", $conceptarray, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND Defaultword=1", $comments);
		$foundwords = array();
		$conceptlookup = array();
	
		if ($comments) echo "<br><br>Conceptlinks:";
		foreach($conceptlinks as $rowID => $conceptlink) {
			if ($comments) echo "<br>Concepts - " . $conceptlink->wordID . " -- " . $conceptlink->conceptID;
			$foundwords[$conceptlink->wordID] = $conceptlink->wordID;
			$conceptlookup[$conceptlink->wordID] = $conceptlink->conceptID;
		}
	
	
		$words = Table::loadWhereInArray("worder_words","WordID", $foundwords, "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		$wordclasslist = array();
		foreach($words as $index=> $word) {
			$wordclasslist[$word->wordclassID] = $word->wordclassID;
		}
		
		$wordclassfeatures = array();
		foreach($wordclasslist as $temp => $wordclassID) {
			$wordclassfeatures[$wordclassID] = Table::load("worder_wordclassfeatures", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND WordclassID=" . $wordclassID);
			if ($comments) echo "<br> wordclass - " . $wordclassID  ." - count: "  . count($wordclassfeatures[$wordclassID]);
		}
		if ($comments) echo "<br>";
		
		
		
		if ($comments) echo "<br><br><br>";
	
		echo "{";
	
		$firstword = true;
		echo " \"words\": [";
		foreach($words as $index => $word) {
	
			if ($firstword == true) {
				$firstword = false;
			} else {
				echo ",";
			}
	
			echo "{ \"wordID\":\"" . $word->wordID . "\"";
			echo ", \"conceptID\":\"" . $conceptlookup[$word->wordID] . "\"";
			echo ", \"wordclassfeatures\": {";
	
			// TODO: ehkä tähän pitäisi asettaa default valuessit mikäli näitä ei ole asetettu?
			if ($word->features != "") {
				$featurelist = explode("|", $word->features);
			} else {
				$featurelist = array();
			}
			
			$first = false;
			foreach($wordclassfeatures[$word->wordclassID] as $index => $wordclassfeature) {
				
				if (($wordclassfeature->inflectional == 0) || ($wordclassfeature->inflectional == 1)) {
					$featureID = $wordclassfeature->featureID;
					$valueID = $wordclassfeature->defaultvalueID;
					
					
					foreach($featurelist as $index => $featurestr) {
						$featurevalues = explode(":", $featurestr);
					
						$listfeatureID = $featurevalues[0];
						$listvalueID = $featurevalues[1];
							
						if ($listfeatureID == $featureID) {
							$valueID = $listvalueID;
						}
						//$feature = $features[$featurevalues[0]];
						//$value = $features[$featurevalues[1]];
					}
					
					if ($valueID > 0) {
						if ($first == false) {
							$first = true;
						} else {
							echo ",";
						}
						echo "\"" . $featureID . "\":\"" . $valueID . "\"";
					}
				}
				
				
			}
			echo "}";
			echo "}";
		}
		echo "]";
		echo " }";
	}
	
	// Tätä käytetään analyse-näkymällä lauseen analysointiin.
	public function analysewordsJSONAction() {
	
		$comments = false;
		if (isset($_GET['comments'])) {
			$comments = true;
		}
	
		include_once('./modules/worder/_classes/featurestructure.class.php');
		include_once('./modules/worder/_classes/rule.class.php');
	
		$languageID = $_GET['languageID'];
	
		// Tähän pitäisi huomioida välilyönnillä yhdistetyt lauseet, niitä on ainakin englannissa
		// Tähän pitäisi tehdä tietokantaan joku kikka, että osaisi etsiä niitä, eli alkuosan wordformissa pitäisi olla merkintä esim.
		$sentencewords = explode(' ', $_GET['sentence']);
		if (isset($_GET['sentence'])) {
			if ($comments) echo  "<br>SentenceJSON1 - " . $_GET['sentence'];
			setModuleSessionVar('activesentence',$_GET['sentence']);
		}
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
	
		if ($comments) echo "<br><br>foundwords count:" . count($foundwords);
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
				if ($comments) echo "<br>No lemma found for wordform " . $sentenceword;
				return $this->sendError("No wordoform found for '" . $sentenceword . "'");
				$errors = true;
				exit();  // TODO: Pitää palauttaa errortailukko
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
	
		FeatureStructure::$wordclasses = $wordclasses;
		FeatureStructure::$features = $features;
		FeatureStructure::$components = $components;
		FeatureStructure::$arguments = $arguments;
	
	
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
			//$this->printSentenceString($sentencearray, $words, $concepts, $wordclasses, $arguments, $components, $features, $languageID);
		}
		if ($comments) echo "<br><br><br>";
	
		echo "{";
		//echo " " . count($combinations);
		//$first = false;
	
		echo "	\"sentences\":";
		echo "[";
	
		$first = true;
		foreach($combinations as $index => $combination) {
	
			if ($first == true) {
				$first = false;
			} else {
				echo ", ";
			}
				
			$sentencearray = array();
			foreach($combination as $xpos => $targetindex) {
				$wordform = $wordtable[$xpos][$targetindex];
				//echo "" . $word->wordID . ",";
				$sentencearray[] = $wordform;
			}
			$this->printSentence($sentencearray, $words, $concepts, $wordclasses, $arguments, $components, $features, $languageID, $wordclassefeatures, $comments);
		}
		echo "]";
			
	
		$firstword = true;
		echo ", \"words\": [";
		foreach($words as $index => $word) {
	
			if ($firstword == true) {
				$firstword = false;
			} else {
				echo ",";
			}
				
			echo "{ \"wordID\":\"" . $word->wordID . "\"";
			echo ", \"wordclassfeatures\": {";
			if ($word->features != "") {
				$featurelist = explode("|", $word->features);
				$first = false;
				foreach($featurelist as $index => $featurestr) {
	
					//echo "<br><br>.." . $featurestr . "..<br>" . $word->wordID . "<br>";
					$featurevalues = explode(":", $featurestr);
						
					$feature = $features[$featurevalues[0]];
					$value = $features[$featurevalues[1]];
	
					if ($first == false) {
						$first = true;
					} else {
						echo ",";
					}
					echo "\"" . $feature->featureID . "\":\"" . $value->featureID . "\"";
				}
			}
			echo "}";
			echo "}";
		}
		echo "]";
	
	
		$first = true;
		echo ", \"features\": {";
		foreach($features as $index => $feature) {
			if ($first == true) {
				$first = false;
			} else {
				echo ",";
			}
			echo "\"" . $feature->featureID . "\":\"" . $feature->name . "\"";
		}
		echo " }";
		echo " }";
	}
	
	
	

	// Tätä käytetään analyse-näkymällä lauseen analysointiin.
	public function analysewordsintegerJSONAction() {

		$comments = false;
		if (isset($_GET['comments'])) {
			$comments = true;
		}
		
		include_once('./modules/worder/_classes/featurestructure.class.php');
		include_once('./modules/worder/_classes/rule.class.php');
		
		$languageID = $_GET['languageID'];
		
		// Tähän pitäisi huomioida välilyönnillä yhdistetyt lauseet, niitä on ainakin englannissa
		// Tähän pitäisi tehdä tietokantaan joku kikka, että osaisi etsiä niitä, eli alkuosan wordformissa pitäisi olla merkintä esim.
		$tempsentence = strtolower($_GET['sentence']);
		$_SESSION['activesentence'] = $tempsentence;
		
		$sentencewords = explode(' ', $tempsentence);
		if (isset($_GET['sentence'])) {
			if ($comments) echo  "<br>SentenceJSON1 - " . $_GET['sentence'];
			setModuleSessionVar('activesentence',$_GET['sentence']);
		}
		$wordparam = "";
		$first = true;
		if ($comments) echo "<br>Wordforms";
		foreach ($sentencewords as $index => $wordform) {
			if ($wordform != "") {
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
		}
		if ($comments) echo "<br>Formparams - " . $wordparam;
		
		// etsitään myös välillä yhdistetyt moniosaiset sanat, kahden sanan
		$previousword = null;
		$doublewords = array();
		foreach ($sentencewords as $index => $wordform) {
			if ($previousword == null) {
				if ($wordform != "") {
					$previousword = $wordform;
				} 
			} else {
				if ($wordform != "") {
					if ($first == true) {
						$first = false;
					} else {
						$wordparam = $wordparam . ",";
					}
					if ($comments) echo "<br>Wordfom xxx - " . $index . " -> " . $previousword . " " . $wordform;
					//$wordparam = $wordparam . "'" . strtolower($wordform) . "'";
					$wordparam = $wordparam . "'" . strtolower($previousword) . " " . strtolower($wordform) . "'";
					//$sentencewords[$index] = strtolower($wordform);
					$doublewords[$index] = strtolower($previousword) . " " . strtolower($wordform);
					$previousword = $wordform;
				}
			}
		}
		
		// wordparam-muuttujassa on nyt kaikki mahdolliset sana kombinaatiot, kahdelle sanalle...
		
		
		if ($comments) echo "<br>LanguageID - " . $languageID;
		$wordforms = Table::load("worder_wordforms"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND Grammatical=1 AND Wordform IN (" . $wordparam . ")", $comments);
		$foundwords = array();
		$foundwordforms = array();
		$foundwordsformsall = array();
		// pitäisi listata seuraavaksi kaikki wordID:t jotka löytyy.
		
		// tässä on mahdollista löytyä useampia vaihtoehtoja...
		if ($comments) echo "<br>Found words:";
		foreach ($wordforms as $index => $wordform) {
			if ($comments) echo "<br> -- found forms xxxx - " . $index . " - " . $wordform->wordform . " - " . $wordform->wordID;
			//aa $foundwords[$wordform->wordID] = $wordform->wordID;
			$foundwords[$wordform->wordID] = $wordform->wordID;
			$foundwordsformsall[] = $wordform;
			$foundwordforms[$wordform->wordID] = $wordform;
			$foundwordforms[$wordform->wordID] = $wordform;
		}
				
		if ($comments) echo "<br><br>foundwords count:" . count($foundwords);
		$words = Table::loadWhereInArray("worder_words","WordID", $foundwords, "WHERE GrammarID=" . $_SESSION['grammarID']);
		$conceptlinks = Table::loadWhereInArray("worder_conceptwordlinks", "WordID", $foundwords, "WHERE GrammarID=" . $_SESSION['grammarID'], $comments, " ORDER BY Sortorder");
		$conceptlist = array();
		
		
		if ($comments) echo "<br><br>Conceptlinks:";
		foreach($conceptlinks as $rowID => $conceptlink) {
			if ($comments) echo "<br>Concepts - " . $conceptlink->wordID . " -- " . $conceptlink->conceptID;
			$conceptlist[$conceptlink->conceptID] = $conceptlink->conceptID;
		}
		
		if ($comments) echo "<br><br>";
		$newfoundwords = array();		// tähän lisätään kaikki wordID-conceptID parit...
		foreach($foundwordsformsall as $index => $wordform) {
			$processedwords = array();
			foreach($conceptlinks as $rowID => $conceptlink) {
				if ($conceptlink->wordID == $wordform->wordID) {
					$newform = clone $wordform;
					
					$newform->conceptID = $conceptlink->conceptID;
					
					$newfoundwords[] = $newform;
					
					if ($comments) echo "<br>newfound - " . $wordform->wordID . " - " . $conceptlink->conceptID;
					
					/*
					if (isset($processedwords[$wordID])) {
						$processedform = $foundwordforms[$wordID];
						$wordform = $processedform->getCopy();
						$wordform->conceptID = $conceptlink->conceptID;
						$newfoundwords[] = $wordform;
					} else {
						$wordform = $foundwordforms[$wordID];
						$wordform->conceptID = $conceptlink->conceptID;
						$newfoundwords[] = $wordform;
						$processedwords[$wordID] = $wordID;
					}
					*/
				}
			}
		}
		if ($comments) echo "<br>newfoundwords - " . count($newfoundwords);
		foreach ($newfoundwords as $testID => $test) {
			if ($comments) echo "<br>" . $test->wordform . " - " . $test->conceptID;
		}
		if ($comments) echo "<br><br>";
		
		$loadedconcepts = Table::loadWhereInArray("worder_concepts","ConceptID",$conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID'], $comments);
		if ($comments) echo "<br><br>Loadedconcepts:";
		foreach($loadedconcepts as $conceptID => $concept) {
			if ($comments) echo "<br> -- Concepts - " . $conceptID . " -- " . $concept->conceptID . " -- " . $concept->name;
		}
		$concepts = array();

		if ($comments) echo "<br><br>Process:";
		foreach($conceptlinks as $rowID => $conceptlink) {
			$concept = $loadedconcepts[$conceptlink->conceptID];
			$concepts[$conceptlink->conceptID] = $concept;
			if ($comments) echo "<br> -- " . $conceptlink->conceptID . " - " . $concept->conceptID . " - " . $concept->name;
		}
		
		foreach($newfoundwords as $rowID => $wordform) {
			$concept = $concepts[$wordform->conceptID];
			$wordform->conceptname = $concept->name;
		}
		
		
		// Nyt on haettu kaikki sanat ladattu, pitää vain sopivasti ne käsitellä...
		
		//private function getSentenceCombinations($currentindex, $sentencewords, $foundwords, $currentsentence, $foundsentences) {
		
		//$foundsentences = array();
		$wordtable = array();
		
		//$this->getSentenceCombinations(0, $sentencewords, $wordforms, array(), $wordtable, $comments);
		$this->getSentenceCombinations(0, $sentencewords, $newfoundwords, array(), $wordtable, $comments);
		if ($comments) echo "<br><br>getSentenceCombinations done - " . count($wordtable);
		
		// Nyt on wordtablessa tarvittavat tiedot wordistä concepteineen...
		
		
		
		if ($comments) echo "<br><br>--------------------------";
		
		
		foreach($wordtable as $index => $sentencewords) {
			foreach($sentencewords as $index3 => $wordform) {
				if ($comments) echo "<br> - " . $wordform->wordform . " - " . $wordform->conceptID;
				
			}
			if ($comments) echo "<br> - - -";
		}
		
		// Tämä taulu sisältää wordID:lle löytyneet conceptit
		// onkohan tämä osa se kun konseptille löytyi useampi wordi?
		
		/*
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
				if ($comments) echo "<br>No lemma found for wordform " . $sentenceword;
				return $this->sendError("No wordoform found for '" . $sentenceword . "'");
				$errors = true;
				exit();  // TODO: Pitää palauttaa errortailukko
			}
		
			$wordtable[$wordindex] = $wordarray;
			$countarray[$wordindex] = $counter;
			$wordindex++;
		}
		*/
		
		
		
		$wordclasses = Table::load('worder_wordclasses', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);
		$arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$wordclassefeatures = Table::load('worder_wordclassfeatures', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		
		FeatureStructure::$wordclasses = $wordclasses;
		FeatureStructure::$features = $features;
		FeatureStructure::$components = $components;
		FeatureStructure::$arguments = $arguments;
		
		// TODO: conceptien argumenttien osalta pitää vielä luoda kaikki kombinaatiot
		
		
		$combinations = array();
		$currentarray = array();
		
		foreach($wordtable as $index1 => $wordformss) {
			if ($comments) echo "<br>Wordtable - ";
		
			foreach($wordformss as $index2 => $wordformi) {
				if ($comments) echo "," . $wordformi->rowID . "-" . $wordformi->conceptID;
			}
		}
		if ($comments) echo "<br><br>";
		
		
		
		// generoidaan kaikille konsepteille kaikki argumenttikombinaatiot...
		
		
		
		
		/*
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
			//$this->printSentenceString($sentencearray, $words, $concepts, $wordclasses, $arguments, $components, $features, $languageID);
		}
		if ($comments) echo "<br><br><br>";
		*/
		
		echo "{";
		//echo " " . count($combinations);
		//$first = false;
		
		echo "	\"sentences\":";
		echo "[";
		
		$first = true;
		foreach($wordtable as $index => $combination) {
		
			if ($first == true) {
				$first = false;
			} else {
				echo ", ";
			}
		
			$sentencearray = array();
			foreach($combination as $xpos => $wordform) {
				//$wordform = $wordtable[$index][$xpos];
				//echo "" . $word->wordID . ",";
				$sentencearray[] = $wordform;
			}
			$this->printSentenceInteger($sentencearray, $words, $concepts, $wordclasses, $arguments, $components, $features, $languageID, $wordclassefeatures, $comments);
		}
		echo "]";
			
		
		$firstword = true;
		echo ", \"words\": [";
		foreach($words as $index => $word) {
		
			if ($firstword == true) {
				$firstword = false;
			} else {
				echo ",";
			}
		
			echo "{ \"wordID\":\"" . $word->wordID . "\"";
			echo ", \"wordclassfeatures\": {";
			if ($word->features != "") {
				$featurelist = explode("|", $word->features);
				$first = false;
				foreach($featurelist as $index => $featurestr) {
		
					//echo "<br><br>.." . $featurestr . "..<br>" . $word->wordID . "<br>";
					$featurevalues = explode(":", $featurestr);
		
					$feature = $features[$featurevalues[0]];
					$value = $features[$featurevalues[1]];
		
					if ($first == false) {
						$first = true;
					} else {
						echo ",";
					}
					echo "\"" . $feature->featureID . "\":\"" . $value->featureID . "\"";
				}
			}
			echo "}";
			echo "}";
		}
		echo "]";
		
		echo " }";
	}
	
	
	private function getSentenceCombinations($currentindex, $sentencewords, $foundwords, $currentsentence, &$foundsentences, $comments = false, $prefix = "--") {

		if ($comments) echo "<br><br>" . $prefix . " getSentenceCombinations - " . $currentindex;
		
		//echo "<br><br> -- foundword";
		//foreach($foundwords as $index => $word) {
		//	echo "<br> -- -- " . $word->wordform;
		//}
		
		if ($currentindex == count($sentencewords)) {
			if ($comments) echo "<br>" . $prefix . " End reached..." . $currentindex;
			$newcurrent = array();
			foreach($currentsentence as $index => $word) {
				if ($comments) echo "<br>" . $prefix . " " . $word->wordID . ": " . $word->wordform . " (" . $word->conceptID . ")";
				$newcurrent[] = $word;
			}
			$foundsentences[] = $newcurrent;
			return;
		}
		
		$addition = 0;
		
		$searchword = $sentencewords[$currentindex];
		if ($comments) echo "<br>" . $prefix . " Trying to find - " . $currentindex . " - " . $searchword;
		
		foreach($foundwords as $index => $word) {
			if ($comments) echo "<br>" . $prefix . "  xxx --- " . $index . " - " . $word->wordform;
		}
		foreach($foundwords as $index => $word) {
			if ($comments) echo "<br>" . $prefix . "  loop --- " . $word->wordform . " (searching: " . $searchword . ")";
			if ($word->wordform == $searchword) {
				if ($comments) echo "<br>" . $prefix . " Found word --- " . $word->wordform . " (conceptID:" . $word->conceptID . ")";
				array_push($currentsentence, $word);
				$this->getSentenceCombinations($currentindex+1, $sentencewords, $foundwords, $currentsentence, $foundsentences, $comments, $prefix . " --");			
				if ($comments) echo "<br>" . $prefix . " Return 1";
				array_pop($currentsentence);
			} else {
				 if ($comments) echo "<br>" . $prefix . " no Found - " . $word->wordform . " - " . $searchword;
			}
		}
		
		for($addition = 2; $addition < 3; $addition++) {
			if ($comments) echo "<br>" . $prefix . " Addition " . $addition;
			$searchword = $sentencewords[$currentindex];
			if ($comments) echo "<br>" . $prefix . " searchword - " . $searchword;

			if ($currentindex+$addition <= count($sentencewords)) {
				for($tempindex = 1; $tempindex < $addition; $tempindex++) {
					$searchword = $searchword . " " . $sentencewords[$currentindex+$tempindex];
					if ($comments) echo "<br>" . $prefix . " searchword - " . $searchword;
				}
					
				foreach($foundwords as $index => $word) {
					if ($comments) echo "<br>" . $prefix . " compare - " . $searchword . " vs. " . $word->wordform;
				
					if ($word->wordform == $searchword) {
							
						if ($comments) echo "<br>" . $prefix . " foundi - " . $searchword;
				
						array_push($currentsentence, $word);
						$this->getSentenceCombinations($currentindex+$addition, $sentencewords, $foundwords, $currentsentence, $foundsentences, $comments, $prefix . " --");
						if ($comments) echo "<br>" . $prefix . " Return 2";
						array_pop($currentsentence);
					}
				}
			} else {
				if ($comments) echo "<br>" . $prefix . " over limit";
			}
		}
		
	}
	
	/*
	private function getSentenceCombinations($currentindex, $sentencewords, $foundwords, $currentsentence, &$foundsentences, $comments = false, $prefix = "--") {

		if ($comments) echo "<br><br>" . $prefix . " getSentenceCombinations - " . $currentindex;
		
		//echo "<br><br> -- foundword";
		//foreach($foundwords as $index => $word) {
		//	echo "<br> -- -- " . $word->wordform;
		//}
		
		if ($currentindex == count($sentencewords)) {
			if ($comments) echo "<br>" . $prefix . " End reached..." . $currentindex;
			$newcurrent = array();
			foreach($currentsentence as $index => $word) {
				if ($comments) echo "<br>" . $prefix . " " . $word->wordID . ": " . $word->wordform;
				$newcurrent[] = $word;
			}
			$foundsentences[] = $newcurrent;
			return;
		}
		
		$addition = 0;
		
		$searchword = $sentencewords[$currentindex];
		if ($comments) echo "<br>" . $prefix . " Trying to find - " . $currentindex . " - " . $searchword;
		foreach($foundwords as $index => $word) {
			if ($comments) echo "<br>" . $prefix . "  loop --- " . $word->wordform . " (searching: " . $searchword . ")";
			if ($word->wordform == $searchword) {
				if ($comments) echo "<br>" . $prefix . " Found word --- " . $word->wordform;
				array_push($currentsentence, $word);
				$this->getSentenceCombinations($currentindex+1, $sentencewords, $foundwords, $currentsentence, $foundsentences, $comments, $prefix . " --");			
				if ($comments) echo "<br>" . $prefix . " Return 1";
				array_pop($currentsentence);
			}
		}
		
		for($addition = 2; $addition < 3; $addition++) {
			if ($comments) echo "<br>" . $prefix . " Addition " . $addition;
			$searchword = $sentencewords[$currentindex];
			if ($comments) echo "<br>" . $prefix . " searchword - " . $searchword;

			if ($currentindex+$addition <= count($sentencewords)) {
				for($tempindex = 1; $tempindex < $addition; $tempindex++) {
					$searchword = $searchword . " " . $sentencewords[$currentindex+$tempindex];
					if ($comments) echo "<br>" . $prefix . " searchword - " . $searchword;
				}
					
				foreach($foundwords as $index => $word) {
					if ($comments) echo "<br>" . $prefix . " compare - " . $searchword . " vs. " . $word->wordform;
				
					if ($word->wordform == $searchword) {
							
						if ($comments) echo "<br>" . $prefix . " foundi - " . $searchword;
				
						array_push($currentsentence, $word);
						$this->getSentenceCombinations($currentindex+$addition, $sentencewords, $foundwords, $currentsentence, $foundsentences, $comments, $prefix . " --");
						if ($comments) echo "<br>" . $prefix . " Return 2";
						array_pop($currentsentence);
					}
				}
			} else {
				if ($comments) echo "<br>" . $prefix . " over limit";
			}
		}
		
	}
	*/
	
	

	private function sendError($message) {
		echo "[ ";
		echo "	\"error\":\"1\",";
		echo "	\"message\":\"" . $message . "\",";
		echo " ]";
	}
	
	
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
	

	private function printSentence($sentencearray, &$words, &$concepts, &$wordclasses, &$arguments, &$components, &$features, $languageID, &$wordclassfeatures, $comments = false) {
	
		//echo "<br>Print - " . $languageID;
		//echo "<br>";
	
		echo "[";
		$first = true;
		//echo " " . count($sentencearray);
		//$first = false;
		foreach($sentencearray as $index => $wordform) {
	
			if ($first == true) {
				$first = false;
			} else {
				echo ", ";
			}
			//if ($comments) echo "<br> -- checking sentence - " . $sentenceword;
	
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
						//echo "<br>Argumentline - " . $argumentline;
						$requirementitems = explode(":", $requirementline);
						$argumentID = $requirementitems[0];
						$argument = $arguments[$argumentID];
						$componentID = $requirementitems[1];
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
				
				
				
	
			//if ($comments) echo "<br>Word featurevalue - " . $word->features;
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
				
			$str = $featurestructure->toJSONNew();
			echo $str;
		}
	
		/*
			foreach($words as $index => $word) {
			echo ",[";
			echo "{ \"wordID\":\"" . $word->wordID . "\", wordclassfeatures: {";
			$featurelist = explode("|", $word->features);
			$first = false;
			foreach($featurelist as $index => $featurestr) {
			$featurevalues = explode(":", $featurestr);
	
			$feature = $features[$featurecomps[0]];
			$value = $features[$featurecomps[1]];
	
			if ($first == false) {
			echo "\"" . $feature->name . "\":\"" . $value->name . "\"";
			$first = true;
			} else {
			echo ",\"" . $feature->name . "\":\"" .  $value->name . "\"";
			}
			}
			echo "}}";
			echo "]";
			}
			*/
	
	
		echo " ]";
	}
	
	
	
	
	private function printSentenceInteger($sentencearray, &$words, &$concepts, &$wordclasses, &$arguments, &$components, &$features, $languageID, &$wordclassfeatures, $comments = false) {
	
		$comments = false;
		//echo "<br>Print - " . $languageID;
		//echo "<br>";
	
		echo "[";
		$first = true;
		//echo " " . count($sentencearray);
		//$first = false;
		foreach($sentencearray as $index => $wordform) {
	
			if ($first == true) {
				$first = false;
			} else {
				echo ", ";
			}
			//if ($comments) echo "<br> -- checking sentence - " . $sentenceword;
	
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
			$featurestructure->setConceptName($concept->name);
			
			// arguments -- ei ole alimmalla tasolla asetettu (normikeississä ainakaan)
			// requirements -- nämä tulee conceptista, pitäisi olla jo nyt ladattuna
	
			if ($concept != null) {
				$requirementlist = explode('|', $concept->arguments);
				$classarguments = array();
				if ($comments) echo "<br>Requirements - " .  $concept->arguments;
					
				//echo "<br>Requirements args - " . $concept->arguments;
				//echo "<br>Requirements count - " . count($requirementlist);
				foreach($requirementlist as $requirementindex => $requirementline) {
					if ($requirementline != "") {
						//echo "<br>Argumentline - " . $argumentline;
						$requirementitems = explode(":", $requirementline);
						$argumentID = $requirementitems[0];
						$argument = $arguments[$argumentID];
						$componentID = $requirementitems[1];
						if ($componentID != "") {
							$component = $components[$componentID];
							if ($comments) echo "<br>adding requirement - " . $argument->name . " - " . $component->name;
							$featurestructure->addArgumentRequirement($argumentID,$componentID);
								
						} else {
							//echo "<br>componentID on tyhjä...";
							//echo "concept - " . $concept->conceptID;
							//exit;
						}
					}
				}
				//echo "<br>";
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
	
	
	
	
			//if ($comments) echo "<br>Word featurevalue - " . $word->features;
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
	
			//$str = $featurestructure->toJSONNew();
			//$str = $featurestructure->toJSONNew();
			//echo $str;
			//echo "<br><br>";
			$str = $featurestructure->toJSONInteger();
			echo $str;
	
		}
	
		echo " ]";
	}
	
	
	
	
	private function printSentenceString($sentencearray, &$words, &$concepts, &$wordclasses, &$arguments, &$components, &$features, $languageID) {
	
		$comments = true;
		echo "<br><br>";
		echo "<br>--------------------------------------------------";
	
		$first = true;
		foreach($sentencearray as $index => $wordform) {
	
			/*
				if ($first == true) {
				$first = false;
				} else {
				echo ", ";
				}
				*/
			//if ($comments) echo "<br> -- checking sentence - " . $sentenceword;
	
			if ($comments) echo "<br>Processing wordform - " . $wordform->wordform . " - wordID:" . $wordform->wordID . " - conceptID:" . $wordform->conceptID . " - rowID:" . $wordform->rowID;
			$wordID = $wordform->wordID;
			$word = $words[$wordID];
			$conceptID = $wordform->conceptID;
			$concept = null;
			if (($conceptID != null) && ($conceptID != "")) {
				$concept = $concepts[$conceptID];
			}
			$wordclassID = $word->wordclassID;
			$wordclass = $wordclasses[$wordclassID];
	
			if ($comments) {
				if ($concept != null) {
					echo "<br><br>Setting Word - " . $wordID . " - " . $conceptID . " - " . $concept->name;
					if ($comments) echo "<br><br> --- " . $word->lemma. " - " . $concept->name;
				} else {
					echo "<br><br>Setting Word - " . $wordID . " - no concept";
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
						//echo "<br>Argumentline - " . $argumentline;
						$requirementitems = explode(":", $requirementline);
						$argumentID = $requirementitems[0];
						$argument = $arguments[$argumentID];
						$componentID = $requirementitems[1];
						$component = $components[$componentID];
						if ($comments) echo "<br>adding requirement - " . $argument->name . " - " . $component->name;
						$featurestructure->addArgumentRequirement($argumentID,$componentID);
					}
				}
			} else {
				if ($comments) echo "<br>No requirements - concept not found";
			}
	
			//echo "<br>Features - " .  $wordform->features;
			//echo "<br>Requirements count - " . count($argumentlist);
			foreach($wordform->features as $featureindex => $valueID) {
				if ($comments) echo "<br>Featurevalue - " . $valueID;
				$value = $features[$valueID];
				if ($value->parentID == 0) {
					$featureID = $valueID;
				} else {
					$featureID = $value->parentID;
				}
				$feature = $features[$featureID];
				if ($comments) echo "<br>adding featue - " . $feature->name . " - " . $value->name;
				$featurestructure->addFeature($featureID,$valueID);
			}
				
			if ($comments) echo "<br>Word featurevalue - " . $word->features;
			if ($word->features != "") {
				$featurelist = explode("|",$word->features);
				foreach($featurelist as $f1 => $featurecompo) {
					if ($featurecompo != "") {
						$featurecomps = explode(":", $featurecompo);
						$feature = $features[$featurecomps[0]];
						$value = $features[$featurecomps[1]];
						if ($comments) echo "<br>adding word feature - " . $feature->name . " - " . $value->name;
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
			} else {
				echo "<br>No components - no concept found";
			}
	
			$str = $featurestructure->toJSONNew();
			echo $str;
		}
		echo " ]";
	}
	
	
	
	// Tämä on tosi raskas haku, mutta ei tätä taida nykyisellä rakenteella nopeammaksikaan saada...
	// ongelmana on vielä, että
	// Wordfeaturessia ei tarvitse ottaa huomioon, generate algoritmi on tsekannut ne jo aiemmin..
	public function fetchwordformsJSONAction() {
	
		$comments = false;
		$comments2 = false;
		if (isset($_GET['comments'])) {
			$comments = true;
			$comments2 = true;
		}
		$languageID = $_GET['languageID'];
		$list = $_GET['list'];
	
		$features = Table::load("worder_features", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$featurelookup = array();
		foreach($features as $index => $feature) {
			$featurelookup[$feature->featureID] = $feature->featureID;
			//if ($comments) echo "<br> -- all features: " . $feature->abbreviation . " - " . $feature->featureID;
		}
		$wordclassfeatures = Table::load("worder_wordclassfeatures", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID, $comments);
	
		$searchwordslist = explode('|',$list);
		$neededconceptslist = array();
		$resultlist = array();
		$counter = 0;
		foreach($searchwordslist as $resultindex => $formlist) {
			$formitems = explode(',', $formlist);
			$conceptID = $formitems[0];
			$neededconceptslist[$conceptID] = $conceptID;
		}
	
		$concepts = Table::loadWhereInArray("worder_concepts", "conceptID", $neededconceptslist, "WHERE GrammarID=" . $_SESSION['grammarID'], $comments);
		if ($comments) echo "<br>Concepts...";
		foreach($concepts as $index => $concept) {
			if ($comments) echo "<br> -- " . $concept->conceptID . " -- " . $concept->name;
		}
		$conceptwordlinks = Table::loadWhereInArray("worder_conceptwordlinks", "conceptID", $neededconceptslist, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND Defaultword=1", $comments);
		$wordlist = array();
		$conceptlinks = array();
		if ($comments) echo "<br><br>Wordlinks...";
		foreach($conceptwordlinks as $index => $link) {
			$wordID = $link->wordID;
			$wordlist[$wordID] = $wordID;
			$conceptlinks[$wordID] = $link->conceptID;
			if ($comments) echo "<br> -- " . $wordID . ", conceptID=" . $link->conceptID;
		}
		$words = Table::loadWhereInArray("worder_words", "wordID", $wordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		if ($comments) echo "<br><br>Words...";
		foreach($words as $index => $word) {
			$conceptID = $conceptlinks[$word->wordID];
			$concept = $concepts[$conceptID];
			$concept->wordID = $word->wordID;
			$concept->lemma = $word->lemma;
			if ($comments) echo "<br> -- " . $word->wordID . " -- " . $word->lemma . " - conceptID:" . $conceptID;
		}
	
		if ($comments) echo "<br><br>Processing...";
		foreach($searchwordslist as $resultindex => $formlist) {
			if ($comments) echo "<br><br><br> -- formitems - " . $formlist;
			$formitems = explode(',', $formlist);
			$conceptID = $formitems[0];
			$concept = $concepts[$conceptID];
			$acceptedword = null;
			if ($comments) echo "<br> - " . $concept->name . " - " . $concept->lemma . " - " . $concept->wordID;
	
			// Pitäisi tsekata wordfeaturessit...
			$wordfeatures = Table::load("worder_wordfeaturelinks", "WHERE WordID=" . $concept->wordID . " AND LanguageID=" . $languageID . " AND GrammarID=" . $_SESSION['grammarID'], $comments);
			$defaultfeaturesarray = array();
			
			foreach($wordclassfeatures as $wcfindex => $wordclassfeature) {
					
				if ($wordclassfeature->wordclassID == $concept->wordclassID) {
					if ($wordclassfeature->inflectional == 0) {
	
						$featuretemp = $features[$wordclassfeature->featureID];
						if ($comments) echo "<br> -- needed wordclassfeature - " . $featuretemp->name;
	
						$wordclassfeaturefound = false;
						foreach($wordfeatures as $wcfindex => $wordfeature) {
							$feature = $features[$wordfeature->featureID];
							$featurevalue = $features[$wordfeature->valueID];
	
							if ($wordfeature->featureID == $wordclassfeature->featureID) {
								if ($comments) echo "<br> -- -- wordclassfeature found - " . $feature->name;
								$wordclassfeaturefound = true;
							}
						}
	
						if ($wordclassfeaturefound == false) {
							if ($wordclassfeature->defaultvalueID > 0) {
								if ($comments) echo "<br> -- -- wordclassfeature not found, but default exists, adding default";
								$newwordfeature = new Row();
								$newwordfeature->featureID = $wordclassfeature->featureID;
								$newwordfeature->valueID = $wordclassfeature->defaultvalueID;
								$wordfeatures[] = $newwordfeature;
							} else {
								if ($comments) echo "<br> -- -- wordclassfeature not found, and no default exits";
							}
						}
					} else {
						if ($wordclassfeature->defaultvalueID > 0) {
							$defaultfeaturesarray[$wordclassfeature->featureID] = $wordclassfeature->defaultvalueID;
						}
					}
				}
			}
				
			if ($comments) echo "<br><br>";
			// Wordfeatures taulukko sisältää wordfeaturelinks-taulun rivit...
			// Nyt tarkistetaan onko parametrina annetuista featureista jokin ristiriidassa wordille asetettujen kanssa
			foreach($wordfeatures as $wcfindex => $wordfeature) {
	
				$feature = $features[$wordfeature->featureID];
				$featurevalue = $features[$wordfeature->valueID];
				if ($comments) echo "<br><br><br> -- -- -- checking... " . $feature->name . " - " . $featurevalue->name;
	
				$found = false;
				for($i = 1;$i<count($formitems);$i++) {
					if ($comments) echo "<br> -- -- checking wordfeature item - " . $formitems[$i] . ", trying to find - " . $feature->name . ", " . $featurevalue->name;
					$pairstr = $formitems[$i];
					if ($comments) echo "<br> -- -- pairstr - " . $pairstr;
						
					$pair = explode(':', $pairstr);
					if ($pair[0] == $feature->featureID) {
	
						if ($comments) echo "<br>Compare - " . $pairstr . " - " . $pair[0] . " - " . $pair[1];
						if ($comments) echo "<br>featurelookup - " . $featurelookup[$pair[1]];
	
						if (!isset($featurelookup[$pair[1]])) {
							if ($comments) echo "<br> -- -- requirement found - " . $feature->name . "(" . $feature->featureID . ") == " . $requiredfeature->name . "(" . $requiredfeature->featureID . ")" ;
							echo "{";
							echo "	\"message\":\"unknwon feature value " . $pair[1] . " for word '" . $concept->lemma .  "'\",";
							echo "	\"error\":\"1\"";
							echo "}";
							exit;
						}
						$requiredfeatureID = $featurelookup[$pair[1]];
						$requiredfeature = $features[$requiredfeatureID];
						$wordf = $features[$wordfeature->valueID];
						
						if ($comments) echo "<br> -- -- requirement found - " . $feature->name . "(" . $feature->featureID . ") == " . $requiredfeature->name . "(" . $requiredfeature->featureID . ")" ;
						if ($comments) echo "<br> -- -- existing value - " . $featurevalue->name;
						if ($comments) echo "<br> -- -- wordfeature value - " . $wordf->name . " (" . $wordfeature->valueID . ")";
						
						if ($requiredfeatureID != $wordfeature->valueID) {
							if ($comments) echo "<br> -- -- -- not compatible word features";
							if ($comments) echo "<br> -- -- -- reqiored - " . $wordfeature->valueID;
	
							echo "{";
							echo "	\"message\":\"No compatible word found " . $requiredfeature->name . " (" . $requiredfeature->featureID . ") for word " . $concept->lemma . "\",";
							echo "	\"error\":\"1\"";
							echo "}";
								
							//echo "<br>no wordclassfeature default found for RowID=" . $wordclassfeature->rowID . ", featureID:" . $wordclassfeature->featureID;
							exit;
						} else {
							if ($comments) echo "<br> -- -- compatible true";
							$found = true;
						}
					}
				}
				if ($found == true) {
					//echo "<br> -- -- -- value found;";
					//break;
				} else {
					
				}
			}
				
				
			// Nyt haetaan kyseisen sanan kaikki formit, ja sitten vertaillan haluttuja...
			// TODO: tähän pitäisi ehkä asettaa että defaultform = 1, vaihtoehtoisesti valitaan ensimmäinen
			// 		  kohdalle sattuva featureihin sopiva...
			$forms = Table::load("worder_wordforms", "WHERE WordID=" . $concept->wordID . " AND LanguageID=" . $languageID . " AND GrammarID=" . $_SESSION['grammarID'], $comments);
				
				
	
			$searchfeatures = array();
			foreach($wordclassfeatures as $wcfindex => $wordclassfeature) {
				if ($wordclassfeature->wordclassID == $concept->wordclassID) {
					if ($comments) echo "<br> -- needed feature wordclass - " . $concept->wordclassID . ", inflectional=" . $wordclassfeature->inflectional . ", rowID=" . $wordclassfeature->rowID;
	
					if ($wordclassfeature->inflectional == 1) {
						$feature = $features[$wordclassfeature->featureID];
						if ($comments) echo "<br> -- needed feature - " . $feature->name . " (" . $wordclassfeature->featureID . ")";
						if ($comments) echo "<br> -- forms count x1 - " . count($formitems);
						//var_dump($formitems);
						// Tsekataan löytyykö halutuista muodoista kyseistä featurea, arvo joka halutaan
						$found = false;
						if ($comments) echo "<br> -- forms count - " . count($formitems);
						for($i=0;$i<count($formitems);$i++) {
							if ($comments) echo "<br> -- -- checking item - " . $formitems[$i];
							$pairstr = $formitems[$i];
							$pair = explode(':', $pairstr);
							if ($pair[0] == $feature->featureID) {
								$requiredfeatureID = $featurelookup[$pair[1]];
								$requiredfeature = $features[$requiredfeatureID];
								if ($comments) echo "<br> -- -- requirement found - " . $wordclassfeature->featureID . " == " . $requiredfeature->name;
								$searchfeatures[$wordclassfeature->featureID] = $requiredfeatureID;
								
								if (isset($defaultfeaturesarray[$feature->featureID])) {
									if ($defaultfeaturesarray[$feature->featureID] == $requiredfeatureID) {
										$defaultsearchfeatures[$wordclassfeature->featureID] = 1;
									} else {
										$defaultsearchfeatures[$wordclassfeature->featureID] = 0;
									}
									$found = true;
								} else {
									$defaultsearchfeatures[$wordclassfeature->featureID] = 0;
									$found = true;
								}
								break;
							}
						}
						if ($found == false) {
							if ($wordclassfeature->defaultvalueID == 0) {
								$requiredfeature = $features[$wordclassfeature->featureID];
	
								echo "{";
								echo "	\"message\":\"No feature found " . $requiredfeature->name . " for word " . $concept->lemma . "\",";
								echo "	\"error\":\"1\"";
								echo "}";
	
								//echo "<br>no wordclassfeature default found for RowID=" . $wordclassfeature->rowID . ", featureID:" . $wordclassfeature->featureID;
								exit;
							}
							$defaultsearchfeatures[$wordclassfeature->featureID] = 1;
							$searchfeatures[$wordclassfeature->featureID] = $wordclassfeature->defaultvalueID;
							$requiredfeature = $features[$wordclassfeature->defaultvalueID];
							if ($comments) echo "<br> -- no needed requirement found, use default - " . $wordclassfeature->rowID . " - " . $requiredfeature->name;
							//echo "<br> -- -- requirement found - " . $wordclassfeature->featureID . " == " . $requiredfeature->name;
						}
					}
				}
			}
				
			// Kelataan kaikki formit lävitse, ja tsektaan löytyykö jokaiselle searchfeaturelle arvo...
			foreach($forms as $index => $form) {
	
				if ($comments) echo "<br><br>";
				if ($comments) echo "<br> -- -- " . $form->wordform . " - ";
				if ($comments) print_r($form->features);
	
				$allmatch = true;
				foreach($searchfeatures as $requiredfeatureID  => $requiredvalueID) {
					$found = 0;
					
					foreach($form->features as $i2 => $formFeatureID) {
						$parentID = $features[$formFeatureID]->parentID;
						
						$reqfss = $features[$requiredfeatureID];
						$formfss = $features[$formFeatureID];
						if ($parentID > 0) $parentfss = $features[$parentID];
						$reqvalfss = $features[$requiredvalueID];
						if ($comments) echo "<br> -- -- -- search requiderfeatureID:" . $requiredfeatureID . " - formvalue:"  . $formFeatureID . " - formparentID:" . $parentID. " - requiredvalue:" . $requiredvalueID;
						
						
						
						if ($parentID == 0) {
							if ($comments) echo "<br> -- -- -- -- requiderfeatureID:" . $reqfss->name . " - formvalue:"  . $formfss->name . " - formparentID:null0 - requiredvalue:" . $reqvalfss->name;
						
							if ($requiredfeatureID == $formFeatureID) {
								if ($comments) echo "<br> -- -- -- -- -- -- -- is parent";
								$found = 1;
								break;
							}
						} else {
							if ($comments) echo "<br> -- -- -- -- requiderfeatureID:" . $reqfss->name . " - formvalue:"  . $formfss->name . " - formparentID:" . $parentfss->name. " - requiredvalue:" . $reqvalfss->name;
						}
						
						if ($parentID == $requiredfeatureID) {
							if ($requiredvalueID != $formFeatureID) {
								if ($comments) echo "<br> -- -- -- -- incompatible values: " . $requiredvalueID . " vs. " . $formFeatureID;
								$found = -1;
								break;
							} else {
								if ($comments) echo "<br> -- -- -- -- compatible values";
								$found = 1;
								break;
							}
						}
					}
					if ($found == 0) {
						$feature = $features[$requiredfeatureID];
						if ($comments) echo "<br>-- -- -- no feature found a2 - " . $feature->name;
						$requiredfeature1 = $features[$requiredfeatureID]; 
						if ($comments) echo "<br>-- -- -- no feature found req - " . $defaultsearchfeatures[$requiredfeatureID] . " - " . $requiredfeature1->name;
						
						if ($defaultsearchfeatures[$requiredfeatureID] == 1) {
							if ($comments) echo "<br>-- -- -- -- This is default value, will be accepted x2";
						} else {
							$allmatch = false;
							break;
						}
					}
					if ($found == -1) {
						$feature = $features[$requiredfeatureID];
						if ($comments) echo "<br>-- -- -- feature found, but incompatible - " . $feature->name;
						$allmatch = false;
						break;
					}
				}
				if ($allmatch == true) {
					if ($acceptedword != null) {
						// TODO: tähän pitäisi ehkä asettaa että defaultform = 1, vaihtoehtoisesti valitaan ensimmäinen
						// 		  kohdalle sattuva featureihin sopiva...
						//echo "<br>-- -- accepted word already found 2 - " . $acceptedword. ", " . $form->wordform . " is duplicate";
						//exit;
					} else{
						$acceptedword = $form->wordform;
					}
					if ($comments) echo "<br>-- -- accepted .....";
				} else {
					if ($comments) echo "<br>-- -- not accepted .....";
				}
			}
				
			if ($acceptedword == null) {
				$resultlist[] = "-fail-";
			} else {
				$resultlist[] = $acceptedword;
			}
		}
	
		$resultstr = "";
		$first = true;
		foreach($resultlist as $vindex => $value) {
			if ($first == true) {
				if ($resultstr != "") $resultstr = $resultstr . " ";
				$resultstr = $resultstr . $value;
				$first = false;
			} else {
				$resultstr = $resultstr . " " . $value;
			}
		}
		if ($comments) echo "<br>";
	
		echo "{";
		echo "	\"result\":\"" . $resultstr . "\"";
		echo "}";
	}
	
}
