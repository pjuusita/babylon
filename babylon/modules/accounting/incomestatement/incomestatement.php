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
echo "<button class=section-button id='incomestatementbuttonpdf' style='margin-right:5px;font-size:16px;font-weight:bold;'>PDF</button>";
//$button = new UIButton(UIComponent::ACTION_NEWWINDOW, "payroll/payroll/paycheckpdf&paycheckID=", "PDF");
//$button->show();
//$section->addButton($button);

echo "<script>";
echo "  $('#incomestatementbuttonpdf').click(function () {";
echo "		opennewtab('" . getPdfUrl("accounting/incomestatement/incomestatementpdf") . "&periodID=" . $registry->periodID . "');";
echo "	});";
echo "</script>";


echo "<div class=top-button style='display:inline;width:32px;height:32px;padding-top:6px;padding-left:5px;'><i class='fa fa-cog fa-lg' ></i></div>";
//echo "<div class=top-button style='display:inline;width:32px;height:32px;padding-top:6px;padding-left:5px;'>P</div>";
echo "			</td>";
echo "		</tr>";
echo "	</table>";

echo "	<script>";
echo "		$('#periodselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/incomestatement/showincomestatement')."&periodID='+this.value;";
echo "		});";
echo "	</script>";






$tabsection = new UITabSection("","900px");

$tabIndex = $tabsection->addTab("" . $registry->period->name, "accounting/incomestatement/index&selectionID=0");
foreach($this->registry->selection as $index => $selection) {
	$tabIndex = $tabsection->addTab($selection->name, "accounting/incomestatement/index&selectionID=" . $selection->selectionID);
	//echo "<br>" . $selection->selectionID . " - " . $this->registry->selectionID;
	if ($selection->selectionID == $this->registry->selectionID){
		$tabsection->setActiveIndex($tabIndex);
	}
}


$showdimension = true;
global $dimensionselect;
$dimensionselect = 1;

global $dimensionarray;

if (count($this->registry->dimensionvalues) == 0) {
	$dimensionarray = array();
} else {
	$dimensionarray = $this->registry->dimensionvalues[$dimensionselect];
}


global $registry;
$registry = $this->registry;

