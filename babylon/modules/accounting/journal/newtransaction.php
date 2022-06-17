<?php

// Taa voidaan poistaa, toiminta kopioitu newentry.php -tiedostoon

echo "<div style='width:700px;background-color:#F8F8F8;border:0px ;'>";

echo "<form id='addform' action='".getUrl('taloushallinto/kirjanpito/addtodbtapahtuma')."' method='get'>";
echo "<input type='hidden' name='rt' value='taloushallinto/kirjanpito/addtodbtapahtuma'>";
echo "<fieldset>";
echo "<legend>Uusi tapahtuma</legend>";
echo "<table>";
echo "	<tr>";
echo "		<td style='width:100px;'>Paivamaara:</td>";
echo "		<td style='width:300px;'><input type='text' id='eventdate' onChange='dateselected()' name='eventdate' value=''></td>";
echo "		<td id='dateerror' style='color:red;padding-left:10px'></td>";
echo "	</tr>";

echo "<script>";
echo "	$('#eventdate').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
echo "</script>";
echo "<script>";
echo "	function dateselected() {";
echo "		$('#dateerror').text('');";
echo "	}";
echo "</script>";

echo "	<tr>";
echo "		<td style='width:100px'>Lahdetili:</td>";
echo "		<td style='width:300px'>";
echo "			<select style='width:300px' id='sourceaccount' onChange='sourceselected()' name='sourceaccount'>";
echo " 			<option value='0'></option>";
foreach ($this->registry->tilikartta as $tilikarttaID => $tilikartta) {
	palautatilit($tilikartta,0,'');
}
echo "			</select>";
echo "			<td id='sourceerror' style='color:red;padding-left:10px'></td>";
echo "		</td>";
echo "	</tr>";

echo "<script>";
echo "	function sourceselected() {";
echo "		$('#sourceerror').text('');";
echo "	}";
echo "</script>";

echo "	<tr>";
echo "		<td style='width:100px'>Kohdetili:</td>";
echo "		<td style='width:300px'>";
echo "			<select style='width:300px' id='targetaccount' onChange='targetselected()' name='targetaccount'>";
echo " 			<option value='0'></option>";
foreach ($this->registry->tilikartta as $tilikarttaID => $tilikartta) {
	palautatilit($tilikartta,0,'');
}
echo "			</select>";
echo "			<td id='targeterror' style='color:red;padding-left:10px'></td>";
echo "		</td>";
echo "	</tr>";

echo "<script>";
echo "	function targetselected() {";
echo "		$('#targeterror').text('');";
echo "	}";
echo "</script>";


echo "<script>";
echo "$('#sourceaccount').chosen({});";
echo "</script>";
echo "<script>";
echo "$('#targetaccount').chosen({});";
echo "</script>";

function palautatilit($tilikartta,$selectedtilikartta,$sisennys) {
	if ($selectedtilikartta == $tilikartta->getID()) {
		echo " 			<option selected value='".$tilikartta->getID()."'>".$sisennys.$tilikartta->name."</option>";
	} elseif ($tilikartta->getChildCount() == 0) {
		echo " 			<option value='".$tilikartta->getID()."'>".$sisennys.$tilikartta->name."</option>";
	} 	else {
		echo " 			<option value='".$tilikartta->getID()."'>".$sisennys.$tilikartta->name."</option>";
	}
	if ($tilikartta->getChildCount() > 0) {
		foreach ($tilikartta->getChilds() as $childtilikarttaID => $childtilikartta) {
			palautatilit($childtilikartta,$selectedtilikartta,$sisennys.'- ');
		}
	}

}

echo "	<tr>";
echo "		<td style='width:100px;'>Maara:</td>";
echo "		<td><input type='text' style='text-align:right;width:100px;padding-right:3px' id='amount' name='amount' value=''><td id='amounterror' style='color:red;padding-left:10px'></td></td>";
echo "	</tr>";

echo "<script>";
echo "$('#amount').blur(function(){";
echo "	var amount=$('#amount').val();";
echo "	if (/^[0-9]+[.|,]?[0-9]*$/.test(amount)) {";
echo "		$('#amounterror').text('');";
echo "	} else {";
echo "		$('#amounterror').text('Syata numero');";
echo "	}";
//echo "alert('sdf');";
echo "});";
echo "</script>";

echo "	<tr>";
echo "		<td colspan=2><input onClick='submitbutton()' type='button' value='tallenna'></td>";
echo "	</tr>";

echo "<script>";
echo "function submitbutton() {";
//echo "		alert('submitbutton');";
//echo "		return false;";
echo "	var success=true;";
echo "	if ($('#eventdate').val() == 0) {";
echo "		$('#dateerror').text('valitse paivamaara');";
echo "		success=false;";
echo "	}";
echo "	if ($('#sourceaccount').val() == 0) {";
echo "		$('#sourceerror').text('valitse lahdetili');";
echo "		success=false;";
echo "	}";
echo "	if ($('#targetaccount').val() == 0) {";
echo "		$('#targeterror').text('valitse kohdetili');";
echo "		success=false;";
echo "	}";
echo "	var amount=$('#amount').val();";
echo "	if (!/^-?[0-9]+[.|,]?[0-9]*$/.test(amount)) {";
echo "		$('#amounterror').text('Syata numero');";
echo "		success=false;";
echo "	}";
echo "	if (success) {";
echo "		$('#addform').submit();";
echo "	} else {";
echo "		return false;";
echo "	}";
echo "}";
echo "</script>";

echo "</table>";
echo "</fieldset>";
echo "</form>";

echo "</div>";

?>