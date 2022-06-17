<?php


/**
 *  Tämän luokan tarkoituksena on tarjota sectionin perustoiminnallisuus niin, että samaa sectionin framen
 *  toimintoja ei tarvitse koodata useampaan kertaan. Liittyy siihen, että sectionista tehdään useampia 
 *  eri versioita. Ainakin nyt ensimmäisenä tyälistalla on sectioni, joka ottaa parametreinaan taulukon, 
 *  korvaa siis sectionin innertable toiminnallisuuden
 * 
 *  Pitäisikä tästä vielä tehdä yläluokka tyyliin UIElement, täällä on jonkinverran samaa toiminnallisuutta kuin
 *  UITablessa, esim. section_ID voitaisiin korvata elementID:llä, generate DeleteAction, set DeleteAction, generateLineAction, setLineAction 
 *  saattavat olla yhteisiä. Tosin tämä ei saa olla ehkä UIElement nimeltään, koska ei saisi sekotta UIComponenttiin, joka voisi
 *  nimenomaan olla se oikea nimi/paikka. Muita abstrakteja yliluokkia ovat UIColumn ja UIField. Nämä voisivat kyllä olla
 *  omina yläluokkinaan, koska sectionmaisilla elementeillä olisi hyvä olla kiinteät IDNumerot, potentiaalisia aliluokkia voisivat
 *  kyllä olla myäs UIPaginator ja UIFilterBox, mutta ehkä tälläin tarvitaan vielä erillinen yläluokka, yksi hierarkiataso lisää.
 *  Tällähtekellä UIInsertSection on vähän yksinään, sillä on ainakin section_ID, joka saattaa aiheuttaa sekaannusta, eli sille pitäisi
 *  ainakin asettaa yhteinen yläluokka.
 *  
 *  Voisiko UITable periä UITableSectionin tai toisinpäin? Näissähän tosiaan on vain show funktion eroavaisuus. Ne voisi ehkä yhdistää. Tälläin ne
 *  perisivät molemmat UISectionin, mutta UITablen pitää overridata show-metodi.
 *  
 *  Varmaa nyt kuitenkin on, että UITableSectionissa ja UITablessa on paljon yhteistä toiminnallisuutta, jota ei kannata tuplata.
 *
 */

abstract class UIAbstractSection extends UIComponent {

	protected $lineactiontype = UIComponent::ACTION_NONE;
	protected $lineaction = null;
	protected $lineactionparam = null;
	protected $actiontitle = null;
	
	private static $sectionButtonCount = 1;
	
	protected $title;
	private $editable;
	
	private $openinstart = false;
	private $debug = false;
	private $openfunction = null;
	
	protected $sectionwidth;
	
	protected $updateactiontype;
	protected $updateaction;
	protected $updatevariable;
	
	protected $insertactiontype;
	protected $insertaction;
	
	protected $deleteactiontype = 0;
	protected $deleteaction = null;
	protected $deleteactionparam = null;
	
	// käytetään UITableSection ja UITreeSectionissa näyttämään ilman section framea
	protected $framesVisible = true;
	protected $buttonVerticalAlign = UIComponent::VALIGN_TOP;
	
	protected $loadonopen = false;
	protected $datasource = null;
	protected $datasourcevariable = null;
	protected $titleVisible = true;
	
	protected $items;
	protected $buttons;
	
	protected $mode = UIComponent::MODE_SHOW;
	
	
	public function __construct($title = '', $width = '600px') {
		
		parent::__construct();
		$this->title = $title;
		$this->editable = true;
		$this->debug = false;
		$this->sectionwidth = $width;
		$this->items = array();
		$this->buttons = array();
	}
	
	
	public function printTitleVisible() {
		if ($this->titleVisible == true) {
			echo "<br>---- titlevisible true";
		} else {
			echo "<br>---- titlevisible false";
		}
	}

	public function showTitle($boole) {
		$this->titleVisible = $boole;
	}
	
