<?php






$sql = "SELECT RowID, Wordform FROM worder_wordforms WHERE LanguageID=1 AND GrammarID=1 AND SystemID=5 AND Hyphenation IS NULL";
echo "<br>sql - " . $sql;

global $mysqli;
$result = $mysqli->query($sql);
$forms = array();
$rowindexes = array();
$counter = 0;

global $mysqli;
$result = $mysqli->query($sql);


while($row = $result->fetch_array()) {
	
	$form = $row['Wordform'];
	$rowID = $row['RowID'];
	
	$forms[] = $form;
	$rowindexes[] = $rowID;
	
	$counter++;
	if ($counter > 5000) break;
}



foreach($forms as $index => $form) {
	echo "<br>";
	$result = hyphenate($form);
	echo "<br>Result - " . $result;
	$rowID = $rowindexes[$index];
	echo "<br>" . $rowID . " - " . $form . " - " . $result;	
	
	
	$sql = "UPDATE worder_wordforms SET Hyphenation='" . $result . "' WHERE RowID=" . $rowID;
	echo "<br>sql - " . $sql;
	$result = $mysqli->query($sql);
}
echo "<br><br>Finnish";




function hyphenate($word, $comments = false) {
	
	$result = null;
	$parts1 = explode(" ",$word);
	
	foreach($parts1 as $index => $part1) {
		//echo "<br>Part1 - " . $part1;
	
		$result2 = null;
		$parts2 = explode("|",$part1);
		foreach($parts2 as $index => $part2) {
			//echo "<br>Part2 - " . $part2;
				
			$form = hyphenateSingle($part2, false);
			//echo "<br> --- Result: " . $form;
				
			$char = mb_substr($form,0,1,'utf-8');
			if ($char == "-") {
				$form = mb_substr($form,1);
			}
			if ($result2 == null) {
				$result2 = $form;
			} else {
				$result2 = $result2 . "-" . $form;
			}
		}
	
		if ($result == null) {
			$result = $form;
		} else {
			$result = $result . " " . $result2;
		}
	}
	return $result;
}



function hyphenateSingle($word, $comments) {

	$pos = 0;
	$current = "";
	$fisrt = true;
	$consonantcounter = 0;
	$result = "";
	
	while($pos < mb_strlen($word)) {

		//$char = substr($word, $pos, 1);
		$char = mb_substr($word,$pos,1,'utf-8');
		
		if ($comments) echo "<br>" . $pos . " - " . $char;
		
		if (isVowel($char) ) {
			
			$prev = mb_substr($word,$pos-1,1,'utf-8');
			if ($pos == 0) $prev = "k";
			if (isVowel($prev)) {
				// tavuviiva ei koskaan ole kahden saman vokaalin välissä?
				if ($char == $prev) {
					if ($comments) echo "<br>- Vowel - " . $char;
					$current = $current . "" . $char;
					$consonantcounter = 0;
				} else {
					$isDiftong = false;
					if ($prev == "a") {
						if ($char == "i") $isDiftong = true;
						if ($char == "u") $isDiftong = true;
					}
					if ($prev == "e") {
						if (($char == "i")) $isDiftong = true;
						if ($char == "y") $isDiftong = true;
						if ($char == "u") $isDiftong = true;
					}
					if ($prev == "o") {
						if ($char == "i") $isDiftong = true;
						if ($char == "u") $isDiftong = true;
				
					}
					if ($prev == "ä") {
						if ($char == "i") $isDiftong = true;
						if ($char == "y") $isDiftong = true;
				
					}
					if ($prev == "ö") {
						if ($char == "i") $isDiftong = true;
						if ($char == "y") $isDiftong = true;
				
					}
					if ($prev == "u") {
						if ($char == "i") $isDiftong = true;
						if ($char == "o") $isDiftong = true;
					}
					if ($prev == "y") {
						if ($char == "i") $isDiftong = true;
						if ($char == "ö") $isDiftong = true;
					}
					if ($prev == "i") {
						if ($char == "u") $isDiftong = true;
						if ($char == "y") $isDiftong = true;
						if (($char == "e") && ($pos < 3)) $isDiftong = true;
					}
					
					
					
					
					if ($isDiftong == true) {
						
						$curlength = mb_strlen($current);
						$prevprev = "k";
						if ($curlength > 1)	$prevprev = mb_substr($current,$curlength-2,1,'utf-8');
						if (isVowel($prevprev)) {
							if ($comments) echo "<br>Three vowels - " . $word;
							$result = $result . "-" . $current;
							$current = $char;
							$consonantcounter = 0;
							if ($comments) echo "<br>Is diftong - " . $prevprev . " - " . $prev . " - " . $char;
							//$current = $current . "" . $char;
							//$consonantcounter = 0;
						} else {
							if ($comments) echo "<br>Is diftong - " . $char . " - " . $prev;
							$current = $current . "" . $char;
							$consonantcounter = 0;
						}
					} else {
						
						// Tsekataan vielä onko seuraavakin vokaali, tällöin pitää tehdä väli
						if ($comments) echo "<br>Is not diftong - " . $char . " - " . $prev;
						$result = $result . "-" . $current;
						$current = $char;
						$consonantcounter = 0;
						
					}
				}
			} else {
				if ($comments) echo "<br>- Vowel - " . $char;
				$current = $current . "" . $char;
				$consonantcounter = 0;
			}
			
			
			
		} else {
			if ($char == " ") {
				$consonantcounter = 0;
				$result = $result . "||" . $current;
				$current = "";
			} else {
				
				$next1 = mb_substr($word,$pos+1,1,'utf-8');
				if (!isVowel($next1)) {
					if ($comments) echo "<br>Next not is vovel";
					$current = $current . "" . $char;
					$consonantcounter++;
				} else {
					if ($comments) echo "<br>Next is vovel";
					if ($current != "") {
						if ($comments) echo "<br>Current not empty";
						$result = $result . "-" . $current;
						$current = $char;
						//$current = "";
					} else {
						if ($comments) echo "<br>Current empty";
						$current = $current . $char;
					}
					if ($comments) echo "<br>- Consonant - '" . $char . "'";
				}
			}
		}

		if ($consonantcounter > 2) {
			echo "<br> -- three - "  . $char;
			//return true;
		}
		
		$pos++;
	}
	if ($current != "") {
		$result = $result . "-" . $current;
		$current = "";
	}
	
	return $result;
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