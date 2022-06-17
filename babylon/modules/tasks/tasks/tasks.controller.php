<?php

// [15.10.2021] Kopioitu projects/tasks.controller.php


class TasksController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//updateActionPath("index");		// Tämän pitäisi oikeastaan tulla menun actionista...
		//$this->showtasksAction();
		$this->registry->template->show('system/error','unknown');
	}	
	

	
	
	/**
	 * Näyttää kaikki taskit projekteissa, joissa itse on jäsenenä
	 * 
	 */
	public function showtasksAction() {

		updateActionPath('Tehtävälista');
		
		
		$projectID = getModuleSessionVar('projectID', 0);
		
		$this->registry->projects = Table::load("tasks_projects");
		if (($projectID == 0) || !isset($this->registry->projects[$projectID])) {
			echo "<br>Not projectID setted";
			foreach($this->registry->projects as $index => $project) {
				$projectID = $project->projectID;
				setModuleSessionVar('projectID',$projectID);
				break;
			}	
		}
		$this->registry->projectID = getSessionVar('projectID', 0);
		$this->registry->project = $this->registry->projects[$projectID];
		
		$this->registry->projectID = $projectID;
		
		$this->registry->stateID = getModuleSessionVar('stateID',0);		
		$this->registry->labelID = getModuleSessionVar('labelID',0);
		
		if (isset($_GET['projectID'])) {
			$this->registry->stateID = 0;
			setModuleSessionVar('stateID',0);
			$this->registry->labelID = 0;
			setModuleSessionVar('labelID',0);
		}
		
		$this->registry->users = Table::load("system_users");
		$this->registry->states = Table::load("tasks_states", "WHERE ProjectID=" . $projectID);
		$this->registry->labels = Table::load("tasks_labels", "WHERE ProjectID=" . $projectID);
		$this->registry->priorities = Table::load("tasks_priorities", " WHERE ProjectID=" . $projectID);
		$this->registry->colors = Table::load("system_colors");
		
		
		
		/*
		$allprojectsaccess = $this->registry->module->hasAccess(TasksModule::ACCESSRIGHTKEY_ADMIN);
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
		 
		$this->registry->users = Table::load("system_users");
		*/
		
		if ($projectID > 0) {
			$where = "WHERE ProjectID=" . $projectID;
			if ($this->registry->stateID > 0) $where = $where . " AND StateID=" . $this->registry->stateID . " ORDER BY Sortorder";
			$tasks = Table::load("tasks_tasks", $where);
			$tasklist = array();
			foreach($tasks as $taskID => $task) {
				$taskID = $task->taskID;
				if ($taskID < 10) $taskID = "00" . $taskID;
				elseif ($taskID < 100) $taskID = "0" . $taskID;
				$task->itemID = $this->registry->project->prefix . "-" . $taskID;
				$tasklist[$task->taskID] = $task->taskID;
			}
			if ($this->registry->labelID > 0) {
				$tasklabels = Table::loadWhereInArray("tasks_tasklabels","TaskID", $tasklist, "WHERE SystemID=" . $_SESSION['systemID'] . " AND ProjectID=" . $projectID);
				$filteredtasks = array();
				$finallabels = array();
				foreach($tasklabels as $index => $tasklabel) {
					if (isset($tasks[$tasklabel->taskID])) {
						if ($tasklabel->labelID == $this->registry->labelID) {
							$filteredtasks[$tasklabel->taskID] = $tasks[$tasklabel->taskID];
							$finallabels[$tasklabel->rowID] = $tasklabel;
						}
					}
				}
				$this->registry->tasklabels = $tasklabels;
				$this->registry->tasks = $filteredtasks;
			} else {
				$this->registry->tasklabels = Table::loadWhereInArray("tasks_tasklabels","TaskID", $tasks, "WHERE SystemID=" . $_SESSION['systemID'] . " AND ProjectID=" . $projectID);
				$this->registry->tasks = $tasks;
			}
		} else {
			$this->registry->tasklabels = array();
			$this->registry->tasks = array();
		}
		
		
		$this->registry->template->show('tasks/tasks','tasks');
	}
	
	
	

	private function loadTasks($projectID, $stateID, $labelID, $sort = "") {
		
		if ($projectID == 0) return array();
		$where = "WHERE ProjectID=" . $projectID;
		if ($stateID > 0) $where = $where . " AND StateID=" . $stateID;
		$tasks = Table::load("tasks_tasks", $where . " " . $sort);
		$tasklist = array();
		foreach($tasks as $taskID => $task) {
			$tasklist[$task->taskID] = $task->taskID;
		}
		if ($labelID > 0) {
			$tasklabels = Table::loadWhereInArray("tasks_tasklabels","TaskID", $tasklist, "WHERE SystemID=" . $_SESSION['systemID'] . " AND ProjectID=" . $projectID);
			$filteredtasks = array();
			$finallabels = array();
			foreach($tasklabels as $index => $tasklabel) {
				if (isset($tasks[$tasklabel->taskID])) {
					if ($tasklabel->labelID == $labelID) {
						$filteredtasks[$tasklabel->taskID] = $tasks[$tasklabel->taskID];
					}
				}
			}
			return $filteredtasks;
		} else {
			return $tasks;
		}
	}
	
	

	/**
	 * Tähän pitäisi olla tarkistus, onko kyseinen taski omassa omistuksessa.
	 * Mikäli on admin oikeudet, niin sitten voidaan näyttää taski, muuten heitetään
	 * ei oikeuksia varoitus.
	 *
	 */
	public function showtaskAction() {
		
		$this->registry->source = getModuleSessionVar('tasksource',0);
		$taskID = $_GET['id'];
		updateActionPath("Tehtävä-" . $taskID);
	
		$this->registry->task = Table::loadRow("tasks_tasks", $taskID);
		$this->registry->projects = Table::load("tasks_projects");
		//$this->registry->workflows = Table::load("tasks_workflow");
		//$this->registry->tasktypes = Table::load("project_tasktypes");
		$this->registry->states = Table::load("tasks_states", " WHERE ProjectID=" . $this->registry->task->projectID);
		$this->registry->priorities = Table::load("tasks_priorities", " WHERE ProjectID=" . $this->registry->task->projectID);
		$this->registry->minitasks = Table::load("tasks_minitasks", " WHERE TaskID=" . $this->registry->task->taskID);
		$this->registry->labels = Table::load("tasks_labels", " WHERE ProjectID=" . $this->registry->task->projectID);
		$this->registry->tasklabels = Table::load("tasks_tasklabels", " WHERE TaskID = " . $this->registry->task->taskID);
		$this->registry->transitions = Table::load("tasks_transitions", " WHERE ProjectID=" . $this->registry->task->projectID . " AND StartstateID=" . $this->registry->task->stateID);
		$this->registry->log = Table::load("tasks_transitionlog", " WHERE TaskID=" . $this->registry->task->taskID);
	
		$this->registry->action = null;
		if ($this->registry->task->actionID > 0) {
			$this->registry->action = Table::loadRow("system_actionpaths", $this->registry->task->actionID);
		}
	
		//echo "<br>Ministasks - " . count($this->registry->minitasks);
		//foreach($this->registry->minitasks as $index => $minitask) {
		//	echo "<br> -- " . $minitask->minitaskID;
		//}
	
		$this->registry->users = Table::load("system_users");
		foreach($this->registry->users as $index => $user) {
			$user->name = $user->firstname . " " . $user->lastname;
		}
	
		if ($this->registry->task->generatorID > 0) {
			//echo "<br>generatorID - " . $this->registry->task->generatorID;
			$this->registry->generator = Table::loadRow("tasks_generators", $this->registry->task->generatorID);
		}
	
	
		$this->registry->template->show('tasks/tasks','task');
	}
	
	

	public function gettaskheaderJSONAction() {
		
		//echo "<br>Jeejee";
		$comments = false;
		
		$url = $_GET['url'];
		$id = $_GET['id'];
		$taskcounter = 0;		
		$tasklist = array();
		if (isset($_SESSION['AC_' . $url])) {
			$tablelist = explode(",", $_SESSION['AC_' . $url]);
			foreach($tablelist as $index => $tableID) {
				$searchID = $_GET['id'];
				$minitasks = Table::load("tasks_minitasks", "WHERE TargettableID=" . $tableID . " AND TargetID=" . $searchID .  " AND (State=0 OR State=1)", $comments);
		
				foreach($minitasks as $taskID => $minitask) {
					$tasklist[$minitask->minitaskID] = $minitask;					
				}
			}
		}
		//echo "<br>Tasklist found - " . count($tasklist);
		
		echo " { \"tasks\": [ ";
		$first = true;
		foreach($tasklist as $index => $minitask) {
			if ($first == true) {
				$first = false;
			} else {
				echo ",";
			}
			echo " { \"name\":\"" . $minitask->name . "\", ";
			echo "	\"minitaskID\":\"" . $minitask->minitaskID . "\", ";
			echo "	\"state\":\"" . $minitask->state . "\" } ";
		}
		echo " ] } ";
	}
		
	
	/**
	 * Tätä actionia kutsutaan taskbarilta, tämä lataa koko sivun uudelleen (eli redirectaan) ensin
	 * kuitenkin haetaan asianomaista minitaskia seuraava undone task. Oman järjestysnumeronsa jälkeen.
	 * Jos jälkeen ei löydy, niin aloitetaan alusta. Tämä järjestys hommeli siksi, että muuten kelattaisiin
	 * läpi vain kahta päällimmäistä tekemätöntä taskia.
	 * 
	 */
	public function nexttaskAction() {
		
		$minitaskID = $_GET['minitaskID'];
		$comments = false;
		
		$minitask = Table::loadRow('tasks_minitasks', $minitaskID, $comments);
		$minitasks = Table::load('tasks_minitasks', "WHERE TaskID=" . $minitask->taskID, $comments);
		
		$currentfound = false;
		$nexttask = null;
		foreach($minitasks as $index => $temp) {
			//echo "<br>Minitask aa - " . $temp->minitaskID;
			if ($currentfound == false) {
				if ($temp->minitaskID == $minitask->minitaskID) {
					$currentfound = true;
				}
			} else {
				if ($temp->state == 0) {
					$nexttask = $temp;					
				}
			}
			if ($nexttask != null) break;
		}
		
		if ($nexttask == null) {
			foreach($minitasks as $index => $temp) {
				if ($temp->minitaskID != $minitask->minitaskID) {
					if ($temp->state == 0) {
						$nexttask = $temp;
					}
				}
				if ($nexttask != null) break;
			}
		}
				
		if ($nexttask == null) {
			addMessage('Kaikki subtaskit suoritettu');
			//echo "<br><a href='"  .fdsfsfd . "'>link</a>";
			//echo "<br><a href='"  .getUrl('tasks/tasks/showtask'). "&id=" . $nexttask->targetID . "'>link</a>";
			redirecttotal("tasks/tasks/showtask&id=" . $minitask->taskID,null);
		} else {
			$action = Table::loadRow("system_actionpaths", $nexttask->actionID);
			//echo "<br><a href='"  .getUrl($action->actionpath). "&id=" . $nexttask->targetID . "'>link</a>";
			redirecttotal($action->actionpath . "&id=" . $nexttask->targetID,null);
		}
	}
	
	
	
	public function backlogAction() {
	
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
		
		$this->registry->template->show('tasks/tasks','backlog');
	}
	
	
	
	public function archiveAction() {
	
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
		
		$this->registry->template->show('tasks/tasks','archive');
	}
	
	
	

	public function searchAction() {
	
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
		
		
		$this->registry->template->show('tasks/tasks','search');
	}
	
	
	
	/*
	public function taskboardAction() {
	
		$selectedBoardID = getModuleSessionVar('boardID',0);
		$this->registry->labelID = getSessionVar('labelID', 0);
		
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
		$this->registry->projectID = $board->projectID;
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
		
		
		
		//$this->registry->statecolumnmapping = Table::load("tasks_statecolumnmapping", " WHERE BoardID=" . $board->boardID);
		$this->registry->labels = Table::load("tasks_labels", " WHERE ProjectID=" . $board->projectID);
		
		
		$mapping = array();
		foreach($this->registry->statecolumnmapping as $index => $row) {
			$mapping[$row->stateID] = $row->boardcolumnID;
		}
		$this->registry->mapping = $mapping;
		$this->registry->columns = Table::load("tasks_boardcolumns", " WHERE BoardID=" . $this->registry->board->boardID . " ORDER BY Sortorder");
		$alltasks = Table::load("tasks_tasks", " WHERE ProjectID=" . $this->registry->projectID);
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
		
		
		$this->registry->template->show('tasks/tasks','taskboard');
		
	}
	*/
	
	
	
	public function transitionAction() {
		
		$taskID = $_GET['id'];
		$targetstateID = $_GET['target'];
		//$tasktypeID = $_GET['tasktypeID'];
		
		//$tasktype = Table::loadRow("tasks_tasktypes", $tasktypeID);
		$targetstate = Table::loadRow("tasks_states", $targetstateID);
		
		$this->registry->task = Table::loadRow("tasks_tasks", $taskID);
		
		
		echo "<br>Tasktype - " . $tasktypeID;
		echo "<br>Tasktype - " . $tasktype->tablename;
		echo "<br>Action - " . $tasktype->action;
		

		$values = array();
		//$values['TasktypeID'] = $tasktypeID;
		$values['TaskID'] = $taskID;
		$values['UserID'] = $_SESSION['userID'];
		$values['Timestamp'] = date("Y-m-d H:i:s");
		$values['Description'] = "Siirretty tilaan - " . $targetstate->name ."";
		$logID = Table::addRow("tasks_transitionlog", $values, true);
		
		
		$values = array();
		$values['StateID'] = $targetstateID;
		$success = Table::updateRow("tasks_tasks", $values, $taskID, true);
		
		echo "<br>" . $tasktype->action . "&id=" . $taskID;
		
		// Mahdollisesti pitää päivittää system_actions-taulu, jos siirto tapahtuu loppu- tai peruuta-tilaan
				
		redirecttotal("tasks/tasks/showtask&id=" . $taskID,null);
	}
	
	
	

	public function removelabelAction() {
	
		$taskID = $_GET['taskID'];
		$rowID = $_GET['id'];
		$success = Table::deleteRow('tasks_tasklabels',$rowID);
	
		redirecttotal('tasks/tasks/showtask&id=' . $taskID ,null);
	}
	

	public function insertlabelAction() {
	
		$values = array();
		$values['TaskID'] = $_GET['taskID'];
		$values['LabelID'] =  $_GET['labelID'];
		$values['ProjectID'] =  $_GET['projectID'];
		$rowID = Table::addRow("tasks_tasklabels", $values, false);
		
		$label = Table::loadRow("tasks_labels", $_GET['labelID']);
		
		// logi merkintä
		
		$values = array();
		//$values['TasktypeID'] = $tasktypeID;
		$values['TaskID'] = $_GET['taskID'];
		$values['UserID'] = $_SESSION['userID'];
		$values['Timestamp'] = date("Y-m-d H:i:s");
		if ($label != null) {
			$values['Description'] = "Label added - " . $label->name;
		} else {
			$values['Description'] = "Label added";
		}
		$logID = Table::addRow("tasks_transitionlog", $values, false);
		
		
		redirecttotal('tasks/tasks/showtask&id=' . $_GET['taskID'] ,null);
	}
	
	
	public function insertminitaskAction() {
	
		$values = array();
		$values['TaskID'] = $_GET['taskID'];
		$values['Name'] =  $_GET['name'];
		$values['Checked'] =  0;
		$taskID = Table::addRow("tasks_minitasks", $values, false);
		redirecttotal('tasks/tasks/showtask&id=' . $_GET['taskID'] ,null);
	}
	
	
	

	public function completeminitaskAction() {
	
		$taskID = $_GET['taskID'];
		$rowID = $_GET['id'];
		$values = array();
		$values['State'] = 1;
		$values['Endtime'] = date('Y-m-d H:i:s');
		$success = Table::updateRow("tasks_minitasks", $values, $rowID);
		redirecttotal('tasks/tasks/showtask&id=' . $taskID);
	}
	
	
	
	public function checkminitaskJSONAction() {
	
		$minitaskID = $_GET['minitaskID'];
		$values = array();
		$values['State'] = 1;
		$values['Endtime'] = date('Y-m-d H:i:s');
		$success = Table::updateRow("tasks_minitasks", $values, $minitaskID);
		
		echo "[{\"success\":\"1\", \"test\":\"jeee\" }]";
		//redirecttotal('tasks/tasks/showtask&id=' . $taskID);
	}
	

	public function removeminitaskAction() {
	
		$taskID = $_GET['taskID'];
		$rowID = $_GET['id'];
		$success = Table::deleteRow('tasks_minitasks',$rowID);
	
		// TODO logitus ehkä... transitionlogiin...
		// TODO pitäisi periaatteessa päivittää myös system_actions
		
		redirecttotal('tasks/tasks/showtask&id=' . $taskID ,null);
	}
	
	
	
	public function inserttaskAction() {
		
		$projectID = $_GET['projectID'];
		$comments = false;
		
		// TODO: target staten asetus puuttuu
		
		$stateID = 0;
		$state = null;
		if (isset($_GET['stateID'])) {
			$stateID = $_GET['stateID'];
		} else {
			$states = Table::load("tasks_states", "WHERE ProjectID=" . $projectID . " AND Startstate=1", $comments);
			foreach($states as $index => $tempstate) {
				$stateID = $tempstate->stateID;
			}
		}
		
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['ProjectID'] =  $projectID;
		$values['StateID'] =  $stateID;
		//$values['TasktypeID'] =  $tasktypeID;	
		if (isset($_GET['userID'])) {
			$values['UserID'] = $_SESSION['userID'];
		}
		if (isset($_GET['priorityID'])) {
			$values['PriorityID'] = $_GET['priorityID'];
		}
		
		$taskID = Table::addRow("tasks_tasks", $values, $comments);

		if (isset($_GET['labelID'])) {
			$labelID =  $_GET['labelID'];
			if ($labelID != 0) {
				$values = array();
				$values['TaskID'] = $taskID;
				$values['LabelID'] =  $_GET['labelID'];
				$values['ProjectID'] = $projectID;
				$labelID = Table::addRow("tasks_tasklabels", $values, $comments);
			}
		}
		
		// Transition logiin oma rivi taskin luonnista
		//$targetstate = Table::loadRow("tasks_states", $_GET['stateID']);
		$values = array();
		//$values['TasktypeID'] = $tasktypeID;
		$values['TaskID'] = $taskID;
		$values['UserID'] = $_SESSION['userID'];
		$values['Timestamp'] = date("Y-m-d H:i:s");
		if ($state != null) {
			$values['Description'] = "Created - " . $state->name;
		} else {
			$values['Description'] = "Created";
		}
		$logID = Table::addRow("tasks_transitionlog", $values, $comments);
		
		if ($logID > 0) {
			redirecttotal('tasks/tasks/showtask&id=' . $taskID,null);
		}
	}
	
	
	public function updatetaskAction() {
		
		
		$taskID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['ProjectID'] = $_GET['projectID'];
		$values['StateID'] = $_GET['stateID'];
		$values['PriorityID'] = $_GET['priorityID'];
		//$values['TasktypeID'] = $_GET['tasktypeID'];
		$values['Description'] = $_GET['description'];
		$success = Table::updateRow("tasks_tasks", $values, $taskID);
		redirecttotal('tasks/tasks/showtask&id=' . $taskID);
		
		// Actionin muutos pitää muokata system_actions taulua...
	}
	
	
	

	public function updateminitaskAction() {
	
		$rowID = $_GET['id'];
		$taskID = $_GET['taskID'];
		
		$values = array();
		$values['Name'] = $_GET['name'];
		
		$success = Table::updateRow("tasks_minitasks", $values, $rowID);
		redirecttotal('tasks/tasks/showtask&id=' . $taskID);
	}
	
	
	

	/**
	 * TODO: tämä pitäisi olla ainoastaan adminin käytössä, lähinnä testausta varten tarvitaan
	 * poisto. Normaalissa tapauksessa riittäisi käyttää peruuta nappulaa, joka piilottaa kaikilta
	 * listoilta. Ehkä nämä on syytä jättää johonkin arkistoon, koska näissä saattaa olla jotain
	 * tuntikirjauksia yms. viitteitä jotka on hyvä jättää...
	 *
	 */
	public function removetaskAction() {
	
		$comments = false;
		$taskID = $_GET['id'];
		echo "<br>Poista task - " . $taskID;
		
		$success = Table::deleteRowsWhere('tasks_tasklabels',"WHERE TaskID=" . $taskID,$comments);
		$success = Table::deleteRowsWhere('tasks_transitionlog',"WHERE TaskID=" . $taskID,$comments);
		$success = Table::deleteRowsWhere('tasks_minitasks',"WHERE TaskID=" . $taskID,$comments);
		$success = Table::deleteRow('tasks_tasks',$taskID,$comments);

		if (!$comments) redirecttotal('tasks/tasks/showtasks', null);
		
		/*
		$comments = false;
		$generatorID = $_GET['generatorID'];
		$success = Table::deleteRow('tasks_generatorlabels',$rowID);
	
	
	
		$values = array();
		$values['Tasklabels'] = $str;
		$success = Table::updateRow("tasks_generators", $values, $generatorID, $comments);
	
	
		// TODO: 	updateSystemActions - Päivitetään systems_actions-taulun tablelist, jotta säästetään
		//			turhilta tsekkailuilta...
		*/
		//redirecttotal('tasks/generators/showgenerator&id=' . $generatorID ,null);
	}
	
	
	// TODO: tällä funktiolla on tarkoitus ladata tasks-section elementeille.
	//		 sisältö ladataan aina on demand, eli ko. section on aina closed.
	//		 tämän lataaminen sitten suoraan latauksessa onkin sitten kinkkisempi,
	//		 jos sitä ei sitten hardkoodata suoraan...
	public function gettasksectioncontentAction() {
		
	}
	
	
	/**
	 * Drag-and-Drop on suoritettu alaspäin, eli previous id-numero löytyy...
	 * 
	 */
	public function tasklistdragdropdownAction() {
	
		$comments = true;
	
		if ($comments) echo "<br>tasklistdragdropdownAction";
	
		$projectID = $_GET['projectID'];
		$stateID = $_GET['stateID'];
		$labelID = $_GET['labelID'];
		
		$currentID = $_GET['currentID'];
		$previousID = $_GET['previousID'];

		if ($comments) echo "<br>currentID - " . $currentID;
		if ($comments) echo "<br>previousID - " . $previousID;
		
		$tasks = $this->loadTasks($projectID, $stateID, $labelID, "ORDER BY Sortorder");
	
		$previousfound = null;
		$currentfound = null;
		$changelist = array();		// id, sortID
		$previous = null;
		$firstnext = null;
		$end = false;
	
		if ($comments) {
			echo "<br>Taskcount - " . count($tasks);
			foreach($tasks as $index => $task) {
				echo "<br>Tasks - " . $index . " - " . $task->sortorder;
			}
		}
		
		
		// Voidaan olettaa, että current pitää siirttää previoussin alapuolelle.
		foreach($tasks as $index => $task) {
				
			if ($comments) echo "<br>Processing task - " . $task->taskID;
	
			if ($currentfound != null) {
				if ($comments) echo "<br> -- taskID is found";
				if ($firstnext == null) {
					if ($task->taskID == $previousID) {		// peräkkäiset
						if ($comments) echo "<br> -- -- taskfound AA";
						$changelist[$task->taskID] = $currentfound->sortorder;
						$changelist[$currentfound->taskID] = $task->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- lessonfound BB";
						$firstnext = $task;
						$changelist[$task->taskID] = $currentfound->sortorder;
					}
				} else {
					if ($task->taskID == $previousID) {
						if ($comments) echo "<br> -- -- lessonfound CC";
						$changelist[$task->taskID] = $previous->sortorder;
						$changelist[$currentfound->taskID] = $task->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- lessonfound DD";
						$changelist[$task->taskID] = $previous->sortorder;
					}
				}
			}
	
			if ($previousfound != null) {
				if ($comments) echo "<br> -- previous is found";
				if ($firstnext == null) {
					if ($task->taskID == $currentID) {		// peräkkäiset
						if ($comments) echo "<br> -- -- previousfound EE";
						$changelist[$task->taskID] = $previousfound->sortorder;
						$changelist[$previousfound->taskID] = $task->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- previousfound FF --> " . $previous->taskID . " = " . $task->sortorder;
						$firstnext = $task;
						$changelist[$previous->taskID] = $task->sortorder;
					}
				} else {
					if ($task->taskID == $currentID) {		// siirrettävä löytynyt
						if ($comments) echo "<br> -- -- previousfound GG --> " . $previousfound->taskID . " = " . $task->sortorder;
						$changelist[$previous->taskID] = $task->sortorder;
						//$changelist[$previousfound->taskID] = $firstnext->sortorder;
						if ($comments) echo "<br> -- -- previousfound GG --> " . $currentID . " = " . $previousfound->sortorder;
						$changelist[$task->taskID] = $previousfound->sortorder;
						$end = true;
					} else {
						if ($comments) echo "<br> -- -- previousfound HH";
						$changelist[$task->taskID] = $previous->sortorder;
					}
				}
			}
	
				
			if (($previousfound == null) && ($currentfound == null)) {
				if ($comments) echo "<br> -- none yet found...";
				if ($task->taskID == $currentID) {
					$currentfound = $task;
					if ($comments) echo "<br> -- lessonfound start - " . $task->taskID;
				}
				if ($task->taskID == $previousID) {
					$previousfound = $task;
					if ($comments) echo "<br> -- previousfound start - " . $task->taskID;
				}
			}
				
			$previous = $task;
			if ($end == true) break;
		}
	
		//if ($comments) echo "<br><br>Found...";
		//foreach($changelist as $currentID => $sortorder) {
		//	if ($comments) echo "<br>" . $currentID . " -- " . $sortorder;
		//}
	
		if ($end == true) {			// sekä taskID, että previousID löytynyt, päivitetään
			if ($comments) echo "<br>End true, update";
				
			foreach($changelist as $taskID => $sortorder) {
				if ($comments) echo "<br>" . $taskID . " -- " . $sortorder;
				$values = array();
				$values['Sortorder'] = $sortorder;
				$success = Table::updateRow("tasks_tasks", $values, $taskID, $comments);
			}
		} else {
			if ($comments) echo "<br>Endi on false";
		}
		if ($comments) echo "<br>Finnish";
		//if (!$comments) redirecttotal('tasks/tasks/showtasks');
	}

}
