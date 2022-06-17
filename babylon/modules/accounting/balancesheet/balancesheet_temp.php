<?php



include_once SITE_PATH . "classes/pdf/pdf.class.php";


/*
$titlefont = new PDFFont('Arial',20,"");
$titletext = new PDFFont('Arial',14,"");

$GLOBALS['leftmaxx'] = 195;
$GLOBALS['level1indent'] = 25;
$GLOBALS['level2indent'] = 30;
$GLOBALS['level3indent'] = 35;
$GLOBALS['level4indent'] = 40;

$GLOBALS['level1yadd'] = 5;
$GLOBALS['level2yadd'] = 5;
$GLOBALS['level3yadd'] = 4;
$GLOBALS['level4yadd'] = 4;


$topLevelFont = new PDFFont('Arial',14,"B");
$GLOBALS['toplevelfont'] = $topLevelFont;

$level1font = new PDFFont('Arial',12,"B");
$GLOBALS['level1font'] = $level1font;

$level2font  = new PDFFont('Arial',11,"B");
$GLOBALS['level2font'] = $level2font;
$level3font  = new PDFFont('Arial',10,"B");
$GLOBALS['level3font'] = $level3font;
$level3normalfont  = new PDFFont('Arial',10,"");
$GLOBALS['level3normalfont'] = $level3normalfont;
$level4font  = new PDFFont('Arial',9,"");
$GLOBALS['level4font'] = $level4font;

$basefont = new PDFFont('Arial',10,"");
$subtitlefont = new PDFFont('Arial',10,"B");

$minfont = new PDFFont('Arial',7,"");
$GLOBALS['minfont'] = $minfont;
*/

$pdf = new PDF();
$pdf->show();

/*
$pdf->setDebug(false);
$title = new PDFText(80,12, "Tase", $titlefont);
$company = new PDFText(16,10, $registry->company->name, $titletext);
$businesscode = new PDFText(16,16, $registry->company->businesscode, $basefont);




$currentdate = date('Y-m-d H:i:s');

$currentdate = new PDFText($GLOBALS['leftmaxx'],16, "30.04.2021", $basefont);
$currentdate->setAlign("R");

$headerline = new PDFLine(16,24, $GLOBALS['leftmaxx'],25);

$pdf->addComponent($title);
$pdf->addComponent($company);
$pdf->addComponent($businesscode);
$pdf->addComponent($currentdate);
$pdf->addComponent($headerline);

$yPosition = 40;
$tulot = 0;

// Lasketaan tulos, tätä tarvitaan laskentaan...

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


*/


/*
foreach($registry->accounthierarchy as $index => $account) {

	if ($account->accounttypeID == 1) {

		$accountline = new PDFText(16, $yPosition, $account->name, $topLevelFont);
		
		$timeline = new PDFText($GLOBALS['leftmaxx'], $yPosition, sqlDateToStr($registry->enddate), $topLevelFont);
		$timeline->setAlign("R");
		$pdf->addComponent($accountline);
		$pdf->addComponent($timeline);
		
		$vastaavaa = $vastaavaa + generateLevelOne($account, $registry->accounts, $registry->entries, $yPosition, $pdf);
		
		$yPosition = $yPosition + $GLOBALS['level1yadd'];
		$tulotstr = number_format($tulot,2,",","");;
		$accountline = new PDFText(16, $yPosition, $account->name . " yhteensä", $topLevelFont);
		$timeline = new PDFText($GLOBALS['leftmaxx'], $yPosition, $tulotstr, $topLevelFont);
		$timeline->setAlign("R");
		$pdf->addComponent($accountline);
		$pdf->addComponent($timeline);
	}

	if ($account->accounttypeID == 2) {
		
		$yPosition = $yPosition + 10;
		$accountline = new PDFText(16, $yPosition, $account->name, $topLevelFont);
		
		$timeline = new PDFText($GLOBALS['leftmaxx'], $yPosition, substr(sqlDateToStr($registry->startdate),0,6) . " - " . sqlDateToStr($registry->enddate), $topLevelFont);
		$timeline->setAlign("R");
		$pdf->addComponent($accountline);

		$vastattavaa = $vastattavaa + generateLevelOne($account, $registry->accounts, $registry->entries, $yPosition, $pdf);
		
		$yPosition = $yPosition + $GLOBALS['level1yadd'];
		$tulotstr = number_format($tulot,2,",","");;
		$accountline = new PDFText(16, $yPosition, $account->name . " yhteensä", $topLevelFont);
		$timeline = new PDFText($GLOBALS['leftmaxx'], $yPosition, $tulotstr, $topLevelFont);
		$timeline->setAlign("R");
		$pdf->addComponent($accountline);
		$pdf->addComponent($timeline);
	}
}
*/


