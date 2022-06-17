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
class UsersController extends AbstractController {


	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}

	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}

		
	public function indexAction() {

		/*
		$users = Table::load('system_users');
		foreach($users as $index => $user) {
			$user->itemID = $user->userID . "-" . $user->usergroupID;
		}
		$this->registry->users = $users;
		
		$this->registry->usergroups = Table::load('system_usergroups');
		$this->registry->template->show('admin/users','users');
		*/
		$this->registry->template->show('system/error','unknown');
	}
	
	
	public function showusersAction() {
	
		$users = Table::load('system_users');
		foreach($users as $index => $user) {
			$user->itemID = $user->userID . "-" . $user->usergroupID;
		}
		$this->registry->users = $users;

		$this->registry->usergroups = Table::load('system_usergroups');
		$this->registry->template->show('admin/users','users');
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
		$this->registry->template->show('admin/users','user');
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
		
		redirecttotal('admin/users/showuser&id=' . $userID, null);
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
		if (!$comments) redirecttotal('admin/users/showuser&id=' . $userID . "-" . $usergroupID, null);
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
		redirecttotal('admin/users/showuser&id=' . $userID . "-" . $usergroupID, null);
	}
}

?>
