<?php
	
	// Päivitetään wordfeaturelinks-tauluun components taulukko kuntoon.
	//    - Tämä on mennyt epäsynkkaan sen jälkeen kun countable featuret on poistettu
	
	
	
	global $mysqli;

	$sql = "SELECT WordID, LanguageID, Features FROM worder_words";
	$wordlist = array();
	$wordfeatures = array();
	$result = $mysqli->query($sql);
	
	while($row = $result->fetch_array()) {
		$wordID = $row['WordID'];
		$wordlist[$wordID] = array();
		$wordfeatures[$wordID] = $row['Features']; 
	}
	echo "<br>Wordcount - " . count($wordlist);
	
	

	$sql = "SELECT RowID, WordID, FeatureID, ValueID FROM worder_wordfeaturelinks WHERE GrammarID=1";
	$result = $mysqli->query($sql);
	
	while($row = $result->fetch_array()) {

		$rowID = $row['RowID'];
		$wordID = $row['WordID'];
		
		if (isset($wordlist[$wordID])) {
			$arr = $wordlist[$wordID];
			$arr[] = "" . $row['FeatureID'] . ":" . $row['ValueID'] . ":" . $wordID;
			$wordlist[$wordID] = $arr;
		} else {
			echo "<br> *** word not found - " . $wordID;
		}
	}
	echo "<br>wordlist - " . count($wordlist);

	
	foreach($wordlist as $wordID => $arri) {
		
		if (count($arri) > 0) {
			$str = implode("|", $arri);
			//echo "<br>WordUpdate - " . $wordID . " - " . $str;	
			
			$sql = "UPDATE worder_words SET Features='" . $str . "' WHERE WordID=" . $wordID;
			//echo "<br> - sql - " . $sql;
			//$result = $mysqli->query($sql);
		} else {
			$feature = $wordfeatures[$wordID];
			if (($feature != null) && ($feature != "")) {
				echo "<br>should update - " . $wordID;			// featureja on features-kentässä, vaikka listalta ei löydy yhtään, pitää tyhjätä
			} else {
				//echo "<br>No update - " . $wordID;			// Ei päivitettävää
			}
			
		}
	}
	
	/*
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
			//$result = $mysqli->query($sql);
			echo "<br> - sql - " . $sql;
		} else {
			echo "<br> - sql - " . $sql;
		}
	}
	*/
	
?>