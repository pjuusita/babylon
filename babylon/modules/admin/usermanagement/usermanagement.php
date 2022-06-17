<?php

//echo "<br>Modulecount - " . count($registry->modules);

echo "<table  cellspacing='0' cellpadding='0' style='width:600px;border-spacing:0px;border-collapse:collapse;'>";
echo "<tr>";

echo "	<td>";
if ($registry->view == 1) {
	echo "<div onclick=\"openview(1)\" class='header-selection-left-selected'>";
	echo $module->name;
	echo "</div>";
} else {
	echo "<div onclick=\"openview(1)\"  class='header-selection-left'>";
	echo $module->name;
	echo "</div>";
}

if ($registry->view == 2) {
	echo "<div onclick=\"openview(2)\"  class='header-selection-center-selected'>";
	echo $module->name;
	echo "</div>";
} else {
	echo "<div onclick=\"openview(2)\"  class='header-selection-center'>";
	echo $module->name;
	echo "</div>";
}

if ($registry->view == 3) {
	echo "<div onclick=\"openview(3)\"  class='header-selection-right-selected'>";
	echo $module->name;
	echo "</div>";
} else {
	echo "<div onclick=\"openview(3)\"  class='header-selection-right'>";
	echo $module->name;
	echo "</div>";
}
echo "	</td>";
echo "	</table>";


echo "<script>";
echo "	function openview(viewID) {";
echo "			var url = '" .  getUrl("admin/usermanagement/showusers") . "&settingsmoduleID='+moduleID;";
echo "			console.log('ulr - '+url);";
echo "			window.location = url;";
//echo "		$('#sectiondialog-" . $this->getID() . "').dialog({ open: function(event,ui) { " . $openfunctionstring . " } , modal:true, autoOpen: false, width: \"" . $this->sectionwidth . "\"});";
echo "	}";
echo "</script>";
	

if ($registry->selectedmoduleID == 0) {
	include "companysettings.php";
} else {
	$settingscontroller = $registry->module->generateSettingsView($registry);	
}



?>