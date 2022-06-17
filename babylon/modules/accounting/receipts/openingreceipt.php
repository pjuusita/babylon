<?php





echo "<h1>Tilikauden avaus</h1>";

echo "<table>";
echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td style='width:20px;'></td>";
echo "		<td style='width:20px;'></td>";
echo "		<td style='width:20px;'></td>";
echo "		<td style='width:400px;'></td>";
echo "		<td style='width:150px;'></td>";
echo "	</tr>";


$vastaavaa = 0;
$vastattavaa = 0;

foreach($registry->accounthierarchy as $index => $account) {

	
	if ($account->accounttypeID == 1) {

		echo "	<tr  class='listtable-row'>";
		echo "		<td colspan=5 style='font-weight:bold;font-size:22px;'>" . $account->name . "</td>";
		echo "		<td style='width:100px;'></td>";
		echo "	</tr>";
		
		
		$vastaavaa = $vastaavaa + generateLevelOne($account, $this->registry->accounts, $this->registry->entries, true);
		//echo "<br>Tulot - " . $tulot;
		
		echo "	<tr  class='listtable-row'>";
		echo "		<td colspan=5 style='font-weight:bold;font-size:22px;'>" . $account->name . " yhteensä</td>";
		echo "		<td style='width:100px;font-weight:bold;font-size:22px;text-align:right;'>" . number_format($vastaavaa,2,","," ") . " €</td>";
		echo "	</tr>";
	}
	
	if ($account->accounttypeID == 2) {

		echo "	<tr  class='listtable-row'>";
		echo "		<td colspan=5 style='height:14px;'></td>";
		echo "	</tr>";
		
		echo "	<tr  class='listtable-row'>";
		echo "		<td colspan=5 style='font-weight:bold;font-size:22px;'>" . $account->name . "</td>";
		echo "		<td style='width:100px;'></td>";
		echo "	</tr>";
		
		
		$vastattavaa = $vastattavaa + generateLevelOne($account, $this->registry->accounts, $this->registry->entries);
		//echo "<br>Tulot - " . $tulot;
		
		echo "	<tr  class='listtable-row'>";
		echo "		<td colspan=5 style='font-weight:bold;font-size:22px;'>" . $account->name . " yhteensä</td>";
		if ($vastaavaa == $vastattavaa) {
			echo "		<td style='background-color:lightgreen;width:100px;font-weight:bold;font-size:22px;text-align:right;'>" . number_format($vastattavaa,2,","," ") . " €</td>";
		} else {
			echo "		<td style='background-color:pink;width:100px;font-weight:bold;font-size:22px;text-align:right;'>" . number_format($vastattavaa,2,","," ") . " €</td>";
		}
		echo "	</tr>";
	}
}

echo "	<tr  class='listtable-row'>";
echo "		<td colspan=5 style='height:14px;'></td>";
echo "	</tr>";


/*
$tulos = $tulot - $menot;

echo "	<tr  class='listtable-row'>";
echo "		<td colspan=5 style='font-weight:bold;font-size:22px;'>Tulos</td>";
echo "		<td style='width:100px;font-weight:bold;font-size:22px;text-align:right;'>" . number_format($tulos,2,","," ") . " €</td>";
echo "	</tr>";
*/


function generateLevelOne($account, $accounts, $entries) {
	
	$childs = $account->getChilds();
	$totalsum = 0;
	if ($childs == nulL) return 0;
	
	$totalsum = 0;
	foreach($childs as $index => $childaccount) {
		
		$selectedentries = array();
		$sum = 0;
		if (count($entries) > 0) {
			foreach($entries as $index => $entry) {
				if ($entry->accountID == $childaccount->accountID) {
					$sum = $sum + $entry->amount;
					$totalsum = $totalsum + $entry->amount;
				}
			}
		}
		
		$subamount = calculateLevelTwo($childaccount, $accounts, $entries);
		
		//if ($subamount != 0) {
			echo "	<tr  class='listtable-row'>";
			echo "		<td></td>";
			echo "		<td colspan=4 style='font-weight:bold;font-size:20px;'>" . $childaccount->name . "</td>";
			if ($sum > 0) {
				echo "		<td style='width:100px;'>" . number_format($sum,2,","," ") . " €</td>";
			} else {
				//echo "		<td style='width:100px;'>" . number_format($subamount,2,","," ") . "</td>";
				echo "		<td style='width:100px;'></td>";
			}
			echo "	</tr>";
			
			$level2 = generateLevelTwo($childaccount, $accounts, $entries);
			//echo "<br>Level 2 - total " . $level2;
			
			$totalsum = $totalsum + $level2;
		//}
	}
	return $totalsum;
}


function calculateLevelTwo($account, $accounts, $entries) {

	$childs = $account->getChilds();
	$totalsum = 0;
	if ($childs == nulL) return 0;
	
	foreach($childs as $index => $childaccount) {
	
		$selectedentries = array();
		$sum = 0;
		foreach($entries as $index => $entry) {
			if ($entry->accountID == $childaccount->accountID) {
				$sum = $sum + $entry->amount;
				$totalsum = $totalsum + $entry->amount;
			}
		}
		$level3sum = calculateLevelThree($childaccount, $accounts, $entries);
		//echo "<br>Level 3 - total " . $totalsum;
		$totalsum = $totalsum + $level3sum;
	}
	
	return $totalsum;
}


