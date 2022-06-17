<?php

/**
 * Tämä saatetaan korvata tulevaisuudessa tietokantaan tukeutuvalla valikon latauksella.
 * 
 * 
 * Lisättäviä toiminnallisuuksia:
 *   - CollapseSelected, toiminnallisuus hypataan johonkin muualle, joka liittyy johonkin muuhun valikkoon...
 * 
 * 
 */
class Menu {
	
	const MENUKEY_TOP = 'menukey_frontpage';
	const MENUKEY_FRONTPAGE = 'menukey_frontpage';
	const MENUKEY_ADMIN = 'menukey_admin';
	
	// Tässä on seuraavaksi yhteisiä menukeytä, näitä käytetään havaitsemaan onko useammalla
	// Eri modulilla samoja menuja. Esimerkiksi asetuksia saattaa olla useammassa modulissa
	// Tämä tarkoittaa sitten sitä, että ko. controllerissa/view-luokassa pitää huomioida
	// että onko kyseessä oleva toinen moduli aktiivinen vai ei
	// Tämä liittyy niinikään siihen, että eri modilit tarvitsevat joidenkin toisten modulien
	// olemassaoloa, esim. palkanlaskenta tarvitsee henkilöstönhallintaa.
	
	const MENUKEY_HR_SETTINGS = 'menykey_hr_settings';
	const MENUKEY_PAYROLL_SETTINGS = 'menukey_payroll_settigs';
	
	public static $menuCounter = 1;
	public $childs = array();
	
	public $menuID;
	public $name;
	public $tooltip;
	public $parentID;
	public $usergroupID;
	public $module;
	public $action;
	public $placeorder;
	public $defaultopen;
	public $menukey;
	public $parentkey;
	
	private $collapseButton = false;
	private $openByDefault = false;
	private $allwaysOpen = false;
	private $onCollapseLoad = false;
	private $onExpandLoad = true;

	
	public function __construct($name, $module, $action, $parentkey, $menukey, $placeorder) {
		
		$this->menuID = 0;
		$this->name = $name;
		$this->module = $module;
		$this->action = $action;
		$this->parentkey = $parentkey;
		$this->menukey = $menukey;
		$this->placeorder = $placeorder;
		$this->childs = array();
	}
	
	
	
	
	private static function withRow($row,$lang = 1) {
	
		$name = parseMultilangString($row->name, $lang);
		$name = $row->name;
		$module = $row->module;
		$action = $row->action;
		$menukey = $row->menukey;
		$placeorder = $row->placeorder; 
			
		$menu = new Menu($name,$module,$action,null,$menukey,$placeorder);
		$menu->menuID = $row->menuID;
		$menu->tooltip = $row->tooltip; 
		$menu->parentID = $row->parentID; 
		$menu->usergroupID = $row->usergroupID; 
		$menu->defaultopen = $row->defaultopen;
		$menu->childs = array();
		return $menu;
	}
	

	
	
	
	public function getChildCount() {
		return count($this->childs);
	}

	
	public function getChild($index) {
		return $this->childs[$index];
	}

	public function addChild($child) {
		$this->childs[] = $child;
	}

	public function getID() {
		return $this->menuID;
	}


	/**
	 * Mahdollistaa menun avaamisen ja sulkemisen ilman loadia.
	 * 
	 */	
	public function hasCollapseButton() {
		return collapseButton;
	}
	
	public function setCollapseButton($value) {
		$this->collapseButton = $value;	
	}
	
	
	public function defaultOpen() {
		return $this->defaultopen;
	}
	
	public function setDefaultOpen($value) {
		$this->allwaysOpen = $value;		
	}
	
	
	public function openByDefault() {
		return $this->openByDefault;
	}

	public function setOpenByDefault($value) {
		$this->openByDefault = $value;		
	}


	public function onCollapseLoad() {
		return $this->onCollapseLoad;		
	}
	
	public function setOnCollapseLoad($value) {
		$this->onCollapseLoad = $value;		
	}


	public function onExpandLoad() {
		return $this->onExpandLoad;
	}

	public function setOnExpandLoad($value) {
		$this->onExpandLoad = $value;		
	}
	
	// ***********************************************************
	
	public function getTitle() {
		return $this->name;
	} 
	 
		
	public function getModule() {
		return $this->module;
	}
	
	
	public function getAction() {
		return $this->action;
	}
	
	
	public function getTooltip() {
		return $this->tooltip;
	}
	
	
	
	
	public static function printMenuString($menu, $level = 0) {
		
		if ($level == 0) {
			foreach($menu as $index => $menuitem) {
				Menu::printMenuString($menuitem,1);
			}
			return;
		}
		$str = '-';
		for($i = 0;$i<$level;$i++) $str = $str . '-';
		echo "<br>" . $str . "" . $menu->name;
		foreach($menu->childs as $index => $menuitem) {
			Menu::printMenuString($menuitem,$level+1);
		}
	}
	
	/**  Load menu from database, database connection must be initialized
	 * 
	 * 
	 */
	public static function loadMenu($usergroupID, $lang = 2) {

		global $mysqli;
		$comments = false;
		
		//echo "<br>usergroup - " . $usergroupID;
		
		// TODO: menun luonti pitää hoitaa jotenkin käyttäjäryhmien, tai aktiivisten modulien avulla, varmaan sessioniin tallennus
		//       että ei mene lataamiseen aikaa
		$myrole = 3;
		
		if ($mysqli == null) echo "<br>connection null";
		
		//echo "<br>Creating menu";
		//if ($comments) echo "<br>Loadmenu... (usergroupID = " . $usergroupID . ")";
		
		$menuitems = Table::load('system_menu',"WHERE UsergroupID=" . $usergroupID . " ORDER BY Placeorder");
		
		foreach($menuitems as $index => $row) {
			$menuitem = Menu::withRow($row,$lang);
			$list[$row->menuID] = $menuitem;
		}
		
		$rootmenu = array();
		$menuarray = array();
		$mainmenu = array();
		foreach($list as $index => $menuitem) {
			if ($menuitem->parentID == 0) {
				$rootmenu[] = $menuitem;
				$menuarray[$menuitem->menuID] = $menuitem;
				//echo "<br>Lisätään rootmenu - " . $menuitem->menuID . " - " . $menuitem->name;
				unset($list[$index]);
			} else {
				//echo "<br>Ei rootmenu - "  .$menuitem->menuID . " - " . $menuitem->name;
			}
		}
		
		while (!empty($list)) {
			$counter = 0;
			foreach($list as $index => $menuitem) {			
				//echo "<br>Käsitellään menuitemiä - " . $menuitem->name . ", etsitään " . $menuitem->parent;
				if(isset($menuarray[$menuitem->parentID])) {
					//echo "<br>..Parent laytyi, " . $menuarray[$menuitem->parent]->name;
					$parent = $menuarray[$menuitem->parentID];
					$parent->addChild($menuitem);
					$menuarray[$menuitem->menuID] = $menuitem;
					unset($list[$index]);
					$counter++;
				} else {
					//echo "<br>..Parenttia ei laytynyt..";
				}
			}	
			if ($counter == 0) {
				foreach ($list as $index => $menuitem) {
					echo "<br>Menuitemille ei läytynyt parenttia - " . $menuitem->menuID . ": " . $menuitem->name;
				}
				break;
			}	
		}
		
		
		
		//Menu::printMenuString($rootmenu);
		
		return $rootmenu;
	}
	
}
