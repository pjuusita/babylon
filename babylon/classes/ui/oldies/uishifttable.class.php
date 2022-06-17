<?php

class UIShiftTable {
	
//********************************************************************************************************************************************************************
//**
//**
//********************************************************************************************************************************************************************

	private $shiftTableID;
	
	public function __construct($title,$id,$startDate,$days,$timeStep,$reserveAction,$updateAction,$deleteAction,$groupBy,$colorBy) {

		$shiftTableID 		= 'shiftTable'.$title.$id;

		$showID				= "show".$id;
		$hideID				= "hide".$id;
		
		$this->createScripts($id,$shiftTableID);
		
		$this->shiftTableID	= $shiftTableID;
		
		$imgUrl		  		= getUrl('hr/shifts/shifthourimage') . '&width=1400&hours=25&height=25';
		$headerImgUrl 		= getUrl('hr/shifts/shifthourheader') . '&width=1400&hours=25&height=25';
		$imgWidth	  		= 1400;
		$hours		  		= 25;
		
		$reserveAction 		= getUrl($reserveAction);
		$updateAction 		= getUrl($updateAction);
		$deleteAction 		= getUrl($deleteAction);
		
		echo "<br><div class='ui-widget-header'>";
		echo "<table width='100%'>";
		
		echo "<td style='text-align : left'>".$title."</td>";
		echo "<td style='text-align : right;></td>";
		echo "<td style='text-align : right'><button id='".$hideID."' onclick='showandhide".$id."(1,0)'>Piilota <button id='".$showID."' style='display:none' onclick='showandhide".$id."(0,1)'>Avaa</td>";
				
		echo "</table>";
		echo "</div>";
		
		echo "<div id='".$shiftTableID."' style = 'background-color : lightgray ; overflow-x : auto ; white-space : nowrap; width : 1200px'></div> ";
	
		echo "<script>																																			";
		echo "		createShiftTable('".$title."','".$shiftTableID."','".$startDate."','".$days."','".$timeStep."','".$imgWidth."',".$hours.",'".$imgUrl."','".$headerImgUrl."','".$reserveAction."','".$updateAction."','".$deleteAction."','".$groupBy."','".$colorBy."');		";
		echo "		onRefreshReassignShiftMarkerTop();																											";
		echo "</script>																																			";
	
	}

//********************************************************************************************************************************************************************
//**
//**
//********************************************************************************************************************************************************************
	
	public function createExistingShiftMarkers() {
		
		$shiftTableID	= $this->shiftTableID;
			
		echo "<script>																																";
		echo "	var shiftTable			 = document.getElementById('".$shiftTableID."');															";
		echo "	createExistingShiftMarkers(shiftTable);																								";
		echo "</script>																																";
		
	}
	
//********************************************************************************************************************************************************************
//**
//**
//********************************************************************************************************************************************************************
	
	public function setExistingEmployeeData($data) {

		$shiftTableID	= $this->shiftTableID;
		
		echo "<script>																																	";
		echo "	var shiftTable			 = document.getElementById('".$shiftTableID."');																";
		echo "	shiftTable.employeeData = [];																											";
		echo "</script>																																	";
		
		$colorIndex = 0;
		
		foreach($data as $rowIndex => $dataRow) {
		
			echo "<script>																																";
			echo "	var employeeRow = [];																												";
			echo "</script>																																";
				
			
			$dataVariables = $dataRow->getDataVariables();
		
			foreach($dataVariables as $varname => $varvalue) {
					
				echo "<script>																															";
				echo "	employeeRow['".$varname."'] = '".$varvalue."';																					";
				//echo "  employeeRow['color']	= '".$colorIndex."'";
				echo "</script>																															";
		
			}
		
			$colorIndex++;
			
			echo "<script>																																";
			echo "	shiftTable.employeeData[employeeRow['employeeID']] = employeeRow;																	";
			echo "</script>																																";
		
		}
	}
	
	//********************************************************************************************************************************************************************
	//**
	//**
	//********************************************************************************************************************************************************************
	
	public function setExistingProjectData($data) {
	
		$shiftTableID = $this->shiftTableID;
	
		echo "<script>																																	";
		echo "	var shiftTable 			= document.getElementById('".$shiftTableID."');																	";
		echo "	shiftTable.projectData 	= [];																											";
		echo "</script>																																	";
	
		$colorIndex = 0;
		
		foreach($data as $rowIndex => $dataRow) {
	
			$dataVariables = $dataRow->getDataVariables();
	
			echo "<script>																																";
			echo "	var projectRow = [];																												";
			echo "</script>																																";
				
				
			foreach($dataVariables as $varname => $varvalue) {
					
				echo "<script>																															";
				echo "	projectRow['".$varname."'] = '".$varvalue."';																					";
				//echo "  projectRow['color']	= '".$colorIndex."'";
				echo "</script>																															";
	
			}
			
			$colorIndex++;
	
			echo "<script>																																";
			echo "	shiftTable.projectData[projectRow['projectID']] = projectRow;																		";
			echo "</script>																																";
	
		}
	}
	
	
//********************************************************************************************************************************************************************
//**
//**
//********************************************************************************************************************************************************************
	
