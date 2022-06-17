<?php



/**
 * TODO: Tämänhän voisi itseasiassa yhdistää UITablen kanssa, lisätään vain UITableen funktio addSubLevel tms.
 * ehkä funktio addsublevelcolumn... tämä mahdollistaisi ehkä ainoastaan kaksitasoisen hierarkian, UITreellä
 * pitää sitten hoitaa useamman levelin hierarkiat, tai sitten addsublevelcolumnille asetataan level-numero.
 * 
 * @author PetriUusitalo
 *
 */

class UITierTableSection extends UIAbstractSection {
	
	private $data;
	private $standalone = false;	// tämä on tarkoitettu UITable tyyppisen näkymän tuottamiseen. (tälläin lisää nappula ylhäällä)
	
	//private $buttons = array();
	//private $buttonactions = array();
	
	//private $checkable = false;		// ei käytässä
	private $deletesuccesssaction = null;

	
	private $width = 18;
	private $height = 18;
	private $widths = null;

	private $sublevelColumns = null;
	private $parentVariable  = null;
	private $childVariable  = null;
	private $sublevelData = null;
	
	
	private $sublineactiontype = UIComponent::ACTION_NONE;
	private $sublineaction = null;
	private $sublineactionparam = null;
	
	
	public function __construct($title = '', $width = '600px') {
		parent::__construct($title, $width);
		$this->setFramesVisible(false);
		$this->widths = array();
	}
	
	
	public function setColumnWidth($index, $width) {
		$this->widths[$index] = $width;
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
	
	
	
	public function setLevelData($data, $columns, $parentvariable, $childVariable) {
		$this->sublevelData = $data;
		$this->sublevelColumns = $columns;
		$this->parentVariable = $parentvariable;
		$this->childVariable = $childVariable;
	}
	
	
	public function setSubLevelLineAction($lineactiontype, $lineaction, $paramvariable = null) {
		$this->sublineactiontype = $lineactiontype;
		$this->sublineaction = $lineaction;
		$this->sublineactionparam = $paramvariable;
	}
	
	
	/**
	 * Mikäli tätä ei ole asetettu, oletetaan, että action url forwardoi automaattisesti oikeaan 
	 * paikkaan. Tämän tarkoitus on lähinnä mahdollistaa javascriptin deleten käsittelijä.
	 * Debugissa voisi olla hyvä, että javascript käsittelijää ei käytetä vaan aina mennään forwardilla
	 * 
	 * // TODO: yhtenäinen toiminto muihinkin delete actioneihins
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
	
	
	/*
	public function addButton($text, $link, $actiontype = UIComponent::ACTION_FORWARD) {
		$this->buttons[$text] = $link;
		$this->buttonactions[$text] = $actiontype;
	}
	*/
	
	

	public function addColumn($column) {
		if ($this->items == null) $this->items = array();
		$this->items[] = $column;
	}
	
	
	
	public function showAsStandalone($boolean = true) {
		$this->standalone = $boolean;
	}
	
	
	

	private function createUIFixedColumnTD($column,$row) {
		$variable = $column->datavariable;
	
		if ($this->namefunction != null) {
			echo "<td class='uitree-td'>b " . call_user_func($this->namefunction, $row) ."</td>";
		} else {
			echo "<td class='uitree-td'>a " . $row->$variable . "</td>";
		}
	}
	
	
	private function getPrivateLevelPrivate($parentrow, $currentlevel, &$maxlevel) {
		
		$currentlevel++;
		if ($currentlevel > $maxlevel) $maxlevel = $currentlevel;
		if ($parentrow->getChildCount() > 0) {
			foreach($parentrow->getChildren() as $index => $row) {
				$this->getPrivateLevelPrivate($row, $currentlevel, $maxlevel);
			}
		}
		return;		
	}
	
	
	public function getLevel($data) {
		$maxlevel = 0;
		
		foreach($data as $index => $row) {
			$this->getPrivateLevelPrivate($row, 0, $maxlevel);
		}
		return $maxlevel;
	}
	

	
	public function generateContent() {
		
		if ($this->buttonVerticalAlign == UIComponent::VALIGN_TOP) {
			echo "	<table style='width:100%'>";
			echo "		<tr>";
			echo "			<td style='text-align:right;vertical-align:bottom;'>";
			if (count($this->buttons) > 0) {
				foreach($this->buttons as  $index => $button) {
					$button->show();
					echo " ";
				}
			}
			echo "			</td>";
			echo "		</tr>";
			echo "	</table>";
			//$this->generateButtons();
		}
		
		
		//$maxlevel = $this->getLevel($this->data);
		//echo "<br>Maxlevel - " . $this->getLevel($this->data);
		

		echo "<table class='listtable' style='width:100%;'>";
		
		$rowNumber = 0;
		$bgColor = "background-color:#E6E6E6;";
		foreach($this->data as $index => $row) {
		
							
			$rowNumber++;
			$trclass = "listtable-evenrow";
			if ($rowNumber % 2 == 0) $trclass = "listtable-evenrow";
			//if ($rowNumber % 2 == 0) $trclass = "listtable-oddrow";
						
						
			//if ($this->lineaction!=null) echo "<tr onclick='".$this->lineaction."(".$row->getID().")'>";
			//if ($this->lineaction==null) echo "<tr>";
			$display = "display:none";
			$display = "";
			
			$pointerclass = "";
			if ($this->lineaction != null) $pointerclass = "cursor:pointer;";
			
			echo "<tr class='" . $trclass . "' style='" . $display . ";" . $pointerclass . "' id='treerow-" . $this->getID() . "-" . $rowNumber . "'>";
			$this->generateLineaction("treerow-" . $this->getID() . "-" . $rowNumber,$row, $this->lineaction);
			
			//$first = true;
			$itemcounter = 0;
			foreach($this->items as $index => $column) {
				$itemcounter++;
				
				$widthstr = "";
				if (isset($this->widths[$itemcounter])) {
					$widthstr  = "width:" . $this->widths[$itemcounter] . ";";
				}
				
				$variable = $column->datavariable;
				$class = get_class($column);
				switch($class) {
					
					case 'UIHiddenColumn':
						echo "	<input id=treerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
						break;
						
					case 'UIButtonColumn':
						echo "	<td style='". $bgColor . ";width:30px;padding-right:0px;margin-left:0px;margin-right:0px;text-align:right;padding:left:3px;'>";
						echo "		<div style='width:100%;backgroud-color:green;'>";
						echo "		<button class=section-button style='padding-top:1px;padding-left:5px;width:27px;height:19px;' OnClick=\"buttonpressed_" . $this->getID() . "_" . $index . "(" . $rowNumber . ")\">";
						echo "			<i style='padding-left:0px;top:2px;' class='" . $column->icon . "'></i>";
						echo "		</button>";
						echo "		<div>";
						echo "	</td>";
						break;
						
					case 'UISelectColumn' :
						

						$datavariable = $column->datavariable;
						$showvariable = $column->showvariable;
						
						if (is_integer($datavariable)) {
							//echo "<br>array - " . count($column->selection);
							//echo "<br>index - " . $column->datavariable;
							$value = $column->selection[$row[$column->datavariable]];
								
							//echo "" . $value;
						} else {
							//echo "<br>index - " . $datavariable;
							echo "<input id=treerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='".$row->$datavariable . "'>";
								
								
							if (isset($column->selection[$row->$datavariable])) {
								//echo "<br>index - " . $column->selection[$row->$datavariable];
								if ($showvariable == NULL) {
									$value = $column->selection[$row->$datavariable];
								} else {
									$value = $column->selection[$row->$datavariable]->$showvariable;
								}
							} else {
								$value = "<font size=-1 style='font-style:italic;color:green'>" . $row->$datavariable . "</font>";
							}
						}
						//if ($itemcounter == 2) {
						//	echo "	<td id=treecol-" . $this->getID() . "-" . $rowNumber . "-" . $index . "' style='background-color:pink;'>";
						//} else {
							echo "	<td style='". $bgColor . ";" . $widthstr . ";' id=treecol-" . $this->getID() . "-" . $rowNumber . "-" . $index . "'>";
						//}
						echo "		<span>" .$value ."</span>";
						echo "	</td>";
						
						//echo "	 <input id=treerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
						//echo "		<span>b " .$row->$variable ."</span>";
						break;
							
					
						
					default :
						echo "	<input id=treerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
						echo "	<td style='". $bgColor . ";" . $widthstr . ";' id=treecol-" . $this->getID() . "-" . $rowNumber . "-" . $index . "'>";
						echo "		<span>" .$row->$variable ."</span>";
						echo "	</td>";
						break;
				}
			}
			echo "</tr>";
			$this->showNextLevel($row, 0, $rowNumber,null, count($this->items), true);
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
				echo "		function buttonpressed_" . $this->getID() . "_" . $index . "(rownro) {";
				
				echo "			console.log('rownro - '+rownro);";
				echo "			window.event.stopPropagation();";
				echo "			window.event.stopImmediatePropagation();";
				echo "			window.event.preventDefault();";
			
				if ($column->actiontype == UIComponent::ACTION_JAVASCRIPT) {
			
					echo "		alert('not implemented - copied from UItableSection')";
					/*
					echo "  	$('#sectiondialog-" . $datacolumn->getVariable() . "').dialog('open');";
						
					$callstring = "";
					foreach($this->items as $index => $item) {
						if ($callstring != "") $callstring = $callstring . ",";
						echo "  	var value" . $item->getID() . " = $('#treerow-" . $this->getID() . "-'+rownro+'-" .  $item->getID() . "').val();";
						$callstring = $callstring . "value" . $item->getID();
					}
						
					echo "		" . $column->action . "(" . $callstring . ");";
					*/
			
				}
			
				if ($column->actiontype == UIComponent::ACTION_OPENDIALOG) {
					
					foreach($this->items as $index => $item) {
						echo "  	valuexx = $('#treerow-" . $this->getID() . "-'+rownro+'-" .  $item->getID() . "').val();";
						if ($item instanceof UIButtonColumn) {
			
						} else {
							echo "		setValue_" . $datavariable . "('" . $item->getVariable() . "',valuexx);";
						}
					}
					echo "  	$('#sectiondialog-" . $datacolumn->getVariable() . "').dialog('open');";
				}
			
				if ($column->actiontype == UIComponent::ACTION_FORWARD) {
						
					echo "		event.stopPropagation();";
					echo "		event.stopImmediatePropagation();";
					echo "		event.preventDefault();";
						
					echo "			var id = $('#treerow-" . $this->getID() . "-'+rownro+'-" .  $datacolumn->getID() . "').val();";
					echo "			var idstr = 'treerow-" . $this->getID() . "-'+rownro+'-" .  $datacolumn->getID() . "';";
						
					echo "			if (id === undefined) {";
					echo "				alert('no variable \'" .  $column->getVariable() . "\' found - '+idstr);";
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
			
			
			/*
			$class = get_class($column);
			if ($class == 'UIButtonColumn') {
				foreach($this->items as $index2 => $datacolumn) {
					if ($datacolumn->getVariable() == $column->getVariable()) break;
				}
					
				echo "<script>";
				echo "		function buttonpressed_" . $this->getID() . "_" . $index . "(rownro) {";
				//echo "			console.log('buttonpressed - " . getUrl($column->action) . "&id='+rownro);";
				echo "			var id = $('#treerow-" . $this->getID() . "-'+rownro+'-" .  $datacolumn->getID() . "').val();";
				echo "			var idstr = 'treerow-" . $this->getID() . "-'+rownro+'-" .  $datacolumn->getID() . "';";
				//echo "			console.log('idvalue - '+id+' - ' +idstr);";
					
				echo "			if (id === undefined) {";
				echo "				alert('no variable 12  \'" .  $column->getVariable() . "\' found');";
				echo "				window.event.stopPropagation();";
				echo "				return 0;";
				echo "			}";
					
				echo "			var loc = '" . getUrl($column->action) . "&id='+id;";
				//echo "			console.log('loc - '+loc);";
				//echo "			alert('jeejee - '+rownro);";
				echo "			window.location = loc;";			// tämä pitäisi toteuttaa jsonina
				echo "			window.event.stopPropagation();";
				echo "		};";
				echo "</script>";
			}
			*/
		}
			
		echo "</table>";
		
	
		
	}
	
	


	private function getPrivateSubRowCount($parentrow, &$currentrow) {
	
		$currentrow++;
		if ($parentrow->getChildCount() > 0) {
		    
		    /** @var mixed $index */
			foreach($parentrow->getChildren() as $index => $row) {
				$this->getPrivateSubRowCount($row, $currentrow);
			}
		}
		return;
	}
	
	
	public function getSubRowCount($data) {
		$currentrow = 0;
		foreach($data as $index => $row) {
			$this->getPrivateSubRowCount($row, $currentrow);
		}
		return $currentrow;
	}
	
	
	private function showChildsLevel() {
		
	}
	

	/**
	 * perus generateLineaction - funkkarissa on se ongelma, että se käyttää items-taulua mikä
	 * sisältää nykyisen rakenteen perus columnsseja. Kun käytetään jotakin sisäistä riviä, kuten nyt
	 * esimerkiksi käytetään UITierTablseSectionissa opendialogin setvaluet menee väärälle ID:lle.
	 *
	 * Tämä voitaisiin varmaankin yhdistää perus generateLineactioniin, mutta pidetään nyt selkeyden
	 * vuoksi erillään (voi nimittäin olla, että tuon viimeisen columns-arvon tarkoitus hämärtyy
	 * ennenkuin mietitään ja dokumentoidaan tarkemmin.
	 *
	 */
	private function generateLineactionSecondTier($itemID, $row, $targetsectionID, $columns) {
	
		echo "<script>";
		switch($this->sublineactiontype) {
			case UIComponent::ACTION_FORWARD :
				//echo "  $('#" . $itemID . "').click(function () {";
				//echo "		loadpage('" . $this->generateLineActionUrl($row) . "','" . $this->actiontitle . ");";
				//echo "	});";
				break;
			case UIComponent::ACTION_JAVASCRIPT:
				//echo "  $('#" . $itemID . "').click(function () {";
				//echo "		alert('lineaction LINEACTION JAVASCRIPT not implemented');";
				//echo "	});";
				break;
			case UIComponent::ACTION_FORWARD_INDEX:
				//echo "  $('#" . $itemID . "').click(function () {";
				//echo "		loadpage('" . $this->generateLineActionUrl($row) . "','" . $this->actiontitle . ");";
				//echo "	});";
				break;
			case UIComponent::ACTION_CHECK:
				//echo "  $('#" . $itemID . "').click(function () {";
				//echo "		alert('lineaction LINEACTION CHECK not implemented');";
				//echo "	});";
				break;
			case UIComponent::ACTION_OPENDIALOG:
				echo "  $('#" . $itemID . "').click(function () {";
				//echo "		console.log('lineaction OPENDIALOG - targetsection - " . $targetsectionID . "' );";
				echo "		var value = 0;";
				foreach($columns as $index => $item) {
					//echo "		console.log('lineaction OPENDIALOG - item->getID - " .  $item->getID() . "' );";
					echo "  	valuexx = $('#" . $itemID . "-" . $item->getID() . "').val();";
					//echo "		console.log('lineaction2 #" . $itemID . "-" . $item->getID() . " - " . get_class($item) . " - " .  $item->getVariable() . " ... '+valuexx);";
					if ($item instanceof UIButtonColumn) {
						// buttoneita ei oteta mukaan
					} else {
						//echo "		console.log('calling setValue_" . $targetsectionID . "(" . $item->getVariable() . ",valuexx); - ' + valuexx);";
						echo "		setValue_" . $targetsectionID . "('" . $item->getVariable() . "',valuexx);";
					}
					echo "  	$('#sectiondialog-" . $targetsectionID . "').dialog('open');";
				}
				echo "	});";
				break;
			default :
				break;
		}
		echo "</script>";
	}
	
	
	

	private function showNextLevel($parentrow ,$depth, &$rowNumber, $openArray, $tdcount, $open = true) {
	
		//$rows = $this->sublevelData;
		
		
		/*
		echo "<script>";
		echo "		function showrows(start, end) {";
		echo "			for(i = start+1;i<end;i++) {";
		echo "				$('#treerow-" . $this->getID() . "-'+i).show();";
		echo "			}";
		echo "			$('#buttonicon-" . $this->getID() . "-'+start).removeClass('fa-caret-right');";
		echo "			$('#buttonicon-" . $this->getID() . "-'+start).addClass('fa-sort-down');";
		echo "			$('#buttonicon-" . $this->getID() . "-'+start).attr('onclick','hiderows('+start+','+end+')');";
		echo "		};";
		echo "</script>";
		*/		
				
		echo "<tr>";
		//$this->generateLineaction("treerow-" . $this->getID() . "-" . $rowNumber,$row, $this->sublineaction);
		echo "<td colspan=" . $tdcount . " style='padding-left:30px;'>";
		echo "<table  class='listtable' style='width:100%' >";
		
		/*
		echo "<tr>";
		echo "	<td>sublevelData - " . count($this->sublevelData)  . "</td>";
		echo "</tr>";
		*/
		
		if ($this->sublevelData != null) 
		if (count($this->sublevelData) > 0) {
			
			
			
			foreach($this->sublevelData as $index => $row) {
			
				$parentvariable = $this->parentVariable;
				$childvariable = $this->childVariable;
				
				
				if ($parentrow->$parentvariable == $row->$childvariable) {
					$rowNumber++;
					$trclass = "listtable-oddrow";
					if ($rowNumber % 2 == 0) $trclass = "listtable-evenrow";
						
						
					//if ($this->lineaction!=null) echo "<tr onclick='".$this->lineaction."(".$row->getID().")'>";
					//if ($this->lineaction==null) echo "<tr>";
					$display = "display:none";
					if ($open) $display = "";
					
					$pointerclass = "";
					if ($this->sublineaction != null) $pointerclass = "cursor:pointer;";
					
					echo "<tr class='" . $trclass . "' style='" . $display . ";" . $pointerclass . "' id='subtreerow-" . $this->getID() . "-" . $rowNumber . "'>";
					$this->generateLineactionSecondTier("subtreerow-" . $this->getID() . "-" . $rowNumber, $row, $this->sublineaction, $this->sublevelColumns);
					//$this->generateLineaction("treerow-" . $this->getID() . "-" . $rowNumber,$row);
					//$first = true;
					foreach($this->sublevelColumns as $index => $column) {
							
						$variable = $column->datavariable;
						
					
							$class = get_class($column);
							switch($class) {
					
								case 'UIHiddenColumn':
										
									echo "	<input id=subtreerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
									break;
										
					
								case 'UIButtonColumn':
					
									echo "	<td style='width:1%;padding-left:0px;padding-right:0px;margin-left:0px;margin-right:0px;text-align:center;padding-left:2px;'>";
									echo "		<div style='width:100%;backgroud-color:green;'>";
									echo "		<button class=section-button style='padding-top:1px;padding-left:5px;width:27px;height:19px;' OnClick=\"buttonpressed_" . $this->getID() . "_" . $index . "(" . $rowNumber . ")\">";
									echo "			<i style='padding-left:0px;top:2px;' class='" . $column->icon . "'></i>";
									echo "		</button>";
									echo "		<div>";
									echo "	</td>";
									break;

								case 'UISelectColumn':

									$datavariable = $column->datavariable;
									$showvariable = $column->showvariable;
									
									if (isset($column->selection[$row->$datavariable])) {
										$value = $column->selection[$row->$datavariable]->$showvariable;
									} else {
										$value = "<font size=-1 style='font-style:italic;color:green'>" . $row->$datavariable . "</font>";
									}
									
									echo "	<input id=subtreerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
									echo "	<td id=treecol-" . $this->getID() . "-" . $rowNumber . "-" . $index . "'>";
									echo "		<span>" .$value ."</span>";
									echo "	</td>";
									break;
									
								default :
					
									echo "	<input id=subtreerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
									echo "	<td id=treecol-" . $this->getID() . "-" . $rowNumber . "-" . $index . "'>";
									//echo "		<span>treerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . "..." .$row->$variable ."</span>";
									echo "		<span>" . $row->$variable . "</span>";
									echo "	</td>";
									break;
							}
					
						
					}
					echo "</tr>";
				}
				
				
				
				//$childopen = false;
				//if (isset($openArray[$rowNumber])) {
					//$childopen = true;
					//echo "<tr><td>open- " . $rowNumber . "</td></tr>";
				//} else {
					
					//echo "<tr><td>closed - " . $rowNumber . "</td></tr>";
				//}
			}	
		}
		
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		
		foreach($this->sublevelColumns as $index => $column) {
			$class = get_class($column);
			if ($class == 'UIButtonColumn') {
					
				foreach($this->sublevelColumns as $index2 => $datacolumn) {
					if ($datacolumn->getVariable() == $column->getVariable()) break;
				}
					
				echo "<script>";
				echo "		function buttonpressed_" . $this->getID() . "_" . $index . "(rownro) {";
				//echo "			console.log('subbuttonpressed - " . getUrl($column->action) . "&id='+rownro);";
				echo "			var id = $('#subtreerow-" . $this->getID() . "-'+rownro+'-" .  $datacolumn->getID() . "').val();";
				echo "			var idstr = 'subtreerow-" . $this->getID() . "-'+rownro+'-" .  $datacolumn->getID() . "';";
				//echo "			console.log('idvalue - '+id+' - ' +idstr);";
					
				echo "			if (id === undefined) {";
				echo "				alert('no variable 13 \'" .  $column->getVariable() . "\' found');";
				echo "				window.event.stopPropagation();";
				echo "				return 0;";
				echo "			}";
					
				echo "			var loc = '" . getUrl($column->action) . "&id='+id;";
				//echo "			console.log('loc - '+loc);";
				//echo "			alert('jeejee - '+rownro);";
				echo "			window.location = loc;";			// tämä pitäisi toteuttaa jsonina
				echo "			window.event.stopPropagation();";
				echo "		};";
				echo "</script>";
			}
		}
	}
	
	
	
	public function openrowAction() {
		$id = $_GET['id'];
	}
	

	public function closerowAction() {
		$id = $_GET['id'];
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