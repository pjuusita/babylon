<?php


/**
 * Yleinen containeri, jonne säilätään tietokannasta ladattu data. 
 *
 *
 * @author Petri Uusitalo
 * @copyright Babelsoft Oy, 2016
 *
 */
class Registry {

	public static $lang;
	
	private $vars = array();

	//private $sitepath;
	
	
	function __construct() {
		
		//$css = new Registry();
		$this->vars['css_menucolor'] = '#232323';
		$this->vars['css_logo_file'] = 'Babelsoft_transparent.gif';
		$this->vars['css_logo_xsize'] = '200px';
		$this->vars['css_logo_ysize'] = '60px';
		
		//$css->menucolor = '222';
	}
	
	
	
	/* en usko että tätä tarvitaan, ollaan itse lisätty jossainvaiheessa
	function __construct($sitepath) {
		$this->sitepath = $sitepath;
	}
	
	
	public function getSitePath() {
		return $this->sitepath;
	}
	*/
	
	/**
	 *
	 * @set undefined vars
	 *
	 * @param string $index
	 *
	 * @param mixed $value
	 *
	 * @return void
	 *
	 */
	public function __set($index, $value)
	{
		$this->vars[$index] = $value;
	}

	/**
	 *
	 * @get variables
	 *
	 * @param mixed $index
	 *
	 * @return mixed
	 *
	 */
	public function __get($index)
	{
		if (!isset($this->vars[$index])) {
			//if (DEV) echo "<br>" . debug_print_backtrace();
			return null;
		}
		return $this->vars[$index];
	}

	
	
	public function __isset($index)
	{
		return isset($this->vars[$index]);
	}
	
		
	
	public function issetted($index) {
		return isset($this->vars[$index]);
	}
	
	
	public function loadParams() {
		
		foreach($_GET as $index => $value) {
			if ($index != 'rt') {
				$this->vars[$index] = $value;
			}
		}
	}
	
}

?>