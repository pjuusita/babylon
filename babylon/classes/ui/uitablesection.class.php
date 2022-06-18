<?php


/**
 * Tämä on kopioitu suoraan UISectionista, tämän on tarkoitus korvata section toiminta jossa contentiksi asetetaan innertable. Joskin innertable
 * vaikuttaa kyllä toimivan ihan ok. Suurin ongelma taitaa olla taulukon lisäys.
 * 
 * NOTE: Kopiointi suoritettu 3.3.2018 saattaa olla vanhentunut
 * 
 * TODO: lisää showCountRow -- näyttää viimeisellä rivillä rivien kokonaislukumäärän, tämä on kaiketi 
 * vaihtoehtoinen showSumRow-toiminnolle, molemmat eivät ehkä voi olla päällä, tai miksei.
 * 
 * @author pjuusita
 *
 */

class UITableSection extends UIAbstractSection {

	private $data;
	private $standalone = false;	// tämä on tarkoitettu UITable tyyppisen näkymän tuottamiseen. (tälläin lisää nappula ylhäällä)
	private $tableheadervisible = true;
	
	//private $columns = null;
	
	private $checkable = false;
	private $deletesuccesssaction = null;
	private $showlinenumbers;
	private $showselectboxes;
	private $selectedcolumnvariable = null;	
	private $selectablecolumnvariable = null;
	private $selectcolumnidvariable = null;	
	
	private $maxcolumnwidth;
	private $showtotal = false;
	private $width;
	private $dialog;
	private $widths = null;
	
	
	private $sortactiontype;
	private $sortaction;
	private $sortvariable;
	private $deleteactiveparam;
	private $errormessage = null;
	private $sortable = false;
	private $showsumrow = false;
	private $linebackground = null;
	private $settingsaction = null;
	
	private $topselectedID = null;
	private $topkeyvariable = null;
	private $topnamevariable = null;
	private $topselection = null;
	private $toptargeturl = null;
		
	
	public function __construct($title = '', $width = '800px') {
		parent::__construct($title, $width);
		$this->setFramesVisible(false);
		$this->showlinenumbers = false;
		$this->showselectboxes = false;
		$this->buttonVerticalAlign == UIComponent::VALIGN_TOP;
		$this->width = $width;
		$this->maxcolumnwidth = '300px';
		$this->tableheadervisible = true;
		$this->showTitle = true;
		$this->title = $title;
		$this->sortactiontype = 0;
		$this->linebackground = null;
		$this->widths = array();
	}
	
	

	public function setColumnWidth($index, $width) {
		$this->widths[$index] = $width;
	}
	
	/**
	 * Oletuksena on nyt alkuvaiheessa, että tämä settingsaction on aina jonkin dialogin avaaminen
	 * 
	 * @param string $settingsaction
	 */
	public function setSettingsAction($settingsaction) {
		$this->settingsaction = $settingsaction;		
	}
	
	
	public function setLineBackground($colorvariable) {
		$this->linebackground = $colorvariable;
	}
	
	
	public function addTopSelection($selectedID, $keyvariable, $topselection, $namevariable, $toptargeturl) {
		$this->topselectedID = $selectedID;
		$this->topkeyvariable = $keyvariable;
		$this->topnamevariable = $namevariable;
		$this->topselection = $topselection;
		$this->toptargeturl = $toptargeturl;	
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
	
	public function setErrorMessage($message) {
		$this->errormessage = $message;
	}
	
	
	public function showTableHeader($boolean) {
		$this->tableheadervisible = $boolean;
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
		//echo "<br>SetButtonAlign - " . $align;
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
	

	// selectedcolumnvariable kertoo millä muuttujalla katsotaan onko täppä paikalla, nollasta eroava = checked
	// selectablecolumnvariable kertoo onko rivi täpättävissä, arvolla nolla checkbox = disabled
	public function showSelectBoxes($boolean = true, $selectedcolumnvariable = null, $selectablecolumnvariable = null, $idcolumnvariable = null) {
		$this->showselectboxes = $boolean;
		$this->selectedcolumnvariable = $selectedcolumnvariable;
		$this->selectablecolumnvariable = $selectablecolumnvariable;
		$this->selectcolumnidvariable = $idcolumnvariable;
	}
	
	
	// asetetaan functio, joka laukeaa kun jotain checkboxia clikataan. Tämän avulla
	// esimerkiksi voidaan träkätä valittujen rivien lukumäärää, tai esim disabloida tai 
	// aktivoida nappulaa, joka tekee jotain valituille riveillä.
	public function setSelectBoxOncheckedFunction($oncheckedfunction) {
		// TODO: Not implemented..
	}
	

	public function generateContent() {
	
		if ($this->errormessage != null) {
			echo "<table cellpadding='0' cellpadding='0'  style='width:100%;'>";
			echo "	<tr>";
			echo "		<td style='width:100%;'>";
			//echo "			<div class=errordiv id='sectionerrordiv-" . $this->getID() . "' style='display:none'></div>";
			echo "			<div class=errormessagediv id='sectionerrordiv-" . $this->getID() . "'>" . $this->errormessage . "</div>";
			echo "		</td>";
			echo "	</tr>";
			echo "</table>";
		}
		
		
		//echo "	<div>";
	
		/*
		echo "	<table cellpadding='0' cellpadding='0'  style='width:100%;'>";
		echo "		<tr>";
		echo "			<td>";
		
		// successmessage
	
		echo "<table cellpadding='0' cellpadding='0' style='width:100%;'>";
		echo "	<tr>";
		echo "		<td style='width:62%;'>";
		echo "			<div class=successdiv id='sectionsuccessdiv-" . $this->getID() . "' style='display:none'></div>";
		echo "		</td>";
		echo "		<td style='width:36%;'></td>";
		echo "	</tr>";
		echo "</table>";
	
		// errormessage
		echo "<table cellpadding='0' cellpadding='0'  style='width:100%;'>";
		echo "	<tr>";
		echo "		<td style='width:62%;'>";
		echo "			<div class=errordiv id='sectionerrordiv-" . $this->getID() . "' style='display:none'></div>";
		echo "		</td>";
		echo "		<td style='width:36%;'></td>";
		echo "	</tr>";
		echo "</table>";
	
		// fields
		/*
		foreach($this->fields as $index => $field) {
			$field->show($this->data);
		}
		* /
		echo "			</td>";
		echo "		</tr>";
		echo "</table>";
		*/
		
		
		echo "<script>";
		echo "		function addSuccessMessage_" . $this->getID() . "(message) {";
		echo "			var textnode = document.createTextNode(message);";
		echo "			textnode.id='sectionerrortext-" . $this->getID() . "';";
		echo "			$('#sectionsuccessdiv-" . $this->getID() . "').html('');";
		echo "			$('#sectionsuccessdiv-" . $this->getID() . "').append(textnode);";
		echo "			$('#sectionsuccessdiv-" . $this->getID() . "').show();";
		echo "			setTimeout(function() { $('#sectionsuccessdiv-" . $this->getID() . "').slideUp(500); },1000);";
		echo "		}";
		echo "</script>";
			
		
		
		/*
		if (count($this->data) == 0) {
			//echo "empty"; // Tähän ehkä hieman spacingia
			if (($this->buttonVerticalAlign == UIComponent::VALIGN_BOTTOM) || ($this->buttonVerticalAlign == 0)) $this->generateButtons();
			echo "<div style='width:100%;height:10px;'>Data null</div>";
			return;
		}
		*/
		
		if ($this->framesVisible == false) {
			if ($this->showTitle == true) {
			
				if (($this->buttonVerticalAlign == UIComponent::VALIGN_TOP) || ($this->buttonVerticalAlign == 0)) {
					echo "	<table style='width:" . $this->width . "'>";
					echo "		<tr>";
					echo "			<td style='text-align:left;vertical-align:bottom;padding-top:0px;'>";
					//echo "<font style='font-weight:bold;font-size:24px;'>aaaa" . $this->title . "</font>";
					echo "<h1 style='margin-bottom:0px;'>" . $this->title . "</h1>";
					echo "			</td>";
					echo "			<td style='text-align:right;vertical-align:bottom;'>";
					if (count($this->buttons) > 0) {
						foreach($this->buttons as  $index => $button) {
							$button->show();
						}
					}
					if ($this->settingsaction != null) {
						$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $this->settingsaction->getID(), "");
						$button->setIcon('fa-cog fa-lg');
						$button->show();
					}
						
					echo "			</td>";
					echo "		</tr>";
					echo "	</table>";
				} else {
					echo "	<table style='width:100%'>";
					echo "		<tr>";
					echo "			<td style='text-align:right;vertical-align:bottom;'>";
					echo "<font style='font-weight:bold;font-size:24px;'>" . $this->title . "</font>";
					echo "			</td>";
					echo "		</tr>";
					echo "	</table>";
				}
			} else {
				if (($this->buttonVerticalAlign == UIComponent::VALIGN_BOTTOM) || ($this->buttonVerticalAlign == 0)) {
					echo "	<table style='width:100%'>";
					echo "		<tr>";
					echo "			<td style='text-align:right;vertical-align:bottom;'>";
					if (count($this->buttons) > 0) {
						foreach($this->buttons as  $index => $button) {
							$button->show();
							echo " ";
						}
					}
					if ($this->settingsaction != null) {
						$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $this->settingsaction->getID(), "");
						$button->setIcon('fa-cog fa-lg');
						$button->show();
					}
						
					echo "			</td>";
					echo "		</tr>";
					echo "	</table>";
				}
			}
		} else {
			if (($this->buttonVerticalAlign == UIComponent::VALIGN_TOP) || ($this->buttonVerticalAlign == 0)) {
				echo "	<table style='width:100%'>";
				echo "		<tr>";
				echo "			<td style='text-align:right;vertical-align:bottom;'>";
				if (count($this->buttons) > 0) {
					foreach($this->buttons as  $index => $button) {
						$button->show();
						echo " ";
					}
				}
				if ($this->settingsaction != null) {
					$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $this->settingsaction->getID(), "");
					$button->setIcon('fa-cog fa-lg');
					$button->show();
				}
				
				echo "			</td>";
				echo "		</tr>";
				echo "	</table>";
			}
		}
		
