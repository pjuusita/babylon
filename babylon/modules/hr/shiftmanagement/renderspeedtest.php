<?php


function oneTablePerRow148Cells($rowCount,$cells) {
	
	
	for($row=0;$row<$rowCount;$row++) {

		echo "<table style='border-collapse : collapse; width:100%'>";
			echo "<tr style='border-collapse : collapse;'>";
				echo "<td style='border-collapse : collapse;width:1200px;'>";
		
				for($cell=0;$cell<$cells;$cell++) {
					CreateCellDiv();
				}
				
				echo "</td>";
			echo "</tr>";

		echo "</table>";
				
	}
}


function oneTableRows148Cells($rowCount,$cells) {
	
	echo "<table style='border-collapse : collapse; width:100%'>";
	
		for($row=0;$row<$rowCount;$row++) {
	
			echo "<tr style='border-collapse : collapse;'>";
		
			echo "<td style='border-collapse : collapse;width:1200px;'>";
	
				for($cell=0;$cell<$cells;$cell++) {
					CreateCellDiv();
				}
	
			echo "</td>";
			echo "</tr>";
	}
	
	echo "</table>";
	
}

function allDivs($rowCount,$cells) {

	echo "<div style='border-collapse : collapse; width:100%'>";

	for($row=0;$row<$rowCount;$row++) {

		
		echo "<div style='border-collapse : collapse;width:1200px;'>";

		for($cell=0;$cell<$cells;$cell++) {
			CreateCellDiv();
		}

		echo "</div>";
	}

	echo "</div>";

}

function createCellDiv() {
	
	$paint = $_GET['paint'];
	
	if ($paint=='yes') echo "<div style='border-style : solid solid solid solid; float:left; border-width : 1px' onmouseover='paintSelected(event,\"black\")' onmouseleave='paintSelected(event,\"white\")' ondragenter='preventDefaults(event)' ondragover='preventDefaults(event)'  ondragleave='preventDefaults(event)' ondrop='onDropDiv(event)'>";
	if ($paint=='no') echo "<div style='border-style : solid solid solid solid; float:left; border-width : 1px'  ondragenter='preventDefaults(event)' ondragover='preventDefaults(event)'  ondragleave='preventDefaults(event)'  ondrop='onDropDiv(event)'>";
	
		echo "&nbsp";
	
	echo "</div>";
	
}

function oneTableOneTD($rowCount,$cells) {
	
	echo "<table style='border-collapse : collapse; width:100%; table-layout : fixed;'>";
	
	for($row=0;$row<$rowCount;$row++) {
	
		echo "<tr>";
		echo "<td style='background-color : white'>";
			createCellDivFullRow();
		echo "</td>";
		echo "</tr>";
	
	
	}
	
	echo "</table>";
	
}


function createCellDivFullRow() {
	
	echo "<div style='width : 100%; border-style : solid solid solid solid; float:left; border-width : 1px'  ondragenter='preventDefaults(event)' ondragover='preventDefaults(event)'  ondragleave='preventDefaults(event)'  ondrop='onDropFullDiv(event)'>";
	echo "&nbsp";
	echo "</div>";
	
}


function createDraggableDiv() {
	
	echo "<div draggable=true style='background-color : green'>";
	echo "Drag Me!";
	echo "</div>";
	
	
}

//*************************************************************************************************************************************************************

echo "<script>																																			";
echo "																																					";
echo "	function paintSelected(event,color) {																											";
echo "																																					";
echo "		var element = event.target;																													";
echo "		element.style.backgroundColor = color;																										";
echo "																																					";
echo "	}																																				";
echo "																																					";
echo "</script>																																			";

//*************************************************************************************************************************************************************

echo "<script>																																			";
echo "																																					";
echo "	function preventDefaults(event) {																												";
echo "																																					";
echo "		event.preventDefault();																														";
echo "																																					";
echo "	}																																				";
echo "																																					";
echo "</script>																																			";

//*************************************************************************************************************************************************************

echo "<script>																																			";
echo "																																					";
echo "	 function onDropDiv(event) {																													";
echo "																																					";
echo "		preventDefaults(event);																														";
echo "																																					";
echo "		var element = event.target;																													";
echo "		var coords  = element.getBoundingClientRect();																								";
echo "		var newDiv  = document.createElement('div');																								";
echo "																																					";
echo "		newDiv.style.backgroundColor = 'green'; 																									";
echo "		newDiv.style.position		 = 'absolute';																									";
echo "		newDiv.innerHTML			 = 'Dropped Div';																								";
echo "																																					";
echo "		$(newDiv).offset({top : coords.top , left : coords.left});																					";
echo "																																					";
echo "		document.body.appendChild(newDiv);																											";
echo "																																					";
echo "		console.log(coords.left + ':' + coords.top);																								";
echo "																																					";
echo "																																					";
echo "																																					";
echo "	}																																				";
echo "																																					";
echo "</script>																																			";

//*************************************************************************************************************************************************************

echo "<script>																																			";
echo "																																					";
echo "	 function onDropFullDiv(event) {																												";
echo "																																					";
echo "		preventDefaults(event);																														";
echo "																																					";
echo "		var element 		= event.target;																											";
echo "		var clientCoords  	= element.getBoundingClientRect();																						";
echo "																																					";
echo "		var clientWidth		=  clientCoords.right - clientCoords.left;																				";
echo "		var step			=  parseInt(clientWidth / 148);																							";
echo "																																					";
echo "		var mouseX			= event.clientX;																										";
echo "		var mouseY			= event.clientY;																										";
echo "																																					";
echo "		var dropX			= parseInt(mouseX/step) * step;																							";
echo "																																					";
echo "		var newDiv  		= document.createElement('div');																						";
echo "		var widthText		= (48 * step) + 'px'; 																																	";
echo "																																					";
echo "		newDiv.style.backgroundColor = 'green'; 																									";
echo "		newDiv.style.position		 = 'absolute';																									";
echo "		newDiv.innerHTML			 = 'Dropped Div';																								";
echo "		newDiv.style.width			 = widthText;																									";
echo "																																					";
echo "																																					";
echo "		$(newDiv).offset({top : clientCoords.top , left : dropX});																					";
echo "																																					";
echo "		document.body.appendChild(newDiv);																											";
echo "																																					";
echo "		console.log('step:'+step+',dropX:'+dropX+',mouseX:'+mouseX);																				";
echo "																																					";
echo "	}																																				";
echo "																																					";
echo "</script>																																			";



//*************************************************************************************************************************************************************

echo "<script>																																			";
echo "																																					";
echo "	function setOnMousePointer(event) {																												";
echo "																																					";
echo "		var mouseX = event.clientX;																													";
echo "		var mouseY = event.clientY;																													";
echo "																																					";
echo "		var img    = document.createElement('img');																									";
echo "																																					";
echo "		event.dataTransfer.set(img,0,0);																											";
echo "	}																																				";
echo "																																					";
echo "</script>																																			";




echo "<script>																																			";
echo "																																					";
echo "	function onDrop(event) {																														";
echo "																																					";
echo "																																					";
echo "																																					";
echo "	}																																				";
echo "																																					";
echo "</script>																																			";



$run = $_GET['proto'];

createDraggableDiv();

if ($run=='tableperrow') oneTablePerRow148Cells(70,106);
if ($run=='onetable') 	 oneTableRows148Cells(70,106);
if ($run=='onetableonetd')	oneTableOneTD(2000,106);
if ($run=='alldivs')	 allDivs(70,106);




?>