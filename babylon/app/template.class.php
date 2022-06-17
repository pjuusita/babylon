<?php

/**
 * Käyttäliittymän ulkoasupohjan / rungon yläluokka. Imlementaatiot /templates-hakemistossa.
 * 
 * Template määrää headerin, menun ja contentaren sijainnit.
 * 
 * Ideana olisi, että esim. desktop-selain käyttää omaa templatea ja mobiiliin skaalautuva käyttäliittymä omaansa.
 * 
 * 
 * @author Petri Uusitalo
 * @copyright Babelsoft Oy, 2016
 *
 */
abstract class Template {


	public $registry;
	
	private $module;
	
	private $title;
	
	
	/*
	 * @Variables array
	* @access private
	*/
	private $vars = array();

	/**
	 *
	 * @constructor
	 *
	 * @access public
	 *
	 * @return void
	 *
	 */
	function __construct($registry) {
		$this->registry = $registry;
	}


	
	
	/**
	 *
	 * @set undefined vars
	 *
	 * @param string $index
	 *
	 * @param mixed $value
	 *
	 * @return void
	 *
	 */
	// TODO: tämä on poistettavaa kamaa, testataan hetki esiintyykö tämä missään
	public function __set($index, $value)
	{
		//echo "<br>Template set variable ... " . $index . " = " . $value . " (poista kutsu)";
		$this->vars[$index] = $value;
	}

	
	// TODO: tämä on poistettavaa kamaa, testataan hetki esiintyykö tämä missään
	public function __get($index)
	{
		//echo "<br>Template get variable ... " . $index . " = " . $value . " (poista kutsu)";
		return $this->vars[$index];
	}
	
	
	
	public function getTitle() {
		echo "<br>Template.getTitle ... (poistettavaa?)";
		return 'MainTitle';
	}
	

	
	// TODO: Tsekkaa tämä, näitä on säädetty titlen osalta ainakin
	private function generateHeaderLinks() {
		
		echo "\n<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Merriweather Sans'>";
		echo "\n<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Monda'>";
		echo "\n<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Noto Sans KR'>";
		echo "\n<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Cabin'>";
		echo "\n<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>";
		
		echo "\n<link rel='stylesheet' href='" . SITEPATH . "css/jquery-ui.css'>";
		echo "\n<link rel='stylesheet' href='" . SITEPATH . "css/chosen.css'>";
		echo "\n<link rel='stylesheet' href='" . SITEPATH . "css/fileuploader.css'>";
		echo "\n<link rel='stylesheet' href='" . SITEPATH . "css/select2.css'>";
		echo "\n<link rel='stylesheet' href='" . SITEPATH . "css/menu.css'>";
		echo "\n<link rel='stylesheet' href='" . SITEPATH . "css/table.css'>";
		echo "\n<link rel='stylesheet' href='" . SITEPATH . "css/tasks.css'>";
		
		// Tätä käytetään ainoastaan tasks projektissa, voitaisiin poistaa tai siirtää esim. table.css:ään
		//echo "\n<link rel='stylesheet' href='" . SITEPATH . "css/tasks.css?r=" .  rand() . "'>";
		
		echo "\n<script type='text/javascript' src='" . SITEPATH . "js/jquery-3.2.1.min.js'></script>";
		echo "\n<script type='text/javascript' src='" . SITEPATH . "js/jquery-ui.js'></script>";
		echo "\n<script type='text/javascript' src='" . SITEPATH . "js/fileuploader.js'></script>";
		echo "\n<script type='text/javascript' src='" . SITEPATH . "js/chosen.jquery.js'></script>";
		echo "\n<script type='text/javascript' src='" . SITEPATH . "js/select2.js'></script>";
		//echo "\n<script type='text/javascript' src='https://unpkg.com/wavesurfer.js'></script>";
		echo "\n<script type='text/javascript' src='js/wavesurfer/wavesurfer.js'></script>";
		//echo "\n<script type='text/javascript' src='https://unpkg.com/wavesurfer.js/dist/plugin/wavesurfer.spectrogram.js'></script>";
		echo "\n<script type='text/javascript' src='js/wavesurfer/wavesurfer.spectrogram.js'></script>";
		//echo "\n<script type='text/javascript' src='https://unpkg.com/wavesurfer.js/dist/plugin/wavesurfer.regions.js'></script>";
		echo "\n<script type='text/javascript' src='js/wavesurfer/wavesurfer.regions.js'></script>";
		//echo "\n<script type='text/javascript' src='https://unpkg.com/wavesurfer.js/dist/plugin/wavesurfer.timeline.js'></script>";
		echo "\n<script type='text/javascript' src='js/wavesurfer/wavesurfer.timeline.js'></script>";
		//echo "\n<script type='text/javascript' src='https://unpkg.com/wavesurfer.js/dist/plugin/wavesurfer.minimap.js'></script>";
		echo "\n<script type='text/javascript' src='js/wavesurfer/wavesurfer.minimap.js'></script>";
				
		// En muista missä tätä tarvittaisiin
		//echo "\n<script type='text/javascript' src='" . SITEPATH . "js/prism.js'></script>";
		
		//echo "\n<script type='text/javascript' src='" . SITEPATH . "js/worder/featurestructure.js?r=" .  rand() . "'></script>";
		//echo "\n<script type='text/javascript' src='" . SITEPATH . "js/worder/rule.js?r=" .  rand() . "'></script>";
		//echo "\n<script type='text/javascript' src='" . SITEPATH . "js/worder/worder.js?r=" .  rand() . "'></script>";
		//echo "\n<script type='text/javascript' src='" . SITEPATH . "js/worder/syntaxanalyser.js?r=" .  rand() . "'></script>";
		//echo "\n<script type='text/javascript' src='" . SITEPATH . "js/worder/syntaxgenerator.js?r=" .  rand() . "'></script>";
		
		$this->includedJSFiles();
	}
	
