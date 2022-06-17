<?php
class UIImprovedSection extends UIComponent {
	
	private 		$tabs	 	  = null;
	private			$activetab    = 0;
	
	public function __construct($title) {
		parent::__construct();
		$this->title 	 = $title;
	}
	
	public function addTab($tab) {
		
		if ($this->tabs==null) $this->tabs = array();
		$this->tabs[] = $tab;
		
	}
	
	private function createSectionHeader() {
		
		$tabs = $this->tabs;
		
		echo "<div>";
		
		foreach($tabs as $index => $tab) {
			
			$title = $tab->getTitle();
			$action = getUrl($tab->getAction());
			
			echo "<span class='titletable-div-open' onclick='doAction(\"".$action."\")'>&nbsp".$title."&nbsp</span>&nbsp";
		}
		
		echo "</div>";
	}
	

	
	private function createSectionFooter() {

		echo "<div class='section-header-closed'>";
		echo "</div>";
	}
	
	private function createContent() {
		
		$tabs 		= $this->tabs;
		$activetab  = $this->activetab;
		
		if ($tabs==null) return;
		
		echo "<div id='content'>";
		
			$tabs[$activetab]->show();
		
		echo "</div>";
		
	}
	
	public function setActiveTab($activetab,$data) {
		
		$this->activetab = $activetab;
		$this->tabs[$activetab]->setData($data);
	}
	
	private function createScripts() {
		
		//***********************************************************************************************************************
		//*** FUNCTION SHOWTAB().
		//*** Shows tab when tab-button is pressed.
		//***********************************************************************************************************************
		
		echo "<script>																											";
		echo "																													";
		echo "	function showTab(tabIndex) {																					";
		echo "																													";
		echo "																													";
		echo "																													";
		echo "	}																												";
		echo "																													";
		echo "</script>																											";
		
		//***********************************************************************************************************************
		//*** FUNCTION SHOWALLTABS(show).
		//*** Shows or hides all tabs true/false.
		//***********************************************************************************************************************
		
		echo "<script>																											";
		echo "																													";
		echo "	function doAction(action) {																						";
		echo "																													";
		echo "		$																											";
		echo "																													";
		echo "	}																												";
		echo "																													";
		echo "</script>																											";
		
		//***********************************************************************************************************************
		//*** FUNCTION SHOWTAB(tabID,show)
		//*** Shows or hides tab true/false.
		//***********************************************************************************************************************
		
		echo "<script>																											";
		echo "																													";
		echo "	function showTab(tabID) {																						";
		echo "																													";
		echo "																													";
		echo "	}																												";
		echo "																													";
		echo "</script>																											";
		
	}
	
	public function show() {
	
		$this->createSectionHeader();		
		$this->createContent();
		$this->createSectionFooter();
		
	}
}