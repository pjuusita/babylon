<?php


class UICustomSelectField  {
	
	private $title;
	private $id;
	private $selection;
	private $valueVar;
	private $textVar;
	private $creationFunction;
	private $updateFunction;
	private $customParameters;
	 
	public function __construct($title,$id,$selection,$creationFunction,$updateFunction,$customParameters) {
	
		$this->title 			= $title;
		$this->id				= $id;
		$this->selection 		= $selection;
		$this->creationFunction = $creationFunction;
		$this->updateFunction 	= $updateFunction;
		$this->customParameters = $customParameters;
		
	}
	
	function createJSData() {
			
		$selection 			= $this->selection;
		$id		   			= $this->id;
		$customParameters 	= $this->customParameters;
		
		//echo "CustomParameters = ".$customParameters;
		
		echo "<script>																									";
		echo "																											";
		echo "	var element = document.getElementById('".$id."');														";
		echo "	console.log(element);																					";
		echo "	element.selection = [];																					";
		echo "	element.customParameters = '".$customParameters."';														";

		foreach($selection as $index => $item) {
			
		$dataVars = $item->getDataVariables();
			
		echo "	var dataRow = [];																						";
		
		foreach($dataVars as $varIndex => $var) {
		
		echo " dataRow['".$varIndex."'] = '".$item->$varIndex."';														";
		
		}
			
		echo "  console.log(dataRow);																					";
		echo "	element.selection.push(dataRow);																		";
			
		}
		
		echo "		console.log(element.selection);																		";
		
		echo "</script>																									";
		
	}
	
	function createRoot() {
		
		$id = $this->id;
		$updatefunction = $this->updateFunction;
		
		echo "<br>";
		echo "<table>";
		echo "<td style='width : 100px'>";
		echo $this->title;
		echo "</td>";
		echo "<td><select id='".$id."' onchange=\"".$updatefunction."('".$id."')\">";
		echo "</select>";
		echo "</td>";
		echo "</table>";
			
	}
 	
	function show($data=null) {
		
		$this->createRoot();
		$this->createJSData();
		
		echo "<script>".$this->creationFunction."('".$this->id."');</script>";
		
	}
}

?>
