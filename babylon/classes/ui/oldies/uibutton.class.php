<?php
class UIButton {
	
	public $action;
	public $label;
	public $itemID;
	public $datavariable;
	
	public function __construct($label, $action, $itemID = null, $datavariable = null) {
		$this->label  		= $label;
		$this->action 		= $action;
		$this->itemID 		= $itemID;
		$this->datavariable = $datavariable;
	}
	
	public function setItemID($itemID) {
		
		$this->itemID = $itemID;
	}
	
	public function setDataVariable($datavariable) {
		
		$this->datavariable = $datavariable;
	}
	
	public function setLabel($label) {
		
		$this->label = $label;
	}
	
	public function setAction($action) {
		
		$this->action = $action;
	}
	
	private function createButton() {
		
		$action 	  = getUrl($this->action);
		$itemID 	  = $this->itemID;
		$datavariable = $this->datavariable;
		$label		  = $this->label;
		
		echo "<span class='buttonstyle' onclick='doAction(\"".$action."\",\"".$datavariable."\",\"".$itemID."\")'>";
		echo "".$label;
		echo "</span>";
		
	}
	
	
	public function show() {
		
		$this->createButton();
	}
	
}
?>