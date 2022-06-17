<?php


function generateLogo($menu,$width, $logofile) {
	echo "	<tr style='background-color:pink;'>";
	echo "  	<td style='background-color:#696969;padding-top:10px;padding-bottom:10px;'><a href='" . getUrl($_SESSION['frontpage']) . "'><img src='" . getImageUrl($logofile) . "'></a></td>";
	echo "  </tr>";
}




function generateMenuItems($menu, $grammars, $registry, &$menuparents) {

	echo "				<tr>";
	echo "					<td style='font-family: Merriweather Sans;height:100%;width:100%;text-decoration:none;font-size:16px;color:white;padding: 7px 0px 2px 8px;box-sizing: border-box;'>";
	echo "Active grammar";
	echo "					</td>";
	echo "				</tr>";
	
	echo "				<tr>";
	echo "					<td style='padding: 0px 0px 0px 24px;'>";
	//echo "<a  href='" . getUrl($menu->getModule() . "/" . $menu->getAction()) . "&menuID=" .  $menu->getID() . "' style='text-decoration:none'>";
	//echo "<a  href='" . getUrl($menu->getModule() . "/" . $menu->getAction()) . "&menuID=' style='text-decoration:none'>";
	
	$grammarID = 0;
	if (isset($_SESSION['grammarID'])) {
		$grammarID = $_SESSION['grammarID'];
	}
	
	echo "<select id=grammarselector01 class=field-select style='font-family: Merriweather Sans;width:170px;font-size:14px;'>";
	foreach ($grammars as $ind => $grammar) {
		if ($grammarID == 0) {
			$grammarID = $grammar->grammarID;
			$_SESSION['grammarID'] = $grammar->grammarID;
			$_SESSION['conceptsactive'] = $grammar->conceptsactive;
			$_SESSION['componentsactive'] = $grammar->componentsactive;
			$_SESSION['multilangactive'] = $grammar->multilangactive;
		}
		if ($grammar->grammarID == $grammarID) {
			echo "<option value=" . $grammar->grammarID . " selected>" . $grammar->name. "</option>";
		} else {
			echo "<option value=" . $grammar->grammarID . ">" . $grammar->name. "</option>";
		}
	}
	echo "</select>";
	echo "					</td>";
	echo "				</tr>";
	echo "				<tr>";
	echo "					<td style='height:20px;'>";
	echo "					</td>";
	echo "				</tr>";
	
	echo "<script>";
	echo "	$('#grammarselector01').change(function() {";
	echo "		grammarID = $('#grammarselector01').val();";
	echo "		var url = '" . getUrl("worder/grammars/selectgrammar") . "&grammarID=' + grammarID;";
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
	echo "					<td class=menurow id=menuid-" . $menu->getID() . " onclick=\"toplevelmenuclick(event, " . $menu->getID() . ",'" . $menu->getModule() . "/" . $menu->getAction() . "','" . $menu->getTitle() . "')\" style='cursor:pointer;background-image:url(" . getImageUrl("module.png") . ");background-repeat:repeat-x;'>";
	echo "<a  href='" . getNewWindowUrl($menu->getModule() . "/" . $menu->getAction()) . "&menuID=" .  $menu->getID() . "' style='text-decoration:none'>";
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
	echo "<a  href='" . getNewWindowUrl($menu->getModule() . "/" . $menu->getAction()) . "&menuID=" .  $menu->getID() . "' style='text-decoration:none'>";
	echo "<div onclick=\"sublevelmenuclick(event, " . $menu->getID() . ",'" . $menu->getModule() . "/" . $menu->getAction() . "','" . $menu->getTitle() . "')\"  style='font-family: Merriweather Sans;font-size: 1.1em;width:100%;text-decoration:none;font-size:14px;color:white;padding: 4px 3px 4px 16px;'>";
	echo "".$menu->getTitle();
	echo "</div>";
	echo "</a>";
	echo "</div>";
}





function createMenu($menu, $grammars, $width, $logofile) {

	// TODO: menulle pitää tehdä yläluokka app-hakemistoon
	// TODO: javascript funktiot pitää siirtää yläluokkaan, nämä pitää läytyä kaikilta sivuilta, ehkä nämä pitäisi siirtää jopa yläframeen, templateen? Voisi olla ehkä jopa globaali js-tiedosto
	

	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "  function toplevelmenuclick(event, menuid, url, actionname) {";

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
	
	echo "		loadpagefrommenu(url, menuid, actionname);";
	echo "	};";
	echo "</script>";
	
	
	
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "  function sublevelmenuclick(event, menuid, url, actionname) {";
	
	//echo "		console.log('sublevelmenuclick - '+menuid);";
	//echo "		console.log(' -- actionname - '+actionname);";
	//echo "		console.log('menuparent - '+menuparents[menuid]);";
	//echo "		window.sessionStorage.setItem('menuid',menuid);";
	
	echo "		event.stopPropagation();";
	echo "		event.stopImmediatePropagation();";
	echo "		event.preventDefault();";
	
	echo "		var oldmenuid = window.sessionStorage.getItem('menuid');";
	echo "		$('#menudiv-'+oldmenuid).css('background-image', 'url(images/module.png)');";
	echo "		$('#menudiv-'+menuid).css('background-image', 'url(images/module3.png)');";
	echo "		window.sessionStorage.setItem('menuid',menuid);";
	
	echo "		loadpagefrommenu(url, menuid, actionname);";
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
	generateMenuItems($menu,$grammars, $width, $menuparents);
	echo "	</table>";
	

	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	
	echo "		var menuparents = [];";
	
	if (isset($_SESSION['actionpath'])) {
		$actions = $_SESSION['actionpath'];
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
	echo "		var windowID = " . $_SESSION['windowID'] . ";";
	echo "		var actionname = '" . getPageTitle() . "';";
	
	echo "\n\n		$(document).ready(function() {";
	
	
	
	echo "			console.log('documentready grammarmenu');";
	echo "			console.log(' - newwindow - " . $_SESSION['newwindow']  . "');";
	
	// Jos ollaan avattu uusi ikkuna, niin pitää asetetaan href uudelle windowID:lle.
	if ($_SESSION['newwindow'] == 1) {
		echo "			var newloc = window.location.href;";
		echo "			console.log(' - newloc - '+newloc);";
		echo "			newloc = newloc.replace(\"wID=0\", \"wID=" . $_SESSION['windowID'] . "\");";
		echo "			console.log(' - newloc - '+newloc);";
		//echo "			window.history.replaceState({}, document.title, newloc);";
		//echo "			$('#locationurl').html('current - '+newloc);";
	} else {
		echo "			$('#locationurl').html('current - '+window.location.href);";
	}
			
	echo "			if (window.sessionStorage.getItem('windowid') === null) {";
	echo "				var r = Math.round(Math.random()*10000);";
	echo "				window.sessionStorage.setItem('windowid',r);";
	echo "			} else {";
	//echo "				alert('windowid found - '+window.sessionStorage.getItem('windowid'));";
	echo "			}";
	
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



createMenu($menu, $grammars, 160, $registry->css_logo_file);

?>