<?php

function createProtoType() {
	
	echo "<table id='employees' style='border-collapse : collapse;'>";
		createEmployees();
	echo "</table>";
	
	echo "<br><br>";
	
	echo "<table id='shiftmanagementsheet' style='border-collapse : collapse;'>";
	
	createHeader();
	createRows();
	
	echo "</table>";
	
}


function createHeader() {
	
	echo "<tr>";
	
	//echo "<th></th>";
	echo "<th colspan=6 style='text-align:center;'>06:00</th>";
	
	for($hour=7;$hour<30;$hour++) {
		
		$textHour = $hour;
		if ($hour>23) $textHour = $hour - 24;
		
		if ($hour<10) $textHour = "0".$textHour;
		
		$textHour.= ":00";
		
		echo "<th colspan=10 style='text-align:center;'>".$textHour."</th>";
		
	}
	
	echo "<th colspan=6 style='text-align:center;'>06:00</th>";
	//echo "<th></th>";
	
	echo "<tr>";
}

function createRows() {
	
	for($day=0;$day<7;$day++) {
		
		echo "<tr>";
		
		$dayName = getDayName($day);
		
		echo "<td>".$dayName."</td>";
			
		for($hours=0;$hours<240;$hours++) {
			
			$modulo = $hours%10;
				
			if ($modulo!=0) echo "<td ondragenter='preventDefaults(event)' ondragover='preventDefaults(event)' ondrop='onDropElement(event)' style='background-color : lightgray; border-style : none none none none; padding : 0px 0px 3px 0px;'>&nbsp&nbsp</td>";
			if ($modulo==0) echo "<td ondragenter='preventDefaults(event)' ondragover='preventDefaults(event)' ondrop='onDropElement(event)' style='background-color : lightgray; border-style : none none none solid; border-color : white; padding : 0px 0px 3px 0px;'>&nbsp&nbsp</td>";
		
		}	
		
		echo "<td>";
		echo "<button onclick='newRow(event)'>+</button>";
		echo "</td>";
		
		echo "</tr>";
	}	
	
}

function createEmployees() {
	
	for($count = 0;$count<6;$count++) {

		$employee = getEmployeeName($count);
		$color	  = getColor($count);
		echo "<tr><td><div draggable=true ondragstart='onDragStart(event,\"innerHTML\")' style='background-color : ".$color."'>".$employee."</div></td></tr>";
		
	}
}


