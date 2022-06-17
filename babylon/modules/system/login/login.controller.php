<?php



class Loginstruct {
	public $loginID;
	public $userID;
	public $databasename;
	public $description;
	public $username;
	public $logintype;
	public $systemID;
}



class LoginController extends AbstractController {


	public function getCSSFiles() {
		return array('login.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js');
	}
	
	
	public function getTemplate($action) {
		return 'login';
	}
	
	
	public function indexAction() {
		//$this->registry->template->header = 'Login';
		$this->registry->template->show('system/login','index');
		//$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function logoutAction() {
		session_destroy();
		redirecttotal("system/login/index");
	}
	
	

	/**
	 * 	Tätä kutsuu admin, sisäänkirjautumisen etusivulta, kun luodaan uutta tietokantaa.
	 *
	 */
	public function newdialogAction() {
		$this->registry->template->show('admin/service','createnew');
	}
	
	
	
	public function loginAction() {
		
		
		
		if (isset($_POST['username']) && isset($_POST['password'])) {
			
			if ($_POST['username'] == '') {
				addErrorMessage('Incorrect username or password.5');
			} elseif ($_POST['password'] == '') {
				addErrorMessage('Incorrect username or password.6');
			} else {
				
				$con = ConnectDatabase(CENTRAL_LOGIN_DATABASE);
				$loginlines = Login::loadLines($_POST['username'], $con);
				$systems = Login::loadSystems($con);
				//echo "<br>Loginlines - " . count($loginlines);
				$_SESSION['username'] = $_POST['username'];
				$con->close();
				
				if (count($loginlines) == 0) {
					addErrorMessage('Incorrect username or password.');
				} elseif (count($loginlines) >= 1) {
					$roles = array();
					foreach ($loginlines as $loginID => $login) {
						
						//echo "<br>Connect - " . $login->database;
						$con = ConnectDatabase($login->database);
						
						if ($con == false) {
							echo "<br>Database not exists - " . $login->database;						
						} else {
							//echo "<br>Database - " . $login->database;
							// en tiedä miksi tässä on monikko
							$users = User::loadUsers($login->systemID, $_POST['username'], $_POST['password'], $con);
							//echo "<br>LoadUsers - " . $login->database . " - " . count($users);
							
							if (count($users) == 0) {
								
								// TODO: Tämä ei toimi, jos on poistettu käyttäjä niin tänne tultaneen myös
								
								//$name = "Setup - " . $login->database;
								$logiuser = new Loginstruct();
								$logiuser->loginID = "";
								$logiuser->userID = "";
								$logiuser->database = $login->database;
								$logiuser->description = "Setup new database";
								$logiuser->systemID = $login->systemID;
								$logiuser->systemname = $systems[$login->systemID];
								$logiuser->username = $_POST['username'];
								$logiuser->logintype = 2;	// setup
								$roles[] = $logiuser;
									
							} else {
								foreach($users as $index => $user) {
									//echo "<br>user - " . $user->description;
									//echo "<br>user systemID - " . $user->systemID;
									//$name = Settings::getSetting('system_settings_appname', null, $con);		// TODO: Tämä saadaan systems taulusta
									$name = Settings::getSystemSetting($user->systemID, 'system_settings_appname', null, $con);		// TODO: Tämä saadaan systems taulusta
									$logiuser = new Loginstruct();
									$logiuser->loginID = $login->loginID;
									$logiuser->userID = $user->userID;
									$logiuser->database = $login->database;
									$logiuser->description = $name;
									$logiuser->username = $login->loginname;
									$logiuser->usergroupname = $user->usergroupname;
									$logiuser->usergroupID = $user->usergroupID;
									$logiuser->systemID = $login->systemID;
									$logiuser->systemname = $systems[$login->systemID];
									$logiuser->logintype = 1;	// normal login
									$roles[] = $logiuser;
								}
								$con->close();
							}
							
							
							//mysql_close($con);
						}
						
					}
					$this->registry->roles = $roles;
					//echo "<br>Jeejee - " . get_class($this->registry->template);
					$this->registry->template->show('system/login','selectrole');
					exit;
					
				} elseif (count($loginlines) == 1) {
					
					foreach($loginlines as $index => $user) {
						$_SESSION['userID'] = $user->userID;
						$_SESSION['activemenuid'] = 0;
						$_SESSION['username'] = $user->username;
						$_SESSION['usergroupID'] = $user->usergroupID;
						$_SESSION['languageID'] = "cccc" . $user->language;
						$_SESSION['database'] = $user->database;
						$_SESSION['systemID'] = $user->systemID;
						$_SESSION['windowID'] = 1;
						$_SESSION['windowcounter'] = 1;
						$_SESSION['systemname'] = $systems[$login->systemID];
						
					}
					// initialise session variables		

					//echo "<br>Entrypoint - " . $user->entrypoint;
					//exit;
					
					redirecttotal($user->entrypoint);
				}
			}
		} else {
			if (!isset($_POST['username'])) {
				addErrorMessage('Incorrect username or password.1');
			} elseif ($_POST['username'] == '') {
				addErrorMessage('Incorrect username or password.2');
			} elseif ($_POST['password'] == '') {
				addErrorMessage('Incorrect username or password.3');
			} else {
				addErrorMessage('Incorrect username or password.4');
			}
			session_destroy();
			redirecttotal("system/login/index");
		}
		
		session_destroy();
		redirecttotal("system/login/index");
	}
	
	
	public function changeroleAction() {
		
		$con = ConnectDatabase(CENTRAL_LOGIN_DATABASE);
		$loginlines = Login::loadLines($_SESSION['username'], $con);
		//echo "<br>Loginlines - " . count($loginlines);
		$con->close();
		
		$roles = array();
		foreach ($loginlines as $index => $login) {

			$con = ConnectDatabase($login->database);
		
			if ($con == false) {
				echo "<br>Database not exists - " . $login->database;
			} else {
				//echo "<br>Database - " . $login->database;
				// en tiedä miksi tässä on monikko
				$users = User::loadUsers($login->systemID, $_SESSION['username'], 'password-here', $con);
				//echo "<br>LoadUsers - " . count($users);
				foreach($users as $index => $user) {
					//echo "<br>user - " . $user->description;
					$name = Settings::getSetting('system_settings_appname', null, $con);
					$logiuser = new Loginstruct();
					$logiuser->loginID = $login->loginID;
					$logiuser->userID = $user->userID;
					$logiuser->database = $login->database;
					$logiuser->description = $name;
					$logiuser->username = $user->description;
					$logiuser->usergroupname = $user->usergroupname;
					$logiuser->systemID = $user->systemID;
					$roles[] = $logiuser;
				}
				$con->close();
				//mysql_close($con);
			}
		
		}
		$this->registry->roles = $roles;
		//echo "<br>Jeejee - " . get_class($this->registry->template);
		$this->registry->template->show('login','selectrole');
	}
	
	
	public function selectroleAction() {
		
		$con = ConnectDatabase();
		
		$this->registry->users = User::loadUsersWithName($_SESSION['username']);
		$this->registry->template->header = 'Login';
		$this->registry->template->show('system/login','selectrole');
	}
	
	
	public function roleselectedAction() {
		
		global $mysqli;
		$comments = false;
		
		$loginID = $_GET['loginID'];
		$userID = $_GET['userID'];
		$usergroupID = $_GET['usergroupID'];
		$counter = 0;
		
		if ($comments) echo "<br>LoginID - " . $loginID;
		if ($comments) echo "<br>userID - " . $userID;
		if ($comments) echo "<br>usergroupID - " . $usergroupID;
		
		
		$con = ConnectDatabase(CENTRAL_LOGIN_DATABASE);
		if ($con == null) echo "<br>connull";
		$login = Login::loadID($loginID, $con);
		
		if ($comments) echo "<br>Myload ok - " . $login->database;
		
		$con = ConnectDatabase($login->database);
		
		/*
		echo "<br>Database - " . $_SESSION['database'];
		$tablecount = Table::getTableCount();
		if ($tablecount == 0) {
			redirecttotal("admin/install/index");
		}
		echo "<br>Tablecount - " . $tablecount;
		exit;
		*/
		
		$this->registry->users = User::loadUsersWithName($login->loginname, $con);
		
		foreach($this->registry->users as $index => $user) {

			if ($comments) echo "<br>users - " . $user->userID . " - " . $userID;
			
			if (($user->userID == $userID) && ($user->usergroupID == $usergroupID)) {
				$_SESSION['userID'] = $user->userID;
				$_SESSION['username'] = $user->loginname;
				$_SESSION['activemenuid'] = 0;
				$_SESSION['usergroupID'] = $user->usergroupID;
				$_SESSION['windowID'] = 1;
				$_SESSION['windowcounter'] = 1;
				$_SESSION['languageID'] = $user->languageID;
				$_SESSION['database'] = $login->database;
				$_SESSION['systemID'] = $login->systemID;
				$_SESSION['mastersystemID'] = $login->systemID;
				$_SESSION['masterusergroupID'] = $user->usergroupID;
				$_SESSION['template'] = $user->template;
				//exit;
				
				$mysqli = $con;
				$system = Table::loadRow("system_systems", $login->systemID);
				$_SESSION['frontpage'] = $system->frontpage;
				if ($system->fortpage == "") {
					$system->frontpage = "system/frontpage/index&wID=1";
				}
				$_SESSION['systemname'] = $system->name;
				
				// Ladataan sessioniin actionpathit
				$actions = Table::load("system_actionpaths", "WHERE Active=1");
				foreach($actions as $actionID => $action) {
					$_SESSION['AC_' . $action->actionpath] = $action->tablelist;
				}
				
				// asetetaan aktiivinen companyID, onkohan tämä tarpeellinen jos 
				if (Table::exists("system_companies")) {
					$companies = Table::load("system_companies", "WHERE SystemID=" . $login->systemID);
					foreach($companies as $companyIndex => $company) {
						$_SESSION['companyID'] = $company->companyID;
					}
					if (count($companies) == 0) {
						$_SESSION['companyID'] = "no companyID";
					}
				} else {
					echo "<br>Companytable not exists";
				}
				if (!$comments) redirecttotal($system->frontpage); // TODO: tämä pitää hakea asetuksista
			} else {
				//echo "<br>no redirect - " . $user->username . " "  . $user->entrypoint;
			}
			$counter++;
		}
	}
	
	
	// Tätä funktiota kutsutaan, aina siisäänkirjautuessa, tämän pitäisi alustaa kaikki session muuttujat
	//  - user
	//  - menu (siirretään ehkä session muuttujaan, niin ei tarvitse ladata tietokannasta jokakerta
	//  - dimensiot (ehkä niiden olemassaolo ainakin on tarpeen esittää täällä)
	//  - multicompany (ilmoittaa onko tytäryritykset käytössä, lisävalikoita moneen paikkaan)
	//  - toimipisteet ehkä, toimialat ehkä
	//  - aktiiviset modulit (nämäkin ehkä sessioniin, niin ei tarvitse jokakerta ladata)
	//  - joitakin käyttäjän oletusasetuksia: toimipiste, toimiala
	//  - tehokkuussyistä ehkä myös tablet ja columnit tänne, säästetään myös useita hakuja
	//	- pitäisikö toimialoja ja toimipisteitä olla aina oletuksena yksi? Näin ei tarvitse asettaa sitä enää erikseen?
	//
	//  * tarvitaan erilliset assessfunctiot näitä varten, 
	//  * ehkä jotkin näistä on tarpeen toteuttaa luokkien tallennuksena, ei pelkästään listana, listoja kuitenkin pääasiassa
	//  * Tehokkuussyistä saattaa olla tarpeen logittaa, että mitä kaikki tauluja yhdessä kutsussa haetaan
	private function initSessionVariables($user) {
			
	}
}
