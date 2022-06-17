<?php


class LoginTemplate extends Template {

	
	public function printErrorMessages() {
		
		if (isset($_SESSION['errorcount'])) {
			$errorcount = intval($_SESSION['errorcount']);
			for($i = 0;$i<$errorcount;$i++) {
		
				echo "	<div style='width:300px;height:40;border: 2px solid #888888;background-color:pink;border-radius:3px;padding:6px 10px;'>";
				echo "	<table style='width:300px;background-color:pink;border-collapse:collapse;'>";
				echo "		<tr>";
				echo "			<td style='height:5px;font-weight:bold;font-size:14px;color:black;text-align:left;'>" . $_SESSION["errormessage-" .$i] . "</td>";
				echo "		</tr>";
				echo "	</table>";
				echo "	</div>";
				unset($_SESSION["errormessage-" .$i]);
			}
			$_SESSION['errorcount'] = 0;
		} else {
			$_SESSION['errorcount'] = 0;
		}
	}
	
	
	
	public function show($module, $filename) {
		
		$this->generateHeader("login.css");
		echo "	<table  class=indextable align=\"center\">";
		echo "		<tr>";
		echo "			<td style=\"height:40px\"></td>";
		echo "		</tr>";
		echo "		<tr>";
		echo "			<td style='width:400px'>";
		$this->printErrorMessages();
		echo "			</td>";
		echo "		</tr>";
		echo "		<tr>";
		echo "			<td style='width:300px'>";
		$contentpath = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . $this->registry->modulename . DIRECTORY_SEPARATOR  . $this->registry->controllername . DIRECTORY_SEPARATOR . $filename . '.php';
		include ($contentpath);
		echo "	 		</td>";
		echo "	 	<tr>";
		echo "	</table>";
		$this->generateFooter();
	}
}


?>