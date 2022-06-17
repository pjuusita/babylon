<?php


// [15.10.2021] Kopioitu projects/projects.controller.php


class AuthorsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
	
	public function indexAction() {
		$this->showauthorsAction();
	}	
	

	public function showauthorsAction() {
	
		updateActionPath("Authors");
		$authors = Table::load("books_authors");
		$this->registry->authors = $authors;
		$this->registry->template->show('books/authors','authors');
	}
	
	
	public function showauthorAction() {
	
		$authorID = $_GET['id'];
	
		updateActionPath("Author");
		$author = Table::loadRow("books_authors",$authorID);
		$this->registry->author = $author;
		$this->registry->template->show('books/authors','author');
	}
	
	
	
	public function insertauthorAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$authorID = Table::addRow("books_authors", $values, false);
	
		/*
			$values = array();
			$values['Author'] = $_GET['name'];
			$success = Table::addRow("tasks_projects", $values, false);
		*/
	
		redirecttotal('books/authors/showauthor&id=' . $authorID,null);
	}

	
	public function updateauthorAction() {
	
		$authorID = $_GET['id'];
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::updateRow("books_authors", $values, $authorID, true);
	
		redirecttotal('books/authors/showauthor&id=' . $authorID);
	}
	
	
}
