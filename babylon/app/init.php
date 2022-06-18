<?php
	
/**
 *
 * Frameworkin instanssin perusasetukset ja yleiskäyttäiset funktiot.
 *
 * @author Petri Uusitalo
 * @copyright Babelsoft Oy, 2016
 *
 */
	
	include 'controller.class.php';
	include 'registry.class.php';
	include 'router.class.php';
	include 'template.class.php';
	include 'module.class.php';


	define('_white','#FFFFFF');
	define('_black','#000000');
	define('_dimgray','#101010');
	define('_gray','#A0A0A0');
	define('_lightgray','#C0C0C0');
	define('_red','#AA0000');
	
	define('SCOPE_MINIMAL',1);
	define('SCOPE_BASIC',1);
	define('SCOPE_FULL',1);
	
	session_name(APPLICATIONKEY . "SESSIONID");
	session_start();
	
	$sitepath = realpath(dirname(__FILE__));
	//echo "<br>Sitepath - " . $sitepath;
	$lastpos = strrpos($sitepath, DIRECTORY_SEPARATOR);
	$sitepath = substr($sitepath,0,$lastpos);
	define ('SITE_PATH', $sitepath . DIRECTORY_SEPARATOR);
	define ('CENTRAL_LOGIN_DATABASE', 'babelsoftf_login');
	
	$urlparamcomments = false;
	
	
	/**
	 * TODO: 17.10.21  Poistin tämän käytöstä, kun oletan että tätä ei käytännössä käytetä missään...
	 * 
	 * @param Registry $registry
	 */
	/*
	function loadSession(&$registry) {
		foreach($_SESSION as $index => $value) {
			if (is_array($value)) {
				//echo "<br>Sessionvariable - " . $index . " - array:" . count($value);
			} else {
				//echo "<br>Sessionvariable - " . $index . " - " . $value;
			}
			$registry->$index = $value;
		}
	}
	*/
	
	
	
	function __autoload($class_name) {
		
		if ($class_name == 'User') {
			$file = SITE_PATH  . 'app' . DIRECTORY_SEPARATOR . "user.class.php";
		}
		
		/*
		if (substr($class_name,0,2) == 'UI') {
			$filename = strtolower($class_name) . '.class.php';
			$file = SITE_PATH  . 'classes' . DIRECTORY_SEPARATOR . $filename;
			
			if (file_exists($file) == false) {
				$filename = strtolower($class_name) . '.class.php';
				$file = SITE_PATH  . 'lib' . DIRECTORY_SEPARATOR . $filename;
				if (file_exists($file) == false) return false;
				include ($file);
				return;
			}
		}
		*/
		
		
		$filename = strtolower($class_name) . '.class.php';
		//$file = SITE_PATH  . 'classes' . DIRECTORY_SEPARATOR . $filename;
		//$file = SITE_PATH  . 'lib' . DIRECTORY_SEPARATOR . 'menu' . DIRECTORY_SEPARATOR . $filename;
		
		$file = SITE_PATH  . 'classes' . DIRECTORY_SEPARATOR . $filename;
		//echo "<br>File - " . $file;
		
		if (file_exists($file) == false) {
			$filename = strtolower($class_name) . '.class.php';
			//$file = SITE_PATH  . 'classes' . DIRECTORY_SEPARATOR . $filename;
			$file = SITE_PATH  . 'classes' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . $filename;
			//echo "<br>File - " . $file;
		}	
		
		if (file_exists($file) == false) {
			//$filename = strtolower($class_name) . '.class.php';
			//$file = SITE_PATH  . 'libui' . DIRECTORY_SEPARATOR . $filename;
			$filename = strtolower($class_name) . '.class.php';
			$file = SITE_PATH  . 'classes' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR . $filename;
			//echo "<br>File - " . $file;
		}
		
		if (file_exists($file) == false) {
			//$filename = strtolower($class_name) . '.class.php';
			//$file = SITE_PATH  . 'libui' . DIRECTORY_SEPARATOR . $filename;
			$filename = strtolower($class_name) . '.class.php';
			$file = SITE_PATH  . 'lib' . DIRECTORY_SEPARATOR . 'worder' . DIRECTORY_SEPARATOR . $filename;
			//echo "<br>File - " . $file;
		}
		
		
		//echo "<br>File last - " . $file;
		
		if (file_exists($file) == false) {
			return false;
		}

		
		// tämä saattaa olla hieman hidas, koska tsekataan tiedoston olemassaoloa usein turhaan
		/*
		if (file_exists($file) == false) {
			$filename = strtolower($class_name) . '.class.php';
			$file = SITE_PATH  . 'libui' . DIRECTORY_SEPARATOR . $filename;
			if (file_exists($file) == false) {
				$filename = strtolower($class_name) . '.class.php';
				$file = SITE_PATH  . 'lib' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR . $filename;
				if (file_exists($file) == false) return false;
				include ($file);
				return;
			}
			include ($file);
			return;
		}
		*/
		
		include ($file);
	}
	
	
	
	function showTasksSection($registry, $sectionwidth, $title = "Tasks") {

		if (count($registry->system->minitasks) == 0 && count($registry->system->tasks) == 0) {
			echo "<br>No tasks";
			//return;
		}
		
		$alltasks = array();
		foreach($registry->system->minitasks as $index => $task) {
			$task->stateID = $task->state;
			$alltasks[$index] = $task;
		}
		foreach($registry->system->tasks as $index => $task) {
			$alltasks[$index] = $task;
		}
		
		$section = new UITableSection($title,$sectionwidth);
		$section->setOpen(true);
		$section->editable(true);
		$section->showLineNumbers(true);
		$section->setFramesVisible(true);
		$section->setButtonAlign(UIComponent::VALIGN_BOTTOM);
		
		$section->setLineAction(UIComponent::ACTION_FORWARD, 'tasks/tasks/showtask', 'taskID');
		
		
		//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $searchsection->getID(), 'Etsi lause');
		//$section->addButton($button);
		//$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/lessons/removesentence&lessonID=' . $registry->lesson->lessonID, 'sentenceID');
		
		$column = new UISortColumn("ID", "taskID", "taskID");
		$section->addColumn($column);
		
		$column = new UISortColumn("ID", "minitaskID", "minitaskID");
		$section->addColumn($column);
		
		$column = new UISortColumn("Name", "name", "name");
		$section->addColumn($column);
		
		$column = new UISortColumn("State", "stateID", "stateID");
		$section->addColumn($column);
		//$column = new UISelectColumn("State", "name", "stateID", $registry->taskstates);
		//$section->addColumn($column);
		
		$column = new UISortColumn("Checked", "endtime", "endtime");
		$column->setFormatter(Column::COLUMNTYPE_DATETIME);
		$section->addColumn($column);
		
		$section->setData($alltasks);
		$section->show();
		
	}
	

	/**
	 * Tämän funktion avulla haetaan kaikki 
	 * 
	 * @param string $paramname
	 * @return int
	 */
	function getIntParam($paramname) {
		global $urlparamcomments;
		if ($urlparamcomments) echo "<br>" . $paramname . " - " . $_GET[$paramname];
		//$param = intval($_GET[$paramname]);
		//if (is_int($param) == false) {
		//	echo "<br>Parametri - " . $paramname . " ei ole kokonaisluku";
		//	exit;
		//}
		return intval($_GET[$paramname]);
	}
	
	
	// Pitääköhän tämän asettaa session muuttujaan?
	function setUrlParamComments($boole) {
		global $urlparamcomments;
		echo "<br>setting urlparametercomments";
		$urlparamcomments = true;
	}
	
	
	// TODO: jos parametria ei ole, niin mitä tehdään, heitetäänkö error vai palautetaanko nolla.
	//			pitänee heittää nolla, koska nolla palautettaessa ei tiedetä oliko nolla oikeasti vai määrittelemätön
	function getFloatParam($paramname) {
		global $urlparamcomments;
		if ($urlparamcomments) echo "<br>" . $paramname . " - " . $_GET[$paramname];
		if (isset($_GET[$paramname])) {
			if ($_GET[$paramname] == "") {
				//echo "<br>Param emtpy xx";
				return 0;
			}
			return floatval(str_replace(",", ".", trim($_GET[$paramname])));
		} 
		echo "<br>parameter not setted";
		return null;
	}
	
	
	function getFirstItem($items) {
		foreach($items as $index => $item) {
			return $item;
		}
	}
	
	
	function sqlDateToStr($date) {
		if ($date == '0000-00-00') {
			$valuestr = "Ei asetttu";
		} else {
			$day = substr($date, 8);
			$month = substr($date, 5, 2);
			$year = substr($date, 0, 4);
			$datestr = $year . "-" . $month . "-" . $day ;
			$valuestr = $day . "." . $month . "." . $year;
		}
		return $valuestr;
	}
	
	
	function startsWith ($string, $startString)
	{
		if (is_array($string)) return false;
		$len = strlen($startString);
		return (substr($string, 0, $len) === $startString);
	}
	
	
	function dateStrToSql($date) {
		$day = substr($date, 0, 2);
		$month = substr($date, 3, 2);
		$year = substr($date, 6, 4);
		$datestr = $year . "-" . $month . "-" . $day ;
		return $datestr;
	}
	
	
	
	function getSetting($name, $defaultValue = NULL, $con = NULL) {
	
		if ($con == NULL) {
			global $mysqli;
		} else {
			$mysqli = $con;
		}
		//echo "<br>Name - " . $name;
		//echo "<br>Database - " . $_SESSION['database'];
	
		$sql = "SELECT * FROM system_settings WHERE Name='" . $name . "' AND SystemID=" . $_SESSION['systemID'];
		$result = $mysqli->query($sql);
		if (!$result) {
			echo "<br>" . $sql;
			echo "<br>Settings.class.php - getSetting failed: " . $mysqli->error;
			die();
		}
	
		$found = false;
		while($row = $result->fetch_array()) {
			$value = $row['Value'];
			$found = true;
		}
		if ($found == false) $value = $defaultValue;
		return $value;
	}
	

	function getFileExtension($filename) {
		$pos = strpos($filename, ".");
		$str = substr($filename, $pos+1);
		return $str;
	}
	
	
	
	function parseMultilangString($multilangtext, $languageID) {
		
		//echo "<br>mult - " . $multilangtext;
		
		$multilangarray = explode('[',$multilangtext);
		if (count($multilangarray) == 0) {
			echo "<br>multilang nothing to parse";
			return $multilangtext;
		} else {
			foreach ($multilangarray as $index => $value) {
				if ($value != '') {
					$itemarray = explode(']', $value);
					if (count($itemarray) == 1) {
						//echo "<br>---no other value - '" . $value . "'";	
					} else {
						//echo "<br>---" . $itemarray[0] . "---" . $itemarray[1];
						if ($itemarray[0] == $languageID) return $itemarray[1];
					}
				}
			}
		}
		return $multilangtext;
	}
	
	
	function createMultilangString($list) {
		$str = "";
		foreach($list as $languageID => $name) {
			$str = $str . "[" . $languageID . "]" . $name;
		}
		return $str;
	}
	

	function parseMultilangStringWithEmpty($multilangtext, $languageID) {
	
		//echo "<br>mult - " . $multilangtext;
	
		$multilangarray = explode('[',$multilangtext);
		if (count($multilangarray) == 0) {
			echo "<br>multilang nothing to parse";
			return $multilangtext;
		} else {
			foreach ($multilangarray as $index => $value) {
				if ($value != '') {
					$itemarray = explode(']', $value);
					if (count($itemarray) == 1) {
						//echo "<br>---no other value - '" . $value . "'";
					} else {
						//echo "<br>---" . $itemarray[0] . "---" . $itemarray[1];
						if ($itemarray[0] == $languageID) return $itemarray[1];
					}
				}
			}
		}
		return "";
	}
	
	
	function parseMultilangArray($multilangString, $defaultlanguageID = 2) {
	
		$list = array();
		
		if (($multilangString == null) || ($multilangString == "")) {
			//echo "<br>Nofound - 1 - " . $multilangString;
			return $list;
		}
		
		if (strpos($multilangString, "]") == false) {
			
			//echo "<br>Nofound - 2 - rrr " . strpos($multilangString,"]") . " - " . $multilangString;
			$list[$defaultlanguageID] = $multilangString;
			return $list;
		}
		
		$temporary = explode("[",$multilangString);
		
		foreach($temporary as $index => $row) {
			if ($row!=null) {
				$mini_temporary = explode("]",$row);
				$lang_id 		= str_replace("[","",$mini_temporary[0]);
				$lang_string		= $mini_temporary[1];	
				//echo "ID=".$lang_id.", NAME = ".$lang_value;
				$list[$lang_id] = $lang_string;
			}
		}	
		
		return $list;
	}
	
	
		
	function getResourceText($key, $languageID = null) {
		if (isset($_SESSION["R_" . $key])) return $_SESSION["R_" . $key];
		$resourcetext = Table::loadRow("system_resourcetext", "WHERE Resourcekey='"  . $key . "'");
		if ($resourcetext == null) die('<br><br>resource text not found - ' . $key);
		$_SESSION["R_" . $key] = $resourcetext->value;
		return $resourcetext->value;
	}
	
	
	
	function getAccessLevel($accesskey, $comments = false) {
		
		//echo "<br>Usergroup - " . $_SESSION['usergroupID'];
		//clearaccesskeys();
		//var_dump($_SESSION);
		
		// Prefixi tarvitaan, koska muuten ei pystytä erottamaan tätä muista session muuttujista, voidaan tyhjätä pelkät accesskeyt
		if (isset($_SESSION["AK_" . $accesskey])) {
			if ($comments) echo "<br>Has acceskey - " . $accesskey;
			return $_SESSION["AK_" . $accesskey];
		} else {
			if ($comments) echo "<br>Load acceskey - " . $accesskey;
				
			// TODO: vaikuttaako tulevat user office, company, branch, departmenet?
			$accesskey = Table::loadRow("system_accesskeys", "WHERE Name='" . $accesskey . "'", true);
			$accessrow = Table::loadRow("system_usergroupaccessrights", "WHERE AccesskeyID=" . $accesskey->accesskeyID . " AND UsergroupID=" . $_SESSION['usergroupID'], true);
			if ($accessrow != null) {
				$accessrow->accesslevel;
				$_SESSION["AK_" . $accesskey->name] = $accessrow->accesslevel;
				return $accessrow->accesslevel;
			} else {
				$_SESSION["AK_" . $accesskey->name] = 0;
			}
		}
		
		return 0;
	}
	
	
	function getCurrentAccessRights($usergroupID) {
		
		$accessrows = Table::load("system_accessrights", "WHERE UsergroupID=" . $usergroupID);
		
		$accessrightlist = array();
		if ($accessrows == null) {
			echo "<br>accessrows is null";
			return $accessrightlist;
		}
		
		foreach($accessrows as $index =>  $row) {
			$accessrightlist[$row->accesskey] = $row->accesslevel;
			//echo "<br>get - " . $row->accesskey . " = " . $row->accesslevel;
		}
		return $accessrightlist;	
	}
	
	

	/**
	 * 18.10.21		Ei käytetä mistään, mutta lienee hyödyllinen kuitenkin
	 * 				Käsitellään sitten tarkemmin kun säädetään käyttöoikeuksia
	 */
	function clearaccesskeys() {
		// Poista session muuttujasta kaikki R_-alkuiste tekstit
		foreach($_SESSION as $accesskey =>  $value) {
			if (substr($accesskey,0,3) === "AK_") {
				echo "<br>match - " . $accesskey;
				unset($_SESSION[$accesskey]);
			} else {
				echo "<br>---- " . $accesskey;
			}
		}
	}
	
	
	// TODO: 17.10.21	Tässä tulee tosiaan se ongelma, kun avataan uusi ikkuna, että tämä menee
	//					epäsynkkaan kun eri lokaatiot ovat avoinna eri ikkunoilla. Pitäisi jotenkin
	//					joko saada urliin mukaan tabID, mielellään piilotettuna.
	//			
	function updateActionPath($actionname) {
		
		setPageTitle($actionname);
		
		if (isset($_GET['menuID'])) {				// Tänne tullaan kun openinnewwindow
			unset($_SESSION['actionpath']);
			//echo "<br>Clear actionpath";
			//$_SESSION['session_menuID'];
		}
		
		$actions = array();
		if (isset($_SESSION['actionpath'])) $actions = $_SESSION['actionpath'];
		//echo "<br>Actionname - " . $actionname . " - " . $_GET['menuID'];
		//$index = 0;
		//foreach($actions as $actionnametemp => $actionpath) {
			//echo "<br>" . $index . " - actions - " . $actionnametemp . " - " . $actionpath;
			//$index++;
		//}
		$url = $_SERVER['PHP_SELF'];
		$url = str_replace(NOFRAMESHANDLER,ROOTPHP, $url);		// korvataan pathista noframes.php kutsu
		
		$params = $_SERVER['QUERY_STRING'];
		$params = str_replace('&noframes','', $params); // actionpathiin halutaan reload framejen kanssa
		$actions[$actionname] = $url . "?" . $params;
		//echo "<br>-----------";
		//echo "<br>Actionname - " . $actionname . " - " . $actions[$actionname];
		//echo "<br>-----------";
		//$index = 0;
		//foreach($actions as $actionname => $actionpath) {
			//echo "<br>" . $index . " -- actions - " . $actionname . " - " . $actionpath;
			//$index++;
		//}
		$_SESSION['actionpath'] = $actions;
		$_SESSION['actionname'] = $actionname;
	}
	
	

	function generateMessages() {
	
		if (!isset($_SESSION['messagecount'])) $_SESSION['messagecount'] = 0;
	
		//echo "<br>erorrmessagecount - " . $_SESSION['errorcount'];
		echo "<div id=messagediv>";
		
		if ($_SESSION['messagecount'] != 0) {
			$messagecount = $_SESSION['messagecount'];
			for($i = 0;$i<$messagecount;$i++) {
				echo "<div style='background-color:#00ff00;;border: thin solid #006600;width:100%'>";
				echo "" . $_SESSION['message-'. $i] . "";
				echo "</div>";
			}
			$_SESSION['messagecount'] = 0;
		} else {
			//echo "<br>No errormessages";
		}
		echo "</div>";	
	
	}
	
	
	
	function printActionPath($registry) {
				
		echo "<div id=actionpathdiv style='clear:left;style='height:60px;'>";
		echo "	<div id=actionpathcontent style='font-size:18px;padding-top:6px;padding-left:6px;padding-bottom:16px;'>";
		$actions = array();
		if (isset($_SESSION['actionpath'])) $actions = $_SESSION['actionpath'];

		echo "<a href='" . getUrl('system/frontpage/index') . "'>Etusivu</a>";
		
		foreach($actions as $actionname => $actionpath) {
			echo " // ";
			echo "<a href='" . $actionpath . "'>" . $actionname . "</a>";
		}	
		echo "</div>";
		echo "</div>";
		
		// Messages: error ja success....
		echo "<div id=messagesdiv>";
		if (!isset($_SESSION['messagecount'])) $_SESSION['messagecount'] = 0;
		if ($_SESSION['messagecount'] > 0) {
			$messagecount = $_SESSION['messagecount'];
			for($i = 0;$i<$messagecount;$i++) {
				echo "<div class=tasksuccessmessagediv style='width:800px;'>";
				echo "" . $_SESSION['message-'. $i] . "";
				echo "</div>";
			}
			$_SESSION['messagecount'] = 0;
		}
		echo "</div>";
		
		// Taskbar
		echo "<script>";
		echo "		function createTaskBar(name, minitaskID, state) {";
		
		//echo "			console.log('createtaskbar - 1');";
		echo "			var table = document.createElement('table');";
		echo "			table.cellPadding = 0;";
		echo "			table.id = 'taskbar_'+minitaskID;";
		echo "			table.className = 'taskmessagediv';";
		echo "			table.style.width = '800px';";
				
		//echo "			console.log('createtaskbar - 2');";
		echo "			tr = document.createElement('tr');";
		echo "			td = document.createElement('td');";
		echo "			td.style.width = '620px';";
		echo "			td.innerHTML = name;";
		echo "			tr.appendChild(td);";

		echo "			if (state != 1) {";
		//echo "				console.log('createtaskbar - 3');";
		echo "				td = document.createElement('td');";
		echo "				td.style.width = '90px';";
		echo "				td.id = 'donesection_'+minitaskID;";
		echo "				button = document.createElement('button');";
		echo "				button.className = 'section-button';";
		echo "				button.textContent = 'Done';";
		echo "				button.style.width = '80px';";
		echo "				button.setAttribute('onClick', 'doneminitaskbuttonclicked(\''+minitaskID+'\')');";
		echo "				td.appendChild(button);";
		
		echo "				tr.appendChild(td);";
		//echo "				console.log('createtaskbar - 3');";
		echo "				td = document.createElement('td');";
		echo "				td.style.width = '90px';";
		echo "				button = document.createElement('button');";
		echo "				button.className = 'section-button';";
		echo "				button.textContent = 'Next';";
		echo "				button.style.width = '80px';";
		echo "				button.setAttribute('onClick', 'nextminitaskbuttonclicked(\''+minitaskID+'\')');";
		echo "				td.appendChild(button);";
		echo "				tr.appendChild(td);";
		
		echo "			} else {";
		echo "				td = document.createElement('td');";
		echo "				td.style.width = '90px';";
		echo "				table.className = 'tasksuccessmessagediv';";
		echo "				td.id = 'donesection_'+minitaskID;";
		echo "				td.innerHTML = 'Done1';";
		echo "				tr.appendChild(td);";
		echo "			}";
		
		
		echo "			table.appendChild(tr);";
		//echo "			console.log('createtaskbar - 4');";
		echo "			$('#taskbardiv').append(table);";
		echo "		}";
		echo "</script>";
		
		echo "<script>";
		echo "		function doneminitaskbuttonclicked(minitaskID) {";
		//echo "			console.log('doneminitaskbuttonclicked - '+minitaskID);";
		// json-haku, joka palauttaa ok
		//checkminitaskJSON
		//echo "			console.log('" . getUrl('tasks/tasks/checkminitaskJSON') . "&minitaskID='+minitaskID);";
		echo "			$.getJSON('" . getUrl('tasks/tasks/checkminitaskJSON') . "&minitaskID='+minitaskID,'',function(data) {";
		//echo "				console.log('done success');";
		//echo "				console.log('done success - ' + data[0].success);";
		echo "				if (data[0].success == 1) {";
		//echo "					console.log('done success 2');";
		echo "					var tdID = 'donesection_'+minitaskID;";
		//echo "					console.log(tdID);";
		echo "					$('#'+tdID).empty();";
		echo "					$('#'+tdID).text('Done2');";
		echo "					$('#'+tdID).css('text-align', 'left');";
		//echo "					$('#'+tdID).css('background-color', 'lightgreen');";
		//echo "					$('#taskbar_'+minitaskID).css('border-color', '#3cb371');";
		//echo "					$('#taskbar_'+minitaskID).css('background-color', 'lightgreen');";
		echo "					$('#taskbar_'+minitaskID).removeClass('taskmessagediv');";
		echo "					$('#taskbar_'+minitaskID).addClass('tasksuccessmessagediv');";
		
		echo "				}";
		echo "			});";
		echo "		}";
		echo "</script>";
		
		echo "<script>";
		echo "		function nextminitaskbuttonclicked(minitaskID) {";
		echo "			console.log('nextminitaskbuttonclicked - ', minitaskID);";
		// Tämä suorittaa latausoperaation olisikohan ihan loadpage? En tosin tiedä misten next task saadaan...
		//  - onko next kiinteä, vai onko se seuraava avoin minitask taskin sisällä? Seuraava avoin.
		echo "			window.location = '" . getUrl('tasks/tasks/nexttask') . "&minitaskID='+minitaskID;";
		echo "		}";
		echo "</script>";
		
		// Tähän tsekkaus onko ao. actionille jotain taskeja...
		//echo "<br>check actionpath tasks...";
		echo "<div id=taskbardiv style='margin-left:6px;'>";
		if (isset($_SESSION['AC_' . $_SESSION['current_location']])) {
			//echo "<br> -- actionfound - " . $_SESSION['AC_' . $_SESSION['current_location']];
			
			$tablelist = explode(",", $_SESSION['AC_' . $_SESSION['current_location']]);
			foreach($tablelist as $index => $tableID) {
				//echo "<br> -- -- tableID - " . $tableID;
				//$tablestruct = Table::getTableWithID($tableID);
				//$key = $tablestruct->getKeyColumn();
				$searchID = $_GET['id'];
				
				// TODO: Minitaskit pitäisi ladata jossain muualla, systemissä kyllä, mutta missä...
				//			ehkä setControllerLocation olisi otollinen paikka, siellä asetetaan current_location..
				//$minitasks = Table::load("tasks_minitasks", "WHERE TargettableID=" . $tableID . " AND TargetID=" . $searchID . " AND (State=0 OR State=1)");
				//echo "<br>foud - " . count($minitasks);
				
				if (count($registry->system->minitasks) > 0) {
					foreach($registry->system->minitasks as $taskID => $minitask) {
						if ($minitask->state == 0) {
						
							/*
							echo "<table id=taskbar_" . $minitask->minitaskID . " cellpadding='0' cellpadding='0'  class=tasksuccessmessagediv style=';width:800px;margin-bottom:5px;'>";
							echo "	<tr>";
							echo "		<td style='width:620px;'>";
							echo "" . $minitask->name;
							echo "		</td>";
							echo "		<td id='donesection_" . $minitask->minitaskID . "' style='text-align:right;width:90px;'>";
							echo "Done";
							echo "		</td>";
							echo "		<td style='text-align:right;width:90px;'>";
							//echo "<button onclick='nextminitaskbuttonclicked(" . $minitask->minitaskID . ")' class=section-button style='width:80px;'>Next</button>";
							echo "		</td>";
							echo "	</tr>";
							echo "</table>";
					
						} else {
							*/
							
							echo "<table id=taskbar_" . $minitask->minitaskID . " cellpadding='0' cellpadding='0'  class=taskmessagediv style=';width:800px;margin-bottom:5px;'>";
							echo "	<tr>";
							echo "		<td style='width:620px;'>";
							echo "" . $minitask->name;
							echo "		</td>";
							echo "		<td id='donesection_" . $minitask->minitaskID . "' style='text-align:right;width:90px;'>";
							echo "<button onclick='doneminitaskbuttonclicked(" . $minitask->minitaskID . ")' class=section-button style='width:80px;'>Done3</button>";
							echo "		</td>";
							echo "		<td style='text-align:right;width:90px;'>";
							echo "<button onclick='nextminitaskbuttonclicked(" . $minitask->minitaskID . ")' class=section-button style='width:80px;'>Next</button>";
							echo "		</td>";
							echo "	</tr>";
							echo "</table>";
						}
							
						//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $this->settingsaction->getID(), "");
						//$button->setIcon('fa-cog fa-lg');
						//$button->show();
					}
				}
				
				if (count($registry->system->tasks) > 0) {
					foreach($registry->system->tasks as $taskID => $minitask) {
						if ($minitask->state == 1) {
							echo "<table id=taskbar_" . $minitask->minitaskID . " cellpadding='0' cellpadding='0'  class=tasksuccessmessagediv style=';width:800px;margin-bottom:5px;'>";
							echo "	<tr>";
							echo "		<td style='width:620px;'>";
							echo "" . $minitask->name;
							echo "		</td>";
							echo "		<td id='donesection_" . $minitask->minitaskID . "' style='text-align:right;width:90px;'>";
							echo "Done";
							echo "		</td>";
							echo "		<td style='text-align:right;width:90px;'>";
							//echo "<button onclick='nextminitaskbuttonclicked(" . $minitask->minitaskID . ")' class=section-button style='width:80px;'>Next</button>";
							echo "		</td>";
							echo "	</tr>";
							echo "</table>";
								
						} else {
							echo "<table id=taskbar_" . $minitask->minitaskID . " cellpadding='0' cellpadding='0'  class=taskmessagediv style=';width:800px;margin-bottom:5px;'>";
							echo "	<tr>";
							echo "		<td style='width:620px;'>";
							echo "" . $minitask->name;
							echo "		</td>";
							echo "		<td id='donesection_" . $minitask->minitaskID . "' style='text-align:right;width:90px;'>";
							echo "<button onclick='doneminitaskbuttonclicked(" . $minitask->minitaskID . ")' class=section-button style='width:80px;'>Done3</button>";
							echo "		</td>";
							echo "		<td style='text-align:right;width:90px;'>";
							echo "<button onclick='nextminitaskbuttonclicked(" . $minitask->minitaskID . ")' class=section-button style='width:80px;'>Next</button>";
							echo "		</td>";
							echo "	</tr>";
							echo "</table>";
						}
							
						//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $this->settingsaction->getID(), "");
						//$button->setIcon('fa-cog fa-lg');
						//$button->show();
					}
				}
				
				
				/*
				foreach($minitasks as $taskID => $minitask) {
					if ($minitask->state == 1) {
						echo "<table id=taskbar_" . $minitask->minitaskID . " cellpadding='0' cellpadding='0'  class=tasksuccessmessagediv style=';width:800px;margin-bottom:5px;'>";
						echo "	<tr>";
						echo "		<td style='width:620px;'>";
						echo "" . $minitask->name;
						echo "		</td>";
						echo "		<td id='donesection_" . $minitask->minitaskID . "' style='text-align:right;width:90px;'>";
						echo "Done";
						echo "		</td>";
						echo "		<td style='text-align:right;width:90px;'>";
						//echo "<button onclick='nextminitaskbuttonclicked(" . $minitask->minitaskID . ")' class=section-button style='width:80px;'>Next</button>";
						echo "		</td>";
						echo "	</tr>";
						echo "</table>";
						
					} else {
						echo "<table id=taskbar_" . $minitask->minitaskID . " cellpadding='0' cellpadding='0'  class=taskmessagediv style=';width:800px;margin-bottom:5px;'>";
						echo "	<tr>";
						echo "		<td style='width:620px;'>";
						echo "" . $minitask->name;
						echo "		</td>";
						echo "		<td id='donesection_" . $minitask->minitaskID . "' style='text-align:right;width:90px;'>";
						echo "<button onclick='doneminitaskbuttonclicked(" . $minitask->minitaskID . ")' class=section-button style='width:80px;'>Done3</button>";
						echo "		</td>";
						echo "		<td style='text-align:right;width:90px;'>";
						echo "<button onclick='nextminitaskbuttonclicked(" . $minitask->minitaskID . ")' class=section-button style='width:80px;'>Next</button>";
						echo "		</td>";
						echo "	</tr>";
						echo "</table>";
					}
					
					//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $this->settingsaction->getID(), "");
					//$button->setIcon('fa-cog fa-lg');
					//$button->show();
				}
				*/
			}
		}
		echo "</div>";
		
		
		
		//foreach($_SESSION as $index => $value) {
		//	echo "<br> - " . $index . " - " . $value;
		//}
		
		
	}
	
	
	function getMultilangString($multilangtext, $languageID = null) {
	
		if ($languageID == null) {
			$languageID = $_SESSION['languageID'];
		}
		
		$multilangarray = explode('[',$multilangtext);
		if (count($multilangarray) == 0) {
			return $multilangtext;
		} else {
			foreach ($multilangarray as $index => $value) {
				if ($value != '') {
					$itemarray = explode(']', $value);
					if ($itemarray[0] == $languageID) {
						if (isset($itemarray[1])) return $itemarray[1];
					}
				}
			}
		}
		return $multilangtext;
		
	}
	
	
	// TODO: toteuttamatta - tämä saattaa olla eri kuin 
	function getSystemLang() {
		return 2;
	}
	
	
	// tämä mahdollistaa stringinä annetun functionnimen kutsumisen
	function callFunc($name) {
		$name();
	}
	
	
	function hasClass($class_name) {
		$filename = strtolower($class_name) . '.class.php';
		$file = SITE_PATH  . 'classes' . DIRECTORY_SEPARATOR . $filename;
		return file_exists($file);		
	}
	
	
	function returnAjaxResult($queryResult) {
		if ($queryResult == true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"" . $success . "\"}]";
		}
	}
	
	
	function getUrl($action, $params = array(), $anchor = '') {

		if ($params == null) $params = array();
		
		$paramtext = '';
		
		if (isset($_GET['wID'])) {
			$paramtext = $paramtext . "&wID=" . $_SESSION['windowID'];
		}
		
		if (count($params) > 0) {
			$paramtext =  $paramtext . '&';
			$first = true;
			foreach ($params as $index => $param) {
				if ($first == true) {
					$paramtext = $paramtext . $index . "=" . $param;
					$first  = false;
				} else {
					$paramtext = $paramtext . '&' .$index . "=" . $param;
				}
			}
		}
		if ($anchor != '') {
			if (DEV) return ROOTPHP . '?rt=' . $action . $paramtext . '#' . $anchor;
			return ROOTPHP . '?rt=' . $action . $paramtext . '#' . $anchor;
		} else {
			if (DEV) return ROOTPHP . '?rt=' . $action . $paramtext;
			return ROOTPHP . '?rt=' . $action . $paramtext;
		}
	}
	
	
	/**
	 * Kuten getUrl-funktio, mutta ei ota mukaan wID:tä. Tämä funktio on tarkoitettu
	 * href-linkkeihin, joilla aukaistaan selaimessa uusi ikkuna tai tabi.
	 * 
	 * @param string $action
	 * @param string $params
	 * @param string $anchor
	 * @return string
	 */
	function getNewWindowUrl($action, $params = array(), $anchor = '') {
	
		if ($params == null) $params = array();
	
		$paramtext = "&wID=0";
		
		if (count($params) > 0) {
			$paramtext =  $paramtext . '&';
			$first = true;
			foreach ($params as $index => $param) {
				if ($first == true) {
					$paramtext = $paramtext . $index . "=" . $param;
					$first  = false;
				} else {
					$paramtext = $paramtext . '&' .$index . "=" . $param;
				}
			}
		}
		if ($anchor != '') {
			if (DEV) return ROOTPHP . '?rt=' . $action . $paramtext . '#' . $anchor;
			return ROOTPHP . '?rt=' . $action . $paramtext . '#' . $anchor;
		} else {
			if (DEV) return ROOTPHP . '?rt=' . $action . $paramtext;
			return ROOTPHP . '?rt=' . $action . $paramtext;
		}
	}
	
	
	
	
	
	function getRootUrl() {
		return ROOTPHP . '?rt=';
	}
	
	

	function getNoframesUrl($action, $params = array(), $anchor = '') {
	
		if ($params == null) $params = array();
	
		$paramtext = '';
		if (count($params) > 0) {
			$paramtext =  $paramtext . '&';
			$first = true;
			foreach ($params as $index => $param) {
				if ($first == true) {
					$paramtext = $paramtext . $index . "=" . $param;
					$first  = false;
				} else {
					$paramtext = $paramtext . '&' .$index . "=" . $param;
				}
			}
		}
		if ($anchor != '') {
			if (DEV) return NOFRAMESHANDLER . '?rt=' . $action . $paramtext . '#' . $anchor;
			return NOFRAMESHANDLER . '?rt=' . $action . $paramtext . '#' . $anchor;
		} else {
			if (DEV) return NOFRAMESHANDLER . '?rt=' . $action . $paramtext;
			return NOFRAMESHANDLER . '?rt=' . $action . $paramtext;
		}
	}
	
	

	function getPdfUrl($action, $params = array(), $anchor = '') {
	
		if ($params == null) $params = array();
	
		$paramtext = '';
		if (count($params) > 0) {
			$paramtext =  $paramtext . '&';
			$first = true;
			foreach ($params as $index => $param) {
				if ($first == true) {
					$paramtext = $paramtext . $index . "=" . $param;
					$first  = false;
				} else {
					$paramtext = $paramtext . '&' .$index . "=" . $param;
				}
			}
		}
		if ($anchor != '') {
			if (DEV) return PDFHANDLER . '?rt=' . $action . $paramtext . '#' . $anchor;
			return PDFHANDLER . '?rt=' . $action . $paramtext . '#' . $anchor;
		} else {
			if (DEV) return PDFHANDLER . '?rt=' . $action . $paramtext;
			return PDFHANDLER . '?rt=' . $action . $paramtext;
		}
	}
	
	
	function getImageUrl($imagefilename) {
		if (DEV) return SITEPATH . 'images/' . $imagefilename;
		return SITEPATH . 'images/' . $imagefilename;
	}
	
	
	
	function http_getUrl($text) {
		if (DEV) return $sitepath . ROOTPHP . '?rt=' . $text;
		return ROOTPHP . '?rt=' . $text;
	}
	
	
	function generateRandomString($length = 8) {
		return substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',$length)),0,$length);
	}
	
	
	function arrayToSqlString($arri) {
		$str = null;
		foreach($arri as $index => $value) {
			if ($str == null) {
				$str = $value;
			} else {
				$str = $str . "," . $value;
			}
		}
		return $str;
	}
	
	
	function addErrorMessage($message) {
		$errorcount = intval($_SESSION['errorcount']);
		$_SESSION['errormessage-'.$errorcount] = $message;
		$_SESSION['errorcount'] = ($errorcount + 1);
	}
	
	
	
	function addMessage($message) {
		$errorcount = 0;
		if (isset($_SESSION['messagecount'])) {
			$errorcount = intval($_SESSION['messagecount']);
			$_SESSION['message-'.$errorcount] = $message;
			$_SESSION['messagecount'] = ($errorcount + 1);
		}
	}
	
	
	function redirectlocal($module, $action) {
		header("Location: " . getUrl("" . $module . "/" . $action ) );
		exit;
		// save session
		// header(...)
	}
	

	function redirectfromcontroller($target) {
		header("Location: " . getUrl("" . $target) );
		exit;
		// save session
		// header(...)
	}
	
	function redirecttotal($target, $params = array(), $anchor = '') {
		header("Location: " . getUrl("". $target, $params, $anchor) );
		exit;
		// save session
		// header(...)
	}
		
	

	function decodeSpecialCharacters($str) {
		$str = str_replace('_H_',"#",$str);
		return $str;
	}
	
	
	
	function convertIDsToValues($IDs,$IDvar,$values,$valuevar) {
		foreach($IDs as $index => $row) {
			$row->$IDvar 			= $values[$row->$IDvar]->$valuevar;
		}
	}
	
	

	function addLoggerError($errormessage) {
		
		if (!isset($_SESSION['system_errors'])) $_SESSION['system_errors'] = array();
		
		if (DEV) {
			$errors = $_SESSION['system_errors'];
			$errors[] = $errormessage;
			$_SESSION['system_errors'] = $errors;
		} else {
			$errors = $_SESSION['system_errors'];
			$errors[] = $errormessage;
			$_SESSION['system_errors'] = $errors;
		}
	}
	
	
	// logitus pitäisi ehkä hoitaa moduleittain. Virheen etsiminen voisi olla ehkä helpompaa
	// tai sitten ei. Errorit samaan logiin. Mutta info logitus moduleittain --- esim. modulin käyttäänotto yms. tai juurikin update
	function errorLog($errormessage) {
		
		$date = date('Y_m_d');
		$time = date('Y-m-d H:i:s');
		
		$file = SITE_PATH  . 'log' . DIRECTORY_SEPARATOR . $date . '_log.txt';
		$filehandle = fopen($file,'a');
		fwrite($filehandle, $time . "\t" . $errormessage . PHP_EOL );
		fclose($filehandle);
		
		if (!isset($_SESSION['system_errors'])) $_SESSION['system_errors'] = array();
		
		if (DEV) {
			$errors = $_SESSION['system_errors'];
			$errors[] = $errormessage;
			$_SESSION['system_errors'] = $errors;
		} else {
			$errors = $_SESSION['system_errors'];
			$errors[] = $errormessage;
			$_SESSION['system_errors'] = $errors;
		}
	}
	
	
	function setModuleLocation($module, $action) {
		//echo "<br>setModuleLocation - " . $module;
		$_SESSION['current_module'] = "" . $module;
	}
	
	
	function setControllerLocation($controller, $action, $registry) {
		//echo "<br>setControllerLocation - " . $controller;
		if (isset($_SESSION['current_location'])) $_SESSION['previous_location'] = $_SESSION['current_location'];
		
		$location = $controller . "/" . $action;
		//$_SESSION['current_location'] = "" . $controller . "/" . $action;
		$_SESSION['current_location'] = $location;
		$_SESSION['current_controller'] = "" . $controller;
		
		if (isset($_SESSION['AC_' . $location])) {
			$tablelist = explode(",", $_SESSION['AC_' . $location]);
			foreach($tablelist as $index => $tableID) {
				if (isset($_GET['id'])) {
					$searchID = $_GET['id'];
					//$registry->system->minitasks = Table::load("tasks_minitasks", "WHERE TargettableID=" . $tableID . " AND TargetID=" . $searchID . " AND (State=0 OR State=1)");
					$registry->system->minitasks = Table::load("tasks_minitasks", "WHERE TargettableID=" . $tableID . " AND TargetID=" . $searchID);
					$registry->system->tasks = Table::load("tasks_tasks", "WHERE TargettableID=" . $tableID . " AND TargetID=" . $searchID);
				}
			}
			
			
		} else {
			$registry->system->minitasks = array();
			$registry->system->tasks = array();
				
		}
		//$registry->system->jee = "jeejeeregsys";
	}
	
	
	// Voitaisiin ehkä muuttaa getCompoentState, tai käytetään vain getSessionVar
	function getSectionState($sectionID) {
		$prefix = "W" . $_SESSION['windowID'] . "_";
		$sessionkey = $prefix . $_SESSION['current_location'] . "_section_" . $sectionID;
		//echo "<br>sessionstr - " . $sessionStr;
		if (isset($_SESSION[$sessionkey])) {
			return $_SESSION[$sessionkey];
		} else {
			return null;
		}
	}
	
	
	// Voitaisiin ehkä muuttaa setComponentOpen, tai käytetään vain getSessionVar
	function setSectionClosed($sectionID) {
		$prefix = "W" . $_SESSION['windowID'] . "_";
		$sessionkey = $prefix . $_SESSION['current_location'] . "_section_" . $sectionID;
		$_SESSION[$sessionkey] = "Closed";
	}

	// jotkut sectionit voidaan ehkä määritellä alwaysopeniksi
	// Voistaisin ehkä muuttaa setComponentOpen, tai käytetään vain getSessionVar
	// 16.11.2019, ei muistikuvaa miten tarkoitus toimia, voidaan ehkä poistaa
	function setSectionOpen($sectionID) {
		$prefix = "W" . $_SESSION['windowID'] . "_";
		$sessionkey = $prefix . $_SESSION['current_location'] . "_section_" . $sectionID;
		$_SESSION[$sessionkey] = "Open";
	}
	
	

	function setModuleSessionVar($variable, $value) {
	
		$prefix = "W" . $_SESSION['windowID'] . "_";
		$sessionkey = $prefix .  $_SESSION['current_module'] . "_" . $variable;
		//echo "<br>setsession - " . $_SESSION['current_location'] . " ... " . $variable . " ... " . $value;
		$_SESSION[$sessionkey] = $value;
	}
	
	

	function getOldModuleSessionVar($variable, $defaultvalue = null) {
		
		$prefix = "W" . $_SESSION['windowID'] . "_";
		$sessionkey = $prefix . $_SESSION['current_module'] . "_" . $variable;
		if (isset($_SESSION[$sessionkey])) {
			return $_SESSION[$sessionkey];
		}
		return $defaultvalue;
	}
	

	// TODO: 	Tarvittaisiin ehkä vielä controllersessionvar, niin voidaan käyttää muuttujia
	//			jotka eivät näy missään muussa kontrollerissa. Vai olisiko getSessionVar oletukusena
	//			tällainen. Sitten olisi getSystemSessionVar, jota käytettäisiin oikeasti globaaleihin
	//			muuttujiin. Olisiko grammarID tällainen? Ainakin bookkeepingID on.
	function getModuleSessionVar($variable, $defaultvalue = null) {
	
		$comments = false;
	
		$prefix = "W" . $_SESSION['windowID'] . "_";
		$sessionkey = $prefix . $_SESSION['current_module'] . "_" . $variable;
		if ($comments) echo "<br>getModuleSessionVar - " . $sessionkey . " ... ";
	
		if (isset($_GET[$variable])) {
			if ($comments) echo "<br>getModuleSessionVar in url exists - " . $_GET[$variable];
			$_SESSION[$sessionkey] = $_GET[$variable];
			return $_GET[$variable];
		} else {
			if ($comments) echo "<br>getModuleSessionVar - " . $sessionkey;
			if (isset($_SESSION[$sessionkey])) {
				if ($comments) echo "<br>getModuleSessionVar exists - " . $_SESSION[$sessionkey];
				return $_SESSION[$sessionkey];
			} else {
				$_SESSION[$sessionkey] = $defaultvalue;
				if ($comments) echo "<br>getModuleSessionVar not exists - " . $defaultvalue;
				return $defaultvalue;
			}
		}
	}
	

	function isModuleSessionVarSetted($variable) {
		$prefix = "W" . $_SESSION['windowID'] . "_";
		$sessionkey = $prefix . $_SESSION['current_module'] . "_" . $variable;
		if (isset($_SESSION[$sessionkey])) return true;
		return false;
	}
	
	// Nämä modulet pitäisi mahdollisesti jotenkin hardkoodata, mutta toistaiseksi
	// nämä otetaan modulename-stringin perusteella, oletetaan että niitä tuplia ei synny
	// Voisi nämä modulet tosin asettaa suoraan module luokkaankin, mutta tällöin asiakastietokantaan
	// menee turhaa tietoa
	function isModuleActive($modulename, $comments = false) {
		$module = Table::loadRow("system_modules", "WHERE Modulename='" . $modulename . "' AND SystemID=" . $_SESSION['systemID'], $comments);
		if ($module->active == 1) return true;
		return false;
	}
	
	
	function setSystemVar($variable, $value) {
		$_SESSION[$variable] = $value;
	}
	
	
	function getSystemVar($variable) {
		return $_SESSION[$variable];
	}
	
	
	// Tästä pitäisi ehkä tehdä erikseen versio joka ottaa pelkästän sessionista, eikä
	// nappaa url-parametrista tätä lainkaan.
	function getSessionVar($variable, $defaultvalue = null, $comments = false) {
		
		//$comments = false;
		$prefix = "W" . $_SESSION['windowID'] . "_";
		$sessionkey = $prefix . $_SESSION['current_location'] . "_" . $variable;
		
		if ($comments) echo "<br>getsession - " . $sessionkey . " ... ";
		
		if (isset($_GET[$variable])) {
			if ($comments) echo "<br>Session in url exists - " . $_GET[$variable];
			$_SESSION[$sessionkey] = $_GET[$variable];
			return $_GET[$variable];
		} else {
			if ($comments) echo "<br>sessionkey - " . $sessionkey;
			if (isset($_SESSION[$sessionkey])) {
				if ($comments) echo "<br>Session exists - " . $_SESSION[$sessionkey];
				return $_SESSION[$sessionkey];
			} else {
				$_SESSION[$sessionkey] = $defaultvalue;
				if ($comments) echo "<br>Session not exists - " . $defaultvalue;
				return $defaultvalue;
			}
		}
	}
	
	
	function setSessionVar($variable, $value) {
		
		$prefix = "W" . $_SESSION['windowID'] . "_";
		$sessionkey = $prefix . $_SESSION['current_location'] . "_" . $variable;
		//echo "<br>setsession - " . $_SESSION['current_location'] . " ... " . $variable . " ... " . $value;
		$_SESSION[$sessionkey] = $value;
	}
	
	
	
	function getSessionArray($variable) {
		
		$prefix = "W" . $_SESSION['windowID'] . "_";
		$sessionkey = $prefix . $_SESSION['current_location'] . "_" . $variable;
		if (isset($_SESSION[$sessionkey])) {
			$arrayStr = $_SESSION[$sessionkey];
			//echo "<br> getSessionArray - " . $arrayStr;
			$intarray = explode(',', $arrayStr);
			$returnarray = array();
			foreach($intarray as $index => $value) {
				$returnarray[$value] = $value;
			}
			//echo "<br> getSessionArray - ";
			//print_r($returnarray);
			return $returnarray;
		} else {
			//echo "<br> getSessionArray - not found";
			return array();
		}	
	}
	
	
	
	
	function setSessionArray($variable, $array) {
		$prefix = "W" . $_SESSION['windowID'] . "_";
		$sessionkey = $prefix . $_SESSION['current_location'] . "_" . $variable;
		$arraystr = implode(',',$array);
		$_SESSION[$sessionkey] = $arraystr;
	}
	
	
	/*
	function CreateDatabase($databasename) {
		
		$servername = "localhost";
		$username = "babelsoftf_one";
		$password = "jones123";
		
		// Create connection
		$conn = new mysqli($servername, $username, $password);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// Create database
		$sql = "CREATE DATABASE " . $databasename;
		if ($conn->query($sql) === TRUE) {
			echo "Database created successfully";
		} else {
			echo "Error creating database: " . $conn->error;
		}
		
		$conn->close();
	}
	*/
	
	function ConnectDatabase($database = null) {
	
		//echo "<br>Connectdatabase - " . $database;
		//echo "<br>Session Connectdatabase - " . $_SESSION['database'];
		
	
		if ($database == null) {
			if (isset($_SESSION['database'])) {
				$database = $_SESSION['database'];
			} else {
				echo "<br>System_database null";
	
				//session_destroy();
				//redirecttotal("system/login/index");
	
				//die('No database selected (connecting)');
	
			}
		} else {
			$_SESSION['database'] = $database;
		}
	
		// nämä ovat online serverin tunnarit, huom: kaikkiin tietokantoihin sama käyttäjä
		$host = "localhost";
		$username = "babelsoftf_one";
		$password = "jones123";
		$database = $database;
	
		//$username="root";
		//$password="";
	
		//echo "<br>database - "  .$database;
	
		$connection = null;
		if (DEV) {
			include_once 'connection.class.php';
			$connection = new Connection("localhost", $username, $password, $database);
			//mysqli_set_charset($connection,"ISO-8859-1");
		} else {
			// tämä ei mahdollisesti toimi, pitää ehkä laittaa toisen if haaran include tähän
			include_once 'connection.class.php';
			$connection = new Connection("localhost", $username, $password, $database);
			//mysqli_set_charset($connection,"ISO-8859-1");
	
			//$connection = new mysqli("localhost", $username, $password, $database);
		}
	
		$connection->set_charset("utf8");
		if ($connection->connect_error) {
			echo "<br>connecterror - " . $connection->error;
			return false;
		}
		return $connection;
	}
	
	
	function calculateDateDifference($startDate, $endDate) {
		$dateDifference = floor((strtotime($endDate) - strtotime($startDate))/(60*60*24));
		return $dateDifference;
	}
	
	
	function ConnectDatabaseTemp($database = null) {
	
		$host = "localhost";
		$username = "babelsoftf_one";
		$password = "jones123";
		$database = $database;
		
		$connection = null;
		include_once 'connection.class.php';
		$connection = new Connection("localhost", $username, $password, $database);
		
		$connection->set_charset("utf8");
		if ($connection->connect_error) {
			echo "<br>connecterror - " . $connection->error;
			return false;
		}
		return $connection;
	}
	
	
	function setPageTitle($value) {
		$_SESSION['pagetitle'] = $value;
		//setSessionVar("pagetitle", $value);
	}
	
	
	function getPageTitle() {
		return $_SESSION['pagetitle'];
		//return getSessionVar("pagetitle");
	}

	
	$mysqli = null;
	$registry = new Registry();
	
	function init($database = NULL) {
		
		global $registry;
		global $mysqli;
		//set_magic_quotes_runtime (false);				// deprecated on new php version
		
		
		
		ini_set('display_startup_errors',1);
		ini_set("display_errors", 1);
		ini_set("track_errors", 1);
		ini_set("html_errors", 1);
		
		//error_reporting(E_ALL);
		
		
		
		
		//loadSession($registry);
		$_SESSION['system_queries'] = array();
		
		if (DEV) {
		
			error_reporting(E_ALL ^ E_DEPRECATED);
		
			if (!isset($_SESSION['system_requests'])) $_SESSION['system_requests'] = array();
		
			if (isset($_GET['system_clearrequestlog'])) {
				$_SESSION['system_requests'] = array();
			}
		
			$requests = $_SESSION['system_requests'];
			//echo "<br>Request - " . $requests;
		
			if (isset($_GET['rt'])) {
					
				$post = "";
				foreach($_POST as $index => $value) {
					//echo "<br>Posti - " . $index . " --> " . $value;
					$post = $post . "&" . $index . "=" . $value;
				}
					
				$requests[] = $_SERVER['REQUEST_URI'] . $post;
				$_SESSION['system_requests'] = $requests;
			}
		} else {
		
			if (isset($_GET['rt'])) {
		
				$post = "";
				foreach($_POST as $index => $value) {
					//echo "<br>Posti - " . $index . " --> " . $value;
					$post = $post . "&" . $index . "=" . $value;
				}
		
				$requests[] = $_SERVER['REQUEST_URI'] . $post;
				$_SESSION['system_requests'] = $requests;
			}
		}
		
		$_SESSION['newwindow'] = 0;
		$_SESSION['pagetitle'] = 0;
		
		if (isset($_GET['wID'])) {
			$wID = $_GET['wID'];
			
			if ($wID == '0') {
				// TODO: luodaan uusi tID, tällöin pitäisi myös $sID olla asetettu, sitä käytetään kopiointiin...
				
				// Kelataan läpi kaikki session variablet, jos prefixinä on t[X], niin kopioidaan se
				// session t_counter pitäisi jossain asettaa. Varmaan loginiin.
				
				if (!isset($_SESSION['windowcounter'])) {
					echo "<br>missing tabcounter...";
					$_SESSION['windowcounter'] = 1;
				}
				$windowID = $_SESSION['windowcounter'] + 1;
				$_SESSION['windowcounter'] = $windowID;
				$_SESSION['windowID'] = $windowID;
				$_SESSION['newwindow'] = 1;
				if (isset($_GET['sID'])) {
					$sourceTabID = $_GET['sID'];
					$prefix = "W" . $sourceTabID . "_";
					$prefixlength = strlen($prefix);
					foreach($_SESSION as $oldkey => $value) {
						if (substring($oldkey, 0, $oldkeylength) == $prefix) {
							$newkey = substring($oldkey, $prefixlength);
							$_SESSION["W" . $windowID . "_" . $newkey] = $value;
						}
					}
				} else {
					
				}
				$_SESSION['windowID'] = $windowID;
				//echo "<br>2 - setwindowID - " . $wID;
				
			} else {
				$_SESSION['windowID'] = $wID;
				//echo "<br>1 - setwindowID - " . $wID;
			}
			
			if (isset($_GET['menuID'])) {
				$_SESSION['W' . $_SESSION['windowID'] . '_activemenuID'] = $_GET['menuID'];
			}
			
			
		} else {
			
			// kyseinen toiminto ei tarvitse windowID:tä, mitä tällaiset ovat? 
			// JSON-kutsut ainakin pääosin. niissäkin tosin saatetaan joskus tarvita.
			//unset($_SESSION['windowID']);
			$_SESSION['windowID'] = 0;
			//echo "<br>3 - setwindowID - 0";
				
			//echo "<br>tID not setted...";
			
			
		}
		
		
		
		// NOTE tämä pitäisi ehkä siirtää johonkin muualle, mutta en nyt keksinyt parempaakaan paikkaa tähänhätään
		$rt = "unknown";
		if ($database == NULL) {
			$mysqli = null;
			//echo "<br>Ei tietokantayhteyttä";
			//exit();
			//echo "<br>Redirect total system/login/index";
			if (isset($_GET['rt'])) {
				$rt = $_GET['rt'];
				if (strpos($_GET['rt'], 'system/login') !== false) {
					$mysqli = null;
				} else {
					//echo "<br>Redirect total system/login";
					//exit();
					redirectlocal("system/login", "index");
				}
			} else {
				$rt = "exit";
				//echo "<br>Redirect total2 system/login";
				//exit();
				redirectlocal("system/login", "index");
			}
		} else {
			$_SESSION['database'] = $database;
			$mysqli = ConnectDatabase($_SESSION['database']);
			
			if (isset($_GET['rt'])) {
				$rt = $_GET['rt'];
			} else {
				$rt = "unknown route";
			}
		}
		

		$paramstr = "";
		foreach($_GET as $index => $value) {
			//echo "<br>Param - " . $index . " - " . $value;
			if (($index != 'rt') && ($value != '')) {
				if ($paramstr == "") {
					$paramstr = $index . "=" . $value;
				} else {
					$paramstr = $paramstr . ";" . $index . "=" . $value;
				}
			}
		}
			
		
		if (isset($_SESSION['userID'])) {
			//echo "<br>User - " . $_SESSION['userID'];
			$time = new DateTime();
			$userID = $_SESSION['userID'];
			//echo "<br>Time = " . $time->format('Y-m-d H:i:s');
			//echo "<br>SystemID = " . $_SESSION['systemID'];
			//echo "<br>Systemname = " . $_SESSION['systemname'];
			$userlogfile = "" . $time->format('Y-m-d') . "_" . $_SESSION['systemname'] . ".log";
			$path = SAVEROOT . "logs/user-" . $userID . "/" . $userlogfile;
			//echo "<br>Path - " . $path;			
			
			$fh = fopen($path, "a+");
			

			$widthmax = 70;
			$str = $time->format('Y-m-d H:i:s') . "     " . $rt;
			while (strlen($str) < 70) {
				$str = $str . " ";
			}
			$str = $str . " ";
			fwrite($fh, $str . $paramstr . PHP_EOL);
			fclose($fh);
		} else {
			$time = new DateTime();
			$userlogfile = "" . $time->format('Y-m-d') . "_master.log";
			$path = SAVEROOT . "logs/" . $userlogfile;
			$fh = fopen($path, "a+");
			
			$widthmax = 70;
			$str = $time->format('Y-m-d H:i:s') . "     " . $rt;
			while (strlen($str) < 70) {
				$str = $str . " ";
			}
			$str = $str . " ";
			fwrite($fh, $str . $paramstr . PHP_EOL);
			fclose($fh);
		}
		
		/*
		if (!isset($_SESSION['database'])) {
			$mysqli = null;
		}
		*/
	}
	
	//echo "<br>Database - " . $_SESSION['database'];
	//echo "<br>jee";
	
	//if (isset($_GET['noframes'])) echo ''. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	
	 
	 /*** create the database registry object ***/
	// $registry->db = db::getInstance();
?>