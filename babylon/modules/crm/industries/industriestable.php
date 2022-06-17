<?php

foreach ($this->registry->toimialat as $toimialaID => $toimiala ) {
	shownimike($toimiala,0);
}

function shownimike($toimiala,$sisennys) {
	echo "<div id=otsikko".$toimiala->toimialaluokkaID.">";

	for ($x=0;$x<$sisennys;$x++) {
		echo "<span style='display: inline-block;width: 50px'></span>";
	}

	if (count ($toimiala->childs) > 0) {
		echo "<span style='display: inline-block;width: 50px'><img style=';vertical-align:text-top' id='image".$toimiala->toimialaluokkaID."' onclick=\"plusbutton('".$toimiala->toimialaluokkaID."')\" src='" . getImageUrl('buttonplus.gif') . "'></span>";
	} else {
		echo "<span style='display: inline-block;width: 50px'></span>";
	}

	echo "<span style='display: inline-block;width: 50px'>".$toimiala->koodi."</span>";
	echo "<span style='display: inline-block;width: 600px'>".$toimiala->nimike."</span>";
	echo "</div>";

	echo "<div id=".$toimiala->toimialaluokkaID." style='display:none'>";
	if (count ($toimiala->childs) > 0) {
		foreach ($toimiala->childs as $childtoimialaID => $childtoimiala) {
			shownimike($childtoimiala,$sisennys+1);
		}
	}
	echo "</div>";
}

echo "<script>";
echo "function plusbutton(id) {";
echo "	$('#'+id).show();";
//echo "	alert ('plus:'+alue);";
echo " 	$('#image'+id).attr('src','" . getImageUrl('buttonminus.gif') . "');";
echo " 	$('#image'+id).attr('onclick','minusbutton('+id+')');";
echo "}";
echo "</script>";

echo "<script>";
echo "function minusbutton(id) {";
echo "	$('#'+id).hide();";
//echo "	alert ('minus'+alue);";
echo " 	$('#image'+id).attr('src','" . getImageUrl('buttonplus.gif') . "');";
echo " 	$('#image'+id).attr('onclick','plusbutton('+id+')');";
echo "}";
echo "</script>";

?>