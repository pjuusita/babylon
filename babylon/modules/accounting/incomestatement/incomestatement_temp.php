<?php

//echo "<br>cwd - " . getcwd();
//echo "<br>sitepath - " . SITE_PATH;
//echo "<br><br>";

include_once SITE_PATH . "classes/pdf/pdf.class.php";
//include_once SITE_PATH . "classes/pdf/pdfcomponent.class.php";

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
$level2font  = new PDFFont('Arial',11,"");
$GLOBALS['level2normalfont'] = $level2font;
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


$pdf = new PDF();

$pdf->setDebug(false);
$pdf->SetAutoPageBreak(true);

//generateTopHeader($pdf, $titlefont,$titletext,$basefont, $title,$company,$businesscode,$currentdate,$headerline);

$title = new PDFText(80,12, "Tuloslaskelma", $titlefont);
$company = new PDFText(16,10, $registry->company->name, $titletext);
$businesscode = new PDFText(16,16, $registry->company->businesscode, $basefont);

$currentdate = date('Y-m-d H:i:s');

$currentdate = new PDFText($GLOBALS['leftmaxx'],16, "30.04.2022", $basefont);
$currentdate->setAlign("R");

$headerline = new PDFLine(16,24, $GLOBALS['leftmaxx'],25);

$pdf->addComponent($title);
$pdf->addComponent($company);
$pdf->addComponent($businesscode);
$pdf->addComponent($currentdate);
$pdf->addComponent($headerline);
$yPosition = 30;
$tulot = 0;
$menot = 0;


foreach($registry->accounthierarchy as $index => $account) {

	if ($account->accounttypeID == 3) {

		$accountline = new PDFText(16, $yPosition, $account->name, $topLevelFont);
		
		$timeline = new PDFText($GLOBALS['leftmaxx'], $yPosition, substr(sqlDateToStr($registry->startdate),0,6) . " - " . sqlDateToStr($registry->enddate), $topLevelFont);
		$timeline->setAlign("R");
		$pdf->addComponent($accountline);
		$pdf->addComponent($timeline);

		$tulot = $tulot + generateLevelOne($account, $registry->accounts, $registry->entries, $yPosition, $pdf);
		
		$yPosition = $yPosition + $GLOBALS['level1yadd'];
		$tulotstr = number_format($tulot,2,","," ");;
		$accountline = new PDFText(16, $yPosition, $account->name . " yhteensä", $topLevelFont);
		$timeline = new PDFText($GLOBALS['leftmaxx'], $yPosition, $tulotstr, $topLevelFont);
		$timeline->setAlign("R");
		$pdf->addComponent($accountline);
		$pdf->addComponent($timeline);
	}

	
}



foreach($registry->accounthierarchy as $index => $account) {
	if ($account->accounttypeID == 4) {
	
		$yPosition = $yPosition + 10;
		$accountline = new PDFText(16, $yPosition, $account->name, $topLevelFont);
	
		$timeline = new PDFText($GLOBALS['leftmaxx'], $yPosition, substr(sqlDateToStr($registry->startdate),0,6) . " - " . sqlDateToStr($registry->enddate), $topLevelFont);
		$timeline->setAlign("R");
		$pdf->addComponent($accountline);
		//$pdf->addComponent($timeline);
	
		$menot = $menot + generateLevelOne($account, $registry->accounts, $registry->entries, $yPosition, $pdf);
	
		$yPosition = $yPosition + $GLOBALS['level1yadd'];
		$tulotstr = number_format($tulot,2,","," ");;
		$accountline = new PDFText(16, $yPosition, $account->name . " yhteensä", $topLevelFont);
		$timeline = new PDFText($GLOBALS['leftmaxx'], $yPosition, $tulotstr, $topLevelFont);
		$timeline->setAlign("R");
		$pdf->addComponent($accountline);
		$pdf->addComponent($timeline);
	}
}


$taxlesstulos = $tulot - $menot;

$yPosition = $yPosition + $GLOBALS['level1yadd'] + $GLOBALS['level1yadd'];
$taxlesstulosstr = number_format($taxlesstulos,2,","," ");;
$accountline = new PDFText(16, $yPosition, "Tulos ennen veroja", $topLevelFont);
$timeline = new PDFText($GLOBALS['leftmaxx'], $yPosition, $taxlesstulos, $topLevelFont);
$timeline->setAlign("R");
$pdf->addComponent($accountline);
$pdf->addComponent($timeline);

