
//********************************************************************************************************************************************************
//*** CLASS CHANGEDROPDOWNCONTENTCOLUMN(NAME,CONTENT)
//*** Creates DOM-element for TimeSpanColumn-class
//********************************************************************************************************************************************************
																																		
	function ChangeDropdownContentColumn(name,value,text,content,fromColumn,validationFunction,updateAction, predictive) {																			
																																					
		this.name 			     = name;																										
		this.content 		   	 = content;
		this.fromColumn		  	 = fromColumn;
		this.value				 = value;																										
		this.text			   	 = text;																										
		this.domElement		   	 = null;
		this.validationFunction	 = validationFunction;
		this.updateAction	   	 = updateAction;
		this.rowIdentifierObject = null;
		this.predictive 		 = predictive;
		
		//********************************************************************************************************************************************************
		//***  OBJECT FUNCTION CREATEDOMELEMENT()
		//***  Creates domElement for object.
		//********************************************************************************************************************************************************
		
		this.createDomElement = function() {																									
				
			this.domElement = this.createChangeDropdownContentElement();																			
			//$(this).chosen({});
			
			//$(this.domElement).chosen();
			return this.domElement;		
			
		};																																		
				
		//********************************************************************************************************************************************************
		//***  OBJECT FUNCTION GETNAME()
		//***  Returns the name of the object.
		//********************************************************************************************************************************************************
		
		this.getName = function() {																												
			return this.name;																													
		};
		
		//********************************************************************************************************************************************************
		//***  OBJECT FUNCTION GETFROMCOLUMN()
		//***  Returns the name of datavariable of database row.
		//********************************************************************************************************************************************************
		
		this.getFromColumn = function() {																												
			return this.fromColumn;																													
		};					

		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETVALUE() 
		//*** Returns value contained in object's domElement.
		//********************************************************************************************************************************************************
		
		this.getValue = function() {																											
			var elem = this.getDomElement();
			var value  = elem.value;
			//alert('returning value '+value);
			return value;																																			
		};																																		
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION SETVALUE(VALUE)
		//*** Sets value inside of the objects domElement.
		//********************************************************************************************************************************************************

		this.setValue = function(value) {
			
			var domElement = this.getDomElement();
			domElement.value = value;
			
		};
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION SETDISABLED(ISDISABLED)
		//*** Sets if domElement is active or not.
		//********************************************************************************************************************************************************
		
		this.setDisabled = function(isDisabled) {
			
			var domElement = this.getDomElement();
			domElement.disabled = isDisabled;
			
		};
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETTARGETCOLUMNS()
		//*** *Depraved* Returns targetColumns. *Depraved*
		//********************************************************************************************************************************************************
		
		this.getTargetColumns = function() {																											
			return this.targetColumns;																															
		};																																		
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION FINALIZE()
		//*** Finalizes object.
		//********************************************************************************************************************************************************
		
		this.finalize = function(dataObjects) {																										
			
		for(var dataIndex in dataObjects) {
				
				var dataObject = dataObjects[dataIndex];
					
				if (dataObject.getClass()=='TimeSheetRowIdentifier') {
					
					this.rowIdentifierObject = dataObject;
	
				}
			}
		};
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION VALIDATE()
		//*** Called to validate object with external function.
		//********************************************************************************************************************************************************
		
		this.validate = function() {
		
			var validationFunction = this.validationFunction;
				
			if (validationFunction!=null) {
				return window[validationFunction](this);
			}
			
			return false;
		};
			
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION CREATEEVENTLISTENERS()
		//*** Creates necessary listeners for domElements.
		//********************************************************************************************************************************************************
		
		this.createEventListeners = function() {																								
				
			var sourceElement 	   = this.getDomElement();
			var sourceObject  	   = this;
			var validationFunction = this.validationFunction;
				
			sourceElement.addEventListener('click',function(event) {																		
			
				if (validationFunction!="") {
					
					var isValid = window[validationFunction](sourceObject);
				
					if(!isValid) {	
						sourceObject.setErrorClasses();
						return;
					}
					
					if(isValid)	 {
						
						sourceElement.className = "timesheet-dropdownmenu-valid";
					}
					
				}
				
				window[calculationAlgorithm](dataRows);
				sourceObject.updateToDatabase();
			
				
			},true);
		};
		
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION SETERRORCLASSES()
		//*** Sets error classes to option elements by blanketing error then removing it from valid choices.
		//********************************************************************************************************************************************************
		
		this.setErrorClasses = function() {
			
			var sourceElement = this.getDomElement();
			var optionCount = sourceElement.options.length;
			var options		= sourceElement.options;
		
			sourceElement.className = "timesheet-dropdownmenu-error";
			
			for(var optionIndex = 0; optionIndex < optionCount; optionIndex++) {
				if (optionIndex!=0) options[optionIndex].className = "timesheet-dropdownmenu-valid";
			}
			
		};
		
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION UPDATETODATABASE()
		//*** Updates value to database.
		//********************************************************************************************************************************************************
		
		this.updateToDatabase = function() {
			
			var updateAction 		= this.updateAction;
			var rowIdentifierObject	= this.rowIdentifierObject;
			var rowID				= rowIdentifierObject.getValue();
			var sourceElement		= this.getDomElement();
			
			var parameters 	 = '&id='+rowID;
				parameters 	 = parameters + '&value='+sourceElement.value;
			
				//alert(updateAction+parameters);
				
			$.getJSON(updateAction, parameters, function() {
					
			})																														
			.done(function(reply) {																																																									
			});	
			
		};
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETSELECTEDOBJECT()
		//*** Returns selected object.
		//********************************************************************************************************************************************************
		
		this.getSelectedObject = function() {																											

			var id = this.getValue();
			return this.content[id];																															
		};																																		
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETDOMELEMENT()
		//*** Returns domElement.
		//********************************************************************************************************************************************************
		
		this.getDomElement = function() {
			return this.domElement;																												
		};																																		
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETCLASS()
		//*** Returns the PHP-class where from object was created. 
		//********************************************************************************************************************************************************
		
		this.getClass = function() {																											
			return 'ChangeDropdownContentColumn';																								
		};		
			
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION CREATECHANGEDROPDOWNCONTENTELEMENT()
		//*** Creates domElement.
		//********************************************************************************************************************************************************
	
		this.createChangeDropdownContentElement = function() {
			
			var content 	= this.content;																								
			var select  	= document.createElement('select');																						
			var item		= null;																													
			var value 		= this.value;																									
			var text		= this.text;																									
																																						
			item 			= document.createElement('option');																						
			item.text		= 'Ei valittu';																											
			item.value		= 0;																													
			
			//$(select).chosen();																																	
			
			select.add(item);																													
			
			for(var index in content) {																											
																																						
				var contentItem = content[index];																								
																																						
				item = document.createElement('option');																						
				item.text = contentItem.data[text];																								
				item.value = contentItem.data[value];																							
																																					
				select.add(item);																												
																																						
			}																																	
			return select;																														
		
		};							
	
	}						
															
																																										