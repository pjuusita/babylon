<?php

	
class Login {

	public $loginID;
	public $loginname;
	public $database;
	public $active;
	public $password;
	public $systemID;
	
	
	public function __construct($row) {
		
		if ($row != null) {
			$this->loginID = $row['LoginID'];
			$this->loginname = $row['Loginname'];
			$this->password= $row['Password'];
			$this->database = $row['Databasename'];
			$this->active = $row['Active'];
			$this->systemID = $row['SystemID'];
		}
	}
	

	public function getName() {
		return $this->loginname;
	} 
	
	
	public static function loadLines($loginname, $connection = null) {
		
		if ($connection == null) {
			global $mysqli;
		} else {
			$mysqli = $connection;
		}
		
		$sql = "SELECT * FROM login WHERE Loginname='" . $loginname . "' AND Active='1'";
		$result = $mysqli->query($sql);
		if (!$result) {
			die('Login::load query failed: ' . $result  . " - " . $mysqli->error);
		}
		
		$list = array();
		while($row = $result->fetch_array()) {
			$item = new Login($row);
			$list[$item->loginID] = $item;
		}
		return $list;
	}
	
	
	public static function loadSystems($connection = null) {
	
		if ($connection == null) {
			global $mysqli;
		} else {
			$mysqli = $connection;
		}
	
		$sql = "SELECT * FROM systems";
		$result = $mysqli->query($sql);
		if (!$result) {
			die('Login::loadSystems query failed: ' . $result  . " - " . $mysqli->error);
		}
	
		$list = array();
		while($row = $result->fetch_array()) {
			$systemID = $row['SystemID'];
			$name = $row['Name'];
			$list[$systemID] = $name;
		}
		return $list;
	}
	

	public static function getUserID($username, $database) {
	
		$mysqli = ConnectDatabase(CENTRAL_LOGIN_DATABASE);
		$sql = "SELECT LoginID FROM login WHERE Loginname='" . $username . "' AND Databasename='". $database . "'";
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>Sql - " . $sql;
			die('Login::load query failed: ' . $result  . " - " . $mysqli->error);
		}
		$row = $result->fetch_array();
		$mysqli->close();
		return $row['LoginID'];
	}
	
	

	public static function getPassword($username, $database) {
	
		$mysqli = ConnectDatabase(CENTRAL_LOGIN_DATABASE);
		$sql = "SELECT Password FROM login WHERE Loginname='" . $username . "' AND Databasename='". $database . "'";
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>Sql - " . $sql;
			die('Login::load query failed: ' . $result  . " - " . $mysqli->error);
		}
		$row = $result->fetch_array();
		$mysqli->close();
		return $row['Password'];
	}
	
	
	

	public static function loadID($loginID, $con = NULL) {
	
	
		if ($con == null) {
			global $mysqli;
			if ($mysqli == null) echo "mysqli null";
		} else {
			//echo "<br>Myload";
			if ($con == null) echo "mysqli null";
			$mysqli = $con;
		}
	
		$sql = "SELECT * FROM login WHERE LoginID='" . $loginID. "'";
	
		$result = $mysqli->query($sql);
		if (!$result) {
			return null;
		}
		$row = $result->fetch_array();
		$login = new Login($row);
		return $login;
	}
}


?>