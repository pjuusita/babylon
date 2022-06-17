<?php

	// Listataan kaikki conceptit, joissa on useampi kuin yksi sana kiinnitettynä.
	// Näihin tapauksiin pitäisi asettaa ainoastaan yksi sana defaultiksi...
	
	global $mysqli;

	//$receipts = Table::load("accounting_receipts", "WHERE Debet IS NULL or Credit IS NULL", true);
	$receipts = Table::load("accounting_receipts", "WHERE Debet < 0 or Credit < 0", true);
	
	//$purchaselist = array();
	foreach($receipts as $index => $receipt) {
		echo "<br>ReceiptID - " . $receipt->receiptID;		
		//$purchaselist[$receipt->purchaseID] = $receipt->purchaseID; 
	}

	foreach($receipts as $index => $receipt) {

		$entries = Table::load("accounting_entries", "WHERE ReceiptID=" . $receipt->receiptID);

		$debet = 0;
		$credit = 0;
		foreach($entries as $index => $entry) {
			if ($entry->amount > 0) {
				$debet = $debet + $entry->amount;
			} else {
				$credit = $credit + (-1 * $entry->amount);
			}
		}
		$values = array();
		$values['Debet'] = $debet;
		$values['Credit'] = $credit;
		$success = Table::updateRow("accounting_receipts", $values, $receipt->receiptID);	
	}
	
	echo "<br><br>..............";
	

?>