<?php


// [15.10.2021] Kopioitu projects/projects.controller.php


class BooksController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
	
	public function indexAction() {
		//$this->showprojectsAction();
		$this->registry->template->show('system/error','unknown');
	}	
	

	public function showbooksAction() {
		
		updateActionPath("Books");
		$this->registry->labels = Table::load("books_labels");
		
		$this->registry->labelID = getSessionVar('labelID', 0);
		
		if (isset($_GET['labelID'])) {
			$this->registry->labelID = $_GET['labelID'];
			setSessionVar('labelID', $this->registry->labelID);
		}

		if ($this->registry->labelID == 0) {
			//echo "<br>LabelID on nolla";
			$books = Table::load("books_books");
		} else {
			$allbooks = Table::load("books_books");
			$books = array();
			foreach($allbooks as $bookID => $book) {
				foreach($book->labels as $index => $labelID) {
					//echo "<br> - Book: " . $bookID . ", label: " . $labelID;
					if ($labelID == $this->registry->labelID) {
						$books[$book->bookID] = $book;
					}
				}
			}
		}
		$this->registry->books = $books;
		
		
		$this->registry->template->show('books/books','books');
	}
	

	public function showbookAction() {
	
		$bookID = $_GET['id'];
		
		updateActionPath("Book");
		$book = Table::loadRow("books_books",$bookID);
		$this->registry->book = $book;
		$this->registry->authors = Table::load("books_authors");
		$this->registry->labels = Table::load("books_labels");
		
		$bookauthors = array();
		foreach($book->authors as $index => $value) {
			if ($value != '') {
				$bookauthors[$value] = $this->registry->authors[$value];
			}
		}
		$this->registry->bookauthors = $bookauthors;
		
		$booklabels = array();
		foreach($book->labels as $index => $value) {
			if ($value != '') {
				$booklabels[$value] = $this->registry->labels[$value];
			}
		}
		$this->registry->booklabels = $booklabels;
		
		
		$this->registry->template->show('books/books','book');
	}
	

	
	public function addauthortobookAction() {

		$bookID = $_GET['bookID'];
		$authorID = $_GET['authorID'];
		
		$book = Table::loadRow("books_books",$bookID);
		
		$authorsstr = implode(':', $book->authors) . ":" . $authorID;
		
		$values = array();
		$values['Authors'] = $authorsstr;
		$success = Table::updateRow("books_books", $values, $bookID, false);
	
		/*
			$values = array();
			$values['Author'] = $_GET['name'];
			$success = Table::addRow("tasks_projects", $values, false);
		*/
	
		redirecttotal('books/books/showbook&id=' . $bookID,null);
	}
	
	
	
	public function insertbookAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$bookID = Table::addRow("books_books", $values, false);

		/*
		$values = array();
		$values['Author'] = $_GET['name'];
		$success = Table::addRow("tasks_projects", $values, false);
		*/
		
		redirecttotal('books/books/showbook&id=' . $bookID,null);
	}
	
	

	public function insertlabelAction() {
	
		$bookID = $_GET['bookID'];
		$labelID = $_GET['labelID'];
		
		$book = Table::loadRow("books_books",$bookID);
		
		$labelstr = implode(':', $book->labels) . ":" . $labelID;
		
		$values = array();
		$values['Labels'] = $labelstr;
		$success = Table::updateRow("books_books", $values, $bookID);
		
		redirecttotal('books/books/showbook&id=' . $bookID, null);
	}
	
	
	public function updatebookAction() {
	
		$bookID = $_GET['id'];
		
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Subtitle'] = $_GET['subtitle'];
		$success = Table::updateRow("books_books", $values, $bookID, true);
		
		redirecttotal('books/books/showbook&id=' . $bookID);
	}
	


	public function removeautorAction() {
	
		$bookID = $_GET['bookID'];
		$removeAuthorID = $_GET['id'];
	
		$book = Table::loadRow("books_books",$bookID);
		
		foreach($book->authors as $index => $authorID) {
			if ($authorID != "") {
				if ($authorID == $removeAuthorID) {
					
				} else {
					$authorsstr = $authorsstr . ":" . $authorID;
				}
			}
		}
		
		$values = array();
		$values['Authors'] = $authorsstr;
		$success = Table::updateRow("books_books", $values, $bookID, false);
	
		redirecttotal('books/books/showbook&id=' . $bookID);
	}
	


	
	

	public function removelabelAction() {
	
		$bookID = $_GET['bookID'];
		$removeLabelID = $_GET['id'];
	
		$book = Table::loadRow("books_books",$bookID);
		$labelsstr = "";
		foreach($book->labels as $index => $labelID) {
			if ($labelID != "") {
				if ($labelID == $removeLabelID) {
						
				} else {
					$labelsstr = $labelsstr . ":" . $labelID;
				}
			}
		}
	
		$values = array();
		$values['Labels'] = $labelsstr;
		$success = Table::updateRow("books_books", $values, $bookID, false);
	
		//echo "<br>BookID - " . $bookID;
		redirecttotal('books/books/showbook&id=' . $bookID);
	}
	
	
	
}
