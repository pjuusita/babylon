<?php

$showdimension = true;

global $dimensionselect;
$dimensionselect = 1;

global $dimensionarray;

if (count($this->registry->dimensionvalues) == 0) {
	$dimensionarray = array();
} else {
	$dimensionarray = $this->registry->dimensionvalues[$dimensionselect];
}


echo "<table style='width:900px;'>";
echo "	<tr>";
echo "		<td style='width:100%;text-align:right;'>";
echo "			<select id=periodselectfield class='field-select' style='width:120px;margin-right:5px;'>";
foreach($this->registry->periods as $index => $period) {
	if ($this->registry->periodID ==  $period->periodID) {
		echo "		<option selected='selected' value='" . $index . "'>" . $period->fullname . "</option>";
	} else {
		echo "		<option value='" . $index . "'>" . $period->fullname . "</option>";
	}
}
echo "			</select>";

echo "	<script>";
echo "		$('#periodselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/incomestatement/showincomestatement')."&periodID='+this.value;";
echo "		});";
echo "	</script>";


echo "			<select id=selectionselectfield class='field-select' style='width:120px;margin-right:5px;'>";
echo "				<option value='0'></option>";
foreach($this->registry->selection as $index => $value) {
	if ($this->registry->selectionID ==  $value->rowID) {
		echo "		<option  selected='selected' value='" . $value->rowID . "'>" . $value->name . "</option>";
	} else {
		echo "		<option value='" . $value->rowID . "'>" . $value->name . "</option>";
	}
}
echo "			</select>";

echo "	<script>";
echo "		$('#selectionselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/incomestatement/showincomestatement')."&selectionID='+this.value;";
echo "		});";
echo "	</script>";


echo "			<input class=uitextfield id=startdatefield type='text'";
echo " 				style='width:83px;margin-right:3px;' value='" . sqlDateToStr($this->registry->startdate) . "' readonly>";
echo "-";
echo "			<input class=uitextfield id=enddatefield type='text'";
echo " 				style='width:83px;' value='" . sqlDateToStr($this->registry->enddate) . "' readonly>";

echo "	<script>";
echo "		$('#startdatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
echo "	</script>";

echo "	<script>";
echo "		$('#enddatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
echo "	</script>";

echo "		</td>";
echo "	</tr>";
echo "</table>";



echo "	<script>";
echo "		$('#accountselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/accountbalances/showaccountbalances')."&periodID='+this.value;";
echo "		});";
echo "	</script>";


foreach( $this->registry->entries as $index => $entry) {
	$account = $this->registry->accounts[$entry->accountID];
	//echo "<br>" . $entry->accountID . " - " . $account->name . " - " . $entry->amount;
}


//echo "<h1>Tulot</h1>";
echo "<br>";
echo "<br>";

echo "<table>";
echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td style='width:20px;'></td>";
echo "		<td style='width:20px;'></td>";
echo "		<td style='width:20px;'></td>";
echo "		<td style='width:400px;'></td>";
echo "		<td style='width:150px;'></td>";

echo "		<td style='width:50px;'></td>";
echo "		<td style='width:20px;'></td>";


if ($dimensionselect > 0) {
	foreach($dimensionarray as $index => $dimensionvalue) {
		echo "		<td style='width:130px;font-size:20px;text-align:right;font-weight:bold;'>" . $dimensionvalue->abbreviation . "</td>";
	}
}

echo "		<td style='width:50px;'></td>";

echo "		<td style='width:50px;'></td>";
echo "		<td style='width:100px;'></td>";

echo "	</tr>";


$tulot = 0;
$menot = 0;

foreach($registry->accounthierarchy as $index => $account) {

	
	if ($account->accounttypeID == 3) {

		echo "	<tr  class='listtable-row'>";
		echo "		<td colspan=4 style='font-weight:bold;font-size:22px;'>" . $account->name . "</td>";
		echo "		<td style='width:100px;'></td>";
		echo "	</tr>";
		
		
		$tulot = $tulot + generateLevelOne($account, $this->registry->accounts, $this->registry->entries, true);
		//echo "<br>Tulot - " . $tulot;
		
		echo "	<tr  class='listtable-row'>";
		echo "		<td colspan=5 style='font-weight:bold;font-size:22px;'>" . $account->name . " yhteensä</td>";
		echo "		<td style='width:100px;font-weight:bold;font-size:22px;text-align:right;'>" . number_format($tulot,2,","," ") . " €</td>";
		echo "	</tr>";
	}
	
	if ($account->accounttypeID == 4) {

		echo "	<tr  class='listtable-row'>";
		echo "		<td colspan=4 style='font-weight:bold;font-size:22px;'>" . $account->name . "</td>";
		echo "		<td style='width:100px;'></td>";
		echo "	</tr>";
		
		
		$menot = $menot + generateLevelOne($account, $this->registry->accounts, $this->registry->entries);
		//echo "<br>Tulot - " . $tulot;
		
		echo "	<tr  class='listtable-row'>";
		echo "		<td colspan=5 style='font-weight:bold;font-size:22px;'>" . $account->name . " yhteensä</td>";
		echo "		<td style='width:100px;font-weight:bold;font-size:22px;text-align:right;'>" . number_format($menot,2,","," ") . " €</td>";
		echo "	</tr>";
		
	}
}




