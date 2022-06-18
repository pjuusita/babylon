<?php


class TimesheetModule extends AbstractModule {
	
	
	const MENUKEY_TIMESHEET = 444;

	public function getDefaultName() {
		return "[1]Timesheet[2]Tuntiseuranta";
	}
	
	

	public function getMenu($accessrights) {
	
		$menuitems = array();
		/*
		if (isset($accessrights[TimesheetModule::ACCESSRIGHTKEY_WORKORDERS])) {
			$accesslevel = $accessrights[TimesheetModule::ACCESSRIGHTKEY_WORKORDERS];
				
			if ($accesslevel > 0) {
				$menuitems[0] = new Menu("Tuntikirjaukset","timesheet/timesheets","showtimesheets",Menu::MENUKEY_TOP,TimesheetModule::MENUKEY_TIMESHEET,200);
				$menuitems[1] = new Menu("Avoimet","timesheet/timesheets","showactivetimesheets",TimesheetModule::MENUKEY_TIMESHEET,null,210);
				$menuitems[2] = new Menu("Arkisto","timesheet/timesheets","showarchive",TimesheetModule::MENUKEY_TIMESHEET,null,220);
				$menuitems[2] = new Menu("Tuntikirjaus","timesheet/settings","showsettings",TimesheetModule::MENUKEY_TIMESHEET,null,220);
			}
		}
		*/
		return $menuitems;
	}
    public function getAccessRights()
    {}

    public function hasAccess($accesskey)
    {}

    public function hasAccessRight($action)
    {}

	
	
}


?>