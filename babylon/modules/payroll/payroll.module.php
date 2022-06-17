<?php

/**
 *  Laajuus/scope liittyy ehka asiakashallinnan monipuolisuuteen. Mini versiossa
 *  yrityksilla on ainoastaan yksi osoite. Laajemmissa versioissa asiakkaalla voi 
 *  olla useampia toimipaikkoja, kustannuspaikkoja (tyamaa, halli, tehdas tms.) ja/tai
 *  tytaryhtiaita.
 *  
 *  Mini versiossa yrityksilla voi myas olla ainoastaan yksi laskutusosoite, laajemmassa
 *  versiossa voi olla useampia laskutusosoitteita.
 * 
 *
 */

class PayrollModule extends AbstractModule {
		
	private static $pensioninsurancetypes = null;

	const ACCESSLEVEL_READ = 1;
	const ACCESSLEVEL_WRITE = 2;
	const ACCESSLEVEL_WRITEREMOVE = 3;
	const ACCESSLEVEL_ALL = 99;
	
	const ACCESSRIGHTKEY_PAYROLL = 'payroll_accesskey';
	
	const MENUKEY_PAYROLL = 'menukey_payroll';
	
	const DEDUCTIONTYPE_EMPLOYEE_PENSION_17_52 = 1;			// Palkansaajan työttömyysvakuutusmaksu
	const DEDUCTIONTYPE_EMPLOYEE_PENSION_53_62 = 2;			// Palkansaajan työttömyysvakuutusmaksu
	const DEDUCTIONTYPE_EMPLOYEE_PENSION_63_67 = 3;			// Palkansaajan työttömyysvakuutusmaksu
	
	const DEDUCTIONTYPE_EMPLOYER_PENSION_17_52 = 4;			// Työnantajan työttömyysvakuutusmaksu
	const DEDUCTIONTYPE_EMPLOYER_PENSION_53_62 = 5;			// Työnantajan työttömyysvakuutusmaksu
	const DEDUCTIONTYPE_EMPLOYER_PENSION_63_67 = 6;			// Työnantajan työttömyysvakuutusmaksu
	
	const DEDUCTIONTYPE_WITHOLDINGTAX = 7;			// Työnantajan työttömyysvakuutusmaksu
	
	const DEDUCTIONTYPE_EMPLOYEE_UNEMPLOYMENT = 8;			// Palkansaajan työttömyysvakuutusmaksu
	const DEDUCTIONTYPE_EMPLOYER_UNEMPLOYMENT = 9;			// Työnantajan työttömyysvakuutusmaksu
	
	const DEDUCTIONTYPE_EMPLOYER_ACCIDENTINSURANCE = 10;			// Työtapaturmavakuutusmaksu
	const DEDUCTIONTYPE_EMPLOYER_SICKNESSINSURANCE = 11;			// Sairasvakuutusmaksu
	const DEDUCTIONTYPE_EMPLOYER_LIFEINSURANCE = 14;				// Ryhmähenkivakuutus
	const DEDUCTIONTYPE_EMPLOYER_VACATIONSALARYRESERVATION = 17;
	
	// Tietokannassa vanhat arvot, näitä ei mielestäni käytetä missään
	//  1 - työeläkemaksu
	//  2 - Työtapaturmamaksu
	//  3 - Työttömyysvakuutusmaksu
	//  4 - Sairausvakuutusmaksu
	//  5 - Ennakonpidätys
	


	
	public function getDefaultName() {
		return "Palkanlaskenta";
	}
	

	public function getDimensions() {
		$dimensions = array();
		$dimensions[Dimension::DIMENSION_COMPANY] = new Dimension(Dimension::DIMENSION_COMPANY, "Yritys", "Yritykset", "system_companies");
		$dimensions[Dimension::DIMENSION_BRANCH] = new Dimension(Dimension::DIMENSION_BRANCH, "Toimiala", "Toimialat", "system_branches");
		$dimensions[Dimension::DIMENSION_OFFICE] = new Dimension(Dimension::DIMENSION_OFFICE, "Toimipiste", "Toimipisteet", "system_offices");
		$dimensions[Dimension::DIMENSION_DEPARTMENT] = new Dimension(Dimension::DIMENSION_DEPARTMENT, "Osasto", "Osastot", "system_departments");
		return $dimensions;
	}
	
	
	const ACCESSKEY_PAYROLL_SETTINGS = 'accesskey_payroll_settings';
	const ACCESSKEY_PAYROLL_PAYROLLS = 'accesskey_payroll_payroll';
	
	
	
