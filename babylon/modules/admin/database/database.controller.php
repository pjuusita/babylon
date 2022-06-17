<?php


class DatabaseController extends AbstractController {


	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css', 'section.css');
		//return array('menu.css','testcss.php');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','yritys.css','petestyle.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}


	public function indexAction() {
		//$this->showdatabasetablesAction();
		$this->registry->template->show('system/error','unknown');
	}


	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	

	public function showdatabasetablesAction () {

		$this->registry->moduleID = getSessionVar('moduleID', 0);
		$this->registry->tables = Table::load('system_tables', ' WHERE ModuleID=' . $this->registry->moduleID . " ORDER BY Name");
		$this->registry->modules = Table::load('system_modules');
		$this->registry->template->show('admin/database','databasetables');
	}

	

	public function scriptsAction () {
	
		$this->registry->moduleID = getSessionVar('moduleID', 0);
		if (isset($_GET['id'])) {
			$this->registry->moduleID = $_GET['id'];
			setSessionVar('moduleID',$_GET['id']);
		}
		
		if ($this->registry->moduleID > 0) {

			//echo "<br>ModuleID - " . $this->registry->moduleID;
			$this->registry->module = Table::loadRow('system_modules', $this->registry->moduleID);
			
			$foundfiles = array();
			$dir = SITE_PATH  . 'scripts/' . strtolower($this->registry->module->modulename);
			//echo "<br>Dir - "  .$dir;
			$files = scandir($dir);
			//echo "<br>Dir - " . $dir;
			
			
			if ($files != null) {
				//echo "<br>Linesnull";
				
				foreach($files as $index => $value) {
					//echo "<br>file - " . $value;
					if (($value == '.') || ($value == 'index.php') || ($value == '..')) {
						//echo "<br>" . $value;
					} else {
						//echo "<br>foundfile - " . $value;
						//$foundfiles[] = $value;
							
						$row = new Row();
						$row->name = $value;
							
						$foundfiles[] = $row;
							
						//echo "<br><a href='" . $value.  "'>" . $value . "</a>";
					}
				}
			}
			
			
			$this->registry->files = $foundfiles;
			
		} else {
			$this->registry->modules = Table::load('system_modules');
		}
		
		$this->registry->template->show('admin/database','scripts');
	}
	
	
	public function executescriptAction() {
		
		$this->registry->script = $_GET['id'];
		$this->registry->moduleID = $_GET['moduleID'];
		$this->registry->module = Table::loadRow('system_modules', $this->registry->moduleID);
		
		$this->registry->path = SITE_PATH  . 'scripts' . DIRECTORY_SEPARATOR . strtolower($this->registry->module->modulename) . DIRECTORY_SEPARATOR . $this->registry->script;
			
		$this->registry->template->show('admin/database','executescript');
		
		
	}
	
	
	public function showtablecolumnAction () {
		$columnID = $_GET['id'];
		$this->registry->column = Column::load($columnID);
		$this->registry->table = Table::loadRow("system_tables", $this->registry->column->tableID);
		
		
		$this->registry->tables = Table::load('system_tables');
		//$this->registry->tables = Table::load('system_tables', ' WHERE ModuleID=' . $this->registry->table->moduleID . " ORDER BY Name");
		
		$this->registry->columnstypes = Column::getColumnTypes();
		//$this->registry->tables = Table::load('system_tables', ' WHERE ModuleID=' . $this->registry->moduleID . " ORDER BY Name");
		$this->registry->obligatoryvalues = array ( '0' => 'Ei pakollinen' , '1' => 'Pakollinen', '2' => 'Pakollinen lisattaessa' );
		$this->registry->template->show('admin/database','tablecolumn');
	}
	
	
	
	public function showdatabasetableAction () {
		$tableID = $_GET['id'];
		$this->registry->temptables = Table::load('system_tables');
		$this->registry->tables = array();
		foreach($this->registry->temptables as $index => $table) {
			$tables[$table->tableID] = $table->name;
		}
		$this->registry->tables = $tables;
		$this->registry->table = Table::getTableWithID($tableID);
		$this->registry->columntypes = Column::getColumnTypeClasses();
		$this->registry->obligatoryvalues = array ( '0' => 'Ei pakollinen' , '1' => 'Pakollinen', '2' => 'Pakollinen lisattaessa' );
		
		$this->registry->template->show('admin/database','databasetable');
	}
	
	
	
	
	
