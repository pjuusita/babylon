<?php



class UIFilterBox extends UIComponent {

	private $fieldCounter;
	private $actions;
	private $texts;
	private $items;
	private $selected;
	private $itemvariables;
	private $urlparams;
	private $itemtypes;
	private $width;
	
	private $emptyselect = true;
	private $emptytextt = "";
	
	private static $SELECT_FILTER = 1;
	private static $TEXT_FILTER = 2;
	
	
	public function __construct($width = "200px"){
		parent::__construct();
		
		$this->width = $width;
		$this->fieldCounter = 0;
		$this->actions = array();
		$this->texts = array();
		$this->items = array();
		$this->selected = array();
		$this->itemvariables = array();
		$this->itemtypes = array();
		$this->urlparams = array();
	}


	public function setEmptySelect($boolean, $emptytext = "") {
		$this->emptyselect = $boolean;
		$this->emptytext = $emptytext;
	}
	
	
	
	public function addSelectFilter($selectedID, $items, $action, $emptytext = "", $urlparam = NULL, $itemvariable = NULL) {
		$this->actions[$this->fieldCounter] = $action;
		$this->itemtypes[$this->fieldCounter] = UIFilterBox::$SELECT_FILTER;
		$this->texts[$this->fieldCounter] = $emptytext;
		$this->items[$this->fieldCounter] = $items;
		$this->selected[$this->fieldCounter] = $selectedID;
		$this->itemvariables[$this->fieldCounter] = $itemvariable;
		$this->urlparams[$this->fieldCounter] = $urlparam;
		$this->fieldCounter++;
	}
	
	
	public function addTextFilter($action, $selectedvalue = "", $emptytext = "", $urlparam = NULL) {
		$this->actions[$this->fieldCounter] = $action;
		$this->texts[$this->fieldCounter] = $emptytext;
		$this->itemtypes[$this->fieldCounter] = UIFilterBox::$TEXT_FILTER;
		$this->items[$this->fieldCounter] = null;
		$this->selected[$this->fieldCounter] = $selectedvalue;
		$this->itemvariables[$this->fieldCounter] = null;
		$this->urlparams[$this->fieldCounter] = $urlparam;
		$this->fieldCounter++;
	}
	
	
	public function show() {

		$height = "30";
		
		echo "<div'>";
		//echo "<div style='border-left:2px solid #aaaaaa;margin-left:15px;'>";
		$first = true;
		foreach($this->actions as $selectID => $action) {
			
			if ($this->itemtypes[$selectID] == UIFilterBox::$TEXT_FILTER) {
				echo "<input id=filterselect_". $this->getID() ."_" . $selectID . " value='" . $this->selected[$selectID] . "' class=field-select style='padding-left:5px;margin-top:4px; height:" . $height . "px;width:" . $this->width . "'>";
				echo "	<script>";
				echo "	$('#filterselect_". $this->getID() ."_" . $selectID . "').keyup(function(e){";
				echo "		if(e.keyCode == 13)";
				echo "		{";
				//echo "			value = $('#filterselect_". $this->getID() ."_" . $selectID . "').val();";
				//echo "			alert('value - " . $this->urlparams[$selectID] . " - '+value);";
				echo "			value = $('#filterselect_". $this->getID() ."_" . $selectID . "').val();";
				echo "			window.location = '" . getUrl($action) . "&" . $this->urlparams[$selectID] . "='+value";
				echo "		}";
				echo "	});";
				echo "	</script>";
			} else {
				if ($first == true) {
					echo "<select id=filterselect_". $this->getID() ."_" . $selectID . " onchange=\"filterselect_" . $this->getID() . "_" . $selectID . "(this.value);\" class=field-select style='height:" . $height . "px;width:" . $this->width . "'>";
					$first = false;
				} else {
					echo "<select id=filterselect_". $this->getID() ."_" . $selectID . " onchange=\"filterselect_" . $this->getID() . "_" . $selectID . "(this.value);\" class=field-select style='margin-top:4px;height:" . $height . "px;width:" . $this->width . "'>";
				}

				// TODO: Tämä pitäisi tehdä niin, että voidaan jotenkin valita, että tyhjä valinta on ylipäätään mahdollinen, tämä 
				// on tarpeen silloin kun jokin on pakko olla valittu.
				if ($this->emptyselect == true) {
					if ($this->texts[$selectID] != null) {
						echo "<option value='0' selected>" . $this->texts[$selectID] . "</option>";
					} else {
						if (isset($this->emptytext)) {
							echo "<option value='0' selected>" . $this->emptytext . "</option>";
						} else {
							echo "<option value='0' selected></option>";
						}
					}
				} 
				foreach($this->items[$selectID] as $index => $item) {
					if ($this->itemvariables[$selectID] == NULL) {
						//echo "<option value='0' selected>Nulli - " . $selectID . "</option>";
						$itemstr = $item;
					} else {
						$var = $this->itemvariables[$selectID];
						$itemstr = $item->$var;
					}
				
					if ($this->selected[$selectID] == $index) {
						echo "<option value='" . $index . "' selected>" . $itemstr . "</option>";
					} else {
						echo "<option value='" . $index . "'>" . $itemstr . "</option>";
					}
				}
				echo "	</select>";
				
				echo "	<script>";
				echo "		function filterselect_" . $this->getID() . "_" . $selectID . "(value) {";
				//echo "			alert('filterchange - " . $action . "&" . $this->urlparams[$selectID] . "='+value);";
				echo "			window.location = '" . getUrl($action) . "&" . $this->urlparams[$selectID] . "='+value";
				echo "		};";
				echo "	</script>";
			}
		}
		//echo "	</div>";
		echo "	</div>";
	}
	
}


?>