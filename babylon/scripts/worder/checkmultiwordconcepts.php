<?php

	// Listataan kaikki conceptit, joissa on useampi kuin yksi sana kiinnitettynä.
	// Näihin tapauksiin pitäisi asettaa ainoastaan yksi sana defaultiksi...
	
	global $mysqli;

	$sql = "SELECT * FROM worder_conceptwordlinks WHERE SystemID=5 AND GrammarID=1 ORDER BY ConceptID";
	echo "<br>sql - " . $sql;

	$result = $mysqli->query($sql);
	$keylist = array();
	$previousconceptID = -1;
	$doubles = array();
	$counter = 0;
	while($row = $result->fetch_array()) {
		
		$conceptID = $row['ConceptID'];
		if ($conceptID != $previousconceptID) {
			$doubles = array();
		} else {
			$languageID = $row['LanguageID'];
			$defaultword = $row['Defaultword'];
			if ($defaultword == 1) {
				if (isset($doubles[$languageID])) {
					$counter++;
					echo "<br>" . $counter . ". Double found - " . $conceptID;
					echo " <a href='https://www.babelsoft.fi/demo/index.php?rt=worder/concepts/showconcept&id=" . $conceptID . "'>link</a>";
				} else {
					$doubles[$languageID] = 1;
				}
			}
		}
		$previousconceptID = $conceptID;
	}
	echo "<br>..............";
	
	// TODO: Pitäisi tsekata, että kaikissa sanoissa on oletussana asetettu


?>