/*
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




function generateLevelOne($account, $accounts, $entries, &$yPosition, $pdf) {
	

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

		if ($subamount != 0) {
			$yPosition = $yPosition + $GLOBALS['level1yadd'];
			$accountline = new PDFText($GLOBALS['level1indent'], $yPosition, $childaccount->name, $GLOBALS['level1font']);
			
			$subamount2 = number_format($subamount,2,",","");
			$amountline = new PDFText($GLOBALS['leftmaxx'], $yPosition, "a" . $subamount2);
			$amountline->setAlign("R");
			
			if ($pdf == null) {
				echo "<br>pdf null";
			}
			$pdf->addComponent($accountline);
			if ($sum > 0) {
				$pdf->addComponent($amountline);
			}
		//} else {
			// alitilillä ei ole mitään, ehkä nykytilillä on...
		}
		$totalsum = $totalsum + generateLevelTwo($childaccount, $accounts, $entries, $yPosition, $pdf);
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



function generateLevelTwo($account, $accounts, $entries, &$yPosition, $pdf) {

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

		$subamount = calculateLevelThree($childaccount, $accounts, $entries);
		
		if ($subamount != 0) {
			$yPosition = $yPosition + $GLOBALS['level2yadd'];
			
			//$testline1 = new PDFText(125, $yPosition, number_format($subamount,2,","," ") , $GLOBALS['level4font']);
			//$pdf->addComponent($testline1);
			//$testline2 = new PDFText(150, $yPosition, number_format($sum,2,","," "), $GLOBALS['level4font']);
			//$pdf->addComponent($testline2);
			
			$accountline = new PDFText($GLOBALS['level2indent'], $yPosition, $childaccount->name, $GLOBALS['level2font']);
			$subamount2 = number_format($subamount,2,","," ");
			$amountline = new PDFText($GLOBALS['leftmaxx'], $yPosition, $subamount2, $GLOBALS['level2font']);
			$amountline->setAlign("R");
			$pdf->addComponent($accountline);
				
		} else {
			if ($sum > 0) {
				$yPosition = $yPosition + $GLOBALS['level2yadd'];
				
				//$testline1 = new PDFText(125, $yPosition, number_format($subamount,2,","," ") , $GLOBALS['level4font']);
				//$pdf->addComponent($testline1);
				//$testline2 = new PDFText(150, $yPosition, number_format($sum,2,","," "), $GLOBALS['level4font']);
				//$pdf->addComponent($testline2);
				
				$accountline = new PDFText($GLOBALS['level2indent'], $yPosition, $childaccount->name, $GLOBALS['level2font']);
				$subamount2 = number_format($subamount,2,","," ");
				$amountline = new PDFText($GLOBALS['leftmaxx'], $yPosition, $subamount2, $GLOBALS['level2font']);
				$amountline->setAlign("R");
				$pdf->addComponent($accountline);
				$pdf->addComponent($amountline);
				
			}
		}
		
					
		$totalsum = $totalsum + generateLevelThree($childaccount, $accounts, $entries, $yPosition, $pdf);
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


function generateLevelThree($account, $accounts, $entries, &$yPosition, $pdf) {

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
				$account = $accounts[$entry->accountID];
				if ($account->accounttypeID == 6) {
					// Tuloveroja ei oteta mukaan
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
		
		
		
		if ($subamount != 0) {
			$yPosition = $yPosition + $GLOBALS['level3yadd'];
			
			//$testline1 = new PDFText(135, $yPosition, number_format($subamount,2,","," ") , $GLOBALS['level4font']);
			//$pdf->addComponent($testline1);
			//$testline2 = new PDFText(150, $yPosition, number_format($sum,2,","," "), $GLOBALS['level4font']);
			//$pdf->addComponent($testline2);
			
			$accountline = new PDFText($GLOBALS['level3indent'], $yPosition, $childaccount->name, $GLOBALS['level3font']);
			$amountline = new PDFText($GLOBALS['leftmaxx'], $yPosition, number_format($subamount,2,","," "), $GLOBALS['level3font']);
			$amountline->setAlign("R");
			$pdf->addComponent($accountline);
		} else {
			if ($sum != 0) {
				$yPosition = $yPosition + $GLOBALS['level3yadd'];
				
				//$testline1 = new PDFText(135, $yPosition, number_format($subamount,2,","," ") , $GLOBALS['level4font']);
				//$pdf->addComponent($testline1);
				//$testline2 = new PDFText(150, $yPosition, number_format($sum,2,","," "), $GLOBALS['level4font']);
				//$pdf->addComponent($testline2);
				
				$accountline = new PDFText($GLOBALS['level3indent'], $yPosition, $childaccount->name, $GLOBALS['level3normalfont']);
				$amountline = new PDFText($GLOBALS['leftmaxx'], $yPosition, number_format($sum,2,","," "), $GLOBALS['level3normalfont']);
				$amountline->setAlign("R");
				$pdf->addComponent($accountline);
				$pdf->addComponent($amountline);
			} else {
				// ei tulosta riviä lainkaan jos molemmat on nollia...
			}
		}
		$totalsum = $totalsum + generateLevelFour($childaccount, $accounts, $entries, $yPosition, $pdf);
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



function generateLevelFour($account, $accounts, $entries, &$yPosition, $pdf) {

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

		
		// Nelostason riviä ei tehdä lainkaan, jos sen summa on nolla
		if ($sum > 0) {
			$yPosition = $yPosition + $GLOBALS['level4yadd'];
			
			//$testline2 = new PDFText(150, $yPosition, number_format($sum,2,","," "), $GLOBALS['level4font']);
			//$pdf->addComponent($testline2);
			
			$accountline = new PDFText($GLOBALS['level4indent'], $yPosition, $childaccount->name, $GLOBALS['level4font']);
			
			$subamount2 = number_format($sum,2,","," ");
			$amountline = new PDFText($GLOBALS['leftmaxx'], $yPosition, $subamount2, $GLOBALS['level4font']);
			$amountline->setAlign("R");
			if ($pdf == null) {
				echo "<br>pdf null";
			}
			$pdf->addComponent($accountline);
			$pdf->addComponent($amountline);
		}
		
	}
	return $totalsum;
}

*/




/*
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

*/


?>
