<?php

/**
 * Settingscontrollerissa hallitaan jarjestelman asetuksia. Periaatteessa jokaista kaytassa olevaa modulia varten on oma
 * sectioninsa, jokaiselta modulilta tulisi siis saada tarpeelliset asetukset.
 * 
 * Perusasetuksia on muutamia, ainakin ulkoasuun ja logoon liittyvia asetuksia, mutta periaatteessa namakin voisivat tulla
 * suoraan kaytettavissa olevista oletusmoduleista. Lahinna kai niin, etta system module on automaattisesti kaytassa, myas admin
 * moduli on todennakaisesti automaattisesti kaytassa. Osa moduleista on tosin pelkastaan hallinta kayttaan, esim. database.
 *
 * 
 *
 */
class ActionsController extends AbstractController {


	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}

	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}

		
	public function indexAction() {
		$this->registry->template->show('system/error','unknown');
	}
	
	
	public function showactionsAction() {
	
		updateActionPath("Actions");
		$this->registry->actions = Table::load('system_actionpaths');
		$this->registry->template->show('admin/actions','actions');
	}

	
	public function addactionAction() {
	
		$values = array();
		$values['Actionpath'] = $_GET['actionpath'];
		$values['Active'] = 0;
		$userID = Table::addRow("system_actionpaths", $values, $comments);
		redirecttotal('admin/actions/showactions', null);
	}
	
	public function updateactionAction() {
	
		$actionID = $_GET['actionID'];
		$values = array();
		$values['Actionpath'] = $_GET['actionpath'];
		$success = Table::updateRow("system_actionpaths", $values, $actionID);
		redirecttotal('admin/actions/showactions', null);
	}
	
		
}

?>
