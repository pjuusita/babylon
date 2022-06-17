<?php


class BookingModule extends AbstractModule {
	
	const ACCESSLEVEL_PLAYER = 1;
	const ACCESSLEVEL_MANAGER = 2;
	const ACCESSLEVEL_ADMIN = 3;
	
	const ACCESSRIGHTKEY_RESERVATIONS = 'bookings_rights';
	
	const MENUKEY_RESERVATION = 'menukey_reservation';
	
	const ACCESSKEY_BOOKING_SETTINGS = 'accesskey_booking_settings';
	const ACCESSKEY_BOOKING_BOOKINGS = 'accesskey_booking_bookings';
	
	
	public function getDefaultName() {
		return "Varausasetukset";
	}
	

	// TODO: Pikakorjattu, ei mietitty
	public function getAccessRights() {
	
		$accessrights = array();
		$accessrights[BookingModule::ACCESSKEY_BOOKING_SETTINGS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[BookingModule::ACCESSKEY_BOOKING_BOOKINGS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		
		//$accesslevels = array();
		//$accesslevels[BookingModule::ACCESSLEVEL_ADMIN] = "reservation_accesslevel_all";
		//$accesslevels[BookingModule::ACCESSLEVEL_MANAGER] = "reservation_accesslevel_manager";
		//$accesslevels[BookingModule::ACCESSLEVEL_PLAYER] = "reservation_accesslevel_player";
		//$accessrights[BookingModule::ACCESSRIGHTKEY_RESERVATIONS] = $accesslevels;
	
		return $accessrights;
	}
	
	
	
	public function getMenu($accessrights) {
	
		$menuitems = array();
		$accesslevel = $accessrights(BookingModule::ACCESSKEY_BOOKING_BOOKINGS);
		//$accesslevel = getAccessLevel(BookingModule::ACCESSRIGHTKEY_RESERVATIONS);
		$menuindex = 0;
		if ($accesslevel > 0) {
			$menuitems[] = new Menu("Varaukset","booking/reservation","showsettings",Menu::MENUKEY_TOP,BookingModule::MENUKEY_RESERVATION,2350);
			$menuitems[] = new Menu("Varaukset","booking/reservation","showsettings",BookingModule::MENUKEY_RESERVATION,null,2450);
			//$menuitems[] = new Menu("Varaushallinta","booking/reservationmanager","showsettings",BookingModule::MENUKEY_RESERVATION,null,2450);
			$menuitems[] = new Menu("Varausasetukset","booking/reservationsettings","showsettings",Menu::MENUKEY_ADMIN,null,2450); 
		}
		return $menuitems;
	}
	
	
	
	
	public function hasAccessRight($action) {
	
		$accesslevel = getAccessLevel(BookingModule::ACCESSRIGHTKEY_RESERVATIONS);
		if ($accesslevel == 0) {
			echo "<br>Accesslevel false";
			return false;
		}
	
		switch($action) {
			case "reservationsettings/showsettings":
				return true;
				break;
			case "reservationsettings/insertobject":
				return true;
				break;
			case "reservationsettings/updateobject":
				return true;
				break;
			case "reservationsettings/removeobject":
				return true;
				break;
		}	
		
		return false;
	}
	


	public function hasAccess($accesskey) {
		return false;
	}
	
}


?>