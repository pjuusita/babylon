<?php

include_once 'docelement.class.php';
include_once 'docsection.class.php';
include_once 'doctext.class.php';
include_once 'docfont.class.php';
include_once 'doccolor.class.php';
include_once 'docline.class.php';
include_once 'doc.class.php';
include_once 'doctable.class.php';
include_once 'doccolumn.class.php';
include_once 'docindexvaluelist.class.php';

$elements = $this->registry->elements;
$data	  = $this->registry;

//echo count($this->registry->sumtotals);

$doc = new DOC($elements,$data);
$doc->draw();


?>