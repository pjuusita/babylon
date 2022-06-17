<?php


	function endsWith( $haystack, $needle ) {
		$length = strlen( $needle );
		if( !$length ) {
			return true;
		}
		return substr( $haystack, -$length ) === $needle;
	}
	

	// language = english, wordclass = noun
	$sql = "SELECT * FROM worder_words WHERE LanguageID=1 AND WordclassID=1 AND GrammarID=1";

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
	$sql = "SELECT * FROM worder_wordfeaturelinks WHERE LanguageID=1 AND WordclassID=1 AND FeatureID=997";
	echo "<br>sql - " . $sql;
	
	
	
	
	$forms = array();
	$formwordIDs = array();
	$formlists = array();
	$rarities = array();
	
	global $mysqli;
	$result = $mysqli->query($sql);
	$index = 0;
	$rolefounds = array();
	while($row = $result->fetch_array()) {
		$wordID = $row['WordID'];
		$valueID = $row['ValueID'];
		$rolefounds[$wordID] = $valueID;
		$index++;
	}
	echo "<br>Formcount - " . $index;
	echo "<br><br>------------";
	
	
	
	$sql = "SELECT * FROM worder_wordforms WHERE LanguageID=1 AND WordclassID=1 AND Features='51:5'";
	echo "<br>sql - " . $sql;
	$adessives = array();
	$result = $mysqli->query($sql);
	$index = 0;
	$articletypes = array();
	while($row = $result->fetch_array()) {
		$wordID = $row['WordID'];
		$wordform = $row['Wordform'];
		$adessives[$wordID] = $wordform;
		$index++;
	}
	
	
	
	
	$features = Table::load("worder_features");
	$counter=0;
	foreach($words as $wordID => $lemma) {
		
		if (isset($rolefounds[$wordID])) {
			$featureID = $rolefounds[$wordID];
			$feature = $features[$featureID];
			//echo "<br>" . $lemma . " - " . $rolefounds[$wordID] . " - " . $feature->name;
				
		} else {
			
			$formstring = "--tuntematon--";
			if (isset($adessives[$wordID])) {
				$formstring = $adessives[$wordID];
				echo "<br>" . $lemma . " ... " . $wordID . " ... " . $formstring . " ..... ";
				echo " - <a id=button" . $wordID . "-1 onclick='addAdessiveRolePressed(" . $wordID . ",998)' style='text-decoration:underline;cursor:pointer;color:blue;'>Location</a>";
				echo " - <a id=button" . $wordID . "-2 onclick='addAdessiveRolePressed(" . $wordID . ",999)' style='text-decoration:underline;cursor:pointer;color:blue;'>Proximity</a>";
				echo " - <a id=button" . $wordID . "-3 onclick='addAdessiveRolePressed(" . $wordID . ",1000)' style='text-decoration:underline;cursor:pointer;color:blue;'>Surface</a>";
				echo " - <a id=button" . $wordID . "-4 onclick='addAdessiveRolePressed(" . $wordID . ",1001)' style='text-decoration:underline;cursor:pointer;color:blue;'>Instrument</a>";
				echo " - <a id=button" . $wordID . "-5 onclick='addAdessiveRolePressed(" . $wordID . ",1002)' style='text-decoration:underline;cursor:pointer;color:blue;'>Time</a>";
				echo " - <a id=button" . $wordID . "-6 onclick='addAdessiveRolePressed(" . $wordID . ",1003)' style='text-decoration:underline;cursor:pointer;color:blue;'>Owner</a>";
				echo " - <a id=button" . $wordID . "-7 onclick='addAdessiveRolePressed(" . $wordID . ",1004)' style='text-decoration:underline;cursor:pointer;color:blue;'>None</a>";
			} else {
				echo "<br>" . $lemma . " ... " . $wordID . " ... " . $formstring . " ..... ";
				echo " - <a id=button" . $wordID . "-1 onclick='addAdessiveRolePressed(" . $wordID . ",998)' style='text-decoration:underline;cursor:pointer;color:blue;'>Location</a>";
				echo " - <a id=button" . $wordID . "-2 onclick='addAdessiveRolePressed(" . $wordID . ",999)' style='text-decoration:underline;cursor:pointer;color:blue;'>Proximity</a>";
				echo " - <a id=button" . $wordID . "-3 onclick='addAdessiveRolePressed(" . $wordID . ",1000)' style='text-decoration:underline;cursor:pointer;color:blue;'>Surface</a>";
				echo " - <a id=button" . $wordID . "-4 onclick='addAdessiveRolePressed(" . $wordID . ",1001)' style='text-decoration:underline;cursor:pointer;color:blue;'>Instrument</a>";
				echo " - <a id=button" . $wordID . "-5 onclick='addAdessiveRolePressed(" . $wordID . ",1002)' style='text-decoration:underline;cursor:pointer;color:blue;'>Time</a>";
				echo " - <a id=button" . $wordID . "-6 onclick='addAdessiveRolePressed(" . $wordID . ",1003)' style='text-decoration:underline;cursor:pointer;color:blue;'>Owner</a>";
				echo " - <a id=button" . $wordID . "-7 onclick='addAdessiveRolePressed(" . $wordID . ",1004)' style='text-decoration:underline;cursor:pointer;color:blue;'>None</a>";
			}
		
				
			//echo " <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/features/addArticletype&wordID=" . $wordID . "&featureID=253'>A-type</a>";
			//echo " ..... <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/features/addArticletype&wordID=" . $wordID . "&featureID=254'>An-type</a>";
			//echo " ..... <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/features/addArticletype&wordID=" . $wordID . "&featureID=255'>The-type</a>";
			//echo " ..... <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/features/addArticletype&wordID=" . $wordID . "&featureID=256'>NoArticle</a>";
		}
		
		
		
		echo "	<script>";
		echo "		function addAdessiveRolePressed(wordID, featureID) {";
		echo "			var url = '" . getUrl('worder/features/setAdessiveRole') . "&wordID='+wordID+'&featureID='+featureID;";
		echo "			console.log('url - '+url);";
		
		echo "			$('#button'+wordID+'-1').hide();";
		echo "			$('#button'+wordID+'-2').hide();";
		echo "			$('#button'+wordID+'-3').hide();";
		echo "			$('#button'+wordID+'-4').hide();";
		echo "			$('#button'+wordID+'-5').hide();";
		echo "			$('#button'+wordID+'-6').hide();";
		echo "			$.getJSON(url,function(data) {";
		echo "				console.log('success - '+data);";
		//echo "				$('#button'+wordID).hide();";
		echo "			}); ";
		echo "		}";
		echo "	</script>";
		
		
		//if ($counter > 100) break;
	}
	
	

?>