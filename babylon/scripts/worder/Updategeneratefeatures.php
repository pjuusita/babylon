<?php


// Lessoneiden sorttaus sekoittuu jossainvaiheessa kun näitä on veivattu, epäselvää
// onko kyseessä olemassaoleva bugi vai onko bugi jo korjattu, mutta vanhoja virheellisiä
// sortordereita edelleen esiintyy. 
 
// Hoidetaan homma niin, että haetaan kaikki lessonit, järjestetään ne nykyisen mukaan
// ja sen jälkeen päivitetään sortorder kenttä uudelleen olemassaolevien ID-numeroiden 
// mukaiseksi...

	$languageID = 1;  // suomi	

	global $mysqli;
	$sql = 'SELECT * FROM worder_objectives WHERE ObjectiveID=132 AND SystemID=5 AND GrammarID=' . $_SESSION['grammarID'] . ' ORDER BY Sortorder';
	echo "<br>sql - " . $sql;

	$objectives = Table::load("worder_objectives", "WHERE SystemID=5 AND GrammarID=" . $_SESSION['grammarID'] . " ORDER BY Sortorder");
	$allfeatures = Table::load("worder_features");
	
	foreach($objectives as $index => $objective) {
		echo "<br>objective - " . $objective->objectiveID . " ---- " . $objective->features . " vs. " . $objective->generatefeatures;
		
		$featurelist = explode('|',$objective->features);
		
		foreach($featurelist as $index => $featurestr) {
			
			if ($featurestr != '') {
				$items = explode(':', $featurestr);
				
				$featureID = $items[0];
				$valueID = $items[1];				
				
				$feature = $allfeatures[$featureID];
				$value = $allfeatures[$valueID];
				
				echo "<br>" . $featureID . " = " . $valueID . " -> " . $feature->semanticlinkID . " = " . $value->semanticlinkID;
				
				$values = array();
				$values['LanguageID'] = $languageID;
				$values['WordclassID'] = $objective->wordclassID;
				$values['FeatureID'] = $feature->semanticlinkID;
				$values['ValueID'] = $value->semanticlinkID;
				$values['ObjectiveID'] = $objective->objectiveID;
				$values['GrammarID'] = $_SESSION['grammarID'];
				//$success = Table::addRow("worder_objectivegeneratefeatures", $values);
			}
		}

		$requirements = Table::load("worder_objectivegeneratefeatures", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ObjectiveID=" . $objective->objectiveID);
		$newfeatures = array();
		foreach($requirements as $index => $requirement) {
			$newfeature = $requirement->featureID . ":" . $requirement->valueID;
			$newfeatures[] = $newfeature;
		}
		$requirementsstr = implode("|", $newfeatures);
		$values = array();
		$values['Generatefeatures'] = $requirementsstr;
		$values['Features'] = '';
		//$success = Table::updateRow("worder_objectives", $values, $objective->objectiveID);
	}
	
?>