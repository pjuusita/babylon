<?php



class Install {
	
	
	public static function installModule($systemID, $moduleID, $comments) {
	
		// Pitäisi katsoa onko käyttäjällä oikeus asentaa...
	
		if ($comments) echo "<br>Install module - " . $moduleID;
		$localmodule = Table::loadRow('system_modules', "WHERE ModuleID=" . $moduleID . " AND SystemID=" . $systemID, $comments);
		
		// Pitää käyttää remoteModulen moduleID:tä, sen saa transitions taulusta
		// TODO: tämä on epäselvää korjaa
		$row = Table::loadRow('system_transitions','WHERE LocalmoduleID=' . $moduleID . ' AND LocaltableID=0 AND LocalrowID=0', $comments);
		$remoteModuleID = $row->remotemoduleID;
		
		if ($comments) {
			echo "<br>install system - " . $systemID;
			echo "<br>LocalModule - " . $localmodule->moduleID;
			echo "<br>remote Module - " . $remoteModuleID;
				
		}
		//$remoteModuleID = $moduleID;
		
		if (($remoteModuleID == null) || ($remoteModuleID == 0)) {
			echo "<br>Remotemodule null";
			exit;
		}
		
		$tableurl = 'https://www.babelsoft.fi/' . SITEPATH . '/json.php?rt=system/database/gettables&moduleid=' . $remoteModuleID;
		echo "<br>Tableurl installModule - " . $tableurl;
		ini_set("allow_url_fopen", 1);
		$json = file_get_contents($tableurl);
		$remotetables = json_decode($json);
		echo "<br>db - " . $_SESSION['database'];
		
		if ($remotetables != null) {
			foreach($remotetables as $tableID => $remotetable) {
				//echo "<br>Table - " . $remotetable->tableID . " - " . $remotetable->name;
				//echo "<br>db - " . $_SESSION['database'];
				if (Table::tableExistsInDatabase($remotetable->name)) {
					echo "<br><br>--------------------------------------------------------------------------";
					echo "<br>Structure compare - " . $remotetable->name . " (remotetableID:" . $remotetable->tableID . ")";
					Install::compareTableColumns($remotetable);
				} else {
					echo "<br><br>--------------------------------------------------------------------------";
					echo "<br>Create table - " . $remotetable->name;
					Install::createDatabaseTable($remotetable, $localmodule);
					echo "<br>table created";
				}
			}
			echo "<br><br>All tables created.";
		} else {
			echo "<br><br>No tables.";
		}
		$values = array();
		$values['Active'] = 1;
		if ($comments) echo "<br>Update Systemmodules - " . $moduleID;
		Table::updateRow("system_modules", $values, "WHERE ModuleID=" . $moduleID . " AND SystemID=" . $systemID, $comments);
		if ($comments) echo "<br>Update Systemmodules - finished";
		
	}
	
	
	// TODO: Tätä ei ehkä käytetä missään
	public static function updateModuleDimensions($systemID, $moduleID, $comments) {
	
		if ($comments) echo "<br>updateModuleDimensions module - " . $moduleID;
	
		$tableurl = 'https://www.babelsoft.fi/' . SITEPATH . '/json.php?rt=system/database/gettables&moduleid=' . $remoteModuleID;
		echo "<br>Tableurl installModule - " . $tableurl;
		ini_set("allow_url_fopen", 1);
		$json = file_get_contents($tableurl);
		$remotetables = json_decode($json);
		echo "<br>db - " . $_SESSION['database'];
		exit;
		/*
		if ($remotetables != null) {
			foreach($remotetables as $tableID => $remotetable) {
				//echo "<br>Table - " . $remotetable->tableID . " - " . $remotetable->name;
				//echo "<br>db - " . $_SESSION['database'];
				if (Table::tableExistsInDatabase($remotetable->name)) {
					echo "<br><br>--------------------------------------------------------------------------";
					echo "<br>Structure compare - " . $remotetable->name . " (remotetableID:" . $remotetable->tableID . ")";
					Install::compareTableColumns($remotetable);
				} else {
					echo "<br><br>--------------------------------------------------------------------------";
					echo "<br>Create table - " . $remotetable->name;
					Install::createDatabaseTable($remotetable, $localmodule);
					echo "<br>table created";
				}
			}
			echo "<br><br>All tables created.";
		} else {
			echo "<br><br>No tables.";
		}
		$values = array();
		$values['Active'] = 1;
		if ($comments) echo "<br>Update Systemmodules - " . $moduleID;
		Table::updateRow("system_modules", $values, "WHERE ModuleID=" . $moduleID . " AND SystemID=" . $systemID, $comments);
		if ($comments) echo "<br>Update Systemmodules - finished";
		*/
		
	}
	
	
	// TODO: Tämä ei toimi jos taulujen modulename on muuttunut...
	public static function synchronizeModulesTable($systemID) {
	
		if ($systemID == 1) return;
		
		$comments = false;
		//echo "<br>synchronzeModulesTable";
		//$moduletableID = Table::getTableID('system_modules');
		//$tablestableID = Table::getTableID('system_tables');
	
		$tableurl = REMOTE_INSTALLSERVER . SITEPATH . '/install.php?rt=system/database/getmodules';
		
		//$tableurl = 'https://www.babelsoft.fi/' . SITEPATH . '/install.php?rt=system/database/getmodules';
		if ($comments) echo "<br>Tableurl - " . $tableurl;
		ini_set("allow_url_fopen", 1);
		$json = file_get_contents($tableurl);
		if ($json == null) {
			echo "<br>json null";
		}
		//var_dump($json);
		//echo "<br><br>";
		$remotemodules = json_decode($json);
		
		//var_dump($remotemodules);
		//echo "<br>remotemodulecount - " . count($remotemodules);
		
		foreach($remotemodules as $moduleID => $remotemodule) {
			if ($comments) echo "<br>Syncronice remotetable - " . $remotemodule->moduleID . " - " . $remotemodule->name;
	
			$existingmodule = Table::loadRow('system_modules',"WHERE Modulename='".$remotemodule->modulename . "' AND SystemID=" . $systemID, $comments);
			if ($existingmodule == null) {
				// lisätään modulinimi
				// lisätään transition tauluun
	
				$values = array();
				$values['Name'] = $remotemodule->name;
				$values['Modulename'] = $remotemodule->modulename;
				$values['Active'] = 0;
				$values['Available'] = $remotemodule->available;
				$values['Defaultlog'] = $remotemodule->defaultlog;
				$values['SystemID'] = $_SESSION['systemID'];
				
				$newModuleID = Table::addRow('system_modules',$values, $comments);
				if ($comments) echo "<br>New moduleID - " . $newModuleID;
	
				$values = array();
				$values['LocalmoduleID'] = $newModuleID;
				$values['RemotemoduleID'] = $remotemodule->moduleID;
	
				$rowID = Table::addRow('system_transitions',$values, $comments);
	
				if ($comments) echo "<br>new module added - " . $remotemodule->name;
	
			} else {
	
				if ($comments) echo "<br>Module " . $remotemodule->modulename . " allready exists moduleID: " . $existingmodule->moduleID;
				// TODO: Module pitäisi updatettaa... mutta tähän ehkä tarvitaan transitiontablea
				
				
				/*
					$transition = Table::loadRow('system_transitions', ' ModuleID=1 AND TableID=' . $moduletableID . ' AND LocalrowID=' . $existingmodule->moduleID);
	
					if ($transition == null) {
					echo "<br>transition not found - ";
					$values = array();
					$values['ModuleID'] = 1;
					$values['TableID'] = $moduletableID;
					$values['LocalrowID'] = $localtableID;
					$values['RemoterowID'] = $table->tableID;
	
					} else {
					echo "<br>transition found";
	
	
					}
					exit;
					*/
				// insertoidaan transition tauluun
			}
		}
	}
	
	
	
