<?php

	// Listataan kaikki conceptit, joissa on useampi kuin yksi sana kiinnitettynä.
	// Näihin tapauksiin pitäisi asettaa ainoastaan yksi sana defaultiksi...
	
	global $mysqli;

	$purchaserows = Table::load("accounting_purchaserows", "WHERE Purchasedate IS NULL", true);

	$purchaselist = array();
	foreach($purchaserows as $index => $purchaserow) {
		echo "<br>PurchaseID - " . $purchaserow->purchaseID;		
		$purchaselist[$purchaserow->purchaseID] = $purchaserow->purchaseID; 
	}

	$purchases = Table::load("accounting_purchases");
	
	foreach($purchaserows as $index => $purchaserow) {
		$purchase = $purchases[$purchaserow->purchaseID];
		
		$values = array();
		$values['Purchasedate'] = $purchase->purchasedate;
		//Table::updateRow("accounting_purchaserows", $values, $purchaserow->rowID);
		echo "<br>RowID: " . $purchaserow->rowID . ", purchasedate:" . $purchase->purchasedate . ", purchaseID:" . $purchase->purchaseID;
	}
	
	echo "<br><br>..............";
	

?>