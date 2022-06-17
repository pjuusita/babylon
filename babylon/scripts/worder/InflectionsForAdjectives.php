<?php


	$sql = "SELECT * FROM worder_finnish_words WHERE WordclassID=3";
	echo "<br>sql - " . $sql;
	
	global $mysqli;
	$result = $mysqli->query($sql);
	$searchwords = array();
	while($row = $result->fetch_array()) {
		$inflection = $row['Inflection'];
		$lemma = $row['Lemma'];
		$wordID = $row['WordID'];
		//echo "<br>" . $row['Lemma'] . ", inflection: " . $row['Inflection'];
		if ($inflection == null) {
			//echo "<br>No inflection";
			$searchwords[$wordID] = $lemma;
		}
	}
	
	echo "<br>..............";
	
	$counter = 0;
	$worder = ConnectDatabaseTemp("babelsoftf_worder");
	foreach($searchwords as $wordID => $lemma) {
		$counter++;
		echo "<br>" . $counter . ". Word - " . $lemma;
		$sql = "SELECT * FROM worder_finnish_words WHERE Lemma='" . $lemma . "' AND WordclassID=3";
		echo "<br>--- " . $sql;
		$result = $worder->query($sql);
		$searchwords = array();
		$inflection = "";
		while($row = $result->fetch_array()) {
			echo "<br>--- Found - " . $row['WordID'] . " - " . $row['Inflection'];
			$inflection = $row['Inflection'];
		}
		
		if ($inflection != "") {
			$sql = "UPDATE worder_finnish_words SET Inflection='" . $inflection . "' WHERE WordID=" . $wordID;
			echo "<br>--- " . $sql;
			$result = $mysqli->query($sql);
		} else {
			echo "<br>--- inflection form not found";
		}
		
		//if ($counter > 3) break;
	}
	
	$worder->close();
	$mysqli->close();
	
?>