	// Tämän voisi yhdistää edelliseen funktioon, sitä pitää vain sitten kutsua current modulella
	public static function synchronizeClientModulesTable($systemID, $comments = false) {
	
		//$comments = false;
		//echo "<br>synchronzeModulesTable";
		//$moduletableID = Table::getTableID('system_modules');
		//$tablestableID = Table::getTableID('system_tables');
	
		$tableurl = REMOTE_INSTALLSERVER . SITEPATH . '/install.php?rt=system/database/getmodules';
		
		//$tableurl = 'https://www.babelsoft.fi/' . SITEPATH . '/install.php?rt=system/database/getmodules';
		if ($comments) echo "<br>Tableurl - " . $tableurl;
		ini_set("allow_url_fopen", 1);
		$json = file_get_contents($tableurl);
		if ($json == null) {
			echo "<br>json null";
		}
		//var_dump($json);
		//echo "<br><br>";
		$remotemodules = json_decode($json);
	
		//var_dump($remotemodules);
		//echo "<br>remotemodulecount - " . count($remotemodules);
	
		foreach($remotemodules as $moduleID => $remotemodule) {
			if ($comments) echo "<br>Syncronice remotetable - " . $remotemodule->moduleID . " - " . $remotemodule->name;
	
			$existingmodule = Table::loadRow('system_modules',"WHERE SystemID=" . $systemID . " AND Modulename='".$remotemodule->modulename . "'", $comments);
			if ($existingmodule == null) {
				// lisätään modulinimi
				// lisätään transition tauluun
				if ($remotemodule->moduletype == 0) {
					$values = array();
					$values['Name'] = $remotemodule->name;
					$values['Modulename'] = $remotemodule->modulename;
					$values['Active'] = 1;
					$values['Available'] = $remotemodule->available;
					$values['Defaultlog'] = $remotemodule->defaultlog;
					$values['Moduletype'] = $remotemodule->moduletype;
					$values['SystemID'] = $systemID;
						
					$newModuleID = Table::addRow('system_modules',$values, $comments);
					if ($comments) echo "<br>New moduleID - " . $newModuleID;
						
					// TODO: ei saisi tehdä tuplia, tarkista ennen lisäystä
					$values = array();
					$values['LocalmoduleID'] = $newModuleID;
					$values['RemotemoduleID'] = $remotemodule->moduleID;
						
					$rowID = Table::addRow('system_transitions',$values, $comments);
						
					if ($comments) echo "<br>new module added - " . $remotemodule->name;
				}
				
				if ($remotemodule->moduletype == 1) {
					$values = array();
					$values['Name'] = $remotemodule->name;
					$values['Modulename'] = $remotemodule->modulename;
					$values['Active'] = 0;
					$values['Available'] = $remotemodule->available;
					$values['Defaultlog'] = $remotemodule->defaultlog;
					$values['Moduletype'] = $remotemodule->moduletype;
					$values['SystemID'] = $systemID;
					
					$newModuleID = Table::addRow('system_modules',$values, $comments);
					if ($comments) echo "<br>New moduleID - " . $newModuleID;
					
					// TODO: ei saisi tehdä tuplia, tarkista ennen lisäystä
					$values = array();
					$values['LocalmoduleID'] = $newModuleID;
					$values['RemotemoduleID'] = $remotemodule->moduleID;
					
					$rowID = Table::addRow('system_transitions',$values, $comments);
					
					if ($comments) echo "<br>new module added - " . $remotemodule->name;
				}
	
			} else {
	
				if ($comments) echo "<br>Module " . $remotemodule->modulename . " allready exists moduleID: " . $existingmodule->moduleID;
				// TODO: Module pitäisi updatettaa... mutta tähän ehkä tarvitaan transitiontablea
	
	
				/*
				 $transition = Table::loadRow('system_transitions', ' ModuleID=1 AND TableID=' . $moduletableID . ' AND LocalrowID=' . $existingmodule->moduleID);
	
				 if ($transition == null) {
				 echo "<br>transition not found - ";
				 $values = array();
				 $values['ModuleID'] = 1;
				 $values['TableID'] = $moduletableID;
				 $values['LocalrowID'] = $localtableID;
				 $values['RemoterowID'] = $table->tableID;
	
				 } else {
				 echo "<br>transition found";
	
	
				 }
				 exit;
				 */
				// insertoidaan transition tauluun
			}
		}
	}
	
	
	/**
	 * Kun tänne tullaan tiedetään jo, että taulua ei ole olemassa.
	 *
	 * @param string $table
	 * @param mixed $columns
	 */
	public static function createDatabaseTable($remotetable, $localmodule) {
		echo "<br><br>Current database - " . $_SESSION['database'] . "... creating table - " . $remotetable->name;
	
		echo "<br>Tablename - " . $remotetable->name;
	
	
	
		$columnurl = 'https://www.babelsoft.fi/' . SITEPATH . '/json.php?rt=system/database/getcolumns&tableid=' . $remotetable->tableID;
	
		ini_set("allow_url_fopen", 1);
		$json = file_get_contents($columnurl);
		$columns = json_decode($json);
	
		$keycolumn = null;
		foreach($columns as $index => $column) {
			echo "<br>Search key column - " . $column->columnID . " - " . $column->name;
			if ($column->type == 2) $keycolumn = $column;
		}
		echo "<br>Keycolumn found - " . $keycolumn->name;
	
		$localtableID = Table::createTable($remotetable->name, $keycolumn->columnname, $localmodule->moduleID, $localmodule->defaultlog, $remotetable->systemspecific);
		echo "<br>Created local tableID - " . $localtableID;
		Install::insertTransition(1, 1, $localtableID, $remotetable->tableID, 0,0);
	
		foreach($columns as $index => $column) {
			if ($column->type == 2) {
	
				echo "<br>Key column no add - " . $column->name;
				// add column, pitää ehkä ensin tsekata onko se olemassa, mutta kun nyt on äsken kutsuttu createtable niin ei tartte
			} else {
				if ($column->referencetablename == "") {
					$referencertableID = 0;
				} else {
					$referencertableID = Table::getTableID($column->referencetablename, true);
				}
				$columnID = Table::insertColumn($localtableID, $column->variablename, $column->columnname, $column->name, $column->type, $column->obligatory, $referencertableID, $column->min, $column->max, $column->defaultvalue);
				Install::insertTransition(1,1, $localtableID, $remotetable->tableID, $columnID, $column->columnID);
			}
		}
	}
	
	


