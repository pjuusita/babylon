<?php

// [08.04.2022] Kopioitu projects/tasks.controller.php


class FunctionsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//updateActionPath("index");		// Tämän pitäisi oikeastaan tulla menun actionista...
		//$this->showtasksAction();
		$this->registry->template->show('system/error','unknown');
	}	
	

	
	
	/**
	 * Näyttää kaikki taskit projekteissa, joissa itse on jäsenenä
	 * 
	 */
	public function showfunctionsAction() {

		updateActionPath('KB-Functions');
		$this->registry->functions = Table::load("worder_functions", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->template->show('knowledgebase/functions','functions');
	}
	
	
	public function showfunctionAction() {
	
		updateActionPath('KB-Function');
		$functionID = $_GET['id'];
		$this->registry->functions = Table::load("worder_functions", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->function = $this->registry->functions[$functionID];
		
		$this->registry->template->show('knowledgebase/functions','function');
	}
	
	

	public function insertfunctionAction() {
	
		updateActionPath('KB-Function');
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['GrammarID'] = $_SESSION['grammarID'];
		$functionID = Table::addRow("worder_functions", $values, false);

		redirecttotal('knowledgebase/functions/showfunction&id=' . $functionID,null);
	}
		
	
	
	public function updatefunctionAction() {
	
		updateActionPath('KB-Function');
	

		$functionID = $_GET['id'];
		
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['ParentID'] = $_GET['parentID'];
		$values['Description'] = $_GET['description'];
		$success = Table::updateRow("worder_functions", $values, $functionID, true);
		
		redirecttotal('knowledgebase/functions/showfunction&id=' . $functionID,null);
	}
	
	

	public function removefunctionAction() {
	
		$functionID = $_GET['id'];
		
		$propositions = Table::load("worder_propositions", "WHERE FunctionID=" . $functionID);
		if (count($propositions) > 0) {
			echo "<br>Cannot remove, propositions exists";
			exit;
		}
		
		$success = Table::deleteRow('worder_functions',$functionID);
		
		redirecttotal('knowledgebase/function/showfunctions');
	}
	
}
