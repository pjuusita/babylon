<?php



/**
 * [8.6.2019] arguments-toiminto vanhentunut, tästä voitaisiin ehkä muodostaa jokin 
 * argumenttien chekkaus/tarkistus/läpikäynti tyäkalu. Joissakin tapauksissa ja ryhmissä
 * saattaa olla tarpeen, että kaikki jäsenet omaavat tietyn argumentin
 * 
 * 
 * @author pjuusita
 *
 */
class ArgumentsController extends AbstractController {

	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->showargumentsAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	
	public function updateargumentAction() {
		$argumentID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['WordclassID'] = $_GET['wordclassID'];
		$values['WordclassvalueID'] = $_GET['wordclassvalueID'];
		$values['Description'] = $_GET['description'];
		$success = Table::updateRow('worder_arguments', $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ArgumentID=" . $argumentID);
		redirecttotal('worder/arguments/showargument&id=' . $argumentID, null);
	}
	
	
	public function showargumentsAction() {
		
		if (isset($_GET['wordclassID'])) {
			$wordclassID = $_GET['wordclassID'];
			setModuleSessionVar('argumentwordclassID', $wordclassID);
		} else {
			$wordclassID = getModuleSessionVar('argumentwordclassID');
		}
		$this->registry->wordclassID = $wordclassID;
		
		if ($wordclassID == 0) {
			$this->registry->arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID']);
		} else {
			$this->registry->arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $wordclassID);
		}
				
		updateActionPath("Arguments");
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->template->show('worder/arguments','argumentlist');
	}
	
	

	public function showargumentAction() {
		$argumentID = $_GET['id'];
		
		
		
		$this->registry->argument = Table::loadRow("worder_arguments", $argumentID);
		updateActionPath($this->registry->argument->name);
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$terms = Table::load("worder_ruleterms", "WHERE ArgumentID=" . $argumentID . " AND GrammarID=" . $_SESSION['grammarID']);
		$rulelist = array();
		foreach($terms as $index => $term) {
			$rulelist[$term->ruleID] = $term->ruleID;
		}
		$this->registry->rules = Table::loadWhereInArray("worder_rules", "ruleID", $rulelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$links = Table::load("worder_conceptargumentlinks", "WHERE ArgumentID=" . $argumentID . " AND GrammarID=" . $_SESSION['grammarID']);
		$conceptlist = array();
		foreach($links as $index => $link) {
			$conceptlist[$link->conceptID] = $link->conceptID;
		}
		$this->registry->concepts = Table::loadWhereInArray("worder_concepts", "conceptID", $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		global $mysqli;
		
		$conceptlist = array();
		$sql = "SELECT ConceptID, Arguments FROM worder_concepts WHERE GrammarID=" . $_SESSION['grammarID'];
		//echo "<br>SQL1 - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) {
			die('Select failed: ' . $mysqli->connect_error);
		}
		$counter = 0;
		while($row = $result->fetch_array()) {
			$argumentstr = $row['Arguments'];
			if ($argumentstr != "") {
				//echo "<br>Argumentstr - " . $argumentstr;
				$argumentlist = explode("|", $argumentstr);
				foreach($argumentlist as $index => $argumentitem) {
					if ($argumentitem != '') {
						//echo "<br> -- 0 - " . $argumentlist[0];
						$itemlist = explode(':',$argumentitem);
						if ($itemlist[0] == $argumentID) {
							$conceptlist[$row['ConceptID']] = $row['ConceptID'];
							//echo "<br> ****** found - " . $row['ConceptID'];
						}
					}
				}
				//foreach($argumentlist as $index => $value) {
				//}
				$counter++;
			}
			//if ($counter > 100) break;
		}
		$this->registry->allconcepts = Table::loadWhereInArray("worder_concepts", "conceptID", $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		//echo "<br>Counter - " . $counter;
		$this->registry->template->show('worder/arguments','argument');
	}
	
	
	
	public function removeargumentAction() {
	
		$argumentID =  $_GET['argumentID'];
		
		
		$terms = Table::load("worder_ruleterms", "WHERE ArgumentID=" . $argumentID . " AND GrammarID=" . $_SESSION['grammarID']);
		$rulelist = array();
		foreach($terms as $index => $term) {
			$rulelist[$term->ruleID] = $term->ruleID;
		}
		$rules = Table::loadWhereInArray("worder_rules", "ruleID", $rulelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		if (count($rules) > 0) {
			echo "<br>Ei voida poistaa, esiintyy säännöissä (" . count($rules) . ")";
			exit;
		}
		
		$links = Table::load("worder_conceptargumentlinks", "WHERE ArgumentID=" . $argumentID . " AND GrammarID=" . $_SESSION['grammarID']);
		$conceptlist = array();
		foreach($links as $index => $link) {
			$conceptlist[$link->conceptID] = $link->conceptID;
		}
		$concepts = Table::loadWhereInArray("worder_concepts", "conceptID", $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		if (count($concepts) > 0) {
			echo "<br>Ei voida poistaa, esiintyy käsitteissä (" . count($concepts) . ")";
			exit;
		}
		
		
		global $mysqli;
		
		$conceptlist = array();
		$sql = "SELECT ConceptID, Arguments FROM worder_concepts WHERE GrammarID=" . $_SESSION['grammarID'];
		//echo "<br>SQL1 - " . $sql;
		$result = $mysqli->query($sql);
		if (!$result) {
			die('Select failed: ' . $mysqli->connect_error);
		}
		$counter = 0;
		while($row = $result->fetch_array()) {
			$argumentstr = $row['Arguments'];
			if ($argumentstr != "") {
				//echo "<br>Argumentstr - " . $argumentstr;
				$argumentlist = explode("|", $argumentstr);
				foreach($argumentlist as $index => $argumentitem) {
					if ($argumentitem != '') {
						//echo "<br> -- 0 - " . $argumentlist[0];
						$itemlist = explode(':',$argumentitem);
						if ($itemlist[0] == $argumentID) {
							$conceptlist[$row['ConceptID']] = $row['ConceptID'];
							//echo "<br> ****** found - " . $row['ConceptID'];
						}
					}
				}
				//foreach($argumentlist as $index => $value) {
				//}
				$counter++;
			}
			//if ($counter > 100) break;
		}
		$allconcepts = Table::loadWhereInArray("worder_concepts", "conceptID", $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		if (count($allconcepts) > 0) {
			echo "<br>Ei voida poistaa, esiintyy kaikissa käsitteissä (" . count($allconcepts) . ")";
			exit;
		}
		
		//echo "<br>Voidaan poistaa";
		//exit;
		$success = Table::deleteRow('worder_arguments',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ArgumentID=" . $argumentID, false);
		redirecttotal('worder/arguments/showarguments', null);
	}
	
}
