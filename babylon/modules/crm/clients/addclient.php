<?php

echo "<br>Toteutus vanhentunut, toteuta insertsectionilla";


/*
echo "<table class=infotable style='width:600px'>";
echo "	<tr>";
echo "		<td class='infotablesectionheader' style='width:10px;'></td>";
echo "		<td class='infotablesectionheader' style='width:100px;'></td>";
echo "		<td class='infotablesectionheader' style=''></td>";
echo "		<td class='infotablesectionheader' style='width:20px;'></td>";
echo "	</tr>";

echo "	<tr>";
echo "		<th class='infotablesectionheader'></th>";
echo "		<th class='infotablesectionheader'>Lisaa asiakashenkilo</th>";
echo "		<th class='infotablesectionheader'></th>";
echo "		<td class='infotablesectionheader' style=''></td>";
echo "	</tr>";

//if ($this->registry->yritys)) $this->registry->yritys=0;
if (isset($this->registry->sukunimi) == null) $this->registry->sukunimi='';
if (isset($this->registry->etunimi)) $this->registry->etunimi='';
if (isset($this->registry->puhnro)) $this->registry->puhnro='';
if (isset($this->registry->email)) $this->registry->email='';

echo "	<tr>";
echo "		<td colspan=4 style='height:8px;'></td>";
echo "	</tr>";

echo "	<tr style=infotablesectionheader>";
echo "		<td></td>";
echo "		<td class=infotitle>Yritys</td>";
echo "		<td>";
echo "			<select id='yrityseditfield'>";
echo " 				<option value='0'></option>";
foreach ($this->registry->yritykset as $yritysID => $yritys) {
	if ($this->registry->yritys == $yritysID)
		echo " 		<option selected value='".$yritysID."'>".$yritys->nimi."</option>";
	else
		echo " 		<option value='".$yritysID."'>".$yritys->nimi."</option>";
}
echo "			</select>";
echo "		</td>";
echo "	</tr>";
echo "	<tr style=infotablesectionheader>";
echo "		<td></td>";
echo "		<td class=infotitle>Sukunimi</td>";
echo "		<td><input id=sukunimieditfield type='text' value='".$this->registry->sukunimi."'></td>";
echo "	</tr>";
echo "	<tr style=infotablesectionheader>";
echo "		<td></td>";
echo "		<td class=infotitle>Etuimi</td>";
echo "		<td><input id=etunimieditfield type='text' value='".$this->registry->etunimi."'></td>";
echo "	</tr>";
echo "	<tr style=infotablesectionheader>";
echo "		<td></td>";
echo "		<td class=infotitle>Puhelinumero</td>";
echo "		<td><input id=puhnroeditfield type='text' value='".$this->registry->puhnro."'></td>";
echo "	</tr>";
echo "	<tr style=infotablesectionheader>";
echo "		<td></td>";
echo "		<td class=infotitle>Sähkäposti</td>";
echo "		<td><input id=emaileditfield type='text' value='".$this->registry->email."'></td>";
echo "		<td style='text-align:right;padding-right:20px;'>";
echo "			<span style='white-space:nowrap'>";
echo "				<span class=petebutton id=tallennalinkki href='#'>Tallenna</span>";
echo "				<span class=petebutton id=peruutalinkki onclick='peruuta()' >Peruuta</span>";
echo "			</span>";
echo "		</td>";
echo "	</tr>";
echo "</table>";


echo "<script>";
echo "	function peruuta(){";
echo "		window.location='".getUrl('crm/clients/showclients')."';";
echo "	}";
echo "</script>";

echo "<script>";
echo "		$('#tallennalinkki').click(function (){";
echo "			var yritys=$('#yrityseditfield').val();";
echo "			var yritysnimi=$('#yrityseditfield option:selected').text();";
echo "			var sukunimi=$('#sukunimieditfield').val();";
echo "			var etunimi=$('#etunimieditfield').val();";
echo "			var puhnro=$('#puhnroeditfield').val();";
echo "			var email=$('#emaileditfield').val();";
echo "			if (sukunimi == '') {";
echo "				alert ('Nimi kentta ei voi olla tyhja.');";
echo " 		 		return false;";
echo "			}";
echo "			window.location='".getUrl('crm/clients/insertclient')."&yritys='+yritys+'&sukunimi='+sukunimi+'&etunimi='+etunimi+'&puhnro='+puhnro+'&email='+email;";
echo "		})";
echo "</script>";
*/

?>