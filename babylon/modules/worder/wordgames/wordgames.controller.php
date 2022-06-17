<?php


class WordgamesController extends AbstractController {

	
	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->selectwordAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	

	public function selectwordAction() {
		$this->registry->template->show('worder/wordgames','selectword');
	}
	
	
	public function getLemma($language, $lemma) {
		
	}
	
	
	public function featurevaluesToString($values) {
		$str = "";
		foreach($values as $index => $value) {
			if ($str != "") $str = $str . ":";
			$str = $str .  $index . "=" . $value;
		}
		return $str;
	}
	
	public function featureHasParent($feature, $parentID, $features) {
		
		
		if ($feature->parentID == 0) {
			//echo "<br>ParentID nolla";
			return null;
		}
		if ($feature->parentID == $parentID) {
			//echo "<br>Parent found";
			return $feature->name;
		}
		$feature = $features[$feature->parentID];
		return $this->featureHasParent($feature, $parentID, $features);
	}
	
	
	public function compareFeatures($sourcelemma, $targetword, $sourcefeatures, $sourcewordclassID, $targetfeatures, $targetwordclassID, $features, $wordclasses, &$translations) {
		
		if ($sourcewordclassID != $targetwordclassID) {
			echo "<br>Wordclass mitchmatch";
		}
		
		$neededfeatures = $wordclasses[$sourcewordclassID]->features;

		$same = true;
		$foundparents = array();
		foreach($neededfeatures as $index=> $featureID) {
			//echo "<br>FeatureID - " . $featureID . " - " . $sourcefeatures[$featureID] . " - " . $targetfeatures[$featureID];
			if ($sourcefeatures[$featureID] == $targetfeatures[$featureID]) {
				//echo "<br>same";
			} else {
				if (($sourcefeatures[$featureID] == 0) || ($targetfeatures[$featureID] == 0)) {
					return false;
				}
				$feature = $features[$targetfeatures[$featureID]];
				//echo "<br>feature - "  . $feature->name;
				
				$foundparent = $this->featureHasParent($feature, $sourcefeatures[$featureID], $features);
				$foundparent2 = $this->featureHasParent($features[$sourcefeatures[$featureID]], $feature->featureID, $features);
				
				if (($foundparent == null) && ($foundparent2 == null)) {
					$same = false;
				} else {
					if ($foundparent != null) {
						$foundparents[] = $foundparent;
					}
				}
				
				
				if ($foundparent2 == null) {
					//$same = false;
				} else {
					//echo "<br>Foundparents 2 - " . $foundparent2;
					//$foundparents[] = $foundparent;
				}
				
				
				//$foundparent2 = $this->featureHasParent($feature, $sourcefeatures[$featureID], $features);
				
				//echo "<br>foundparent - "  . $foundparent;
				/*
				if ($foundparent == true) {
					echo "<br>parentfound";
				} else {
					echo "<br>Notsama";
					$same = false;
				}
				*/
			}
		}
		
		
		
		if ($same == true) {
			
			if (count($foundparents) > 0) {
				$newsourcelemma = $sourcelemma . " (" . implode(',',$foundparents) . ")";
				//echo "<br>Newsourcelemma - " . $newsourcelemma;
				if (!isset($translations[$newsourcelemma])) {
					$arri = array();
					$arri[$targetword->lemma] = $targetword->lemma;
					//echo "<br>Translations lemma 3 - " . $targetword->lemma;
					$translations[$newsourcelemma] = $arri;
				} else {
					$arri = $translations[$newsourcelemma];
					$arri[$targetword->lemma] = $targetword->lemma;
					//echo "<br>Translations lemma 4 - " . $targetword->lemma;
					$translations[$newsourcelemma] = $arri;
				}
			} else {
				if (!isset($translations[$sourcelemma])) {
					$arri = array();
					$arri[$targetword->lemma] = $targetword->lemma;
					//echo "<br>Translations lemma 5 - " . $targetword->lemma;
					$translations[$sourcelemma] = $arri;
				} else {
					$arri = $translations[$sourcelemma];
					$arri[$targetword->lemma] = $targetword->lemma;
					//echo "<br>Translations lemma 6 - " . $targetword->lemma;
					$translations[$sourcelemma] = $arri;
				}
			}
			
			
			//$translations[$sourcelemma] = $targetword->lemma;
		}
		//echo "<br> - " . implode(':',$sourcefeatures);
		//echo "<br> - " . implode(':', $targetfeatures);
		
		return $same;
	}

	

