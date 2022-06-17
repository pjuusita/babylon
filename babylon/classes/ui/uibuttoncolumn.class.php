<?php


class UIButtonColumn extends UIColumn {

	public $sortlink;

	public $height = '20';
	public $width = '21';
	public $columnwidth = '40px';
	
	public $action;
	public $icon;
	public $name;
	public $actiontype;
	public $colorvariable;
	public $color;
	
	
	public function __construct($actiontype, $datavariable, $action) {
		parent::__construct("", $datavariable);
		$this->action = $action;
		$this->actiontype = $actiontype;
		$this->name = "";
		$this->icon = null;
		$this->color = null;
		$this->colorvariable = null;
	}
	
	
	public function setWidth($width) {
		$this->width = $width;
	}

	public function setTitle($title) {
		$this->name = $title;
	}
	
		
	public function setIcon($iconName) {
		$this->icon = $iconName;
	}
	
	
	public function setSize($width, $height) {
		$this->height = $height;
		$this->width = $width;
	}
	
	
	public function setColorVariable($colorvariable) {
		$this->colorvariable = $colorvariable;
	}
	

	public function setColor($color) {
		$this->color = $color;
	}
		
	
	/**
	 * Määrittää mitä tehdään jos buttonin painaminen onnistuu, käytetään lähinnä JSON:illa.
	 * 
	 * @param string $actiontype
	 * @param string $datavariable
	 * @param string $action
	 */
	public function setSuccessAction($actiontype, $datavariable, $action) {
		// TODO
	}
	
	
	/**
	 * Määrittää mitä tehdään jos buttonin painaminen epäonnistuu, JSON palauttaa failin.
	 *
	 * @param string $actiontype
	 * @param string $datavariable
	 * @param string $action
	 */
	public function setFailAction($actiontype, $datavariable, $action) {
		// TODO	
	}
}

?>