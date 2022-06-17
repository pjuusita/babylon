<?php

	if (isset($_GET['noframes'])) {
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
		echo "\n<html>";
		echo "\n<head>";
		echo "\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
		echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" >";
		echo "\n<script type='text/javascript'  title='bbb'  src='/babylon/js/jquery.min.js?r=" .  rand() . "'></script>";
		echo "\n<script type='text/javascript' src='/babylon/js/utils.js?r=" .  rand() . "'></script>";
		echo "\n</head>";
		echo "\n<body id='topbody' style='margin:0px;padding:0px;text-align:left;'>";
		
	}


	echo "<h2>Classify</h2>";
	echo "  <a href='" . getUrl("worder/conceptclassifier/index&noframes=1&id=" .( $registry->concept->conceptID-1)) . "'>noframes</a> | ";
	echo "  <a href='" . getUrl("worder/conceptclassifier/index&id=" . ($registry->concept->conceptID-1)) . "'>withframes</a>";
	
	
	echo "<table>";
	echo "	<tr>";
	echo "		<td style='width:100px;'>ConceptID</td>";
	echo "		<td style='width:300px;'>" . $registry->concept->conceptID . "</td>";
	echo "	</tr>";
	
	
	if ($registry->concept->word_en != "") {
		echo "	<tr>";
		echo "		<td>Word_en</td>";
		echo "		<td>" . $registry->concept->word_en . "</td>";
		echo "	</tr>";
	} else {
		echo "	<tr>";
		echo "		<td>Word_en</td>";
		echo "		<td style='color:red'>Ei asetettu</td>";
		echo "	</tr>";
	}
	
	if ($registry->concept->word_fi != "") {
		echo "	<tr>";
		echo "		<td>Word_fi</td>";
		echo "		<td>" . $registry->concept->word_fi . "</td>";
		echo "	</tr>";
	} else {
		echo "	<tr>";
		echo "		<td>Word_fi</td>";
		echo "		<td style='color:red'>Ei asetettu</td>";
		echo "	</tr>";
	}
	
	if ($registry->concept->gloss_en != "") {
		echo "	<tr>";
		echo "		<td style='vertical-align:top;'>Gloss</td>";
		echo "		<td>" . $registry->concept->gloss_en . "</td>";
		echo "	</tr>";
	} else {
		echo "	<tr>";
		echo "		<td>Gloss</td>";
		echo "		<td style='color:red'>Ei asetettu</td>";
		echo "	</tr>";
	}
	
	if ($registry->concept->gloss_fi != "") {
		echo "	<tr>";
		echo "		<td>Gloss_fi</td>";
		echo "		<td>" . $registry->concept->gloss_fi . "</td>";
		echo "	</tr>";
	} 
	
	echo "	<tr>";
	echo "		<td>Wordclass</td>";
	echo "		<td>" . $registry->wordclasses[$registry->concept->wordclassID]->name . "</td>";
	echo "	</tr>";

	echo "	<tr>";
	echo "		<td colspan=2 style='height:10px;'>";
	echo "		</td>";
	echo "	</tr>";
	
	echo "	<tr>";
	echo "		<td colspan=2>";
	
	foreach($registry->rarities as $index => $rarity) {
		
		echo "<button style='width:80px;margin-right:4px;' id='rarity-" . $rarity->rarityID . "' value='" . $rarity->rarityID . "'>";
		echo "" . $rarity->name;
		echo "</button>";
		
		echo "<script>";
		echo "	$('#rarity-" . $rarity->rarityID . "').click(function() {";
		//echo "		alert('rarityID change - " . $rarity->name . "');";
		if (isset($_GET['noframes'])) {
			//echo "		window.location = '" . getUrl("worder/conceptclassifier/setrarity", array( "id" =>  $registry->concept->conceptID , "rarity" => $rarity->rarityID, "noframes" => 1)). "'";
		} else {
			//echo "		window.location = '" . getUrl("worder/conceptclassifier/setrarity", array( "id" =>  $registry->concept->conceptID , "rarity" => $rarity->rarityID)). "'";
		}
		echo "	});";
		echo "</script>";
	}

	echo "	<tr>";
	echo "		<td colspan=2 style='height:10px;'>";
	echo "		</td>";
	echo "	</tr>";
	
	echo "	<tr>";
	echo "		<td colspan=2>";
	echo "<select id='wordclassselect' style='width:120px'>";
	foreach($registry->wordclasses as $index => $wordclass) {
		echo "<option value='" .$wordclass->wordclassID . "'>" . $wordclass->name . "</option>";
	}
	echo "</select>";
	echo "		</td>";
	echo "	</tr>";
	
	echo "<script>";
	echo "	$('#wordclassselect').change(function() {";
	echo "		var value = $('#wordclassselect').val();";
	//echo "		alert('sanaluokka change - " . $registry->concept->conceptID . " - '+value);";
	if (isset($_GET['noframes'])) {
		//echo "		window.location = '" . getUrl("worder/conceptclassifier/setwordclass", array( "id" =>  $registry->concept->conceptID, "noframes" => 1)). "&wordclass='+value";
	} else {
		//echo "		window.location = '" . getUrl("worder/conceptclassifier/setwordclass", array( "id" =>  $registry->concept->conceptID)). "&wordclass='+value";
	}
	
	echo "	});";
	echo "</script>";
	echo "<table>";
	
	
	if (isset($_GET['noframes'])) {
		echo "</body>";
		echo "<html>";
	}
?>