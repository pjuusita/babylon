<?php


echo "<table style='width:900px;'>";
echo "<tr>";
echo "		<td>";
echo "<div style='width:400px;display:flex;'>";
echo "<select id=periodselectfield class='top-select' style='display:inline;width:150px;margin-right:5px;margin-bottom:15px;'>";
foreach($this->registry->periods as $index => $period) {
	if ($this->registry->periodID ==  $period->periodID) {
		echo "<option selected='selected' value='" . $index . "'>" . $period->fullname . "</option>";
	} else {
		echo "<option value='" . $index . "'>" . $period->fullname . "</option>";
	}
}
echo "</select>";
echo "</div>";
echo "			</td>";
echo "			<td style='float:right;display:flex;'>";

// TODO plussa pitää ehkä heittää contentin sisään
//echo "<div class=top-button style='display:inline;width:32px;height:32px;padding-top:7px;padding-left:6px;margin-right:5px;'><i class='fa fa-plus fa-lg' ></i></div>";
echo "<button class=section-button id='balancesheetbuttonpdf' style='margin-right:5px;font-size:16px;font-weight:bold;'>PDF</button>";
echo "<div class=top-button style='display:inline;width:32px;height:32px;padding-top:6px;padding-left:5px;'><i class='fa fa-cog fa-lg' ></i></div>";
//echo "<div class=top-button style='display:inline;width:32px;height:32px;padding-top:6px;padding-left:5px;'>P</div>";
echo "			</td>";
echo "		</tr>";
echo "	</table>";

echo "	<script>";
echo "		$('#periodselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/balancesheet/showbalancesheet')."&periodID='+this.value;";
echo "		});";
echo "	</script>";


echo "<script>";
echo "  $('#balancesheetbuttonpdf').click(function () {";
echo "		opennewtab('" . getNoframesUrl("accounting/balancesheet/balancesheetpdf") . "');";
echo "	});";
echo "</script>";






$tabsection = new UITabSection("","900px");

$tabIndex = $tabsection->addTab("" . $registry->period->name, "accounting/balancesheet/showbalancesheet&selectionID=0");
foreach($this->registry->selection as $index => $selection) {
	$tabIndex = $tabsection->addTab($selection->name, "accounting/balancesheet/showbalancesheet&selectionID=" . $selection->selectionID);
	//echo "<br>" . $selection->selectionID . " - " . $registry->selectionID;
	if ($selection->selectionID == $registry->selectionID){
		$tabsection->setActiveIndex($tabIndex);
	}
}


$showdimension = true;
$dimensionselect = 1;

global $dimensionarray;

if (count($this->registry->dimensionvalues) == 0) {
	$dimensionarray = array();
} else {
	$dimensionarray = $registry->dimensionvalues[$dimensionselect];
}


global $registry;
$registry = $registry;

