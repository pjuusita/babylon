<?php



	$arguments = Table::load("worder_arguments", "WHERE GrammarID=1");
	$objectivearguments = Table::load("worder_objectivearguments", "WHERE GrammarID=1");
	$requirements = Table::load("worder_objectiveprerequisites", "WHERE GrammarID=1");
	//$objectives = Table::load("worder_objectives", "WHERE GrammarID=1");
	

	foreach($objectivearguments as $index => $objectiveargument) {
		
		echo "<br>Checking argument - " . $objectiveargument->rowID;
		$found = false;		
		foreach($requirements as $index => $requirement) {
			if (($objectiveargument->objectiveID == $requirement->objectiveID) && ($objectiveargument->valueobjectiveID == $requirement->prerequisiteID)) {
				$found = true;
			}
		}
		
		if ($found == true) {
			echo "<br> -- objective argument found, objectiveID: " . $objectiveargument->objectiveID;
			
			
			
		} else {
			$argument = $arguments[$objectiveargument->argumentID];
			echo "<br> -- objective argument not found, objectiveID: " . $objectiveargument->objectiveID . " (argument:" . $argument->name . ")";
		}
	}
	
	echo "<br><br>Update...";

	/*
	foreach($objectivearguments as $index => $objectiveargument) {
	
		$values = array();
		$values['ObjectiveID'] = $objectiveargument->objectiveID;
		$values['PrerequisiteID'] = $objectiveargument->valueobjectiveID;
		$values['ArgumentID'] = $objectiveargument->argumentID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$rowID = Table::addRow("worder_objectiveprerequisites", $values);

		break;
	}
	*/
	
	
	
	
	echo "<br><br>Finished.";

	
	
	
?>