<?php
	
	global $width;
	$width = "600px";


	function createWikiRow($content, $template) {
		
		global $width;

		$content = str_replace("__content__", $content, $template);
		
		echo "<table style='width:" . $width .";border-collapse: collapse;border-spacing: 0;'  cellspacing=0 >";
		echo "	<tr>";
		echo "		<td>" . $content . "</td>";
		echo "		<td style='text-align:right;'><a href='#'>edit</a></td>";
		echo "	</tr>";
		echo "</table>";
	}


	echo "<h1>" . $this->registry->document->name . "</h1>";
	//createWikiRow("<h1>" . $this->registry->document->name . "</h1>");
	
	//echo "<br>content - " . $this->registry->document->name;
	
	//echo "<table>";
	foreach($this->registry->elements as $index => $element) {
		//echo "<tr>";
		//echo "	<td>";
		$template = $this->registry->templates[$element->templateID];
		createWikiRow($element->content, $template->content);
		//echo "	</td>";
		//echo "</tr>";
	}
	
	
	
	
	
	
	


	$insertsection = new UISection('Elementin lisäys','550px');
	$insertsection->setDialog(true);
	$insertsection->setMode(UIComponent::MODE_INSERT);
	$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'wiki/documents/insertelement&documentID=' . $this->registry->document->documentID);
	
	$field = new UISelectField("Template","templateID","templateID",$registry->templates, "name");
	$insertsection->addField($field);
	
	$field = new UISelectField("Parent","parentID","parentID",$registry->elements, "content");
	$insertsection->addField($field);

	$field = new UITextField("Sisältö", "content", 'content');
	$insertsection->addField($field);
	
	$insertsection->show();
	
	
	echo "<br><br>";
	echo "<table style='width:" . $width ."'>";
	echo "	<tr>";
	echo "		<td></td>";
	echo "		<td style='text-align:right;'><div id=linkki01 style='color:blue;text-decoration: underline;cursor: pointer;'>lisää</div></td>";
	echo "	</tr>";
	echo "</table>";
	
	
	echo "<script>";
	echo "  $('#linkki01').click(function () {";
	echo "		console.log('buttonaction OPENDIALOG');";
	echo "  	$('#sectiondialog-" . $insertsection->getID() . "').dialog('open');";
	echo "	});";
	echo "</script>";
	
?>