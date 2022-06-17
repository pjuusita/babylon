<?php

/**
 * 
 * 12.10.2021	Tätä käytetään ainoastaan vuoronhallintaproto3.php, eli pahasti vanhentunut
 * 				Tämä on ilmeisesti korvattu jollain projektihallinnan toiminnoilla, ehkä states
 *
 */


class UIProcessTable {
	
	private $stages  = null;
	
	public function __construct($title) {
		
		
	}
	
	public function setTasks($tasks) {
		
		$this->tasks = $tasks;
		
	}
	
	public function setStages($stages) {
		
		$this->stages = $stages;
		
	}
	
	private function createTableHeader() {
		
		$stages = $this->stages;
		
		echo "<tr>";
		
		foreach($stages as $index => $stage) {
			
			$stageID = 'stage'.$stage->stageID;
			echo "<th id='".$stageID."'>".$stage->name."</th>";
		}
		
		echo "</tr>";
	
	}
	
	private function createTasks() {
		
		$tasks 	= $this->tasks;
		$stages = $this->stages;
		
		echo "<tr valign='top'>";
		
		foreach($stages as $stageIndex => $stage) {	
		
			echo "<td>";
			
			foreach($tasks as $taskIndex => $task) {
			
				if ($task->stageID == $stage->stageID) {
					
					$this->createTaskDiv($task);
					
				}
					
			}	
			
			echo "</td>";
		}
		
		echo "</tr>";

	} 
	
	private function createTaskDiv($task) {
		
		echo "<div style='float : left ; background-color : lightgray ; border-style : solid; border-color : black; border-radius : 5px; max-width : 300px; min-width : 300px; min-height : 125px'>";
		
		echo "<table>";
		
		echo "<tr>";
		
		echo "<td>";
		echo "".$task->type;
		echo "</td>";
		
		echo "<td>";
		echo "".$task->name;
		echo "<br>".$task->assigned;
		
		echo "</td>";
		
		echo "</tr>";
		
		echo "<tr>";
		
		echo "<td>";
		echo "".$task->priority;
		echo "</td>";
		
		echo "<td>";
		
		echo "".$task->stage;
		echo "<br>".$task->state;
		
		echo "</td>";
		
		echo "</tr>";
		
		echo "</table>";
		echo "</div>";
		
	}
	
	public function show() {
		
		echo "<table>";
		$this->createTableHeader();
		$this->createTasks();
		echo "</table>";
		
	}
	
}
?>