$taxes = 0;

foreach($registry->accounts as $index => $childaccount) {

	if ($childaccount->accounttypeID == 6) {
		$selectedentries = array();
		$sum = 0;
		foreach($registry->entries as $index => $entry) {
			if ($entry->accountID == $childaccount->accountID) {
				$sum = $sum + $entry->amount;
			}
		}
		if ($sum > 0) {
			$yPosition = $yPosition + $GLOBALS['level3yadd'];
			$accountline = new PDFText($GLOBALS['level2indent'], $yPosition, $childaccount->name, $GLOBALS['level2normalfont']);
			$amountline = new PDFText($GLOBALS['leftmaxx'], $yPosition, number_format($sum,2,","," "), $GLOBALS['level2normalfont']);
			$amountline->setAlign("R");
			$pdf->addComponent($accountline);
			$pdf->addComponent($amountline);
			$taxes = $taxes + $sum;
		}
	}
	
	
}

$tulos = $taxlesstulos - $taxes;

$yPosition = $yPosition + $GLOBALS['level1yadd'] + $GLOBALS['level1yadd'];
$tulosstr = number_format($tulos,2,",","");;
$accountline = new PDFText(16, $yPosition, "Tulos", $topLevelFont);
$timeline = new PDFText($GLOBALS['leftmaxx'], $yPosition, $tulosstr, $topLevelFont);
$timeline->setAlign("R");
$pdf->addComponent($accountline);
$pdf->addComponent($timeline);




function generateTopHeader($pdf, $titlefont,$titletext,$basefont, $title,$company,$businesscode,$currentdate,$headerline) {

	$title = new PDFText(80,12, "Tuloslaskelma", $titlefont);
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
		
		$leveltwosum = generateLevelTwo($childaccount, $accounts, $entries, $yPosition, $pdf);

		if ($leveltwosum > 0) {
			$yPosition = $yPosition + $GLOBALS['level2yadd'];
			$sumline = new PDFText($GLOBALS['level1indent'], $yPosition, $childaccount->name . " yhteensä", $GLOBALS['level1font']);
			$leveltwosum2 = number_format($leveltwosum,2,","," ");
			$amountline = new PDFText($GLOBALS['leftmaxx'], $yPosition, $leveltwosum2);
			$amountline->setAlign("R");
			$pdf->addComponent($sumline);
			$pdf->addComponent($amountline);
		}
		
		$totalsum = $totalsum + $leveltwosum;
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


function generateLevelTwo($account, $accounts, $entries, &$yPosition, $pdf) {

	$childs = $account->getChilds();
	$totalsum = 0;
	if ($childs == nulL) return 0;


	
	foreach($childs as $index => $childaccount) {


		if ($yPosition > 220) {
			$newpage = new PDFNewPage();
			$pdf->addComponent($newpage);
			$yPosition = 30;
		}
		
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
				
		$levelthreesum = generateLevelThree($childaccount, $accounts, $entries, $yPosition, $pdf);
		
		if ($levelthreesum > 0) {
			$yPosition = $yPosition + $GLOBALS['level2yadd'];
			$sumline = new PDFText($GLOBALS['level2indent'], $yPosition, $childaccount->name . " yhteensä", $GLOBALS['level2font']);
			$levelthreesum2 = number_format($levelthreesum,2,","," ");
			$amountline = new PDFText($GLOBALS['leftmaxx'], $yPosition, $levelthreesum2, $GLOBALS['level2font']);
			$amountline->setAlign("R");
			$pdf->addComponent($sumline);
			$pdf->addComponent($amountline);
		}
		
		$totalsum = $totalsum + $levelthreesum;
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
				$account = $accounts[$entry->accountID];
				if ($account->accounttypeID == 6) {
					// Tuloveroja ei oteta mukaan
				} else {
					$sum = $sum + $entry->amount;
					$totalsum = $totalsum + $entry->amount;
				}
			}
		}
		$totalsum = $totalsum + calculateLevelFour($childaccount, $accounts, $entries);
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
			if ($sum > 0) {
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


				$account = $accounts[$entry->accountID];
				if ($account->accounttypeID == 6) {
					// Tuloveroja ei oteta mukaan
				} else {
					$sum = $sum + $entry->amount;
					$totalsum = $totalsum + $entry->amount;
				}
			}
		}
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
				if ($childaccount->accounttypeID == 6) {
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
		if ($sum != 0) {
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


$pdf->show();





?>
