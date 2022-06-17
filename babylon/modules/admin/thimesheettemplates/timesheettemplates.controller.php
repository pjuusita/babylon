<?php

/**
 *   Doctemplates ehka siirto systemin alle
 * 
 * 
 * @author pjuusita
 *
 */
class TimesheettemplatesController extends AbstractController {

	
	
	public function getCSSFiles() {
		return array('menu.css', 'testcss.php','mytheme/jquery-ui-test.css');
		//return array('menu.css','testcss.php');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','yritys.css','petestyle.css');
	}

	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	

	public function indexAction() {
		//$this->showtimesheettemplatesAction();
		$this->registry->template->show('system/error','unknown');
	}


	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	

	public function showtimesheettemplatesAction () {
		$this->registry->timesheettemplates = Table::load("timesheet_templates");
		$this->registry->template->show('admin/timesheettemplates','timesheettemplates');
	}
	
	

	public function shownewtimesheettemplateAction () {
		
		$orientations = array();
		$orientations[0] = "Pysty";
		$orientations[1] = "Vaaka";
		$this->registry->orientations = $orientations;
		$this->registry->timesheet_elementtypes = Table::loadKeyValueArray("timesheet_elementtypes","DocelementtypeID","Name" );
	
		$this->registry->columns = Table::getTable("timesheet_templates")->getColumns();
		$this->registry->template->show('admin/timesheettemplates','newtimesheettemplate');
	}
	
	
	

	public function inserttemplateAction () {
		
		$success = Table::addRow('timesheet_templates',$_GET);
		if ($success == true) {
			echo "[ {\"success\":\"true\", \"message\":\"Lisätty onnistuneesti\" }]";
		} else {
			$paramstr = "---";
			foreach ($_GET as $index => $value) {
				$paramstr = $paramstr . "," . $index . ":" . $value;
			}
			echo "[{\"success\":\"" . $success . $paramstr . "\"}]";
		}
	}
	


	public function showtimesheettemplateAction () {
		
		$id = $_GET['id'];
		$this->registry->timesheettemplate = Table::loadRow("timesheet_templates", $id);
		
		$this->registry->timesheet_elementtypes = Table::loadKeyValueArray("timesheet_elementtypes","ElementtypeID","Name" );
		
		$this->registry->columns = Table::getTable("timesheet_templates")->getColumns();
		$this->registry->elements = Table::load("timesheet_elements", "WHERE TemplateID='" . $id . "'");
		$this->registry->template->show('admin/timesheettemplates','timesheettemplate');
	}
	
	
	


	public function shownewtimesheetelementAction () {
		
		$this->registry->timesheet_elementtypes = Table::loadKeyValueArray("timesheet_elementtypes","DocelementtypeID","Name" );
		$this->registry->columns = Table::getTable("timesheet_elements")->getColumns();
		$this->registry->template->show('admin/timesheettemplates','newtimesheetelement');
	}
	
	
	
	
/*
	public function showdocelementAction () {
		
		echo "<br>Utils/section poistettu";
		exit;
		
		$elementID = $_GET['id'];
		//$this->registry->admin_docelementtypes = Table::loadKeyValueArray("admin_docelementtypes", "DocelementtypeID", "Name");
		//$this->registry->columns = Table::getTable("admin_docelements")->getColumns();
		//$this->registry->element = Table::loadRow("admin_docelements", $elementID);
		//$this->registry->template->show('admin/doctemplates','docelement');
		
		$table = Table::getTable("admin_docelements");
		$this->registry->columns = $table->getColumns();
		$this->registry->content = Table::loadRow("admin_docelements", $elementID);
		$this->registry->updateaction = "admin/doctemplates/updateelement";
		$this->registry->returnaction = "admin/doctemplates/showdoctemplate&id=" . $this->registry->content->doctemplateID;
		$this->registry->updatevariable = $table->getKeyColumn()->variablename;
		$this->registry->template->show('utils','section');
	}
	

	public function updatetemplateAction() {
		
		$success='';
		$columnvalues=array();
		
		foreach($_GET as $index => $value) {
			if (($index != 'id') && ($index != 'rt')) {
				$columnvalues[$index]=$value;
			} elseif ($index == 'id') {
				$id = $value;
			}
		}
		$success = Table::updateRod("admin_doctemplates", $columnvalues, ... = $id);
		
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
		
	}
	
	
	public function updateelementAction() {
	
		$success='';
		$columnvalues=array();
	
		foreach($_GET as $index => $value) {
			if (($index != 'id') && ($index != 'rt')) {
				$columnvalues[$index]=$value;
			} elseif ($index == 'id') {
				$id = $value;
			}
		}
		$success = Table::updateRow("admin_docelements", $columnvalues, ... = $id);
	
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
	
	}
	

	public function showdoctemplateAction () {
	
		$id = $_GET['id'];
		$this->registry->doctemplate = Table::loadRow("admin_doctemplates", $id);
		$orientations = array();
		$orientations[0] = "Pysty";
		$orientations[1] = "Vaaka";
		$this->registry->orientations = $orientations;
		
		$this->registry->admin_docelementtypes = Table::loadKeyValueArray("admin_docelementtypes","DocelementtypeID","Name" );
		$this->registry->admin_docelements = Table::loadKeyValueArray("admin_docelements","DocelementID","DocelementID" );
		
		$this->registry->columns = Table::getTable("admin_docelements")->getColumns();
		$this->registry->elements = Table::load("admin_docelements", "WHERE DoctemplateID='" . $id . "'");
		$this->registry->template->show('admin/doctemplates','doctemplate');
	}
	
	

	

	public function shownewdoctemplateAction () {
	
		$orientations = array();
		$orientations[0] = "Pysty";
		$orientations[1] = "Vaaka";
		$this->registry->orientations = $orientations;
		$this->registry->elementtypes = Table::load("admin_docelementtypes");
		$this->registry->template->show('admin/doctemplates','newdoctemplate');
	}
	
	

	public function inserttemplateAction () {
	
		$success = Table::addRow('admin_doctemplates',$_GET);
		if ($success == true) {
			echo "[ {\"success\":\"true\", \"message\":\"Lisätty onnistuneesti\" }]";
		} else {
			$paramstr = "---";
			foreach ($_GET as $index => $value) {
				$paramstr = $paramstr . "," . $index . ":" . $value;
			}
			echo "[{\"success\":\"" . $success . $paramstr . "\"}]";
		}
		
		
		
		//Table::addRow("", $values);
		
		//$this->registry->doctemplates = Table::load("admin_doctemplates");
		//$this->registry->template->show('admin/doctemplates','doctemplates');
	}
	
	
	public function removedocelementAction() {
		
		$templateID = $_GET['id'];
		
		// TODO: tahan pitaisi laittaa myas kaikkien elementtien poisto docelements taulusta
		$success = Table::deleteRow('admin_doctemplates',"DoctemplateID='".$templateID);
		if (!$success) {
			die('removedocelementAction failed: ');
		} else {
			addMessage("Dokumenttipohja poistettu onnistuneesti");
		}
		redirecttotal("admin/doctemplates/showdoctemplates");
	}
	*/
	
}

?>
