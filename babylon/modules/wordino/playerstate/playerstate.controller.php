<?php


class PlayerstateController extends AbstractController {
	
	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		
	
	public function indexAction() {
		//$this->playerstateAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	

	public function playerstateAction() {

		$this->registry->players = Table::load("wordino_players", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->template->show('wordino/playerstate','playerstate');
	}

	
}
