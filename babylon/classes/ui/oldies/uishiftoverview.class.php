<?php

//********************************************************************************************************************************************************************
//**  CLASS UISHIFTOVERVIEW
//**  Creates UIShiftOverview. Created by Tuukka Rintala 2016.
//********************************************************************************************************************************************************************

class UIShiftOverview {
	
	public function  __construct($date,$days,$daypadding,$employees,$tasks,$projects,$shifts,$top,$left) {
			
		//echo "<div id='shiftOverviewDiv' class='shiftOverview'></div>";
		echo "<div id='shiftOverviewDiv'></div>";
		
		
		$this->setExistingEmployeeData($employees);
		$this->setExistingShiftData($shifts);
		$this->setExistingTaskData($tasks);
		$this->setExistingProjectData($projects);
			
		echo "<script>																																	";
		echo "																																			";
		echo "	var parentDiv	= document.getElementById('shiftOverviewDiv');																			";
		echo "	shiftOverview(parentDiv,'".$date."','".$days."','".$daypadding."','".$top."','".$left."');												";
		echo "																																			";
		echo "</script>																																	";
		
		
	}

//********************************************************************************************************************************************************************
//**  PUBLIC FUNCTION SETEXISTINGEMPLOYEEDATA($DATA)
//**  Creates employee-data for shiftoverview.
//********************************************************************************************************************************************************************
	
	public function setExistingEmployeeData($data) {
	
		echo "<script>																																	";
		echo "	var shiftOverviewComponent	 = document.getElementById('shiftOverviewDiv');																";
		echo "	shiftOverviewComponent.employeeData 	 = [];																							";
		echo "</script>																																	";
	
		foreach($data as $rowIndex => $dataRow) {
	
			echo "<script>																																";
			echo "	var employeeRow = [];																												";
			echo "</script>																																";
	
			$dataVariables = $dataRow->getDataVariables();
	
			foreach($dataVariables as $varname => $varvalue) {
					
				echo "<script>																															";
				echo "	employeeRow['".$varname."'] = '".$varvalue."';																					";
				echo "</script>																															";
	
			}
	
			echo "<script>																																";
			echo "	shiftOverviewComponent.employeeData[employeeRow['employeeID']] = employeeRow;														";
			echo "</script>																																";
	
		}
	}

//********************************************************************************************************************************************************************
//**  PUBLIC FUNCTION SETEXISTINGTASKDATA($DATA)
//**  Creates task-data for shiftoverview.
//********************************************************************************************************************************************************************
	
	public function setExistingTaskData($data) {
	
		echo "<script>																																	";
		echo "	var shiftOverviewComponent	 = document.getElementById('shiftOverviewDiv');																";
		echo "	shiftOverviewComponent.taskData 	 = [];																								";
		echo "</script>																																	";
	
		foreach($data as $rowIndex => $dataRow) {
	
			echo "<script>																																";
			echo "	var taskRow = [];																													";
			echo "</script>																																";
	
			$dataVariables = $dataRow->getDataVariables();
	
			foreach($dataVariables as $varname => $varvalue) {
					
				echo "<script>																															";
				echo "	taskRow['".$varname."'] = '".$varvalue."';																						";
				echo "</script>																															";
	
			}
	
			echo "<script>																																";
			echo "	shiftOverviewComponent.taskData[taskRow['taskID']] = taskRow;																		";
			echo "</script>																																";
	
		}
	}
	
//********************************************************************************************************************************************************************
//**  PUBLIC FUNCTION SETEXISTINGPROJECTDATA($DATA)
//**  Creates project-data for shiftoverview.
//********************************************************************************************************************************************************************
	
	public function setExistingProjectData($data) {
	
		echo "<script>																																	";
		echo "	var shiftOverviewComponent	 = document.getElementById('shiftOverviewDiv');																";
		echo "	shiftOverviewComponent.projectData 	 = [];																								";
		echo "</script>																																	";
	
		foreach($data as $rowIndex => $dataRow) {
	
			echo "<script>																																";
			echo "	var projectRow = [];																												";
			echo "</script>																																";
	
			$dataVariables = $dataRow->getDataVariables();
	
			foreach($dataVariables as $varname => $varvalue) {
					
				echo "<script>																															";
				echo "	projectRow['".$varname."'] = '".$varvalue."';																					";
				echo "</script>																															";
	
			}
	
			echo "<script>																																";
			echo "	shiftOverviewComponent.projectData[projectRow['projectID']] = projectRow;															";
			echo "</script>																																";
	
		}
	}
	
//********************************************************************************************************************************************************************
//**  FUNCTION SETEXISTINSHIFTDATA($DATA)
//**  Creates shift-data for shiftoverview.
//********************************************************************************************************************************************************************
	
	public function setExistingShiftData($data) {
	
		echo "<script>																																	";
		echo "	var shiftOverviewComponent	 = document.getElementById('shiftOverviewDiv');																";
		echo "	shiftOverviewComponent.shiftData 	 = [];																								";
		echo "</script>																																	";
	
		foreach($data as $rowIndex => $dataRow) {
	
			echo "<script>																																";
			echo "	var shiftRow = [];																													";
			echo "</script>																																";
	
	
			$dataVariables = $dataRow->getDataVariables();
	
			foreach($dataVariables as $varname => $varvalue) {
					
				echo "<script>																															";
				echo "	shiftRow['".$varname."'] = '".$varvalue."';																						";
				//echo "console.log('".$varname."' + '=' +  '".$varvalue."');";
				echo "</script>																															";
	
			}
	
			echo "<script>																																";
			echo "	shiftOverviewComponent.shiftData.push(shiftRow);																					";
			echo "</script>																																";
	
		}
	}
	
}




?>