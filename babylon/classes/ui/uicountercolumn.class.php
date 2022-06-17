<?php

/**
 * En tiedä tarvitaanko tätä mihinkään, voidaan tehdä suoraan tablesectionille, että näyttää rivinumerot.
 * 
 * @author pjuusita
 *
 */
class UICounterColumn extends UIColumn {

	
	public function __construct($name, $datavariable, $sortlink = null, $width = '') {
		parent::__construct("", "");
	}

	public function setLink($linkurl, $linkvariable) {
	}

	
	public function setSortIcons($iconup,$icondown,$size) {
	}
}
?>