	public function getAccessRights() {
	
		$accessrights = array();
		$accessrights[PayrollModule::ACCESSKEY_PAYROLL_SETTINGS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		$accessrights[PayrollModule::ACCESSKEY_PAYROLL_PAYROLLS] = AbstractModule::ACCESSKEYTYPE_READWRITE;
		
		//$accesslevels = array();
		//$accesslevels[PayrollModule::ACCESSLEVEL_ALL] = "payroll_accesslevel_all";
		//$accessrights[PayrollModule::ACCESSRIGHTKEY_PAYROLL] = $accesslevels;
	
		return $accessrights;
	}
	
	
	/**
	 * 
	 * 
	 * {@inheritDoc}
	 * @see AbstractModule::getMenu()
	 */
	public function getMenu($accessrights) {
	
		$menuitems = array();
		$accesslevel = $accessrights[PayrollModule::ACCESSKEY_PAYROLL_PAYROLLS];
		$menuindex = 0;
		
		// TODO: Menut pitäisi jotenkin saada ehdolliseksi. Esimerkiksi jos moduleista on käytössä henkilöstö moduli, niin
		//		 tällöin palkanlaskennassa ei tarvitsisi erikseen omaa henkilöstö-alimenua. Tämä pitäisi olla käyttäjäkohtainen
		//		 tieto, eli ei riitä, että moduli on järjestelmässä aktiviinen, vaan pitää olla käyttöoikeudet kyseiseen moduliin
		
		if ($accesslevel > 0) {
			$menuitems[] = new Menu("Palkat","payroll/payroll","showpayroll",Menu::MENUKEY_TOP,PayrollModule::MENUKEY_PAYROLL,550);
			$menuitems[] = new Menu("Henkilöstö","hr/workers","showworkers",PayrollModule::MENUKEY_PAYROLL,null,550);
			$menuitems[] = new Menu("Palkkalaskelmat","payroll/payroll","showpayroll",PayrollModule::MENUKEY_PAYROLL,null,550);
			$menuitems[] = new Menu("Palkanlaskenta","payroll/payrollsettings","showsettings",Menu::MENUKEY_ADMIN,null,560);
			//$menuitems[] = new Menu("Palkanlaskenta","payroll/payrollsettings","showsettings",Menu::MENUKEY_ADMIN,null,550);
		}
		return $menuitems;
	}
	
	
	public function hasAccessRight($action) {
	
		return true;
		
		//$accesslevel = getAccessLevel(PayrollModule::ACCESSRIGHTKEY_PAYROLL);
		//echo "<br>PayrollModule accesslevel - " . $accesslevel;
		return true;
		
		if ($accesslevel == 0) {
			echo "<br>Accesslevel false";
			return false;
		}
		return true;
		
		switch($action) {
			case "payroll/showpayroll":
				return true;
				break;
			case "payroll/showpaycheck":
				return true;
				break;
			case "payroll/updatepaycheck":
				return true;
				break;
			case "payroll/insertentry":
				return true;
				break;
			case "payroll/updateentry":
				return true;
				break;
			case "payroll/insertpaycheckrow":
				return true;
				break;
			case "payroll/updatepaycheckrow":
				return true;
				break;
			case "payroll/updateexpenserows":
				return true;
				break;
			case "payroll/acceptpaycheck":
				return true;
				break;
			case "payroll/updatepaychecktotals":
				return true;
				break;
			case "payroll/uploadpaycheck":
				return true;
				break;
			case "payroll/downloadpaycheck":
				return true;
				break;
			case "payroll/removepaycheckattachment":
				return true;
				break;
			case "payroll/getworkerpaycheckdataJSON":
				return true;
				break;
			case "payroll/returntoopen":
				return true;
				break;
			case "payrollsettings/insertlabouragreement":
				return true;
				break;
			case "payrollsettings/updatelabouragreement":
				return true;
				break;
			case "payrollsettings/insertpayrollperiod":
				return true;
				break;
			case "payrollsettings/updatepayrollperiod":
				return true;
				break;
			case "payrollsettings/insertworktitle":
				return true;
				break;
			case "payrollsettings/updateworktitle":
				return true;
				break;
			case "payrollsettings/insertsalarytype":
				return true;
				break;
			case "payrollsettings/updatesalarytype":
				return true;
				break;
			case "payrollsettings/updatedeductiontype":
				return true;
				break;
			case "payrollsettings/insertdeductiontype":
				return true;
				break;
			case "payrollsettings/insertdeductiontype":
				return true;
				break;
			case "payrollsettings/showsalarytype":
				return true;
				break;
			case "payrollsettings/insertlabouragreementtosalarytype":
				return true;
				break;
			case "labouragreements/showlabouragreement":
				return true;
				break;
			case "payrollsettings/updatedeductionaccount":
				return true;
				break;
			case "payrollsettings/updatesalarytypeaccount":
				return true;
				break;
		}
		return false;
	}
	
	
	public function getBookkeepingPeriod() {
	
	
		if (isset($_GET['periodID'])) {
			$periodID = $_GET['periodID'];
			setModuleSessionVar('periodID', $periodID);
			return $periodID;
		} else {
			$periodID = getModuleSessionVar('periodID');
			if (($periodID == null) || ($periodID == '')) {
				//echo "<br>No period selected";
				$periods = Table::load('accounting_periods');
				if ($periods == null) {
					echo "<br>Tilikausia ei löydy";
					return null;
				}
				foreach($periods as $index => $period) {
					setModuleSessionVar('periodID', $period->periodID);
					return $period->periodID;
					//echo "<br> -- period found - " . $period->name;
				}
			} else {
				//echo "<br>not null period *" . $periodID . "*";
			}
			return $periodID;
		}
	
	}
	
	
	public function hasAccess($accesskey) {
		
		$accesslevel = getAccessLevel(PayrollModule::ACCESSRIGHTKEY_PAYROLL);
		echo "<br> - accesslevel - " . $accesslevel;
		if ($accesslevel > 0) {
			return true;
		}
		return false;
	}
	
	
}


?>