<?php

	
	// State=0 tilassa olevilla ostolaskuilla ei saisi olla lainkaan vientejä.
	// Tätä on muutettu.

	global $mysqli;

	$purchases = Table::load("accounting_purchases");
	$entries = Table::load("accounting_entries");

	
	$missingreceipts = 0;
	$nullcounter = 0;
	$missmatchcounter = 0;
	echo "<br>Checking purchases...";
	foreach($purchases as $index => $purchase) {
		
		$found = 0;
		if ($purchase->state == 0) {
			foreach($entries as $entryID => $entry) {
				if ($entry->receiptID == $purchase->receiptID) $found++;
			}
		}
		if ($found > 0) {
			echo "<br>Failed state purchase found - PurchaseID:" . $purchase->purchaseID;
		}		
	}
	

	echo "<br><br>finnished..............";


?>