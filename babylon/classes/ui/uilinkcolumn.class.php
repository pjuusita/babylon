<?php


class UILinkColumn extends UIColumn {


	public $sortlink;
	// Ascending, descending
	public $sortdirection = 'ascending';
	public $linkurl = null;
	public $linkvariable = null;
	public $sorticonup = null;
	public $sorticondown = null;
	public $showvariable = null;
	public $linktext = null;
	public $iconsize = 32;
	public $width = '';
	public $action = null;
	
	public function __construct($name, $linktext, $datavariable, $action, $sortlink = null, $width = NULL) {
		parent::__construct($name, $datavariable);
		$this->sortlink = $sortlink;
		$this->action = $action;
		$this->linktext = $linktext;
		$this->showvariable = null;
		
		if (is_array($width)) echo "<br> - " . $name;
		$this->width = $width;
		//$this->align = Column::ALIGN_RIGHT;
	}
	
	public function setShowVariable($showvariable) {
		$this->showvariable = $showvariable;
	}
}
?>