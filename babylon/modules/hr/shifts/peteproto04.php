<?php



echo "<table>";

echo "	<tr>";
echo "		<td>Alkuaika</td>";
echo "		<td style='width:10px;'></td>";
echo "		<td>Aikaikkuna</td>";
echo "		<td style='width:10px;'></td>";
echo "		<td>Nakyma</td>";
echo "		<td style='width:10px;'></td>";
echo "		<td>Jaottelu</td>";
echo "		<td style='width:10px;'></td>";
echo "		<td>Varikoodi</td>";
echo "		<td style='width:10px;'></td>";
echo "		<td></td>";
echo "		<td></td>";
echo "	</tr>";

echo "	<tr>";

$currentdate = date('d.m.Y');
echo "		<td>";
echo "			<input id=startdate style='width:130px;height:34px;font-size:22px;text-align:center;' value=" . $currentdate . ">";
echo "			<script>																								";
echo "				$('#startdate').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});				";
echo "			</script>																								";
echo "		</td>";
echo "		<td style='width:10px;'></td>";
echo "		<td style='vertical-align:top;'>";
echo "			<button style='height:40px;width:30px;margin-top:0px;'><</button>";
echo "			<select id=startdate style='width:120px;height:40px;font-size:22px;text-align:center;'>";
echo "				<option value='1'>1 paiva</option>";
echo "				<option value='2'>2 paiva</option>";
echo "				<option value='3'>3 paiva</option>";
echo "				<option value='4'>4 paiva</option>";
echo "				<option value='5'>5 paiva</option>";
echo "				<option value='6'>5 paiva</option>";
echo "				<option value='7' selected>1 viikko</option>";
echo "				<option value='10'>10 paivaa</option>";
echo "				<option value='14'>2 viikkoa</option>";
echo "			</select>";
echo "			<button style='height:40px;width:30px;'>></button>";
echo "			<script>																								";
echo "				$('#startdate').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});				";
echo "			</script>																								";
echo "		</td>";
echo "		<td style='width:10px;'></td>";
echo "		<td>";
echo "			<div id='nakyma'>";
echo "				<input type='checkbox' id='nakyma2'><label for='nakyma2'>horizontal</label>";
echo "				<input type='checkbox'  id='nakyma1'><label for='nakyma1'>vertical</label>";
echo "			</div>";
echo "		</td>";
echo "		<td style='width:10px;'></td>";

echo "		<td>";
echo "			<div id='jaottelu'>";
echo "				<input type='checkbox'  id='jaottelu1'><label for='jaottelu1'>Tyakohde</label>";
echo "				<input type='checkbox' id='jaottelu2'><label for='jaottelu2'>Tyatehtava</label>";
echo "				<input type='checkbox' id='jaottelu3'><label for='jaottelu3'>Tyantekija</label>";
echo "			</div>";
echo "		</td>";
echo "		<td style='width:10px;'></td>";
echo "		<td>";
echo "			<div id='varikoodi'>";
echo "				<input type='checkbox' id='varikoodi1'><label for='varikoodi1'>Tyakohde</label>";
echo "				<input type='checkbox' id='varikoodi2'><label for='varikoodi2'>Tyatehtava</label>";
echo "				<input type='checkbox' id='varikoodi3'><label for='varikoodi3'>Tyantekija</label>";
echo "			</div>";
echo "		</td>";
echo "		<td style='width:10px;'></td>";
echo "		<td>";
echo "			<input id=startdate style='width:130px;height:34px;font-size:22px;text-align:center;' value='asetukset'>";
echo "		</td>";

echo "	</tr>";




echo "<style>";
echo "	.ui-button .ui-icon.verticalbutton { ";
echo "		background-image: url('/babylon/images/vertical2.png');";
echo "		background-size: 35px 35px;";
echo "		margin: -17px 0px 0px -17px;";
//echo "		float: right;";
echo "		width:35px;";
echo "		height:35px;";
//echo "		margin-top:1px;";
//echo "		border-collapse:collapse;";
echo "	}";
echo "</style>";



