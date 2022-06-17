<?php

/**
 *  System module on tyypiltään BASE, koska tämän moduulin toiminnot näkyvät kuitenkin menussa superuserille.
 *  Ja muutenkin toiminnallisuus on samanlainen kuin muilla BASE-ryhmän moduleilla.
 *  
 *  System module pitäisi ehkä olla näkyvissä pelkästään superuserille devaukseen, tämä on ylempi käyttäjä 
 *  adminin yläpuolella. Ei pitäisi näkyä loppukäyttäjille lainkaan.
 *  
 *  
 *  Pohdintaa sybsystemeistä... 7.12.2019 teksit poistettu vanhasta controllerista subsystems.controller.php
 *  TODO: tämä teksti on siirrettävä johonkin dokumenttointiin ja pohdittava paremmin. 
 *  Todennäköisesti vanhentunutta pohdintaa
 *  
 *  Tämä taitaa olla vähän sellainen meta tason moduli. System-moduli. 
 *  
 *  Tämän avulla hallitaan tilannetta niin, että järjestelmä voi käyttää useampaa
 *  tietokantaa, sisäänkirjautuessa joko ohjataan johonkin oletustietokantaan, tai 
 *  jos on oikeudet useampaan systeemiin, niin sitten sisäänkirjautumisen jälkeen
 *  valitaan mitä tietokantaa käytetään. 
 *  
 *  Jokainen tietokanta toimii standalone järjestelmänään. Ne laskutetaan lähtäkohtaisesti
 *  erikseen siten, että kirjanpitotoimisto hostaa yksittäisen asiakkaan kirjanpidon, mutta
 *  mikäli loppukäyttäjä ostaa lisää moduleita, niin nämä laskutetaan erikseen. vaihtoehtoinen
 *  tapa on, että nämä läpilaskutetaan kirjanpitotoimiston kautta. Esimerkiksi kirjanpito ja
 *  palkanlaskenta tulee kirjanpitotoimiston kautta, mutta esimerkiksi tuotehallinta tulee
 *  ehkä suoraan laskuttaen. Tästä on ehkä poikkeuksena nimenomaan tämä tilitoimisto, joka
 *  on tavallaan kokonaisvaltainen palveluntarjoaja. 
 *  
 *  Alidoimainien kautta palvelun tarjoaminen onkin sitten jo hankalampi operaatio, joko 
 *  asiakkaan domainissa tai tilitoimiston alidomainissa. Lähtäkohtana varmaankin on, että
 *  loppuasiakkaan domainiin emme tarjoa laskutusta tilitoimiston kautta, mutta tilitoimiston
 *  käyttäjät pystyvät kyllä kirjautumaan tähän omaan domainiin omilla tunnareillaan 
 *  central-loginin kautta (valtuutuksien hyväksymänä).
 *  
 *  Tämän mukana taitaa tulla appusers-taulu ainakin, jos se ei ole jo oletuksena mukana
 *  tai sitten lisätään appusers-tauluun uusi sarake target-db. Tämä tulee ainakin
 *  tilitoimisto paketin mukana
 * 
 *  Tilitoimisto-moduli tarjoaa ehkä nimenomaan taloushallintaan liittyvää kalustoa, ehkä
 *  myäs tuntikirjauksiin liittyvää kalustoa. Tässä on ideana oikeastaan se, että meillä
 *  ei tarvitse olla omaa myyntiä kun kirjanpito asiakkaat hoitavat myynnin. Isommat asiakkaat
 *  sitten voivat ostaa laajemman toiminnanohjausjärjestelmän suoraan. Lähtäkohtaisesti
 *  hinnoittelu on loppuasiakkaille ja kirjanpitotoimistoille sama, mutta ehkä kirjanpito
 *  toimistoille pitäisi hankkia joitakin määräalennuksia. Lähtäkohtana pitäisi kuitenkin
 *  olla, että kirjanpitotoimisto ei saisi suoranaisesti rahastaa mun tuotteella. 
 *  
 *  Ehkä kirjanpito-ohjelmiston laskutuksen kiinnittämistä käyttäjien määrän sitoen voisi
 *  pakottaa asiakkaan hankkimaan ohjelman suoraan meiltä. Tällätavoin saataisiin varmaankin
 *  myäs serverin kuormitusta jaettua.
 *  
 *  Ehkä oletuksena myäs asiakkaiden tuotehallinta, josta voidaan tilata/lisätä asiakkaille
 *  tuotteita. Tämä sivu näkyy kyllä loppukäyttäjien tuotehallinnassa, mutta yläjärjestelmällä
 *  voisi olla tätä varten oma näkymänsä. Mahdollisesti myäs asiakaskohtainen hinnasto. Ja
 *  asiakaskohtainen tarjooma, sekä ylä, että alatasolla.
 *  
 *  Ideana jokatapauksessa olisi, että oikeastaan nämä tilitoimistot olisivat mun palvelun
 *  tarjoajia. Jolloin mä saisin toimintaa kasvatettua vaivattomasti vain hankkimalla 
 *  tilitoimistokäyttäjiä. 
 *  
 */
class SystemModule extends AbstractModule {

	

	public function getDefaultName() {
		//return "[1]System[2]Järjestelmän sisäiset toiminnot";
		return "system_modulename";
	}
	
	
	public function getAccessRights() {
		return array();
	}
	
	
	public function getMenu($userrights) {
		return array();
	}

	
	// System-module sisältää ainoastaan toimintoja, jotka on aina käytettävissä kaikille
	// käyttäjien hallinta sensitiivisille operaatioille pitää hoitaa tapauskohtaisesti
	// yleensä nämä toiminnot ovat taustalla toimivia operaatioita, kuten tiedoston lataamista
	// sessiomuuttujien asetusta, menu operaatioita jne. Ei käyttöoikeustsekkauksia normaali
	// modulin tapaan
	public function hasAccessRight($action) {
		return true;
	}
	

	public function hasAccess($accesskey) {
		

		// Nämä on erikoistapauksia install toimintoihin, näihin ei tartte/voi hakea getAccessLevel
		// funktion arvoa, koska usergroupID-ei ole näissä tapauksissa asetettu
		// update 12.12.2019 - näiden funktionti kutsuoikeudet pitäisi ratkaista jotenkin muuten 
		//					 - ei saisi olla julkinen. Jonkinlainen productkey tms.
		/*
		switch($accesskey) {
			case "dbsync/getmodules":
				return true;				// oikeudet pitäisi jotenkin tsekata, serverkey tms.
				break;
			case "dbsync/getcolumns":
				return true;				// oikeudet pitäisi jotenkin tsekata, serverkey tms.
				break;
			case "dbsync/gettables":
				return true;				// oikeudet pitäisi jotenkin tsekata, serverkey tms.
				break;
		}
		*/
		return true;
	}
}


?>