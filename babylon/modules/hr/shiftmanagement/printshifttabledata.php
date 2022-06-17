<?php

$DOC = new BasePDF();

$tableColumns = "employeeID:projectID:taskID";
$tableData	  = $this->registry->allshifts;

$employees	  = $this->registry->employees;
$shifts	  	  = $this->registry->allshifts;

/*foreach($employees as $index => $employee) {
	
	$employeeID = $employee->employeeID;
	$shiftData  = array();

	
	foreach($shifts as $shiftIndex => $shift) {	
		
		if ($shift->employeeID==$employeeID) {
		
			$shiftData[] = $shift;
		}
	}
	
	$shiftTable 	= new PDFTable(10,10,175,$shiftData,$DOC);
	$employeeColumn = new PDFTableColumn("Tyantekija","employeename",40);
	$taskColumn 	= new PDFTableColumn("Tyatehtava","taskname",40);
	$projectColumn 	= new PDFTableColumn("Kohde","projectname",40);
	$startColumn 	= new PDFTableColumn("Alkaa","starttime",20);
	$endColumn	 	= new PDFTableColumn("Loppuu","endtime",20);
	
	$shiftTable->addColumn($employeeColumn);
	$shiftTable->addColumn($taskColumn);
	$shiftTable->addColumn($projectColumn);
	$shiftTable->addColumn($startColumn);
	$shiftTable->addColumn($endColumn);
	
	$shiftTable->setComponentStyle("Arial","",18,_black,_white,_lightgray,0.25,0.5,1,"L");
	$shiftTable->setHeaderStyle("Arial","B",14,_black,_white,_lightgray,0.25,0.5,1,"L");
	$shiftTable->setContentStyle("Arial","",12,_black,_white,_lightgray,0.25,0.5,1,"L");
	
	$DOC->addComponent($shiftTable);
	
}*/

$startDateComponents		= explode("-",$this->registry->startDate);
$endDateComponents			= explode("-",$this->registry->endDate);
$subject					= $this->registry->subject;

$startDateStr				= $startDateComponents[2].".".$startDateComponents[1].".";
$endDateStr					= $endDateComponents[2].".".$endDateComponents[1].".".$endDateComponents[0];

define('EURO','€');

$headerText					= "Tyavuoroluettelo ".$startDateStr."-".$endDateStr;

$header						= new PDFSection('#FFFFFF','#FFFFFF',0,0,0,100,25);
$babelImage					= new PDFImage("babelsoft.gif",5,10,80,15);

$timeStamp					= new PDFText($headerText,100,18,120,10,"Arial","",15,"#000000","#FFFFFF",0,"#FFFFFF");

$header->addComponent($babelImage);
$header->addComponent($timeStamp);

$DOC->setPageHeader($header);

$footer			= new PDFSection('#FFFFFF','#FFFFFF',0,0,280,100,25);
$pageNumber		= new PDFPageNumber(100,10,"Arial","",11,'#000000');

$footer->addComponent($pageNumber);

$DOC->setPageFooter($footer);

$subjectText		= new PDFText($subject,10,40,120,10,"Arial","UB",20,"#000000","#FFFFFF",0,"#FFFFFF");

$DOC->addComponent($subjectText);

$shiftTable 		= new PDFShiftTable(10,40,175,$shifts,$DOC,"date");


$employeeColumn 	= new PDFTableColumn("Tyantekija","employeename",40);
$taskColumn 		= new PDFTableColumn("Tyatehtava","taskname",40);
$projectColumn 		= new PDFTableColumn("Kohde","projectname",40);
$dateColumn			= new PDFTableDateColumn("Pvm","date",25,'d.m.D');
$shiftSpanColumn	= new PDFTableColumn("Aika","shiftspan",30);
//$shiftSpanColumn	= new PDFTableSumColumn("Aika","shiftspan",30,'timedifference');
//$startColumn 	= new PDFTableColumn("Alkaa","starttime",20);
//$endColumn	 	= new PDFTableColumn("Loppuu","endtime",20);

$shiftTable->addColumn($dateColumn);
$shiftTable->addColumn($shiftSpanColumn);
//$shiftTable->addColumn($startColumn);
//$shiftTable->addColumn($endColumn);
$shiftTable->addColumn($employeeColumn);
$shiftTable->addColumn($taskColumn);
//$shiftTable->addColumn($projectColumn);

$shiftTable->setComponentStyle("Arial","",18,_black,_white,_lightgray,0.25,0.5,1,"L");
$shiftTable->setHeaderStyle("Arial","B",14,_black,_white,_lightgray,0.25,0.5,1,"L");
$shiftTable->setContentStyle("Arial","",12,_black,_white,_lightgray,0.25,0.5,1,"L");

$DOC->addComponent($shiftTable);

$DOC->show();

?>