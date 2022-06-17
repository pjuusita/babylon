<?php

	// Tarkistetaan löytyykö kaikki componentlinks taulussa olevat rivit concept.Components-kentästä
	
	global $mysqli;

	$sql = "SELECT * FROM worder_conceptcomponentlinks WHERE SystemID=5 AND GrammarID=1";
	echo "<br>sql - " . $sql;

	$result = $mysqli->query($sql);
	$keylist = array();
	while($row = $result->fetch_array()) {
	
		$conceptID = $row['ConceptID'];
		$componentID = $row['ComponentID'];
		$fromconceptID = $row['FromconceptID'];
		
		$key = $conceptID . ":" . $componentID . ":" . $fromconceptID;
		$keylist[$key] = $key;
	}
	
	
	$sql = "SELECT * FROM worder_concepts WHERE SystemID=5 AND GrammarID=1";
	echo "<br>sql - " . $sql;
	
	$result = $mysqli->query($sql);
	$components = array();
	$insertneeded = array();
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
					echo "<br> -- key = " . $key;
					if (isset($keylist[$key])) {
						echo "<br> -- Key exists *********";
					} else {
						echo "<br> -- Insert key needed.......";
						$insertneeded[$key] = $key;
					}
				}
			}
		}
	}
	echo "<br><br>.........................";
	
	$counter = 0;
	$updatelist = array();
	
	foreach($insertneeded as $index => $keystr) {
	
		$keys = explode(":",$keystr);
		$conceptID = $keys[0];
		$componentID = $keys[1];
		$fromconceptID = $keys[2];
	
		$updatevalues = array();
		$updatevalues['ConceptID'] = $conceptID;
		$updatevalues['ComponentID'] = $componentID;
		$updatevalues['FromconceptID'] = $fromconceptID;
		$updatevalues['InheritancemodeID'] = 5;
		$updatevalues['GrammarID'] = 1;
		//$success = Table::addRow("worder_conceptcomponentlinks", $updatevalues);
		echo "<br>Insertti - " . $conceptID . " - " . $componentID . " - " . $fromconceptID;
	
		$updatelist[$conceptID] = $conceptID;
	
		$counter++;
	}
	
	
	echo "<br>..............";
	
	$concepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $updatelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
	foreach($concepts as $conceptID => $concept) {
		echo "<br>" . $concept->conceptID . " - " . $concept->name;
	}
	
	echo "<br><br>Counter - " . $counter;
	
	echo "<br>..............";
	


?>