	// voisi ehkä muuttaa setFrameVisibility(boolean)
	public function setFramesVisible($boolean) {
		if ($boolean == true) 
		if ($this->buttonVerticalAlign==0) $this->buttonVerticalAlign = UIComponent::VALIGN_BOTTOM;
		$this->framesVisible = $boolean;
		if ($boolean) {
			$this->titleVisible = false;
		} else {
			$this->titleVisible = true;
		}
	}
	
	

	public function setDataSource($datasource, $variable = null) {
		$this->loadonopen = true;
		$this->datasource = $datasource;
		$this->datasourcevariable = $variable;
	}
	

	public function setMode($mode) {
		$this->mode = $mode;
		
		if ($mode == UIComponent::MODE_EDIT) {
			foreach($this->items as $index => $item) {
				$item->setEditActive(true);
			}
		}
		if ($mode == UIComponent::MODE_INSERT) {
			foreach($this->items as $index => $item) {
				$item->setEditActive(true);
			}
			$this->setOpen(true);
		}
		
		// custom edit, edit nappulan teksti 
		/*
		if($mode == UIComponent::MODE_EDIT) {
			
		}
		*/
	}	
	
	
	/*
	public function addButton($actiontype, $action, $label) {
		$button = new UIButton($label, $action);
		$this->buttons[] = $button;
	}
	*/
	
	
	public function addButton($button) {
		//$button = new UIButton($label, $action);
		$this->buttons[] = $button;
	}
	
	

	public function setWidth($width) {
		$this->sectionwidth = $width;
	}
	
	
	public function disableEdit() {
		$this->editable = false;
	}
	
	public abstract function setData($data);
	
	

	public function debugging() {
		return $this->debug;
	}
	

	public function setDebug($boolean) {
		$this->debug = $boolean;
	}
	
	
	public function editable($editable) {
		$this->editable = $editable;
	}
	
	

	public function isEditable() {
		return $this->editable;
	}
	
	
	public function getShowJSFunction() {
		return "showsection" . $this->getID() . "()";
	}
	
	
	public function getHideJSFunction() {
		return "hidesection" . $this->getID() . "()";
	}
	
	/**
	 * 
	 * 
	 * Tämä korvaa erilliset updateActionin ja setInsertAction, koska nämä ovat sama asia sectionissa ja insertsection tyyppisessä toiminnassa.
	 * 
	 * @param string $actiontype
	 * @param string $action
	 * @param string $updatevariable
	 * @param string $debug
	 */
	public function setSaveAction($actiontype, $action, $updatevariable = null) {
		$this->updateactiontype = $actiontype;
		$this->updateaction = $action;
		$this->updatevariable = $updatevariable;
	}
	
	
	public function setUpdateAction($actiontype, $action, $updatevariable, $debug = false) {
		$this->updateactiontype = $actiontype;
		$this->updateaction = $action;
		$this->updatevariable = $updatevariable;
		$this->setDebug($debug);
	}
	
	

	public function setInsertAction($actiontype, $action) {
		$this->insertactiontype = $actiontype;
		$this->insertaction = $action;
		
		//echo "<br>actiontype - " . $actiontype;
		//echo "<br>action - " . $action;
		
		$this->updateactiontype = $actiontype;
		$this->updateaction = $action;
	}
	

	/**
	 *
	 * @param string $deleteaction
	 * @param string $deletesuccessacion
	 * @param number $paramvariable
	 *
	 *
	 *
	 */
	public function setDeleteAction($actiontype, $deleteaction, $paramvariable = null) {
		$this->deleteactiontype = $actiontype;
		$this->deleteaction = $deleteaction;
		$this->deleteactionparam = $paramvariable;
	}
	
	
	
	public function setOpen($open) {
		$this->openinstart = $open;
	}
	
	
	
	public function onOpen($action, $target) {
		$this->contentloadurl = $target;
		$this->openfunction = "loadcontent" . $this->getID() . "()";
	}
	
	
	
	public function getTitle() {
		return $this->title;
	}
	


