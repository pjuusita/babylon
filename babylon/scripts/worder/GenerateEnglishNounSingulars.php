<?php


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
	
	

	// Gerate plurals ....
	/*
	$counter=0;
	foreach($words as $wordID => $lemma) {
	
		$singularpresent = false;
		$singularstring = "";
		foreach($formwordIDs as $index => $formwordID) {
			if ($formwordID == $wordID) {
				//echo "<br>WordFound - " . $lemma . " - " . $wordID;
				$features = $formlists[$index];
				$rarity = $rarities[$index];
				if (($features == '132:458') && ($rarity == 0)) {
					$singularpresent = true;
					$singularstring = $forms[$index];
				}
			}
		}
	
		if ($singularpresent == true) {
	
			echo "<br>--- Singular found - " . $singularstring . " -- " . $wordID;;
	
	
				
		} else {
			$found = false;
			$sql = "INSERT INTO worder_wordforms (Wordform, WordID, Features, Grammatical, SystemID, GrammarID, LanguageID, WordclassID, Rarity) VALUES ('" . $lemma . "',"  . $wordID . ", '132:458',1, 5, 1, 2, 1, 0)";
			echo "<br>Sql - " . $sql;

			/*
			$result = $mysqli->query($sql);
			if (!$result) {
				die("Error 1: " . $mysqli->connect_error);
			}
			* /
			$counter++;
		}
	
		if ($counter > 100) break;
	}
	*/
	
?>