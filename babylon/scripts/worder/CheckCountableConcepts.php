<?php
	
	// Tarkistetaan että kaikilla substantiivi concepteilla on Countable tai Uncountable component, ainoastaan yksi 
	//	  Countable = 6, Uncountable = 7, Single = 3

	
	global $mysqli;

	$sql = "SELECT * FROM worder_conceptcomponentlinks WHERE (ComponentID=6 OR ComponentID=7) AND GrammarID=" . $_SESSION['grammarID'];
	echo "<br>SQL - " . $sql;
	$conceptlist = array();
	$result = $mysqli->query($sql);
	$doublecounter = 0;
	$rowsfordelete = array();
	
	while($row = $result->fetch_array()) {
		$conceptID = $row['ConceptID'];
		$componentID = $row['ComponentID'];
		
		if (isset($conceptlist[$conceptID])) {
			echo "<br>-- double concept found - " . $conceptID;
			if ($componentID == $conceptlist[$conceptID]) {
				//echo "<br>-- identical can be deleted - " . $conceptID;
				$rowID = $row['RowID'];
				//$sql = "DELETE FROM worder_conceptcomponentlinks WHERE RowID=" . $rowID;
				//echo "<br>-- sql - " . $sql;
				$rowsfordelete[$rowID] = $rowID;
			} else {
				echo "<br>****************** non identical - " . $conceptID;
			}
			$doublecounter++;
		}
		if ($conceptID == 24) echo "<br>---- 24 found";
		$conceptlist[$conceptID] = $componentID;
	}
	
	if ($doublecounter > 0) {
		
		foreach($rowsfordelete as $index => $rowID) {
			$sql = "DELETE FROM worder_conceptcomponentlinks WHERE RowID=" . $rowID;
			echo "<br>-- sql - " . $sql;
			//$result = $mysqli->query($sql);
			
		}
		
		
		echo "<br><br>Doubles found - " . $doublecounter;
		exit;
	}
	echo "<br>Conceptlist - " . count($conceptlist);
		
	echo "<br><br>";
	$sql = "SELECT ConceptID FROM worder_concepts WHERE WordclassID=1 AND GrammarID=" . $_SESSION['grammarID'];
	echo "<br>SQL - " . $sql;
	$wordconcepts = array();
	$result = $mysqli->query($sql);
	$notfoundcounter = 0;
	
	while($row = $result->fetch_array()) {
		$conceptID = $row['ConceptID'];
		if (!isset($conceptlist[$conceptID])) {
			//echo "<br>--- Concept not found - " . $conceptID;		
			echo "<br> - " . $notfoundcounter . " - not found - <a target=_blank href='https://www.babelsoft.fi/demo/index.php?rt=worder/concepts/showconcept&id=" . $conceptID . "'>link</a>";
				
			$notfoundcounter++;
		} else {
			//echo "<br>Concept found - " . $conceptID;
		}
	}
		
	echo "<br>Notfoundcounter = " . $notfoundcounter;
		

?>