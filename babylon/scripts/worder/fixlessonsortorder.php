<?php


// Lessoneiden sorttaus sekoittuu jossainvaiheessa kun näitä on veivattu, epäselvää
// onko kyseessä olemassaoleva bugi vai onko bugi jo korjattu, mutta vanhoja virheellisiä
// sortordereita edelleen esiintyy. 
 
// Hoidetaan homma niin, että haetaan kaikki lessonit, järjestetään ne nykyisen mukaan
// ja sen jälkeen päivitetään sortorder kenttä uudelleen olemassaolevien ID-numeroiden 
// mukaiseksi...

	$languageID = 1;  // suomi	

	global $mysqli;
	$sql = 'SELECT * FROM worder_lessons WHERE SystemID=5 AND GrammarID=' . $_SESSION['grammarID'] . ' AND LanguageID=' . $languageID . ' ORDER BY Sortorder';
	echo "<br>sql - " . $sql;

	$result = $mysqli->query($sql);
	$keylist = array();
	$indexlist = array();
	$counter = 1;
	while($row = $result->fetch_array()) {
		echo "<br>"  . $counter . " - " . $row['LessonID'] . " - " . $row['Sortorder'];
		$keylist[$counter] = $row['LessonID'];		
		$indexlist[$counter] = $row['LessonID'];		
		$counter++;
	}
	
	asort($keylist);
	echo "<br>----------------------------";
	echo "<br>----------------------------";
	
	$tempcounter = 1;
	foreach($keylist as $counter => $lessonID) {
		
		echo "<br>counter: " . $counter . ", lessonID: " . $lessonID . ", orig: " . $indexlist[$tempcounter];
		
		$sql = "UPDATE worder_lessons SET Sortorder=" . $lessonID . " WHERE LessonID=" . $indexlist[$tempcounter];
		echo "<br>" . $sql;
		
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>Error: " . $mysqli->error;
			exit();
		}
		$tempcounter++;
	}
	

?>