	private static function insertTransition($localmoduleID, $remotemoduleID, $localtableID, $remotetableID, $localrowID, $remoterowID) {
	
	
		$values = array();
		$values['LocalmoduleID'] = $localmoduleID;
		$values['RemotemoduleID'] = $remotemoduleID;
		$values['LocaltableID'] = $localtableID;
		$values['RemotetableID'] = $remotetableID;
		$values['LocalrowID'] = $localrowID;
		$values['RemoterowID'] = $remoterowID;
	
		$rowID = Table::addRow('system_transitions',$values, true);
	
	
	
	}
	
	
	/**
	 * Vertaa tämänhetkisessä tietokannassa olevaa tietokantarakennetta rajapinnan kautta base-tietokannasta haettuun.
	 * Tätä käytetään tietokantarakenteen päivittämiseen.
	 * 
	 * @param unknown $remotetable
	 */
	public static function compareTableColumns($remotetable, $comments = false) {
		
		//$comments = true;
		if ($comments) echo "<br><br>Compare database - " . $_SESSION['database'] . "... compare table - " . $remotetable->name;
	
		$columnurl = 'https://www.babelsoft.fi/' . SITEPATH . '/json.php?rt=system/database/getcolumns&tableid=' . $remotetable->tableID;
		if ($comments) echo "<br>Columnurl - " . $columnurl;
		if ($comments) echo "<br>Columnurl - " . $columnurl;
		
		ini_set("allow_url_fopen", 1);
		$json = file_get_contents($columnurl);
		$remotecolumns = json_decode($json);
	
		$keycolumn = null;
		foreach($remotecolumns as $index => $column) {
			//echo "<br>" . $column->columnID . " - " . $column->name;
			if ($column->type == 2) $keycolumn = $column;
		}
	
		if ($comments) echo "<br>Compare existing table - " . $remotetable->name;
		$localtable = Table::getTable($remotetable->name, $comments);
		$existingcolumns = $localtable->getColumns();
	
		foreach($existingcolumns as $index3 => $localcolumn) {
	
			if ($comments) echo "<br> -- Processing column: " . $localcolumn->name;
				
			if ($localcolumn->tableID == $localtable->tableID) {
				//echo "<br> -- " . $localcolumn->variablename;
				$found = 0;
				foreach($remotecolumns as $index4 => $remotecolumn) {
					if ($remotecolumn->columnname == $localcolumn->columnname) {
						//echo " -- found2";
						$found++;
						//echo "<br><font style='color:red'> -- columnname - " . $remotecolumn->columnname . "</font>";
						
						if ($remotecolumn->variablename != $localcolumn->variablename) {
							// Update tähän ja logitus tiedostoon
							if ($comments) echo "<br><font style='color:red'>missmatch - variablename - " . $remotecolumn->variablename . " - " . $localcolumn->variablename . "</font>";
						}
						if ($remotecolumn->name != $localcolumn->name) {
							if ($comments) echo "<br>rem - " . $remotecolumn->name;
							if ($comments) echo "<br>loc - " . $localcolumn->name;
							if ($comments) echo "<br><font style='color:red'>missmatch - name - " . $remotecolumn->name . " - " . $localcolumn->name . "</font>";
						}
						if ($remotecolumn->type != $localcolumn->type) echo "<br><font style='color:red'>missmatch (" .  $remotecolumn->columnname . ") - type - " . $remotecolumn->type . " - " . $localcolumn->type . "</font>";
						if ($remotecolumn->obligatory != $localcolumn->obligatory) echo "<br><font style='color:red'>missmatch (" .  $remotecolumn->columnname . ") - obligatory - " . $remotecolumn->obligatory . " - " . $localcolumn->obligatory . "</font>";
						if ($remotecolumn->editable != $localcolumn->editable) echo "<br><font style='color:red'>missmatch (" .  $remotecolumn->columnname . ") - editable - " . $remotecolumn->editable . " - " . $localcolumn->editable . "</font>";
						if ($remotecolumn->min != $localcolumn->min) echo "<br><font style='color:red'>missmatch (" .  $remotecolumn->columnname . ") - min - " . $remotecolumn->min . " - " . $localcolumn->min . "</font>";
						if ($remotecolumn->max != $localcolumn->max) echo "<br><font style='color:red'>missmatch (" .  $remotecolumn->columnname . ") - max - " . $remotecolumn->max . " - " . $localcolumn->max . "</font>";
						if ($remotecolumn->defaultvalue != $localcolumn->defaultvalue) echo "<br><font style='color:red'>missmatch (" .  $remotecolumn->columnname . ") - defaultvalue (" .  $remotecolumn->columnname . ") - " . $remotecolumn->defaultvalue . " - " . $localcolumn->defaultvalue . "</font>";
						if ($remotecolumn->tablevisibility != $localcolumn->tablevisibility) echo "<br><font style='color:red'>missmatch (" .  $remotecolumn->columnname . ") - tablevisibility - " . $remotecolumn->tablevisibility . " - " . $localcolumn->tablevisibility . "</font>";
						if ($remotecolumn->sectionvisibility != $localcolumn->sectionvisibility) echo "<br><font style='color:red'>missmatch (" .  $remotecolumn->columnname . ") - sectionvisibility - " . $remotecolumn->sectionvisibility . " - " . $localcolumn->sectionvisibility . "</font>";
							
						if ($comments) echo "<br>Remote - " . $remotecolumn->removeID;
						if ($comments) echo "<br>local - " . $localcolumn->removeID;
						if ($comments) echo "<br>referencetable - " . $localcolumn->referencetableID;
						if ($comments) echo "<br>remote referencetablename - " . $remotecolumn->referencetablename;
							
						
						if (($remotecolumn->referencetablename == "")) {
							if ($localcolumn->referencetableID == 0) {
								if ($comments) echo "<br>Referencecompare ok - referencetablename empty vs. local referencetableID = 0";
							} else {
								echo "<br><font style='color:red'>Referencecompare failed - '" . $remotecolumn->referencetablename . "' - local referencetableID='" .$localcolumn->referencetableID . "'</font>";
							}
						} else {
							if ($localcolumn->referencetableID == 0) {
								echo "<br><font style='color:red'>Referencecompare failed - '" . $remotecolumn->referencetablename . "' - local referencetableID='" .$localcolumn->referencetableID . "'</font>";
							} else {
								$localreferencetablename = Table::getTableName($localcolumn->referencetableID);
								if ($comments) echo "<br>Local referencetable - " . $localreferencetablename . " - " . $localcolumn->referencetableID;
								if ($remotecolumn->referencetablename == $localreferencetablename) {
									if ($comments) echo "<br>Referencecompare ok - " . $remotecolumn->referencetablename . " - " .$localreferencetablename;
								} else {
									echo "<br><font style='color:red'>Referencecompare failed - '" . $remotecolumn->referencetablename . "' - '" .$localreferencetablename . "'</font>";
								}
							}
						}
						if ($remotecolumn->removeID != $localcolumn->removeID) echo "<br><font style='color:red'>missmatch - removeID - " . $remotecolumn->removeID . " - " . $localcolumn->removeID . "</font>";
						if ($remotecolumn->logvalue != $localcolumn->logvalue) echo "<br><font style='color:red'>missmatch - logvalue - " . $remotecolumn->logvalue . " - " . $localcolumn->logvalue . "</font>";
	
					}
				}
				if ($found == 0) {
					echo "<br><font style='color:red'> -- " . $localcolumn->variablename . " -- not found in remote</font>";
				}
	
	
			}
		}
	
		// äsken käytiin lävitse existing columns, nyt pitää käydä remotecolumnssit lävitse ja tsekata puuttuuko joku
		foreach($remotecolumns as $index3 => $remotecolumn) {
	
			if ($remotetable->tableID == $remotecolumn->tableID) {		// jos annetulla haulla on tullut sarakkeita muista tauluista
				$found = 0;
				foreach($existingcolumns as $index4 => $localcolumn) {
					if ($localcolumn->tableID == $localtable->tableID) {
						if ($remotecolumn->columnname == $localcolumn->columnname) {
							$found++;
						}
					}
				}
				if ($found == 0) {
					echo "<br>Column not found" . $remotecolumn->columnname . " (" . $remotetable->name . ")";
					echo "" . $remotecolumn->variablename . " -- not found, adding<br>";
					if ($comments) echo "<br>Get Referencetable ID - " .  $remotecolumn->referencetablename;
					if ($remotecolumn->referencetablename == "") {
						$referenceTableID = 0;
					} else {
						$referenceTableID = Table::getTableID($remotecolumn->referencetablename, true);
					}
					echo "<br>InsertColumn - 111";
					Table::insertColumn($localtable->tableID, $remotecolumn->variablename, $remotecolumn->columnname, $remotecolumn->name, $remotecolumn->type, $remotecolumn->obligatory, $referenceTableID, $remotecolumn->min, $remotecolumn->max, $remotecolumn->defaultvalue, true);
					echo "<br>InsertColumn - 222";
				}
			}
		}
	}
	
	
	
	
	// yhdistä non system funktioon
	// TODO: tämän pitäisi käydä kaikki usergoupit lävitse ja luoda menu uudelleen kaikille
	// TODO: Custom menuja ei saisi ylikirjoittaa eikä poistaa (ainakin jos action on sellainen johon on oikeudet)
	public static function createSystemMenu($systemID, $usergroupID, $comments = false) {
	
		$comments = true;
		
		if ($comments) echo "<br>usergroupID - " . $usergroupID;
		
		$modules = Table::load("system_modules", "WHERE SystemID=" . $systemID . " AND Active=1", $comments);
		
		$accessrows = Table::load("system_usergroupaccessrights", "WHERE SystemID=" . $systemID . " AND UsergroupID=" . $usergroupID, true);
		$accesskeys = Table::load("system_accesskeys");
		
		Table::deleteRowsWhere("system_menu", " WHERE UsergroupID=" . $usergroupID, true);
	
		$currentmenu = array();
		$parentlist = array();
	
		// Lisätään etusivu menuitem
		$menuitem = new Menu("Etusivu","system/frontpage","index", Menu::MENUKEY_TOP, Menu::MENUKEY_FRONTPAGE, 10);
		$menuID = Install::addMenuItemToSystem($systemID, $menuitem, 0, $usergroupID);
		$menuitem->menuID = $menuID;
		$parentlist[Menu::MENUKEY_FRONTPAGE] = $menuID;
		$currentmenu[] = $menuitem;
	
		// Lisätään hallinta menu
		$menuitem = new Menu("Hallinta","admin/users","showpersonalsettings", Menu::MENUKEY_TOP, Menu::MENUKEY_ADMIN, 9000);
		$menuID = Install::addMenuItemToSystem($systemID, $menuitem, 0, $usergroupID);
		$menuitem->menuID = $menuID;
		$parentlist[Menu::MENUKEY_ADMIN] = $menuID;
		$currentmenu[] = $menuitem;
	
		$menuitem = new Menu("Omat tiedot","admin/users","showpersonalsettings", null,  Menu::MENUKEY_ADMIN, 400);
		$menuID = Install::addMenuItemToSystem($systemID, $menuitem, $parentlist[Menu::MENUKEY_ADMIN], $usergroupID);
		$menuitem->menuID = $menuID;
		$currentmenu[] = $menuitem;
		
	
		foreach($modules as $index => $module) {
	
			$accessrights = array();
			$moduleaccesslevel = 0;
			foreach($accessrows as $rowID => $accessrow) {
				if (($accessrow->moduleID == $module->moduleID) && ($accessrow->accesskeyID == 0)) {
					$moduleaccesslevel = $accessrow->accesslevel;
				}
			}
			
			if ($moduleaccesslevel > 0) {
				
				$modulepath = SITE_PATH . "modules/";
				$modulefile = $modulepath . $module->modulename . "/" . $module->modulename . ".module.php";
				if ($comments) echo "<br>modulename - " .  $module->modulename;
				if ($comments) echo "<br>Modulefile - " .  $modulepath;
				if ($comments) echo "<br>Modulefile - " .  $modulefile;
				
				include_once ($modulefile);
				$classname = $module->modulename . "Module";
				$moduleinstance = new $classname();
				
				if ($comments) echo "<br>Classname - " .  $classname;
					
					
				if ($moduleaccesslevel == AbstractModule::ACCESSRIGHT_CUSTOM) {
					foreach($accessrows as $rowID => $accessrow) {
						if ($accessrow->accesskeyID > 0) {
							$accesskey = $accesskeys[$accessrow->accesskeyID];
							if ($accessrow->accesslevel > 0) {
								$accessrights[$accesskey->name] = $accessrow->accesslevel;
							}
						}
					}
				} else {
					$allaccesskeys = $moduleinstance->getAccessRights();
					foreach($allaccesskeys as $accesskey => $accesslevel) {
						$accessrights[$accesskey] = $moduleaccesslevel;
					}
				}
				
				foreach($accessrights as $key => $level) {
					echo "<br> -- accessright - " . $key . " - " . $level;
				}
				
				
				$menulist = $moduleinstance->getMenu($accessrights);
				if ($comments) echo "<br>menulist count - " . count($menulist);
				if (count($menulist) == 0) {
					echo "<br>No module instance getMenu found - " . $moduleinstance->getDefaultName();
					echo "<br><br>";
				} else {
					foreach ($menulist as $index => $newmenuitem) {
				
						//echo "<br>";
						//var_dump($newmenuitem);
						if ($comments) echo "<br>Adding index - " . $index;
						if ($comments) echo "<br>Adding menuitem - " . ($newmenuitem->name);
						if ($comments) echo "<br>Adding menuitem parent - " . ($newmenuitem->parentkey);
				
						if ($newmenuitem->parentkey ==  Menu::MENUKEY_TOP) {
							$menuID = Install::addMenuItemToSystem($systemID, $newmenuitem, 0, $usergroupID);
							$newmenuitem->menuID = $menuID;
							$parentlist[$newmenuitem->menukey] = $menuID;
							$currentmenu[] = $newmenuitem;
						} else {
							if (isset($parentlist[$newmenuitem->parentkey])) {
								$parentID = $parentlist[$newmenuitem->parentkey];
								$menuID = Install::addMenuItemToSystem($systemID, $newmenuitem, $parentID, $usergroupID);
								$newmenuitem->menuID = $menuID;
								$parentlist[$newmenuitem->menukey] = $menuID;
								$currentmenu[] = $newmenuitem;
							} else {
								echo "<br>No parent found - " . $newmenuitem->parentkey;
							}
						}
					}
				}
			} else {
				echo "<br>Ei käyttöoikeuksia moduliin - " . $module->name;
			}
		}
	}
	


