//********************************************************************************************************************************************************
//**  OBJECT TIMESPANCOLUMN(NAME,SELECTCOLUMNNAME,START,END)
//*** Implements TimeSpanColumn-Object
//********************************************************************************************************************************************************
																																
	function TimeSheetSplitColumn(name,splitName,splitAction) {																				
																																				
		this.name 			  = name;																										
		this.domElement		  = null;																										
		this.index			  = null;
		this.listener		  = false;
		this.splitName		  = splitName;
		this.splitObject	  = null;		
		this.splitAction	  = splitAction;
		 
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
			return 'new value';
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
		//*** OBJECT FUNCTION SETDISABLED(ISDISABLED)
		//*** Sets if domElement is active or not.
		//********************************************************************************************************************************************************
		
		this.setDisabled = function(isDisabled) {
			
			var domElement = this.getDomElement();
			domElement.disabled = isDisabled;
			
		};
		
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION SETINDEX(INDEX)
		//*** Sets index on which row SplitColumn is tied.
		//********************************************************************************************************************************************************

		this.setIndex = function(index) {
			
			this.index = index;	
		};
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETINDEX()
		//*** Returns index on which row SplitColumn is tied.
		//********************************************************************************************************************************************************

		this.getIndex = function() {
		
			return this.index;	
		};

		
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION FINALIZE(DATAOBJECTS)
		//*** Finalizes object.
		//********************************************************************************************************************************************************
		
		this.finalize = function(dataObjects) {																										
			
			var splitName = this.splitName;
			
			for(var dataIndex in dataObjects) {
				
				var dataObject = dataObjects[dataIndex];
				
				//alert(dataObject.getName() + ' compareto to ' + this.splitName);
				
				if (dataObject.getName()==splitName) {
					//alert('Found splitObject');
					this.splitObject = dataObject;
					//alert(dataObject.getName());
				}
			}
			
		};																																		
				
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION CREATEEVENTLISTENERS()
		//*** Creates associated eventlisteners to updateColumns.
		//********************************************************************************************************************************************************
			
		this.createEventListeners = function() {																								
			
			var index 			= this.getIndex();
			var sourceElement 	= this.getDomElement();
			var splitObject 	= this.splitObject;
			
			sourceElement.onclick = function() {
				
				var splitID;
				var parameters  = '&copyvalue='+splitObject.getValue();
				
				//alert(splitObject);
				
				$.getJSON(splitAction, parameters, function() {																			
				})																															
				.done(function(reply) {
					
					splitID  = reply[0].insertID;
					
					//alert('Created new database row with primary key = ' + splitID);
					
					createSplitRow(index,splitID,splitObject);
					
					window[calculationAlgorithm](dataRows);
					
				});																																	
			}
		};		
			
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION CREATEDOMELEMENT
		//*** Creates DOM-Element.
		//********************************************************************************************************************************************************
		
		this.createDomElement = function() {																									
																																				
			this.domElement = createTimeSheetSplitColumnElement(this);	 																					
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
		//*** Returns class-name 'TimeSpanColumn'.
		//********************************************************************************************************************************************************
		
		this.getClass = function() {																											
			return 'TimeSheetSplitColumn';																											
		};																																																																			
	}									
	
	
//********************************************************************************************************************************************************
//*** FUNCTION CREATETIMESPANELEMENT(COLUMN) 
//*** Creates DOM-element for TimeSpanColumn-class
//********************************************************************************************************************************************************
		
	function createTimeSheetSplitColumnElement(column) {																								
																																						
		var button = document.createElement('button');
		button.innerHTML = 'Split';
		return button;

	}		
	

		