echo "<style>";
echo "	.ui-button .ui-icon.horizontalbutton { ";
echo "		background-image: url('/babylon/images/horizontal2.png');";
echo "		background-size: 35px 35px;";
echo "		margin: -17px 0px 0px -17px;";
//echo "		float: right;";
echo "		width:35px;";
echo "		height:35px;";
//echo "		margin-top:1px;";
//echo "		border-collapse:collapse;";
echo "	}";
echo "</style>";



echo "<style>";
echo "	.ui-button .ui-icon.tyatehtavabutton { ";
echo "		background-image: url('/babylon/images/task.png');";
echo "		background-size: 40px 40px;";
echo "		margin: -17px 0px 0px -17px;";
//echo "		float: right;";
echo "		width:35px;";
echo "		height:35px;";
//echo "		margin-top:1px;";
//echo "		border-collapse:collapse;";
echo "	}";
echo "</style>";


echo "<style>";
echo "	.ui-button .ui-icon.tyakohdebutton { ";
echo "		background-image: url('/babylon/images/project.png');";
echo "		background-size: 40px 40px;";
echo "		margin: -17px 0px 0px -17px;";
//echo "		float: right;";
echo "		width:35px;";
echo "		height:35px;";
//echo "		margin-top:1px;";
//echo "		border-collapse:collapse;";
echo "	}";
echo "</style>";


echo "<style>";
echo "	.ui-button .ui-icon.tyantekijabutton { ";
echo "		background-image: url('/babylon/images/person.png');";
echo "		background-size: 40px 40px;";
echo "		margin: -17px 0px 0px -17px;";
//echo "		float: right;";
echo "		width:35px;";
echo "		height:35px;";
//echo "		margin-top:1px;";
//echo "		border-collapse:collapse;";
echo "	}";
echo "</style>";

echo "<style>";
echo "	.ui-button  { ";
echo "		padding: 0px;";
echo "		margin: 0px;";
echo "		display: inline-block;";
echo "		width: 40px;";
echo "		height: 40px;";
//echo "		margin-top:1px;";
//echo "		border-collapse:collapse;";
echo "	}";
echo "</style>";



/*
echo "	icons: {";
echo "		primary: '" . getImageUrl("person.png") . "',";
//echo "		secondary: 'ui-icon-triangle-1-s'";
echo "	}";
echo "});";
*/
echo "<script>																																	";
echo "		$('#nakyma').buttonset();";
//echo "		$('#nakyma').css('background-image','/babylon/images/person.png');";
echo "		$('#jaottelu').buttonset();";
echo "		$('#varikoodi').buttonset();";
echo "</script>																																	";


echo "<script>																																	";
echo "$('#nakyma1').button({";
echo "	text: false,";
echo "	icons: {";
echo "		primary: 'verticalbutton'";
echo "	}});";
echo "</script>								";


echo "<script>																																	";
echo "$('#nakyma2').button({";
echo "	text: false,";
echo "	icons: {";
echo "		primary: 'horizontalbutton'";
echo "	}});";
echo "</script>								";





echo "<script>																																	";
echo "$('#varikoodi1').button({";
echo "	text: false,";
echo "	icons: {";
echo "		primary: 'tyakohdebutton'";
echo "	}});";
echo "</script>								";

echo "<script>																																	";
echo "$('#varikoodi2').button({";
echo "	text: false,";
echo "	icons: {";
echo "		primary: 'tyatehtavabutton'";
echo "	}});";
echo "</script>								";


echo "<script>																																	";
echo "$('#varikoodi3').button({";
echo "	text: false,";
echo "	icons: {";
echo "		primary: 'tyantekijabutton'";
echo "	}});";
echo "</script>								";





echo "<script>																																	";
echo "$('#jaottelu1').button({";
echo "	text: false,";
echo "	icons: {";
echo "		primary: 'tyakohdebutton'";
echo "	}});";
echo "</script>								";


echo "<script>																																	";
echo "$('#jaottelu2').button({";
echo "	text: false,";
echo "	icons: {";
echo "		primary: 'tyatehtavabutton'";
echo "	}});";
echo "</script>								";


echo "<script>																																	";
echo "$('#jaottelu3').button({";
echo "	text: false,";
echo "	icons: {";
echo "		primary: 'tyantekijabutton'";
echo "	}});";
echo "</script>								";


echo "</table>";



?>