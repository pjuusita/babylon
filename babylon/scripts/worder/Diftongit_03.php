<?php



// Etsitään kaikki kolme konsonanttia-sisältävät sanat


$sql = "SELECT Lemma FROM worder_words WHERE LanguageID=1";
echo "<br>sql - " . $sql;

global $mysqli;
$result = $mysqli->query($sql);
$foundwords = array();

$counter = 0;
$counter2 = 0;
$consonantcounter = 0;

while($row = $result->fetch_array()) {
	
	$form = $row['Lemma'];
	$form = strtolower($form);
	//echo "<br>Form - "  . $form;
	$pos = 0;
	$consonantcounter = 0;
	while($pos < mb_strlen($form)) {
	
		//$char = substr($word, $pos, 1);
		$char = mb_substr($form,$pos,1,'utf-8');
		
		if (isVowel($char) ) {
			//echo "<br>- Vowel - " . $char;
			$consonantcounter = 0;
		} else {
			$consonantcounter++;
		}
		if ($consonantcounter > 2) {
			echo "<br>Three consonants found - " . $form;
			$hyp = hyphenate($form, false);
			echo " -- " . $hyp;
			break;
		}
		$pos++;
	}
	$counter++;
	//if ($counter > 100) break;
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



function hyphenate($word, $comments) {

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
				$result = $result . "-" . $current . " ";
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


?>