	public function selectwordquestionAction() {
	
		$sourceLang = 2;
		$targetLang = 1;
		$sourceLanguage = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $sourceLang);
		$targetLanguage = Table::loadRow("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $targetLang);
		$wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);
		
		
		unset($_SESSION['wordselection-' . $sourceLang]);
		if (!isset($_SESSION['wordselection-' . $sourceLang])) {
			
			// TODO: tällähetkellä ladataan kaikki sanat, tulevaisuudessa pitäisi ladata vain sanat
			// jotka ovat tietyllä käyttäjällä käytässä (=opittuna) + sitten uusi opeteltava sanajoukko.
			$wordselection = array();
			$wordcounts = array();
			$words = Table::load("worder_words", "WHERE GrammarID=" . $_SESSION['grammarID']);
			
			$featurevalues = array();
			$lemmawordclasses = array();
				
			foreach($words as $index => $word) {
				$lemma = $word->lemma;
				$pos = strpos($lemma, " (");
				//echo "<br>pos - " . $pos;
				if ($pos > 0) {
					$lemma = substr($lemma, 0, $pos);
				}
				//echo "<br>Word - " . $word->lemma . " , '" . $lemma . "' - " . $this->featurevaluesToString($word->featurevalues);
				
				if (isset($wordselection[$lemma])) {
					$concepts = $wordselection[$lemma];
					//echo "<br>existing featurevaleus - " . $this->featurevaluesToString($word->featurevalues);
					//$concepts[] = $word->conceptID . "|" . $this->featurevaluesToString($word->featurevalues);
					$concepts[] = $word->conceptID;
					$featurevalues[$lemma . "-" . $word->conceptID] = $word->featurevalues;
					$lemmawordclasses[$lemma . "-" . $word->conceptID] = $word->wordclassID;
					$wordselection[$lemma] = $concepts;
					$wordselection[$lemma] = $concepts;
				} else {
					$concepts = array();
					//echo "<br>new featurevaleus - " . $this->featurevaluesToString($word->featurevalues);
					//$concepts[] = $word->conceptID . "|" . $this->featurevaluesToString($word->featurevalues);
					$concepts[] = $word->conceptID;
					$featurevalues[$lemma . "-" . $word->conceptID] = $word->featurevalues;
					$lemmawordclasses[$lemma . "-" . $word->conceptID] = $word->wordclassID;
					$wordselection[$lemma] = $concepts;
				}
				$wordcounts[$lemma] = 0;
			}
			
			$_SESSION['wordcounts-' . $sourceLang] = $wordcounts;
			$_SESSION['wordselection-' . $sourceLang] = $wordselection;
			
			$targetwords = Table::load("worder_words", "WHERE GrammarID=" . $_SESSION['grammarID']);
			$translations = array();
			
			foreach($wordselection as $lemma => $concepts) {
				
				//$sourceconcepts = explode(',', $concepts);
				//echo "<br>Concepts - " . $lemma . " - " . implode('--', $concepts);
				$foundtranslations = array();
				
				foreach($concepts as $index1 => $conceptID) {
					
					//$data = explode("|",$value);
					//$conceptID = $data[0];
					//echo "<br>Searching conceptID - " . $conceptID;
					//echo "<br>Searching key - " . $lemma . "-" . $conceptID;
						
					if (count($featurevalues[$lemma . "-" . $conceptID]) > 0) {
						//echo "<br>Forms " . $lemma . "-" . $conceptID . " - "  .  implode(':',$featurevalues[$lemma . "-" . $conceptID]);	
						
						foreach ($targetwords as $index2 => $word) {
							if ($word->conceptID == $conceptID) {
								//echo "<br>compare - " . $word->lemma;
								
								$compare = $this->compareFeatures($lemma, $word, $featurevalues[$lemma . "-" . $conceptID], $lemmawordclasses[$lemma . "-" . $conceptID], $word->featurevalues, $word->wordclassID, $features, $wordclasses, $translations);
								if ($compare == 1) {
									//echo "<br>Found-a - " . $word->lemma;
									$targetlemma = $word->lemma;
									$pos = strpos($targetlemma, " (");
									//echo "<br>pos - " . $pos;
									if ($pos > 0) {
										$targetlemma = substr($targetlemma, 0, $pos);
									}
									//$foundtranslations[$targetlemma] = $targetlemma;	
								} else {
									//echo "<br>fearures doesn't match";
								}
							}
						}
					} else {
						foreach ($targetwords as $index3 => $word) {
							if ($word->conceptID == $conceptID) {
								
								$targetlemma = $word->lemma;
								$pos = strpos($targetlemma, " (");
								//echo "<br>pos - " . $pos;
								if ($pos > 0) {
									$targetlemma = substr($targetlemma, 0, $pos);
								}
								if (!isset($translations[$lemma])) {
									$arri = array();
									$arri[$targetlemma] = $targetlemma;
									$translations[$lemma] = $arri;
									//echo "<br>Translations lemma 1 - " . $lemma;
								} else {
									$arri = $translations[$lemma];
									$arri[$targetlemma] = $targetlemma;
									//echo "<br>Translations lemma 2 - " . $lemma;
									$translations[$lemma] = $arri;
								}
								//$foundtranslations[$targetlemma] = $targetlemma;	
								//echo "<br>Found-b - " . $word->lemma;
							}
						}
					}
				}
				//$translations[$lemma] = implode(',', $foundtranslations);
				//echo "<br><br>";
			}
			
			$_SESSION['wordtranslations-' . $sourceLang] = $translations;
				
		} 
		
