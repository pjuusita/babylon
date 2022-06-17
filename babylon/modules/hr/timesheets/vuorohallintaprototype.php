<?php

echo "<br><b>Prototyyppi viikkonakymasta </b><br><br><br>";

function createProto() {

	echo "<div id='contextmenu' style='display:none;position:absolute;background-color:lightgray'>";
	echo "Taissa<br>Vapaalla<br>Poissa<br>Ei tiedossa";
	echo "</div>";
	
	echo "<table id='Viikkonakyma' style='width:800px;user-select : none;'>";

		CreateHeader();
		CreateRows();
		
	echo "</table>";

}

function createHeader() {

	echo "<tr>";
	echo "<th style='width:20%;text-align:center;'></th>";
	echo "<th style='width:20%;text-align:center;' colspan=7>Viikko 1</th>";
	echo "<th style='width:20%;text-align:center;' colspan=7>Viikko 2</th>";
	echo "<th style='width:20%;text-align:center;' colspan=7>Viikko 3</th>";
	echo "<th style='width:20%;text-align:center;' colspan=7>Viikko 4</th>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<th></th>";
	
		for($week=0;$week<4;$week++) {
					
			for($day=0;$day<7;$day++) {
				$dayName = getDayName($day);
				echo "<th>".$dayName."</th>";	
			}
		}
	
	echo "</tr>";
}

function createRows() {
	
	createRow();
	createRow();
	createRow();
	createRow();
	createRow();
	createRow();
	createRow();
	createRow();
	createRow();
	createRow();
	createRow();
	createRow();
	
}

function createRow() {
	
	echo "<tr style='user-select : none;'>";
	
	$employeeName = getEmployeeName();
	
	echo "<td >";
	echo "<div draggable=true ondragstart='onDragStart(event)'>".$employeeName."</div>";
	echo "</td>";
	
	for($week=0;$week<4;$week++) {
		
		for($day=0;$day<7;$day++) {
			createDay();
		}
				
	}
	
	echo "</tr>";
}

function createDay() {
	
	$color = getColor();
	
	echo "<td ondragenter='preventDefaults(event)' ondragover='preventDefaults(event)' ondrop='onDropElement(event)' onmousedown='onMouseDown(event)' onmouseup='onMouseUp(event)' onmouseover='onMouseOverColor(event)' oncontextmenu='openContextMenu(event)' style='user-select : none;background-color : ".$color."'>&nbsp&nbsp&nbsp</td>";
	
}

function getColor() {
	
	$colors = array();
	
	$colors[0] = "gray";
	$colors[1] = "gray";
	$colors[2] = "gray";
	$colors[3] = "gray";
	$colors[4] = "gray";
	$colors[5] = "gray";
	
	$colorNumber = rand(0,5);
	
	return $colors[$colorNumber];
	
}

