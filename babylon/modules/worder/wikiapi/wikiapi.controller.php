<?php


class WikiApiController extends AbstractController {
	
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
	
	

	public function getenglishnounsAction() {
		
		$comments = false;
		$grammarID = 1;
		$languageID = 2;
		$wordclassID = 1;
		
		$words = Table::load("worder_words", "WHERE WordclassID=" . $wordclassID . " AND LanguageID=" . $languageID . " AND GrammarID=" . $grammarID);
		$concepts = Table::load("worder_concepts", "WHERE WordclassID=" . $wordclassID . " AND GrammarID=" . $grammarID);
		$links = Table::load("worder_conceptwordlinks", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . " AND Defaultword=1");
		$wordforms = Table::load("worder_wordforms", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . " AND WordclassID=" . $wordclassID . " AND (Features='132' OR Features='132:458')");

		$wordformsbyid = array();
		foreach($wordforms as $index => $wordform) {
			$wordformsbyid[$wordform->wordID] = $wordform;
		}
		
		if ($comments) echo "<br>Conceptcount - " . count($concepts);
		foreach($words as $index => $word) {
			$word->conceptID = 0;
		}
		
		$counter = 0;
		$finalwords = array();
		$wordarray = array();
		foreach($links as $index => $link) {
			if (isset($words[$link->wordID])) {
				$word = $words[$link->wordID];
				if (isset($concepts[$link->conceptID])) {
					if ($comments) echo "<br>Word - "  . $word->lemma;
					$concept = $concepts[$link->conceptID];
					if ($concept->wordclassID == $wordclassID) {
						
						if (!isset($finalwords[$word->lemma])) {
							$finalwords[$word->lemma] = 0;
							$wordarray[$word->lemma] = $word;
						} else {
							$finalwords[$word->lemma] = $finalwords[$word->lemma] + 1;
						}
						if ($word->conceptID>0 || $concept->wordID>0) {
							if (isset($word->conceptID)) {
								if ($comments) echo "<br>ConceptID already setted for - " . $word->lemma . " - " . $word->wordID;
							} else {
								if ($comments) echo "<br>WordID already setted for - " . $word->lemma . " - " . $word->wordID;
								$word->conceptID = $concept->conceptID;
								$concept->wordID = $word->wordID;
								$words[$word->wordID] = $word;
								$concepts[$concept->conceptID] = $concept;
								$finalwords[$word->lemma] = $word;
								$counter++;
								
							}
						} else {
							$word->conceptID = $concept->conceptID;
							$concept->wordID = $word->wordID;
							$words[$word->wordID] = $word;
							$concepts[$concept->conceptID] = $concept;
							$finalwords[$word->lemma] = $word;
							$counter++;
						}
					}
				} else {
					if ($comments) echo "<br>No concept found - " . $link->conceptID;
				}
			} else {
				if ($comments) echo "<br>No word found - " . $link->wordID;
			}
		}

		$finalcount = 0;
		$counter = 0;
		foreach($finalwords as $index => $count) {
			$finalcount = $finalcount + 1;
			//if ($count > 1) echo "<br>finalcount over2 - " . $index;
			//echo "<br>" . $counter . "..." . $index;
			
			
			
			$word = $wordarray[$index];
			//echo "<br>..." . $word->lemma . "..." . $word->hyphenationbase . " ... " . $word->wordID;;

			if (strpos($word->hyphenationbase, '(') !== false) {
				if ($comments) echo "<br>..." . $word->lemma . "..." . $word->hyphenationbase . " ... " . $word->wordID;;
			}
			
			
			if ($word->hyphenationbase == '') {
				if ($comments) echo "<br>..." . $word->lemma . "..." . $word->hyphenationbase . " ... " . $word->wordID;;
			}
		}
		if ($comments) echo "<br>Finalcount - " . $finalcount;
		if ($comments) echo "<br>Counter - " . $counter;
		if ($comments) echo "<br>Counter - " . count($finalwords);
		if ($comments) echo "<br>Counter - " . count($wordformsbyid);
		
		foreach($wordformsbyid as $index => $form) {
			//echo "<br> -- isset " . $index . " - " . $form->rowID;
				
		}
		
		$finalresult = array();
		
		foreach($finalwords as $index => $word) {
			///echo "<br> -- " . $word->lemma . " - " . $word->wordID;
			error_reporting(E_ALL ^ E_DEPRECATED);
			//echo "<br> -- isset " . $wordformsbyid[$word->wordID];
				
			if ($wordformsbyid[$word->wordID] == null) {
				if ($comments) echo "<br> -- nulli " . $word->wordID;
			} else {
				$wordform = $wordformsbyid[$word->wordID];
				if ($comments) echo "<br> ---- " . $word->lemma . " - wordID:" . $word->wordID . " - conceptID:" . $word->conceptID . " --- " . $wordform->wordform;
				$word->form = $wordform->wordform;
				$finalresult[$word->lemma] = $word;
			}
			
		}
		
		
		if ($comments) echo "<br>Finalcount - " . count($finalresult);
		if ($comments) echo "<br><br>";
		
		echo "[";
		$first = true;
		foreach($finalresult as $index => $word) {
			if ($first == false) echo ",";
			else $first = false;
			echo "{";
			echo "\"wordID\":\"" . $word->wordID . "\",";
			echo "\"conceptID\":\"" . $word->conceptID . "\",";
			echo "\"name\":\"" . $word->form . "\",";
			echo "\"languageID\":\"" . $word->languageID . "\"";
			echo "}";
		}
		echo "]";
	}
	
	
	public function getwordclassconceptsAction() {
	    
	    $comments = false;
	    $grammarID = 1;
	    $languageID = 2;
	    
	    $wordclassID = 0;
	    if (isset($_GET['wordclassID'])) {
	        $wordclassID = $_GET['wordclassID'];
	    } else {
            echo "<br>getwordclassconcepts - wordclassID needed";
            exit;
	    }
        	  
	    $featurestr = null;
	    if ($wordclassID == 1) {
	       $featurestr = "(Features='132' OR Features='132:458')";
	    }
	    if ($wordclassID == 2) {
	       $featurestr = "Features='127'"; 
	    }
	    if ($wordclassID == 3) {
	        $featurestr = "Features='259'";
	    }
	    if ($featurestr == null) {
	        echo "<br>Unknown wordclass - "  . $wordclassID;
	        exit;
	    }
	    
	    $words = Table::load("worder_words", "WHERE WordclassID=" . $wordclassID . " AND LanguageID=" . $languageID . " AND GrammarID=" . $grammarID);
	    if ($comments) echo "<br>words - " . count($words);
	    $concepts = Table::load("worder_concepts", "WHERE WordclassID=" . $wordclassID . " AND GrammarID=" . $grammarID);
	    $links = Table::load("worder_conceptwordlinks", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . " AND Defaultword=1");
	    $wordforms = Table::load("worder_wordforms", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . " AND WordclassID=" . $wordclassID . " AND " . $featurestr);
	    
	    $wordformsbyid = array();
	    foreach($wordforms as $index => $wordform) {
	        $wordformsbyid[$wordform->wordID] = $wordform;
	    }
	    
	    if ($comments) echo "<br>Conceptcount - " . count($concepts);
	    foreach($words as $index => $word) {
	        $word->conceptID = 0;
	    }
	    
	    $counter = 0;
	    $finalwords = array();
	    $wordarray = array();
	    foreach($links as $index => $link) {
	        if (isset($words[$link->wordID])) {
	            $word = $words[$link->wordID];
	            if (isset($concepts[$link->conceptID])) {
	                if ($comments) echo "<br>Word - "  . $word->lemma;
	                $concept = $concepts[$link->conceptID];
	                if ($concept->wordclassID == $wordclassID) {
	                    
	                    if (!isset($finalwords[$word->lemma])) {
	                        $finalwords[$word->lemma] = 0;
	                        $wordarray[$word->lemma] = $word;
	                    } else {
	                        $finalwords[$word->lemma] = $finalwords[$word->lemma] + 1;
	                    }
	                    if ($word->conceptID>0 || $concept->wordID>0) {
	                        if (isset($word->conceptID)) {
	                            if ($comments) echo "<br>ConceptID already setted for - " . $word->lemma . " - " . $word->wordID;
	                        } else {
	                            if ($comments) echo "<br>WordID already setted for - " . $word->lemma . " - " . $word->wordID;
	                            $word->conceptID = $concept->conceptID;
	                            $concept->wordID = $word->wordID;
	                            $words[$word->wordID] = $word;
	                            $concepts[$concept->conceptID] = $concept;
	                            $finalwords[$word->lemma] = $word;
	                            $counter++;
	                            
	                        }
	                    } else {
	                        $word->conceptID = $concept->conceptID;
	                        $concept->wordID = $word->wordID;
	                        $words[$word->wordID] = $word;
	                        $concepts[$concept->conceptID] = $concept;
	                        $finalwords[$word->lemma] = $word;
	                        $counter++;
	                    }
	                }
	            } else {
	                if ($comments) echo "<br>No concept found - " . $link->conceptID;
	            }
	        } else {
	            if ($comments) echo "<br>No word found - " . $link->wordID;
	        }
	    }
	    
	    $finalcount = 0;
	    $counter = 0;
	    foreach($finalwords as $index => $count) {
	        $finalcount = $finalcount + 1;
	        //if ($count > 1) echo "<br>finalcount over2 - " . $index;
	        //echo "<br>" . $counter . "..." . $index;
	        
	        
	        
	        $word = $wordarray[$index];
	        //echo "<br>..." . $word->lemma . "..." . $word->hyphenationbase . " ... " . $word->wordID;;
	        
	        if (strpos($word->hyphenationbase, '(') !== false) {
	            if ($comments) echo "<br>..." . $word->lemma . "..." . $word->hyphenationbase . " ... " . $word->wordID;;
	        }
	        
	        
	        if ($word->hyphenationbase == '') {
	            if ($comments) echo "<br>..." . $word->lemma . "..." . $word->hyphenationbase . " ... " . $word->wordID;;
	        }
	    }
	    if ($comments) echo "<br>Finalcount - " . $finalcount;
	    if ($comments) echo "<br>Counter - " . $counter;
	    if ($comments) echo "<br>Counter - " . count($finalwords);
	    if ($comments) echo "<br>Counter - " . count($wordformsbyid);
	    
	    foreach($wordformsbyid as $index => $form) {
	        //echo "<br> -- isset " . $index . " - " . $form->rowID;
	        
	    }
	    
	    $finalresult = array();
	    
	    foreach($finalwords as $index => $word) {
	        ///echo "<br> -- " . $word->lemma . " - " . $word->wordID;
	        error_reporting(E_ALL ^ E_DEPRECATED);
	        //echo "<br> -- isset " . $wordformsbyid[$word->wordID];
	        
	        if ($wordformsbyid[$word->wordID] == null) {
	            if ($comments) echo "<br> -- nulli " . $word->wordID;
	        } else {
	            $wordform = $wordformsbyid[$word->wordID];
	            if ($comments) echo "<br> ---- " . $word->lemma . " - wordID:" . $word->wordID . " - conceptID:" . $word->conceptID . " --- " . $wordform->wordform;
	            $word->form = $wordform->wordform;
	            $finalresult[$word->lemma] = $word;
	        }
	        
	    }
	    
	    
	    if ($comments) echo "<br>Finalcount - " . count($finalresult);
	    if ($comments) echo "<br><br>";
	    
	    echo "[";
	    $first = true;
	    foreach($finalresult as $index => $word) {
	        if ($first == false) echo ",";
	        else $first = false;
	        echo "{";
	        echo "\"wordID\":\"" . $word->wordID . "\",";
	        echo "\"conceptID\":\"" . $word->conceptID . "\",";
	        echo "\"name\":\"" . $word->form . "\",";
	        echo "\"languageID\":\"" . $word->languageID . "\"";
	        echo "}";
	    }
	    echo "]";
	}
	
	
	
	
	public function getenglishnounswithdefinitionsAction() {
	    
	    $comments = false;
	    $grammarID = 1;
	    $languageID = 2;
	    $wordclassID = 1;
	    
	    $words = Table::load("worder_words", "WHERE WordclassID=" . $wordclassID . " AND LanguageID=" . $languageID . " AND GrammarID=" . $grammarID);
	    $concepts = Table::load("worder_concepts", "WHERE WordclassID=" . $wordclassID . " AND GrammarID=" . $grammarID);
	    $links = Table::load("worder_conceptwordlinks", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . " AND Defaultword=1");
	    $wordforms = Table::load("worder_wordforms", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . " AND WordclassID=" . $wordclassID . " AND (Features='132' OR Features='132:458')");
	    $definitions = Table::load("worder_definitions", "WHERE WordclassID=" . $wordclassID . " AND GrammarID=" . $grammarID . " AND SourceID=1");
	    
	    $wordformsbyid = array();
	    foreach($wordforms as $index => $wordform) {
	        $wordformsbyid[$wordform->wordID] = $wordform;
	    }
	    
	    if ($comments) echo "<br>Conceptcount - " . count($concepts);
	    foreach($words as $index => $word) {
	        $word->conceptID = 0;
	    }
	    
	    $counter = 0;
	    $finalwords = array();
	    $wordarray = array();
	    foreach($links as $index => $link) {
	        if (isset($words[$link->wordID])) {
	            $word = $words[$link->wordID];
	            if (isset($concepts[$link->conceptID])) {
	                if ($comments) echo "<br>Word - "  . $word->lemma;
	                $concept = $concepts[$link->conceptID];
	                if ($concept->wordclassID == $wordclassID) {
	                    
	                    if (!isset($finalwords[$word->lemma])) {
	                        $finalwords[$word->lemma] = 0;
	                        $wordarray[$word->lemma] = $word;
	                    } else {
	                        $finalwords[$word->lemma] = $finalwords[$word->lemma] + 1;
	                    }
	                    if ($word->conceptID>0 || $concept->wordID>0) {
	                        if (isset($word->conceptID)) {
	                            if ($comments) echo "<br>ConceptID already setted for - " . $word->lemma . " - " . $word->wordID;
	                        } else {
	                            if ($comments) echo "<br>WordID already setted for - " . $word->lemma . " - " . $word->wordID;
	                            $word->conceptID = $concept->conceptID;
	                            $concept->wordID = $word->wordID;
	                            $words[$word->wordID] = $word;
	                            $concepts[$concept->conceptID] = $concept;
	                            $finalwords[$word->lemma] = $word;
	                            $counter++;
	                            
	                        }
	                    } else {
	                        $word->conceptID = $concept->conceptID;
	                        $concept->wordID = $word->wordID;
	                        $words[$word->wordID] = $word;
	                        $concepts[$concept->conceptID] = $concept;
	                        $finalwords[$word->lemma] = $word;
	                        $counter++;
	                    }
	                }
	            } else {
	                if ($comments) echo "<br>No concept found - " . $link->conceptID;
	            }
	        } else {
	            if ($comments) echo "<br>No word found - " . $link->wordID;
	        }
	    }
	    
	    $finalcount = 0;
	    $counter = 0;
	    foreach($finalwords as $index => $count) {
	        $finalcount = $finalcount + 1;
	        //if ($count > 1) echo "<br>finalcount over2 - " . $index;
	        //echo "<br>" . $counter . "..." . $index;
	        
	        
	        
	        $word = $wordarray[$index];
	        //echo "<br>..." . $word->lemma . "..." . $word->hyphenationbase . " ... " . $word->wordID;;
	        
	        if (strpos($word->hyphenationbase, '(') !== false) {
	            if ($comments) echo "<br>..." . $word->lemma . "..." . $word->hyphenationbase . " ... " . $word->wordID;;
	        }
	        
	        
	        if ($word->hyphenationbase == '') {
	            if ($comments) echo "<br>..." . $word->lemma . "..." . $word->hyphenationbase . " ... " . $word->wordID;;
	        }
	    }
	    if ($comments) echo "<br>Finalcount - " . $finalcount;
	    if ($comments) echo "<br>Counter - " . $counter;
	    if ($comments) echo "<br>Counter - " . count($finalwords);
	    if ($comments) echo "<br>Counter - " . count($wordformsbyid);
	    
	    foreach($wordformsbyid as $index => $form) {
	        //echo "<br> -- isset " . $index . " - " . $form->rowID;
	        
	    }
	    
	    $finalresult = array();
	    
	    foreach($finalwords as $index => $word) {
	        ///echo "<br> -- " . $word->lemma . " - " . $word->wordID;
	        error_reporting(E_ALL ^ E_DEPRECATED);
	        //echo "<br> -- isset " . $wordformsbyid[$word->wordID];
	        
	        if ($wordformsbyid[$word->wordID] == null) {
	            if ($comments) echo "<br> -- nulli " . $word->wordID;
	        } else {
	            $wordform = $wordformsbyid[$word->wordID];
	            if ($comments) echo "<br> ---- " . $word->lemma . " - wordID:" . $word->wordID . " - conceptID:" . $word->conceptID . " --- " . $wordform->wordform;
	            $word->form = $wordform->wordform;
	            $finalresult[$word->lemma] = $word;
	        }
	    }

	    $descriptionsByConceptID = array();
	    $shortdescriptionByConceptID = array();
	    
	    foreach($definitions as $index => $definition) {
	        if ($definition->definitiontype == 0) {
	            $descriptionsByConceptID[$definition->conceptID] = $definition->definitionID;
	        }
	        if ($definition->definitiontype == 1) {
	            $shortdescriptionByConceptID[$definition->conceptID] = $definition->definitionID;
	        }
	    }
	    
	    
	    if ($comments) echo "<br>Finalcount - " . count($finalresult);
	    if ($comments) echo "<br><br>";
	    
	    echo "[";
	    $first = true;
	    $counter = 0;
	    foreach($finalresult as $index => $word) {
	        if ($first == false) echo ",";
	        else $first = false;
	        echo "{";
	        echo "\"wordID\":\"" . $word->wordID . "\",";
	        echo "\"conceptID\":\"" . $word->conceptID . "\",";
	        echo "\"name\":\"" . $word->form . "\",";
	        
	        if (isset($descriptionsByConceptID[$word->conceptID])) {
	            $definitionID = $descriptionsByConceptID[$word->conceptID];
	            $definition = $definitions[$definitionID];
	            echo "\"description\":\"" . $definition->definition . "\",";
	            echo "\"descriptionindex\":\"" . $definition->definitionindex . "\",";
	        } else {
	            echo "\"description\":\"\",";
	            echo "\"descriptionindex\":\"0\",";
	        }

	        if (isset($shortdescriptionByConceptID[$word->conceptID])) {
	            $definitionID = $shortdescriptionByConceptID[$word->conceptID];
	            $definition = $definitions[$definitionID];
	            echo "\"shortdesc\":\"" . $definition->definition . "\",";
	            echo "\"shortdescindex\":\"" . $definition->definitionindex . "\",";
	        } else {
	            echo "\"shortdesc\":\"\",";
	            echo "\"shortdescindex\":\"0\",";
	        }
	        
	        echo "\"languageID\":\"" . $word->languageID . "\"";
	        echo "}";
	        $counter++;
	        //if ($counter > 5) break;
	    }
	    echo "]";
	}
	
	
	
	
	
