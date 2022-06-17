<?php


class DatabaseController extends AbstractController {


	
	public function getCSSFiles() {
		return array();
	}
	
	
	public function getJSFiles() {
		return array();
	}

	
	public function getTemplate($action) {
		return 'minimal';
	}
	
	
	public function indexAction() {
		//$this->showcomparedatabasesAction();
		$this->registry->template->show('system/error','unknown');
	}


	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	

	
	
	/**
	 * Tätä pystytään kutsumaan json.php-tiedostosta. Tietoturvaongelma
	 * 
	 */	
	public function gettablesAction() {
	
		global $mysqli;
		
		//echo "<br>database - " . $_SESSION['database'];
		
		if (isset($_GET['moduleid'])) {
			$moduleID = $_GET['moduleid'];
			$localtables = Table::load('system_tables', ' WHERE ModuleID=' .$moduleID . '');
		} else {
			$localtables = Table::load("system_tables");
		}

		$tables = array();
		if ($localtables == null) {
			echo json_encode($tables);
			return;
		}
		foreach ($localtables as $index => $table) {
			$tableID = $table->tableID;
			$arri = array();
			$arri['name'] = $table->name;
			$arri['tableID'] = $table->tableID;
			$arri['logtable'] = $table->logtable;
			$arri['systemspecific'] = $table->systemspecific;
				
			$name = $table->name;
			$tables[$tableID] = $arri;
		}
		echo json_encode($tables);
	}
	
	

	public function getcolumnsAction() {
	
		global $mysqli;
		
		if (isset($_GET['tableid'])) {
			$tableID = $_GET['tableid'];
			$localcolumns = Table::load('system_columns', ' WHERE TableID=' .$tableID);
		} else {
			$localcolumns = Table::load("system_columns");
		}
		$tables = Table::load("system_tables");
		
		$columns = array();
		foreach ($localcolumns as $index => $column) {
			$columnID = $column->columnID;
			
			$arri = array();
			$arri['columnID'] = $column->columnID;
			$arri['tableID'] = $column->tableID;
			$arri['variablename'] = $column->variablename;
			$arri['columnname'] = $column->columnname;
			$arri['name'] = $column->name;
			$arri['type'] = $column->type;
			$arri['obligatory'] = $column->obligatory;
			
			if ($column->referencetableID != 0) {
				$table = $tables[$column->referencetableID];
				$arri['referencetablename'] = $table->name;
			} else {
				$arri['referencetablename'] = "";
			}
			//$arri['referencetableID'] = $column->referencetableID;
			$arri['editable'] = $column->editable;
			$arri['min'] = $column->min;
			if ($column->max == null) {
				$arri['max'] = '';
			} else {
				$arri['max'] = $column->max;
			}
			$arri['defaultvalue'] = $column->defaultvalue;
			$arri['tablevisibility'] = $column->tablevisibility;
			$arri['sectionvisibility'] = $column->sectionvisibility;
			$arri['removeID'] = $column->removeID;
			$arri['logvalue'] = $column->logvalue;
			$arri['sortorder'] = $column->sortorder;
				
			$columns[$columnID] = $arri;
		}
	
		echo json_encode($columns);
	}
	
	
	
	public function getresourcetextsAction() {
	
		global $mysqli;
	
		$texts = Table::load("system_resourcetext");
	
		$strings = array();
		$multiblecheckarray = array();
		foreach ($texts as $index => $text) {
			$stringID = $text->stringID;
			$arri = array();
			
			$key = $text->moduleID . "-" . $text->resourcekey;
			if (isset($multiblecheckarray[$key])) {
				//echo "<br>Double key - " . $text->stringID . " - " . $text->value . " - " . $text->moduleID;				
				//exit;
			} else {
				$multiblecheckarray[$key] = 1;
			}
			
			
			$arri['moduleID'] = $text->moduleID;
			$arri['value'] = $text->value;
			$arri['resourcekey'] = $text->resourcekey;
			$strings[$stringID] = $arri;
		}
		echo json_encode($strings);
	}
	
	
	
	
	public function getmodulesAction() {
	
		global $mysqli;
		
		
		$localmodules = Table::load("system_modules", "WHERE Available=1");
		
		$modules = array();
		foreach ($localmodules as $index => $module) {
			$moduleID = $module->moduleID;
			$arri = array();
			$arri['moduleID'] = $module->moduleID;
			$arri['name'] = $module->name;
			$arri['modulename'] = $module->modulename;
			$arri['active'] = $module->active;
			$arri['available'] = $module->available;
			$arri['moduletype'] = $module->moduletype;
			$arri['defaultlog'] = $module->defaultlog;
			$modules[$moduleID] = $arri;
		}
		echo json_encode($modules);
	}
	
}

?>