echo "	<tr  class='listtable-row'>";
echo "		<td colspan=5 style='height:14px;'></td>";
echo "	</tr>";


$tulos = $tulot - $menot;

echo "	<tr  class='listtable-row'>";
echo "		<td colspan=5 style='font-weight:bold;font-size:22px;'>Tulos ennen veroja</td>";
echo "		<td style='width:100px;font-weight:bold;font-size:22px;text-align:right;'>" . number_format($tulos,2,","," ") . " €</td>";
echo "	</tr>";

$tulosaccounts = array();

foreach($this->registry->entries as $index => $entry) {
	$account = $this->registry->accounts[$entry->accountID];
	if ($account->accounttypeID == 6) {
			// Tuloveroja ei oteta mukaan
		if (!isset($tulosaccounts[$entry->accountID])) $tulosaccounts[$entry->accountID] = 0;
		$tulosaccounts[$entry->accountID] = $tulosaccounts[$entry->accountID] + $entry->amount;
	} else {
		//$sum = $sum + $entry->amount;
	}
}

$taxsum = 0;

foreach($tulosaccounts as $accountID => $amount) {
	$account = $this->registry->accounts[$accountID];
	
	echo "	<tr  class='listtable-row'>";
	echo "		<td></td>";
	echo "		<td></td>";
	echo "		<td colspan=3 style='font-size:20px;'>" . $account->name . "</td>";
		echo "		<td style='width:100px;'>" . number_format($amount,2,","," ") . " €</td>";
	echo "	</tr>";
	$taxsum = $taxsum + $amount;
	
}


echo "	<tr  class='listtable-row'>";
echo "		<td colspan=5 style='font-weight:bold;font-size:22px;'>Tulos</td>";
echo "		<td style='width:100px;font-weight:bold;font-size:22px;text-align:right;'>" . number_format($tulos-$taxsum,2,","," ") . " €</td>";
echo "	</tr>";



function generateLevelOne($account, $accounts, $entries) {
	
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
				echo "		<td style='width:100px;'>" . number_format($sum,2,","," ") . " €</td>";
			} else {
				//echo "		<td style='width:100px;'>" . number_format($subamount,2,","," ") . "</td>";
				echo "		<td style='width:100px;'></td>";
			}
			echo "	</tr>";
			
			$totalsum = $totalsum + generateLevelTwo($childaccount, $accounts, $entries);
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
				
				$account = $accounts[$entry->accountID];
				if ($account->accounttypeID == 6) {
					// Tuloveroja ei oteta mukaan
				} else {
					$sum = $sum + $entry->amount;
					$totalsum = $totalsum + $entry->amount;
				}
				//$sum = $sum + $entry->amount;
				//$totalsum = $totalsum + $entry->amount;
			}
		}
		$totalsum = $totalsum + calculateLevelThree($childaccount, $accounts, $entries);
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
				

				$account = $accounts[$entry->accountID];
				if ($account->accounttypeID == 6) {
					// Tuloveroja ei oteta mukaan
				} else {
					$sum = $sum + $entry->amount;
					$totalsum = $totalsum + $entry->amount;
				}
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
				
				$account = $accounts[$entry->accountID];
				if ($account->accounttypeID == 6) {
					// Tuloveroja ei oteta mukaan
					//$sum = $sum + $entry->amount;
					//$totalsum = $totalsum + $entry->amount;
				} else {
					$sum = $sum + $entry->amount;
					$totalsum = $totalsum + $entry->amount;
				}
				
				//$sum = $sum + $entry->amount;
				//$totalsum = $totalsum + $entry->amount;
			}
		}
		//$totalsum = $totalsum + generateLevelTwo($account, $accounts, $entries);
	}
	return $totalsum;
}