	public function setExistingTaskData($data) {

		$shiftTableID = $this->shiftTableID;
		
		echo "<script>																																	";
		echo "	var shiftTable 			= document.getElementById('".$shiftTableID."');																	";
		echo "	shiftTable.taskData 	= [];																											";
		echo "</script>																																	";
		
		$colorIndex = 0;
		
		foreach($data as $rowIndex => $dataRow) {
		
			$dataVariables = $dataRow->getDataVariables();

			echo "<script>																																";
			echo "	var taskRow = [];																													";
			echo "</script>																																";
			
			
			foreach($dataVariables as $varname => $varvalue) {
					
				echo "<script>																															";
				echo "	taskRow['".$varname."'] = '".$varvalue."';																						";
				//echo "  taskRow['color']	= '".$colorIndex."'";
				echo "</script>																															";
		
			}
			
			$colorIndex++;
				
			echo "<script>																																";
			echo "	shiftTable.taskData[taskRow['taskID']] = taskRow;																					";
			echo "</script>																																";
				
		}
	}

//********************************************************************************************************************************************************************
//**
//**
//********************************************************************************************************************************************************************
	
	public function setPrimaryData($dataRow,$idColumn,$colorIndex) {
	
		$shiftTableID = $this->shiftTableID;
		
		
		echo "<script>																																	";
		echo "	var shiftTable 			= document.getElementById('".$shiftTableID."');																	";
		echo "	shiftTable.primaryData 	= [];																											";
		//echo "  console.log('Setting primary tableData.');																								";
		echo "</script>																																	";
	
		$dataVariables = $dataRow->getDataVariables();
	
		echo "<script>																																	";
		echo "	var primaryRow = [];																													";
		echo "</script>																																	";
				
				
		foreach($dataVariables as $varname => $varvalue) {
					
			echo "<script>																																";
			echo "																																		";
			echo "	primaryRow['".$varname."'] = '".$varvalue."';																						";
			echo "																																		";
			echo "</script>																																";
	
		}
	
		echo "<script>																																";
		echo "																																		";
		echo "	primaryRow['color'] = '".$colorIndex."';																							";
		echo "	shiftTable.primaryData = primaryRow;																								";
		echo "</script>																																";
		
	}
		
	
//********************************************************************************************************************************************************************
//**
//**
//********************************************************************************************************************************************************************
	
	public function setExistingShiftMarkerData($data) {
		
		$shiftTableID = $this->shiftTableID;

		echo "<script>																																	";
		echo "	var shiftTable 				= document.getElementById('".$shiftTableID."');																";
		echo "	shiftTable.shiftData 		= [];																										";
		echo "</script>																																	";
		
		foreach($data as $rowIndex => $dataRow) {
			
			$dataVariables = $dataRow->getDataVariables();
		
			echo "<script>																																";
			echo "	var shiftRow = [];																													";
			echo "</script>																																";
			
			foreach($dataVariables as $varname => $varvalue) {
					
				echo "<script>																															";
				echo "	shiftRow['".$varname."'] = '".$varvalue."';																						";
				echo "</script>																															";
		
			}
		
			echo "<script>																																";
			echo "	shiftTable.shiftData.push(shiftRow);																								";
			echo "</script>																																";
		
		}
		
	}
	
//********************************************************************************************************************************************************************
//**
//**
//********************************************************************************************************************************************************************
	
	private function createScripts($id,$shiftTableID) {
		
		$showID				= "show".$id;
		$hideID				= "hide".$id;
		
		echo "<script>																																			";
		echo "																																					";
		echo "	function showandhide".$id."(toShow,toHide) {																									";
		echo "																																					";
		echo "		var show = document.getElementById('".$showID."');																							";
		echo "		var hide = document.getElementById('".$hideID."');																							";
		echo "		var table = document.getElementById('".$shiftTableID."');																					";
		echo "																																					";
		echo "		if (toShow=='0') $(show).hide();																											";
		echo "		if (toShow=='1') $(show).show();																											";
		echo "		if (toShow=='0') $(table).show();																											";
		echo "																																					";
		echo "		if (toHide=='0') $(hide).hide();																											";
		echo "		if (toHide=='1') $(hide).show();																											";
		echo "		if (toHide=='0') $(table).hide();																											";
		echo "																																					";
		echo "		onVisibilityChange();																														";
		echo "	}																																				";
		echo "																																					";
		echo "</script>																																			";

		echo "<script>																																			";
		echo "																																					";
		echo "	function onRefreshReassignShiftMarkerTop() {																																				";
		echo "																																					";
		echo "		onVisibilityChange();																														";
		echo "	}																																				";
		echo "																																					";
		echo "</script>																																			";
		
		
	}
	
	
}

?>