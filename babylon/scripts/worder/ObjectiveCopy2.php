<?php

	// TODO: Varmaankin tarpeeton, ainakin vanhentunut, objectives -> inflectionsets


	$sql = "SELECT * FROM worder_inflectionsets WHERE ParentID=314";
	$targetInflectionsetID = 1244;
	
	$missing = array();
	global $mysqli;
	$list = array();
	
	$result = $mysqli->query($sql);
	$counter = 0;
	while($row = $result->fetch_array()) {
		//$res = new Row();
		$values = array();
		
		$name = $row['Name'];
		$name = str_replace("Inessive", "Comitative", $name);
		$name = str_replace("-issa", "-ine", $name);
		$values['Name'] = $name;
		$values['LanguageID'] = $row['LanguageID'];
		$values['GrammarID'] = $row['GrammarID'];
		$values['SystemID'] = $row['SystemID'];
		$values['ParentID'] = $targetInflectionsetID;
		$list[$row['InflectionsetID']] = $values;
		echo "<br>Found - " . $row['InflectionsetID'] . " - " . $name;
		$counter++;
	}
	echo "<br>Counter - " . $counter;
	echo "<br>";
	$counter = 0;
	foreach($list as $index => $values) {
		$newInflectionsetrowID = $counter;
		//$newInflectionsetrowID = Table::addRow("worder_inflectionsets", $values, false);
		$newlist[$index] = $newInflectionsetrowID; 
		$counter++;
	}
	//echo "<br>Finished";
	
	
	$counter = 0;
	
	foreach($list as $inflectionsetID => $valuesxxx) {
		
		echo "<br>Kopioidaan inflectionset - " . $inflectionsetID;
		// TODO: Varmaankin vanhentunut, objectives -> inflectionsets
		$sql = "SELECT * FROM worder_inflectionsetitems WHERE InflectionsetID=" . $inflectionsetID;
		echo "<br> --- "  . $sql;
		$missing = array();
		global $mysqli;
		$linklist = array();
		
		$newInflectionsetrowID = $newlist[$inflectionsetID];
		$result = $mysqli->query($sql);
		$counter = 0;
		while($row = $result->fetch_array()) {
			//$res = new Row();
			$values = array();
			$rowID = $row['RowID'];
			$values['InflectionsetID'] = $newInflectionsetrowID;
			$values['FeatureID'] = $row['FeatureID'];
			$values['LanguageID'] = $row['LanguageID'];
			$values['GrammarID'] = $row['GrammarID'];
			$values['SystemID'] = $row['SystemID'];
			$values['WordgroupID'] = $row['WordgroupID'];
			$values['Features'] = $row['Features'];
			$values['WordclassID'] = $row['WordclassID'];
			$values['ParentfeatureID'] = $row['ParentfeatureID'];
			$linklist[] = $values;
			echo "<br> --- Foound - " . $rowID;
			$counter++;
		}
		
		
		foreach($linklist as $index => $values) {
			//$success = Table::addRow("worder_inflectionsetitems", $values, false);
		}
		
		
	}
	echo "<br>Counter - " . $counter;
	
	
	/*
	$sql = "SELECT * FROM worder_inflectionsetitems WHERE InflectionsetID=225";

	$missing = array();
	global $mysqli;
	$list = array();
	
	$result = $mysqli->query($sql);
	$counter = 0;
	while($row = $result->fetch_array()) {
		//$res = new Row();
		$values = array();
		$rowID = $row['RowID'];
		$values['InflectionsetID'] = 313;
		$values['FeatureID'] = $row['FeatureID'];
		$values['LanguageID'] = $row['LanguageID'];
		$values['GrammarID'] = $row['GrammarID'];
		$values['SystemID'] = $row['SystemID'];
		$values['WordgroupID'] = $row['WordgroupID'];
		$values['Features'] = $row['Features'];
		$values['WordclassID'] = $row['WordclassID'];
		$values['ParentfeatureID'] = $row['WordclassID'];
		$list[] = $values;
		echo "<br>Foound - " . $rowID;
		$counter++;
	}
	echo "<br>Counter - " . $counter;

	
	foreach($list as $index => $values) {
		$success = Table::addRow("worder_inflectionsetitems", $values, false);
	}
	echo "<br>Finished";
	*/
	
	/*
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
	*/

?>