	// Override..
	// Tämä pitäisi ylikirjoittaa Template-luokissa.	
	protected function includedJSFiles() {
		//echo "\n<script type='text/javascript' src='" . SITEPATH . "js/worder/featurestructure.js?r=" .  rand() . "'></script>";
		//echo "\n<script type='text/javascript' src='" . SITEPATH . "js/worder/rule.js?r=" .  rand() . "'></script>";
		//echo "\n<script type='text/javascript' src='" . SITEPATH . "js/worder/worder.js?r=" .  rand() . "'></script>";
		//echo "\n<script type='text/javascript' src='" . SITEPATH . "js/worder/syntaxanalyser.js?r=" .  rand() . "'></script>";
		//echo "\n<script type='text/javascript' src='" . SITEPATH . "js/worder/syntaxgenerator.js?r=" .  rand() . "'></script>";
	}
	
	
	protected function generateHeader($cssfile = null) {
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
		echo "\n<html>";
		echo "\n<head>";
		echo "\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
		echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" >";

		if ($cssfile != null) {		// erikoissivut, esim. login
			echo "\n<link rel='stylesheet' title='" . $cssfile  . " (old)' href='" . SITEPATH . "css/" . $cssfile . "?r=" .  rand() . "'>";
			echo "\n<script type='text/javascript'  title='bbb'  src='" . SITEPATH . "js/jquery.min.js?r=" .  rand() . "'></script>";
			echo "\n<script type='text/javascript' src='" . SITEPATH . "js/utils.js'></script>";
		} else {
			$this->generateHeaderLinks();
			echo "\n<script type='text/javascript' title='utils.js' src='" . SITEPATH . "js/utils.js'></script>";
		}
		
		if (isset( $_SESSION['pagetitle'])) {
			echo "\n<title>" . $_SESSION['pagetitle'] . "</title>";
		} else {
			echo "\n<title>Title2</title>";
		}
		
		/*
		echo "<script>";
		echo "	$(function() {";
		echo "		$('#tabs').tabs();";
		echo "	});";
		echo "</script>";
		*/

		echo "<script>";
		echo "	function addSuccessMessage(message) {";
		echo "		var messagecount = 0;";
		echo "		if (window.sessionStorage.getItem('successmessagecount') === null) {";
		echo "			window.sessionStorage.setItem('successmessagecount',1);";
		echo "			messagecount = 1;";
		echo "		} else {";
		echo "			messagecount = window.sessionStorage.getItem('successmessagecount');";
		echo "			messagecount++;";
		echo "			window.sessionStorage.setItem('successmessagecount',messagecount);";
		echo "		}";
		echo "		window.sessionStorage.setItem('successmessage-'+messagecount, message);";
		echo "	};";
		echo "</script>";
		
		echo "<script>";
		echo "	function consumeMessage() {";
		echo "		if (window.sessionStorage.getItem('successmessagecount') === null) {";
		echo "			return null;";
		echo "		} else {";
		echo "			messagecount = window.sessionStorage.getItem('successmessagecount');";
		echo "			if (messagecount == 0) return null;";
		echo "			var message = window.sessionStorage.getItem('successmessage-'+messagecount);";
		echo "			window.sessionStorage.removeItem('successmessage-'+messagecount);";
		echo "			messagecount--;";
		echo "			messagecount = window.sessionStorage.setItem('successmessagecount', messagecount);";
		echo "			return message;";
		echo "		}";
		echo "	};";
		echo "</script>";
		
		
		echo "<script>";
		echo "	function getSuccessMessageCount() {";
		echo "		if (window.sessionStorage.getItem('successmessagecount') === null) {";
		echo "			return 0;";
		echo "		} else {";
		echo "			messagecount = window.sessionStorage.getItem('successmessagecount');";
		echo "			return messagecount;";
		echo "		}";
		echo "	};";
		echo "</script>";
		
		// Tänne kaikki yleiskäyttöiset javascriptit, jotka tarvitsevat muuttujan arvoja php:stä.
		// nämähän voisi kyllä olla ehkä js-tiedostossakin... ettei joka kerta ladattaisi.

		echo "<script language=\"JavaScript\" type=\"text/javascript\">";
		echo "  function loadpagefrommenu(ulrlink, menuid, actionname) {";
		
		//echo "		console.log('loadfrommenu - '+actionname);";
		echo "		var url = null;";
		echo "		var divurl = null;";
		echo "		var locci = null;";
		
		//echo "		console.log('loadmenuurl - '+windowID);";
		
		echo "			var url = '" . SITEPATH . "" . ROOTPHP . "?wID='+windowID+'&rt='+ulrlink+'&menuID='+menuid;";
		echo "			var divurl = '" . SITEPATH . "" . NOFRAMESHANDLER . "?wID='+windowID+'&rt='+ulrlink+'&menuID='+menuid;";
		echo "			var locci = '&noframes';";		// TODO: activemenu pitää hakea jostain javan muuttujasta
		//echo "			actionpath[actionname] = url;";
		
		//echo "			console.log('currentactionpath');";
		//echo "			for (var name in actionpath) {";
		//echo "				console.log(' - '+name+' - '+actionpath[name]);";
		//echo "			}";
		
		
		echo "		var content = 'jokucontent';";
		echo "		var action = 'jokutitle';";
		echo "		window.history.pushState({\"html\":content,\"pageTitle\":action},\"\", url);";
		
		echo "		$('#contentdiv').html('');";
		echo "		$('#messagesdiv').html('');";
		
		echo "		$('#contentdiv').load(divurl+locci, function () {";
		
		echo "			mutex = 0;";
		echo "			$('#contentloadingdiv').hide();";
		echo "			$('#contentdiv').show();";
		echo "			console.log('actionpathcontent update');";
		
		echo "			actionpath = [];";
		echo "			var title = getcontenttitle();";
		echo "			if (title == 0) {";
		echo "				console.log('actiontitle nolla');";
		echo "				actionpath[actionname] = url;";
		echo "				document.title = actionname;";
		echo "			} else {";
		echo "				console.log('actiontitle - '+title);";
		echo "				actionpath[title] = url;";
		echo "				document.title = title;";
		echo "			}";
		echo "			$('#actionpathcontent').html('');";
		echo "			var contentstring = '<a href=\'" . getUrl('system/frontpage/index') . "\'>Etusivu</a>';";
		echo "			for (var name in actionpath) {";
		//echo "				console.log(' - '+name+' - '+actionpath[name]);";
		echo "				contentstring += ' // <a href=\''+actionpath[name]+'\'>'+name+'</a>';";
		echo "			}";
		//echo "			contentstring += ' // <a href=\''+divurl+locci+'\'>'+document.title+'</a>';";
		
		//echo "			console.log(' - '+contentstring);";
		echo "			$('#actionpathcontent').html(contentstring);";
		
		//echo "			console.log(' - b call');";
		//echo "			console.log(' - a call');";
		echo "		});";
		
		
		
		
		//echo "		console.log('in loadpage - 55');";
		
		echo "		return false;";
		echo "	};";
		echo "</script>";
		
		
		

		// Tätä tarvitaan, että browserin back nappula toimii / refreshaa..
		// En tiedä miksi tämä ei toimi oletuksena, ehkä kun stop propagationeja
		// on ainakin paljon...
		echo "<script language=\"JavaScript\" type=\"text/javascript\">";
		echo "	window.addEventListener('popstate', function(event) {";
		//echo "		console.log('popstate fired! - '+window.location.href);";
		echo "		window.location = window.location.href;";
		echo "	});";
		echo "</script>";
		
		
		
		// TODO: 17.10.21 loadpage olisi ehkä hyvä siirtää frameworkkiin koska loadpagea
		//                kutsutaan useammasta paikasta, esim. lineactioni kutsuu tätä.
		//				  tässä on nyt se ongelma, että tämä pitää toteuttaa jokaiseen
		//				  menuluokkaan erikseen. Käytännössä tämä taitaa olla kopioitu nykyisiin
		//				  menuihin.
		//
		echo "<script language=\"JavaScript\" type=\"text/javascript\">";
		echo "  function loadpage(ulrlink, actionname) {";
		
		//echo "		console.log('loadpageurl - '+windowID);";
		//echo "		console.log('ulrlink - '+ulrlink);";
		//echo "		console.log('actionname - '+actionname);";
		
		echo "		var url = '" . SITEPATH . "" . ROOTPHP . "?wID='+windowID+'&rt='+ulrlink;";
		echo "		var divurl = '" . SITEPATH . "" . NOFRAMESHANDLER . "?wID='+windowID+'&rt='+ulrlink;";
		echo "		var locci = '&noframes';";		// TODO: activemenu pitää hakea jostain javan muuttujasta
		
		
		// TODO: 17.10.21 Ei muistikuvaa onko tämä back-nappulan toiminto toimiva, ehkä
		echo "		var content = 'jokucontent';";
		echo "		var action = 'jokutitle';";
		echo "		window.history.pushState({\"html\":content,\"pageTitle\":action},\"\", url);";
		
		//echo "		console.log('clear messages');";
		//echo "		$('#contentdiv').html('');";
		//echo "		$('#contentdiv').innerHTML = '';";
		echo "		$('#contentdiv').textContent = '';";
		echo "		$('#messagesdiv').html('');";
		
		echo "		$('#contentdiv').load(divurl+locci, function () {";
		echo "			mutex = 0;";
		echo "			$('#contentloadingdiv').hide();";
		echo "			$('#contentdiv').show();";
		//echo "			console.log('actionpathcontent update');";
		echo "			var title = getcontenttitle();";
		echo "			if (title == 0) {";
		//echo "				console.log('actiontitle nolla');";
		echo "				actionpath[actionname] = url;";
		echo "				document.title = actionname;";
		echo "			} else {";
		//echo "				console.log('actiontitle - '+title);";
		echo "				actionpath[title] = url;";
		echo "				document.title = title;";
		echo "			}";
		echo "			$('#actionpathcontent').html('');";
		echo "			var contentstring = '<a href=\'" . getUrl('system/frontpage/index') . "\'>Etusivu</a>';";
		echo "			for (var name in actionpath) {";
		//echo "				console.log(' - '+name+' - '+actionpath[name]);";
		echo "				contentstring += ' // <a href=\''+actionpath[name]+'\'>'+name+'</a>';";
		echo "			}";
		echo "			$('#actionpathcontent').html(contentstring);";
		echo "		});";
		
		echo "		var taskheaderurl = '" . SITEPATH . NOFRAMESHANDLER . "?rt=tasks/tasks/gettaskheaderJSON&url='+ulrlink;";
		//echo "		console.log('taskheaderurl - '+taskheaderurl);";
		echo "		$.getJSON(taskheaderurl,'',function(data) {";
		//echo "			console.log('taskreaderurl fetch done');";
		//echo "			console.log('taskcount - '+data.tasks.length);";
		echo "			$.each(data.tasks, function(index) {";
		echo "				name = data.tasks[index].name;";
		echo "				id = data.tasks[index].minitaskID;";
		echo "				state = data.tasks[index].state;";
		//echo "				console.log(' -- name -'+name+', id='+id);";
		echo "				createTaskBar(name,id,state);";
		echo "			});";
		echo "		}); ";
		
		echo "		return false;";
		echo "	};";
		echo "</script>";
		
		
		
		
		echo "\n</head>";
		echo "\n<body id='topbody' style='margin:0px;padding:0px;text-align:left;'>";
		
		echo "	<input hidden id='windowid'>";
		
		//if (defined('DEV') && (DEV == true)) {
		if (false) {
			
			$sessionarray = array();
			$langarray = array();
			$requests = array();
			$queries = array();
			$roles = array();
			$menuitems = array();
				
			foreach($_SESSION as $index => $value) {
				
				if ($index === 'system_requests') {
					$requests = $_SESSION['system_requests'];
				} elseif ($index == 'system_queries') {
					$queries = $_SESSION['system_queries'];
				} else {
					if (substr($index,0,4) === 'lang') {
						$langarray[$index] = $value;
					} else {
						$sessionarray[$index] = $value;
					}
				}
			}
				
			
			$modules = Table::load('system_modules');
			
			if ($modules == null) {
				echo "<br>System_modules load failed.";
			}
			
			echo "<div style='width:100%;background-color:orange;'>";
			echo "	<div  style='float:left;width:100px;margin-left:3px;text-align:center;vertical-align:center;padding-top:4px;border: thin solid grey' onClick='showSessionVariables()'>";
			echo "Session:" . count($sessionarray);
			echo "	</div>";
			echo "	<div  style='float:left;width:100px;margin-left:3px;text-align:center;vertical-align:center;padding-top:4px;border: thin solid grey' onClick='showDevParams()'>";
			echo "Params:" . (count($_GET) + count($_POST));
			echo "	</div>";
			echo "	<div  style='float:left;width:100px;margin-left:3px;text-align:center;vertical-align:center;padding-top:4px;border: thin solid grey' onClick='showDevLangkeys()'>";
			echo "Langkeys:" . count($langarray);
			echo "	</div>";
			echo "	<div  style='float:left;width:100px;margin-left:3px;text-align:center;vertical-align:center;padding-top:4px;border: thin solid grey' onClick='showDevModules()'>";
			echo "Modules:" . count($modules);
			echo "	</div>";
			echo "	<div  style='float:left;width:100px;margin-left:3px;text-align:center;vertical-align:center;padding-top:4px;border: thin solid grey' onClick='showDevRequests()'>";
			echo "Requests:" . count($requests);
			echo "	</div>";
			echo "	<div  style='float:left;width:100px;margin-left:3px;text-align:center;vertical-align:center;padding-top:4px;border: thin solid grey' onClick='showDevQueries()'>";
			echo "Queries:" . count($queries);
			echo "	</div>";
			echo "	<div  style='float:left;width:100px;margin-left:3px;text-align:center;vertical-align:center;padding-top:4px;border: thin solid grey' onClick='showDevRoles()'>";
			echo "Roles: " . count($roles);
			echo "	</div>";
			echo "	<div  style='float:left;width:100px;margin-left:3px;text-align:center;vertical-align:center;padding-top:4px;border: thin solid grey' onClick='showDevMenu()'>";
			echo "Menu: " . count($menuitems);
			echo "	</div>";
			echo "	<div  style='float:left;width:100px;margin-left:3px;text-align:center;vertical-align:center;padding-top:4px;border: thin solid grey' onClick='showDevTools()'>";
			echo "Tools";
			echo "	</div>";
			echo "	<div  style='float:left;width:100px;margin-left:3px;text-align:center;vertical-align:center;padding-top:4px;border: thin solid grey' onClick='showDevClasses()'>";
			echo "Classes";
			echo "	</div>";
			echo "	<div  style='clear:both'>";
			echo "	</div>";
			echo "</div>";
				
			
			echo "<script>";
			echo "	function hideAll(item) {";
			echo "		if(item != 'sessionvariables') $('#sessionvariablediv').hide();";
			echo "		if(item != 'params') $('#devparamsdiv').hide();";
			echo "		if(item != 'langkeys') $('#devlangkeysdiv').hide();";
			echo "		if(item != 'modules') $('#devmodulesdiv').hide();";
			echo "		if(item != 'requests') $('#devrequestsdiv').hide();";
			echo "		if(item != 'queries') $('#devqueriesdiv').hide();";
			echo "		if(item != 'roles') $('#devrolesdiv').hide();";
			echo "		if(item != 'menu') $('#devmenudiv').hide();";
			echo "		if(item != 'classes') $('#devclassesdiv').hide();";
			echo "		if(item != 'tools') $('#devtoolsdiv').hide();";
			echo "	}";
			echo "</script>";
				
			
			echo "<script>";
			echo "	function showSessionVariables() {";
			echo "		hideAll('sessionvariables');";
			echo "		$('#sessionvariablediv').toggle();";
			echo "	}";
			echo "</script>";
			
			echo "<script>";
			echo "	function showDevParams() {";
			echo "		hideAll('params');";
			echo "		$('#devparamsdiv').toggle();";
			echo "	}";
			echo "</script>";

			echo "<script>";
			echo "	function showDevLangkeys() {";
			echo "		hideAll('langkeys');";
			echo "		$('#devlangkeysdiv').toggle();";
			echo "	}";
			echo "</script>";
				
			echo "<script>";
			echo "	function showDevModules() {";
			echo "		hideAll('modules');";
			echo "		$('#devmodulesdiv').toggle();";
			echo "	}";
			echo "</script>";
			
			echo "<script>";
			echo "	function showDevRequests() {";
			echo "		hideAll('requests');";
			echo "		$('#devrequestsdiv').toggle();";
			echo "	}";
			echo "</script>";
			
			echo "<script>";
			echo "	function showDevQueries() {";
			echo "		hideAll('queries');";
			echo "		$('#devqueriesdiv').toggle();";
			echo "	}";
			echo "</script>";
			
			echo "<script>";
			echo "	function showDevRoles() {";
			echo "		hideAll('roles');";
			echo "		$('#devrolesdiv').toggle();";
			echo "	}";
			echo "</script>";
				
			echo "<script>";
			echo "	function showDevMenu() {";
			echo "		hideAll('menu');";
			echo "		$('#devmenudiv').toggle();";
			echo "	}";
			echo "</script>";
			
			echo "<script>";
			echo "	function showDevTools() {";
			echo "		hideAll('tools');";
			echo "		$('#devtoolsdiv').toggle();";
			echo "	}";
			echo "</script>";
			

			echo "<script>";
			echo "	function showDevClasses() {";
			echo "		hideAll('classes');";
			echo "		$('#devclassesdiv').toggle();";
			echo "	}";
			echo "</script>";
				
			
			echo "<div id='sessionvariablediv' style='width:100%;text-align:center;vertical-align:center;padding-top:4px;border-bottom: thin solid grey; display:none;background-color:orange;'>";
			echo "<table>";
			foreach($sessionarray as $index => $value) {
				if (is_array($value)) {
					echo "<tr><td style='text-align:left;padding-left:20px;'>" . $index . "</td><td style='padding-left:20px;text-align:left;'>Array " . print_r($value) . "</td></tr>";
				} else {
					echo "<tr><td style='text-align:left;padding-left:20px;'>" . $index . "</td><td style='padding-left:20px;text-align:left;'>" . $value . "</td></tr>";
				}
			}
			echo "</table>";
			echo "</div>";

			echo "<div id='devparamsdiv' style='width:100%;text-align:center;vertical-align:center;padding-top:4px;border-bottom: thin solid grey; display:none;background-color:orange;'>";
			echo "<table>";
			foreach($_GET as $index => $value) {
				echo "<tr><td style='padding-left:20px;text-align:left;'>GET</td><td style='text-align:left;padding-left:20px;'><td style='text-align:left;padding-left:20px;'>" . $index . "</td><td style='padding-left:20px;text-align:left;'>" . $value . "</td></tr>";
			}
			foreach($_POST as $index => $value) {
				echo "<tr><td style='padding-left:20px;text-align:left;'>POST</td><td style='text-align:left;padding-left:20px;'>" . $index . "</td><td style='padding-left:20px;text-align:left;'>" . $value . "</td></tr>";
			}
				
			echo "</table>";
			echo "</div>";
				
			echo "<div id='devlangkeysdiv' style='width:100%;text-align:center;vertical-align:center;padding-top:4px;border-bottom: thin solid grey; display:none;background-color:orange;'>";
			echo "<table>";
			foreach($langarray as $index => $value) {
				echo "<tr><td style='text-align:left;padding-left:20px;'>" . $index . "</td><td style='padding-left:20px;text-align:left;'>" . $value . "</td></tr>";
			}
			echo "</table>";
			echo "</div>";
				
			echo "<div id='devmodulesdiv' style='width:100%;text-align:center;vertical-align:center;padding-top:4px;border-bottom: thin solid grey; display:none;background-color:orange;'>";
			echo "<table>";
			foreach($modules as $index => $module) {
				echo "<tr><td style='text-align:left;padding-left:20px;'>" . $module->name . "</td><td style='padding-left:20px;text-align:left;'>" . $module->active . "</td></tr>";
			}
			echo "</table>";
			echo "</div>";
				
			echo "<div id='devrequestsdiv' style='width:100%;text-align:center;vertical-align:center;padding-top:4px;border-bottom: thin solid grey; display:none;background-color:orange;'>";
			echo "<table>";
			foreach($requests as $index => $request) {
				echo "<tr><td style='text-align:left;padding-left:20px;'>" . $index . "</td><td style='padding-left:20px;text-align:left;'>" . $request . "</td></tr>";
			}
			echo "<tr><td colspan=2 style='text-align:left;padding-left:20px;'><a href='" .  ($_SERVER['REQUEST_URI'] . "&system_clearrequestlog=1") . "'>clear log</a></td></tr>";
			echo "</table>";
			echo "</div>";
				
			echo "<div id='devqueriesdiv' style='width:100%;text-align:center;vertical-align:center;padding-top:4px;border-bottom: thin solid grey; display:none;background-color:orange;'>";
			echo "<table>";
			foreach($queries as $index => $query) {
				echo "<tr><td style='text-align:left;padding-left:20px;'>" . $index . "</td><td style='padding-left:20px;text-align:left;'>" . $query . "</td></tr>";
			}
			echo "<tr><td colspan=2 style='text-align:left;padding-left:20px;'><a href='" .  ($_SERVER['REQUEST_URI'] . "&system_clearquerieslog=1") . "'>clear log</a></td></tr>";
			echo "</table>";
			echo "</div>";
				
			echo "<div id='devrolesdiv' style='width:100%;text-align:center;vertical-align:center;padding-top:4px;border-bottom: thin solid grey; display:none;background-color:orange;'>";
			echo "<table>";
			//foreach($queries as $index => $query) {
			//	echo "<tr><td style='text-align:left;padding-left:20px;'>" . $index . "</td><td style='padding-left:20px;text-align:left;'>" . $query . "</td></tr>";
			//}
			echo "<tr><td colspan=3 style='text-align:left;padding-left:20px;'>Roles here</td></tr>";
			echo "</table>";
			echo "</div>";


			echo "<div id='devmenudiv' style='width:100%;text-align:center;vertical-align:center;padding-top:4px;border-bottom: thin solid grey; display:none;background-color:orange;'>";
			echo "<table>";
			//foreach($queries as $index => $query) {
			//	echo "<tr><td style='text-align:left;padding-left:20px;'>" . $index . "</td><td style='padding-left:20px;text-align:left;'>" . $query . "</td></tr>";
			//}
			echo "<tr><td colspan=3 style='text-align:left;padding-left:20px;'>Menu here</td></tr>";
			echo "</table>";
			echo "</div>";
			
			echo "<div id='devclassesdiv' style='width:100%;text-align:center;vertical-align:center;padding-top:4px;border-bottom: thin solid grey; display:none;background-color:orange;'>";
			echo "<table>";
			//echo "<tr><td colspan=3 style='text-align:left;padding-left:20px;'>Tools:</td></tr>";
			echo "<tr><td colspan=3 style='text-align:left;padding-left:20px;'><a href='javascript:classcrawler()'>Check classes</a></td></tr>";
			echo "</table>";
			echo "</div>";
				
			
			
			echo "<script title='headerscript' type='text/javascript'>";
			echo "	function classcrawler() {";
			
			//echo "			console.log(' ------------------------------ ');";
					
			//echo "		console.log(' - styleheetcount - '+document.styleSheets.length);";
					
			echo "for( var i = 0; i< document.styleSheets.length; i++ ){";
			echo "		stylesheet = document.styleSheets[i];";
			//echo "		console.log(' - '+stylesheet.href);";
			
			
			
			//echo "		console.log(' - '+stylesheet.href);";
			
			//echo "		ruleList = stylesheet.cssRules;";
			//echo "		for (j=0; j<ruleList.length; j++)";
			//echo "		{";
			//echo "				console.log(' ... ' +ruleList[j].cssText);";
			//echo "		}";
			//echo "		console.log(' m - '+stylesheet.media);";
			//echo "		console.log(' - '+stylesheet.type);";
			echo "}";
			
			//echo "			console.log(' - no more stylesheets');";
				

			echo "for( var i = 0; i< document.scripts.length; i++ ){";
			echo "		script = document.scripts[i];";
			//echo "		console.log(' - '+stylesheet.href);";
			echo "		if (script.src != '') {";
			echo "			if (script.title == '') {";
			//echo "				console.log(' - '+script.src);";
			echo "			} else {";
			//echo "				console.log(' - '+script.title);";
			echo "			}";
			echo "		}";
			echo "}";
				
			
			//echo "			console.log(' ....');";
				
			
			// find all classnames, for checkin is this class name defined in template
			echo "			var all = document.getElementsByTagName('*');";
			echo "			var classnamelist = new Object();";
			echo "			for (var i=0, max=all.length; i < max; i++) {";
			echo "				var classname = all[i].className;";
			echo "				if (classname != '') {";
			//echo "					console.log(' -- ' + classname);";
			echo "					if (classname in classnamelist) {";
			//echo "						console.log('exits');";
			echo "						classnamelist[classname]++;";
			echo "					} else {";
			echo "						classnamelist[classname] = 1;";
			echo "					}";
			echo "				}";
			echo "			}";
			
			
			
			
			
			//echo "			console.log('classnamelist - '+Object.keys(classnamelist).length);";
			//echo "			$.each(classes, function (index, value) {";
			echo "			for(var item in classnamelist) {";
			//echo "				console.log(''+index+' - ' + value);";
			//echo "				console.log(''+item+' - ' + classnamelist[item]);";
			echo "			}";
				
			//echo "			console.log(' ------------------------------ ');";
				
			echo "	}";
			echo "</script>";
					
			
			echo "<div id='devtoolsdiv' style='width:100%;text-align:center;vertical-align:center;padding-top:4px;border-bottom: thin solid grey; display:none;background-color:orange;'>";
			echo "<table>";
			//echo "<tr><td colspan=3 style='text-align:left;padding-left:20px;'>Tools:</td></tr>";
			echo "<tr><td colspan=3 style='text-align:left;padding-left:20px;'><a href='" .  getUrl("admin/service/modulecrawler") . "'>Modulecrawler</a></td></tr>";
			echo "</table>";
			echo "</div>";
			
			
			// this is called when page is loaded, for updateheader content
			echo "	<script>";
			echo "		$( document ).ready(function() {";
			echo "			classcrawler();";	
			echo "		});";
			echo "	</script>";
			
				
			// muuta div layoutiksi, hae googlella 'how tto layout html'
			//echo "<div  style='width:100%;height:20px;background-color:#ffcc00;border-style:solid;border-width:0px 0px 1px 0px;border-color:#555;padding:0px;spacing:0px;'>";
			
			/*
			foreach($_SESSION as $index => $value) {
				echo "<br>session - " . $index . " = " . $value;
			}
			
			echo "\n<table style='width:100%;height:20px;background-color:#ffcc00;border-style:solid;border-width:0px 0px 1px 0px;border-color:#555;padding:0px;spacing:0px;'>";
			echo "\n	<tr>";
			echo "\n		<td style='width:140px;'><b>Module:</b> " . $this->registry->modulename . "</td>";
			echo "\n		<td style='width:140px;'><b>Controller:</b> " . $this->registry->controllername . "</td>";
			echo "\n		<td style='width:140px;'><b>Action:</b> " . $this->registry->action . "</td>";
			echo "\n		<td id=sessionbutton style='width:140px;'><b>Session:</b>" . count($_SESSION) . "</td>";
			echo "\n		<td style=''></td>";
			echo "\n	</tr>";
			echo "\n	<tr>";
			echo "\n		<td id=sessiontd colspan=4 style='width:80px;height:0px;padding:0px;spacing:0px;background-color:blue;overflow:hidden;display:block'>";
			echo "\n				<table  style='width:100%;height:20px;background-color:#ffcc00;border-style:solid;border-width:0px 0px 1px 0px;border-color:#555;padding:0px;spacing:0px;' cellpadding=0 cellspacing=0>";
			echo "\n					<tr>";
			echo "\n						<td style='width:140px;'></td>";
			echo "\n						<td style='width:140px;'></td>";
			echo "\n					</tr>";
			foreach($_SESSION as $key => $value) {
				echo "\n					<tr>";
				echo "\n						<td style=''>" . $key . "</td>";
				echo "\n						<td style=''>" . $value . "</td>";
				echo "\n					</tr>";
			}
			echo "\n				</table>";
			echo "\n		</td>";
			echo "\n	</tr>";
			echo "\n</table>";
			
			
			echo "<script>";
			echo "	$('#sessionbutton').click(function() {";
			echo "		if ($(\"#sessiontd\").is(\":visible\") == true) {";
			echo "			$(\"#sessionvariables\").hide();";
			echo "			$(\"#sessiontd\").hide();";
			echo "		} else {";
			echo "			$(\"#sessionvariables\").show();";
			echo "			$(\"#sessiontd\").show();";
			echo "		}";
			echo "	});";
			echo "</script>";
			*/	
			
			//echo "\n<div align='left' style='display:table;width:100%;min-height:660px;height:auto !important;'>";
			//echo "\n<div style='display:table-cell;vertical-align:middle;'>";
		} else {
			
			//echo "<br>Dev no";
			
			//echo "\n<div align='left' style='display:table;width:100%;min-height:660px;height:auto !important;'>";
			//echo "\n<div style='display:table-cell;vertical-align:middle;'>";
		}
		
	}
	

