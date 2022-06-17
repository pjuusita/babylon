<?php


	function RoleExists($profileitems, $profileitemstring) {
		
		foreach ($profileitems as $profileID => $profileitem) {
			if ($profileitem->profilename == $profileitemstring) return true;
		}
		return false;		
	}


	function outputModuleContent($module, $dbmodule, $sitepath, $modulepath, $profileitems) {
		
		switch ($module->getModuleType()) {
		
			case AbstractModule::SYSTEM:
				echo "<br>" . $dbmodule->modulename . ": System module";
				echo "<br>";
				break;
		
			case AbstractModule::BASE:
				echo "<br>" . $dbmodule->modulename . ": Base module";
				echo "<br>";
				break;
					
			case AbstractModule::ADDON:
		
				echo "<br>" . $dbmodule->modulename . ": Moduli on aktiivinen (AddOn)";
		
				$scopes = $module->scopeSelection();
		
				foreach($scopes as $index => $scope) {
						
					echo "<blockquote>\tScope - " . $index . " - " . $scope;
					$submodules = $module->getSubModules($scope);
					if ($submodules == null) $submodules = array();
						
					if (count($submodules) == 0) {
						echo "<br>\t\tEi alimoduleita";
					} else {
						foreach ($submodules as $index2 => $submodule) {
							echo "<br>\t\tSubmodule - " . $index2;
						}
					}
					
					echo "<br>";
					// Tämä get controllers on toistaiseksi poistettu kun se oli niin pahasti
					// toteuttamatta, lisätään tarvittaessa takaisin, nämä controllerit voidaan
					// periaatteessa hakea suoraan hakemistoistakin. Voidaan ehkä hakea tietokannstakin
					// johon pitäisi periaatteessa lisätä myös kutsuttavat actionit (käyttöoikeuksiin sitoen)
					$controllers = $module->getControllers($scope);
					
					if (count($controllers) == 0) {
						echo "<span style='color:red'>Ei kontrollereita</span>";
 					} else {
 						foreach($controllers as $index => $controllername) {
 							echo "<br>Controller - " . $index . " - " . $controllername;
 							
 							$controllerfile = $modulepath . DIRECTORY_SEPARATOR . $controllername .  DIRECTORY_SEPARATOR . $controllername . '.controller.php';
 							//echo "<br>Controllerfile - " . $controllerfile;
 							//echo "<br>Modulepath - " . $modulepath;
 							
 							include_once $controllerfile;
 							$class = ucfirst($controllername) . 'Controller';
 							$controller = new $class(null);
 								
 							// 1.12.2019 getRoles-poistettu, käyttöoikeuksien hallinnan muokkauksia
 							$roles = $controller->getRoles($scope);
 							foreach($roles as $index => $role) {
 								echo "<br> ---------------- Role - " . $role;
 								
 								if (!RoleExists($profileitems, $role)) {
 									die("<br>Roolia ei olemassa - " . $role);
 								}
 							}		
 						}			
 					}
					
					echo "</blockquote>";
				}
		
				break;
		
			default:
				die('Tuntematon moduletype - ' . $module->getModuleType() );
		}
	}



	
	function endswith($string, $test) {
		$strlen = strlen($string);
		$testlen = strlen($test);
		if ($testlen > $strlen) return false;
		return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
	}

	
	
	function recursiveCrawler($fullpath, $modulepath, &$modulefiles) {
		
		
		$dircontent = scandir($fullpath);
		
		foreach ($dircontent as $index => $value) {
			//echo "<br>" . $index . " - " . $value;
		
			$currentpath = $fullpath . $value;
			$currentmodulepath = $modulepath . '/' . $value;
			
			if (($value == '.') || ($value == '..')) {
				//echo "<br>Dotti - " . $currentpath;
			} elseif (is_dir($currentpath)) {
				//echo "<br>Directory found - " . $currentpath;			
				recursiveCrawler($currentpath . '/', $currentmodulepath,$modulefiles);
			} else {
				//echo "<br>File found - " . $currentpath;
				if (endswith($value,".module.php")) {
					$modulename = substr($value,0,strpos($value,'.'));
					echo "<br>------ Modulefile found - " . $value . " - '" . $modulename . "'";
					$modulefiles[$modulename] = $currentpath;
				}
			}
		}
	}
	

	echo "<br>Sitepath - " . SITE_PATH;

	$modulepath = SITE_PATH . "modules/";
	$modulefiles = array();
	recursiveCrawler($modulepath, "", $modulefiles);
	echo "<br><br>";
	$checked = array();
	$profiles = array();
	
	foreach($modulefiles as $modulename => $modulefile) {
		
		
		echo "<br>" . $modulename . " - " . $modulefile;
		
		//$modulename = substr($index, 1);
		
		include ($modulefile);
		echo "<br>Modulefile: " . $modulefile;
		$classname = $modulename . "Module";
		$module = new $classname();
		
		
		
		$found = false;
		foreach($registry->modules as $index => $dbmodule) {
			//echo "<br>" . $modulename. " vs . " . $dbmodule->modulename;
			if ($modulename == $dbmodule->modulename) {
				$found =  true;
				if ($dbmodule->stage != $module->getStage()) {
					//echo "<br>**** " . $modulename . ":  Moduli stage ei täsmää - " . $modulename . " - " . AbstractModule::getStageString($dbmodule->stage) . " vs. " . AbstractModule::getStageString($module->getStage());
				}
				
				if ($dbmodule->moduletype != $module->getModuleType()) {
					//echo "<br>**** " . $modulename . ": Moduli type ei täsmää - " . AbstractModule::getModuleTypeString($dbmodule->moduletype) . " vs. " . AbstractModule::getModuleTypeString($module->getModuleType());
				}
				
				if ($module->getStage() == AbstractModule::PRODUCTION) {
					
					$moduletemppath = substr($modulefile,0,strrpos($modulefile,'/'));
					outputModuleContent($module, $dbmodule, SITE_PATH, $moduletemppath, $registry->profileitems);
					
				
					
				} else {
					echo "<br>" . $dbmodule->modulename . ": Moduli ei ole tuotannossa<br>";
				}
				
				
				$checked[$dbmodule->modulename] = 1;
			}
		}
		if ($found == false) {
			
			echo "<br>Lisätään module: " . $modulename;
			
			echo "<br>TODO: fix this, getDefaultMenu on vanhentunut funktio modulessa...";
			$success = Table::addRow('system_modules',array('Name' => $module->getDefaultName(), 'Defaultmenu' => $module->getDefaultMenu(1), 'Modulename' => $modulename, 'Active' => 0, 'Moduletype' => $module->getModuleType(), 'Stage' => $module->getStage()), true);
			if ($success) {
				echo "<br>Insert success - " . $modulename;		
			} else {
				echo "<br>Insert failed - " . $modulename;
			}

			
			if ($module->getStage() == AbstractModule::PRODUCTION) {
					
				$moduletemppath = substr($modulefile,0,strrpos($modulefile,'/'));
				outputModuleContent($module, $dbmodule, SITE_PATH, $moduletemppath, $registry->profileitems);
					
			} else {
				echo "<br>" . $dbmodule->modulename . ": Moduli ei ole tuotannossa<br>";
			}
			
			//echo "<br>**** Modulia ei läytynyt tietokannasta - " . $modulename;
		}		
		
	}

	
	foreach($registry->modules as $index => $dbmodule) {
		if (isset($checked[$dbmodule->modulename])) {
	
		} else {
			echo "<br>Modulia ei läytynyt - " . $dbmodule->modulename;
		}
	}
	
	
	
	
?>