function statementcontent() {

	$dimensionsactive = false;
	
	global $registry;
	global $dimensionselect;
	
	echo "<table  class='listtable'>";
	echo "	<tr>";
	echo "		<td style='width:20px;'></td>";
	echo "		<td style='width:20px;'></td>";
	echo "		<td style='width:20px;'></td>";
	echo "		<td style='width:20px;'></td>";
	echo "		<td style='width:400px;'></td>";
	echo "		<td style='width:150px;text-align:right;font-weight:bold;'>Alkusaldo</td>";
	echo "		<td style='width:150px;text-align:right;font-weight:bold;'>Loppusaldo</td>";
	echo "		<td style='width:150px;text-align:right;font-weight:bold;'>Muutos</td>";
	
	if ($dimensionselect > 0) {
		echo "		<td style='width:20px;font-size:20px;text-align:right;border-right:2px solid #888888;'></td>";
		foreach($dimensionarray as $index => $value) {
			echo "		<td style='width:150px;font-size:20px;text-align:right;'>" . $value->abbreviation . "</td>";
		}
	}
	
	echo "	</tr>";
	
	
	echo "	</tr>";
	
	
	$vastaavaa = 0;
	$vastattavaa = 0;
	$tulot = 0;
	$menot = 0;
	
	$entries = $registry->entries;
	
	foreach($registry->accounthierarchy as $index => $account) {
	
		if ($account->accounttypeID == 3) {
			$tulot = $tulot + calculateLevelOne($account, $registry->accounts, $registry->entries, true);
		}
	
		if ($account->accounttypeID == 4) {
			$menot = $menot + calculateLevelOne($account, $registry->accounts, $registry->entries);
		}
	}
	
	
	$tulos = $tulot - $menot;
	
	$entry = new Row();
	$entry->accountID = $registry->totalprofitaccountID;
	$entry->amount = -1 * $tulos;
	$entry->accounttypeID = 2;
	$entries[] = $entry;
	
	$totalprofitaccount = $registry->allaccounts[$registry->totalprofitaccountID];
	$totalprofitaccount->selectionamount = $tulos;
	$totalprofitaccount->startamount = 0;
	
	foreach($registry->accounthierarchy as $index => $account) {
	
	
		if ($account->accounttypeID == 1) {
	
			echo "	<tr  class='listtable-row'>";
			echo "		<td colspan=5 style='font-weight:bold;font-size:22px;'>" . $account->name . "</td>";
			echo "		<td style='width:150px;'></td>";
			echo "	</tr>";
	
	
			$vastaavaa = $vastaavaa + generateLevelOne($account, $registry->accounts, $entries, $registry->allaccounts, true);
			//echo "<br>Tulot - " . $tulot;
	
			echo "	<tr  class='listtable-row'>";
			echo "		<td colspan=6 style='font-weight:bold;font-size:22px;'>" . $account->name . " yhteensä</td>";
			echo "		<td style='width:150px;font-weight:bold;font-size:22px;text-align:right;'>" . number_format($vastaavaa,2,","," ") . " €</td>";
			echo "	</tr>";
		}
	
		if ($account->accounttypeID == 2) {
	
			echo "	<tr  class='listtable-row'>";
			echo "		<td colspan=5 style='height:14px;'></td>";
			echo "	</tr>";
	
			echo "	<tr  class='listtable-row'>";
			echo "		<td colspan=6 style='font-weight:bold;font-size:22px;'>" . $account->name . "</td>";
			echo "		<td style='width:150px;'></td>";
			echo "	</tr>";
	
			$vastattavaa = $vastattavaa + generateLevelOne($account, $registry->accounts, $entries, $registry->allaccounts);
	
			echo "	<tr  class='listtable-row'>";
			echo "		<td colspan=6 style='font-weight:bold;font-size:22px;'>" . $account->name . " yhteensä</td>";
			$diff = (round($vastaavaa,2) - round($vastattavaa,2));
			if ($diff == 0) {
				echo "		<td style='background-color:lightgreen;width:150px;font-weight:bold;font-size:22px;text-align:right;'>" . number_format($vastattavaa,2,","," ") . " €</td>";
			} else {
				echo "		<td style='background-color:pink;width:150px;font-weight:bold;font-size:22px;text-align:right;'>" . number_format($vastattavaa,2,","," ") . " €</td>";
			}
			echo "	</tr>";
		}
	}
	echo "</table>";
}

$tabsection->setCustomContent("statementcontent");
$tabsection->show();




function calculateLevelOne($account, $accounts, $entries) {

	$childs = $account->getChilds();
	$totalsum = 0;
	if ($childs == nulL) return 0;

	foreach($childs as $index => $childaccount) {

		$selectedentries = array();
		$sum = 0;
		foreach($entries as $index => $entry) {
			if ($entry->accountID == $childaccount->accountID) {
				$sum = $sum + $entry->amount;
			}
		}
		if (($childaccount->accounttypeID == 2) || ($childaccount->accounttypeID == 3)) {
			$sum = $sum * -1;
		}
		$totalsum = $totalsum + $sum;
		$totalsum = $totalsum + calculateLevelTwo($childaccount, $accounts, $entries);
	}
	return $totalsum;
}



function generateLevelOne($account, $accounts, $entries, $allaccounts) {

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
		echo "		<td colspan=4 style='font-weight:bold;font-size:20px;'>" . $childaccount->name . "</td>";
		if ($sum > 0) {
			echo "		<td style='width:150px;'>" . number_format($sum,2,","," ") . " €</td>";
		} else {
			//echo "		<td style='width:150px;'>" . number_format($subamount,2,","," ") . "</td>";
			echo "		<td style='width:150px;'></td>";
		}
		//echo "		<td style='width:150px;text-align:right;'>" . $childaccount->accountID . "</td>";
		echo "		<td style='width:150px;text-align:right;'></td>";
		echo "	</tr>";
			
			
		$totalsum = $totalsum + generateLevelTwo($childaccount, $accounts, $entries, $allaccounts);
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
			}
		}
		if (($childaccount->accounttypeID == 2) || ($childaccount->accounttypeID == 3)) {
			$sum = $sum * -1;
		}
		$totalsum = $totalsum + $sum;
		$totalsum = $totalsum + calculateLevelThree($childaccount, $accounts, $entries);
	}
	return $totalsum;
}