function generateLevelTwo($account, $accounts, $entries) {
	
	$childs = $account->getChilds();
	$totalsum = 0;
	if ($childs == nulL) return 0;
	
	foreach($childs as $index => $childaccount) {
	
		$selectedentries = array();
		$sum = 0;
		foreach($entries as $index => $entry) {
			if ($entry->accountID == $childaccount->accountID) {
				$sum = $sum + $entry->amount;
				$totalsum = $totalsum + $entry->amount;
			}
		}
	
		$subamount = calculateLevelTwo($childaccount, $accounts, $entries);
		
		//if ($subamount != 0) {
			echo "	<tr  class='listtable-row'>";
			echo "		<td></td>";
			echo "		<td></td>";
			echo "		<td colspan=3 style='font-weight:bold;font-size:18px;'>" . $childaccount->name . "</td>";
			if ($sum > 0) {
				echo "		<td style='width:100px;'>" . number_format($sum,2,","," ") . " €</td>";
			} else {
				//echo "		<td style='width:100px;'>" . number_format($subamount,2,","," ") . "</td>";
				echo "		<td style='width:100px;'></td>";
			}
			echo "	</tr>";
			
			$totalsum = $totalsum + generateLevelThree($childaccount, $accounts, $entries);
			
		//}
		
		
		
	}
	return $totalsum;	
}


function calculateLevelThree($account, $accounts, $entries) {

	$childs = $account->getChilds();
	$totalsum = 0;
	if ($childs == nulL) return 0;
	
	foreach($childs as $index => $childaccount) {

		$selectedentries = array();
		$sum = 0;
		foreach($entries as $index => $entry) {
			if ($entry->accountID == $childaccount->accountID) {
				//echo "<br>AccountMatch - " . $entry->accountID . " - " . $entry->amount;
				$sum = $sum + $entry->amount;
				$totalsum = $totalsum + $entry->amount;
			}
		}
		//$totalsum = $totalsum + generateLevelTwo($account, $accounts, $entries);
	}
	return $totalsum;
}


function generateLevelThree($account, $accounts, $entries) {

	$childs = $account->getChilds();
	$totalsum = 0;
	if ($childs == nulL) return 0;
	
	foreach($childs as $index => $childaccount) {

		$selectedentries = array();
		$sum = 0;
		foreach($entries as $index => $entry) {
			if ($entry->accountID == $childaccount->accountID) {
				//echo "<br>AccountMatch - " . $entry->accountID . " - " . $entry->amount;
				$sum = $sum + $entry->amount;
			}
		}

		if ($childaccount->accounttypeID == 2) {
			$sum = $sum * -1;
		}
		$totalsum = $totalsum + $sum;
		
		$subamount = calculateLevelFour($childaccount, $accounts, $entries);
		
		//if ($sum != 0) {
			if ($sum != 0) {
				echo "	<tr  class='listtable-row'>";
				echo "		<td></td>";
				echo "		<td></td>";
				echo "		<td></td>";
				echo "		<td colspan=2 style='font-size:18px;'>" . $childaccount->name . "</td>";
				if ($sum != 0) {
					echo "		<td style='width:100px;font-size:20px;text-align:right;'>" . number_format($sum,2,","," ") . " €</td>";
				} else {
					echo "		<td style='width:100px;font-size:20px;text-align:right;'>" . number_format(0,2,","," ") . " €</td>";
				}
				echo "	</tr>";
			}
		//}
		
		
		$totalsum = $totalsum + generateLevelFour($childaccount, $accounts, $entries);
		
		//$totalsum = $totalsum + generateLevelTwo($account, $accounts, $entries);
	}
	return $totalsum;
}




function calculateLevelFour($account, $accounts, $entries) {

	$childs = $account->getChilds();
	$totalsum = 0;
	if ($childs == nulL) return 0;
	
	foreach($childs as $index => $childaccount) {

		$selectedentries = array();
		$sum = 0;
		foreach($entries as $index => $entry) {
			if ($entry->accountID == $childaccount->accountID) {
				//echo "<br>AccountMatch - " . $entry->accountID . " - " . $entry->amount;
				$sum = $sum + $entry->amount;
				$totalsum = $totalsum + $entry->amount;
			}
		}
		//$totalsum = $totalsum + generateLevelTwo($account, $accounts, $entries);
	}
	return $totalsum;
}


function generateLevelFour($account, $accounts, $entries) {

	$childs = $account->getChilds();
	$totalsum = 0;
	if ($childs == nulL) return 0;
	
	foreach($childs as $index => $childaccount) {

		$selectedentries = array();
		$sum = 0;
		foreach($entries as $index => $entry) {
			if ($entry->accountID == $childaccount->accountID) {
				//echo "<br>AccountMatch - " . $entry->accountID . " - " . $entry->amount;
				$sum = $sum + $entry->amount;
			}
		}

		if ($childaccount->accounttypeID == 2) {
			$sum = $sum * -1;
		}
		$totalsum = $totalsum + $sum;


		//if ($sum != 0) {
			echo "	<tr  class='listtable-row'>";
			echo "		<td></td>";
			echo "		<td></td>";
			echo "		<td></td>";
			echo "		<td></td>";
			echo "		<td colspan=1 style='font-size:18px;'>" . $childaccount->name . "</td>";
			if ($sum != 0) {
				echo "		<td style='width:100px;font-size:20px;text-align:right;'>" . number_format($sum,2,","," ") . " €</td>";
			} else {
				echo "		<td style='width:100px;font-size:20px;text-align:right;'>" . number_format(0,2,","," ") . " €</td>";
			}
			echo "	</tr>";
		//}


		//$totalsum = $totalsum + generateLevelTwo($account, $accounts, $entries);
	}
	return $totalsum;
}




?>