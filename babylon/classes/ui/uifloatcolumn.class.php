<?php


class UIFloatColumn extends UIColumn {


	public $sortdirection = 'ascending';
	public $linkurl = null;
	public $linkvariable = null;
	public $sorticonup = null;
	public $sorticondown = null;
	public $iconsize = 32;
	public $width = '';
	
	public function __construct($name, $datavariable, $width = NULL) {
		
		parent::__construct($name, $datavariable);

		if (is_array($width)) echo "<br> - " . $name;
		$this->width = $width;
	}

	public function setLink($linkurl, $linkvariable) {
		$this->linkurl = $linkurl;
		$this->linkvariable = $linkvariable;
	}
		
	public function setSortIcons($iconup,$icondown,$size) {

		$this->sorticonup = $iconup;
		$this->sorticondown = $icondown;
		$this->iconsize = $size;
	}
}


?>