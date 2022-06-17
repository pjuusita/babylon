<?php



$words = Table::load("worder_words", "WHERE LanguageID=1 AND WordclassID=1 AND GrammarID=" . $_SESSION['grammarID']);
$linklist = Table::load("worder_wordfeaturelinks","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND FeatureID=477");

	
foreach($linklist as $index => $link) {
	$wordID = $link->wordID;
	if (isset($words[$wordID])) {
		//echo "<br>Feature found in word - " . $words[$wordID]->lemma;
		unset($words[$wordID]);
	}
}
$this->registry->words = $words;




$counter=0;
$endingcounter = 0;
echo "<table>";
foreach($words as $wordID => $word) {

	echo "<tr>";
	echo "	<td>";
	echo "" . $counter;
	echo "	</td>";
	echo "	<td>";
	echo "" . $word->wordID;
	echo "	</td>";
	echo "	<td>";
	echo "" . $word->lemma;
	echo "</td>";
	echo "	<td>";
	echo " <button id=ssabutton" . $wordID . " onclick='setlla(" . $wordID . ")' style=''>-lla</button>";
	echo "</td>";
	echo "	<td>";
	echo " <button id=llabutton" . $wordID . "  onclick='setssa(" . $wordID . ")' style=''>-ssa</a>";
	echo "</td>";
	echo "	<td>";
	echo " <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/words/showword&id=" . $wordID . "'>word</a>";
	echo "</td>";
	echo "</tr>";
	
		//echo " - <button id=button" . $wordID . " onclick='addpluralbuttonpressed(" . $wordID . ")'>use s-ending</button>";
	//echo " - <a id=button" . $wordID . " onclick='addpluralbuttonpressed(" . $wordID . ")' style='text-decoration:underline;cursor:pointer;color:blue;'>use -s ending</a>";
	//echo " - <a target='_blank' href='https://www.babelsoft.fi/demo/index.php?rt=worder/words/showword&id=" . $wordID . "'>use -s ending</a>";
		
	$counter++;

	if ($counter > 10000) {
		echo "<br>Breakki Counter - " . $counter;
		break;
	}
}

echo "<br>Counter - " . $counter;
//echo "<br>Endcounter - " . $endingcounter;

echo "	<script>";
echo "		function setssa(wordID) {";

echo "			console.log('button ssa pressed - '+wordID);";
echo "			console.log('url - " . getUrl('worder/words/addfinnislocationtypessa') . "&wordID='+wordID);";
echo "			$.getJSON('" . getUrl('worder/words/addfinnislocationtypessa') . "&wordID='+wordID,'',function(data) {";
echo "				console.log('success - '+data);";
echo "				$('#ssabutton'+wordID).hide();";
echo "				$('#llabutton'+wordID).hide();";
echo "			}); ";
echo "		}";
echo "</script>";

echo "	<script>";
echo "		function setlla(wordID) {";

echo "			console.log('button lla pressed - '+wordID);";
echo "			console.log('url - " . getUrl('worder/words/addfinnislocationtypella') . "&wordID='+wordID);";
echo "			$.getJSON('" . getUrl('worder/words/addfinnislocationtypella') . "&wordID='+wordID,'',function(data) {";
echo "				console.log('success - '+data);";
echo "				$('#ssabutton'+wordID).hide();";
echo "				$('#llabutton'+wordID).hide();";
echo "			}); ";
echo "		}";
echo "</script>";


?>