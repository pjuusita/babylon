<?php

//echo "<br>cwd - " . getcwd();
//echo "<br>sitepath - " . SITE_PATH;
//echo "<br><br>";

include_once SITE_PATH . "classes/pdf/pdf.class.php";
//include_once SITE_PATH . "classes/pdf/pdfcomponent.class.php";

$titlefont = new PDFFont('Arial',14,"B");
$addressfont = new PDFFont('Arial',12,"");
$basefont = new PDFFont('Arial',10,"");
$subtitlefont = new PDFFont('Arial',10,"B");

$pdf = new PDF();
$pdf->setDebug(false);
$title = new PDFText(105,10, "PALKKALASKELMA", $titlefont);
$address1 = new PDFText(16,10, $registry->company->name, $basefont);
$address = $registry->companyaddress->streetaddress . ", " . $registry->companyaddress->postalcode . " " . $registry->companyaddress->city;
$address2 = new PDFText(16,15, $address, $basefont);

$pdf->addComponent($title);
$pdf->addComponent($address1);
$pdf->addComponent($address2);

// Vastaanottjan tiedot
$address = new PDFText(16,35, $registry->person->firstname . " " . $registry->person->lastname, $addressfont);
$pdf->addComponent($address);
$address = new PDFText(16,40, $registry->person->streetaddress, $addressfont);
$pdf->addComponent($address);
$address = new PDFText(16,45, $registry->person->postalcode . " " . $registry->person->city, $addressfont);
$pdf->addComponent($address);


$section = new PDFSection(105, 16, 90, 50);
$section->setBorderColor(PDFColor::BLACK);
$section->setBackgroundColor(PDFColor::WHITE);
$section->setBorderWidth(0.2);
$section->setBorderRadius(2);

$xPos = 3;
$yPos = 5;
$valueXPos = 25;
$yHeight = 6;

//$label = new PDFText($xPos,$yPos, "Työnantaja",$basefont);
//$section->addComponent($label);
//$label = new PDFText($valueXPos,$yPos, $registry->company->name . " (" . $registry->company->businesscode . ")", $basefont);
//$section->addComponent($label);
//$yPos = $yPos + $yHeight;

$label = new PDFText($xPos,$yPos, "Henkilö",$basefont);
$section->addComponent($label);
$label = new PDFText($valueXPos,$yPos, $registry->person->lastname . " " . $registry->person->firstname,$basefont);
$section->addComponent($label);

//$yPos = $yPos + $yHeight;
//$label = new PDFText($xPos,$yPos, "Syntymäaika",$basefont);
//$section->addComponent($label);

$yPos = $yPos + $yHeight;
$label = new PDFText($xPos,$yPos, "Pankkitili",$basefont);
$section->addComponent($label);
$label = new PDFText($valueXPos,$yPos, $registry->person->bankaccountnumber,$basefont);
$section->addComponent($label);

$yPos = $yPos + $yHeight;
$label = new PDFText($xPos,$yPos, "Palkkakausi",$basefont);
$section->addComponent($label);

$startdate = strtotime($registry->paycheck->startdate);
$startstr = date('d.m.Y', $startdate);
$enddate = strtotime($registry->paycheck->enddate);
$endstr = date('d.m.Y', $enddate);

$label = new PDFText($valueXPos,$yPos, $startstr . " - " . $endstr,$basefont);
$section->addComponent($label);

$yPos = $yPos + $yHeight;
$label = new PDFText($xPos,$yPos, "Maksupäivä",$basefont);
$section->addComponent($label);
$paymentdate = strtotime($registry->paycheck->paymentdate);
$paymentdatestr = date('d.m.Y', $paymentdate);
$label = new PDFText($valueXPos,$yPos, $paymentdatestr,$basefont);
$section->addComponent($label);

$yPos = $yPos + $yHeight;
$label = new PDFText($xPos,$yPos, "Verokortti",$basefont);
$section->addComponent($label);

if ($registry->taxcard != null) {
	$startdate = strtotime($registry->taxcard->startdate);
	$startstr = date('d.m.Y', $startdate);
	$enddate = strtotime($registry->taxcard->enddate);
	$endstr = date('d.m.Y', $enddate);
	
	$label = new PDFText($valueXPos,$yPos, $startstr . " - " . $endstr,$basefont);
	$section->addComponent($label);
	
	$yPos = $yPos + $yHeight;
	$taxlimit = $registry->taxcard->taxlimit;
	$label = new PDFText($valueXPos,$yPos, "0 - " . ($taxlimit),$basefont);
	$section->addComponent($label);
	$label = new PDFText($valueXPos+30,$yPos, number_format($registry->taxcard->percent1,2,","," ") . "%",$basefont);
	$section->addComponent($label);
	
	$yPos = $yPos + $yHeight;
	$label = new PDFText($valueXPos,$yPos, "" . $taxlimit . " -",$basefont);
	$section->addComponent($label);
	$label = new PDFText($valueXPos+30,$yPos, number_format($registry->taxcard->percent2,2,","," ") . "%",$basefont);
	$section->addComponent($label);
	
} else {
	$label = new PDFText($valueXPos,$yPos, "puuttuu",$basefont);
	$section->addComponent($label);
	
}

