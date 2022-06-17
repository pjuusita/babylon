<?php







function createPrototype() {
	
	createEmployeeBlock();
	createHourTable();
}

function createEmployeeBlock() {
	
	echo "<table>";
	echo "<tr>";
	
	for($employee=0;$employee<7;$employee++) {
		
		$employeeName = getEmployeeName($employee);
		$color		  = getColor($employee);
		
		echo "<td>";
		echo "<div draggable=true ondragstart='employeeBlockOnStartDrag(event)' style='background-color : ".$color."'>".$employeeName."</div>";
		echo "</td>";
		
	}
	
	echo "</tr>";
	echo "</table>";
	
	
}

function createHourTable() {
	
	echo "<table style='width:100%; border-collapse : collapse;'>";

		createHourTableHeader();
		createHourTableRows();
		
	echo "</table>";
	
}

function createHourTableHeader() {
	
	echo "<tr>";	

	echo "<th></th>";
	
	for($hour=0;$hour<24;$hour++) {
		
		$hourText = $hour.":00";
		if (strlen($hourText)==4) $hourText = "0".$hourText;
		
		echo "<th style='text-align : center'>";
		echo "".$hourText;
		echo "</th>";
		
	}
	
	echo "</tr>";
	
}

function createHourTableRows() {
	
	for($day=0;$day<7;$day++) {
		createHourTableRow($day);
	}
}

function createHourTableRow($day) {
	
	$imagePath = getImageUrl('smallhourruler.png');
	
	echo "<tr ondragenter='hourTableOnDragEnter(event)' ondragover='hourTableOnDragOver(event)' ondragleave='hourTableOnDragLeave(event)' ondrop='hourTableOnDrop(event)'>";
	
	echo "<td style='height : 25px; border-style : none none none none; padding : 0px 0px 0px 0px;'>";
	echo "".getDayName($day);
	echo "</td>";
	
	echo "<td colspan=24 style='width : 320px; height : 25px; border-style : none none none none;  padding : 0px 0px 0px 0px;'>";
	echo "<img id='rulerimage' src='".$imagePath."' style='width : 100%'></img>";
	echo "</td>";
	
	echo "</tr>";
	
	echo "<tr style='display : none; background-color : black' ondragenter='hourTableOnDragEnter(event)' ondragover='preventDefaults(event)' ondragleave='hourTableOnDragLeave(event)' ondrop='hourTableOnDrop(event)'>";
	
	echo "<td style='height : 25px; border-style : none none none none;  padding : 0px 0px 0px 0px;'></td>";

	echo "<td colspan=24 style='width : 320px; height : 25px; border-style : none none none none;  padding : 0px 0px 0px 0px;'>";
	echo "<img src='".$imagePath."' style='width : 100%'></img>";
	echo "</td>";
	
	echo "</tr>";
	
}























function getColor($colorNumber) {

	$colors = array();

	$colors[0] = "pink";
	$colors[1] = "yellow";
	$colors[2] = "lightblue";
	$colors[3] = "red";
	$colors[4] = "green";
	$colors[5] = "darkgray";
	$colors[6] = "lightgreen";

	return $colors[$colorNumber];

}

function getEmployeeName($employeeNumber) {

	$employees = array();

	$employees[0] = "Juha Sipila";
	$employees[1] = "Timo Soini";
	$employees[2] = "Paavo Arhinmaki";
	$employees[3] = "Jutta Urpilainen";
	$employees[4] = "Jyrki Katainen";
	$employees[5] = "Alexander Stubb";
	$employees[6] = "Paavo Vayrynen";
	$employees[7] = "Anna Kontula";
	$employees[8] = "Li Anderson";
	$employees[9] = "Ben Zyskowicz ";
	$employees[10] = "Sauli Niinista";
	$employees[11] = "Erkki Tuomioja";
	$employees[12] = "Teuvo Hakkarainen";
	$employees[13] = "Anne Berner";
	$employees[14] = "Esko Aho";
	$employees[15] = "Simon Elo";

	return $employees[$employeeNumber];

}

function getDayName($dayNumber) {

	$dayNames = array();

	$dayNames[0] = "Ma";
	$dayNames[1] = "Ti";
	$dayNames[2] = "Ke";
	$dayNames[3] = "To";
	$dayNames[4] = "Pe";
	$dayNames[5] = "La";
	$dayNames[6] = "Su";

	return $dayNames[$dayNumber];

}


createPrototype();

?>