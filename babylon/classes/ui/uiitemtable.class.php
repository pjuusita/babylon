<?php


/**
 * Tämä on kopioitu suoraan UISectionista, tämän on tarkoitus korvata section toiminta jossa contentiksi asetetaan innertable. Joskin innertable
 * vaikuttaa kyllä toimivan ihan ok. Suurin ongelma taitaa olla taulukon lisäys.
 * 
 * NOTE: Kopiointi suoritettu 3.3.2018 saattaa olla vanhentunut
 * 
 * @author pjuusita
 *
 */

class UIItemTable extends UIAbstractSection {
	
	private $data;
	private $standalone = false;	// tämä on tarkoitettu UITable tyyppisen näkymän tuottamiseen. (tälläin lisää nappula ylhäällä)
	private $tableheadervisible = true;
	
	
	private $checkable = false;
	private $deletesuccesssaction = null;
	private $showlinenumbers;
	private $maxcolumnwidth;
	private $showtotal = false;
	
	private $sortable = false;
	

	public function __construct($title = '', $width = '600px') {
		parent::__construct($title, $width);
		$this->setFramesVisible(false);
		$this->showlinenumbers = false;
		$this->maxcolumnwidth = '300px';
		$this->tableheadervisible = true;
	}
	
	
	public function setTableHeaderVisible($boolean) {
		$this->tableheadervisible = $boolean;
	}
	
	
	public function setShowTotal($boolean) {
		$this->showtotal = $boolean;
	}
	

	public function setSortable($boolean) {
		$this->sortable = $boolean;
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
	

	public function generateContent() {
	
		
		if ($this->buttonVerticalAlign == UIComponent::VALIGN_TOP) $this->generateButtons();
		
		
		echo "<div style='width:100%'>";
		
		
		
		$rowNumber = 0;
		if (count($this->data) == 0) {
			echo "<br>Ei yhtään itemiä";
		} else {
				
			foreach($this->data as $index => $row) {
				
				//echo "<div class=section-item id=item-list-" . $row->getID . " onclick=\"" . $row->getID() . "\" >";
				//echo "<a  href='" . $this->generateLineActionUrlForHref($row) . "' style='text-decoration:none'>";
				
				echo "<div class=section-item id=item-list-" . $row->getID() . " onclick=\"" . $row->getID() . "\" >";
				$this->generateLineaction("item-list-" . $row->getID(), $row, $this->lineaction);
				echo "<table><tr>";
				foreach($this->items as $index => $column) {
					
					
					$variable = $column->datavariable;
				
					
					$class = get_class($column);
					
					switch($class) {
							
						case 'UISortColumn':
								
							echo "<td style='width:" . $column->width . "'>";
							echo "<input type=hidden id=item-list-" . $row->getID() . "-" . $column->getID() . " value='" . $row->$variable . "' />";							
							echo "" . $row->$variable . "</td>";
							break;

						case 'UISelectColumn':
							
							$datavariable = $column->datavariable;
							$showvariable = $column->showvariable;
							
							//echo "<br>datavariable - " . $datavariable;
							//echo "<br>showvariable - " . $showvariable;
								
							
							if ($showvariable == null) {
								$value = $column->selection[$row->$datavariable];
								echo "<td style='width:" . $column->width . "'>" . $value . "</td>";
							} else {
								if (is_integer($datavariable)) {
									$value = $column->selection[$row[$column->datavariable]];
								} else {
									if (isset($column->selection[$row->$datavariable])) {
										if ($showvariable == NULL) {
											$value = $column->selection[$row->$datavariable];
										} else {
											$value = $column->selection[$row->$datavariable]->$showvariable;
										}
									} else {
										$value = "<font size=-1 style='font-style:italic;color:green'>" . $row->$datavariable . "</font>";
									}
								}
								if ($row->$datavariable == 0) {
									echo "<td style='width:" . $column->width . "'>n/a</td>";
								} else {
									echo "<td style='width:" . $column->width . "'>" . $value . "</td>";
								}
							}
							break;
									
						default :
							//echo "<br>Class - " . $class;
							//echo "<br>Class2 Other - " . $class;
		
							if ($column->linkurl == null) {
								echo "" . $row->$variable;
							} else {
								$idcolumnname = $column->linkvariable;
								echo "<a href='" . getUrl($column->linkurl, array( "id" => $row->$idcolumnname)) . "'>" . $row->$variable . "</a>";
							}
							break;
					}
				}
				echo "</tr></table>";
				echo "</div>";
				//echo "</a>";
				
				
			}
		}
		echo "</div>";
		
		
		if (($this->buttonVerticalAlign == UIComponent::VALIGN_BOTTOM) || ($this->buttonVerticalAlign == 0)) $this->generateButtons();
	}
	


	public function show() {
		if ($this->framesVisible == true) {
			parent::show();			
		} else {
			$width = '600px';   // $this->width
			echo "<div style='width:" . $width  .";'>";
			$this->generateContent();
			echo "</div>";
		}
		return false;
	}
	
}

?>