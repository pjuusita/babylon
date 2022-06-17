<?php


class ThememanagerController extends AbstractController {
	

	
	public function getCSSFiles() {
		//return array('testcss.php', 'menu.css','mytheme/jquery-ui.css','yritys.css','prism.css','chosen.css');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','yritys.css','prism.css','chosen.css','petestyle.css');
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
	
	
	public function indexAction() {
		//$this->showthemesAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	
//***** THEME ACTIONS ************************************************************************
	
	public function showthemesAction() {
		
	    // Tämä lienee korvatt row-luokalla
		//$this->registry->themes = Themes::loadThemes();
		$this->registry->template->header = 'Main';
		$this->registry->template->show('admin/thememanager','themes');
	}
	
	
	public function showthemeAction() {
		
		$themeID = $_GET['id'];
		$this->registry->theme = Table::loadRow("system_themes", $themeID);
		$this->registry->themeitems = Table::load("system_themeitems");
		
		$tempavelues = Table::load("system_themeitemvalues", " WHERE ThemeID='" . $themeID . "'");
		$this->registry->themeitemvalues = array();
		foreach($tempavelues as $themeitemvalue) {
			$this->registry->themeitemvalues[$themeitemvalue->themeitemID] = $themeitemvalue;
		}
		
		$this->registry->template->header = 'Main';
		$this->registry->template->show('admin/thememanager','theme');
	}
	
	
	public function showthemeitemvalueAction() {

		
		/*
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
		*/
	}
		
	
	public function updatethemeAction() {
		
		$success='';
		$columns = $this->getColumnsArray();
		$id = $this->getID();
		
		// Tämä lienee korvattu Row-luokalla
		//$success = Themes::updateTheme($id,$columns);
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
		
		// Tämä lienee korvattu Row-luokalla
		// $success = Themes::insertTheme($name,$ownerID);
		$this->isSuccess($success);
	
	}

	
	public function removethemeAction() {
		
		$this->registry->loadParams();
		
		$themeID = $_GET['themeid'];
		
		// Tämä lienee korvattu Row-luokalla
		// $success = Themes::removeTheme($themeID);
		
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
		
		// Tämä lienee korvattu Row-luokalla
		// $success = Themes::deleteTheme($themeID);
		
		if ($success) {
			addMessage("Teema poistettu onnistuneesti");
			redirecttotal('admin/thememanager/showthemes');
		} else {
			addErrorMessage("Teeman poisto epaonnistui!");
			redirecttotal('admin/thememanager/showthemes');
		}
	}
	
	
//***** THEMEITEM ACTIONS **************************************************************************************
	
	public function showthemeitemsAction() {
		
		$this->registry->themeitems = Table::load('system_themeitems');
		$this->registry->themes = Table::load('system_themes');
		
		$this->registry->template->header = 'Main';
		$this->registry->template->show('admin/thememanager','themeitems');
	}
	

	public function showthemeitemAction() {
	
		$themeItemID = $_GET['id'];
		$languages = Table::load('system_languages');
		$this->registry->languages = $languages;
		$this->registry->themeitem = Table::loadRow('system_themeitems',$themeItemID, true);
		//$this->registry->themeitem->name 
		$this->registry->template->header = 'Main';
		$this->registry->template->show('admin/thememanager','themeitem');
	}
	
	
	public function updatethemeitemvalueAction() {
	
		$themeID = $_GET['themeid'];
		$themeItemID = $_GET['id'];
		$value = $_GET['Value'];
		
		// Tämä lienee korvattu Row-luokalla
		// $success = ThemeItemValues::updateThemeItemValue($themeID, $themeItemID,$value);
		$this->isSuccess($success);
	}
	
	
	
	
	public function insertthemeitemvalueAction() {

		$themeID = $_GET['themeid'];
		$themeitemID = $_GET['themeitemid'];
		$value = $_GET['Value'];
		
		// Tämä lienee korvattu Row-luokalla
		// $themeitem = ThemeItemValues::loadThemeItemValue($themeID, $themeitemID);
		if ($themeitem == null) {
		    // Tämä lienee korvattu Row-luokalla
		    // $success = ThemeItem::insertThemeItemValue($themeID,$themeitemID,$value);
			$this->isSuccess($success);
		} else {
		    // Tämä lienee korvattu Row-luokalla
		    //ThemeItemValues::deleteThemeItemValue($themeID, $themeitemID);
		    // Tämä lienee korvattu Row-luokalla
		    //$success = ThemeItem::insertThemeItemValue($themeID,$themeitemID,$value);
			$this->isSuccess($success);
		}
		
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
	
	
	public function shownewthemeitemAction() {
	
		$this->registry->loadParams();
		$this->registry->languages = Table::load("system_languages","WHERE Active=1");
		$this->registry->template->show('admin/thememanager/','insertthemeitem');
	}
	
	
	public function insertthemeitemAction() {
		
		$this->registry->loadParams();
		$itemname = $_GET['Itemname'];
		$description = $_GET['Description'];
		// Tämä lienee korvattu Row-luokalla
		//$success = ThemeItem::insertThemeItem($itemname,$description);
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
			redirecttotal('admin/thememanager/showthemeitems');
				
		} else {
		    // Tämä lienee korvattu Row-luokalla
		    //$success = ThemeItem::removeThemeItem($themeItemID);
			
			if ($success) {
				addMessage("Maarittely merkitty poistetuksi onnistuneesti");
				redirecttotal('admin/thememanager/showthemeitems');
			} else {
				addErrorMessage("Maarittelyn poistetuksi merkitseminen epaonnistui!");
				redirecttotal('admin/thememanager/showthemeitems');
			}
		}
	}

	
	
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
