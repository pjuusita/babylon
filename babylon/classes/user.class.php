<?php

	
class User {

	public $userID;
	public $loginname;
	public $password;
	public $username;
	public $entrypoint;
	public $description;
	public $usergroupID;
	public $languageID;
	public $database;
	public $systemID;
	public $template;
	
	
	public function __construct($row) {
		
		if ($row != null) {
			$this->userID = $row['UserID'];
			//$this->loginname = $row['Loginname'];
			$this->password = $row['Password'];
			$this->username = $row['Username'];
			//$this->entrypoint = $row['Entrypoint'];
			//$this->description = $row['Description'];
			$this->usergroupID = $row['UsergroupID'];
			$this->systemID = $row['SystemID'];
			if (isset($row['LanguageID'])) {
				$this->languageID = $row['LanguageID'];
			} else {
				$this->languageID = 0;		// TODO: system languageID
			}
			$this->languageID = $row['LanguageID'];
				
			if (isset($row['Template'])) {
				$this->template = $row['Template'];
			} else {
				echo "<br>Warning: Template missing";
				$this->template = "menu";
			}
			//$this->languageID = $row['LanguageID'];
			//$this->database = $row['Database'];
		}
	}

	
	public static function createWithParams($id, $name, $productID = "") {
		$instance = new self(null);
		return $instance;
	}
	
	
	
	public static function createWithRow($row,$lang = 1) {
		$instance = new self($row);
		return $instance;
	}
	

	public function getChildCount() {
		return count($this->childs);
	}

	
	public function getChild($index) {
		return $this->childs[$index];
	}

	public function addChild($child) {
		$this->childs[] = $child;
	}

	public function getID() {
		return $this->featureID;
	}

	public function getName() {
		return $this->name;
	} 
	
	
	public static function loadUsers($systemID, $username, $password, $con = NULL) {
		
		//echo "<br>LoadUsers";
		
		if ($con == null) {
			global $mysqli;
		} else {
			$mysqli = $con;
		}
		
		
		//global $mysqli;
		//echo "SystemID = " . $systemID;
		//addErrorMessage("Username = " . $username);
		//addErrorMessage("Password = " . $password);
			
		//$sql = "SELECT * FROM users WHERE Loginname='" . $username . "'";
		
		// Korjattu väliaikaisesti, että pääsee loggaamaan sisään.
		//echo "<br>Database - " . $_SESSION['database'];
		$sql = "SELECT * FROM system_users WHERE Username='" . $username . "'";
		
		
		//$sql = "SELECT * FROM users WHERE Loginname='" . $username . "' AND Password='" . $password . "'";
		//echo "<br>sql - " . $sql;
		
		$result = $mysqli->query($sql);
		if (!$result) {
			//echo "<br>sql - " . $sql;
			//die('<br>loadUsers query failed 5x: ' . $mysqli->connect_error);
			// logitetaan virhe...
			errorLog("No users found from database - " . $_SESSION['database']);
			return array();
		}
		
		$users = array();
		$usergrouplist = array();
		while($row = $result->fetch_array()) {
			//var_dump($row);
			//echo "<br>loaduser aaaa";
			$user = new User($row);
			$usergrouplist[$user->usergroupID] = $user->usergroupID;
			$users[] = $user;
			//echo "<br> -- user found - " . $user->username . " - " . $user->usergroupID;
		}
		
		// Käyttäjille pitäisi lisäksi lisätä usergroupname...
		//$usergroups = Table::loadWhereInArray('system_usergroups','usergroupID', $usergrouplist, "WHERE SystemID=" . $systemID,true);
		$sql = "SELECT UsergroupID, Name FROM system_usergroups";
		$result = $mysqli->query($sql);
		$usergroups = array();
		while($row = $result->fetch_array()) {
			$usergroups[$row['UsergroupID']] = $row['Name'];
		}
		
		foreach($users as $userID => $user) {
			$name = $usergroups[$user->usergroupID];
			$user->usergroupname = $name;
			//echo "<br> - usergroup:" . $user->usergroupID . ", groupname:" . $name;
		}
		
		return $users;
	}
	
	
	
	public static function loadUsersByID() {
	
		//echo "<br>loadUsersByID";
		
		global $mysqli;
		//$sql = "SELECT * FROM users";
		// Korjattu väliaikaisesti, että pääsee loggaamaan sisään.
		$sql = "SELECT * FROM system_users";
		
		
		$result = $mysqli->query($sql);
		if (!$result) {
			//echo "<br>sql - " . $sql;
			die('loadUsers query failed 7: ' . $mysqli->connect_error);
		}
	
		$list = array();
		while($row = $result->fetch_array()) {
			echo "<br>loaduser bbbb";
			$user = new User($row);
			$list[$user->userID] = $user->loginname;
		}
		return $list;
	}
	
	
	public static function loadUsersWithName($username, $con = null) {
		
		global $mysqli;
		$sql = "SELECT * FROM system_users WHERE Username='" . $username . "'";
		
		if ($con != null) {
			$result = $con->query($sql);
		} else {
			$result = $mysqli->query($sql);
		}
		if (!$result) {
			echo "<br>sql - " . $sql;
			die('loadUsers query failed 8: ' . $mysqli->connect_error);
		}
		
		$users = array();
		while($row = $result->fetch_array()) {
			echo "<br>loaduser cccc";
			$user = new User($row);
			$users[] = $user;
		}
		return $users;
	}
	
}


?>