<?php

// 1.3.2020 Voitaneen poistaa, workflow toiminta korvattu muuten


class DocumentsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->showstartpageAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	

	public function showstartpageAction() {
		
		$this->registry->documents = Table::load("wiki_documents", "WHERE ParentID=0");
			
		$this->registry->template->show('wiki/documents','index');
	}
	
	

	public function showdocumentAction() {
		
		$documentID = $_GET['documentID'];
		$this->registry->document = Table::loadRow("wiki_documents", $documentID);
		$this->registry->elements = Table::load("wiki_elements", "WHERE DocumentID=" . $documentID);
		$this->registry->templates = Table::load("wiki_templates");
		
		$this->registry->template->show('wiki/documents','document');
	}
	
		
	public function insertdocumentAction() {
	
		$values = array();
		$values['Name'] =  $_GET['name'];
		$values['Identifier'] =  $_GET['path'];
		$values['ParentID'] =  $_GET['parentID'];
		$documentID = Table::addRow("wiki_documents", $values, true);
		//redirecttotal('wiki/documents/showdocument&id=' . $documentID ,null);
	}
	
	


	public function insertelementAction() {
	
		$values = array();
		$values['DocumentID'] =  $_GET['documentID'];
		$values['TemplateID'] =  $_GET['templateID'];
		$values['Content'] =  $_GET['content'];
		$values['ParentID'] =  $_GET['parentID'];
		$documentID = Table::addRow("wiki_elements", $values, true);
		//redirecttotal('wiki/documents/showdocument&id=' . $documentID ,null);
	}
	
	
}
