<?php


class UIStatusColumn extends UIColumn {


	public $sortlink;
	// Ascending, descending
	public $sortdirection = 'ascending';
	public $linkurl = null;
	public $linkvariable = null;
	public $sorticonup = null;
	public $sorticondown = null;
	public $iconsize = 32;
	public $width = '';
	
	public function __construct($name, $datavariable, $width = NULL) {
		
		parent::__construct($name, $datavariable);
		$this->width = $width;
		$this->statuslist = array();
	}

	public function addStatus($index, $color) {
		$this->statuslist[$index] = $color;
	}

}
?>