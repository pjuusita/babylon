<?php


/**
 * Käytetään yritysten toimialojen määrittämiseen
 * 
 * @author pjuusita
 *
 */
class GroupsController extends AbstractController {


	public function getCSSFiles() {
		return array('menu.css', 'main.css','mytheme/jquery-ui.css','yritys.css');
	}

	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}

	
	public function indexAction() {
		//$this->showgroupsAction();
		$this->registry->template->show('system/error','unknown');
	}
	
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	

	public function showgroupsAction() {
		$this->registry->groups = Table::load('crm_groups');
		$this->registry->template->show('crm/groups','groups');
	}

	
	

	public function insertasiakasryhmaAction() {

		$this->registry->loadParams();

		$nimi=$_GET['nimi'];
		$ytunnus=$_GET['ytunnus'];
		$jakeluosoite=$_GET['jakeluosoite'];
		$postinumero=$_GET['postinumero'];
		$success=true;

		if ($nimi == '') $success=false;
		if ($ytunnus == '') $success=false;
		if ($jakeluosoite == '') $success=false;
		if ($postinumero == '') $success=false;

		if ($success == false) {
			addErrorMessage("Kentat ei saa olla tyhjia");
			$this->registry->nimi=$nimi;
			$this->registry->ytunnus=$ytunnus;
			$this->registry->jakeluosoite=$jakeluosoite;
			$this->registry->postinumero=$postinumero;

			$this->registry->template->show('crm/clientgroups','newclientgroup');
		} else {
			// Vahentunut, korvattu Row-luokalla
			//$success=Yritys::addYritys($nimi,$ytunnus,$jakeluosoite,$postinumero);
			if ($success == true) {
			    // Vahentunut, korvattu Row-luokalla
			    //$this->registry->yritykset = Yritys::loadYritykset();
				$this->registry->template->show('crm/clientgroups','clientgroupstable');
			} else {
				addErrorMessage("Tuntematon tietokantavirhe.");
				$this->registry->template->show('crm/clientgroups','newclientgroup');
			}
		}
	}

	

	public function insertgroupAction () {
	
		$comments = false;
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$rowID = Table::addRow("crm_groups", $values, $comments);
		if ($comments == false) redirecttotal('crm/groups/showgroups',null);
	}
	
	
	
	
	public function deleteclientgroupAction () {

		$this->registry->loadParams();

		$asiakasryhmaID=$_GET['asiakasryhmaid'];
		$success = Table::deleteRow("crm_clientgroup"," ClientgroupID='" . $asiakasryhmaID . "'");
		if ($success) {
		    // Vahentunut, korvattu Row-luokalla
		    //$success=Yritys::nollaaAsiakasryhma($asiakasryhmaID);
			if ($success) {
				$this->registry->asiakasryhmat = Table::load('crm_clientgroups');
				$this->registry->template->show('crm/clientgroups','clientgroupstable');
			} else {
				addErrorMessage($success);
				$this->registry->asiakasryhmat = Table::load('crm_clientgroups');
				$this->registry->template->show('crm/clientgroups','clientgroupstable');
			}
		} else {
			addErrorMessage($success);
			$this->registry->asiakasryhmat = Table::load('crm_clientgroups');
			$this->registry->template->show('crm/clientgroups','clientgroupstable');

		}

	}


	public function changeclientgroupnameAction () {
	
		$clientgroupID = $_GET['asiakasryhmaid'];
		$nimi = $_GET['nimi'];
		$columns = array();
		$columns['Name'] = $nimi;
		$success = Table::updateRow('crm_clientgroups', $columns, $clientgroupID);
		if ($success == true) {
			echo "[{\"success\":\"true\",\"nimi\":\"" . $nimi . "\"}]";
		} else {
			echo "[{\"success\":\"" . $success . "\"}]";
		}
	}

	
	public function getclientgroupcountAction () {
	
		$asiakasryhmaID=$_GET['asiakasryhmaid'];
		// Vahentunut, korvattu Row-luokalla
		//$lkm = Yritys::getAsiakasryhmalkm($asiakasryhmaID);
		echo "[{\"lkm\":\"" . $lkm . "\"}]";
//		echo "[{\"lkm\":\"5\"}]";
	}
}
?>