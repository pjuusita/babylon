<?php
	
	// Tavoitteena olisi siirtää kaikki wordfeaturessien countable arvot conceptin featureksi.
	
	// Jokaisella conceptilla pitää olla arvo uncountable tai countable
	//	- tätä ei voida pakottaa concepteilla ei ole featureita
	//  - countable on single feature
	
	// Countablet löytyy 

	// Pitää ehkä ensin päivittää worder_wordfeaturelinks-taulun languageID ja wordclassID...

	
	global $mysqli;

	$sql = "SELECT * FROM worder_wordfeaturelinks WHERE FeatureID=602 AND ValueID=604";
	echo "<br>SQL - " . $sql;
	$words = array();
	$result = $mysqli->query($sql);
	
	while($row = $result->fetch_array()) {
		$wordID = $row['WordID'];
		$words[$wordID] = $wordID;
	}
	echo "<br>Wordcount - " . count($words);
	
	
	
	$sql = "SELECT RowID, WordID, ConceptID FROM worder_conceptwordlinks";
	echo "<br>SQL - " . $sql;
	$wordconcepts = array();
	$result = $mysqli->query($sql);
	$conceptlist = array();
	
	while($row = $result->fetch_array()) {
		$wordID = $row['WordID'];
		$conceptID = $row['ConceptID'];
		
		if (isset($words[$wordID])) {
			if (!isset($wordconcepts[$wordID])) {
				$arr = array();
				$arr[$conceptID] = $conceptID;
				$wordconcepts[$wordID] = $arr;
				$conceptlist[$conceptID] = $conceptID;
			} else {
				$arr = $wordconcepts[$wordID];
				$arr[$conceptID] = $conceptID;
				$wordconcepts[$wordID] = $arr;
				$conceptlist[$conceptID] = $conceptID;
			}
		}
	}
	echo "<br>Conceptlinkcount - " . count($words);
		

	$concepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $conceptlist);
	echo "<br>Concepts - " . count($concepts);
	
	
	$counter = 0;	
	echo "<br><br>";
	foreach($wordconcepts as $wordID => $conceptlist) {
		echo "<br>WordID - " . $wordID;
		foreach($conceptlist as $i2 => $conceptID) {
			echo "<br> -- conceptID - " . $conceptID;
			
			$found = false;
			$sql = "INSERT INTO worder_conceptcomponentlinks (ComponentID, InheritancemodeID, ConceptID, GrammarID, SystemID, FromconceptID) VALUES (7,3," . $conceptID . ",1, 5, " . $conceptID . ")";
			echo "<br> -- " . $sql;
			/*
			$result = $mysqli->query($sql);
			if (!$result) {
				die("Error 1: " . $mysqli->connect_error);
			}
			*/
				
			$concept = $concepts[$conceptID];
			if ($concept->components == "") {
				$sql = "UPDATE worder_concepts SET Components='7:" . $conceptID . "' WHERE ConceptID=" . $conceptID;
				echo "<br> -- " . $sql;
				/*
				$result = $mysqli->query($sql);
				if (!$result) {
					die("Error 1: " . $mysqli->connect_error);
				}
				*/
				
			} else {
				$sql = "UPDATE worder_concepts SET Components='" . $concept->components . "|7:" . $conceptID . "' WHERE ConceptID=" . $conceptID;
				echo "<br> -- " . $sql;
				$result = $mysqli->query($sql);
				/*
				if (!$result) {
					die("Error 1: " . $mysqli->connect_error);
				}
				*/
			}
			
			/*
			$result = $mysqli->query($sql);
			if (!$result) {
				die("Error 1: " . $mysqli->connect_error);
			}
			*/
		}
		$counter++;
		//if ($counter > 1) break;
	}
	

	/*
	$sql = "SELECT RowID, WordID, LanguageID, WordclassID FROM worder_wordfeaturelinks WHERE GrammarID=1";
	$wordlinks = array();
	$result = $mysqli->query($sql);
	
	while($row = $result->fetch_array()) {
		$rowID = $row['RowID'];
		$wordID = $row['WordID'];
		$wordlinks[$rowID] = $wordID;
		
		$languageID = $wordlanguages[$wordID];
		//echo "<br>LanguageID = '" . $row['LanguageID'] . "'";
		if ($row['LanguageID'] != null) {
			if ($languageID != $row['LanguageID']) {
				echo "<br>Language missmach in word - " . $wordID;
				exit;
			}
		}
		
		$wordclassID = $wordclasses[$wordID];
		if ($row['WordclassID'] != null) {
			if ($wordclassID != $row['WordclassID']) {
				echo "<br>Wordclass missmach in word - " . $wordID . " - " . $wordclassID . " - " . $row['WordclassID'];
				exit;
			}
		}
	}
	echo "<br>Wordlinks - " . count($wordlinks);
	
	
	$execute = false;
	echo "<br><br><br>";
	foreach($wordlinks as $rowID => $wordID) {
		
		if ((!isset($wordclasses[$wordID])) || (!isset($wordlanguages[$wordID]))) {
			echo "<br>Missing word - " . $wordID;
			exit;
		}
		
		$wordclassID = $wordclasses[$wordID];
		$languageID = $wordlanguages[$wordID];
		
		$sql = "UPDATE worder_wordfeaturelinks SET LanguageID='" . $languageID . "', WordclassID='" . $wordclassID . "' WHERE RowID=" . $rowID;
		if ($execute) {
			$result = $mysqli->query($sql);
			echo "<br> - sql - " . $sql;
		} else {
			echo "<br> - sql - " . $sql;
		}
	}
	
	*/

	/*
	function endsWith( $haystack, $needle ) {
		$length = strlen( $needle );
		if( !$length ) {
			return true;
		}
		return substr( $haystack, -$length ) === $needle;
	}
	

	$sql = "SELECT * FROM worder_words WHERE LanguageID=2 AND WordclassID=1 AND GrammarID=1";

	$missing = array();
	global $mysqli;
	$languages = array();
	$wordclasses = array();
	$words = array();
	
	$result = $mysqli->query($sql);
	while($row = $result->fetch_array()) {
		//echo "<br>" . $row['Lemma'] . " - " . $row['Inflection'];
		$wordID = $row['WordID'];
		$languages[$wordID] = $row['LanguageID'];
		$wordclasses[$wordID] = $row['WordclassID'];
		
		$words[$wordID] = $row['Lemma'];
	}
	echo "<br>Wordcount - " . count($words);
	
	

	$sql = "SELECT * FROM worder_wordforms WHERE LanguageID=2 AND WordclassID=1 AND GrammarID=1";
	echo "<br>sql - " . $sql;
	
	$forms = array();
	$formwordIDs = array();
	$formlists = array();
	
	global $mysqli;
	$result = $mysqli->query($sql);
	$index = 0;
	while($row = $result->fetch_array()) {
		$forms[$index] = $row['Wordform'];
		$formwordIDs[$index] = $row['WordID'];
		$formlists[$index] = $row['Features'];
		
		$index++;
	}
	
	echo "<br>Formcount - " . $index;
	echo "<br><br>------------";
	
	
	
	
	
	
	$counter=0;
	foreach($words as $wordID => $lemma) {
		
		$singularpresent = false;
		$singularstring = "";
		foreach($formwordIDs as $index => $formwordID) {
			if ($formwordID == $wordID) {
				//echo "<br>WordFound - " . $lemma . " - " . $wordID;
				$features = $formlists[$index];
				if ($features == '132:458') {
					$singularpresent = true;
					$singularstring = $forms[$index];
				}
			}
		}
		
		if ($singularpresent == true) {

			echo "<br>--- Singular found - " . $singularstring . " -- " . $wordID;;
				
		
			
		} else {
			$found = false;
			$sql = "INSERT INTO worder_wordforms (Wordform, WordID, Features, Grammatical, SystemID, GrammarID, LanguageID, WordclassID, Defaultform) VALUES ('" . $lemma . "',"  . $wordID . ", '132:458',1, 5, 1, 2, 1, 1)";
			echo "<br>Sql - " . $sql;
			
			$result = $mysqli->query($sql);
			if (!$result) {
				die("Error 1: " . $mysqli->connect_error);
			}
			$counter++;
		}
		
		if ($counter > 100) {
			echo "<br>Breakki Counter - " . $counter;
			break;
		}
	}
	

	*/
	
?>