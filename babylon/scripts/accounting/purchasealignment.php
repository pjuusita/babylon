<?php

	// Listataan kaikki conceptit, joissa on useampi kuin yksi sana kiinnitettynä.
	// Näihin tapauksiin pitäisi asettaa ainoastaan yksi sana defaultiksi...
	
	global $mysqli;

	$purchases = Table::load("accounting_purchases", "WHERE SystemID=3 AND State=4 AND Purchasedate > '2019-12-31' AND Paymentdate IS NULL", true);
	//$entries = Table::load("accounting_entries", "WHERE SystemID=3 AND Entrydate > '2019-12-31'", true);

	foreach($purchases as $index => $purchase) {
		echo "<br>PurchaseID - " . $purchase->purchaseID;		
	}

	echo "<br><br>..............";

	
	$purchases = Table::load("accounting_purchases", "WHERE SystemID=3 AND State=3 AND Purchasedate > '2019-12-31' AND Paymentdate IS NULL", true);
	//$entries = Table::load("accounting_entries", "WHERE SystemID=3 AND Entrydate > '2019-12-31'", true);
	
	foreach($purchases as $index => $purchase) {
		echo "<br>PurchaseID - " . $purchase->purchaseID;
	}
	
	echo "<br><br>..............";
	

?>