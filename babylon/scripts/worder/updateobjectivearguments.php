<?php


// Lessoneiden sorttaus sekoittuu jossainvaiheessa kun näitä on veivattu, epäselvää
// onko kyseessä olemassaoleva bugi vai onko bugi jo korjattu, mutta vanhoja virheellisiä
// sortordereita edelleen esiintyy. 
 
// Hoidetaan homma niin, että haetaan kaikki lessonit, järjestetään ne nykyisen mukaan
// ja sen jälkeen päivitetään sortorder kenttä uudelleen olemassaolevien ID-numeroiden 
// mukaiseksi...

	$languageID = 1;  // suomi	

	global $mysqli;
	//$sql = 'SELECT * FROM worder_objectives WHERE ObjectiveID=132 AND SystemID=5 AND GrammarID=' . $_SESSION['grammarID'] . ' ORDER BY Sortorder';
	//echo "<br>sql - " . $sql;

	$objectives = Table::load("worder_objectives", "WHERE SystemID=5 AND GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Sortorder", true);
	$allfeatures = Table::load("worder_features");
	$allobjectives = Table::load("worder_objectives", "WHERE SystemID=5 AND GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Sortorder", true);
	
	foreach($objectives as $index => $objective) {
		echo "<br>objective - " . $objective->objectiveID . " ---- " . $objective->arguments;
		
		$argumentlist = explode('|',$objective->arguments);
		
		foreach($argumentlist as $index => $argumentstr) {
			
			if ($argumentstr != '') {
				$items = explode(':', $argumentstr);
				
				$argumentID = $items[0];
				$wordclassID = $items[1];				
				$valueobjectiveID = $items[2];
				
				echo "<br>" . $argumentID . " = " . $wordclassID . " -> " . $valueobjectiveID;
				
				$targetobjective = $allobjectives[$valueobjectiveID];
				
				$values = array();
				$values['ObjectiveID'] = $objective->objectiveID;
				$values['ArgumentID'] = $argumentID;
				$values['ValueobjectiveID'] = $valueobjectiveID;
				$values['LanguageID'] = $targetobjective->languageID;
				$values['WordclassID'] = $targetobjective->wordclassID;
				$values['GrammarID'] = $_SESSION['grammarID'];
				//$success = Table::addRow("worder_objectivearguments", $values);
			}
		}
	}
	
?>