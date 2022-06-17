<?php


echo "<table style='width:700px;'>";
echo "	<tr>";
echo "		<td style='width:100%;text-align:right;'>";
//$field = new UISelectField("","rowID","name", $this->registry->selection, "name");
//$field = new UISelectField("Käyttäjä","userID","UserID", $registry->users, "username");
//$field->show($this->registry->selectedindex);

echo "<select id=periodselectfield class='field-select' style='width:120px;margin-right:5px;'>";
foreach($this->registry->periods as $index => $period) {
	if ($this->registry->periodID ==  $period->periodID) {
		echo "<option selected='selected' value='" . $index . "'>" . $period->fullname . "</option>";
	} else {
		echo "<option value='" . $index . "'>" . $period->fullname . "</option>";
	}
}
echo "</select>";


echo "	<script>";
echo "		$('#periodselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/vatreport/showvatreport')."&periodID='+this.value;";
echo "		});";
echo "	</script>";




echo "<select id=selectionselectfield class='field-select' style='width:120px;margin-right:5px;'>";
echo "<option value='0'></option>";
foreach($this->registry->selection as $index => $value) {
	if ($this->registry->selectionID ==  $value->rowID) {
		echo "<option  selected='selected' value='" . $value->rowID . "'>" . $value->name . "</option>";
	} else {
		echo "<option value='" . $value->rowID . "'>" . $value->name . "</option>";
	}
}
echo "</select>";



echo "	<script>";
echo "		$('#selectionselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/vatreport/showvatreport')."&selectionID='+this.value;";
echo "		});";
echo "	</script>";



echo "		</td>";
echo "	</tr>";
echo "</table>";


echo "<h1>Arvonlisäveroilmoitus</h1>";

echo "<table>";
echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td style='width:400px;'></td>";
echo "		<td style='width:100px;'></td>";
echo "	</tr>";


echo "	<tr  class='listtable-row'>";
echo "		<td colspan=2 style='font-weight:bold'>Vero kotimaan myynnistä verokannoittain</td>";
echo "		<td style='width:100px;'></td>";
echo "	</tr>";

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>24%:n vero</td>";
echo "		<td style='text-align:right;'>" .  number_format($this->registry->vatsums[1],2,","," ") . " €</td>";
echo "	</tr>";
$maksettava = $this->registry->vatsums[1];

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>14%:n vero</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[2],2,","," ") . " €</td>";
echo "	</tr>";
$maksettava = $maksettava + $this->registry->vatsums[2];

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>10%:n vero</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[3],2,","," ") . " €</td>";
echo "	</tr>";
$maksettava = $maksettava + $this->registry->vatsums[3];

echo "	<tr>";
echo "		<td colspan=3 style='height:10px;'></td>";
echo "	</tr>";


echo "	<tr>";
echo "		<td colspan=2 style='font-weight:bold'>Vero ostoista ja maahantuonneista</td>";
echo "		<td style='width:100px;'></td>";
echo "	</tr>";

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>Vero tavaraostoista muista EU-maista</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[5],2,","," ") . " €</td>";
echo "	</tr>";
$maksettava = $maksettava + $this->registry->vatsums[5];

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>Vero palveluostoista muista EU-maista</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[6],2,","," ") . " €</td>";
echo "	</tr>";
$maksettava = $maksettava + $this->registry->vatsums[6];

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>Vero tavaroiden maahantuonneista EU:n ulkopuolelta</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[4],2,","," ") . " €</td>";
echo "	</tr>";
$maksettava = $maksettava + $this->registry->vatsums[4];

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>Vero rakentamispalvelun tai metalliromun ostoista</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[14],2,","," ") . " €</td>";
echo "	</tr>";
$maksettava = $maksettava + $this->registry->vatsums[14];



echo "	<tr>";
echo "		<td colspan=3 style='height:10px;'></td>";
echo "	</tr>";


echo "	<tr>";
echo "		<td colspan=2 style='font-weight:bold'>Vähennettävä vero</td>";
echo "		<td style='width:100px;'></td>";
echo "	</tr>";


echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>Verokauden vähennettävä vero</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[7],2,","," ") . " €</td>";
echo "	</tr>";
$vähennettävä = $this->registry->vatsums[7];


/*
echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>Ilmoitatko alarajahuojennuksen tietoja tällä verokaudella</td>";
echo "		<td style='text-align:right;'>Ei</td>";
echo "	</tr>";
*/

echo "	<tr>";
echo "		<td colspan=3 style='height:10px;'></td>";
echo "	</tr>";

$total = $maksettava - $vähennettävä;

echo "	<tr>";
echo "		<td colspan=2 style='font-weight:bold'>Maksettava vero / Palautukseen oikeuttava vero</td>";
echo "		<td style='text-align:right;font-weight:bold;'>" . number_format($total,2,","," ") . " €</td>";
echo "	</tr>";




echo "	<tr>";
echo "		<td colspan=3 style='height:10px;'></td>";
echo "	</tr>";


echo "	<tr>";
echo "		<td colspan=2 style='font-weight:bold'>Myynnit, ostot ja maahantuonnit</td>";
echo "		<td style='width:100px;'></td>";
echo "	</tr>";

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>0-verokannan alainen liikevaihto</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[8],2,","," ") . " €</td>";
echo "	</tr>";

/*
echo "	<tr>";
echo "		<td colspan=3 style='height:5px;'></td>";
echo "	</tr>";
*/

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>Tavaroiden myynnit muihin EU-maihin</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[10],2,","," ") . " €</td>";
echo "	</tr>";

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>Palveluiden myynnit muihin EU-maihin</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[11],2,","," ") . " €</td>";
echo "	</tr>";

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>Tavaraostot muista EU-maista</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[12],2,","," ") . " €</td>";
echo "	</tr>";

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>Palveluostot muista EU-maista</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[13],2,","," ") . " €</td>";
echo "	</tr>";

/*
echo "	<tr>";
echo "		<td colspan=3 style='height:5px;'></td>";
echo "	</tr>";
*/

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>Tavaroiden maahantuonnit EU:n ulkopuolelta</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[9],2,","," ") . " €</td>";
echo "	</tr>";

/*
echo "	<tr>";
echo "		<td colspan=3 style='height:5px;'></td>";
echo "	</tr>";
*/

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>Rakentamispalvelun ja metalliromun myynnit</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[15],2,","," ") . " €</td>";
echo "	</tr>";

echo "	<tr>";
echo "		<td style='width:20px;'></td>";
echo "		<td>Rakentamispalvelun ja metalliromun ostot</td>";
echo "		<td style='text-align:right;'>" . number_format($this->registry->vatsums[16],2,","," ") . " €</td>";
echo "	</tr>";




echo "	<tr>";
echo "		<td colspan=3 style='height:50px;'></td>";
echo "	</tr>";

?>