<?php


	function endsWith( $haystack, $needle ) {
		$length = strlen( $needle );
		if( !$length ) {
			return true;
		}
		return substr( $haystack, -$length ) === $needle;
	}
	

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
	
	

	$sql = "SELECT * FROM worder_wordforms WHERE LanguageID=2 AND WordclassID=1 AND GrammarID=1";
	echo "<br>sql - " . $sql;
	
	$forms = array();
	$formwordIDs = array();
	$formlists = array();
	
	global $mysqli;
	$result = $mysqli->query($sql);
	$index = 0;
	while($row = $result->fetch_array()) {
		$forms[$index] = $row['Wordform'];
		$formwordIDs[$index] = $row['WordID'];
		$formlists[$index] = $row['Features'];
		
		$index++;
	}
	
	echo "<br>Formcount - " . $index;
	echo "<br><br>------------";
	
	
	
	
	
	
	$counter=0;
	$endingcounter = 0;
	foreach($words as $wordID => $lemma) {
		
		$singularpresent = false;
		$singularstring = "";
		foreach($formwordIDs as $index => $formwordID) {
			if ($formwordID == $wordID) {
				//echo "<br>WordFound - " . $lemma . " - " . $wordID;
				$features = $formlists[$index];
				if ($features == '133:458') {
					$singularpresent = true;
					$singularstring = $forms[$index];
				}
			}
		}
		
		if ($singularpresent == true) {

			//echo "<br>--- Plural found - " . $singularstring . " -- " . $wordID;;
				
		
			
		} else {
			
			echo "<br>--- " . $counter . " ---- Plural not found - " . $wordID . " -- " . $lemma;
			//echo " - <button id=button" . $wordID . " onclick='addpluralbuttonpressed(" . $wordID . ")'>use s-ending</button>";
			echo " - <a id=button" . $wordID . " onclick='addpluralbuttonpressed(" . $wordID . ")' style='text-decoration:underline;cursor:pointer;color:blue;'>use -s ending</a>";
			echo " - <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/words/showword&id=" . $wordID . "'>use -s ending</a>";
			
			if (endsWith($lemma,"y")) {
				echo "<br>--- Plural not found - " . $lemma . " -- " . $wordID . " --- ends with y ";
				echo "<a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/words/showword&id=" . $wordID . "'>link</a>";
				
				$endingcounter++;
			}
			
			if (endsWith($lemma,"is")) {
				echo "<br>--- Plural not found - " . $lemma . " -- " . $wordID;
			}
			
			if (endsWith($lemma,"fe")) {
				echo "<br>--- Plural not found - " . $lemma . " -- " . $wordID;;
			}
			
			if (endsWith($lemma,"f")) {
				echo "<br>--- Plural found - " . $lemma . " -- " . $wordID;
				echo " <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/words/showword&id=" . $wordID . "'>link</a>";
			}
			
			if (endsWith($lemma,"cs")) {
				echo "<br>--- generoi plural, loppuu -s, monikko = " . $lemma . "es -- " . $wordID;
				$found = true;
			}
			
			// Normaali -es pääte: -s, -sh, -ch, -x, -z
			
			if (endsWith($lemma,"s")) {
				//echo "<br>--- generoi plural, loppuu -s, monikko = " . $lemma . "es -- " . $wordID;
				$found = true;
			}
			
			if (endsWith($lemma,"ch")) {
				echo "<br>--- generoi plural, loppuu -ch, " . $lemma . " -- " . $wordID;
				echo " <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/words/showword&id=" . $wordID . "'>link</a>";
			}
			
			if (endsWith($lemma,"x")) {
				echo "<br>--- generoi plural, loppuu -x, " . $lemma . " -- " . $wordID;
				echo " <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/words/showword&id=" . $wordID . "'>link</a>";
			}

			if (endsWith($lemma,"ss")) {
				echo "<br>--- generoi plural, loppuu -x, " . $lemma . " -- " . $wordID;
				echo " <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/words/showword&id=" . $wordID . "'>link</a>";
			}
				
			
			if (endsWith($lemma,"sh")) {
				echo "<br>--- generoi plural, loppuu -sh, " . $lemma . " -- " . $wordID;
				echo " <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/words/showword&id=" . $wordID . "'>link</a>";
			}
				
			
			if (endsWith($lemma,"z")) {
				echo "<br>--- generoi plural, loppuu -z, " . $lemma . " -- " . $wordID;
				echo " <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/words/showword&id=" . $wordID . "'>link</a>";
			}
				
			
			if (endsWith($lemma,"is")) {
				$endi = substr($lemma, strlen($lemma)-2,1);
				echo "<br>--- generoi plural, loppuu -is, " . $lemma . " -- monikko = " . $endi . "es -- " . $wordID;
			}
			
			
			if (endsWith($lemma,"o")) {
				$endi = substr($lemma, strlen($lemma)-2,1);
					
				echo "<br>--- generoi plural, loppuu -o, " . $lemma . " -- monikko = " . $lemma . "es -- " . $wordID;
				echo " <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/words/showword&id=" . $wordID . "'>link</a>";
			}
			
			
			/*
			$found = false;
			$sql = "INSERT INTO worder_wordforms (Wordform, WordID, Features, Grammatical, SystemID, GrammarID, LanguageID, WordclassID) VALUES ('" . $lemma . "',"  . $wordID . ", '132:458',1, 5, 1, 2, 1)";
			echo "<br>Sql - " . $sql;
			
			$result = $mysqli->query($sql);
			if (!$result) {
				die("Error 1: " . $mysqli->connect_error);
			}
			*/
			$counter++;
		}
		
		if ($counter > 10000) {
			echo "<br>Breakki Counter - " . $counter;
			break;
		}
	}
	
	echo "<br>Counter - " . $counter;
	echo "<br>Endcounter - " . $endingcounter;
	
	

	echo "	<script>";
	echo "		function addpluralbuttonpressed(wordID) {";
	
	//echo "			alert('button pressed - '+wordID);";
	echo "			console.log('url - " . getUrl('worder/words/setenglishpluralform') . "&wordID='+wordID);";
	
	echo "			$.getJSON('" . getUrl('worder/words/setenglishpluralform') . "&wordID='+wordID,'',function(data) {";
	echo "				console.log('success - '+data);";
	echo "				$('#button'+wordID).hide();";
	echo "			}); ";
	/*
	echo "			console.log('search button pressed');";
	echo "			var searh = $('#searchsentencefield').val();";
	echo "			if (searh == '') {";
	echo "				alert('ei saa olla tyhjä 2');";
	echo "			}";
	echo "			$('#searchsentenceloadingdiv').show();";
	echo "			$('#searchsentenceloadeddiv').hide();";
	//echo "			var languageID = $('#languagefield').val();";
	echo "			var languageID = " . $registry->rule->languageID . ";";
	echo "			console.log('languageid -'+languageID+'-');";
	echo "			console.log('" . getUrl('worder/sentences/searchsentences') . "&search='+searh+'&languageID='+languageID);";
	
	echo "			$.getJSON('" . getUrl('worder/sentences/searchsentences') . "&search='+searh+'&languageID='+languageID,'',function(data) {";
	echo "					console.log('data.length aa - '+data.length);";
	echo "					$('#searchsentenceloadingdiv').hide();";
	echo "					$('#searchsentenceloadeddiv').show();";
	echo "					$('#searchsentenceresulttable tr').remove();";
	echo "					$.each(data, function(index) {";
	echo "						console.log('row - '+data[index].sentenceID+' - '+data[index].sentence);";
	echo "						var row = '<tr>'";
	//echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].sentenceID+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].sentence+'</td>'";
	//echo "							+ '<td title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/words/showword') . "&lang='+languageID+'&id='+data[index].wordID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td><button onclick=\"addSentence(\''+data[index].sentenceID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#searchsentenceresulttable').append(row);";
	echo "					});";
	echo "			}); ";
	echo " 			console.log('finish');";
	*/
	echo "		}";
	echo "	</script>";
	
	
	
	
	
	// Gerate plurals ....
	/*
	$counter=0;
	foreach($words as $wordID => $lemma) {
	
		$singularpresent = false;
		$singularstring = "";
		foreach($formwordIDs as $index => $formwordID) {
			if ($formwordID == $wordID) {
				//echo "<br>WordFound - " . $lemma . " - " . $wordID;
				$features = $formlists[$index];
				$rarity = $rarities[$index];
				if (($features == '132:458') && ($rarity == 0)) {
					$singularpresent = true;
					$singularstring = $forms[$index];
				}
			}
		}
	
		if ($singularpresent == true) {
	
			echo "<br>--- Singular found - " . $singularstring . " -- " . $wordID;;
	
	
				
		} else {
			$found = false;
			$sql = "INSERT INTO worder_wordforms (Wordform, WordID, Features, Grammatical, SystemID, GrammarID, LanguageID, WordclassID, Rarity) VALUES ('" . $lemma . "',"  . $wordID . ", '132:458',1, 5, 1, 2, 1, 0)";
			echo "<br>Sql - " . $sql;

			/*
			$result = $mysqli->query($sql);
			if (!$result) {
				die("Error 1: " . $mysqli->connect_error);
			}
			* /
			$counter++;
		}
	
		if ($counter > 100) break;
	}
	*/
	
?>