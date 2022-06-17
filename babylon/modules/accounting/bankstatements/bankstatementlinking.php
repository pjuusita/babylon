<?php

	// Pitäisi johonkin varmaan entryyn merkitä, onko kyseinen rivi jo linkitetty ja mihin
	// Tai sitten vaihtoehtoisesti merkitään omaan taulukkoonsa. Samalla tilikaudella.
	
	echo "<br>Linking jee";

	echo "<br>Bankstatement row date - " . $this->registry->bankStatementRow->entrydate;
	echo "<br>Bankstatement row amount - " . $this->registry->bankStatementRow->amount;
	echo "<br>Bankstatement row status - " . $this->registry->bankStatementRow->status;
	
	$amount = abs($this->registry->bankStatementRow->amount);
	$rowID = $this->registry->bankStatementRow->rowID;
	echo "<br>Unlinked entries...";
	
	foreach($this->registry->receipts as $index => $receipt) {
		
		if ($receipt->grossamount == $amount) {
			echo "<br>" . $receipt->receiptID . ". " . $receipt->receiptdate . " - <span style='background-color:green;'>" . $receipt->grossamount . "</span>";
		} else {
			echo "<br>" . $receipt->receiptID . ". " . $receipt->receiptdate . " - " . $receipt->grossamount;
		}
		echo " <a href='" . getUrl('accounting/bankstatements/linkreceipt') . "&receiptID=" . $receipt->receiptID . "&rowID=" . $rowID . "'>linkitä</a>";
	}
	
	
?>