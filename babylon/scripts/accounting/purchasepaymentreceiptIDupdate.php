<?php

	// Listataan kaikki conceptit, joissa on useampi kuin yksi sana kiinnitettynä.
	// Näihin tapauksiin pitäisi asettaa ainoastaan yksi sana defaultiksi...
	
	global $mysqli;

	$purchases = Table::load("accounting_purchases", "WHERE State=4", true);

	//$purchaselist = array();
	foreach($purchases as $index => $purchase) {
		//echo "<br>PurchaseID - " . $purchase->purchaseID;		
		//$purchaselist[$purchase->purchaseID] = $purchase->purchaseID; 
	}
	echo "<br><br>--------------------------------------";
	
	$receipts = Table::load("accounting_receipts", "WHERE PurchaseID > 0 AND BankstatementrowID > 0");
	foreach($purchases as $index => $purchase) {
		
		echo "<br>Looping purchase - " . $purchase->purchaseID;
		foreach($receipts as $index2 => $receipt) {
			if ($receipt->purchaseID == $purchase->purchaseID) {
				echo "<br> - receipt found - " . $receipt->receiptID;
				
				$values = array();
				$values['PaymentreceiptID'] = $receipt->receiptID;
				Table::updateRow("accounting_purchases", $values, $purchase->purchaseID);
			}
		}
		
		/*
		$values = array();
		$values['SupplierID'] = $purchase->supplierID;
		echo "<br>ReceiptID: " . $receipt->receiptID . ", purchaseID:" . $purchase->purchaseID . ", supplierID:" . $purchase->supplierID;
		$values = array();
		$values['SupplierID'] = $purchase->supplierID;
		//Table::updateRow("accounting_receipts", $values, $receipt->receiptID);
		*/
	}
	
	echo "<br><br>..............";
	

?>

