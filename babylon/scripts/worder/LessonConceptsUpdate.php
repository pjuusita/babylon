<?php

	// TODO: Varmaankin tarpeeton, ainakin vanhentunut, objectives -> inflectionsets


	$sql = "SELECT * FROM `worder_features`";
	
	global $mysqli;
	$featurelist = array();
	
	$result = $mysqli->query($sql);
	$counter = 0;
	while($row = $result->fetch_array()) {
		$featurelist[$row['FeatureID']] = $row['ParentID'];
	}	
	echo "<br>Featurecount - " . count($featurelist);
	
	$sql = "SELECT * FROM worder_inflectionsetitems";

	global $mysqli;
	$inflectionsetlist = array();
	
	$result = $mysqli->query($sql);
	$updatelist = array();
	$counter = 0;
	while($row = $result->fetch_array()) {
		
		$featureID = $row['FeatureID'];
		$parentID = $row['ParentfeatureID'];
		
		$shouldparentID = $featurelist[$featureID];
		if ($shouldparentID != $parentID) {
			echo "<br> - parent don't match - InflectionsetID:" . $row['InflectionsetID'] . ", featureID:" . $featureID . ", parentID:" . $parentID;
			$inflectionsetlist[$row['InflectionsetID']] = 1;
			$rowID = $row['RowID'];
			$updatelist[$rowID] =  $shouldparentID;			
			$counter++;
		}
	}

	echo "<br>sets - " . count($inflectionsetlist);
	echo "<br>Counter - " . $counter;
		
	echo "<br><br>";
	foreach($updatelist as $rowID => $parentID) {

		
		$values = array();
		$values["ParentfeatureID"] = $parentID;
		echo "<br>Update  - " . $rowID . " -- " . $parentID;
		//$success = Table::updateRow("worder_inflectionsetitems", $values, $rowID , false);
	 	
	}
	
	
	/*
	echo "<br>Counter - " . $counter;

	
	$items = Table::loadWhereInArray('worder_inflectionsetitems', 'InflectionsetID', $inflectionsetlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
	
	echo "<br>-----";
	echo "<br>Items - " . count($items);
	
	$featurevalues = array();
	foreach($items as $index => $link) {
		//$success = Table::addRow("worder_inflectionsetitems", $values, false);
		$featureID = $link->featureID;
		if (isset($featurevalues[$featureID])) {
			echo "<br>Feature already found - " . $link->rowID . " -- " . $link->featureID . " --  inflectionsetID=" . $link->inflectionsetID;
			echo "<br>Feature alread - " . $featurevalues[$featureID];
		} else {
			$featurevalues[$featureID] = $link->inflectionsetID;
		}
		
		if (isset($featurelist[$featureID])) {
			$featurelist[$featureID] = 1;
		} else {
			echo "<br>--- not accepted feature - " . $featureID;
		}
		
		if (count($link->features) > 0) {
			echo "<br>Linkki - " . $link->rowID . " -- " . count($link->features);
			
			
			
 		} else {
 			echo "<br>Linkki - " . $link->rowID . " -- ";
 		}
		
 		$values = array();
 		$values["Features"] = "58:6";
 		//$success = Table::updateRow("worder_inflectionsetitems", $values, $link->rowID , false);
 		
	}
	echo "<br>--------";
	foreach($featurelist as $featureID => $value) {
		if ($value == 0) echo "<br>--- missing feature - " . $featureID;
	}
	
	echo "<br>Finished";
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