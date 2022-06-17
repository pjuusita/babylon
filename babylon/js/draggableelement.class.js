//****************************************************************************************************************************************************************
//**  OBJECT.FUNCTION DRAGGABLEELEMENT(title,data,draggableClass,isPrimary)
//**  Creates a new draggable element.
//****************************************************************************************************************************************************************

function draggableElement(title,data,draggableClass,isPrimary) {
	
	this.title	 		= title;
	this.element 		= null;
	this.data 	 		= data;
	this.draggableClass = draggableClass;
	this.isPrimary		= isPrimary;
	
	//console.log('Creating ' + this.isPrimary + ' draggable element with title ' + this.title);
	
	//****************************************************************************************************************************************************************
	//**  OBJECT.FUNCTION CREATEELEMENT()
	//**  Creates dom-element and encapsulates data for easy access.
	//****************************************************************************************************************************************************************
	
	this.createElement  = function() {
		
		var draggableDiv			= document.createElement('div');
		
		draggableDiv.innerHTML 		= this.title;
		draggableDiv.title			= this.title;
		draggableDiv.data			= this.data;
		draggableDiv.draggableClass = this.draggableClass;
		draggableDiv.isPrimary		= this.isPrimary;
		draggableDiv.className		= 'draggableElement';
		
		this.element				= draggableDiv;
		
	};
	
	//****************************************************************************************************************************************************************
	//**  OBJECT.FUNCTION SETBACKGROUNDCOLOR(backgroundColor)
	//**  Sets elements background color.
	//****************************************************************************************************************************************************************

	this.setBackgroundColor	= function(backgroundColor) {
		
		var element 					= this.element;
		element.style.backgroundColor 	= backgroundColor;
		element.backgroundColor			= backgroundColor;
	
	}

	//****************************************************************************************************************************************************************
	//**  OBJECT.FUNCTION SETBORDERCOLOR(borderColor)
	//**  Sets elements border color.
	//****************************************************************************************************************************************************************

	this.setBorderColor		= function(borderColor) {
		
		var element						= this.element;
		element.style.borderColor		= borderColor;
		element.borderColor				= borderColor;
		
	}

	//****************************************************************************************************************************************************************
	//**  OBJECT.FUNCTION CREATEEVENTS()
	//**  Creates events for element.
	//****************************************************************************************************************************************************************

	this.createEvents = function() {
		
		var element = this.element;
		
		$(element).draggable({

			helper	 	: 'clone',
			appendTo 	: 'body'
		
		});
			
	};

	//****************************************************************************************************************************************************************
	//**  OBJECT.FUNCTION GETELEMENT()
	//**  Returns dom-element.
	//****************************************************************************************************************************************************************

	this.getElement = function() {
		return this.element;
	};

	//****************************************************************************************************************************************************************
	//** PSEUDO MAIN
	//** Main
	//****************************************************************************************************************************************************************

	this.createElement();
	this.createEvents();
	
}