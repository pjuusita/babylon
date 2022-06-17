<?php


class GrammarsController extends AbstractController {

	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->showgrammarsAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	

	public function showgrammarsAction() {
		updateActionPath("Kieliopit");
		$this->registry->grammars = Table::load("worder_grammars", "WHERE UserID=" . $_SESSION['userID']);
		$this->registry->template->show('worder/grammars','grammars');
	}
	
	
	
	public function showgrammarAction() {
	
		$grammarID = $_GET['id'];
		
		$this->registry->grammar = Table::loadRow("worder_grammars", $grammarID);
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $grammarID);
		$this->registry->states = Table::load("worder_states", "WHERE GrammarID=" . $grammarID);
		$this->registry->colors = Table::load("system_colors");
		
		if ($this->registry->multilangactive == 0) {
			$defaultlanguage = null;
			foreach($this->registry->languages as $index => $language) {
				$defaultlanguage = $language;
				break;
			}
			$this->registry->grammar->languagename = $defaultlanguage->name;
		} else {
			
		}
		
		$this->registry->template->show('worder/grammars','grammar');
	}
	

	public function selectgrammarAction() {
	
		$grammarID = $_GET['grammarID'];
		$_SESSION['grammarID'] = $grammarID;
			
		$languages = Table::load('worder_languages', "WHERE GrammarID=" . $grammarID);
		foreach($languages as $index =>$language) {
			$_SESSION['grammarlanguageID'] = $language->languageID;
			break;
		}
		
		$grammar = Table::loadRow('worder_grammars', "WHERE GrammarID=" . $grammarID);
		
		$_SESSION['conceptsactive'] = $grammar->conceptsactive;
		$_SESSION['componentsactive'] = $grammar->componentsactive;
		$_SESSION['multilangactive'] = $grammar->multilangactive;
		
		
		//echo "<br>previousloc - " . $_SESSION['previous_location'];
		//echo "<br>currentloc - " . $_SESSION['current_location'];
		//echo "<br>frontti - " . $_SESSION['frontpage'];
		//$this->registry->grammar = Table::loadRow("worder_grammars", $grammarID);
		//$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $grammarID);
		//$this->registry->template->show('worder/grammars','grammar');
		redirecttotal( $_SESSION['frontpage'], null);
	}
	

	public function insertgrammarAction() {
		
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['UserID'] = $_SESSION['userID'];
		$grammarID = Table::addRow('worder_grammars',$values);
		
		$values = array();
		$values['GrammarID'] = $grammarID;
		$values['Name'] = $_GET['language'];
		$values['Active'] = 1;
		$languageID = Table::addRow('worder_languages',$values);
		
		$_SESSION['grammarID'] = $grammarID;
		$_SESSION['defaultlanguageID'] = $languageID;
		
		redirecttotal('worder/grammars/showgrammar&id=' . $grammarID);
	}
	
	

	public function getgrammarlanguagesJSONAction() {
	
		$grammarID = $_GET['grammarID'];
		$languages = Table::load('worder_languages', "WHERE GrammarID=" . $grammarID . "");
		
		echo "[";
		$first = true;
		foreach($languages as $index => $language) {
			if ($first == true) $first = false; else echo ",";
			echo " {";
			echo "	  \"languageID\":\"" . $language->languageID . "\",";
			echo "	  \"name\":\"" . $language->name . "\"";
			echo " }\n";
		}
		echo "]";
	}
	
	
	
	
	public function insertlanguageAction() {

		$grammarID = $_GET['grammarID'];
		
		$values = array();
		$values['GrammarID'] = $_GET['grammarID'];
		$values['Name'] = $_GET['name'];
		$values['Active'] = 1;
		$success = Table::addRow('worder_languages',$values);
		redirecttotal('worder/grammars/showgrammar&id=' . $grammarID, null);
	}
	
	
	public function removelanguageAction() {
		
		$grammarID = $_GET['grammarID'];
		
		$languages = Table::load("worder_languages" ,"WHERE GrammarID=" . $grammarID, true);
		if (count($languages) > 1) {
			$languageID = $_GET['id'];
			$success = Table::deleteRow('worder_languages',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $languageID, true);
			redirecttotal('worder/grammars/showgrammar&id=' . $grammarID, null);
		} else {
			echo "<br>Ei voida poistaa, vähintään yksi kieli pitää ainakin olla";	
			//redirecttotal('worder/grammars/showgrammar&id=' . $grammarID, null);
		}
	}
	
	
	public function updategrammarAction() {
	
		$grammarID = $_GET['id'];
		
		
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Description'] = $_GET['description'];
		$values['Conceptsactive'] = $_GET['conceptsactive'];
		$values['Componentsactive'] = $_GET['componentsactive'];
		
		if ($_GET['multilangactive'] == 0) {
			$languages = Table::load("worder_languages" ,"WHERE GrammarID=" . $grammarID, true);
			if (count($languages) > 1) {
				echo "<br>Multilang cannot be disable, multible languages exists. Delete languages";
				exit;			
			} else {
				$values['Multilangactive'] = $_GET['multilangactive'];
			}
		} else {
			$values['Multilangactive'] = $_GET['multilangactive'];
		}
		$_SESSION['conceptsactive'] = $_GET['conceptsactive'];
		$_SESSION['componentsactive'] = $_GET['componentsactive'];
		
		$success = Table::updateRow("worder_grammars", $values, $grammarID);
	
		redirecttotal('worder/grammars/showgrammar&id=' . $grammarID, null);
	}
	
	
	

	public function insertstateAction() {
	
		$grammarID = $_GET['grammarID'];
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['GrammarID'] = $grammarID;
		$values['ColorID'] = $_GET['colorID'];
		if (isset($_GET['defaultstate'])) {
			$values['Defaultstate'] = $_GET['defaultstate'];
		} else {
			$values['Defaultstate'] = 0;
		}
		$success = Table::addRow("worder_states", $values, true);
	
		redirecttotal('worder/grammars/showgrammar&id=' . $grammarID,null);
	}
	
	
	public function updatestateAction() {
	
		$id = $_GET['id'];
		$grammarID = $_GET['grammarID'];
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['ColorID'] = $_GET['colorID'];
		if (isset($_GET['defaultstate'])) {
			$values['Defaultstate'] = $_GET['defaultstate'];
		} else {
			$values['Defaultstate'] = 0;
		}
		$success = Table::updateRow("worder_states", $values, $id);
		
		redirecttotal('worder/grammars/showgrammar&id=' . $grammarID);
	}
	
	
	
	
}