function generateLevelThree($account, $accounts, $entries) {

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
				//echo "<br>AccountMatch - " . $entry->accountID . " - " . $entry->amount;
				//$sum = $sum + $entry->amount;
				
				//$sum = $sum + $entry->amount;
				$account = $accounts[$entry->accountID];
				if ($account->accounttypeID == 6) {
					// Tuloveroja ei oteta mukaan
					echo "<br>Tuloveroja - " . $entry->amount;
					//$sum = $sum + $entry->amount;
				} else {
					$sum = $sum + $entry->amount;
				}
				
			}
		}

		if ($childaccount->accounttypeID == 3) {
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
				
				echo "		<td></td>";
				echo "		<td style='width:100px;border-left:2px solid #333'></td>";
				
				$totaldimsum = 0;
				
				if ($dimensionselect > 0) {
					foreach($dimensionarray as $index => $value) {
							
						$variable = "dimension" . $dimensionselect;
						$dimsum = 0;
						foreach($entries as $index => $entry) {
							//echo "<br>Entry - " . $variable . " - " . $entry->$variable . " - " . $value->dimensionvalueID;
							if ($entry->accountID == $childaccount->accountID) {
								if ($entry->$variable == $value->dimensionvalueID) {
									//echo "<br>AccountMatch - " . $entry->accountID . " - " . $entry->amount;
									//echo "<br>Summaa - " . $entry->amount . " - " . $dimsum;
									$dimsum = $dimsum + $entry->amount;
								}
							}
						}
						//echo "<br>Summaa - " . $dimsum;
						if ($childaccount->accounttypeID == 3) {
							$dimsum = $dimsum * -1;
						}
						$totaldimsum = $totaldimsum + $dimsum;
						echo "		<td style='width:100px;font-size:20px;text-align:right;'>" . number_format($dimsum,2,","," ") . " €</td>";
					}
					
					if ((floatval($totaldimsum)) < floatval($sum)) {
						echo "		<td style='width:50px;'></td>";
						echo "		<td style='width:100px;color:pink;'>" . floatval($totaldimsum) . " vs. " . floatval($sum) . "</td>";
					} else {
						echo "		<td style=''>aa</td>";
						echo "		<td style=''>aaa</td>";
					}
						
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
				
				
				$account = $accounts[$entry->accountID];
				if ($account->accounttypeID == 6) {
					// Tuloveroja ei oteta mukaan
				} else {
					$sum = $sum + $entry->amount;
					$totalsum = $totalsum + $entry->amount;
				}
				
				//$sum = $sum + $entry->amount;
				//$totalsum = $totalsum + $entry->amount;
				
				//echo "<br>AccountMatch - " . $entry->accountID . " - " . $entry->amount;
			}
		}
		//$totalsum = $totalsum + generateLevelTwo($account, $accounts, $entries);
	}
	return $totalsum;
}


function generateLevelFour($account, $accounts, $entries) {

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
				
				//$sum = $sum + $entry->amount;
								
				$account = $accounts[$entry->accountID];
				if ($account->accounttypeID == 6) {
					 // Tuloveroja ei oteta mukaan
					 echo "<br>Tuloveroja - " . $entry->amount;
				} else {
					$sum = $sum + $entry->amount;
				}
			}
		}

		if ($childaccount->accounttypeID == 3) {
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
			//echo "		<td style='width:100px;font-size:20px;text-align:right;'>aaa " . $dimensionselect . "</td>";
			
			echo "		<td></td>";
			echo "		<td style='width:100px;border-left:2px solid #333'></td>";
			
			
			if ($dimensionselect > 0) {
				
				$totaldimsum = 0;
				
				if (count($dimensionarray) == 0) {
					$variable = "dimension" . $dimensionselect;
					$dimsum = 0;
					foreach($entries as $index => $entry) {
						if ($entry->accountID == $childaccount->accountID) {
							$dimsum = $dimsum + $entry->amount;
						}
					}
					if ($childaccount->accounttypeID == 3) {
						$dimsum = $dimsum * -1;
					}
					echo "		<td style='width:130px;font-size:20px;text-align:right;'>" . number_format($dimsum,2,","," ") . " €</td>";
					$totaldimsum = $totaldimsum + $dimsum;
				} else {
					foreach($dimensionarray as $index => $value) {
							
						$variable = "dimension" . $dimensionselect;
						$dimsum = 0;
						foreach($entries as $index => $entry) {
							//echo "<br>Entry - " . $variable . " - " . $entry->$variable . " - " . $value->dimensionvalueID;
							if ($entry->accountID == $childaccount->accountID) {
								if ($entry->$variable == $value->dimensionvalueID) {
									//echo "<br>AccountMatch - " . $entry->accountID . " - " . $entry->amount;
									//echo "<br>Summaa - " . $entry->amount . " - " . $dimsum;
									$dimsum = $dimsum + $entry->amount;
								}
							}
						}
						//echo "<br>Summaa - " . $dimsum;
						if ($childaccount->accounttypeID == 3) {
							$dimsum = $dimsum * -1;
						}
							
						echo "		<td style='width:130px;font-size:20px;text-align:right;'>" . number_format($dimsum,2,","," ") . " €</td>";
						$totaldimsum = $totaldimsum + $dimsum;
					}
				}
			}
			
			if (($totaldimsum + 0.001) < $sum) {
				echo "		<td style='width:50px;'></td>";
				echo "		<td style='width:100px;color:pink;'>" . $dimsum . " vs." . $totaldimsum . "</td>";
				//echo "		<td style='width:100px;color:pink;'>" . number_format($dimsum,2,","," ") . " €..bbb ." . $totaldimsum . "</td>";
			} else {
				echo "		<td></td>";
				echo "		<td></td>";
			}
			
			echo "	</tr>";
		//}


		//$totalsum = $totalsum + generateLevelTwo($account, $accounts, $entries);
	}
	return $totalsum;
}




?>