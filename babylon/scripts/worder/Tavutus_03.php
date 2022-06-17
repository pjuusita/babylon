<?php


// Laskee erilaisten tavujen lukumäärät...



$sql = "SELECT RowID, Wordform, Hyphenation FROM worder_wordforms WHERE LanguageID=1 AND GrammarID=1 AND SystemID=5";
echo "<br>sql - " . $sql;

global $mysqli;
$result = $mysqli->query($sql);
$forms = array();
$rowindexes = array();
$counter = 0;
$foundforms = array();

global $mysqli;
$result = $mysqli->query($sql);


while($row = $result->fetch_array()) {
	
	$form = $row['Hyphenation'];
	$rowID = $row['RowID'];
	
	$parts1 = explode(" ",$form);
	foreach($parts1 as $index => $part1) {
		$parts2 = explode("-",$part1);
		foreach($parts2 as $index => $part2) {
			
			//echo "<br>Part '" . $part2 . "'";
			
			if (isset($foundforms[$part2])) {
				$foundforms[$part2] = $foundforms[$part2] + 1;
			} else {
				$foundforms[$part2] = 1;
			}
		}
	}
	$counter++;
	//if ($counter > 1000) break;
}

echo "<br><br>Foundforms - " . count($foundforms);
$counter = 1;
foreach ($foundforms as $form => $count) {
	echo "<br>" . $counter . ". " . $form . " = " . $count;
	$counter++;
}




?>