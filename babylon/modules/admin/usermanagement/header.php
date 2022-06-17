<?php


echo "<table  cellspacing='0' cellpadding='0' style='margin-bottom:20px;width:600px;border-spacing:0px;border-collapse:collapse;'>";
echo "<tr>";
echo "	<div style='width:600px;display: table;table-layout: fixed;border-spacing:0;'>";

$stylestring = "margin:0px;display: table-cell;";

if ($registry->viewID == 1) {
	echo "<div onclick=\"openview(1)\" class='header-selection-left-selected' style='" . $stylestring . "'>";
	echo "Käyttäjät";
	echo "</div>";
} else {
	echo "<div onclick=\"openview(1)\"  class='header-selection-left' style='" . $stylestring . "'>";
	echo "Käyttäjät";
	echo "</div>";
}

if ($registry->viewID == 2) {
	echo "<div onclick=\"openview(2)\"  class='header-selection-center-selected' style='" . $stylestring . "'>";
	echo "Käyttäryhmät";
	echo "</div>";
} else {
	echo "<div onclick=\"openview(2)\"  class='header-selection-center' style='" . $stylestring . "'>";
	echo "Käyttäryhmät";
	echo "</div>";
}

if ($registry->viewID == 3) {
	echo "<div onclick=\"openview(3)\"  class='header-selection-right-selected' style='" . $stylestring . "'>";
	echo "Tiimit";
	echo "</div>";
} else {
	echo "<div onclick=\"openview(3)\"  class='header-selection-right' style='" . $stylestring . "'>";
	echo "Tiimit";
	echo "</div>";
}
echo "	</tr>";
echo "	</table>";


/*
echo "<table  cellspacing='0' cellpadding='0' style='width:600px;border-spacing:0px;border-collapse:collapse;'>";
echo "<tr>";
//echo "	<div style='width:600px; display: inline-block;box-sizing: border-box;'>";
if ($registry->view == 1) {
	echo "<td>";
	echo "<div onclick=\"openview(1)\" class='header-selection-left-selected' style='width:100%;float:left;'>";
	echo "Käyttäjät";
	echo "</div>";
	echo "</td>";
} else {
	echo "<td>";
	echo "<div onclick=\"openview(1)\"  class='header-selection-left' style='width:100%;float:left;'>";
	echo "Käyttäjät";
	echo "</div>";
	echo "</td>";
}

if ($registry->view == 2) {
	echo "<td>";
	echo "<div onclick=\"openview(2)\"  class='header-selection-center-selected' style='width:100%;float:left;'>";
	echo "Käyttäryhmät";
	echo "</div>";
	echo "</td>";
} else {
	echo "<td>";
	echo "<div onclick=\"openview(2)\"  class='header-selection-center' style='width:100%;float:left;'>";
	echo "Käyttäryhmät";
	echo "</div>";
	echo "</td>";
}

if ($registry->view == 3) {
	echo "<td>";
	echo "<div onclick=\"openview(3)\"  class='header-selection-right-selected' style='width:100%;float:left;'>";
	echo "Tiimit";
	echo "</div>";
	echo "</td>";
} else {
	echo "<td>";
	echo "<div onclick=\"openview(3)\"  class='header-selection-right' style='width:100%;float:left;'>";
	echo "Tiimit";
	echo "</div>";
	echo "</td>";
}
echo "	</tr>";
echo "	</table>";
*/

echo "<script>";
echo "	function openview(viewID) {";
echo "			var url = '" .  getUrl("admin/usermanagement/showmanagement") . "&viewID='+viewID;";
echo "			console.log('ulr - '+url);";
echo "			window.location = url;";
//echo "		$('#sectiondialog-" . $this->getID() . "').dialog({ open: function(event,ui) { " . $openfunctionstring . " } , modal:true, autoOpen: false, width: \"" . $this->sectionwidth . "\"});";
echo "	}";
echo "</script>";

?>