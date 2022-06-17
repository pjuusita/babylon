<?php

// tama on erikoisluokka, jota kaytetaan css tiedoston hakemiseen. parametrina annetaan id-numero, jos id-numeroa ei anneta kayteaan oletusteemaa. myahemmassa vaiheessa sessionin avulla voidaan mahdollisesti saada suoraa asiakkaalle maaritetty id, jolloin parametria ei tarvitse kutsua.


error_reporting(E_ALL);
define ('DEV', true);

header('content-type:text/css');
include '../app/init.php';
init();


// parametri id antaa kaytettavan teeman id numeron $_GET['id']

// lataa kaikki teemaan liittyvat themeitemit Themeitem::loadItems(themeid)
// lataa kaikki cssclassesit 
// lataa kaikki classdefinitionit (riittaa periaatteessa vain ne joiden cssclassid on aiemmin ladattujen joukossa


// nyt kaikki data on ladattu muistiin, generoidaan seuraavaksi itse css tiedoston sisalta

// kelataan kaikki cssclassessit
//   echota classin nimi (mahdollisesti classiin tarvitaan subitem, selector jokin muulisa valinta: ), esim: myclass:hover
//   kelataan jokaisen cssclassiin asianomaisen css-classin definitioni
//     echota kyseisen cssdefinitionin propertyname ja value
//        Jos value on asetettu, echota se
//        jos themeitemID on asetettu tulosta valuena themeitem->value
//   echota classin loppu sulku

/*echo "body { \n";
echo "	margin:0;\n";
echo "	padding:0;\n";
echo "}\n";
 

echo ".myheader {\n";
echo "	background-color:green;\n";
echo "}\n";*/

	function constructCSS($registry) {
		
		echo "@CHARSET \"UTF-8\";\n\n";
		
		foreach($registry->cssClasses as $cssClasses => $cssClass) {
			echo "".$cssClass->name." { \n";
			
			foreach($registry->cssDefinitions as $cssDefinitions => $cssDefinition) {
				if ($cssDefinition->cssclassID == $cssClass->cssclassID) {
					if ($cssDefinition->themeitemID != 0) {
						$itemvalue = $registry->themeItemValues[$cssDefinition->themeitemID];
						echo "\t" . $cssDefinition->propertyname . ":" . $itemvalue . ";\n";
					} else {
						echo "\t" . $cssDefinition->propertyname . ": " . $cssDefinition->value . ";\n";
					}
				}
			}
			echo "} \n\n";
		}
	}

	
	function loadCSS($cssfileID, $themeID) {

		$registry = new Registry();
		$registry->cssClasses = Table::load("system_cssclasses", " WHERE CssfileID='" . $cssfileID ."'");
		$registry->themes = Table::load('system_themes');
		
		
		//$registry->cssDefinitions = CssDefinitions::loadCssDefinitions($registry->cssClasses);
		//$registry->themeItems = ThemeItem::loadThemeItems();
		//$registry->themeItemValues = ThemeItem::loadItemvalues($themeID);
		
		//printDebugData($registry);
		return $registry;
	}
	
	function printDebugData($registry) {
		printCSSClasses($registry);
		printCSSDefinitions($registry);
		printThemes($registry);
		printThemeItems($registry);
	}
		
	function printCSSClasses($registry) {
		
		
		echo "\n\nCSS CLASSES \n";
		echo "----------------------------------------------------\n\n";
		
		foreach($registry->cssClasses as $CssClass => $cssclass) {
			echo "cssClassID ".$cssclass->cssclassID."\n";
			echo "name ".$cssclass->name."\n";
			echo "selector".$cssclass->selector."\n";
			echo "insertID".$cssclass->insertID."\n";
			echo "insertDate Name ".$cssclass->insertDate."\n";
			echo "removeID".$cssclass->removeID."\n";
			echo "removeDate".$cssclass->removeDate."\n\n";
		}
	}
	
	function printCSSDefinitions($registry) {
		
		echo "\n\nCSS DEFINITIONS \n";
		echo "----------------------------------------------------\n\n";
		
		foreach($registry->cssDefinitions as $CssDefinitions => $cssDefinition) {
			echo "cssDefinitionID ".$cssDefinition->cssDefinitionID."\n";
			echo "propertyName ".$cssDefinition->propertyName."\n";
			echo "cssClassID ".$cssDefinition->cssClassID."\n";
			echo "themeItemID ".$cssDefinition->themeItemID."\n";
			echo "value ".$cssDefinition->value."\n";
			echo "insertID".$cssDefinition->insertID."\n";
			echo "insertDate ".$cssDefinition->insertDate."\n";
			echo "removeID".$cssDefinition->removeID."\n";
			echo "removeDate".$cssDefinition->removeDate."\n\n";	
		}
	}
	
	function printThemes($registry) {
		
		echo "\n\nTHEMES \n";
		echo "----------------------------------------------------\n\n";
		
		foreach($registry->themes as $Themes => $themes) {
			echo "themeID ".$themes->themeID."\n";
			echo "name ".$themes->name."\n";
			echo "public ".$themes->public."\n";
			echo "ownerID ".$themes->ownerID."\n";
			echo "insertID ".$themes->insertID."\n";
			echo "insertDate ".$themes->insertDate."\n";
			echo "removeID ".$themes->removeID."\n";
			echo "removeDate ".$themes->removeDate."\n\n";
		}
	}
	
	function printThemeItems($registry) {

		echo "\n\nTHEME ITEMS \n";
		echo "----------------------------------------------------\n\n";
		
		foreach($registry->themeItems as $ThemeItem => $themeItem) {
			echo "themeItemID ".$themeItem->themeItemID."\n";
			echo "themeID ".$themeItem->themeID."\n";
			echo "itemName ".$themeItem->itemName."\n";
			echo "value ".$themeItem->value."\n";
			echo "insertID ".$themeItem->insertID."\n";
			echo "insertDate ".$themeItem->insertDate."\n";
			echo "removeID ".$themeItem->removeID."\n";
			echo "removeDate ".$themeItem->removeDate."\n";
		}
	}
	
	
	function main($cssFileID, $themeID) {
		$cssContent = loadCSS($cssFileID,$themeID);
		constructCSS($cssContent);
	}

	
	if (isset($_GET['f'])) {
		main($_GET['f'], $_GET['t']);
	} else {
		echo "<br>CSS.php cssFileID not provided";
		die('');
	}
	
?>
