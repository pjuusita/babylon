<?php


class ConceptclassifierController extends AbstractController {
	
	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','worder.css','yritys.css','prism.css','chosen.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
		
	
	public function indexAction() {
		//$this->showselectAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showconceptsAction() {
		

	}
	
	

	public function showselectAction() {
	
		if (isset($_GET['id'])) {
			$conceptID = $_GET['id'];
		} else {
			$conceptID = 0;	
		}
		
		$this->registry->concept = Table::loadRowWhere("worder_concepts"," WHERE GrammarID='" . $_SESSION['grammarID'] . " AND ConceptID>" . $conceptID . " AND RarityID=0");
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->rarities = Table::load("worder_rarities");
		
		$this->registry->template->show('worder/conceptclassifier','classify');
	}
	
	
	/*
	public function setrarityAction() {
		
		if (isset($_GET['id'])) {
			$conceptID = $_GET['id'];
		} else {
			$conceptID = 0;
		}
		
		if (isset($_GET['rarity'])) {
			$rarityID = $_GET['rarity'];
		} else {
			$rarityID = 0;
		}
		
		if (isset($_GET['noframes']))  {
			$noframes = "&noframes=1";
		} else {
			$noframes = "";
		}
		
		$rarity = Table::loadRow("worder_rarities", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		$concept = Table::loadRow("worder_concepts", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		$wordclass = Table::loadRow("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $concept->wordclassID);
		
		//echo "<br>id - " . $conceptID;
		//echo "<br>rarity - " . $rarityID;
		
		$success = Table::updateRow("worder_concepts", array("RarityID" => $rarityID), "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		if ($success == true) {

			$userID = $_SESSION['userID'];
			$timestamp = date("Y-m-d H:i:s");
			
			if ($concept->conceptadded == 0) {
				$success = Table::updateRow("worder_concepts", array("Conceptadded" => 1), "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
			}

			if ($concept->wordclassadded == 0) {
				$success = Table::updateRow("worder_concepts", array("Wordclassadded" => 1), "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
			}
				
			// conceptlog
			
			
			echo "<br>Success - " . $success;
			redirectlocal($module, "worder/conceptclassifier/index&id=" . $conceptID . $noframes);
		} else {
			echo "<br>Not Success";
		}
	}
	*/
	
	
	/*
	public function setwordclassAction() {
	
		if (isset($_GET['id'])) {
			$conceptID = $_GET['id'];
		} else {
			$conceptID = 0;
		}
	
		if (isset($_GET['wordclass'])) {
			$wordclassID = $_GET['wordclass'];
		} else {
			$wordclassID = 0;
		}
	
		if (isset($_GET['noframes']))  {
			$noframes = "&noframes=1";
		} else {
			$noframes = "";
		}
	
		$concept = Table::loadRow("worder_concepts", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		$wordclass = Table::loadRow("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $wordclassID);
	
		//echo "<br>id - " . $conceptID;
		//echo "<br>rarity - " . $rarityID;
	
		$success = Table::updateRow("worder_concepts", array("WordclassID" => $wordclassID), "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
		$success = Table::updateRow("worder_concepts", array("Wordclassadded" => 1), "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
			
		if ($success == true) {
	
			if ($concept->conceptadded == 0) {
				$success = Table::updateRow("worder_concepts", array("Conceptadded" => 1), "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ConceptID=" . $conceptID);
			}
				
			echo "<br>Success - " . $success;
			redirectlocal($module, "worder/conceptclassifier/index&id=" . ($conceptID-1) . $noframes);
		} else {
			echo "<br>Not Success";
		}
	}
	*/
	
	
	public function updateconceptAction() {
	
		echo "[{\"success\":\"true\"}]";
	}
	
	
}
?>