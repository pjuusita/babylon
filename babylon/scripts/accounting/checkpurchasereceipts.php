<?php

	
	// State=0 tilassa olevilla ostolaskuilla ei saisi olla lainkaan vientejä.
	// Tätä on muutettu.

	global $mysqli;

	$purchases = Table::load("accounting_purchases");
	$receipts = Table::load("accounting_receipts");
	//$entries = Table::load("accounting_entries");

	
	$missingreceipts = 0;
	$nullcounter = 0;
	$missmatchcounter = 0;
	echo "<br>Checking purchases...";
	foreach($receipts as $index => $receipt) {
		if ($receipt->purchaseID > 0) {
			$purchase = $purchases[$receipt->purchaseID];
			if ($purchase->receiptID != $receipt->receiptID) {
				echo "<br>No match - receiptID: " . $receipt->receiptID . ", purchaseID:" . $receipt->purchaseID;
				
				if ($receipt->receiptsetID == 1) {
					echo "<br> --- fail";
				}
				
			}
		}
	}
	

	echo "<br><br>finnished..............";


?>