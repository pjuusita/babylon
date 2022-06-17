<?php

	
	// State=0 tilassa olevilla ostolaskuilla ei saisi olla lainkaan vientejä.
	// Tätä on muutettu.

	global $mysqli;

	$receipts = Table::load("accounting_receipts", "WHERE ReceiptsetID=3");
	$entries = Table::load("accounting_entries");
	$statements = Table::load("accounting_bankstatements");
	
	foreach($receipts as $index => $receipt) {
		echo "<br>ReceiptID:" . $receipt->receiptID . " -- " . $receipt->explanation;
		
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $receipt->bankstatementrowID);
		
		if ($bankstatementrow == null) {
			echo "<br>Tuntematon bankstatement row - "  . $receipt->bankstatementrowID . ", in receipt " . $receipt->receiptID;
		} else {
			$bankstatement = Table::loadRow('accounting_bankstatements', $bankstatementrow->bankstatementID);
			$startday = substr($bankstatement->startdate, 8);
			$startmonth = substr($bankstatement->startdate, 5, 2);
			$startyear = substr($bankstatement->startdate, 0, 4);
			$endday = substr($bankstatement->startdate, 8);
			$endmonth = substr($bankstatement->startdate, 5, 2);
			$endyear = substr($bankstatement->startdate, 0, 4);
			
			if (($startmonth == $endmonth) && ($startyear == $endyear)) {
				$bankstatementname = $startyear . "/" . $startmonth;
			} else {
				$bankstatementname = $startday . ".". $startmonth . "." . $startyear;
			}
			
			$values = array();
			$values['Explanation'] = "Tiliote " . $bankstatementname . ", " . sqlDateToStr($bankstatementrow->entrydate);
			$success = Table::updateRow('accounting_receipts', $values, $receipt->receiptID, false);
			
			echo "<br> --- " . $values['Explanation'];
		}
		
	}
	

	echo "<br><br>finnished..............";


?>