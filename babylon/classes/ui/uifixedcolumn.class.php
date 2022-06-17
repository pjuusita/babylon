<?php



class UIFixedColumn extends UIColumn {

	public $link;

	public function __construct($name, $datavariable) {
		parent::__construct($name,$datavariable);
	}
	
	public function setLink($url,$linkvariable) {
		$this->link = $url;
	}
}
?>