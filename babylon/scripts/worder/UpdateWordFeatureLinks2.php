<?php
	
	// Päivitetään wordfeaturelinks-tauluun wordclassID ja languageID.
	//    kyseiset kentät ovat myöhemmin lisätty, ja uusi lisäystoiminto
	//    lisää kyseiset arvot, eli tätä ei liene tarpeen ajaa uudelleen
	
	
	
	global $mysqli;
	
	$sql = "SELECT WordID, LanguageID, WordclassID FROM worder_words WHERE GrammarID=1";
	$wordclasses = array();
	$wordlanguages = array();
	$result = $mysqli->query($sql);
	
	while($row = $result->fetch_array()) {
		$wordID = $row['WordID'];
		$languageID = $row['LanguageID'];
		$wordclassID = $row['WordclassID'];
		$wordlanguages[$wordID] = $languageID;
		$wordclasses[$wordID] = $wordclassID;
	}
	echo "<br>Wordcount - " . count($wordclasses);
	
	
	
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
			//$result = $mysqli->query($sql);
			echo "<br> - sql - " . $sql;
		} else {
			echo "<br> - sql - " . $sql;
		}
	}
	

	/*
	// Päivitetään wordfeaturelinks-tauluun wordclassID ja languageID.
	//    kyseiset kentät ovat myöhemmin lisätty, ja uusi lisäystoiminto
	//    lisää kyseiset arvot, eli tätä ei liene tarpeen ajaa uudelleen
	
	global $mysqli;

	
	$features = Table::load("worder_features");
	
	

	$sql = "SELECT RowID, ValueID FROM worder_wordfeaturelinks WHERE GrammarID=1";
	$wordlinks = array();
	$featurevalues = array();
	
	$result = $mysqli->query($sql);
	
	while($row = $result->fetch_array()) {
		$rowID = $row['RowID'];
		$featureID = $row['ValueID'];
		$wordlinks[$rowID] = $featureID;
	}
	echo "<br>Wordlinks - " . count($wordlinks);
	
	
	$execute = false;
	echo "<br><br><br>";
	foreach($wordlinks as $rowID => $featureID) {
		
		$parentID = $features[$featureID]->parentID;
		$sql = "UPDATE worder_wordfeaturelinks SET FeatureID='" . $parentID . "' WHERE RowID=" . $rowID;
		if ($execute) {
			$result = $mysqli->query($sql);
			echo "<br> - sql - " . $sql;
		} else {
			echo "<br> - sql - " . $sql;
		}
	}
	*/
	
?>