	/*
	 * Sama kuin edellinen, mutta otetaan mukaan vain sanat jotka ovat kiinnitetty lessoneihin. Sanoja lessoneissa on varmaan
	 * paljon vähemmän kuin kokonaisssanasto ja ainakin ne pitäisi asettaa prioriteetiksi käännöksiä asetettaessa, mielellään
	 * vielä lessoneiden järjestyksen mukaisessa järjestyksessä.
	 * 
	 */
	public function getenglishnounswithdefinitionsinlessonsAction() {
	    
	    $comments = false;
	    $grammarID = 1;
	    $languageID = 2;
	    $wordclassID = 1;
	    
	    $words = Table::load("worder_words", "WHERE WordclassID=" . $wordclassID . " AND LanguageID=" . $languageID . " AND GrammarID=" . $grammarID);
	    $concepts = Table::load("worder_concepts", "WHERE WordclassID=" . $wordclassID . " AND GrammarID=" . $grammarID);
	    $links = Table::load("worder_conceptwordlinks", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . " AND Defaultword=1");
	    $wordforms = Table::load("worder_wordforms", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . " AND WordclassID=" . $wordclassID . " AND (Features='132' OR Features='132:458')");
	    $definitions = Table::load("worder_definitions", "WHERE WordclassID=" . $wordclassID . " AND GrammarID=" . $grammarID . " AND SourceID=1");
	    $lessonlinks = Table::load("worder_lessonconcepts", "WHERE GrammarID=" . $grammarID . " AND LessonID IN (1,2,4,5,210,167,33,76,34,22)");
	    //echo "<br>Lessonlinks - " . count($lessonlinks);
	    
	    $wordformsbyid = array();
	    foreach($wordforms as $index => $wordform) {
	        $wordformsbyid[$wordform->wordID] = $wordform;
	    }
	    
	    if ($comments) echo "<br>Conceptcount - " . count($concepts);
	    foreach($words as $index => $word) {
	        $word->conceptID = 0;
	    }
	    
	    $counter = 0;
	    $finalwords = array();
	    $wordarray = array();
	    foreach($links as $index => $link) {
	        if (isset($words[$link->wordID])) {
	            $word = $words[$link->wordID];
	            if (isset($concepts[$link->conceptID])) {
	                if ($comments) echo "<br>Word - "  . $word->lemma;
	                $concept = $concepts[$link->conceptID];
	                if ($concept->wordclassID == $wordclassID) {
	                    
	                    if (!isset($finalwords[$word->lemma])) {
	                        $finalwords[$word->lemma] = 0;
	                        $wordarray[$word->lemma] = $word;
	                    } else {
	                        $finalwords[$word->lemma] = $finalwords[$word->lemma] + 1;
	                    }
	                    if ($word->conceptID>0 || $concept->wordID>0) {
	                        if (isset($word->conceptID)) {
	                            if ($comments) echo "<br>ConceptID already setted for - " . $word->lemma . " - " . $word->wordID;
	                        } else {
	                            if ($comments) echo "<br>WordID already setted for - " . $word->lemma . " - " . $word->wordID;
	                            $word->conceptID = $concept->conceptID;
	                            $concept->wordID = $word->wordID;
	                            $words[$word->wordID] = $word;
	                            $concepts[$concept->conceptID] = $concept;
	                            $finalwords[$word->lemma] = $word;
	                            $counter++;
	                            
	                        }
	                    } else {
	                        $word->conceptID = $concept->conceptID;
	                        $concept->wordID = $word->wordID;
	                        $words[$word->wordID] = $word;
	                        $concepts[$concept->conceptID] = $concept;
	                        $finalwords[$word->lemma] = $word;
	                        $counter++;
	                    }
	                }
	            } else {
	                if ($comments) echo "<br>No concept found - " . $link->conceptID;
	            }
	        } else {
	            if ($comments) echo "<br>No word found - " . $link->wordID;
	        }
	    }
	    
	    $finalcount = 0;
	    $counter = 0;
	    foreach($finalwords as $index => $count) {
	        $finalcount = $finalcount + 1;
	        //if ($count > 1) echo "<br>finalcount over2 - " . $index;
	        //echo "<br>" . $counter . "..." . $index;
	        
	        
	        
	        $word = $wordarray[$index];
	        //echo "<br>..." . $word->lemma . "..." . $word->hyphenationbase . " ... " . $word->wordID;;
	        
	        if (strpos($word->hyphenationbase, '(') !== false) {
	            if ($comments) echo "<br>..." . $word->lemma . "..." . $word->hyphenationbase . " ... " . $word->wordID;;
	        }
	        
	        
	        if ($word->hyphenationbase == '') {
	            if ($comments) echo "<br>..." . $word->lemma . "..." . $word->hyphenationbase . " ... " . $word->wordID;;
	        }
	    }
	    if ($comments) echo "<br>Finalcount - " . $finalcount;
	    if ($comments) echo "<br>Counter - " . $counter;
	    if ($comments) echo "<br>Counter - " . count($finalwords);
	    if ($comments) echo "<br>Counter - " . count($wordformsbyid);
	    
	    $lessonconcepts = array();
	    foreach($lessonlinks as $index => $link) {
	        //echo "<br> -- isset " . $index . " - " . $form->rowID;
	        $lessonconcepts[$link->conceptID] = 1;
	    }
	    $finalresult = array();
	    foreach($finalwords as $index => $word) {
	        ///echo "<br> -- " . $word->lemma . " - " . $word->wordID;
	        error_reporting(E_ALL ^ E_DEPRECATED);
	        //echo "<br> -- isset " . $wordformsbyid[$word->wordID];
	        
	        if ($wordformsbyid[$word->wordID] == null) {
	            if ($comments) echo "<br> -- nulli " . $word->wordID;
	        } else {
	            $wordform = $wordformsbyid[$word->wordID];
	            if ($comments) echo "<br> ---- " . $word->lemma . " - wordID:" . $word->wordID . " - conceptID:" . $word->conceptID . " --- " . $wordform->wordform;
	            $word->form = $wordform->wordform;
	            
	            if (isset($lessonconcepts[$word->conceptID])) {
	                $finalresult[$word->lemma] = $word;
	            }
	        }
	    }
	    
	    $descriptionsByConceptID = array();
	    $shortdescriptionByConceptID = array();
	    
	    foreach($definitions as $index => $definition) {
	        if ($definition->definitiontype == 0) {
	            $descriptionsByConceptID[$definition->conceptID] = $definition->definitionID;
	        }
	        if ($definition->definitiontype == 1) {
	            $shortdescriptionByConceptID[$definition->conceptID] = $definition->definitionID;
	        }
	    }
	    
	    
	    if ($comments) echo "<br>Finalcount - " . count($finalresult);
	    if ($comments) echo "<br><br>";
	    
	    echo "[";
	    $first = true;
	    $counter = 0;
	    foreach($finalresult as $index => $word) {
	        if ($first == false) echo ",";
	        else $first = false;
	        echo "{";
	        echo "\"wordID\":\"" . $word->wordID . "\",";
	        echo "\"conceptID\":\"" . $word->conceptID . "\",";
	        echo "\"name\":\"" . $word->form . "\",";
	        
	        if (isset($descriptionsByConceptID[$word->conceptID])) {
	            $definitionID = $descriptionsByConceptID[$word->conceptID];
	            $definition = $definitions[$definitionID];
	            echo "\"description\":\"" . $definition->definition . "\",";
	            echo "\"descriptionindex\":\"" . $definition->definitionindex . "\",";
	        } else {
	            echo "\"description\":\"\",";
	            echo "\"descriptionindex\":\"0\",";
	        }
	        
	        if (isset($shortdescriptionByConceptID[$word->conceptID])) {
	            $definitionID = $shortdescriptionByConceptID[$word->conceptID];
	            $definition = $definitions[$definitionID];
	            echo "\"shortdesc\":\"" . $definition->definition . "\",";
	            echo "\"shortdescindex\":\"" . $definition->definitionindex . "\",";
	        } else {
	            echo "\"shortdesc\":\"\",";
	            echo "\"shortdescindex\":\"0\",";
	        }
	        
	        echo "\"languageID\":\"" . $word->languageID . "\"";
	        echo "}";
	        $counter++;
	        //if ($counter > 5) break;
	    }
	    echo "]";
	    
	    //echo "<br><br>Finalresult - " . count($finalresult);   // 953
	}
	
	
	
