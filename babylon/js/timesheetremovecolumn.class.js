//********************************************************************************************************************************************************
//**  OBJECT TIMESHEETREMOVECOLUMN(NAME,COMPARECOLUMN);
//*** Implements TimeSheetRemoveColumn-Object
//********************************************************************************************************************************************************
																																
	function TimeSheetRemoveColumn(name,compareColumnName,restriction,removeAction) {																				
																																				
		this.name 			 	  = name;
		this.compareColumnName    = compareColumnName;
		this.domElement		 	  = null;																										
		this.index			      = null;
		this.compareColumn		  = null;
		this.restriction		  = restriction;
		this.removeAction		  = removeAction;
		  
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
		//*** OBJECT FUNCTION GETINDEX()
		//*** Returns index on which row SplitColumn is tied.
		//********************************************************************************************************************************************************

		this.getRestriction = function() {
		
			return this.restriction;	
		};
		
		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETCOMPARETO()
		//*** Returns object of a column which to compare.
		//********************************************************************************************************************************************************

		this.setCompareTo= function(compareTo) {
		
			this.compareColumn = compareTo;	
		};

		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION SETCOMPARETO()
		//*** Sets object to compare to.
		//********************************************************************************************************************************************************

		this.getCompareTo = function(compareTo) {
		
			return this.compareColumn;
		};

		//********************************************************************************************************************************************************
		//*** OBJECT FUNCTION GETCOMPARETONAME()
		//*** Returns compare name.
		//********************************************************************************************************************************************************

		this.getCompareToName = function() {
		
			return this.compareColumnName;
		};
		
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION FINALIZE(COLUMNS)
		//*** Finalizes object.
		//********************************************************************************************************************************************************
		
		this.finalize = function(columns) {																										
			
			//alert('Finalizing remove button by trying to find column ' + this.compareColumnName);
			
			var targetColumnName = this.compareColumnName;																						
			
			for(var column in columns) {																										
																																					
				if (columns[column].getName()==targetColumnName) {																				
					//alert(columns[column].getName());																																
					this.setCompareTo(columns[column]);
				}																																
			}			
		};																																		
				
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION CREATEEVENTLISTENERS()
		//*** Creates associated eventlisteners to updateColumns.
		//********************************************************************************************************************************************************
		
			
		this.createEventListeners = function() {																								
			
			var index 		  = this.getIndex();
			var sourceElement = this.getDomElement();
			var restriction	  = this.getRestriction();
			var removeAction  = this.removeAction;
			
			sourceElement.onclick = function() {
				
				dataRow = dataRows[index];
				
				//alert('Trying to remove row...');
				
				var rowID;
				
				for(var dataIndex in dataRow) {
					// Gets rowidentifier
					var dataObject = dataRow[dataIndex];
					
					if (dataObject.getName()=='TimesheetRowIdentifier') {
						//alert('Found rowidentifier object!');
						rowID = dataObject.getValue();	
					}
				}
				
				var isValid = removeRow(index,restriction);
				
				//alert('Is row valid for removal ' + isValid);
				
				if (isValid) {
		
					//alert('Removing row ' + rowID + ' from database.');
								
					var parameters  = '&id='+rowID;																							
			
					//alert('Removing with ' + removeAction + parameters);
					
					$.getJSON(removeAction, parameters, function() {																		
					})																														
					.done(function(reply) {																																																								
					});																													
				}
			
				window[calculationAlgorithm](dataRows);
			}
		};		
					
		//********************************************************************************************************************************************************
		//**  OBJECT FUNCTION CREATEDOMELEMENT
		//*** Creates DOM-Element.
		//********************************************************************************************************************************************************
		
		this.createDomElement = function() {																									
																																				
			this.domElement = createTimeSheetRemoveColumnElement(this);	 																					
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
			return 'TimeSheetRemoveColumn';																											
		};							
		
		
	}									
	
	
//********************************************************************************************************************************************************
//*** FUNCTION CREATETIMESPANELEMENT(COLUMN) 
//*** Creates DOM-element for TimeSpanColumn-class
//********************************************************************************************************************************************************
		
	function createTimeSheetRemoveColumnElement(column) {																								
																																						
		var button = document.createElement('button');
		button.innerHTML = 'Remove';
		return button;

	}		
	