	/**
	 * Lineaction tyyypit:
	 *   - 1 - forwardUrl,
	 *   - 2 - custonjavascriptfunction (joka saa parametrinaan clikatun rivin itemin id-numeron)
	 *   - 3 - checkline (mahdollinen vain jos checkable on true)
	 *
	 * Huom: lineactionin paramvariable voi olla array, tämä toiminto voitaisiin lisätä myäs UITableen
	 * Huom: Tämä on oikeastaan tablemaisten elementtien ominaisuus, perussection ei tätä käytä
	 * 
	 */
	public function setLineAction($lineactiontype, $lineaction, $paramvariable = null, $actiontitle = null) {
		//echo "<br>paramvariable - " . $paramvariable;
		//echo "-";
		//echo "<br>actiontitle - " . $actiontitle;
		$this->lineactiontype = $lineactiontype;
		$this->lineaction = $lineaction;
		$this->lineactionparam = $paramvariable;
		if ($actiontitle == null) {
			//echo "<br>Actiontitle null";
			// TODO: actiontitle taitaa olla nyt tarpeeton, kun title saadaan noframes contentilta
			$this->actiontitle = "Uknownactiontitle";
		} else {
			$this->actiontitle = $actiontitle;
		}
	}
	
	

	public function generateLineActionUrlForHref($row) {
	
		$url = getUrl($this->lineaction);
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
			} else {
				$var = $this->lineactionparam;
				$url = $url . '&id=' . $row->$var;
			}
		}
		return $url;
	}
	
	
	public function generateLineActionUrl($row, $source = 0) {
	
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
				//$url = $url . '&id=' . $this->lineactionparam . '-' . $row[$this->lineactionparam];
				$url = $url . '&id=' . $row[$this->lineactionparam];
			} else {
				$var = $this->lineactionparam;
				$url = $url . '&id=' . $row->$var;
			}
		}
		return $url;
	}
	
	

	protected function generateLineaction($itemID, $row, $targetsectionID) {
	
		echo "<script>";
		switch($this->lineactiontype) {
			case UIComponent::ACTION_FORWARD :
				echo "  $('#" . $itemID . "').click(function () {";
				//echo "		alert('" . $this->generateLineActionUrl($row,12) . "');";
				//echo "		alert('lineaction first');";
				//echo "		console.log('--------------------- ccc');";
				echo "		loadpage('" . $this->generateLineActionUrl($row,13) . "','" . $this->actiontitle . "');";
				echo "	});";
				break;
			case UIComponent::ACTION_NEWWINDOW :
				echo "  $('#" . $itemID . "').click(function () {";
				echo "		console.log('opentab');";
				//echo "		opennewtab('" . $this->generateLineActionUrl($row,15) . "');";
				echo "	});";
				break;
			case UIComponent::ACTION_JAVASCRIPT:
				echo "  $('#" . $itemID . "').click(function () {";
				echo "		alert('lineaction LINEACTION JAVASCRIPT not implemented');";
				echo "	});";
				break;
			case UIComponent::ACTION_FORWARD_INDEX:
				echo "  $('#" . $itemID . "').click(function () {";
				echo "		loadpage('" . $this->generateLineActionUrl($row,14) . "','" . $this->actiontitle . "');";
				echo "	});";break;
			case UIComponent::ACTION_CHECK:
				echo "  $('#" . $itemID . "').click(function () {";
				echo "		alert('lineaction LINEACTION CHECK not implemented');";
				echo "	});";
				break;
			case UIComponent::ACTION_OPENDIALOG:
				echo "  $('#" . $itemID . "').click(function () {";
				
				echo "		console.log('lineacion open');";
				//echo "		console.log('lineaction OPENDIALOG - targetsection - " . $targetsectionID . "' );";
				//echo "		console.log('lineaction OPENDIALOG - itemscount - " . count($this->items) . "' );";
				echo "		var value = 0;";
				
				// TODO: Hieman on epäselvää, miksi tässä kysytään ko. komponentin Modea, eikö tässä 
				// pitäisi kysyä nimenomaan target-sectionin typeä? 
				// 07.12.2019, poistettu mode editin vertailu...
				//if ($this->mode == UIComponent::MODE_EDIT) {
					foreach($this->items as $index => $item) {
						//echo "		console.log('lineaction OPENDIALOG - item->getID - " .  $item->getID() . "' );";
						//echo "		console.log('item class - " .  get_class($item) . "' );";
						echo "  	valuexx = $('#" . $itemID . "-" . $item->getID() . "').val();";
							
						//echo "		console.log('mode - " . $this->mode . "');";
						//echo "		console.log('lineaction2 #" . $itemID . "-" . $item->getID() . " - " . get_class($item) . " - " .  $item->getVariable() . " ... '+valuexx);";
						if ($item instanceof UIButtonColumn) {
							// buttoneita ei oteta mukaan
						} else {
							echo "		setValue_" . $targetsectionID . "('" . $item->getVariable() . "',valuexx);";
						}
					}
					echo "  	$('#sectiondialog-" . $targetsectionID . "').dialog('open');";
				/*
				} else {
					echo "  	$('#sectiondialog-" . $targetsectionID . "').dialog('open');";
					if ($this->loadonopen == true) {
						//echo "		console.log('loadcontent');";
						echo "		loadcontent" . $this->getID() . "()";
					} else {
						//echo "		console.log('loadcontent false " . $this->getID() . "');";
					}
				}
				*/
				echo "	});";
				break;
			default :
				break;
		}
		echo "</script>";
	}
	
	
	protected function generateLineactionContent($itemID, $row, $targetsectionID) {
		switch($this->lineactiontype) {
			case UIComponent::ACTION_FORWARD :
				//echo "		console.log('---------------------aaa');";
				echo "		loadpage('" . $this->generateLineActionUrl($row,16) . "','" . $this->actiontitle . "');";
				break;
			case UIComponent::ACTION_NEWWINDOW :
				echo "		console.log('opentab');";
				break;
			case UIComponent::ACTION_JAVASCRIPT:
				echo "		alert('lineaction LINEACTION JAVASCRIPT not implemented');";
				break;
			case UIComponent::ACTION_FORWARD_INDEX:
				echo "		loadpage('" . $this->generateLineActionUrl($row,17) . "','" . $this->actiontitle . "');";
				break;
			case UIComponent::ACTION_CHECK:
				echo "		alert('lineaction LINEACTION CHECK not implemented');";
				break;
			case UIComponent::ACTION_OPENDIALOG:
				echo "		var value = 0;";
				foreach($this->items as $index => $item) {
					echo "  	valuexx = $('#" . $itemID . "-" . $item->getID() . "').val();";
					if ($item instanceof UIButtonColumn) {
						// buttoneita ei oteta mukaan
					} else {
						echo "		setValue_" . $targetsectionID . "('" . $item->getVariable() . "',valuexx);";
					}
				}
				echo "  	$('#sectiondialog-" . $targetsectionID . "').dialog('open');";
				break;
			default :
				break;
		}
	}
	
		
	

	private function generateDeleteActionUrl($row) {
	
		$url = $this->deleteaction;
		if (is_array($this->deleteactionparam)) {
			foreach ($this->deleteactionparam as $var => $urlparam) {
				if (is_array($row)) {
					$url = $url . '&' . $urlparam . "=" . $row[$var];
				} else {
					$url = $url . '&' . $urlparam . "=" . $row->$var;
				}
			}
		} else {
			if (is_array($row)) {
				$url = $url . '&id=' . $row[$this->deleteactionparam];
			} else {
				$var = $this->deleteactionparam;
				$url = $url . '&id=' . $row->$var;
			}
		}
		return $url;
	}
	
	
	
	protected function generateDeleteAction($itemID,$row) {
	
		echo "<script>";
		echo "  $('#" . $itemID . "').click(function () {";
		
		echo "		console.log('pressed - " . $itemID . "');";
		echo "		console.log('" . getUrl($this->generateDeleteActionUrl($row)) . "');";
		
		echo "		window.location = '" . getUrl($this->generateDeleteActionUrl($row)) . "';";
		echo "			window.event.stopPropagation();";
		echo "	});";
		echo "</script>";
	}
	
	
	protected function generateButtons() {
		if ($this->framesVisible == 0) {
			echo "	<table style='width:" . $this->sectionwidth . "'>";
		} else {
			echo "	<table style='width:100%'>";
		}
		echo "		<tr>";
		if ($this->framesVisible == false) {
			if ($this->titleVisible == true) {
				echo "			<td class=pagetitle style='font-size:24px;font-weight:bold;height:50px;vertical-align:top;'>";
				echo "" . $this->title;
				echo "			</td>";
			}
		} else {
			echo "			<td class=pagetitle style='font-size:24px;font-weight:bold;'>";
			echo "			</td>";
		}
		
		// TODO: tässä nappula menee hieman yli rajan
		// Siirretty section->generateContent
		/*
		echo "			<td style='text-align:right;vertical-align:bottom;'>";
		if (count($this->buttons) > 0) {
			foreach($this->buttons as  $index => $button) {
				$button->show();
				echo " ";
			}
		}
		echo "			</td>";
		*/
		echo "		</tr>";
		echo "	</table>";
	}
		
	
	protected function generateSectionHeader($action = null) {
	
		/*
			if ($this->openinstart == true) {
			echo "<br>Openinstart == true";
			} else {
			echo "<br>Openinstart == false";
			}
			*/
	
	
		//echo "<div class=section-spacerdiv style='backgroun-color:pink;'></div>";
	

		$state = getSectionState($this->getID());
		$open = false;
		if ($state == null) {
			$open = $this->openinstart;
			//echo "<br>sectionstate - NULL";
		} else {
			if ($state == 'Open') $open = true;
			//echo "<br>sectionstate - " . $state;
		}
		
		
		if ($open) {
			echo "<div class='section-header-closed' id=sectionheaderclose-".$this->getID()." onclick=\"javascript:showsection" . $this->getID() . "()\" style='display:none;'>";
		} else {
			echo "<div class='section-header-closed' id=sectionheaderclose-".$this->getID()." onclick=\"javascript:showsection" . $this->getID() . "()\" style='display:block;'>";
		}
		echo "		<table style='width:100%;'>";
		echo "			<tr>";
		echo "				<td class=section-title style='width:450px;'>".$this->title."<td>";
		echo "				<td style='text-align:right;'>";
		//echo "					<a class='section-header-button' href='#'>Avaa</a>";
		echo "				<button class=section-button-header style='widht:22px;height:22px;'><i class='fa fa-cog' ></i></button>";
		echo "				<button class=section-button-header style='widht:22px;height:22px;'><i class='fa fa-chevron-down'></i></button>";
	
		echo "				<td>";
		echo "		</table>";
		echo "</div>";
	
		// Muutin sektionin tyyliä siten, että width:605:px -> min-width:605px.
		
		
		if ($open) {
			echo "	<div  id=sectionheaderopen-".$this->getID()." class=section-header-open onclick=\"javascript:hidesection" . $this->getID() . "()\"  style='display:block;'>";
		} else {
			echo "	<div  id=sectionheaderopen-".$this->getID()." class=section-header-open onclick=\"javascript:hidesection" . $this->getID() . "()\" style='display:none;'>";
		}
		
		echo "		<table style='width:100%;'>";
		echo "			<tr>";
		echo "				<td class=section-title  style='width:450px;'>".$this->title."<td>";
		echo "				<td style='text-align:right;'>";
		echo "				<button class=section-button-header style='widht:22px;height:22px;'><i class='fa fa-cog' ></i></button>";
		echo "				<button class=section-button-header style='widht:22px;height:22px;'><i class='fa fa-chevron-up'></i></button>";
		//echo "					<a class='section-header-button' href='#'>Piilota</a>";
		echo "				<td>";
		echo "		</table>";
		echo "</div>";
	
		/*
			//echo "	<tr onclick=\"javascript:hidesection" . $this->getID() . "(".$this->getID().")\">";
			echo "		<div style='background-color:blue;float:left;width:70%;'>".$this->title."</div>";
			//echo "		<td id=sectiontitle-".$this->getID()." style='width:525px;'>".$this->title."</td>";
			echo "		<div style='text-align:right;float:right;background-color:green;width:30%'>";
			echo "			<a class='section-header-button' href='#'>Piilota</a>";
			echo "		</div>";
			//echo "		<td>";
			//echo "			<a href='#'>Piilota</a>";
			//echo "		</td>";
			echo "	</div>";
			*/
	
		echo "<script>";
		echo "		function hidesection" . $this->getID() . "() {";
		echo "			$.getJSON('" . getUrl('system/session/closesection') . "&json=1&id=" . $this->getID() . "','',function(reply) {";
		echo "			}); ";
		echo "			$('#sectionheaderclose-" . $this->getID() . "').show();";
		echo "			$('#sectionheaderopen-" . $this->getID() . "').hide();";
		echo "			$('#sectioncontent-".$this->getID()."').hide();";
		//echo "			editCancelPressed" . $this->getID() . "();";
		echo "		};";
		echo "	</script>";
		
		echo "<script>";
		echo "		function showsection" . $this->getID() . "() {";
		echo "			$.getJSON('" . getUrl('system/session/opensection') . "&json=1&id=" . $this->getID() . "','',function(reply) { ";
		//echo "				alert('closesection - '+reply);";
		echo "			}); ";
		echo "			$('#sectionheaderclose-" . $this->getID() . "').hide();";
		echo "			$('#sectionheaderopen-" . $this->getID() . "').show();";
		echo "			$('#sectioncontent-".$this->getID()."').show();";
		
		if ($this->loadonopen == true) {
			echo "		loadcontent" . $this->getID() . "()";
		}
				
		//echo "			alert('onopen');";
		//if ($action != null) {
		//	echo "			$('#contenttable-".$this->getID()."').load(\"" . getUrl($action) . "\");";
		//}
		echo "		};";
		echo "	</script>";
	
		if ($open) {
			echo "<div id=sectioncontent-".$this->getID()." class='section-content' style='display:block;'>";
		} else {
			echo "<div id=sectioncontent-".$this->getID()." class='section-content' style='display:none;'>";
		}
	
	
	}
	
	// frameworkin pitää hoitaa sectionin sulkeminen, koska editointi jossain muualla voi olla kesken, pitaa kutsua itempagen showsection funktiota
	
	
	protected abstract function generateContent();
	
	
	
	protected function generateFooter() {
	
		// TODO siirrä css:äään
		
		echo "</div>";
		echo "<div style='width:100%;height:6px;'></div>";
		
		/*
			echo "	<table class=section-header-closed style='width:100%;'>";
			echo "		<tr>";
			echo "			<td class=contentleft></td>";
			echo "			<td class=contentcell style=''>";
			echo "				<table class=contentinsidetable style='width:100%;text-align:right'>";
			echo "				</table>";
			echo "			</td>";
			echo "		</tr>";
			echo "	</table>";
			*/
		//echo "</div>";
	
	}
	
	
	public function startCustomContent() {
		$this->generateSectionHeader(null);
	}
	
	public function endCustomContent() {
		$this->generateFooter();
	}
	
	
	public function showLoader($action) {
		$this->generateSectionHeader($action);
		$this->generateFooter();
	}
	
	
	
	public function show() {
	
		echo "<div style='width:" . $this->sectionwidth  .";'>";
		$this->generateSectionHeader();
		$this->generateContent();
		$this->generateFooter();
		echo "</div>";
	
		return false;
	}
	
	
}


?>