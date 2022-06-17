<?php



class UsergroupsController extends AbstractController {


	public function getCSSFiles() {
		//return array('menu.css','testcss.php');
		return array('menu.css','babylon.css');
	}


	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
	

	public function indexAction() {
		//$this->showusergroupsAction();
		$this->registry->template->show('system/error','unknown');
	}


	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************



	public function showusergroupsAction() {

		$usergroups = Table::load("system_usergroups");
		
		/*
		// TODO: Ei mitään muistikuvaa, mitä tällä hierarkialla ja conceptID:sseillä haettiin
		//		 Hierarkia on vielä mietinnässä, 
		$conceptIDs = array();
		$rootIDs = array();
		
		foreach($usergroups as $index => $usergroup) {
				
			if ($usergroup->parentID == 0) {
				$rootIDs[$usergroup->usergroupID] = $usergroup;
			} else {
				$conceptIDs[$usergroup->parentID]->addChild($usergroup);
			}
			$conceptIDs[$usergroup->usergroupID] = $usergroup;
		}
		*/
		$this->registry->usergroups = $usergroups;
		
		
		$this->registry->template->show('admin/usergroups','usergroups');
	}

	


	public function showusergroupAction() {
	
		$comments = false;
	
		$usergroupID = $_GET['id'];
		$this->registry->usergroup = Table::loadRow("system_usergroups", $usergroupID);
	
		// Ladataan dimensiot ja käyttäjien oikeudet niihin...
		$tempdimensions = Table::load('system_dimensions','WHERE Active=1');
		$dimensions = array();	// temp dimension palauttaa RowID:n keynä
		foreach($tempdimensions as $index => $dimension) {
			$tablename = Table::getTableName($dimension->tableID);
			if ($comments) echo "<br>Table - " . $tablename;
			$content = Table::load($tablename);
			$dimension->content = $content;
			$dimensions[$dimension->dimensionID] = $dimension;
		}
		
		$this->registry->dimensionvisibility = Table::load('system_usergroupdimensions','WHERE UsergroupID=' . $usergroupID);
		$dimensionaccess = array();
		foreach($this->registry->dimensionvisibility as $index => $dimensionvisibility) {
			if ($dimensionvisibility->dimensionvalueID == 0) {
				if (isset($dimensions[$dimensionvisibility->dimensionID])) {
					$dimension = $dimensions[$dimensionvisibility->dimensionID];
					$dimension->accesslevel = $dimensionvisibility->accesslevel;
					if ($comments) echo "<br> - dimension found - " . $dimension->name . " - " . $dimensionvisibility->accesslevel;
				} else {
					if ($comments) echo "<br> -- unknown dimension - " . $dimensionvisibility->dimensionID  ." (todennäköisesti poistettu)";
				}
			}
		}
		
		foreach($dimensions as $index => $dimension) {

			if ($dimension->accesslevel == AbstractModule::VISIBILITY_SELECTED) {
				$visibilities = array();
				foreach($this->registry->dimensionvisibility as $index => $dimensionvisibility) {
					if ($dimensionvisibility->dimensionID == $dimension->dimensionID) {
						if ($dimensionvisibility->dimensionvalueID > 0) {
							$visibilities[$dimensionvisibility->dimensionvalueID] = $dimensionvisibility->accesslevel;
							if ($comments) echo "<br> - visibility found - " . $dimension->name . " - " . $dimensionvisibility->accesslevel;
						}
					}
				}
				foreach($dimension->content as $index => $contentitem) {
					if ($comments) echo "<br>Contentitem - " . $contentitem->name . " - " . $contentitem->getID();
					if (isset($visibilities[$contentitem->getID()])) {
						if ($comments) echo "<br>Contentitem - " . $contentitem->getID();
						$accesslevel = $visibilities[$contentitem->getID()];
						$contentitem->accesslevel = $accesslevel;
					} else {
						$contentitem->accesslevel = 0;
					}
				}
			} else {
				foreach($dimension->content as $index => $contentitem) {
					if ($dimension->accesslevel == AbstractModule::VISIBILITY_ALL) {
						$contentitem->accesslevel = 1;
					} else {
						$contentitem->accesslevel = 0;
					}
				}
			}
		}
		
		
		$this->registry->dimensions = $dimensions;
		
		
		
		if ($comments) echo ("<br>Usergroup - " . $usergroupID);
	
		$this->registry->modules =  Table::load("system_modules", "WHERE Active=1");
		$groupaccessrights =  Table::load("system_usergroupaccessrights", "WHERE UsergroupID=" . $usergroupID);
		$moduleaccess = array();
		foreach($groupaccessrights as $index => $groupaccess) {
			if ($groupaccess->accesskeyID == 0) {
				$moduleaccess[$groupaccess->moduleID] = $groupaccess->accesslevel;
			}
		}
			
		
	
		if ($this->registry->accessrights == null) {
			if ($comments) echo "<br>no accessrights found";
			$this->registry->accessrights = array();
		}
	
		$modulepath = SITE_PATH . "modules/";
	
		//include ($modulefile);
		//echo "<br>Modulefile: " . $modulefile;
		//$classname = $modulename . "Module";
		//$module = new $classname();
	
		$moduleitems = array();
		$accesskeys = null;
		
		foreach($this->registry->modules as $moduleID => $module) {
				
			/*
				$modulefile = $modulepath . $module->modulename . "/" . $module->modulename . ".module.php";
				include_once ($modulefile);
				if ($comments) echo "<br>Modulefile - " . $modulefile;
				$classname = ucfirst($module->modulename) . "Module";
				if ($comments) echo "<br>Moduleclassname - " . $classname;
				$moduleinstance = new $classname();
				*/
			//if ($comments) echo "<br> -- Accesskey - " . $moduleaccessright;
			//$accesskeyID = $accesskeys[$accesskey];
	
			if (isset($moduleaccess[$moduleID])) {
				if ($comments) echo "<br> -- -- moduleaccess found - " . $moduleID . " - " . $moduleaccess[$moduleID] . " - " . $module->name;
				$module->accesslevel = $moduleaccess[$moduleID];
			} else {
				if ($comments) echo "<br> -- -- no moduleaccess found - " . $moduleID . " - " . $module->name;
				$module->accesslevel =  AbstractModule::ACCESSRIGHT_NONE;
			}
				
			if ($module->accesslevel == AbstractModule::ACCESSRIGHT_CUSTOM) {
				//echo "<br> -- custom needed";
				
				/*
				$modulefile = $modulepath . $module->modulename . "/" . $module->modulename . ".module.php";
				include_once ($modulefile);
				if ($comments) echo "<br>Modulefile - " . $modulefile;
				$classname = ucfirst($module->modulename) . "Module";
				if ($comments) echo "<br>Moduleclassname - " . $classname;
				$moduleinstance = new $classname();
				$moduleaccessrights = $moduleinstance->getAccessRights();
				*/
				
				if ($accesskeys == null) {
					$accesskeys =  Table::load("system_accesskeys");
					/*
					$accesskeystemp =  Table::load("system_accesskeys");
					$accesskeys = array();
					foreach($accesskeystemp as $index => $accesskey) {
						$accesskeys[$accesskey->name] = $accesskey;
					}
					*/
				}
				
				
				$currentaccesskeys = array();
				foreach($accesskeys as $accesskeyID => $accesskey) {
					if ($accesskey->moduleID == $module->moduleID) {
						$currentaccesskeys[$accesskey->accesskeyID] = $accesskey;
					}
				}
				if (count($currentaccesskeys) == 0) {
					echo "<br>Accesskey not found for module - " . $module->name;
				}
				
				// Sisältää asianomaisen käyttäjäryhmän kaikki asetetut accessrightit
				$usergroupaccessrights = array();
				foreach($groupaccessrights as $index => $groupaccess) {
					if ($groupaccess->moduleID == $module->moduleID) {
						$usergroupaccessrights[$groupaccess->accesskeyID] = $groupaccess;
					}
				}
				
				$moduleitemaccess = array();
				foreach($currentaccesskeys as $accesskeyID => $accesskey) {
					if (isset($usergroupaccessrights[$accesskey->accesskeyID])) {
						$accesskey->name = getResourceText($accesskey->name);
						if (isset($usergroupaccessrights[$accesskey->accesskeyID])) {
							$item = $usergroupaccessrights[$accesskey->accesskeyID];
							//echo "<br>- item " . $item->rowID;
							$accesskey->accesslevel = $usergroupaccessrights[$accesskey->accesskeyID]->accesslevel;
						} else {
							$accesskey->accesslevel = 0;
						}
						$moduleitemaccess[] = $accesskey;
					} else {
						$newaccessitem = new Row();
						$newaccessitem->usergroupID = $usergroupID;
						$newaccessitem->moduleID = $module->moduleID;
						$newaccessitem->accesskeyID = $accesskey->accesskeyID;
						$newaccessitem->accesslevel = 0;
						$newaccessitem->keytype = $accesskey->keytype;
						$newaccessitem->name = getResourceText($accesskey->name);
						$moduleitemaccess[] = $newaccessitem;
					}				
				}
				$moduleitems[$module->moduleID] = $moduleitemaccess;
			}
			if ($comments) echo "<br>--------------------------------------";
		}
	
		$this->registry->moduleitems = $moduleitems;
		//$this->registry->accessrows = $accessrows;
	
	
		$this->registry->template->show('admin/usergroups','usergroup');
	}
	
	
	
