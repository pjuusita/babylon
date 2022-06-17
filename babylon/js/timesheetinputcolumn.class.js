//********************************************************************************************************************************************************
//**  OBJECT TIMESHEETINPUTCOLUM(NAME)
//*** Implements TimeSpanColumn-Object
//********************************************************************************************************************************************************
																																
	function TimeSheetInputColumn(name,dataVariable,validationFunction,updateAction) {																				
																																				
		this.name 			  		= name;
		this.domElement		  		= null;
		this.validationFunction 	= validationFunction;
		this.updateAction			= updateAction;
		this.dataVariable			= dataVariable;
		this.rowIdentifierObject  	= null;
		
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION GETNAME()
		//*** Returns name.
		//********************************************************************************************************************************************************
		
	 	this.getName  = function() { 																											
			return this.name																													
		};																																		
																																			
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION GETVALUE()
		//*** Returns value.
		//********************************************************************************************************************************************************
		
		this.getValue = function() {
			var domElement = this.domElement;
			return domElement.value;
		};
		
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION GETDATAVARIABLE()
		//*** Returns name.
		//********************************************************************************************************************************************************
		
	 	this.getDataVariable  = function() { 																											
			return this.dataVariable;																													
		};		
																																				
		//********************************************************************************************************************************************************
		//*** FUNCTION CREATEOPTIONMINUTES(MINUTESELECT)
		//*** Creates Option-elements for minutes.
		//********************************************************************************************************************************************************

		this.setValue = function(value) {
			
			var domElement = this.getDomElement();
			domElement.value = value;
			
		};
		
		//********************************************************************************************************************************************************
		//*** FUNCTION CREATEOPTIONMINUTES(MINUTESELECT)
		//*** Creates Option-elements for minutes.
		//********************************************************************************************************************************************************
		
		this.getCopy = function() {
			
			return '';
			
		};
		
		//********************************************************************************************************************************************************
		//*** FUNCTION CREATEOPTIONMINUTES(MINUTESELECT)
		//*** Creates Option-elements for minutes.
		//********************************************************************************************************************************************************
		
		this.setCopy = function() {
			
		};
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETTEXT();
		//*** Returns text property.
		//********************************************************************************************************************************************************
		
		this.getText = function() {
			return this.text;
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
		//**  OBJECT FUNCTION FINALIZE(COLUMNS)
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
		//**  OBJECT FUNCTION VALIDATE()
		//*** Called to validate object with external function.
		//********************************************************************************************************************************************************
		
		this.validate = function() {
		
			var validationFunction = this.validationFunction;
			var isValid			   = false
			
			if (validationFunction!=null) {
				isValid = window[validationFunction](this);
			}
			
			return isValid;
			
		};
		
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION CREATEEVENTLISTENERS()
		//*** Creates associated eventlisteners to updateColumns.
		//********************************************************************************************************************************************************
		
		this.createEventListeners = function() {																								
					
			var updateAction 		= this.updateAction;
			var sourceObject 		= this;
			var sourceElement 		= this.getDomElement();
			var rowIdentifierObject = this.rowIdentifierObject;
			
			sourceElement.onchange = function() {
			
				window[calculationAlgorithm](dataRows);
				
				var parameters	 = '&id='+rowIdentifierObject.timesheetRowId + '&value=' + sourceElement.value; 
				
				var isValid 	 = sourceObject.validate();
				
				if(!isValid) {
					sourceElement.className = "timesheet-input-error";
				}
				if(isValid)	 {
					sourceElement.className = "timesheet-input-valid";
				}
				console.log(' - '+updateAction+parameters);
				
				$.getJSON(updateAction, parameters, function() {																			
				})																															
				.done(function(reply) {	
				});	
				
			}
		};		
			
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION CREATEDOMELEMENT
		//*** Creates DOM-Element.
		//********************************************************************************************************************************************************
		
		this.createDomElement = function() {
			
			var domElement = this.createTimeSheetInputElement();
			this.domElement = domElement;
			return this.domElement;																												
																													
		};																																		
																																					
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION GETDOMELEMENT()
		//*** Returns DOM-Element.
		//********************************************************************************************************************************************************
		
		this.getDomElement = function() {																										
			return this.domElement;																												
		};																																		
		
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION GETCLASS()
		//*** Returns class-name 'TimeSheetFixedColumn'.
		//********************************************************************************************************************************************************
		
		this.getClass = function() {																											
			return 'TimeSheetInputColumn';																											
		};
		
		//********************************************************************************************************************************************************
		//*** FUNCTION CREATETIMESHEETFIXEDCOLUMNELEMENT(COLUMN) 
		//*** Creates DOM-element for column.
		//********************************************************************************************************************************************************
				
		this.createTimeSheetInputElement = function() {																								
																																								
			var input 	= document.createElement('input');																							
			input.size  = 8;																													
																																					
			return input;		
			
		};																																	
			
	}									
	
	

