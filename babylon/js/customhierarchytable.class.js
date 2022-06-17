//******************************************************************************************************************
//** FUNCTION CHANGECHILDVISIBILITYTD(EVENT)
//** Hides leaves.
//******************************************************************************************************************

function changeChildVisibilityTD(event) {
	
	event.stopPropagation();
	
	var target = event.target;
	element	   = target.parentNode.parentNode.parentNode.parentNode;
	
	var child = element.childNodes[1];
	
	var visibility = child.style.display;
	
	if (visibility=='none') {
		target.className = 'hierarchyOpenerOpen';
		$(child).show();
	}
	if (visibility!='none') {
		target.className = 'hierarchyOpenerClosed';
		$(child).hide();
	}
}

//******************************************************************************************************************
//** FUNCTION REDIRECTFROMHIERARCHYTABLE(EVENT,URLSTRING)
//** Redirects to.
//******************************************************************************************************************

function redirectFromhierarchyTable(event,urlString) {
	
	event.stopPropagation();
	
	window.location = urlString;
	
	
}

//******************************************************************************************************************
//** FUNCTION HIERARCHYITEMONMOUSEENTER(EVENT)
//** Class changes on mouseEnter (plain css:hover cannot be used since it bleeds to parents). 
//******************************************************************************************************************

function hierarchyItemOnMouseEnter(event) {
	
	event.stopPropagation();
	
	var target = event.target.parentNode;
	
	target.className = 'hierarchyEnclosingDivHover';
}

//******************************************************************************************************************
//** FUNCTION HIERARCHYITEMONMOUSELEAVE(EVENT)
//** Class changes on mouseLeave (plain css:hover cannot be used since it bleeds to parents). 
//******************************************************************************************************************

function hierarchyItemOnMouseLeave(event) {
	
	var target = event.target.parentNode;
	
	target.className = 'hierarchyEnclosingDiv';
	
}