	public function updatetableAction() {
		
		$comments = false;
		
		$tableID = $_GET['id'];
		
		$table = Table::getTableWithID($tableID);
		$errormessage = true;
				
		$name = $_GET['Name'];
		if ($table->name != $name) {
			$success = $table->renameTable($name);
			if ($success != true) {
				$errormessage = $success;
			}
		}
		
		
		$values = array();
		$values['Description'] = $_GET['description'];
		$success = Table::updateRow("system_tables", $values, $tableID, $comments);

		
		if (!$comments) redirecttotal('admin/database/showdatabasetable&id=' . $tableID);
	
		/*
		if ($errormessage == true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"" . $errormessage . "\"}]";
		}
		*/
	}
	

	public function showinserttableAction () {
		$this->registry->modules = Table::load('system_modules');
		$this->registry->template->show('admin/database','inserttable');
	}
	
	

	public function inserttableAction () {
		
		$tablename = $_GET['Name'];
		$idfieldname = $_GET['IDFieldname'];
		$moduleID = $_GET['moduleID'];
		
		$errormessage = "Tuntematon virhe";
		$accept = true;
		if ($tablename == '') {
			$errormessage = "Nimi saa olla tyhja";
			$errorfield = "Name";
			$accept = false;
		}
		if ($idfieldname == '') {
			$errormessage = "Ei saa olla tyhja";
			$errorfield = "IDFieldname";
			$accept = false;
		}
		
		if (Table::exists($tablename)) {
			echo "[{\"success\":\"" . $errormessage . "\" , \"errormessage\":\"Varattu nimi\", \"errorfield\":\"0\"}]";
			exit;
		} else {
			AddMessage("Tietokantataulu Lisätty onnistuneesti");
			$module = Table::loadRow("system_modules", $moduleID);
			$tableID = Table::createTable($tablename, $idfieldname, $moduleID, $module->defaultlog,1);
			
			
			echo "<br>Table created - " . $tableID;
			/*
			if ($success == 1) {
				echo "[{\"success\":\"true\"}]";
			} else {
				echo "[{\"success\":\"" . $success . "\"}]";
			}
			*/
			
			redirecttotal('admin/database/showdatabasetable&id=' . $tableID);
			
		}
	}
	

	
	

	public function showinsertcolumnAction () {

		$this->registry->tableID = $_GET['tableid'];
		$columnstypes = Column::getColumnTypes();
		
		
		$this->registry->temptables = Table::loadTables(false, "ORDER BY Name");
		$this->registry->tables = array();
		foreach($this->registry->temptables as $index => $table) {
			$tables[$table->tableID] = $table->name;
		}
		$this->registry->tables = $tables;
		
		
		//if ($this->registry->columnstypes == null) echo "<br>Nullii";
		//echo "<br>Nullii - " . get_class($this->registry->columnstypes);
		//foreach ($columnstypes as $index => $value) {
		//	echo "<br>columntypes - " . $index . "";
		//}
		
		
		
		$this->registry->columnstypes = $columnstypes;
		$obligatoryvalues = array ( '0' => 'Ei pakollinen' , '1' => 'Pakollinen', '2' => 'Pakollinen lisattaessa' );
		$this->registry->obligatoryvalues = $obligatoryvalues;
		
		$this->registry->table = Table::getTableWithID($_GET['tableid']);
		$this->registry->template->show('admin/database','insertcolumn');
	}
	
	

