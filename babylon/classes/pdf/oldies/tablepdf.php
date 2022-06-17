<?php

$table_data = $this->registry->table_data;

$header = new PDFSection(_white,_lightgray,0.5,1,1,208,20);
$header_logo = new PDFImage("babelsoft.gif",4,2,40,10);
$header_osoite = new PDFText("Myyrmaentie 2,01600 Vantaa, Finland",4,14,60,10,"Arial","",8,_black,_white,0.25,_white);
$header_tiedot = new PDFText("Themeitem Raportti",155,2,50,10,"Arial","B",14,_black,_white,0.25,_white);
$header_paivamaara = new PDFDate(156.5,10,"Arial","",8,_black);

$header->addComponent($header_logo);
$header->addComponent($header_osoite);
$header->addComponent($header_tiedot);
$header->addComponent($header_paivamaara);

$footer = new PDFSection(_white,_lightgray,0.5,1,276,208,20);
$footer_sivunumero = new PDFPageNumber(107,15,"Arial","",10,_black);

$footer->addComponent($footer_sivunumero);

$document = new BasePDF();

$document->setPageHeader($header);
$document->setPageFooter($footer);
$document->setPageTopMargin(10);
$document->setPageBottomMargin(10);


$table_columns = "themeitemID:itemname:description";

$pdftable = new PDFTable(10,25,175,$table_columns,$table_data,$document);
	$pdftable->setComponentStyle("Arial","",18,_black,_white,_lightgray,0.25,0.5,1,"L");
	$pdftable->setHeaderStyle("Arial","B",14,_black,_white,_lightgray,0.25,0.5,1,"L");
	$pdftable->setContentStyle("Arial","",12,_black,_white,_lightgray,0.25,0.5,1,"L");

$document->addComponent($pdftable);

$left 			 = 25;
$top 			 = 150;
$tree_data  	 = $this->registry->tree_data;
$tree_raw_data	 = $this->registry->raw_tree_data;
$columns		 = "number:name:parentID:precedence";
$primary_columns = "number:name";
$depth			 = 4;

$tree = new PDFTree($left,$top,$document,$tree_data,$tree_raw_data,$columns,$primary_columns,$depth);

$parent_font_name 	= "Arial";
$parent_font_style = "B";
$parent_font_size 	= 13;

$child_font_name 	= "Arial";
$child_font_style = "";
$child_font_size 	= 12;

$tree->setParentFont($parent_font_name,$parent_font_style,$parent_font_size,_black);
$tree->setChildFont($child_font_name,$child_font_style,$child_font_size,_black);

$document->addComponent($tree);
$document->show();

?>

