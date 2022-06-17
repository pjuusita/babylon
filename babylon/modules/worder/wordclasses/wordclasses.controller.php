<?php


class WordclassesController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->showwordclasslistAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	

	public function showwordclasslistAction() {
		
		updateActionPath("Wordclasses");
		
		$this->registry->features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->hierarchy = Table::loadHierarchy('worder_wordclasses','parentID', "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclasses = Table::load("worder_wordclasses","WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->template->show('worder/wordclasses','wordclasslist');
	}
	
	
	
	
	public function showwordclassAction() {

		$wordclassID = $_GET['id'];
		
		$this->registry->wordclasses = Table::load("worder_wordclasses","WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclass = $this->registry->wordclasses[$wordclassID];
		updateActionPath($this->registry->wordclass->name);
		
		$this->registry->features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ParentID=0" );
		$this->registry->allfeatures = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		foreach($this->registry->languages as $index => $language) {
			$this->registry->languageID = $language->languageID;
			break;
		}
		
		$arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $this->registry->wordclass->wordclassID . " ORDER BY Name");
				
		$table = array();
		foreach($arguments as $index => $argument) {

			$row = array();
			
				
			$row[0] = $argument->languageID;
			//echo "<br>--- " . $argument->languageID;
			$row[1] = $argument->argumentID;
			$row[2] = $argument->name;
			if ($argument->typeID == 1) {
				$featureID = $argument->featurevalueID;
				$row[3] = 'Feature';
				$row[4] = $this->registry->features[$featureID]->name;
			} else {
				if ($argument->typeID == 2) {
					$wordclassID = $argument->wordclassvalueID;
					$row[3] = 'Wordclass';
					
					if ($argument->wordclassvalueID == 0) {
						$row[4] = "None";
					} else {
						$row[4] = $this->registry->wordclasses[$argument->wordclassvalueID]->name;
					}
				} else {
					$row[3] = '-';
					$row[4] = "-";
				}
			}
			$row[5] = $argument->typeID;
			$row[6] = $argument->wordclassvalueID;
			$row[7] = $argument->featurevalueID;
			$row[8] = $argument->argumentID;
				
			$table[] = $row;
		}
		$this->registry->arguments = $table;
		
		$this->registry->wordclassfeatures = Table::load("worder_wordclassfeatures", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $this->registry->wordclass->wordclassID. " ORDER BY LanguageID, Inflectional");
		//$this->registry->semanticfeatures= Table::load("worder_wordclassfeatures", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $this->registry->wordclass->wordclassID . " AND LanguageID=0");
		
		//$this->registry->wordclassfeatures = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID='");
		//$this->registry->features = Table::loadWhereInArray("worder_features", "FeatureID" ,  $this->registry->contentdata->features, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$baseforms = Table::load("worder_wordbaseforms", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $this->registry->wordclass->wordclassID);
		
		foreach($baseforms as $index => $form) {
			$fullstr = null;
			$pairs = explode("|", $form->featurepairs);
			foreach($pairs as $index => $pairstr) {
				$items = explode(":",$pairstr);
				$feature = $this->registry->allfeatures[$items[0]];
				$value = $this->registry->allfeatures[$items[1]];
				if ($fullstr == null) {
					$fullstr = $feature->name . "=" . $value->name;
				} else {
					$fullstr = $fullstr . "," . $feature->name . "=" . $value->name;
				}
			}	
			$form->featurestring = $fullstr;
			$language = $this->registry->languages[$form->languageID];
			$form->fullname = $language->name . " - " . $form->name;
		}
		$this->registry->baseforms = $baseforms;
		
		$this->registry->template->show('worder/wordclasses','wordclass');
	}
	
	


	public function showwordclassfeatureAction() {
	
		$wordclassfeatureID = $_GET['id'];
		$this->registry->wordclassfeature = Table::loadRow("worder_wordclassfeatures", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $wordclassfeatureID);
		
		$this->registry->wordclasses = Table::load("worder_wordclasses","WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->features = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ParentID=0 AND LanguageID=" . $this->registry->wordclassfeature->languageID );
		$this->registry->featurevalues = Table::load("worder_features","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ParentID=" . $this->registry->wordclassfeature->featureID );
		$this->registry->wordclasses = Table::load("worder_wordclasses", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		// TODO: mikäli featuretype = word (Inflectional=0), niin pitäisi ladata lista sanoista, joissa tämä esiintyy...
		// TODO: mikäli featuretype = rule (Inflectional=2), niin pitäisi ladata lista ruleista, joissa tämä esiintyy...
		
		if ($this->registry->wordclassfeature->inflectional == 0) {
			$featureID = $this->registry->wordclassfeature->featureID;
			$wordclassID = $this->registry->wordclassfeature->wordclassID;
			$links = Table::load("worder_wordfeaturelinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND FeatureID=" . $featureID . " AND WordclassID=" . $wordclassID);
			$wordlist = array();
			foreach($links as $index => $link) {
				$wordlist[$link->wordID] = $link->wordID;
			}
			$words = Table::loadWhereInArray("worder_words","WordID", $wordlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
			$this->registry->words = $words;
		} else {
			$this->registry->words = array();
		}
		
		$this->registry->semanticvalues = array();
		$this->registry->template->show('worder/wordclasses','wordclassfeature');
	}
	
	
	//******************************************************************************************************
	//***** ACTIONS
	
	public function updatewordclassAction() {

		$wordclassID = $_GET['id'];
		
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Abbreviation'] = $_GET['abbreviation'];
		//$values['Comment'] = $_GET['comment'];
		//$values['Active'] = $_GET['active'];
		$values['ParentID'] = $_GET['parentID'];
		$values['Features'] = $_GET['features'];
		$values['GrammarID'] = $_SESSION['grammarID'];
		$success = Table::updateRow('worder_wordclasses', $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $wordclassID);
		
		redirecttotal('worder/wordclasses/showwordclass&id=' . $wordclassID);
	}
	
	

	public function updatewordclassfeatureAction() {
	
		$rowID = $_GET['id'];
	
		$values = array();
		$values['WordclassID'] = $_GET['wordclassID'];
		$values['LanguageID'] = $_GET['languageID'];
		$values['Inflectional'] = $_GET['inflectional'];
		$values['FeatureID'] = $_GET['featureID'];
		$values['DefaultvalueID'] = $_GET['defaultvalueID'];
		$values['Description'] = $_GET['description'];
		$values['SemanticdefaultID'] = $_GET['semanticdefaultID'];
		
		$success = Table::updateRow('worder_wordclassfeatures', $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);
	
		//redirecttotal('worder/wordclasses/showwordclassfeature&id=' . $rowID);
	}
	
	
	

	public function insertwordclassargumentAction() {

		$wordclassID = $_GET['wordclassID'];
		$nimi = $_GET['name'];
		//$type = $_GET['typeID'];
		$type = 2;				// TODO: ehkä tarpeeton, empty ja main vain eroavat
		//$languageID = $_GET['languageID'];
		$languageID = 0; 		// TODO: vanhentunut, tarpeeton
		$featurevalueID = 0;
		if (isset($_GET['featurevalueID'])) $featurevalueID = $_GET['featurevalueID'];
		if ($featurevalueID == '') $featurevalueID = 0;
		
		$wordclassvalueID = 0;
		if (isset($_GET['wordclassvalueID'])) $wordclassvalueID = $_GET['wordclassvalueID'];
		if ($wordclassvalueID == '') $wordclassvalueID = 0;
		
		//echo "<br>Loaded wordlcassvalue - " . $wordclassvalueID;
		
		$insertarray = array();
		$insertarray['WordclassID'] = $wordclassID;
		$insertarray['Name'] = $nimi;
		$insertarray['TypeID'] = $type;
		$insertarray['LanguageID'] = $languageID;
		$insertarray['WordclassvalueID'] = $wordclassvalueID;
		$insertarray['FeaturevalueID'] = $featurevalueID;
		$insertarray['GrammarID'] = $_SESSION['grammarID'];
		
		$success = Table::addRow('worder_arguments',$insertarray);
		
		redirecttotal('worder/wordclasses/showwordclass&id=' . $wordclassID);
	}
	
		
	public function updatefeatureAction() {
	
		$rowID = $_GET['id'];
		
		$this->registry->wordclassfeature = Table::loadRow("worder_wordclassfeatures","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);
		
		$languageID = $_GET['languageID'];
		$featureID = $_GET['featureID'];
		$onlyinrules = $_GET['onlyinrules'];
		$defaultvalueID = $_GET['defaultvalueID'];
		
		
		$values = array();
		$values['LanguageID'] = $languageID;
		$values['FeatureID'] = $featureID;
		$values['Onlyinrules'] = $onlyinrules;
		$values['DefaultvalueID'] = $defaultvalueID;

		if (isset($_GET['semanticdefaultID'])) {
			$values['SemanticdefaultID'] = $_GET['semanticdefaultID'];
		}
		if (isset($_GET['wordbookformID'])) {
			$values['wordbookformID'] = $_GET['wordbookformID'];
		}
		$values['Inflectional'] =  $_GET['inflectional'];
		
		$success = Table::updateRow("worder_wordclassfeatures", $values,  "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" .$rowID);
		//echo "<br>WordclassID - " . $this->registry->wordclassfeature->wordclassID;
		redirecttotal('worder/wordclasses/showwordclass&id=' . $this->registry->wordclassfeature->wordclassID);
	}
	
	

	public function updateargumentAction() {
	
		$argumentID = $_GET['id'];
		
		$wordclassID = $_GET['wordclassID'];
		$name = $_GET['name'];
		$typeID = $_GET['typeID'];
		$languageID = $_GET['languageID'];
		
		$featurevalueID = 0;
		if (isset($_GET['featurevalueID'])) $featurevalueID = $_GET['featurevalueID'];
		if ($featurevalueID == '') $featurevalueID = 0;
		
		$wordclassvalueID = 0;
		if (isset($_GET['wordclassvalueID'])) $wordclassvalueID = $_GET['wordclassvalueID'];
		if ($wordclassvalueID == '') $wordclassvalueID = 0;
		
		//echo "<br>Loaded wordlcassvalue - " . $wordclassvalueID;
		
		$values = array();
		$values['WordclassID'] = $wordclassID;
		$values['Name'] = $name;
		$values['TypeID'] = $typeID;
		$values['LanguageID'] = $languageID;
		$values['WordclassvalueID'] = $wordclassvalueID;
		$values['FeaturevalueID'] = $featurevalueID;
		$values['GrammarID'] = $_SESSION['grammarID'];
		
		$success = Table::updateRow("worder_arguments", $values,  "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ArgumentID=" .$argumentID);
		redirecttotal('worder/wordclasses/showwordclass&id=' . $wordclassID);
	}
	
	

	public function insertfeatureAction() {
	
		$comments = false;
		$wordclassID = $_GET['wordclassID'];
		$featureID = $_GET['featureID'];
		$languageID = $_GET['languageID'];
	
		$insertarray = array();
		$insertarray['WordclassID'] = $wordclassID;
		$insertarray['FeatureID'] = $featureID;
		$insertarray['LanguageID'] = $languageID;
		$insertarray['Inflectional'] =  $_GET['inflectional'];;
		$insertarray['GrammarID'] = $_SESSION['grammarID'];
		$insertarray['DefaultvalueID'] = $_GET['defaultvalueID'];
		$success = Table::addRow('worder_wordclassfeatures',$insertarray, $comments);
		
		if (!$comments) redirecttotal('worder/wordclasses/showwordclass&id=' . $wordclassID, null);
	}		
	
	
	
	public function insertwordclassAction() {

		$name =  $_GET['name'];
		$parent =  $_GET['parentID'];
		
		$values = array();
		$values['Name'] = $name;
		$values['ParentID'] = $parent;
		$values['GrammarID'] = $_SESSION['grammarID'];
		
		$wordclassID = Table::addRow("worder_wordclasses", $values, false);
		
		redirecttotal('worder/wordclasses/showwordclasslist', null);
	}
	

	

	public function insertbaseformAction() {
	
		$comments = true;
		$name = $_GET['name'];
		$wordclassID = $_GET['wordclassID'];
		$languageID = $_GET['languageID'];
		$featureID = $_GET['featureID'];
		$valueID = $_GET['valueID'];
		
		$valuestr = "" . $featureID . ":" . $valueID;
		
		$insertarray = array();
		$insertarray['WordclassID'] = $wordclassID;
		$insertarray['Name'] = $name;
		$insertarray['LanguageID'] = $languageID;
		$insertarray['Featurepairs'] = $valuestr;
		$insertarray['GrammarID'] = $_SESSION['grammarID'];
		$success = Table::addRow('worder_wordbaseforms',$insertarray, $comments);
	
		if (!$comments) redirecttotal('worder/wordclasses/showwordclass&id=' . $wordclassID, null);
	}
	
	

	public function appendbaseformAction() {
	
		$comments = false;
		$wordclassID = $_GET['wordclassID'];
		$formID = $_GET['formID'];
		$featureID = $_GET['featureID'];
		$valueID = $_GET['valueID'];
	
		$form = Table::loadRow("worder_wordbaseforms","WHERE GrammarID=" . $_SESSION['grammarID'] . " AND FormID=" . $formID);
		
		$valuestr = $form->featurepairs . "|" . $featureID . ":" . $valueID;
	
		$updatearray = array();
		$updatearray['Featurepairs'] = $valuestr;
		$success = Table::updateRow('worder_wordbaseforms',$updatearray, "WHERE FormID=" . $formID);
	
		if (!$comments) redirecttotal('worder/wordclasses/showwordclass&id=' . $wordclassID, null);
	}
	
	

	public function removebaseformAction() {
		$formID = $_GET['id'];
		$wordclassID = $_GET['wordclassID'];
		$success = Table::deleteRow('worder_wordbaseforms',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND FormID=" . $formID);
		redirecttotal('worder/wordclasses/showwordclass&id=' . $wordclassID, null);
	}
	
	
	
	public function removewordclassfeatureAction() {
		$rowID = $_GET['id'];
		$wordclassID = $_GET['wordclassID'];
		$success = Table::deleteRow('worder_wordclassfeatures',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND RowID=" . $rowID);
		redirecttotal('worder/wordclasses/showwordclass&id=' . $wordclassID, null);
	}
	
	
	
	public function removeargumentAction() {
		$argumentID = $_GET['id'];
		$wordclassID = $_GET['wordclassID'];
		
		// TODO: tässä pitäisi varmaan poistaa ko. argumentti kaikista ruleista ja concepts argument restrictionsseista ainakin
		
		$success = Table::deleteRow('worder_arguments',"WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ArgumentID=" . $argumentID);
		redirecttotal('worder/wordclasses/showwordclass&id=' . $wordclassID, null);
	}
	
	

	public function getWordclassArgumentsJSONAction() {
	
		$wordclassID = $_GET['wordclassID'];
		//$languageID = $_GET['languageID'];

		$arguments = Table::load("worder_arguments", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND WordclassID=" . $wordclassID);
		//echo "<br>arguments - " . count($arguments);
		$array = array();
		foreach($arguments as $index => $argument) {
			$array[$argument->argumentID] = $argument->name;
			//echo "<br>" . $argument->argumentID . " - " . $argument->name;
		}
		echo json_encode($array);
	}
	
	
	
}