$pdf->addComponent($section);


// Palkkatiedot section -- itseasiassa tämän height pitää laskea

$section = new PDFSection(15, 70, 180, 100);
$section->setBorderColor(PDFColor::BLACK);
$section->setBackgroundColor(PDFColor::WHITE);
$section->setBorderWidth(0.2);
$section->setBorderRadius(2);

$pdf->addComponent($section);

$textleftpos = 3;

// Rahapalkka
$label = new PDFText($textleftpos,4, "Palkat ja lisät",$subtitlefont);
$section->addComponent($label);
$yPos = 4;
$yHeight = 5;
$amountpos = 110;
$unitpricepos = 140;
$totalpos = 170;
$totalsum = 0;

foreach($registry->paycheckrows as $index => $row) {
	if ($row->salarycategoryID == 1) {
		$yPos = $yPos + $yHeight;
		$salarytype = $registry->salarytypes[$row->salarytypeID];
		$label = new PDFText($textleftpos, $yPos, $salarytype->name, $basefont);
		$section->addComponent($label);
		
		if ($row->amount != null) {
			$label = new PDFText($amountpos,$yPos, number_format($row->amount,2,","," "),$basefont);
			$label->setAlign('R');
			$section->addComponent($label);
		}
		if ($row->amount != null) {
			$label = new PDFText($unitpricepos,$yPos, number_format($row->unitprice,2,","," "),$basefont);
			$label->setAlign('R');
			$section->addComponent($label);
		}
		if ($row->total != null) {
			$label = new PDFText($totalpos,$yPos, number_format($row->total,2,","," "),$basefont);
			$label->setAlign('R');
			$section->addComponent($label);
		}
		$totalsum = $totalsum + $row->total;
	}
}

$yPos = $yPos + $yHeight;
$label = new PDFText($textleftpos,$yPos, "Ennakonpidätyksen alainen ansio",$subtitlefont);
$section->addComponent($label);

$label = new PDFText($totalpos, $yPos, number_format($totalsum,2,","," "),$subtitlefont);
$label->setAlign('R');
$section->addComponent($label);



// Verottomat korvaukset
$yPos = $yPos + 10;
$label = new PDFText($textleftpos,$yPos, "Verovapaat korvaukset",$subtitlefont);
$section->addComponent($label);
$yHeight = 5;
$amountpos = 110;
$unitpricepos = 140;
$totalpos = 170;
//$totalsum = 0;

foreach($registry->paycheckrows as $index => $row) {
	if ($row->salarycategoryID == 2) {
		$yPos = $yPos + $yHeight;
		$salarytype = $registry->salarytypes[$row->salarytypeID];
		$label = new PDFText($textleftpos, $yPos, $salarytype->name, $basefont);
		$section->addComponent($label);

		if ($row->amount != null) {
			$label = new PDFText($amountpos,$yPos, number_format($row->amount,2,","," "),$basefont);
			$label->setAlign('R');
			$section->addComponent($label);
		}
		if ($row->amount != null) {
			$label = new PDFText($unitpricepos,$yPos, number_format($row->unitprice,2,","," "),$basefont);
			$label->setAlign('R');
			$section->addComponent($label);
		}
		if ($row->total != null) {
			$label = new PDFText($totalpos,$yPos, number_format($row->total,2,","," "),$basefont);
			$label->setAlign('R');
			$section->addComponent($label);
		}
		$totalsum = $totalsum + $row->total;
	}
}



// Vähennykset
$yPos = $yPos + 10;
$label = new PDFText($textleftpos,$yPos, "Vähennykset",$subtitlefont);
$section->addComponent($label);
$yHeight = 5;
$amountpos = 110;
$unitpricepos = 140;
$totalpos = 170;
//$totalsum = 0;

foreach($registry->paycheckrows as $index => $row) {
	if ($row->salarycategoryID == 4) {
		$yPos = $yPos + $yHeight;
		$salarytype = $registry->deductions[$row->deductionID];
		$label = new PDFText($textleftpos, $yPos, $salarytype->shortname, $basefont);
		$section->addComponent($label);

		if ($row->amount != null) {
			$label = new PDFText($amountpos,$yPos, number_format($row->amount,2,","," "),$basefont);
			$label->setAlign('R');
			$section->addComponent($label);
		}
		if ($row->amount != null) {
			$label = new PDFText($unitpricepos,$yPos, number_format($row->unitprice,2,","," "),$basefont);
			$label->setAlign('R');
			$section->addComponent($label);
		}
		if ($row->total != null) {
			$label = new PDFText($totalpos,$yPos, number_format($row->total,2,","," "),$basefont);
			$label->setAlign('R');
			$section->addComponent($label);
		}
		$totalsum = $totalsum - $row->total;
	}
}



