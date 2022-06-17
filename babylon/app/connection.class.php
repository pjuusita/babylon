<?php

/**
 * Tietokantayhteys
 * 
 * Tähän on tarkoitus lisätä devausta varten toiminnallisuutta joka logittaa kaikki 
 * queryt ja näitä pystytään ehkä selaamaan devauspaneelilla.
 * 
 * 
 * @author Petri Uusitalo
 * @copyright Babelsoft Oy, 2018
 * 
 */
class Connection extends mysqli {

	public $databasename;
	
	
	public function __construct($host, $username, $password, $database) {
		parent::__construct($host, $username, $password, $database);
		$this->databasename = $database;
	}

	
	public function query($query, $resultmode = MYSQLI_STORE_RESULT) {
		// print query to log	

		if (!isset($_SESSION['system_queries'])) $_SESSION['system_queries'] = array();
		$requests = $_SESSION['system_queries'];
		$requests[] = $this->databasename . " - " . $query;
		$_SESSION['system_queries'] = $requests;
		return parent::query($query,NULL);		
	}
}

?>