<?php


class GeneratorsController extends AbstractController {

	public static $operators = null;
	
	const OPERATOR_EQUAL = 1;
	const OPERATOR_NOT_EQUAL = 2;
	const OPERATOR_IS_EMPTY = 3;
	const OPERATOR_NOT_EMPTY = 4;
	const OPERATOR_OVERRIDE = 5;
	const OPERATOR_GREATERTHAN = 6;
	const OPERATOR_SMALLERTHAN = 7;
		
	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->showgeneratorsAction();
		$this->registry->template->show('system/error','unknown');
	}	
	

	
	/**
	 * Näyttää taskit kaikista projekteista, tämä on hallinta käyttäjän action.
	 * 
	 */
	public function showgeneratorsAction() {
		
		updateActionPath("Generators");
		$this->registry->generators = Table::load("tasks_generators");
		$this->registry->template->show('tasks/generators','generators');
	}
	
	

	public static function getGeneratorOperators() {
		if (GeneratorsController::$operators == null) {
			$operators = array();
			$operators[GeneratorsController::OPERATOR_EQUAL] = "=";
			$operators[GeneratorsController::OPERATOR_NOT_EQUAL] = "!=";
			$operators[GeneratorsController::OPERATOR_GREATERTHAN] = ">";
			$operators[GeneratorsController::OPERATOR_SMALLERTHAN] = "<";
			GeneratorsController::$operators = $operators;
		}
		return GeneratorsController::$operators;
	}

	
	
	public function showgeneratorAction() {
		
		$comments = false;
		updateActionPath("Generator");
		$generatorID = $_GET['id'];
		$generator = Table::loadRow("tasks_generators", $generatorID);
		$this->registry->generator = $generator;
		$this->registry->tables = Table::load("system_tables", "ORDER BY Name", $comments);
		$this->registry->operators = GeneratorsController::getGeneratorOperators();
		$this->registry->projects = Table::load("tasks_projects");
		$this->registry->actions = Table::load("system_actionpaths");
		$this->registry->languages = Table::load("system_languages");
		
		if ($this->registry->generator->projectID > 0) {
			// TODO: tässä pitäisi olla projektin tiimiläiset
			//$userlist = Table::load("tasks_members", "WHERE ProjectID=" . $this->registry->generator->projectID);
			//$this->registry->users = Table::loadWhereInArray("system_users", "userID", $userlist);
			$users = Table::load("system_users");
			foreach($users as $index => $user) {
				$user->fullname = $user->lastname . " " . $user->firstname;
			}
			$this->registry->users = $users;
			$this->registry->states = Table::load("tasks_states", "WHERE ProjectID=" . $this->registry->generator->projectID);
			$this->registry->priorities = Table::load("tasks_priorities", "WHERE ProjectID=" . $this->registry->generator->projectID);
		} else {
			$this->registry->users = array();
			$this->registry->states = array();
			$this->registry->priorities = array();
		}
				
		if ($this->registry->generator->basetableID > 0) {
			$this->registry->columns = Table::load("system_columns", "WHERE TableID=" . $this->registry->generator->basetableID);
		} else {
			$this->registry->columns = null;
		}
		
		if ($comments) echo "<br>generator - " . $this->registry->generator->generatorID;
		
		if ($this->registry->generator->projectID > 0) {
			$this->registry->labels = Table::load("tasks_labels", " WHERE ProjectID=" . $this->registry->generator->projectID, $comments);
		} else {
			$this->registry->labels = array();
		}
		$this->registry->generatorlabels = Table::load("tasks_generatorlabels", "WHERE GeneratorID=" . $this->registry->generator->generatorID);
		
	
		if ($this->registry->generator->basetableID > 0) {
			$tablename = $this->registry->tables[$this->registry->generator->basetableID];
			$tablestruct = Table::getTableWithID($this->registry->generator->basetableID);
			$keycolumn = $tablestruct->getKeyColumn();
			$columnvariable = $keycolumn->variablename;
			if ($comments) echo "<br> -- columnname - " . $keycolumn->columnname;
			if ($comments) echo "<br> -- columnname - " . $keycolumn->variablename;
			$where = "" . $this->registry->generator->basefilter;
			if ($this->registry->generator->completioncondition != '') {
				$where = $where . " AND " . $this->registry->generator->completioncondition;
			}
			
			$items = array();
			if ($where == "") {
				//$items = Table::load($tablename->name, null, $comments);
				$items = array();
			} else {
				if ($comments) echo "<br>Where - '" . $where . "'";
				$items = Table::load($tablename->name,"WHERE " . $where, $comments);
			}
			if ($comments) echo "<br>Items found count - " . count($items);
			
			// Tämä on aika hankala ja monimutkainen haku. Ideana tässä on, että haetaan secondary tablesta
			// ne rivit, joissa pääavain löytyy primary-tietokannansta, joka on secondary tietokannan viitteenä.
			if ($this->registry->generator->targettableID > 0) {
				
				
				$itemlist = array();
				$tablestruct = Table::getTableWithID($this->registry->generator->targettableID);
				$columns = $tablestruct->getColumns();
				$targetkeycolumn = $tablestruct->getKeyColumn();
				
				$linkcolumn = null;
				foreach($columns as $index => $column) {
					if ($column->referencetableID == $generator->basetableID) {
						$linkcolumn = $column;
						break;
					}
				}
				if ($comments) echo "<br>Linkccolumn - " . $linkcolumn->name;

				$targetlinkkolumnvar = $linkcolumn->variablename;
				$itemlist = array();
				$itemstr = "";
				$comma = "";
				if ($comments) echo "<br>var - " . $targetlinkkolumnvar;
				foreach($items as $index => $item) {
					$itemID = $item->$targetlinkkolumnvar;
					//echo "<br> - " . $index . " - " . get_class($item) . " -" . $itemID . "- " . $targetlinkkolumnvar;
					$itemlist[$item->$targetlinkkolumnvar] = 0;
					$itemstr = $itemstr . $comma . $itemID;
					if ($comma == "") $comma = ",";
				}
				
				if ($comments) echo "<br>itemstr - " . $itemstr;
				if ($comments) echo "<br>itemlist count - " . count($itemlist);
				if ($comments) echo "<br>targetableid count - " . $this->registry->generator->targettableID;
				$targettablename = $this->registry->tables[$this->registry->generator->targettableID];
				if ($targettablename == null) {
					if ($comments) echo "<br>Targettablename - " . $targettablename;
				} else {
					if ($comments) echo "<br>Targettablename - " . $targettablename->name;
				}
				//$tablestruct = Table::getTableWithID($this->registry->generator->targettableID);
				$targetkeycolumn = $tablestruct->getKeyColumn();
				if ($comments) echo "<br>Keycolumn - " . $targetkeycolumn->columnname;
				//echo "<br>tfsdfds - " . $targettablename->name;
				//$targetcolumn = 
				
				
				//$linkeditems = Table::load($targettablename->name);
				//echo "<br>Linkeditemscount - " . count($linkeditems);
				if ($comments) echo "<br>Targetfilter - " . $generator->targetfilter;
				
				$targetfilter = "";
				if ($generator->targetfilter != "") {
					$targetfilter = " AND " . $generator->targetfilter;
				}
				$linkeditems = Table::load($targettablename->name, "WHERE " . $keycolumn->columnname . " IN (" . $itemstr . ") " . $targetfilter, $comments );
				if ($comments) echo "<br>Linkeditemscount - " . count($linkeditems);
								
				if ($comments) echo "<br>var - " . $targetlinkkolumnvar;
				
				foreach($linkeditems as $index => $item) {
					//echo "<br>Linkeditems - " . $index . " - " . $item->wordform;
					$itemID = $item->$targetlinkkolumnvar;
					if (isset($itemlist[$itemID])) {
						$itemlist[$itemID] = $itemlist[$itemID]+1;
					}
				}
				if ($comments) echo "<br>itemlistcount - " . count($itemlist);
				$targetcount = $generator->targetcount;
				$newitems = array();
				if ($targetcount == 0) {
					foreach($itemlist as $itemID => $count) {
						$item = $items[$itemID];
						if ($count == 0) {
							if ($comments) echo "<br>found ... " . $itemID . " - ". $count . " - " . $item->lemma;
							$newitems[$itemID] = $items[$itemID];
						}
					}
				} else {
					foreach($itemlist as $itemID => $count) {
						$item = $items[$itemID];
						if ($count < $generator->targetcount) {
							if ($comments) echo "<br>found ... " . $itemID . " - ". $count . " - " . $item->lemma;
							$newitems[$itemID] = $items[$itemID];
						}
					}
				}
				$items = $newitems;
			} else {
				
				
			}
			
		} else {
			$items = array();
		}
		
		
		
		
		$this->registry->minitasks = Table::load("tasks_minitasks", "WHERE GeneratorID=" . $generatorID);
		$foundminitasks = array();
		foreach($this->registry->minitasks as $index => $minitask) {
			if ($minitask->targettableID == $this->registry->generator->basetableID) {
				$foundminitasks[$minitask->targetID] = $minitask->taskID;
			}
		}
		$this->registry->tasks = Table::load("tasks_tasks", "WHERE GeneratorID=" . $generatorID);
		foreach($this->registry->tasks as $index => $task) {
			if ($task->targettableID == $this->registry->generator->basetableID) {
				$foundminitasks[$task->targetID] = $task->taskID;
			}
		}
		
		foreach($items as $index => $item) {
			if (isset($foundminitasks[$item->$columnvariable])) {
				if ($comments) echo "<br> -- taskfound - " . $item->name . " - " . $foundminitasks[$item->$columnvariable];
				$item->selected = 1;
				$item->selectable = 0;
				$item->taskID = $foundminitasks[$item->$columnvariable];
				$item->taskname = "Task: " . $item->taskID;
			} else {
				$item->selected = 0;
				$item->selectable = 1;
				$item->taskID = 0;
				$item->taskname = "-";
			}
		}
		$this->registry->items = $items;
		$this->registry->template->show('tasks/generators','generator');		
	}
	
	
	
	
	/**
	 * 
	 * 
	 * 
	 */
	private static function loadCandidates($root, $tables, $columns, $baserequirements, $generator, $comments) {
		
		/*
		$root = null;
		foreach($baserequirements as $index => $requirement) {
			
			if ($requirement->parentID == 0) {
				echo "<br>Isroot - " . $requirement->requirementID . " - " . $requirement->parentID;
				$root = $requirement;
			}
			$column = $columns[$requirement->columnID];
			$operator = " x ";
			if ($requirement->operator == GeneratorsController::OPERATOR_EQUAL) $operator = " = ";
			if ($requirement->operator == GeneratorsController::OPERATOR_NOT_EQUAL) $operator = " != ";
			if ($requirement->operator == GeneratorsController::OPERATOR_IS_EMPTY) $operator = " == ''";
			if ($requirement->operator == GeneratorsController::OPERATOR_NOT_EMPTY) $operator = " != ''";
				
			$requirement->name = "" . $column->name . $operator . $requirement->value;
			if ($requirement->logicoperator == 1) {
				$requirement->name = "AND " . $column->name . $operator . $requirement->value;
			}
			if ($requirement->logicoperator == 2) {
				$requirement->name = "OR " . $column->name . $operator . $requirement->value;
			}
		}
		*/
		if ($comments) echo "<br>Root - " . $root->requirementID;
		$where = GeneratorsController::recursiveWhere($root, $columns, $comments);
		//$where = "WHERE " . substr($where, 1, strlen($where)-2);
		$where = "WHERE " . $where;
		if ($comments) echo "<br>Where --- " . $where;
		
		$tablename = $tables[$generator->basetableID];
		if ($comments) echo "<br>Tablename - " . $tablename->name;
		$items = array();
		$items = Table::load($tablename->name, $where, $comments);
		
		return $items;
	}
	
	
	public static function recursiveWhere($node, $columns, $comments) {
	
		$str = "";
		$column = $columns[$node->columnID];
		$operator = " x ";
		if ($node->operator == GeneratorsController::OPERATOR_EQUAL) $operator = "=";
		if ($node->operator == GeneratorsController::OPERATOR_NOT_EQUAL) $operator = "!=";
		//if ($node->operator == GeneratorsController::OPERATOR_IS_EMPTY) $operator = "==''";
		//if ($node->operator == GeneratorsController::OPERATOR_NOT_EMPTY) $operator = "!=''";

		$str = "" . $column->name . $operator . $node->value;
		if ($comments) echo "<br>str - " . $str;	
		$childs = $node->getChildren();
		if ($childs != null) {
			foreach($childs as $index => $child) {
				$childstr = GeneratorsController::recursiveWhere($child, $columns, $comments);
				
				if ($child->logicoperator == 1) {
					$str = "(". $str . " AND " . $childstr . ")";
				} else if ($child->logicoperator == 2) {
					$str = "(". $str . " OR " . $childstr . ")";
				} else {
					$str = "" . $childstr;
				}
			}
		}
		return $str;
	}
	
	
	public static function recursiveSetRequirementName($node, $columns, $comments) {
		
		$column = $columns[$node->columnID];
		$operator = " x ";
		if ($node->operator == GeneratorsController::OPERATOR_EQUAL) $operator = " = ";
		if ($node->operator == GeneratorsController::OPERATOR_NOT_EQUAL) $operator = " != ";
		if ($node->operator == GeneratorsController::OPERATOR_IS_EMPTY) $operator = " == ''";
		if ($node->operator == GeneratorsController::OPERATOR_NOT_EMPTY) $operator = " != ''";
		
		$node->name = "" . $column->name . $operator . $node->value;
		if ($comments) echo "<br>Nodename - " . $node->name;
		if ($node->logicoperator == 1) {
			$node->name = "AND " . $column->name . $operator . $node->value;
		} 
		if ($node->logicoperator == 2) {
			$node->name = "OR " . $column->name . $operator . $node->value;
		}
		
		$childs = $node->getChildren();
		if ($childs != null) {
			foreach($childs as $index => $child) {
				GeneratorsController::recursiveSetRequirementName($child, $columns, $comments);
			}
		}
	}
	
	
	
	public function insertgeneratorlabelAction() {
	
		$comments = false;
		$generatorID = $_GET['generatorID'];
		
		$values = array();
		$values['GeneratorID'] = $generatorID;
		$values['LabelID'] =  $_GET['labelID'];
		$rowID = Table::addRow("tasks_generatorlabels", $values, $comments);
	
		$labels = Table::load("tasks_generatorlabels", "WHERE GeneratorID=" . $generatorID);
	
		$str = null;
		foreach($labels as $index => $label) {
			if ($str == null) {
				$str = "" . $label->labelID;
			} else {
				$str = $str . ":" . $label->labelID;
			}
		}
		$values = array();
		$values['Tasklabels'] = $str;
		$success = Table::updateRow("tasks_generators", $values, $generatorID, $comments);
		
		if (!$comments) redirecttotal('tasks/generators/showgenerator&id=' .$generatorID ,null);
	}
	
	

	// TODO: koodi poistettu toistaiseksi käytöstä, ettei satu vahinkoja. Jos generaattorissa on taskeja
	// ja varsinkin suoritettuja taskeja, niin poisto ei pitäisi olla mahdollista, sen sijaan arkistointi.
	public function removegeneratorlabelAction() {
	
		/*
		$comments = false;
		$generatorID = $_GET['generatorID'];
		$rowID = $_GET['id'];
		$success = Table::deleteRow('tasks_generatorlabels',$rowID);
		
		$labels = Table::load("tasks_generatorlabels", "WHERE GeneratorID=" . $generatorID);
		$str = null;
		foreach($labels as $index => $label) {
			if ($str == null) {
				$str = "" . $label->labelID;
			} else {
				$str = $str . ":" . $label->labelID;
			}
		}
		
		$values = array();
		$values['Tasklabels'] = $str;
		$success = Table::updateRow("tasks_generators", $values, $generatorID, $comments);
		*/
		
		redirecttotal('tasks/generators/showgenerator&id=' . $generatorID ,null);
	}
	
	
	public function insertorrequirementAction() {
	
		$comments = true;
		$parentID = $_GET['parentID'];
		$generatorID = $_GET['generatorID'];
		$columnID = $_GET['columnID'];
		$operator = $_GET['operator'];
		$value = $_GET['value'];
		
		$generator = Table::loadRow("tasks_generators", $generatorID);
		
		$values = array();
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['GeneratorID'] = $generatorID;
		$values['TableID'] = $generator->basetableID;
		$values['ColumnID'] = $columnID;
		$values['Value'] = $value;
		$values['Operator'] = $operator;
		$values['Logicoperator'] = 2;
		$values['ParentID'] = $parentID;
		$success = Table::addRow("tasks_baserequirements", $values, $comments);
		if (!$comments) redirecttotal('tasks/generators/showgenerator&id=' . $generatorID, null);
	}
	
	
	public function insertandrequirementAction() {
	
		$comments = true;
		$parentID = $_GET['parentID'];
		$generatorID = $_GET['generatorID'];
		$columnID = $_GET['columnID'];
		$operator = $_GET['operator'];
		$value = $_GET['value'];
		
		$generator = Table::loadRow("tasks_generators", $generatorID);
		
		$values = array();
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['GeneratorID'] = $generatorID;
		$values['TableID'] = $generator->basetableID;
		$values['ColumnID'] = $columnID;
		$values['Value'] = $value;
		$values['Operator'] = $operator;
		$values['Logicoperator'] = 1;
		$values['ParentID'] = $parentID;
		$success = Table::addRow("tasks_baserequirements", $values, $comments);
		if (!$comments) redirecttotal('tasks/generators/showgenerator&id=' . $generatorID, null);
	}
	
	
	public function insertfirstrequirementAction() {
	
		$comments = true;
		$generatorID = $_GET['generatorID'];
		$columnID = $_GET['columnID'];
		$operator = $_GET['operator'];
		$value = $_GET['value'];
		
		$generator = Table::loadRow("tasks_generators", $generatorID);
		
		$values = array();
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['GeneratorID'] = $generatorID;
		$values['TableID'] = $generator->basetableID;
		$values['ColumnID'] = $columnID;
		$values['Value'] = $value;
		$values['Operator'] = $operator;
		$values['Logicoperator'] = 0;
		$values['ParentID'] = 0;
		$success = Table::addRow("tasks_baserequirements", $values, $comments);
		if (!$comments) redirecttotal('tasks/generators/showgenerator&id=' . $generatorID, null);
	}
	
	
	
	public function removerequirementAction() {
	
		$comments = true;
		$requirementID = $_GET['id'];
		$generatorID = $_GET['generatorID'];
		$requirement = Table::loadRow("tasks_baserequirements", $requirementID, $comments);
	
		$childs = Table::load("tasks_baserequirements", "WHERE GeneratorID=" . $generatorID . " AND ParentID=" . $requirementID, $comments);

		
		if (count($childs) > 0) {
			echo "<br>Has childs, cannot be removed.";
		}
		
		Table::deleteRow("tasks_baserequirements", $requirementID, $comments);
		
		//if (!$comments) redirecttotal('worder/objectives/showobjective&id=' . $objectiveID, null);
	}
	
	
	
	
	// Palauttaa filtteröidyt itemit. Ladataan itemit basetable:sta ja filtteröidään se
	// halutuilla kriteereillä. Tätä käytetään sekä generaattorin näkymässä, että 
	// taskien luonnissa. Näytetään ainoastaan tehtävät, jotka eivät ole missään olemassa
	// olevassa tasksetissä mukana.
	private function loadFilteredItems() {
		
	}
	
	public function insertgeneratorAction() {
		$comments = true;
		$values = array();
		$values['Name'] = $_GET['name'];
		if (isset($_GET['userID'])) $values['UserID'] = $_GET['userID'];
		$generatorID = Table::addRow("tasks_generators", $values, false);
		if (!$comments) redirecttotal('tasks/generators/showgenerator&id=' . $generatorID ,null);
	}
	
	
	public function updategeneratorAction() {
	
		$comments = false;
		$generatorID = $_GET['id'];
		$oldgenerator = Table::loadRow('tasks_generators', $generatorID);
		$values = array();
		
		if (isset($_GET['basetableID'])) {
			$basetableID = $_GET['basetableID'];
			echo "<br> -- BasetableID = " . $basetableID;
			if ($oldgenerator->basetableID != $basetableID) {
				echo "<br> -- basetable changed";
				$values['BasecolumnID'] = 0;
				$values['BasenamecolumnID'] = 0;
				$values['BasetableID'] = $basetableID;
				$values['Comletioncondition'] = "";
				$values['ActionID'] = "";
				$values['ActionvariableID'] = "";
				$values['Targetcount'] = 0;
				$values['Basefilter'] = "";
			}
		} else {
			$basetableID = $oldgenerator->basetableID;
		}
		
		if (isset($_GET['name'])) $values['Name'] = $_GET['name'];
		if (isset($_GET['description'])) $values['Description'] = $_GET['description'];
		if (isset($_GET['projectID'])) $values['ProjectID'] = $_GET['projectID'];
		if (isset($_GET['userID'])) $values['UserID'] = $_GET['userID'];
		if (isset($_GET['startstateID'])) $values['StartstateID'] = $_GET['startstateID'];
		if (isset($_GET['basenamecolumnID'])) $values['BasenamecolumnID'] = $_GET['basenamecolumnID'];
		if (isset($_GET['basecolumnID'])) $values['BasecolumnID'] = $_GET['basecolumnID'];
		if (isset($_GET['basefilter'])) $values['Basefilter'] = $_GET['basefilter'];
		if (isset($_GET['taskprefix'])) $values['Taskprefix'] = $_GET['taskprefix'];
		if (isset($_GET['targetfilter'])) $values['Targetfilter'] = $_GET['targetfilter'];
		if (isset($_GET['operator'])) $values['Operator'] = $_GET['operator'];
		//if (isset($_GET['actionpath'])) $values['Actionpath'] = $_GET['actionpath'];
		if (isset($_GET['actionID'])) $values['ActionID'] = $_GET['actionID'];
		if (isset($_GET['languageID'])) $values['LanguageID'] = $_GET['languageID'];
		if (isset($_GET['actionvariableID'])) $values['ActionvariableID'] = $_GET['actionvariableID'];
		if (isset($_GET['targettableID'])) {
			if ($_GET['targettableID'] == '') {
				$values['TargettableID'] = 0;
			} else {
				$values['TargettableID'] = $_GET['targettableID'];
			}
		}
		if (isset($_GET['priorityID'])) {
			$values['PriorityID'] = $_GET['priorityID'];
		} else {
			$values['PriorityID'] = 0;
		}
		if (isset($_GET['targetcolumnID'])) $values['TargetcolumnID'] = $_GET['targetcolumnID'];
		if (isset($_GET['basefilter'])) $values['Basefilter'] = $_GET['basefilter'];
		if (isset($_GET['completioncondition'])) $values['Completioncondition'] = $_GET['completioncondition'];
		if (isset($_GET['targetcount'])) $values['Targetcount'] = $_GET['targetcount'];
		
		// jos päätaulua muutetaan, niin pitää nollata muita kenttiä...
		
		$success = Table::updateRow("tasks_generators", $values, $generatorID, $comments);

		
		echo "<br> -- BasetableID = " . $basetableID;
		if (isset($_GET['actionID'])) {
				
			$actionID = $_GET['actionID'];
			echo "<br> -- ActionID = " . $actionID;
			
			if ($actionID == "") {
				echo "<br>Tsekataan oldactionID:n poisto";
				//updateSystemActionsRemove();
				
			} else {
				echo "<br>action id ei tyhjä... " . $actionID;
				$action = Table::loadRow("system_actionpaths", $actionID);
					
				if ($oldgenerator->actionID != $actionID) {
				
					echo "<br>Actionid not equals";
						
					$tables = explode(',',$action->tablelist);
					$found = false;
						
					foreach($tables as $index => $tableID) {
						if ($basetableID == $tableID) {
							echo "<br>table already exists - " . $tableID;
							$found = true;
						}
					}
				
					// TODO: 	pitäisi tsekata onko oldgenerator->actionID:llä vielä aktiivisia taskeja,
					//			jos ei ole, niin ko. tableID voitaisiin poistaa listalta..
					//
				
					if ($found == false) {
						$values = array();
						echo " -- " . $oldgenerator->tablelist;
						if ($action->tablelist == '') {
							echo "<br>tablelist empty - " . $action->tablelist;
							$values['Tablelist'] = $basetableID;
						} else {
							echo "<br>tablelist tt empty - " . $action->tablelist;
							$values['Tablelist'] = $action->tablelist . "," . $basetableID;
						}
						echo "<br>tablelist after - " . $values['Tablelist'] ;
				
						echo "<br> -- update actionpath";
						$success = Table::updateRow("system_actionpaths", $values, $actionID, $comments);
					}
					//updateSystemActionsAdd();
					//updateSystemActionsRemove();
				} else {
					echo "<br>Actionid equals";
				}
			}
			
			
			
		}
			
		if (!$comments) redirecttotal('tasks/generators/showgenerator&id=' . $generatorID);
	}
	
	
	public function generateminitasksAction() {
		
		$comments = false;
		if ($comments) echo "<br>generateminitasksAction...";
		if ($comments) echo "<br> - generatorID - " . $_GET['generatorID'];
		if ($comments) echo "<br> - tasklist - " . $_GET['tasklist'];
		if ($comments) echo "<br> - taskcount - " . $_GET['taskcount'];
		if ($comments) echo "<br> - subtaskcount - " . $_GET['subtaskcount'];
		
		$tasklist = explode(":", $_GET['tasklist']);
		$taskidlist = array();
		foreach($tasklist as $index => $value) {
			if ($comments) echo "<br>... index: " . $index . ", value: " . $value;
			$taskidlist[$value] = $value;
		}
		
		// Ladataan itemssit, targettablesta, saadaan namecolumnit täältä...
		
		$taskcount = $_GET['taskcount'];
		$subtaskcount = $_GET['subtaskcount'];
		$generatorID = $_GET['generatorID'];
		$generator = Table::loadRow("tasks_generators", $generatorID);
		$generatorlabels = Table::load("tasks_generatorlabels", "WHERE GeneratorID=" . $generator->generatorID);
		$labels = Table::load("tasks_labels", " WHERE ProjectID=" . $generator->projectID, $comments);
		$startstate = null;
		if ($generator->startstateID > 0) $startstate = Table::loadRow("tasks_states", $generator->startstateID);
		$basetable = Table::getTableWithID($generator->basetableID);
		$actionID = $generator->actionID;
		
		
		
		
		
		$tablestruct = Table::getTableWithID($generator->basetableID);
		$key = $tablestruct->getKeyColumn();
		$namecolumn = $tablestruct->getColumns()[$generator->basenamecolumnID];
		
		$where = " WHERE " . $key->columnname . " IN (" . str_replace(":",",",$_GET['tasklist']) . ")";
		$items = Table::load($tablestruct->name, $where, $comments);
		
		foreach($items as $index => $value) {
			if ($comments) echo "<br>".  $index . " - " . $value->lemma;
		}
		
		// näitä ei tarvita mihinkään...
		//$columns = $basetable->getColumns();
		//$targetcolumn = $columns[$generator->basecolumnID];
		//$columnnamevar = $targetcolumn->variablename;
		
		$max = Table::customWhereClause("SELECT MAX(Tasknumber) FROM tasks_tasks WHERE GeneratorID=" . $generatorID);
		if ($comments) echo "<br> --- maxii:" . $max;
		
		//$taskcountingenerator = Table::countRows('tasks_tasks', 'TaskID', "WHERE GeneratorID=" . $generatorID);
		//echo "<br> - taskcountingenerator - " . $taskcountingenerator;
		
		// laske kuinka paljon jää itemejä yli, jos on vain muutama, niin sitten plussataan 
		// mutta otetaan nyt aluksi suoraan taskcount...
		$currentdate = date('Y-m-d H:i:s');
		$listindex = 0;
		for($taskIndex = 0;$taskIndex<$taskcount;$taskIndex++) {

			// Lisätään tasks-tauluun rivi
			$values = array();
			$values['Name'] = $generator->name . " " . ($taskIndex+1 + $max);
			$values['GeneratorID'] = $generator->generatorID;
			$values['Created'] = $currentdate;
			$values['Tasknumber'] =  ($taskIndex+1 + $max);
			$values['StateID'] =  $generator->startstateID;
			$values['CreatorID'] = $_SESSION['userID'];
			$values['UserID'] = $generator->userID;
			$values['ActionID'] = $actionID;
			$values['ProjectID'] = $generator->projectID;
			$values['PriorityID'] = $generator->priorityID;
			$values['Description'] = $generator->description;
			$values['TargettableID'] = $generator->basetableID;
							
			$taskID = Table::addRow("tasks_tasks", $values, $comments);
			if ($comments) echo "<br>Task added - " . $taskID;
			
			$values = array();
			$values['TaskID'] = $taskID;
			$values['UserID'] = $_SESSION['userID'];
			$values['Timestamp'] = $currentdate;
			if ($startstate != null) {
				//$values['Description'] = "Created - " . $startstate->name . " (" . $labelstr . ")";
				$values['Description'] = "Created - " . $startstate->name;
			} else {
				$values['Description'] = "Created";
				//$values['Description'] = "Created (" . $labelstr . ")";
			}
			$logID = Table::addRow("tasks_transitionlog", $values);
				
			
			$labelstr = null;
			foreach($generatorlabels as $index => $link) {
				$values = array();
				$values['TaskID'] = $taskID;
				$values['LabelID'] =  $link->labelID;
				$values['ProjectID'] = $generator->projectID;
				$rowID = Table::addRow("tasks_tasklabels", $values, $comments);
				$labelstr = null;
				$label = $labels[$link->labelID];
				if ($labelstr == null) {
					$labelstr = $label->short;
				} else {
					$labelstr = $labelstr . "," . $label->short;
				}
				
				$values = array();
				$values['TaskID'] = $taskID;
				$values['UserID'] = $_SESSION['userID'];
				$values['Timestamp'] = $currentdate;
				$values['Description'] = "Label added - " . $label->name;
				$logID = Table::addRow("tasks_transitionlog", $values);
			}

			
			
			// TODO: käydään läpi tasklista asianomaisella indeksillä...
			$taskindexstring = "";
			$minitaskcounter = 0;
			for($minitaskIndex=0; $minitaskIndex < $subtaskcount; $minitaskIndex++) {
				
				$values = array();
				$values['TaskID'] = $taskID;
				$values['TargetID'] = $tasklist[$listindex];
				$values['UserID'] = 0;
				$values['State'] = 0;
				$values['ActionID'] = $actionID;
				if ($comments) echo "<br> -- " . $listindex;
				$item = $items[ $tasklist[$listindex]];
				
				
				if ($item == null) {
					$values['Name'] = "" . $generator->name . " - ". $tasklist[$listindex];
				} else {
					//$var = $namecolumn->variablename;
					//$values['Name'] = "" . $generator->name . " - ". $item->$var;
					
					if ($namecolumn->getColumnType() == Column::COLUMNTYPE_MULTILANG) {
						$var = $namecolumn->variablename;
						$itemname = $item->$var;
						if ($generator->languageID > 0) {
							$values['Name'] = $generator->name . " - " . parseMultilangString($itemname, $generator->languageID);
						} else {
							$values['Name'] = $generator->name . " - ". $item->$var;
						}
					} else {
						$var = $namecolumn->variablename;
						$values['Name'] = $generator->name . " - ". $item->$var;
					}
					
				}
				$values['GeneratorID'] = $generator->generatorID;
				$values['TargettableID'] = $generator->basetableID;
				$minitaskID = Table::addRow("tasks_minitasks", $values, $comments);
					
				if ($comments) echo "<br> - Creating minitask: " . $tasklist[$listindex];
				
				// TODO: pitäisikö taskiin lisätä logimerkintä....
				
				$listindex++;
				$minitaskcounter++;
				if ($listindex == count($taskidlist)) break;
			}
			if ($comments) echo "<br>Minitaskcount - " . $minitaskcounter;
			
			if ($minitaskcounter > 0) {
				$values = array();
				$values['TaskID'] = $taskID;
				$values['UserID'] = $_SESSION['userID'];
				$values['Timestamp'] = $currentdate;
				$values['Description'] = "Ministasks Created (" . $minitaskcounter . " pcs)";
				$logID = Table::addRow("tasks_transitionlog", $values);
			}			
				
			//$taskID = Table::addRow("project_tasks", $values, false);
				
		}
		if ($comments) {
			echo "<br>Completed.";
		} else {
			
		}
		
		if (!$comments) redirecttotal('tasks/generators/showgenerator&id=' . $generatorID);
	}
	
	
	
	public function generatetasksAction() {
	
		$comments = false;
		if ($comments) echo "<br>generatetasksAction...";
		if ($comments) echo "<br> - generatorID - " . $_GET['generatorID'];
		if ($comments) echo "<br> - tasklist - " . $_GET['tasklist'];
		
		$tasklist = explode(":", $_GET['tasklist']);
		$taskidlist = array();
		$taskcount = 0;
		foreach($tasklist as $index => $value) {
			if ($comments) echo "<br>... index: " . $index . ", value: " . $value;
			$taskidlist[$value] = $value;
			$taskcount++;
		}

		$generatorID = $_GET['generatorID'];
		$generator = Table::loadRow("tasks_generators", $generatorID);
		$generatorlabels = Table::load("tasks_generatorlabels", "WHERE GeneratorID=" . $generator->generatorID);
		$labels = Table::load("tasks_labels", " WHERE ProjectID=" . $generator->projectID, $comments);
		$startstate = null;
		if ($generator->startstateID > 0) $startstate = Table::loadRow("tasks_states", $generator->startstateID);
		$basetable = Table::getTableWithID($generator->basetableID);
		$actionID = $generator->actionID;
		
		
		$tablestruct = Table::getTableWithID($generator->basetableID);
		$key = $tablestruct->getKeyColumn();
		$namecolumn = $tablestruct->getColumns()[$generator->basenamecolumnID];

		if ($comments) echo "<br> --- namecolumntype - " . $namecolumn->getColumnType();
		
		$where = " WHERE " . $key->columnname . " IN (" . str_replace(":",",",$_GET['tasklist']) . ")";
		$items = Table::load($tablestruct->name, $where, $comments);
		
		if ($comments) {
			foreach($items as $index => $value) {
				echo "<br>".  $index . " - " . $value->lemma;
			}
		}
		
		$max = Table::customWhereClause("SELECT MAX(Tasknumber) FROM tasks_tasks WHERE GeneratorID=" . $generatorID);
		//$taskcountingenerator = Table::countRows('tasks_tasks', 'TaskID', "WHERE GeneratorID=" . $generatorID);
		//if ($comments) echo "<br> - taskcountingenerator - " . $taskcountingenerator;
		
		$currentdate = date('Y-m-d H:i:s');
		$listindex = 0;
		for($taskIndex = 0;$taskIndex<$taskcount;$taskIndex++) {
			
			$values = array();
			$item = $items[ $tasklist[$taskIndex]];
			$var = $namecolumn->variablename;
			//$values['Name'] = "" . $generator->taskprefix . " - ". $item->$var;
			
			if ($namecolumn->getColumnType() == Column::COLUMNTYPE_MULTILANG) {
				$itemname = $item->$var;
				if ($generator->languageID > 0) {
					$values['Name'] = $generator->name . " - " . parseMultilangString($itemname, $generator->languageID);
				} else {
					$values['Name'] = $generator->name . " - ". $item->$var;
				}
			} else {
				$values['Name'] = $generator->name . " - ". $item->$var;
			}
			
				
			$values['GeneratorID'] = $generator->generatorID;
			$values['Created'] = $currentdate;
			$values['TargetID'] = $tasklist[$taskIndex];
			$values['Tasknumber'] =  ($taskIndex+1 + $max);
			$values['StateID'] =  $generator->startstateID;
			$values['CreatorID'] = $_SESSION['userID'];
			$values['UserID'] = $generator->userID;
			$values['ActionID'] = $actionID;
			$values['ProjectID'] = $generator->projectID;
			$values['PriorityID'] = $generator->priorityID;
			$values['Description'] = $generator->description;
			$values['TargettableID'] = $generator->basetableID;
		
			$taskID = Table::addRow("tasks_tasks", $values, false);
			echo "<br>Task added - " . $taskID;
		
			$values = array();
			$values['TaskID'] = $taskID;
			$values['UserID'] = $_SESSION['userID'];
			$values['Timestamp'] = $currentdate;
			if ($startstate != null) {
				//$values['Description'] = "Created - " . $startstate->name . " (" . $labelstr . ")";
				$values['Description'] = "Created - " . $startstate->name;
			} else {
				$values['Description'] = "Created";
				//$values['Description'] = "Created (" . $labelstr . ")";
			}
			$logID = Table::addRow("tasks_transitionlog", $values);
		
		
			$labelstr = null;
			foreach($generatorlabels as $index => $link) {
				$values = array();
				$values['TaskID'] = $taskID;
				$values['LabelID'] =  $link->labelID;
				$values['ProjectID'] = $generator->projectID;
				$rowID = Table::addRow("tasks_tasklabels", $values, false);
				$labelstr = null;
				$label = $labels[$link->labelID];
				if ($labelstr == null) {
					$labelstr = $label->short;
				} else {
					$labelstr = $labelstr . "," . $label->short;
				}
		
				$values = array();
				$values['TaskID'] = $taskID;
				$values['UserID'] = $_SESSION['userID'];
				$values['Timestamp'] = $currentdate;
				$values['Description'] = "Label added - " . $label->name;
				$logID = Table::addRow("tasks_transitionlog", $values);
			}
			
			
		}
		if ($comments) {
			echo "<br>Completed.";
		} else {
		
		}
	}
	
	
	
	// Tätä pitäisi kutsua kun taski poistetaan tai kun actionID:tä vaihdetaan (kutsu vanhalla
	// actionID:llä). Tsekkaa onko actionille jäänyt taskeja, jolla päätellään tarvitseeko kyseistä
	// tableviitettä enää actiontaulussa. Suoritettu ja peruutettu tilassa olevia taskeja ei huomioida.
	// päivittää myös sessionin ac_muuttujat, mukäli muutos tapahtuu...
	private function updateSystemActionsRemove($actionID, $tableID) {
		
		
		foreach($_SESSION as $key => $value) {
			if (startWith($key, 'AC_')) {
				echo "<br>Remove session - " . $key;
				unsert($_SESSION[$key]);
			}
		}
		
		$actions = Table::load("system_actionpaths", "WHERE Active=1");
		foreach($actions as $actionID => $action) {
			$_SESSION['AC_' . $action->actionpath] = $action->tablelist;
		}
	}
	
	
	// Uusi taski lisätty, tsekataan / updatetaan uusi taulu actioneille
	private function updateSystemActionsAdd($actionID, $tableID) {
		
		foreach($_SESSION as $key => $value) {
			if (startWith($key, 'AC_')) {
				echo "<br>Remove session - " . $key;
				unsert($_SESSION[$key]);
			}
		}
		
		$actions = Table::load("system_actionpaths", "WHERE Active=1");
		foreach($actions as $actionID => $action) {
			$_SESSION['AC_' . $action->actionpath] = $action->tablelist;
		}
		
	}
	
	
	private function updateSystemActions() {
	
		foreach($_SESSION as $key => $value) {
			if (startWith($key, 'AC_')) {
				echo "<br>Remove session - " . $key;
				unsert($_SESSION[$key]);
			}
		}
	
		$actions = Table::load("system_actionpaths", "WHERE Active=1");
		foreach($actions as $actionID => $action) {
			$_SESSION['AC_' . $action->actionpath] = $action->tablelist;
		}
	
	}
}
