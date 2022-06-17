<?php


class UIPaginator extends UIComponent {

	private $rowsperpage;
	private $currentpage;
	private $totalrowcount;
	private $maxbuttoncount;
	private $pageAction;
	
	
	public function __construct($currentpage, $rowsperpage, $totalrowcount, $pageAction){
		parent::__construct();
		$this->currentpage 		= $currentpage;
		$this->rowsperpage		= $rowsperpage;
		$this->totalrowcount 	= $totalrowcount;
		$this->pageAction = $pageAction;		
		$this->maxbuttoncount = 5;
	}
	
	
	public function setPageAction($pageAction) {
		$this->pageAction = $pageAction;
	}

	
	public function show() {
		
		// total rows pitää paikkansa (ainakin concepts-controllerilta tulevat)
		// currentpage pitää paikkansa.
		
		$pagecount = ceil($this->totalrowcount / $this->rowsperpage);
		$firstpage = 1;
		$lastpage = $this->maxbuttoncount;
		
		$previouspage = $firstpage;
		
		$nextpage = $this->currentpage + 1;
		
		
		$lastpage = $this->currentpage + floor(($this->maxbuttoncount-1) / 2);
			
		
		if ($pagecount < $this->maxbuttoncount) {
			//echo "<br>overmax";
			$lastpage = $pagecount;
		} else {
			$firstpage = $this->currentpage - floor(($this->maxbuttoncount-1) / 2);
			$lastpage = $this->currentpage + floor(($this->maxbuttoncount-1) / 2);	
			//echo "<br>firstpagexx - " . $firstpage;
			//echo "<br>lastpage - " . $lastpage;
			
			if ($lastpage < $this->maxbuttoncount) $lastpage = $this->maxbuttoncount;
			
			if ($lastpage > $pagecount) {
				$firstpage = $firstpage - ($lastpage - $pagecount);
				$lastpage = $pagecount;
				//echo "<br>Lastpage over max - " . $firstpage . " - " . $lastpage;
			}
			if ($firstpage < 1) {
				$firstpage = 1;
				$previouspage = 1;
			}
		}
		
		//echo "<br>rowsperpage - " . $this->rowsperpage;
		//echo "<br>currentpage - " . $this->currentpage;
		//echo "<br>pagecount - " . $pagecount;
		//echo "<br>firstpage - " . $firstpage;
		//echo "<br>lastpage - " . $lastpage;
		//echo "<br>maxbuttoncount - " . $this->maxbuttoncount;
		//echo "<br>half - " . floor(($this->maxbuttoncount-1) / 2);

		$width = "30";
		$height = "30";
		$numberwidth = "30";
		$perpagewidth = "30";
		if ($lastpage > 10) $numberwidth = "34";
		if ($lastpage > 99) $numberwidth = "50";
		if ($lastpage > 1000) $numberwidth = "80";
		
		if ($this->rowsperpage > 10) $perpagewidth = "34";
		if ($this->rowsperpage > 99) $perpagewidth = "40";
		if ($this->rowsperpage > 1000) $perpagewidth = "80";
		
		
		if ($lastpage < 100) $rowsperpagestep = "5";
		if ($lastpage > 100) $rowsperpagestep = "10";
		if ($lastpage > 300) $rowsperpagestep = "20";
		if ($lastpage > 500) $rowsperpagestep = "25";
		if ($lastpage > 1000) $rowsperpagestep = "100";
		if ($lastpage > 10000) $rowsperpagestep = "250";
		
		echo "Page - " . $this->currentpage . "/" . $pagecount . " (" . $this->rowsperpage . " per page, totalrows: " . $this->totalrowcount . ")";
		
		echo "<br>";
		echo "<button  class=section-button style='margin-right:1px;width:50px;height:" . $height . "px;'  OnClick=\"window.location = '" . getUrl($this->pageAction) . "&page=1'\" id='productaddbutton'>first</button>";
		echo "<button  class=section-button style='margin-right:1px;width:50px;height:" . $height . "px;'  OnClick=\"window.location = '" . getUrl($this->pageAction) . "&page=" . $previouspage . "'\" id='productaddbutton'>back</button>";
		echo "<button  class=section-button style='margin-right:1px;width:50px;height:" . $height . "px;' OnClick=\"window.location = '" . getUrl($this->pageAction) . "&page=" . $nextpage . "'\" id='productaddbutton'>next</button>";
		echo "<button  class=section-button style='margin-right:1px;width:50px;height:" . $height . "px;' OnClick=\"window.location = '" . getUrl($this->pageAction) . "&page=" . $nextpage . "'\" id='productaddbutton'>last</button>";
		
		/*
		echo "<div id=pagecontent-".$this->getID() . " style='display:block;'>";
		echo "<table cellpadding=0><tr>";
		echo "<td><a href='" . getUrl($this->pageAction) . "&page=1' style='text-decoration:none'><div>";
		echo "<button  class=section-button style='margin-right:1px;width:" . $width . "px;height:" . $height . "px;' OnClick=\"window.location = '" . getUrl($this->pageAction) . "&page=1'\" id='productaddbutton'><i class='fa fa-fast-backward'></i></button>";
		echo "</a></td>";
		echo "<td><a href='" . getUrl($this->pageAction) . "&page=" . $previouspage . "' style='text-decoration:none'><div>";
		echo "<button  class=section-button style='margin-right:1px;width:" . $width . "px;height:" . $height . "px;'  OnClick=\"window.location = '" . getUrl($this->pageAction) . "&page=" . $previouspage . "'\" id='productaddbutton'><i class='fa fa-step-backward'></i></button>";
		echo "</a></td>";
		
		// jos sivujen määrä nousee liian suureksi, tämän voisi muuttaa dropdown boxiksi
		$lastpage++;
		for($pageindex = $firstpage; $pageindex < $lastpage; $pageindex++) {
			if ($pageindex == $this->currentpage) $class = "section-button-pressed disabled";
			else $class = "section-button";
			echo "<td><a href='" . getUrl($this->pageAction) . "&page=" . $pageindex . "' style='text-decoration:none'><div>";
			echo "<button  class=" . $class . " style='margin-right:1px;width:" . $numberwidth . "px;height:" . $height . "px;'  OnClick=\"window.location = '" . getUrl($this->pageAction) . "&page=" . $pageindex . "'\" id='productaddbutton'>" . $pageindex . "</button>";
			echo "</a></td>";
		}
		
		echo "<td><a href='" . getUrl($this->pageAction) . "&page=" . $nextpage . "' style='text-decoration:none'><div>";
		echo "<button  class=section-button style='margin-right:1px;width:" . $width . "px;height:" . $height . "px;' OnClick=\"window.location = '" . getUrl($this->pageAction) . "&page=" . $nextpage . "'\" id='productaddbutton'><i class='fa fa-step-forward'></i></button>";
		echo "</a></td>";
		echo "<td><a href='" . getUrl($this->pageAction) . "&page=" .  ($lastpage-1) . "' style='text-decoration:none'><div>";
		echo "<button  class=section-button style='margin-right:5px;width:" . $width . "px;height:" . $height . "px;' OnClick=\"window.location = '" . getUrl($this->pageAction) . "&page=" . ($lastpage-1) . "'\" id='productaddbutton'><i class='fa fa-fast-forward'></i></button>";
		echo "</a></td>";
		
		//echo "<br>rowsperpagestep = " . $rowsperpagestep;
		//echo "<br>rowsperpagestep = " . $totalrowcount;
		
		echo "<td>";
		echo "<select id=perpageselector-". $this->getID() ." class=field-select style='height:" . $height . "px;width:" . (14 + $perpagewidth) . "px'>";
		if ($this->rowsperpage > $this->totalrowcount) $looprowcount = $this->rowsperpage+1;
		else $looprowcount = $this->totalrowcount+$stepindex+1;
		
		
		for($stepindex = $rowsperpagestep; $stepindex < $looprowcount; $stepindex=$stepindex+$rowsperpagestep) {
			if ($this->rowsperpage == $stepindex) {
				$found = true;
				echo "<option value='" . $stepindex . "' selected>" . $stepindex . "</option>";
			} else {
				if (($stepindex > $this->rowsperpage) && ($found == false)) {
					echo "<option value='" . $this->rowsperpage . "' selected>" . $this->rowsperpage . "</option>";
				} 
				echo "<option value='" . $stepindex . "'>" . $stepindex . "</option>";
			}			
		}
		echo "</select>";
		echo "</td>";
		echo "</tr></table>";
		
		echo "	<script>";
		echo "		$('#perpageselector-". $this->getID() ."').change(function() {";
		echo "			var value = $(this).val();";
		//echo "			alert('jee - '+value);";
		echo "			window.location = '" . getUrl($this->pageAction) . "&rowsperpage='+value";
		echo "		})";
		echo "	</script>";
		
		
		echo "</div>";
		*/
	}
}
?>