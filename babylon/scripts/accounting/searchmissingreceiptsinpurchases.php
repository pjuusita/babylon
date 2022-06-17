<?php

	// Listataan kaikki conceptit, joissa on useampi kuin yksi sana kiinnitettynä.
	// Näihin tapauksiin pitäisi asettaa ainoastaan yksi sana defaultiksi...
	
	global $mysqli;

	$purchases = Table::load("accounting_purchases");
	$receipts = Table::load("accounting_receipts");
	$entries = Table::load("accounting_entries");
	$costpools = Table::load("accounting_costpools");
	$purchaserows = Table::load("accounting_purchaserows");
	
	
	$missingreceipts = 0;
	echo "<br>Checking purchases...";
	foreach($purchases as $index => $purchase) {
		if (!isset($receipts[$purchase->receiptID])) {
			echo "<br> --------------------------------------- puuttuva receiptID: " . $purchase->receiptID . " in purchase: " . $purchase->purchaseID . " - " . $purchase->purchasedate;
			$missingreceipts++;
		}
	}
	
	
	
	echo "<br><br><br>Checking entries...";
	foreach($entries as $index => $entry) {
		if (!isset($receipts[$entry->receiptID])) {
			echo "<br> --------------------------------------- puuttuva receiptID: " . $entry->receiptID . " in entry:" . $entry->entryID;
			$missingreceipts++;
		}
		
		if ($entry->costpoolID > 0) {
			if (!isset($costpools[$entry->costpoolID])) {
				echo "<br> --------------------------------------- puuttuva costpoolID: " . $entry->costpoolID . " in entry:" . $entry->entryID;
				$missingreceipts++;
			}
		}
		
	}

	echo "<br><br>missingreceipts - " . $missingreceipts;
	
	

	$missing = 0;
	echo "<br>Checking purchaserows purchase check...";
	foreach($purchaserows as $index => $purchaserow) {
		if (!isset($purchases[$purchaserow->purchaseID])) {
			echo "<br> --------------------------------------- puuttuva purchaseID: " . $purchaserow->purchaseID . " in purchase: " . $purchaserow->rowID;
			$missing++;
		}
	}
	echo "<br><br>Puuttuvat purchaset - " . $missing;
	
	
	
	$missing = 0;
	echo "<br>Checking purchaserows...";
	foreach($purchaserows as $index => $purchaserow) {
		if (!isset($costpools[$purchaserow->costpoolID])) {
			echo "<br> --------------------------------------- puuttuva costpoolID: " . $purchaserow->costpoolID . " in purchase: " . $purchaserow->purchaseID;
			$missing++;
		}
	}
	echo "<br><br>Puuttuvat costpoolit - " . $missing;
	
	
	
	
	/*
	foreach($receipts as $index => $receipt) {
		echo "<br>ReceiptID - " . $receipt->receiptID;		
		
		$debet = 0;
		$credit = 0;
		$entrycounter = 0;
		foreach($entries as $index => $entry) {
			if ($entry->receiptID == $receipt->receiptID) {
				if ($receipt->receiptID == 2892) {
					echo "<br> --- entryID: " . $entry->entryID . ", value: " . $entry->amount;
				}
				$value = round($entry->amount,2);
				if ($value < 0) {
					$credit = $credit + $value;
					if ($receipt->receiptID == 2892) {
						echo "<br>Adding credit:";
						echo "<br> - value: " . $value;
						echo "<br> - credit-value: " . $credit;
					}
				} else {
					$debet = $debet + $value;
					if ($receipt->receiptID == 2892) {
						echo "<br>Adding credit:";
						echo "<br> - value: " . $value;
						echo "<br> - debet-value: " . $debet;
					}
				}
				$entrycounter++;
			}
		}
		if ($entrycounter == 0) {
			echo "<br>---------------------------------- receiptillä ei entryjä - ReceiptID: " . $receipt->receiptID;
		}
		
		
		$delta = ($debet - (-1 * $credit));
		echo "<br> -- "  . $debet . " vs. " . $credit . " .... " . $delta;
		if ($delta > 0.001) {
			echo "<br> --------------------------------------- entries fail ... - " . $receipt->receiptID . " .... " . ($debet - (-1 * $credit));
		}
		if ($delta < -0.001) {
			echo "<br> --------------------------------------- entries fail ... - " . $receipt->receiptID . " .... " . ($debet - (-1 * $credit));
		}
		
		
		if ($receipt->debet == null) {
			echo "<br> --------------------------------------- debet = null ... - " . $receipt->receiptID;
			$values = array();
			$values['Debet'] = $debet;
			Table::updateRow("accounting_receipts", $values, $receipt->receiptID);
			
		}
		if ($receipt->credit == null) {
			echo "<br> --------------------------------------- credit = null ... - " . $receipt->receiptID;
			$values = array();
			$values['Credit'] = (-1 * $credit);
			Table::updateRow("accounting_receipts", $values, $receipt->receiptID);
		}
	}
	*/

	echo "<br><br>finnished..............";


?>