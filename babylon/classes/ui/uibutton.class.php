<?php




class UIButton extends UIComponent {
	
	public $action;
	public $label;
	public $datavariable;
	public $actiontype;
	public $icon;
	
	public $successactiontype;
	public $successaction;
	
	public $failactiontype;
	public $failaction;
	
	
	public function __construct($actiontype, $action, $label, $datavariable = null) {
		parent::__construct();
		$this->label = $label;
		$this->action = $action;
		$this->actiontype = $actiontype;
		$this->datavariable = $datavariable;
	}
	
	
	public function setLabel($label) {
		$this->label = $label;
		$this->icon = null;
	}
	
	public function setIcon($icon) {
		$this->icon = $icon;		
	}
	
	public function setAction($action) {
		$this->action = $action;
	}
	
	
	/**
	 *  Kun buttonin actiontype = ACTION_JSON, suoritetaan kun JSON suoritetaan onnistuneesti
	 *  Ellei tätä ole asetettu, suljetaan dialogi/editointi ja näytetään message asianmukaisessa paikassa, ja epäonnistuneessa
	 *  tapauksessa tulostetaan virheilmoitus mutta ei suljeta.
	 */
	public function setSuccessAction($actiontype, $action) {
		$this->successactiontype = $actiontype;		
		$this->successaction = $action;		
	}
	
	/**
	 *  Kun buttonin actiontype = ACTION_JSON, suoritetaan kun JSON alauttaa epäonnistuneen suorituksen
	 *  Ellei tätä ole asetettu, suljetaan dialogi/editointi ja näytetään virheilmotus asianmukaisessa paikassa.
	 */
	public function setFailAction($actiontype, $action) {
		$this->failactiontype = $actiontype;
		$this->failaction = $action;
	}
	
	
	
	public function show() {
		if ($this->icon != null) {
			if ($this->label == "") {
				echo "<button class=section-button id='button-" . $this->getID() . "' style='height:25px;margin-left:2px;'><i class='fa " . $this->icon . "'></i></button>";
			} else {
				echo "<button class=section-button id='button-" . $this->getID() . "' style='width:20px;margin-left:2px;'><i class='fa " . $this->icon . "'></i>" . $this->label . "</button>";
			}
		} else {
			echo "<button class=section-button id='button-" . $this->getID() . "' style='margin-left:2px;'>" . $this->label . "</button>";
		}
		echo "<script>";
		switch($this->actiontype) {
			case UIComponent::ACTION_FORWARD :
				
				echo "  $('#button-" . $this->getID() . "').click(function () {";
				//echo "		console.log('buttonaction ACTION_FORWARD');";
				//echo "		alert('buttonaction ACTION_FORWARD');";
				echo "		window.location = '" . getUrl($this->action) . "';";
				echo "	});";
				break;
			case UIComponent::ACTION_NEWWINDOW :
				echo "  $('#button-" . $this->getID() . "').click(function () {";
				//echo "		console.log('opentab');";
				echo "		opennewtab('" . getNoframesUrl($this->action) . "');";
				echo "	});";
				break;
			case UIComponent::ACTION_JAVASCRIPT:
				echo "  $('#button-" . $this->getID() . "').click(function () {";
				//echo "		console.log('buttonaction OPENDIALOG');";
				echo "		" . $this->action . "(" . $this->getID() . ");";
				//echo "		alert('buttonaction ACTION_JAVASCRIPT');";
				echo "	});";
				break;
			case UIComponent::ACTION_OPENDIALOG:
				echo "  $('#button-" . $this->getID() . "').click(function () {";
				//echo "		console.log('buttonaction OPENDIALOG');";
				echo "  	$('#sectiondialog-" . $this->action . "').dialog('open');";
				echo "	});";
				break;
			default :
				break;
		}
		echo "</script>";
	}
}
?>