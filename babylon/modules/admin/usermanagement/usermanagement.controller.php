<?php

/**
 * Settingscontrollerissa hallitaan jarjestelman asetuksia. Periaatteessa jokaista kaytassa olevaa modulia varten on oma
 * sectioninsa, jokaiselta modulilta tulisi siis saada tarpeelliset asetukset.
 * 
 * Perusasetuksia on muutamia, ainakin ulkoasuun ja logoon liittyvia asetuksia, mutta periaatteessa namakin voisivat tulla
 * suoraan kaytettavissa olevista oletusmoduleista. Lahinna kai niin, etta system module on automaattisesti kaytassa, myas admin
 * moduli on todennakaisesti automaattisesti kaytassa. Osa moduleista on tosin pelkastaan hallinta kayttaan, esim. database.
 * 
 *
 */
class UsermanagementController extends AbstractController {


	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}

	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	// Annetaan toistaiseksi olla, josko tulee ylimääräisiä kutsuja, tämähän pitäisi 
	// tulla frameworkiltä.
	public function indexAction() {
		$this->registry->template->show('system/error','unknown');
	}
	
	
	// Tätä kutsutaan menusta, muistaa mihin välilehdelle jäätiin
	public function showmanagementAction() {
		
		$viewID = getModuleSessionVar("viewID",1);
		if ($viewID == 1) {
			$this->showusersAction();		
		}
		if ($viewID == 2) {
			$this->showusergroupsAction();
		}
		if ($viewID == 3) {
			$this->showteamsAction();
		}
	}
	
	
	public function showusersAction() {
	
		updateActionPath("Käyttäjät");
		$this->registry->viewID = getModuleSessionVar("viewID",1);
		$users = Table::load('system_users');
		foreach($users as $index => $user) {
			$user->itemID = $user->userID . "-" . $user->usergroupID;
		}
		$this->registry->users = $users;

		$this->registry->usergroups = Table::load('system_usergroups');
		$this->registry->template->show('admin/usermanagement','users');
	}

	

	public function showteamsAction() {
	
		updateActionPath("Tiimit");
		$this->registry->viewID = getModuleSessionVar("viewID",1);
		$users = Table::load('system_users');
		foreach($users as $index => $user) {
			$user->itemID = $user->userID . "-" . $user->usergroupID;
		}
		$this->registry->users = $users;
	
		$this->registry->usergroups = Table::load('system_usergroups');
		$this->registry->template->show('admin/usermanagement','users');
	}
	
	
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	

	public function showpersonalsettingsAction() {
		$userID = $_SESSION['userID'];
		$usergroupID = $_SESSION['usergroupID'];
		$this->loadUserData($userID, $usergroupID);
	}
	
	
	public function showuserAction() {
		$itemID = $_GET['id'];
		$items = explode("-", $itemID);
		$userID = $items[0];
		$usergroupID = $items[1];
		$this->loadUserData($userID, $usergroupID);
	}

	

	public function showusergroupsAction() {
		
		updateActionPath("Käyttäjäryhmät");
		$this->registry->viewID = getModuleSessionVar("viewID",1);
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
	
	
		$this->registry->template->show('admin/usermanagement','usergroups');
	}
	
	
	
	
	public function showusergroupAction() {
	
		$comments = false;
	
		$usergroupID = $_GET['id'];
		$this->registry->usergroup = Table::loadRow("system_usergroups", $usergroupID);
		updateActionPath(ucfirst($this->registry->usergroup->name));
		
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
	
	
		$this->registry->template->show('admin/usermanagement','usergroup');
	}
	
	
	
	
	
	private function loadUserData($userID, $usergroupID) {
	
		$this->registry->user = Table::loadRow('system_users', "WHERE UserID=" . $userID . " AND UsergroupID=" . $usergroupID);
		
		$this->registry->usergroups = Table::load('system_usergroups');
		$tempdimensions = Table::load('system_dimensions','WHERE Active=1');
		$dimensions = array();
		foreach($tempdimensions as $index => $dimension) {
			$dimensions[$dimension->dimensionID] = $dimension;
			$tablename = Table::getTableName($dimension->tableID);
			$content = Table::load($tablename);
			$dimension->content = $content;
			//echo "<br>Dimension - " . $dimension->name . " - " . $tablename;
			$dimension->dimensionvalueID = 0;
		}
		
		$usergroupdimensions = Table::load('system_usergroupdimensions', "WHERE UsergroupID=" . $this->registry->user->usergroupID);
		foreach($usergroupdimensions as $index => $usergroupdimension) {
			if ($usergroupdimension->dimensionvalueID == 0) {
				$dimension = $dimensions[$usergroupdimension->dimensionID];
				$dimension->visibility = $usergroupdimension->accesslevel;
				//echo "<br>Dimensionvisibility - " . $dimension->name . ", dimensionID:" . $dimension->dimensionID . ", visibility:" . $dimension->visibility;
			}
		}
		foreach($usergroupdimensions as $index => $usergroupdimension) {
			$dimension = $dimensions[$usergroupdimension->dimensionID];
			if ($dimension->visibility == AbstractModule::VISIBILITY_ALL) {
				foreach($dimension->content as $index => $contentItem) {
					//echo "<br> -- visibility all - " . $contentItem->name;
					$contentItem->visibleselected = 1;
				}
			}
			if ($dimension->visibility == AbstractModule::VISIBILITY_SELECTED) {
				if ($usergroupdimension->dimensionvalueID > 0) {
					$contentItem = $dimension->content[$usergroupdimension->dimensionvalueID];
					//echo "<br> -- visibility selected - " . $contentItem->name;
					$contentItem->visibleselected = 1;
				}
			}
		}
		
		
		$userdimensions = Table::load('system_userdimensions', "WHERE UserID=" . $userID. " AND UsergroupID=" . $usergroupID);
		foreach($userdimensions as $index => $userdimension) {
			$dimension = $dimensions[$userdimension->dimensionID];
			$dimension->dimensionvalueID = $userdimension->dimensionvalueID;
			$var = "dimensionvalueID-" . $dimension->dimensionID;
			$this->registry->user->$var = $userdimension->dimensionvalueID;
			//echo "<br>Dimension visibility - " . $dimension->name . " - " . $dimension->visibility . " - " . $userdimension->dimensionvalueID;
			
			if ($dimension->visibility == AbstractModule::VISIBILITY_USER) {
				// Käyttäjällä on näkyvyys valittuihin, on editoitavissa
				foreach($userdimension->visibilityvalues as $index => $contentID) {
					//echo "<br>Dimension " . $dimension->name . " - visiblevalue: " . $contentID;
					$contentitem = $dimension->content[$contentID];
					$contentitem->visibleselected = 1;
				}
			}
			if ($dimension->visibility == AbstractModule::VISIBILITY_SELECTED) {
				// Käyttäjällä on näkyvyys valittuihin, ei editoitavissa
			}
			if ($dimension->visibility == AbstractModule::VISIBILITY_ALL) {
				// Käyttäjällä on näkyvyys kaikkiin, ei editoitavissa
				foreach($dimension->content as $index => $contentitem) {
					$contentitem->visibleselected = 1;
				}
			}
		}
		
		$this->registry->dimensions = $dimensions;
		$this->registry->template->show('admin/usermanagement','user');
	}
	
	
	// TODO: Mieti pitääkö näitä päivittää global login tauluun
	// TODO: Näiden tietojen pitää olla nähdäkseni synkassa jonkun työntekijätaulun kanssa mahd.
	//			- pitää ehkä tsekata onko jokin tietty moduli käytössä
	public function updateuserAction() {
	
		$userID = $_GET['id'];
		$username = $_GET['username'];
		$firstname = $_GET['firstname'];
		$lastname = $_GET['lastname'];
		$phone = $_GET['phonenumber'];
		if (isset($_GET['email'])) $email = $_GET['phonenumber'];
		$usergroupID = $_GET['usergroupID'];
		
		$values = array();
		$values['UsergroupID'] = $usergroupID;
		$values['Firstname'] = $firstname;
		$values['Lastname'] = $lastname;
		$values['Phonenumber'] = $phone;
		Table::updateRow("system_users",$values, $userID);
		
		redirecttotal('admin/usermanagement/showuser&id=' . $userID, null);
	}
		
	
	public function updateusersettingsAction() {
	
		$comments = true;
		$userID = $_GET['id'];
		$usergroupID = $_GET['usergroupID'];
		
		$dimensions = Table::load('system_dimensions','WHERE Active=1');
		$userdimensions = Table::load('system_userdimensions','WHERE UserID=' . $userID . " AND UsergroupID=" . $usergroupID);
		
		foreach($dimensions as $index => $dimension) {
			$key = "dimensionvalue-" . $dimension->dimensionID;
			if (isset($_GET[$key])) {
				if ($_GET[$key] != "") {
					$found = false;
					foreach ($userdimensions as $index => $userdimension) {
						if ($userdimension->dimensionID == $dimension->dimensionID) {
							$value = $_GET[$key];
							if ($value != $userdimension->dimensionvalueID) {
								$values = array();
								$values['DimensionvalueID'] = $value;
								Table::updateRow("system_userdimensions",$values, $userdimension->rowID);
							}
							$found = true;
						}
					}
					if ($found == false) {
						$values = array();
						$values['UserID'] = $userID;
						$values['UsergroupID'] = $usergroupID;
						$values['DimensionID'] =  $dimension->dimensionID;
						$values['DimensionvalueID'] =  $_GET[$key];
						$values['Visibilityvalues'] =   $_GET[$key];
						$taskID = Table::addRow("system_userdimensions", $values, false);
					}
				} else {
					if ($comments) echo "<br>Dimension not setted - " . $key;
				}
			} else {
				echo "<br>Dimensionvalue not receivied - " . $dimension->name;
				exit;
			}
		}
		if (!$comments) redirecttotal('admin/usermanagement/showuser&id=' . $userID . "-" . $usergroupID, null);
	}
	
	
	
	public function checkuservisibilityAction() {
	
		$comments = false;
		$userID = $_GET['userID'];
		$usergroupID = $_GET['usergroupID'];
		$dimensionID = $_GET['dimensionID'];
		$contentID = $_GET['contentID'];
		
		// TODO: Pitänee tarkistaa käyttäjän oikeudet usergrouprighttista varmuudeksi
		
		$userdimension = Table::loadRow('system_userdimensions','WHERE UserID=' . $userID . " AND UsergroupID=" . $usergroupID . " AND DimensionID=" . $dimensionID);
		
		if ($userdimension == null) {
			$values = array();
			$values['UserID'] = $userID;
			$values['UsergroupID'] = $usergroupID;
			$values['DimensionID'] =  $dimensionID;
			$values['DimensionvalueID'] =  0;
			$values['Visibilityvalues'] =  $contentID;
			$taskID = Table::addRow("system_userdimensions", $values, $comments);
		} else {
			$visibilityvalues = array();
			foreach($userdimension->visibilityvalues as $index => $value) {
				$visibilityvalues[$value] = $value;
			} 
			$visibilityvalues[$contentID] = $contentID;
			$values = array();
			$values['Visibilityvalues'] = implode(":", $visibilityvalues);
			Table::updateRow("system_userdimensions",$values, $userdimension->rowID);
		}
		
		echo "1";
		return;
	}
	
	
	public function uncheckuservisibilityAction() {
	
		$comments = false;
		$userID = $_GET['userID'];
		$usergroupID = $_GET['usergroupID'];
		$dimensionID = $_GET['dimensionID'];
		$contentID = $_GET['contentID'];

		// TODO: Pitänee tarkistaa käyttäjän oikeudet usergrouprighttista varmuudeksi
		
		$userdimension = Table::loadRow('system_userdimensions','WHERE UserID=' . $userID . " AND UsergroupID=" . $usergroupID . " AND DimensionID=" . $dimensionID, true);
	
		if ($userdimension == null) {
			$values = array();
			$values['UserID'] = $userID;
			$values['DimensionID'] =  $dimensionID;
			$values['DimensionvalueID'] =  0;
			$values['Visibilityvalues'] =  "";
			$taskID = Table::addRow("system_userdimensions", $values, $comments);
		} else {
			$visibilityvalues = array();
			foreach($userdimension->visibilityvalues as $index => $value) {
				if ($value != $contentID) {
					$visibilityvalues[$value] = $value;
				}
			} 
			$values = array();
			$values['Visibilityvalues'] = implode(":", $visibilityvalues);
			Table::updateRow("system_userdimensions",$values, $userdimension->rowID);
		}
		
		echo "1";
		return;
	}
	
	
	
	public function adduserAction() {

		$comments = true;
		
		$email = $_GET['email'];
		$firstname = $_GET['firstname'];
		$lastname = $_GET['lastname'];
		$phonenumber = $_GET['phonenumber'];
		$usergroupID = $_GET['usergroupID'];

		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			if ($comments) echo $email . " is a valid email address";
		} else {
			if ($comments) echo $email . " is not a valid email address";
			exit;
		}
		
		$existingusers = Table::load("system_users", "WHERE Username='" . $email . "'");
		
		if (count($existingusers) > 0) {
			echo "<br> -- Sähköposti löytyi jo kyseisestä järjestelmästä";
			$sameusergroup = Table::load("system_users", "WHERE Username='" . $email . "' AND UsergroupID=" . $usergroupID);
			if (count($sameusergroup) > 0) {
				echo "<br> -- käyttäjä samalla käyttäjäryhmällä löytyi, ei voida lisätä";
				exit;
			} else {
				echo "<br> -- lisätään sama käyttäjä eri käyttäjäryhmällä";
				
				foreach($existingusers as $index => $user) {}
				echo "<br> -- uusi userID - " . $user->userID;
				
				$values = array();
				$values['UserID'] = $user->userID;
				$values['UsergroupID'] =  $usergroupID;
				$values['Firstname'] =  $firstname;
				$values['Lastname'] =  $lastname;
				$values['Phonenumber'] =  $phonenumber;
				$values['Username'] =  $email;
				$values['Email'] =  $email;
				$values['Template'] =  $user->template;
				$values['Password'] =  "abcd";		// Normaalissa tapauksessa salasana hoidetaan sähköpostilla
				$userID = Table::addRowWithKey("system_users", $values, $comments);
			}
		} else {
			echo "<br> -- luodaan uusi käyttäjätunnus - " . $email;
		}
		
		// TODO: tänne pääsy on testaamatta, ylhäällä exit;
		redirecttotal('admin/usermanagement/showuser&id=' . $userID . "-" . $usergroupID, null);
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
		if (!$comments) redirecttotal('admin/usermanagement/showusergroup&id=' . $usergroupID, null);
	}
	
	
	
	
	public function updateusergroupAction() {
	
		$comments = false;
		$usergroupID = $_GET['usergroupID'];
		$name = $_GET['name'];
	
		$values = array();
		$values['Name'] = $name;
		Table::updateRow("system_usergroups", $values, $usergroupID, $comments);
		redirecttotal('admin/usermanagement/showusergroup&id=' . $usergroupID, null);
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
		if (!$comments) redirecttotal('admin/usermanagement/showusergroup&id=' . $usergroupID, null);
	}
	
	
	public function insertusergroupAction() {
		$values = array();
		$values['Name'] = $_GET['name'];
		$usergroupID = Table::addRow("system_usergroups", $values, false);
		redirecttotal('admin/usermanagement/showusergroup&id=' . $usergroupID,null);
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
