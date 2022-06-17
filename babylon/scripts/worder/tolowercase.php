<?php


$sql = "SELECT * FROM worder_wordforms WHERE LanguageID=2";

$missing = array();
global $mysqli;
$languages = array();
$wordclasses = array();
$words = array();

$counter = 0;
$result = $mysqli->query($sql);
$words = array();
$forms = array();
while($row = $result->fetch_array()) {
	$wordform = $row['Wordform'];
	$lower = strtolower($wordform);
	$wordID = $row['WordID'];
	if ($wordform != $lower) {
		echo "<br> - " . $wordID . " - " . $wordform . " - " . $lower;
		$counter++;
		$words[$wordID] = $wordID;
		$rowID = $row['RowID'];
		$forms[$rowID] = $lower;
	}
	
}
echo "<br>Wordcount - " . $counter;
echo "<br>Wordcount - " . count($words);




echo "<br><br><br>";
foreach($forms as $rowID => $lower) {

	$sql = "UPDATE worder_wordforms SET Wordform='" . $lower . "' WHERE RowID=" . $rowID;
	//$result = $mysqli->query($sql);
	echo "<br> - sql - " . $sql;
}



echo "<br><br><br>";
foreach($words as $rowID => $wordID) {

	$sql = "UPDATE worder_words SET Casemarking='1' WHERE WordID=" . $wordID;
	//$result = $mysqli->query($sql);
	echo "<br> - sql - " . $sql;
}


?>