<?php




class UITreeSection extends UIAbstractSection {
	
	private $data;
	private $standalone = false;	// tämä on tarkoitettu UITable tyyppisen näkymän tuottamiseen. (tälläin lisää nappula ylhäällä)
	
	//private $buttons = array();
	//private $buttonactions = array();
	
	//private $checkable = false;		// ei käytässä
	private $deletesuccesssaction = null;
	private $allopen = false;
	private $deleteactiveparam = false;
	
	private $maxcolumnwidth;
		
	private $width = 18;
	private $height = 18;
	

	public function __construct($title = '', $width = '600px') {
		parent::__construct($title, $width);
		$this->maxcolumnwidth = '400px';
		$this->showTitle(true);
		//$this->titleVisible = true;
		$this->width = $width;
		$this->setFramesVisible(false);
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
	
	
	public function setCollapse($boole) {
		$this->allopen = $boole;
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
	
	
	/**
	 * Asetetaan muuttuja jonka perusteela poista nappula on aktiivinen tai disabloitu. 
	 * 
	 * @param unknown $activationparam
	 */
	public function setDeleteActiveParam($activationparam) {
		$this->deleteactiveparam = $activationparam;
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
		$currentlevel = 0;
		foreach($data as $index => $row) {
			$this->getPrivateLevelPrivate($row, 0, $maxlevel);
		}
		return $maxlevel;
	}
	

	
	public function generateContent() {
		
		if ($this->buttonVerticalAlign == UIComponent::VALIGN_TOP) {
							  
			echo "	<table style='width:100%'>";
			echo "		<tr>";
			echo "			<td style='vertical-align:bottom;'>";
			if ($this->titleVisible == true) {
				echo "<span style='font-weight:bold;font-size:24px;'>" . $this->title . "</span>";
			} else {
				echo "<span style='font-weight:bold;font-size:24px;'></span>";
			}
			echo "			</td>";
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
		
		
		$maxlevel = $this->getLevel($this->data);
		

		echo "<table class='listtable' style='width:100%;padding:0px;'>";
		echo " 	<tr class='listtable-row'>";
		
		$firststr = "";
		$level = 0;
		//for($level = 0; $level < $maxlevel; $level++) {
		$firststr = $firststr . "<button id='treebutton-" . $this->getID() . "-" . $level . "-openall' class=section-button style='padding-left:4px;padding-top:0px;width:20px;height:20px;display:none;' OnClick=\"hideallrows(" . $level . ")\"><i class='fa fa-sort-down'></i></button>";
		
		// TODO: ei oikein teitoa mikä allaoleva rivi, esiintyy boolean columnissa
		//$firststr = $firststr . "<button id='treebutton-" . $this->getID() . "-" . $level. "-closeall' class=section-button style='padding-left:2px;width:20px;height:20px;' OnClick=\"showallrows(" . $level . ")\"><i style='padding-left:4px;top:4px;left:15px;' class='fa fa-caret-right'></i></button>";
		
			
		//}
		
		// Headerin generointi on ehkä myäs UIAbstractSectionin funktioita, ehkä staattinen
		$first = true;
		foreach($this->items as $index => $column) {
		
			$class = get_class($column);
			switch($class) {
					
				case 'UISortColumn' :
					
					echo "<td  class='listtable-header'>" . parseMultilangString($column->name,2) . "</td>";
						
					/*
					echo "<td class='listtable-header'>";
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
					echo "<a href='' onClick='linnkkipress()'>" . $column->name ."</a>";
					echo "</td>";
					*/
					break;
		
				case 'UIHiddenColumn' :
						break;
						
		
				case 'UIArrayColumn' :
		
					if ($column->link == null) {
						echo "		<td  class='listtable-header'>" . $column->name . "</th>";
					} else {
						echo "		<td  class='listtable-header'><a href='". getUrl($column->link) . "'>" . $column->name . "</a></th>";
					}
					break;
		
				case 'UISelectColumn' :
		
					$dropDownMenuID = "table-" . $this->getID() . "-" . $index;
					echo "	<td  class='listtable-header' onmouseout=\"$('#" . $dropDownMenuID . "').hide()\" onMouseOver=\"$('#" . $dropDownMenuID . "').show()\" style=''>";
					echo "		" . getMultilangString($column->name);
					/*
					echo "		<div class='listtable-dropdownmenu' id=" . $dropDownMenuID . ">";
					echo "			<a href='".getUrl($column->link, array($column->datavariable =>  0))."'> kaikki </a><br>";
					foreach($column->selection as $contentID => $content) {
						$showvariable = $column->showvariable;
						echo "		<a href='".getUrl($column->link, array($column->datavariable =>  $contentID))."'>" . $content->$showvariable . "</a><br>";
					}
					echo "		</div>";
					*/
					echo "	</td>";
					break;
		
				case 'UIFixedColumn':
		
					echo "<td  class='listtable-header'>" . $column->name . "</td>";
					break;
		
				case 'UIButtonColumn':
				
					echo "<td  class='listtable-header' style='width:20px;'>" . $column->name . "</td>";
					break;
					
				default :
		
					if ($first == true) {
						echo "<td  class='listtable-header' style='padding-left:0px;'>";
						echo $firststr;
						echo "<span style='margin-left:6px;'>" . $column->name . "</span>";
						echo "</td>";
						$first = false;
					} else {
						echo "<td  class='listtable-header'>" . $column->name . "</td>";
					}
					
					break;
			}
		}
		if ($this->deleteaction != null) {
			echo "<td  class='listtable-header'></td>";
		}
		echo " 	</tr>";
		
		$rowNumber = 0;
		//$openArray = getSessionArray('tree-' . $this->getID());
		$openArray = array();
		$rulerownumbers = array();
		$hidestart = array();
		//$this->showChilds($this->data, 0, $rowNumber,$openArray, true, $this->allopen);
		
		$currentopen = false;
		if ($this->allopen == true) $currentopen = true;
		
		$this->showChilds($this->data, 0, $rowNumber,$openArray, $currentopen, false, $rulerownumbers, $hidestart);
		
		echo "</table>";
		
		
		
		echo "<script>";
		echo "		function subhide" . $this->getID() . "(rownro) {";
		
		echo "			var startpos = 0;";
		echo "			var endpos = 0;";
		foreach($hidestart as $startpos => $endpos) {
			echo "if (rownro == '" . $startpos . "') {";
			echo "	startpos = " . $startpos . ";";
			echo "	endpos = " . $endpos . ";";
			echo "}";
		}
		//echo "			console.log(' - hidessi - '+startpos+'-'+endpos);";
		echo "			for(x = startpos+1;x<(endpos+1);x++) {";
		echo "				$('#treerow-" . $this->getID() . "-'+x).hide();";
		echo "			}";
		
		echo "		};";
		
		echo "</script>";
			
		
		
		
		//$this->openParentScripts($this->data, $rulerownumbers);
		
		
		echo "<script>";
		echo "		function hiderows(start, end) {";
		//echo "			alert('hiderows');";
		echo "			for(i = start+1;i<(end+1);i++) {";
		echo "				$('#treerow-" . $this->getID() . "-'+i).hide();";
		echo "			}";
		//echo "			$('#treebutton-" . $this->getID() . "-'+start+'-open').hide();";
		//echo "			$('#treebutton-" . $this->getID() . "-'+start+'-closed').show();";
		
		//echo "			console.log('closetreenode - " . getUrl('system/session/closetreenode') . "&json=1&treeid=" . $this->getID() . "&rowid='+start);";
		echo "			$.getJSON('" . getUrl('system/session/closetreenode') . "&json=1&treeid=" . $this->getID() . "&rowid='+start,'',function(reply) { ";
		echo "				alert('success closetreenode - " . getUrl('system/session/closetreenode') . "&json=1&treeid=" . $this->getID() . "&rowid='+start);";
		echo "			});";
		
		//echo "			$('#buttonicon-" . $this->getID() . "-'+start).removeClass('fa-sort-down');";
		//echo "			$('#buttonicon-" . $this->getID() . "-'+start).addClass('fa-caret-right');";
		//echo "			$('#treebutton-" . $this->getID() . "-'+start).attr('onclick','showrows('+start+','+end+')');";
		echo "			updaterowcolors();";
		//echo "			window.event.stopPropagation();";
		echo "		};";
		echo "</script>";
		
		
		echo "<script>";
		echo "		function showrows(start, end) {";
		echo "			console.log('showrows - '+start+' - '+end);";
		echo "			for(i = start+1;i<(end+1);i++) {";
		echo "				$('#treerow-" . $this->getID() . "-'+i).show();";
		//echo "				console.log('showi - '+i);";
		echo "			}";
		
		//echo "			$('#treebutton-" . $this->getID() . "-'+start+'-open').show();";
		//echo "			$('#treebutton-" . $this->getID() . "-'+start+'-closed').hide();";
		
		//echo "			console.log('closetreenode - " . getUrl('system/session/opentreenode') . "&json=1&treeid=" . $this->getID() . "&rowid='+start);";
		echo "			$.getJSON('" . getUrl('system/session/opentreenode') . "&json=1&treeid=" . $this->getID() . "&rowid='+start,'',function(reply) { ";
		echo "				alert('success closetreenode - " . getUrl('system/session/opentreenode') . "&json=1&treeid=" . $this->getID() . "&rowid='+start);";
		echo "			});";
		
		
		//echo "			$('#buttonicon-" . $this->getID() . "-'+start).removeClass('fa-caret-right');";
		//echo "			$('#buttonicon-" . $this->getID() . "-'+start).addClass('fa-sort-down');";
		//echo "			$('#treebutton-" . $this->getID() . "-'+start).attr('onclick','hiderows('+start+','+end+')');";
		
		echo "			for(i = start+1;i<(end+1);i++) {";
		echo "				if ($('#treebutton-" . $this->getID() . "-'+i+'-open').is(':hidden')) {";
		//echo "					console.log('openbutton is hidden - '+i);";
		//echo "					console.log('hidden - '+i);";
		//echo "					$('#treebutton-" . $this->getID() . "-'+i+'-open').click();";
		echo "					subhide" . $this->getID() . "(i);";
		echo "				} else {";
		//echo "					console.log('openbutton not hidden - '+i);";
		//echo "					$('#treebutton-" . $this->getID() . "-'+i+'-closed').click();";
		echo "				}";
		echo "			}";
		
		// kelataan kaikki buttonin lävitse, ja jos open buttoneita on visiblenä, niin kutustaan closebuttonin clickiä
		
		
		echo "			updaterowcolors();";
		//echo "			window.event.stopPropagation();";
		echo "		};";
		echo "</script>";
		
		
		echo "<script>";
		echo "		function hideallrows(level) {";
		echo "			alert('hideallrows-'+level);";
		//echo "				console.log(''+i+' - '+trclass);";
		echo "		};";
		echo "</script>";
		
		echo "<script>";
		echo "		function showallrows(level) {";
		echo "			alert('showallrows-'+level);";
		//echo "				console.log(''+i+' - '+trclass);";
		echo "		};";
		echo "</script>";
		
		
		echo "<script>";
		echo "		function updaterowcolors() {";
		//echo "			console.log('updatecolors');";
		echo "			var trclass = '';";
		echo "			var classnro = 1;";
		echo "			for(i = 1;i<". ($rowNumber+1) . ";i++) {";
		//echo "				console.log(''+i+' - '+trclass);";
		
		echo "				if ($('#treerow-" . $this->getID() . "-'+i).is(':hidden')) {";
		//echo "					console.log(''+i+' - hidden - '+trclass);";
		echo "				} else {";
		//echo "					console.log(''+i+' - open - '+trclass);";
		echo "					if (classnro == 1) {";
		echo "						trclass = 'listtable-evenrow';";
		echo "						classnro = 2;";
		echo "					} else {";
		echo "						trclass = 'listtable-oddrow';";
		echo "						classnro = 1;";
		echo "					}";
		echo "					$('#treerow-" . $this->getID() . "-'+i).removeClass('listtable-evenrow');";
		echo "					$('#treerow-" . $this->getID() . "-'+i).removeClass('listtable-oddrow');";
		echo "					$('#treerow-" . $this->getID() . "-'+i).addClass(trclass);";
		echo "				}";
		echo "			}";
		echo "		};";
		echo "</script>";
		
		
		echo "<script>";
		echo "		$( document ).ready(function() {";
		echo "			updaterowcolors();";
		echo "		});";
		echo "</script>";
		
	}
	
	
	/*
	private function openParentScripts($parentrow, $rulerownumbers) {
		
		$list = array();
		foreach($parentrow as $index => $row) {
			UITreeSection::getChildsRecursive($row, $list);
		}
		
		
		echo "<script>";
		echo "	function showparent(parentID) {";
		echo "		console.log(' - showparent - '+parentID);";
		
		foreach($list as $ruleID => $parentID) {
			//echo " 	console.log(' rrr: " . $ruleID . " - " . $parentID . "');";
		}
		
		foreach($list as $ruleID => $parentID) {
			echo " if (parentID == " . $ruleID . ") {";
			$rowNumber = $rulerownumbers[$ruleID];
			echo "		$('#treebutton-" . $this->getID() . "-" . $rowNumber . "-open').show();";
			echo "		$('#treebutton-" . $this->getID() . "-" . $rowNumber . "-closed').hide();";
			foreach($list as $tempruleID => $tempparentID) {
				if ($tempparentID == $ruleID) {
					$rowNumber = $rulerownumbers[$tempruleID];
					//echo " 	console.log('show ruleID:" . $tempruleID . " - " . $rowNumber . "');";
					echo "	$('#treerow-" . $this->getID() . "-" . $rowNumber . "').show();";
				}
			}
			echo " }";
		}
		echo "	}";
		echo "</script>";
		
		
		echo "<script>";
		echo "		function hideparent(parentID) {";
		//echo "			console.log(' - hideparent - '+parentID);";
		foreach($list as $ruleID => $parentID) {
			echo " if (parentID == " . $ruleID . ") {";
			$rowNumber = $rulerownumbers[$ruleID];
			echo "		$('#treebutton-" . $this->getID() . "-" . $rowNumber . "-open').hide();";
			echo "		$('#treebutton-" . $this->getID() . "-" . $rowNumber . "-closed').show();";
			foreach($list as $tempruleID => $tempparentID) {
				if ($tempparentID == $ruleID) {
					$rowNumber = $rulerownumbers[$tempruleID];
					//echo " 	console.log('hide ruleID:" . $tempruleID . " - " . $rowNumber . "');";
					echo "	$('#treerow-" . $this->getID() . "-" . $rowNumber . "').hide();";
					echo "	hideparent(" . $tempruleID . ");";
				}
			}
			echo " }";
		}
		echo "		};";
		echo "</script>";
	}
	*/
	
	private static function getChildsRecursive($parentrow, &$list) {
		
		$list[$parentrow->getID()] = $parentrow->parentID;
			
		//echo "<br><br>getChildsRecursive";
		if ($parentrow->getChildCount() > 0) {
			//echo "<br> - parent: " . $parentrow->getID();
			foreach($parentrow->getChildren() as $index => $row) {
				//echo "<br> -- childrow - " . $parentrow->getID() . " -> " . $row->getID();
				UITreeSection::getChildsRecursive($row, $list);
			}
		}	
	}
	

	private function getPrivateSubRowCount($parentrow, &$currentrow) {
	
		$currentrow++;
		if ($parentrow->getChildCount() > 0) {
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
	
	
	

	private function showChilds($parentrow ,$depth, &$rowNumber, $openArray, $open, $allopen, &$rulerownumbers, &$hidestart) {
	
		$rows = null;
		$currentparent = null;
		if (is_array($parentrow)) {
			$rows = $parentrow;
		} else {
			$rows = $parentrow->getChildren();
			$currentparent = $parentrow;
		}
		
		
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
				
		
		if (count($rows) > 0) {
			
			foreach($rows as $index => $row) {
			
				$rowNumber++;
				$trclass = "listtable-evenrow";
				if ($rowNumber % 2 == 0) $trclass = "listtable-oddrow";
					
					
				//if ($this->lineaction!=null) echo "<tr onclick='".$this->lineaction."(".$row->getID().")'>";
				//if ($this->lineaction==null) echo "<tr>";
				$display = "display:none";
				if ($open) $display = "";
				if ($allopen) $display = "";
				
				if ($depth == 0) $display = "";
				
				$pointerclass = "";
				if ($this->lineaction != null) $pointerclass = "cursor:pointer;";
				
				echo "<tr class='" . $trclass . "' style='" . $display . ";" . $pointerclass . ";' id='treerow-" . $this->getID() . "-" . $rowNumber . "'>";
				$rulerownumbers[$row->getID()] = $rowNumber; 
				$this->generateLineaction("treerow-" . $this->getID() . "-" . $rowNumber,$row,$this->lineaction);
				
				
				
				$first = true;
				
				foreach($this->items as $index => $column) {
			
					$variable = $column->datavariable;
					if ($first == true) {
						
						$class = get_class($column);
						
						
						if ($row->getChildCount() > 0) {
							$subrowcount = $this->getSubRowCount($row->getChildren());
							//echo "<td id='treecol-" . $this->getID() . "-" . $rowNumber . "-" . $index . "' style='padding-left:" . ($depth*20) . "px;'>";
							echo "<td id='treecol-" . $this->getID() . "-" . $rowNumber . "-" . $index . "' style='padding-left:" . ($depth*20) . "px;'>";
							if (is_numeric($this->lineaction)) {
								//echo "<td style='padding-left:10px;padding-top:0px;max-width:" . $this->maxcolumnwidth . ";width:" . $this->maxcolumnwidth . ";'>";
							} elseif ($this->lineaction == null) {
								//echo "<td style='padding-left:10px;padding-top:0px;vertical-align:top;max-width:" . $this->maxcolumnwidth . ";width:" . $this->maxcolumnwidth . "'>";
							} else {
								//echo "<td style='padding-left:10px;padding-top:0px;max-width:" . $this->maxcolumnwidth . ";width:" . $this->maxcolumnwidth . "'>";
								echo "<div>";
							}
								

							//echo "<button id='treebutton-" . $this->getID() . "-" . $rowNumber . "-open' class=section-button style='display:none;padding-left:4px;padding-top:0px;width:20px;height:20px;display:none;background-color:Transparent;border-width:0px;'><i style='top:-4px'  id='buttonicon-" . $this->getID() . "-" . $rowNumber . "' class='fa fa-chevron-down'></i></button>";
							//echo "<button id='treebutton-" . $this->getID() . "-" . $rowNumber . "-closed' class=section-button style='padding-left:6px;width:20px;height:20px;background-color:Transparent;border-width:0px;;'><i id='buttonicon-" . $this->getID() . "-" . $rowNumber . "' class='fa fa-chevron-right'></i></button>";
								
							//if (isset($openArray[$rowNumber]) || ($this->allopen == true)) {
							if ($this->allopen == true) {
								echo "<button id='treebutton-" . $this->getID() . "-" . $rowNumber . "-open' class=section-button style='padding-left:4px;padding-top:0px;width:20px;height:20px;background-color:Transparent;border-width:0px;'><i id='buttonicon-" . $this->getID() . "-" . $rowNumber . "' class='fa fa-chevron-down'></i></button>";
								echo "<button id='treebutton-" . $this->getID() . "-" . $rowNumber . "-closed' class=section-button style='padding-left:2px;width:20px;height:20px;display:none;background-color:Transparent;border-width:0px;'><i style='padding-left:4px;top:4px;left:15px;' id='buttonicon-" . $this->getID() . "-" . $rowNumber . "' class='fa fa-chevron-right'></i></button>";
								//echo "<button id='treebutton-" . $this->getID() . "-" . $rowNumber . "-open' class=section-button style='padding-left:4px;padding-top:0px;width:20px;height:20px;background-color:Transparent;border-width:0px;' OnClick=\"hiderows(" . $rowNumber . "," . ($rowNumber + $subrowcount) . ")\"><i id='buttonicon-" . $this->getID() . "-" . $rowNumber . "' class='fa fa-chevron-down'></i></button>";
								//echo "<button id='treebutton-" . $this->getID() . "-" . $rowNumber . "-closed' class=section-button style='padding-left:2px;width:20px;height:20px;display:none;background-color:Transparent;border-width:0px;;' OnClick=\"showrows(" . $rowNumber . "," . ($rowNumber + $subrowcount) . ")\"><i style='padding-left:4px;top:4px;left:15px;' id='buttonicon-" . $this->getID() . "-" . $rowNumber . "' class='fa fa-chevron-right'></i></button>";
							} else {
								echo "<button id='treebutton-" . $this->getID() . "-" . $rowNumber . "-open' class=section-button style='padding-left:4px;padding-top:0px;width:20px;height:20px;display:none;background-color:Transparent;border-width:0px;'><i style='top:-4px'  id='buttonicon-" . $this->getID() . "-" . $rowNumber . "' class='fa fa-chevron-down'></i></button>";
								echo "<button id='treebutton-" . $this->getID() . "-" . $rowNumber . "-closed' class=section-button style='padding-left:6px;width:20px;height:20px;background-color:Transparent;border-width:0px;;'><i id='buttonicon-" . $this->getID() . "-" . $rowNumber . "' class='fa fa-chevron-right'></i></button>";
								//echo "<button id='treebutton-" . $this->getID() . "-" . $rowNumber . "-open' class=section-button style='padding-left:4px;padding-top:0px;width:20px;height:20px;display:none;background-color:Transparent;border-width:0px;;' OnClick=\"hiderows(" . $rowNumber . "," . ($rowNumber + $subrowcount) . ")\"><i style='top:-4px'  id='buttonicon-" . $this->getID() . "-" . $rowNumber . "' class='fa fa-chevron-down'></i></button>";
								//echo "<button id='treebutton-" . $this->getID() . "-" . $rowNumber . "-closed' class=section-button style='padding-left:6px;width:20px;height:20px;background-color:Transparent;border-width:0px;;' OnClick=\"showrows(" . $rowNumber . "," . ($rowNumber + $subrowcount) . ")\"><i id='buttonicon-" . $this->getID() . "-" . $rowNumber . "' class='fa fa-chevron-right'></i></button>";
							}
							
							if (is_numeric($this->lineaction)) {
								//echo "<td style='padding-left:10px;padding-top:0px;max-width:" . $this->maxcolumnwidth . ";width:" . $this->maxcolumnwidth . ";'>";
							} elseif ($this->lineaction == null) {
								//echo "<td style='padding-left:10px;padding-top:0px;vertical-align:top;max-width:" . $this->maxcolumnwidth . ";width:" . $this->maxcolumnwidth . "'>";
							} else {
								//echo "<td style='padding-left:10px;padding-top:0px;max-width:" . $this->maxcolumnwidth . ";width:" . $this->maxcolumnwidth . "'>";
								echo "<a href='" .getUrl($this->generateLineActionUrl($row)) . "' style='text-decoration:none'>";
							}
							
							echo "	<input id=treerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
							
							if ($class ==  'UIButtonColumn') {
								if ($parentrow != null) {
									echo "	<input id=treerowparent-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $parentrow->$variable . "'>";
								} else {
									echo "	<input id=treerowparent-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='0'>";
								}
							}
							
							//echo "<span style='margin-left:6px;'>treerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . "..." .$row->$variable ."</span>";
							if (isset($column->languageID)) {
								if ($column->languageID != null) {
									echo "<span style='margin-left:6px;'>" . parseMultilangString($row->$variable,$column->languageID) ."</span>";
								} else {
									echo "<span style='margin-left:6px;'>" . parseMultilangString($row->$variable,2) ."</span>";
								}
							} else {
								echo "<span style='margin-left:6px;'>" . parseMultilangString($row->$variable,2) ."</span>";
							}
							
								
							if (is_numeric($this->lineaction)) {
							} elseif ($this->lineaction == null) {
							} else {
								echo "</a></div>";
							}
							echo "</td>";
								
							/*
							echo "<script>";
							echo "		function treebuttonclose" . $this->getID() . "() {";
							echo "			hiderows(" . $rowNumber . "," . ($rowNumber + $subrowcount) . ");";
							echo "		};";
							echo "</script>";
							*/

							echo "<script>";
							echo "	$('#treebutton-" . $this->getID() . "-" . $rowNumber . "-open').click(function(event) {";
							//echo "		$('#treebutton-" . $this->getID() . "-" . $rowNumber . "').click( function () {;";
							echo "			console.log('closebutton clicked - " . $subrowcount . " .. " . $rowNumber . "," . ($rowNumber + $subrowcount) . "');";
							
							echo "			$('#treebutton-" . $this->getID() . "-" . $rowNumber . "-closed').show();";
							echo "			$('#treebutton-" . $this->getID() . "-" . $rowNumber . "-open').hide();";
							
							
							echo "			console.log('hiderows-". $rowNumber . "');";
							//echo "			hideparent(" . $row->getID() . ");";
							echo "			hiderows(" . $rowNumber . "," . ($rowNumber + $subrowcount) . ");";
							
							//$hideend[$rowNumber] = ($rowNumber + $subrowcount);
							
							echo "			event.stopPropagation();";
							echo "		});";
							echo "</script>";
							
							
							echo "<script>";
							echo "	$('#treebutton-" . $this->getID() . "-" . $rowNumber . "-closed').click(function(event) {";
							//echo "		$('#treebutton-" . $this->getID() . "-" . $rowNumber . "').click( function () {;";
							//echo "			alert('subrowcount - " . $subrowcount . "');";
							echo "			console.log('openbutton clicked - " . $subrowcount . "');";
								
							echo "			$('#treebutton-" . $this->getID() . "-" . $rowNumber . "-closed').hide();";
							echo "			$('#treebutton-" . $this->getID() . "-" . $rowNumber . "-open').show();";
								
							
							echo "			console.log('showrows-". $rowNumber . "');";
							echo "			showrows(" . $rowNumber . "," . ($rowNumber + $subrowcount) . ");";
							//echo "			showparent(" . $row->getID() . ");";
							echo "			event.stopPropagation();";
							echo "		});";
							echo "</script>";
								
							$hidestart[$rowNumber] = ($rowNumber + $subrowcount);
								
								
							
						} else {
							
							
							
							echo "	<input id=treerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
							
							$class = get_class($column);
							
							if ($class ==  'UIButtonColumn') {
								if ($parentrow != null) {
									echo "	<input id=treerowparent-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $parentrow->$variable . "'>";
								} else {
									echo "	<input id=treerowparent-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='0'>";
								}
							}
							
							echo "	<td id=treecol-" . $this->getID() . "-" . $rowNumber . "-" . $index . "'  style='padding-left:" . (($depth*20)+20) . "px'>";
							if (is_numeric($this->lineaction)) {
								//echo "<td style='padding-left:10px;padding-top:0px;max-width:" . $this->maxcolumnwidth . ";width:" . $this->maxcolumnwidth . ";'>";
							} elseif ($this->lineaction == null) {
								//echo "<td style='padding-left:10px;padding-top:0px;vertical-align:top;max-width:" . $this->maxcolumnwidth . ";width:" . $this->maxcolumnwidth . "'>";
							} else {
								//echo "<td style='padding-left:10px;padding-top:0px;max-width:" . $this->maxcolumnwidth . ";width:" . $this->maxcolumnwidth . "'>";
								echo "<a href='" .getUrl($this->generateLineActionUrl($row)) . "' style='text-decoration:none'><div>";
							}
							//echo "		<span style='margin-left:7px;'>treerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . "..." .$row->$variable ."</span>";
							
							if (isset($column->languageID)) {
								if ($column->languageID != null) {
									echo "		<span style='margin-left:7px;'>" . parseMultilangString($row->$variable,$column->languageID) ."</span>";
								} else {
									echo "		<span style='margin-left:7px;'>" . parseMultilangString($row->$variable,2) ."</span>";
								}
							} else {
								echo "		<span style='margin-left:7px;'>" . parseMultilangString($row->$variable,2) ."</span>";
							}
								
							if (is_numeric($this->lineaction)) {
							} elseif ($this->lineaction == null) {
							} else {
								echo "</a></div>";
							}
							echo "	</td>";
						}
						$first = false;
					} else {
						
						$class = get_class($column);
						
						if ($class ==  'UIButtonColumn') {
							echo "<td style='padding-left:0px;padding-right:0px;margin-left:0px;margin-right:0px;text-align:center;'>";
							if ($currentparent != null) {
								//var_dump($currentparent);
								echo "	<input id=treerowparent-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $currentparent->wordgroupID . "'>";
							} else {
								echo "	<input id=treerowparent-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='0'>";
							}
								
						} else {
							if (is_numeric($this->lineaction)) {
								echo "<td style='padding-left:10px;padding-top:0px;'>";
							} elseif ($this->lineaction == null) {
								echo "<td style='padding-left:10px;padding-top:0px;vertical-align:top;'>";
							} else {
								echo "<td style='padding-left:10px;padding-top:0px;'>";
								echo "<a href='" .getUrl($this->generateLineActionUrl($row)) . "' style='text-decoration:none'><div>";
							}
						}
						
						
						
						switch($class) {
								
							case 'UIHiddenColumn':
							
								echo "	<input id=treerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
								break;
							
								
							case 'UIButtonColumn':
						
								//echo "	<td style='padding-left:0px;padding-right:0px;margin-left:0px;margin-right:0px;text-align:center;'>";
								echo "		<div style='width:100%;backgroud-color:green;'>";
								echo "		<button class=section-button style='padding-top:1px;padding-left:5px;width:27px;height:19px;' OnClick=\"buttonpressed_" . $this->getID() . "_" . $index . "(" . $rowNumber . ")\">";
								echo "			<i style='padding-left:0px;top:2px;' class='" . $column->icon . "'></i>";
								echo "		</button>";
								echo "		<div>";
								//echo "	</td>";
								break;
							
							case 'UISelectColumn':

								$datavariable = $column->datavariable;
								$showvariable = $column->showvariable;

								if (is_integer($datavariable)) {
									//echo "<br>array - " . count($column->selection);
									//echo "<br>index - " . $column->datavariable;
									$value = $column->selection[$row[$column->datavariable]];
									
									//echo "" . $value;
								} else {
									//echo "<br>index - " . $datavariable;
									echo "<input id=tablerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='".$row->$datavariable . "'>";
									
									
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
								echo "		<span>" .$value ."</span>";
								
								//echo "	 <input id=treerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
								//echo "		<span>b " .$row->$variable ."</span>";
								break;

								
							case 'UIMultilangColumn' :
									echo "	<input id=treerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
									echo "		<span>aa " . parseMultilangStringWithEmpty($row->$variable, $column->languageID) ."</span>";
									break;
								
								
							default :
						
								echo "	<input id=treerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . " type='hidden' value='" . $row->$variable . "'>";
								//echo "	<td id=treecol-" . $this->getID() . "-" . $rowNumber . "-" . $index . "'>";
								//echo "		<span>treerow-" . $this->getID() . "-" . $rowNumber . "-" .  $column->getID() . "..." .$row->$variable ."</span>";
								echo "		<span>" .$row->$variable ."</span>";
								//echo "	</td>";
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
								echo "</div></a></td>";
							}
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
							echo "<td style='text-align:right;padding-top:2px;padding-bottom:1px;'>";
							echo "<button class=section-button-header style='widht:22px;height:22px;' disabled='disabled'><i class='fa fa-ban' ></i></button>";
							echo "</td>";
							//$this->generateDeleteAction("rowdeletebutton-" . $this->getID() . "-" . $rowNumber,$row);
						} else {
							echo "<td style='text-align:right;padding-top:2px;padding-bottom:1px;'><button id='rowdeletebutton-" . $this->getID() . "-" . $rowNumber . "' class=section-button-header style='margin-left:3px;widht:22px;height:22px;'><i class='fa fa-ban' ></i></button></td>";
							$this->generateDeleteAction("rowdeletebutton-" . $this->getID() . "-" . $rowNumber,$row);
						}
							
					} else {
						echo "<td style='text-align:right;padding-top:2px;padding-bottom:1px;'><button id='rowdeletebutton-" . $this->getID() . "-" . $rowNumber . "' class=section-button-header style='widht:22px;height:22px;'><i class='fa fa-ban' ></i></button></td>";
						$this->generateDeleteAction("rowdeletebutton-" . $this->getID() . "-" . $rowNumber,$row);
					}
				}		

				//echo "  <td>" . $rowNumber . "</td>";
				echo "</tr>";

				$childopen = false;
				//if (isset($openArray[$rowNumber]) || ($this->allopen == true)) {
				if ($this->allopen == true) {
					$childopen = true;
					//echo "<tr><td>open- " . $rowNumber . "</td></tr>";
				} else {
					
					//echo "<tr><td>closed - " . $rowNumber . "</td></tr>";
				}
				$childopen = false;
				if ($open == false) $childopen = false;
				if ($row->getChildCount() > 0) {
					$this->showChilds($row,($depth+1), $rowNumber, $openArray, $open, $childopen, $rulerownumbers, $hidestart);
				}
				
			}	
			
			
						
			foreach($this->items as $index => $column) {
				$class = get_class($column);
				if ($class == 'UIButtonColumn') {
					
					foreach($this->items as $index2 => $datacolumn) {
						if ($datacolumn->getVariable() == $column->getVariable()) break;
					}
					
					echo "<script>";
					echo "		function buttonpressed_" . $this->getID() . "_" . $index . "(rownro) {";
					//echo "			console.log('buttonpressed - " . getUrl($column->action) . "&id='+rownro);";
					
					

					if ($column->actiontype == UIComponent::ACTION_JAVASCRIPT) {
					
					
						echo "		event.stopPropagation();";
						echo "		event.stopImmediatePropagation();";
						echo "		event.preventDefault();";
					
						echo "  	$('#sectiondialog-" . $datacolumn->getVariable() . "').dialog('open');";
							
						$callstring = "";
						foreach($this->items as $index => $item) {
							if ($callstring != "") $callstring = $callstring . ",";
							echo "  	var value" . $item->getID() . " = $('#tablerow-" . $this->getID() . "-'+rownro+'-" .  $item->getID() . "').val();";
							$callstring = $callstring . "value" . $item->getID();
						}
							
						echo "		" . $column->action . "(" . $callstring . ");";
					
					}
					
					if ($column->actiontype == UIComponent::ACTION_OPENDIALOG) {
						
						foreach($this->items as $index2 => $datacolumn) {
							if ($datacolumn->getVariable() == $column->getVariable()) break;
						}
						$datavariable = $column->datavariable;
						
						
						echo "		event.stopPropagation();";
						echo "		event.stopImmediatePropagation();";
						echo "		event.preventDefault();";
							
						foreach($this->items as $index => $item) {
							echo "  	valuexx = $('#treerow-" . $this->getID() . "-'+rownro+'-" .  $item->getID() . "').val();";
							if ($item instanceof UIButtonColumn) {
					
							} else {
								//echo "		console.log('datavariable - " . $datavariable . " - " . $item->getVariable() . "');";
								echo "		setValue_" . $datavariable . "('" . $item->getVariable() . "',valuexx);";
							}
						}
						echo "  	$('#sectiondialog-" . $datacolumn->getVariable() . "').dialog('open');";
					}
					
					

					if ($column->actiontype == UIComponent::ACTION_FORWARD) {
						echo "			var parentid = $('#treerowparent-" . $this->getID() . "-'+rownro+'-" .  $column->getID() . "').val();";
						echo "			var id = $('#treerow-" . $this->getID() . "-'+rownro+'-" .  $datacolumn->getID() . "').val();";
						echo "			var idstr = 'treerow-" . $this->getID() . "-'+rownro+'-" .  $datacolumn->getID() . "';";
						echo "			var loc = '" . getUrl($column->action) . "&id='+id+'&parentid='+parentid;"; 
						echo "			window.location = loc;";			// tämä pitäisi toteuttaa jsonina
						echo "			window.event.stopPropagation();";
					}
					
					
					echo "		};";
					
					echo "</script>";
				}
			}
		}
	}
	
	
	
	/**
	 * Kopioitu UIAbstractSection:ista. Tämä pitää ylikirjoittaa treessä, että saadaan parentin id-mukaan.
	 * 
	 * 
	 * @param string $row
	 */
	public function generateButtonLineActionUrl($row, $parentrow) {
	
		$url = $this->lineaction;
		if (is_array($this->lineactionparam)) {
			foreach ($this->lineactionparam as $var => $urlparam) {
				if (is_array($row)) {
					$url = $url . '&' . $urlparam . "=" . $row[$var];
				} else {
					$url = $url . '&' . $urlparam . "=" . $row->$var;
				}
			}
		} else {
			if (is_array($row)) {
				$url = $url . '&id=' . $row[$this->lineactionparam];
				if ($parentrow !=  null) {
					$url = $url . '&parentid=' . $parentrow[$this->lineactionparam];
				} else {
					$url = $url . '&parentid=0';
				}
			} else {
				$var = $this->lineactionparam;
				$url = $url . '&id=' . $row->$var;
				if ($parentrow !=  null) {
					$url = $url . '&parentid=' . $parentrow->$var;
				} else {
					$url = $url . '&parentid=0';
				}
			}
		}
		return $url;
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
			//echo "<br>width: " . $this->width;
			$width = '600px';   // $this->width
			$width = $this->width;
			echo "<div style='width:" . $width  .";'>";
			$this->generateContent();
			echo "</div>";
		}
		return false;
	}
	
}

?>