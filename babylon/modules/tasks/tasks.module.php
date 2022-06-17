<?php


class TasksModule extends AbstractModule {
	
	
	const ACCESSRIGHTKEY_TASKVISIBILITY = 'tasks_accesskey_taskvisibility';
	const ACCESSRIGHTKEY_TASKGENERATOR = 'tasks_accesskey_generators';
	const ACCESSRIGHTKEY_TASKMANAGEMENT = 'tasks_accesskey_taskmanagement';
	const ACCESSRIGHTKEY_ADMIN = 'tasks_accesskey_admin';
	
	// muutosoikeudet kaikkiin systeemin projekteihin, tämä on yleensä vain system adminilla
	const ACCESSKEY_MANAGEALLPROJECTS = 100;	

	// näkyvyys kaikkiin projekteihin, mikäli tätä ei ole, niin näkyvissä on vain omat projektit...
	const ACCESSKEY_VIEWALLPROJECTS = 101;		
	
	
	const MENUKEY_TASKS = 'menukey_tasks';
	
	
	public function getDefaultName() {
		return "Tehtävät";
	}
	
	

	public function getAccessRights() {
	
		$accessrights = array();
		$accessrights[TasksModule::ACCESSRIGHTKEY_TASKVISIBILITY] = AbstractModule::ACCESSKEYTYPE_READWRITE;;
		$accessrights[TasksModule::ACCESSRIGHTKEY_TASKGENERATOR] = AbstractModule::ACCESSKEYTYPE_READWRITE;;
		$accessrights[TasksModule::ACCESSRIGHTKEY_TASKMANAGEMENT] = AbstractModule::ACCESSKEYTYPE_READWRITE;;
		$accessrights[TasksModule::ACCESSRIGHTKEY_ADMIN] = AbstractModule::ACCESSKEYTYPE_READWRITE;;
		
		return $accessrights;
	}
	
	
	
	public function getMenu($userrights) {
		
		$menuitems = array();
		$menuitems[] = new Menu("Tehtävät","tasks/tasks","showtasks",Menu::MENUKEY_TOP,TasksModule::MENUKEY_TASKS,593);
		$menuitems[] = new Menu("Boards","tasks/board","showtaskboard",TasksModule::MENUKEY_TASKS,null,596);
		$menuitems[] = new Menu("Tasks","tasks/tasks","showtasks",TasksModule::MENUKEY_TASKS,null,597);
		$menuitems[] = new Menu("Projects","tasks/projects","showprojects",TasksModule::MENUKEY_TASKS,null,598);
		$menuitems[] = new Menu("Task Generator","tasks/generators","showgenerators",TasksModule::MENUKEY_TASKS,null,599);
		return $menuitems;
	}
	

	
	public function hasAccessRight($action) {
		
		return true;
		
		
		switch($action) {
			case "generators/showgenerators":
				return true;
				break;
			case "tasks/showtasks":
				return true;
				break;
			default:
				if ($comments) echo "<br>Project index access default";
				return false;	// routerille tiedoksi, että actionia ei löytyny, pitää logittaa virhe
				break;
		}
		return false;
	}
	
	
	
	public function hasAccess($accesskey) {

		return true;
		
		switch($accesskey) {
			case TasksModule::ACCESSKEY_MANAGEALLPROJECTS:
				$accesslevel = getAccessLevel(TasksModule::ACCESSRIGHTKEY_TASKVISIBILITY);
				if ($accesslevel == AbstractModule::ACCESSRIGHT_READ) return true;
				if ($accesslevel == AbstractModule::ACCESSRIGHT_ALL) return true;
				break;
			default:
				if ($comments) echo "<br>Project accesskey default";
				return false;	// routerille tiedoksi, että accesskey ei löytyny, pitää logittaa virhe
				break;
		}
		
		return false;
	}
	
	
	
}


?>