		if ($this->topselection != null) {
			echo "	<table style='width:100%'>";
			echo "		<tr>";
			echo "			<td style='text-align:left;vertical-align:bottom;'>";
			
			echo "	<select id='topselectfield". $this->getID() ."' class='top-select' style='width:150px;margin-right:5px;margin-bottom:15px;'>";
			//$this->topselectedID = $selectedID;
			//$this->topkeyvariable = $keyvariable;
			//$this->topnamevariable = $namevariable;
			//$this->topselection = $topselection;
				
			if ($this->topselectedID == null) {
				if ($selectedID == null) {
					echo "<option value='' selected></option>";
				} else {
					echo "<option value=''></option>";
				}
			}
			$keyvar = $this->topkeyvariable;
			$namevar = $this->topnamevariable;
			foreach ($this->topselection as $rowID => $row) {
				$key = $row->$keyvar;
				$name = $row->$namevar;
				if ($key == $this->topselectedID) {
					echo "<option selected value='" . $key . "'>" . $name . "</option>";
				} else {
					echo "<option value='" . $key . "'>" . $name . "</option>";
				}
			}
			echo "</select>";
			
			
			echo "<script>";
			echo "	$('#topselectfield". $this->getID() ."').change(function(){";
			echo "		selected = $('#topselectfield". $this->getID() ."').val();";
			echo "		console.log('selected - '+selected);";
			echo "		console.log('selected - " . $this->toptargeturl . "');";
			echo "		window.location = '" . getUrl($this->toptargeturl) . "&periodID='+selected;";
			echo "	});";
			echo "</script>";
			
			
			echo "			</td>";
			echo "		</tr>";
			echo "	</table>";
		} else {
			/*
			echo "	<table style='width:100%'>";
			echo "		<tr>";
			echo "			<td style='text-align:right;vertical-align:bottom;'>";
			echo "Top selector here2";
			echo "			</td>";
			echo "		</tr>";
			echo "	</table>";
			*/
		}
				
		
		echo "<input id='sectionidvalue-".$this->getID()."' type='hidden' value=''>";
		
		$widthsrt = $this->width;
		$widthstr = str_replace('px','',$widthsrt);
		//echo "<br>This width - " . $this->width;
		if (intval($widthsrt) > 0) {
			$width = intval($widthstr) - 24;
			echo "<div style='width:" . $width . ";overflow-x: auto; background-color:white;'>";
			echo "<table class='listtable' id='sectiontable" . $this->getID() . "' style='width:" . $width . "px;padding:0px 0px 0px 0px;margin:0px;'>";
		} else {
			echo "<div style='width:" . $this->width . ";background-color:pink;'>";
			echo "<table class='listtable' id='sectiontable" . $this->getID() . "' style='width:" . $this->width . ";'>";
		}
		

		//echo "<br>button align - " . $this->buttonVerticalAlign;
		/*
		if ($this->buttonVerticalAlign == UIComponent::VALIGN_TOP) {
			//$this->generateButtons();
						
			echo " 	<tr class='listtable-row'>";
			$columncount = count($this->items)+1;
			echo "			<td colspan=" . $columncount . " style='text-align:right;vertical-align:bottom;'>";
			if (count($this->buttons) > 0) {
				foreach($this->buttons as  $index => $button) {
					$button->show();
					echo " ";
				}
			}
			if ($this->settingsaction != null) {
				$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $this->settingsaction->getID(), "");
				$button->setIcon('fa-cog fa-lg');
				$button->show();
			}
			
			echo "			</td>";
			echo "		</tr>";
		}
		*/
		
		
		// Otsikkorivin luonti
		if ($this->tableheadervisible == true) {
		
			echo " 	<tr class='listtable-row'>";
			
			if ($this->showselectboxes == true) {
				echo "<td  class='listtable-header'><input type='checkbox' id='checkall-" . $this->getID() . "' onChange='tableallcheckboxclicked_" . $this->getID() . "()'></td>";
				
				echo "<script>";
				echo "	function tableallcheckboxclicked_" . $this->getID() . "() {";
				echo "		console.log('all clicked');";
				echo "		if ($('#checkall-" . $this->getID() . "').is(':checked')) {";
				echo "			console.log(' - check all');";
				echo "			for (let i = 1; i < " . (count($this->data)+1) . "; i++) {";
				echo "				$('#checkbox_" . $this->getID() . "_'+i).prop('checked', true);";
				echo "			}";
				echo "		} else {";
				echo "			console.log(' - check all');";
				echo "			for (let i = 1; i < " . (count($this->data)+1) . "; i++) {";
				echo "				$('#checkbox_" . $this->getID() . "_'+i).prop('checked', false);";
				echo "			}";
				echo "		}";
				echo "	}";
				echo "</script>";
				
			}
				
			if ($this->showlinenumbers == true) {
				echo "<td  class='listtable-header'>#</td>";
			}
						
			foreach($this->items as $index => $column) {
			
				//echo "<br>Columntype - " . $column->type;
				$class = get_class($column);
				//echo "<br>Class - " . $class;
			
				$align = "";
				if ($column->align == Column::ALIGN_RIGHT) {
					$align = "text-align:right;";
				}
				
				switch($class) {
						
					case 'UIFloatColumn' :
							
						echo "<td style='" . $align . "' class='listtable-header' style='background-color:pink;width:" . $column->width . "'>" . $column->name . "</td>";
						break;
					
					case 'UISpaceColumn' :
								
							echo "<td class='listtable-header' style='min-width:" . $column->width . ";max-width:" . $column->width . ";width:" . $column->width . "'></td>";
							break;
								
					case 'UISortColumn' :
			
						if ($column->width != null) {
							echo "<td class='listtable-header' style='" . $align . "'>" . $column->name . "</td>";
						} else {
							echo "<td class='listtable-header'  style='" . $align . ";'>" . $column->name . "</td>";
						}
			
						
						/*
						 echo "<td class='listtable-header'>";
			
						 //Sort nuolen rakennus sortingcolumniin.
						 if (($column->sorticonup!=null) && ($column->sorticondown!=null)) {
			
						 if ($this->sortingcolumn==$column->name) {
						 if ($this->sortingdirection=='ascending') {
						 echo "<img height=".$column->iconsize." width=".$column->iconsize." src='".$column->sorticonup."'>";
						 $this->sortingdirection = 'descending';
						 } else {
						 echo "<img height=".$column->iconsize." width=".$column->iconsize." src='".$column->sorticondown."'>";
						 $this->sortingdirection = 'ascending';
						 }
						 }
						 }
			
						 echo "<a href='' onClick='linnkkipress()'>a " . $column->name ."</a>";
						 //echo "<a href='". getUrl($column->sortlink) . "&sortdirection=".$this->sortingdirection."'>" . $column->name ."</a>";
						 echo "</td>";
						 */
						break;
			

					case 'UILinkColumn' :
							
						if ($column->width != null) {
							echo "<td class='listtable-header' style='" . $align . ";width:" . $column->width . "'>" . $column->name . "</td>";
						} else {
							echo "<td class='listtable-header'  style='" . $align . ";width:" . $column->width . "'>" . $column->name . "</td>";
						}
						break;
							
					case 'UIMultilangColumn' :
							
						echo "<td style='" . $align . "' class='listtable-header'>" . $column->name . "</td>";
						break;
							
					case 'UIHiddenColumn':
							
						echo "<td style='" . $align . "' class='listtable-header'></td>";
						break;
							
					case 'UIButtonColumn':
						
						if ($column->columnwidth != "") {
							//echo "<td style='" . $align . "' class='listtable-header' style='width:" . $column->columnwidth . ";'>" . $column->name . "</td>";
							echo "<td style='" . $align . "' class='listtable-header' style='width:43px;'>" . $column->name . "</td>";
						} else {
							echo "<td style='" . $align . "' class='listtable-header' style='width:43px;'>" . $column->name . "</td>";
						}
						break;
							
					case 'UIArrayColumn' :
			
							
			
						echo "<th style='" . $align . "' class='listtable-header' style='width:20px;max-width:" . $this->maxcolumnwidth . "'>" . $column->name . "</th>";
						break;
			
						/*
						 * ArrayColumnia an ennenvanhaan käytetty johonkin muuhun, tämä on vanha toteutus
						 if ($column->link == null) {
						 echo "		<td  class='listtable-header'>" . $column->name . "</td>";
						 } else {
						 echo "		<td  class='listtable-header'><a href='". getUrl($column->link) . "'>" . $column->name . "</a></td>";
						 }
						 */
						break;
			
					case 'UISelectColumn' :
			
						//echo "<br>rowvariable- " . $column->name;
						if ($column->width != null) {
							$style = "style='width:" . $column->width . "'";
						} else {
							$style = "style=''";
						}
			
						$dropDownMenuID = "table-" . $this->getID() . "-" . $index;
						echo "	<td style='max-width:" . $column->width . ";width:" . $column->width . "' class='listtable-header' onmouseout=\"$('#" . $dropDownMenuID . "').hide()\" onMouseOver=\"$('#" . $dropDownMenuID . "').show()\" " . $style . ">";
						echo "		" . getMultilangString($column->name);
						
						// Alla select taulun haederin pudotusvalikko
						/*
						echo "		<div class='listtable-dropdownmenu' id=" . $dropDownMenuID . ">";
						echo "			<a href='".getUrl($column->link, array($column->datavariable =>  0))."'> kaikki3 </a><br>";
			
						
						foreach($column->selection as $contentID => $content) {
							$showvariable = $column->showvariable;
							//echo "<br>rowvariable content - " . $datavariable;
							echo "		<a href='".getUrl($column->link, array($column->datavariable =>  $contentID))."'>" . $content->$showvariable . "</a><br>";
						}
						echo "		</div>";
						*/
						echo "	</td>";
						break;
					
					case 'UIColorColumn' :
								
						echo "	<td style='" . $align . "' class='listtable-header'>";
						echo "" . getMultilangString($column->name);
						echo "	</td>";
						break;

					case 'UIBallColumn' :
					
						$column->generateHeaderCell();
						break;
								
					case 'UIFixedColumn':		// en nyt muista mitä tällä oli tarkoitus tehdä
			
						$this->createUIFixedColumnheader($column);
						break;
			
					case 'UIStatusColumn' :
								
						if ($column->width != null) {
							echo "<td style='" . $align . "' class='listtable-header' style='width:" . $column->width . "'>" . $column->name . "</td>";
						} else {
							echo "<td style='" . $align . "' class='listtable-header'>" . $column->name . "</td>";
						}
						break;
						
					default :
			
						echo "<td style='" . $align . "' class='listtable-header'>" . $column->name . "</td>";
						break;
			
				}
			}
			if ($this->deleteaction != null) {
				echo "<td  class='listtable-header'></td>";
			}
			echo " 	</tr>";
		}
		
