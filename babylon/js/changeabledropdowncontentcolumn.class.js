
	
	
																																
		
//********************************************************************************************************************************************************
//*** CLASS CHANGEABLEDROPDOWNCONTENTCOLUMN(NAME)
//*** Creates DOM-element for TimeSpanColumn-class
//********************************************************************************************************************************************************
																																							
	function ChangeableDropdownContentColumn(name,targetColumnName,content,filter,value,text,fromColumn,validationFunction,updateAction) {												
																																					
		this.name 	 				= name;																										
		this.targetColumnName 		= targetColumnName;																							
		this.targetColumn			= null;																										
		this.content 				= content;																									
		this.filter					= filter;																									
		this.value					= value;																									
		this.text					= text;																										
		this.confirmID				= 'empty';
		this.domElement		    	= null;
		this.fromColumn				= fromColumn;
		this.validationFunction		= validationFunction;
		this.updateAction			= updateAction;
		this.rowIdentifierObject	= null;
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION CREATEDOMELEMENT()
		//*** Creates and returns domElement.
		//********************************************************************************************************************************************************
		
		this.createDomElement = function() {																									
																																					
			this.domElement = this.createChangeableDropdownContentElement();	
			//$(this).chosen({});
			return this.domElement;																												
																																					
		};																																		
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETNAME()
		//*** Returns the name of the object.
		//********************************************************************************************************************************************************
		
		this.getName = function() {																												
			return this.name;																													
		};																																		
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETVALUE()
		//*** Returns value contained in select domElement.
		//********************************************************************************************************************************************************
		
		this.getValue = function() {
			
			var select = this.getDomElement();
			var value  = select.value;
			return value;																															
		};																																		
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTOIN SETVALUE(VALUE)
		//*** Sets value of select domElement.
		//********************************************************************************************************************************************************

		this.setValue = function(value) {
			
			var domElement 	 = this.domElement;
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
		//*** OBJECT FUNCTION GETCONTENT()
		//*** Returns whole content.
		//********************************************************************************************************************************************************
		
		this.getContent = function() {																											
			return this.content;																												
		};																																		
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETSELECTEDOBJECT()
		//*** Returns selected object.
		//********************************************************************************************************************************************************
		
		this.getSelectedObject = function() {
			
			var value	= this.value;
			var filter  = this.filter;
			var confirmID = this.confirmID;
			var valueID   = this.getValue();
			var content = this.content;
			
			//alert('ConfirmID:' + confirmID + ', ValueID' + valueID + ', Content Object' + content);
			
			for(var index in content) {
				
				if ((content[index].data[filter]==confirmID) && (content[index].data[value]==valueID)) {
					
					return this.content[index];
					
				}
			}
			
			return null;																												
		};																																		
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION FINALIZE(DATAOBJECTS)
		//*** Finalizes object.
		//********************************************************************************************************************************************************
		
		this.finalize = function(dataObjects) {																										
																																					
			var targetColumnName = this.targetColumnName;																						
																																					
			for(var dataIndex in dataObjects) {																										
																																					
				if (dataObjects[dataIndex].getName()==targetColumnName) {																				
																																					
					this.targetColumn = dataObjects[dataIndex];																						
				}																																
			}	
			
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
		
			var validationFunction = this.validatationFunction;
			
			if (validationFunction!=null) {
				return window[validationFunction](this);
			}
			
			return false;
		};
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION CREATEEVENTLISTENERS()
		//*** Creates necessary listeners.
		//********************************************************************************************************************************************************
		
		this.createEventListeners = function() {																								
																																					
			var targetColumn 		= this.targetColumn;																						
			var targetElement		= targetColumn.getDomElement();																				
			var sourceObject		= this;
			var sourceElement		= this.getDomElement();
			var updateAction		= this.updateAction;
			var validationFunction  = this.validationFunction;
			
			
			//*****************************************************************************************************************************************************
			// FOREIGN EVENTLISTENER. Attachs this listener to foreing domElement to get confirmID to create new select-content.
			//*****************************************************************************************************************************************************
			
			targetElement.addEventListener('change',function(event) {																		
				
				var confirmID 	= this.value;																									
				
				sourceObject.createNewValues(confirmID);
				
				if (validationFunction!=null) {
					
					var isValid = window[validationFunction](sourceObject);
				
					if(!isValid) {
						sourceObject.setErrorClasses();
					}
					if(isValid)	 {
						sourceElement.className = "timesheet-dropdownmenu-valid"; 
					}
				
					sourceObject.updateToDatabase();
					
				}																									
			},true);
			
			//*****************************************************************************************************************************************************
			// NATIVE EVENTLISTENER. Attachs this listener to sourceElement.
			//*****************************************************************************************************************************************************
			
			sourceElement.addEventListener('click',function(event) {																		
				
				if (validationFunction!=null) {
					
					var isValid = window[validationFunction](sourceObject);
				
					if(!isValid) {
						sourceObject.setErrorClasses();
					}
					if(isValid)	{
						sourceElement.className = "timesheet-dropdownmenu-valid"; 
					}
					
				}	
				
				sourceObject.updateToDatabase();
				window[calculationAlgorithm](dataRows);
				
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
		//*** OBJECT FUNCTION UPDATETODATABASE()
		//*** Updates value to database.
		//********************************************************************************************************************************************************
		
		this.updateToDatabase = function() {
						
			var updateAction 		= this.updateAction;
			var rowIdentifierObject	= this.rowIdentifierObject;
			var rowID				= rowIdentifierObject.getValue();
			var sourceElement		= this.getDomElement();
			
			var parameters 	 = '&id='+rowID;
				parameters 	 = parameters + '&value='+sourceElement.value;
				
			$.getJSON(updateAction, parameters, function() {
					
			})																														
			.done(function(reply) {																																																									
			});	
			
		};
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION CREATENEWVALUES(CONFIRMID)
		//*** Creates new values for select.
		//********************************************************************************************************************************************************
		
		this.createNewValues = function(confirmID) {																							
				
			this.confirmID = confirmID;
			var content = this.content;																											
			var select  = this.getDomElement();																									
			var filter  = this.filter;																											
			var value   = this.value;																											
			var text  	= this.text;																											
			var item	= null;																													
																																					
			while(select.firstChild) {	
			
				select.removeChild(select.firstChild); 
			
			}																
																																					
			item 		= document.createElement('option');																						
			item.text	= 'Ei valittu';																											
			item.value	= 0;																													
			select.add(item);																													
																																					
			for(var index in content) {																										
																																					
				var contentItem = content[index];	
				
				if (confirmID==contentItem.data[filter]) {																					
																																					
					item   = document.createElement('option');																				
					item.text  = contentItem.data[text];																					
					item.value = contentItem.data[value];																					
					select.add(item);																										
																																					
				}																															
			}																																
		};																																		
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETDOMELEMENT()
		//*** Returns domElement.
		//********************************************************************************************************************************************************
		
		this.getDomElement = function() {																										
			return this.domElement;																												
		};																																		
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETFROMCOLUMN()
		//*** Returns fromColumn.
		//********************************************************************************************************************************************************
	
		this.getFromColumn = function() {		
			return this.fromColumn;
		};

		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETCLASS()
		//*** Returns PHP-class where from object was created.
		//********************************************************************************************************************************************************
		
		this.getClass = function() {																											
			return 'ChangeableDropdownContentColumn';																							
		};																																		

		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION CREATECHANGEABLEDROPDOWNCONTENTELEMENT(CHANGEDROPDOWN)
		//*** Creates DOM-element for ChangeDropdownContentColumn-class.
		//********************************************************************************************************************************************************
																																									
		this.createChangeableDropdownContentElement = function () {																		
			
			var select  = document.createElement('select');																						
			var item	= document.createElement('option');																						
																																						
			item.text 	= 'Ei valittu';																											
			item.value 	= 0;																													
																																						
			select.add(item);	
			
			select.id 	= this.getName();																								
																																						
			return select;																														
																																					
		};	
		
	}	
	
	
																												
												