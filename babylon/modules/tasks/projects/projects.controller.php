<?php


// [15.10.2021] Kopioitu projects/projects.controller.php


class ProjectsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
	
	public function indexAction() {
		//$this->showprojectsAction();
		$this->registry->template->show('system/error','unknown');
	}	
	

	public function showprojectsAction() {
		
		updateActionPath("Projektit");
		$allprojectsaccess = $this->registry->module->hasAccess(TasksModule::ACCESSRIGHTKEY_TASKMANAGEMENT);
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
			
		$this->registry->template->show('tasks/projects','projects');
	}
	

	public function showprojectAction() {
	
		$projectID = $_GET['id'];
		updateActionPath("Projekti-" . $projectID);
		$this->registry->project = Table::loadRow("tasks_projects",$projectID);
		//$this->registry->tasktypes = Table::load("tasks_tasktypes");
		$this->registry->users = Table::load("system_users");
		$this->registry->projectmembers = Table::load("tasks_members", " WHERE ProjectID=" . $projectID);
		$this->registry->priorities = Table::load("tasks_priorities", " WHERE ProjectID=" . $projectID);
		$this->registry->labels = Table::load("tasks_labels", " WHERE ProjectID=" . $projectID);
		$this->registry->states = Table::load("tasks_states", " WHERE ProjectID=" . $projectID);
		$this->registry->transitions = Table::load("tasks_transitions", " WHERE ProjectID=" . $projectID);
		$this->registry->boards = Table::load("tasks_boards", "WHERE ProjectID=" . $projectID);
		$this->registry->colors = Table::load("system_colors");
		$this->registry->boardcolumns = Table::loadWhereInArray("tasks_boardcolumns", "boardID", $this->registry->boards);
		$this->registry->statecolumnmappings = Table::loadWhereInArray("tasks_statecolumnmapping", "boardID", $this->registry->boards);
		
		$statetypes = array();
		$statetype = new Row();
		$statetype->typeID = 0;
		$statetype->name = "Perustila";
		$statetypes[$statetype->typeID] = $statetype;
		
		$statetype = new Row();
		$statetype->typeID = 1;
		$statetype->name = "Backlog";
		$statetypes[$statetype->typeID] = $statetype;
		
		$statetype = new Row();
		$statetype->typeID = 2;
		$statetype->name = "Arkisto";
		$statetypes[$statetype->typeID] = $statetype;
		$this->registry->statetypes = $statetypes;
		
		
		$members = array();
		if ($this->registry->projectmembers != null) {
			foreach($this->registry->projectmembers as $index => $member) {
				$user = $this->registry->users[$member->userID];
				$user->name = $user->firstname  . " " . $user->lastname;
				$members[] = $user;
			}
		}
		$this->registry->members = $members;
		
		$this->registry->template->show('tasks/projects','project');
	}
	
	
	
	public function insertprojectAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::addRow("tasks_projects", $values, false);
	
		redirecttotal('tasks/projects/showprojects',null);
	}
	
	

	public function insertlabelAction() {
	
		$projectID = $_GET['projectID'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Short'] = $_GET['short'];
		$values['ProjectID'] = $projectID;
		$values['ColorID'] = $_GET['colorID'];
		$success = Table::addRow("tasks_labels", $values, false);
	
		redirecttotal('tasks/projects/showproject&id=' . $projectID);
	}
	
	
	public function updateboardAction() {
	
		$projectID = $_GET['projectID'];
		$boardID = $_GET['id'];
		
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::updateRow("tasks_boards", $values, $boardID, true);
		
		redirecttotal('tasks/projects/showproject&id=' . $projectID);
	}
	
	
	
	public function insertboardAction() {
	
		$values = array();
		$values['ProjectID'] = $_GET['projectID'];
		$values['Name'] = $_GET['name'];
		$success = Table::addRow("tasks_boards", $values, true);
	
		redirecttotal('tasks/projects/showproject&id=' . $_GET['projectID'],null);
	}
	
	
	
	public function insertboardcolumnAction() {
	
		$values = array();
		$values['BoardID'] = $_GET['boardID'];
		$values['Name'] = $_GET['name'];
		$success = Table::addRow("tasks_boardcolumns", $values, true);
	
		redirecttotal('tasks/projects/showproject&id=' . $_GET['projectID'],null);
	}
	
	

	public function insertstateAction() {
	
		$values = array();
		$values['ProjectID'] = $_GET['projectID'];
		$values['Name'] = $_GET['name'];
		$values['Short'] = $_GET['name'];
		
		$values['Startstate'] = 0;
		$values['Completedstate'] = 0;
		$values['Cancelledstate'] = 0;
		$values['Backlogstate'] = 0;
				
		$success = Table::addRow("tasks_states", $values, true);
	
		redirecttotal('tasks/projects/showproject&id=' . $_GET['projectID'],null);
	}
	
	
	public function updatelabelAction() {
	
		$projectID = $_GET['projectID'];
		$labelID = $_GET['id'];
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Short'] = $_GET['short'];
		$values['ColorID'] = $_GET['colorID'];
		
		$color = Table::loadRow("system_colors",$_GET['colorID']);
		$values['Colorcode'] = $color->normal;
		
		$success = Table::updateRow("tasks_labels", $values, $labelID, true);
		redirecttotal('tasks/projects/showproject&id=' . $projectID);
	}
	

	public function updatestateAction() {
	
		$projectID = $_GET['projectID'];
		$stateID = $_GET['id'];
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Short'] = $_GET['name'];
		$values['Startstate'] = $_GET['startstate'];
		$values['Backlogstate'] = $_GET['backlogstate'];
		$values['Completedstate'] = $_GET['completedstate'];
		$values['Cancelledstate'] = $_GET['cancelledstate'];
		$success = Table::updateRow("tasks_states", $values, $stateID, false);
	
		redirecttotal('tasks/projects/showproject&id=' . $projectID);
	}
	
	

	public function inserttransitionAction() {
	
		$values = array();
		$values['ProjectID'] = $_GET['projectID'];
		$values['StartstateID'] = $_GET['startstateID'];
		$values['TargetstateID'] = $_GET['targetstateID'];
		$values['Name'] = $_GET['name'];
		$success = Table::addRow("tasks_transitions", $values);
	
		redirecttotal('tasks/projects/showproject&id=' . $_GET['projectID'],null);
	}
	
	
	
	public function updateboardcolumnAction() {
	
		$projectID = $_GET['projectID'];
		$boardcolumnID = $_GET['id'];
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::updateRow("tasks_boardcolumns", $values, $boardcolumnID, true);
	
		redirecttotal('tasks/projects/showproject&id=' . $projectID);
	}
	
	

	public function updatetransitionAction() {
	
		$projectID = $_GET['projectID'];
		$transitionID = $_GET['id'];
		
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['StartstateID'] = $_GET['startstateID'];
		$values['TargetstateID'] = $_GET['targetstateID'];
		$success = Table::updateRow("tasks_transitions", $values, $transitionID);
	
		redirecttotal('tasks/projects/showproject&id=' . $projectID);
	}
	
	
	
	public function insertstatemappingAction() {
	
		$values = array();
		$values['BoardID'] = $_GET['boardID'];
		$values['BoardcolumnID'] = $_GET['boardcolumnID'];
		$values['StateID'] = $_GET['stateID'];
		
		$success = Table::addRow("tasks_statecolumnmapping", $values, true);
	
		redirecttotal('tasks/projects/showproject&id=' . $_GET['projectID'],null);
	}
	
	

	public function updatestatecolumnmappingsAction() {
	
		$projectID = $_GET['projectID'];
		$rowID = $_GET['id'];
		
		$values = array();
		$values['BoardID'] = $_GET['boardID'];
		$values['BoardcolumnID'] = $_GET['boardcolumnID'];
		$values['StateID'] = $_GET['stateID'];
		$success = Table::updateRow("tasks_statecolumnmapping", $values, $rowID);
	
		redirecttotal('tasks/projects/showproject&id=' . $projectID);
	}
	
	
	
	public function removeboardAction() {
		
		
	}
	

	public function removelabelAction() {
	
		// TODO: toteuta, jos label on käytössä, ei poisteta. Ehkä pitää asettaa vain inaktiiviseksi.
	
	}
	
	
	public function updateprojectAction() {
	
		$id = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Prefix'] = $_GET['prefix'];
		$success = Table::updateRow("tasks_projects", $values, $id);
	
		redirecttotal('tasks/projects/showproject&id=' . $id);
	}
	
	
	

	// no rights implemented
	/*
	public function inserttasktypeAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::addRow("project_tasktypes", $values, true);
		redirecttotal('tasks/projects/showproject&id=' . $projectID);
	}
	*/
	
	
	
	// no rights implemented
	/*
	public function updatetasktypeAction() {
	
		$id = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::updateRow("project_tasktypes", $values, $id);
			
		redirecttotal('tasks/projects/showproject&id=' . $projectID);
	}
	*/
	
	
	// no rights implemented
	/*
	public function deletetasktypeAction() {
	
		$id = $_GET['id'];
		$success = Table::deleteRow("project_tasktypes", $id);
	
		echo "<br>delete success - " . $success;
		//echo "<br>name - " . $values['Name'];
	
		redirecttotal('tasks/projects/showproject&id=' . $projectID);
	}
	*/
	
	
	// no rights implemented
	public function insertmemberAction() {
	
		$projectID = $_GET['ProjectID'];
		$userID = $_GET['UserID'];
	
		$rowID = Table::loadRow('tasks_members', "WHERE UserID='" . $userID . "' AND ProjectID='" . $projectID . "'");
		if ($rowID == null) {
			$values = array();
			$values['ProjectID'] = $projectID;
			$values['UserID'] = $userID;
			$userID = Table::addRow("tasks_members",$values, false, true);
			echo "<br>foradd - " . $rowID;
		}
		redirecttotal('tasks/tasks/showproject&id=' . $projectID, null);
	}
	
	

	public function insertpriorityAction() {
	
		$projectID = $_GET['projectID'];
		
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['ProjectID'] = $projectID;
		$values['ColorID'] = $_GET['colorID'];
		$success = Table::addRow("tasks_priorities", $values, true);
	
		redirecttotal('tasks/projects/showproject&id=' . $projectID,null);
	}
	
	
	public function updatepriorityAction() {
	
		$id = $_GET['id'];
		$projectID = $_GET['projectID'];
		
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['ColorID'] = $_GET['colorID'];
		$success = Table::updateRow("tasks_priorities", $values, $id);
	
		//echo "<br>id - " . $id;
		//echo "<br>name - " . $values['Name'];
	
		redirecttotal('tasks/projects/showproject&id=' . $projectID);
	}
	
	
}
