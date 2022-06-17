<?php


class UITabSection extends UIAbstractSection {

	public $tabIndex;
	public $tabs;
	public $tabAction;
	public $activeIndex;
	public $contenttable;
	private $customConcentFunction;
	
	public function __construct($title = '', $width = '800px') {
		parent::__construct($title, $width);
		$this->setFramesVisible(false);
		//$this->showlinenumbers = false;
		//$this->width = $width;
		//$this->maxcolumnwidth = '300px';
		//$this->tableheadervisible = true;
		//$this->sortactiontype = 0;
		$this->customConcentFunction = null;
		
		$this->activeIndex = 0;
		$this->tabIndex = 0;
		$this->tabs = array();
		$this->tabActions = array();
	}
	
	
	

	public function setCustomContent($customContentFunction) {
		$this->customConcentFunction = $customContentFunction;
	}
	
	
	
	/**
	 * Deleteactivation param kertoo minkä arvon perusteella määritellään onko
	 * kyseisen rivin poisto nappula näkyvissä vai ei (harmaannutettuna). Mikäli
	 * kyseisen muuttujan arvo on nolla tai false, niin tulkitaan, että delete ei 
	 * ole mahdolllista / nappula ei saa olla aktiivinen.
	 * 
	 * @param string $activationparam
	 */
	public function setDeleteActiveParam($activationparam) {
		$this->deleteactiveparam = $activationparam;
	}
	
	
	public function setTableHeaderVisible($boolean) {
		$this->tableheadervisible = $boolean;
	}
	
	
	public function setShowTotal($boolean) {
		$this->showtotal = $boolean;
	}
	
	
	public function setShowSumRow($boolean) {
		$this->showsumrow = $boolean;
	}
	

	
	public function setSortable($sortactiontype, $sortaction, $sortvariable) {
		$this->sortactiontype = $sortactiontype;
		$this->sortaction = $sortaction;
		$this->sortvariable = $sortvariable;
	}
	
	
	
	/**
	 * Data voi olla joko ihan taulukko taulukoita, tai sitten taulukko row-luokkia. Row-luokkia (tai ehkä mikä tahansa luokka käy) varten 
	 * tarvitaan ehkä sitten columnssit...
	 * 
	 * {@inheritDoc}
	 * @see UIAbstractSection::setData()
	 */
	public function setData($data) {
		$this->data = $data;
	}
	
	
	
	
	public function setButtonAlign($align) {
		$this->buttonVerticalAlign = $align;
	}
	
	

	
	
	/**
	 * Mikäli tätä ei ole asetettu, oletetaan, että action url forwardoi automaattisesti oikeaan 
	 * paikkaan. Tämän tarkoitus on lähinnä mahdollistaa javascriptin deleten käsittelijä.
	 * Debugissa voisi olla hyvä, että javascript käsittelijää ei käytetä vaan aina mennään forwardilla
	 * 
	 * // TODO: yhtenäinen toiminto muihinkin deleteactioneihins
	 * 
	 * @param string $deletefailaction
	 */
	public function setDeleteSuccessAction($deletefailaction) {
		//$this->deletesuccessaction = $deletesuccessacion;
	}
	
	/**
	 * Mikäli tätä ei ole asetettu, oletetaan, että action url forwardoi automaattisesti oikeaan
	 * paikkaan. Tämän tarkoitus on lähinnä mahdollistaa javascriptin deleten käsittelijä. 
	 * Debugissa voisi olla hyvä, että javascript käsittelijää ei käytetä vaan aina mennään forwardilla
	 *
	 * @param string $deletefailaction
	 */
	public function setDeleteFailAction($deletefailaction) {
		//$this->deletefailaction = $deletefailaction;
	}
	
	

	public function addColumn($column) {
		if ($this->items == null) $this->items = array();
		$this->items[] = $column;
	}
	
	
	public function showAsStandalone($boolean = true) {
		$this->standalone = $boolean;
	}
	
	
	public function showLineNumbers($boolean = true) {
		$this->showlinenumbers = $boolean;
	}
	
	

	public function showRowNumbers($boolean = true) {
		$this->showlinenumbers = $boolean;
	}
	
	public function addTab($title, $dataAction) {
		$this->tabs[$this->tabIndex] = $title;
		$this->tabActions[$this->tabIndex] = $dataAction;
		//echo "<br>index - " . $this->tabIndex . " - " . $dataAction;
		$this->tabIndex++;
		return $this->tabIndex - 1;
	}
	
	
	public function setActiveIndex($index) {
		$this->activeIndex = $index;
	}
	
	
	public function generateContent() {
	
		echo "<div style='white-space: nowrap; overflow-x:hidden;'>";
		$counter = 0;
		//echo "<br>ActiveIndex - " . $this->activeIndex;
		
		foreach($this->tabs as $index => $tabname) {
			if ($index == $this->activeIndex) {
				echo "<button class=tabbuttonactive>" . $tabname . "</button>";
				echo "<div style='vertical-align:bottom;width:5px;display:inline-block;border-bottom: 2px solid #ccc;margin-bottom:0px;'></div>";
				
				
			} else {
				echo "<button id='button-" . $this->getID() . "-"  .$counter . "' class=tabbuttondisabled>" . $tabname . "</button>";
				//echo "<div style='margin:0px;display:inline-block;width:5px;height:30px;border-bottom: 2px solid #ccc;background-color:yellow;'></div>";
				echo "<div style='vertical-align:bottom;width:5px;display:inline-block;border-bottom: 2px solid #ccc;margin-bottom:0px;'></div>";
				
				echo "<script>";
				echo "	$('#button-" . $this->getID() . "-"  .$counter . "').click(function() {";
				//echo "		alert('" . $this->tabActions[$index] . "');";
				echo "		window.location = '" . getUrl($this->tabActions[$index]) . "';";
				echo "	});";
				echo "</script>";
			}
			$counter++;
		}		
		//echo "<div style='background-color:pink;width:400px;vertical-align:bottom;display:inline-block;border-bottom: 2px solid #ccc;'></div>";
		echo "<div style='display: inline-block;height:5px;overflow:hidden;vertical-align:bottom;width:400px;border-bottom: 2px solid #ccc;'></div>";
		
		
		
		
		
		echo "</div>";
		echo "<div id=content-" . $this->getID() . " style='padding:10px 10px 10px 20px;border-radius: 0px 4px 0px 4px;	border-bottom: 2px solid; border-left: 2px solid; border-right: 2px solid; border-color: #ccc;'>";
		
		if ($this->customConcentFunction == null) {
			$this->contenttable->show();		
		} else {
			callFunc($this->customConcentFunction);
		}
		
		
		echo "</div>";		
		
		
		
	}
	

	public function setContent($table) {
		$this->contenttable = $table;
	}
	
	public function setDialog($boolean) {
		$this->dialog = $boolean;
	}
	
	
	

	public function show() {
		
	
		if ($this->framesVisible == true) {
			echo "<div style='width:" . $this->sectionwidth  .";'>";
			$this->generateSectionHeader();
			$this->generateContent();
			$this->generateFooter();
			echo "</div>";
		} else {
			if ($this->sectionwidth != null) {
				echo "<div style='width:" .  $this->sectionwidth  .";'>";
				$this->generateContent();
				echo "</div>";
			} else {
				$width = '600px'; 
				echo "<div style='width:" .  $width  .";'>";
				$this->generateContent();
				echo "</div>";
			}
		}
		return false;
	}
	
}

?>