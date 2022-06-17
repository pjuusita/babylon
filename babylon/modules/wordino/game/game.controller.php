<?php


class GameController extends AbstractController {

	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->playAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	

	public function playAction() {

		$this->registry->playerID = getSessionVar('playerID', 0);
		
		$this->registry->players = Table::load("wordino_players", "WHERE GrammarID=" . $_SESSION['grammarID']);
		if ($this->registry->playerID == 0) {
			foreach($this->registry->players as $index => $player) {
				$this->registry->player = $player;
				$this->registry->playerID = $player->playerID;
				setSessionVar('playerID', $this->registry->playerID);
				break;
			} 
		} else {
			$this->registry->player = $this->registry->players[$this->registry->playerID];
		}

		$sourcelanguageID = $this->registry->player->sourcelanguageID;
		$targetlanguageID = $this->registry->player->targetlanguageID;
		
		$this->registry->lessons = Table::load("worder_lessons", "WHERE LanguageID=" . $targetlanguageID . " AND GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->lessonlist = Table::load("wordino_lessons", "WHERE PlayerID=" . $this->registry->playerID . " AND GrammarID=" . $_SESSION['grammarID']);
		$selected = array();
		foreach($this->registry->lessonlist as $index => $lessonlink) {
			if (isset($this->registry->lessons[$lessonlink->lessonID])) {
				$selected[] = $this->registry->lessons[$lessonlink->lessonID];
			} else {
				echo "<br>Lesson not found - " . $lessonlink->lessonID;
			}
		}
		$this->registry->selectedlessons = $selected;
		$this->registry->template->show('wordino/game','game');
	}

	
	

	public function addlessonAction() {
	
		$comments = true;
		echo "<br>Addlesson";
	
		$playerID = $_GET['playerID'];
		$player = Table::loadRow("wordino_players", $playerID);
		$targetlanguageID = $player->targetlanguageID;
		$sourcelanguageID = $player->sourcelanguageID;
		
		$lessonID = $_GET['lessonID'];
		$lesson = Table::loadRow("worder_lessons", $lessonID);
		echo "<br>Lesson load - "  . $lesson->name;
		
		
		
		$values = array();
		$values['PlayerID'] = $playerID;
		$values['LessonID'] = $lessonID;
		$values['LanguageID'] = $player->targetlanguageID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['Lessonstatus'] = 1;
		//$wordparentlinkID = Table::addRow("wordino_lessons", $values, $comments);

		
		$lessonconceptlinks = Table::load("worder_lessonconcepts", "WHERE LessonID=" . $lessonID);
		$concepts = array(); 
		foreach($lessonconceptlinks as $index => $link) {
			$concepts[$link->conceptID] = $link->conceptID;
		}
		$concepts = Table::loadWhereInArray("worder_concepts","ConceptID", $concepts, "WHERE SystemID=" . $_SESSION['systemID']);
		
		$wordlinks = Table::loadWhereInArray("worder_conceptwordlinks","ConceptID", $concepts, "WHERE SystemID=" . $_SESSION['systemID'] . " AND (LanguageID=" . $player->sourcelanguageID . " OR LanguageID=" . $player->targetlanguageID . ")");
		$selectedwords = array();
		$counter = 0;
		echo "<br>";
		foreach($concepts as $index => $concept) {
			$counter++;
			echo "<br>" . $counter . ". Concept - " . $concept->name;
			
			$targetwordID = 0;
			$sourcewordID = 0;
			foreach($wordlinks as $rowID => $wordlink) {
				if (($wordlink->defaultword == 1) && ($wordlink->languageID == $targetlanguageID) && ($wordlink->conceptID == $concept->conceptID)) {
					echo "<br> -- targetword found - " . $wordlink->wordID;
					$selectedwords[$wordlink->wordID] = $wordlink->wordID;
				}
				if (($wordlink->defaultword == 1) && ($wordlink->languageID == $sourcelanguageID) && ($wordlink->conceptID == $concept->conceptID)) {
					echo "<br> -- sourceword found - " . $wordlink->wordID;
					$selectedwords[$wordlink->wordID] = $wordlink->wordID;
				}
			}
		}

		$words = Table::loadWhereInArray("worder_words","WordID", $selectedwords, "WHERE SystemID=" . $_SESSION['systemID'] . " AND GrammarID=" . $_SESSION['grammarID']);
		
		echo "<br>";
		$targetwords = array();
		$sourcewords = array();
		foreach($concepts as $index => $concept) {
			$counter++;
			echo "<br>" . $counter . ". Concept - " . $concept->name;
				
			$targetwordID = 0;
			$sourcewordID = 0;
			foreach($wordlinks as $rowID => $wordlink) {
				if (($wordlink->defaultword == 1) && ($wordlink->languageID == $targetlanguageID) && ($wordlink->conceptID == $concept->conceptID)) {
					$word = $words[$wordlink->wordID];
					echo "<br> -- targetword found - " . $words[$wordlink->wordID]->lemma;
					$selectedwords[$wordlink->wordID] = $wordlink->wordID;
					$targetwords[$concept->conceptID] = $word;
				}
				if (($wordlink->defaultword == 1) && ($wordlink->languageID == $sourcelanguageID) && ($wordlink->conceptID == $concept->conceptID)) {
					$word = $words[$wordlink->wordID];
					echo "<br> -- sourceword found - " . $words[$wordlink->wordID]->lemma;
					$selectedwords[$wordlink->wordID] = $wordlink->wordID;
					$sourcewords[$concept->conceptID] = $word;
				}
			}
		}
		
		echo "<br><br>";
		foreach($concepts as $index => $concept) {
			
			$targetword = $targetwords[$concept->conceptID];
			$sourceword = $sourcewords[$concept->conceptID];
				
			$values = array();
			$values['PlayerID'] = $playerID;
			$values['LanguageID'] = $player->targetlanguageID;
			$values['GrammarID'] = $_SESSION['grammarID'];
			$values['ConceptID'] = $concept->conceptID;
				
			$values['TargetwordID'] = $targetword->wordID;
			$values['Targetword'] = $targetword->lemma;
			$values['SourcewordID'] = $sourceword->wordID;
			$values['Sourceword'] = $sourceword->lemma;
				
			//echo "<br>Adding line - " . $targetword->wordID . " - " . $sourceword->lemma;
			//$rowID = Table::addRow("wordino_playerwords", $values);
		}
		
		
		// Lisätään lessoni wordino_lessons-tauluun
		// Lisätään lessonin sisältö
		//		- objectives	-- näistä ei kaikkia aktivoida suoraan, available
		//		- rules			-- näistä ei kaikkia aktivoida suoraan, available
		//		- words			-- nämä lisätään suoraan kaikki
		
	
	
		//redirecttotal('wordino/game/play',null);
	}
	
}
