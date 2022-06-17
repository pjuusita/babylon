<?php






$sql = "SELECT WordID, Lemma FROM worder_words WHERE LanguageID=1 AND GrammarID=1 AND SystemID=5";
echo "<br>sql - " . $sql;

global $mysqli;
$result = $mysqli->query($sql);
$foundwords = array();

$counter = 0;
$counter2 = 0;
$conscounter = 0;

while($row = $result->fetch_array()) {
	//if ($counter2 == 10) {
		$lemma = $row['Lemma'];
		$wordID = $row['WordID'];
		$lemma = trim($lemma);
		//echo "<br>";
		echo "<br>Counter - " . $counter . " -" . $lemma . "-" . strlen($lemma);
		$comments = false;
		//if ($counter == 122 ) $comments = true;
		$boole = hasThreeConsonants(strtolower($lemma), $comments);
		if ($boole == true) {
			echo "<br> " . $wordID . " ------- " . $lemma;
			$conscounter++;
		}
		$counter2 = 0;
	//}
	
	$counter++;
	$counter2++;
	//if ($counter > 1000) break;
}

echo "<br>Conscounter - " . $conscounter;

function hasThreeConsonants($word, $comments) {
	$word = ($word);

	
	$parts = explode('-', $word);
	
	
	$pos = 0;
	$current = "";
	$consonantcounter = 0;
	while($pos < mb_strlen($word)) {
		
		//$char = substr($word, $pos, 1);
		$char = mb_substr($word,$pos,1,'utf-8');
		
		//echo "<br>" . $pos . " - " . $char;
		
		if (isVowel($char) ) {
			if ($comments) echo "<br>- Vowel - " . $char;
			$consonantcounter = 0;
		} else {
			if ($char == " ") {
				$consonantcounter = 0;
			} else {
				if ($comments) echo "<br>- Consonant - '" . $char . "'";
				$consonantcounter++;
			}
		}

		if ($consonantcounter > 2) {
			echo "<br> -- three - "  . $char;
			return true;
		}
		
		$pos++;
	}
	//echo "<br>***" . $current;
	return false;
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