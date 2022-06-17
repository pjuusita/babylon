<?php


/**
 * 
 * Core-routeri, ohjaa  single entrypoint strategian mukaisesti suorituksen rt-urlparametrin avulla suorituksen 
 * oikeaan paikkaan.
 *
 * TODO: Tietoturvatarkistukset ja käyttäjien oikeuksien tarkistus todennäkäisesti lisätään tänne.
 * 
 * @author Petri Uusitalo
 * @copyright Babelsoft Oy, 2016
 *
 */
class router {

	private $defaulttemplate;
	
	private $defaultmodule;
		
	private $registry;

	private $path;

	private $args = array();

	public $file;
	public $modulefile;
	
	//public $controller;

	//public $action;

	
	function __construct($registry, $path) {
		$this->registry = $registry;
		$this->setPath($path);
		
		//$this->defaultmodule = $defaultmodule;
	}	

	
	/*
	 *  Käytetään globaalia SITE_PATH muuttujaa, ehkä jos samaan systeemiin lisätään
	 *  useampia projekteja niin olisi ehkä syytä käyttää pathia eritavalla, mennään
	 *  nyt näin. 
	 */
	private function setPath($path) {
		if (is_dir($path) == false) {
			throw new Exception ('Invalid controller path: `' . $path . '`');
		}
		$this->path = $path;
	}
	
	
	// tää on vähän huono kun tämä funktio asettaa vain lokaaleja variableja eikä 
	// oikein pysy jyvällä että mitä asettaa, olisi parempi kun palauttaisi objektin
	private function getController($controllerpath = null, $defaulttemplate = null) {
	
		//echo "<br>RT - " . $_GET['rt'];
		$comments = false;
		
		$this->defaulttemplate = null;
		
		$route = 'system/login/index';
		if ($controllerpath == null) {
			if (empty($_GET['rt'])) {
				$route = 'system/login/index';
				$this->defaulttemplate = 'login';
			} else {
				if ($_GET['rt'] == '') {
					$route = 'system/login/index';
					$this->defaulttemplate = 'login';
				} else {
					$this->defaulttemplate = 'login';
					$route = (empty($_GET['rt'])) ? '' : $_GET['rt'];
				}
			}
		} else {
			$route = $controllerpath;
			if ($defaulttemplate == null) {
				$this->defaulttemplate = 'login';
			} else {
				$this->defaulttemplate = $defaulttemplate;
			}
		}
			
		$pos = strrpos($route,"/");
		$firstPos = strpos($route,"/");
		$firststr = substr($route, 0, $firstPos);
		$first = substr($route, 0, $pos);
		$last = substr($route,$pos+1);
		
		$this->registry->modulename = $firststr;
		$this->registry->controllerpath = $first;
		if($last != '') {
			$pos = strrpos($first,"/");
			if ($pos == false) {
				$this->registry->controllername = $first;
				$this->registry->actionname = $last;
			} else {
				$this->registry->controllername = substr($first,$pos+1);
				$this->registry->actionname = $last;
			}
		}
		
		
		if ($comments) echo "<br>path:" . $this->path;
		if ($comments) echo "<br>controller: "  .$this->registry->controllername;
		if ($comments) echo "<br>actionname: "  . $this->registry->actionname;
		if ($comments) echo "<br>modulename: "  . $this->registry->modulename;
		if ($comments) echo "<br>controllerpath: "  . $this->registry->controllerpath;
		if ($comments) echo "<br>";
		
		
		$this->file = $this->path . 'modules' . DIRECTORY_SEPARATOR . $this->registry->controllerpath . DIRECTORY_SEPARATOR . $this->registry->controllername . '.controller.php';
		
		$this->modulefile = $this->path . 'modules' . DIRECTORY_SEPARATOR . $this->registry->modulename . DIRECTORY_SEPARATOR . $this->registry->modulename . ".module.php";
		
		if ($comments) echo "<br>modulefile: "  . $this->modulefile;
		
		//echo "<br>" . $this->file;
	}
	
	
	public static function executeAction($action, $systempath, $registry, $params = "") {

		$comments = false;
		//$comments = true;
		
		$pos = strrpos($action,"/");
		$firstPos = strpos($action,"/");
		$firststr = substr($action, 0, $firstPos);
		$first = substr($action, 0, $pos);
		$last = substr($action,$pos+1);
		
		$modulename = $firststr;
		$controllerpath = $first;
		if($last != '') {
			$pos = strrpos($first,"/");
			if ($pos == false) {
				$controllername = $first;
				$actionname = $last;
			} else {
				$controllername = substr($first,$pos+1);
				$actionname = $last;
			}
		}
		
		
		if ($comments) echo "<br>path:" . $systempath;
		if ($comments) echo "<br>controller: "  .$controllername;
		if ($comments) echo "<br>actionname: "  . $actionname;
		if ($comments) echo "<br>modulename: "  . $modulename;
		if ($comments) echo "<br>controllerpath: "  . $controllerpath;
		if ($comments) echo "<br>";
		
		$file = $systempath . 'modules' . DIRECTORY_SEPARATOR . $controllerpath . DIRECTORY_SEPARATOR . $controllername . '.controller.php';
		
		if ($comments) echo "<br>file: "  . $file;
		
		if (is_readable($file) == false) {
			echo $file;
			die ('<br>404 Not Found - ' . $file);
		}
		
		include $file;
		$class = ucfirst($controllername) . 'Controller';
		$controller = new $class($registry);
		$action = $actionname . 'Action';
		
		if (is_callable(array($controller, $actionname . 'Action')) == false) {
			echo "<br>Action not callable: " . $actionname . "";
			exit;
		}
		$controller->$action();
	}
	
	
	
	
	public function load() {

		$this->getController();
		$this->registry->system = new Registry();
		
		if (is_readable($this->file) == false) {
			echo $this->file;
			die ('<br>404 Not Found - ' . $this->file);
		}
		
		include $this->file;
		$class = ucfirst($this->registry->controllername) . 'Controller';
		$this->registry->controller = new $class($this->registry);
		
		include $this->modulefile;
		$class = ucfirst($this->registry->modulename) . 'Module';
		$this->registry->module = new $class();
		//$this->registry->jeejee = "jeejeereg";
		
		// Tässä vaiheessa modulefile-muuttujassa on käytettävän controllerin module.
		if (is_callable(array($this->registry->module, 'hasAccessRight')) == false) {
			echo "<br>Module hasAccessRight not callable, defaulted to indexAction";
			echo "<br>TODO: add erroraction..";
			//$action = 'indexAction';
			die('');
		} else {
			//echo "<br>Module hasAccessRight can be called";
			
			// pitää ladata rightsit tietokannasta... tuotantoversiossa ne olisivat sessiossa...
			//echo "<br>Accessrowcount - " . count($accessrows);
			//$access = array();
			//foreach($accessrows as $index => $accessrow) {
			//	echo "<br>Access - " . $accessrow->accesskey . " - " . $accessrow->accesslevel;	
			//}
			
			$action = $this->registry->controllername . MYPATH_SEPARATOR . $this->registry->actionname;
			$access = $this->registry->module->hasAccessRight($action);
			
		
			if ($access == false) {
				
				//header("Location: " . getUrl('system/error/norightserror') );
				//redirecttotal('system/error/norightserror',null);
				$this->getController("system/error/norightserror","menu");
								
				include $this->file;
				$class = ucfirst($this->registry->controllername) . 'Controller';
				$this->registry->controller = new $class($this->registry);
				
			}
		}

		
		if (is_callable(array($this->registry->controller, $this->registry->actionname . 'Action')) == false) {
			echo "<br>Action not callable: " . $this->registry->actionname . ", defaulted to indexAction";
			echo "<br>TODO: add erroraction..";
			exit;
			//$action = 'indexAction';
			exit;
		} else {
			$action = $this->registry->actionname . 'Action';
		}
		
		$this->registry->action = $action;
		
		$templatename = $this->registry->controller->getTemplate($this->registry->actionname);
		//$templatename = null;
		if ($templatename == null) {
			if (MENUPRESENT == true) {
				$templatename = "menu";
				if (isset($_SESSION['template'])) {
					$templatename = $_SESSION['template'];
				} else {
					$templatename = "menu";
					echo "<br>Template not setted";
				}
			} else {
				$templatename = "minimal";
			}
		}
		
		//$templatename = $this->registry->controller->getTemplate($this->registry->actionname);
		$this->templatefile = $this->path . 'templates' . DIRECTORY_SEPARATOR . $templatename . '.layout.php';
		
		include $this->templatefile;
		//$templateclass = ucfirst($this->registry->controller->getTemplate($this->registry->actionname)) . 'Template';
		$templateclass = ucfirst($templatename) . 'Template';
		$this->registry->template = new $templateclass($this->registry);
		
		$user = null;
		if ($this->registry->issetted('user')) $user = $this->registry->user;
		
		
		if (!isset($_GET['json'])) setControllerLocation($this->registry->controllerpath, $this->registry->actionname, $this->registry);			
		if (!isset($_GET['json'])) setModuleLocation($this->registry->modulename, $this->registry->actionname);	
		
		//echo "<br>jeppis - " . $this->registry->system->jee;
		$this->registry->controller->$action();
	}
}

?>