function statementcontent() {

	$dimensionsactive = false;
	
	global $registry;
	
	echo "<br>";
	
	echo "<table style='width:800px:background-color:yellow;'>";
	echo "	<tr>";
	echo "		<td style='width:20px;'></td>";
	echo "		<td style='width:20px;'></td>";
	echo "		<td style='width:20px;'></td>";
	echo "		<td style='width:20px;'></td>";
	echo "		<td style='width:420px;'></td>";
	echo "		<td style='width:300px;'></td>";
	echo "	</tr>";
	
	$tulot = 0;
	$menot = 0;
	$firstincomeline = true;
	$first = false;
	
	foreach($registry->accounthierarchy as $index => $account) {
	
	
		if ($account->accounttypeID == 3) {
	
			if ($firstincomeline == true) {
				echo "	<tr  class='listtable-row'>";
				echo "		<td colspan=5 style='font-weight:bold;font-size:22px;'>" . $account->name . "</td>";
				echo "		<td style='width:150px;font-weight:bold;font-size:20px;text-align:right;'>" . substr(sqlDateToStr($registry->startdate),0,6) . " - " . sqlDateToStr($registry->enddate) . "</td>";
				echo "	</tr>";
			} else {
				echo "	<tr  class='listtable-row'>";
				echo "		<td colspan=5 style='font-weight:bold;font-size:22px;'>" . $account->name . "</td>";
				echo "		<td style='width:150px;'></td>";
				echo "	</tr>";
			}
	
	
			$tulot = $tulot + generateLevelOne($account, $registry->accounts, $registry->entries, true);
			//echo "<br>Tulot - " . $tulot;
	
			echo "	<tr  class='listtable-row'>";
			echo "		<td colspan=5 style='font-weight:bold;font-size:22px;'>" . $account->name . " yhteensä</td>";
			echo "		<td style='width:100px;font-weight:bold;font-size:22px;text-align:right;'>" . number_format($tulot,2,","," ") . " €</td>";
			echo "	</tr>";
		}
	
		if ($account->accounttypeID == 4) {
	
			if ($first == false) {
				$first = true;
				echo "	<tr  class='listtable-row'>";
				echo "		<td colspan=4 style='font-weight:bold;font-size:22px;pink;height:20px;'></td>";
				echo "		<td style='width:100px;'></td>";
				echo "	</tr>";
			}
			
			echo "	<tr  class='listtable-row'>";
			echo "		<td colspan=4 style='font-weight:bold;font-size:22px;'>" . $account->name . "</td>";
			echo "		<td style='width:100px;'></td>";
			echo "	</tr>";
	
	
			$menot = $menot + generateLevelOne($account, $registry->accounts, $registry->entries);
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
	
	foreach($registry->entries as $index => $entry) {
		$account = $registry->accounts[$entry->accountID];
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
		$account = $registry->accounts[$accountID];
	
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
}

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
	}

	/*
	echo "	<tr  class='listtable-row'>";
	echo "		<td></td>";
	echo "		<td colspan=4 style='font-weight:bold;font-size:20px;'>" . $account->name . " yhteensä</td>";
	if ($totalsum > 0) {
		echo "		<td  style='font-weight:bold;font-size:20px;'>" . number_format($totalsum,2,","," ") . " €</td>";
	} else {
		//echo "		<td style='width:100px;'>" . number_format($subamount,2,","," ") . "</td>";
		echo "		<td style='width:100px;'></td>";
	}
	echo "	</tr>";
	*/
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
				

				$tempaccount = $accounts[$entry->accountID];
				if ($tempaccount->accounttypeID == 6) {
					// Tuloveroja ei oteta mukaan
				} else {
					$sum = $sum + $entry->amount;
					$totalsum = $totalsum + $entry->amount;
				}
			}
		}

		$subamount = calculateLevelTwo($childaccount, $accounts, $entries);
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
	}
	
	if ($totalsum > 0) {
		echo "	<tr  class='listtable-row'>";
		echo "		<td></td>";
		echo "		<td colspan=4 style='font-weight:bold;font-size:20px;'>" . $account->name . " yhteensä</td>";
		echo "		<td style='font-weight:bold;font-size:20px;text-align:right;'>" . number_format($totalsum,2,","," ") . " €</td>";
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
				$tempaccount = $accounts[$entry->accountID];
				if ($tempaccount->accounttypeID == 6) {
					// Tuloveroja ei oteta mukaan
					//echo "<br>Tuloveroja - " . $entry->amount;
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
		} else {
			
			$countti = countLevelFour($childaccount, $accounts, $entries);
			if ($countti > 0) {
				echo "	<tr  class='listtable-row'>";
				echo "		<td></td>";
				echo "		<td></td>";
				echo "		<td></td>";
				echo "		<td colspan=2 style='font-size:18px;font-weight:bold;'>" . $childaccount->name . "</td>";
				echo "		<td></td>";
				echo "	</tr>";
			}
		}
		
		$totalsum = $totalsum + generateLevelFour($childaccount, $accounts, $entries);
	}
	
	if ($totalsum != 0) {
		echo "	<tr  class='listtable-row'>";
		echo "		<td></td>";
		echo "		<td></td>";
		echo "		<td colspan=3 style='font-weight:bold;font-size:18px;'>" . $account->name . " yhteensä</td>";
		echo "		<td style='font-weight:bold;font-size:18px;text-align:right;'>" . number_format($totalsum,2,","," ") . " €</td>";
		echo "	</tr>";
	}
	return $totalsum;
}




function countLevelFour($account, $accounts, $entries) {

	$childs = $account->getChilds();
	$count = 0;
	if ($childs == nulL) return 0;

	foreach($childs as $index => $childaccount) {
		foreach($entries as $index => $entry) {
			if ($entry->accountID == $childaccount->accountID) {
				$account = $accounts[$entry->accountID];
				if ($account->accounttypeID == 6) {
					// Tuloveroja ei oteta mukaan
				} else {
					$count++;
				}
			}
		}
	}
	return $count;
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
					 //echo "<br>Tuloveroja - " . $entry->amount;
				} else {
					$sum = $sum + $entry->amount;
				}
			}
		}

		if ($childaccount->accounttypeID == 3) {
			$sum = $sum * -1;
		}
		$totalsum = $totalsum + $sum;


		if ($sum != 0) {
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
			
			//echo "		<td></td>";
			//echo "		<td style='width:100px;border-left:2px solid #333'></td>";
			
			/*
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
					echo "		<td style='width:130px;font-size:20px;text-align:right;'>aa1aa " . number_format($dimsum,2,","," ") . " €</td>";
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
							
						echo "		<td style='width:130px;font-size:20px;text-align:right;'>aa2aa " . number_format($dimsum,2,","," ") . " €</td>";
						$totaldimsum = $totaldimsum + $dimsum;
					}
				}
			}
			
			if (($totaldimsum + 0.001) < $sum) {
				//echo "		<td style='width:50px;'></td>";
				//echo "		<td style='width:100px;color:blue;'>" . $dimsum . " vs." . $totaldimsum . "</td>";
			} else {
				//echo "		<td></td>";
				//echo "		<td></td>";
			}
			*/
			
			echo "	</tr>";
		}


		//$totalsum = $totalsum + generateLevelTwo($account, $accounts, $entries);
	}
	return $totalsum;
}



$tabsection->setCustomContent("statementcontent");
$tabsection->show();





?>