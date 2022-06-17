<?php

	// Tämän funktion tarkoitus on päivittää verbin number-featuren arvo vastaamaan uutta
	// V-Number featuren arvoa...
	//		V-Number = 1158		old value:2
	//		SG = 	1159		old value: 5
	//		PL = 	1160		old value: 6
	//		None =	1161		old value: 597
	//

function endsWith( $haystack, $needle ) {
	$length = strlen( $needle );
	if( !$length ) {
		return true;
	}
	return substr( $haystack, -$length ) === $needle;
}


function startsWith( $haystack, $needle ) {
	$length = strlen( $needle );
	return substr( $haystack, 0, $length ) === $needle;
}


	global $mysqli;

	$sql = "SELECT * FROM worder_wordforms WHERE SystemID=5 AND GrammarID=1 AND LanguageID=1 AND WordclassID=2";
	echo "<br>sql - " . $sql;

	$result = $mysqli->query($sql);
	$keylist = array();
	$wordit = array();
	$counter = 0;
	$found = 0;
	while($row = $result->fetch_array()) {
		
		$featurelist = $row['Features'];
		
		if (false !== strpos($featurelist, ':5:')) {
			//echo "<br> - - - - - - contains - " . $featurelist;
			
			$keylist[$row['RowID']] = str_replace(':5:',':1159:', $featurelist);
			$wordit[$row['RowID']] = $row['WordID'];
		} else {
			//echo "<br>Not contains - " . $featurelist;
		}
		
		if (false !== strpos($featurelist, ':6:')) {
			//echo "<br> - - - - - - contains - " . $featurelist;
				
			$keylist[$row['RowID']] = str_replace(':6:',':1160:', $featurelist);
			$wordit[$row['RowID']] = $row['WordID'];
		} else {
			//echo "<br>Not contains - " . $featurelist;
		}
		
		
		if (false !== strpos($featurelist, ':2:')) {
			//echo "<br> - - - - - - contains 2  - " . $featurelist . ", wordID: " . $row['WordID'];
			//$found++;
			$keylist[$row['RowID']] = str_replace(':2:',':1158:', $featurelist);
			$wordit[$row['RowID']] = $row['WordID'];
		}
		
		
		if (endsWith($featurelist, ':2')) {
			echo "<br> - - - - - - endwith 2 - " . $featurelist . ", wordID: " . $row['WordID'];
		}
		
		if (startsWith($featurelist, '2:')) {
			echo "<br> - - - - - - startsWith 2 - " . $featurelist . ", wordID: " . $row['WordID'];
		}
		
		
		
		if (endsWith($featurelist, ':5')) {
			echo "<br> - - - - - - endwith 5 - " . $featurelist . ", wordID: " . $row['WordID'];
		}
		
		if (startsWith($featurelist, '5:')) {
			echo "<br> - - - - - - startsWith 5 - " . $featurelist . ", wordID: " . $row['WordID'];
		}
		

		if (endsWith($featurelist, ':6')) {
			echo "<br> - - - - - - endwith 6 - " . $featurelist . ", wordID: " . $row['WordID'];
		} else {
			//echo "<br>Not contains - " . $featurelist;
		}
		
		if (startsWith($featurelist, '6:')) {
			echo "<br> - - - - - - startsWith 6 - " . $featurelist . ", wordID: " . $row['WordID'];
		} else {
			//echo "<br>Not contains - " . $featurelist;
		}
		
		
		if (false !== strpos($featurelist, '597')) {
			echo "<br> - - - - - - contains 597 - " . $featurelist . ", wordID: " . $row['WordID'];
		} else {
			//echo "<br>Not contains - " . $featurelist;
		}
		
		
		
		$counter++;
		//if ($counter > 1000) break;
	}
	echo "<br>..............";
	echo "<br>count = " . $counter;
	echo "<br>found = " . $found;
	echo "<br>countff  = " . count($wordit);
	echo "<br><br>";
	
	foreach($keylist as $rowID => $value) {
		
		$wordID = $wordit[$rowID];
		echo "<br>" . $rowID . " -- " . $value . " (wordID:" . $wordID . ")";
		
		$sql = "UPDATE worder_wordforms SET Features='" . $value . "' WHERE RowID=" . $rowID;
		//echo "<br>SQL - " . $sql;
		
		/*
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>Error: " . $mysqli->error;
			exit();
		}
		*/
	}
	

?>