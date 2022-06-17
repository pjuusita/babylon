<?php

/**
 *  
 *  toiseen keskeneraiseen projektiin liittyvaa koodia, merkkasin systemiksi niin ei tartte saataa kummemmin
 *  
 */
class BooksModule extends AbstractModule {

	
	const ACCESSKEY_BOOKS_BOOKS = 'books_books';
	
	const MENUKEY_BOOKS = 'menukey_books_books';
	const MENUKEY_AUTHORS = 'menukey_books_authors';
	const MENUKEY_SETTINGS = 'menukey_books_settings';
	


	public function getDimensions() {
		$dimensions = array();
		return $dimensions;
	}
	
	
	
	public function getAccessRights() {
		
		$accessrights = array ();
		$accessrights[BooksModule::ACCESSKEY_BOOKS_BOOKS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		return $accessrights;
	}
	

	public function getDefaultName() {
		return "[1]Books[2]Books";
	}
	
	

	public function hasAccess($accesskey) {
		return true;
	}
	
	
	public function getMenu($accessrights) {
		
		$menuitems = array ();
		if ($accessrights == null) return $menuitems;
		
		
		if ($accessrights[BooksModule::ACCESSKEY_BOOKS_BOOKS] > 0) {
			$menuitems[] = new Menu("Books","books/books","showbooks",Menu::MENUKEY_TOP,BooksModule::MENUKEY_BOOKS,1300);
			$menuitems[] = new Menu("Books","books/books","showbooks",BooksModule::MENUKEY_BOOKS,null,1310);
			$menuitems[] = new Menu("Authors","books/authors","showauthors",BooksModule::MENUKEY_BOOKS,null,1320);
			$menuitems[] = new Menu("Settings","books/settings","showsettings",BooksModule::MENUKEY_BOOKS,null,1330);
		}
		
		return $menuitems;
	}
	
	
	

	public function hasAccessRight($action) {
	
		return true;
	
		switch($action) {
			case "books/showbooks":
				return true;
				break;
			case "books/showbook":
				return true;
				break;
			case "books/showauthors":
				return true;
				break;
			case "books/showauthor":
				return true;
				break;
			case "books/showsettings":
				return true;
				break;
			case "books/insertbook":
				return true;
				break;
			case "books/updatebook":
				return true;
				break;
		}
		return false;
	}
	
	
	
	
}


?>