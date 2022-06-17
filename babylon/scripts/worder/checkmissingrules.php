<?php

	// Tsekataan onko poistettujen rulejen referenttejä vielä olemassa (poisto manuaalisesti)
	
	// Taulut:
	// - worder_rules
	// - worder_ruleterms
	// - worder_rulefeatureconstraints
	// - worder_rulefeatureagreements
	// - worder_rulesentencelinks
	// - worder_rulecomponentrequirements
	// - worder_ruleresultfeatures
	// - wordino_playerrules
	// - worder_rulesetlinks

global $mysqli;
$sql = "SELECT * FROM worder_rules";

$result = $mysqli->query($sql);
$rules = array();
while($row = $result->fetch_array()) {
	$ruleID = $row['RuleID'];
	$rules[$ruleID] = $ruleID;	
}
echo "<br>Rulecount - " . count($rules);


echo "<br><br>worder_ruleterms";
$sql = "SELECT * FROM worder_ruleterms";

$result = $mysqli->query($sql);
$missingcount = 0;
$ruletermstoremove = array();
while($row = $result->fetch_array()) {
	$ruleID = $row['RuleID'];
	if (!isset($rules[$ruleID])) {
		echo "<br> -- missing rule - " . $ruleID;
		$missingcount++;
		$rowID = $row['RowID'];
		$ruletermstoremove[$rowID] = $rowID;
	}
}
echo "<br>Rulecount - " . $missingcount;

foreach($ruletermstoremove as $index => $rowID) {
	$success = Table::deleteRow('worder_ruleterms',"WHERE RowID=" . $rowID);
	echo "<br>Deleting row - " . $rowID;
}



echo "<br><br>worder_rulefeatureconstraints";
$sql = "SELECT * FROM worder_rulefeatureconstraints";

$result = $mysqli->query($sql);
$missingcount = 0;
$itemstoremove = array();
while($row = $result->fetch_array()) {
	$ruleID = $row['RuleID'];
	if (!isset($rules[$ruleID])) {
		echo "<br> -- missing rule - " . $ruleID;
		$missingcount++;
		$rowID = $row['RowID'];
		$itemstoremove[$rowID] = $rowID;
	}
}
echo "<br>Rulecount - " . $missingcount;

foreach($itemstoremove as $index => $rowID) {
	//$success = Table::deleteRow('worder_rulefeatureconstraints',"WHERE RowID=" . $rowID);
	echo "<br>Deleting row - " . $rowID;
}





echo "<br><br>worder_rulefeatureagreements";
$sql = "SELECT * FROM worder_rulefeatureagreements";

$result = $mysqli->query($sql);
$missingcount = 0;
$itemstoremove = array();
while($row = $result->fetch_array()) {
	$ruleID = $row['RuleID'];
	if (!isset($rules[$ruleID])) {
		echo "<br> -- missing rule - " . $ruleID;
		$missingcount++;
		$rowID = $row['RowID'];
		$itemstoremove[$rowID] = $rowID;
	}
}
echo "<br>Rulecount - " . $missingcount;

foreach($itemstoremove as $index => $rowID) {
	//$success = Table::deleteRow('worder_rulefeatureagreements',"WHERE RowID=" . $rowID);
	echo "<br>Deleting row - " . $rowID;
}




echo "<br><br>worder_rulesetlinks";
$sql = "SELECT * FROM worder_rulesetlinks";

$result = $mysqli->query($sql);
$missingcount = 0;
$itemstoremove = array();
while($row = $result->fetch_array()) {
	$ruleID = $row['RuleID'];
	if (!isset($rules[$ruleID])) {
		echo "<br> -- missing rule - " . $ruleID;
		$missingcount++;
		$rowID = $row['RowID'];
		$itemstoremove[$rowID] = $rowID;
	}
}
echo "<br>Rulecount - " . $missingcount;

foreach($itemstoremove as $index => $rowID) {
	//$success = Table::deleteRow('worder_rulesetlinks',"WHERE RowID=" . $rowID);
	echo "<br>Deleting row - " . $rowID;
}




