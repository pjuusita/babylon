







<?php

	//echo "<h1>Hello World!</h1>";
	
/*
 * 
 * A vending machine has the following denominations: 1c, 5c, 10c, 25c, 50c, and $1. Your task is to write a program that will be used in a vending machine to return change. Assume that the vending machine will always want to return the least number of coins or notes. Devise a function getChange(M, P) where M is how much money was inserted into the machine and P the price of the item selected, that returns an array of integers representing the number of each denomination to return. 

Example:
getChange(5, 0.99) // should return [1,0,0,0,0,4]

getChange(3.14, 1.99) // should return [0,1,1,0,0,1]
getChange(3, 0.01) // should return [4,0,2,1,1,2]
getChange(4, 3.14) // should return [1,0,1,1,1,0]
getChange(0.45, 0.34) // should return [1,0,1,0,0,0]
 * 
 * 
 */
	
	function getChange($M, $P) {

		$money = $M * 100;   // Total cents;
		$moneyback = $money - ($P * 100);
		
		$result = array();
		// calculting full dollars
		$dollars = (int)($moneyback/ 100);
		//echo "<br>Dollars - " . $dollars;
		$moneyback = $moneyback - ($dollars * 100);
		
		// calculting full dollars
		$fiftycents = (int)($moneyback/ 50);
		//echo "<br>fiftycents - " . $fiftycents;
		$moneyback = $moneyback - ($fiftycents * 50);
		
		// calculting Twentyfivecents 
		$twentyfivecents = (int)($moneyback/ 25);
		//echo "<br>Twentyfivecents - " . $twentyfivecents;
		$moneyback = $moneyback - ($twentyfivecents * 25);
		
		// calculting Tencentss
		$tencents = (int)($moneyback/ 10);
		//echo "<br>Tencents - " . $tencents;
		$moneyback = $moneyback - ($tencents * 10);
		
		// calculting full dollars
		$fivecents = (int)($moneyback/ 5);
		//echo "<br>Fivecents - " . $fivecents;
		$moneyback = $moneyback - ($fivecents * 5);
		
		// calculting one cents
		$onecents= $moneyback;
		echo "<br>Onecents - " . $onecents;
		$moneyback = $moneyback - $onecents;
		echo "<br>Moneybak - " . $moneyback;	// should be zero
		
		echo "<br>Money - " . $M . ", Price - " . $P;
		
		
		$result[] = $onecents;
		$result[] = $fivecents;
		$result[] = $tencents;
		$result[] = $twentyfivecents;
		$result[] = $fiftycents;
		$result[] = $dollars;
		
		return $result;
	}
	
	
	echo "<br><br>should return [1,0,0,0,0,4]";
	$result = getChange(5, 0.99);
	echo "<br>----";
	print_r($result);
	
	echo "<br><br>getChange(3.14, 1.99) - should return [0,1,1,0,0,1]";
	$result = getChange(3.14, 1.99); // should return [0,1,1,0,0,1]
	echo "<br>----";
	print_r($result);
	
	echo "<br><br> should return [4,0,2,1,1,2]";
	$result = getChange(3, 0.01); // should return [4,0,2,1,1,2]
	echo "<br>----";
	print_r($result);
	
	echo "<br><br>should return [1,0,1,1,1,0]";
	$result = getChange(4, 3.14); // should return [1,0,1,1,1,0]
	echo "<br>----";
	print_r($result);
	
	echo "<br><br>should return [1,0,1,0,0,0]";
	$result = getChange(0.45, 0.34); // should return [1,0,1,0,0,0]
	echo "<br>----";
	print_r($result);
	
?>