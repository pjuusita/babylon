<?php

class UIBooleanColumn extends UIColumn {

	public $data;
	public $link;
	public $width;
	public $selection;
	
	public function __construct($name, $datavariable, $data = null, $link = "", $selection = null, $width = 0) {
		parent::__construct($name, $datavariable);
		$this->data = $data;
		$this->link = $link;
		if ($selection == 0) {
			$this->selection = array();
			$this->selection[] = 'Ei';
			$this->selection[] = 'Kyllä';
		} else {
			$his->selection = $selection;
		}
		if ($width > 0) {
			$this->width = $width . 'px';
		} else {
			$this->width = '';
		}
	}
}


?>