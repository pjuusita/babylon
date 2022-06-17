<?php

/**
 *
 *	16.4.2020 pjuusita	Tätä ei käytetä missään
 *
 *
 */	
class Settings {

	public $settingID;
	public $name;
	public $value;
	public $type;
	public $settingGroup;		// parent settingID
	public $sortOrder;
	
	
	public function __construct($row) {
		if ($row != null) {
			$this->settingID = $row['SettingID'];
			$this->name = $row['Name'];
			$this->value = $row['Value'];
			$this->type = $row['Type'];
			$this->settingGroup = $row['SettingGroup'];
			$this->sortOrder = $row['SortOrder'];
		}
	}
	
	
	// tähän tarvittaisiin ehkä lang mukaan
	// TODO: korvaa haku systeemikohtaisella haulla
	public static function getSetting($name, $defaultValue = NULL, $con = NULL) {
	
		if ($con == NULL) {
			global $mysqli;
		} else {
			$mysqli = $con;
		}
		//echo "<br>Name - " . $name;
		//echo "<br>Database - " . $_SESSION['database'];
		
		$sql = "SELECT * FROM system_settings WHERE Name='" . $name . "' AND SystemID=" . $_SESSION['systemID'];
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>" . $sql;
			echo "<br>Settings.class.php - getSetting failed: " . $mysqli->error;
			die();
		}
	
		$found = false;
		while($row = $result->fetch_array()) {
			$value = $row['Value'];
			$found = true;
		}
		if ($found == false) $value = $defaultValue;
		return $value;
	}
	
	
	public static function getSystemSetting($systemID, $name, $defaultValue = NULL, $con = NULL) {
	
		if ($con == NULL) {
			global $mysqli;
		} else {
			$mysqli = $con;
		}
		//echo "<br>Name - " . $name;
		//echo "<br>Database - " . $_SESSION['database'];
	
		$sql = "SELECT * FROM system_settings WHERE Name='" . $name . "' AND SystemID=" . $systemID;
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>" . $sql;
			echo "<br>Settings.class.php - getSetting failed: " . $mysqli->error;
			die();
		}
	
		$found = false;
		while($row = $result->fetch_array()) {
			$value = $row['Value'];
			$found = true;
		}
		if ($found == false) $value = $defaultValue;
		return $value;
	}
	
	
	public static function saveSetting($name, $value, $defaultValue) {
		
		$comments = false;
		if ($name == null) {
			echo "<br>savesetings name nulli";
			exit;
		}
		
		
		if ($name != null) {
			$settings = Table::load('system_settings');
		
			$rows = Table::load('system_settings'," WHERE name='" . $name . "'", $comments);
			if ($rows == null) {
				//echo "<br>nullliiii";
			}
		
			if ($rows == null) {
				$insertarray = array();
				$insertarray['Name'] = $name;
				$insertarray['Value'] = $value;
				$success = Table::addRow('system_settings',$insertarray,true);
			} else {
				$row = Table::loadRow('system_settings',"WHERE name='" . $name . "'", $comments);
				$insertarray = array();
				$insertarray['Name'] = $name;
				$insertarray['Value'] = $value;
				$success = Table::updateRow('system_settings',$insertarray, $row->settingID, $comments);
			}
		} else {
			$insertarray = array();
			$insertarray['Name'] = $name;
			$insertarray['Value'] = $defaultValue;
			$success = Table::addRow('system_settings',$insertarray,$comments);
		}
	}
	
	
}
?>