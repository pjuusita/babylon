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
		
		
		
		/*		
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
		*/
	}
	
	


}
?>