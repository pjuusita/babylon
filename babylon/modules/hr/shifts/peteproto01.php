<?php

	echo "<h1>Pete shift proto </h1>";

	
	

	function createProtoType() {
	
		//createWeekly();
	
		echo "<div id='management-div' style='border: 1px solid black;'>";
		
		//echo "<br><br>";
		createNames();
		createHourTable();
	
		/*
		echo "<script>																																	";
		echo "																																			";
		echo "	$(init);";
		echo "																																			";
		echo "	function init() {";
		for($employee=0;$employee<6;$employee++) {
			echo "	$('#namediv" . $employee . "').draggable( {";
			echo "		cursor: 'move',";
			echo "		containment: '#management-div',";
			echo "		stop: handleDragStop,";
			echo "	});";
		}
		echo "	}";
		echo "																																			";
		echo "</script>																																	";
		*/
		
		
		echo "<script>			";
		echo "																															";
		echo "	function handleDragStop(event, ui) {";
		echo "		alert('stoppi');";
		echo "	}";
		echo "																																			";
		echo "</script>";
		
		echo "</div>";
		
		

		echo "<script>";
		echo "	function dragoverme() {";
		echo "		c;";
		echo "	}";
		echo "</script>";
		
		
	}
	
	
	function createNames() {
		for($employee=0;$employee<6;$employee++) {
			$employeeName 		= getEmployeeName($employee);
			$color		  		= getColor($employee);
			$bordercolor		= getBorderColor($employee);
			echo "<div id='namediv" . $employee . "'  draggable='true'  onmouseover=\"javascript:this.style.cursor='move';\" style='border: 3px solid ". $bordercolor . ";background-color : ".$color.";width: 200px;height:16px;text-align:center;padding:5px;margin-bottom:3px;-moz-border-radius: 10px;-webkit-border-radius: 10px;position:inner;'>".$employeeName."</div>";
		}
	}

	

	function createHourTable() {
	

		echo "<script>";
		echo "	function dragentteri(event) {";
		//echo "		console.log('gragentteri');";
		echo "		event.preventDefault();";
		echo "		var element = event.target;";
		echo "		element.style.background = 'green';";
		echo "	}";
		echo "</script>";
		
		echo "<script>";
		echo "	function dragoutti(event) {";
		//echo "		console.log('dragoutti');";
		echo "		event.preventDefault();";
		echo "		var element = event.target;";
		echo "		element.style.background = '#dfdfdf';";
		echo "	}";
		echo "</script>";
		
		echo "<script>";
		echo "	function dragrdop(event) {";
		echo "		event.preventDefault();";
		//echo "		console.log('dragrdop');";
		echo "	}";
		echo "</script>";
		
		echo "<script>";
		echo "	function dragover(event) {";
		echo "		event.preventDefault();";
		//echo "		console.log('dragover');";
		echo "	}";
		echo "</script>";
		
		
		$week = 1;
		for($week=0;$week<10;$week++) {
			for($day=0;$day<7;$day++) {
				echo "<table id='shiftmanagementsheet' style='border-collapse : collapse;'>";
				$dayName = getDayName($day);
				echo "<tr id='tophalv-".($day + $week * 7)."'>";
				echo "<td style='width:30px;'>".$dayName."</td>";
				for($hour = 0; $hour < 106; $hour++) {
					echo "		<td ondragenter='dragentteri(event)' ondrop='dragrdop(event)' ondragover='dragover(event)'  ondragleave='dragoutti(event)'  style='background-color:#dfdfdf;border-top:2px solid white;border-right:1px solid white;height:30px;width:10px;'>";
					echo "		</td>";
				}
				echo "</tr'>";
				echo "</table>";
			}
		}
	
	}
	

	function createWeekly() {
	
		echo "<table id='weekly'  style='border-collapse : collapse;'>";
	
		createWeeklyHeader();
		createWeeklyRows();
	
		echo "</table>";
	
	}
	
	
	function createWeeklyHeader() {
	
	
		echo "<tr>";
	
		echo "<th></th>";
		echo "<th colspan=7 style='text-align:center;'>Vko 1</th>";
		echo "<th colspan=7 style='text-align:center;'>Vko 2</th>";
		echo "<th colspan=7 style='text-align:center;'>Vko 3</th>";
		echo "<th colspan=7 style='text-align:center;'>Vko 4</th>";
		echo "<th colspan=7 style='text-align:center;'>Vko 5</th>";
		echo "<th colspan=7 style='text-align:center;'>Vko 6</th>";
		echo "<th colspan=7 style='text-align:center;'>Vko 7</th>";
		echo "<th colspan=7 style='text-align:center;'>Vko 8</th>";
	
		echo "</tr>";
	
		echo "<tr>";
	
		echo "<th></th>";
	
		for($month=0;$month<2;$month++) {
			for($week=0;$week<4;$week++) {
				for($day=0;$day<7;$day++) {
						
					$dayName = getDayName($day);
					echo "<th>".$dayName."</th>";
				}
			}
		}
	
		echo "</tr>";
	
	}
	
	function createWeeklyRows() {
	
		for($employee=0;$employee<6;$employee++) {
	
			$employeeName = getEmployeeName($employee);
			$color		  = getColor($employee);
	
			echo "<tr>";
				
			echo "<td><div draggable=true ondragstart='onDragStart(event,\"innerHTML\")' style='background-color : ".$color."'>".$employeeName."</div></td>";
	
			for($month=0;$month<2;$month++) {
	
				for($day=1;$day<=28;$day++) {
						
					$modulo = $day % 7;
	
					if ($modulo!=0) echo "<td style='background-color : lightgray; border-width : 1px; border-style : none none none none; padding : 0px 0px 3px 0px;'>&nbsp&nbsp&nbsp&nbsp</td>";
					if ($modulo==0) echo "<td style='background-color : lightgray; border-width : 1px; border-style : none solid none none; border-color : white; padding : 0px 0px 3px 0px;'>&nbsp&nbsp&nbsp&nbsp</td>";
	
				}
			}
			echo "</tr>";
	
		}
	}
	
	
	

	function createHeader() {
	
		echo "<tr>";
	
		echo "<th colspan=6 style='text-align:center;'>06:00</th>";
	
		for($hour=7;$hour<30;$hour++) {
	
			$textHour = $hour;
			if ($hour>23) $textHour = $hour - 24;
	
			if ($hour<10) $textHour = "0".$textHour;
	
			$textHour.= ":00";
	
			echo "<th colspan=10 style='text-align:center;'>".$textHour."</th>";
	
		}
	
		echo "<th colspan=6 style='text-align:center;'>06:00</th>";
	
	
		echo "<tr>";
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
	
		$colors[0] = "#1bbc9d";
		$colors[1] = "#e77e23";
		$colors[2] = "#2fcc71";
		$colors[3] = "#f1c40f";
		$colors[4] = "#3598dc";
		$colors[5] = "#9c59b8";
		$colors[6] = "#e84c3d";
	
		return $colors[$colorNumber];
	
	}
	
	

	function getBorderColor($colorNumber) {
	
		$colors = array();
	
		$colors[0] = "#16a086";
		$colors[1] = "#d55401";
		$colors[2] = "#27ae61";
		$colors[3] = "#f49c14";
		$colors[4] = "#2a80b9";
		$colors[5] = "#8f44ad";
		$colors[6] = "#c1392b";
	
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
	echo "	function onDropNewRow(event) {																											";
	echo "																																			";
	echo "		var sourceRow = event.target.parentNode;																							";
	echo "		var rowIndex  = sourceRow.rowIndex;																									";
	echo "		var table	  = sourceRow.parentNode;																								";
	echo "		var clones	  = table.rows[rowIndex-1].cloneNode(true);																				";
	echo "		middleRowOnDragLeave(event);																										";
	echo "		var emptyRowClones = table.rows[rowIndex].cloneNode(true);																																	";
	echo "																																			";
	echo "																																			";
	echo "		clones.childNodes[0].innerHTML = '';																								";
	echo "		table.insertBefore(emptyRowClones,table.childNodes[rowIndex+1]);																	";
	echo "		table.insertBefore(clones,table.childNodes[rowIndex+1]);																			";
	echo "																																			";
	echo "																																			";
	echo "		var shift	  = dragged.parentNode.parentNode.parentNode.parentNode;																";
	echo "		var mouseX 	  = event.clientX;																										";
	echo "		var mouseY 	  = event.clientY;																										";
	echo "																																			";
	echo "		$(shift).offset({top:mouseY,left:mouseX});																							";
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
	echo "	function onDragEnter(event) {																											";
	echo "																																			";
	echo "		preventDefaults(event);																												";
	echo "																																			";
	echo "		var element				  	  = event.target;																						";
	echo "		element.style.backgroundColor = 'black';																							";
	echo "																																			";
	echo "	}																																		";
	echo "																																			";
	echo "</script>																																	";
	
	//************************************************************************************************************************************************
	
	echo "<script>																																	";
	echo "	function onDragOver(event) {																											";
	echo "																																			";
	echo "		preventDefaults(event);																												";
	echo "																																			";
	echo "		var element				  = event.target;																							";
	echo "		var elementPos			  = element.getBoundingClientRect();																		";
	echo "		var elementHeightMid	  = (elementPos.bottom - elementPos.top) / 2;																";
	echo "		var relativeMouseX		  = event.clientX; 																							";
	echo "		var relativeMouseY		  = event.clientY - elementPos.top;																			";
	echo "		var compare				  = elementHeightMid - relativeMouseY;																		";
	echo "																																			";
	echo "		var row = element.parentNode;																										";
	echo "		var rowIndex = row.rowIndex;																																	";
	echo "																																			";
	echo "		if (compare>0) { row.parentNode.rows[rowIndex-1].style.display = 'initial'; row.parentNode.rows[rowIndex+1].style.display = 'none'; }																								";
	echo "		if (compare<=0)	{ row.parentNode.rows[rowIndex+1].style.display = 'initial'; row.parentNode.rows[rowIndex-1].style.display = 'none';  }																													";
	echo "																																			";
	echo "																																			";
	echo "	}																																		";
	echo "																																			";
	echo "</script>																																	";
	
	//************************************************************************************************************************************************
	
	echo "<script>																																	";
	echo "																																			";
	echo "	function onDragLeave(event) {																											";
	echo "																																			";
	echo "		preventDefaults(event);																												";
	echo "																																			";
	echo "		var element				  	  = event.target;																						";
	echo "		element.style.backgroundColor = 'lightgray';																							";
	echo "																																			";
	echo "		var row = element.parentNode;																										";
	echo "		var rowIndex = row.rowIndex;																																	";
	echo "																																			";
	echo "		row.parentNode.rows[rowIndex-1].style.display = 'none';																								";
	echo "		row.parentNode.rows[rowIndex+1].style.display = 'none';																													";
	echo "																																			";
	echo "																																			";
	echo "																																			";
	echo "	}																																		";
	echo "																																			";
	echo "</script>																																	";
	
	echo "<script>																																	";
	echo "																																			";
	echo "		function middleRowOnDragEnter(event)	{																							";
	echo "																																			";
	echo "			preventDefaults(event);																											";
	echo "			var element = event.target;																										";
	echo "			element.style.backgroundColor = 'black';																						";
	echo "																																			";
	echo "		}																																	";
	echo "																																			";
	echo "</script>																																	";
	
	
	echo "<script>																																	";
	echo "																																			";
	echo "		function middleRowOnDragLeave(event)	{																							";
	echo "																																			";
	echo "			preventDefaults(event);																											";
	echo "			var element = event.target;																										";
	echo "			element.style.backgroundColor = 'lightgray';																						";
	echo "																																			";
	echo "		}																																	";
	echo "																																			";
	echo "</script>																																	";
	
	echo "<script>																																	";
	echo "																																			";
	echo "		function middleRowOnDrop(event)	{																									";
	echo "																																			";
	echo "			preventDefaults(event);																											";
	echo "																																			";
	echo "			newRow(event);																																";
	echo "																																			";
	echo "																																			";
	echo "																																			";
	echo "																																			";
	echo "		}																																	";
	echo "																																			";
	echo "</script>																																	";
	
	
	
	//************************************************************************************************************************************************
	
	echo "<script>																																	";
	echo "																																			";
	echo "	function onDropElement(event) {																											";
	echo "																																			";
	echo "																																			";
	echo "		onDragLeave(event);																													";
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
	echo "																																			";
	echo "		dragMiddleCell.addEventListener('dragstart',function(ev) {																			";
	echo "			dragged = dragMiddleCell;																										";
	//echo "			shift.style.display = 'none';																																";
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
	echo "		paintWeeklyView(dragged,targetCell);																								";
	echo "																																			";
	echo "		return shift;																														";
	echo "																																			";
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
	echo "		$(shift).offset({top:dropCoords.top,left:dropCoords.left - offset});																";
	echo "	}																																		";
	echo "																																			";
	echo "</script>																																	";
	
	//************************************************************************************************************************************************
	
	echo "<script>																																	";
	echo "																																			";
	echo "	function paintWeeklyView(dragged,droppedOver) {																							";
	echo "																																			";
	echo "		var weeklyTable		 = document.getElementById('weekly');																			";
	echo "																																			";
	echo "		var rowIndex 		 = dragged.parentNode.parentNode.rowIndex;																		";
	echo "		var dayIndex		 = parseInt(droppedOver.parentNode.id);																			";
	echo "		var cell 			 = weeklyTable.rows[rowIndex].cells[dayIndex+1];																";
	echo "																																			";
	echo "		cell.style.backgroundColor = 'blue';																								";
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
	echo "	document.onselectstart = function() {																									";
	echo "		window.getSelection().removeAllRanges();																							";
	echo "	};																																		";
	echo "																																			";
	echo "</script>																																	";
	
	createProtoType();
	
	
?>