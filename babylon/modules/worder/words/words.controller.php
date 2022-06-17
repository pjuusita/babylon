<?php

include_once('./modules/worder/_classes/inheritancemodes.class.php');


function cmpLanguageWords($a, $b) {
	if ($a->inflection < $b->inflection) return -1;
	return 1;
}




class WordsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('testcss.php','menu.css','chosen.css','style2.css','prism.css');
		//return array('menu.css','testcss.php','chosen.css');
		return array();		
	}
	
	
	public function getJSFiles() {
		return array('prism.js','jquery-3.2.1.min.js','jquery-ui.js','chosen.jquery.js','init.js');
		//return array('jquery-3.2.1.min.js','jquery-ui.js','chosen.jquery.js','init.js');
	}
		
	
	public function indexAction() {
		//$this->showwordsAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	

	public function showwordsAction() {

		updateActionPath("Lexicon");
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
		//echo "<br>LanguageID - " .$languageID;
		
		$this->registry->wordclassID = getSessionVar('wordclassID', 0);
		$this->registry->wordgroupID = getSessionVar('wordgroupID', 0);
		$this->registry->search = getSessionVar('search', '');
		$this->registry->rarities = Table::load("worder_rarities");
		

		$this->registry->currentpage = getSessionVar('page', 1);
		$this->registry->rowsperpage = getSessionVar('rowsperpage', 20);
		
		if (isset($_GET['rowsperpage'])) {
			$this->registry->currentpage = 1;
			setSessionVar('page', 1);
		}
		
		
		if (isset($_GET['wordclassID'])) {
			setSessionVar('wordgroupID', 0);
			$this->registry->wordgroupID = 0;
			setSessionVar('search', '');
			$this->registry->search = '';
			setSessionVar('page', 1);
			$this->registry->currentpage = 1;
		}
		
		
		if (isset($_GET['search'])) {
			setSessionVar('wordgroupID', 0);
			$this->registry->wordgroupID = 0;
			setSessionVar('wordclassID', 0);
			$this->registry->wordclassID = 0;
			setSessionVar('page', 1);
			$this->registry->currentpage = 1;
		}
		
		$this->registry->languageID = $languageID;
		//$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'], true);
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);

		
		// Mikäli aktiivinen wordclassID ei löydy, asetetaan valittu pudotusvalikko kaikki valituksi
		// Tämä esiintyy esimerkiksi grammarin vaihdon jälkeen
		$firstwordclass = null;
		$found = false;
		foreach($this->registry->wordclasses as $index => $wordclass) {
			if ($firstwordclass == null) $firstwordclass = $wordclass;
			if ($this->registry->wordclassID == $wordclass->wordclassID) $found = true;
		}
		if ($found == false) {
			$this->registry->wordclassID = 0;
			setSessionVar('wordclassID', 0);
			
			/*
			if ($this->registry->wordclassID > 0) {
				$this->registry->wordclassID == $firstwordclass->wordclassID;
				setSessionVar('wordclassID', $firstwordclass->wordclassID);
			}
			*/
		}
	
		$inflectorarray = Table::load("worder_inflectors");
		
		if ($this->registry->wordclassID > 0) {
			
			//echo "<br>load with wordclass - " .$languageID . " - " . $this->registry->wordclassID;
			$this->registry->words = Table::load("worder_words", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND WordclassID=" . $this->registry->wordclassID);
			//$this->registry->words = Table::load("worder_concepts",$this->registry->currentpage, $this->registry->rowsperpage, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $this->registry->wordclassID);
			//$this->registry->totalrows = Table::countRows("worder_concepts", "worder_words", "WordID", " WHERE GrammarID=" . $_SESSION['grammarID'] . " AND Selected=1 AND WordclassID=" . $this->registry->wordclassID);
			//echo "<br>Totalrows - " . $this->registry->totalrows;
			$this->registry->totalrows = 1;
			$this->registry->template->show('worder/words','words');
			return;
		}
		
		if ($this->registry->search != "") {
			
		}
		
		//echo "<br>LanguageID - " . $languageID;
		$this->registry->words = Table::load("worder_words", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
			
		
		//$this->registry->totalrows = Table::countTableRows("worder_words");
		//$this->registry->words = Table::loadWithPaging("worder_concepts",$this->registry->currentpage, $this->registry->rowsperpage, " WHERE GrammarID=" . $_SESSION['grammarID'] . " AND Selected=1");
		$this->registry->template->show('worder/words','words');
	}
	
	
	
	
	
	
	public function showwordAction() {
	
		if (!isset($_GET['id'])) {
			redirecttotal('worder/words/showwords');
			return;
		}
		
		$wordID = $_GET['id'];
		$comments = false;
		
		/*
		if (strpos($_GET['id'],'-') > 0) {
			$params = explode("-", $_GET['id']);
			$wordID = $params[0];
			$conceptID = $params[1];
		} else {
			$wordID = $_GET['id'];
			$conceptID = 0;
		}
		*/
		//$languageID = $_GET['languageID'];
		
		
		$word = Table::loadRow("worder_words","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
		updateActionPath(ucfirst($word->lemma));
		if ($word == null) {
			echo "<br>Word not found";
			redirecttotal('worder/words/showwords');
			return;
		}
		
		if ($word->inflectionforms == "") {
			if (($word->languageID == 1) && ($word->wordclassID == 1)) {
				$inflectionform = $word->lemma . "/" . $word->lemma . "/" . $word->lemma . "/" . $word->lemma . "/" . $word->lemma . "/" . $word->lemma . "/" . $word->lemma . "/" . $word->lemma;
				echo "<br>Update inflection -- " . $inflectionform;	
				
				$columns = array();
				$columns['Inflectionforms'] = $inflectionform;
				$success = Table::updateRow("worder_words", $columns, $wordID);
				
				$word->inflectionforms = $inflectionform;
			}
		}
		if ($word->inflectorID == 0) {
			if ($word->languageID == 1) {
				echo "<br>Updating inflector -- " . 1;	
				$columns = array();
				$columns['InflectorID'] = 1;
				$success = Table::updateRow("worder_words", $columns, $wordID);
				$word->inflectorID = 1;
			}
		}
		
		$this->registry->word = $word;
		$languageID = $word->languageID;
		$this->registry->language = Table::loadRow("worder_languages","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
	
		if ($word != null) {
	
			$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
			
			$inflectors = array();
			$inflectorarray = Table::load("worder_inflectors", "WHERE LanguageID='" . $languageID . "'");
			foreach($inflectorarray as  $index => $inflector) {
				$inflectors[$inflector->inflectorID] = $inflector->name;
			}
			$this->registry->inflectors = $inflectors;
	
			$this->registry->features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']. " ORDER BY Name");
			$this->registry->inheritancemodes = InheritanceModes::getInheritanceModes();
		
			$this->registry->wordID = $wordID;
			
			$links = Table::load("worder_conceptwordlinks", "WHERE GrammarID=" . $_SESSION['grammarID']. " AND WordID=" . $wordID . " ORDER BY Sortorder");
			$conceptlist = array();
			//if ($this->registry->word->conceptID > 0) {
				//$concepts[$this->registry->conceptID] = Table::loadRow("worder_concepts", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $this->registry->word->conceptID);
			//}
			foreach($links as $index => $row) $conceptlist[$row->conceptID] = $row->conceptID;
			$loadedconcepts = Table::loadWhereInArray("worder_concepts","conceptID", $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
			
			$tempconcepts = array();
			foreach($links as $index => $link) {
				$concept = $loadedconcepts[$link->conceptID];
				$tempconcepts[$concept->conceptID] = $concept;
			}
			$this->registry->concepts = $tempconcepts;
			// loadWhereInArray($tablename, $wherecolumnID, $array, $whereclause = "", $comments = false) {
			
			$featurevalues = array();
			$featurevaluesets = array();
			$mandatories = array();
	
			
			
			$hierarchy = array();
			$parentlines = explode('|',$this->registry->word->parentpaths);
			
			
			
			// Ladataan parent hierarchy
			$allparents = explode(":", $this->registry->word->allparents);
			$wordlist = array();
			foreach($allparents as $index => $parentID) {
				if (($parentID != null) && ($parentID != "") && ($parentID != "0")) {
					$wordlist[$parentID] = $parentID;
				}
			}
			
			
			// Directparents-listaa käytetään sen määrittämiseen voidaanko poistaa
			$directparentlist = explode(":", $this->registry->word->parents);
			$directparents = array();
			foreach($directparentlist as $index => $parentID) {
				$directparents[$parentID] = $parentID;
			}
			//var_dump($directparents);
			
			if (count($wordlist) == 0) {
				//echo "<br>empty list";
				$hierarchy[] = $this->registry->word;
				$this->registry->hierarchy = $hierarchy;
			} else {
				//echo "<br>not empty list";
				
				$parents = Table::loadWhereInArray('worder_words', 'WordID', $wordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
				if ($comments) echo "<br>parentcount - " . count($parents);
				foreach($parentlines as $index => $parentline) {
					if ($parentline != "") {
						if ($comments) echo "<br>Prosessing parentline - " . $parentline;
						$parentsstrs = explode(':', $parentline);
						$first = true;
						$lastparent = null;
						if ($comments) echo "<br>Parentstr - " . $parentline;
						foreach($parentsstrs as $index2 => $parentID) {
							if ($comments) echo "<br>index - " . $index2 . " --- " . $parentID;
							if ($parentID != "") {
								if ($first == true) {
									$officialparent = $parents[$parentID];
									$copyparent = new Row();
									$copyparent->lemma = $officialparent->lemma;
									$copyparent->wordID = $officialparent->wordID;
									$lastparent = $copyparent;
									$hierarchy[] = $lastparent;
									$first = false;
									if (isset($directparents[$lastparent->wordID])) {
										if ($comments) echo "<br>Directparent1 - " . $lastparent->wordID;
										$lastparent->removepossible = 1;
									}
								} else {
									$officialparent = $parents[$parentID];
									$copyparent = new Row();
									$copyparent->lemma = $officialparent->lemma;
									$copyparent->wordID = $officialparent->wordID;
									$lastparent->addChild($copyparent);
									$lastparent = $copyparent;
									if (isset($directparents[$lastparent->wordID])) {
										if ($comments) echo "<br>Directparent - " . $lastparent->wordID;
										$lastparent->removepossible = 1;
									}
								}
							} else {
								//echo "<br>Indexsinull";
							}
						}
						if ($this->registry->word != null) $lastparent->addChild($this->registry->word);
					}
			
				}
				$this->registry->hierarchy = $hierarchy;
			}
			
			
			// Loading sentences
			$sentencelinks = Table::load("worder_sentencelinks", " WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
			$sentencelist = array();
			foreach($sentencelinks as $index => $link) {
				$sentencelist[$link->sentenceID] = $link->conceptID;
			}
			$this->registry->sentences = Table::loadWhereInArray("worder_sentences", "sentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);

			foreach($this->registry->sentences as $sentenceindex => $sentence) {
				$conceptID = $sentencelist[$sentence->sentenceID];
				$sentence->conceptID = $conceptID;
			}
			
			// --------------------------------------
			// 		Loading features
			// --------------------------------------
			$wordclassfeatureslinks = Table::load("worder_wordclassfeatures", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $this->registry->word->wordclassID . " AND LanguageID=" .  $languageID);
			$wordclassfeatures = array();
			$inflectionalfeatures = array();
			foreach($wordclassfeatureslinks as $index => $wordclassfeaturelink) {
				if ($wordclassfeaturelink->inflectional == 0) {
					$wordclassfeatures[$wordclassfeaturelink->featureID] = $this->registry->features[$wordclassfeaturelink->featureID];
				} else {
					if ($wordclassfeaturelink->inflectional == 1) {
						$inflectionalfeatures[$wordclassfeaturelink->featureID] = $this->registry->features[$wordclassfeaturelink->featureID];
					}
				}
			}
			$this->registry->wordclassfeatures = $wordclassfeatures;
			$this->registry->inflectionalfeatures = $inflectionalfeatures;
				
			$featurelinks = explode("|", $this->registry->word->features);
			//echo "<br> - features: " . $this->registry->word->features;
			$ownfeaturelinks = Table::load("worder_wordfeaturelinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $this->registry->word->wordID);
			$ownfeatures = array();
			if ($ownfeaturelinks != null) {
				foreach($ownfeaturelinks as $index => $featurelink) {
					$ownfeatures[] = $featurelink;
				}
			}
			if ($featurelinks != null) {
				foreach($featurelinks as $index => $featurelink) {
					if ($featurelink == "") unset($featurelinks[$index]);
				}
			}
				
			$featurevalues = array();
			
			//echo "<br>Feature links - " . count($featurelinks);
			$settedfeatures = array();
			foreach($featurelinks as $index => $featurelink) {
				
				//echo "<br>Featurtelink - "  .$featurelink;
				
				$featureitems = explode(":", $featurelink);
				$featureID = $featureitems[0];
				$valueID = $featureitems[1];
				$sourcewordID = $featureitems[2];
				
				$feature = $this->registry->features[$featureID];
				$arry = array();
				$arry[0] = $feature->name;
				$arry[1] = $featureID;
				if ($valueID == 0) {
					$arry[2] = "<span style='color:red;font-style:italic;'>Undefined</span>";
					$arry[3] = 0;
				} else {
					$feature = $this->registry->features[$valueID];
					$arry[2] = $feature->name;
					$arry[3] = $valueID;
				}
				if ($sourcewordID == $wordID) {
					//$featurelink = $ownfeatures[$featureID];
					//$featurelink = $ownfeatures[$featurelink->rowID];
					$ownlink = null;
					foreach($ownfeatures as $iii => $ownlinkloop) {
						if (($ownlinkloop->featureID == $featureID) && ($ownlinkloop->valueID == $valueID)) {
							$ownlink = $ownlinkloop;
						}
					}
					if ($ownlink == null) {
						echo "<br>Wonlink is null - " . $wordID;
					}
					$mode = $this->registry->inheritancemodes[$ownlink->inheritancemodeID];
					$arry[4] = "" . $mode->name;
					$arry[5] = "<font style='font-weight:bold;font-style:italic'>This</font>";
					$arry[6] = 1;
				} else {
					$arry[4] = "<font style='color:grey;font-style:italic'>(inherited)</font>";
					
					$parent = $parents[$sourcewordID];
					$arry[5] = $parent->lemma;
					$arry[6] = 0;
				}
				
				$featurevalues[] = $arry;
				$settedfeatures[$featureID] = 1;
			}
			
			foreach($this->registry->wordclassfeatures as $index => $wordclassfeature) {
				if (!isset($settedfeatures[$wordclassfeature->featureID])) {
					$arry = array();
					$feature = $this->registry->features[$wordclassfeature->featureID];
					$arry[0] = $feature->name;
					$arry[1] = $feature->featureID;
					$arry[2] = "<span style='color:red;font-style:italic;'>Undefined</span>";
					$arry[3] = 0;
					$arry[4] = "-";
					$arry[5] = "-";
					$arry[6] = 0;
					$featurevalues[$wordclassfeature->featureID] = $arry;
				}				
			}
			$this->registry->featurevalues = $featurevalues;
			
			
			// --------------------------------------
			// 		Loading inflections
			// --------------------------------------
			$foundformstable = array();
			$foundchecked = array();
			$checkedforms = array();
			if ($this->registry->word->inflection != "") {
	
				$forms = $this->getInflections($word, $this->registry->features);
				$checkedforms = Table::load("worder_wordforms", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $this->registry->word->wordID);
				if ($checkedforms == null) $checkedforms = array();
	
				if ($forms != null) {
					foreach($forms as $index => $form) {
						$arry = array();
						$arry[] = $form->wordform;
							
						$featurestr = "";
						$featurearray = "";
						$first = true;
						foreach($form->features as $index => $featureID) {
							$feature = $this->registry->features[$featureID];
							if ($first == true) {
								$featurestr = $feature->abbreviation;
								$featurearray = $featureID;
								$first = false;
							} else {
								$featurestr = $featurestr . "," . $feature->abbreviation;
								$featurearray = $featurearray . "," . $featureID;
							}
						}
						$arry[] = $featurestr;
							
						$found = false;
						foreach($checkedforms as $index2 => $checkdform) {
							if ($form->wordform == $checkdform->wordform) {
								if ($this->formsEquals($form->features,$checkdform->features) == true) {
									$found = $checkdform->rowID;
								}
							}
						}
	
						if ($found == false) {
							$arry[] = "*not found";
						} else {
							$arry[] = "OK";
							$foundchecked[$found] = 1;
						}
						$arry[] = $wordID . ":" . $form->wordform . ":" . $featurearray . ":" . $featurestr;
	
						if ($found == true) {
							$arry[] = $found;
						} else {
							$arry[] = $form->wordform;
						}
						$arry[] = $form->wordformID;
						$foundformstable[] = $arry;
					}
				}
	
			} else {
				$foundformstable = array();
			}
			
			
			// Tässä tsekataan muodot joiden analyysi puuttuu
			foreach($checkedforms as $index2 => $checkedform) {
				//echo "<br>foundi - " . $index2;
				if (!isset($foundchecked[$index2])) {
	
					if ($checkedform->grammatical == 1) {
						$arry = array();
						$arry[] = $checkedform->wordform;
						$featurestr = "";
						$featurearray = "";
						$first = true;
						foreach($checkedform->features as $index => $featureID) {
							$feature = $this->registry->features[$featureID];
							if ($first == true) {
								$featurestr = $feature->abbreviation;
								$featurearray = $featureID;
								$first = false;
							} else {
								$featurestr = $featurestr . "," . $feature->abbreviation;
								$featurearray = $featurearray . "," . $featureID;
							}
						}
						$arry[] = $featurestr;
						$arry[] = "Puuttuu-1";
						$arry[] = "0";
						$arry[] = $checkedform->rowID;
						$arry[] = $checkedform->wordformID;
						$foundformstable[] = $arry;
					}
				}
			}
			$this->registry->forms = $foundformstable;
			
			
				
			// --------------------------------------
			// 		Loading wordforms
			// --------------------------------------
			$wordforms = Table::load("worder_wordforms", " WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $this->registry->word->wordID);
				
			foreach($wordforms as $index => $form) {
				
				$arry = array();
				$arry[] = $form->wordform;
					
				$featurestr = "";
				$featurearray = "";
				$first = true;
				foreach($form->features as $index => $featureID) {
					//echo "<br>Feature found - " . $featureID;
					$feature = $this->registry->features[$featureID];
					if ($first == true) {
						$featurestr = $feature->abbreviation;
						$featurearray = $featureID;
						$first = false;
					} else {
						$featurestr = $featurestr . "," . $feature->abbreviation;
						$featurearray = $featurearray . "," . $featureID;
					}
				}
				if ($featurestr == "") $featurestr = "-";
				$arry[] = $featurestr;
					
				if ($form->grammatical == 1) {
					$arry[] = "OK";
					$arry[] = "1";
					$form->grammaticalstr = "OK";
					$form->grammatical = 1;
				} else {
					$arry[] = "*false";
					$form->grammaticalstr = "&lt;false&gt;";
					$form->grammatical = 0;
				}
				
				if ($form->defaultform == 0) {
					$form->default = "";
				} else {
					$form->default = "x";
				}
				
				$foundformstable[] = $arry;
					
				$values = array();
				foreach($form->features as $index => $featureID) {
					$values[$featureID] = $featureID;
				}
				foreach($this->registry->inflectionalfeatures as $index => $wordclassfeature) {
					$parentfeature = $this->registry->features[$wordclassfeature->featureID];
					$found = false;
					foreach($this->registry->features as $index2 => $feature) {
						if ($feature->parentID == $parentfeature->featureID) {
							if (isset($values[$feature->featureID])) {
								$variable = $parentfeature->name;
								//echo "<br>variable = "  . $variable;
								$form->$variable = $feature->featureID;
								$found = true;
							}
						}
						// Löytyy feature, jonka arvo on parentfeature, tämä on geneerinen featurevalue joka tarkentuu
						if ($parentfeature->featureID == $feature->featureID) {
							if (isset($values[$feature->featureID])) {
								$variable = $parentfeature->name;
								$form->$variable = $feature->featureID;
								$found = true;
							}
						}
					}
					if ($found == false) {
						$variable = $parentfeature->name;
						$form->$variable = 0;
					}
				}
			}
			
	
				
			//$this->registry->forms = $foundformstable;
			$this->registry->acceptedforms = $wordforms;
				
				
				
			$this->registry->template->show('worder/words','word');
				
				
		} else {
			// TODO: word not found
			echo "<br>No word found - " . $wordID;
			//redirecttotal('worder/words/showwords&lang=' . $languageID,null);
		}
	}
	
	
	
	public function showlanguageconceptsAction() {
		
		$languageID = $_GET['lang'];
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		
		$this->registry->grouptypeID = getSessionVar('grouptypeID', 0);
		$this->registry->grouptypes = Table::load('worder_wordgrouptypes', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordgroups = Table::load('worder_wordgroups', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		
		/*
		$linkedwords = Table::load("worder_wordgroups, "WHERE GrammarID=" . $_SESSION['grammarID']);
		$selectedconcepts = array();
		
		foreach($linkedwords as $index => $wordlink) {
			$wordgroup = $this->registry->wordgroups[$wordlink->wordgroupID];
			//if (($wordgroup->grouptypeID == $this->registry->grouptypeID) && ($wordgroup->languageID == $languageID)) {
			if ($wordgroup->grouptypeID == $this->registry->grouptypeID) {
				$selectedconcepts[$wordlink->conceptID] = $wordlink->conceptID;
			}
		}
		*/
		
		$selectedwords = Table::loadWhereInArray("worder_concepts", "ConceptID", $selectedconcepts, "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->words = $selectedwords;
		
		
		
		$this->registry->language = $language;
		
		$this->registry->template->show('worder/words','wordconcepts');
	}
	
	
	
	public function getlanguageconceptsJSONAction() {
	
		$languageID = $_GET['lang'];
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
	
		$grouptypeID = getSessionVar('grouptypeID', 0);
		$wordgroups = Table::load('worder_wordgroups');
	
		$linkedwords = Table::load("worder_wordgroup", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$selectedconcepts = array();
	
		foreach($linkedwords as $index => $wordlink) {
			$wordgroup = $wordgroups[$wordlink->wordgroupID];
			if ($wordgroup->grouptypeID == $grouptypeID) {
				$selectedconcepts[$wordlink->conceptID] = $wordlink->conceptID;
			}
		}
	
		$selectedwords = Table::loadWhereInArray("worder_concepts", "ConceptID", $selectedconcepts, "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->words = $selectedwords;
		
		echo "{";
		$first = true;
		foreach($selectedwords as $index => $value) {
			if ($first == true) {
				$first = false;
			} else {
				echo ",";
			}
			echo " \"" . $value->finnish_wordID . "\":";
			echo "	{";
			echo "			\"word\": \"" . $value->finnish_word. "\", ";
			echo "			\"concept\": \"" . $value->conceptID. "\" ";
			echo "	}";
		}
		echo "}";
	}
	
	
	


	public function getwordformsJSONAction() {
	
		$languageID = $_GET['languageID'];
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
	
		$wordID = $_GET['wordID'];
		$wordforms = Table::load("worder_wordforms", " WHERE WordID=" . $wordID);
		
		if ($wordforms == null) {
			echo "{";
			echo "}";
			return;
		}
		
		echo "{";
		$first = true;
		foreach($wordforms as $index => $wordform) {
			if ($first == true) {
				$first = false;
			} else {
				echo ",";
			}
			echo " \"" . $wordform->rowID . "\": \"" . $wordform->wordform. "\" ";
		}
		echo "}";
	}
	
	
	
	

	public function searchwordsJSONAction() {
	
		$search = $_GET['search'];
		$languageID = $_GET['languageID'];
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		
		$words = Table::load("worder_words","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND Lemma LIKE '%" . $search . "%' AND LanguageID=" . $languageID . " ORDER BY Lemma");

		$wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		echo "[";
		$first = true;
		foreach($words as $index => $word) {
			if ($first == true) $first = false; else echo ",";
			//echo "<br>" . $concept->conceptID . " - " . $concept->name . " - " . $concept->frequency;
			
			echo " {";
			echo "	  \"wordID\":\"" . $word->wordID . "\",";
			echo "	  \"name\":\"" . $word->lemma . "\",";
			echo "	  \"gloss\":\"" . $word->lemma . "\",";
			if ($word->wordclassID == 0) {
				echo "	  \"wordclassID\":\"0\",";
				echo "	  \"wordclass\":\"No class\",";
			} else {
				$wordclass = $wordclasses[$word->wordclassID];
				echo "	  \"wordclassID\":\"" . $word->wordclassID . "\",";
				echo "	  \"wordclass\":\"" . $wordclass->name . "\",";
			}
			echo "	  \"frequency\":\"0\"";
			echo " }\n";
		}
		echo "]";
		//echo "[  { \"conceptID\":\"12112\", \"name\":\"bbbb\" }, { \"conceptID\":\"1233112\" , \"name\":\"bbbaab\" }  ]";
	}
	
	

	public function searchconceptsAction() {
	
		$search = $_GET['search'];
		$concepts = Table::load("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LOWER(Name) LIKE '%" . $search . "%'");
	
		echo "[";
		$first = true;
		foreach($concepts as $index => $concept) {
			if ($first == true) $first = false; else echo ",";
			//echo "<br>" . $concept->conceptID . " - " . $concept->name . " - " . $concept->frequency;
	
			$wordclass = $concept->wordclassID;
			if ($concept->wordclassID == 1) $wordclass = "N";
			if ($concept->wordclassID == 2) $wordclass = "V";
			if ($concept->wordclassID == 3) $wordclass = "A";
			if ($concept->wordclassID == 10) $wordclass = "AS";
			if ($concept->wordclassID == 4) $wordclass = "AD";
			if ($concept->wordclassID == 13) $wordclass = "Fraasi";
				
			echo " {";
			echo "	  \"conceptID\":\"" . $concept->conceptID . "\",";
			echo "	  \"name\":\"" . $concept->name . "\",";
			echo "	  \"gloss\":\"" . $concept->gloss . "\",";
			echo "	  \"wordclassID\":\"" . $wordclass . "\",";
			echo "	  \"frequency\":\"" . $concept->frequency . "\"";
			echo " }\n";
		}
		echo "]";
		//echo "[  { \"conceptID\":\"12112\", \"name\":\"bbbb\" }, { \"conceptID\":\"1233112\" , \"name\":\"bbbaab\" }  ]";
	}
	
	
	

	public function insertconceptAction() {
	
		$comments = false;
	
		$languageID = $_GET['languageID'];
		$conceptID = $_GET['conceptID'];
		$wordID = $_GET['wordID'];
	
		$concept = Table::loadRow("worder_concepts", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		
		$linktable = "worder_conceptwordlinks";
		$values = array();
		$values['ConceptID'] = $conceptID;
		$values['WordID'] = $wordID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['LanguageID'] = $languageID;
		$rowID = Table::addRow($linktable, $values, $comments);
	
		if ($comments == false) redirecttotal('worder/words/showword&id=' . $wordID . "&lang=" . $languageID,null);
	}
	
	
	
	
	
	
	
	/**
	 * Recursive function for fetching all childs of given feature, itself included
	 * 
	 * @param integer $featureID
	 * @param mixed $features
	 */
	public static function getFeatureValueSet($featureID, $features, &$valueset) {

		foreach($features as $index => $feature) {
			//echo "<br>Feature - " . $feature;
			if ($feature->featureID == $featureID) $valueset[$featureID] = $feature;
			if ($feature->parentID == $featureID) {
				WordsController::getFeatureValueSet($feature->featureID, $features, $valueset);
			}
		}
	}
	
	
	
	private static function formsequals($formsA, $formsB) {
		
		//echo "<br>counta - " . count($formsA);
		//echo "<br>countb - " . count($formsB);
		
		$formbexists = array();		
		foreach($formsA as $indexa => $valuea) {
			$counter = 0;
			foreach($formsB as $indexb => $valueb) {
				//echo "<br>" . $valuea . " - " . $valueb;
				if ($valuea == $valueb) {
					//echo "<br>equals - " . $indexb;
					$formbexists[$indexb] = 1;	
					$counter++;
				} else {
					//echo "<br>not equals - " . $indexb;
				}
			}
			if ($counter == 0) {
				//echo "<br>counter nolla";
				return false;
			}
		}
		
		foreach ($formsB as $index => $value) {
			if (!isset($formbexists[$index])) {
				//echo "<br>notexists - " . $index;
				return false;
			}
		}
		
		return true;
	}
	
	

	public function formsToString($wordform, $features)  {
		$str = "";
		foreach($wordform->features as $index => $value) {
			$str = $str . "," . $features[$value]->abbreviation;
		}
		return $str;
	}
	
	
	
	
	private function getInflections($word, $features) {
		
		//echo "<br>InflectorID - " . $inflectorID;
		//echo "<br>Inflection - " . $inflection;
		//echo "<br>Inflectionclass - " . $inflectionclass;
		
		//echo "<br>";
		//var_dump($features);
		// FinnishVerbInflector::generateEveryForm($inflection);
		
		if ($word->inflectorID == 1) {
			// TODO: path rootista
			
			$dir = 'modules\\worder\\_classes\\finnishnouninflector.class.php';
			$dir = str_replace('\\', DIRECTORY_SEPARATOR, $dir);
			include( SITE_PATH . $dir);
			
			$forms = FinnishNounInflector::getWordForms($word->wordID, $word->lemma, $word->inflectionforms, $word->inflection);
			
			//FinnishNounInflector::
			//foreach($forms as $index=>$value) {
			//	echo "<br>" . $value->wordform . " -- " . $this->formsToString($value, $features);
			//}
			return $forms;
		} elseif ($word->inflectorID == 2) {
			// TODO: path rootista
			//include('C:\\Users\\pjuusita\\git\\babylon\\Babylon\\modules\\worder\\inflectors\\finnishverbinflector.class.php');

			$dir = 'modules\\worder\\_classes\\finnishverbinflector.class.php';
			$dir = str_replace('\\', DIRECTORY_SEPARATOR, $dir);
			include( SITE_PATH . $dir);
				
			$forms = array();
			$forms = FinnishVerbInflector::getWordForms($word->wordID, $word->lemma, $word->inflectionforms, $word->inflection);
		
			return $forms;
			
			
			/*
			foreach($forms as $index=>$value) {
				echo "<br>" . $value->wordform . " -- " . $this->formsToString($value, $features);
			}
			*/

			
		} elseif ($word->inflectorID == 7) {		// epäsäännälliset verbit
			
			$loadedforms = Table::load("worder_finnish_wordforms", " WHERE WordID=" . $this->registry->word->wordID. " AND Grammatical=1");
			
			// LOADED FORMS pitäisi muuttaa WordForm-luokan instannssiksi... varmaan konstruktori Wordformiin
			
			return $loadedforms;
				
			
		} elseif ($word->inflectorID == 9) {		// epäsäännälliset muut, tänne toistaiseksi substantiivit, pronominit yms.
						
				$loadedforms = Table::load("worder_finnish_wordforms", " WHERE WordID=" . $this->registry->word->wordID. " AND Grammatical=1");
						
				// LOADED FORMS pitäisi muuttaa WordForm-luokan instannssiksi... varmaan konstruktori Wordformiin
					
				return $loadedforms;
						

		} elseif ($word->inflectorID == 11) {		// epäsäännälliset muut, tänne toistaiseksi substantiivit, pronominit yms.
				
			$dir = 'modules\\worder\\_classes\\germanverbinflector.class.php';
			$dir = str_replace('\\', DIRECTORY_SEPARATOR, $dir);
			include( SITE_PATH . $dir);
				
			$forms = array();
			$forms = GermanVerbInflector::getWordForms($word->wordID, $word->lemma, $word->inflectionforms, $word->inflection);
			return $forms;
				
		} else {
			echo "<br>aa Unkonwn inflectorID - " . $word->inflectorID;
			return null;
		}
	}
	
	

	public function addwordfeatureAction() {
	
		$comments = false;
		$saveaction = true;
	
		$wordID =  $_GET['wordID'];
		$featureID =  $_GET['featureID'];
		$valueID =  $_GET['valueID'];
		if (isset($_GET['inheritancemodeID'])) {
			$inheritancemodeID =  $_GET['inheritancemodeID'];
		} else {
			$inheritancemodeID =  1;
		}
		$newfeaturekey = $featureID . ":" . $valueID . ":" . $wordID;
		
		$word = Table::loadRow("worder_words","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID, $comments);
		$existingFeatures = Table::load("worder_wordfeaturelinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID . " AND FeatureID=" . $featureID );
		
		/*
		if (count($existingFeatures) > 0) {
			echo "<br>Feature already exits.";
			exit;
		}
		*/
		
		$updatevalues = array();
		$updatevalues['WordID'] = $wordID;
		$updatevalues['FeatureID'] = $featureID;
		$updatevalues['ValueID'] = $valueID;
		$updatevalues['LanguageID'] = $word->languageID;
		$updatevalues['WordclassID'] = $word->wordclassID;
		$updatevalues['InheritancemodeID'] = $inheritancemodeID;
		$updatevalues['GrammarID'] = $_SESSION['grammarID'];
		if ($saveaction) $success = Table::addRow("worder_wordfeaturelinks", $updatevalues, $comments);
		if ($comments) {
			echo "<br>Adding worder wordfeaturelinks<br>";
			print_r($updatevalues);
		}
		
		$updatevalues = array();
		$wordfeaturesarray = explode("|", $word->features);
		$wordfeatures = array();
		foreach ($wordfeaturesarray as $index => $featurekey) {
			if ($featurekey != "") $wordfeatures[$featurekey] = $featurekey;
		}
		$wordfeatures[$newfeaturekey] = $newfeaturekey;
		if (count($wordfeatures) > 1) {
			$updatevalues['Features'] = implode('|', $wordfeatures);
		} else {
			$updatevalues['Features'] = implode('', $wordfeatures);
		}
		if ($saveaction) Table::updateRow('worder_words', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
		if ($comments) {
			echo "<br>Updating worder_concept<br>";
			print_r($updatevalues);
		}
			
		// päivitetään myäs kaikki alikäsitteet
		if ($inheritancemodeID != 3) {
	
			$childIDs = WordsController::getChildIDs($wordID);
			$childwords = Table::loadWhereInArray('worder_words', 'WordID', $childIDs, "WHERE GrammarID=" . $_SESSION['grammarID']);
				
			if ($childwords == null) {
				if ($comments) echo "<br><br>Childwords count - No childs found";
			} else {
				if ($comments) echo "<br><br>Childconcept count - " . count($childwords);
				foreach($childwords as $childwordID => $childword) {
						
					$updatevalues = array();
					if ($comments) echo "<br> - updating child " . $childword->name . " (" . $childword->wordID . ")";
						
					// päivitetään lapsen componentit
					$childfeaturearray = explode("|", $childword->features);
					$childfeatures = array();
					foreach ($childfeaturearray as $index => $featurekey) {
						if ($featurekey == "") {
							if ($comments) echo "<br>Empty found, no add";
						} else {
							$childfeatures[$featurekey] = $featurekey;
						}
					}
					$childfeatures[$newfeaturekey] = $newfeaturekey;
					if ($comments) echo "<br>Childcomponents - " . $childword->features;
	
					if ($comments) echo "<br> - - - before save = " . var_dump($childfeatures);
					if (count($childfeatures) > 1) {
						$updatevalues['Features'] = implode('|', $childfeatures);
					} else {
						$updatevalues['Features'] = implode('', $childfeatures);;
					}
						
					if ($comments) echo "<br> - - - Components = " . $updatevalues['Features'];
	
					if ($saveaction) Table::updateRow('worder_words', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $childwordID, $comments);
					if ($comments) {
						echo "<br><br>updatechildrow wordID - " . $wordID;
						print_r($updatevalues);
					}
				}
			}
		}
		if (!$comments) redirecttotal('worder/words/showword&id=' . $wordID, null);
	}
	
	
	
	/**
	 * TODO: Tämä on aika raskas operaatio
	 *
	 *
	 * @param unknown $conceptID
	 * @return multitype:unknown
	 */
	public static function getChildIDs($wordID, $comments = false) {
	
		global $mysqli;
		//$comments = true;
		$wordlinks = Table::load("worder_wordparentlinks","WHERE GrammarID=" . $_SESSION['grammarID'], $comments);
	
		$words= array();
		$words[$wordID] = $wordID;
	
		$oldcount = 0;
		$newcount = 1;
		while ($oldcount != $newcount) {
			$oldcount = $newcount;
			//echo "<br>Jee";
			foreach($wordlinks as $index => $wordlink) {
				if ($comments == true) echo "<br>Linkki - " . $wordlink->wordID . " - " . $wordlink->parentID;
				if (isset($words[$wordlink->parentID])) {
					if ($comments == true) echo "<br>--child found - " . $wordlink->wordID;
					$words[$wordlink->wordID] = $wordlink;
				}
			}
			$newcount = count($words);
		}
		unset($words[$wordID]);
		//echo "<br>getchhildsids<br>";
		//print_r($concepts);
	
		return $words;
	}
	
	
	//
	// Tämä on aika haastava funktio, syy on siinä, että halutaan pitää words-taulun kentät
	// parents, allparents ja parentpahts ajantasalla, koska silloin pitäisi normaalien hakujen
	// olla paljon tehokkaampia, ei tarvitse erikseen käydä lävitse links-tauluja lävitse.
	// Periaatteessa tällöin saattaa olla niin, että links taulu on turha, mutta kaippa sekin
	// pitää varmuuden vuoksi jättää.
	//
	// Ongelmana on viel ätoistaiseksi se, miten ylikirjoittaminen toimii, jos sama feature
	// asetetaan jossainkohtaa pathia toiseen kertaan, niin sen pitäisi ylikirjoittaa arvo.
	// toisaalta taas myös inheritancemodessa se voidaan mielestäni poista tai korvata. Tämä
	// sama ongelma esiintyy concepteissa.
	//
	//
	public function addparenttowordAction() {
	
		$wordID = $_GET['wordID'];
		$newParentID = $_GET['parentID'];
		$languageID = $_GET['languageID'];
		
		$comments = true;
		$saveactive = true;
	
		if ($wordID == $newParentID) {
			echo "<br>Cannot be parent of self";
			exit;
		}
	
		echo "<br>Loading word - " . $wordID;
		$word = Table::loadRow("worder_words","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID, $comments);

		echo "<br>Loading parent - " . $newParentID;
		$parent = Table::loadRow("worder_words","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $newParentID, $comments);
	
		if ($comments) echo "<br>wordID - "  . $wordID;
		if ($comments) echo "<br>parentID - "  . $newParentID;
	
		// luodaan lista kaikista ladattavista concepteista
		$parentarray = array();
		$finalallparentsarray = array();
	
		$parentarray[$wordID] = $wordID;
		$parentarray[$newParentID] = $newParentID;
		$currentparents = explode(':', $word->parents);
			
		foreach($currentparents as $index => $parentID) {
			if ($parentID == $newParentID) {
				echo "<br>Parent already exists - " . $parentID;
				exit;
			}
		}
	
		$allparents = explode(':', $word->allparents);
		foreach($allparents as $index => $parentID) {
			if (($parentID != 0) && ($parentID != '')) {
				$parentarray[$parentID] = $parentID;
				$finalallparentsarray[$parentID] = $parentID;
				if ($comments) echo "<br>--- Adding1 - " . $parentID;
			}
		}
		$finalallparentsarray[$newParentID] = $newParentID;
	
		$currentparentsstr = $word->parents;
		if ($comments) echo "<br>Currentparentstr - " . $word->parents;
		if ($currentparentsstr == "") {
			$currentparentsstr = $newParentID;
		} else {
			if ($currentparentsstr == "0") {
				$currentparentsstr = $newParentID;
			} else {
				$currentparentsstr = $currentparentsstr . ":" . $newParentID;
			}
		}
	
		if ($comments) {
			foreach($parentarray as $index => $parentID) {
				echo "<br>--- Parentti - " . $parentID;
			}
		}
	
	
		// nyt parentarray:ss on kaikki liittyvät sanat, ladataan linkit
		$parentlinks = Table::loadWhereInArray("worder_wordparentlinks","WordID",$parentarray,"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID, $comments);
		if ($comments) {
			echo "<br><br>Links";
			foreach($parentlinks as $index => $link) {
				echo "<br>--- Linkki - " . $link->rowID . ", wordID:" . $link->wordID . ", parentID:" . $link->parentID;
			}
		}
	
	
		// Luodaan kaikki parentpathsit
		if ($comments) echo "<br><br>";
		$allpaths = array();
		foreach($currentparents as $index => $parentID) {
			if ($comments) echo "<br>Checking parentpaths - " . $parentID;
			$oldpaths = WordsController::getPaths($parentID, $wordID, $parentlinks, $comments);
			foreach($oldpaths as $index => $path) {
				echo "<br>Oldpath found - " . $path;
				$allpaths[] = $path;
			}
			if ($comments) echo "<br>oldPaths - ";
			if ($comments) var_dump($oldpaths);
		}
		$newpaths = WordsController::getPaths($newParentID, $wordID, $parentlinks, $comments);
		if ($comments) echo "<br>newwipaths - ";
		if ($comments) var_dump($newpaths);
	
		foreach($newpaths as $index => $path) {
			$allpaths[] = $path;
		}
	
		$pathstr = WordsController::pathsArrayToString($allpaths);
	
		if ($comments) echo "<br><br>Pathstr - " . $pathstr;
		if ($comments) echo "<br><br>Currentparents - " . $currentparentsstr;
		if ($comments) echo "<br><br>allparents - " . implode(':',$finalallparentsarray);
	
		$values = array();
		$values['WordID'] = $wordID;
		$values['ParentID'] = $newParentID;
		$values['LanguageID'] = $languageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		if ($saveactive) {
			$rowID = Table::addRow("worder_wordparentlinks", $values, false, false);
		} else {
			$rowID = 99999;
		}
		
		
		// Tehdään temppirow linkseihin, voisi myös ladata rowID:n tietokannasta, mutta tämä nopeampi
		// Tätä tarvitaan siihen, että uudet pathit lasketaan parentlinks-taulukon avulla
		$newlink = new Row();
		$newlink->rowID = $rowID;
		$newlink->parentID = $newParentID;
		$newlink->wordID = $wordID;
		$parentlinks[$newlink->rowID] = $newlink;
	
		if ($comments) echo "<br>Lisätty worder wordparentlinks - WordID:" . $wordID . ", parentID:" . $newParentID;
		// Pitääkö tämä uusi linkki lisätä parentlinksiin, todennäköisesti?
	
		// Mikäli käsiteltävä konsepti on rootissa, ja ollaan asettamassa sille parenttia, poistetaan rootti parentti
		if (($word->parents == null) || ($word->parents == "0") || ($word->parents == "")) {
			if ($saveactive) Table::deleteRowsWhere("worder_wordparentlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID . " AND ParentID=0", false, false);
			foreach($parentlinks as $index => $link) {
				if (($link->wordID == $wordID) && ($link->parentID == 0)) {
					if ($comments) echo "<br>Removing item from parentlinks - "  . $link->wordID;
					unset($parentlinks[$index]);
				}
			}
		}
	
		$values = array();
		$updatevalues['Parents'] = $currentparentsstr;
		$updatevalues['Allparents'] = implode(':',$finalallparentsarray);
		$updatevalues['Parentpaths'] = $pathstr;
		if ($saveactive) Table::updateRow('worder_words', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
		if ($comments) echo "<br>Päivitetty words - WordID:" . $wordID;
	
	
		// Nyt pitäisi olla käytettävissä kaikki informaatio koko operaation tekemiseen...
	
		//   - Tsekkaa loopit
		// 			- Käsittääkseni riittää tsekata kyseisen parentin loopit suhteessa uuteen parenttiin
		//				- ehkä lapsillakin on mahdollista olla looppeja? Vai haittaako ne?
		//				- Ei saisi olla itseensä looppeja... pitänee ladata lapset myös...
		//   - Sitten pitäisi myös päivittää childit, tämä onkin sitten se työläämpi operaatio
		//   		- parentlinks on kyllä kunnossa, mutta childien perityt listat pitää päivittää
		//			- myös parentpathit pitää päivittää
	
	
	
		// Pitää päivittää myös kaikkien childien pathit.
		// Childit ovat siis kaikki wordin hierarkiassa alapuolella olevat wordit, jotka
		// perivät töltä ja tämän parenteilta ominaisuuksia. Word voi periä vanhemmiltaan
		// wordclass-featureiden arvoja (jotka on lexeemi-kohtaisia, ei wordform kohtaisia)
		$childarray = array();
		WordsController::getChildLinks($wordID, $childarray, $parentlinks, $comments);
		if ($comments) echo "<br><br>Childs found - " . implode(",", $childarray);
		$childarray[$wordID] = $wordID;
	
		// childarray sisältää kaikki childit
		// finalallparentsarray sisältää kaikki parentit, mukaan lukien nykyisen parentin
		// sisältääkö myös nykyisen wordID:n?
		$allwordssarray = array();
		foreach($finalallparentsarray as $index => $tempID) $allwordssarray[$tempID] = $tempID;
		foreach($childarray as $index => $tempID) $allwordssarray[$tempID] = $tempID;
		$allwordssarray[$wordID] = $wordID;
		
		$featurelinks = WordsController::loadAllFeatures($allwordssarray, $comments);
		//$componentlinks = ConceptsController::loadAllComponents($allconceptsarray);
		//$argumentlinks = ConceptsController::loadAllArguments($allconceptsarray);
	
		foreach($childarray as $index => $childID) {
			WordsController::updateWordFeatures($childID, $parentlinks, $featurelinks, $saveactive, $comments);
			WordsController::updateParentsAndPaths($childID, $parentlinks, $saveactive, $comments);
			//ConceptsController::updateComponentsAndArgumens($childID, $parentlinks, $componentlinks, $argumentlinks, $saveactive, $comments);
		}
	
	
	
		// Pitää päivittää childien componentlinksit
		// Pitää päivittää childien argumentlinksit
	
		// Childit pitää myös päivättää kun
		//	- argumentti lisätään
		//  - argumentti poistetaan
		//  - componentti lisätään
		//  - componentti poistetaan
	
		if ($comments == false) redirecttotal('worder/words/showword&id=' . $wordID . '',null);
	}
	
	
	
	/**
	 * 	Päivittää parametrina olevan conceptID:n parents ja parentpaths-kentät. Oletuksena on että parentlinks-taulu
	 *  Sisältää kaikki oleelliset linkit.
	 *
	 * @param unknown $childID
	 * @param unknown $parentlinks
	 */
	private static function updateParentsAndPaths($wordID, &$parentlinks, $saveactive, $comments = false) {
	
		if ($comments) echo "<br><br>Updating parents and paths: " . $wordID;
		$paths = WordsController::getPaths($wordID, -1, $parentlinks, $comments);
		$allparents = array();
		$directparents = array();
		$parentpaths = array();
		foreach($paths as $index => $patharray) {
			if ($comments) echo "<br>--- " . implode(":",$patharray);
			$parentpath = "";
			$previousparent = 0;
			foreach($patharray as $index2 => $parentID) {
				if ($parentID == $wordID) {
					if ($previousparent != 0) $directparents[$previousparent] = $previousparent;
				} else {
					if ($parentpath == "") {
						$parentpath = $parentID;
					} else {
						$parentpath = $parentpath . ":" . $parentID;
					}
					$allparents[$parentID] = $parentID;
				}
				$previousparent = $parentID;
			}
			$parentpaths[] = $parentpath;
		}
	
		if ($comments) echo "<br> -- Pathstr - " . implode("|",$parentpaths);
		if ($comments) echo "<br> -- Currentparents - " . implode(':',$directparents);
		if ($comments) echo "<br> -- allparents - " . implode(':',$allparents);
	
		$values = array();
		$updatevalues['Parents'] = implode(':',$directparents);
		$updatevalues['Allparents'] = implode(':',$allparents);
		$updatevalues['Parentpaths'] = implode("|",$parentpaths);
		if ($saveactive) Table::updateRow('worder_words', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
		if ($comments) echo "<br>Child päivitetty concepts - WordID:" . $wordID;
	}
	
	
	
	
	private static function loadAllFeatures($wordlist, $comments = false) {
		//echo "<br>Loading components -- " . implode(":", $conceptlist);
		$links = Table::loadWhereInArray("worder_wordfeaturelinks","WordID",$wordlist, "WHERE GrammarID=" . $_SESSION['grammarID'], $comments);
		return $links;
	}
	
	
	
	

	/**
	 * Päivittää annetun conceptID:n components ja requirements kentät.
	 *
	 * @param unknown $conceptID
	 * @param unknown $parentlinks
	 * @param unknown $componentlinks
	 * @param unknown $argumentlinks
	 * @param unknown $saveactive
	 */
	private static function updateWordFeatures($wordID, $parentlinks, $featurelinks, $saveactive, $comments = false) {
	
		if ($comments) echo "<br><br>update features: " . $wordID;
		$paths = WordsController::getPaths($wordID, -1, $parentlinks, $comments);
	
		if ($comments) {
				
			echo "<br>Count paths --- " . count($paths);
			foreach($featurelinks as $index => $link) {
				echo "<br>Featurelink - " . $link->componentID . " - " . $link->wordID;
			}
		}
	
	
		// Tarvittaisiin vain all parents, rekursiivisesti
		$allparents = array();
		foreach($paths as $index => $patharray) {
			if ($comments) echo "<br>Pathi - ";
			foreach($patharray as $index2 => $parentID) {
				if ($comments) echo "<br>Parent - " . $parentID;
				if (($parentID != null) && ($parentID != 0)) {
					$allparents[$parentID] = $parentID;
				} else {
					if ($comments) echo "<br>Not parent - " . $parentID;
				}
			}
		}
		$allparents[$wordID] = $wordID;
	
		$features = array();
		foreach($allparents as $index => $parentID) {
			if ($comments) echo "<br>Links from parent - " . $parentID;
			if ($featurelinks != null) {
				foreach($featurelinks as $index => $link) {
					if ($comments) echo "<br>Featurelink --- featureID:" . $link->featureID . ", mode: " . $link->inheritancemodeID . ", wordID:" . $link->wordID;
					if ($link->wordID == $parentID) {

						if ($link->inheritancemodeID == InheritanceModes::SINGLE) {
							if ($link->wordID == $wordID) {
								$features[] = $link->featureID . ":" . $link->valueID . ":" . $link->wordID;
							}
						} else if (($link->inheritancemodeID == InheritanceModes::FOR_CHILDS) || ($link->inheritancemodeID == InheritanceModes::INHERITABLE)) {
							$features[] = $link->featureID . ":" . $link->valueID . ":" . $link->wordID;
						}
					}
				}
			} else {
				if ($comments) echo "<br>Featurelinks null";
			}
		}
	
		if ($comments) echo "<br>-- Found components - " . implode("|", $features);
		
		$values = array();
		$updatevalues['Features'] = implode("|", $features);
		if ($saveactive) Table::updateRow('worder_words', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
		if ($comments) echo "<br>Features päivitetty word - WordID:" . $wordID;
	}
	
	
	

	public function removeparentAction() {
	
		$wordID = $_GET['wordID'];
		$parentID = $_GET['id'];
	
		$saveactive = true;
		$comments = false;
	
		if ($comments) echo "<br>Remove - " . $wordID . " - " . $parentID;
	
		$word = Table::loadRow("worder_words","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
		
		if ($saveactive == true) Table::deleteRowsWhere("worder_wordparentlinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID . " AND ParentID=" . $parentID);
	
		$rows = Table::load('worder_wordparentlinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
	
		if ($comments) {
			//var_dump($rows);
			foreach($rows as $index => $link) {
				echo "<br>Link - " . $link->parentID;
			}
		}
	
		if (count($rows) == 0) {
			$insertarray = array();
			$insertarray['ParentID'] = 0;
			$insertarray['WordID'] = $wordID;
			$insertarray['GrammarID'] = $_SESSION['grammarID'];
			$insertarray['LanguageID'] = $word->languageID;
			Table::addRow("worder_wordparentlinks", $insertarray, false, false);
		}
	
		$parentarray = array();
		$allparents = explode(':', $word->allparents);
		foreach($allparents as $index => $parentID) {
			if (($parentID != 0) && ($parentID != '')) {
				$parentarray[$parentID] = $parentID;
			}
		}
		if (count($parentarray) == 0) {
			if ($comments)echo "<br>no parents";
		}
		$parentarray[$wordID] = $wordID;
		$parentlinks = Table::loadWhereInArray("worder_wordparentlinks","WordID",$parentarray,"WHERE GrammarID=" . $_SESSION['grammarID']);
		if ($comments)echo "<br><br>Links";
		foreach($parentlinks as $index => $link) {
			if ($comments)echo "<br>--- Linkki - " . $link->rowID . ", conceptID:" . $link->conceptID . ", parentID:" . $link->parentID;
		}
	
	
		// Pitää päivittää myös kaikkien childien pathit.
		$childarray = array();
		WordsController::getChildLinks($wordID, $childarray, $parentlinks, $comments);
		if ($comments) echo "<br><br>Childs found - " . implode(",", $childarray);
		$childarray[$wordID] = $wordID;	// lisätään päivitettäviin myös self
		
		// childarray sisältää kaikki childit
		// finalallparentsarray sisältää kaikki parentit, mukaan lukien nykyisen parentin, sisältääkö myös nykyisen conceptID:n?
		$allwordssarray = array();
		foreach($parentarray as $index => $tempID) $allwordssarray[$tempID] = $tempID;
		foreach($childarray as $index => $tempID) $allwordssarray[$tempID] = $tempID;
		$allwordssarray[$wordID] = $wordID;
		if ($comments) {
			foreach($allwordssarray as $index => $value) {
				echo "<br>--- Allwords - " . $index . ", conceptID:" . $value. "";
			}
		}
		
		$featurelinks = WordsController::loadAllFeatures($allwordssarray, $comments);
		
		foreach($childarray as $index => $childID) {
			WordsController::updateWordFeatures($childID, $parentlinks, $featurelinks, $saveactive, $comments);
			WordsController::updateParentsAndPaths($childID, $parentlinks, $saveactive, $comments);
			//ConceptsController::updateComponentsAndArgumens($childID, $parentlinks, $componentlinks, $argumentlinks, $saveactive, $comments);
		}
		
		if (!$comments) redirecttotal('worder/words/showword&id=' . $wordID, null);
	}
	
	


	private static function getChildLinks($parentID, &$childarray, &$parentlinks, $comments = false) {
		$parentarray = array();
		$parentarray[$parentID] = $parentID;
		WordsController::getChildLinksRecursive($parentarray, $childarray, $parentlinks,0, $comments);
	}
	
	

	/**
	 * Tämä palauttaa rekursiivisesti kaikki löydetyt lapset taulukossa childarray. Löydetyt parentlinksit lisätään
	 * parametriin
	 *
	 * @param unknown $parentarray		ConceptID:t joiden childit halutaan etsiä, alkuperäisessä kutsussa yksi itemi, mutta rekursio lisää löydetyt childit tähän
	 * @param unknown $childarray		Taulukko johon lisätään kaikki löydetyt lapset, level-järjestyksessä, voi sisältää tuplia
	 * @param unknown $parentlinks		Taulukkoon lisätään kaikki ladatut parentlinksit, näitä ei tarvitse ladata uudelleen
	 * @param number $level				Rekursiossa käytettävä kountteri, kutsutaan alunperin ilman tätä
	 */
	private static function getChildLinksRecursive($parentarray, &$childarray, &$parentlinks, $level, $comments = false) {
	
		if ($level > 10) {
			echo "<br>Too far recursion";
			exit;
		}
		if ($comments) echo "<br>Level - " . $level;
		$childlinks = Table::loadWhereInArray("worder_wordparentlinks","ParentID",$parentarray,"WHERE GrammarID=" . $_SESSION['grammarID'], true);
		if ($comments) echo "<br><br>Childlinks";
		$allchilds = array();
		foreach($childlinks as $index => $link) {
			if ($comments) echo "<br>--- Linkki - " . $link->rowID . ", wordID:" . $link->wordID . ", parentID:" . $link->parentID;
			$allchilds[$link->wordID] = $link->wordID;
			if (!isset($childarray[$link->wordID])) {
				$childarray[$link->wordID] = $link->wordID;		// Tämä estää tuplien synnyn
				// $childarray[] = $link->wordtID						// Vaihtoehtoinen joka kerää myös tuplaesiintymät
			}
			$parentlinks[$link->rowID] = $link;
		}
		if (count($allchilds) == 0) {
			if ($comments) echo "<br>No more childs";
		} else {
			$level = $level+1;
			if ($comments) echo "<br>Continuing to next level " . $level  . " - " . implode(",", $allchilds);
			WordsController::getChildLinksRecursive($allchilds, $childarray, $parentlinks, $level, $comments);
		}
	}
	
	

	private static function pathsArrayToString($paths) {
		$str = "";
		foreach($paths as $index => $patharray) {
			if ($str == "") {
				$str = implode(":",$patharray);
			} else {
				$str = $str . "|" . implode(":",$patharray);
			}
		}
		return $str;
	}
	

	private static function getPaths($parentID, $newchildID, $links, $comments = false) {
		$pathsarray = array();
		if ($comments) echo "<br>Get paths - parentID:" . $parentID . ", child:" . $newchildID;
		$currentpatharray = array();
		$currentpatharray[] = $parentID;
		WordsController::getPathsRecursive($parentID, $newchildID, $currentpatharray, $pathsarray, $links, $comments);
		return $pathsarray;
	}
	
	
	// Kelataan rekursiivisesti läpi kaikki linkit,
	private static function getPathsRecursive($parentID, $newchildID, $currentpatharray, &$pathsarray, $links, $comments = false) {
		if ($comments) echo "<br>Currentpath - " .  implode(":", $currentpatharray);
		foreach($links as $index => $link) {
			if ($comments) echo "<br>--- Checking link - " . $link->rowID . ", wordID:" . $link->wordID . ", parentID:" . $link->parentID;
			if ($link->wordID == $parentID) {
				if ($comments) echo "<br>--- word matches";
				if ($link->parentID == 0) {
					//$currentpath = $currentpath;
					if ($comments) echo "<br>currentpath found -- " . implode(":", $currentpatharray);
					$pathsarray[] = $currentpatharray;
				} else {
					if ($link->parentID == $newchildID) {
						if ($comments) echo "<br>--- Loop exits: loop - " . $link->rowID . ", wordID:" . $link->wordID . ", parentID:" . $link->parentID;
						if ($comments) echo "<br><br>";
						echo "<br>FAILED, LOOP FOUND";
						exit;
					} else {
						$newcurrentpath = array();
						$newcurrentpath[] = $link->parentID;
						foreach($currentpatharray as $index => $coneptID) {
							$newcurrentpath[] = $coneptID;
						}
						//array_unshift($currentpatharray , $link->parentID);
						WordsController::getPathsRecursive($link->parentID, $newchildID, $newcurrentpath, $pathsarray, $links, $comments);
					}
				}
			}
		}
	}
	
	
	
	public function insertgroupAction() {
		
		$values = array();
		$wordID =  $_GET['wordID'];
		$langID =  $_GET['lang'];
		$wordgroupID =  $_GET['groupID'];
		$values['WordgroupID'] = $wordgroupID;
		$values['ConceptID'] = $_GET['conceptID'];
		
		$success = Table::addRow("worder_wordgroupconcepts", $values, false);
	
	
		if ($success === true) {
			addMessage('Lisätty onnistuneesti.');
		} else {
			addErrorMessage("Tuntematon tietokantavirhe. - " . $success);
		}
		//echo ('worder/words/showword&id=' . $wordID);
		
		redirecttotal('worder/words/showword&lang=' . $langID . '&id=' . $wordID,null);
	}

	

	public function insertsentenceAction() {
	
		$languageID =  $_GET['languageID'];
		$wordID =  $_GET['wordID'];
		$sentence =  $_GET['sentence'];
		$conceptID =  $_GET['conceptID'];
		
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$word = Table::loadRow("worder_words", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
				
		$values = array();
		$values['Sentence'] = $sentence;
		$values['Words'] = $wordID . ":" . $conceptID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['LanguageID'] = $languageID;
		$values['Correctness'] = $_GET['correctness'];
		$sentenceID = Table::addRow("worder_sentences", $values, false);
		
		$values = array();
		$values['SentenceID'] = $sentenceID;
		$values['WordID'] = $wordID;
		$values['ConceptID'] = $conceptID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['LanguageID'] = $languageID;
		$rowID = Table::addRow("worder_sentencelinks", $values, false);
		
		redirecttotal('worder/words/showword&lang=' . $languageID . '&id=' . $wordID,null);
	}
	
	

	public function insertwordformAction() {
	
		$comments = false;
		$wordID = $_GET['wordID'];
		$languageID = $_GET['languageID'];
		$wordform = $_GET['wordform'];
		
	
		// Jos on asetettu default muotoon, niin ei tarvitse tallentaa
		$this->registry->language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$word = Table::loadRow('worder_words',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
		
		
		$wordclassfeatures = Table::load("worder_wordclassfeatures", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $word->wordclassID . " AND LanguageID=" .  $languageID);
		$featurelist = "";
	
		foreach($wordclassfeatures as $index => $wordclassfeature) {
				
			$str = "feature-" . $wordclassfeature->featureID;
			if (isset($_GET[$str])) {
				$value = $_GET[$str];
				if ($comments) echo "<br>--- value found - " . $str;
				if (($value != "") && ($value != 0)) {
					if ($featurelist != "") $featurelist = $featurelist . ":" . $value;
					else $featurelist = $value;
				}
			} else {
				if ($comments) echo "<br>--- value not found - " . $str;
			}
		}
		if ($comments) echo "<br>Featurelist - " . $featurelist;
		
		$values = array();
		$values['WordID'] = $wordID;
		$values['Wordform'] = $wordform;
		$values['Features'] =  $featurelist;
		$values['GrammarID'] =  $_SESSION['grammarID'];
		$values['LanguageID'] =  $languageID;
		$values['WordclassID'] = $word->wordclassID;
		$values['Grammatical'] = 1;
		$values['Defaultform'] = 1;
		$rowID = Table::addRow('worder_wordforms', $values, $comments);
	
	
	
		if ($comments == false) redirecttotal('worder/words/showword&lang=' . $languageID . '&id=' . $wordID,null);
	}
	
	


	public function insertwordAction() {
	
		$comments = false;
	
		$languageID = $_GET['languageID'];
		$wordclassID = $_GET['wordclassID'];
		$lemma = $_GET['word'];
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
			
		$values = array();
		$values['Lemma'] = $lemma;
		$values['WordclassID'] = $wordclassID;
		$values['LanguageID'] = $languageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$wordID = Table::addRow("worder_words", $values, $comments);
		
		if ($wordclassID == null) {
			$wordclassfeatures = Table::load("worder_wordclassfeatures", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" .  $languageID, true);
			$featurelist = "";
		} else {
			$wordclassfeatures = Table::load("worder_wordclassfeatures", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $wordclassID . " AND LanguageID=" .  $languageID, true);
			$featurelist = "";
		}
	
		$values = array();
		$values['WordID'] = $wordID;
		$values['ParentID'] = 0;
		$values['LanguageID'] = $languageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$wordparentlinkID = Table::addRow("worder_wordparentlinks", $values, $comments);
		
		
		/*
		foreach($wordclassfeatures as $index => $wordclassfeature) {
			if (($wordclassfeature->wordbookformID != null) && ($wordclassfeature->wordbookformID != "") && ($wordclassfeature->wordbookformID != 0)) {
				if ($featurelist != "") $featurelist = $featurelist . ":" . $wordclassfeature->wordbookformID;
				else $featurelist = $wordclassfeature->wordbookformID;
			}
		}
		
		$values = array();
		$values['WordID'] = $wordID;
		$values['Wordform'] = $lemma;
		$values['Features'] =  $featurelist;
		$values['Grammatical'] = 1;
		$values['LanguageID'] = $languageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['Defaultform'] = 1;
		$rowID = Table::addRow("worder_wordforms", $values, $comments);
		*/
		
		if (!$comments) redirecttotal('worder/words/showword&id=' . $wordID,null);
	}
	
	
	
	public function updatefeaturesAction() {
	
		$comments = false;
		
		global $mysqli;
		$wordID = $_GET['wordID'];
		$languageID = $_GET['languageID'];
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		
		$word = Table::loadRow("worder_words","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
		
		
		$wordclass = Table::loadRow("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $word->wordclassID);
		$this->registry->features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);
			
		if ($comments) echo "<br>Wordclass - " . $wordclass->name;
		$sqlstr = "";
			
		foreach($wordclass->features as $index => $featureID) {
		
			$feature = $this->registry->features[$featureID];
			
			if (isset($_GET[$feature->name])) {
				if ($comments) echo "<br>Param value " . $_GET[$feature->name] . " - " . $feature->featureID;
				if ($sqlstr != "") $sqlstr = $sqlstr . ":";
				$sqlstr = $sqlstr . "" . $feature->featureID . "=" . $_GET[$feature->name];
			} else {
				if ($comments) echo "<br>Param " . $feature->name  . " nout found";
			}
		}
		if ($comments) echo "<br>str " . $sqlstr . "";
		
		echo "<br>Korjaa, worder_words ei sisälä featurevalues saraketta";
		exit();
		$sql = "UPDATE worder_words SET FeatureValues='" . $sqlstr . "' WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID;
		//if ($comments) echo "<br>Sql - " . $sql;
		
		$result = $mysqli->query($sql);
		
		
		redirecttotal('worder/words/showword&lang=' . $languageID . '&id=' . $wordID ,null);
		
		//echo "[{\"success\":\"true\"}]";
	}
	

	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function acceptwordformAction() {
	
		$comments = true;
		$languageID = $_GET['lang'];
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		
		$str = $_GET['id'];
		$strlist = explode(":", $str);
		$wordID = $strlist[0];
		$baseform = $strlist[1];
		$features = $strlist[2];
			
		$word = Table::loadRow("worder_words", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID, $comments);
		
		$wordfrom = Table::loadRowWhere("worder_wordforms"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID . " AND Wordform='" . $baseform . "' AND Features='" . str_replace(",", ":", $features) . "'", $comments);
		
		if ($wordfrom != null) {
			if ($comments) echo "<br>Läytyi";
			$values['Grammatical'] = 1;
			$success = Table::updateRow("worder_wordforms", $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $wordfrom->rowID, $comments);
			
		} else {
			$values = array();
			$values['WordID'] = $wordID;
			$values['Wordform'] = $baseform;
			$values['Features'] =  str_replace(",", ":", $features);
			$values['Grammatical'] = 1;
			$values['LanguageID'] = $word->languageID;
			$values['WordclassID'] = $word->wordclassID;
			$values['Defaultform'] = 1;
			$values['GrammarID'] = $_SESSION['grammarID'];
			$wordID = Table::addRow("worder_wordforms", $values, $comments);
		}
		
		
		
		//redirecttotal('worder/words/showword&lang=' . $languageID . '&id=' . $strlist[0],null);
	}
	
	
	
	/**
	 * Asettaa sanamuodon taivutusmuodon vääräksi wordforms-taulussa
	 * 
	 */
	public function failwordformAction() {
	
		
		$languageID = $_GET['lang'];
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
	
		$wordID = $_GET['id'];
		$originalwordID = $_GET['wordid'];;
		//echo "<br>wordID - " . $wordID;
	
		if (is_numeric($wordID)) {
			$values['Grammatical'] = 0;
			$success = Table::updateRow("worder_wordforms", $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $wordID);
		} else {
			$wordID = $_GET['wordid'];
			$wordform = $_GET['id'];
				
			$values = array();
			$values['WordID'] = $wordID;
			$values['Wordform'] = $wordform;
			$values['Features'] =  '';
			$values['Grammatical'] = 0;
			$values['Defaultform'] = 0;
			$values['GrammarID'] = $_SESSION['grammarID'];
			$wordID = Table::addRow("worder_wordforms", $values, false);	
		}
		
		redirecttotal('worder/words/showword&lang=' . $languageID . '&id=' . $originalwordID,null);
	}
	
	



	public function searcwordAction() {
	
		$search = $_GET['search'];
		$languageID = $_GET['LanguageID'];
		$words  = Table::load("worder_words","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID . " AND name REGEXP '" . $search . "'", true);
	
		echo "[";
		$first = true;
		foreach($words as $index => $word) {
			if ($first == true) $first = false; else echo ",";
			//echo "<br>" . $concept->conceptID . " - " . $concept->name . " - " . $concept->frequency;
	
			$wordclass = $word->wordclassID;
			if ($word->wordclassID == 1) $wordclass = "N";
			if ($word->wordclassID == 2) $wordclass = "V";
			if ($word->wordclassID == 3) $wordclass = "A";
			if ($word->wordclassID == 10) $wordclass = "AS";
			if ($word->wordclassID == 4) $wordclass = "AD";
	
			echo " {";
			echo "	  \"conceptID\":\"" . $word->wordID . "\",";
			echo "	  \"name\":\"" . $word->name . "\",";
			echo "	  \"gloss\":\"" . $word->name . "\",";
			echo "	  \"wordclassID\":\"" . $wordclass . "\",";
			echo "	  \"frequency\":\"0\"";
			echo " }\n";
		}
		echo "]";
		//echo "[  { \"conceptID\":\"12112\", \"name\":\"bbbb\" }, { \"conceptID\":\"1233112\" , \"name\":\"bbbaab\" }  ]";
	}
	
	
	
	/**
	 * Tätä listausta käytetään tarkistamaan onko sanalle asetettu taivutusmuotoja
	 *
	 * Potentiaaliset filtterit
	 *    - grouptype (sanojen lukumäärä on suuri, raskas operaatio, mutta ehkä kuitenkin hyädyllinen)
	 *    - group
	 *    - component
	 *
	 *  Filtterit korvaavat aina aiemman olemasaolevan filtterin arvon kokonaan, ei ja toimintoa
	 *
	 */
	public function inflectedformsAction() {
	
		global $mysqli;
	
		$comments = false;
		updateActionPath("Inflectedforms");
		
		if ($comments) echo "<br>currentlocation - " . $_SESSION['current_location'];
		
		$this->registry->languageID = getSessionVar('languageID', 0);
		$this->registry->wordclassID = getSessionVar('wordclassID', 0);
		//$this->registry->languageID = $_GET['lang'];
		$this->registry->languages = Table::load("worder_languages","WHERE GrammarID=" . $_SESSION['grammarID']. " ORDER BY Name");

		if ($this->registry->languageID == 0) {
			foreach($this->registry->languages as $index => $language) { 
				$this->registry->languageID = $language->languageID;
				break;
			}
		}
		$this->registry->language = $this->registry->languages[$this->registry->languageID];
		
		
		echo "<br>LanguageID - " . $this->registry->languageID;
		
		//$this->registry->languageID = 1;
		//$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
		//$this->registry->language = $language;
		
		//$this->registry->grouptypes = Table::load('worder_wordgrouptypes', "WHERE GrammarID=" . $_SESSION['grammarID']);
		//$this->registry->wordgroups = Table::load('worder_wordgroups', "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		
		if ($this->registry->wordclassID == 0) {
			$selectedwords = Table::load("worder_words", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
		} else {
			$selectedwords = Table::load("worder_words", " WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID . " AND WordclassID=" . $this->registry->wordclassID);
		}
		
		$selectedwordlist = array();
		foreach($selectedwords as $index => $word) {
			$selectedwordlist[$word->wordID] = $word->wordID;
		}
		
		//$conceptlinks = Table::loadWhereInArray("worder_conceptwordlinks", "WordID", $selectedwordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		//if ($comments) echo "<br>conceptlinktable - worder_conceptwordlinks - " . count($conceptlinks);
		
		//$selectedcontepts = array();
		//$wordconcepts = array();
		//foreach($conceptlinks as $index => $link) {
			//echo "<br>Selected concept - " . $link->conceptID;
			//$selectedcontepts[$link->conceptID] = $link->conceptID;
			//$wordconcepts[$link->wordID] = $link->conceptID;
		//}

		//$concepts = Table::loadWhereInArray("worder_concepts", "ConceptID", $selectedcontepts, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		//echo "<br>Korvaa, läpikäynti liian raskas";
		//exit();
		
		$sql = "SELECT * FROM worder_wordforms WHERE Grammatical=1 AND GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID;
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>Error: " . $mysqli->error;
			exit;
		}
				
		$wordcounts = array();
		while($row = $result->fetch_array()) {
			$wordID = $row['WordID'];
			if (isset($selectedwords[$wordID])) {
				if (isset($wordcounts[$wordID])) {
					$wordcounts[$wordID] = $wordcounts[$wordID] + 1;
				} else {
					$wordcounts[$wordID] = 1;
				}
			} else {
				//echo "<br> - word not selected";
			}
		}
		
		$words = array();
		$wordswithoutconcept = array();
			
		$noinflections = 0;
		$enoughinflections = 0;
		$missinginflections = 0;
		$missinglist = array();
		
		foreach ($selectedwords as $index => $word) {
			$wordarray = array();
			
				if ($comments) echo "<br>Concept not found - " . $word->lemma . ", wordID=" . $word->wordID . ", conceptID=" . $conceptID;
				
				$wordarray[0] = "";
				$wordarray[1] = $word->wordID;
				$wordarray[2] = $word->lemma;
				
				if (isset($wordcounts[$word->wordID])) {
					$wordcount = $wordcounts[$word->wordID];
				} else {
					$wordcount = 0;
				}
				$wordclass = $this->registry->wordclasses[$word->wordclassID];
				
				$wordarray[3] = $wordcount;
				$wordarray[4] = $wordclass->name;				// tarkalleen ottaen tämä voi olla eri
				
				echo "<br>Wordcount - " . $wordcount . " - " . $wordclass->wordclassID . " - " . $this->registry->languageID;
				
				if ($wordcount == 0) {
					
					$inf = explode("-",$word->inflection);
					$inf1 = $inf[0];
					if (isset($missinglist[$inf1])) {
						//echo "<br>Missingword - " . $word->lemma;
						$missinglist[$inf1] = $missinglist[$inf1] + 1;
					} else {
						$missinglist[$inf1] = 1;				
					}
					$wordarray[5] = "<font style='color:red'>E" . $inf[0] . "x Ei taivutusmuotoja</font>";
					$missinginflections++;
				} else {
					if ($wordclass->wordclassID == 1) {

						$found = false;
						
						if ($this->registry->languageID == 1) {		// suomi
							if ($wordcount < 26) {
								$wordarray[5] = "<font style='color:red'>Puutteellinen a " . $wordcount . "</font>";
								$missinginflections++;
							} else {
								$wordarray[5] = "<font style='color:green'>OK</font>";
								$enoughinflections++;
							}
							$found = true;
						}
						
						if ($this->registry->languageID == 2) {		// englanti
							if ($wordcount < 2) {
								$wordarray[5] = "<font style='color:red'>Puutteellinen b " . $wordcount . "</font>";
								$missinginflections++;
							} else {
								$wordarray[5] = "<font style='color:green'>OK</font>";
								$enoughinflections++;
							}
							$found = true;
						}
						
						if ($found == false) {
							$wordarray[5] = "<font style='color:red'>Ei määritelty " . $wordcount . "</font>";
							$missinginflections++;
						}
						
						
					} elseif ($wordclass->wordclassID == 9) {		// numeraali, toistaiseksi substantiivin taivutusmuodot 26 riittää
						if ($wordcount < 26) {
							$wordarray[5] = "<font style='color:red'>Puutteellinen c " . $wordcount . "</font>";
							$missinginflections++;
						} else {
							$wordarray[5] = "<font style='color:green'>OK - " . $wordcount . "</font>";
							$enoughinflections++;
						}
					} elseif ($wordclass->wordclassID == 3) {		// adjektiivi, toistaiseksi ilman komparatiivia ja superlatiivia, 26 riittää
						if ($wordcount < 26) {
							$wordarray[5] = "<font style='color:red'>Puutteellinen</font>";
							$missinginflections++;
						} else {
							$wordarray[5] = "<font style='color:green'>OK</font>";
							$enoughinflections++;
						}
					} elseif ($wordclass->wordclassID == 2) {		// verbi
						
						$langfound = false;
						if ($this->registry->languageID == 1) {		// suomi
							$langfound = true;
							if ($wordcount < 51) {
								$wordarray[5] = "<font style='color:red'>Puutteellinen</font>";
								$missinginflections++;
							} else {
								$wordarray[5] = "<font style='color:green'>OK - " . $wordcount . "</font>";
								//$wordarray[5] = "<font style='color:green'>OK</font>";
								$enoughinflections++;
							}
						}
						
						if ($this->registry->languageID == 2) {		// englanti
							$langfound = true;
							if ($wordcount < 5) {
								$wordarray[5] = "<font style='color:red'>Puutteellinen</font>";
								$missinginflections++;
							} else {
								$wordarray[5] = "<font style='color:green'>OK - " . $wordcount . "</font>";
								//$wordarray[5] = "<font style='color:green'>OK</font>";
								$enoughinflections++;
							}
						}
						
						if ($this->registry->languageID == 3) {		// Saksa
							$langfound = true;
							if ($wordcount < 18) {
								$wordarray[5] = "<font style='color:red'>Puutteellinen</font>";
								$missinginflections++;
							} else {
								$wordarray[5] = "<font style='color:green'>OK - " . $wordcount . "</font>";
								//$wordarray[5] = "<font style='color:green'>OK</font>";
								$enoughinflections++;
							}
						}
						
						
						if ($langfound == false) {
							$wordarray[5] = "<font style='color:red'>Kieli ei määritelty</font>";
						}
						
						
					} else {
						$wordarray[5] = "<font style='color:red'>N/A (wordclass:" . $word->wordclassID . ")</font>";
						$noinflections++;
					}
				}
				$wordarray[6] = $word->inflection;
				$wordswithoutconcept[] = $wordarray;
		}
		$this->registry->wordswithoutconcept = $wordswithoutconcept;
		
		$this->registry->missinginflections = $missinginflections;
		$this->registry->enoughinflections = $enoughinflections;
		$this->registry->noinflections = $noinflections;
		/*
		foreach ($missinglist as $index => $value) {
			echo "<br>Missing - " . $index . " -  " . $value;
		}
		*/
		
		$this->registry->template->show('worder/words','inflectedforms');
	}
	
	
	public function declensionsAction() {
		
		$comments = false;
		updateActionPath("Featurefilter");
		$this->registry->languages = Table::load("worder_languages","WHERE GrammarID=" . $_SESSION['grammarID']. " ORDER BY Name");
		$this->registry->wordclasses = Table::load("worder_wordclasses","WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->languageID = getSessionVar('languageID', 0);
		$this->registry->parentfeatureID = getSessionVar('parentfeatureID', 0);
		$this->registry->featureID = getSessionVar('featureID', 0);
		$this->registry->wordclassID = getSessionVar('wordclassID', 0);
		
		if ($comments) echo "<br>languageID - " . $this->registry->languageID;
		if ($comments) echo "<br>parentfeatureID - " . $this->registry->parentfeatureID;
		if ($comments) echo "<br>featureID - " . $this->registry->featureID;
		if ($comments) echo "<br>wordclassID - " . $this->registry->wordclassID;
		
		
		if ($this->registry->languageID == 0) {
			$this->registry->features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']. " ORDER BY Name");
		} else {
			$this->registry->features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']. " AND LanguageID=" . $this->registry->languageID . " ORDER BY Name");
		}
		
		$parentfeatures = array();
		if ($this->registry->wordclassID != 0) {
			$wordclassfeatures = Table::load("worder_wordclassfeatures","WHERE LanguageID=" . $this->registry->languageID . " AND WordclassID=" . $this->registry->wordclassID . " AND GrammarID=" . $_SESSION['grammarID']);
			
			foreach($wordclassfeatures as $index => $link) {
				$feature = $this->registry->features[$link->featureID];
				$parentfeatures[$feature->featureID] = $feature;
			}
		
		} else {
			
		}
		$this->registry->parentfeatures = $parentfeatures;
		
		$currentfeatures = array();		
		$row = new Row();
		$row->featureID = 0;
		$row->name = "-not available-";
		$currentfeatures[0] = $row;
		if ($this->registry->parentfeatureID == 0) {
			
		} else {
			foreach($this->registry->features as $index => $feature) {
				if ($feature->parentID == $this->registry->parentfeatureID) {
					$currentfeatures[$feature->featureID] = $feature;
				}
			}
		}
		$this->registry->currentfeatures = $currentfeatures;
		
		
		if ($this->registry->featureID > 0) {
			
			$linklist = Table::load("worder_wordfeaturelinks","WHERE GrammarID=" . $_SESSION['grammarID']. " AND ValueID=" . $this->registry->featureID);
			$wordlist = array();
			foreach($linklist as $index => $link) {
				$wordlist[$link->wordID] = $link->wordID;
			}
			$words = Table::loadWhereInArray("worder_words", "WordID", $wordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
			$this->registry->words = $words;
		} else {
			
			$words = Table::load("worder_words", "WHERE LanguageID=" . $this->registry->languageID . " AND WordclassID=" . $this->registry->wordclassID . " AND GrammarID=" . $_SESSION['grammarID']);
			$linklist = Table::load("worder_wordfeaturelinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND FeatureID=" . $this->registry->parentfeatureID);

			
			foreach($linklist as $index => $link) {
				$wordID = $link->wordID;
				if (isset($words[$wordID])) {
					//echo "<br>Feature found in word - " . $words[$wordID]->lemma;
					unset($words[$wordID]);
				}
			}
			$this->registry->words = $words;
		}
				
		$this->registry->template->show('worder/words','declensions');
	}
	
	
	
	
	
	
	
	/**
	 * Poistaa sanamuodon wordforms-taulusta
	 * 
	 */
	public function deletewordformAction() {
	
		$languageID = $_GET['lang'];
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
	
		$rowID = $_GET['id'];
		$wordID = $_GET['wordid'];
		echo "<br>wordID - " . $wordID;
	
		$values = array();
		$values['WordID'] = $wordID;
		$values['Grammatical'] = 0;
	
		Table::deleteRow("worder_wordforms", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);
	
		redirecttotal('worder/words/showword&lang=' . $languageID . '&id=' . $wordID,null);
	}
	
	
	
	
	public function checkallformsAction() {
	
		$comments = false;
		$languageID = $_GET['lang'];
		
		$wordID = $_GET['wordid'];
		if ($comments) echo "<br>wordid - " . $wordID;
		if ($comments) echo "<br>language - " . $languageID;

		$word = Table::loadRow("worder_words","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" .$wordID);
		$inflector = Table::loadRow("worder_inflectors", $word->inflectorID);
		$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$formstable = array();
		$foundchecked = array();
		
		if ($word->inflection != "") {
			echo "<br>inflection - " . $word->inflection;
			$forms = $this->getInflections($word, $features);
		
		
			foreach($forms as $index => $form) {
				if ($comments) echo "<br>Form - " . $form->wordform . " - " . implode(":", $form->features);
				$values = array();
				$values['WordID'] = $wordID;
				$values['Wordform'] = $form->wordform;
				$values['Features'] =  implode(":", $form->features);
				$values['Grammatical'] = 1;
				$values['GrammarID'] = $_SESSION['grammarID'];
				$values['LanguageID'] = $languageID;
				$values['Defaultform'] = 1;
				$values['WordclassID'] = $word->wordclassID;
				Table::addRow("worder_wordforms", $values, false);	
			}
		}
		if (!$comments) redirecttotal('worder/words/showword&lang=' . $languageID . '&id=' . $wordID,null);
	}
	
	
	
	
	
	
	public function updatewordAction() {
	
		//echo "<br>Muuta normaaliin getti muotoon";
		//exit();
		
		$wordID = $_GET['id'];
		
		$word = Table::loadRow("worder_words", $wordID);
		
		$languageID = $_GET['languageID'];
		$wordclassID = $_GET['wordclassID'];
		$casemarking = $_GET['casemarking'];
		$lemma = $_GET['lemma'];
		//$phonetic = $_GET['phonetic'];
		$inflection = $_GET['inflection'];
		$inflectorID = $_GET['inflectorID'];
		$inflectionforms = $_GET['inflectionforms'];
		

		
		//$this->registry->language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		
		$success='';
		$columns = array();
		$columns['Lemma'] = $lemma;
		
		if (isset($_GET['weight']))	$columns['Weight'] = $_GET['weight'];
		if (isset($_GET['transcription_latin'])) $columns['Transcription_latin'] = $_GET['transcription_latin'];
		if (isset($_GET['transcription_cyrillic'])) $columns['Transcription_cyrillic'] = $_GET['transcription_cyrillic'];
		if (isset($_GET['phonetic'])) $columns['Phonetic'] = $_GET['phonetic'];
		
		//$columns['Transcription_latin'] = $latin;
		//$columns['Transcription_cyrillic'] = $cyrillic;
		//$columns['Phonetic'] = $phonetic;
		$columns['Inflection'] = $inflection;
		$columns['InflectorID'] = $inflectorID;
		$columns['Inflectionforms'] = $inflectionforms;
		$columns['WordclassID'] = $wordclassID;
		$columns['GrammarID'] = $_SESSION['grammarID'];
		$columns['Casemarking'] = $casemarking;
		$columns['Description'] = $_GET['description'];
		
		//		$success=count($_GET);
		$success = Table::updateRow("worder_words", $columns, $wordID);
		/*
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
		*/
		
		if ($word->wordclassID != $wordclassID) {
			$values = array();
			$values['WordclassID'] = $wordclassID;
			$success = Table::updateRowsWhere("worder_wordforms", $values, " WHERE WordID=" . $word->wordID);
		}
		
		
		if ($word->wordclassID != $wordclassID) {
			$values = array();
			$values['WordclassID'] = $wordclassID;
			$success = Table::updateRowsWhere("worder_wordforms", $values, " WHERE WordID=" . $word->wordID);
		}
		
		
		redirecttotal('worder/words/showword&id=' . $wordID ,null);
	}
	
	
	public function updatewordbaseAction() {
	
		$wordID = $_GET['wordID'];

		$base0 = $_GET['base0'];
		$base1 = $_GET['base1'];
		$base2 = $_GET['base2'];
		$base3 = $_GET['base3'];
		$base4 = $_GET['base4'];
		$base5 = $_GET['base5'];
		$base6 = $_GET['base6'];
		$base7 = $_GET['base7'];
		
		$baseform = $base0 . "/" . $base1 . "/" . $base2 . "/" . $base3 . "/" . $base4 . "/" . $base5 . "/" . $base6 . "/" . $base7;
		$success='';
		$columns = array();
		$columns['Inflectionforms'] = $baseform;
		$success = Table::updateRow("worder_words", $columns, $wordID);
		redirecttotal('worder/words/showword&id=' . $wordID ,null);
	}
	
	
	
	
	
	

	public function updatewordformAction() {
	
		$comments = false;
		$languageID = $_GET['languageID'];
		$rowID = $_GET['id'];
		$wordID = $_GET['wordID'];
		
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$word = Table::loadRow("worder_words","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
		$wordclassfeatures = Table::load("worder_wordclassfeatures", "WHERE  GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $word->wordclassID . " AND LanguageID=" .  $languageID. " AND Inflectional=1", $comments);
		$features = Table::load("worder_features", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ParentID=0 AND LanguageID=" .  $languageID, $comments);
		$featurelist = "";
		if ($comments) echo "<br>WordclassID - " . $word->wordclassID;
			
		$updatevalues = array();
		foreach($wordclassfeatures as $index => $wordclassfeature) {
		
			if ($comments) echo "<br>WordclassfeatureID - " . $wordclassfeature->featureID;
			$feature = $features[$wordclassfeature->featureID];
			if ($comments) echo "<br>Wordclassfeaturename - " . $feature->name;
			
			if (isset($_GET[$feature->name])) {
				$value = $_GET[$feature->name];
				
				if ($comments) echo "<br>--- value found - " . $feature->name . " = " . $value;
				if ($wordclassfeature->defaultvalueID != $feature->featureID) {
					if (($value != "") && ($value != 0)) {
						if ($featurelist != "") $featurelist = $featurelist . ":" . $value;
						else $featurelist = $value;
					} else {
						if ($comments) echo "<br>This is default form - " . $feature->featureID;
					}
				} else {
					if (($value != "") && ($value != 0)) {
						if ($featurelist != "") $featurelist = $featurelist . ":" . $value;
						else $featurelist = $value;
					} else {
						if ($comments) echo "<br>This is default form - " . $feature->featureID;
					}
				}
			} else {
				if ($comments) echo "<br>--- value not found - " . $str;
			}
		}
		if ($comments) echo "<br>Featurelist - " . $featurelist;
		
		$values = array();
		$values['Wordform'] =  $_GET['wordform'];
		$values['Features'] = $featurelist;
		$values['Grammatical'] = $_GET['grammatical'];;
		$success = Table::updateRow("worder_wordforms", $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);
		
		if (!$comments) redirecttotal('worder/words/showword&lang=' . $languageID . '&id=' . $wordID ,null);
	}



	public function pluralcheckAction() {
		
		$comments = false;
		$languageID = $_GET['languageID'];
		$wordID = $_GET['wordID'];
		
		
		$values = array();
		$values['Pluralchecked'] = 1;
		$success = Table::updateRow("worder_words", $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
		
		if (!$comments) redirecttotal('worder/words/showword&lang=' . $languageID . '&id=' . $wordID ,null);
	}
	
	

	public function undefaultwordformAction() {

		$comments = false;
		$languageID = $_GET['languageID'];
		$rowID = $_GET['id'];
		$wordID = $_GET['wordID'];
		
		$form = Table::loadRow("worder_wordforms",$rowID, $comments);
		
		$defaultform = 0;
		if ($form->defaultform == 0) {
			$defaultform = 1;
		}
		
		$values = array();
		$values['Defaultform'] = $defaultform;
		$success = Table::updateRow("worder_wordforms", $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID, $comments);
		
		if (!$comments) redirecttotal('worder/words/showword&lang=' . $languageID . '&id=' . $wordID ,null);
	}
	
	
	


	public function removefeatureAction() {
	
		$comments = false;
		$saveaction = true;
		
		
		$featureID = $_GET['featureID'];
		$valueID = $_GET['valueID'];
		$wordID = $_GET['wordID'];
		
		$word = Table::loadRow("worder_words",$wordID, $comments);
		
		$success = 0;
		if ($saveaction == true) {
			$success = Table::deleteRowsWhere("worder_wordfeaturelinks"," WHERE FeatureID=" . $featureID . " AND WordID=" . $wordID . " AND GrammarID=" . $_SESSION['grammarID'], $comments);
		} else {
			$success = 1;
		}
		if ($success == 0) {
			if ($comments) echo "<br>No feature for remove found.";
			return false;
		}
		
		if ($comments) echo "<br>Poistetaan rivi worder wordfeaturelinks taulusta";
		$deletekey = $featureID . ":" . $valueID . ":" . $wordID;
		if ($comments) echo "<br>Deletekey - " . $deletekey;
		if ($comments) echo "<br>Originalrequirements - " . $word->features;
		
		$updatevalues = array();
		$wordfeaturesarray = explode("|", $word->features);
		$wordfeatures = array();
		foreach ($wordfeaturesarray as $index => $argumentstruct) {
			if ($argumentstruct != $deletekey) {
				if ($argumentstruct != "") $wordfeatures[$argumentstruct] = $argumentstruct;
			}
		}
		if (count($wordfeatures) > 1) {
			$updatevalues['Features'] = implode('|', $wordfeatures);
		} else {
			$updatevalues['Features'] = implode('', $wordfeatures);
		}
		if ($saveaction) Table::updateRow('worder_words', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
		if ($comments) {
			echo "<br>Updating worder_concept<br>";
			print_r($updatevalues);
		}
		
		$childIDs = WordsController::getChildIDs($wordID);
		$childwords = Table::loadWhereInArray('worder_words', 'WordID', $childIDs, "WHERE GrammarID=" . $_SESSION['grammarID']);
		if ($childwords == null) {
			if ($comments)echo "<br><br>Childword count - No childs found";
		} else {
			if ($comments)echo "<br><br>Childword count - " . count($childwords);
			foreach($childwords as $childwordID => $childword) {
				 
				$updatevalues = array();
				if ($comments)echo "<br> - updating child " . $childword->lemma . " (" . $childword->wordID . ")";
				if ($comments)echo "<br> - orginal Child features - " . $childword->features;
		
				// päivitetään lapsen componentit
				$childfeaturesarray = explode("|", $childword->features);
				$childfeatures = array();
				foreach ($childfeaturesarray as $index => $argumentstruct) {
					if ($argumentstruct != $deletekey) {
						if ($argumentstruct != "") $childfeatures[$argumentstruct] = $argumentstruct;
					}
				}
				if (count($childfeatures) > 1) {
					$updatevalues['Features'] = implode('|', $childfeatures);
				} else {
					$updatevalues['Features'] = implode('', $childfeatures);
				}
				if ($comments)echo "<br> - Features = " . $updatevalues['Features'];
		
				if ($saveaction) Table::updateRow('worder_words', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $childwordID);
				if ($comments) {
					echo "<br>updatechildrow wordID - " . $childwordID . "<br>";
					print_r($updatevalues);
				}
			}
		}
		if (!$comments) redirecttotal('worder/words/showword&id=' . $wordID, null);
	}
	
	
	
	public function removeconceptfromwordAction() {
	
		$conceptID = $_GET['id'];
		$languageID = $_GET['lang'];
		$wordID = $_GET['wordID'];
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
	
		$success = Table::deleteRowsWhere("worder_conceptwordlinks"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID . " AND ConceptID=" . $conceptID, true);
	
	
		/*
			$success = Table::deleteRow("worder_words","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $id);
	
			// tässä saattaa olla virhe, languageID on tarpeeton, tsekkaa
			$success = Table::deleteRowsWhere(worder_wordgroupwords," WHERE GrammarID=" . $_SESSIOn['grammarID'] . " AND WordID=" . $id);
	
			$success = Table::deleteRowsWhere("worder_conceptwordlinks"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $id);
		*/
	
		// TODO pitäisi varmaan poistaa muualtakin
	
		//echo "[{\"success\":\"true\"}]";
		//redirecttotal('worder/words/showwords&lang=' . $languageID);
	}
	
	
	function removesentencefromwordAction() {
				
		$wordID = $_GET['wordID'];
		$sentenceID = $_GET['id'];
		$languageID = $_GET['languageID'];
		
		$success = Table::deleteRowsWhere("worder_sentencelinks", "WHERE GrammarID=". $_SESSION['grammarID'] . " AND WordID=" . $wordID . " AND SentenceID=" . $sentenceID, false);
		
		redirecttotal('worder/words/showword&lang=' . $languageID . "&id=" . $wordID);
	}
	
	
	
	public function removewordformAction() {
	
		$rowID = $_GET['id'];
		$languageID = $_GET['languageID'];
		$wordID = $_GET['wordID'];
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
	
		$success = Table::deleteRowsWhere("worder_wordforms"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID, false);
		redirecttotal('worder/words/showword&lang=' . $languageID . "&id=" . $wordID);
	}
	
	
	
	public function removewordAction() {

		$comments = true;
		$wordID = $_GET['wordID'];
		$word = Table::loadRow("worder_words", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID, $comments);

		if ($comments) echo "<br>Word to remove - " . $word->lemma;
		$removepossible = true;
		
		$parents = Table::load("worder_wordparentlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID, $comments);
		if ($parents != null) {
			if (count($parents) > 0) {
				foreach($parents as $index => $parentlink) {
					if ($parentlink->parentID == 0) {
						//echo "<br>Zeroparent";
					} else {
						echo "<br>Contains parents, remove not possible";
						echo "<br> -- parentID - " . $parentlink->parentID;
						$removepossible = false;
					}
				}
			}
		}
		
		$childs = Table::load("worder_wordparentlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ParentID=" . $wordID, $comments);
		if (count($childs) > 0) {
			echo "<br>Contains childs, remove not possible";
			foreach($childs as $index => $link) {
				echo "<br> -- childID - " . $link->wordID;
			}
			$removepossible = false;
		}
		
		$wordfeaturelinks = Table::load("worder_wordfeaturelinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID, $comments);
		if (count($wordfeaturelinks) > 0) {
			echo "<br>Contains wordfeaturelinks, remove not possible";
			foreach($wordfeaturelinks as $index => $link) {
				echo "<br> -- featureID - " . $link->featureID;
			}
			$removepossible = false;
		}
		
		$conceptlinks = Table::load("worder_conceptwordlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID, $comments);
		if (count($conceptlinks) > 0) {
			echo "<br>Contains concepts, remove not possible";
			foreach($conceptlinks as $index => $link) {
				echo "<br> -- conceptID - " . $link->conceptID;
			}
			$removepossible = false;
		}
		

		$sentencelinks = Table::load("worder_sentencelinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID, $comments);
		if (count($sentencelinks) > 0) {
			echo "<br>Contains sentences, remove not possible";
			foreach($sentencelinks as $index => $link) {
				echo "<br> -- sentenceID - " . $link->sentenceID;
			}
			$removepossible = false;
		}
		

		$wordforms = Table::load("worder_wordforms", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID, $comments);
		if (count($wordforms) > 0) {
			echo "<br>Contains wordforms, remove not possible";
			foreach($wordforms as $index => $link) {
				echo "<br> -- Wordform - " . $link->wordform;
			}
			$removepossible = false;
		}
		
		
		if ($removepossible == true) {
			echo "<br><br>Remove is possible.";
			$success = Table::deleteRowsWhere('worder_words', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID, $comments);
			redirecttotal('worder/words/showwords');
		} else {
			echo "<br><br>Remove not possible.";
		}
		
	}
	
	

	
	public function insertsentencetowordAction() {
	
		$comments = false;
		
		$wordID = $_GET['wordID'];
		$conceptID = $_GET['conceptID'];
		$sentenceID = $_GET['sentenceID'];
		$languageID = $_GET['languageID'];
		
		if ($comments) echo "<br>wordID - " . $wordID;
		if ($comments) echo "<br>conceptID - " . $conceptID;
		if ($comments) echo "<br>sentenceID - " . $sentenceID;
		if ($comments) echo "<br>languageID - " . $languageID;
		
		$sentence = Table::loadRow('worder_sentencelinks', "WHERE WordID=" . $wordID . " AND SentenceID=" . $sentenceID);
		if ($sentence != null) {
			// TODO: lisää errormessage
			//echo "<br>Already exists";
			redirecttotal('worder/words/showword&lang=' . $languageID . "&id=" . $wordID);
			return;
		}
		
		$values = array();
		$values['WordID'] = $wordID;
		$values['SentenceID'] = $sentenceID;
		$values['ConceptID'] = $conceptID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['LanguageID'] = $languageID;
		$rowID = Table::addRow("worder_sentencelinks", $values, $comments);
		
		if (!$comments) redirecttotal('worder/words/showword&lang=' . $languageID . "&id=" . $wordID);
	}
	
	

	public function moveconceptAction() {
	
		$conceptID = $_GET['id'];
		$wordID = $_GET['wordID'];
		$languageID = $_GET['languageID'];
		$comments = false;
		$orderby = "";
		if (isset($_GET['dir'])) {
			$direction = $_GET['dir'];
			if ($direction == 'down') $orderby = "DESC";
		}
		$links = Table::load("worder_conceptwordlinks", "WHERE WordID=" . $wordID . " AND GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Sortorder " . $orderby);
	
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
					//$previousID = $objective->rowID;
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
	
	
			$sql = "UPDATE worder_conceptwordlinks SET Sortorder='" . $previous->sortorder . "' WHERE RowID=" . $current->rowID . "";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) echo "<br>Error: " . $mysqli->error;
	
			$sql = "UPDATE worder_conceptwordlinks SET Sortorder='" . $current->sortorder . "' WHERE RowID=" . $previous->rowID  . "";
			if ($comments) echo "<br>sql - " . $sql;
			$result = $mysqli->query($sql);
			if (!$result) echo "<br>Error: " . $mysqli->error;
		}
	
		if (!$comments) redirecttotal('worder/words/showword&lang=' . $languageID . "&id=" . $wordID);
	}
	
	
	
	

	public function setenglishpluralformAction() {
	
		global $mysqli;
		
		$comments = true;
		$wordID = $_GET['wordID'];
		$word = Table::loadRow('worder_words', "WHERE WordID=" . $wordID . " AND GrammarID=" . $_SESSION['grammarID']);
		
		
		$sql = "INSERT INTO worder_wordforms (Wordform, WordID, Features, Grammatical, SystemID, GrammarID, LanguageID, WordclassID, Defaultform) VALUES ('" . $word->lemma . "s',"  . $word->wordID . ", '133:458',1, 5, 1, " . $word->languageID . ", " . $word->wordclassID . ",1)";
		//echo "<br>Sql - " . $sql;
			
		$result = $mysqli->query($sql);
		if (!$result) {
			die("Error 1: " . $mysqli->connect_error);
		}
		
		$sql = "INSERT INTO worder_wordfeaturelinks (WordID, FeatureID, ValueID, InheritancemodeID, SystemID, GrammarID, LanguageID, WordclassID) VALUES (" . $word->wordID . ", 460, 463, 1, 5, 1, " . $word->languageID . ", " . $word->wordclassID . ")";
		//echo "<br>Sql - " . $sql;
			
		$result = $mysqli->query($sql);
		if (!$result) {
			die("Error 1: " . $mysqli->connect_error);
		}
		
		$str = $word->features;
		if ($str == "") {
			$str = "460:463:" . $wordID;
		} else {
			$str = $str . "|460:463:" . $wordID;
		}
		$sql = "UPDATE worder_words SET Features='" . $str . "' WHERE WordID=" . $wordID;
		//echo "<br>Sql - " . $sql;
			
		$result = $mysqli->query($sql);
		if (!$result) {
			die("Error 1: " . $mysqli->connect_error);
		}
		
		
		echo "1";
		
		/*
		$wordID = $_GET['wordID'];
		$conceptID = $_GET['conceptID'];
		$sentenceID = $_GET['sentenceID'];
		$languageID = $_GET['languageID'];
	
		if ($comments) echo "<br>wordID - " . $wordID;
		if ($comments) echo "<br>conceptID - " . $conceptID;
		if ($comments) echo "<br>sentenceID - " . $sentenceID;
		if ($comments) echo "<br>languageID - " . $languageID;
	
		$sentence = Table::loadRow('worder_sentencelinks', "WHERE WordID=" . $wordID . " AND SentenceID=" . $sentenceID);
		if ($sentence != null) {
			// TODO: lisää errormessage
			//echo "<br>Already exists";
			redirecttotal('worder/words/showword&lang=' . $languageID . "&id=" . $wordID);
			return;
		}
	
		$values = array();
		$values['WordID'] = $wordID;
		$values['SentenceID'] = $sentenceID;
		$values['ConceptID'] = $conceptID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['LanguageID'] = $languageID;
		$rowID = Table::addRow("worder_sentencelinks", $values, $comments);
		*/
		if (!$comments) redirecttotal('worder/words/showword&lang=' . $languageID . "&id=" . $wordID);
	}
	
	

	

	public function addfinnislocationtypessaAction() {
	
		global $mysqli;
	
		$comments = true;
		$wordID = $_GET['wordID'];
		$word = Table::loadRow('worder_words', "WHERE WordID=" . $wordID . " AND GrammarID=" . $_SESSION['grammarID']);
		
		$str = $word->features;
		if ($str == "") {
			$str = "477:478:" . $wordID;
		} else {
			$str = $str . "|477:478:" . $wordID;
		}
		$sql = "UPDATE worder_words SET Features='" . $str . "' WHERE WordID=" . $wordID;
		$result = $mysqli->query($sql);
		if (!$result) {
			die("Error 1: " . $mysqli->connect_error);
		}
		
		
		$values = array();
		$values['WordID'] = $wordID;
		$values['FeatureID'] = 477;
		$values['ValueID'] = 478;
		$values['WordclassID'] = 1;
		$values['InheritancemodeID'] = 1;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['LanguageID'] = 1;
		$rowID = Table::addRow("worder_wordfeaturelinks", $values);

		echo "1";
	}
	
	

	public function addfinnislocationtypellaAction() {
	
		global $mysqli;
	
		$comments = true;
		$wordID = $_GET['wordID'];
		$word = Table::loadRow('worder_words', "WHERE WordID=" . $wordID . " AND GrammarID=" . $_SESSION['grammarID']);
	
		$str = $word->features;
		if ($str == "") {
			$str = "477:479:" . $wordID;
		} else {
			$str = $str . "|477:479:" . $wordID;
		}
		$sql = "UPDATE worder_words SET Features='" . $str . "' WHERE WordID=" . $wordID;
		$result = $mysqli->query($sql);
		if (!$result) {
			die("Error 1: " . $mysqli->connect_error);
		}
	
	
		$values = array();
		$values['WordID'] = $wordID;
		$values['FeatureID'] = 477;
		$values['ValueID'] = 479;
		$values['WordclassID'] = 1;
		$values['InheritancemodeID'] = 1;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['LanguageID'] = 1;
		$rowID = Table::addRow("worder_wordfeaturelinks", $values);
	
		echo "1";
	}
	
		

}
