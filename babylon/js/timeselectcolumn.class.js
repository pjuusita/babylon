//********************************************************************************************************************************************************
//*** OBJECT TIMESELECTCOLUMN(NAME)
//*** Implements TimeSelectColumn-Object.
//********************************************************************************************************************************************************
																																																											
	function TimeSelectColumn(name,startHourColumn,startMinuteColumn,endHourColumn,endMinuteColumn,updateAction) {																										
																																				
		this.name 					= name;																												
		this.domElement 			= null;
		this.updateAction 			= updateAction;
		this.rowIdentifierObject 	= null;
		
		this.startHourColumn 		= startHourColumn;
		this.startMinuteColumn  	= startMinuteColumn;
		this.endHourColumn 			= endHourColumn;
		this.endMinuteColumn 		= endMinuteColumn;
		 
	//********************************************************************************************************************************************************
	//**  OBJECT FUNCTION CREATEDOMELEMENT()
	//*** Creates DOM-Element
	//********************************************************************************************************************************************************
		
		this.createDomElement = function() {																									
																																				
			this.domElement = this.createTimeSelectElement(this);																					
			return this.domElement;																												
																																				
		};																																		
																																				
	//********************************************************************************************************************************************************
	//**  OBJECT FUNCTION GETNAME()
	//*** Returns name.
	//********************************************************************************************************************************************************
		
		this.getName = function() {																												
			return this.name;																													
		};																																		
		
	//********************************************************************************************************************************************************
	//**  OBJECT FUNCTION GETVALUE()
	//*** Returns value.
	//********************************************************************************************************************************************************
		
		this.getValue = function() {																											
																																				
			var starthours 	 = this.domElement.childNodes[0].childNodes[0].childNodes[0].childNodes[0].value;									
			var startminutes = this.domElement.childNodes[0].childNodes[0].childNodes[1].childNodes[0].value;									
			var endhours     = this.domElement.childNodes[0].childNodes[0].childNodes[2].childNodes[0].value;									
			var endminutes   = this.domElement.childNodes[0].childNodes[0].childNodes[3].childNodes[0].value;									
				
			var time = new Time(starthours,startminutes,endhours,endminutes);
																																					
			return time;																														
																																				
		};																																		
		
	//********************************************************************************************************************************************************
	//*** OBJECT FUNCTION SETVALUE(TIMEARRAY)
	//*** Sets value from timeArray.
	//********************************************************************************************************************************************************

		this.setValue = function(timeArray) {
			
			this.domElement.childNodes[0].childNodes[0].childNodes[0].childNodes[0].value = timeArray[this.startHourColumn];									
			this.domElement.childNodes[0].childNodes[0].childNodes[1].childNodes[0].value = timeArray[this.startMinuteColumn];									
			this.domElement.childNodes[0].childNodes[0].childNodes[2].childNodes[0].value = timeArray[this.endHourColumn];									
			this.domElement.childNodes[0].childNodes[0].childNodes[3].childNodes[0].value = timeArray[this.endMinuteColumn];									
		
		};
		
	//********************************************************************************************************************************************************
	//*** OBJECT FUNCTION SETDISABLED(ISDISABLED)
	//*** Sets if domElement is active or not.
	//********************************************************************************************************************************************************
		
		this.setDisabled = function(isDisabled) {
			
			var domElement = this.getDomElement();
			
			domElement.childNodes[0].childNodes[0].childNodes[0].childNodes[0].disabled = isDisabled;							
			domElement.childNodes[0].childNodes[0].childNodes[1].childNodes[0].disabled = isDisabled;				
			domElement.childNodes[0].childNodes[0].childNodes[2].childNodes[0].disabled = isDisabled;								
			domElement.childNodes[0].childNodes[0].childNodes[3].childNodes[0].disabled = isDisabled;	
			
		};
		
		
	//********************************************************************************************************************************************************
	//**  OBJECT FUNCTION GETSELECTEDOBJECT()
	//*** Returns Time-Object.
	//********************************************************************************************************************************************************

		this.getSelectedObject = function() {
			var value = this.getValue();
			return value;
			
		};
		
	//********************************************************************************************************************************************************
	//**  OBJECT FUNCTIONS GETTIMECOLUMNS
	//*** Returns column-names of corresponding times.
	//********************************************************************************************************************************************************

		this.getStartHourColumn = function() {
			return this.startHourColumn;
		};
	
		this.getStartMinuteColumn = function() {
			return this.startMinuteColumn;
		};
	
		this.getEndHourColumn = function() {
			return this.endHourColumn;
		};
	
		this.getEndMinuteColumn = function() {
			return this.endMinuteColumn;
		};
	
	//********************************************************************************************************************************************************
	//**  OBJECT FUNCTION FINALIZE(DATAOBJECTS)
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
		
			var validationFunction = this.validatationFunction;
			
			if (validationFunction!=null) {
				return window[validationFunction](this);
			}
			
			return false;
		};
																																							
	//********************************************************************************************************************************************************
	//**  OBJECT FUNCTION UPDATETODATABASE()
	//*** Updates value to database.
	//********************************************************************************************************************************************************
		
		this.updateToDatabase = function() {
			
			var updateAction 		= this.updateAction;
			var timeObject	 		= this.getValue();
			var rowIdentifierObject	= this.rowIdentifierObject;
			var rowID				= rowIdentifierObject.getValue();
			
			var parameters 	 = '&id='+rowID;
				parameters 	 = parameters + '&starthour='+timeObject.starthours;
				parameters	 = parameters + '&startminute='+timeObject.startminutes;
				parameters	 = parameters + '&endhour='+timeObject.endhours;
				parameters	 = parameters + '&endminute='+timeObject.endminutes;
			
				//alert(updateAction+parameters);
				
			$.getJSON(updateAction, parameters, function() {
					
			})																														
			.done(function(reply) {																																																									
			});	
			
		};
		
	//********************************************************************************************************************************************************
	//**  OBJECT FUNCTION CREATEEVENTLISTENERS()
	//*** Creates associated eventlisteners.
	//********************************************************************************************************************************************************
		
		this.createEventListeners = function() {
			
			var sourceElement = this.getDomElement();
			var sourceObject  = this;
			
			sourceElement.addEventListener('click',function(event) {																		
			
				window[calculationAlgorithm](dataRows);
				sourceObject.updateToDatabase();
				
			},true);
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
	//*** Returns class-name 'TimeSelectColumn'.
	//********************************************************************************************************************************************************
		
		this.getClass = function() {																											
			return 'TimeSelectColumn';																											
		};																																		
							
	//********************************************************************************************************************************************************
	//*** OBJECT FUNCTION CREATETIMESELECTELEMENT()
	//*** Creates DOM-element for TimeSelectColumn-class.
	//********************************************************************************************************************************************************
																																							
		this.createTimeSelectElement = function(column) {																								
																																						
			var selectTable		  = document.createElement('table'); 																			
			var row				  = selectTable.insertRow();																					
			var cell		      = null;																										
																																						
			var dropdownStartHours   = document.createElement('select');																		
			var dropdownStartMinutes = document.createElement('select');																		
			var dropdownEndHours 	 = document.createElement('select');																		
			var dropdownEndMinutes   = document.createElement('select');																		
																																						
			this.createOptionHours(dropdownStartHours);																								
			this.createOptionMinutes(dropdownStartMinutes);																							
			this.createOptionHours(dropdownEndHours);																								
			this.createOptionMinutes(dropdownEndMinutes);																							
																																						
			cell = row.insertCell();																											
			cell.appendChild(dropdownStartHours);																								
																																						
			cell = row.insertCell();																											
			cell.appendChild(dropdownStartMinutes);																								
																																						
			cell = row.insertCell();																											
			cell.appendChild(dropdownEndHours);																									
																																						
			cell = row.insertCell();																											
			cell.appendChild(dropdownEndMinutes);																								
																																						
			return selectTable;																													
																																						
		};
		
	//********************************************************************************************************************************************************
	//*** OBJECT FUNCTION CREATEOPTIONHOURS(HOURSELECT)
	//*** Creates Option-elements for hours.
	//********************************************************************************************************************************************************
					
		this.createOptionHours = function(hourSelect) {																								
																																								
			var hourOption;																														
			var hourString;																														
			var stringLen;																														
																																								
			for(hour=0;hour<24;hour++) {																										
																																								
				hourString 		= hour.toString();																								
				stringLen		= hourString.length;																							
																																								
				if (stringLen==1) hourString = '0' + hourString;																				
																																								
					hourOption 		= document.createElement('option');																				
					hourOption.text = hourString;																									
					hourOption.value = hour;																										
																																								
					hourSelect.add(hourOption);																										
																																								
				}																																	
		};																																		
																																								
	//********************************************************************************************************************************************************
	//*** FUNCTION CREATEOPTIONMINUTES(MINUTESELECT)
	//*** Creates Option-elements for minutes.
	//********************************************************************************************************************************************************
																																																																						
		this.createOptionMinutes = function(minuteSelect) {																							
																																								
			var minuteOption;																													
			var minuteString;																													
			var stringLen;																														
																																								
			for(minute=0;minute<60;minute++) {																									
																																								
				minuteString 		= minute.toString();																						
				stringLen			= minuteString.length;																						
																																								
				if (stringLen==1) minuteString = '0' + minuteString;																			
																																								
				minuteOption 	  	= document.createElement('option');																			
				minuteOption.text  	= minuteString;																								
				minuteOption.value 	= minute;																								
																																								
				minuteSelect.add(minuteOption);																									
																																								
			}																																	
		};	
	}
	

//********************************************************************************************************************************************************
//*** OBJECT TIME(STARTHOURS,STARTMINUTES,ENDHOURS,ENDMINUTES);
//*** Time-Object.
//********************************************************************************************************************************************************
			
	function Time(starthours,startminutes,endhours,endminutes) {
		
		this.starthours 	= starthours;
		this.startminutes 	= startminutes;
		this.endhours 		= endhours;
		this.endminutes 	= endminutes;
		
		this.toString = function() {
			
			var timeString = this.starthours + ':' + this.startminutes + '-' + this.endhours + ':' + this.endminutes;
			
			return timeString;
		};
		
	}
												