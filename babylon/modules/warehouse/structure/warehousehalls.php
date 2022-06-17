<?php




echo "<table class='yritystaulu' style='widht:800px;'>";
echo "	<tr class='otsikkorivi'>";
echo "		<th>Nimi</th>";
echo "		<th>Lyhenne</th>";
echo "		<th>Koko</th>";
echo "		<th>Osoite</th>";
echo "	</tr>";
foreach($this->registry->halls as $index => $hall) {
	echo "	<tr class='tavallinenrivi'>";
	echo "		<td>" . $hall->name . "</td>";
	echo "		<td>" . $hall->abbreviation . "</td>";
	echo "		<td>" . $hall->width ."x".$hall->length . "x" . $hall->height . "</td>";
	echo "		<td>" . $hall->streetaddress . "</td>";
	echo "	</tr>";
}
echo "</table>";


echo "<br><div id='divmessage' class='petebutton'>new message</div>";


echo "<script>";
echo "	function suljemessage() {";
echo "		$('#jeehider').hide();";
echo "	}";
echo "</script>";

echo "<script>";
echo "$('#divmessage').click(function (){";
echo "		$('#topbody').prepend(";

//echo "			<table id=hider style=\"width:100%;height:100%;background-color:pink;\"><tr><td style=\"width:50%\"></td><td>message</td><td style=\"height:50%\"></td></tr></table>');";

echo "			'<div id=jeehider style=\"display:none;height:100%;width:100%;background-color:rgba(0,0,0,0.3);position:absolute;top:0%;left:0%;z-index:99;\">";
echo "				<div style=\"display:table;position:absolute:width:100%;height:100%;margin-left:auto;margin-right:auto;\">";
echo "					<div id=\"jeee\" style=\"display:table-cell;vertical-align:middle;\">";
echo "						<div style=\"margin-left:auto;margin-right:auto;width:400px;background-color:white;border: 2px solid black;\">";
echo "							<table style=\"width:100%;\"><tr><td>This is message.<td></tr><tr><td style=\"height:4px;border-top: 1px solid darkgrey\"></td></tr>";
echo "							<tr><td style=\"margin-right:10px;\"><div style=\"float:right\" onclick=\"suljemessage()\" class=petebutton >OK</div></td></tr><tr><td style=\"height:4px;\"></td></tr></table>";
echo "						</div>";
echo "					</div>";
echo "				</div>";
echo "			</div>');";

/*
echo "			'<div id=jeehider style=\"display:none;height:100%;width:100%;background-color:rgba(0,0,0,0.3);position:absolute;top:0%;left:0%;z-index:99;\">";
echo "				<div id=\"jeee\" style=\"display:table-cell;vertical-align:middle;z-index:100;background-color:white;\">";
echo "					<div style=\"margin-left:auto;margin-right:auto;width:400px;\">";
echo "						<table style=\"width:100%;\"><tr><td>This is message.<td></tr><tr><td style=\"height:4px;border-top: 1px solid darkgrey\"></td></tr>";
echo "						<tr><td style=\"margin-right:10px;\"><div style=\"float:right\" onclick=\"suljemessage()\" class=petebutton >OK</div></td></tr><tr><td style=\"height:4px;\"></td></tr></table>";
echo "					</div>";
echo "				</div>";
echo "			</div>');";
*/



//echo "		$('#hider').prepend('<div id=\"popup_box\" style=\"display:block;\">This is errormessage.</div>');";
//echo "		$('#jeehider').show();";
//echo "		$('#popup_box').show();";
//echo "});";
//echo "</script>";

/*
 * 
 * 
echo "<script>";
echo "$('#divmessage').click(function (){";
echo "		$('#topbody').prepend(";
echo "			'<div id=\"hider\" style=\"display:none;\">";
echo "				<table id=\"jeee\" style=\"width:400px;backgroun-color:white;height:100px;top:50%;height:150px;left:50%;borderposition:absolute;opacity:100;z-index:100;\">";
echo "					<tr>";
echo "						<td></td>";
echo "						<td>This is message</td>";
echo "						<td></td>";
echo "					</tr>";
echo "			</div>');";
//echo "		$('#hider').prepend('<div id=\"popup_box\" style=\"display:block;\">This is errormessage.</div>');";
echo "		$('#hider').show();";
//echo "		$('#popup_box').show();";
echo "});";
echo "</script>";
*/


/*
echo "<div id='popup_box'>";
echo "Message<br/>";
echo "<a id='buttonClose'>Close</a>";
echo "</div>";
echo "<div id='content'>";
echo "Pages main content.<br />";
echo "<a id='showpopup'>ClickMe</a>";
echo "</div>";

echo "<script>";
echo "$('#divmessage').click(function (){";
echo "		alert('jeejee');";
echo "});";
echo "</script>";
*/

/*
echo "<script>";
echo "$(document).ready(function () {";
echo "	alert('loaded');";
echo "	$('#hider').hide();";
echo "	$('#popup_box').hide();";
echo "	$('#showpopup').click(function () {";
echo "		$('#hider').fadeIn('slow');";
echo "		$('#popup_box').fadeIn('slow');";
echo "	});";
echo "	$('#buttonClose').click(function () {";
echo "		$('#hider').fadeOut('slow');";
echo "		$('#popup_box').fadeOut('slow');";
echo "	});";
echo "});";
*/


?>