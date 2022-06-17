<?php


class PlayersController extends AbstractController {

	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->showplayersAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	

	public function showplayersAction() {

		$this->registry->players = Table::load("wordino_players", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->template->show('wordino/players','players');
	}
	
	
	public function showplayerAction() {
	
		$playerID = $_GET['id'];
		$this->registry->player = Table::loadRow("wordino_players", $playerID);
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		$this->registry->template->show('wordino/players','player');
	}
	
	
	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	
	

	public function insertplayerAction() {
		
		$values = array();
		$values['SourcelanguageID'] = $_GET['sourcelanguageID'];
		$values['TargetlanguageID'] = $_GET['targetlanguageID'];
		$values['Description'] = $_GET['description'];
		$values['Name'] = $_GET['name'];
		$values['GrammarID'] = $_SESSION['grammarID'];
		
		$success = Table::addRow("wordino_players", $values);
		
		redirecttotal('wordino/players/showplayers',null);
	}
}
