<?php


// nimeämiskäytäntä pitäisi olla layout tai layout template

class MenuTemplate extends Template {

	
	public function printErrorMessages() {
	
		if (isset($_SESSION['errorcount'])) {
			$errorcount = intval($_SESSION['errorcount']);
			for($i = 0;$i<$errorcount;$i++) {
	
				echo "	<div style='width:600px;height:40;border: 2px solid #888888;background-color:pink;border-radius:3px;padding:6px 10px;'>";
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
		
	
	
	public function loadMenuTemplateContent($con = null) {
		$this->registry->menu = Menu::loadMenu($_SESSION['usergroupID']);
	}
	
	
	public function show($module, $filename) {

		$menupath = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . 'system/menu' . DIRECTORY_SEPARATOR . 'menu.php';
		//$this->title = "";
		$title = "Menu title";
		
		$this->loadMenuTemplateContent();
		$menu = $this->registry->menu;
		$this->generateHeader();
		
		
		echo "	<div>";
		//echo "		<div style='width:100%;background-color:lightgrey;height:30px;vertical-align:middle;font-family: Merriweather Sans;height:100%;width:100%;text-decoration:none;font-size:16px;color:white;background-image:url(images/module.png);padding: 7px 0px 7px 8px;''>";
		echo "		<div style='width:100%;background-color:lightgrey;height:30px;vertical-align:middle;font-family: Merriweather Sans;width:100%;text-decoration:none;font-size:16px;color:white;'>";
		echo "			<table>";
		echo "				<tr>";
		echo "					<td style='width:200px;padding-left:10px;'>" . date('d.m.Y') . "&nbsp&nbsp" . date('h:i:s') . "</td>";
		echo "					<td style='width:500px;'>User: " . $_SESSION['userID'] . " - " . $_SESSION['username'] . "</td>";
		echo "					<td style='width:200px;'><a style='color:white;text-decoration:underline' href='" . getUrl('system/login/changerole') . "'>Vaihda roolia</a>&nbsp&nbsp&nbsp<a style='text-decoration:underline'  href='" . getUrl('system/login/logout') . "'>Logout</a></td>";
		echo "				</tr>";
		echo "			</table>";
		echo "		</div>";
		
		
		echo "		<table style='width:100%;text-align:left;'>";
		
		echo "			<tr>";
		echo "				<td style='vertical-align:top;text-align:left;width:200px;white-space:nowrap;'>";
		$registry = $this->registry;
		include ($menupath);
		echo "				</td>";		
		echo "				<td id=contenttd style='vertical-align:top;width:100%;padding-left:5px;padding-right:5px;padding-top:20px;'>";
		
		echo "					<div id=contentdiv style='height:100%;width:100%;padding-left:7px;'>";
		echo "						<div style='clear:left;'></div>";
		
		$this->printErrorMessages();
		
		
		echo "						<div style='display:block;'>";
		$contentpath = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $filename . '.php';
		include ($contentpath);
		echo "						</div>";
		echo "					</div>";
		echo "					<div id=contentloadingdiv style='display:none;height:100%;width:100%;padding-left:50px;padding-top:50px;'>";
		echo "						<h1>LOGO</h1>"; //<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
		echo "					</div>";
		echo "				</td>";
		echo "			</tr>";		
		echo "		</table>";
		
		echo "		<table style='width:780px;background-color:#ffffff;border-spacing: 6px 4px;' cellspacing=0 cellpadding=0 align='center'>";
		echo "			<tr>";
		echo "				<td style='text-align:right;width:780px;'>";
		//echo "					<a href=\"" . getUrl('system/login/logout') . "\">Logout</a>";
		echo "				</td>";
		echo "			</tr>";
		echo "		</table>";
		$this->generateFooter();
		echo "	</div>";
		
		exit;
	}
}


?>