		echo "<br>-------------------------";
		
		$wordcounts = $_SESSION['wordcounts-' . $sourceLang];
		$wordselection  = $_SESSION['wordselection-' . $sourceLang];
		echo "<br><br>wordcount - " . count($translations);		
		$max = 1;
		$counter = 1;
		foreach($translations as $index => $value) {
			echo "<br>" . $counter . " - index[" . $index . "] - " . implode(',',$translations[$index]);
			$count = count($translations[$index]);
			//echo "<br>Count - " . $count;
			if ($count > $max) $max = $count;
			$counter++;
		}
		$max = 2 * $max;		// 50% odsit kysyä samoja
		echo "<br><br>Max - " . $max;
		$count = 0;
		foreach($wordcounts as $index => $value) {
			$count = $count + $max - $value;
		}
		echo "<br>Count - "  . $count;
		
		$randi = rand(1,76);
		echo "<br>Randi - "  . $randi;
		$count = 0;
		foreach($wordcounts as $index => $value) {
			$count = $count + $max - $value;
			if ($count > $randi) break;
		}
		echo "<br>Selected - " . $index;
		
		// haetaan kaikki sanat joiden sanan perusmuoto on satunnaisesti valittu lemma	
		//$lemmas = $this->getLemma($language, $lemma, $words);
		
		// haetaan kaikki haettujen sanojen käsitteet
		
		// haetaan kaikki haettujen käsitteiden käännäkset
		
		
		
		
		$question = 'Koti';
		$options[] = "aaa";
		$options[] = "bbb";
		$options[] = "ccc";
		$options[] = "ddd";
		$answer = "4";
		
				
		echo "{";
		echo "	\"question\":\"" . $question . "\",";
		echo "	\"answer\":\"" . $answer . "\",";
		echo "	\"options\":[ ";
		$first = false;
		foreach($options as $index => $value) {
			if ($first == false) {
				$first = true;
			} else {
				echo ",";
			}
			echo "\"" . $value . "\"";
			//echo " { \"". $index . "\":\"" . $value . "\" } ";
		}
		echo "  ]";
		echo "}";
		
	}
	

	
	
	
}
