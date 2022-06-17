<?php

	echo "<h1>Pete shift proto </h1>";

	
	

	function createShiftView() {
	
		echo "<div style='height:50px;font-size:24px;'>Oletus tyavuoropituus: ";
		echo "<select id='defaultworktime' style='font-size:24px;padding:4px;width:100px;height:40px;'>";
		echo "		<option value='6'>6h</option>";
		echo "		<option value='7'>7h</option>";
		echo "		<option value='8'>8h</option>";
		echo "		<option value='9'>9h</option>";
		echo "</select>";
		echo "</div>";
		
		echo "<div id='management-div' style='border: 1px solid black;'>";
		createNames();
		createHourTable(40);
		echo "</div>";
	}
	
	
	function createNames() {
		for($employee=0;$employee<6;$employee++) {
			$employeeName 		= getEmployeeName($employee);
			$color		  		= getColor($employee);
			$bordercolor		= getBorderColor($employee);
			echo "<div id='namediv" . $employee . "' class='dragsource' draggable='true'  onmouseover=\"javascript:this.style.cursor='move';\" style='border: 3px solid ". $bordercolor . ";background-color : ".$color.";width: 200px;height:16px;text-align:center;padding:5px;margin-bottom:3px;-moz-border-radius: 10px;-webkit-border-radius: 10px;position:inner;'>".$employeeName."</div>";

			echo "<script>";
			echo "	$('#namediv" . $employee . "').draggable( {";
			//echo "		helper: 'clone',";
			echo "		stop: function(event, ui) {";
			echo "			var position = $(this).position();";
			echo "			var xPos = ui.offset.left - $(this).offset().left;";
			echo "			var yPos = ui.offset.top - $(this).offset().top;";
			//echo "			console.log('droppos: left: '+xPos+', top: '+yPos);";
			echo "		}";
			echo " });";
			
			/*
			echo "	$('#namediv" . $employee . "').droppable( {";
			echo "		over: function(event,ui) {";
			echo "			var element = event.target;";
			echo "			element.style.background = 'green';";
			//echo "			event.stopPropagation();";
			//echo "			event.cancalBubble = true;";
			echo "		},";
			echo "		out: function(event,ui) {";
			echo "			var element = event.target;";
			echo "			element.style.background = 'lightblue';";
			//echo "			element.style.backgroundImage = \"url('".getNoframesUrl('hr/shifts/shifthourimage')."&width=960&hours=24&height=15')\";";
			//echo "			event.stopPropagation();";
			//echo "			event.cancalBubble = true;";
			echo "		},";
			echo " });";
			*/
			
			echo "</script>";
		}
	}

	

	function createHourTable($hourwidth) {
	
		
		echo "<table id='headertable' style='border-collapse:collapse;table-layout:fixed;width:1010px;'>";
		
		echo "		<tr>";
		echo "			<td style='width:50px;padding:0px;text-align:left;'><div style'width:100px;'></div></td>";
		echo "			<td style='background-color:#dfdfdf;border-bottom:1px solid white;border-right:1px solid white;height:15px;width:10px;width:960px;'  background='".getNoframesUrl('hr/shifts/shifthourheader')."&width=960&hours=24&height=30'></td>";
		echo "		</tr>";

		echo "</table>";
		
		
		
		$week = 1;
		$counter = 1;
		for($week=0;$week<10;$week++) {
			for($day=0;$day<7;$day++) {

				
				echo "<table id='shiftmanagementsheet" . $week . "-" . $day . "' style='border-collapse:collapse;table-layout:fixed;width:1010px;'>";
				
				echo "<tr id='tap-" . $counter . "' style='display:none'>";
				echo "	<td style='width:50px;'></td>";
				echo "	<td class='dragtargetbottomnewline' style=''>";
				echo "		<div style='border-collapse:collapse;table-layout:fixed;width:960px;border:2px dashed black;background-color:#d0d0d0;height:30px;'>";
				echo "		</div>";
				echo "	</td>";
				echo "</tr>";
				
				
				$dayName = getDayName($day);
				echo "<tr>";
				echo "<td rowspan=2 style='width:50px;padding:0px;text-align:left;'><div style'width:100px;'>".$dayName."</div></td>";
				//echo "<td rowspan=2 style='width:50px;padding:0px;text-align:left;'><div style'width:100px;'>".$dayName."</div></td>";
				//for($hour = 0; $hour < 106; $hour++) {
					echo "<td id='top-" . $counter . "' class='dragtargettop' style='background-color:#dfdfdf;border-top:1px solid white;border-right:1px solid white;height:15px;width:960px;'  background='".getNoframesUrl('hr/shifts/shifthourimage')."&width=960&hours=24&height=15'></td>";
					
					//echo "<td ondragenter='dragentteritop(event," . $week . "," . $day . ")' ondrop='dragrdoptop(event," . $week . "," . $day . ")' ondragover='dragovertop(event," . $week . "," . $day . ")'  ondragleave='dragouttitop(event," . $week . "," . $day . ")'  style='background-color:#dfdfdf;border-top:1px solid white;border-right:1px solid white;height:15px;width:10px;'></td>";
				//}
				echo "</tr>";
				
				echo "<tr>";
				//for($hour = 0; $hour < 106; $hour++) {
					echo "<td id='bot-" . $counter . "' class='dragtargetbottom' style='background-color:#dfdfdf;border-bottom:1px solid white;border-right:1px solid white;height:15px;width:10px;width:960px;'  background='".getNoframesUrl('hr/shifts/shifthourimage')."&width=960&hours=24&height=15'></td>";
					//echo "<td ndragenter='dragentteribottom(event," . $week . "," . $day . ")' ondrop='dragrdopbottom(event," . $week . "," . $day . ")' ondragover='dragoverbottom(event," . $week . "," . $day . ")'  ondragleave='dragouttibottom(event," . $week . "," . $day . ")'  style='background-color:#dfdfdf;border-bottom:1px solid white;border-right:1px solid white;height:15px;width:10px;'></td>";
				//}
				echo "</tr>";
				
				echo "<tr  id='bat-" . $counter . "'  style='display:none'>";
				echo "	<td></td>";
				echo "	<td class='dragtargetbottomnewline' style=''>";
				echo "		<div style='border-collapse:collapse;table-layout:fixed;width:960px;border:2px dashed black;background-color:#d0d0d0;height:30px;'>";
				echo "		</div>";
				echo "	</td>";
				echo "</tr>";
				
				echo "</table>";
				
				//echo "<div id='shiftmanagementsheet" . $week . "-" . $day . "-bottom' style='padding-left:50px;display:none;border-collapse:collapse;table-layout:fixed;width:1200px;border:2px dashed black;background-color:#d0d0d0;height:20px;'>";
				//echo "		<td style='border:2px dashed black;background-color:#d0d0d0'></td>";
				//echo "</div>";
				
				$counter++;
			}
		}
		
		echo "<script>";
		echo "		function createdropdiv(xPos, yPos) {";
		
		echo "		}";
		echo "</script>";
		
		

		echo "<script>";
		echo "	$('.dragtargettopnewline').droppable ({";
		echo "		accept: '.dragsource',";
		echo "		drop: function(event, ui) {";
		echo "		},";
		echo "		over: function(event,ui) {";
		echo "			var element = event.target;";
		echo "			console.log('overtopnewline- '+element.id);";
		echo "		},";
		echo "		out: function(event,ui) {";
		echo "			var element = event.target;";
		echo "			console.log('outtopnewline- '+element.id);";
		echo "		},";
		echo "	});";
		echo "</script>";
		
		
		echo "<script>";
		echo "	$('.dragtargettop').droppable ({";
		echo "		accept: '.dragsource',";
		echo "		drop: function(event, ui) {";
		
		echo "			var xPos = ui.offset.left - $(this).offset().left;";
		echo "			var yPos = ui.offset.top - $(this).offset().top;";
		echo "			var newYPos = $(this).offset().top - ui.offset.top;";
		
		echo "			var element = $(ui.draggable)[0];";
		
		//echo "			console.log('this: '+$(this));";
		echo "			$(this).css('backgroundColor', 'red');";
		echo "			$(this).css('background-image',\"url('".getNoframesUrl('hr/shifts/shifthourimage')."&width=960&hours=24&height=15')\");";
		echo "			element.style.left = '0px';";
		echo "			element.style.top = '0px';";
		
		echo "			var newElement = createNewShift(event, element, $(this).offset().left,$(this).offset().top,(ui.offset.left - $(this).offset().left));";
		
		echo "		},";
		echo "		over: function(event,ui) {";
		echo "			var element = event.target;";
		echo "			element.style.background = 'green';";
		
		//echo "			console.log('rowod - '+element.id);";
		//echo "			console.log('rowod - '+element.id.substring(4));";
		//echo "			console.log('rowod - '+element);";
		
		echo "			$('#tap-'+element.id.substring(4)).show();";
		
		/*
		echo "			var parent = element.parentNode;";
		echo "			var index = parent.rowIndex;";
		echo "			var table = parent.parentNode;";
		echo "			var tr = table.rows[index-1];";
		echo "			tr.style.display = 'table-row';";
		*/
		//echo "			console.log(tr);";
		
		echo "		},";
		echo "		out: function(event,ui) {";
		echo "			var element = event.target;";
		echo "			element.style.background = 'transparent';";
		echo "			element.style.backgroundImage = \"url('".getNoframesUrl('hr/shifts/shifthourimage')."&width=960&hours=24&height=15')\";";
		
		echo "			$('#tap-'+element.id.substring(4)).hide();";
		
		/*
		echo "			var parent = element.parentNode;";
		echo "			var index = parent.rowIndex;";
		echo "			var table = parent.parentNode;";
		echo "			var tr = table.rows[index-1];";
		echo "			tr.style.display = 'none';";
		*/
		//echo "			console.log(tr);";
		
		
		echo "		},";
		echo "	});";
		echo "</script>";

		
		echo "<script>";
		echo "	$('.dragtargetbottom').droppable ({";
		echo "		accept: '.dragsource',";
		echo "		drop: function(event, ui) {";
		
		echo "			var xPos = ui.offset.left - $(this).offset().left;";
		echo "			var yPos = ui.offset.top - $(this).offset().top;";
		echo "			var newYPos = $(this).offset().top - ui.offset.top;";
		
		echo "			var element = $(ui.draggable)[0];";
		
		//echo "			console.log('this: '+$(this));";
		echo "			$(this).css('backgroundColor', 'red');";
		echo "			$(this).css('background-image',\"url('".getNoframesUrl('hr/shifts/shifthourimage')."&width=960&hours=24&height=15')\");";
		echo "			element.style.left = '0px';";
		echo "			element.style.top = '0px';";
		
		echo "			var newElement = createNewShift(event, element, $(this).offset().left,$(this).offset().top,(ui.offset.left - $(this).offset().left));";
		
		echo "		},";
		echo "		over: function(event,ui) {";
		echo "			var element = event.target;";
		echo "			element.style.background = 'green';";
		
		//echo "			element."
		
		echo "			var parent = element.parentNode;";
		echo "			var index = parent.rowIndex;";
		echo "			var table = parent.parentNode;";
		echo "			var tr = table.rows[index+1];";
		echo "			tr.style.display = 'table-row';";
		//echo "			console.log(tr);";
		
		echo "		},";
		echo "		out: function(event,ui) {";
		echo "			var element = event.target;";
		echo "			element.style.background = 'transparent';";
		echo "			element.style.backgroundImage = \"url('".getNoframesUrl('hr/shifts/shifthourimage')."&width=960&hours=24&height=15')\";";
		
		echo "			var parent = element.parentNode;";
		echo "			var index = parent.rowIndex;";
		echo "			var table = parent.parentNode;";
		echo "			var tr = table.rows[index+1];";
		echo "			tr.style.display = 'none';";
		//echo "			console.log(tr);";
		
		echo "		},";
		echo "	});";
		echo "</script>";
		
		

		echo "<script>";
		echo "	$('.dragtargetbottomnewline').droppable ({";
		echo "		accept: '.dragsource',";
		echo "		drop: function(event, ui) {";
		echo "		},";
		echo "		over: function(event,ui) {";
		echo "			var element = event.target;";
		echo "			console.log('overbottomnewline- '+element.id);";
		echo "		},";
		echo "		out: function(event,ui) {";
		echo "			var element = event.target;";
		echo "			console.log('outbottomnewline- '+element.id);";
		echo "		},";
		echo "	});";
		echo "</script>";
		
		

		echo "<script>";
		echo "		function createNewShift(event, element, xPos, yPos, lengthPos) {";
		
		echo "			var hourWidth = " . $hourwidth . ";";
		echo "			var sourceDiv 					= event.target;";
		
		echo "			var newShift  					= document.createElement('div');";
		echo "			newShift.style.backgroundColor = element.style.backgroundColor;";
		echo "			newShift.style.height = element.style.height;";
		echo "			newShift.style.padding = element.style.padding;";
		echo "			newShift.style.border = element.style.border;";
		echo "			newShift.style.borderRadius = element.style.borderRadius;";
		
		echo "			newShift.onmouseover = function(event) {";
		echo "				console.log('mouseover');";
		echo "				newShift.style.backgroundColor = element.style.borderColor;";
		echo "			};";
		echo "			newShift.onmouseout = function(event) {";
		echo "				console.log('mouseout');";
		echo "				newShift.style.backgroundColor = element.style.backgroundColor;";
		echo "			};";
		echo "			newShift.onclick = function(event) {";
		echo "				console.log('onclick');";
		echo "				newShift.setAttribute('activateddi','true');";
		echo "				newShift.style.borderColor = 'black';";
		
		echo "				var active						= document.createElement('div');";
		echo "				active.style.backgroundColor 	= element.style.backgroundColor;";
		echo "				active.style.height 			= element.style.height;";
		echo "				active.style.padding 			= element.style.padding;";
		echo "				active.style.border 			= element.style.border;";
		echo "				active.style.borderRadius 		= element.style.borderRadius;";
		
		echo "			};";
		
		echo "			newShift.style.position		 	= 'absolute';";
		
		echo "			var width = $('#defaultworktime').val() * hourWidth;";
		echo "			newShift.style.width		  	= (width- 17) + 'px';";
		
		echo "			var lenni = Math.round(lengthPos / hourWidth);";
		
		echo "			document.body.appendChild(newShift);";
		echo "			newShift.style.left = (xPos + (lenni * hourWidth) +1) + 'px';";
		echo "			newShift.style.top = (yPos+2) + 'px';";
		
		echo "		}";
		echo "</script>";
		
		
		
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

	
	
	createShiftView();
	
	
?>