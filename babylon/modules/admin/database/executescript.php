<?php


	echo "<h3>Executing - " . $registry->script . "</h3>";

	echo "<br>path " . $registry->path;
	include($registry->path);
?>