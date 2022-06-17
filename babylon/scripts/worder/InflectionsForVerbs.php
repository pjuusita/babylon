<?php

	echo "<br>Jeeejee - inflections for verbs";
	
	// Haetaan wordino [current] kannasta kaikki verbien perusmuodot, joiden inflection ei ole asetettu
	
	// Haetaan worder kannasta kaikkien verbien perusmuodolla, ja napataan inflection
	
	// Tallennetaan wordino kantaan kaikki löydetyt inflection-stringit
	
	
	
	

	$sql = "SELECT * FROM worder_words WHERE LanguageID=1 AND WordclassID=2";
	
	$missing = array();
	global $mysqli;
	$result = $mysqli->query($sql);
	while($row = $result->fetch_array()) {
		$inflection = $row['Inflection'];
		echo "<br>" . $row['Lemma'] . " - " . $row['Inflection'];
		if (($inflection == null) || ($inflection == '')) {
			if ($row['Inflection'] == '') {
				echo "<br>Empty";
				$missing[$row['Lemma']] = $row['WordID'];
			}
		}
	}
	
	
	$sql = "SELECT * FROM worder_finnish_words WHERE WordclassID=2";
	echo "<br>sql - " . $sql;
	
	$mysqli2 = ConnectDatabase('babelsoftf_worder');
		
	
	$found = array();
	global $mysqli;
	$result = $mysqli2->query($sql);
	while($row = $result->fetch_array()) {
		//echo "<br>" . $row['Lemma'] . " - " . $row['Inflection'];
		$word = $row['Lemma'];
		$inflection = $row['Inflection'];
		if (isset($missing[$word])) {
			echo "<br>Found - " . $word . " -- " . $row['Inflection'];
			if ($inflection != "") {
				$found[$missing[$word]] = $inflection;
			}
		}
	}
	
	$mysqli = ConnectDatabase('babelsoftf_wordino');
	echo "<br>.........................................................";
	foreach($found as $index => $value) {
		$sql = "UPDATE worder_words SET Inflection='" . $value . "' WHERE WordID=" . $index;
		$result = $mysqli->query($sql);
		echo "<br>" . $sql;
	}
	

?>