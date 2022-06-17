<?php


	// Tällä tarkistetaan ovatko conceptcomponentlinks-taulu ja concept.components yhteneväiset
	// - ei tee tietokantapäivitystä automaattisesti
	
	global $mysqli;

	$sql = "SELECT * FROM worder_conceptcomponentlinks WHERE SystemID=5 AND GrammarID=1";
	echo "<br>sql - " . $sql;

	$result = $mysqli->query($sql);
	$keylist = array();
	while($row = $result->fetch_array()) {
	
		$inheritancemodeID = $row['InheritancemodeID'];
		$conceptID = $row['ConceptID'];
		$componentID = $row['ComponentID'];
		$fromconceptID = $row['FromconceptID'];

		if ($conceptID == $fromconceptID) {
			if ($inheritancemodeID == 2) {
				// Ei lisätä omia for-children componentteja	
				echo "<br> ----- inheritance 2: " .$conceptID;
				//$key = $conceptID . ":" . $componentID . ":" . $fromconceptID;
			} else {
				$key = $conceptID . ":" . $componentID . ":" . $fromconceptID;
			}
		} else {
			$key = $conceptID . ":" . $componentID . ":" . $fromconceptID;
		}
		$keylist[$key] = $key;
	}
	
	
	
	$sql = "SELECT * FROM worder_concepts WHERE SystemID=5 AND GrammarID=1";
	echo "<br>sql - " . $sql;
	
	$result = $mysqli->query($sql);
	$components = array();
	$foundkeys = array();
	while($row = $result->fetch_array()) {
		
		$conceptID = $row['ConceptID'];
		$compstr = $row['Components'];
		if ($compstr != "") {
			$list = explode("|", $compstr);
			echo "<br>ConceptID: " .$conceptID . " - Components:" . $compstr;
				
			foreach($list as $index => $linkstr) {
				if ($linkstr != "") {
					$link = explode(":", $linkstr);
					$key = $conceptID . ":" .  $link[0] . ":" .  $link[1];
					$foundkeys[$key] = $key;
				}
			}
		}
	}
	
	echo "<br><br>.........................";
	
	$counter = 0;
	$updatelist = array();
	
	/*
	foreach($keylist as $index => $keystr) {
		
		$keys = explode(":",$keystr);
		$conceptID = $keys[0];
		$componentID = $keys[1];
		$fromconceptID = $keys[2];
		
		if (isset($foundkeys[$keystr])) {
			// Pitäisi tsekata löytyykö kaikki keyssit...
			
		} else {
			echo "<br>Key not found - " . $conceptID . " - " . $componentID . " - " . $fromconceptID;
		}
		
		$counter++;
	}
	echo "<br><br>Counter - " . $counter;
	*/
	

	foreach($foundkeys as $index => $keystr) {
	
		$keys = explode(":",$keystr);
		$conceptID = $keys[0];
		$componentID = $keys[1];
		$fromconceptID = $keys[2];
	
		if (isset($keylist[$keystr])) {
				
		} else {
			echo "<br>Key not found - " . $conceptID . " - " . $componentID . " - " . $fromconceptID;
		}
	
		$counter++;
	}
	echo "<br><br>Counter - " . $counter;
	
	
	echo "<br>..............";
	



?>