	/**
	 *  Sama kuin edellinen, mutta spanishstate ja espanjan käännökset mukana 
	 *  - 
	 * 
	 */
	public function getspanishnounconceptsAction() {
	    
	    $comments = false;
	    $grammarID = 1;
	    $languageID = 27;      // espanja
	    $wordclassID = 1;      // noun
	    
	    
	    //$words = Table::load("worder_words", "WHERE WordclassID=" . $wordclassID . " AND LanguageID=" . $languageID . " AND GrammarID=" . $grammarID);
	    //$concepts = Table::load("worder_concepts", "WHERE WordclassID=" . $wordclassID . " AND GrammarID=" . $grammarID);
	    //$links = Table::load("worder_conceptwordlinks", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . " AND Defaultword=1");
	    //$wordforms = Table::load("worder_wordforms", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . " AND WordclassID=" . $wordclassID . " AND (Features='132' OR Features='132:458')");
	    //$definitions = Table::load("worder_definitions", "WHERE WordclassID=" . $wordclassID . " AND GrammarID=" . $grammarID . " AND SourceID=1");
	    $lessonlinks = Table::load("worder_lessonconcepts", "WHERE GrammarID=" . $grammarID . " AND LessonID IN (1,2,4,5,210,167,33,76,34,22)");
	    
	    if ($comments) echo "<br>lessonconcepts - " . count($lessonlinks);
	    
	    $conceptlist = array();
	    foreach($lessonlinks as $index => $link) {
	        $conceptlist[$link->conceptID] = $link->conceptID;
	    }
	    
	    $concepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
	    if ($comments) echo "<br>concepts - " . count($concepts);
	    
	    $conceptlist = array();
	    foreach($concepts as $index => $concept) {
	        //echo "<br>Concept - " . $concept->conceptID . " wordclass: " . $concept->wordclassID . " vs. " . $wordclassID;
	        if ($concept->wordclassID == $wordclassID) {
	            $conceptlist[$concept->conceptID] = $concept->conceptID;
	        }
	    }
	    if ($comments) echo "<br>conceptlist - " . count($conceptlist);
	    
	    $wordlinks = Table::loadWhereInArray('worder_conceptwordlinks', 'ConceptID', $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
	    if ($comments) echo "<br>conceptwordlinks - " . count($wordlinks);
	    
	    $wordlist = array();
	    foreach($wordlinks as $index => $link) {
	       $wordlist[$link->wordID] = $link->wordID;
	    }
	    $words = Table::loadWhereInArray('worder_words', 'WordID', $wordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
	    if ($comments) echo "<br>words - " . count($words);
	    
	    $translations = array();
	    $spanishtranslations = array();
	    foreach($wordlinks as $index => $link) {
	        $word = $words[$link->wordID];
	        
	        if (isset($translations[$link->conceptID])) {
	            $str = $translations[$link->conceptID];
	            $str = $str . "," . $word->lemma;
	            $translations[$link->conceptID] = $str;
	        } else {
	            $str = $word->lemma;
	            $translations[$link->conceptID] = $str;
	        }
	        
	        if ($word->languageID == $languageID) {
	            if (isset($spanishtranslations[$link->conceptID])) {
	                $str = $spanishtranslations[$link->conceptID];
	                $str = $str . "," . $word->lemma;
	                $spanishtranslations[$link->conceptID] = $str;
	            } else {
	                $str = $word->lemma;
	                $spanishtranslations[$link->conceptID] = $str;
	            }
	        }
	    }
	    if ($comments) echo "<br>translations - " . count($translations);
	    if ($comments) echo "<br>spanishtranslations - " . count($spanishtranslations);
	    
        $definitionsall = Table::loadWhereInArray('worder_definitions', 'ConceptID', $conceptlist, "WHERE SourceID=1 AND GrammarID=" . $_SESSION['grammarID']);
        if ($comments) echo "<br>definitionsall - " . count($definitionsall);
        
        $definitions = array();
        $shortdefinitions = array();
        foreach($definitionsall as $definitionID => $definition) {
            if ($definition->definitiontype == 0) {
                $definitions[$definition->conceptID] = $definition;
            }
            if ($definition->definitiontype == 1) {
                $shortdefinitions[$definition->conceptID] = $definition;
            }
        }
        if ($comments) echo "<br>definitions - " . count($definitions);
        if ($comments) echo "<br>shortdefinitions - " . count($shortdefinitions);
        
	    if ($comments) echo "<br>Finalcount - " . count($finalresult);
	    if ($comments) echo "<br><br>";
	    
	    echo "[";
	    $first = true;
	    $counter = 0;
	    foreach($conceptlist as $index => $conceptID) {
	        
	        $concept = $concepts[$conceptID];
	        
	        
	        // ei palauteta concepteja joissa ei ole definitioneja asetettu...
	        if (isset($shortdefinitions[$concept->conceptID])) {
	            if ($first == false) echo ",";
	            else $first = false;
	            echo "{";
	            echo "\"conceptID\":\"" . $concept->conceptID . "\",";
	            echo "\"name\":\"" . $concept->name . "\",";
	            
	            if (isset($definitions[$concept->conceptID])) {
	                $definition = $definitions[$concept->conceptID];
	                echo "\"description\":\"" . $definition->definition . "\",";
	                echo "\"descriptionindex\":\"" . $definition->definitionindex . "\",";
	                echo "\"descriptionspanish\":\"" . $definition->spanish . "\",";
	            } else {
	                echo "\"description\":\"\",";
	                echo "\"descriptionindex\":\"0\",";
	                echo "\"descriptionspanish\":\"\",";
	            }
	            
	            
	            if (isset($shortdefinitions[$concept->conceptID])) {
	                $definition = $shortdefinitions[$concept->conceptID];
	                echo "\"shortdesc\":\"" . $definition->definition . "\",";
	                echo "\"shortdescindex\":\"" . $definition->definitionindex . "\",";
	                echo "\"shortdescspanish\":\"" . $definition->spanish . "\",";
	            } else {
	                echo "\"shortdesc\":\"\",";
	                echo "\"shortdescindex\":\"0\",";
	                echo "\"shortdescspanish\":\"\",";
	            }
	            
	            if (isset($translations[$concept->conceptID])) {
	                $translation = $translations[$concept->conceptID];
	                echo "\"translations\":\"" . $translation . "\",";
	            } else {
	                echo "\"translations\":\"\",";
	            }
	            
	            if (isset($spanishtranslations[$concept->conceptID])) {
	                $translation = $spanishtranslations[$concept->conceptID];
	                echo "\"spanish\":\"" . $translation . "\",";
	            } else {
	                echo "\"spanish\":\"\",";
	            }
	            
	            echo "\"languageID\":\"" . $concept->languageID . "\"";
	            echo "}";
	            $counter++;
	            
	        }
	        
	        //if ($counter > 5) break;
	    }
	    echo "]";
	    
	    //echo "<br><br>Finalresult - " . count($finalresult);   // 953
	}
	
	
	public function getnounconceptswithoutenglishformAction() {
	    
	    $comments = true;
	    $grammarID = 1;
	    $languageID = 2;
	    $wordclassID = 1;
	    
	    $words = Table::load("worder_words", "WHERE WordclassID=" . $wordclassID . " AND LanguageID=" . $languageID . " AND GrammarID=" . $grammarID);
	    $concepts = Table::load("worder_concepts", "WHERE WordclassID=" . $wordclassID . " AND GrammarID=" . $grammarID);
	    $links = Table::load("worder_conceptwordlinks", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . " AND Defaultword=1");
	    $wordforms = Table::load("worder_wordforms", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . " AND WordclassID=" . $wordclassID . " AND (Features='132' OR Features='132:458')");
	    
	    $wordformsbyid = array();
	    foreach($wordforms as $index => $wordform) {
	        $wordformsbyid[$wordform->wordID] = $wordform;
	    }
	    
	    if ($comments) echo "<br>Conceptcount - " . count($concepts);
	    foreach($words as $index => $word) {
	        $word->conceptID = 0;
	    }
	    
	    $counter = 0;
	    $finalwords = array();
	    $wordarray = array();
	    $foundword = null;
	    foreach($concepts as $index => $concept) {
	        $conceptwordfound = false;
	        foreach($links as $index => $link) {
	            if ($concept->conceptID == $link->conceptID) {
	                if (isset($words[$link->wordID])) {
	                    $conceptwordfound = true;
	                    $foundword = $words[$link->wordID];
	                }
	            } 
	        }
	        
	        if ($conceptwordfound == false) {
	            echo "<br> - Default sana puuttuu - " . $concept->name . " (" .  $concept->conceptID . ")";
	            $counter++;
	            
	        } else {
	            
	            $wordformfound = false;
	            foreach($wordforms as $i2 => $form) {
	                if ($form->wordID == $foundword->wordID) {
	                    $wordformfound = true;
	                }
	            }
	            if ($wordformfound == false) {
	                echo "<br> - Wordform not found  - "  . $foundword->lemma . " (" . $foundword->wordID . ", conceptID: " . $concept->conceptID . ")";
	                $counter++;
	            }
	        }
	    }
	    echo "<br><br>Failed - " . $counter;
	}

	
	
	
	public function getconceptswithoutenglishformAction() {
	    
	    $comments = true;
	    $grammarID = 1;
	    $languageID = 2;
	    
	    $wordclassID = 0;
	    if (isset($_GET['wordclassID'])) {
	        $wordclassID = $_GET['wordclassID'];
	    } else {
	        echo "<br>getwordclassconcepts - wordclassID needed";
	        exit;
	    }
	    
	    
	    
	    $featurestr = null;
	    if ($wordclassID == 1) {
	        $featurestr = "(Features='132' OR Features='132:458')";
	    }
	    if ($wordclassID == 2) {
	        $featurestr = "Features='127'";
	    }
	    if ($wordclassID == 3) {
	        $featurestr = "Features='259'";
	    }
	    if ($featurestr == null) {
	        echo "<br>Unknown wordclass - "  . $wordclassID;
	        exit;
	    }
	    
	    
	    $words = Table::load("worder_words", "WHERE WordclassID=" . $wordclassID . " AND LanguageID=" . $languageID . " AND GrammarID=" . $grammarID);
	    $concepts = Table::load("worder_concepts", "WHERE WordclassID=" . $wordclassID . " AND GrammarID=" . $grammarID);
	    $links = Table::load("worder_conceptwordlinks", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . " AND Defaultword=1");
	    $wordforms = Table::load("worder_wordforms", "WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . " AND WordclassID=" . $wordclassID . " AND " . $featurestr);
	    
	    $wordformsbyid = array();
	    foreach($wordforms as $index => $wordform) {
	        $wordformsbyid[$wordform->wordID] = $wordform;
	    }
	    
	    if ($comments) echo "<br>Conceptcount - " . count($concepts);
	    foreach($words as $index => $word) {
	        $word->conceptID = 0;
	    }
	    
	    $counter = 0;
	    $finalwords = array();
	    $wordarray = array();
	    $foundword = null;
	    foreach($concepts as $index => $concept) {
	        $conceptwordfound = false;
	        foreach($links as $index => $link) {
	            if ($concept->conceptID == $link->conceptID) {
	                if (isset($words[$link->wordID])) {
	                    $conceptwordfound = true;
	                    $foundword = $words[$link->wordID];
	                }
	            }
	        }
	        
	        if ($conceptwordfound == false) {
	            echo "<br> - Default sana puuttuu - " . $concept->name . " (" .  $concept->conceptID . ")";
	            $counter++;
	            
	        } else {
	            
	            $wordformfound = false;
	            foreach($wordforms as $i2 => $form) {
	                if ($form->wordID == $foundword->wordID) {
	                    $wordformfound = true;
	                }
	            }
	            if ($wordformfound == false) {
	                echo "<br> - Wordform not found  - "  . $foundword->lemma . " (" . $foundword->wordID . ", conceptID: " . $concept->conceptID . ")";
	                $counter++;
	            }
	        }
	    }
	    echo "<br><br>Failed - " . $counter;
	}
	
	
	public function fetchwordforconceptAction() {
	    
	    $conceptID = $_GET['conceptID'];
	    $links = Table::load("worder_conceptwordlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
        $words = array();
	    
        echo "[";
        $first = true;
        $counter = 0;
        
	    foreach($links as $index => $link) {
	        
	        $word = Table::loadRow("worder_words", $link->wordID);
	        
            if ($first == false) echo ",";
            else $first = false;
            echo "{";
            echo "\"wordID\":\"" . $word->wordID . "\",";
            echo "\"conceptID\":\"" . $word->conceptID . "\",";
            echo "\"lemma\":\"" . $word->lemma . "\",";
            echo "\"languageID\":\"" . $word->languageID . "\"";
            echo "}";
            $counter++;
	    }
	    echo "]";
	}
	
	
	
	
	public function addconceptdefinitionAction() {

	    $conceptID = $_GET['conceptID'];
	    $definition = $_GET['definition'];
	    $langaugeID = $_GET['languageID'];
	    $wordclassID = $_GET['wordclassID'];
	    $type = $_GET['type'];
	    $definitionindex = $_GET['definitionindex'];
	    
	    $insertarray = array();
	    $insertarray['ConceptID'] = $conceptID;
	    $insertarray['Definition'] = $definition;
	    $insertarray['LanguageID'] = $langaugeID;
	    $insertarray['WordclassID'] = $wordclassID;
	    $insertarray['SourceID'] = 1;
	    $insertarray['Spanish'] = 0;
	    $insertarray['Definitiontype'] = $type;
	    $insertarray['Definitionindex'] = $definitionindex;
	    $insertarray['GrammarID'] = $_SESSION['grammarID'];
	    Table::addRow("worder_definitions", $insertarray, false, false);
	    
	    return 1; 
	}
	
	
	
	public function addspanishnounwordforconceptAction() {
	    
	    if (!isset($_GET['conceptID'])) {
	        echo "<br>conceptID not defined";
	        exit;
	    }
	    if (!isset($_GET['word'])) {
	        echo "<br>wordlemma not defined";
	        exit;
	    }
	    
	    if (!isset($_GET['gender'])) {
	        echo "<br>Gender not defined";
	        exit;
	    }
	    
	    $conceptID = $_GET['conceptID'];
	    $word = $_GET['word'];
	    $gender = $_GET['gender'];
	    $wordclassID = 1;                  // noun
	    $languageID = 27;                  // spanish
	    
	    $values = array();
	    $values['Lemma'] = $word;
	    $values['WordclassID'] = $wordclassID;
	    $values['LanguageID'] = $languageID;
	    $values['ConceptID'] = $conceptID;
	    $values['GrammarID'] = $_SESSION['grammarID'];
	    $wordID = Table::addRow("worder_words", $values, false);
	    
	    // kiinnitetään uusi wordi conceptiin...
	    $values = array();
	    $values['ConceptID'] = $conceptID;
	    $values['WordID'] = $wordID;
	    $values['LanguageID'] = $languageID;
	    $values['GrammarID'] = $_SESSION['grammarID'];
	    $values['Defaultword'] = 1;
	    Table::addRow("worder_conceptwordlinks", $values, false);
	    
	    if ($gender == 'f') {
	        $values = array();
	        $values['LanguageID'] = $languageID;
	        $values['WordclassID'] = $wordclassID;
	        $values['WordID'] = $wordID;
	        $values['FeatureID'] = 352;                // Spanish.Gender
	        $values['ValueID'] = 354;                  // Spanish.Feminine
	        $values['InheritancemodeID'] = 1;
	        $values['GrammarID'] = $_SESSION['grammarID'];
	        Table::addRow("worder_wordfeaturelinks", $values, false);
	        
	        $values = array();
	        $values['Features'] = "352:354:" . $wordID;
	        Table::updateRow('worder_words', $values, "WHERE WordID=" . $wordID, false);
	        
	        $values = array();
	        $values['WordID'] = $wordID;
	        $values['Wordform'] = $word;
	        $values['Features'] =  "1273:354";         // singular
	        $values['Grammatical'] = 1;
	        $values['LanguageID'] = $languageID;
	        $values['WordclassID'] = $wordclassID;
	        $values['Defaultform'] = 1;
	        $values['GrammarID'] = $_SESSION['grammarID'];
	        $wordID = Table::addRow("worder_wordforms", $values, false);
	        
	    }
	    
	    if ($gender == 'm') {
	        $values = array();
	        $values['LanguageID'] = $languageID;
	        $values['WordclassID'] = $wordclassID;
	        $values['WordID'] = $wordID;
	        $values['FeatureID'] = 352;                // Spanish.Gender
	        $values['ValueID'] = 353;                  // Spanish.Masculine
	        $values['InheritancemodeID'] = 1;
	        $values['GrammarID'] = $_SESSION['grammarID'];
	        Table::addRow("worder_wordfeaturelinks", $values, false);
	        
	        $values = array();
	        $values['Features'] = "352:353:" . $wordID;
	        Table::updateRow('worder_words', $values, "WHERE WordID=" . $wordID, false);
	        
	        $values = array();
	        $values['WordID'] = $wordID;
	        $values['Wordform'] = $word;
	        $values['Features'] =  "1273:353";         // singular
	        $values['Grammatical'] = 1;
	        $values['LanguageID'] = $languageID;
	        $values['WordclassID'] = $wordclassID;
	        $values['Defaultform'] = 1;
	        $values['GrammarID'] = $_SESSION['grammarID'];
	        $wordID = Table::addRow("worder_wordforms", $values, false);
	        
	    }
	    
	    
	    // lisätään wordform - perusmuoto sg
	   
	    // Pitää myös asettaa definitionit asetettu tilaan...
	    
	    $values = array();
	    $values['Spanish'] = 1;
	    Table::updateRowsWhere('worder_definitions', $values, "WHERE ConceptID=" . $conceptID . " AND LanguageID=2 AND WordclassID=" . $wordclassID, false);
	    
	    return 1;
	}
	
	
	
	public function updateconceptsspanishstateAction() {
	    
	    if (!isset($_GET['conceptID'])) {
	        echo "<br>conceptID not defined";
	        exit;
	    }
	    $conceptID = $_GET['conceptID'];
	    
	    if (!isset($_GET['state'])) {
	        echo "<br>state not defined";
	        exit;
	    }
	    $state = $_GET['state'];
	    $wordclassID = 1;                  // noun
	    $languageID = 27;                  // spanish
	    
	    $values = array();
	    $values['Spanish'] = $state;
	    Table::updateRowsWhere('worder_definitions', $values, "WHERE ConceptID=" . $conceptID . " AND LanguageID=2 AND WordclassID=" . $wordclassID);
	    
	    return 1;
	}
	
	
}
?>