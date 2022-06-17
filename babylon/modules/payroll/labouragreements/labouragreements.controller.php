<?php



class LabouragreementsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
	
	
	public function indexAction() {
		//$this->showlabouragreementAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	public function showlabouragreementAction() {
	
		$comments = false;
		updateActionPath("Työehtosopimus");
		
		$labouragreementID = $_GET['id'];
		$this->registry->labouragreement = Table::loadRow('hr_labouragreements',"WHERE LabouragreementID=" . $labouragreementID, $comments);
		$worktitlelinks = Table::load('hr_worktitlelinks',"WHERE LabouragreementID=" . $labouragreementID, $comments);
		if ($comments) echo "<br>Selected count - " . count($worktitlelinks);
		$selectedworktitles = array();
		
		foreach($worktitlelinks as $index => $link) {
			$selectedworktitles[$link->worktitleID] = $link->worktitleID;
			if ($comments) echo "<br>Selected - " . $link->worktitleID;
		}
		$this->registry->worktitles = Table::loadWhereInArray('hr_worktitles','worktitleID', $selectedworktitles, "WHERE SystemID=" . $_SESSION['systemID'],$comments);
		//echo "<br>worktitles - " . $link->worktitleID;
		$this->registry->periods = Table::loadRow('payroll_periods',"WHERE LabouragreementID=" . $labouragreementID, $comments);
		$this->registry->salarycategories = Table::loadRow('hr_salarycategories',"WHERE LabouragreementID=" . $labouragreementID, $comments);
		$this->registry->payrollperiods = Table::load('payroll_periods',"WHERE LabouragreementID=" . $labouragreementID . " ORDER BY Startdate DESC", $comments);
		
		$this->registry->template->show('payroll/labouragreements','labouragreement');
	}

	
	

	// TODO: onko tämä väärässä paikassa ylimääräinen?
	public function insertpayrollperiodAction() {
	
		$values = array();
		$values['LabouragreementID'] = $_GET['labouragreementID'];
		$values['Name'] = $_GET['name'];
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];
		$values['Bookkeepingdate'] = $_GET['enddate'];
		$values['Paymentdate'] = $_GET['paymentdate'];
		$success = Table::addRow("payroll_periods", $values, true);
		redirecttotal('payroll/payrollsettings/showsettings',null);
	}
	
	
	
	
	

	
}

?>