	public function updatedimensionvisibilityAction() {
	
		$comments = false;
		$usergroupID = $_GET['usergroupID'];
		$dimensionID = $_GET['dimensionID'];
		if (isset($_GET['dimensionvalueID'])) {
			$dimensionvalueID = $_GET['dimensionvalueID'];
		} else {
			$dimensionvalueID = 0;
		}
		$accesslevel = $_GET['accesslevel'];
	
		if ($accesslevel == 0) {
			// Pitäisi ehkä poistaa myös kaikki muutkin rivit...
			if ($dimensionvalueID == 0) {
				$success = Table::deleteRowsWhere("system_usergroupdimensions", "WHERE UsergroupID=" . $usergroupID . " AND DimensionID=" . $dimensionID, $comments);
			} else {
				$success = Table::deleteRowsWhere("system_usergroupdimensions", "WHERE UsergroupID=" . $usergroupID . " AND DimensionID=" . $dimensionID . " AND DimensionvalueID=" . $dimensionvalueID, $comments);
			}
		} else {
			$access = Table::loadRow("system_usergroupdimensions", "WHERE UsergroupID=" . $usergroupID . " AND DimensionID=" . $dimensionID . " AND DimensionvalueID=" . $dimensionvalueID, $comments);
				
			if ($access == null) {
				if ($comments) echo "<br>accessrow null";
			} else {
				if ($comments) echo "<br>accessrow found - " . $access->rowID;
			}
				
			if ($access != null) {
				$values = array();
				$values['Accesslevel'] = $accesslevel;
				Table::updateRow("system_usergroupdimensions",$values, $access->rowID, $comments);
			} else {
				$values = array();
				$values['UsergroupID'] = $usergroupID;
				$values['DimensionID'] = $dimensionID;
				$values['DimensionvalueID'] = $dimensionvalueID;
				$values['Accesslevel'] = $accesslevel;
				$userID = Table::addRow("system_usergroupdimensions", $values, $comments);
			}
		}
		if (!$comments) redirecttotal('admin/usergroups/showusergroup&id=' . $usergroupID, null);
	}
	
	
	

