<?php


	
	$sql = "SELECT * FROM worder_finnish_words WHERE WordclassID=3";
	echo "<br>sql - " . $sql;
	
	global $mysqli;
	$result = $mysqli->query($sql);
	$searchwords = array();
	$inflections = array();
	while($row = $result->fetch_array()) {
		$inflection = $row['Inflection'];
		$lemma = $row['Lemma'];
		$wordID = $row['WordID'];
		//echo "<br>" . $row['Lemma'] . ", inflection: " . $row['Inflection'];
		if ($inflection != '') {
			//echo "<br>No inflection";
			$searchwords[$wordID] = $lemma;
			$inflections[$wordID] = $inflection;
		}
	}
	
	$dir = 'modules\\worder\\_classes\\finnishnouninflector.class.php';
	$dir = str_replace('\\', DIRECTORY_SEPARATOR, $dir);
	include( SITE_PATH . $dir);
		
	
	
	echo "<br>..............";
	
	$counter = 0;
	foreach($searchwords as $wordID => $lemma) {
		
		echo "<br>Luodaan muodot - " . $lemma . " - " . $inflections[$wordID];
		
		$forms = FinnishNounInflector::getWordForms($word->wordID, $word->lemma, $word->inflectionforms, $word->inflection);
		
		foreach($forms as $index => $value) {
			echo "<br>Value - " . $value;
		}
		
		
		$counter++;
		if ($counter > 2) break;
	}


?>