	public function insertcolumnAction () {
	
		$tableID = $_GET['tableid'];
		$variablename = $_GET['Variablename'];
		$columnname = $_GET['Columnname'];		
		$name = $_GET['Name'];
		$type = $_GET['Type'];
		
		$obligatory = 0;
		if (isset($_GET['Obligatory'])) $obligatory = $_GET['Obligatory'];

		$referencetableID = $_GET['ReferencetableID'];
		$min = $_GET['Min'];
		$max = $_GET['Max'];
		$defaultvalue = $_GET['Defaultvalue'];
		
		$success = Table::insertColumn($tableID, $variablename,$columnname,$name,$type,$obligatory,$referencetableID,$min,$max,$defaultvalue);
		
		
		redirecttotal('admin/database/showdatabasetable&id=' . $tableID);
		
		/*
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
		*/
		// pitäisi palauttaa json success koska tämä on nykyään toteutettu jsonilla
		//return true;
	}
	
	
	public function removetableAction() {
		
		global $mysqli;
		$tableID = $_GET['id'];
		
		// Tsekataan onko tähän tauluun viitteitä mistään muusta taulusta?
		$table = Table::getTableWithID($tableID);
		$columns = $table->getColumns();
		
		$referencecolumns = Table::load("system_columns", "WHERE ReferencetableID=" . $tableID);
		
		$referencesfound = false;
		foreach($referencecolumns as $index => $column) {
			$reftable = Table::loadRow("system_tables", $column->tableID);
			echo "<br>Referencefound - " . $reftable->name . " - " . $column->name;
			$referencesfound = true;
		}
		if ($referencesfound == true) {
			echo "<br>Remove failed.";
			die();
		}

		echo "<br>No references found";
		
		// delete all columns from system_columns
		foreach($columns as $index => $column) {
			$success = Table::deleteRow("system_columns", $column->columnID);
			echo "<br>Deleting row - " . $column->name;
		}
		$success = Table::deleteRow("system_tables", $tableID);
		
		$sql = "DROP TABLE " . $table->name;
		echo "<br>SQL - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) {
			return "drop table failed - " . $mysqli->error;
		}
		
		// delete table from system_tables
		
		
		
		/*
		$success = Table::removeTable($tableID);
		
		if ($success == true) {
			addMessage("Taulu poistettu onnistuneesti");
			redirecttotal('admin/database/showdatabasetables');
			//$this->showtablesAction();
		} else {
			addErrorMessage("Tuntematon tietokantavirhe.");
			redirecttotal('admin/database/showdatabasetables');
			//$this->showtablecontentAction();
		}
		*/
	}
	
	
	public function removecolumnAction() {

		$columnID = $_GET['id'];
		$tableID =  $_GET['tableID'];
		$success = Table::removeColumn($columnID, $tableID);
		
		if ($success == true) {
			addMessage("Sarake poistettu onnistuneesti");
			redirecttotal('admin/database/showdatabasetable&id=' . $tableID);
		} else {
			addErrorMessage("Tuntematon tietokantavirhe.");
			redirecttotal('admin/database/showdatabasetables');
		}
	}
	
	
	public function updatecolumnAction() {
		
		$columnID = $_GET['id'];
		
		$values = array();
		$values['Variablename'] = $_GET['variablename'];
		$values['Columnname'] = $_GET['columnname'];
		$values['Name'] = $_GET['name'];
		$values['Type'] = $_GET['type'];
		$values['Obligatory'] = $_GET['obligatory'];
		$values['ReferencetableID'] = $_GET['referencetableID'];
		$values['Min'] = $_GET['min'];
		if ($_GET['max'] == "") {
			//echo "<br>Max is null - ";
			$values['Max'] = null;
		} else {
			//echo "<br>Max is - " . $_GET['max'];
			$values['Max'] = $_GET['max'];
		}
		$values['Defaultvalue'] = $_GET['defaultvalue'];
		$values['Tablevisibility'] = $_GET['tablevisibility'];
		$values['Sectionvisibility'] = $_GET['sectionvisibility'];
		$values['Logvalue'] = $_GET['logvalue'];
		$values['Sortorder'] = $_GET['sortorder'];
		$success = Column::update($columnID, $values);
		
		redirecttotal('admin/database/showtablecolumn&id=' . $columnID);
		
		/*
		foreach($_GET as $index => $value) {
			//						$success=$success.$index.'= '.$value.' - ';
			if (($index != 'id') && ($index != 'rt')) {
				$columns[$index]=$value;
			} elseif ($index == 'id') {
				$id=$value;
			}
		}
		//		$success=count($_GET);
		 */
		
		/*
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
		*/
		
		//
		//echo "[{\"success\":\"true\"}]";
	}
	
}

?>