$yPos = 92;
$label = new PDFText($textleftpos,$yPos, "Maksetaan",$titlefont);
$section->addComponent($label);

$label = new PDFText($totalpos, $yPos, number_format($totalsum,2,",",""),$titlefont);
$label->setAlign('R');
$section->addComponent($label);

// Maksetaaan



// Kertymätiedot vuoden alusta
$section = new PDFSection(15, 173, 180, 40);
$section->setBorderColor(PDFColor::BLACK);
$section->setBackgroundColor(PDFColor::WHITE);
$section->setBorderWidth(0.2);
$section->setBorderRadius(2);

$label = new PDFText(2,2, "Kertymä vuoden alusta",$subtitlefont);
$section->addComponent($label);

$pdf->addComponent($section);



// Lomatiedot
$section = new PDFSection(15, 216, 180, 20);
$section->setBorderColor(PDFColor::BLACK);
$section->setBackgroundColor(PDFColor::WHITE);
$section->setBorderWidth(0.2);
$section->setBorderRadius(2);

$label = new PDFText(2,2, "Lomatiedot",$subtitlefont);
$section->addComponent($label);

$pdf->addComponent($section);




// Työaikapankki
$section = new PDFSection(15, 239, 180, 20);
$section->setBorderColor(PDFColor::BLACK);
$section->setBackgroundColor(PDFColor::WHITE);
$section->setBorderWidth(0.2);
$section->setBorderRadius(2);


$label = new PDFText(2,2, "Työaikapankki",$subtitlefont);
$section->addComponent($label);

$pdf->addComponent($section);



$pdf->show();



/*

$person = $this->registry->employee;
$address = $this->registry->employeeaddress;

$titleText = new PDFText("PALKKALASKELMA",100,10,100,15,"Arial","b",14,"#000000","#FFFFFF",0,"#FFFFFF");
$personname = new PDFText($person->name,20,30,50,15,"Arial","b",10,"#000000","#FFFFFF",0,"#FFFFFF");
$personaddress = new PDFText($address->streetaddress,20,35,50,15,"Arial","b",10,"#000000","#FFFFFF",0,"#FFFFFF");
$personpostal = new PDFText($address->countrycode . "-" . $address->postalcode . " " . $address->city,20,40,50,15,"Arial","b",10,"#000000","#FFFFFF",0,"#FFFFFF");


$header			= new PDFSection("#FFFFFF","#FFFFFF",0,1,1,200,15);
$header->addComponent($titleText);
$header->addComponent($personname);
$header->addComponent($personaddress);
$header->addComponent($personpostal);

// Onko koordinaatit section kohtaisia?
$infosection = new PDFSection("#FFFFFF","#FFFFFF",0,1,1,200,15);
$bankaccount = new PDFText("FI26 4405 0010 0781 14",0,0,50,15,"Arial","b",10,"#000000","#FFFFFF",0,"#FFFFFF");
$paymentdate = new PDFText("03.05.2019",0,10,50,15,"Arial","b",10,"#000000","#FFFFFF",0,"#FFFFFF");
$paymentpediod = new PDFText("01.04.2019 - 30.04.2019",0,20,50,15,"Arial","b",10,"#000000","#FFFFFF",0,"#FFFFFF");
$infosection->addComponent($bankaccount);
$infosection->addComponent($paymentdate);
$infosection->addComponent($paymentpediod);





$footer			= new PDFSection("#FFFFFF","#FFFFFF",0,1,280,200,15);
$pageNumber		= new PDFPageNumber(100,5,"Arial","",12,"#000000");
$footer->addComponent($pageNumber);

$basePDF->setPageHeader($header);
$basePDF->addComponent($infosection);
$basePDF->setPageFooter($footer);
*/

/*
$pdfTable		= new PDFTable(5,15,200,$salaryperiods,$basePDF);

$yearColumn		= new PDFTableColumn("Vuosi","year",15);
$periodColumn	= new PDFTableColumn("Kausi","periodnumber",15);
$startColumn	= new PDFTableDateColumn("Alkaa","startdate",25,"Y-m-d");
$endColumn		= new PDFTableDateColumn("Loppuu","enddate",25,"Y-m-d");
$payColumn		= new PDFTableDateColumn("Maksu","payday",25,"Y-m-d");
$nameColumn		= new PDFTableColumn("Sopimuskausi","periodname",90);

$pdfTable->addColumn($yearColumn);
$pdfTable->addColumn($periodColumn);
$pdfTable->addColumn($startColumn);
$pdfTable->addColumn($endColumn);
$pdfTable->addColumn($payColumn);
$pdfTable->addColumn($nameColumn);

$basePDF->addComponent($pdfTable);
*/


//$basePDF->show();



?>
