<?php

class UIDynamicDiv {
	
	private $title;
	private $id;
	private $selection;
	private $subSeleciton;
	private $function;
	private $actionURL;
	private $redirectURL;
	private $customParameters;
	
	public function __construct($title,$id,$selection,$subSelection,$function,$actionURL,$redirectURL,$customParameters) {
		
		$this->title 			= $title;
		$this->id 				= $id;
		$this->selection 		= $selection;
		$this->subSelection 	= $subSelection;
		$this->function			= $function;
		$this->actionURL		= $actionURL;
		$this->redirectURL		= $redirectURL;
		$this->customParameters = $customParameters;
				
	}
	
	function createPrimarySelectionData() {
			
		$selection = $this->selection;
		$id		   = $this->id;
		$customParameters = $this->customParameters;
		
		echo "<script>																									";
		echo "																											";
		echo "	var element = document.getElementById('".$id."');														";
		echo "	console.log(element);																					";
		echo "	element.selection = [];																					";
		echo "	element.customParameters = '".$customParameters."';														";
		
		foreach($selection as $index => $item) {
				
			$dataVars = $item->getDataVariables();
				
			echo "	var dataRow = [];																					";
	
			foreach($dataVars as $varIndex => $var) {
	
				echo " dataRow['".$varIndex."'] = '".$item->$varIndex."';												";
	
			}

			echo "  console.log(dataRow);																				";
			echo "	element.selection.push(dataRow);																	";
				
		}
	
		echo "		console.log('Selection' + element.selection);														";
	
		echo "</script>																									";
	
	}
	
	function createSubSelectionData() {
		
		$subSelection	  = $this->subSelection;
		$id		   		  = $this->id;
		
		echo "<script>																									";
		echo "																											";
		echo "	var element = document.getElementById('".$id."');														";
		echo "	console.log(element);																					";
		echo "	element.subSelection = [];																				";
		
		foreach($subSelection as $index => $item) {
		
			$dataVars = $item->getDataVariables();
		
			echo "	var dataRow = [];																					";
		
			foreach($dataVars as $varIndex => $var) {
		
				echo " dataRow['".$varIndex."'] = '".$item->$varIndex."';												";
		
			}
		
			echo "  console.log(dataRow);																				";
			echo "	element.subSelection.push(dataRow);																	";
		
		}
		
		echo "		console.log('subSelection ' + element.subSelection + ' count=' + element.subSelection.length);		";
		
		echo "</script>																									";
		
		}
	
	public function show() {
		
		$id = $this->id;
		
		echo "<br><br>";
		echo "<div id='".$id."'>";
		
		echo "</div>";
		
		$this->createPrimarySelectionData();
		$this->createSubSelectionData();
		$actionurl 	  = getUrl($this->actionURL);
		$redirecturl  = getUrl($this->redirectURL);
		//$redirecturl  = getUrl("hr/employees/chooseemployeewage");
		$function = $this->function;

		echo "<br><br><button onclick=\"".$function."('".$actionurl."','".$redirecturl."')\">Tallenna</button>";
	}
	
}

?>