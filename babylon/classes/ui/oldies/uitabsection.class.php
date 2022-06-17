<?php

class UITabSection {
	
	private static $tabsection_count = 0;
	private $tabsectionID;
	private $tabs 		 			 = null;
	private $activetabID;
	
	public function __construct($title) {
		
		$this->getID() = self::$tabsection_count;
		$this->title 		= $title;
		
		self::$tabsection_count++;
		
	}
	
	public function addTab($tab) {
		
		if ($this->tabs==null) {
			$this->tabs 	   = array();
			$this->activetabID = 0;
		}

		if (get_class($tab) == 'UISection') $tab->setOpen(true);
		$this->tabs[] = $tab;
	}
	
	public function setActiveTab($activetab) {
		
		$tabs = $this->tabs;
		
		foreach($tabs as $index => $tab) {
			if ($activetab === $tab) $active_tabID = $index; 
		}
		
	}
	
	private function setActiveTabID($tabID) {
		$this->activetabID;
	}
 
	public function createTabSectionHeader() {

		$tabs = $this->tabs;
		
		echo "<table>";
		
		echo "	<tr>";
		
		foreach($tabs as $index => $tab) {
			
			$title 	   		= $tab->getTitle();
			$tabsectionID 	= $this->getID();
			$show_parameter = $tabsectionID."_".$index;  
			
			echo "<th>";
			echo "<span id='tabbutton_".$show_parameter."' class='buttonstyle' onclick=showTab('tab_".$show_parameter."')>";
			echo "".$title;
			echo "</span>";
			echo "</th>";
		}
		
		echo "	</tr>";
		
		echo "</table>";
	}
	
	public function createTabs() {
		
		$tabs 	 	  = $this->tabs;
		$activetabID  = $this->activetabID;
		$tabsectionID = $this->getID();
		
		foreach($tabs as $index => $tab) {
			
			if ($index==$activetabID) echo "<div id='tab_".$tabsectionID."_".$index."' name='tabsection_".$tabsectionID."'>";
			if ($index!=$activetabID) echo "<div id='tab_".$tabsectionID."_".$index."' name='tabsection_".$tabsectionID."' style='display:none'>";
				
				$tab->show();
				
			echo "</div>";
		}
	}
	
	private function createScripts() {
		
		//***************************************************************************************************************
		//*** FUNCTION SHOWTAB(tabID,tabSectionID)
		//*** Shows tabs and hides others.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function showTab(tabID) {																				";
		echo "																											";
		echo "		var showtab   = getTabElement(tabID);																";
		echo "		var sectionID = getSectionID(showtab);																";
		echo "		var alltabs   = getTabElements(sectionID);															";
		echo "		var length 	  = alltabs.length;																		";
		echo "																											";
		echo "		for(var n=0;n<length;n++) {																			";
		echo "			hideElement(alltabs[n]);																		";
		echo "			toggleButton(alltabs[n],false);																	";
		echo "		}																									";
		echo "																											";
		echo "		showElement(showtab);																				";
		echo "		toggleButton(showtab,true);																			";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";

		//***************************************************************************************************************
		//*** FUNCTION HIDEELEMENT(element)
		//*** Hides element.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function hideElement(element) {																			";
		echo "																											";
		echo "			var elementID = '#' + element.id;																";
		echo "			$(elementID).hide();																			";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION SHOWELEMENT(element)
		//*** Shows element.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function showElement(element) {																			";
		echo "																											";
		echo "			var elementID = '#' + element.id;																";
		echo "			$(elementID).show();																			";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		
		
		//***************************************************************************************************************
		//*** FUNCTION GETTABELEMENT
		//*** Returns tab-element with tabID.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getTabElement(tabID) {																			";
		echo "																											";
		echo "			var element = document.getElementById(tabID);													";
		echo "			return element;																					";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETSECTIONID(element)
		//*** Returns tabsectionid-part of the element's id.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getSectionID(element) {																		";
		echo "																											";
		echo "			var elementID = element.id;																		";
		echo "			var IDs = elementID.split('_');																	";
		echo "			return IDs[1];																					";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION getTabID(element)
		//*** Returns indexID (column index/count) part of the element.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getTabIndexID(element) {																		";
		echo "																											";
		echo "			var elementID = element.id;																		";
		echo "			var IDs = elementID.split('_');																	";
		echo "																											";
		echo "			return IDs[2];																					";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETTABELEMENTS(tabsectionID)
		//*** Returns all elements contained on tabsection.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getTabElements(tabsectionID) {																	";
		echo "																											";
		echo "			var name = 'tabsection_' + tabsectionID;														";
		echo "			var elements = document.getElementsByName(name);												";
		echo "																											";
		echo "			return elements;																				";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION TOGGLEBUTTON(element,istoggled)
		//*** Toggles tab-button of given element active or inactive.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function toggleButton(element,istoggled) {																";
		echo "																											";
		echo "			var button = getButton(element); 																";
		echo "			var buttonID = '#' + button.id;																	";
		echo "																											";
		echo "			if (istoggled==true) {																			";
		echo "																											";
		echo "				$(buttonID).removeClass('buttonstyle');					  									";
		echo "				$(buttonID).addClass('successdiv');					  										";
		echo "																											";
		echo "			}																								";
		echo "			if (istoggled==false) {																			";
		echo "																											";
		echo "				$(buttonID).addClass('buttonstyle');					  									";
		echo "				$(buttonID).removeClass('successdiv');					  										";
		echo "																											";
		echo "			}																								";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION SAVEROWANDHIDEROW(save,show)
		//*** Saves and hides row and shows fixed row given as paramaters.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getButton(element) {																			";
		echo "																											";
		echo "			var tabIndexID = getTabIndexID(element); 														";
		echo "			var sectionID  = getSectionID(element); 														";
		echo "			var buttonID   = 'tabbutton_'+sectionID+'_'+tabIndexID;											";
		echo "			var button     = document.getElementById(buttonID);												";
		echo "																											";
		echo "			return button;																					";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
	}
	
	
	public function show() {
		
			$this->createTabSectionHeader();
			$this->createTabs();
			$this->createScripts();
	}
}
?>