echo "<br><br>worder_lessonrules";
$sql = "SELECT * FROM worder_lessonrules";

$result = $mysqli->query($sql);
$missingcount = 0;
$itemstoremove = array();
while($row = $result->fetch_array()) {
	$ruleID = $row['RuleID'];
	if (!isset($rules[$ruleID])) {
		echo "<br> -- missing rule - " . $ruleID;
		$missingcount++;
		$rowID = $row['RowID'];
		$itemstoremove[$rowID] = $rowID;
	}
}
echo "<br>Rulecount - " . $missingcount;

foreach($itemstoremove as $index => $rowID) {
	//$success = Table::deleteRow('worder_lessonrules',"WHERE RowID=" . $rowID);
	echo "<br>Deleting row - " . $rowID;
}



echo "<br><br>worder_rulesentencelinks";
$sql = "SELECT * FROM worder_rulesentencelinks";

$result = $mysqli->query($sql);
$missingcount = 0;
$itemstoremove = array();
while($row = $result->fetch_array()) {
	$ruleID = $row['RuleID'];
	if (!isset($rules[$ruleID])) {
		echo "<br> -- missing rule - " . $ruleID;
		$missingcount++;
		$rowID = $row['RowID'];
		$itemstoremove[$rowID] = $rowID;
	}
}
echo "<br>Rulecount - " . $missingcount;

foreach($itemstoremove as $index => $rowID) {
	//$success = Table::deleteRow('worder_rulesentencelinks',"WHERE RowID=" . $rowID);
	echo "<br>Deleting row - " . $rowID;
}




echo "<br><br>worder_rulecomponentrequirements";
$sql = "SELECT * FROM worder_rulecomponentrequirements";

$result = $mysqli->query($sql);
$missingcount = 0;
$itemstoremove = array();
while($row = $result->fetch_array()) {
	$ruleID = $row['RuleID'];
	if (!isset($rules[$ruleID])) {
		echo "<br> -- missing rule - " . $ruleID;
		$missingcount++;
		$rowID = $row['RowID'];
		$itemstoremove[$rowID] = $rowID;
	}
}
echo "<br>Rulecount - " . $missingcount;

foreach($itemstoremove as $index => $rowID) {
	//$success = Table::deleteRow('worder_rulecomponentrequirements',"WHERE RowID=" . $rowID);
	echo "<br>Deleting row - " . $rowID;
}




echo "<br><br>worder_ruleresultfeatures";
$sql = "SELECT * FROM worder_ruleresultfeatures";

$result = $mysqli->query($sql);
$missingcount = 0;
$itemstoremove = array();
while($row = $result->fetch_array()) {
	$ruleID = $row['RuleID'];
	if (!isset($rules[$ruleID])) {
		echo "<br> -- missing rule - " . $ruleID;
		$missingcount++;
		$rowID = $row['RowID'];
		$itemstoremove[$rowID] = $rowID;
	}
}
echo "<br>Rulecount - " . $missingcount;

foreach($itemstoremove as $index => $rowID) {
	//$success = Table::deleteRow('worder_ruleresultfeatures',"WHERE RowID=" . $rowID);
	echo "<br>Deleting row - " . $rowID;
}




echo "<br><br>worder_ruleunsets";
$sql = "SELECT * FROM worder_ruleunsets";

$result = $mysqli->query($sql);
$missingcount = 0;
$itemstoremove = array();
while($row = $result->fetch_array()) {
	$ruleID = $row['RuleID'];
	if (!isset($rules[$ruleID])) {
		echo "<br> -- missing rule - " . $ruleID;
		$missingcount++;
		$rowID = $row['RowID'];
		$itemstoremove[$rowID] = $rowID;
	}
}
echo "<br>Rulecount - " . $missingcount;

foreach($itemstoremove as $index => $rowID) {
	//$success = Table::deleteRow('worder_ruleunsets',"WHERE RowID=" . $rowID);
	echo "<br>Deleting row - " . $rowID;
}






?>