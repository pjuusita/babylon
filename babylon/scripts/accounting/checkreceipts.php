<?php

	// Listataan kaikki conceptit, joissa on useampi kuin yksi sana kiinnitettynä.
	// Näihin tapauksiin pitäisi asettaa ainoastaan yksi sana defaultiksi...
	
	global $mysqli;

	$receipts = Table::load("accounting_receipts", "WHERE SystemID=3 AND Receiptdate > '2019-12-31'", true);
	$entries = Table::load("accounting_entries", "WHERE SystemID=3 AND Entrydate > '2019-12-31'", true);

	$missingreceipts = 0;
	foreach($entries as $index => $entry) {
		if (!isset($receipts[$entry->receiptID])) {
			echo "<br> --------------------------------------- puuttuva receiptID: " . $entry->receiptID;
			$missingreceipts++;
		}
	}
	if ($missingreceipts > 0) {
		echo "<br>Missing receipts....";
		exit;
	}
	
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
		
		/*
		if ($receipt->debet < 0) {
			
			$delta = $receipt->credit - (-1 * $credit);
			echo "<br> - credit neg: " . $credit;
			if (($delta > 0.001) || ($delta < -0.001)) {
				echo "<br> --------------------------------------- credit difference ... - " . $receipt->receiptID . ", delta=" . $delta;
			}
			
			$delta = $receipt->debet - (-1 * $debet);
			echo "<br> - debet neg: " . $debet;
			if (($delta > 0.001) || ($delta < -0.001)) {
				echo "<br> --------------------------------------- debet difference ... - " . $receipt->receiptID . ", delta=" . $delta;
			}
			
		} else {

			$delta = $receipt->credit - (-1 * $credit);
			echo "<br> - credit pos: " . $credit;
			if (($delta > 0.001) || ($delta < -0.001)) {
				echo "<br> --------------------------------------- credit difference ... - " . $receipt->receiptID . ", delta=" . $delta;
			}
				
			$delta = $receipt->debet - (-1 * $debet);
			echo "<br> - debet pos: " . $debet;
			if (($delta > 0.001) || ($delta < -0.001)) {
				echo "<br> --------------------------------------- debet difference ... - " . $receipt->receiptID . ", delta=" . $delta;
			}
		}
		*/
		
		
	}

	echo "<br><br>..............";


?>