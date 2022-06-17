//**********************************************************************************************************************************************************
//**
//**	Validation functions
//**
//**	isValidInteger(validatedObject)
//**	isValidNumber(validatedObject)
//**	numberIsGreaterThanZero(validatedObject)
//**	numberIsGreaterThanOrEqualToZero(validatedObject)
//**	numberIsSmallerThanZero(validatedObject)
//**	numberIsSmallerThanOrEqualToZero(validatedObject)
//**	selectedValueNotZero(validateObject)
//**
//**
//**
//**
//**
//**
//**
//**
//**
//**
//**
//**
//**********************************************************************************************************************************************************


//**********************************************************************************************************************************************************
//** FUNCTION ISVALIDINTEGER(VALIDATEOBJECT);
//** Checks if value contained in object is an integer.
//**********************************************************************************************************************************************************

function isValidInteger(validatedObject) {
	
	var domElement = validatedObject.getDomElement();
	var value 	   = validatedObject.getValue();
	
	var regInteger = /^-?[0-9]+$/;

	return regInteger.test(value);
		
}

//**********************************************************************************************************************************************************
//** FUNCTION ISVALIDNUMBER(VALIDATEOBJECT);
//** Checks if value contained in object is a number.
//**********************************************************************************************************************************************************

function isValidNumber(validatedObject) {
	
	var domElement = validatedObject.getDomElement();
	var value 	   = validatedObject.getValue();
	
	if ((isNaN(value)) || (value=='')) return false;
	
	return true;
	
}

//**********************************************************************************************************************************************************
//** FUNCTION ISVALUEVALIDNUMBER(VALUE);
//** Checks if value is a number.
//**********************************************************************************************************************************************************

function isValueValidNumber(value) {
	
	if ((isNaN(value)) || (value=='')) return false;
	
	return true;
	
}

//**********************************************************************************************************************************************************
//** FUNCTION NUMBERISGREATERTHANZERO(VALIDATEOBJECT);
//** Checks if value contained in object is a number and greater than zero.
//**********************************************************************************************************************************************************

function numberIsGreaterThanZero(validatedObject) {
	
	var domElement = validatedObject.getDomElement();
	var value 	   = validatedObject.getValue();
	
	if ((isNaN(value)) || (value=='')) return false;
	if (value<=0) return false;
	
	return true;
	
}

//**********************************************************************************************************************************************************
//** FUNCTION NUMBERISGREATERTHANOREQUALTOZERO(VALIDATEOBJECT);
//** Checks if value contained in object is a number and greater than or equal to zero.
//**********************************************************************************************************************************************************

function numberIsGreaterThanOrEqualToZero(validatedObject) {
	
	var domElement = validatedObject.getDomElement();
	var value 	   = validatedObject.getValue();
	
	if ((isNaN(value)) || (value=='')) return false;
	if (value<0) return false;
	
	return true;
	
}

//**********************************************************************************************************************************************************
//** FUNCTION NUMBERISSMALLERTHANZERO(VALIDATEOBJECT);
//** Checks if value contained in object is a number and smaller than zero.
//**********************************************************************************************************************************************************

function numberIsSmallerThanZero(validatedObject) {
	
	var domElement = validatedObject.getDomElement();
	var value 	   = validatedObject.getValue();
	
	if ((isNaN(value)) || (value=='')) return false;
	if (value>=0) return false;
	
	return true;
	
}

//**********************************************************************************************************************************************************
//** FUNCTION NUMBERISMALLERTHANOREQUALTOZERO(VALIDATEOBJECT);
//** Checks if value contained in object is a number and smaller than or equal to zero.
//**********************************************************************************************************************************************************

function numberIsSmallerThanOrEqualToZero(validatedObject) {
	
	var domElement = validatedObject.getDomElement();
	var value 	   = validatedObject.getValue();
	
	if ((isNaN(value)) || (value=='')) return false;
	if (value>0) return false;
	
	return true;
	
}

//**********************************************************************************************************************************************************
//** FUNCTION SELECTEDVALUENOTZERO(VALIDATEOBJECT);
//** Checks if value contained in object is not zero.
//**********************************************************************************************************************************************************

function selectedValueNotZero(validateObject) {
	
	var value = validateObject.getValue();
	
	if (value==0) return false;
	
	return true;
	
}