		echo "<tbody id='sectiontbody" . $this->getID() . "'>";
		
		if ($this->loadonopen) {
			
			echo " 	<tr>";
			echo "		<td id=loadingcloumn" . $this->getID() . " colspan=" . (count($this->items)+1) . " style='padding-left:10px;'>";
			echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
			echo "		</td>";
			echo "	</tr>";
			
			
			
		} else {
			
			$sums = array();
			foreach($this->items as $index => $column) {
				$sums[$index] = 0;
			}
			
			$rowNumber = 0;
			if ($this->data == 0) {
				echo " 	<tr>";
				if ($this->tableheadervisible == true) {
					echo "       <td colspan=" . (count($this->items)+1) . " style='padding-left:10px;'>Empty table</td>";
				}
				echo "</tr>";
			} elseif (count($this->data) == 0) {
				echo " 	<tr>";
				if ($this->tableheadervisible == true) {
					echo "       <td colspan=" . (count($this->items)+1) . " style='padding-left:10px;'>Empty table</td>";
				}
				echo "</tr>";
			} else {
				
				echo "<script>";
				echo "	var selectedrowbackground" . $this->getID() . " = '#000000';";
				echo "	var selectedrownumber" . $this->getID() . "  = -1;";
				echo "	var selectedrowid" . $this->getID() . "  = -1;";
				
				echo "</script>";
				
				foreach($this->data as $index => $row) {
						
					$rowNumber++;
					$pointerclass = "";
					if ($this->lineaction != null) $pointerclass = "cursor:pointer;";
					$trclass = "listtable-evenrow";
					$trbackgroundcolor = "white";
					if ($rowNumber % 2 == 0) {
						$trclass = "listtable-oddrow";
						$trbackgroundcolor = "#e2eff8";
					}
	
					if ($this->linebackground != null) {
						$variable = $this->linebackground;
						$pointerclass = $pointerclass . "background-color:" . $row->$variable . ";";
					}
					
					if ($this->sortactiontype > 0) {
						$variable = $this->sortvariable;
						if (is_numeric($variable)) {
							//echo "<br>variable - " . $variable;
							$data = $row[$variable];
							//echo "<br>data - " . $data;
						} else {
							//echo "<br>variable - " . $variable;
							$data = $row->$variable;
							//echo "<br>data - " . $data;
						}
						echo " 	<tr onclick='rowclick_" . $this->getID() . "_".$rowNumber."(this)' id='tablerow-" . $this->getID() . "-".$rowNumber."' class='" . $trclass . "' data-sortdata='" . $data . "' style='" . $pointerclass . "'>";
					} else {
						echo " 	<tr onclick='rowclick_" . $this->getID() . "_".$rowNumber."(this)'  id='tablerow-" . $this->getID() . "-".$rowNumber."' class='" . $trclass . "'  style='" . $pointerclass . "'>";
					}
					//echo " 	<tr id='tablerow-" . $this->getID() . "-".$rowNumber."' class='" . $trclass . "' style='" . $pointerclass . "'>";
						
					echo "<script>";
					
					if ($this->mode == UIComponent::MODE_LINESELECT) {
						
						//echo "	var selectedrowbackground" . $this->getID() . "  = '#000000';";
						//echo "	var selectedrownumber" . $this->getID() . "  = -1;";
						
						echo "	function rowclick_" . $this->getID() . "_".$rowNumber."(event) {";
						
						echo "		console.log('rowclick');";
						echo "		console.dir(event);";
						//echo "		event.stopPropagation();";
						//echo "		return;";
						echo "		console.log('rowclikki aa');";
						
						echo "		var oldrowname = '#tablerow-" . $this->getID() . "-'+selectedrownumber" . $this->getID() . ";";
						echo "		if (selectedrownumber" . $this->getID() . " > 0) {";
						echo "			$(oldrowname).css('background-color',selectedrowbackground" . $this->getID() . ");";
						//echo "			$(oldrowname).css('background-color','pink');";
						echo "		}";
						
						echo "		var rowname = '#tablerow-" . $this->getID() . "-".$rowNumber."';";
						echo "		selectedrowbackground" . $this->getID() . " = '" . $trbackgroundcolor . "';";
						//echo "		selectedrowbackground" . $this->getID() . " = 'pink';";
						echo "		selectedrownumber" . $this->getID() . "  = " . $rowNumber . ";";
						echo "		$(rowname).css('background-color','#90EE90');";
						
						$param = $this->lineactionparam;
						$idrowwi = $row->$param;
						echo "		console.log('rowclick - " . $idrowwi . " - " . $param . "');";
						echo "		selectedrowid" . $this->getID() . " = '" . $idrowwi . "';";
						
						
						switch($this->lineactiontype) {
							case UIComponent::ACTION_FORWARD :
								echo "		console.log('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');";
								echo "		loadpage('" . $this->generateLineActionUrl($row,1) . "');";
								echo "		console.log('ACTION_FORWARD');";
								break;
							case UIComponent::ACTION_NEWWINDOW :
								echo "		console.log('ACTION_NEWWINDOW');";
								break;
							case UIComponent::ACTION_JAVASCRIPT:
								echo "		console.log('lineaction ACTION_JAVASCRIPT');";
								echo "		console.log('lineactionparam - " . $this->lineaction . "');";
								echo "		alert('jee2e');";
								echo "		event.stopPropagation();";
								echo "		event.stopImmediatePropagation();";
								echo "		event.preventDefault();";
									
								echo "		return;";
								echo "" . $this->lineaction . "();";
								break;
							case UIComponent::ACTION_FORWARD_INDEX:
								echo "		console.log('ACTION_FORWARD_INDEX');";
								break;
							case UIComponent::ACTION_CHECK:
								echo "		alert('lineaction LINEACTION CHECK not implemented');";
								break;
							case UIComponent::ACTION_OPENDIALOG:
								echo "		var value = 0;";
								echo "		console.log('opendialog - " . $this->lineaction . "');";
								break;
							default :
								break;
						}
						echo "		return;";
						echo "	}";
							
					} else {
						echo "	function rowclick_" . $this->getID() . "_".$rowNumber."(event) {";
						
						
						switch($this->lineactiontype) {
							
							case UIComponent::ACTION_FORWARD :
								//echo "console.log('forward - " . $this->generateLineActionUrl($row,2) . "');";
								//echo "console.log('testingaa - " . $this->generateLineActionUrl($row,3) . "');";
								echo "		console.log('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');";
								echo "		console.log('opentab');";
								//echo "		loadpage('" . $this->generateLineActionUrl($row,4) . "','" . $this->actiontitle . "');";
								break;
							case UIComponent::ACTION_NEWWINDOW :
								echo "		console.log('opentab');";
								break;
							case UIComponent::ACTION_JAVASCRIPT:
								echo "		alert('lineaction LINEACTION JAVASCRIPT not implemented');";
								echo "		return;";
								break;
							case UIComponent::ACTION_FORWARD_INDEX:
								echo "		loadpage('" . $this->generateLineActionUrl($row,5) . "','" . $this->actiontitle . "');";
								break;
							case UIComponent::ACTION_CHECK:
								echo "		alert('lineaction LINEACTION CHECK not implemented');";
								break;
							case UIComponent::ACTION_OPENDIALOG:
								echo "		var value = 0;";
									
								//echo "		console.log('itemid  - " . $itemID . "');";
								//echo "		console.log('targetsection - " . $this->lineaction . "');";
									
								foreach($this->items as $index => $item) {
									//echo "		console.log('itemid xx  - " . $item->getID() . "');";
						
									//id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . "
									echo "  	valuexx = $('#tablerow-" . $this->getID() . "-" . $rowNumber . "-" . $item->getID() . "').val();";
									//echo "		console.log('.. value - '+valuexx);";
						
									if ($item instanceof UIButtonColumn) {
										// buttoneita ei oteta mukaan
									} else {
										echo "		setValue_" . $this->lineaction . "('" . $item->getVariable() . "',valuexx);";
									}
								}
								$targetsectionID = $this->lineaction;
									
								echo "  	$('#sectiondialog-" . $this->lineaction . "').dialog('open');";
								break;
							default :
								break;
						}
						
						echo "	}";
							
					}
					
					
					//echo "		console.log('rowclickaa  - ".$rowNumber."')";
					echo "</script>";
			
					/*
					if (is_numeric($this->lineaction)) {
						$this->generateLineactionContent("tablerow-" . $this->getID() . "-" . $rowNumber,$row,$this->lineaction);
						
						//$this->generateLineaction("tablerow-" . $this->getID() . "-" . $rowNumber,$row,$this->lineaction);
					}
					*/
						
			
					// Luodaan checkable <td>
					if ($this->checkable) $this->generateCheckableTD($rowNumber);
						
					if ($this->showselectboxes == true) {
						echo "<td style='padding-left:10px;'>";
						
						$checked = "";
						$disabled = "";
						
						$idvariable = $this->selectcolumnidvariable;
						$rowID = $row->$idvariable;
						
						echo "<input type='hidden' id='checkboxidvalue_" . $this->getID() . "_".$rowNumber."' value='" . $rowID . "'>";
						
						if ($this->selectablecolumnvariable == null) {
							//echo "<br>selectablecolumnvariable variable is null;";
							if ($this->selectedcolumnvariable != null) {
								$variable = $this->selectedcolumnvariable;
								$value = $row->$variable;
								if ($value == 1) {
									// selected, active
									echo "<input type='checkbox' style='vertical-align:middle;' id='checkbox_" . $this->getID() . "_".$rowNumber."' checked >";
								} else {
									// not selected, active
									echo "<input type='checkbox' style='vertical-align:middle;' id='checkbox_" . $this->getID() . "_".$rowNumber."'>";
								}
							} else {
								// not selected, active
								echo "<input type='checkbox' style='vertical-align:middle;'  id='checkbox_" . $this->getID() . "_".$rowNumber."'>";
							}
						} else {
							$selectablevariable = $this->selectablecolumnvariable;
							$selectable = $row->$selectablevariable;
							
							if ($selectable == 0) {
								if ($this->selectedcolumnvariable != null) {
									$variable = $this->selectedcolumnvariable;
									$value = $row->$variable;
									if ($value == 1) {
										// selected
										echo "<input type='checkbox' style='vertical-align:middle;'  id='checkbox_" . $this->getID() . "_".$rowNumber."' checked disabled>";
									} else {
										// not selected
										echo "<input type='checkbox' style='vertical-align:middle;'  id='checkbox_" . $this->getID() . "_".$rowNumber."' disabled>";
									}
								} else {
									// not selected, active
									echo "<input type='checkbox' style='vertical-align:middle;'  id='checkbox_" . $this->getID() . "_".$rowNumber."' disabled>";
								}
							} else {
								if ($this->selectedcolumnvariable != null) {
									$variable = $this->selectedcolumnvariable;
									$value = $row->$variable;
									if ($value == 1) {
										// selected
										echo "<input type='checkbox' style='vertical-align:middle;'  id='checkbox_" . $this->getID() . "_".$rowNumber."' checked>";
									} else {
										// not selected
										echo "<input type='checkbox' style='vertical-align:middle;'  id='checkbox_" . $this->getID() . "_".$rowNumber."'>";
									}
								} else {
									// not selected, active
									echo "<input type='checkbox' style='vertical-align:middle;'  id='checkbox_" . $this->getID() . "_".$rowNumber."'>";
								}
							}
					
						}
						echo "</td>";
					}
						
					
					if ($this->showlinenumbers == true) {
						echo "<td>" . $rowNumber  . "</td>";
					}
			
					echo "<script>";
					echo "	function onclickfalse(e) {";
// 					cho "		event.preventDefault();";
					echo "		console.log('----------------------------');";
					echo "		console.log('onclickfalse');";
					echo "		console.log('------------------------------');";
					echo "		window.event.stopPropagation();";
					echo "		return false;";
					echo "	};";
					echo "</script>";
					
					$columnnumber = 0;
					
					$keycolumn = null;
					foreach($this->items as $index => $column) {
						if ($column->datavariable == $this->lineactionparam) $keycolumn = $column;
					}
					
					foreach($this->items as $index => $column) {
			
						$columnnumber++;
						$variable = $column->datavariable;

						$widthstr = "";
						if (isset($this->widths[$columnnumber])) {
							$widthstr  = "width:" . $this->widths[$columnnumber] . ";";
						}
							
						$class = get_class($column);
			
						// tämä voitaisiin tarkistaa mieluummin lineactiontypellä
						$align = "";
						
						
						if ($class == 'UISortColumn') {
							if ($column->dataformatter == Column::COLUMNTYPE_FLOAT) {
								$column->align = Column::ALIGN_RIGHT;
							}
						} else {
						
						}
						if ($column->align == Column::ALIGN_RIGHT) {
							$align = "text-align:right;";
						} else if ($column->align == Column::ALIGN_CENTER) {
							$align = "text-align:center;";
						}
						
						if (isset($column->width)) {
							$width = $column->width;
						} else {
							$width = "";
						}
						
						if (isset($this->widths[$columnnumber])) {
							$width  = $this->widths[$columnnumber];
						}
						
						if ($class ==  'UIButtonColumn') {
							echo "<td style='padding-left:10px;" . $align . ";padding-bottom:0px;max-width:" . $width . ";width:" . $width . ";text-align:right;'>";
							//echo "<td style='padding-left:10px;" . $align . "padding-top:0px;max-width:43px;width:43px;'>";
						} else {
							if (is_numeric($this->lineaction)) {
								echo "<td style='padding-left:10px;" . $align . ";padding-bottom:0px;padding-top:3px;padding-bottom:0px;max-width:" . $width . ";width:" . $width . ";overflow-x:hidden;'>";
							} elseif ($this->lineaction == null) {
								echo "<td style='padding-left:10px;" . $align . ";padding-bottom:0px;padding-top:3px;padding-bottom:0px;vertical-align:top;max-width:" . $width . ";width:" . $width . ";overflow-x:hidden;overflow-y:hidden;'>";
							} else {
								//if ($width == "") {
									echo "<td style='padding-left:10px;" . $align . ";padding-bottom:0px;padding-top:3px;padding-bottom:0px;width:" . $width . "max-width:" . $width . ";overflow-x:hidden;overflow-y:hidden;'>";
									echo "<a id=clickhref-" . $this->getID() . "-" . $column->getID() . "-" . $rowNumber . " href='" .getUrl($this->generateLineActionUrl($row,6)) . "'  style='text-decoration:none;'>"; //onclickfalse(event)>";
									echo "<div id=clickdiv-" . $this->getID() . "-" . $column->getID() . "-" . $rowNumber . ">";
																		
									/*
									echo "<script>";
									echo "	$('#clickhref-" . $this->getID() . "-" . $column->getID() . "-" . $rowNumber . "').click(function() {";
									echo "		e.preventDefault();";
									echo "		return false;";
									echo "	});";
									echo "</script>";
									*/
									
									
									
									
									echo "<script>";
									echo "	$('#clickdiv-" . $this->getID() . "-" . $column->getID() . "-" . $rowNumber . "').click(function() {";
									echo "		console.log('divclikki - " . $this->getID() . "-" . $column->getID() . "-" . $rowNumber . "');";
									//echo "		console.log('" . $this->generateLineActionUrl($row,7) . "');";
									echo "		console.log(' - lineaction - " . $this->lineaction . "');";
									
									
									if ($this->mode == UIComponent::MODE_LINESELECT) {
										$param = $this->lineactionparam;
										$idrowwi = $row->$param;
										echo "		console.log('rowclick3 - " . $idrowwi . "');";
										echo "		selectedrowid" . $this->getID() . " = '" . $idrowwi . "';";
										
									}
									
									
									if ($this->lineactiontype == UIComponent::ACTION_JAVASCRIPT) {
										echo "		console.log('javascriptaction');";
										echo "		console.log('rownro - " . $rowNumber . "');";
										echo "		console.log('lineactionparam - " . $this->lineactionparam . "');";
										
										/*
										$callvariable = "";
										foreach($this->items as $index2 => $item) {
											if ($item instanceof UIButtonColumn) {
											} else {
												if ($this->lineactionparam == $item->datavariable) {
													echo "  	var value" . $item->getID() . " = $('#tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $item->getID() . "').val();";
													$callvariable = "value" . $item->getID();
												}
											}
										}
										*/
										//$callvariable = "4";
										echo "		" . $this->lineaction . "(" . $rowNumber . ");";
									}
									
									if ($this->lineactiontype == UIComponent::ACTION_OPENDIALOG) {
										echo "		console.log('opendialogaction not implemented');";
										/*
										echo "		event.stopPropagation();";
										echo "		event.stopImmediatePropagation();";
										echo "		event.preventDefault();";
											
										foreach($this->items as $index => $item) {
											echo "  	valuexx = $('#tablerow-" . $this->getID() . "-'+rownro+'-" .  $item->getID() . "').val();";
											if ($item instanceof UIButtonColumn) {
									
											} else {
												echo "		setValue_" . $datavariable . "('" . $item->getVariable() . "',valuexx);";
											}
										}
										echo "  	$('#sectiondialog-" . $datacolumn->getVariable() . "').dialog('open');";
										*/
									}
									
									if ($this->lineactiontype == UIComponent::ACTION_FORWARD) {
										echo "		console.log('forwardaction');";
										/*
										echo "		event.stopPropagation();";
										echo "		event.stopImmediatePropagation();";
										echo "		event.preventDefault();";
													*/
									
										
										//echo "		console.log('datavariable - " . $keycolumn->datavariable . "');";
										//echo "		console.log('lineactionparam - " . $this->lineactionparam. "');";
										//echo "		console.log('getID - " . $this->getID() . "');";
										//echo "		console.log('keycolumn getID - " . $keycolumn->getID() . "');";
										//echo "		var fid = '#tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $keycolumn->getID() . "';";
										//echo "		console.log('fid - '+fid);";
										//echo "		var tid = $(fid).val();";
										//echo "		console.log('tid - '+tid);";
										
										if ($keycolumn == null) {
											//echo "	alert('keycolumn fail');";
										} else {
											echo "		var id = $('#tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $keycolumn->getID() . "').val();";
										}
										
										echo "		console.log('id - '+id);";
										
										echo "		var loc = '" . getUrl($this->lineaction) . "&id='+id;";
										echo "		window.location = loc;";			// tämä pitäisi toteuttaa jsonina
										//echo "		window.event.stopPropagation();";
									}
									
									
									
									//echo "		loadpage('" . $this->generateLineActionUrl($row,8) . "','" . $this->actiontitle . "');";
									//echo "		window.event.stopPropagation();";
									echo "		return false;";
									echo "	});";
									echo "</script>";
									
								//} else {
								//	echo "<td style='padding-left:10px;" . $align . ";padding-bottom:0px;padding-top:3px;padding-bottom:0px;width:" . $width . "max-width:" . $width . ";overflow-x:hidden;overflow-y:hidden;'>";
								//	echo "<a href='" .getUrl($this->generateLineActionUrl($row,9)) . "' style='text-decoration:none'>ffff";
									//echo "<a href='#' style='text-decoration:none'>";
								//	echo "<div style='width:" . $width . ";overflow-x:hidden;'>";
								//}
							}
						}
							
						switch($class) {

							case 'UISpaceColumn':
							
								echo "";
								break;
									
								
							//case 'UISortColumn':
							case 'UISortColumn':
			
								if ($column->linkurl == null) {
									echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
									if ($column->dataformatter == null) {
										echo "<span style='white-space: nowrap;'>" . $column->formatValue($row->$variable, "") . "<span>";
									} else {
										if ($column->needMonospace()  == true) {
											echo "<div style='padding-bottom:1px;font-size:17px;font-family:Noto Sans KR;line-height: 20px;position: relative; bottom: 1px'>";
											echo "" . $column->formatValue($row->$variable, "");
											echo "</div>";
										} else {
											echo "<span style='font-size:17px;'>";
											echo "" . $column->formatValue($row->$variable, "");
											echo "</span>";
										}
									}
								} else {
									
									$idcolumnname = $column->linkvariable;
									echo "<input  id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$idcolumnname . "'>";
									if ($column->dataformatter == null) {
										echo "<a href='' onclick=\"return linnkkipress('" . $column->linkurl . "&id=" . $row->$idcolumnname . "')\">" . $column->formatValue($row->$variable, "") . "</a>";
									} else {
										echo "<a href='' onclick=\"return linnkkipress('" . $column->linkurl . "&id=" . $row->$idcolumnname . "')\">" . $column->formatValue($row->$variable, "") . "</a>";
									}
									//echo "<a href='" . getUrl($column->linkurl, array( "id" => $row->$idcolumnname)) . "' onclick='linnkkipress()'>" . $row->$variable . "</a>";
								}
			
			
								if ($column->dataformatter == null) {
									
									
								} else {
									
									if ($column->dataformatter == Column::COLUMNTYPE_FLOAT) {
										$sums[$index] = $sums[$index] + $row->$variable;
										//echo "<br>summa - " . $sums[$index];
									}
								}
								
								
								echo "	<script>";
								echo "		function linnkkipress(link) {";
								//echo "			console.log('linkkipress  - '+link);";
								echo "			loadpage(link,'" . $this->actiontitle . "', 'jokutitle');";
								echo "			return false;";
								echo "		}";
								echo "	</script>";
								break;
			
							case 'UILinkColumn';
							
								$var = $column->datavariable;
								$target = $row->$var;
								
								if ($column->showvariable == null) {
									$linkname = $column->linktext;
								} else {
									$showvar = $column->showvariable;
									$linkname = $row->$showvar;
								}
								
								if (is_array($target)) {
									$first = true;
									foreach($target as $ext => $link) {
										if ($first == true) {
											$first = false;
										} else {
											echo ", ";
										}
										if (strpos($ext, "_") > 0) {
											$pos = strpos($ext, "_");
											echo "<a target='_blank' href='" . getUrl($column->action) . "&id=" . $link . "')\">" . substr($ext,0,$pos) . "</a>";
										} else {
											echo "<a target='_blank' href='" . getUrl($column->action) . "&id=" . $link . "')\">" . $ext . "</a>";
										}
									}									
								} else {
									if ($target != "") {
										echo "<a target='_blank' href='" . getUrl($column->action) . "&id=" . $row->$var . "')\">" . $linkname . "</a>";
									} else {
										echo "-";
									}
								}
								
								break;
								
							case 'UIFloatColumn':
										
								echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
								echo "" . $row->$variable;
								
								$sums[$index] = $sums[$index] + $row->$variable;
								
								break;
									
									
							case 'UIMultilangColumn' :
									
								echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
								echo "" . parseMultilangStringWithEmpty($row->$variable, $column->languageID);
								break;
									
							case 'UIHiddenColumn':
			
								if (is_numeric($variable)) {
									echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row[$variable] . "'>";
								} else {
									echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
								}
								break;
			
							case 'UIButtonColumn':
								
								$colorstr = "";
								if ($column->color != null) {
									$colorstr = "background-color:" . $column->color . ";";
								}
								if ($column->colorvariable != null) {
									$var = $column->colorvariable;
									$color = $row->$var;
									//echo "-" . $row->color;
									if ($color != "") $colorstr = "background-color:" . $color . ";";
								}
																
								if ($column->icon == null) {
									echo "		<button class=section-button style='" . $colorstr . "margin-top:2px;padding-top:0px;width:100%;height:22px;font-size:12px;white-space:nowrap;' OnClick=\"buttonpressed_" . $this->getID() . "_" . $index . "(event," . $rowNumber . ")\">";
									echo "" . $column->name;
									echo "		</button>";
								} else {
									echo "		<button class=section-button style='" . $colorstr . "margin-right:2px;margin-top:2px;padding-top:1px;padding-left:5px;width:27px;height:22px;' OnClick=\"buttonpressed_" . $this->getID() . "_" . $index . "(event," . $rowNumber . ")\">";
									echo "			<i style='padding-left:0px;top:2px;' class='" . $column->icon . "'></i>";
									echo "		</button>";
								}
								break;
									
									
							case 'UIArrayColumn':
									
								echo "<span style=''>";
								if ($column->itemarray == null) {
									$var = $column->datavariable;
									$first = 0;
									if (count($row->$var) == 0) {
										echo "-";
									} else {
										foreach($row->$var as $index => $value) {
											if ($first == 1) echo ",";
											echo "" . $value;
											if ($first == 0) $first = 1;
										}
									}
								} else {
									if ($column->itemarrayvariable == null) {
										$var = $column->datavariable;
										$first = 0;
										if (count($row->$var) == 0) {
											echo "-";
										} else {
											foreach($row->$var as $index => $value) {
												if ($first == 1) echo ", ";
												echo "" . $value;
												if ($first == 0) $first = 1;
											}
										}
									} else {
										$itemvar = $column->itemarrayvariable;
										$var = $column->datavariable;
											
										$first = 0;
										foreach($row->$var as $index => $value) {
											if ($first == 1) echo ", ";
											echo "" . $column->itemarray[$value]->$itemvar;
											if ($first == 0) $first = 1;
										}
									}
								}
								echo "</span>";
									
								/*
								 if (isset($column->data[$row->$variable])) {
								 $value = $column->data[$row->$variable];
								 } else {
								 $value = "<font size=-1 style='font-style:italic;color:red'>Ei asetettu</font>";
								 }
								 echo "" . $value;
								 */
								break;
			
							case 'UISimpleColumn':
			
								$value = $row[$column->dataindex];
								
								if ($column->columntype == Column::COLUMNTYPE_INTEGER) {
									echo "" . $value;
									if (strpos($value,'>') == false) {
										echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $value . "'>";
									} else {
										echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='html-content no value'>";
									}
								} else {
									if (($value == null) || ($value == "")) {
									
										if ($column->columntype == Column::COLUMNTYPE_INTEGER) {
											echo "<font style='color:red'></font>";
										} else {
											echo "<font style='color:red'>puuttuu</font>";
										}
										echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $value . "'>";
									
									} else {
										echo "" . $value;
											
										// Ei aseteta hideen input valueen arvoa, jos sisältä on html:ää
										if (strpos($value,'>') == false) {
											echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $value . "'>";
										} else {
											echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='html-content no value'>";
										}
									}
								}
								
								break;
			
							case 'UISelectColumn':
			
								
								//echo "<br>rowvariable content - " . count($column->selection);
			
								$datavariable = $column->datavariable;
								$showvariable = $column->showvariable;

								//echo "<br>Datavariable - " . $datavariable;
								//echo "<br>Showvariable - " . $showvariable;
								
								if (is_integer($datavariable)) {
									//echo "<br>array - " . count($column->selection);
									//echo "<br>index - " . $column->datavariable;
									$value = $column->selection[$row[$column->datavariable]]->$showvariable;
									//$value = "int";									
									//echo "" . $value;
								} else {
									//echo "<br>index - " . $datavariable;
									echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='".$row->$datavariable . "'>";
									
									if (isset($column->selection[$row->$datavariable])) {
										//echo "<br>index aa - " . $column->selection[$row->$datavariable];
										if ($showvariable == NULL) {
											$value = $column->selection[$row->$datavariable];
											//$value = "null-1";
										} else {
											
											$value = $column->selection[$row->$datavariable]->$showvariable;
											//$value = $showvariable;
											//$value = "null-2";
										}
									} else {
										if (isset( $row->$datavariable)) {
											$value = "<font size=-1 style='font-style:italic;color:green'>" . $row->$datavariable . "</font>";
										} else {
											$value = "<font size=-1 style='font-style:italic;'>" . $column->undefinedstring . "</font>";
										}
										
										//$value = "null-3";
									}
																
								}
									
								//$row->$datavariable
								//$value = $column->datavariable;
			
								//echo "<span style='white-space: nowrap;'>" . $value . "</span>";
								echo "<span style='white-space: nowrap;'>" . getMultilangString($value,1) . "</span>";
								//echo "<span style='white-space: nowrap;'>" . getMultilangString($value) . "</span>";
								break;
			
							case 'UIColorColumn':
										
								$datavariable = $column->datavariable;
								$showvariable = $column->showvariable;
										
								echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='".$row->$datavariable . "'>";
								if (isset($column->selection[$row->$datavariable])) {
									if ($showvariable == NULL) {
										$value = $column->selection[$row->$datavariable];
									} else {
										$value = $column->selection[$row->$datavariable];
									}
								} else {
									$value = "<font size=-1 style='font-style:italic;color:white'>n/a</font>";
								}
								if ($value == null) {
									echo "<div style='white-space: nowrap;width:40px;height:16px;background-color:#" . $value->normal . ";'></div>";
								} else {
									if ($row->$datavariable == 0) {
										echo "<div style='white-space: nowrap;width:40px;height:16px;'>n/a</div>";
									} else {
										echo "<div style='white-space: nowrap;width:40px;height:16px;background-color:#" . $value->normal . ";'></div>";
									}
								}
								break;
										
							case 'UIBallColumn':
								
								$column->generateContentCell($row, $rowNumber);
								break;
								
										
							case 'UIFixedColumn':
			
								$this->createUIFixedColumnTD($column,$row);
								break;
			

							case 'UIBooleanColumn':
	
								$value = $row->$variable;
								
								if ($value == 1) {
									echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='1'>";
									echo "x";
								} else {
									echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='0'>";
									echo "";
								}
								
								break;
										
							case 'UIStatusColumn':
										
								$statusindex = $row->$variable;
								if (isset($column->statuslist[$statusindex])) {
									echo "<div style='width:100%;min-height:10px;background-color:" . $column->statuslist[$statusindex] . "'>";
									echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='". $statusindex . "'>";
									echo "</div>";
								} else {
									echo "**" . $statusindex . "**";
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
						
						if ($class ==  'UIButtonColumn') {
							echo "</td>";
						} else {
							if (is_numeric($this->lineaction)) {
								echo "</td>";
							} elseif ($this->lineaction == null) {
								echo "</td>";
							} else {
								echo "</div>";
								echo "</a>";
								echo "</td>";
							}
						}
					}
			
					if ($this->deleteaction != null) {
						
						if ($this->deleteactiveparam != null) {
							$var = $this->deleteactiveparam;
							if (is_array($row)) {
								$active = $row[$var];
							} else {
								$active = $row->$var;
							}
							if (($active == null) || ($active == false) || ($active == 0)) {
								echo "<td style='text-align:right;padding-top:2px;padding-bottom:1px;width:40px;'>";
								echo "<button class=section-button-header style='widht:22px;height:22px;' disabled='disabled'><i class='fa fa-ban' ></i></button>";
								echo "</td>";
								//$this->generateDeleteAction("rowdeletebutton-" . $this->getID() . "-" . $rowNumber,$row);
							} else {
								echo "<td style='text-align:right;padding-top:2px;padding-bottom:1px;width:40px;'><button id='rowdeletebutton-" . $this->getID() . "-" . $rowNumber . "' class=section-button-header style='margin-left:3px;widht:22px;height:22px;'><i class='fa fa-ban' ></i></button></td>";
								$this->generateDeleteAction("rowdeletebutton-" . $this->getID() . "-" . $rowNumber,$row);
							}
							
						} else {
							echo "<td style='text-align:right;padding-top:2px;padding-bottom:1px;width:40px;'><button id='rowdeletebutton-" . $this->getID() . "-" . $rowNumber . "' class=section-button-header style='widht:22px;height:22px;'><i class='fa fa-ban' ></i></button></td>";
							$this->generateDeleteAction("rowdeletebutton-" . $this->getID() . "-" . $rowNumber,$row);
						}
 						
			
						/*
						 if (is_array($row)) {
						 //echo "<td style='text-align:right;padding-top:2px;padding-bottom:1px;'><button onClick=\"deleterowaction" . $this->getID() . "('" . $row[$this->deleteactionparam] . "')\" class=section-button-header style='widht:22px;height:22px;'><i class='fa fa-ban' ></i></button></td>";
						 $this->generateDeleteaction("tablesectioncolumn-" . $this->getID() . "-" . $rowNumber . "-" . $columnnumber,$row);
			
						 } else {
						 $param = $this->deleteactionparam;
						 echo "<td style='text-align:right;padding-top:2px;padding-bottom:1px;'><button onClick='deleterowaction" . $this->getID() . "(" . $row->$param . ")' class=section-button-header style='widht:22px;height:22px;'><i class='fa fa-ban' ></i></button></td>";
						 }
						 */
					}
					echo "</tr>";
				}
					
			
				foreach($this->items as $index => $column) {
					$class = get_class($column);
			
			
					if ($class == 'UIButtonColumn') {
							
					    /** @var mixed $index2 */
						foreach($this->items as $index2 => $datacolumn) {
							if ($datacolumn->getVariable() == $column->getVariable()) break;
						}
						$datavariable = $column->datavariable;
						
						echo "<script>";
						echo "		function buttonpressed_" . $this->getID() . "_" . $index . "(event,rownro) {";
						//echo "			alert('temp 01');";
							
						
						if ($column->actiontype == UIComponent::ACTION_JAVASCRIPT) {
						
								
							//echo "  	$('#sectiondialog-" . $datacolumn->getVariable() . "').dialog('open');";
							
							//$callstring = "";
							//echo "	console.log(' - try -  " . $column->datavariable . "');";
							$callvariable = "";
							foreach($this->items as $index => $item) {
								//echo "	console.log(' - item -  " . $index . " - " . $item->datavariable . "');";
								
								if ($item instanceof UIButtonColumn) {
									//echo "console.log('buttoncolumni');";
								} else {
									if ($column->datavariable == $item->datavariable) {
										echo "  	var value" . $item->getID() . " = $('#tablerow-" . $this->getID() . "-'+rownro+'-" .  $item->getID() . "').val();";
										//echo "	console.log(' - value - '+value" . $item->getID() . ");";
										$callvariable = "value" . $item->getID();
									}
								}
								
								/*
								if ($callstring != "") $callstring = $callstring . ",";
								echo "  	var value" . $item->getID() . " = $('#tablerow-" . $this->getID() . "-'+rownro+'-" .  $item->getID() . "').val();";
								$callstring = $callstring . "value" . $item->getID();
								*/
							}
							//echo "		console.log('columnaction - " . $column->action . "');";
							//echo "		console.log('callstring - " . $callstring . "');";
							//echo "		console.log('callvariable - " . $callvariable . "');";
							//echo "		" . $column->action . "(" . $callstring . ");";
							echo "		" . $column->action . "(" . $callvariable . ");";
								
						}
						
						if ($column->actiontype == UIComponent::ACTION_OPENDIALOG) {
							//echo "			console.log('datavariable - " . $datacolumn->getVariable() . "');";
							//echo "			console.log('col datavariable - " . $column->getVariable() . "');";
							//echo "			console.log('buttonpressed - " . getUrl($column->action) . "&id='+rownro);";
							
							echo "		event.stopPropagation();";
							echo "		event.stopImmediatePropagation();";
							echo "		event.preventDefault();";
							
							foreach($this->items as $index => $item) {
								//echo "		console.log('lineaction OPENDIALOG - item->getID - " .  $item->getID() . "' );";
								//echo "		console.log('item class - " .  get_class($item) . "' );";
								echo "  	valuexx = $('#tablerow-" . $this->getID() . "-'+rownro+'-" .  $item->getID() . "').val();";
									
								//echo "		console.log('datavariable aa - " . $datavariable . "');";
								//echo "		console.log('lineaction2 #tablerow-" . $this->getID() . "-" . $item->getID() . " - " . get_class($item) . " - " .  $item->getVariable() . " ... '+valuexx);";
								
								if ($item instanceof UIButtonColumn) {
								
								} else {
									echo "		setValue_" . $datavariable . "('" . $item->getVariable() . "',valuexx);";
								}
								
								/*
								if ($item instanceof UIButtonColumn) {
									// buttoneita ei oteta mukaan
								} else {
									echo "		setValue_" . $targetsectionID . "('" . $item->getVariable() . "',valuexx);";
								}
								*/
							}
							
							echo "  	$('#sectiondialog-" . $datacolumn->getVariable() . "').dialog('open');";
							
							//echo "  	$('#sectiondialog-" . $targetsectionID . "').dialog('open');";
						} 
						
						if ($column->actiontype == UIComponent::ACTION_FORWARD) {
							
							//echo "			console.log('datavariable - " . $datacolumn->getVariable() . " - " . $datacolumn->getID() . "');";
							//echo "			console.log('col datavariable - " . $column->getVariable() . "');";
							//echo "			console.log('buttonpressed - " . getUrl($column->action) . "&id='+rownro);";
							
							echo "		event.stopPropagation();";	
							echo "		event.stopImmediatePropagation();";
							echo "		event.preventDefault();";
							
							echo "			var id = $('#tablerow-" . $this->getID() . "-'+rownro+'-" .  $datacolumn->getID() . "').val();";
							//echo "			var idstr = 'tablerow-" . $this->getID() . "-'+rownro+'-" .  $datacolumn->getID() . "';";
							
							echo "			if (id === undefined) {";
							echo "				alert('no variable \'" .  $column->getVariable() . "\' found');";
							echo "				window.event.stopPropagation();";
							echo "				return 0;";
							echo "			}";
							echo "			var loc = '" . getUrl($column->action) . "&id='+id;";
							echo "			window.location = loc;";			// tämä pitäisi toteuttaa jsonina
							echo "			window.event.stopPropagation();";
						}
						
						
						echo "		};";
						echo "</script>";
			
							
						// TODO: tähän pitäisi tsekata UIButtonColumn->actiontype, tällähetkellä hardkoodattu UIComponent::ACTION_FORWARD tyyppinen toiminta
			
					}
				}
			}
			
			if ($this->data > 0) {
				if ($this->showsumrow == true) {
				
					$rowNumber++;
					//$pointerclass = "";
					//if ($this->lineaction != null) $pointerclass = "cursor:pointer;";
					$trclass = "listtable-evenrow";
					if ($rowNumber % 2 == 0) $trclass = "listtable-oddrow";
						
					echo " 	<tr id='tablerow-" . $this->getID() . "-".$rowNumber."' class='" . $trclass . "'>";
						
					//echo " 	<tr id='tablesumrow-" . $this->getID() . "-".$rowNumber."' class='listtable-oddrow'>";
					//echo "<tr>";
				
					foreach($this->items as $index => $column) {
						$class = get_class($column);
						if ($class == 'UISortColumn') {
				
							if ($column->dataformatter == Column::COLUMNTYPE_FLOAT) {
								echo "<td style='text-align:right;border-top:2px solid grey;font-weight:bold;'>";
								if ($column->dataformatter == null) {
									echo $column->formatValue($sums[$index], "");
								} else {
									echo $column->formatValue($sums[$index], "");
								}
								//echo "" . $sums[$index];
								echo "</td>";
							} else {
								echo "<td style='border-top: 2px solid grey;'></td>";
							}
						} else {
							echo "<td style='border-top:2px solid grey;'></td>";
						}
					}
					if ($this->deleteaction != null) {
						echo "<td style='border-top:2px solid grey;'></td>";
					}
					if ($this->showlinenumbers == true){
						echo "<td style='border-top:2px solid grey;'></td>";
					}
					if ($this->showselectboxes == true){
						echo "<td style='border-top:2px solid grey;'></td>";
					}
					echo "</tr>";
				}
			}
			
			
			
			if ($this->showtotal == 1) {
				echo "<tr>";
				echo "<td colspan=" . (count($this->items)+1) . " style='height:5px;text-align:right;padding-right:10px;'>" . count($this->data) . "</td>";
				echo "</tr>";
			} else {
				echo "<tr>";
				echo "<td style='height:5px;'></td>";
				echo "</tr>";
			}
			
		}
		echo "</tbody>";
		
		echo "</table>";
		
		echo "</div>";
		
		
		
		// Tämä kopioitu UISectionista, sulje buttonin toteutus, en tiedä miten tämä suhtautuu jos generateButtons luo muita nappuloita
		if ($this->dialog == true) {
			echo "	<table style='width:100%;border-collapse:collapse;margin-top:3px;'>";
			echo "		<tr>";
			echo "			<td class=contentcell style=''>";
			echo "				<table class=contentinsidetable style='width:100%;text-align:right;border-collapse:collapse;'>";
			echo "					<tr id=sectionfooter-".$this->getID().">";
				
			echo "						<td style='display:inline; float:left;'>";
			echo "						</td>";
				
			echo "						<td style='padding-right:5px;align:right;'>";
				
			echo "							<div id=sectionsavebuttons-".$this->getID()." style='width:250px;float:right;'>";
			
			echo "								<button  class=section-button onclick='closeDialog".$this->getID()."()'>Sulje</button>";
			echo "							</div>";
				
			echo "						</td>";
			echo "					</tr>";
			echo "				</table>";
			echo "			</td>";
			echo "		</tr>";
			echo "	</table>";
			

			echo "<script>";
			echo "		function closeDialog".$this->getID()."() {";
			echo "  		$('#sectiondialog-" . $this->getID() . "').dialog('close');";
			echo "		};";
			echo "	</script>";
				
		}
		
		
		
		// TODO: vielä tarvitsisi javascript tarkistuksen, että mikäli tämä on oletuksena auki, niin sitten 
		//       loadi pitäisi toteuttaa automaattisesti heti. Tämä varmaan saadaan helposti contentin 
		//       tulostukseen, loadaus javascripti vaan mukaan.
		//
		// 		 - Buttonit ja niiden aktionit puuttuu
		//		 - lineaction puuttuu
		//		 - buttonien painallus voisi ladata ainoastaan sisällän uusiksi
		//		 - Toimii todennäkäisesti ainoastaan indeksipohjaisissa tauluissa, tai riippuu siitä palauttaako datasource oikein
		//		 - controllerien routaus voisi olla ehkä kevyempi näillä json kutsuilla, niitä saadaan kevyemmäksi
		//
		if ($this->loadonopen) {
			echo "<script>";
			echo "		function loadcontent" . $this->getID() . "() {";
			
			if ($this->datasourcevariable != "") {
				echo "			var idvalue = $('#sectionidvalue-".$this->getID()."').val();";
				//echo "			console.log(' datasourcevalue - " . $this->datasourcevariable . " - '+idvalue);";
				//echo "			console.log('loadcontent - " . getUrl($this->datasource) . "&" . $this->datasourcevariable . "=' + idvalue);";
				echo "			$.getJSON('" . getUrl($this->datasource) . "&" . $this->datasourcevariable . "=' + idvalue,'',function(data) {";
			} else {
				//echo "			console.log('loadcontent - " . getUrl($this->datasource) . "&" . $this->datasourcevariable . "=' + idvalue);";
				echo "			$.getJSON('" . getUrl($this->datasource) . "','',function(data) {";
			}
			
					
			//echo "					console.log('data.length - '+data.length);";
			echo "					$('#sectiontbody" . $this->getID() . " tr').remove();";
			echo "					$('#searchloadingdiv').hide();";
			echo "					var rowcounter = 0;";
			//echo "					var trclass = 'listtable-evenrow';";
			
			echo "					$.each(data, function(index) {";
			echo "						rowcounter++;";
			echo "						var row = '';";
				
			echo "						if (rowcounter % 2 == 0) {";
			echo "							row = '<tr class=\'listtable-evenrow\'>';";
			//echo "							trclass = 'listtable-evenrow';";
			echo "						} else {";
			echo "							row = '<tr class=\'listtable-oddrow\'>';";
			//echo "							trclass = 'listtable-oddrow';";
			echo "						}";
			
			$index = 0;
			
			// TODO: tätä ei ole toteutettu, toteutetaan kun ensimmäisen kerran tarvitaan
			if ($this->showselectboxes == true) {
				echo "			row = row + '<td style=\"padding-left:10px;\">n/a</td>';";
			}
			
			if ($this->showlinenumbers == true) {
				echo "			row = row + '<td style=\"padding-left:10px;\">'+rowcounter+'</td>';";
			}
			foreach($this->items as $index => $column) {
			
				$class = get_class($column);
				switch($class) {
					
					case 'UIFloatColumn':
						// TODO: not implemented
						break;
							
					case 'UISortColumn':
						// TODO: not implemented
						break;
					case 'UIMultilangColumn' :
						// TODO: not implemented
						break;
					case 'UIHiddenColumn':
						break;
					case 'UIButtonColumn':
						// TODO: not implemented
						echo "			row = row + '<td style=\"padding-left:10px;\"></td>';";
						break;
					case 'UIArrayColumn':
						// TODO: not implemented
						break;
					case 'UISimpleColumn':
						
						if ($class ==  'UIButtonColumn') {
							echo "			row = row + '<td style=\"padding-left:10px;\">'+data[index][" . $index . "]+'</td>';";
						} else {
							if (is_numeric($this->lineaction)) {
								echo "			row = row + '<td style=\"padding-left:10px;\">'+data[index][" . $index . "]+'</td>';";
							} elseif ($this->lineaction == null) {
								echo "			row = row + '<td style=\"padding-left:10px;\">'+data[index][" . $index . "]+'</td>';";
							} else {
								echo "			row = row + '<td style=\"padding-left:10px;\">';";
								echo "			row = row + '<a href=\"" .getUrl($this->lineaction) . "&id='+ data[index][" . $this->lineactionparam . "]+'\" style=\"text-decoration:none\"><div>';";
								echo "			row = row + data[index][" . $index . "]+'</div></a></td>';";
								
								//echo "<td style='padding-left:10px;padding-top:0px;max-width:" . $this->maxcolumnwidth . ";width:" . $this->maxcolumnwidth . "'>";
								//echo "<a href='" .getUrl($this->generateLineActionUrl($row,10)) . "' style='text-decoration:none'><div>";
							}
						}
						
						//echo "			row = row + '<td style=\"padding-left:10px;\">'+data[index][" . $index . "]+'</td>';";
						//echo "						console.log('row - '+data[index][" . $index . "]);";
						$index++;
						break;
					case 'UISelectColumn':
						// TODO: not implemented
						break;
					case 'UIColorColumn':
						// TODO: not implemented
						break;
					case 'UIFixedColumn':
						// TODO: not implemented
						break;
					default :
						break;
				}
			}
				
			
			/*
			echo "						var row = '<tr>'";
			echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
			echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordclassID+'</td>'";
			echo "							+ '<td style=\"padding-right:10px;\">'+data[index].conceptID+'</td>'";
			echo "							+ '<td title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/concepts/showconcept') . "&id='+data[index].conceptID+'\">'+data[index].name+'</a></td>'";
			echo "							+ '<td><button onclick=\"addItem(\''+data[index].conceptID+'\')\">lisää</button></td>'";
			echo "							+ '</tr>';";
			echo "						$('#searchresulttable').append(row);";
			*/
			echo "						var row = row + '</tr>';";
			//echo "					console.log('row - '+row);";
			echo "						$('#sectiontbody" . $this->getID() . "').append(row);";
			echo "					});";
				
			echo "			}); ";
				
			echo "		}";
			echo "</script>";
		}
		
		if ($this->sortactiontype > 0) {
			echo "<script>";
			echo "	$(function() {";
			echo "		sortstartindex = 0;";
			echo "		sortendindex = 0;";
			echo "		sortdataitem = 0;";
				
			echo "		$('#sectiontbody" . $this->getID() . "').sortable({";
			//echo "			helper: 'clone',";
			echo "			start: function (event, ui) {";
			//echo "				console.log('sortable start - ' + ui.item.index());";
			//echo "				console.log('sortable data - ' + ui.item.data('sortdata'));";
			echo "				sortstartindex =  ui.item.index();";
			//echo "				console.log('sortable start index - ' + sortstartindex);";
			echo "				sortdataitem = ui.item.data('sortdata');";
			//echo "				console.log('sortstart - ' + sortdataitem);";
			echo "			},";
			echo "			update: function (event, ui) {";
			//echo "				console.log('sortable update - ' + ui.originalPosition.top);";
			//echo "				console.log('sortable update - ' + ui.position.top);";
			echo "				sortendindex =  ui.item.index();";
			//echo "				console.log('sortable end index - ' + sortendindex);";
			echo "				var direction = 'up';";
			echo "				var place = 0;";
			echo "				var item = 0;";
			echo "				if (sortstartindex < sortendindex) {";
			//echo "					console.log('up');";
			echo "					bb = $('#tablerow-" . $this->getID() . "-'+(sortendindex+1)).data('sortdata');";
			//echo "					console.log('sort sortdataitem - '+sortdataitem+'');";
			//echo "					console.log('sort bb - '+bb);";
			echo "					place = bb;";
			echo "					item = sortdataitem;";
			echo "				} else {";
			echo "					direction = 'down';";
			//echo "					console.log('down');";
			echo "					bb = $('#tablerow-" . $this->getID() . "-'+(sortendindex+1)).data('sortdata');";
			//echo "					console.log('sort sortdataitem - '+sortdataitem+'');";
			//echo "					console.log('sort bb - '+bb);";
			echo "					place = bb;";
			echo "					item = sortdataitem;";
			echo "				}";
			
			echo "				var loc = '" . getUrl($this->sortaction) . "&direction='+direction+'&place='+place+'&item='+item;";
			//echo "				console.log(''+loc);";
			echo "			alert('temp 02');";	
			echo "				window.location = loc;";			// tämä pitäisi toteuttaa jsonina
						
			echo "			},";
			echo "			end: function (event, ui) {";
			//echo "				console.log('sortable end');";
			echo "			}";
			echo "		});";
			//echo "		$('#sectiontable" . $this->getID() . "').sortable();";
			//echo "		$('#sectiontable" . $this->getID() . "').disableSelection();";
			echo "	});";
			echo "</script>";
		}
				
		

		
						
		if (($this->buttonVerticalAlign == UIComponent::VALIGN_BOTTOM) || ($this->buttonVerticalAlign == 0)) {
			//$this->generateButtons();
			//echo "<br>Bottom buttons";
			//$this->generateButtons();
			echo "	<table style='width:100%'>";
			echo " 	<tr class='listtable-row'>";
			$columncount = count($this->items)+1;
			echo "			<td colspan=" . $columncount . " style='text-align:right;vertical-align:bottom;'>";
			if (count($this->buttons) > 0) {
				foreach($this->buttons as  $index => $button) {
					$button->show();
					echo " ";
				}
			}
			echo "			</td>";
			echo "		</tr>";
			echo "	</table>";						   
		}
			
			
		// Tämä on kipioitu UISectionista, tätä kutsutaan ainakin silloin kun tablesectionista kutsutaan
		// on lineaction open dialog, ja asetaan valueita sectioniin. Tablesectionin on tarve ottaa vastaan
		// joitakin onloadissa tarvittavia parametreja...
		
		echo "	<script>";
		echo "		function setValue_" . $this->getID(). "(fieldname, value) {";
		//echo "			console.log('----------------------------------------------------------');";
		//echo "			console.log('call setvalueaa - " . $this->getID() . " - " . get_class($this) . " - '+fieldname+' - '+value);";
		//echo "			console.log('call datasourcevariable - " . $this->datasourcevariable . "');";
		
		if ($this->datasourcevariable != "") {
			echo "		if (fieldname == '" . $this->datasourcevariable . "') {";
			echo "			$('#sectionidvalue-".$this->getID()."').val(value);";
			//echo "			console.log(' datasourcevariable found - " . $this->datasourcevariable . " - '+value+' - '+fieldname);";
			echo "		}";
		}

		/*
		foreach($this->items as $index => $field) {
			if ($field instanceof UIButtonColumn) {
			} else {
				echo "		if (fieldname == '" . $field->getVariable() . "') {";
				echo "			" . $field->setValueJSFunction() . "(value);";
				echo "		}";
			}
		}
		*/
		echo "		}";
		echo "	</script>";
		
		
		if ($this->showselectboxes == true) {
			echo "	<script>";
			echo "		function getSelectedItems_" . $this->getID(). "() {";
			echo "			console.log('getselecteditems - " . (count($this->data)+1). "');";
			echo "			var selected = [];";
			echo "			var elementname = '';";
			echo "			var index = 0;";
			echo "			for (let i = 1; i < " . (count($this->data)+1) . "; i++) {";
			
			echo "				console.log('- tsekki');";
				
			// disabledeja ei oteta mukaan...
			echo "				if ($('#checkbox_" . $this->getID() . "_'+i).is(':disabled')) {";
			echo "					var id = $('#checkboxidvalue_" . $this->getID() . "_'+i).val();";
			echo "					console.log('- row disabled '+i+' - '+id);";
			echo "				} else {";
			echo "					if ($('#checkbox_" . $this->getID() . "_'+i).is(':checked')) {";
			echo "						var id = $('#checkboxidvalue_" . $this->getID() . "_'+i).val();";
			echo "						console.log('- row selected '+i+' - '+id);";
			echo "						selected[index] = id;";
			echo "						index++;";
			echo "					} else {";
			echo "					console.log('- not selectd '+i);";
			echo "					}";
			echo "				}";
			echo "			}";
			echo "			return selected;";
			echo "		}";
			echo "	</script>";
		}
	}
		

	public function getSelectedItemsFunction() {
		return "getSelectedItems_" . $this->getID(). "";
	}
	
	
	public function setDialog($boolean) {
		$this->dialog = $boolean;
	}
	
	
	

	public function show() {
		
		/*
		if ($this->framesVisible == true) {
			parent::show();			
		} else {
			$width = '600px';   // $this->width
			echo "<div style='width:" . $width  .";'>";
			$this->generateContent();
			echo "</div>";
		}
		return false;
		*/
		

		/*
		 * // kopioitu UISectionista
		if ($this->customConcentFunction != null) {
			echo "<div style='width:" . $this->sectionwidth  .";'>";
			$this->generateSectionHeader();
			callFunc($this->customConcentFunction);
			$this->generateFooter();
			echo "</div>";
			return false;
		}
		*/
		
		if ($this->dialog) {
			echo "<div id='sectiondialog-" . $this->getID() . "'   title=\"" . $this->title . "\"  style='overflow:visible;width:" . $this->sectionwidth  .";display:none;'>";
			$this->generateContent();
			echo "</div>";
		
			echo "<script>";
			echo "	$(function() {";
			echo "		$('#sectiondialog-" . $this->getID() . "').dialog({ open: function(event,ui) { sectiononload_" . $this->getID() . "(); }, modal:true, autoOpen: false, width: \"" . $this->sectionwidth . "\" });";
			echo "	});";
			echo "</script>";
		
			echo "<script>";
			echo "	function sectiononload_" . $this->getID() . "() {";
			//echo "		console.log('section on load');";
			if ($this->loadonopen) {
				echo "	loadcontent" . $this->getID() . "();";
			}
			echo "	};";
			echo "</script>";
			
		} else {
			
			if ($this->framesVisible == true) {
				echo "<div style='width:" . $this->sectionwidth  .";'>";
				$this->generateSectionHeader();
				$this->generateContent();
				$this->generateFooter();
				echo "</div>";
			} else {
				if ($this->width != null) {
					$width = $this->width;   
					echo "<div style='width:" . $width  .";'>";
					$this->generateContent();
					echo "</div>";
				} else {
					$width = '600px'; 
					echo "<div style='width:" . $width  .";'>";
					$this->generateContent();
					echo "</div>";
				}
			}
			return false;
			
			
		}
		return false;
		
	}
	
}

?>