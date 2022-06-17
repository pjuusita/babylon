<?php

	echo "<h1>" . $_SESSION['database'] . "</h1>";

	
	echo "<br><b>Found tables</b>";
	foreach($registry->localtables as $index => $localtable) {
		$tablefound = 0;
		foreach($registry->remotetables as $index2 => $remotetable) {
			if ($localtable->name == $remotetable->name) {
				echo "<br>" . $index . " - " . $localtable->tableID . " - " . $localtable->name . " -- found - remote: "  . $remotetable->tableID;
				$tablefound++;
				
				foreach($registry->localcolumns as $index3 => $localcolumn) {
					
					if ($localcolumn->tableID == $localtable->tableID) {
						//echo "<br> -- " . $localcolumn->variablename;
						$found = 0;
						foreach($registry->remotecolumns as $index4 => $remotecolumn) {
							if ($remotecolumn->tableID == $remotetable->tableID) {
								if ($remotecolumn->columnname == $localcolumn->columnname) {
									//echo " -- found2";
									$found++;
									
									if ($remotecolumn->variablename != $localcolumn->variablename) echo "<br><font style='color:red'>missmatch - variablename - " . $remotecolumn->variablename . " - " . $localcolumn->variablename . "</font>";
									if ($remotecolumn->name != $localcolumn->name) echo "<br><font style='color:red'>missmatch - name - " . $remotecolumn->name . " - " . $localcolumn->name . "</font>";
									if ($remotecolumn->type != $localcolumn->type) echo "<br><font style='color:red'>missmatch - type - " . $remotecolumn->type . " - " . $localcolumn->type . "</font>";
									if ($remotecolumn->obligatory != $localcolumn->obligatory) echo "<br><font style='color:red'>missmatch - obligatory - " . $remotecolumn->obligatory . " - " . $localcolumn->obligatory . "</font>";
									if ($remotecolumn->referencetableID != $localcolumn->referencetableID) echo "<br><font style='color:red'>missmatch - referencetableID - " . $remotecolumn->referencetableID . " - " . $localcolumn->referencetableID . "</font>";
									if ($remotecolumn->editable != $localcolumn->editable) echo "<br><font style='color:red'>missmatch - editable - " . $remotecolumn->editable . " - " . $localcolumn->editable . "</font>";
									if ($remotecolumn->min != $localcolumn->min) echo "<br><font style='color:red'>missmatch - min - " . $remotecolumn->min . " - " . $localcolumn->min . "</font>";
									if ($remotecolumn->max != $localcolumn->max) echo "<br><font style='color:red'>missmatch 2 - max - " . $remotecolumn->max . " - " . $localcolumn->max . "</font>";
									if ($remotecolumn->defaultvalue != $localcolumn->defaultvalue) echo "<br><font style='color:red'>missmatch 2 - defaultvalue - " . $remotecolumn->defaultvalue . " - " . $localcolumn->defaultvalue . "</font>";
									if ($remotecolumn->tablevisibility != $localcolumn->tablevisibility) echo "<br><font style='color:red'>missmatch - tablevisibility - " . $remotecolumn->tablevisibility . " - " . $localcolumn->tablevisibility . "</font>";
									if ($remotecolumn->sectionvisibility != $localcolumn->sectionvisibility) echo "<br><font style='color:red'>missmatch - sectionvisibility - " . $remotecolumn->sectionvisibility . " - " . $localcolumn->sectionvisibility . "</font>";
									if ($remotecolumn->removeID != $localcolumn->removeID) echo "<br><font style='color:red'>missmatch - removeID - " . $remotecolumn->removeID . " - " . $localcolumn->removeID . "</font>";
									if ($remotecolumn->logvalue != $localcolumn->logvalue) echo "<br><font style='color:red'>missmatch - logvalue - " . $remotecolumn->logvalue . " - " . $localcolumn->logvalue . "</font>";
									
									
								}
							}
						}
						if ($found == 0) {
							echo "<br><font style='color:red'> -- " . $localcolumn->variablename . " -- not found</font>";
						}
					}
				}
				
				
				foreach($registry->remotecolumns as $index3 => $remotecolumn) {
						
					if ($remotetable->tableID == $remotecolumn->tableID) {
						$found = 0;
						foreach($registry->localcolumns as $index4 => $localcolumn) {
							if ($localcolumn->tableID == $localtable->tableID) {
								if ($remotecolumn->columnname == $localcolumn->columnname) {
									$found++;
								}
							}
						}
						if ($found == 0) {
							echo "<br><font style='color:blue'>" . $remotecolumn->variablename . " -- not found</font>";
						}
					}
				}
				
				
			}
		}
		if ($tablefound == 0) {
			//echo "<br>Table not found - "  . $localtable->name;
		}
	}


	echo "<br><br><b>Not found tables in remote</b>";
	foreach($registry->localtables as $index => $localtable) {
	
		$found = 0;
		foreach($registry->remotetables as $index2 => $remotetable) {
			if ($localtable->name == $remotetable->name) {
				$found++;
			}
		}
		if ($found == 0) {
			echo "<br>" . $index . " - " . $localtable->name . " -- not found";
		}
	}
	
	
	
	echo "<br><br><b>Not found tables in local</b>";
	foreach($registry->remotetables as $index => $remotetable) {
	
		$found = 0;
		foreach($registry->localtables as $index2 => $localtable) {
			if ($localtable->name == $remotetable->name) {
				$found++;
			}
		}
		if ($found == 0) {
			echo "<br>" . $index . " - " . $remotetable->name . " -- not found";
		}
	}
	

	echo "<br><br><b>Localcolumns</b>";
	
	// lisää puuttuvien taulujen ID-numeroiden tsekkaus
	foreach($registry->localcolumns as $index => $localcolumn) {
		if (isset($registry->localtables[$localcolumn->tableID])) {
			//echo "<br>" . $index . " - " . $localcolumn->variablename . " -- " . $localcolumn->tableID;
		} else {
			echo "<br>" . $index . " - " . $localcolumn->variablename . " -- " . $localcolumn->tableID . " -- no table found";
			
			
		}
	}

	
	// TODO: tarvittaisiin vielä tsekkaus läytyykä localtables kaikki oikeasti tietokannasta
	// TODO: tarvittaisiin vielä tsekkaus läytyykä localcolumnssit kaikki oikeasti tietokannasta
	
	// Näissä on ainakin muutamia ongelmia tällähetkellä
	//  - system_accesskeys
	//  - system_colors
	//  - system_roles
	
	// TODO: Vastaavasti pitäisi tsekata sisältääkä tietokannasta joitakin tauluja joita ei läydy system_tablesista
	// TODO: pitäisi tsekata läytyykä tietokannasta jotain sarakkeita mitä ei läydy system_columnssista
	
	// TODO: Sisällän synkkaus epäselvä
	// TODO: Automaattiset lisäys operaatiot, linkki josta suoritetaan lokaaliin kantaan update, sekä system_columns että tarvittaessa myäs allaolevan tietokantataulun rakenne
	
?>