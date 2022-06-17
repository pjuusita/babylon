//****************************************************************************************************************************************************************
//**  FUNCTION FLOATINGSELECTION(title,isPrimary)
//**  Creates floating selection.
//****************************************************************************************************************************************************************

function floatingSelection(title,isPrimary) {
	
	this.title				= title;
	this.element 			= document.createElement('div');
	this.element.className 	= 'floatingSelection';
	this.element.id 		= 'selection'+title;
	this.element.object 	= this;
	this.selectionData  	= null;
	this.innerTable			= null;
	this.draggableClass		= 'floatingSelection';
	this.element.object		= this;
	this.isPrimary			= isPrimary;
	this.colors				= getJSColors();

	//****************************************************************************************************************************************************************
	//**  OBJECT FUNCTION CREATEDRAGGABLEELEMENTS(nameVariable,draggableClass)
	//**  Creates draggable elements for selection using nameVariable and draggableClass.
	//****************************************************************************************************************************************************************
	
	this.createDraggableElements = function(nameVariable,draggableClass) {
		
		var data 		= this.selectionData;
		var innerTable 	= this.innerTable;
		
		for(var rowIndex in data) {
		 
			var dataRow = data[rowIndex];
			
			var colorIndex = parseInt(dataRow['color']);
			
			if (this.isPrimary=='primary') var color = this.colors.colorsIntIndexed[colorIndex];
			if (this.isPrimary!='primary') var color = this.colors.colors['lightgray'];
			
			var newDraggableElement = new draggableElement(dataRow[nameVariable],dataRow,draggableClass,this.isPrimary);
		
			newDraggableElement.setBackgroundColor(color.normal);
			newDraggableElement.setBorderColor(color.dark);
			newDraggableElement.isPrimary = this.isPrimary;
			
			innerTable.draggableArea.appendChild(newDraggableElement.getElement());
			
			colorIndex++;
		}
	};
	
	//****************************************************************************************************************************************************************
	//**  OBJECT FUNCTION CREATEINNERTABLE()
	//**  Creates inner table for floating selection.
	//****************************************************************************************************************************************************************

	this.createInnerTable = function() {

		var innerTable 			= floatingSelectionInnerTable(this.title,this.element);
		this.innerTable			= innerTable;
		
		return innerTable;
		
	};

	//****************************************************************************************************************************************************************
	//** OBJECT FUNCTION CREATECSS(element)
	//** Creates style.
	//****************************************************************************************************************************************************************

	this.createCSS = function(element) {
		
		element.style.position = 'fixed';
		element.style.width	   = '200px';
		
	};

	//****************************************************************************************************************************************************************
	//** OBJECT FUNCTION CREATEEVENTS(element)
	//** Creates events for element.
	//****************************************************************************************************************************************************************

	this.createEvents = function(element) {
		
			$(element).draggable({
				
				drag : function(event,iu) {

					var mouseX = event.clientX;
					var mouseY = event.clientY;
					
					$(element).offset({top : mouseY, left : mouseX});
				}
			
			});
			
	};

	//****************************************************************************************************************************************************************
	//** PSEUDO MAIN
	//** Pseudo main.
	//****************************************************************************************************************************************************************

	this.createCSS(this.element);
	this.element.appendChild(this.createInnerTable());
	this.createEvents(this.element);

}