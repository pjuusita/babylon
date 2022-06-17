<?php

/**
 * Kopioitu SortColumnista turhia kenttiä
 * 
 * @author pjuusita
 *
 */

class UIHiddenColumn extends UIColumn {


	public $sortlink;
	// Ascending, descending
	public $sortdirection = 'ascending';
	public $linkurl = null;
	public $linkvariable = null;
	public $sorticonup = null;
	public $sorticondown = null;
	public $iconsize = 32;
	public $width = '';
	
	public function __construct($name, $datavariable, $sortlink = null, $width = '') {
		
		parent::__construct($name, $datavariable);

		$this->sortlink = $sortlink;
		if (is_array($width)) echo "<br> - " . $name;
		$this->width = $width . 'px';
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