function getEmployeeName() {
	
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
	
	$employeesNumber = rand(0,15);
	
	return $employees[$employeesNumber];
	
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


echo "<script>																						";
echo "																								";
echo "	var from = null;																			";
echo "	var to	 = null;																			";
echo "																								";
echo "																								";
echo "																								";
echo "</script>																						";



echo "<script>																						";
echo "																								";
echo "	function onMouseDown(event)	{																";
echo "																								";
echo "		from = event.currentTarget;																";
echo "																								";
echo "	}																							";
echo "																								";
echo "</script>																						";

echo "<script>																						";
echo "																								";
echo "	function onMouseUp(event)	{																";
echo "																								";
echo "		to	 		 		  = event.currentTarget;											";
echo "																								";
echo "		var toRowIndex  	  = to.parentNode.rowIndex;											";
echo "		var cellIndex 		  =	to.cellIndex;													";
echo "		var fromRowIndex	  = from.parentNode.rowIndex;										";
echo "																								";
echo "		if (toRowIndex==fromRowIndex) colorCell(toRowIndex,cellIndex);							";
echo "																								";
echo "		from 		  = null;																	";
echo "		to	 		  = null;																	";
echo "																								";
echo "	}																							";
echo "																								";
echo "</script>																						";

echo "<script>																						";
echo "																								";
echo "	function onMouseOverColor(event) {															";
echo "																								";
echo "		var current 	  = event.currentTarget;												";
echo "																								";
echo "		if (from!=null) {																		";
echo "																								";
echo "			var fromIndex = from.cellIndex;														";
echo "			var toIndex	  = current.cellIndex;													";
echo "			var rowIndex  = from.parentNode.rowIndex;											";
echo "																								";
echo "			if (fromIndex==toIndex)	{															";
echo "				colorCell(rowIndex,fromIndex);													";
echo "				return;																			";
echo "			}																					";
echo "																								";
echo "			var dir;																			";
echo "																								";
echo "			if (fromIndex<toIndex)  dir = 1;													";
echo "			if (fromIndex==toIndex) dir = 0;													";
echo "			if (fromIndex>toIndex)  dir = -1;													";
echo "																								";
echo "																								";
echo "			for(var cellIndex = fromIndex;cellIndex!=toIndex+dir;cellIndex = cellIndex + dir) {	";
echo "																								";
echo "				colorCell(rowIndex,cellIndex);													";
echo "			}																					";
echo "		}																						";
echo "	}																							";
echo "																								";
echo "</script>																						";

echo "<script>																						";
echo "																								";
echo "	function colorCell(rowIndex,index) {														";
echo "																								";
echo "		var table	= document.getElementById('Viikkonakyma');									";
echo "		var cell	= table.rows[rowIndex].cells[index];										";
echo "																								";
echo "		cell.style.backgroundColor = 'green';  													";
echo "																								";
echo "	}																							";
echo "</script>																						";

echo "<script>																						";
echo "																								";
echo "	function openContextMenu(event) {															";
echo "																								";
echo "		event.preventDefault();																	";
echo "																								";
echo "		var contextMenu   = document.getElementById('contextmenu');								";
echo "																								";
echo "		var cell		  = event.currentTarget;												";
echo "		var coords		  = cell.getBoundingClientRect();										";
echo "																								";
echo "		$('#contextmenu').offset({top:coords.top,left:coords.left});							";
echo "		$('#contextmenu').show();																";
echo "																								";
echo "																								";
echo "	}																							";
echo "																								";
echo "</script>																						";

echo "<script>																						";
echo "																								";
echo "	function onDragStart(event) {																";
echo "																								";
echo "																								";
echo "																								";
echo "																								";
echo "																								";
echo "		event.dataTransfer.setData('text',event.target.innerHTML);								";
echo "																								";
echo "																								";
echo "																								";
echo "																								";
echo "																								";
echo "																								";
echo "	}																							";
echo "																								";
echo "</script>																						";

echo "<script>																						";
echo "																								";
echo "	function onDropElement(event) {																";
echo "																								";
echo "		var targetCell = event.target; 															";
echo "		var nameLabel  = document.createElement('div');											";
echo "		nameLabel.style.position = 'absolute';													";
echo "																								";
echo "		nameLabel.innerHTML = event.dataTransfer.getData('text');								";
echo "		document.body.appendChild(nameLabel);													";
echo "																								";
echo "		var coords		  = targetCell.getBoundingClientRect();									";
echo "		$(nameLabel).offset({top:coords.top,left:coords.left});									";
echo "																								";
echo "																								";
//echo "			console.log('Dropped on ' + targetCell);											";
echo "	}																							";
echo "																								";
echo "</script>																						";

echo "<script>																						";
echo "																								";
echo "	function preventDefaults(event) {															";
echo "																								";
echo "		event.preventDefault();																	";
echo "																								";
echo "	}																							";
echo "																								";
echo "</script>																						";


CreateProto();



?>