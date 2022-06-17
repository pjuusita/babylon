<?php

/**
 * Settingscontrollerissa hallitaan jarjestelman asetuksia. Periaatteessa jokaista kaytassa olevaa modulia varten on oma
 * sectioninsa, jokaiselta modulilta tulisi siis saada tarpeelliset asetukset.
 * 
 * Perusasetuksia on muutamia, ainakin ulkoasuun ja logoon liittyvia asetuksia, mutta periaatteessa namakin voisivat tulla
 * suoraan kaytettavissa olevista oletusmoduleista. Lahinna kai niin, etta system module on automaattisesti kaytassa, myas admin
 * moduli on todennakaisesti automaattisesti kaytassa. Osa moduleista on tosin pelkastaan hallinta kayttaan, esim. database.
 * 
 * Aika vähällä käytöllä, tätä käytetään ilmeisesti lähes pelkästään treesectionin ja sectioneiden
 * avaamiseen, sulkemiseen ja tilan hallintaan. Nämä voidaan varmasti toteuttaa jotenkin muuten suoraan
 * uitreesectionissa tai muualla frameworkissä. Treen nodejen aukioleminen ei muutenkaan toimi kovin 
 * hyvin, että se vaatisi muutenkin viilaamista. Samalla voisi luopua tästä kontrollerista ehkä, jos
 * vain tyylikkäämpi ratkaisu keksitään. Pääosa session hallinnasta on toteutettu init.php:ssä.
 *
 */
class SessionController extends AbstractController {

	
	public function getCSSFiles() {
		return array();
	}

	
	public function getJSFiles() {
		return array();
	}
	
	
	
	public function getTemplate($action) {
		return 'minimal';
	}

	
	public function indexAction() {
		$this->registry->template->show('system/error','unknown');
	}

	
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function opensectionAction() {
		$sectionID = $_GET['id'];
		setSectionOpen($sectionID);
		//echo "[{\"success\":\"true\"}]";
		echo "" . 3;
	}
	
	
	public function closesectionAction() {
		$sectionID = $_GET['id'];
		setSectionClosed($sectionID);
		echo "" . 4;
	}
		
	
	public function opentreenodeAction() {
		
		$treeID = $_GET['treeid'];
		$rowID = $_GET['rowid'];
		
		$array = getSessionArray('tree-' . $treeID);
		$array[$rowID] = $rowID;
		setSessionArray('tree-' . $treeID, $array);
		
		echo "";
	}

	
	public function clearresourcetextsAction() {
		// Poista session muuttujasta kaikki R_-alkuiste tekstit
		foreach($_SESSION as $index =>  $value) {
			if (substr($index,0,2) === "R_") {
				echo "<br>match - " . $index;
				unset($_SESSION[$index]);
			} else {
				echo "<br>---- " . $index;
			}
		}
		redirecttotal('system/frontpage/index', null);
	}
	
	
	public function closetreenodeAction() {
		
		$treeID = $_GET['treeid'];
		$rowID = $_GET['rowid'];
		
		$array = getSessionArray('tree-' . $treeID);
		unset($array[$rowID]);
		setSessionArray('tree-' . $treeID, $array);
		
		echo "";
	}
	
}

?>
