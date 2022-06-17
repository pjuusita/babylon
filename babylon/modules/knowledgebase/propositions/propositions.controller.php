<?php

// [08.04.2022] Kopioitu projects/tasks.controller.php


class PropositionsController extends AbstractController {

	
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
	public function showpropositionsAction() {

		updateActionPath('KB-Functions');
		
		$functionID = getModuleSessionVar('functionID', 0);
		$this->registry->functionID = $functionID;
		
		$this->registry->functions = Table::load("worder_functions");
		if ($functionID == 0) {
			$this->registry->propositions = Table::load("worder_propositions", "WHERE GrammarID=" . $_SESSION['grammarID']);
		} else {
			$this->registry->propositions = Table::load("worder_propositions", "WHERE FunctionID=" . $functionID);
		}
		
		$conceptlist = array();
		foreach($this->registry->propositions as $index => $proposition) {
			$conceptlist[$proposition->param1] = $proposition->param1;
			$conceptlist[$proposition->param2] = $proposition->param2;
		}
		
		$this->registry->concepts = Table::loadWhereInArray('worder_concepts', 'ConceptID', $conceptlist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		
		$this->registry->template->show('knowledgebase/propositions','propositions');
	}
	
	
	

	public function insertpropositionAction() {
	
		updateActionPath('KB-Function');
	
		$values = array();
		$values['FunctionID'] = $_GET['functionID'];
		$values['Param1'] = $_GET['param1'];
		$values['Param2'] = $_GET['param2'];
		$values['GrammarID'] = $_SESSION['grammarID'];
		$functionID = Table::addRow("worder_propositions", $values, false, true);
	
		redirecttotal('knowledgebase/propositions/showpropositions',null);
	}
	
	
	
	

}
