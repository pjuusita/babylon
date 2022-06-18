<?php

/**
 *  Laajuus/scope liittyy ehka asiakashallinnan monipuolisuuteen. Mini versiossa
 *  yrityksilla on ainoastaan yksi osoite. Laajemmissa versioissa asiakkaalla voi 
 *  olla useampia toimipaikkoja, kustannuspaikkoja (tyamaa, halli, tehdas tms.) ja/tai
 *  tytaryhtiaita.
 *  
 *  Mini versiossa yrityksilla voi myas olla ainoastaan yksi laskutusosoite, laajemmassa
 *  versiossa voi olla useampia laskutusosoitteita.
 * 
 *
 */

class WorkordersModule extends AbstractModule {
	
	//const ACCESSRIGHT_READ = 1;
	//const ACCESSRIGHT_WRITE = 2;
	//const ACCESSRIGHT_WRITEREMOVE = 3;

	const ACCESSRIGHTKEY_WORKORDERS = 'accesskey_workorders_workorders';
	const ACCESSRIGHTKEY_WORKORDERTEXTMESSAGE = 'accesskey_workorders_textmessage';
	
	const MENUKEY_WORKORDERS = 'menukey_workorders';
	
	
	public function getDefaultName() {
		return "Toimeksiannot";
	}
	


	public function getAccessRights() {
	
		$accessrightitems = array();
		//$accessrightlevels = array();
		//$accessrightlevels[AbstractModule::ACCESSRIGHT_READ] = "Vain katselu";
		//$accessrightlevels[WorkordersModule::ACCESSRIGHT_WRITE] = "Muokkaus ja  lisäys";
		//$accessrightlevels[WorkordersModule::ACCESSRIGHT_WRITEREMOVE] = "Muokkaus, lisäys ja poisto";
		//$accessrightlevels[AbstractModule::ACCESSRIGHT_ALL] = "Kaikki oikeudet";
		$accessrightitems[WorkordersModule::ACCESSRIGHTKEY_WORKORDERS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		return $accessrightitems;
	}
	
	
	public function getMenu($accessrights) {
		
		$menuitems = array();
		if (isset($accessrights[WorkordersModule::ACCESSRIGHTKEY_WORKORDERS])) {
			$accesslevel = $accessrights[WorkordersModule::ACCESSRIGHTKEY_WORKORDERS];
			
			if ($accesslevel > 0) {
				$menuitems[0] = new Menu("Toimeksiannot","workorders/workorders","showorders",Menu::MENUKEY_TOP,TasksModule::MENUKEY_WORKORDERS,500);
				$menuitems[1] = new Menu("Aktiiviset","workorders/workorders","showorders",TasksModule::MENUKEY_WORKORDERS,null,510);
				$menuitems[2] = new Menu("Arkisto","workorders/workerders","showorders",TasksModule::MENUKEY_WORKORDERS,null,520);
			}
			if ($accesslevel == AbstractModule::ACCESSRIGHT_ALL) {
				$menuitems[3] = new Menu("Toimeksiantoasetukset","workorders/settings","showorders",TasksModule::MENUKEY_WORKORDERS,null, 530);
			}
		}
		return $menuitems;
	}
	
	
	
	/**
	 * Tämä funktio olettaaa, että modulin accessRightsit on jo asetettu frameworkin toimesta...
	 * Tänne ei pitäisi tulla edes jos kyseistä modulia ei ole käytössä.
	 * 
	 * @param int $action
	 * @param int $scope
	 */
	public function hasAccessRight($action) {
		
		$accesslevel = getAccessLevel(WorkordersModule::ACCESSRIGHTKEY_WORKORDERS);
		if ($accesslevel == 0) return false;
		if ($accesslevel == AbstractModule::ACCESSRIGHT_ALL) return true;
				
		echo "<br>WorkordersModule.hasAccessRight - " . $action;
		
		switch($action) {
			case "/workorders/showorders":
				return true;
				break;
			case "/workorders/showworkorder":
				return true;
				break;
			case "/workorders/updateworkorder":
				if ($accesslevel != WorkordersModule::ACCESSRIGHT_READ) return true;
				break;
			case "/workorders/insertworkerorder":
				if ($accesslevel != WorkordersModule::ACCESSRIGHT_READ) return true;
				break;
			case "/workorders/bindworker":
				if ($accesslevel != WorkordersModule::ACCESSRIGHT_READ) return true;
				break;
			case "/workorders/sendtextmessage":
				$textmessageaccess = getAccessLevel(WorkordersModule::ACCESSRIGHTKEY_WORKORDERTEXTMESSAGE);
				if ($textmessageaccess == 1) return true;
				break;
			case "/workorders/insertorder":
				if ($accesslevel != ACCESSRIGHT_READ) return true;
				break;
		}
		return false;
	}
	
	
	
	public function hasAccess($accesskey) {

		if ($accesskey == WorkordersModule::ACCESSRIGHTKEY_WORKORDERTEXTMESSAGE) {
			$accesslevel = getAccessLevel(WorkordersModule::ACCESSRIGHTKEY_WORKORDERTEXTMESSAGE);
			if ($accesslevel == 1) return true;
		} 
		
		if ($accesskey == WorkordersModule::ACCESSRIGHTKEY_WORKORDERS) {
			$accesslevel = getAccessLevel(WorkordersModule::ACCESSRIGHTKEY_WORKORDERS);
			if ($accesslevel > 0) return true;
		}
		
		return false;
	}
	
	
}


?>