<?php

// [08.04.2022] Kopioitu projects/tasks.controller.php


class GeneratorsController extends AbstractController {

	
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
	public function showgeneratorsAction() {

		updateActionPath('KB-Functions');
		
		$this->registry->template->show('knowledgebase/generators','generators');
	}
	
	
	

}
