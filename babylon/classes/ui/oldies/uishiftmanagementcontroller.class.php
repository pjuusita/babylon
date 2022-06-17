<?php

class UIShiftManagementController {

	//********************************************************************************************************************************************************************
	//**
	//**
	//********************************************************************************************************************************************************************

	public function __construct($updateAction,$printAction,$groupBy,$colorBy,$startDate,$days,$employees,$tasks,$projects) {
			
		
		$updateAction = getUrl($updateAction);
		$printAction  = getUrl($printAction);
		
		echo "<div id='shiftManagementController'></div>";
		
		$this->setExistingEmployeeData($employees);
		$this->setExistingTaskData($tasks);
		$this->setExistingProjectData($projects);
		
		echo "<script>																																			";
		echo "																																					";
		echo "	var shiftManagementControllerDiv = document.getElementById('shiftManagementController');														";
		echo "	var component				  	 = shiftManagementController(shiftManagementControllerDiv,'".$updateAction."','".$printAction."','".$groupBy."','".$colorBy."','".$startDate."','".$days."');	";
		echo "																																					";
		echo "	shiftManagementControllerDiv.appendChild(component.element);																					";
		echo "																																					";
		echo "	console.log(component.element);																													";
		echo "																																					";
		echo "</script>																																			";
		
		echo "<br>";
	
	}
	
	//********************************************************************************************************************************************************************
	//**  PUBLIC FUNCTION SETEXISTINGEMPLOYEEDATA($DATA)
	//**  Creates employee-data for shiftoverview.
	//********************************************************************************************************************************************************************
	
	public function setExistingEmployeeData($data) {
	
		echo "<script>																																	";
		echo "	var shiftManagementControllerComponent	 = document.getElementById('shiftManagementController');																";
		echo "	shiftManagementControllerComponent.employeeData 	 = [];																							";
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
			echo "	shiftManagementControllerComponent.employeeData[employeeRow['employeeID']] = employeeRow;														";
			echo "</script>																																";
	
		}
	}
	
	//********************************************************************************************************************************************************************
	//**  PUBLIC FUNCTION SETEXISTINGTASKDATA($DATA)
	//**  Creates task-data for shiftoverview.
	//********************************************************************************************************************************************************************
	
	public function setExistingTaskData($data) {
	
		echo "<script>																																	";
		echo "	var shiftManagementControllerComponent	 = document.getElementById('shiftManagementController');																";
		echo "	shiftManagementControllerComponent.taskData 	 = [];																								";
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
			echo "	shiftManagementControllerComponent.taskData[taskRow['taskID']] = taskRow;																		";
			echo "</script>																																";
	
		}
	}
	
	//********************************************************************************************************************************************************************
	//**  PUBLIC FUNCTION SETEXISTINGPROJECTDATA($DATA)
	//**  Creates project-data for shiftoverview.
	//********************************************************************************************************************************************************************
	
	public function setExistingProjectData($data) {
	
		echo "<script>																																	";
		echo "	var shiftManagementControllerComponent	 = document.getElementById('shiftManagementController');										";
		echo "	shiftManagementControllerComponent.projectData 	 = [];																					";
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
			echo "	shiftManagementControllerComponent.projectData[projectRow['projectID']] = projectRow;												";
			echo "</script>																																";
	
		}
	}
}

?>