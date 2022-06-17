<?php


function generateLogo($menu,$width, $logofile) {
	echo "	<tr style='background-color:pink;'>";
	echo "  	<td style='background-color:#696969;padding-top:10px;padding-bottom:10px;'><a href='" . getUrl("system/frontpage/index") . "'><img src='" . getImageUrl($logofile) . "'></a></td>";
	echo "  </tr>";
}



function generateMenuItems($menu,$registry, &$menuparents) {

	foreach($menu as $index => $menuitem) {
		GenerateTopLevelMenu($menuitem, $registry, $menuparents);
	}
}

function GenerateTopLevelMenu($menu, $registry, &$menuparents) {
	
	// Pitäisikä tämä tarkistaa onko haluttu css-tiedosto ladattu?
	
	echo "				<tr>";
	echo "					<td id=menuid-" . $menu->getID() . " onclick=\"toplevelmenuclick(event, " . $menu->getID() . ",'" . $menu->getModule() . "/" . $menu->getAction() . "',0)\" style='cursor:pointer;background-image:url(" . getImageUrl("module.png") . ");background-repeat:repeat-x;'>";
	echo "<a  href='" . getUrl($menu->getModule() . "/" . $menu->getAction()) . "&menuID=" .  $menu->getID() . "' style='text-decoration:none'>";
	echo "<div id=menudiv-" . $menu->getID() . " style='font-family: Merriweather Sans;height:33px;width:100%;text-decoration:none;font-size:16px;color:white;background-image:url(images/module.png);padding: 3px 0px 7px 8px;box-sizing: border-box;'>";
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
	
	echo "<div id=menudiv-" . $menu->getID() . "  style='width:100%;height:33px;padding-left:8px;background-image:url(images/module.png);background-size: 100% 33px;box-sizing: border-box'>";
	echo "<a  href='" . getUrl($menu->getModule() . "/" . $menu->getAction()) . "&menuID=" .  $menu->getID() . "' style='text-decoration:none'>";
	echo "<div   onclick=\"sublevelmenuclick(event, " . $menu->getID() . ",'" . $menu->getModule() . "/" . $menu->getAction() . "','" . $menu->getTitle() . "')\"  style='font-family: Merriweather Sans;font-size: 1.1em;width:100%;text-decoration:none;font-size:14px;color:white;padding: 4px 3px 4px 16px;'>";
	echo "".$menu->getTitle();
	echo "</div>";
	echo "</a>";
	echo "</div>";
}





function createMenu($menu, $width, $logofile) {

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
	
	
	
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "  function sublevelmenuclick(event, menuid, url, actionname) {";
	
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
	
	//echo "		loadpage(url);";
	echo "		loadpagefrommenu(url, menuid, actionname);";
	
	
	echo "	};";
	echo "</script>";
	
	
	
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
	echo "  function hideAllSubmenus() {";
	foreach($menu as $index => $menuitem) {
		echo "		$('#submenudiv-" . $menuitem->menuID . "').hide();";
	}
	echo "	};";
	echo "</script>";
	
	
	$menuparents = array();
	
	echo "	<table class=menu_container cellspacing=0 cellpadding=0 style='background-color:#696969;width:" . $width  . "px;padding:0;border-collapse:collapse;'>";
	generateLogo($menu,$width, $logofile);
	generateMenuItems($menu,$width, $menuparents);
	echo "	</table>";
	

	

	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	
	echo "		var menuparents = [];";
	

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
	
	
	
	echo "		$(document).ready(function() {";
	
	echo "			console.log('documentready menu');";
	
	
	echo "			menuid = 0;";
	echo "			if (window.sessionStorage.getItem('menuid') === null) {";
	
	// en muista mitä tällä on tarkoitusta?
	echo "				var url = window.location.href;";
	echo "				var start = url.indexOf('rt=');";
	echo "				var end = url.indexOf('&',start);";
	echo "				var rt = url.substring(start+3,end);";
	echo "			} else {";
	
	echo "			}";
	
	// jos menua ei läytynyt urlista, ladataan session storagesta
	echo "			if (menuid == 0) {";
	echo "				menuid = window.sessionStorage.getItem('menuid');";
	//echo "				console.log('menuid found - '+menuid);";
	echo "			}";
	
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
	
}



createMenu($menu, 160, $registry->css_logo_file);

?>