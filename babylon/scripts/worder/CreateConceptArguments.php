<?php

	// Lisätään kaikille verbi-käsitteille seuraavat argumentit (mikäli niitä ei vielä ole)
	//	- Agent = Noun
	//  - Agent = Pronoun
	//  - Dota = Noun
	//	- Agent = Proper Noun
	
	// TODO: pitäisi ehkä hieman selailla verbejä, onko selkeästi joku tyyppi, johon nämä eivät
	//		 sovellu. Ainakin säätila: sataa. ehkä copulat.

	// Tämä generoi kyllä varmaan osalle verbeistä ylimääräisi muotoja, mutta nämä ovat kuitenkin 
	// yleisempiä, että parempi nämä on generoida automaattiseti kuin käydä käsin yksikerrallaan
	// lävitse.
	
	// Pitää lisätä ainoastaan siinä tapauksessa, että yhtään agenttia ei ole vielä asetettu, jos 
	// ei ole niin lisätään molemmat.

	// Tässä pitää päivittää sekä worder_oncepts.arguments-kenttä, että lisätä uudet rivit




function endsWith( $haystack, $needle ) {
	$length = strlen( $needle );
	if( !$length ) {
		return true;
	}
	return substr( $haystack, -$length ) === $needle;
}


// language = english, wordclass = noun
$sql = "SELECT * FROM worder_concepts WHERE WordclassID=2 AND GrammarID=1";

$missing = array();
global $mysqli;
$languages = array();
$wordclasses = array();
$concepts = array();
$arguments = array();
$conceptlist = array();

$result = $mysqli->query($sql);
while($row = $result->fetch_array()) {
	//echo "<br>" . $row['Lemma'] . " - " . $row['Inflection'];
	$conceptID = $row['ConceptID'];
	$wordclasses[$conceptID] = $row['WordclassID'];
	$concepts[$conceptID] = $row['Name'];
	$arguments[$conceptID] = $row['Arguments'];
	//echo "<br>Arguments - " . $row['Arguments'];
	
}
echo "<br>Wordcount - " . count($concepts);



// language = english, wordclass = noun
/*
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
*/

$components = Table::load("worder_components");
echo "<br>Componentcount - " . count($components);

$wordlinks = Table::loadWhereInArray("worder_conceptwordlinks", "ConceptID", $concepts, "WHERE LanguageID=1 AND GrammarID=1");
echo "<br>wordlinks - " . count($wordlinks);

$wordlist = array();
foreach($wordlinks as $index => $link) {
	$wordlist[$link->wordID] = $link->conceptID;
}

$words = Table::loadWhereInArray("worder_words", "WordID", $wordlist, "WHERE LanguageID=1 AND GrammarID=1");
echo "<br>wordscount - " . count($words);

$conceptwords = array();
foreach($wordlinks as $index => $link) {
	$conceptwords[$link->conceptID] = $words[$link->wordID];
}


echo "<table>";


$counter=0;

//$targetargumentID = 7;		// agent
//$targetcomponentID = 172;		// noun
//$targetcomponentID = 80;		// pronoun
//$targetcomponentID = 8;		// proper noun

$targetargumentID = 85;		// agent
$targetcomponentID = 172;		// noun

$targetargument = Table::loadRow("worder_arguments", $targetargumentID);
$targetcomponent = Table::loadRow("worder_components", $targetcomponentID);

foreach($concepts as $conceptID => $name) {

	/*
	if (isset($articletypes[$wordID])) {
		$featureID = $articletypes[$wordID];
		$feature = $features[$featureID];
		//echo "<br>" . $lemma . " - " . $articletypes[$wordID] . " - " . $feature->name;

	} else {
	*/
	echo "<tr>";
	echo "<td>";
	echo "" . $conceptID . "";
	echo "</td>";
	echo "<td>";
	echo "" . $name . "";
	echo "</td>";
	echo "<td>";
	
	echo "<td style='width:120px;'>";
	echo " <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/concepts/showconcept&id=" . $conceptID . "'>Link</a>";
	echo "</td>";
	
	if (isset($conceptwords[$conceptID])) {
		$word = $conceptwords[$conceptID];
		echo "<td>";
		echo "" . $word->lemma . "";
		echo "</td>";
	} else {
		echo "<td>";
		echo "---";
		echo "</td>";
	}
	
	$agentfound = false;
	if (isset($arguments[$conceptID])) {
		
		$args = explode('|', $arguments[$conceptID]);
		$componentstring = null;
		$componentfound = false;
		foreach($args as $index => $value) {
			$parts = explode(':', $value);			
			if ($parts[0] == $targetargumentID) { 
				$agentfound = true;
				if ($componentstring == null) {
					$componentstring = $components[$parts[1]]->name;
				} else {
					$componentstring = $componentstring . "," . $components[$parts[1]]->name;
				}
				if ($parts[1] == $targetcomponentID) {
					$componentfound = true;
				}
				
				//echo "-" . $parts[0];
			} else {
				//echo "a-" . $parts[0];
			}
		}
		//echo "" . $arguments[$conceptID];
		echo "</td>";
	} else {
		echo "none";
		echo "</td>";
	}
	if ($agentfound == true) {
		echo "<td>";
		echo "" . $componentstring;
		echo "</td>";
	} else {
		echo "<td> - ";
		echo "</td>";
	}
	if ($componentfound == true) {
		echo "<td>";
		echo "found";
		echo "</td>";
	} else {
		echo "<td>";
		echo "<a id=button" . $conceptID . "-1 onclick='addArgumentPressed(" . $conceptID . "," . $targetargumentID . "," . $targetcomponentID . ")' style='text-decoration:underline;cursor:pointer;color:blue;'>Add " . $targetargument->name . " " . $targetcomponent->name . "</a>";
		echo "</td>";
	}
	
	
	echo "</td>";
	echo "</tr>";
	

	

	//if ($counter > 100) break;
}
echo "</table>";

echo "	<script>";
echo "		function addArgumentPressed(conceptID, argumentID, componentID) {";
echo "			var url = '" . getUrl('worder/concepts/addargumentJSON') . "&conceptID='+conceptID+'&argumentID='+argumentID+'&componentID='+componentID+'&inheritancemodeID=3';";
echo "			console.log('url - '+url);";

echo "			$('#button'+conceptID+'-1').hide();";
////echo "			$('#button'+conceptID+'-2').hide();";
////echo "			$('#button'+conceptID+'-3').hide();";
////echo "			$('#button'+conceptID+'-4').hide();";
echo "			$.getJSON(url,function(data) {";
echo "				console.log('success - '+data);";
////echo "				$('#button'+wordID).hide();";
echo "			}); ";
echo "		}";
echo "	</script>";



?>