<?php

	// Listataan kaikki conceptit, joissa on useampi kuin yksi sana kiinnitettynä.
	// Näihin tapauksiin pitäisi asettaa ainoastaan yksi sana defaultiksi...
	
	global $mysqli;

	$receipts = Table::load("accounting_receipts", "WHERE PurchaseID > 0", true);

	$purchaselist = array();
	foreach($receipts as $index => $receipt) {
		echo "<br>PurchaseID - " . $receipt->purchaseID;		
		$purchaselist[$receipt->purchaseID] = $receipt->purchaseID; 
	}

	$purchases = Table::load("accounting_purchases");
	echo "<br><br>--------------------------------------";
	foreach($receipts as $index => $receipt) {
		$purchase = $purchases[$receipt->purchaseID];
		
		$values = array();
		$values['SupplierID'] = $purchase->supplierID;
		echo "<br>ReceiptID: " . $receipt->receiptID . ", purchaseID:" . $purchase->purchaseID . ", supplierID:" . $purchase->supplierID;
			
		$values = array();
		$values['SupplierID'] = $purchase->supplierID;
		//Table::updateRow("accounting_receipts", $values, $receipt->receiptID);
	}
	
	echo "<br><br>..............";
	

?>