	protected function generateFooter() {
		echo "\n</body>";
		echo "\n</html>";
	}

	
	protected function getErrormessageCount() {
		
	}
	
	
	
	protected function generateErrormessages() {
		
		if (!isset($_SESSION['errorcount'])) $_SESSION['errorcount'] = 0;
		
		//echo "<br>erorrmessagecount - " . $_SESSION['errorcount']; 
		
		if ($_SESSION['errorcount'] != 0) {
			$messagecount = $_SESSION['errorcount'];
			for($i = 0;$i<$messagecount;$i++) {
				echo "<div style='background-color:pink;border: thin solid red;width:100%'>";
				echo "" . $_SESSION['errormessage-'. $i] . "";
				echo "</div>";
			}
			$_SESSION['errorcount'] = 0;
		} else {
			//echo "<br>No errormessages";
		}
			
		
	}
	
	
	
	
	public function showContent($module, $filename, $locationpath) {
		$this->show($module, $filename);
	}
	
	
	public function getModule() {
		return $this->module;
	}
	
	
	abstract function show($module, $filename);
	
	// Error messaget pitää tehdä template kohtaisesti, koska sen ulkoasu on täysin
	// templatesta riippuvainen. Ainoa kriteeri on, että se on divin sisässä jonka nimi 
	// on messagediv...
	abstract function printErrorMessages();
	
	
	/*	
	function show($name, $full = true) {
		
		//if ($template == '') {
			// käytetään oletustemplatea, tai ehkä modulin templatea, jos sellainen läytyy?
		//}
		
		
		
		$path = SITE_PATH . 'templates' . DIRECTORY_SEPARATOR . 'menu' . '.layout.php';
		
		$this->generateHeader();
		include ($path);
		$this->generateFooter();
		
		
		
		/*
		$path = SITE_PATH . 'modules' . DIRECTORY_SEPARATOR . $this->module . DIRECTORY_SEPARATOR . $name . '.php';

		if (file_exists($path) == false)
		{
			throw new Exception('Template not found in '. $path);
			return false;
		}

		// Load variables
		foreach ($this->vars as $key => $value)
		{
			$$key = $value;
		}		
		
		include ($path);
		* /
	}
	*/

}

?>