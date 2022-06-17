<?php


echo "<div style='height:500px;width:800px;background-color:yellow;'>";
echo "	<h1>Hyllyt</h1>";

echo "	<select style='width:350px;' id='single-label-example'>";
echo "	<option value=''></option>";
echo "	<option selected>American Black Bear</option>";
echo "	<option>Asiatic Black Bear</option>";
echo "	<option>Brown Bear</option>";
echo "	<option>Giant Panda</option>";
echo "	<option>Sloth Bear</option>";
echo "	<option>Sun Bear</option>";
echo "	<option>Polar Bear</option>";
echo "	<option>Spectacled Bear</option>";
echo "	</select>";

echo "</div>";


echo "<script type='text/javascript'>";
echo "	$('#single-label-example').chosen({});";
echo "	$('#single-label-example').chosen().change(function () {";
echo "		alert('jeejee - '+this.value);";
echo "	});";
echo "</script>";


?>