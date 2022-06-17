<?php

//******************************************************************************************************
//***** SORT-FUNCTIONS
//******************************************************************************************************

function sortDefinitionsByPropertyNameAscending($cssdefinitionsA, $cssdefinitionsB) {
	$A=strtolower($cssdefinitionsA->propertyname);
	$B=strtolower($cssdefinitionsB->propertyname);
	if ($A > $B) return 1;
	if ($A < $B) return -1;
	return 0;
}

function sortDefinitionsByValueAscending($cssdefinitionsA, $cssdefinitionsB) {
	$A=strtolower($cssdefinitionsA->value);
	$B=strtolower($cssdefinitionsB->value);
	if ($A > $B) return 1;
	if ($A < $B) return -1;
	return 0;
}

function sortDefinitionsByPropertyNameDescending($cssdefinitionsA, $cssdefinitionsB) {
	$A=strtolower($cssdefinitionsA->propertyname);
	$B=strtolower($cssdefinitionsB->propertyname);
	if ($A > $B) return -1;
	if ($A < $B) return 1;
	return 0;
}

function sortDefinitionsByValueDescending($cssdefinitionsA, $cssdefinitionsB) {
	$A=strtolower($cssdefinitionsA->value);
	$B=strtolower($cssdefinitionsB->value);
	if ($A > $B) return -1;
	if ($A < $B) return 1;
	return 0;
}

function sortCssClassesByNameAscending($cssClassA, $cssClassB) {
	$A=strtolower($cssClassA->name);
	$B=strtolower($cssClassB->name);
	if ($A > $B) return 1;
	if ($A < $B) return -1;
	return 0;
}

function sortCssClassesByNameDescending($cssClassA, $cssClassB) {
	$A=strtolower($cssClassA->name);
	$B=strtolower($cssClassB->name);
	if ($A > $B) return -1;
	if ($A < $B) return 1;
	return 0;
}

//******************************************************************************************************
//***** CLASS CSSMANAGERCONTROLLER
//******************************************************************************************************


// Tama luokka ja kontrolleri voitaisiin siirtaa thememanagerin alle...



