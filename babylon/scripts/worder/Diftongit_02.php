<?php



// Etsitään kaikki eu-sisältävät sanat


$sql = "SELECT Wordform FROM worder_wordforms";
echo "<br>sql - " . $sql;

global $mysqli;
$result = $mysqli->query($sql);
$foundwords = array();

$counter = 0;
$counter2 = 0;

while($row = $result->fetch_array()) {
	
	$form = $row['Wordform'];
	
	if (strpos($form, 'eu') !== false) {
		echo "<br>Eu found - "  .$form;
	}
	
	$counter++;
	//if ($counter > 10000) break;
}
echo "<br>Finished.";


function isVowel($char) {

	if ($char == 'a') return true;
	if ($char == 'e') return true;
	if ($char == 'i') return true;
	if ($char == 'o') return true;
	if ($char == 'u') return true;
	if ($char == 'y') return true;
	if ($char == 'ä') return true;
	if ($char == 'ö') return true;
	return false;
}


?>