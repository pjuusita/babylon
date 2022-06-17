<?php



class WordtrainersettingsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
	
	
	public function indexAction() {
		//$this->showsettingsAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	

	public function showsettingsAction() {
	
		// Pitäisi mahdollisesti tarkistaa onko serverillä uusia kieliä (new ikoni sitten listaan mobiili apissa)
		
		
		$settings = Table::load('system_settings');
	
		$settingsrow = new Row();
		$settingsrow->systemID = 1;
		$settingsrow->dailygoal = 0;
		$settingsrow->sourcelanguageID = 0;
		$settingsrow->targetlanguageID = 0;
		
		foreach($settings as $index => $row) {
			if ($row->name == 'system_settings_dailygoal') {
				$settingsrow->dailygoal = $row->value;
			}
			if ($row->name == 'system_settings_sourcelanguageID') {
				$settingsrow->sourcelanguageID = $row->value;
			}
			if ($row->name == 'system_settings_targetlanguageID') {
				$settingsrow->targetlanguageID = $row->value;
			}
		}
		$this->registry->settings = $settingsrow;
		$this->registry->languages = Table::load('wordtrainer_languages');
		$this->registry->dailygoals= $this->getDailyGoals();
		
		$this->registry->template->show('wordtrainer/wordtrainersettings','wordtrainersettings');
	}
	

	private function getDailyGoals() {
		$goals = array();
		$goals[2] = "2min päivässä";
		$goals[5] = "5min päivässä";
		$goals[10] = "10min päivässä";
		$goals[15] = "15min päivässä";
		$goals[20] = "20min päivässä";
		return $goals;
	}
	
	

	public function updatesettingsAction() {
	
		$rows = Table::load('system_settings');
		if ($rows == null) {
			redirecttotal('wordtrainer/wordtrainersettings/showsettings',null);
			exit;
		}
		

		if (isset($rows['system_settings_dailygoal'])) {
			$row = $rows['system_settings_dailygoal'];
			$insertarray = array();
			$insertarray['Name'] = 'system_settings_dailygoal';
			$insertarray['Value'] = $_GET['dailygoal'];
			$success = Table::updateRow('system_settings',$insertarray, $row->settingID, true);
			
		} else {
			$insertarray = array();
			$insertarray['Name'] = 'system_settings_dailygoal';
			$insertarray['Value'] = $_GET['dailygoal'];
			$success = Table::addRow('system_settings',$insertarray, true);
		}
		

		if (isset($rows['system_settings_sourcelanguageID'])) {
			$row = $rows['system_settings_sourcelanguageID'];
			$insertarray = array();
			$insertarray['Name'] = 'system_settings_sourcelanguageID';
			$insertarray['Value'] = $_GET['sourcelanguageID'];
			$success = Table::updateRow('system_settings',$insertarray, $row->settingID, true);
				
		} else {
			$insertarray = array();
			$insertarray['Name'] = 'system_settings_sourcelanguageID';
			$insertarray['Value'] = $_GET['sourcelanguageID'];
			$success = Table::addRow('system_settings',$insertarray, true);
		}
		

		if (isset($rows['system_settings_targetlanguageID'])) {
			$row = $rows['system_settings_targetlanguageID'];
			$insertarray = array();
			$insertarray['Name'] = 'system_settings_targetlanguageID';
			$insertarray['Value'] = $_GET['targetlanguageID'];
			$success = Table::updateRow('system_settings',$insertarray, $row->settingID, true);
				
		} else {
			$insertarray = array();
			$insertarray['Name'] = 'system_settings_targetlanguageID';
			$insertarray['Value'] = $_GET['targetlanguageID'];
			$success = Table::addRow('system_settings',$insertarray, true);
		}
	
		//redirecttotal('wordtrainer/wordtrainersettings/showsettings',null);
	}
	
	

	public function insertlanguageAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Active'] = 1;
		$success = Table::addRow("wordtrainer_languages", $values);
	
		redirecttotal('wordtrainer/wordtrainersettings/showsettings',null);
	}
	
	
}

?>