function generateLevelTwo($account, $accounts, $entries, $allaccounts) {

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
			echo "		<td style='width:150px;'>" . number_format($sum,2,","," ") . " €</td>";
		} else {
			//echo "		<td style='width:150px;'>" . number_format($subamount,2,","," ") . "</td>";
			echo "		<td style='width:150px;'></td>";
		}
		//echo "		<td style='width:150px;text-align:right;'>" . $childaccount->accountID . "</td>";
		echo "		<td style='width:150px;text-align:right;'></td>";
		echo "	</tr>";
			
		$totalsum = $totalsum + generateLevelThree($childaccount, $accounts, $entries, $allaccounts);
	}
	

	if ($totalsum > 0) {
		echo "	<tr  class='listtable-row'>";
		echo "		<td></td>";
		echo "		<td colspan=5 style='font-weight:bold;font-size:20px;'>" . $account->name . " yhteensä</td>";
		echo "		<td style='font-weight:bold;font-size:20px;width:150px;text-align:right;'>" . number_format($totalsum,2,","," ") . " €</td>";
		echo "		<td style='width:150px;text-align:right;'></td>";
		echo "	</tr>";
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
				$sum = $sum + $entry->amount;
				$account = $accounts[$childaccount->accountID];
				//if ($account->accounttypeID == 3) echo "<br>Sum - " . $sum . " - " . $account->name . " - " . $entry->amount;
			}
		}
		if (($childaccount->accounttypeID == 2) || ($childaccount->accounttypeID == 3)) {
			$sum = $sum * -1;
		}
		$totalsum = $totalsum + $sum;
		$totalsum = $totalsum + calculateLevelFour($childaccount, $accounts, $entries);
		//if ($account->accounttypeID == 3) echo "<br>Total - " . $totalsum;
	}
	return $totalsum;
}


function generateLevelThree($account, $accounts, $entries, $allaccounts) {

	$childs = $account->getChilds();
	$totalsum = 0;
	if ($childs == nulL) return 0;

	foreach($childs as $index => $childaccount) {

		$selectedentries = array();
		$sum = 0;
		$entrycount = 0;
		foreach($entries as $index => $entry) {
			if ($entry->accountID == $childaccount->accountID) {
				//echo "<br>AccountMatch - " . $entry->accountID . " - " . $entry->amount;
				$sum = $sum + $entry->amount;
				$entrycount++;
			}
		}

		if (($childaccount->accounttypeID == 2) || ($childaccount->accounttypeID == 3)) {
			$sum = $sum * -1;
		}
		$totalsum = $totalsum + $sum;

		$subamount = calculateLevelFour($childaccount, $accounts, $entries);

		//if ($sum != 0) {
		//	if ($sum != 0) {
		if ($entrycount > 0) {
			echo "	<tr id='balancesheetrow-" . $childaccount->accountID . "' style='cursor:pointer;'>";
			echo "		<td></td>";
			echo "		<td></td>";
			echo "		<td></td>";
			echo "		<td colspan=2 style='font-weight:bold;font-size:18px;pointer:cursor;'>" . $childaccount->name . "</td>";
			$tempaccount = $allaccounts[$childaccount->accountID];
			echo "		<td style='width:150px;font-size:20px;text-align:right;'>" . number_format($tempaccount->startamount,2,","," ") . " €</td>";
			echo "		<td style='width:150px;font-size:20px;text-align:right;'>" . number_format(($tempaccount->startamount + $tempaccount->selectionamount),2,","," ") . " €</td>";

			if ($tempaccount->selectionamount == 0) {
				echo "		<td style='width:150px;font-size:20px;text-align:right;'>-</td>";
			} elseif ($tempaccount->selectionamount < 0) {
				echo "		<td style='width:150px;font-size:20px;text-align:right;'>" . number_format($tempaccount->selectionamount,2,","," ") . " €</td>";
			} else {
				echo "		<td style='width:150px;font-size:20px;text-align:right;'>+" . number_format($tempaccount->selectionamount,2,","," ") . " €</td>";
			}
			echo "	</tr>";

		} else {
			echo "	<tr  class='listtable-row'>";
			echo "		<td></td>";
			echo "		<td></td>";
			echo "		<td></td>";
			echo "		<td colspan=2 style='font-weight:bold;font-size:18px;pointer:cursor;'>" . $childaccount->name . "</td>";
			if (floatval($sum) == 0) {
				echo "		<td style='width:150px;font-size:20px;text-align:right;'></td>";
			} else {
				echo "		<td style='width:150px;font-size:20px;text-align:right;'>" . number_format(0,2,","," ") . " €</td>";
			}
			echo "		<td style='width:150px;text-align:right;'></td>";
			echo "	</tr>";
		}
		$totalsum = $totalsum + generateLevelFour($childaccount, $accounts, $entries, $allaccounts);
	}

	
	if ($totalsum > 0) {
		echo "	<tr  class='listtable-row'>";
		echo "		<td></td>";
		echo "		<td></td>";
		echo "		<td colspan=4 style='font-weight:bold;font-size:20px;'>" . $account->name . " yhteensä</td>";
		echo "		<td style='font-weight:bold;font-size:20px;width:150px;text-align:right;'>" . number_format($totalsum,2,","," ") . " €</td>";
		echo "		<td style='width:150px;text-align:right;'></td>";
		echo "	</tr>";
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
				$account = $accounts[$childaccount->accountID];
				//if ($account->accounttypeID == 3) echo "<br>Sum - " . $sum . " - " . $account->name;
				$sum = $sum + $entry->amount;
				
				if ($childaccount->number == 1720) {
					//echo "<br>" . $entry->entrydate . " - receiptID: " . $entry->receiptID . ", amount:" . $entry->amount . " ... " . $sum;
				}
				
			}
		}
		if (($childaccount->accounttypeID == 2) || ($childaccount->accounttypeID == 3)) {
			$sum = $sum * -1;
		}
		$totalsum = $totalsum + $sum;
	}
	return $totalsum;
}



