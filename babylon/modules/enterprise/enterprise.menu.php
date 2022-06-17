<?php


function generateLogo($menu,$width, $logofile) {
	echo "	<tr style='background-color:pink;'>";
	echo "  	<td style='background-color:#696969;padding-top:10px;padding-bottom:10px;'><a href='" . getUrl($_SESSION['frontpage']) . "'><img src='" . getImageUrl($logofile) . "'></a></td>";
	echo "  </tr>";
}




function generateMenuItems($menu, $subsystems, $companies, $registry, &$menuparents) {

	echo "				<tr>";
	echo "					<td style='font-family: Merriweather Sans;height:100%;width:100%;text-decoration:none;font-size:16px;color:white;padding: 7px 0px 2px 8px;box-sizing: border-box;'>";
	echo "Asiakas:";
	echo "					</td>";
	echo "				</tr>";
	
	echo "				<tr>";
	echo "					<td style='padding: 0px 0px 0px 24px;'>";
	
	echo "<select id=menysystemselector class=field-select style='font-family: Merriweather Sans;width:170px;font-size:14px;'>";
	foreach ($subsystems as $ind => $system) {
		if ($system->clientsystemID == $_SESSION['systemID']) {
			echo "<option value=" . $system->clientsystemID . " selected>" . $system->name. "</option>";
		} else {
			echo "<option value=" . $system->clientsystemID . ">" . $system->name. "</option>";
		}
	}
	echo "</select>";
	echo "					</td>";
	echo "				</tr>";
	
	if (count($companies) > 1) {
		echo "				<tr>";
		echo "					<td style='font-family: Merriweather Sans;height:100%;width:100%;text-decoration:none;font-size:16px;color:white;padding: 7px 0px 2px 8px;box-sizing: border-box;'>";
		echo "Yritys:";
		echo "					</td>";
		echo "				</tr>";
		
		echo "				<tr>";
		echo "					<td style='padding: 0px 0px 0px 24px;'>";
		
		echo "<select id=menucompanyselector class=field-select style='font-family: Merriweather Sans;width:170px;font-size:14px;'>";
		foreach ($companies as $ind => $company) {
			if ($company->companyID == $_SESSION['companyID']) {
				echo "<option value=" . $company->companyID . " selected>" . $company->name. "</option>";
			} else {
				echo "<option value=" . $company->companyID . ">" . $company->name. "</option>";
			}
		}
		echo "</select>";
		echo "					</td>";
		echo "				</tr>";
	}
	
	echo "				<tr>";
	echo "					<td style='height:20px;'>";
	echo "					</td>";
	echo "				</tr>";
	
	echo "<script>";
	echo "	$('#menysystemselector').change(function() {";
	echo "		systemID = $('#menysystemselector').val();";
	echo "		var url = '" . getUrl("enterprise/contracts/selectsystem") . "&systemID=' + systemID;";
	echo "		console.log('- url '+url);";
	echo "		window.location = url;";
	echo "	});";
	echo "</script>";

	echo "<script>";
	echo "	$('#menucompanyselector').change(function() {";
	echo "		companyID = $('#menucompanyselector').val();";
	echo "		var url = '" . getUrl("enterprise/contracts/selectcompany") . "&companyID=' + companyID;";
	echo "		console.log('- url '+url);";
	echo "		window.location = url;";
	echo "	});";
	echo "</script>";
		
	
	
	foreach($menu as $index => $menuitem) {
		GenerateTopLevelMenu($menuitem, $registry, $menuparents);
	}
}

function GenerateTopLevelMenu($menu, $registry, &$menuparents) {
	
	// Pitäisikä tämä tarkistaa onko haluttu css-tiedosto ladattu?
	
	echo "				<tr>";
	//echo "					<td class=menurow id=menuid-" . $menu->getID() . " onclick=\"toplevelmenuclick(event, " . $menu->getID() . ",'" . $menu->getModule() . "/" . $menu->getAction() . "',0)\" style='cursor:pointer;background-repeat:repeat-x;background-color:pink;'>";
	echo "					<td class=menurow id=menuid-" . $menu->getID() . " onclick=\"toplevelmenuclick(event, " . $menu->getID() . ",'" . $menu->getModule() . "/" . $menu->getAction() . "',0)\" style='cursor:pointer;background-image:url(" . getImageUrl("module.png") . ");background-repeat:repeat-x;'>";
	echo "<a  href='" . getUrl($menu->getModule() . "/" . $menu->getAction()) . "&menuID=" .  $menu->getID() . "' style='text-decoration:none'>";
	echo "<div id=menudiv-" . $menu->getID() . " style='font-family: Merriweather Sans;height:33px;width:100%;text-decoration:none;font-size:16px;color:white;padding: 3px 0px 3px 8px;box-sizing: border-box;'>";
	//echo "<div id=menudiv-" . $menu->getID() . " style='font-family: Merriweather Sans;height:35px;width:100%;text-decoration:none;font-size:16px;color:white;background-image:url(images/module.png);padding: 7px 0px 7px 8px;box-sizing: border-box;'>";
	echo "" . $menu->getTitle();
	echo "</div>";
	echo "</a>";
	echo "				</tr>";
	
	if ($menu->getChildCount() > 0) {
		echo "				<tr style='background-color:pink;'>";
		echo "					<td  id='submenudiv-" . $menu->menuID . "' style='display:none;padding-left:0px;'>";
		//echo "					<td  id='submenudiv-".$menu->getID()."'  onclick=\"loadurltest(" . $menu->getID() . ",'" . $menu->getModule() . "','" . $menu->getAction() . "',0)\" style='display:none;'>";
		foreach($menu->childs as $index => $menuitem) {
			GenerateSubmenuItem($menuitem, $menu, $menuparents);
		}
		echo "					</td>";
		echo "				</tr>";
	} else {
		echo "				<tr>";
		echo "					<td colspan=2>";
		echo "					</td>";
		echo "				</tr>";
	}
	
	
}