	public function updateusergroupAction() {
	
		$comments = false;
		$usergroupID = $_GET['usergroupID'];
		$name = $_GET['name'];

		$values = array();
		$values['Name'] = $name;
		Table::updateRow("system_usergroups", $values, $usergroupID, $comments);
		redirecttotal('admin/usergroups/showusergroup&id=' . $usergroupID, null);
	}
	
	
	
	public function updateusergroupmoduleaccessAction() {

		$comments = false;
		$usergroupID = $_GET['usergroupID'];
		$moduleID = $_GET['moduleID'];
		if (isset($_GET['accesskeyID'])) {
			$accesskeyID = $_GET['accesskeyID'];
		} else {
			$accesskeyID = 0;
		}
		$accesslevel = $_GET['accesslevel'];
		
		// TODO: adminkäyttäjäryhmältä ei saisi sallia käyttäjänhallinnan poistoa.
		
		if ($accesslevel == 0) {
			if ($accesskeyID == 0) {
				$success = Table::deleteRowsWhere("system_usergroupaccessrights", "WHERE UsergroupID=" . $usergroupID . " AND ModuleID=" . $moduleID, $comments);
			} else {
				$success = Table::deleteRowsWhere("system_usergroupaccessrights", "WHERE UsergroupID=" . $usergroupID . " AND ModuleID=" . $moduleID . " AND AccesskeyID=" . $accesskeyID, $comments);
			}
		} else {
			$access = Table::loadRow("system_usergroupaccessrights", "WHERE UsergroupID=" . $usergroupID . " AND ModuleID=" . $moduleID . " AND AccesskeyID=" . $accesskeyID, $comments);
			
			if ($access == null) {
				if ($comments) echo "<br>accessrow null";
			} else {
				if ($comments) echo "<br>accessrow found - " . $access->rowID;
			}
			
			if ($access != null) {
				$values = array();
				$values['Accesslevel'] = $accesslevel;
				Table::updateRow("system_usergroupaccessrights",$values, $access->rowID, $comments);
			} else {
				$values = array();
				$values['UsergroupID'] = $usergroupID;
				$values['ModuleID'] = $moduleID;
				$values['AccesskeyID'] = $accesskeyID;
				$values['Accesslevel'] = $accesslevel;
				$userID = Table::addRow("system_usergroupaccessrights", $values, $comments);
			}
		}
		if (!$comments) redirecttotal('admin/usergroups/showusergroup&id=' . $usergroupID, null);
	}
		
	
	public function insertusergroupAction() {
		$values = array();
		$values['Name'] = $_GET['name'];
		$usergroupID = Table::addRow("system_usergroups", $values, false);
		redirecttotal('admin/usergroups/showusergroup&id=' . $usergroupID,null);
	}
	

	public function updateusergroupmenuAction() {

		$usergroupID = $_GET['id'];

		Install::createSystemMenu($_SESSION['systemID'], $usergroupID, true);
		
		echo "<br>Redirect ...";
		exit;
	}
	

	public function removeusergroupAction() {
		echo "<br>Not implemented...";
		exit;
	}
	
}

?>