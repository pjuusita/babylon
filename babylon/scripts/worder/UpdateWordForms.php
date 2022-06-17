<?php


	$sql = "SELECT * FROM worder_words";

	$missing = array();
	global $mysqli;
	$languages = array();
	$wordclasses = array();
	
	$result = $mysqli->query($sql);
	while($row = $result->fetch_array()) {
		//echo "<br>" . $row['Lemma'] . " - " . $row['Inflection'];
		$wordID = $row['WordID'];
		$languages[$wordID] = $row['LanguageID'];
		$wordclasses[$wordID] = $row['WordclassID'];
	}


	$sql = "SELECT * FROM worder_wordforms";
	echo "<br>sql - " . $sql;
	
	$found = array();
	global $mysqli;
	$updatelanguagerows = array();
	$updatewordclassesrows = array();
	$result = $mysqli->query($sql);
	while($row = $result->fetch_array()) {
		//echo "<br>" . $row['Lemma'] . " - " . $row['Inflection'];
		$wordID = $row['WordID'];
		$rowID = $row['RowID'];
		$wordclassID = $row['WordclassID'];
		$languageID = $row['LanguageID'];
		
		if (isset($languages[$wordID])) {
			if ($languages[$wordID] != $languageID)	{
				//echo "<br>Language not match for RowID:" . $rowID . ", languageID=" . $languageID . ", shoild be " . $languages[$wordID];
				$updatelanguagerows[$rowID] = $languages[$wordID];
			}	
		} else {
			echo "<br>Words not found - " . $wordID;
		}
		
		if (isset($wordclasses[$wordID])) {
			if ($wordclasses[$wordID] != $wordclassID)	{
				echo "<br>Wordclasses not match for RowID:" . $rowID . ", wordclassID=" . $wordclassID . ", shoild be " . $wordclasses[$wordID];
				$updatewordclassesrows[$rowID] = $wordclasses[$wordID];
			}
		} else {
			echo "<br>Words not found - " . $wordID;
		}
	}
	echo "<br>Finished.";
	
	foreach($updatelanguagerows as $index => $value) {
		$sql = "UPDATE worder_wordforms SET LanguageID=" . $value . " WHERE RowID=" . $index;
		$result = $mysqli->query($sql);
		echo "<br>" . $sql;
	}
	
	foreach($updatewordclassesrows as $index => $value) {
		$sql = "UPDATE worder_wordforms SET WordclassID=" . $value . " WHERE RowID=" . $index;
		$result = $mysqli->query($sql);
		echo "<br>" . $sql;
	}
	

?>