function generateLevelFour($account, $accounts, $entries, $allaccounts) {

	global $dimensionselect;
	global $dimensionarray;

	$childs = $account->getChilds();
	$totalsum = 0;
	if ($childs == nulL) return 0;

	foreach($childs as $index => $childaccount) {

		$selectedentries = array();
		$sum = 0;
		foreach($entries as $index => $entry) {
			if ($entry->accountID == $childaccount->accountID) {
				if (($childaccount->accounttypeID == 2) || ($childaccount->accounttypeID == 4)) {
					$sum = $sum + (-1 * $entry->amount);
				} else {
					$sum = $sum + $entry->amount;
				}
				
			}
		}
		
		$totalsum = $totalsum + $sum;

		echo "	<tr id='balancesheetrow-" . $childaccount->accountID . "' style='cursor:pointer;'>";
		echo "		<td></td>";
		echo "		<td></td>";
		echo "		<td></td>";
		echo "		<td></td>";
		echo "		<td colspan=1 style='font-size:18px;pointer:cursor;'>" . $childaccount->name . "</td>";
		$tempaccount = $allaccounts[$childaccount->accountID];
			
		$startamount = $tempaccount->startamount;
		if (($childaccount->accounttypeID == 4)) {
			$selectionamount = $tempaccount->selectionamount * -1;
		} else {
			$selectionamount = $tempaccount->selectionamount;
		}
			
		if (($tempaccount->accounttype == 2) || ($tempaccount->accounttype == 3)) {
			echo "		<td style='width:150px;font-size:20px;text-align:right;'>" . number_format(($tempaccount->startamount),2,","," ") . " €</td>";
			echo "		<td style='width:150px;font-size:20px;text-align:right;'>" . number_format(((($tempaccount->startamount + $selectionamount))),2,","," ") . " €</td>";
		} else {
			echo "		<td style='width:150px;font-size:20px;text-align:right;'>" . number_format(( $tempaccount->startamount),2,","," ") . " €</td>";
			echo "		<td style='width:150px;font-size:20px;text-align:right;'>" . number_format(((($tempaccount->startamount + $selectionamount))),2,","," ") . " €</td>";
		}
		if ($selectionamount == 0) {
			echo "		<td style='width:150px;font-size:20px;text-align:right;'>-</td>";
		} elseif ($selectionamount < 0) {
			if (($tempaccount->accounttype == 2) || ($tempaccount->accounttype == 3)) {
				echo "		<td style='width:150px;font-size:20px;text-align:right;'>" . number_format($selectionamount,2,","," ") . " €</td>";
			} else {
				echo "		<td style='width:150px;font-size:20px;text-align:right;'>" . number_format(($selectionamount),2,","," ") . " €</td>";
			}
		} else {
			echo "		<td style='width:150px;font-size:20px;text-align:right;'>" . number_format($selectionamount,2,","," ") . " €</td>";
		}
		echo "	</tr>";
		
		echo "<script>";
		echo "  $('#balancesheetrow-" . $childaccount->accountID . "').click(function () {";
		echo "		openentriesdialog(" . $childaccount->accountID . ",'" . $childaccount->number . " - " . $childaccount->name . "');";
		echo "	});";
		echo "</script>";
	}
	

	if ($totalsum > 0) {
		echo "	<tr  class='listtable-row'>";
		echo "		<td></td>";
		echo "		<td></td>";
		echo "		<td></td>";
		echo "		<td colspan=3 style='font-weight:bold;font-size:20px;'>" . $account->name . " yhteensä</td>";
		echo "		<td style='font-weight:bold;font-size:20px;width:150px;text-align:right;'>" . number_format($totalsum,2,","," ") . " €</td>";
		echo "		<td style='width:150px;text-align:right;'></td>";
		echo "	</tr>";
	}
	
	
	
	return $totalsum;
}



?>