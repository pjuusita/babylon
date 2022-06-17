<?php

	




if ($this->registry->errors != null) {
	foreach($this->registry->errors as $index => $errormessage) {
		echo "<table cellpadding='0' cellpadding='0'  style='width:800px;margin-bottom:5px;'>";
		echo "	<tr>";
		echo "		<td style='width:100%;'>";
		//echo "			<div class=errordiv id='sectionerrordiv-" . $this->getID() . "' style='display:none'></div>";
		echo "			<div class=errormessagediv style='border-width:2px;padding:10px 10px 10px 10px;'>";
		echo "" . $errormessage;
		echo "			</div>";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
	}
}



?>