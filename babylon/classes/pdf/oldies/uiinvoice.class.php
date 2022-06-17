<?php
class UIInvoice {
	private $title;
	private $invoiceID;
	private $decimals = 2;
	private $loadedrows = null;
	private $productset;
	private $vats;
	private $reserveaction;
	private $updateaction;
	private $updatetotalaction;
	private $removeaction;
	private $columns = null;
	
	// ******************************************************************************************************************
	// *** PUBLIC FUNCTION CONSTRUCTOR
	// *** Default constructor.
	// ******************************************************************************************************************
	public function __construct($title) {
		$this->title = $title;
	}
	
	// ******************************************************************************************************************
	// *** PUBLIC FUNCTION SETPRODUCTSET($productset)
	// *** Sets product data.
	// ******************************************************************************************************************
	public function setProductSet($productset) {
		$this->productset = $productset;
	}
	
	// ******************************************************************************************************************
	// *** PUBLIC FUNCTION SETVATS($vats)
	// *** Sets vats-data.
	// ******************************************************************************************************************
	public function setVats($vats) {
		$this->vats = $vats;
	}
	
	// ******************************************************************************************************************
	// *** PUBLIC FUNCTION SETROWS($rows)
	// *** Sets existing rows.
	// ******************************************************************************************************************
	public function setLoadedRows($loadedrows) {
		$this->loadedrows = $loadedrows;
	}
	
	// ******************************************************************************************************************
	// *** PUBLIC FUNCTION SETROWS($rows)
	// *** Sets existing rows.
	// ******************************************************************************************************************
	public function setInvoice($invoiceID) {
		$this->invoiceID = $invoiceID;
	}
	
	// ******************************************************************************************************************
	// *** PUBLIC FUNCTION SET UPDATEACTION()
	// *** Sets updateaction.
	// ******************************************************************************************************************
	public function setUpdateRowAction($updateaction) {
		$this->updateaction = $updateaction;
	}
	
	// ******************************************************************************************************************
	// *** PUBLIC FUNCTION SETRESERVEROWACTION()
	// *** Sets reserve row action.
	// ******************************************************************************************************************
	public function setReserveRowAction($reserveaction) {
		$this->reserveaction = $reserveaction;
	}
	
	// ******************************************************************************************************************
	// *** PUBLIC FUNCTION SETREMOVEACTION()
	// *** Sets reserve row action.
	// ******************************************************************************************************************
	public function setRemoveRowAction($removeaction) {
		$this->removeaction = $removeaction;
	}
	
	// ******************************************************************************************************************
	// *** PUBLIC FUNCTION SETUPDATETOTALACTION()
	// *** Sets updateaction.
	// ******************************************************************************************************************
	public function setUpdateTotalAction($updatetotalaction) {
		$this->updatetotalaction = $updatetotalaction;
	}
	
	// ******************************************************************************************************************
	// *** PUBLIC FUNCTION SETDECIMALS()
	// *** Sets reserve row action.
	// ******************************************************************************************************************
	public function setDecimals($decimals) {
		$this->decimals = $decimals;
	}
	
	// ******************************************************************************************************************
	// *** PRIVATE FUNCTION CREATEINVOICE()
	// *** Root function of invoice creation.
	// ******************************************************************************************************************
	private function createInvoice() {
		echo "<div id='invoice'>";
		echo "</div>";
	}
	
