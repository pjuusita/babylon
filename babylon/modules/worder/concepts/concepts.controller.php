<?php

include_once('./modules/worder/_classes/inheritancemodes.class.php');



class ConceptsController extends AbstractController {
	
	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','worder.css','yritys.css','prism.css','chosen.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
		
	
	public function indexAction() {
		//$this->showhierarchyAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	

	public function conceptautocompleteAction() {
		
		if (isset($_GET['prefix'])) {
			$prefix = $_GET['prefix'];
		} else {
			$prefix = "bbb";
		}
		
		$concepts = Table::searchString('worder_concepts','Name',"[2]".$prefix, 6, "ConceptID");
		
		$words = array();		
		foreach($concepts as $index => $value) {
			//echo "<br>"  . $value;
			$words[$index] = substr($value, 3);
		}
		
		/*
		$words = array();		
		for($index = 0;$index < 10;$index++) {
			$word = $prefix . rand(1000,9999);
			echo "<br>"  . $word;
			$words[] = $word;
		}
		*/
		echo json_encode($words);
	}
		
	
	
	public function autocompleteAction() {
		$this->registry->template->show('worder/concepts','autocomplete');
	}
	
	
	
	public function showconceptsAction() {
		
		updateActionPath("Conceptlist");
		
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->wordclassID = getSessionVar('wordclassID', 0);
		$this->registry->languageID = getSessionVar('languageID', 0);

		if ($this->registry->wordclassID > 0) {
			$concepts = Table::load("worder_concepts", "WHERE WordclassID=" . $this->registry->wordclassID . " AND GrammarID=" . $_SESSION['grammarID']);
		} else {
			$concepts = array();
		}
		
		foreach($concepts as $index => $concept) {
			$concept->word = "<div style=\"color:red\">Unknown</div>";;
		}
		
		$foundconcepts = array();
		
		if ($this->registry->languageID > 0) {
			$words = Table::load("worder_words", "WHERE WordclassID=" . $this->registry->wordclassID . " AND GrammarID=" . $_SESSION['grammarID']);
			
			$links = Table::load("worder_conceptwordlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
			$lessonconcepts = Table::load("worder_lessonconcepts", "WHERE GrammarID=" . $_SESSION['grammarID']);
			foreach($lessonconcepts as $index => $link) {
				$conceptID = $link->conceptID;
				if (isset($concepts[$conceptID])) {
					$concept = $concepts[$conceptID];
					$concept->lessonID = $link->lessonID;
					$concepts[$conceptID] = $concept;
				}
			}
				
			$links = Table::load("worder_conceptwordlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
			
			foreach($links as $index => $link) {
				if (isset($words[$link->wordID])) {
					if (isset($concepts[$link->conceptID])) {
						$word = $words[$link->wordID];
						$concept = $concepts[$link->conceptID];
						$concept->word = $word->lemma;
						$foundconcepts[$link->conceptID] = 1;
					}
				}
			}
			
			
				
			
		}
		$this->registry->concepts = $concepts;

		$this->registry->found = count($foundconcepts);
		$this->registry->fullcount = count($this->registry->concepts);
		
		$this->registry->template->show('worder/concepts','conceptlist');
	}

	
	

	public function showconceptsoldAction() {
	
		// TODO: errormessaget ei näy tässä, esim. jos yritettään näyttää conceptiID:tä joka ei ole olemassa redirectoriddn tänne
		//       errormessaget pitäisi tulla frameworkistä...
	
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		//$this->registry->wordgroups = Table::load("worder_wordgroups", "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		$this->registry->currentpage = getSessionVar('page', 1);
		$this->registry->rowsperpage = getSessionVar('rowsperpage', 100);
	
		$this->registry->rowsperpage = 200;
	
		if (isset($_GET['rowsperpage'])) {
			$this->registry->currentpage = 1;
			setSessionVar('page', 1);
		}
	
		$this->registry->defaultlanguageID = getSessionVar('defaultlanguage', 1);
	
		$this->registry->wordclassID = getSessionVar('wordclassID', 0);
		$this->registry->wordgroupID = getSessionVar('wordgroupID', 0);
		$this->registry->search = getSessionVar('search', '');
		$this->registry->rarities = Table::load("worder_rarities");
	
	
		if ($this->registry->search != '') {
			if ($this->registry->search != "") {
				$this->registry->concepts = Table::loadWithPaging("worder_concepts",$this->registry->currentpage, $this->registry->rowsperpage, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND name LIKE '%" . $this->registry->search . "%' OR Gloss LIKE  '%" . $this->registry->search . "%'", false);
				$this->registry->totalrows = Table::countTableRows("worder_concepts", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND (name LIKE '%" . $this->registry->search . "%' OR Gloss LIKE  '%" . $this->registry->search . "%')");
				$this->registry->template->show('worder/concepts','conceptlist');
				return;
			} else {
				$this->registry->concepts = array();
				$this->registry->template->show('worder/concepts','conceptlist');
				return;
			}
		}
	
			
		if ($this->registry->wordclassID > 0) {
			//echo "<br>Classid";
			$this->registry->concepts = Table::loadWithPaging("worder_concepts",$this->registry->currentpage, $this->registry->rowsperpage, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND Selected=1 AND WordclassID=" . $this->registry->wordclassID);
			$this->registry->totalrows = Table::countRows("worder_concepts","ConceptID", " WHERE GrammarID=" . $_SESSION['grammarID'] . " AND Selected=1 AND WordclassID=" . $this->registry->wordclassID);
			//echo "<br>Totalrows - " . $this->registry->totalrows;
			$this->registry->template->show('worder/concepts','conceptlist');
			return;
		}
	
	
		$this->registry->totalrows = 100;
		//$this->registry->totalrows = Table::countRows("worder_concepts", "WHERE GrammarID=" . $_SESSION['grammarID']);
		//echo "<br>Totalrows - " . $this->registry->totalrows;
		$this->registry->concepts  = Table::load("worder_concepts", "WHERE GrammarID=" . $_SESSION['grammarID']);
		//$this->registry->concepts = Table::loadWithPaging("worder_concepts",$this->registry->currentpage, $this->registry->rowsperpage, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND name LIKE '%" . $this->registry->search . "%' OR Gloss LIKE  '%" . $this->registry->search . "%'", false);
	
		//$this->registry->concepts = Table::loadWithPaging("worder_concepts",$this->registry->currentpage, $this->registry->rowsperpage, " WHERE GrammarID=" . $_SESSION['grammarID'] . " AND Selected=1 ORDER BY Frequency DESC");
		$this->registry->template->show('worder/concepts','conceptlist');
	}
	
	

	public function showhierarchyAction() {
	
		updateActionPath("Concepts");
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$conceptlinks = Table::load("worder_conceptparentlinks", "WHERE GrammarID=" . $_SESSION['grammarID']);

		$conceptIDs = array();
		$rootIDs = array();
		
		foreach($conceptlinks as $index => $conceptlink) {
			if ($conceptlink->parentID == 0)  {
				$rootIDs[$conceptlink->conceptID] = $conceptlink->conceptID;
			} else {
				$conceptIDs[$conceptlink->parentID] = $conceptlink->parentID;
			}
			$conceptIDs[$conceptlink->conceptID] = $conceptlink->conceptID;
		}

		$concepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $conceptIDs, "WHERE GrammarID=" . $_SESSION['grammarID']);
			
		$hierarchy = array();
		foreach($rootIDs as $conceptID => $value) {
			$hierarchy[] = $concepts[$conceptID];
		}
		foreach($conceptlinks as $index => $conceptlink) {
			if ($conceptlink->parentID != 0) {
				$concepts[$conceptlink->parentID]->addChild($concepts[$conceptlink->conceptID]);
			}
		}
		
		$this->registry->hierarchy = $hierarchy;
		$this->registry->template->show('worder/concepts','hierarchy');
	}
	
	
	
	public function showconceptAction() {
	
		
		$conceptID = $_GET['id'];
	
		$this->registry->concept = Table::loadRow("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		updateActionPath($this->registry->concept->name);
		if ($this->registry->concept == null) {
			addErrorMessage("Concept not found");
			// TODO: Virheilmoitus pitäisi tallentaa johonkin addErrorMessage
			redirecttotal('worder/concepts/showconcepts', null);
			exit();
		}
		$this->registry->defaultlanguageID = getSessionVar('defaultlanguage', 1);
		
	
	
		//$this->registry->systemlangs = Table::load("system_languages", "WHERE Active=1");
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND Active=1");
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		//$this->registry->rarities = Table::load("worder_rarities");
		$this->registry->inheritancemodes = InheritanceModes::getInheritanceModes();
		$this->registry->components = Table::load('worder_components',"WHERE GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Name");
		
		// Tämän rajaaminen tuotti ongelmia luokittelu-käsitteen kanssa, sieltä pitää kuitenkin periä
		//$this->registry->arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $this->registry->concept->wordclassID);
				
		//$this->registry->arguments = Table::load("worder_arguments", "WHERE WordclassID=" . $this->registry->concept->wordclassID . " AND GrammarID=" . $_SESSION['grammarID']);
		$this->registry->arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		
		$this->registry->heredities = Array('0' => 'Non-heritable', '1' => 'Inclusive heritable', '2' => 'Non-inlcusive heritable', '3' => 'Inherited');
		$this->registry->connectivity = Array('0' => 'AND', '1' => 'OR');
		//$this->registry->wordgroups = Table::load('worder_wordgroups');
		//$this->registry->groupIDs = Table::load("worder_wordgroupconcepts"," WHERE ConceptID='" . $conceptID . "'");
		//$this->registry->allgrouplinks = Table::load('worder_wordgrouplinks');
	
		$parentsIDs = array();
		$parents = array();

		/*
		$groups = array(); 
		if ($this->registry->groupIDs != null) {
			foreach($this->registry->groupIDs as $index => $grouplink) {
				//echo "<br>grouppi - " . $grouplink->wordgroupID;
				//$this->getGroupsRecursively($this->registry->allgrouplinks, $conceptgroup->wordgroupID, $parentsIDs);
				$groups[] = $this->registry->wordgroups[$grouplink->wordgroupID];
			}
		}
		$this->registry->groups = $groups;
		*/
		//echo "<br>Parentscount - " . count($parentsIDs);
	
	
		// Ladataan hierarkia
		$hierarchy = array();
		$parentlines = explode('|',$this->registry->concept->parentpaths);
		//echo "<br>parentstr - " . $this->registry->concept->parents;
		//echo "<br>";
		/*
		$parentconceptsIDs = array();
	
		foreach($parentlines as $index => $parentline) {
			$parentIDs = explode(':', $parentline);
			foreach($parentIDs as $index2 => $parentID) {
				if ($parentID != "") {
					if ($parentID != '0') {
						$parentconceptsIDs[$parentID] = $parentID;
					}
				}
			}
		}
		*/
		//echo "<br>ParentconceptIDs - ";
		//print_r($parentconceptsIDs);
		
		$allparents = explode(":", $this->registry->concept->allparents);
		$conceptlist = array();
		foreach($allparents as $index => $parentID) {
			if (($parentID != null) && ($parentID != "") && ($parentID != "0")) {
				$conceptlist[$parentID] = $parentID;				
			}
		}
		
		
		// Directparents-listaa käytetään sen määrittämiseen voidaanko poistaa
		$directparentlist = explode(":", $this->registry->concept->parents);
		$directparents = array();
		foreach($directparentlist as $index => $parentID) {
			$directparents[$parentID] = $parentID;
		}
		
		$allparentstemp = array();
		$allparentstemp[$this->registry->concept->conceptID] = "(this)";
		
		if (count($conceptlist) == 0) {
			$hierarchy[] = $this->registry->concept;
			$this->registry->hierarchy = $hierarchy;
					
		} else {
			$parents = Table::loadWhereInArray('worder_concepts', 'ConceptID', $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
			foreach ($parents as $index => $parent) {
				$allparentstemp[$parent->conceptID] = $parent->name;
			}
			foreach($parentlines as $index => $parentline) {
				if ($parentline != "") {
					//echo "<br>Prosessing parentline - " . $parentline;
					$parentsstrs = explode(':', $parentline);
					$first = true;
					$lastparent = null;
					//echo "<br>Parentstr - " . $parentline;
					foreach($parentsstrs as $index2 => $parentID) {
						//echo "<br>index - " . $index2 . " --- " . $parentID;
						if ($parentID != "") {
							if ($first == true) {
								$officialparent = $parents[$parentID];
								$copyparent = new Row();
								$copyparent->name = $officialparent->name;
								$copyparent->conceptID = $officialparent->conceptID;
								$lastparent = $copyparent;
								$hierarchy[] = $lastparent;
								$first = false;
								if (isset($directparents[$lastparent->conceptID])) {
									//echo "<br>Directparent - " . $lastparent->conceptID;
									$lastparent->removepossible = 1;
								}
							} else {
								$officialparent = $parents[$parentID];
								$copyparent = new Row();
								$copyparent->name = $officialparent->name;
								$copyparent->conceptID = $officialparent->conceptID;
								$lastparent->addChild($copyparent);
								$lastparent = $copyparent;
								if (isset($directparents[$lastparent->conceptID])) {
									//echo "<br>Directparent - " . $lastparent->conceptID;
									$lastparent->removepossible = 1;
								}
							}
						} else {
							//echo "<br>Indexsinull";
						}
					}
					if ($this->registry->concept != null) $lastparent->addChild($this->registry->concept);
				}
				
			}
			$this->registry->hierarchy = $hierarchy;
		}
		$this->registry->allparents = $allparentstemp;
		
		
		$this->registry->inheritancemodes = InheritanceModes::getInheritanceModes();
		$owncomponentstemp = Table::load("worder_conceptcomponentlinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID );
		$compresultlist = array();
		foreach($owncomponentstemp as $index => $link) {
			
			$arri = array();
			$component = $this->registry->components[$link->componentID];
			$arri[0] = "" . $component->name . " (" . $link->componentID . ")";
			if ($link->fromconceptID == $conceptID) {
				//$componentlink = $owncomponents[$componentitems[0]];
				$mode = $this->registry->inheritancemodes[$link->inheritancemodeID];
				$arri[1] = "" . $mode->name;
				$arri[2] = "<font style='font-weight:bold;font-style:italic'>This</font>";
				$arri[3] = $link->componentID;
				$arri[4] = $link->conceptID;
				$arri[5] = 1;
			} else {
				$arri[1] = "<font style='color:grey;font-style:italic'>(inherited)</font>";
				$sourceconcept = $parents[$link->fromconceptID];
				$arri[2] = parseMultilangString($sourceconcept->name,2);
				$arri[3] = $link->componentID;
				$arri[4] = $link->conceptID;
				$arri[5] = 0;
			}
			$compresultlist[] = $arri;
		}
		$this->registry->conceptcomponents = $compresultlist;
		
		
		// haetaan argumentit
		$conceptarguments = array();
		if ($this->registry->concept->arguments != "") {
			$ownarguments = Table::load('worder_conceptargumentlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID='" . $conceptID . "'");
			$conceptargumentIDs = explode("|", $this->registry->concept->arguments);
			
			//echo "<br>Argumentlinks - " . $this->registry->concept->arguments;
				
			if ($conceptargumentIDs != null) {
				foreach($conceptargumentIDs as $index => $argumentline) {
					if ($argumentline != "") {
						$requirementitems = explode(":", $argumentline);
						
						$argumentID = $requirementitems[0];
						$componentID = $requirementitems[1];
						$sourceconceptID = $requirementitems[2];
						//echo "<br>ArgumentID - " . $argumentID . ", ComponentID - " . $componentID . ", ConceptID - " . $sourceconceptID;
							
						if ($sourceconceptID != $conceptID) {
							$arri = array();
							$argument = $this->registry->arguments[$argumentID];
							if ($argument == null) echo "<br>Argumentti on nulli";
							$arri[0] = "" . $argument->name . " (" . $argumentID . ")";
							$component = $this->registry->components[$componentID];
							$arri[1] = "" . $component->name . " (" . $componentID . ")";
							$sourceconcept = $parents[$sourceconceptID];
							$arri[2] = parseMultilangString($sourceconcept->name,2);
							$arri[3] = "<font style='color:grey;font-style:italic'>(inherited)</font>";
						
							$arri[4] = $argumentID;
							$arri[5] = $componentID;
							$arri[6] = 0;
							$wordclass = $this->registry->wordclasses[$argument->wordclassID];
							$arri[7] = $wordclass->name;
							
							$conceptarguments[] = $arri;
						}
					}
				}
				if ($ownarguments != null) {
					foreach($ownarguments as $index => $componentlink) {
							
						$arri = array();
							
						$argument = $this->registry->arguments[$componentlink->argumentID];
						$arri[0] = "" . $argument->name . " (" . $componentlink->argumentID . ")";
							
						$component = $this->registry->components[$componentlink->componentID];
						$arri[1] = "" . $component->name . " (" . $componentlink->componentID . ")";
							
						$arri[2] = "<font style='font-weight:bold;font-style:italic'>This</font>";
							
						$mode = $this->registry->inheritancemodes[$componentlink->inheritancemodeID];
						$arri[3] = "" . $mode->name;
						
						$arri[4] = $componentlink->argumentID;
						$arri[5] = $componentlink->componentID;
						$arri[6] = 1;
						$wordclass = $this->registry->wordclasses[$argument->wordclassID];
						$arri[7] = $wordclass->name;
						
						$conceptarguments[] = $arri;
					}
				}
			}
		}
		$this->registry->conceptarguments = $conceptarguments;
	

		$words = array();
		foreach ($this->registry->languages as $index => $language) {
				
			if ($language->active == 1) {
				$loadedwords = Table::loadNoID("worder_conceptwordlinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID . " AND LanguageID=" . $language->languageID);
				
				if ($loadedwords == null) {
					//echo "<br>Language "  . $language->name . " table not found.";
				} elseif (count($loadedwords) == 0) {
					//echo "<br>Language "  . $language->name . " words not found.";
				} else {
					foreach($loadedwords as $wordID => $row) {
	
						$word = Table::loadRow('worder_words', $row->wordID);
						$wordclass = $this->registry->wordclasses[$word->wordclassID];
						if ($word == null) echo "<br>no word found - worder_words - " . $row->wordID;
						$data = array();
						$data[] = $language->name;
						$data[] = $word->lemma;
						$data[] = '' . $word->wordID . '-' . $language->languageID;
						$data[] = $word->wordID;
						$data[] = $language->languageID;
						$data[] = $wordclass->name;
						if ($row->defaultword == 1) {
							$data[] = "x";
						} else {
							$data[] = " ";
						}
						$words[] = $data;
					}
				}
			}
		}	
		$this->registry->words = $words;
			
		$allsentences = array();
		$sentencelinks = Table::load('worder_sentencelinks', " WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
			
		if (count($sentencelinks) > 0) {
			$sentenceIDs = array();
			foreach($sentencelinks as $linkindex => $link) {
				$sentenceIDs[$link->sentenceID] = $link->sentenceID;
			}
			$tempsentences = Table::loadWhereInArray('worder_sentences', "sentenceID", $sentenceIDs);
			foreach($tempsentences as $index => $sentence) {
				$allsentences[] = $sentence;
			}
		}
		$this->registry->sentences = $allsentences;

		$lessonlinks = Table::load('worder_lessonconcepts', " WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		$templessons = array();
		if (count($lessonlinks) > 0) {
			$lessonIDs = array();
			foreach($lessonlinks as $linkindex => $link) {
				$lessonIDs[$link->lessonID] = $link->lessonID;
			}
			$templessons = Table::loadWhereInArray('worder_lessons', "lessonID", $lessonIDs);
		}
		$this->registry->lessons = $templessons;
		
		$this->registry->descriptions = Table::load('worder_conceptdescriptions', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID='" . $conceptID . "'");
		$this->registry->comments = Table::load('worder_conceptcomments', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID='" . $conceptID . "'");

		$this->registry->template->show('worder/concepts','concept');
	
	}
	
	
	
	
	public function insertsentencetoconceptAction() {

		$conceptID =  $_GET['conceptID'];
		$sentenceID =  $_GET['sentenceID'];
		
		$sentence = Table::load("worder_sentences","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SentenceID=" . $sentenceID);
		
		$values = array();
		$values['SentenceID'] = $sentenceID;
		$values['ConceptID'] = $conceptID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['LanguageID'] = $sentence->languageID;
		$values['WordID'] = 0;
		$sentenceID = Table::addRow('worder_sentencelinks', $values, false);
		
		redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}
 	


 	public function searchconceptsJSONAction() {
 	
 		$search = $_GET['search'];
 		$concepts = Table::load("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND name LIKE '%" . $search . "%' ORDER BY Frequency DESC");
 	
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
 	
 			echo " {";
 			echo "	  \"conceptID\":\"" . $concept->conceptID . "\",";
 			echo "	  \"name\":\"" . $concept->name . "\",";
 			echo "	  \"gloss\":\"" . $concept->gloss . "\",";
 			echo "	  \"wordclassID\":\"" . $wordclass . "\",";
 			echo "	  \"frequency\":\"" . $concept->frequency. "\"";
 			echo " }\n";
 		}
 		echo "]";
 		//echo "[  { \"conceptID\":\"12112\", \"name\":\"bbbb\" }, { \"conceptID\":\"1233112\" , \"name\":\"bbbaab\" }  ]";
 	}
 	

 	public function getconceptswithcomponentJSONAction() {

 		$comments = false;
 		$componentID = $_GET['componentID'];
 		$concepts = Table::load("worder_conceptcomponentlinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ComponentID=" . $componentID, $comments);
		$wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
 		
 		$allchilds = array();
 		foreach($concepts as $rowID => $concept) {
 			if ($comments) echo "<br>Concept with component - " . $concept->conceptID;
 			$childIDs = ConceptsController::getChildIDs($concept->conceptID);
 			foreach($childIDs as $childID => $value) {
 				$allchilds[$childID] = $childID;
 				if ($comments) echo "<br>- Child concept - "  .$childID;
 			}
 		}

 		foreach($concepts as $rowID => $concept) {
 			$allchilds[$concept->conceptID] = $concept->conceptID;
 		}
 			
 		$childconcepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $allchilds, "WHERE GrammarID=" . $_SESSION['grammarID']);
 		
 		
 		echo "[";
 		$first = true;
 		foreach($childconcepts as $index => $concept) {
 			//echo "<br>" . $concept->conceptID . " - " . $concept->name . " - " . $concept->frequency;
 	
 			if ($concept->wordclassID != 25) {
 				if ($first == true) $first = false; else echo ",";
 				//$wordclassID = $concept->wordclassID;
 				$wordclass = $wordclasses[$concept->wordclassID];
 				/*
 				if ($concept->wordclassID == 1) $wordclass = "N";
 				if ($concept->wordclassID == 2) $wordclass = "V";
 				if ($concept->wordclassID == 3) $wordclass = "A";
 				if ($concept->wordclassID == 10) $wordclass = "AS";
 				if ($concept->wordclassID == 4) $wordclass = "AD";
 				if ($concept->wordclassID == 5) $wordclass = "PRON";
 				*/
 				
 				echo " {";
 				echo "	  \"conceptID\":\"" . $concept->conceptID . "\",";
 				echo "	  \"name\":\"" . $concept->name . "\",";
 				echo "	  \"gloss\":\"" . $concept->gloss . "\",";
 				echo "	  \"wordclassID\":\"" . $wordclass->name . "\",";
 				echo "	  \"frequency\":\"" . $concept->frequency. "\"";
 				echo " }\n";
 			}
 		}
 		echo "]";
 		//echo "[  { \"conceptID\":\"12112\", \"name\":\"bbbb\" }, { \"conceptID\":\"1233112\" , \"name\":\"bbbaab\" }  ]";
 	}
 	
	
	public function getComponentsRecursively($concept, $concepts, &$components) {

		echo "<br>recursively class - " . get_class($concept);
		echo "<br>recursively - " . $concept->name;
		if ($concept->heredity > 0) {
			//$components[] = $concept;
			
			
		}
		
		$conceptcomponents = Table::load("worder_conceptcomponentlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $concept->conceptID);
		foreach($conceptcomponents as $index => $component) {
			echo "<br>targetconceptID - " . $component->targetconceptID;
			if (isset($concepts[$concept->targetconceptID])) {
				echo "<br>not setted";				
			}
			foreach($concepts as $index => $value) {
				//echo "<br>" . $index . " - " . $value->name;
			}
			$target = $concepts[$component->targetconceptID];
			$components[] = $component;
		}
		
		if ($concept->parentID > 0) {
			$parentconcept = $concepts[$concept->parentID];
			//echo "<br>Parent - " . $parentconcept->name;
			$this->getComponentsRecursively($parentconcept, $concepts, $components);
		}
		return;
	}
	
	
	
	public function getexampleconceptstructureJSONAction() {
	
		$comments = true;
		$conceptID = $_GET['conceptID'];
		
		if ($comments) echo "<br>ConceptID - " . $conceptID;
		
		// Haetaan argumentit...
		$concept = Table::load("worder_concepts", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		$arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$argumentlinks = Table::load('worder_conceptargumentlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID='" . $conceptID . "'");
		$rolearray = array();
		foreach($argumentlinks as $index => $link) {
			$argumentID = $link->argumentID;
			$argument = $arguments[$argumentID];
			
			$componentID = $link->componentID;
			$component = $components[$componentID];
					
			echo "<br>Argument found - " . $argument->name . "(" . $argumentID . ")" . " - " . $component->name . "(" . $componentID . ")";
			
			
			$componentlinks = Table::load('worder_conceptcomponentlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ComponentID='" . $componentID . "'");
			$conceptlist = array();
			foreach($componentlinks as $index => $value) {
				//echo "<br> index - " . $index . " - " . $value->conceptID;
				$conceptlist[$value->conceptID] = $value->conceptID;
			}
			$concepts = Table::loadWhereInArray("worder_concepts", 'ConceptID', $conceptlist);
			foreach($concepts as $index => $concept) {
				if ($concept->wordclassID == $argument->wordclassvalueID) {
					echo "<br>--- " . $argument->name . " - " . $concept->name;
					if (!isset($rolearray[$argumentID])) $rolearray[$argumentID] = array();
					$rolearray[$argumentID][] = $concept;
				} else {
					//echo "<br>xxxx " . $concept->name;
				}
			}
		}
		
		echo "<br><br>";
		foreach($rolearray as $argumentID => $conceptarray) {
			$argument = $arguments[$argumentID];
			echo "<br>Argument: " . $argument->name . " - " . count($conceptarray);
		}
		
		
		$fs = new FeatureStructure($concept->name, $concept->wordclassID);
		for($counter = 0;$counter < 10; $counter++) {
			echo "<br>" . ($counter+1) . ". ";
			foreach($rolearray as $argumentID => $conceptarray) {
				$argument = $arguments[$argumentID];
				echo "<br>Rand - 0-" . count($conceptarray);
				echo "<br>Argument: " . $argument->name . " = ";
				$index = rand(0,count($conceptarray)-1);
				$tempconcept = $conceptarray[$index];
				echo "" . $argument->name . " - " . $tempconcept->name . " (" . $tempconcept->conceptID . ")";
				
				$tempFS = new FeatureStructure($tempconcept->name, $tempconcept->wordclassID);
			}
		}
	}
	
	
	

	public function getexamplesentencestructureJSONAction() {
	
		$comments = true;
		$conceptID = $_GET['conceptID'];
	
		if ($comments) echo "<br>ConceptID - " . $conceptID;
	
		// Haetaan argumentit...
		$arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$components = Table::load("worder_components", "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		$argumentlinks = Table::load('worder_conceptargumentlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID='" . $conceptID . "'");
		$rolearray = array();
		foreach($argumentlinks as $index => $link) {
			$argumentID = $link->argumentID;
			$argument = $arguments[$argumentID];
				
			$componentID = $link->componentID;
			$component = $components[$componentID];
				
			echo "<br>Argument found - " . $argument->name . "(" . $argumentID . ")" . " - " . $component->name . "(" . $componentID . ")";
				
				
			$componentlinks = Table::load('worder_conceptcomponentlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ComponentID='" . $componentID . "'");
			$conceptlist = array();
			foreach($componentlinks as $index => $value) {
				//echo "<br> index - " . $index . " - " . $value->conceptID;
				$conceptlist[$value->conceptID] = $value->conceptID;
			}
			$concepts = Table::loadWhereInArray("worder_concepts", 'ConceptID', $conceptlist);
			foreach($concepts as $index => $concept) {
				if ($concept->wordclassID == $argument->wordclassvalueID) {
					echo "<br>--- " . $argument->name . " - " . $concept->name;
					if (!isset($rolearray[$argumentID])) $rolearray[$argumentID] = array();
					$rolearray[$argumentID][] = $concept;
				} else {
					//echo "<br>xxxx " . $concept->name;
				}
			}
		}
	
		echo "<br><br>";
		foreach($rolearray as $argumentID => $conceptarray) {
			$argument = $arguments[$argumentID];
			echo "<br>Argument: " . $argument->name . " - " . count($conceptarray);
		}
	
		
		echo "<br>" . ($counter+1) . ". ";
		foreach($rolearray as $argumentID => $conceptarray) {
			$argument = $arguments[$argumentID];
			echo "<br>Argument: " . $argument->name . " = ";
			$index = rand(0,count($conceptarray)-1);
			$conc = $conceptarray[$index];
			echo "" . $argument->name . " - " . $conc->name . " (" . $conc->conceptID . ")";
		}
	}
	
	
	public function getParentsRecursively($concept, &$parentcomponents) {
	
		if ($concept->parentID > 0) {
			$parent = Table::loadRow("worder_parents",$concept->parentID, true);
			$parentcomponents[$concept->parentID] = $parent;
			$this->getParentsRecursively($parent, $parentcomponents);
		}
		return;
	}
	
	

	private function getGroupsRecursively($allgrouplinks, $currentgroupID, &$parents) {
	
		foreach($allgrouplinks as $index => $link) {
				
			if ($link->wordgroupID == $currentgroupID) {
				$parents[$currentgroupID] = $currentgroupID;
				if ($link->parentgroupID != 0) {
					$this->getGroupsRecursively($allgrouplinks, $link->parentgroupID, $parents);
				}
			}
		}
		return;
	}
	
	
	
	public function updateconceptAction() {
	
		$conceptID = $_GET['id'];
		
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['WordclassID'] = $_GET['wordclassID'];
		$values['Gloss'] = $_GET['gloss'];
		
		$success = Table::updateRow('worder_concepts', $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);

		redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}
		
	
	/*
	public function isSetToDefaultAction() {
		
		$wordID = $_GET['wordID'];
		$languageID = $_GET['languageID'];
		
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $_GET['languageID']);
		$word = Table::loadRow("worder_words", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
		
		if ($word->selected == 1) {
			echo "1";
			return 1;
		} else {
			echo "0";
			return 0;
		}
	}
	*/
	

	// tämä funktio ylikirjoittaa aiemman sanan
	// vanha sana unlinkataan
	// mikäli uusi sana on linkattu jo, linkataan se silti myäs tähän (oletetaan, että tarkistus on kysytty käyttäjältä jo aiemmin)
	public function setdefaultwordAction() {
	
		$comments = false;
		if ($comments) echo "<br>setdefaultwordAction";
		
		$conceptID = $_GET['conceptID'];
		$wordID = $_GET['wordID'];
		$languageID = $_GET['languageID'];
		
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		
		$concept = Table::loadRow('worder_concepts',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		
		$word = Table::loadRow("worder_words", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID);
		
		
		if ($concept->finnish_wordID != 0) {
			
			if ($concept->finnish_wordID == $wordID) {
				if ($comments) echo "<br>sama sana on jo linkitetty";
				if (!$comments)	redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
				exit;
			}
			
			if ($comments) echo "<br>Aiemp läytyi";
			
			$oldwords = Table::load('worder_concepts', " WHERE finnish_wordID=?", false, $comments);
			
			$othersfound = false;
			foreach($oldwords as $index=> $foundconcept) {
				if ($comments) echo "<br>Vanha läytyi - " . $index . " - " . $foundconcept->conceptID;
				
				if ($foundconcept->conceptID != $conceptID) {
					if ($comments) echo "<br>otherfound - " . $conceptID . " - " . $foundconcept->conceptID;
					$othersfound = true;
				}
			
			}

			if ($othersfound == true) {
				// muitakin viitteitä ei läytynyt, jätetään wordin selected falseksi
				if ($comments) echo "<br>others true";
			} else {
				// muitakin viitteitä läytyi, ei tarvitse päivittää wordin selected arvoa
				
				/*
				// ei mielestäni tueta tätä enää
				$columns = array();
				$columns['Selected'] = 0;
				$success = Table::updateRow("worder_words", $columns, "finnish_wordID", "WHERE GrammarID=" . $_SESSION['grammarID'], $comments);
				if ($comments) echo "<br>others false<br>";
				*/
			}
		} else {
			if ($comments) echo "<br>column nolla, ei aiempaa sanaa asetettu tälle käsitteelle";
		}
		
		
		$columns = array();
		$columns["finnish_word"] = $word->lemma;
		$columns["finnish_wordID"] = $wordID;
		if ($comments) print_r($columns);
		$success = Table::updateRow('worder_concepts', $columns, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID, $comments);
		
		$columns = array();
		$columns['Selected'] = 1;
		$success = Table::updateRow("worder_words", $columns, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID, $comments);
		
		if (!$comments)	redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}	
		
	
	/*
	public function removedefaultwordAction() {
	
		$comments = false;
		
		if ($comments) echo "<br>Jeejee";

		$conceptID = $_GET['conceptID'];
		$columns = array();
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $_GET['lang']);
		$concept = Table::loadRow('worder_concepts',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		
		$wordID = $concept->finnish_wordID;
		
		if ($comments) echo "<br>wordID - " . $wordID;
		if ($comments) echo "<br>";
		
		$columns["finnish_wordID"] = 0;
		$columns["finnish_word"] = "";
	
		if ($comments) print_r($columns);
		$success = Table::updateRow('worder_concepts', $columns, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID, $comments);
	
		
		$columns = array();
		$columns['Selected'] = 0;
		$success = Table::updateRow("worder_words", $columns, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID, $comments);
		
		
		/*
		 if ($success === true) {
		 echo "[{\"success\":\"true\"}]";
		 } else {
		 echo "[{\"success\":\"".$success."\"}]";
		 }
		* /
	
		redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	
	}
	*/
	
	
	

	public function insertcommentAction() {
	
		$conceptID =  $_GET['conceptID'];
		$comment =  $_GET['comment'];
	
		$comment = str_replace('\'','',$comment);
	
		$values = array();
		$values['Comment'] = $comment;
		$values['ConceptID'] = $conceptID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		
		$commentID = Table::addRow("worder_conceptcomments", $values, false);
		
		redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}
	



	public function insertdescriptionAction() {
	
		$conceptID =  $_GET['conceptID'];
		$lang =  $_GET['languageID'];
		$description =  $_GET['description'];
		$url =  $_GET['sourceurl'];
		
		$description = str_replace('\'','',$description);
	
	
		$values = array();
		$values['LanguageID'] = $lang;
		$values['Description'] = $description;
		$values['Sourceurl'] = $url;
		$values['GrammarID'] = $_SESSION['grammarID'];
		setSessionVar('defaultlanguage', $lang);
		
		$success = Table::addRow("worder_conceptdescriptions", $values, false);
	
		redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}
	
	

	public function insertexamplesentenceAction() {
	
		$conceptID =  $_GET['conceptID'];
		$languageID =  $_GET['languageID'];
		$sentence =  $_GET['sentence'];
		
		if (!isset($_GET['conceptID'])) {
			echo "<br>conceptID ei ole asetettu";
			exit;
		}
		
		$values = array();
		$values['Sentence'] = $_GET['sentence'];
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['LanguageID'] = $languageID;
		$values['Correctness'] = 1;
		$sentenceID = Table::addRow('worder_sentences', $values, false);
		
		
		$values = array();
		$values['SentenceID'] = $sentenceID;
		$values['WordID'] = 0;
		$values['ConceptID'] = $conceptID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['LanguageID'] = $languageID;
		$sentenceID = Table::addRow('worder_sentencelinks', $values, false);
		
		redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}
	
	

	public function addconceptAction() {
	
		$comments = false;
	
		$name = $_GET['name'];
		//$conceptdescription = $_GET['description'];
		$wordclass = $_GET['wordclassID'];
	
		if ($comments == true) echo "<br>name - " . $name;
		if ($comments == true) echo "<br>conceptdescription - " . $conceptdescription;
		if ($comments == true) echo "<br>wordclass - " . $wordclass;
	
		$insertarray = array();
		$insertarray['Name'] = $name;
		//$insertarray['Gloss'] = $conceptdescription;
		$insertarray['Selected'] = 1;
		$insertarray['WordclassID'] = $wordclass;
		$insertarray['Parents'] = 0;
		$insertarray['GrammarID'] = $_SESSION['grammarID'];
		$conceptID = Table::addRow('worder_concepts',$insertarray, $comments);
	
		$insertarray = array();
		$insertarray['ParentID'] = 0;
		$insertarray['ConceptID'] = $conceptID;
		$insertarray['GrammarID'] = $_SESSION['grammarID'];
		Table::addRow("worder_conceptparentlinks", $insertarray, false, false);
	
		if ($comments == false) redirecttotal('worder/concepts/showconcept&id=' . $conceptID,null);
	}
	
	
	
	public function addcomponentAction() {
		
		$comments = false;
		$saveaction = true;
		
		$conceptID =  $_GET['conceptID'];
		$componentID =  $_GET['componentID'];
		$inheritancemodeID =  $_GET['inheritancemodeID'];
		$newcomponentKey = $componentID . ":" . $conceptID;
		
		$concept = Table::loadRow("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID, $comments);
		$existingComponents = Table::load("worder_conceptcomponentlinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID . " AND ComponentID=" . $componentID );
		if (count($existingComponents) > 0) {
			echo "<br>Component already exits.";
			exit;
		}
		
		$updatevalues = array();
		$updatevalues['ConceptID'] = $conceptID;
		$updatevalues['ComponentID'] = $componentID;
		$updatevalues['FromconceptID'] = $conceptID;
		$updatevalues['InheritancemodeID'] = $inheritancemodeID;
		$updatevalues['GrammarID'] = $_SESSION['grammarID'];
		if ($saveaction) $success = Table::addRow("worder_conceptcomponentlinks", $updatevalues, $comments);
		if ($comments) {
			echo "<br>Updating worder conceptcomponentlinks<br>";
			print_r($updatevalues);
		}
		
		$updatevalues = array();
	 	$conceptcomponentarray = explode("|", $concept->components);
	 	$conceptcomponents = array();
	 	foreach ($conceptcomponentarray as $index => $componentstruct) {
	 		$conceptcomponents[$componentstruct] = $componentstruct;
	 	}
	 	
	 	if ($inheritancemodeID != InheritanceModes::FOR_CHILDS) {
	 		$conceptcomponents[$newcomponentKey] = $newcomponentKey;
	 		$updatevalues['Components'] = implode('|', $conceptcomponents);
	 		if ($saveaction) Table::updateRow('worder_concepts', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
	 		if ($comments) {
	 			echo "<br>Updating worder_concept<br>";
	 			print_r($updatevalues);
	 		}
	 	}
			 	
		// päivitetään myös kaikki alikäsitteet
		if ($inheritancemodeID != InheritanceModes::SINGLE) {

			$childIDs = ConceptsController::getChildIDs($conceptID);
			$childconcepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $childIDs, "WHERE GrammarID=" . $_SESSION['grammarID']);
			
			if ($childconcepts == null) {
				if ($comments) echo "<br><br>Childconcept count - No childs found";
	 		} else {
	 			if ($comments) echo "<br><br>Childconcept count - " . count($childconcepts);
	 			foreach($childconcepts as $childconceptID => $childconcept) {
	 					
	 				$updatevalues = array();
	 				if ($comments) echo "<br> - updating child " . $childconcept->name . " (" . $childconcept->conceptID . ")";
	 			
	 				// päivitetään lapsen componentit
	 				$childcomponentarray = explode("|", $childconcept->components);
	 				$childcomponents = array();
	 				foreach ($childcomponentarray as $index => $componentstruct) {
	 					if ($componentstruct == "") {
							if ($comments) echo "<br>Empty found, no add";	 						
	 					} else {
	 						$childcomponents[$componentstruct] = $componentstruct;
	 					}
	 				}
	 				$childcomponents[$newcomponentKey] = $newcomponentKey;
	 				if ($comments) echo "<br>Childcomponents - " . $childconcept->components;
	 				
	 				if ($comments) echo "<br> - - - before save = " . var_dump($childcomponents);
	 				$updatevalues['Components'] = implode('|', $childcomponents);
	 				if ($comments) echo "<br> - - - Components = " . $updatevalues['Components'];
	 				
	 				if ($saveaction) Table::updateRow('worder_concepts', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $childconceptID);
	 				if ($comments) {
	 					echo "<br><br>updatechildrow conceptID - " . $conceptID;
	 					print_r($updatevalues);
	 				}
	 				
	 				// Lisätään myös conceptcomponentlinks tauluun uusi rivi...
		 			$updatevalues = array();
					$updatevalues['ConceptID'] = $childconceptID;
					$updatevalues['ComponentID'] = $componentID;
					$updatevalues['FromconceptID'] = $conceptID;
					$updatevalues['InheritancemodeID'] = InheritanceModes::INHERITED;
					$updatevalues['GrammarID'] = $_SESSION['grammarID'];
					if ($saveaction) $success = Table::addRow("worder_conceptcomponentlinks", $updatevalues, $comments);
					if ($comments) {
						echo "<br>Updating worder conceptcomponentlinks<br>";
						print_r($updatevalues);
					}
				 				
	 			}
	 		}		
		}
		if (!$comments) redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}
	
	

	
	
	//
	//
	//  Argumentit ovat muotoa ... ArgumentID:ComponentID:ConceptID
	//
	public function addargumentAction() {
	
		$comments = false;
		$saveaction = true;
		
		if ($comments) echo "<br>addargument Comments - " . $comments;
	
		$conceptID =  $_GET['conceptID'];
		$argumentID =  $_GET['argumentID'];
		$componentID =  $_GET['componentID'];
		$inheritancemodeID =  $_GET['inheritancemodeID'];
		
		if (($componentID == '') || ($inheritancemodeID == '')) {
			echo "<br>No component or inheritancemode";
			exit;
		}
		$newargumentKey = $argumentID . ":" . $componentID . ":" . $conceptID;
		
		$concept = Table::loadRow("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID, $comments);
		
		$updatevalues = array();
		$updatevalues['ConceptID'] = $conceptID;
		$updatevalues['ArgumentID'] = $argumentID;
		$updatevalues['ComponentID'] = $componentID;
		$updatevalues['WordclassID'] = $concept->wordclassID;
		$updatevalues['InheritancemodeID'] = $inheritancemodeID;
		$updatevalues['GrammarID'] = $_SESSION['grammarID'];
		if ($saveaction) $success = Table::addRow("worder_conceptargumentlinks", $updatevalues, $comments);
		if ($comments) {
			echo "<br>adding worder concept argumentlinks<br>";
			print_r($updatevalues);
		}
		
		$updatevalues = array();
		$conceptargumentarray = explode("|", $concept->arguments);
		$conceptarguments = array();
		foreach ($conceptargumentarray as $index => $argumentstruct) {
			if ($argumentstruct == "") {
				echo "<br>Arguments empty, no add";				
			} else {
				$conceptarguments[$argumentstruct] = $argumentstruct;
			}
		}
		$conceptarguments[$newargumentKey] = $newargumentKey;
		$updatevalues['Arguments'] = implode('|', $conceptarguments);
		if ($saveaction) Table::updateRow('worder_concepts', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		if ($comments) {
			echo "<br>Updating worder_concept<br>";
			print_r($updatevalues);
		}
		 
		// päivitetään myös kaikki alikäsitteet
		if ($inheritancemodeID != 3) {
		
			$childIDs = ConceptsController::getChildIDs($conceptID);
			$childconcepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $childIDs, "WHERE GrammarID=" . $_SESSION['grammarID']);
				
			if ($childconcepts == null) {
				if ($comments)echo "<br><br>Childconcept count - No childs found";
			} else {
				if ($comments)echo "<br><br>Childconcept count - " . count($childconcepts);
				foreach($childconcepts as $childconceptID => $childconcept) {
			 		
					$updatevalues = array();
					if ($comments)echo "<br> - updating child " . $childconcept->name . " (" . $childconcept->conceptID . ")";
						
					// päivitetään lapsen componentit
					$childargumentarray = explode("|", $childconcept->arguments);
					$childarguments = array();
					foreach ($childargumentarray as $index => $argumentstruct) {
						if ($argumentstruct == "") {
							echo "<br>Argumentstruct empty, no add - " . $childconcept->conceptID;								
						} else {
							$childarguments[$argumentstruct] = $argumentstruct;
						}
					}
					$childarguments[$newargumentKey] = $newargumentKey;
					if ($comments)echo "<br>Childarguments - " . $childconcept->arguments;
		
					$updatevalues['Arguments'] = implode('|', $childarguments);
					if ($comments)echo "<br> - - - Arguments = " . $updatevalues['Arguments'];
		
					if ($saveaction) Table::updateRow('worder_concepts', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $childconceptID);
					if ($comments) {
						echo "<br><br>updatechildrow conceptID - " . $conceptID;
						print_r($updatevalues);
					}
				}
			}
		}
		
		if (!$comments) redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}
	
	
	
	public function addargumentJSONAction() {
	
		$comments = false;
		if (isset($_GET['comments'])) $comments = true;
		$saveaction = true;
	
		if ($comments) echo "<br>addargument Comments - " . $comments;
	
		$conceptID =  $_GET['conceptID'];
		$argumentID =  $_GET['argumentID'];
		$componentID =  $_GET['componentID'];
		$inheritancemodeID =  3;
	
		$newargumentKey = $argumentID . ":" . $componentID . ":" . $conceptID;
	
		$arguments = Table::load("worder_conceptargumentlinks", "WHERE ConceptID=" . $conceptID . " AND ArgumentID=" . $argumentID . " AND ComponentID=" . $componentID);
		if (count($arguments) > 0) {
			echo "<br>Already exists - fail";
			exit;
		}
		
		
		$concept = Table::loadRow("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID, $comments);
	
		
		
		
		$updatevalues = array();
		$updatevalues['ConceptID'] = $conceptID;
		$updatevalues['ArgumentID'] = $argumentID;
		$updatevalues['ComponentID'] = $componentID;
		$updatevalues['WordclassID'] = $concept->wordclassID;
		$updatevalues['InheritancemodeID'] = $inheritancemodeID;
		$updatevalues['GrammarID'] = $_SESSION['grammarID'];
		if ($saveaction) $success = Table::addRow("worder_conceptargumentlinks", $updatevalues, $comments);
		if ($comments) {
			echo "<br>adding worder concept argumentlinks<br>";
			print_r($updatevalues);
		}
	
		$updatevalues = array();
		$conceptargumentarray = explode("|", $concept->arguments);
		$conceptarguments = array();
		foreach ($conceptargumentarray as $index => $argumentstruct) {
			if ($argumentstruct == "") {
				echo "<br>Arguments empty, no add";
			} else {
				$conceptarguments[$argumentstruct] = $argumentstruct;
			}
		}
		$conceptarguments[$newargumentKey] = $newargumentKey;
		$updatevalues['Arguments'] = implode('|', $conceptarguments);
		if ($saveaction) Table::updateRow('worder_concepts', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		if ($comments) {
			echo "<br>Updating worder_concept<br>";
			print_r($updatevalues);
		}
			
		// päivitetään myäs kaikki alikäsitteet
		// -- tätä funktiota käytetään vain scripteistä manuaalinappulasta, lisätään ainoastaan singlejä
		/*
		if ($inheritancemodeID != 3) {
	
			$childIDs = ConceptsController::getChildIDs($conceptID);
			$childconcepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $childIDs, "WHERE GrammarID=" . $_SESSION['grammarID']);
	
			if ($childconcepts == null) {
				if ($comments)echo "<br><br>Childconcept count - No childs found";
			} else {
				if ($comments)echo "<br><br>Childconcept count - " . count($childconcepts);
				foreach($childconcepts as $childconceptID => $childconcept) {
	
					$updatevalues = array();
					if ($comments)echo "<br> - updating child " . $childconcept->name . " (" . $childconcept->conceptID . ")";
	
					// päivitetään lapsen componentit
					$childargumentarray = explode("|", $childconcept->arguments);
					$childarguments = array();
					foreach ($childargumentarray as $index => $argumentstruct) {
						if ($argumentstruct == "") {
							echo "<br>Argumentstruct empty, no add - " . $childconcept->conceptID;
						} else {
							$childarguments[$argumentstruct] = $argumentstruct;
						}
					}
					$childarguments[$newargumentKey] = $newargumentKey;
					if ($comments)echo "<br>Childarguments - " . $childconcept->arguments;
	
					$updatevalues['Arguments'] = implode('|', $childarguments);
					if ($comments)echo "<br> - - - Arguments = " . $updatevalues['Arguments'];
	
					if ($saveaction) Table::updateRow('worder_concepts', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $childconceptID);
					if ($comments) {
						echo "<br><br>updatechildrow conceptID - " . $conceptID;
						print_r($updatevalues);
					}
				}
			}
		}
		*/
		if (!$comments) echo "1";
			
			//redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}
	
	
	
	// TODO: Voisi nimetä uudelleen, insertexistingword tms.
	public function insertwordAction() {
	
		$comments = false;
		
		$languageID = $_GET['languageID'];
		$conceptID = $_GET['conceptID'];
		$wordID = $_GET['wordID'];
		
		//$concept = Table::loadRow("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
	
		// TODO: Jos kyseisessä käsitteessä on oletussana asetettu, niin Defaultwordiksi pitäisi asettaa 0
		
		//$links = Table::loadRow("worder_conceptwordlinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID . " AND LanguageID=" . $languageID);

		
		
		$values = array();
		$values['ConceptID'] = $conceptID;
		$values['WordID'] = $wordID;
		$values['LanguageID'] = $languageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['Defaultword'] = 1;
		$wordID = Table::addRow("worder_conceptwordlinks", $values, $comments);
		
		//setSessionVar('defaultlanguage', $languageID);
		if ($comments == false) redirecttotal('worder/concepts/showconcept&id=' . $conceptID,null);
	}
	
	

	
	
	
	public function activateconceptAction() {

		$id = $_GET['id'];
		$success='';
		$columns = array();
		$columns['Active']=1;
		$success = Table::updateRow('worder_concepts', $columns, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $id);
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
	}
	

	public function deactivateconceptAction() {
	
		$id = $_GET['id'];
	
		$success='';
		$columns = array();
		$columns['Active']=0;
		$success = Table::updateRow('worder_concepts', $columns, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $id);
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
	}
	

	public function undefaultwordAction() {
	
		$comments = false;
		$wordID = $_GET['id'];
		$conceptID = $_GET['conceptID'];
	
		$wordlinks = Table::load("worder_conceptwordlinks","WHERE ConceptID=" . $conceptID . " AND WordID=" . $wordID, $comments);
		//echo "<br>Link count - " . count($wordlinks);
		foreach($wordlinks as $index => $wordlink) { }
		//echo "<br>Link rowID - " . $wordlink->rowID;
		
		$defaultword = 0;
		if ($wordlink->defaultword == 0) {
			$defaultword = 1;
		}
	
		$values = array();
		$values['Defaultword'] = $defaultword;
		$success = Table::updateRow("worder_conceptwordlinks", $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $wordlink->rowID, $comments);
	
		if (!$comments) redirecttotal('worder/concepts/showconcept&id=' . $conceptID ,null);
	}
	
	
	
	// pitäisikä tämän palauttaa jsonnia? 17.4.2019 Pitäisi
	public function insertgroupAction() {

		$comments = false;
		
		$conceptID =  $_GET['conceptID'];
		$wordgroupID =  $_GET['groupID'];
		
		$values = array();
		$values['WordgroupID'] = $wordgroupID;
		$values['ConceptID'] = $conceptID;
		$values['Sortorder'] = $conceptID;
		
		$success = Table::addRow("worder_wordgroupconcepts", $values, $comments);
		
		redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}
	
	
	public function removegroupAction() {
		$conceptID = $_GET['conceptID'];
		$wordgroupID = $_GET['id'];
		$success = Table::deleteRowsWhere("worder_wordgroupconcepts"," WHERE WordgroupID='" . $wordgroupID . "' AND ConceptID='" . $conceptID . "'", true);
		
		// Pitää poistaa lisäksi kaikista worder_wordgroup[language]_words -tauluista kyseinen concepti...
		$languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		foreach($languages as $index => $language) {
			$success = Table::deleteRowsWhere("worder_wordgroupwords"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordgroupID='" . $wordgroupID . "' AND ConceptID='" . $conceptID . "'", true);
		}
		
		//redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}
	
	

	public function removecomponentAction() {
		
		$comments = true;
		$saveaction = true;
		
		if ($comments) echo "<br>remove compoenent";
		
		
		$conceptID = $_GET['conceptID'];
		$componentID = $_GET['componentID'];
		$concept = Table::loadRow("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID, $comments);
		
		
		if ($saveaction == true) Table::deleteRowsWhere("worder_conceptcomponentlinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID . " AND ComponentID=" . $componentID, $comments);
		if ($comments) echo "<br>Poistetaan rivi conceptcomponentlinks taulusta";
		$deletekey = $componentID . ":" . $conceptID;
		
		$updatevalues = array();
		$conceptcomponentarray = explode("|", $concept->components);
		$conceptcomponents = array();
		foreach ($conceptcomponentarray as $index => $componentstruct) {
			if ($componentstruct == "") {
				if ($comments) echo "<br>Empty componentstuct no add";				
			} else {
				if ($componentstruct != $deletekey) {
					$conceptcomponents[$componentstruct] = $componentstruct;
				}
			}
		}
		
		$updatevalues['Components'] = implode('|', $conceptcomponents);
		if ($saveaction) Table::updateRow('worder_concepts', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		if ($comments) {
			echo "<br>Updating worder_concept<br>";
			print_r($updatevalues);
		}
		
		$childIDs = ConceptsController::getChildIDs($conceptID);
		$childconcepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $childIDs, "WHERE GrammarID=" . $_SESSION['grammarID']);
			
		if ($childconcepts == null) {
			if ($comments) echo "<br><br>Childconcept count - No childs found";
		} else {
			if ($comments) echo "<br><br>Childconcept count - " . count($childconcepts);
			foreach($childconcepts as $childconceptID => $childconcept) {
					
				$updatevalues = array();
				if ($comments) echo "<br> - updating child " . $childconcept->name . " (" . $childconcept->conceptID . ")";
				if ($comments) echo "<br> - original childcomponents - " . $childconcept->components;
					
				// päivitetään lapsen componentit
				$childcomponentarray = explode("|", $childconcept->components);
				$childcomponents = array();
				foreach ($childcomponentarray as $index => $componentstruct) {
					if ($componentstruct != $deletekey) {
						$childcomponents[$componentstruct] = $componentstruct;
					}
				}
				if ($comments) echo "<br> - - - Before implode";
				if ($comments) var_dump($childcomponents);
				$updatevalues['Components'] = implode('|', $childcomponents);
				if ($comments) echo "<br> - - - Components = " . $updatevalues['Components'];
		
				if ($saveaction) Table::updateRow('worder_concepts', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $childconceptID);
				if ($comments) {
					echo "<br>updatechildrow conceptID - " . $childconceptID . "<br>";
					print_r($updatevalues);
				}
				
				$success = Table::deleteRowsWhere("worder_conceptcomponentlinks"," WHERE ConceptID=" . $childconceptID . " AND ComponentID=" . $componentID . " AND FromconceptID=" . $conceptID, $comments);
			}
		}
		if ($comments == false) redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}
	
	
	
	public function removeparentAction() {
		
		$conceptID = $_GET['conceptID'];
		$parentID = $_GET['id'];
		
		
		$saveactive = true;
		$comments = false;
		
		if ($comments) echo "<br>Romove - " . $conceptID . " - " . $parentID;
		
		$concept = Table::loadRow("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		
		if ($saveactive == true) Table::deleteRowsWhere("worder_conceptparentlinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID . " AND ParentID=" . $parentID);

		$rows = Table::load('worder_conceptparentlinks', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		
		if ($comments) {
			var_dump($rows);
			foreach($rows as $index => $link) {
				echo "<br>Link - " . $link->parentID;
			}
		}
		
		if (count($rows) == 0) {
			$insertarray = array();
			$insertarray['ParentID'] = 0;
			$insertarray['ConceptID'] = $conceptID;
			$insertarray['GrammarID'] = $_SESSION['grammarID'];
			Table::addRow("worder_conceptparentlinks", $insertarray, false, false);
		}
		
		$parentarray = array();
		$allconcepts = array();
		$topparents = array();
		$allparents = explode(':', $concept->allparents);
		foreach($allparents as $index => $tempparentID) {
			if ($comments)echo "<br> -- loopparent - " . $tempparentID;
			if (($tempparentID != 0) && ($tempparentID != '')) {
				if ($comments)echo "<br> -- loopparent --- " . $tempparentID;
				$parentarray[$tempparentID] = $tempparentID;
				$allconcepts[$tempparentID] = $tempparentID;
				if ($tempparentID != $conceptID) {
					if ($comments)echo "<br> -- loopparent yes - " . $tempparentID;
					$topparents[$tempparentID] = $tempparentID;
				}
			}
		}
		if (count($parentarray) == 0) {
			if ($comments)echo "<br>no parents";
		}
		$parentarray[$conceptID] = $conceptID;
		$parentlinks = Table::loadWhereInArray("worder_conceptparentlinks","ConceptID",$parentarray,"WHERE GrammarID=" . $_SESSION['grammarID']);
		if ($comments)echo "<br><br>Parentlinks:";
		foreach($parentlinks as $index => $link) {
			if ($comments) echo "<br>--- Linkki - " . $link->rowID . ", conceptID:" . $link->conceptID . ", parentID:" . $link->parentID;
		}
		
		// Pitää päivittää myös kaikkien childien pathit.
		$childarray = array();
		ConceptsController::getChildLinks($conceptID, $childarray, $parentlinks, $comments);
		if ($comments) echo "<br><br>Childs found - " . implode(",", $childarray);
		$childarray[$conceptID] = $conceptID;	// lisätään päivitettäviin myös self
		

		if ($comments) echo "<br><br>Removing parent concepts...";
		
		if ($comments)echo "<br> -- Childs...";
		foreach($childarray as $index => $loopconceptID) {
			$allconcepts[$loopconceptID] = $loopconceptID;
			if ($comments)echo "<br> -- Child - " . $loopconceptID;
		}

		if ($comments)echo "<br> -- Parents...";
		foreach($topparents as $index => $loopconceptID) {
			if ($comments)echo "<br> -- Parent - " . $loopconceptID;
		}
		
		
		// Pitää hakea componentlinksit kaikista parenteista...
		
		$componentlinks = Table::loadWhereInArray("worder_conceptcomponentlinks","ConceptID",$allconcepts,"WHERE GrammarID=" . $_SESSION['grammarID']);
		
		foreach($componentlinks as $index => $componentlink) {
			
			if (isset($childarray[$componentlink->conceptID])) {
				if (isset($topparents[$componentlink->fromconceptID])) {
					if ($comments)echo "<br> -- component should be removed  - conceptID: " . $componentlink->conceptID. ", componentID:" . $componentlink->componentID . ", parentID:" . $componentlink->fromconceptID . ", rowID:" . $componentlink->rowID;
					$success = Table::deleteRow('worder_conceptcomponentlinks',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $componentlink->rowID);
				} else {
					if ($comments)echo "<br> -- no parent source - conceptID: " . $componentlink->conceptID. ", componentID:" . $componentlink->componentID . ", parentID:" . $componentlink->fromconceptID . ", rowID:" . $componentlink->rowID;
				}
			} else {
				if ($comments)echo "<br> -- non parent component - conceptID: " . $componentlink->conceptID. ", componentID:" . $componentlink->componentID . ", parentID:" . $componentlink->fromconceptID . ", rowID:" . $componentlink->rowID;
			}			
		}
		if ($comments)echo "<br><br>";
		
		// Pitää poistaa kaikki componentsit kaikista childeista...
		
		
		
		// childarray sisältää kaikki childit
		// finalallparentsarray sisältää kaikki parentit, mukaan lukien nykyisen parentin, sisältääkö myös nykyisen conceptID:n?
		$allconceptsarray = array();
		foreach($parentarray as $index => $tempID) $allconceptsarray[$tempID] = $tempID;
		foreach($childarray as $index => $tempID) $allconceptsarray[$tempID] = $tempID;
		$allconceptsarray[$conceptID] = $conceptID;
		if ($comments) {
			foreach($allconceptsarray as $index => $value) {
				echo "<br>--- Allconcepts - " . $index . ", conceptID:" . $value. "";
			}
		}
		
		
		$componentlinks = ConceptsController::loadAllComponents($allconceptsarray, $comments);
		$argumentlinks = ConceptsController::loadAllArguments($allconceptsarray);
		
		foreach($childarray as $index => $childID) {
			ConceptsController::updateParentsAndPaths($childID, $parentlinks, $saveactive, $comments);
			ConceptsController::updateComponentsAndArgumens($childID, $parentlinks, $componentlinks, $argumentlinks, $saveactive, $comments);
		}
		if ($comments == true) echo "<br>Redicting -- " . $conceptID;
		if (!$comments) redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}
		

	public function removeargumentAction() {
	
		$comments = false;
		$saveaction = true;
		
		if ($comments) echo "<br>remove argument 1";
		
		$conceptID = $_GET['conceptID'];
		$argumentID = $_GET['argumentID'];
		$componentID = $_GET['componentID'];
		$concept = Table::loadRow("worder_concepts",$conceptID, true);
		
		if ($saveaction == true) Table::deleteRowsWhere("worder_conceptargumentlinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID . " AND ComponentID=" . $componentID . " AND ArgumentID=" . $argumentID);
		if ($comments) echo "<br>Poistetaan rivi worder concept argumentlinks taulusta";
		
		$deletekey = $argumentID . ":" . $componentID . ":" . $conceptID;
		if ($comments) echo "<br>Deletekey - " . $deletekey;
		if ($comments) echo "<br>Originalrequirements - " . $concept->arguments;
		
		$updatevalues = array();
		$conceptargumentarray = explode("|", $concept->arguments);
		$conceptarguments = array();
		foreach ($conceptargumentarray as $index => $argumentstruct) {
			if ($argumentstruct != $deletekey) {
				$conceptarguments[$argumentstruct] = $argumentstruct;
			}
		}
		$updatevalues['Arguments'] = implode('|', $conceptarguments);
		if ($saveaction) Table::updateRow('worder_concepts', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		if ($comments) {
			echo "<br>Updating worder_concept<br>";
			print_r($updatevalues);
		}
		
		$childIDs = ConceptsController::getChildIDs($conceptID);
		$childconcepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $childIDs, "WHERE GrammarID=" . $_SESSION['grammarID']);
		if ($childconcepts == null) {
			if ($comments)echo "<br><br>Childconcept count - No childs found";
		} else {
			if ($comments)echo "<br><br>Childconcept count - " . count($childconcepts);
			foreach($childconcepts as $childconceptID => $childconcept) {
		 		
				$updatevalues = array();
				if ($comments)echo "<br> - updating child " . $childconcept->name . " (" . $childconcept->conceptID . ")";
				if ($comments)echo "<br> - orginal Childarguments - " . $childconcept->arguments;
				
				// päivitetään lapsen componentit
				$childargumentarray = explode("|", $childconcept->arguments);
				$childarguments = array();
				foreach ($childargumentarray as $index => $argumentstruct) {
					if ($argumentstruct != $deletekey) {
						$childarguments[$argumentstruct] = $argumentstruct;
					}
				}
				
				$updatevalues['Arguments'] = implode('|', $childarguments);
				if ($comments)echo "<br> - Arguments = " . $updatevalues['Arguments'];
	
				if ($saveaction) Table::updateRow('worder_concepts', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $childconceptID);
				if ($comments) {
					echo "<br>updatechildrow conceptID - " . $childconceptID . "<br>";
					print_r($updatevalues);
				}
			}
		}
		if ($saveaction) redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}
	
	
	
	

	public function removewordfromconceptAction() {

		global $mysqli;
		$comments = false;
		
		if ($comments) echo "<br>removewordfromconcept";
		
		$conceptID = $_GET['conceptID'];
		$wordID = $_GET['id'];
		$languageID = $_GET['lang'];
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		
		$success = Table::deleteRowsWhere("worder_conceptwordlinks"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID . " AND WordID=" . $wordID, $comments);
		
		// käsite pitäisi poistaa ryhmistä
		//

		/*
		if ($success == true) {
			redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
		} else {
			redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
		}
		*/
		if ($comments == false) redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
	}
	
	


	public function removesentencefromconceptAction() {
	
		global $mysqli;
		$comments = false;
	
		if ($comments) echo "<br>removewordfromconcept";
	
		$conceptID = $_GET['conceptID'];
		$sentenceID = $_GET['id'];
		$success = Table::deleteRowsWhere("worder_sentencelinks"," WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID . " AND ConceptID=" . $conceptID . " AND SentenceID=" . $sentenceID, $comments);
		
		if ($comments == false) redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
		
		//$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
	
		
		// käsite pitäisi poistaa ryhmistä
		//
	
		/*
			if ($success == true) {
			redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
			} else {
			redirecttotal('worder/concepts/showconcept&id=' . $conceptID, null);
			}
		*/
	}
	
	
	public function insertnewwordAction() {
	
		$comments = false;
		
		$languageID = $_GET['languageID'];
		$word = $_GET['word'];
		$conceptID = $_GET['conceptID'];
		$concept = Table::loadRow("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		
		$links = Table::loadRow("worder_conceptwordlinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID . " AND LanguageID=" . $languageID);
		
		$default = 1;
		if (count($links) > 0) {
			$default = 0;
		}
		
		$values = array();
		$values['Lemma'] = $word;
		$values['WordclassID'] = $concept->wordclassID;
		$values['LanguageID'] = $languageID;
		$values['ConceptID'] = $conceptID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$wordID = Table::addRow("worder_words", $values, $comments);
		
		$values = array();
		$values['WordID'] = $wordID;
		$values['ConceptID'] = $conceptID;
		$values['LanguageID'] = $languageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['Defaultword'] = $default;
		$linkID = Table::addRow("worder_conceptwordlinks", $values, $comments);
		
		if ($comments == false) redirecttotal('worder/concepts/showconcept&id=' . $conceptID . '',null);
	}
	
	
	

	public function insertexistingwordAction() {
	
		$languageID = $_GET['languageID'];
		$wordID = $_GET['wordID'];
		$conceptID = $_GET['conceptID'];
		setSessionVar('defaultlanguage', $languageID);
		
		$language = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$values = array();
		$values['WordID'] = $wordID;
		$values['ConceptID'] = $conceptID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$wordID = Table::addRow("worder_conceptwordlinks", $values, false);
		
		redirecttotal('worder/concepts/showconcept&id=' . $conceptID . '',null);
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
	
	
	/**
	 * Toteutettu uudelleen, koska aiempi toteutus oli vaikea, epäselvä ja vaikea ylläpitää...
	 * 
	 * Tavoitteet:
	 * 		- Hakujen tulee olla mahdollisimman nopeita, tämä tarkoittaa että parent, components, arguments listat on concept rivillä tallessa
	 * 			- Tämä johtaa siihen, että lisättäessä ja poistettaessa pitää lapset päivittää
	 * 		- Rakenteen pitäisi olla selkeä
	 * 		- Haut pitäisi pystyä hakemaan kokonaisuudessaan yhdellä kyselyllä, yhdestä taulusta
	 * 
	 *  Kentät
	 *  	- Concept.parents -- kyseisen käsitteen suorat parentit
	 *  	- Concept.allparents -- kyseisen käsitteen kaikki parentit rekursiivisesti, tämä on lista josta components ja arguments peritään
	 * 		- Concept.parentpaths -- lista kaikista parenteista patheineen, tarvitaan ehkä pelkästään conceptin hierachy-sectioniin 
	 */	
	public function addParentToConceptAction() {
	
		$conceptID = $_GET['conceptID'];
		$newParentID = $_GET['parentID'];
		
		$comments = false;
		$saveactive = true;
		
		if ($conceptID == $newParentID) {
			echo "<br>Cannot be parent of self";
			exit;
		}
		
		$concept = Table::loadRow("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		$parent = Table::loadRow("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $newParentID);
		
		if ($comments) echo "<br>conceptID - "  . $conceptID;
		if ($comments) echo "<br>parentID - "  . $newParentID;
		
		// luodaan lista kaikista ladattavista concepteista
		$parentarray = array();
		$finalallparentsarray = array();
		
		$parentarray[$conceptID] = $conceptID;
		$parentarray[$newParentID] = $newParentID;
		$currentparents = explode(':', $concept->parents);
		
		foreach($currentparents as $index => $parentID) {
			if ($parentID == $newParentID) {
				echo "<br>Parent already exists - " . $parentID;
				exit;
			}
		}
		
		$allparents = explode(':', $concept->allparents);
		foreach($allparents as $index => $parentID) {
			if (($parentID != 0) && ($parentID != '')) {
				$parentarray[$parentID] = $parentID;
				$finalallparentsarray[$parentID] = $parentID;
				if ($comments) echo "<br>--- Adding1 - " . $parentID;
			}
		}
		$parents = explode(':', $parent->allparents);
		foreach($parents as $index => $parentID) {
			if (($parentID != 0) && ($parentID != '')) {
				$parentarray[$parentID] = $parentID;
				$finalallparentsarray[$parentID] = $parentID;
				if ($comments) echo "<br>--- Adding2 - " . $parentID;
			}
		}
		$finalallparentsarray[$newParentID] = $newParentID;
		
		$currentparentsstr = $concept->parents;
		if ($comments) echo "<br>Currentparentstr - " . $concept->parents;
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
		
		
		// nyt parentarray:ss on kaikki liittyvät käsitteet, ladataan linkit
		$parentlinks = Table::loadWhereInArray("worder_conceptparentlinks","ConceptID",$parentarray,"WHERE GrammarID=" . $_SESSION['grammarID']);
		if ($comments) {
			echo "<br><br>Links";
			foreach($parentlinks as $index => $link) {
				echo "<br>--- Linkki - " . $link->rowID . ", conceptID:" . $link->conceptID . ", parentID:" . $link->parentID;
			}
		}
		

		// Luodaan kaikki parentpathsit
		if ($comments) echo "<br><br>";
		$allpaths = array();
		foreach($currentparents as $index => $parentID) {
			if ($comments) echo "<br>Checking parentpaths - " . $parentID;
			$oldpaths = ConceptsController::getPaths($parentID, $conceptID, $parentlinks, $comments);
			foreach($oldpaths as $index => $path) {
				$allpaths[] = $path;
			}
			if ($comments) echo "<br>oldPaths - ";
			if ($comments) var_dump($oldpaths);
		}
		$newpaths = ConceptsController::getPaths($newParentID, $conceptID, $parentlinks, $comments);
		if ($comments) echo "<br>newwipaths - ";
		if ($comments) var_dump($newpaths);
		
		foreach($newpaths as $index => $path) {
			$allpaths[] = $path;
		}
		
		$pathstr = ConceptsController::pathsArrayToString($allpaths);
		
		if ($comments) echo "<br><br>Pathstr - " . $pathstr;
		if ($comments) echo "<br><br>Currentparents - " . $currentparentsstr;
		if ($comments) echo "<br><br>allparents - " . implode(':',$finalallparentsarray);
		
		$values = array();
		$values['ConceptID'] = $conceptID;
		$values['ParentID'] = $newParentID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		if ($saveactive) {
			$rowID = Table::addRow("worder_conceptparentlinks", $values, false, false);
		} else {
			$rowID = 9999;
		}
		// Tehdään temppirow linkseihin, voisi myös ladata rowID:n tietokannasta, mutta tämä nopeampi
		// Tätä tarvitaan siihen, että uudet pathit lasketaan parentlinks-taulukon avulla
		$newlink = new Row();
		$newlink->rowID = $rowID;
		$newlink->parentID = $newParentID;
		$newlink->conceptID = $conceptID;
		$parentlinks[$newlink->rowID] = $newlink;
		
		if ($comments) echo "<br>Lisätty worder conceptparentlinks - ConceptID:" . $conceptID . ", parentID:" . $newParentID;
		// Pitääkö tämä uusi linkki lisätä parentlinksiin, todennäköisesti?
		
		// Mikäli käsiteltävä konsepti on rootissa, ja ollaan asettamassa sille parenttia, poistetaan rootti parentti
		if (($concept->parents == null) || ($concept->parents == "0") || ($concept->parents == "")) {
			if ($saveactive) Table::deleteRowsWhere("worder_conceptparentlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID . " AND ParentID=0", false, false);
			foreach($parentlinks as $index => $link) {
				if (($link->conceptID == $conceptID) && ($link->parentID == 0)) {
					if ($comments) echo "<br>Removing item from parentlinks - "  . $link->conceptID;
					unset($parentlinks[$index]);
				}
			}
		}
		
		$values = array();
		$updatevalues['Parents'] = $currentparentsstr;
		$updatevalues['Allparents'] = implode(':',$finalallparentsarray);
		$updatevalues['Parentpaths'] = $pathstr;
		if ($saveactive) Table::updateRow('worder_concepts', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		if ($comments) echo "<br>Päivitetty concepts - ConceptID:" . $conceptID . ", conceptID:" . $conceptID;
		
		
		// Nyt pitäisi olla käytettävissä kaikki informaatio koko operaation tekemiseen...
		
		//   - Tsekkaa loopit
		// 			- Käsittääkseni riittää tsekata kyseisen parentin loopit suhteessa uuteen parenttiin
		//				- ehkä lapsillakin on mahdollista olla looppeja? Vai haittaako ne? 
		//				- Ei saisi olla itseensä looppeja... pitänee ladata lapset myös...
		//   - Sitten pitäisi myös päivittää childit, tämä onkin sitten se työläämpi operaatio
		//   		- parentlinks on kyllä kunnossa, mutta childien perityt listat pitää päivittää
		//			- myös parentpathit pitää päivittää
		
		
		
		// Pitää päivittää myös kaikkien childien pathit.
		$childarray = array();
		ConceptsController::getChildLinks($conceptID, $childarray, $parentlinks, $comments);
		if ($comments) echo "<br><br>Childs found - " . implode(",", $childarray);
		$childarray[$conceptID] = $conceptID;
		
		// childarray sisältää kaikki childit
		// finalallparentsarray sisältää kaikki parentit, mukaan lukien nykyisen parentin, sisältääkö myös nykyisen conceptID:n?
		$allconceptsarray = array();
		foreach($finalallparentsarray as $index => $tempID) $allconceptsarray[$tempID] = $tempID;
		foreach($childarray as $index => $tempID) $allconceptsarray[$tempID] = $tempID;
		$allconceptsarray[$conceptID] = $conceptID;
		
		$componentlinks = ConceptsController::loadAllComponents($allconceptsarray);
		$argumentlinks = ConceptsController::loadAllArguments($allconceptsarray);
		
		foreach($childarray as $index => $childID) {
			ConceptsController::updateParentsAndPaths($childID, $parentlinks, $saveactive, $comments);
			ConceptsController::updateComponentsAndArgumens($childID, $parentlinks, $componentlinks, $argumentlinks, $saveactive, $comments);
		}
		
		
		
		// Pitää päivittää childien componentlinksit
		// Pitää päivittää childien argumentlinksit
		
		// Childit pitää myös päivättää kun
		//	- argumentti lisätään
		//  - argumentti poistetaan
		//  - componentti lisätään
		//  - componentti poistetaan
		
		if ($comments == false) redirecttotal('worder/concepts/showconcept&id=' . $conceptID . '',null);
	}
	
	
	private static function loadAllComponents($conceptlist, $comments = false) {
		//echo "<br>Loading components -- " . implode(":", $conceptlist);
		$links = Table::loadWhereInArray("worder_conceptcomponentlinks","ConceptID",$conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID'], $comments);
		return $links;
	}
	
	
	private static function loadAllArguments($conceptlist, $comments = false) {
		$links = Table::loadWhereInArray("worder_conceptargumentlinks","ConceptID",$conceptlist,"WHERE GrammarID=" . $_SESSION['grammarID']);
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
	private static function updateComponentsAndArgumens($conceptID, $parentlinks, $componentlinks, $argumentlinks, $saveactive, $comments = false) {
		
		if ($comments) echo "<br><br>updateComponents: " . $conceptID;
		$paths = ConceptsController::getPaths($conceptID, -1, $parentlinks, $comments);
		
		if ($comments) {
			echo "<br>Componentlinks --- ";
			foreach($componentlinks as $index => $link) {
				echo "<br>Componentlink - " . $link->componentID . " - " . $link->conceptID;
			}
			
			echo "<br>Count paths --- " . count($paths);
			foreach($componentlinks as $index => $link) {
				echo "<br>Componentlink - " . $link->componentID . " - " . $link->conceptID;
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
		$allparents[$conceptID] = $conceptID;
		
		$components = array();
		$arguments = array();
		
		// poistetaan conceptcomponentlinks-taulun kaikki rivit, ne luodaan uusiksi
		// HUOM: tämä ei ole ehkä paras mahdollinen toteutus, hirveen raskas haku tämä on?
		
		$newcomponents = array();
		$existingcomponents = array();
		
		foreach($allparents as $index => $parentID) {
			if ($comments) echo "<br>Links from parent - " . $parentID;
			if ($componentlinks != null) {
				foreach($componentlinks as $index => $link) {
					//if ($comments) echo "<br>Componentlink --- linkID:" . $link->rowID . ", componentID:" . $link->componentID . ", mode: " . $link->inheritancemodeID . ", conceptID:" . $link->conceptID;
					if ($link->conceptID == $parentID) {
							
						if ($link->inheritancemodeID == InheritanceModes::SINGLE) {
							//if ($link->componentID == $parentID) { alkuperäinen
							if ($link->conceptID == $conceptID) {
								if ($comments) echo "<br>Currentcomponent - componentID:" .$link->componentID . ", conceptID:" . $link->conceptID;
								$components[] = $link->componentID . ":" . $link->conceptID;
							}
						} else if ($link->inheritancemodeID == InheritanceModes::FOR_CHILDS) {
							
							if ($link->conceptID == $conceptID) {
								// Ei lisätä itselle mikäli for-childs-tyyppinen component								
							} else {
								if ($comments) echo "<br>Inheritable currentcomponent - componentID:" .$link->componentID . ", conceptID:" . $link->conceptID;
								$components[] = $link->componentID . ":" . $link->conceptID;
								$newcomponents[$link->componentID] = $link->conceptID;
							}
							
						//} else if (($link->inheritancemodeID == InheritanceModes::FOR_CHILDS) || ($link->inheritancemodeID == InheritanceModes::INHERITABLE)) {
						} else if ($link->inheritancemodeID == InheritanceModes::INHERITABLE) {

							if ($comments) echo "<br>Inheritable currentcomponent - componentID:" .$link->componentID . ", conceptID:" . $link->conceptID;
							$components[] = $link->componentID . ":" . $link->conceptID;
							$newcomponents[$link->componentID] = $link->conceptID;
							
						} else if ($link->inheritancemodeID == InheritanceModes::INHERITED) {
							if ($comments) echo "<br>Inherited found -- ";
						}
					}
					
					if ($link->conceptID == $conceptID) {
						if ($comments) echo "<br>thiscomponent - componentID:" .$link->componentID . ", conceptID:" . $link->conceptID;
						$existingcomponents[$link->componentID] = $link->componentID;
						$newcomponents[$link->componentID] = $link->componentID;
					}
				}
			} else {
				if ($comments) echo "<br>Compoentlinks null";
			}
			
			if ($argumentlinks != null) {
				foreach($argumentlinks as $index => $link) {
					if ($comments) echo "<br>Argumentlink --- componentID:" . $link->componentID . ", mode: " . $link->inheritancemodeID . ", conceptID:" . $link->conceptID;
					if ($link->conceptID == $parentID) {
				
						if ($link->inheritancemodeID == InheritanceModes::SINGLE) {
							if ($link->componentID == $parentID) {			// Mikähän vertailu tämä on, turha? Pitää ehkä verrata conceptID:tä
								$arguments[] = $link->argumentID . ":" . $link->componentID . ":" . $link->conceptID;
							}
						} else if (($link->inheritancemodeID == InheritanceModes::FOR_CHILDS) || ($link->inheritancemodeID == InheritanceModes::INHERITABLE)) {
							$arguments[] = $link->argumentID . ":" . $link->componentID . ":" . $link->conceptID;
						}
					}
				}
			} else {
				if ($comments) echo "<br>Argumentlinks null";
			}
		}

		if ($comments) echo "<br>------------ parentID - " . $parentID;
		if ($comments) echo "<br>newcomponent count - " . count($newcomponents);
		if ($comments) echo "<br>existing component count - " . count($existingcomponents);
		
		// lisätään puuttuvat componentit conceptcomponentlinks-tauluun...
		foreach($newcomponents as $componentID => $parentconceptID) {
		
			if ($comments) echo "<br>New components - " . $index . " - " . $componentID;
		
			if (isset($existingcomponents[$componentID])) {
				if ($comments) echo "<br>Item update - already exists - " . $componentID;
			} else {
				// itemi puuttuu kannasta, lisätään
				$tempupdatevalues = array();
				$tempupdatevalues['ConceptID'] = $conceptID;
				$tempupdatevalues['ComponentID'] = $componentID;
				$tempupdatevalues['FromconceptID'] = $parentconceptID;
				$tempupdatevalues['InheritancemodeID'] = InheritanceModes::INHERITED;
				$tempupdatevalues['GrammarID'] = $_SESSION['grammarID'];
				$success = Table::addRow("worder_conceptcomponentlinks", $tempupdatevalues, false);
				if ($comments) echo "<br>--lisätään conceptcomponentlinks - componentID:" . $componentID . ", concepptID:" . $conceptID;
			}
		}
			
		// poistetaan componentit conceptcomponentlinks-tauluun...
		foreach($existingcomponents as $componentID => $parentconceptID) {
			
			if ($comments) echo "<br>existing components - conceptID:" . $conceptID . " - componentID:" . $componentID;
			
			if (isset($newcomponents[$componentID])) {
				if ($comments) echo "<br>Item exists - no need to remove - " . $componentID;
			} else {
				if ($comments) echo "<br>poistetaan conceptcomponentlinks - componentID:" . $componentID . ", concepptID:" . $conceptID;
				Table::deleteRowsWhere("worder_conceptcomponentlinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID . " AND ComponentID=" . $componentID, $comments);
			}
		}
			
			
		
		if ($comments) echo "<br>-- Found components - " . implode("|", $components);
		if ($comments) echo "<br>-- Found arguments - " . implode("|", $arguments);
	
		$values = array();
		$updatevalues['Components'] = implode("|", $components);
		$updatevalues['Arguments'] = implode("|", $arguments);
		if ($saveactive) Table::updateRow('worder_concepts', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		if ($comments) echo "<br>Components ja Requirements päivitetty concepts - ConceptID:" . $conceptID . ", conceptID:" . $conceptID;
	}
	
	
	private static function getChildLinks($parentID, &$childarray, &$parentlinks, $comments = false) {
		$parentarray = array();
		$parentarray[$parentID] = $parentID;
		ConceptsController::getChildLinksRecursive($parentarray, $childarray, $parentlinks,0, $comments); 
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
		$childlinks = Table::loadWhereInArray("worder_conceptparentlinks","ParentID",$parentarray,"WHERE GrammarID=" . $_SESSION['grammarID']);
		if ($comments) echo "<br><br>Childlinks";
		$allchilds = array();
		foreach($childlinks as $index => $link) {
			if ($comments) echo "<br>--- Linkki - " . $link->rowID . ", conceptID:" . $link->conceptID . ", parentID:" . $link->parentID;
			$allchilds[$link->conceptID] = $link->conceptID;
			if (!isset($childarray[$link->conceptID])) {
				$childarray[$link->conceptID] = $link->conceptID;		// Tämä estää tuplien synnyn
				// $childarray[] = $link->conceptID						// Vaihtoehtoinen joka kerää myös tuplaesiintymät
			}
			$parentlinks[$link->rowID] = $link;
		}
		if (count($allchilds) == 0) {
			if ($comments) echo "<br>No more childs";			
		} else {
			$level = $level+1;
			if ($comments) echo "<br>Continuing to next level " . $level  . " - " . implode(",", $allchilds);
			ConceptsController::getChildLinksRecursive($allchilds, $childarray, $parentlinks, $level, $comments);
		}
	}
	
	
	/**
	 * 	Päivittää parametrina olevan conceptID:n parents ja parentpaths-kentät. Oletuksena on että parentlinks-taulu
	 *  Sisältää kaikki oleelliset linkit.
	 * 
	 * @param unknown $childID
	 * @param unknown $parentlinks
	 */
	private static function updateParentsAndPaths($conceptID, &$parentlinks, $saveactive, $comments = false) {
		
		if ($comments) echo "<br><br>Updating parents and paths: " . $conceptID;
		$paths = ConceptsController::getPaths($conceptID, -1, $parentlinks, $comments);
		$allparents = array();
		$directparents = array();
		$parentpaths = array();
		foreach($paths as $index => $patharray) {
			if ($comments) echo "<br>--- " . implode(":",$patharray);
			$parentpath = "";
			$previousparent = 0;
			foreach($patharray as $index2 => $parentID) {
				if ($parentID == $conceptID) {
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
		if ($saveactive) Table::updateRow('worder_concepts', $updatevalues, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		if ($comments) echo "<br>Child päivitetty concepts - ConceptID:" . $conceptID . ", conceptID:" . $conceptID;
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
		ConceptsController::getPathsRecursive($parentID, $newchildID, $currentpatharray, $pathsarray, $links, $comments);
		return $pathsarray;
	}
	
	
	// Kelataan rekursiivisesti läpi kaikki linkit, 
	private static function getPathsRecursive($parentID, $newchildID, $currentpatharray, &$pathsarray, $links, $comments = false) {
		if ($comments) echo "<br>Currentpath - " .  implode(":", $currentpatharray);
		foreach($links as $index => $link) {
			if ($comments) echo "<br>--- Checking link - " . $link->rowID . ", conceptID:" . $link->conceptID . ", parentID:" . $link->parentID;
			if ($link->conceptID == $parentID) {
				if ($comments) echo "<br>--- concept matches";				
				if ($link->parentID == 0) {
					//$currentpath = $currentpath;
					if ($comments) echo "<br>currentpath found -- " . implode(":", $currentpatharray);
					$pathsarray[] = $currentpatharray;
				} else {
					if ($link->parentID == $newchildID) {
						if ($comments) echo "<br>--- Loop exits: loop - " . $link->rowID . ", conceptID:" . $link->conceptID . ", parentID:" . $link->parentID;
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
						ConceptsController::getPathsRecursive($link->parentID, $newchildID, $newcurrentpath, $pathsarray, $links, $comments);
					}
				}
			}
		}
	}
	
	
	
	
	
	public function searchconceptAction() {
	
		$search = $_GET['search'];
		//$languageID= $_GET['languageID'];
	
		//$language = Table::loadRow('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$concepts  = Table::load("worder_concepts","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND name REGEXP '" . $search . "'");
		
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
	
			echo " {";
			echo "	  \"conceptID\":\"" . $concept->conceptID . "\",";
			echo "	  \"name\":\"" . $concept->name . "\",";
			echo "	  \"gloss\":\"" . $concept->finnish_word . "\",";
			echo "	  \"wordclassID\":\"" . $wordclass . "\",";
			echo "	  \"frequency\":\"" . $concept->frequency . "\"";
			echo " }\n";
		}
		echo "]";
		//echo "[  { \"conceptID\":\"12112\", \"name\":\"bbbb\" }, { \"conceptID\":\"1233112\" , \"name\":\"bbbaab\" }  ]";
	}
	
	
	
	

	public function searchwordsAction() {
	
		$search = $_GET['search'];
		$languageID= $_GET['languageID'];
		
		$language = Table::loadRow('worder_languages', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID);
		$words  = Table::load("worder_words","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND lemma LIKE '" . $search . "'");
		
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
			if ($word->wordclassID == 13) $wordclass = "Fraasi";
			
			echo " {";
			echo "	  \"wordID\":\"" . $word->wordID . "\",";
			echo "	  \"name\":\"" . $word->lemma . "\",";
			echo "	  \"gloss\":\"" . $word->lemma . "\",";
			echo "	  \"wordclassID\":\"" . $wordclass . "\",";
			echo "	  \"frequency\":\"" . $word->conceptID . "\"";
			echo " }\n";
		}
		echo "]";
		//echo "[  { \"conceptID\":\"12112\", \"name\":\"bbbb\" }, { \"conceptID\":\"1233112\" , \"name\":\"bbbaab\" }  ]";
	}
	
	
	
	
	// pitäisi ehkä erottaa removeconcept actioni groupsin ja lessonssien funktiosta
	public function removeconceptAction() {
	
		$comments = false;
		
		$id = $_GET['id'];

		/*
		$languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		foreach($languages as $languagename => $language) {
			
			//echo "<br>Language " . $language->active;
			
			if ($language->active == 1) {
					
				// TODO: Tämä toiminto on mahdollista toteuttaa frameworkin kautta reference tablen avulla
				global $mysqli;
				$sql = "UPDATE worder_words SET ConceptID='' WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $language->languageID . " AND ConceptID='" . $id . "'";
				//echo "<br>sql - " . $sql;
				$result = $mysqli->query($sql);
				if (!$result) echo "update failed: " . $mysqli->connect_error;
				
				
				// TODO: update _words-taulun conceptiID=0, logiin jää vanha arvo
				// TODO: poista word_links-taulusta kaikki rivit joissa conceptID -- logiin jää merkintä poistetusta
				// TODO: poista wordgouppX_words missä conceptID on -- logiin jää merkintä poistetusta
				
				
			}
		}
		*/
		
		// TODO: Poista concept arguments, poistamatta jäänti ei haittaa
		
		// pitäisikä tämä jättää? Ei ainakaan suuri ongelma vaikka jättäisi, poistamatta jättäminen ei haittaa
		// $success = Table::deleteRowsWhere("worder_conceptcomments"," WHERE ConceptID='" . $id . "'", $comments);

		$success = Table::deleteRowsWhere("worder_wordgroupconcepts"," WHERE ConceptID='" . $id . "'", $comments);
		
		$success = Table::deleteRowsWhere("worder_conceptdescriptions"," WHERE ConceptID='" . $id . "'", $comments);
		
		$success = Table::deleteRow("worder_concepts", $id, $comments);
		
		$success = Table::deleteRowsWhere("worder_conceptparentlinks"," WHERE ConceptID='" . $id . "'", $comments);
		
		$success = Table::deleteRowsWhere("worder_lessonconcepts"," WHERE ConceptID='" . $id . "'", $comments);
		
		
		// TODO: pitää päivittää myös childit... ja linkat childit roottiin, voitaisiin tietysti estää poistamasta
		// käsitettä jolla on lapsia, se olisi helpointa
		
		// TODO: tätä kutsutaan kyllä muuallakin kuin conceptslistalta, pitäisi ottaa pathissa yksi yläspäin
		if ($comments == false) redirecttotal('worder/concepts/showconcepts',null);
	}
	
}
?>