function getColor($colorNumber) {

	$colors = array();

	$colors[0] = "pink";
	$colors[1] = "yellow";
	$colors[2] = "lightblue";
	$colors[3] = "red";
	$colors[4] = "green";
	$colors[5] = "white";
	$colors[5] = "lightgreen";
	
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

//************************************************************************************************************************************************

echo "<script>																																	";
echo "																																			";
echo "	function newRow(event) {																												";
echo "																																			";
echo "																																			";
echo "		var source 		= event.target;																										";
echo "		var table		= source.parentNode.parentNode.parentNode;																			";
echo "		var rowIndex	= source.parentNode.parentNode.rowIndex;																			";
echo "		var clones		= source.parentNode.parentNode.cloneNode(true);																		";
echo "																																			";
echo "		table.insertBefore(clones,table.childNodes[rowIndex+1]);																			";
echo "		table.childNodes[rowIndex+1].childNodes[0].innerHTML = '';																			";
echo "																																			";
echo "	}																																		";
echo "																																			";
echo "</script>																																	";

//************************************************************************************************************************************************

echo "<script>																																	";
echo "																																			";
echo "	function onDragStart(event) {																											";
echo "																																			";
echo "		dragged 				= event.target;																								";
echo "		draggedBackgroundColor  = event.target.style.backgroundColor;																		";
echo "		dragX					= event.clientX;																							";
echo "		dragY					= event.clientY;																							";
echo "																																			";
echo "		event.dataTransfer.setData('text',event.target.innerHTML);																			";
echo "																																			";
echo "	}																																		";
echo "																																			";
echo "</script>																																	";

//************************************************************************************************************************************************

echo "<script>																																	";
echo "																																			";
echo "	function onDropElement(event) {																											";
echo "																																			";
echo "																																			";
echo "		if ((dragged.id=='dragCellLeft') || (dragged.id=='dragCellRight') || (dragged.id=='dragCellMiddle')) {								";
echo "																																			";
echo "			moveBoundaries(event,dragged);																									";
echo "			return;																															";
echo "																																			";
echo "		}																																	";
echo "																																			";
echo "		var targetCell  	 			= event.target; 																					";
echo "		var data			 			= event.dataTransfer.getData('text');																";
echo "																																			";
echo "		var shift 						= document.createElement('div');																	";
echo "		shift.style.position 			= 'absolute';																						";
echo "		shift.style.backgroundColor 	= draggedBackgroundColor;																			";
echo "																																			";
echo "		var dragTable   	 			= document.createElement('table');																	";
echo "		dragTable.style.width 			= '100%';																							";
echo "		var dragTableRow 	 			= dragTable.insertRow();																			";
echo "																																			";
echo "		var dragLeftCell 	 			= dragTableRow.insertCell();																		";
echo "		var dragMiddleCell	 			= dragTableRow.insertCell();																		";
echo "		var dragRightCell 	 			= dragTableRow.insertCell();																		";
echo "																																			";
echo "		dragLeftCell.innerHTML   	 	= 'O';																								";
echo "		dragLeftCell.style.textAlign 	= 'left';																							";
echo "		dragLeftCell.id			 		= 'dragCellLeft';																					";
echo "		dragLeftCell.draggable   		= true;																								";
echo "																																			";
echo "		dragLeftCell.addEventListener('dragstart',function(ev) {																			";
echo "			dragged = dragLeftCell;																											";
echo "			dragX = ev.clientX;																												";
echo "			dragY = ev.clientY;																												";
echo "		});																																	";
echo "																																			";
echo "		dragMiddleCell.innerHTML 		= data;																								";
echo "		dragMiddleCell.style.textAlign  = 'center';																							";
echo "		dragMiddleCell.draggable   		= true;																								";
echo "		dragMiddleCell.id			 	= 'dragCellMiddle';																					";
echo "		dragMiddleCell.addEventListener('dragstart',function(ev) {																			";
echo "			dragged = dragMiddleCell;																										";
echo "			dragX = ev.clientX;																												";
echo "			dragY = ev.clientY;																												";
echo "		});																																	";
echo "																																			";
echo "		dragRightCell.innerHTML  		= 'O';																								";
echo "		dragRightCell.id		 		= 'dragCellRight';																					";
echo "		dragRightCell.style.textAlign 	= 'right';																							";
echo "		dragRightCell.draggable 		= true;																								";
echo "																																			";
echo "		dragRightCell.addEventListener('dragstart',function(ev) { 																			";
echo "			dragged = dragRightCell;																										";
echo "			dragX = ev.clientX;																												";
echo "			dragY = ev.clientY;																												";
echo "		});																																	";
echo "																																			";
echo "		shift.appendChild(dragTable);																										";
echo "																																			";
echo "		document.body.appendChild(shift);																									";
echo "																																			";
echo "		var coords		  				= targetCell.getBoundingClientRect();																";
echo "																																			";
echo "		$(shift).offset({top:coords.top,left:coords.left});																					";
echo "																																			";
echo "	}																																		";
echo "																																			";
echo "</script>																																	";

//************************************************************************************************************************************************

echo "<script>																																	";
echo "																																			";
echo "	function moveBoundaries(event,shift) {																									";
echo "																																			";
echo "		if (dragged.id=='dragCellLeft') dropLeftCell(event,shift);																			";
echo "		if (dragged.id=='dragCellRight') dropRightCell(event,shift);																		";
echo "		if (dragged.id=='dragCellMiddle') dropMiddleCell(event,shift);																		";
echo "																																			";
echo "	}																																		";
echo "																																			";
echo "</script>																																	";

//************************************************************************************************************************************************

echo "<script>																																	";
echo "																																			";
echo "	function dropLeftCell(event,shift) {																									";
echo "																																			";
echo "		var mouseX = event.clientX;																											";
echo "		var mouseY = event.clientY;																											";
echo "																																			";
echo "		var shift			= dragged.parentNode.parentNode.parentNode.parentNode;															";
echo "																																			";
echo "		var droppedOver 	= document.elementFromPoint(mouseX,mouseY);																		";
echo "		var dropCoords		= droppedOver.getBoundingClientRect();																			";
echo "																																			";
echo "		var difWidth		= dragX - mouseX;																								";
echo "		var oldWidth		= shift.offsetWidth;																							";
echo "		var newWidth		= difWidth + oldWidth;																							";
echo "																																			";
echo "		shift.style.width   = newWidth + 'px';																								";
echo "																																			";
echo "		$(shift).offset({top:dragged.top,left:dropCoords.left});																			";
echo "																																			";
echo "	}																																		";
echo "																																			";
echo "</script>																																	";

//************************************************************************************************************************************************

echo "<script>																																	";
echo "																																			";
echo "	function dropRightCell(event,shift) {																									";
echo "																																			";
echo "		var mouseX 			= event.clientX;																								";
echo "		var mouseY 			= event.clientY;																								";
echo "																																			";
echo "		var shift			= dragged.parentNode.parentNode.parentNode.parentNode;															";
echo "																																			";
echo "		var droppedOver 	= document.elementFromPoint(mouseX,mouseY);																		";
echo "		var dropCoords		= droppedOver.getBoundingClientRect();																			";
echo "																																			";
echo "		var difWidth		= mouseX - dragX;																								";
echo "		var oldWidth		= shift.offsetWidth;																							";
echo "		var newWidth		= difWidth + oldWidth;																							";
echo "																																			";
echo "		shift.style.width   = newWidth + 'px';																								";
echo "																																			";
echo "	}																																		";
echo "																																			";
echo "</script>																																	";

//************************************************************************************************************************************************

echo "<script>																																	";
echo "																																			";
echo "	function dropMiddleCell(event,shift) {																									";
echo "																																			";
echo "		var mouseX 			= event.clientX;																								";
echo "		var mouseY 			= event.clientY;																								";
echo "																																			";
echo "		var shift			= dragged.parentNode.parentNode.parentNode.parentNode;															";
echo "																																			";
echo "		var droppedOver 	= document.elementFromPoint(mouseX,mouseY);																		";
echo "		var dropCoords		= droppedOver.getBoundingClientRect();																			";
echo "		var offset			= shift.offsetWidth / 2;																						";
echo "																																			";
echo "		$(shift).offset({top:dropCoords.top,left:dropCoords.left - offset});																";
echo "																																			";
echo "	}																																		";
echo "																																			";
echo "</script>																																	";

//************************************************************************************************************************************************

echo "<script>																																	";
echo "																																			";
echo "	function preventDefaults(event) {																										";
echo "																																			";
echo "		event.preventDefault();																												";
echo "																																			";
echo "	}																																		";
echo "																																			";
echo "</script>																																	";

//************************************************************************************************************************************************

echo "<script>																																	";
echo "																																			";
echo "	var dragged 			   = null;																										";
echo "	var dragX   			   = null;																										";
echo "	var dragY   			   = null;																										";
echo "	var draggedBackgroundColor = null;																										";
echo "																																			";
echo "</script>																																	";

createProtoType();



?>