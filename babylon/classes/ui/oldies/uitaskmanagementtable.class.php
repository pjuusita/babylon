<?php


/**
 *  20.2.2020 Voitaneen poistaa, korvattu tasks states
 * 
 * 
 */
class UITaskManagementTable {
	
	public static $UITaskManagementTableCount = 0;
	
	private $process;
	private $stages;
	private $tasks;
	private $updateStageAction;
	private $updateSortOrderAction;
	private $getFullTaskDataAction;
	private $processID;
	private $filterData=null;
	
	public function __construct($stages,$tasks,$updateStageAction,$updateSortOrderAction,$getFullTaskDataAction,$processID) {
		
		$this->stages				 = $stages;
		$this->tasks				 = $tasks;
		$this->updateStageAction 	 = getUrl($updateStageAction);
		$this->updateSortOrderAction = getUrl($updateSortOrderAction);
		$this->getFullTaskDataAction = getUrl($getFullTaskDataAction);
		$this->processID			 = $processID;
		
		self::$UITaskManagementTableCount++;
		
		$this->id					 = 'taskManagementTable-'.self::$UITaskManagementTableCount;
		
	}
	
	private function createTaskManagementTableHeader() {
		
		$stages			= $this->stages;
	
		echo "<tr class='taskTableHeader'>";
		
		foreach($stages as $index => $stage) {
			
			echo "<th align=center>";
			echo "".$stage->name;
			echo "</th>";
			
		}
		
		echo "</tr>";
		
	}
	
	private function createTaskManagementTasks() {
		
		$stages			= $this->stages;
		$tasks			= $this->tasks;
		
		echo "<tr align=center valign=top>";
		
		foreach($stages as $stageIndex => $stage) {
				
			echo "<td id='stage-".$stage->stageID."' align=center valign=top width=250px class='taskArea'>";
				
			foreach($tasks as $taskIndex=>$task) {
				
				if ($task->stageID == $stage->stageID) $this->createTask($task,$task->stageID);
			}
			
			echo "</td>";
				
		}
				
		echo "</tr>";
		
	}
	
	private function createTask($task,$stageID) {
		
		$taskCardID = "taskCard-".$task->taskID;
		
		$taskID 		= $task->taskID;
		$stageID		= $task->stageID;
		$sortOrder		= $task->sortorder;
		$assignedTo 	= $task->assignedToName;
		$priority		= $task->priorityName;
		$taskName		= $task->name;
		$priorityImage 	= $task->priorityImage;
		$assignedImage 	= $task->assignedImage;
			
		echo "<script>smallTaskCard('".$taskCardID."','".$taskID."','".$stageID."','".$sortOrder."','".$assignedTo."','".$priority."','".$stageID."','".$taskName."','".$assignedImage."','".$priorityImage."');</script>";
		
	}
	
	public function setFilterData($filterData,$datavar,$textvar,$title,$filterAction) {
		
		 $this->filterData 		= $filterData;
		 $this->filterDatavar 	= $datavar;
		 $this->filterTextvar 	= $textvar;
		 $this->filterTitle		= $title;
		 $this->filterAction	= getUrl($filterAction)."&id=".$this->processID;
		 
	}
	
	public function createFilterSelection() {

		$filterData 	= $this->filterData;
		$filterDatavar 	= $this->filterDatavar;
		$filterTextvar 	= $this->filterTextvar;
		$filterTitle	= $this->filterTitle;
		$filterAction	= $this->filterAction;		
		
		echo "<div width='100%' align='right'>";
		
		echo $filterTitle."&nbsp&nbsp<select onchange='redirectFromFilterSelection(this,\"".$filterDatavar."\",\"".$filterAction."\")'>";

		echo "<option value=''>Aloita haku</option>";
		echo "<option value=''>Kaikki</option>";
		
		foreach($filterData as $index => $item) {
			
			echo "<option value=".$item->$filterDatavar.">".$item->$filterTextvar."</option>";
			
		}
		
		echo "</select>";
		
		echo "</div>";
		
	}
		
	public function show() {
		
		$tableID 				= "taskManagementTable-".$this->id;
		$stageUpdateAction 		= $this->updateStageAction;
		$sortOrderUpdateAction	= $this->updateSortOrderAction;
		$getFullTaskDataAction	= $this->getFullTaskDataAction;
		$processID				= $this->processID;
		$filterData				= $this->filterData;
		
		echo "<div>";
		
		if ($filterData!=null) $this->createFilterSelection();
		
		echo "<table id='".$tableID."' class='taskTable'>";
		
		echo "<script>setTaskManagementActions('".$tableID."','".$stageUpdateAction."','".$sortOrderUpdateAction."','".$getFullTaskDataAction."','".$processID."');</script>";
		
		$this->createTaskManagementTableHeader();
		$this->createTaskManagementTasks();
		
		echo "</table>";
		echo "</div>";
		
		//echo "<script>createTaskCardFunctionality();</script>";
		echo "<script>createTaskDropZoneFunctionality();</script>";
		
	}
	
	
}



?>