function GenerateSubmenuItem($menu, $parent, &$menuparents) {
	
	$menuparents[$menu->getID()] = $parent->getID();
	
	//echo "<div id=menudiv-" . $menu->getID() . "  style='width:100%;padding-left:8px;background-size: 100% 26px;box-sizing: border-box'>";
	echo "<div class=menurow  id=menudiv-" . $menu->getID() . "  style='width:100%;padding-left:8px;background-image:url(images/module.png);background-size: 100% 33px;box-sizing: border-box;overflow: hidden;'>";
	echo "<a  href='" . getUrl($menu->getModule() . "/" . $menu->getAction()) . "&menuID=" .  $menu->getID() . "' style='text-decoration:none'>";
	echo "<div onclick=\"sublevelmenuclick(event, " . $menu->getID() . ",'" . $menu->getModule() . "/" . $menu->getAction() . "',0)\"  style='font-family: Merriweather Sans;font-size: 1.1em;width:100%;text-decoration:none;font-size:14px;color:white;padding: 4px 3px 4px 16px;'>";
	echo "".$menu->getTitle();
	echo "</div>";
	echo "</a>";
	echo "</div>";
}





function createMenu($menu, $subsystems, $companies, $width, $logofile) {

	// TODO: menulle pitää tehdä yläluokka app-hakemistoon
	// TODO: javascript funktiot pitää siirtää yläluokkaan, nämä pitää läytyä kaikilta sivuilta, ehkä nämä pitäisi siirtää jopa yläframeen, templateen? Voisi olla ehkä jopa globaali js-tiedosto
	

	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "  function toplevelmenuclick(event, menuid, url) {";

	//echo "		console.log('toplevelmenuclick - '+menuid);";
	//echo "		window.sessionStorage.setItem('selectedmenu',menuid);";
	
	echo "		event.stopPropagation();";
	echo "		event.stopImmediatePropagation();";
	echo "		event.preventDefault();";
	
	echo "		hideAllSubmenus();";
	echo "		$('#submenudiv-'+menuid).show();";
	
	echo "		var oldmenuid = window.sessionStorage.getItem('menuid');";
	echo "		$('#menudiv-'+oldmenuid).css('background-image', 'url(images/module.png)');";
	echo "		$('#menudiv-'+menuid).css('background-image', 'url(images/module3.png)');";
	echo "		window.sessionStorage.setItem('menuid',menuid);";
	
	echo "		loadpage(url);";
	echo "	};";
	echo "</script>";
	
	

	// Tätä käytetään lähinnä silloin kun halutaan avata jotain uudessa ikkunassa, esim. pdf-tiedosto.
	// yleensä tätä käytetään nappuloissa.
	// TODO: siirrä johonkin yleiseen javascript-kirjastoon... tämä on nyt toteutettu menussa...
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "  function opennewtab(ulrlink) {";
	echo "		var url = '" . SITEPATH . "'+ulrlink;";
	//echo "		var divurl = '" . SITEPATH . "" . NOFRAMESHANDLER . "?rt='+ulrlink;";
	echo "		var locci = '&noframes';";		// TODO: activemenu pitää hakea jostain javan muuttujasta
	echo "		console.log('--'+ulrlink);";
	echo "		console.log('--'+url);";
	echo "		window.open(url+locci, '_blank');";
	echo "	};";
	echo "</script>";
	
	
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "  function sublevelmenuclick(event, menuid, url) {";
	
	echo "		console.log('sublevelmenuclick - '+menuid);";
	//echo "		console.log('menuparent - '+menuparents[menuid]);";
	//echo "		window.sessionStorage.setItem('menuid',menuid);";
	
	echo "		event.stopPropagation();";
	echo "		event.stopImmediatePropagation();";
	echo "		event.preventDefault();";
	
	echo "		var oldmenuid = window.sessionStorage.getItem('menuid');";
	echo "		$('#menudiv-'+oldmenuid).css('background-image', 'url(images/module.png)');";
	echo "		$('#menudiv-'+menuid).css('background-image', 'url(images/module3.png)');";
	echo "		window.sessionStorage.setItem('menuid',menuid);";
	
	echo "		loadpage(url);";
	echo "	};";
	echo "</script>";
	
	
	
	
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "  function hideAllSubmenus() {";
	foreach($menu as $index => $menuitem) {
		echo "		$('#submenudiv-" . $menuitem->menuID . "').hide();";
	}
	echo "	};";
	echo "</script>";
	
	
	$menuparents = array();
	
	echo "	<table class=menu_container cellspacing=0 cellpadding=0 style='background-color:#696969;width:" . $width  . "px;padding:0;border-collapse:collapse;'>";
	generateLogo($menu,$width, $logofile);
	generateMenuItems($menu,$subsystems, $companies, $width, $menuparents);
	echo "	</table>";
	

	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	
	echo "		var menuparents = [];";
	
	if (isset($_SESSION['actionpath'])) {
		$actions = $_SESSION['actionpath'];
		echo "		var windowID = " . $_SESSION['windowID'] . ";";
		echo "		var actionpath = {";
		$first = true;
		foreach($actions as $actionname => $actionpath) {
			if ($first == true) {
				$first = false;
			} else {
				echo ",";
			}
			echo " \"" . $actionname . "\":\"" . $actionpath . "\"";
		}
		echo "};";
	} else {
		echo "		var actionpath = {};";
	}
	echo "		var actionname = '" . getPageTitle() . "';";
	echo "		var windowID = " . $_SESSION['windowID'] . ";";
	echo "		var actionname = '" . getPageTitle() . "';";
	
	echo "\n\n		$(document).ready(function() {";
	
	
	// Jos ollaan avattu uusi ikkuna, niin pitää asetetaan href uudelle windowID:lle.
	if ($_SESSION['newwindow'] == 1) {
		echo "			var newloc = window.location.href;";
		echo "			console.log(' - newloc - '+newloc);";
		echo "			newloc = newloc.replace(\"wID=0\", \"wID=" . $_SESSION['windowID'] . "\");";
		echo "			console.log(' - newloc - '+newloc);";
		echo "			window.history.replaceState({}, document.title, newloc);";
		//echo "			$('#locationurl').html('current - '+newloc);";
	} else {
		//echo "			$('#locationurl').html('current - '+window.location.href);";
	}
	
	/*
	echo "			menuid = 0;";
	echo "			if (window.sessionStorage.getItem('menuid') === null) {";
	
	// en muista mitä tällä on tarkoitusta?
	echo "				var url = window.location.href;";
	echo "				var start = url.indexOf('rt=');";
	echo "				var end = url.indexOf('&',start);";
	echo "				var rt = url.substring(start+3,end);";
	echo "			} else {";
	
	echo "			}";
	*/
	
	echo "			var menuid = window.sessionStorage.getItem('menuid');";
	
	// jos menua ei läytynyt urlista, ladataan session storagesta
	/*
	echo "			if (menuid == 0) {";
	//echo "				menuid = window.sessionStorage.getItem('menuid');";
	//echo "				console.log('menuid found - '+menuid);";
	echo "			}";
	*/
	echo "			console.log('menuID - ' + menuid);";
	
	
	foreach ($menuparents as $menuID => $parentID) {
		echo "		menuparents[" . $menuID . "] = " . $parentID . ";";
	}
	
	
	// jos menu on valittu aktivoidaan se
	echo "			if (menuid != 0) {";
	
	//echo "		console.log('menuid - '+menuid);";
	//echo "		console.log('menuparent - '+menuparents[menuid]);";
	
	echo "				var parentid = menuparents[menuid];";
	echo "				if (parentid === undefined) {";
	echo "					$('#submenudiv-'+menuid).show();";
	echo "					$('#menudiv-'+menuid).css('background-image', 'url(images/module3.png)');";
	echo "				} else {";
	echo "					$('#submenudiv-'+parentid).show();";
	echo "					$('#menudiv-'+menuid).css('background-image', 'url(images/module3.png)');";
	echo "				}";
	echo "			}";
	
	echo "		});";
	echo "</script>";
	
	
	
	/*
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	
	
	echo "		$(document).ready(function() {";
	

	foreach ($menuparents as $menuID => $parentID) {
		echo "menuparents[" . $menuID . "] = " . $parentID . ";";
	}
	
	
	// jos menu on valittu aktivoidaan se
	echo "			if (menuid != 0) {";
	
	//echo "		console.log('menuid - '+menuid);";
	//echo "		console.log('menuparent - '+menuparents[menuid]);";
	
	echo "				var parentid = menuparents[menuid];";
	echo "				if (parentid === undefined) {";
	echo "					$('#submenudiv-'+menuid).show();";
	echo "					$('#menudiv-'+menuid).css('background-image', 'url(images/module3.png)');";
	echo "				} else {";
	echo "					$('#submenudiv-'+parentid).show();";
	echo "					$('#menudiv-'+menuid).css('background-image', 'url(images/module3.png)');";
	echo "				}";
	
	// kelataan kaikki menut lävitse ja aktivoidaan mikäli menuid täsmää
	echo "			} else {";
	//echo "				console.log('menuid on nolla');";
	echo "			}";
	
	echo "		});";
	echo "</script>";
	*/
	
}



createMenu($menu, $subsystems, $companies, 160, $registry->css_logo_file);

?>