	// ****************************************************************************************************************************************
	// *** PRIVATE FUNCTION CREATESCRIPTS()
	// *** Creates scripts.
	// ****************************************************************************************************************************************
	private function createScripts() {
		
		// ****************************************************************************************************************************************
		// *** FUNCTION UPDATEROWVALUESONSELECT(select)
		// *** Root-function when row values are modified by selecting a new product from dropdown menu.
		// ****************************************************************************************************************************************
		echo "<script>																														";
		echo "																																";
		echo "	function updateRowValuesOnSelect(select)	{																				";
		echo "																																";
		echo "		var row = select.parentNode.parentNode;																					";
		echo "		updateFields(row,select);																								";
		echo "		updatePrices(row);																										";
		echo "																																";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION UPDATEPRICES(row)
		// *** Root-function for price updating.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function updatePrices(row)	{																								";
		echo "																																";
		echo "		countVATlessUnitPrice(row);																								";
		echo "		countRowTotal(row);																										";
		echo "		countTotals();																											";
		echo "		updateJSRow(row);																											";
		echo "		updateTotals();																											";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION UPDATEFIELDS(updaterowID,select)
		// *** Updates fields by fetching member of Product-class with productID associated with Select-element.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function updateFields(row,select)	{																						";
		echo "																																";
		echo "		var productID = select.value;																							";
		echo "		var product   = findWithProductID(productID);																			";
		echo "																																";
		echo "		var rowindex  = row.rowIndex;																							";
		echo "		var table	  = row.parentNode;																							";
		echo "																																";
		echo "		table.rows[rowindex].cells.item(productnumber_).childNodes[0].value 	= product.productnumber;						";
		echo "		table.rows[rowindex].cells.item(vat_).childNodes[0].value 			 	= product.vat;									";
		echo "		table.rows[rowindex].cells.item(unitpricevat_).childNodes[0].value   	= product.unitprice;							";
		echo "		table.rows[rowindex].cells.item(description_).childNodes[0].value 	 	= product.description;							";
		echo "																																";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION FINDWITHPRODUCTID(productID)
		// *** Searches product with productID from global products array.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function findWithProductID(productID) {																						";
		echo "																																";
		echo "		for(i = 0; i < products.length; i++) {																					";
		echo "																																";
		echo "			var product = products[i];																							";
		echo "																																";
		echo "			if (product.productID==productID) return product;																	";
		echo "																																";
		echo "		}																														";
		echo "																																";
		echo "		return null;																											";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEPRODUCSARRAY()
		// *** Creates a global array containing members of Product-class from productset loaded by controller.
		// ****************************************************************************************************************************************
		
		$products = $this->productset;
		
		echo "<script>																														";
		echo "																																";
		echo "	function createProductsArray()	{																							";
		echo "																																";
		echo "		var products = [];																										";
		echo "		var item;																												";
		echo "																																";
		
		foreach ( $products as $index => $product ) {
			
			$productID = $product->productID;
			$productnumber = $product->productnumber;
			$productname = $product->productname;
			$productvat = $product->vat;
			$productunitprice = $product->unitprice;
			$description = $product->description;
			
			echo "																																";
			echo "			item = new Product(" . $productID . "," . $productnumber . ",'" . $productname . "'," . $productvat . "," . $productunitprice . ",'" . $description . "');";
			echo "			products.push(item);																								";
			echo "																																";
		}
		
		echo "																																";
		echo "		return products;																										";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEVATSARRAY()
		// *** Creates a global array containing members of Vat-class from vats loaded by controller.
		// ****************************************************************************************************************************************
		
		$vats = $this->vats;
		
		echo "<script>																														";
		echo "																																";
		echo "	function createVatArray()	{																								";
		echo "																																";
		echo "		var tempvats = [];																										";
		echo "		var item;																												";
		echo "																																";
		
		foreach ( $vats as $index => $vat ) {
			
			$vatID = $vat->vatID;
			$name = $vat->name;
			$startdate = $vat->startdate;
			$enddate = $vat->enddate;
			$type = $vat->type;
			$active = $vat->active;
			$percent = $vat->percent;
			
			echo "																															";
			echo "			item = new Vat(" . $vatID . ",'" . $name . "','" . $startdate . "','" . $enddate . "'," . $type . "," . $active . "," . $percent . ");		";
			echo "			tempvats.push(item);																							";
			echo "																															";
		}
		
		echo "																																";
		echo "		return tempvats;																										";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEROWSARRAY()
		// *** Creates a global array containing members of Product-class from productset loaded by controller.
		// ****************************************************************************************************************************************
		
		$loadedrows = $this->loadedrows;
		$productset = $this->productset;
		
		echo "<script>																														";
		echo "																																";
		echo "	function createRowArray()	{																								";
		echo "																																";
		echo "		var loadedrows = [];																									";
		echo "		var loadedrow;																														";
		
		foreach ( $loadedrows as $index => $row ) {
			
			$invoicerowID = $row->invoicerowID;
			$invoiceID = $row->invoiceID;
			$productnumber = $row->productnumber;
			$productID = $row->productID;
			$amount = $row->amount;
			$vat = $row->vatpercent;
			$unitprice = $row->unitpricewithoutvat;
			$unitpricevat = $row->unitpricewithvat;
			$total = $row->totalwithoutvat;
			$totalvat = $row->totalpricewithvat;
			$description = $productset [$productID]->description;
			
			echo "																															";
			echo "			loadedrow = new InvoiceRow(" . $invoicerowID . "," . $invoiceID . "," . $productnumber . "," . $productID . "," . $amount . "," . $vat . "," . $unitprice . "," . $unitpricevat . "," . $total . "," . $totalvat . ",'" . $description . "');";
			echo "			loadedrows.push(loadedrow);																						";
			echo "																															";
		}
		
		echo "																																";
		echo "		return loadedrows;																										";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION INVOICEROW(invoicerowID,invoiceID,productnumber,productid,amount,vat,unitprice,unitpricevat,total,totalvat)
		// *** Constructor for Product Javascript-class.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function InvoiceRow(invoicerowID,invoiceID,productnumber,productID,amount,vat,unitprice,unitpricevat,total,totalvat,description) {		";
		echo "																																";
		echo "		this.invoicerowID	= invoicerowID;																						";
		echo "		this.invoiceID		= invoiceID;																						";
		echo "		this.productnumber 	= productnumber;																					";
		echo "		this.productID		= productID;																						";
		echo "		this.quantity		= amount;																							";
		echo "		this.vat			= vat;																								";
		echo "		this.unitprice 		= unitprice;																						";
		echo "		this.unitpricevat 	= unitpricevat;																						";
		echo "		this.total			= total;																							";
		echo "		this.totalvat		= totalvat;																							";
		echo "		this.description	= description;																						";
		echo "																																";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION PRODUCT(productID,productnumber,productname,vat,unitprice)
		// *** Constructor for Product Javascript-class.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function Product(productID,productnumber,productname,vat,unitprice,description)	{											";
		echo "																																";
		echo "		this.productID 		= productID;																						";
		echo "		this.productnumber 	= productnumber;																					";
		echo "		this.name 			= productname;																						";
		echo "		this.vat			= vat;																								";
		echo "		this.unitprice 		= unitprice;																						";
		echo "		this.description	= description;																						";
		echo "																																";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION Vat(vatID,name,startdate,enddate,type,active,percent)
		// *** Constructor for Vat Javascript-class.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function Vat(vatID,name,startdate,enddate,type,active,percent)	{															";
		echo "																																";
		echo "		this.vatID 		= vatID;																								";
		echo "		this.name 		= name;																									";
		echo "		this.startdate 	= startdate;																							";
		echo "		this.enddate	= enddate;																								";
		echo "		this.type 		= type;																									";
		echo "		this.active		= active;																								";
		echo "		this.percent	= percent;																								";
		echo "																																";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATETOTALTABLE(invoiceID)
		// *** Creates table for price totals
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createTotalTable() {																								";
		echo "																																";
		echo "		var invoice	  		 	= document.getElementById('invoice');															";
		echo "																																";
		echo "		var totaltable 		 	= document.createElement('table');																";
		echo "		totaltable.className 	= 'listtable';																					";
		echo "		totaltable.style 	 	= 'float:left';																					";
		echo "																																";
		echo "		totaltable.id 		 	= 'totaltable';																					";
		echo "																																";
		echo "		var thvat 			 	=	document.createElement('th');																";
		echo "		thvat.innerHTML 	 	= 'Kanta';																						";
		echo "		totaltable.appendChild(thvat);																							";
		echo "																																";
		echo "		var thsumvatless 	 	=	document.createElement('th');																";
		echo "		thsumvatless.innerHTML 	= 'Veroton';																					";
		echo "		totaltable.appendChild(thsumvatless);																					";
		echo "																																";
		echo "		var thsumvat	 		=	document.createElement('th');																";
		echo "		thsumvat.innerHTML 	 	= 'ALV';																						";
		echo "		totaltable.appendChild(thsumvat);																						";
		echo "																																";
		echo "		var thtotal		 		=	document.createElement('th');																";
		echo "		thtotal.innerHTML 	 	= 'Yht.';																						";
		echo "		totaltable.appendChild(thtotal);																						";
		echo "																																";
		echo "		var row;																												";
		echo "		var cell;																												";
		echo "		var length = loadedvats.length;																							";
		echo "																																";
		echo "		for(n = 0; n<length;n++) {																								";
		echo "																																";
		echo "			row = totaltable.insertRow();																						";
		echo "			cell = row.insertCell(0);																							";
		echo "			cell.innerHTML = (loadedvats[n].percent * 100.00).toFixed(decimals) + '%';											";
		echo "			cell.id		   = loadedvats[n].percent;																				";
		echo "			cell = row.insertCell(1);																							";
		echo "			cell.innerHTML = 0;																									";
		echo "			cell = row.insertCell(2);																							";
		echo "			cell.innerHTML = 0;																									";
		echo "			cell = row.insertCell(3);																							";
		echo "			cell.innerHTML = 0;																									";
		echo "																																";
		echo "		}																														";
		echo "																																";
		echo "		row = totaltable.insertRow();																							";
		echo "																																";
		echo "		cell = row.insertCell();																								";
		echo "		cell.style.borderStyle = 'solid';																						";
		echo "		cell.style.borderColor = 'black';																						";
		echo "		cell.style.borderWidth = '1px 0px 0px 0px';																				";
		echo "																																";
		echo "		cell = row.insertCell();																								";
		echo "		cell.style.borderStyle = 'solid';																						";
		echo "		cell.style.borderColor = 'black';																						";
		echo "		cell.style.borderWidth = '1px 0px 0px 0px';																				";
		echo "																																";
		echo "		cell = row.insertCell();																								";
		echo "		cell.style.borderStyle = 'solid';																						";
		echo "		cell.style.borderColor = 'black';																						";
		echo "		cell.style.borderWidth = '1px 0px 0px 0px';																				";
		echo "		cell.innerHTML = 0;																										";
		echo "																																";
		echo "		cell = row.insertCell();																								";
		echo "		cell.style.borderStyle = 'solid';																						";
		echo "		cell.style.borderColor = 'black';																						";
		echo "		cell.style.borderWidth = '1px 0px 0px 0px';																				";
		echo "		cell.innerHTML = 0;																										";
		echo "																																";
		echo "		invoice.appendChild(totaltable);																						";
		echo "																																";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATETABLEHEADER()
		// *** Creates tableheader for Invoice-component.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createTableHeader() {																								";
		echo "																																";
		echo "		var invoice	  	= document.getElementById('invoice');																	";
		echo "		var table 	  	= document.createElement('table');																		";
		echo "																																";
		echo "		table.className = 'listtable';																							";
		echo "		table.id		= 'invoicetable';																						";
		echo "																																";
		echo "		var th0	    	= document.createElement('th'); 																		";
		echo "		th0.innerHTML   = '<br>Tuotenumero'; 																					";
		echo "		table.appendChild(th0);																									";
		echo "																																";
		echo "		var th1	  	    = document.createElement('th'); 																		";
		echo "		th1.innerHTML   = '<br>Nimi'; 																							";
		echo "		table.appendChild(th1);																									";
		echo "																																";
		echo "		var th2	 	    = document.createElement('th'); 																		";
		echo "		th2.innerHTML   = '<br>LKM'; 																							";
		echo "		table.appendChild(th2);																									";
		echo "																																";
		echo "		var th3	  	    = document.createElement('th'); 																		";
		echo "		th3.innerHTML   = '<br>Tuotekuvaus'; 																					";
		echo "		table.appendChild(th3);																									";
		echo "																																";
		echo "		var th4	 	    = document.createElement('th'); 																		";
		echo "		th4.innerHTML   = 'Yksikkähinta<br>Veroton';	 																		";
		echo "		table.appendChild(th4);																									";
		echo "																																";
		echo "		var th5	  	    = document.createElement('th'); 																		";
		echo "		th5.innerHTML   = '<br>Verollinen'; 																					";
		echo "		table.appendChild(th5);																									";
		echo "																																";
		echo "		var th6	  	    = document.createElement('th'); 																		";
		echo "		th6.innerHTML   = 'Arvonlisävero<br>Kanta'; 																			";
		echo "		table.appendChild(th6);																									";
		echo "																																";
		echo "		var th7         = document.createElement('th'); 																		";
		echo "		th7.innerHTML   = '<br>alv total'; 																						";
		echo "		table.appendChild(th7);																									";
		echo "																																";
		echo "		var th8         = document.createElement('th'); 																		";
		echo "		th8.innerHTML   = 'Veloitetaan<br>Veroton'; 																			";
		echo "		table.appendChild(th8);																									";
		echo "																																";
		echo "		var th9	  	    = document.createElement('th'); 																		";
		echo "		th9.innerHTML   = '<br>Verollinen'; 																					";
		echo "		table.appendChild(th9);																									";
		echo "																																";
		echo "		var th10	  	= document.createElement('th'); 																		";
		echo "		th10.innerHTML   = '+-'; 																								";
		echo "		table.appendChild(th10);																								";
		echo "																																";
		echo "		invoice.appendChild(table);																								";
		echo "																																";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEEXISTINGROWS(loadedrows)
		// *** Creates row after row associated with rowID. Increases masterrowID by one to avoid multiple equal row IDs.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createExistingRows(loadedrows) {																					";
		echo "																																";
		echo "		loadedrows.reverse();																									";
		echo "																																";
		echo "		for(n=0;n<loadedrows.length;n++) {																						";
		echo "																																";
		echo "			createExistingRow(loadedrows[n]);																					";
		echo "																																";
		echo "		}																														";
		echo "																																";
		echo " }																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEEXISTINGROW(row)
		// *** Creates row after row associated with rowID. Increases masterrowID by one to avoid multiple equal row IDs.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createExistingRow(existingrow) {																					";
		echo "																																";
		echo "		var table 			= document.getElementById('invoicetable');															";
		echo "		var insertIndex		= 0;																								";
		echo "																																";
		echo "																																";
		echo "		var row = table.insertRow(insertIndex);																					";
		echo "		row.id  = existingrow.invoicerowID;																						";
		echo "																																";
		echo "		var cell0 = row.insertCell(productnumber_);																				";
		echo "		cell0.appendChild(createProductNumberField());																			";
		echo "		cell0.childNodes[0].value = existingrow.productnumber;																	";
		echo "																																";
		echo "		var cell1 = row.insertCell(productname_);																				";
		echo "		cell1.appendChild(createProductNameField());																			";
		echo "		cell1.childNodes[0].value = existingrow.productID;																		";
		echo "																																";
		echo "		var cell2 = row.insertCell(quantity_);																					";
		echo "		cell2.appendChild(createQuantityField());																				";
		echo "		cell2.childNodes[0].value = existingrow.quantity;																		";
		echo "																																";
		echo "		var cell3 = row.insertCell(description_);																				";
		echo "		cell3.appendChild(createProductDescriptionField());																		";
		echo "		cell3.childNodes[0].value = existingrow.description;																	";
		echo "																																";
		echo "		var cell4 = row.insertCell(unitpricevatless_);																			";
		echo "		cell4.appendChild(createUnitPriceVatlessField());																		";
		echo "		cell4.childNodes[0].value = existingrow.unitprice;																		";
		echo "																																";
		echo "		var cell5 = row.insertCell(unitpricevat_);																				";
		echo "		cell5.appendChild(createUnitPriceVatField());																			";
		echo "		cell5.childNodes[0].value = existingrow.unitpricevat;																	";
		echo "																																";
		echo "		var cell6 = row.insertCell(vat_);																						";
		echo "		cell6.appendChild(createVatField());																					";
		echo "		cell6.childNodes[0].value = existingrow.vat;																			";
		echo "																																";
		echo "		var cell7 = row.insertCell(rowsharevat_);																				";
		echo "		cell7.appendChild(createVatShareField());																				";
		echo "		cell7.childNodes[0].value = (existingrow.unitpricevat - existingrow.unitprice).toFixed(decimals);						";
		echo "																																";
		echo "		var cell8 = row.insertCell(rowtotalvatless_);																			";
		echo "		cell8.appendChild(createTotalVatlessField());																			";
		echo "		cell8.childNodes[0].value = existingrow.total;																			";
		echo "																																";
		echo "		var cell9 = row.insertCell(rowtotalvat_);																				";
		echo "		cell9.appendChild(createTotalVatField());																				";
		echo "		cell9.childNodes[0].value = existingrow.totalvat;																		";
		echo "																																";
		echo "		var cell10 = row.insertCell(buttons_);																					";
		echo "		cell10.appendChild(createRemoveButton());	 																			";
		echo "		cell10.appendChild(createAddButton());																					";
		echo "																																";
		echo "		row.addEventListener('blur',function(event) { onBlurEvent(event); },true);												";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEROW(invoiceID,rowID)
		// *** Creates row after row associated with rowID. Increases masterrowID by one to avoid multiple equal row IDs.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createRow(spawnrowindex) {																							";
		echo "																																";
		echo "		var table = document.getElementById('invoicetable');																	";
		echo "		var insertIndex;																										";
		echo "																																";
		echo "		if (table.rows.length>0) {																								";
		echo "																																";
		echo "		 	insertIndex = spawnrowindex+1;																						";
		echo "																																";
		echo "		} else {																												";
		echo "																																";
		echo "			insertIndex = 0;																									";
		echo "		}																														";
		echo "																																";
		echo "		var row = table.insertRow(insertIndex);																					";
		echo "																																";
		echo "		var cell0 = row.insertCell(productnumber_);																				";
		echo "		cell0.appendChild(createProductNumberField());																			";
		echo "																																";
		echo "		var cell1 = row.insertCell(productname_);																				";
		echo "		cell1.appendChild(createProductNameField());																			";
		echo "																																";
		echo "		var cell2 = row.insertCell(quantity_);																					";
		echo "		cell2.appendChild(createQuantityField());																				";
		echo "																																";
		echo "		var cell3 = row.insertCell(description_);																				";
		echo "		cell3.appendChild(createProductDescriptionField());																		";
		echo "																																";
		echo "		var cell4 = row.insertCell(unitpricevatless_);																			";
		echo "		cell4.appendChild(createUnitPriceVatlessField());																		";
		echo "																																";
		echo "		var cell5 = row.insertCell(unitpricevat_);																				";
		echo "		cell5.appendChild(createUnitPriceVatField());																			";
		echo "																																";
		echo "		var cell6 = row.insertCell(vat_);																						";
		echo "		cell6.appendChild(createVatField());																					";
		echo "																																";
		echo "		var cell7 = row.insertCell(rowsharevat_);																				";
		echo "		cell7.appendChild(createVatShareField());																				";
		echo "																																";
		echo "		var cell8 = row.insertCell(rowtotalvatless_);																			";
		echo "		cell8.appendChild(createTotalVatlessField());																			";
		echo "																																";
		echo "		var cell9 = row.insertCell(rowtotalvat_);																				";
		echo "		cell9.appendChild(createTotalVatField());																				";
		echo "																																";
		echo "		var cell10 = row.insertCell(buttons_);																					";
		echo "		cell10.appendChild(createRemoveButton());	 																			";
		echo "		cell10.appendChild(createAddButton());																					";
		echo "																																";
		echo "		row.addEventListener('blur',function(event) { onBlurEvent(event); },true);												";
		echo "																																";
		echo "		return row;																												";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION ONBLUEREVENT(event);
		// ***
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function onBlurEvent(event) {																								";
		echo "																																";
		echo "		var row	  	  = event.target.parentNode.parentNode;																		";
		echo "		updatePrices(row);																										";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION RESERVEROW();
		// *** Reserves a row from database.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function reserveRow(row) {																									";
		echo "																																";
		echo "	var parameters = 'sortorder='+row.rowIndex+'&id='+invoiceID;																";
		echo "																																";
		echo "	$.getJSON(reserveaction, parameters, function() {																			";
		echo "	})																															";
		echo " 	.done(function(reply) {																										";
		echo "		row.id = reply[0].insertID;																								";
		echo "	});																															";
		echo "																																";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION REMOVEROW(row)
		// *** Removes row.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function removeRow(row) {																									";
		echo "																																";
		echo "		var table 		= document.getElementById('invoicetable');																";
		echo "																																";
		echo "		var rowID		= row.id;																								";
		echo "		var rowindex	= row.rowIndex;																							";
		echo "		var parameters  = '&id='+rowID;																							";
		echo "																																";
		echo "																																";
		echo "		$.getJSON(removeaction, parameters, function() {																		";
		echo "		})																														";
		echo " 		.done(function(reply) {																									";
		echo "																																";
		echo "		});																														";
		echo "																																";
		
		//
		// Viimeisen rivin tuhoaminen tuhoaa laskun?
		//
		
		echo "		if (table.rows.length>1) { 																								";
		echo "			table.deleteRow(rowindex);																							";
		echo "		}																														";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION UPDATEJSROW(row);
		// *** Updates row to database.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function updateJSRow(row) {																									";
		echo "																				 												";
		echo "		if (row.id==null) { 													 												";
		echo "																																";
		echo "			alert('Row has no id');																								";
		echo "			return;																												";
		echo "																																";
		echo "		}																														";
		echo "																						 										";
		echo "		var parameters  = parseSaveParameters(row);																				";
		echo "																																";
		echo "		$.getJSON(updateaction,parameters, function() {																			";
		echo "																																";
		echo "		})																														";
		echo " 		.done(function(reply) {																									";
		echo "																																";
		echo "		});																														";
		echo "																																";
		echo "																																";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION UPDATETOTALS();
		// *** Updates row to database.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function updateTotals() {																									";
		echo "																				 												";
		echo "																				 												";
		echo "		var totaltable = document.getElementById('totaltable');																	";
		echo "		var	rowcount   = totaltable.rows.length - 1;																			";
		echo "																				 												";
		echo "		for(index=0;index<rowcount;index++) {																					";
		echo "																																";
		echo "			var row 		= totaltable.rows[index];																			";
		echo "			var parameters  = parseTotalSaveParameters(row);																	";
		echo "																				 												";
		echo "			alert(updatetotalaction+parameters);																				";
		echo "																																";
		echo "			$.getJSON(updatetotalaction,parameters, function() {																";
		echo "																																";
		echo "			})																													";
		echo " 			.done(function(reply) {																								";
		echo "																																";
		echo "			});																													";
		echo "		}																														";
		echo "																																";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEPRODUCTNUMBERFIELD()
		// *** Creates productnumber field.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createProductNumberField() {																						";
		echo "																																";
		echo "		var input 	= document.createElement('input');																			";
		echo "																																";
		echo "		input.size	= 8;																										";
		echo "																																";
		echo "		return input;																											";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEPRODUCTNAMEFIELD()
		// *** Creates dropdown-menu selection of product names from products array.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createProductNameField() {																							";
		echo "																																";
		echo "		var select   	 = document.createElement('select');																	";
		echo "																																";
		echo "		select.onchange  = function(event) { updateRowValuesOnSelect(event.target) };											";
		echo "																																";
		echo "		var nothing 	 = document.createElement('option');																	";
		echo "		nothing.text 	 = 'ei valittu';																						";
		echo "		nothing.value    = '';																									";
		echo "		nothing.selected = true;																								";
		echo "																																";
		echo "		select.appendChild(nothing);																							";
		echo "																																";
		echo "		for(i = 0;i<products.length;i++) {																						";
		echo "																																";
		echo "			var option = document.createElement('option');																		";
		echo "			option.value = products[i].productID;																				";
		echo "			option.text	 = products[i].name;																					";
		echo "			select.appendChild(option);																							";
		echo "		}																														";
		echo "																																";
		echo "		return select;																											";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEQUANTITYFIELD()
		// *** Creates quantity field.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createQuantityField() {																							";
		echo "																																";
		echo "		var input 	= document.createElement('input');																			";
		echo "																																";
		echo "		input.type	= 'number';																									";
		echo "		input.min	= 0;																										";
		echo "		input.value = 0;																										";
		echo "		input.style = 'width:75px';																								";
		echo "																																";
		echo "		input.onchange   = function(event) { var row = event.target.parentNode.parentNode; updatePrices(row) };					";
		echo "																																";
		echo "		return input;																											";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEVATFIELDS()
		// *** Creates field for VAT with invoiceID and rowID.
		// ****************************************************************************************************************************************
		
		$vats = $this->vats;
		
		echo "<script>																														";
		echo "																																";
		echo "	function createVatField() {																									";
		echo "																																";
		echo "		var select     = document.createElement('select');																		";
		echo "																																";
		echo "		select.onchange = function(event) { var row = event.target.parentNode.parentNode; updatePrices(row) };					";
		echo "																																";
		echo "		for(i=0;i<loadedvats.length;i++) {																						";
		echo "																																";
		echo "			var vat   = document.createElement('option');																		";
		echo "			vat.value = loadedvats[i].percent;																					";
		echo "			vat.text  = (loadedvats[i].percent * 100.00).toFixed(decimals) + '%';												";
		echo "			select.appendChild(vat);																							";
		echo "		}																														";
		echo "																																";
		echo "		return select;																											";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEVATSHAREFIELD()
		// *** Creates field for vat share of vat-price.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createVatShareField() {																					";
		echo "																																";
		echo "		var input 	= document.createElement('input');																			";
		echo "																																";
		echo "		input.size	= 8;																										";
		echo "																																";
		echo "		return input;																											";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEUNITPRICEVATLESSFIELD()
		// *** Creates field for vatless unitprice.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createUnitPriceVatlessField() {																					";
		echo "																																";
		echo "		var input 	= document.createElement('input');																			";
		echo "																																";
		echo "		input.size	= 8;																										";
		echo "																																";
		echo "		return input;																											";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEUNITPRICEVATFIELD()
		// *** Creates field for unitprice with VAT.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createUnitPriceVatField() {																						";
		echo "																																";
		echo "		var input 	= document.createElement('input');																			";
		echo "																																";
		echo "		input.size	= 8;																										";
		echo "																																";
		echo "		return input;																											";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATETOTALVATLESSFIELD(invoiceID,rowID)
		// *** Creates field for total sum of VATless prices.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createTotalVatlessField() {																						";
		echo "																																";
		echo "		var input 	= document.createElement('input');																			";
		echo "																																";
		echo "		input.size	= 8;																										";
		echo "																																";
		echo "		return input;																											";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATETOTALVATFIELD(invoiceID,rowID)
		// *** Creates field for total sum of VAT prices.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createTotalVatField() {																							";
		echo "																																";
		echo "		var input 	= document.createElement('input');																			";
		echo "																																";
		echo "		input.size	= 8;																										";
		echo "																																";
		echo "		return input;																											";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATETOTALVATFIELD(invoiceID,rowID)
		// *** Creates field for total sum of VAT prices.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createProductDescriptionField() {																					";
		echo "																																";
		echo "		var input 	= document.createElement('input');																			";
		echo "																																";
		echo "		input.size	= 8;																										";
		echo "																																";
		echo "		return input;																											";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEADDBUTTON(invoiceID,rowID)
		// *** Creates addrow-button for table row.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createAddButton() {																								";
		echo "																																";
		echo "		var addButton = document.createElement('span');																			";
		echo "		addButton.className = 'buttonstyle';																					";
		echo "		addButton.innerHTML	= '+';																								";
		echo "																																";
		echo "		addButton.onclick = function(event) { 																					";
		echo "																																";
		echo "			var spawnafter = event.target.parentNode.parentNode.rowIndex;														";
		echo "			var row = createRow(spawnafter);																					";
		echo "			reserveRow(row);																									";
		echo "																																";
		echo " 		};																														";
		echo "																																";
		echo "		return addButton;																										";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION CREATEREMOVEBUTTON()
		// *** Creates remove-button for table row.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function createRemoveButton() {																								";
		echo "																																";
		echo "		var removeButton = document.createElement('span');																		";
		echo "																																";
		echo "		removeButton.className = 'buttonstyle';																					";
		echo "		removeButton.innerHTML	= '-';																							";
		echo "																																";
		echo "		removeButton.onclick = function(event) {																				";
		echo "																																";
		echo "				var row = event.target.parentNode.parentNode;																	";
		echo " 				removeRow(row);																									";
		echo "																																";
		echo " 		};																														";
		echo "																																";
		echo "		return removeButton;																									";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION COUNTTOTALS()
		// *** Counts and sets total prices.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function countTotals() {																									";
		echo "																																";
		echo "		var totaltableID = 'totaltable';																						";
		echo "		var totaltable 	 = document.getElementById(totaltableID);																";
		echo "		var rowcount	 = totaltable.rows.length-1;																			";
		echo "																																";
		echo "		for(n=0;n<rowcount;n++) {																								";
		echo "																																";
		echo "			totaltable.rows[n].cells.item(1).innerHTML = countRowTotalWithoutVat(loadedvats[n].percent).toFixed(decimals);		";
		echo "			totaltable.rows[n].cells.item(2).innerHTML = countRowTotalWithVatShare(loadedvats[n].percent).toFixed(decimals); 	";
		echo "			totaltable.rows[n].cells.item(3).innerHTML = countRowTotalSum(loadedvats[n].percent).toFixed(decimals);				";
		echo "																																";
		echo "		}																														";
		echo "																																";
		echo "		totaltable.rows[rowcount].cells.item(1).innerHTML = parseFloat(countInvoiceTotalWithoutVat().toFixed(decimals)).toFixed(decimals);	  ";
		echo "		totaltable.rows[rowcount].cells.item(2).innerHTML = parseFloat(countInvoiceTotalWithVat().toFixed(decimals)).toFixed(decimals);	  ";
		echo "		totaltable.rows[rowcount].cells.item(3).innerHTML = parseFloat(countInvoiceTotal().toFixed(decimals)).toFixed(decimals);		      ";
		echo "																																";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION COUNTINVOICETOTALWITHOUTVAT()
		// *** Counts and sets total prices.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function countRowTotalSum(vat) {																							";
		echo "																																";
		echo "		var invoicetableID 	 = 'invoicetable';																					";
		echo "		var invoicetable 	 = document.getElementById(invoicetableID);															";
		echo "		var rowcount	 	 = invoicetable.rows.length;																		";
		echo "		var sum				 = 0;																								";
		echo "		var add				 = 0;																								";
		echo "																																";
		echo "		for(i=0;i<rowcount;i++) {																								";
		echo "																																";
		echo "			var rowvat   = invoicetable.rows[i].cells.item(vat_).childNodes[0].value;											";
		echo "				add 	 = invoicetable.rows[i].cells.item(rowtotalvat_).childNodes[0].value;									";
		echo "																																";
		echo "			if ((add!='') && (rowvat==vat))	{																					";
		echo "																																";
		echo "				sum = sum + parseFloat(add);																					";
		echo "			}																													";
		echo "		}																														";
		echo "																																";
		echo "		return sum;																												";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION COUNTINVOICETOTALWITHVAT()
		// *** Counts and sets total prices.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function countRowTotalWithVatShare(vat) {																					";
		echo "																																";
		echo "		var invoicetableID 	 = 'invoicetable';																					";
		echo "		var invoicetable 	 = document.getElementById(invoicetableID);															";
		echo "		var rowcount	 	 = invoicetable.rows.length;																		";
		echo "		var sum				 = 0;																								";
		echo "		var add				 = 0;																								";
		echo "																																";
		echo "		for(i=0;i<rowcount;i++) {																								";
		echo "																																";
		echo "			var rowvat   	 	= invoicetable.rows[i].cells.item(vat_).childNodes[0].value;									";
		echo "				vatshare	 	= invoicetable.rows[i].cells.item(rowsharevat_).childNodes[0].value;							";
		echo "																																";
		echo "			if ((vatshare!='') && (rowvat==vat)){																				";
		echo "				sum = sum + parseFloat(vatshare);																				";
		echo "			}																													";
		echo "		}																														";
		echo "																																";
		echo "		return sum;																												";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION COUNTINVOICETOTALWITHOUTVAT()
		// *** Counts and sets total prices.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function countRowTotalWithoutVat(vat) {																						";
		echo "																																";
		echo "		var invoicetableID 	 = 'invoicetable';																					";
		echo "		var invoicetable 	 = document.getElementById(invoicetableID);															";
		echo "		var rowcount	 	 = invoicetable.rows.length;																		";
		echo "		var sum				 = 0;																								";
		echo "		var add				 = 0;																								";
		echo "																																";
		echo "		for(i=0;i<rowcount;i++) {																								";
		echo "																																";
		echo "			var rowvat = invoicetable.rows[i].cells.item(vat_).childNodes[0].value;												";
		echo "			add = invoicetable.rows[i].cells.item(rowtotalvatless_).childNodes[0].value;										";
		echo "																																";
		echo "			if ((add!='') && (rowvat==vat)){																					";
		echo "				sum = sum + parseFloat(add);																					";
		echo "			}																													";
		echo "		}																														";
		echo "																																";
		echo "		return sum;																												";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION COUNTINVOICETOTALWITHOUTVAT()
		// *** Counts and sets total prices.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function countInvoiceTotal() {																								";
		echo "																																";
		echo "		var invoicetableID 	 = 'invoicetable';																					";
		echo "		var invoicetable 	 = document.getElementById(invoicetableID);															";
		echo "		var rowcount	 	 = invoicetable.rows.length;																		";
		echo "		var sum				 = 0;																								";
		echo "		var add				 = 0;																								";
		echo "																																";
		echo "		for(n=0;n<rowcount;n++) {																								";
		echo "																																";
		echo "				add = invoicetable.rows[n].cells.item(rowtotalvat_).childNodes[0].value;										";
		echo "																																";
		echo "			if (add!='') {																										";
		echo "																																";
		echo "				sum = sum + parseFloat(add);																					";
		echo "			}																													";
		echo "		}																														";
		echo "																																";
		echo "		return sum;																												";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION COUNTINVOICETOTALWITHOUTVAT()
		// *** Counts and sets total prices.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function countInvoiceTotalWithoutVat() {																					";
		echo "																																";
		echo "		var invoicetableID 	 = 'invoicetable';																					";
		echo "		var invoicetable 	 = document.getElementById(invoicetableID);															";
		echo "		var rowcount	 	 = invoicetable.rows.length;																		";
		echo "		var sum				 = 0;																								";
		echo "		var add				 = 0;																								";
		echo "																																";
		echo "		for(n=0;n<rowcount;n++) {																								";
		echo "																																";
		echo "				add = invoicetable.rows[n].cells.item(rowtotalvatless_).childNodes[0].value;									";
		echo "																																";
		echo "			if (add!='') {																										";
		echo "																																";
		echo "				sum = sum + parseFloat(add);																					";
		echo "			}																													";
		echo "		}																														";
		echo "																																";
		echo "		return sum;																												";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION COUNTINVOICETOTALWITHOUTVAT()
		// *** Counts and sets total prices.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function countInvoiceTotalWithVat() {																						";
		echo "																																";
		echo "		var invoicetableID 	 = 'invoicetable';																					";
		echo "		var invoicetable 	 = document.getElementById(invoicetableID);															";
		echo "		var rowcount	 	 = invoicetable.rows.length;																		";
		echo "		var sum				 = 0;																								";
		echo "		var add				 = 0;																								";
		echo "																																";
		echo "		for(n=0;n<rowcount;n++) {																								";
		echo "																																";
		echo "				vat = invoicetable.rows[n].cells.item(rowtotalvat_).childNodes[0].value;										";
		echo "				vatless = invoicetable.rows[n].cells.item(rowtotalvatless_).childNodes[0].value;								";
		echo "																																";
		echo "			if ((vat!='') && (vatless!='')) {																					";
		echo "																																";
		echo "				sum = sum + (parseFloat(vat) - parseFloat(vatless));															";
		echo "			}																													";
		echo "		}																														";
		echo "																																";
		echo "		return sum;																												";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION COUNTVATLESSUNITPRICE(row)
		// *** Counts VATless unitprice and sets field value.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function countVATlessUnitPrice(row) {																						";
		echo "																																";
		echo "		var vatprice 	   = row.cells[unitpricevat_].childNodes[0].value;														";
		echo "		var vat      	   = parseFloat(row.cells[vat_].childNodes[0].value);													";
		echo "		var vatlessprice   = vatprice / (1 + vat);																				";
		echo "		var vatlessfield   = row.cells[unitpricevatless_].childNodes[0];														";
		echo "																																";
		echo "		vatlessfield.value  = vatlessprice.toFixed(decimals);																	";
		echo "																																";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION COUNTTOTAL()
		// *** Counts total vat and vatless prices and sets field values.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function countRowTotal(row) {																								";
		echo "																																";
		echo "		var totalvat	   	= row.cells[rowtotalvat_].childNodes[0];															";
		echo "		var totalvatless   	= row.cells[rowtotalvatless_].childNodes[0];														";
		echo "		var totalvatshare  	= row.cells[rowsharevat_].childNodes[0];															";
		echo "																																";
		echo "		var quantity	  	= row.cells[quantity_].childNodes[0].value;															";
		echo "		var vatprice 	   	= row.cells[unitpricevat_].childNodes[0].value;														";
		echo "		var vat      	   	= parseFloat(row.cells[vat_].childNodes[0].value);													";
		echo "		var vatlessprice   	= vatprice / (1 + vat);																				";
		echo "																																";
		echo "		var countvat 	   	= vatprice * quantity;																				";
		echo "		var countvattless  	= vatlessprice * quantity;																			";
		echo "		var countvatshare  	= (vatprice - vatlessprice) * quantity;																";
		echo "																																";
		echo "		totalvat.value	   	= countvat.toFixed(decimals);																		";
		echo "		totalvatless.value 	= countvattless.toFixed(decimals); 																	";
		echo "		totalvatshare.value	= countvatshare.toFixed(decimals);																	";
		echo "																																";
		echo "	}																															";
		echo "																																";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION COUNTTOTALWITHVAT(vat)
		// *** Counts total sum of prices which have vat given as parameter.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function countTotalWithVat(vat) {																							";
		echo "																																";
		echo "		table		= document.getElementById('invoicetable');																	";
		echo "																																";
		echo "		var rows	= table.rows;																								";
		echo "		var sum		= 0;																										";
		echo "																																";
		echo "		for(i=0;i<rows.length;i++) {																							";
		echo "																																";
		echo "			var rowvat = rows[i].cells.item(3).childNodes[0].value;																";
		echo "																																";
		echo "			if (rowvat==vat) {																									";
		echo "																																";
		echo "				var sumvat 	   = parseFloat(rows[i].cells.item(rowtotalvat_).childNodes[0].value);								";
		echo "				var sumvatless = parseFloat(rows[i].cells.item(rowtotalvatless_).childNodes[0].value);							";
		echo "																																";
		echo "				if (rows[i].cells.item(rowtotalvat_).childNodes[0].value!='') sum = sum + (sumvat-sumvatless); 					";
		echo "																																";
		echo "			}																													";
		echo "		}																														";
		echo "																																";
		echo "		return sum.toFixed(decimals);																							";
		echo "																																";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION COUNTTOTALWITHOUTVAT()
		// *** Counts total sum of prices which have vat given as parameter.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function countTotalWithoutVat() 		{																					";
		echo "																																";
		echo "		table		= document.getElementById('invoicetable');																	";
		echo "																																";
		echo "		var rows	= table.rows;																								";
		echo "		var sum		= 0;																										";
		echo "																																";
		echo "		for(i=0;i<rows.length;i++) {																							";
		echo "																																";
		echo "			var add = parseFloat(rows[i].cells.item(rowtotalvatless_).childNodes[0].value);										";
		echo "			sum = sum + add; 																									";
		echo "																																";
		echo "		}																														";
		echo "																																";
		echo "		return sum.toFixed(decimals);																							";
		echo "																																";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION PARSESAVEVALUES()
		// *** Parses url-parameter values for saving.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function parseSaveParameters(row) {																							";
		echo "																																";
		echo "		var rowindex	  = row.rowIndex;																						";
		echo "		var table   	  = row.parentNode;																						";
		echo "		var rowcount	  = table.rows.length;																					";
		echo "		var totaltable	  = document.getElementById('totaltable');																";
		echo "		var lastrow		  = totaltable.rows.length-1;																			";
		echo "																																";
		echo "		var isumvatless	  = totaltable.rows[lastrow].cells.item(isumvatless_).innerHTML;										";
		echo "		var isumvat 	  = totaltable.rows[lastrow].cells.item(isumvat_).innerHTML;											";
		echo "		var isumtotal	  = totaltable.rows[lastrow].cells.item(isumtotal_).innerHTML;											";
		echo "																																";
		echo "		var productnumber = table.rows[rowindex].cells.item(productnumber_).childNodes[0].value;								";
		echo "		var productID	  = table.rows[rowindex].cells.item(productname_).childNodes[0].value;									";
		echo "		var select		  = table.rows[rowindex].cells.item(productname_).childNodes[0];										";
		echo "		var productname   = select.options[select.selectedIndex].innerHTML;														";
		echo "																																";
		echo "		var quantity	 = table.rows[rowindex].cells.item(quantity_).childNodes[0].value;										";
		echo "		var vat			 = table.rows[rowindex].cells.item(vat_).childNodes[0].value;											";
		echo "		var upvatless    = table.rows[rowindex].cells.item(unitpricevatless_).childNodes[0].value;								";
		echo "		var upvat 		 = table.rows[rowindex].cells.item(unitpricevat_).childNodes[0].value;									";
		echo "																																";
		echo "		var sumvatless	 = table.rows[rowindex].cells.item(rowtotalvatless_).childNodes[0].value;								";
		echo "		var sumvat		 = table.rows[rowindex].cells.item(rowtotalvat_).childNodes[0].value;									";
		echo "		var vatshare	 = table.rows[rowindex].cells.item(rowsharevat_).childNodes[0].value;									";
		echo "																																";
		echo "		var paramstring  = '&invoicerowid='+row.id;																				";
		echo "			paramstring += '&invoiceid='+invoiceID;																				";
		echo "			paramstring += '&productid='+productID;																				";
		echo "			paramstring += '&productnumber='+productnumber;																		";
		echo "			paramstring += '&productname='+productname;																			";
		echo "			paramstring += '&quantity='+quantity;																				";
		echo "			paramstring += '&vat='+vat;																							";
		echo "			paramstring += '&unitpricevatless='+upvatless;																		";
		echo "			paramstring += '&unitpricevat='+upvat;																				";
		echo "			paramstring += '&sumvatless='+sumvatless;																			";
		echo "			paramstring += '&sumvat='+sumvat;																					";
		echo "			paramstring += '&vatshare='+vatshare;																				";
		echo "			paramstring += '&sortorder='+rowindex;																				";
		echo "			paramstring += '&rowcount='+rowcount;																				";
		echo "			paramstring += '&isumvatless='+isumvatless;																			";
		echo "			paramstring += '&isumvat='+isumvat;																					";
		echo "			paramstring += '&isumtotal='+isumtotal;																				";
		echo "																																";
		echo "		return paramstring;										 																";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** FUNCTION PARSETOTALSAVEPARAMETERS()
		// *** Parses url-parameter values for saving.
		// ****************************************************************************************************************************************
		
		echo "<script>																														";
		echo "																																";
		echo "	function parseTotalSaveParameters(row) {																					";
		echo "																																";
		echo "																																";
		echo "		var rate 		= row.cells.item(irate_).id;																			";
		echo "		var sumvatless  = row.cells.item(isumvatless_).innerHTML;																";
		echo "		var sumvat 		= row.cells.item(isumvat_).innerHTML;																	";
		echo "		var sumtotal 	= row.cells.item(isumtotal_).innerHTML;																	";
		echo "																																";
		echo "		var paramstring = '';																									";
		echo "																																";
		echo "			paramstring += '&invoiceid='+invoiceID;																				";
		echo "			paramstring += '&rate='+rate;																						";
		echo "			paramstring += '&sumvatless='+sumvatless;																			";
		echo "			paramstring += '&sumvat='+sumvat;																					";
		echo "			paramstring += '&sumtotal='+sumtotal;																				";
		echo "																																";
		echo "																																";
		echo "		return paramstring;										 																";
		echo "	}																															";
		echo "</script>																														";
		
		// ****************************************************************************************************************************************
		// *** GLOBAL
		// *** Calls CREATEPRODUCTARRAY() to create global array of products. Global scripts has to be at the end of scripts, since it calls
		// *** CREATEPRODUCTARRAY() on local page creation. InitialIndex holds index of first row to be added to the invoice table (always 0).
		// ****************************************************************************************************************************************
		
		$invoiceID = $this->invoiceID;
		$reserveaction = getUrl ( $this->reserveaction );
		$updateaction = getUrl ( $this->updateaction );
		$updatetotalaction = getUrl ( $this->updatetotalaction );
		$removeaction = getUrl ( $this->removeaction );
		$decimals = $this->decimals;
		
		echo "<script>																														";
		echo "																																";
		echo "	var invoiceID     = " . $invoiceID . ";																						";
		echo " 	var products;																												";
		echo " 	var loadedrows;																												";
		echo " 	var loadedvats;																												";
		echo "																																";
		echo "	var irate_ 				= 0; 																								";
		echo "	var isumvatless_ 		= 1; 																								";
		echo "	var isumvat_	 		= 2; 																								";
		echo "	var isumtotal_ 			= 3; 																								";
		echo "																																";
		echo "	var productnumber_ 		= 0; 																								";
		echo "	var productname_ 		= 1;																								";
		echo "	var	quantity_ 			= 2;																								";
		echo "	var	description_ 		= 3;																								";
		echo "	var	unitpricevatless_ 	= 4;																								";
		echo "	var	unitpricevat_ 		= 5;																								";
		echo "	var	vat_ 				= 6;																								";
		echo "	var	rowsharevat_		= 7;																								";
		echo "	var	rowtotalvatless_	= 8;																								";
		echo "	var	rowtotalvat_		= 9;																								";
		echo "	var	buttons_ 			= 10;																								";
		echo "																																";
		echo "	var decimals = " . $decimals . ";																							";
		echo "																																";
		echo "	var reserveaction 		= '" . $reserveaction . "';																			";
		echo "	var updateaction  		= '" . $updateaction . "';																			";
		echo "	var updatetotalaction  	= '" . $updatetotalaction . "';																		";
		echo "	var removeaction  		= '" . $removeaction . "';																			";
		echo "																																";
		echo "	$(document).ready(function() {																								";
		echo "																																";
		echo "		products 	= createProductsArray();																					";
		echo "		loadedrows	= createRowArray();																							";
		echo "		loadedvats	= createVatArray();																							";
		echo "																																";
		echo "		createTableHeader(invoiceID);																							";
		echo "																																";
		echo "		if (loadedrows.length>0) createExistingRows(loadedrows);																";
		echo "																																";
		echo "		var row 	= createRow(loadedrows.length-1);																			";
		echo "		reserveRow(row);																										";
		echo "																																";
		echo "		createTotalTable();																										";
		echo "		countTotals();																											";
		echo "																																";
		echo "	});																															";
		echo "																																";
		echo "</script>																														";
	}
	public function show() {
		$this->createInvoice ();
		$this->createScripts ();
	}
}

?>