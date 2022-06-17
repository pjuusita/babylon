<?php
	include '../app/init.php';

	echo "Logfiles";
	$dir = SITE_PATH  . 'log';
	//echo "<br>Dir - "  .$dir;
	$files = scandir($dir);
	
	foreach($files as $index => $value) {
		if (($value == '.') || ($value == 'index.php') || ($value == '..')) {
			//echo "<br>" . $value;
		} else {
			echo "<br><a href='" . $value.  "'>" . $value . "</a>";
		}
	}
?>