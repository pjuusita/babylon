<?php

	
	// Tällä ajetaan näköjään sanaluokka wordforms-taulussa olevilla riveille

	
	$sql = "SELECT * FROM worder_words";
	echo "<br>sql - " . $sql;
	
	global $mysqli;
	$words = Table::load('worder_words');
	
	
	$wordforms = Table::load('worder_wordforms');
	
	foreach($wordforms as $index => $form) {
		echo "<br>Wordform - " . $form->wordform . " - " . $form->rowID;
		$word = $words[$form->wordID];
		echo "<br>Word - " . $word->lemma . " - " . $word->wordclassID;
		$sql = "UPDATE worder_wordforms SET WordclassID=" . $word->wordclassID . " WHERE RowID=" . $form->rowID;
		echo "<br>SQL - " . $sql;
		
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>Error: " . $mysqli->error;
			exit();
		}
		
	}
	
?>