<?php



class WorkordersController extends AbstractController {

	
	
	public function getCSSFiles() {
		//return array('menu.css','testcss.php');
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
	
	
	public function indexAction() {
		//$this->showordersAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	

	public function showordersAction() {
		
		updateActionPath('Tilaukset');
		
		$this->registry->orders = Table::load('workorders_orders');
		$this->registry->companies = Table::load('crm_companies');
		$this->registry->locations = Table::load('crm_locations');
		$this->registry->worktitles = Table::load('hr_worktitles');
		
		$clients = Table::load('crm_clients');
		
		$clientnames = array();
		foreach($clients as $index => $client) {
			//$clientnames[$client->clientID] = $client->firstname . " " . $client->lastname;
			$client->fullname = $client->firstname . " " . $client->lastname;
		}
		$this->registry->clients = $clients;
		$this->registry->template->show('workorders/workorders','workorders');
	}
	
	
	public function showworkorderAction() {
		
		$orderID = $_GET['id'];
		
		$this->registry->order = Table::loadRow('workorders_orders', $orderID);
		
		$this->registry->companies = Table::load('crm_companies');
		$this->registry->locations = Table::load('crm_locations');
		$this->registry->worktitles = Table::load('hr_worktitles');
		$this->registry->workerbindings = Table::load('workorders_workerbindings',' WHERE OrderID=' . $orderID);
		
		$clients = Table::load('crm_clients');
		
		$clientnames = array();
		foreach($clients as $index => $client) {
			//$clientnames[$client->clientID] = $client->firstname . " " . $client->lastname;
			$client->fullname = $client->firstname . " " . $client->lastname;
		}
		$this->registry->clients = $clients;
		
		
		$this->registry->workers = Table::load('hr_workers');
		foreach($this->registry->workers as $index => $worker) {
			//$clientnames[$client->clientID] = $client->firstname . " " . $client->lastname;
			$worker->fullname = $worker->firstname . " " . $worker->lastname;
		}
		
		foreach($this->registry->workerbindings as $index => $binding) {
			$worker = $this->registry->workers[$binding->workerID];
			$binding->fullname = $worker->firstname . " " . $worker->lastname;
		}
		
		
		$this->registry->workerorders = Table::load('workorders_workerorders', ' WHERE OrderID='.$orderID);
		$ordertitles = array();
		foreach($this->registry->workerorders as $index => $workerorder) {
			$ordertitles[$workerorder->workerorderID] = $workerorder;
			$title = $this->registry->worktitles[$workerorder->worktitleID];
			$workerorder->title = $title->name;
		}
		$this->registry->ordertitles = $ordertitles;
		
		
		
		
		//$this->registry->company = $this->registry->companies[$this->registry->order->companyID];
		//$this->registry->locaion = $this->registry->locations[$this->registry->order->workorderID];
		
		$this->registry->template->show('workorders/workorders','workorder');
	}
	
	
	
	public function updateworkorderAction() {
		
		$success='';
		$columns = array();
		foreach($_GET as $index => $value) {
			if (($index != 'id') && ($index != 'rt')) {
				$columns[$index]=$value;
			} elseif ($index == 'id') {
				$id = $value;
			}
		}
		$success = Table::updateRow('crm_companies', $columns, $id, false);
		redirecttotal('crm/companies/showcompany&id=' . $id, null);
	}
	


	public function insertworkerorderAction() {
	
		$workorderID = $_GET['workorderid'];
		$worktitleID = $_GET['worktitleID'];
		$startdate = $_GET['startdate'];
		$count = $_GET['count'];
	
		$values = array();
		$values['WorkorderID'] = $workorderID;
		$values['WorktitleID'] = $worktitleID;
		$values['OrderID'] = $workorderID;
		$values['Starttime'] = $startdate;
		$values['Workercount'] = $count;
	
		$orderID = Table::addRow("workorders_workerorders", $values, true);
	}
	
	

	public function bindworkerAction() {
	
		$orderID = $_GET['orderID'];
		$workerorderID = $_GET['workerorderID'];
		$startdate = $_GET['startdate'];
		$workerID = $_GET['workerID'];
	
		$workerorder = Table::loadRow('workorders_workerorders', $workerorderID);
		$titleID = $workerorder->worktitleID;
		
		$values = array();
		$values['OrderID'] = $orderID;
		$values['WorktitleID'] = $titleID;
		$values['WorkerorderID'] = $workerorderID;
		$values['WorkerID'] = $workerID;
		$values['Startdate'] = $startdate;
		
		$orderID = Table::addRow("workorders_workerbindings", $values, true);
	}
	
	
	public function insertorderAction() {

		$companyID = $_GET['companyID'];
		$locationID = $_GET['locationID'];
		$clientID = $_GET['clientID'];
		$startDate = $_GET['startdate'];
		
		$values = array();
		$values['CompanyID'] = $companyID;
		$values['LocationID'] = $locationID;
		$values['ClientID'] = $clientID;
		$values['Startdate'] = $startDate;
		
		
		$orderID = Table::addRow("workorders_orders", $values, true);
	}

	
	
}

?>
