<?php


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
	$sql = "SELECT * FROM worder_wordfeaturelinks WHERE LanguageID=2 AND WordclassID=1 AND FeatureID=252";
	echo "<br>sql - " . $sql;
	
	$forms = array();
	$formwordIDs = array();
	$formlists = array();
	$rarities = array();
	
	global $mysqli;
	$result = $mysqli->query($sql);
	$index = 0;
	$articletypes = array();
	while($row = $result->fetch_array()) {
		$wordID = $row['WordID'];
		$valueID = $row['ValueID'];
		$articletypes[$wordID] = $valueID;
		$index++;
	}
	echo "<br>Formcount - " . $index;
	echo "<br><br>------------";
	
	$features = Table::load("worder_features");
	$counter=0;
	foreach($words as $wordID => $lemma) {
		
		if (isset($articletypes[$wordID])) {
			$featureID = $articletypes[$wordID];
			$feature = $features[$featureID];
			//echo "<br>" . $lemma . " - " . $articletypes[$wordID] . " - " . $feature->name;
				
		} else {
			echo "<br>" . $lemma . " ..... ";
			echo " - <a id=button" . $wordID . "-1 onclick='addArticletypePressed(" . $wordID . ",253)' style='text-decoration:underline;cursor:pointer;color:blue;'>A-type</a>";
			echo " - <a id=button" . $wordID . "-2 onclick='addArticletypePressed(" . $wordID . ",254)' style='text-decoration:underline;cursor:pointer;color:blue;'>An-type</a>";
			echo " - <a id=button" . $wordID . "-3 onclick='addArticletypePressed(" . $wordID . ",255)' style='text-decoration:underline;cursor:pointer;color:blue;'>The-type</a>";
			echo " - <a id=button" . $wordID . "-4 onclick='addArticletypePressed(" . $wordID . ",256)' style='text-decoration:underline;cursor:pointer;color:blue;'>NoArticle</a>";
				
			//echo " <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/features/addArticletype&wordID=" . $wordID . "&featureID=253'>A-type</a>";
			//echo " ..... <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/features/addArticletype&wordID=" . $wordID . "&featureID=254'>An-type</a>";
			//echo " ..... <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/features/addArticletype&wordID=" . $wordID . "&featureID=255'>The-type</a>";
			//echo " ..... <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/features/addArticletype&wordID=" . $wordID . "&featureID=256'>NoArticle</a>";
		}
		
		
		
		echo "	<script>";
		echo "		function addArticletypePressed(wordID, featureID) {";
		echo "			var url = '" . getUrl('worder/features/addArticletype') . "&wordID='+wordID+'&featureID='+featureID;";
		echo "			console.log('url - '+url);";
		
		echo "			$('#button'+wordID+'-1').hide();";
		echo "			$('#button'+wordID+'-2').hide();";
		echo "			$('#button'+wordID+'-3').hide();";
		echo "			$('#button'+wordID+'-4').hide();";
		echo "			$.getJSON(url,function(data) {";
		echo "				console.log('success - '+data);";
		//echo "				$('#button'+wordID).hide();";
		echo "			}); ";
		echo "		}";
		echo "	</script>";
		
		
		//if ($counter > 100) break;
	}
	
	

?>