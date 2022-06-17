



function encodespecialcharacters(str) {
	if (str == '') return str;
	var newstr = str.split('#').join('_H_');
	return newstr;
};
		