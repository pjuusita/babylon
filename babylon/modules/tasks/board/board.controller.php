<?php

// [15.10.2021] Kopioitu projects/tasks.controller.php


class BoardController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
	


	public function indexAction() {
		$this->registry->template->show('system/error','unknown');
	}
	
	
	public function showboardAction() {
	
		$viewID = getModuleSessionVar("viewID",1);
		if ($viewID == 1) {
			$this->backlogAction();
		}
		if ($viewID == 2) {
			$this->showtaskboardAction();
		}
		if ($viewID == 3) {
			$this->searchAction();
		}
		if ($viewID == 4) {
			$this->archiveAction();
		}
	}
	
	
	
	

	public function showtaskboardAction() {
	
		updateActionPath('Taskboard');
		
		$this->registry->viewID = getModuleSessionVar("viewID",1);
		
		$projectID = getSessionVar('projectID', 0);
		$selectedBoardID = getModuleSessionVar('boardID',0);
		$this->registry->labelID = getModuleSessionVar('labelID', 0);
		
		
		if (isset($_GET['boardID'])) {	// Jos boardID vaihtuu, nollataan labelID
			$this->registry->labelID = 0;
			setSessionVar('labelID',0);
		}
	
		$this->registry->boards = Table::load("tasks_boards");
		if ($selectedBoardID == 0) {
			foreach($this->registry->boards as $index => $boardloop) {
				$board = $boardloop;
			}
		} else {
			$board = $this->registry->boards[$selectedBoardID];
		}
	
		$this->registry->boardID = $board->boardID;
		$this->registry->projectID = $board->projectID;;
		$this->registry->board = $board;
		$this->registry->projects = Table::load("tasks_projects");
		$this->registry->states = Table::load("tasks_states", " WHERE ProjectID=" . $board->projectID);
		$this->registry->priorities = Table::load("tasks_priorities", " WHERE ProjectID=" . $board->projectID);
		$this->registry->colors = Table::load("system_colors");
		$startstates = array();
		foreach($this->registry->states as $index => $state) {
			if ($state->startstate == 1) $startstates[$state->stateID] = $state;
		}
		$this->registry->startstates = $startstates;
	
	
	
		$this->registry->statecolumnmapping = Table::load("tasks_statecolumnmapping", " WHERE BoardID=" . $board->boardID);
		$this->registry->labels = Table::load("tasks_labels", " WHERE ProjectID=" . $board->projectID);
	
	
		$mapping = array();
		foreach($this->registry->statecolumnmapping as $index => $row) {
			$mapping[$row->stateID] = $row->boardcolumnID;
		}
		$this->registry->mapping = $mapping;
		$this->registry->columns = Table::load("tasks_boardcolumns", " WHERE BoardID=" . $this->registry->board->boardID . " ORDER BY Sortorder");
		$alltasks = Table::load("tasks_tasks", " WHERE ProjectID=" . $this->registry->projectID . " ORDER BY Sortorder");
		$this->registry->tasklabels = Table::loadWhereInArray("tasks_tasklabels", "taskID", $alltasks);
	
		$selectedtasks = array();
		if ($alltasks != null) {
			foreach($alltasks as $taskID => $task) {
				$taskID = $task->taskID;
				if ($taskID < 10) $taskID = "00" . $taskID;
				elseif ($taskID < 100) $taskID = "0" . $taskID;
				$project = $this->registry->projects[$task->projectID];
				$task->itemID = $project->prefix . "-" . $taskID;
	
				$selected = false;
				$state = $this->registry->states[$task->stateID];
	
				if ($this->registry->labelID != 0) {
					if ($this->registry->labelID == -1) {
						$labelcounter = 0;
						foreach($this->registry->tasklabels as $index => $tasklabel) {
							if ($tasklabel->taskID == $task->taskID) {
								$labelcounter++;
							}
						}
						if ($labelcounter == 0) $selected = true;
					} else {
						foreach($this->registry->tasklabels as $index => $tasklabel) {
							if ($tasklabel->taskID == $task->taskID) {
								if ($tasklabel->labelID == $this->registry->labelID) $selected = true;
							}
						}
					}
				} else {
					$selected = true;
				}
				if ($selected == true) $selectedtasks[$task->taskID] = $task;
			}
		}
		$this->registry->tasks = $selectedtasks;
	
		/*
			$this->registry->tasks = Table::load("tasks_tasks", " WHERE ProjectID=" . $board->projectID);
			if ($this->registry->tasks != null) {
			foreach($this->registry->tasks as $taskID => $task) {
			$taskID = $task->taskID;
			if ($taskID < 10) $taskID = "00" . $taskID;
			elseif ($taskID < 100) $taskID = "0" . $taskID;
			$project = $this->registry->projects[$task->projectID];
			$task->itemID = $project->prefix . "-" . $taskID;
	
			}
			} else {
			$this->registry->tasks = array();
			}
			*/
	
	
	
		/*
			$allprojectsaccess = $this->registry->module->hasAccess(TasksModule::ACCESS_VIEWALLPROJECTS);
			if ($allprojectsaccess == true) {
			$projects = Table::load("tasks_projects");
			$this->registry->projects = $projects;
			} else {
			$ownprojects = Table::load("tasks_members", " WHERE UserID=" . $_SESSION['userID']);
			$selectedprojects = array();
			foreach($ownprojects as $index => $projectlink) {
			$selectedprojects[$projectlink->projectID] = $projectlink->projectID;
			}
			$projects = Table::loadWhereInArray("tasks_projects", "projectID", $selectedprojects);
			$this->registry->projects = $projects;
			}
				
			$this->registry->projectID = getSessionVar('projectID', 0);
			if (($this->registry->projectID == 0) || !isset($this->registry->projects[$this->registry->projectID])) {
			foreach($this->registry->projects as $index => $project) {
			$this->registry->projectID = $project->projectID;
			setSessionVar('projectID', $project->projectID);
			}
			}
			$this->registry->project = $this->registry->projects[$this->registry->projectID];
	
	
			$this->registry->tasks = Table::load("tasks_tasks", " WHERE ProjectID=" . $this->registry->projectID);
			if ($this->registry->tasks != null) {
			foreach($this->registry->tasks as $taskID => $task) {
			$taskID = $task->taskID;
			if ($taskID < 10) $taskID = "00" . $taskID;
			elseif ($taskID < 100) $taskID = "0" . $taskID;
			$task->itemID = $this->registry->project->prefix . "-" . $taskID;
			}
			} else {
			$this->registry->tasks = array();
			}
			*/
	
		$this->registry->template->show('tasks/board','taskboard');
	
	}
	
	
	
	
	public function backlogAction() {
	
		$this->registry->viewID = getModuleSessionVar("viewID",1);
		
		$this->registry->projectID = getSessionVar('projectID', 0);
		$this->registry->labelID = getSessionVar('labelID', 0);
		
		if (isset($_GET['projectID'])) {	// Jos projectID vaihtuu, nollataan labelID ja stateID
			$this->registry->labelID = 0;
			setSessionVar('labelID',0);
		}
		
		$projects = Table::load("tasks_projects");
		$this->registry->projects = $projects;
		
		if (($this->registry->projectID == 0) || !isset($this->registry->projects[$this->registry->projectID])) {
			foreach($this->registry->projects as $index => $project) {
				$this->registry->projectID = $project->projectID;
				setSessionVar('projectID', $project->projectID);
				break;
			}
		}
		$this->registry->project = $this->registry->projects[$this->registry->projectID];
		$this->registry->states = Table::load("tasks_states", " WHERE ProjectID=" . $this->registry->projectID);
		
		$labels = Table::load("tasks_labels", " WHERE ProjectID=" . $this->registry->projectID);
		$labeliton = new Row();
		$labeliton->name = "Labeliton";
		$labeliton->labelID = -1;
		$labels[-1] = $labeliton;
		$this->registry->labels = $labels;
		$startstates = array();
		foreach($this->registry->states as $index => $state) {
			if ($state->startstate == 1) $startstates[$state->stateID] = $state;
		}
		$this->registry->startstates = $startstates;
		
		

		$alltasks = Table::load("tasks_tasks", " WHERE ProjectID=" . $this->registry->projectID);
		$this->registry->tasklabels = Table::loadWhereInArray("tasks_tasklabels", "taskID", $alltasks);
		
		$selectedtasks = array();
		if ($alltasks != null) {
			foreach($alltasks as $taskID => $task) {
				$taskID = $task->taskID;
				if ($taskID < 10) $taskID = "00" . $taskID;
				elseif ($taskID < 100) $taskID = "0" . $taskID;
				$task->itemID = $this->registry->project->prefix . "-" . $taskID;
		

				$selected = false;
				$state = $this->registry->states[$task->stateID];
				if (($state->backlogstate == 1)) {
					//echo "<br>Backlogstate - " . $task->stateID . " - " . $task->name;
					if ($this->registry->labelID != 0) {
						if ($this->registry->labelID == -1) {
							$labelcounter = 0;
							foreach($this->registry->tasklabels as $index => $tasklabel) {
								if ($tasklabel->taskID == $task->taskID) {
									$labelcounter++;
								}
							}
							if ($labelcounter == 0) $selected = true;
						} else {
							foreach($this->registry->tasklabels as $index => $tasklabel) {
								if ($tasklabel->taskID == $task->taskID) {
									if ($tasklabel->labelID == $this->registry->labelID) $selected = true;
								}
							}
						}
					} else {
						$selected = true;
					}
				}
				if ($selected == true) $selectedtasks[$task->taskID] = $task;
			}
		}
		$this->registry->tasks = $selectedtasks;
		
		$this->registry->template->show('tasks/board','backlog');
	}
	
	
	
	

	public function searchAction() {
	
		$this->registry->viewID = getModuleSessionVar("viewID",1);
		
		$this->registry->projectID = getSessionVar('projectID', 0);
		$this->registry->stateID = getSessionVar('stateID', 0);
		$this->registry->labelID = getSessionVar('labelID', 0);
		
		if (isset($_GET['projectID'])) {	// Jos projectID vaihtuu, nollataan labelID ja stateID
			$this->registry->labelID = 0;
			setSessionVar('labelID',0);
			$this->registry->stateID = 0;
			setSessionVar('stateID',0);
		}
		
		$projects = Table::load("tasks_projects");
		$this->registry->projects = $projects;
	
		if (($this->registry->projectID == 0) || !isset($this->registry->projects[$this->registry->projectID])) {
			foreach($this->registry->projects as $index => $project) {
				$this->registry->projectID = $project->projectID;
				setSessionVar('projectID', $project->projectID);
				break;
			}
		}
		$this->registry->project = $this->registry->projects[$this->registry->projectID];
		$labels = Table::load("tasks_labels", " WHERE ProjectID=" . $this->registry->projectID);
		$labeliton = new Row();
		$labeliton->name = "Labeliton";
		$labeliton->labelID = -1;
		$labels[-1] = $labeliton;
		$this->registry->labels = $labels;
		$this->registry->states = Table::load("tasks_states", " WHERE ProjectID=" . $this->registry->projectID);
		$startstates = array();
		foreach($this->registry->states as $index => $state) {
			if ($state->startstate == 1) $startstates[$state->stateID] = $state;
		}
		$this->registry->startstates = $startstates;
		
		$alltasks = Table::load("tasks_tasks", " WHERE ProjectID=" . $this->registry->projectID);
		$this->registry->tasklabels = Table::loadWhereInArray("tasks_tasklabels", "taskID", $alltasks);
		
		$selectedtasks = array();
		if ($alltasks != null) {
			foreach($alltasks as $taskID => $task) {
				$taskID = $task->taskID;
				if ($taskID < 10) $taskID = "00" . $taskID;
				elseif ($taskID < 100) $taskID = "0" . $taskID;
				$task->itemID = $this->registry->project->prefix . "-" . $taskID;

				$selected = false;
				if (($this->registry->stateID == 0) && ($this->registry->labelID == 0)) $selected = true;
				if ($this->registry->stateID != 0) {
					if ($task->stateID == $this->registry->stateID) $selected = true;					
				}
				if ($this->registry->labelID != 0) {
					if ($this->registry->labelID == -1) {
						$labelcounter = 0;
						foreach($this->registry->tasklabels as $index => $tasklabel) {
							if ($tasklabel->taskID == $task->taskID) {
								$labelcounter++;
							}
						}
						if ($labelcounter == 0) $selected = true;
					} else {
						foreach($this->registry->tasklabels as $index => $tasklabel) {
							if ($tasklabel->taskID == $task->taskID) {
								if ($tasklabel->labelID == $this->registry->labelID) $selected = true;
							}
						}
					}
				}
				if (($this->registry->stateID != 0) && ($this->registry->labelID != 0)) {
					$selected == true;
					if ($task->stateID != $this->registry->stateID) $selected = false;
					foreach($this->registry->tasklabels as $index => $tasklabel) {
						if ($tasklabel->taskID == $task->taskID) {
							if ($tasklabel->labelID != $this->registry->labelID) $selected = false;
						}
					}
				}
					
				if ($selected == true) $selectedtasks[$task->taskID] = $task;
			}
		} else {
			$this->registry->tasks = array();
		}
		$this->registry->tasks = $selectedtasks;
		
		
		$this->registry->template->show('tasks/board','search');
	}
	
	

	public function archiveAction() {
	
		$this->registry->viewID = getModuleSessionVar("viewID",1);
	
		$this->registry->projectID = getSessionVar('projectID', 0);
		$this->registry->labelID = getSessionVar('labelID', 0);
	
		if (isset($_GET['projectID'])) {	// Jos projectID vaihtuu, nollataan labelID
			$this->registry->labelID = 0;
			setSessionVar('labelID',0);
		}
	
		$projects = Table::load("tasks_projects");
		$this->registry->projects = $projects;
	
		if (($this->registry->projectID == 0) || !isset($this->registry->projects[$this->registry->projectID])) {
			foreach($this->registry->projects as $index => $project) {
				$this->registry->projectID = $project->projectID;
				setSessionVar('projectID', $project->projectID);
				break;
			}
		}
		$this->registry->project = $this->registry->projects[$this->registry->projectID];
		$this->registry->states = Table::load("tasks_states", " WHERE ProjectID=" . $this->registry->projectID);
		$this->registry->labels = Table::load("tasks_labels", " WHERE ProjectID=" . $this->registry->projectID);
	
		$alltasks = Table::load("tasks_tasks", " WHERE ProjectID=" . $this->registry->projectID);
		$this->registry->tasklabels = Table::loadWhereInArray("tasks_tasklabels", "taskID", $alltasks);
	
		$selectedtasks = array();
		if ($alltasks != null) {
			foreach($alltasks as $taskID => $task) {
				$taskID = $task->taskID;
				if ($taskID < 10) $taskID = "00" . $taskID;
				elseif ($taskID < 100) $taskID = "0" . $taskID;
				$task->itemID = $this->registry->project->prefix . "-" . $taskID;
	
	
				$selected = false;
				$state = $this->registry->states[$task->stateID];
				if (($state->completedstate == 1) || ($state->cancelledstate == 1)) {
					//echo "<br>Backlogstate - " . $task->stateID . " - " . $task->name;
					if ($this->registry->labelID != 0) {
						if ($this->registry->labelID == -1) {
							$labelcounter = 0;
							foreach($this->registry->tasklabels as $index => $tasklabel) {
								if ($tasklabel->taskID == $task->taskID) {
									$labelcounter++;
								}
							}
							if ($labelcounter == 0) $selected = true;
						} else {
							foreach($this->registry->tasklabels as $index => $tasklabel) {
								if ($tasklabel->taskID == $task->taskID) {
									if ($tasklabel->labelID == $this->registry->labelID) $selected = true;
								}
							}
						}
					} else {
						$selected = true;
					}
				}
				if ($selected == true) $selectedtasks[$task->taskID] = $task;
			}
		}
		$this->registry->tasks = $selectedtasks;
	
		$this->registry->template->show('tasks/board','archive');
	}
	
	
}
