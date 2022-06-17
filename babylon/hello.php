<?php

/*
A precedence rule is given as "P>E", which means that letter "P" is followed directly by the letter "E". Write a function, given an array of precedence rules, that finds the word represented by the given rules.

Note: Each represented word contains a set of unique characters, i.e. the word does not contain duplicate letters.

Examples:
findWord(["P>E","E>R","R>U"]) // PERU
findWord(["I>N","A>I","P>A","S>P"]) // SPAIN


findWord(["U>N", "G>A", "R>Y", "H>U", "N>G", "A>R"]) // HUNGARY
findWord(["I>F", "W>I", "S>W", "F>T"]) // SWIFT
findWord(["R>T", "A>L", "P>O", "O>R", "G>A", "T>U", "U>G"]) // PORTUGAL
findWord(["W>I", "R>L", "T>Z", "Z>E", "S>W", "E>R", "L>A", "A>N", "N>D", "I>T"]) // SWITZERLAND
*/

	function findWord($arr) {
		
		//echo "<br>length - " . sizeof($arr);

		$firstfound = "";
		$foundchars = array();
		
		for($index2 = 0; $index2 < sizeof($arr);$index2++) {
			//echo "<br>arr - " . $arr[$index2];
			$firstchar = substr($arr[$index2], 0, 1);
			$secondchar = substr($arr[$index2], 2,1);
			//echo "<br> -- " . $firstchar . " -- " . $secondchar;
			$foundchars[$secondchar] = $secondchar;
		}
		for($index2 = 0; $index2 < sizeof($arr);$index2++) {
			
			$firstchar = substr($arr[$index2], 0, 1);
			$secondchar = substr($arr[$index2], 2,1);
				
			if (!isset($foundchars[$firstchar]))	 {
				$firstfound = $firstchar;
				break;
			}
		}
		echo "<br>firstfound - " . $firstfound;
		
		$currentchar = $firstfound;
		$foundword = $currentchar;
		for($index = 0; $index < sizeof($arr);$index++) {
			
			for($index2 = 0; $index2 < sizeof($arr);$index2++) {
								
				$firstchar = substr($arr[$index2], 0, 1);
				$secondchar = substr($arr[$index2], 2,1);
				
				if ($firstchar == $currentchar) {
					$foundword = $foundword . $secondchar;
					$currentchar = $secondchar;
					break;
				}
			}
		}
		echo "<br> - " . $foundword;
		return $foundword;
	}
		
	$word = findWord(array("P>E","E>R","R>U"));
	$word = findWord(array("I>F", "W>I", "S>W", "F>T"));
	$word = findWord(array("R>T", "A>L", "P>O", "O>R", "G>A", "T>U", "U>G"));
	$word = findWord(array("W>I", "R>L", "T>Z", "Z>E", "S>W", "E>R", "L>A", "A>N", "N>D", "I>T"));
	
	echo "<br><br>";
?>
