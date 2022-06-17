<?php


$sql = "SELECT Wordform, WordID FROM worder_finnish_wordforms";
echo "<br>sql - " . $sql;

global $mysqli;
$result = $mysqli->query($sql);
$foundwords = array();
while($row = $result->fetch_array()) {
	$wordform = $row['Wordform'];
	
	if (isset($foundwords[$wordform])) {
		echo "<br>Double - " . $wordform . " - " . $foundwords[$wordform];		
	} else {
		$foundwords[$wordform] = $row['WordID'];
	}
}


?>