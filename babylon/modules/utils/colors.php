<?php

function generateColorJSFunctions($colors) {
	
	echo "<script>																																		";
	echo "	function jscolor(name,normal,light,dark) {																									";
	echo "																																				";
	echo "		this.reserved   = false;																												";
	echo "		this.name   	= name;										 																			";
	echo "		this.normal 	= '#'+normal;																											";
	echo "		this.light		= '#'+light;																											";
	echo "		this.dark		= '#'+dark;																												";
	echo "																																				";
	echo "	}																																			";
	echo "																																				";
	echo "</script>																																		";

//*******************************************************************************************************************************************************
//***
//***
//*******************************************************************************************************************************************************
	
	echo "<script>																																		";
	echo "																																				";
	echo "		function colorVariables()	{																											";
	echo "			this.id = 'colorVariables';																											";
	echo "		}																																		";
	echo "																																				";
	echo "	var colorVariables = new colorVariables();																									";
	echo " 	colorVariables.colors 			= [];																										";
	echo " 	colorVariables.colorsIntIndexed = [];																										";
	
	foreach($colors as $colorIndex => $color) {

		$name		= $colors[$colorIndex]->name;
		$normal 	= $colors[$colorIndex]->normal;
		$light 		= $colors[$colorIndex]->light;
		$dark 		= $colors[$colorIndex]->dark;

		echo "	var newcolor = new jscolor('".$name."','".$normal."','".$light."','".$dark."');															";
		echo "	colorVariables.colors['".$name."'] = newcolor;																							";
		echo "	colorVariables.colorsIntIndexed.push(newcolor);																							";
		
	}

	echo "</script>																																		";
	
//*******************************************************************************************************************************************************
//***
//***
//*******************************************************************************************************************************************************
	
	echo "<script>																																		";
	echo "																																				";
	echo "	function getJSColors() {																														";
	echo "																																				";
	echo "		return colorVariables;																													";
	echo "																																				";
	echo "	}																																			";
	echo "																																				";
	echo "</script>																																		";
		
//*******************************************************************************************************************************************************
//***
//***
//*******************************************************************************************************************************************************
	
	echo "<script>																																		";
	echo "		function getJSColor(colorName) {																										";
	echo "																																				";
	echo "																																			";
	echo "																																					";
	echo "	}																																		";
	echo "</script>																																		";

//*******************************************************************************************************************************************************
//***
//***
//*******************************************************************************************************************************************************

	echo "<script>																																		";
	echo "		function getJSSetColor(colorset, item) {																								";
	echo "		}																																		";
	echo "</script>																																		";



}









?>