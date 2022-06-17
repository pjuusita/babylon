<?php


class UtilsController extends AbstractController {


	
	public function getCSSFiles() {
		return array('menu.css', 'testcss.php','mytheme/jquery-ui-test.css');
		//return array('menu.css','testcss.php');
		//return array('menu.css', 'main.css','mytheme/jquery-ui.css','yritys.css','petestyle.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
		

	public function indexAction() {
		//echo "<br>No index action available";
		$this->registry->template->show('system/error','unknown');
	}

}

?>
