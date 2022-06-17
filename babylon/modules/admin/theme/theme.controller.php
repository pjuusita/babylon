<?php


class ThemeController extends AbstractController {
	

	
	public function getCSSFiles() {
		return array('menu.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
	
	
	public function indexAction() {
		//$this->showthemetableAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	
//***** THEME ACTIONS ************************************************************************
	
	public function showthemetableAction() {
		
		$this->registry->themes = Table::load('system_themes');
		$this->registry->template->header = 'Main';
		$this->registry->template->show('admin/theme','themetable');
	}
	
	
	public function showthemeAction() {
		
		$themeID = $_GET['id'];
		
		$this->registry->theme = Table::loadRow("system_themes", $themeID);
		$this->registry->themeitems = Table::load("system_themeitems");
		
		$tempavelues = Table::load("system_themeitemvalues", "WHERE ThemeID='" . $themeID . "'");
		$this->registry->themeitemvalues = array();
		foreach($tempavelues as $themeitemvalue) {
			$this->registry->themeitemvalues[$themeitemvalue->themeitemID] = $themeitemvalue;
		}
		
		$this->registry->template->header = 'Main';
		$this->registry->template->show('admin/theme','theme');
	}
	
	
	public function showthemeitemsAction() {
		$this->registry->themeitems = Table::loadHierarchy('system_themeitems','parentID');
		$this->registry->template->header = 'Main';
		$this->registry->template->show('admin/theme','themeitemtable');
	}
	
	
	
	public function showthemeitemAction() {
	
		$themeItemID = $_GET['id'];
	
		$languages = Table::load('system_languages');
		$this->registry->languages = $languages;
		$this->registry->themeitem = Table::loadRow('system_themeitems',$themeItemID, true);
		$this->registry->template->header = 'Main';
		$this->registry->template->show('admin/theme','themeitem');
	}
	
	


	public function updatethemeitemAction() {
	
		$success='';
		$columns = $this->getColumnsArray();
		$themeItemID = $this->getID();
	
		//print_r($columns);
	
		//$success = ThemeItem::updateThemeItem($id,$columns);
		$success = Table::updateRow('system_themeitems', $columns, $themeItemID);
		$this->isSuccess($success);
	}
	
	
	


	public function newthemeitemAction() {
	
		$this->registry->loadParams();
	
		$this->registry->parents = Table::loadKeyValueArray("system_themeitems","ThemeitemID", "Itemname", "ParentID=0",2);
		$this->registry->languages = Table::load("system_languages","WHERE Active=1");
		
		//if (isset($_GET['themeitemID'])) $this->registry->parentID = $_GET['themeitemID'];
		//else $this->registry->parentID = 0;
		
		$this->registry->defaultitem = new Row();
		$this->registry->defaultitem->parentID = $_GET['id'];
		
		
		$this->registry->template->show('admin/theme/','newthemeitem');
	}
	
	
	/**
	 * TODO: tama ja tallaiset pitaisi korvata geneerisella toiminnalla, en osaa sanoa voidaanko tallaiset siirtaa
	 * kokonaan johonkin utilssin kaltaiseen funkkariin. Koko insert actioni voisi olla utillseissa, mutta tallain 
	 * pitaisi jotenkin siirtaa myas return urli.
	 * 
	 */
	public function insertthemeitemAction() {
	
		$this->registry->loadParams();
	
		$values = array();
		$values['Itemname'] = $_GET['Itemname'];
		$values['Description'] = $_GET['Description'];
		$values['ParentID'] = $_GET['ParentID'];
		
		$success = Table::addRow("system_themeitems",$values);
		$this->isSuccess($success);
	}
	
	
	

	public function removethemeitemAction() {
	
		$this->registry->loadParams();
	
		$themeItemID = $_GET['themeitemID'];
	
		$definitions = Table::load("system_cssdefinitions", " WHERE ThemeitemID=". $themeItemID);
		echo "<br>countti - " . count($definitions);
	
		if (count($definitions) > 0) {
			foreach($definitions as $index => $definition) {
				addErrorMessage("Poisto epaonnistui, item kaytassa: " . $definition->propertyname . "");
			}
			redirecttotal('admin/theme/showthemeitems');
	
		} else {
			//$success = ThemeItem::removeThemeItem($themeItemID);
				
			if ($success) {
				addMessage("Maarittely merkitty poistetuksi onnistuneesti");
				redirecttotal('admin/theme/showthemeitems');
			} else {
				addErrorMessage("Maarittelyn poistetuksi merkitseminen epaonnistui!");
				redirecttotal('admin/theme/showthemeitems');
			}
		}
	}
	
	/*
	public function showthemeitemvalueAction() {

		$themeID = $_GET['themeid'];
		$themeitemID = $_GET['id'];
		
		
		$this->registry->theme = Themes::loadTheme($themeID);
		$this->registry->themeitem = ThemeItem::loadThemeItem($themeitemID);
		$this->registry->themeitemvalue = ThemeItemValues::loadThemeItemValue($themeID, $themeitemID);
		
		if ($this->registry->themeitemvalue == null) {
			echo "<br>value null";
			$this->registry->defaultvalue = new ThemeItemValues();
			$this->registry->defaultvalue->themeID = $themeID;
			$this->registry->defaultvalue->themeitemID = $themeitemID;
			$this->registry->template->show('admin/thememanager','insertthemeitemvalue');
		} else {
			if ($this->registry->themeitemvalue->removeID != 0) {
				$this->registry->defaultvalue = new ThemeItemValues();
				$this->registry->defaultvalue->themeID = $themeID;
				$this->registry->defaultvalue->themeitemID = $themeitemID;
				$this->registry->template->show('admin/thememanager','insertthemeitemvalue');
			} else {
				$this->registry->template->show('admin/thememanager','themeitemvalue');
			}
		}
	}
		
	
	public function updatethemeAction() {
		
		$success='';
		$columns = $this->getColumnsArray();
		$id = $this->getID();
		
		$success = Themes::updateTheme($id,$columns);
		$this->isSuccess($success);
		
	}
	
	public function showinsertthemepageAction() {
		
		$this->registry->loadParams();
		
		$this->registry->kayttajat = User::loadUsersByID();
		$this->registry->template->show('admin/thememanager/','inserttheme');
	}
	
	
	public function insertthemeAction() {
		
		$this->registry->loadParams();
		
		$name = $_GET['Name'];
		$ownerID = $_GET['OwnerID'];
		
		$success = Themes::insertTheme($name,$ownerID);
		$this->isSuccess($success);
	
	}

	
	public function removethemeAction() {
		
		$this->registry->loadParams();
		
		$themeID = $_GET['themeid'];
		
		$success = Themes::removeTheme($themeID);
		
		if ($success) {
			addMessage("Teema merkitty poistetuksi onnistuneesti");
			redirecttotal('admin/thememanager/showthemes');
		} else {
			addErrorMessage("Teeman poistetuksi merkitseminen epaonnistui!");
			redirecttotal('admin/thememanager/showthemes');
		}
	}
	
	
	// Fyysinen poisto tietokannasta.
	public function deletethemeAction() {
		
		$this->registry->loadParams();
		
		$themeID = $_GET['themeid'];
		
		$success = Themes::deleteTheme($themeID);
		
		if ($success) {
			addMessage("Teema poistettu onnistuneesti");
			redirecttotal('admin/thememanager/showthemes');
		} else {
			addErrorMessage("Teeman poisto epaonnistui!");
			redirecttotal('admin/thememanager/showthemes');
		}
	}
	
	
//***** THEMEITEM ACTIONS **************************************************************************************
	
	
	
	public function updatethemeitemvalueAction() {
	
		$themeID = $_GET['themeid'];
		$themeItemID = $_GET['id'];
		$value = $_GET['Value'];
		
		$success = ThemeItemValues::updateThemeItemValue($themeID, $themeItemID,$value);
		$this->isSuccess($success);
	}
	
	
	
	
	public function insertthemeitemvalueAction() {

		$themeID = $_GET['themeid'];
		$themeitemID = $_GET['themeitemid'];
		$value = $_GET['Value'];
		
		$themeitem = ThemeItemValues::loadThemeItemValue($themeID, $themeitemID);
		if ($themeitem == null) {
			$success = ThemeItem::insertThemeItemValue($themeID,$themeitemID,$value);
			$this->isSuccess($success);
		} else {
			ThemeItemValues::deleteThemeItemValue($themeID, $themeitemID);
			$success = ThemeItem::insertThemeItemValue($themeID,$themeitemID,$value);
			$this->isSuccess($success);
		}
		
	}
	
	

	
	public function shownewthemeitemAction() {
	
		$this->registry->loadParams();
		$this->registry->languages = Table::load("system_languages","WHERE Active=1");
		$this->registry->template->show('admin/thememanager/','insertthemeitem');
	}
	

	
	*/
	
	
	//***** GENERAL FUNCTIONS *******************************************************************************************
	
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
