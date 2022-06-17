<?php

	// TODO: Varmaankin tarpeeton, ainakin vanhentunut, objectives -> inflectionsets

	$sql = "SELECT * FROM `worder_features` WHERE `ParentID` = 271";
	
	$missing = array();
	global $mysqli;
	$featurelist = array();
	
	$result = $mysqli->query($sql);
	$counter = 0;
	while($row = $result->fetch_array()) {
		$featurelist[$row['FeatureID']] = 0;
	}	
	echo "<br>Featurecount - " . count($featurelist);
	
	
	$featurevalues = array();
	foreach($featurelist as $featureID => $link) {
		
		$values = array();
		$values['InflectionsetID'] = 322;
		$values['FeatureID'] = $featureID;
		$values['LanguageID'] = 1;
		$values['GrammarID'] = 1;
		$values['SystemID'] = 5;
		$values['WordgroupID'] = 0;
		$values['Features'] = '13:5';
		$values['WordclassID'] = 1;
		$values['ParentfeatureID'] = 271;
		//$success = Table::addRow("worder_inflectionsetitems", $values, false);
		echo "<br>" . $featureID;
	}
		
	echo "<br>Finished";
	
?>