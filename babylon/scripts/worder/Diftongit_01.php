<?php






$sql = "SELECT Wordform FROM worder_wordforms";
echo "<br>sql - " . $sql;

global $mysqli;
$result = $mysqli->query($sql);
$foundwords = array();

$counter = 0;
$counter2 = 0;

while($row = $result->fetch_array()) {
	
	if ($counter2 == 10) {
		$wordform = utf8_decode($row['Wordform']);
		echo "<br>";
		echo "<br>Counter - " . $counter . " - " . $wordform;
		getParts($wordform);
		$counter2 = 0;
	}
	
	$counter++;
	$counter2++;
	if ($counter > 100) break;
}


function endsWith($string, $endString)
{
	$len = strlen($endString);
	if ($len == 0) {
		return true;
	}
	return (substr($string, -$len) === $endString);
}


function parseEnding($word) {
	
	
}


function getParts($word) {
	
	$word = ($word);
	echo "<br> - Parts - " . $word;
	
	$chars = str_split($word);
	foreach($chars as $index => $char) {
		echo "<br>" . $index . " - " . $char;
	}
	
	$pos = 0;
	$current = "";
	while($pos < sizeof($chars)) {
		
		$char = $chars[$pos];
		echo "<br>" . $pos . " - " . $char;
		
		if (isVowel($char) ) {
			$current = $current . $char;
		} else {
			if ($current == "") {
				$current = $current . $char;
			} else {
				echo "<br>*** " . $current;
				$current = $char;
			}
		}
		
		$pos++;
	}
	echo "<br>***" . $current;
}


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