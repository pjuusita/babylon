<?php


// nimeämiskäytäntä pitäisi olla layout tai layout template

class EnterpriseTemplate extends Template {

	

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
		
		$menu = Menu::loadMenu($_SESSION['usergroupID']);
		$this->registry->menu = $menu;
		$mastersystem = Table::loadRow("system_systems","WHERE SystemID=" . $_SESSION['mastersystemID']);
		$clientsystems = Table::load("system_credentials"," WHERE SystemID=" . $_SESSION['mastersystemID']);
		
		$systemlist = array();
		$mastersystem->clientsystemID = $mastersystem->systemID;
		$systemlist[] = $mastersystem;
		foreach($clientsystems as $index => $clientsystem) {
			//echo "<br>Client found - " . $clientsystem->name;
			$systemlist[] = $clientsystem;
		}
		//echo "<br>compnaylist - " . count($companylist);
		
		// TODO: companylist pitää varmaan filtteröidä käyttäjän oikeuksien perusteella.
		$companies = Table::load("system_companies", "WHERE SystemID=" . $_SESSION['systemID']);
				
		$this->registry->subsystems = $systemlist;
		$this->registry->menucompanies = $companies;
	}
	
	
	
	public function show($module, $filename) {

		$menupath = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . 'enterprise' . DIRECTORY_SEPARATOR;
		$menupath = $menupath . 'enterprise.menu.php';
		
		//$this->title = "";
		$title = "Menu title";
		
		$this->loadMenuTemplateContent();
		$menu = $this->registry->menu;
		$subsystems = $this->registry->subsystems;
		$companies = $this->registry->menucompanies;
		$this->generateHeader();
		
		//echo "<br>subsystemcount - " . count($subsystems);
		
		echo "	<div>";
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
		
		printActionPath($registry);
		echo "					<div id=contentdiv style='height:100%;width:100%;padding-left:7px;'>";
		echo "						<div style='clear:left;'></div>";
		
		$this->printErrorMessages();
		
		
		echo "						<div style='display:block;'>";
		$contentpath = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $filename . '.php';
		include ($contentpath);
		echo "						</div>";
		echo "					</div>";
		echo "					<div id=contentloadingdiv style='display:none;height:100%;width:100%;padding-left:50px;padding-top:50px;'>";
		echo "						<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
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