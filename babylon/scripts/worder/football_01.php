<?php

	echo "<br>Start";

	$odds1 = array();
	$odds2 = array();
	$odds3 = array();
	
	$odds1[0] = 86;		$odds2[0] = 9;		$odds3[0] = 5;
	$odds1[1] = 57;		$odds2[1] = 23;		$odds3[1] = 20;
	$odds1[2] = 58;		$odds2[2] = 22;		$odds3[2] = 20;
	$odds1[3] = 27;		$odds2[3] = 28;		$odds3[3] = 44;
	$odds1[4] = 36;		$odds2[4] = 28;		$odds3[4] = 36;
	$odds1[5] = 62;		$odds2[5] = 21;		$odds3[5] = 17;
	$odds1[6] = 10;		$odds2[6] = 15;		$odds3[6] = 74;
	$odds1[7] = 23;		$odds2[7] = 26; 	$odds3[7] = 51;
	$odds1[8] = 75;		$odds2[8] = 14;		$odds3[8] = 11;
	$odds1[9] = 17;		$odds2[9] = 23;		$odds3[9] = 61;
	$odds1[10] = 38;	$odds2[10] = 30;	$odds3[10] = 32;
	$odds1[11] = 59;	$odds2[11] = 23;	$odds3[11] = 18;
	$odds1[12] = 49;	$odds2[12] = 27;	$odds3[12] = 24;
	
	
	
	function recursiveOdds($index, $currentprob, &$probs, $depth, $maxdepth, &$counter, $odds1, $odds2, $odds3) {

		if ($depth < $maxdepth) {
			$win = $currentprob * $odds1[$depth];
			$even = $currentprob * $odds2[$depth];
			$lose = $currentprob * $odds3[$depth];
			
			//echo "<br>Win - " . $odds1[$depth];
			
			
			recursiveOdds($index+1, $win, $probs, $depth+1, $maxdepth, $counter, $odds1, $odds2, $odds3);
			recursiveOdds($index+1, $even, $probs, $depth+1, $maxdepth, $counter, $odds1, $odds2, $odds3);
			recursiveOdds($index+1, $lose, $probs, $depth+1, $maxdepth, $counter, $odds1, $odds2, $odds3);
				
		} else {
			echo "<br>" . $counter . " - " . $currentprob;
			$probs[$counter] = $currentprob;
			$counter++;
		}
	}
	
	$counter = 0;
	$probs = array();
	recursiveOdds(0, 1, $probs, 0, 3, $counter, $odds1, $odds2, $odds3);

	$sum = 0;
	foreach($probs as $index => $value) {
		$sum = $sum + $value;
	}
	echo "<br> --- "  . $sum;
	foreach($probs as $index => $value) {
		$sum = $sum + $value;
		echo "<br>" . $index . ". " . ($value / $sum * 100);
	}
	
	
	
	
	echo "<br>Ready.";

?>