class CssmanagerController extends AbstractController {
	
	
	
	
	public function getCSSFiles() {
		
		//echo "<br>getCSSFiles";
		//return array('testcss.php', 'menu.css','mytheme/jquery-ui.css','yritys.css','prism.css','chosen.css');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','yritys.css','prism.css','chosen.css','petestyle.css');
		//return array('babylon.css', 'menu.css','section.css', 'responsive.css');
		return array('babylon.css', 'menu.css','section.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
	
	
	public function indexAction() {
		//$this->showtabletestAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	
	
	public function showcssclassesAction() {
	
		//$cssFileNames = Table::load("system_cssfiles");
		
		// filtterainti cssfilen perusteella
		/*
		if (isset($_GET['cssfileID'])){
			if ($_GET['cssfileID'] != 0) {
				$registry->cssClasses = Table::load("system_cssclasses", " WHERE CssfileID='" . $cssfileID ."'");
		
			} else {
				$registry->cssClasses = Table::load("system_cssclasses", " WHERE CssfileID='" . $cssfileID ."'");
			}
		} else {
			$registry->cssClasses = Table::load("system_cssclasses", " WHERE CssfileID='" . $cssfileID ."'");
		}
		
		
		if (isset($_GET['sort'])) {
		
			$sort = $_GET['sort'];
		
			if ($sort == 'name') {
		
				$this->registry->sortingcolumn = $sort;
		
				if (($_GET['sortdirection'])=='descending') {
					$this->registry->sortingdirection = $_GET['sortdirection'];
					usort($cssClasses,'sortCssClassesByNameDescending');
				}
				else {
					$this->registry->sortingdirection = "ascending";
					usort($cssClasses,'sortCssClassesByNameAscending');
				}
			}
		}
		*/
		$this->registry->cssclasses = Table::load("system_cssclasses");
		$this->registry->cssfiles = Table::load("system_cssfiles");
		$this->registry->template->show('admin/cssmanager','cssclasses');
	}
	
	public function showcssclassAction() {
		
		$cssClassID = $_GET['id'];
		
		//$cssDefinitions = CssDefinitions::loadCssClassDefinitions($cssClassID);
		
		if (isset($_GET['sort'])) {
			$sort = $_GET['sort'];
			if ($sort == 'propertyname') usort($cssDefinitions,'sortDefinitionsByPropertyNameAscending');
			if ($sort == 'value') usort($cssDefinitions,'sortDefinitionsByValueAscending');		
		}
				
		$this->registry->cssClasses = Table::load("system_cssclasses", " WHERE CssfileID='" . $cssfileID ."'");
		$this->registry->cssclass = $this->registry->cssClasses[$cssClassID];
		
		$this->registry->cssdefinitions = $cssDefinitions;
		//$this->registry->themeitems = Themeitem::loadDistinctThemeItemNames();
		$this->registry->template->header = 'Main';
		$this->registry->template->showContent('admin/cssmanager','cssclass', $this->registry->cssclass->name);
	
	}
	
	public function showinsertcssclassAction() {
		
		//$cssFileNames = CssFile::loadCssFileNameArray();
		$this->registry->cssfiles = $cssFileNames;
		$this->registry->template->header = 'Main';
		
		$this->registry->defaultitem = new Row();
		if (isset($_GET['cssfileid'])) {
			$this->registry->defaultitem->cssfileID = $_GET['cssfileid'];
		} else {
			$this->registry->defaultitem->cssfileID = 0;
		}
		
		
		$this->registry->template->showContent('admin/cssmanager','insertcssclass', 'Uusi css-luokka');
	}

	//**** UPDATECSS-CLASSES ****************************************************************************************
	
	public function updatecssclassAction() {
		
		
		$success='';
		$columns=array();
		
		foreach($_GET as $index => $value) {
			if (($index != 'id') && ($index != 'rt')) {
				$columns[$index]=$value;
			} elseif ($index == 'id') {
				$id=$value;
			}
		}
		
		echo "<br>Not implemented";
		exit();
		
		//$success = CssClass::updateCssClass($id,$columns);
		
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
		
	}
	//**** REMOVE CSS-CLASSES ****************************************************************************************
	
	public function removecssclassAction() {
		
		$success='';
		
		$cssClassID = $_GET['cssclassid'];
	
		//$success = CssClass::removeCssClassByID($cssClassID);
			
		if ($success) {
			addMessage("Css-luokka merkitty poistetuksi onnistuneesti");
			redirecttotal('admin/cssmanager/showcssclasses');
		} else {
			addErrorMessage("Css-luokan poistetuksi merkitseminen epaonnistui!");
			redirecttotal('admin/cssmanager/showcssclasses');
		}
	}
	
	//**** INSERT CSS-CLASSES ****************************************************************************************
	
	public function insertcssclassAction() {
		
		$name = $_GET['Name'];
		$cssfileID = $_GET['CssfileID'];
		
		$success='';
		//$success = CssClass::InsertCssClass($name,"",$cssfileID);
		$this->isSuccess($success);

	}
	
	
//********************************************************************************************************
//***** CSSDEFINITION ACTIONS
//********************************************************************************************************
	
	public function showcssdefinitionsAction() {
	
		//$cssDefinitions = CssDefinitions::loadCssDefinitions();
		
		if (isset($_GET['sort'])) {
			
			$sort = $_GET['sort'];
			
			if ($sort == 'propertyname') {
				$this->registry->sortingcolumn = $sort;
				if (($_GET['sortdirection'])=='descending') {
					$this->registry->sortingdirection = $_GET['sortdirection'];
					usort($cssDefinitions,'sortDefinitionsByPropertyNameDescending');
				}
				else {
					$this->registry->sortingdirection = "ascending";
					usort($cssDefinitions,'sortDefinitionsByPropertyNameAscending');		
				}
			}
			
			if ($sort == 'value') {
				$this->registry->sortingcolumn = $sort;
				
				if (($_GET['sortdirection'])=='descending') {
					$this->registry->sortingdirection = $_GET['sortdirection'];
					usort($cssDefinitions,'sortDefinitionsByValueDescending');
				}
				else {
					$this->registry->sortingdirection = "ascending";
					usort($cssDefinitions,'sortDefinitionsByValueAscanding');
				}
			}
		}
		
		$this->registry->cssdefinitions = $cssDefinitions;
		//$this->registry->themeitems = Themeitem::loadDistinctThemeItemNames();
		
		foreach($this->registry->cssdefinitions as $index => $cssdefinition) {
			if ($cssdefinition->value == '') {
				if (isset($this->registry->themeitems[$cssdefinition->themeitemID])) {
					$item = $this->registry->themeitems[$cssdefinition->themeitemID];
					$cssdefinition->itemstring = $item;
				} else {
					$cssdefinition->itemstring = "Ei asetettu";
				}
			} else {
				$cssdefinition->itemstring = $cssdefinition->value;
			}
		}
	
		$this->registry->template->header = 'Main';
		$this->registry->template->showContent('admin/cssmanager','cssdefinitions', 'CSS määrittelyt');
	}
	
	
	public function showcssdefinitionAction() {
	
		$cssDefinitionID = $_GET['id'];
	
		//$this->registry->cssdefinition = CssDefinitions::loadCssDefinition($cssDefinitionID);
		$this->registry->cssclass = Table::loadRow('system_cssclasses', $this->registry->cssdefinition->cssclassID);
		//$this->registry->themeitems = Themeitem::loadDistinctThemeItemNames();
		$this->registry->template->header = 'Main';
		$this->registry->template->showContent('admin/cssmanager','cssdefinition', $this->registry->cssdefinition->name);
	}
	
	
	public function showinsertcssdefinitionAction() {
		
		//$this->registry->themeitems = Themeitem::loadDistinctThemeItemNames();
		
		$this->registry->cssclasses = Table::load('system_csslasses');
		
		
		
		
		//foreach($this->registry->cssclasses as $index => $value) {
		//	echo "<br>" . $index . " - " . $value;
		//}
		//$this->registry->defaultitem = new CssDefinitions();
		if (isset($_GET['cssclassid'])) $this->registry->defaultitem->cssclassID = $_GET['cssclassid'];
		
		
		$this->registry->template->header = 'Main';
		$this->registry->template->showContent('admin/cssmanager','insertcssdefinition', 'Uusi css-määrittely');
		
	}
		
	
	public function updatecssdefinitionAction() {
		
		$success='';
		$columns=array();
		$str = "sss";
		foreach($_GET as $index => $value) {
			if (($index != 'id') && ($index != 'rt')) {
				$value = decodeSpecialCharacters($value);
				$str = $str . " - " . $index . "-" . $value;
				$columns[$index]=$value;
			} elseif ($index == 'id') {
				$id=$value;
			}
		}
		
		//$success = CssDefinitions::updateCssDefinition($id,$columns);
		
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$str."\"}]";
		}
	}
	
	
	public function insertcssdefinitionAction() {
		
		$values = array();
		
		foreach($_GET as $index => $value) {
			if ($index != 'rt') {
				$values[$index]= decodespecialcharacters($value);
				//echo "<br>" . $index . " - " . $value;
			}
		}
		$success = Table::addRow('system_cssdefinitions', $values);
		
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
	}
	
	
	
	public function removecssdefinitionAction() {
		
		$this->registry->loadParams();
		
		$cssDefinitionID = $_GET['id'];
		//$success = CssDefinitions::removeCssDefinition($cssDefinitionID);
		
		if ($success) {
			echo "[{\"success\":\"true\"}]";
			//addMessage("Määrittely merkitty poistetuksi onnistuneesti");
			//redirecttotal('admin/cssmanager/showcssclass', array(id => $cssDefinition->cssClassID));
		} else {
			echo "[{\"success\":\"false\"}]";
			//addErrorMessage("Määrittelyn poistetuksi merkitseminen epäonnistui!");
			//redirecttotal('admin/cssmanager/showcssclass', array(id => $cssDefinition->cssClassID));
		}
	}
	
	//********************************************************************************************************
	//***** CSSFILES ACTIONS
	//********************************************************************************************************
	
	public function showcssfilesAction() {
		
		//$this->registry->cssfiles = CssFile::loadCssFiles();
		
		$this->registry->template->header = 'Main';
		$this->registry->template->showContent('admin/cssmanager','cssfiles', 'CSS-tiedostot');
	
	}
	
	public function showcssfileAction() {
	
		$cssfileID = $_GET['id'];
		$this->registry->cssfile = Table::loadRow("system_cssfiles", $cssfileID);
		$this->registry->cssclasses = Table::load("system_cssclasses", "WHERE CssfileID='" . $cssfileID . "'");
		$this->registry->template->header = 'Main';
		$this->registry->template->showContent('admin/cssmanager','cssfile', $this->registry->cssfile->name);
	}
	
	
	public function updatecssfileAction() {
		
		$cssfileID = $_GET['id'];
		$cssfileName = $_GET['name'];
	
		//$success = CssFile::updateCssFile($cssfileID,$cssfileName);
		
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
	}
	
	public function showinsertcssfileAction() {
		
		$this->registry->template->header = 'Main';
		$this->registry->template->showContent('admin/cssmanager','insertcssfile', 'Uusi css-tiedosto');
	}
	
	
	
	public function insertcssfileAction() {
		
		$cssfileName = $_GET['name'];
		//$success = CssFile::insertCssFile($cssfileName);

		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
		
		//$this->registry->cssfiles = CssFile::loadCssFiles();
		$this->registry->template->header = 'Main';
		redirecttotal('admin/cssmanager/showcssfiles');
		
		
		//$this->registry->template->showContent('admin/cssmanager','cssfiles');
	
	}
	
	//***** GENERAL FUNCTIONS *******************************************************************************************
	// Pete: Pitäisikä nämä olla privatteja
	 
	public function getColumnsArray() {
	
		$columns=array();
	
		foreach($_GET as $index => $value) {
			if (($index != 'id') && ($index != 'rt')) {
				$columns[$index]=$value;
			}
		}
	
		return $columns;
	}
	
	public function getID() {
	
		foreach($_GET as $index => $value) {
			if ($index == 'id') {
				$id=$value;
			}
		}
	
		return $id;
	}
	
	public function isSuccess($success) {
	
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
	}
	
}	
?>
