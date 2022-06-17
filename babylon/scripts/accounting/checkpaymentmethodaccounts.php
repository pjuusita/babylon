<?php

	// Listataan kaikki conceptit, joissa on useampi kuin yksi sana kiinnitettynä.
	// Näihin tapauksiin pitäisi asettaa ainoastaan yksi sana defaultiksi...
	
	global $mysqli;

	$purchases = Table::load("accounting_purchases");
	$paymentmethods = Table::load("accounting_paymentmethods");

	
	$missingreceipts = 0;
	$nullcounter = 0;
	$missmatchcounter = 0;
	echo "<br>Checking purchases...";
	foreach($purchases as $index => $purchase) {
		if (!isset($paymentmethods[$purchase->paymentmethodID])) {
			echo "<br> --------------------------------------- puuttuva paymentmethodID: " . $purchase->paymentmethodID . " in purchase: " . $purchase->purchaseID . " - " . $purchase->purchasedate;
			$missingreceipts++;
		} else {
			
			if (($purchase->paymentmethodID == null) || ($purchase->paymentmethodID == 0)) {
				echo "<br> ---- paymentmethod null - purchaseID: " . $purchase->purchaseID;
				$nullcounter++;
			} else {
				if (($purchase->payableaccountID == null) || ($purchase->payableaccountID == 0)) {
					echo "<br> ---- paymentmethod account missmatch nulli - purchaseID: " . $purchase->purchaseID;
					$paymentmethod = $paymentmethods[$purchase->paymentmethodID];
					$values = array();
					$values['PayableaccountID'] = $paymentmethod->accountID;
					Table::updateRow("accounting_purchases", $values, $purchase->purchaseID, true);
					
				} else {
					$paymentmethod = $paymentmethods[$purchase->paymentmethodID];
					if ($paymentmethod->accountID != $purchase->payableaccountID) {
						echo "<br> ---- paymentmethod account missmatch - purchaseID: " . $purchase->purchaseID;
						$missmatchcounter++;
					}
				}
			}
		}
	}
	
	
	echo "<br><br>nullcounter - " . $nullcounter;
	echo "<br>puuttuvat paymentmehodit - " . $missingreceipts;
	echo "<br><br>missmatch - " . $missmatchcounter;
	
	
	
	
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