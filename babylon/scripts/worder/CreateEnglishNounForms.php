<?php

    // Tällä mitä ilmeisimmin luodaan pelkästään monikkomuotoja


	function endsWith( $haystack, $needle ) {
		$length = strlen( $needle );
		if( !$length ) {
			return true;
		}
		return substr( $haystack, -$length ) === $needle;
	}
	

	// language = english, wordclass = noun
	$sql = "SELECT * FROM worder_words WHERE LanguageID=2 AND WordclassID=1 AND GrammarID=1";

	$missing = array();
	global $mysqli;
	$languages = array();
	$wordclasses = array();
	$words = array();
	
	$result = $mysqli->query($sql);
	while($row = $result->fetch_array()) {
		//echo "<br>" . $row['Lemma'] . " - " . $row['Inflection'];
		$wordID = $row['WordID'];
		$languages[$wordID] = $row['LanguageID'];
		$wordclasses[$wordID] = $row['WordclassID'];
		
		$words[$wordID] = $row['Lemma'];
	}
	echo "<br>Wordcount - " . count($words);
	
	

	// language = english, wordclass = noun
	$sql = "SELECT * FROM worder_wordforms WHERE LanguageID=2 AND WordclassID=1 AND GrammarID=1";
	echo "<br>sql - " . $sql;
	
	$forms = array();
	$formwordIDs = array();
	$formlists = array();
	$rarities = array();
	
	global $mysqli;
	$result = $mysqli->query($sql);
	$index = 0;
	while($row = $result->fetch_array()) {
		$forms[$index] = $row['Wordform'];
		$formwordIDs[$index] = $row['WordID'];
		$formlists[$index] = $row['Features'];
		//$rarities[$index] = $row['Rarity'];
		
		$index++;
	}
	
	echo "<br>Formcount - " . $index;
	echo "<br><br>------------";
	
	
	
	
	
	
	$counter=0;
	foreach($words as $wordID => $lemma) {
		
		$formpresent = false;
		$pluralstring = "";
		foreach($formwordIDs as $index => $formwordID) {
			if ($formwordID == $wordID) {
				//echo "<br>WordFound - " . $lemma . " - " . $wordID;
				$features = $formlists[$index];
				//$rarity = $rarities[$index];
				if (($features == '133:458')) { //&& ($rarity == 0)) {
					$formpresent = true;
					$pluralstring = $forms[$index];
				}
			}
		}
		
		if ($formpresent == true) {

			echo "<br>Form found - " . $lemma;
			
			if (endsWith($lemma,"y")) {
				echo "<br>--- Plural found - " . $pluralstring . " -- " . $wordID;
			}
			
				
			/*
			 	
			if (endsWith($lemma,"y") && ($found == false)) {
				//echo "<br>--- Plural found - " . $pluralstring . " -- " . $wordID;;
			}
			
			if (endsWith($lemma,"is") && ($found == false)) {
				echo "<br>--- Plural found - " . $pluralstring . " -- " . $wordID;;
			}
			*/
			
			/*
			if (endsWith($lemma,"fe") && ($found == false)) {
				echo "<br>--- Plural found - " . $pluralstring . " -- " . $wordID;;
			}
				
			if (endsWith($lemma,"f") && ($found == false)) {
				echo "<br>--- Plural found - " . $pluralstring . " -- " . $wordID;;
			}
			*/
			
		} else {
			$found = false;
			echo "<br>-------------- Form not found - " . $lemma;
				
			
			/*
			if (endsWith($lemma,"cs") && ($found == false)) {
				echo "<br>--- generoi plural, loppuu -s, monikko = " . $lemma . "es -- " . $wordID;
				$found = true;
			}
			*/
				
			/*
			if (endsWith($lemma,"s") && ($found == false)) {
				//echo "<br>--- generoi plural, loppuu -s, monikko = " . $lemma . "es -- " . $wordID;
				$found = true;
			}

			if (endsWith($lemma,"is") && ($found == false)) {
				$endi = substr($lemma, strlen($lemma)-2,1);
				
				echo "<br>--- generoi plural, loppuu -is, " . $lemma . " -- monikko = " . $endi . "es -- " . $wordID;
				$found = true;
			}


			if (endsWith($lemma,"o") && ($found == false)) {
				$endi = substr($lemma, strlen($lemma)-2,1);
			
				echo "<br>--- generoi plural, loppuu -o, " . $lemma . " -- monikko = " . $lemma . "es -- " . $wordID;
				$found = true;
			}
			*/
			
			/*
			if (endsWith($lemma,"fe") && ($found == false)) {
				echo "<br>--- generoi plural, loppuu -f, monikko = " . $lemma . "+ves -- " . $wordID;
				$found = true;
			}
				
			if (endsWith($lemma,"f") && ($found == false)) {
				echo "<br>--- generoi plural, loppuu -fe, monikko = " . $lemma . "+ves -- " . $wordID;
				$found = true;
			}
			*/
			
			
			if (endsWith($lemma,"y") && ($found == false)) {
				
				$endi = substr($lemma, strlen($lemma)-2,1);
				if (($endi == 'a') || ($endi == 'e') || ($endi == 'i') || ($endi == 'o') || ($endi == 'u') || ($endi == 'y') || ($endi == 'ä') || ($endi == 'ö')) {
					echo "<br>endi - " . $endi;
					echo "<br>--- generoi plural, loppuu -y, monikko = " . $lemma . " -- s  .. " . $wordID;
					$found = true;
					
					/*
					$found = false;
					$sql = "INSERT INTO worder_wordforms (Wordform, WordID, Features, Grammatical, SystemID, GrammarID, LanguageID, WordclassID, Rarity) VALUES ('" . $lemma . "s',"  . $wordID . ", '133:458',1, 5, 1, 2, 1, 0)";
					echo "<br>Sql - " . $sql;
					
					$result = $mysqli->query($sql);
					if (!$result) {
						die("Error 1: " . $mysqli->connect_error);
					}
										 
					
					 $found = false;
					 $sql = "INSERT INTO worder_wordfeaturelinks (WordID, FeatureID, ValueID, InheritancemodeID, SystemID, GrammarID) VALUES (" . $wordID . ",460,476, 1, 5, 1)";
					 echo "<br>Sql - " . $sql;
					 	
					 $result = $mysqli->query($sql);
					 if (!$result) {
					 	die("Error 1: " . $mysqli->connect_error);
					 }
					 */	

					 
				} else {
					echo "<br>endi - " . $endi;
					//echo "<br>--- generoi plural, loppuu -y, monikko = " . $lemma . " --- ies";
					
					$subendi = substr($lemma, 0, strlen($lemma)-1);
					echo "<br>--- generoi plural, loppuu -y, monikko = " . $lemma . " --> " . $subendi . "ies -- " . $wordID;


					$found = false;
					$sql = "INSERT INTO worder_wordforms (Wordform, WordID, Features, Grammatical, SystemID, GrammarID, LanguageID, WordclassID, Rarity) VALUES ('" . $subendi . "ies',"  . $wordID . ", '133:458',1, 5, 1, 2, 1, 0)";
					//echo "<br>Sql - " . $sql;
						
					//$result = $mysqli->query($sql);
					if (!$result) {
						die("Error 1: " . $mysqli->connect_error);
					}
						
					$found = false;
					$sql = "INSERT INTO worder_wordfeaturelinks (WordID, FeatureID, ValueID, InheritancemodeID, SystemID, GrammarID) VALUES (" . $wordID . ",460,461, 1, 5, 1)";
					//echo "<br>Sql - " . $sql;
						
					//$result = $mysqli->query($sql);
					if (!$result) {
						die("Error 1: " . $mysqli->connect_error);
					}
						
					$found = true;
				}
				
				$comments = false;
				$saveaction = true;
				$featureID = 460;
				$valueID = 461;
				
				/*
				$word = Table::loadRow("worder_words","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID, $comments);
				$existingFeatures = Table::load("worder_wordfeaturelinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordID=" . $wordID . " AND FeatureID=" . $featureID );
				
				$newfeaturelist = array();
				foreach($existingFeatures as $index => $link) {
					$newfeaturekey = $link->featureID . ":" . $link->valueID . ":" . $link->wordID;
					$newfeaturelist[] = $newfeaturekey;
					//echo "<br>Existting features - " . $newfeaturekey;
				}
					
				$updatevalues = array();
				$updatevalues['Features'] = implode('|', $newfeaturelist);
				//if ($saveaction) Table::updateRow('worder_words', $updatevalues, "WHERE GrammarID=1 AND WordID=" . $wordID, $comments);
				if ($comments) {
					echo "<br>Updating worder_words<br>";
					print_r($updatevalues);
				}
				*/
				$counter++;
				
			}
			if ($counter > 10) { 
				echo "<br><br>Breakki.";
				break;
			}
		}
		
		
		//if ($counter > 100) break;
	}
	
	

?>