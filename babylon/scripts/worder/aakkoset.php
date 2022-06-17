<?php


global $mysqli;
$sql = "SELECT SentenceID, Sentence FROM worder_sentences";

$result = $mysqli->query($sql);
$sentences = array();
$counter = 0;
while($row = $result->fetch_array()) {
	$sentence = $row['Sentence'];
	if (strpos($sentence,"?") !== false) {
		$sentenceID = $row['SentenceID'];
		echo "<br>" . $sentenceID . " - " . $sentence;
		$counter++;
		$sentences[$sentenceID] = $sentenceID;
	}
	
}
echo "<br>Rulecount - " . $counter;

$newstrings = array();
$sql = "SELECT SentenceID, Sentence FROM worder_sentences2";

echo "<br>sentenceID - " . $sentenceID;
$result = $mysqli->query($sql);
while($row = $result->fetch_array()) {
	if (isset($sentences[$row['SentenceID']])) {
		echo "<br>sentti - " . $row['SentenceID'] . " - " . $row['Sentence'];
		$newstrings[$row['SentenceID']] = $row['Sentence'];
	}
}



foreach($newstrings as $sentenceID => $sentencestr) {
	$sql = "UPDATE worder_sentences SET Sentence='" . $sentencestr . "' WHERE SentenceID=" . $sentenceID;
	echo "<br>sentenceID - " . $sentenceID;
	$result = $mysqli->query($sql);	
	//break;
}


?>