	// TODO: Nimi voidaan yksinkertaistaa, addMenuItem
	private function addMenuItemToSystem($systemID, $newmenuitem, $parentID, $usergroupID) {
	
		$values = array();
		$values['Name'] = $newmenuitem->name;
		$values['Tooltip'] = $newmenuitem->name;
		$values['ParentID'] = $parentID;
		$values['UsergroupID'] = "" . $usergroupID;
		$values['Path'] = '';
		$values['Module'] = $newmenuitem->module;
		$values['Action'] = $newmenuitem->action;
		$values['Placeorder'] = $newmenuitem->placeorder;
		$values['Menukey'] = $newmenuitem->menukey;
		$values['Policy'] = '0';
		$values['SystemID'] = $systemID;
	
		$newMenuID = Table::addRow("system_menu",$values);
		return $newMenuID;
	}
	
	
	// TODO: Ei saisi luoda tuplia
	public static function addAdminUserrights($systemID, $moduleID, $usergroupID, $comments = false) {
	
		$comments = true;
		if ($comments) echo "<br><br><br>";
		
		$module = Table::loadRow('system_modules', "WHERE SystemID=" . $systemID . " AND ModuleID=" . $moduleID, false);
		$modulepath = SITE_PATH . "modules/";
		$modulefile = $modulepath . $module->modulename . "/" . $module->modulename . ".module.php";
		if ($comments) echo "<br>install modulename - " .  $module->modulename;
		if ($comments) echo "<br>install Modulefile - " .  $modulepath;
		if ($comments) echo "<br>Install Modulefile - " .  $modulefile;
		include_once ($modulefile);
	
		$classname = $module->modulename . "Module";
		$moduleinstance = new $classname();
	
		if ($comments) echo "<br>addAdminUserrights Current UsergroupID - " . $usergroupID;
	
		// TODO: Lisätään asennettavalle modulille kaikki oikeudet henkilölle joka asentaa (usergroupID)
		/*
		$accessrights = $moduleinstance->getAccessRights();
		foreach($accessrights as $accesskey => $accesslevels) {
			$maxAccesslevel = 0;
			foreach($accesslevels as $accesslevel => $accesslevelname) {
				if ($accesslevel > $maxAccesslevel) $maxAccesslevel = $accesslevel;
			}
			echo "<br> -- Adding accesslevel - " . $accesskey . " - level: " . $maxAccesslevel;
	
			$values = array();
			$values['UsergroupID'] = $usergroupID;
			$values['ModuleID'] = $moduleID;
			$values['Accesskey'] = $accesskey;
			$values['Accesslevel'] = $maxAccesslevel;
			$values['SystemID'] = $module->systemID;
			
			// tämän ei saisi luoda tuplia...
			$newMenuID = Table::addRow("system_accessrights",$values, false);
		}
		*/
		
		
		// ---------------------------------------------------
		//   Päivitetään dimensiot
		// ---------------------------------------------------
		
		if ($comments) echo "<br><br><br>";
		// Dimensioneiden päivitys voitaisiin ehkä siirtää omaan funktioonsa, mutta
		// täällä se tulisi samalla kun käydään muutenkin modulit lävitse...
		$dimensions = $moduleinstance->getDimensions();
		echo "<br>Dimension count - " . count($dimensions) . " - " . $classname . " - moduleID: " . $moduleID;
		
		$existingdimensionslist = Table::load("system_dimensions", "WHERE ModuleID=" . $moduleID, true);
		$existingdimensions = array();
		echo "<br>existing dimension count - " . count($existingdimensions);
		foreach($existingdimensions as $index => $dimension) {
			echo "<br>existing dimension - " . $dimension->name;
			$existingdimensions[$dimension->dimensionID] = $dimension;
		}
		echo "<br>Dimension count - " . count($dimensions) . " - " . $classname . " - moduleID: " . $moduleID;
		foreach($dimensions as $dimensionID => $dimension) {
			echo "<br>Dimension - " . $dimension->name . " - " . $dimension->databasetable;
			echo "<br>Dimension - " . $dimensionID . " - " . $dimension->dimensionID;
				
			if (isset($existingdimensions[$dimensionID])) {
				echo "<br>Dimension allready exitst... - " . $dimension->name;				
			} else {
				$values = array();
				$values['DimensionID'] = $dimensionID;
				$values['Name'] = $dimension->name;
				$values['Plural'] = $dimension->plural;
				$values['TableID'] = Table::getTableID($dimension->databasetable);
				$values['Active'] = 0;
				$values['ModuleID'] = $moduleID;
				$values['SystemID'] = $systemID;
				$rowID = Table::addRow("system_dimensions", $values, true);
			}
		}
		
		// ---------------------------------------------------
		//   Päivitetään accesskeys
		// ---------------------------------------------------
		
		$accesskeys = $moduleinstance->getAccessRights();
		$existingkeys = Table::load("system_accesskeys", "WHERE ModuleID=" . $moduleID, true);
		$existingnames = array();
		echo "<br>existing accesskey count - " . count($existingkeys);
		foreach($existingkeys as $index => $accesskey) {
			echo "<br>existing accesskey - " . $accesskey->name;
			$existingnames[$accesskey->name] = $accesskey;
		}
		foreach($accesskeys as $accesskey => $keytype) {
			echo "<br>accesskey - " . $accesskey . " - " . $keytype;
			
			if (isset($existingnames[$accesskey])) {
				echo "<br>accesskey allready exitst... - " . $accesskey;
			} else {
				$values = array();
				$values['Name'] = $accesskey;
				$values['Keytype'] = $keytype;
				$values['ModuleID'] = $moduleID;
				$rowID = Table::addRow("system_accesskeys", $values, false);
			}
		}
		

		// ---------------------------------------------------
		//   Annetaan kaikki käyttöoikeudet asentajalle
		// ---------------------------------------------------
		
		// Pitäisi selvittää mikä on admin-käyttäjäryhmä, pääkäyttäjä...
		// Jos nykyinen käyttäjä ei ole admin-käyttäjryhmä, niin 
		
		
	}
	
	

	public static function createNewClientSystem() {
		
		
	
	}
}