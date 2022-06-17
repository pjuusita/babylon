

function loadRule(url, ruleID, targetElement) {

	var comments = false;
	if (comments) console.log('loadrule - '+ruleID);
	console.log('load rule - '+url+'&json=1&lang=1&ruleID='+ruleID);
	
	$.getJSON(url+'&json=1&lang=1&ruleID='+ruleID,function(data) {

		console.log('load success');
		var name = data['name'];
		var wordclassID = data['wordclassID'];
		var index = data['index'];
		
		if (comments) console.log('creating - '+name+','+wordclass);
		rule = new Rule(name,parseInt(wordclassID),parseInt(index));

		var conceptID = data['conceptID'];
		var conceptName = data['conceptName'];
		console.log('jeejeexx -'+conceptID+'-'+conceptName);
		rule.setConcept(conceptID, conceptName);
		
		if (comments) console.log('------');
		$.each(data['terms'], function(index) {
			var term = data['terms'][index];
			if (comments) console.log('term position - '+term['position']);
			if (comments) console.log('term argumentID - '+term['argumentID']);
			if (comments) console.log('term wordclassID - '+term['wordclassID']);
			if (comments) console.log('term index - '+term['index']);
			if (comments) console.log('term argumenstallowed - '+term['argumentallowed']);
			if (comments) console.log('term conceptID - '+term['conceptID']);
			if (comments) console.log('term concept - '+term['concept']);
			if (comments) console.log('------');
			rule.addTerm(parseInt(term['position']),parseInt(term['argumentID']),parseInt(term['wordclassID']),parseInt(term['index']),parseInt(term['argumentallowed']),parseInt(term['conceptID']),term['concept']);
		});

		if (comments) console.log('------');
		$.each(data['agreements'], function(index) {
			var agreement = data['agreements'][index];
			if (comments) console.log('agreement featureID - '+agreement['featureID']);
			//if (comments) console.log('agreement feature - '+agreement['feature']);
			if (comments) console.log('agreement position1 - '+agreement['position1']);
			if (comments) console.log('agreement position2 - '+agreement['position2']);
			if (comments) console.log('agreement index - '+agreement['index']);
			if (comments) console.log('------');
			rule.addAgreement(parseInt(agreement['featureID']),parseInt(agreement['position1']),parseInt(agreement['position2']),parseInt(agreement['index']));
		});

		if (comments) console.log('------');
		$.each(data['constraints'], function(index) {
			var constraints = data['constraints'][index];
			if (comments) console.log('constraints position - '+constraints['position']);
			if (comments) console.log('constraints featureID - '+constraints['featureID']);
			//if (comments) console.log('constraints feature - '+constraints['feature']);
			if (comments) console.log('constraints valueID - '+constraints['valueID']);
			//if (comments) console.log('constraints value - '+constraints['value']);
			if (comments) console.log('constraints operator - '+constraints['operator']);
			if (comments) console.log('------');
			rule.addConstraint(parseInt(constraints['position']),parseInt(constraints['featureID']),parseInt(constraints['valueID']),parseInt(constraints['operator']));
		});

		if (comments) console.log('------');
		$.each(data['components'], function(index) {
			var components = data['components'][index];
			if (comments) console.log('components position - '+components['position']);
			if (comments) console.log('components componentID - '+components['componentID']);
			//if (comments) console.log('components component - '+components['component']);
			if (comments) console.log('components presenceID - '+components['presenceID']);
			//if (comments) console.log('components presence - '+components['presence']);
			if (comments) console.log('------');
			rule.addComponent(parseInt(components['position']),parseInt(components['componentID']),parseInt(components['presenceID']));
		});

		if (comments) console.log('------');
		$.each(data['resultfeatures'], function(index) {
			var resultfeatures = data['resultfeatures'][index];
			if (comments) console.log('resultfeatures featureID - '+resultfeatures['featureID']);
			//if (comments) console.log('resultfeatures feature - '+resultfeatures['feature']);
			if (comments) console.log('resultfeatures valueID - '+resultfeatures['valueID']);
			//if (comments) console.log('resultfeatures value - '+resultfeatures['value']);
			if (comments) console.log('------');
			rule.addResultFeature(parseInt(resultfeatures['featureID']),parseInt(resultfeatures['valueID']));
		});

		if (comments) console.log('------ result positions');
		$.each(data['resultpositions'], function(index) {
			var resultposition = data['resultpositions'][index];
			if (comments) console.log('resultpositions featureID - '+resultposition['featureID']);
			//if (comments) console.log('resultpositions feature - '+resultposition['feature']);
			if (comments) console.log('resultpositions position - '+resultposition['position']);
			if (comments) console.log('resultpositions index - '+resultposition['index']);
			//if (comments) console.log('resultpositions sourcefeature - '+resultposition['sourcefeature']);
			if (comments) console.log('resultpositions sourcefeatureID - '+resultposition['sourcefeatureID']);
			if (comments) console.log('------');
			rule.addResultPosition(parseInt(resultposition['featureID']),parseInt(resultposition['sourcefeatureID']),parseInt(resultposition['position']),parseInt(resultposition['index']));
		});

		if (comments) console.log('------ unset arguments');
		$.each(data['unsetarguments'], function(index) {
			var unsetargument = data['unsetarguments'][index];
			if (comments) console.log('unsetarguments position - '+unsetargument['position']);
			if (comments) console.log('unsetarguments argumentID - '+unsetargument['argumentID']);
			//if (comments) console.log('unsetarguments argument - '+unsetargument['argument']);
			if (comments) console.log('------');
			rule.addUnsetArgument(parseInt(unsetargument['position']),parseInt(unsetargument['argumentID']));
			//rule.addUnsetArgument(unsetargument['position'], unsetargument['argumentID'], unsetargument['argument']);
		});

		if (comments) console.log('------ unset features');
		$.each(data['unsetfeatures'], function(index) {
			var unsetfeatures = data['unsetfeatures'][index];
			if (comments) console.log('unsetfeatures position - '+unsetfeatures['position']);
			if (comments) console.log('unsetfeatures featureID - '+unsetfeatures['featureID']);
			//if (comments) console.log('unsetfeatures feature - '+unsetfeatures['feature']);
			if (comments) console.log('------');
			rule.addUnsetFeature(parseInt(unsetfeatures['position']),parseInt(unsetfeatures['featureID']));
			//rule.addUnsetFeature(unsetfeatures['position'], unsetfeatures['featureID'], unsetfeatures['feature']);
		});

			
		var canvas = document.getElementById(targetElement);
		var ctx = canvas.getContext('2d');
		ctx.fillStyle = '#fff';
		ctx.fillRect(0,0,800,400);
		var fontHeight = 20;
		ctx.fillStyle = '#000';
		rule.drawRule(ctx,20,20, fontHeight);
	});
}


function showRule(rule, elementID, sectionID) {
	//console.log('showrule..');
	var canvas = document.getElementById(elementID);
	var ctx = canvas.getContext('2d');
	ctx.fillStyle = '#fff';
	ctx.fillRect(0,0,1000,400);
	var fontHeight = 20;
	ctx.fillStyle = '#000';
	rule.drawRule(ctx,20,20, fontHeight);

	//console.log('sectionid ...'+sectionID);
	if (sectionID > 0) {
		//console.log('opendialog');
		$('#sectiondialog-'+sectionID).dialog('open');
	}
}
	


function showFeatureStructure(fs, elementID, sectionID) {
	//console.log('showrule..');
	
	var canvas = document.getElementById(elementID);
	var ctx = canvas.getContext('2d');
	var fontHeight = 20;
	ctx.fillStyle = '#fff';
	ctx.fillRect(0,0,1000,400);
	ctx.fillStyle = '#000';
	fs.drawRecursive(ctx,0, 0, fontHeight, 'blue');
	
	/*
	var canvas = document.getElementById(elementID);
	var ctx = canvas.getContext('2d');
	ctx.fillStyle = '#fff';
	ctx.fillRect(0,0,1000,400);
	var fontHeight = 20;
	ctx.fillStyle = '#000';
	rule.drawRule(ctx,20,20, fontHeight);
	*/
	//console.log('sectionid ...'+sectionID);
	if (sectionID > 0) {
		//console.log('opendialog');
		$('#sectiondialog-'+sectionID).dialog('open');
	}
}
	

function drawFoundAnalyse(index, element, width, height) {
	//console.log('draw found - '+index);
	var fs = window.analyser.foundstack[index];
	window.analyser.setActiveFS(fs);
	var canvas = document.getElementById('analysecanvas');
	var ctx = canvas.getContext('2d');
	var fontHeight = 20;
	ctx.fillStyle = '#fff';
	ctx.fillRect(0,0,width,height);
	ctx.fillStyle = '#000';
	var semanticfs = fs.getCopy();
	semanticfs.drawFeatureStructureFull(ctx, 0, 0, fontHeight, 'blue');
}


function drawFoundGenerate(index, element, width, height) {
	//console.log('draw found - '+index);
	var fs = window.generator.generateResults[index];
	var canvas = document.getElementById('generatecanvas');
	var ctx = canvas.getContext('2d');
	var fontHeight = 20;
	ctx.fillStyle = '#fff';
	ctx.fillRect(0,0,width,height);
	ctx.fillStyle = '#000';
	fs.drawRecursive(ctx,0, 0, fontHeight, 'blue');
}



function parseRule(data) {

	var comments = false;
	var name = data['name'];
	var wordclass = data['wordclassID'];
	var index = data['index'];
	
	if (comments) console.log('creating - '+name+','+wordclass);
	rule = new Rule(name,parseInt(wordclass),parseInt(index));
	

	var conceptID = data['conceptID'];
	var conceptName = data['conceptName'];
	//console.log('jeejeexx3 -'+conceptID+'-'+conceptName);
	rule.setConcept(conceptID, conceptName);
	

	if (comments) console.log('------');
	$.each(data['arguments'], function(index) {
		var argument = data['arguments'][index];
		console.log('argument argumentID - '+argument['argumentID']);
		console.log('argument componentID - '+argument['componentID']);
		if (comments) console.log('------');
		rule.addConceptArgument(parseInt(argument['argumentID']),parseInt(argument['componentID']));
	});

	
	
	if (comments) console.log('------ terms');
	$.each(data['terms'], function(index) {
		var term = data['terms'][index];
		if (comments) console.log('term position - '+term['position']);
		if (comments) console.log('term argumentID - '+term['argumentID']);
		if (comments) console.log('term wordclassID - '+term['wordclassID']);
		if (comments) console.log('term index - '+term['index']);
		if (comments) console.log('term argumenstallowed - '+term['argumentallowed']);
		if (comments) console.log('term conceptID - '+term['conceptID']);
		if (comments) console.log('term concept - '+term['concept']);
		if (comments) console.log('------');
		rule.addTerm(parseInt(term['position']),parseInt(term['argumentID']),parseInt(term['wordclassID']),parseInt(term['index']),parseInt(term['argumentallowed']),parseInt(term['conceptID']),term['concept']);
	});

	if (comments) console.log('------ agreements');
	$.each(data['agreements'], function(index) {
		var agreement = data['agreements'][index];
		if (comments) console.log('agreement featureID - '+agreement['featureID']);
		//if (comments) console.log('agreement feature - '+agreement['feature']);
		if (comments) console.log('agreement position1 - '+agreement['position1']);
		if (comments) console.log('agreement position2 - '+agreement['position2']);
		if (comments) console.log('agreement index - '+agreement['index']);
		if (comments) console.log('------');
		rule.addAgreement(parseInt(agreement['featureID']),parseInt(agreement['position1']),parseInt(agreement['position2']),parseInt(agreement['index']));
	});

	if (comments) console.log('------ constraints');
	$.each(data['constraints'], function(index) {
		var constraints = data['constraints'][index];
		if (comments) console.log('constraints position - '+constraints['position']);
		if (comments) console.log('constraints featureID - '+constraints['featureID']);
		//if (comments) console.log('constraints feature - '+constraints['feature']);
		if (comments) console.log('constraints valueID - '+constraints['valueID']);
		//if (comments) console.log('constraints value - '+constraints['value']);
		if (comments) console.log('constraints operator - '+constraints['operator']);
		if (comments) console.log('------');
		rule.addConstraint(parseInt(constraints['position']),parseInt(constraints['featureID']),parseInt(constraints['valueID']),parseInt(constraints['operator']));
	});
		
	if (comments) console.log('------ components');
	$.each(data['components'], function(index) {
		var components = data['components'][index];
		if (comments) console.log('components position - '+components['position']);
		if (comments) console.log('components componentID - '+components['componentID']);
		//if (comments) console.log('components component - '+components['component']);
		if (comments) console.log('components presenceID - '+components['presenceID']);
		//if (comments) console.log('components presence - '+components['presence']);
		if (comments) console.log('------');
		rule.addComponent(parseInt(components['position']),parseInt(components['componentID']),parseInt(components['presenceID']));
	});
		
	if (comments) console.log('------ resultfeatures');
	$.each(data['resultfeatures'], function(index) {
		var resultfeatures = data['resultfeatures'][index];
		if (comments) console.log('resultfeatures featureID - '+resultfeatures['featureID']);
		//if (comments) console.log('resultfeatures feature - '+resultfeatures['feature']);
		if (comments) console.log('resultfeatures valueID - '+resultfeatures['valueID']);
		//if (comments) console.log('resultfeatures value - '+resultfeatures['value']);
		if (comments) console.log('------');
		rule.addResultFeature(parseInt(resultfeatures['featureID']),parseInt(resultfeatures['valueID']));
	});

	if (comments) console.log('------ result positions');
	$.each(data['resultpositions'], function(index) {
		var resultposition = data['resultpositions'][index];
		if (comments) console.log('resultpositions featureID - '+resultposition['featureID']);
		if (comments) console.log('resultpositions feature - '+resultposition['feature']);
		if (comments) console.log('resultpositions position2 - '+resultposition['position']);
		if (comments) console.log('resultpositions index - '+resultposition['index']);
		if (comments) console.log('resultpositions sourcefeature - '+resultposition['sourcefeatureID']);
		if (comments) console.log('------');
		rule.addResultPosition(parseInt(resultposition['featureID']),parseInt(resultposition['sourcefeatureID']),parseInt(resultposition['position']),parseInt(resultposition['index']));
	});

	if (comments) console.log('------ unset arguments');
	$.each(data['unsetarguments'], function(index) {
		var unsetargument = data['unsetarguments'][index];
		if (comments) console.log('unsetarguments position - '+unsetargument['position']);
		if (comments) console.log('unsetarguments argumentID - '+unsetargument['argumentID']);
		//if (comments) console.log('unsetarguments argument - '+unsetargument['argument']);
		if (comments) console.log('------');
		rule.addUnsetArgument(parseInt(unsetargument['position']), parseInt(unsetargument['argumentID']));
		//rule.addUnsetArgument(unsetargument['position'], unsetargument['argumentID'], unsetargument['argument']);
	});

	if (comments) console.log('------ unset features');
	$.each(data['unsetfeatures'], function(index) {
		var unsetfeatures = data['unsetfeatures'][index];
		if (comments) console.log('unsetfeatures position - '+unsetfeatures['position']);
		if (comments) console.log('unsetfeatures featureID - '+unsetfeatures['featureID']);
		//if (comments) console.log('unsetfeatures feature - '+unsetfeatures['feature']);
		if (comments) console.log('------');
		rule.addUnsetFeature(parseInt(unsetfeatures['position']), parseInt(unsetfeatures['featureID']));
		//rule.addUnsetFeature(unsetfeatures['position'], unsetfeatures['featureID'], unsetfeatures['feature']);
	});
	return rule;
}


function createFeatureStructureFromJSON(data) {
		
	var comments = false;
	if (comments) console.log('createFeatureStructureFromJSON...');
	var lemma = data['lemma'];
	if (comments) console.log('- lemma - '+lemma);
	var wordclassID = data['wordclassID'];
	if (comments) console.log('- wordclass - '+wordclass);
	var conceptID = data['conceptID'];
	var conceptName = data['conceptname'];
	
	//console.log('- create fs - '+conceptName);
	var wordID = data['wordID'];
	//console.log('- wordID - '+wordID);
	
	//var wordclassStr = wAbbs.get(parseInt(wordclass));
	var fs = new FeatureStructure(parseInt(wordclassID),lemma, parseInt(conceptID), conceptName);
	if (comments) console.log('first - '+wordclass+'P '+lemma);
	
	//		console.log('something loaded');
	$.each(data, function(index) {

		
		if (comments) console.log('data: '+index+'-'+ data[index]);

		if (index == 'wordclass') {
			if (comments) console.log('wordclass found - '+data[index]);
		}
		if (index == 'name') {
			if (comments) console.log('name found - '+data[index]);
		}
		if (index == 'arguments') {
			if (comments) console.log('arguments found');
			var counter = 0;
			$.each(data[index], function(argumentindex) {
				if (comments) console.log('argument index - '+argumentindex);
				
				var argument = data[index][argumentindex];
				if (comments) console.log('child value - '+argument.argumentname);
				if (comments) console.log('child value ID - '+argument.argumentID);
				if (comments) console.log('child value arg - '+argument.argumentvalue);
				if (comments) console.dir(argument.argumentvalue);
				var argumentfeature = createFeatureStructureFromJSON(argument.argumentvalue);
				if (comments) console.log('child value - '+argument.argumentname);
				if (comments) console.log('child value ID - '+argument.argumentID);
				if (comments) console.log('child value arg - '+argument.argumentvalue);
				fs.addArgument(argument.argumentID, argumentfeature);
				//if (comments) console.log('child value - '+childfeature.argumentname);
				counter++;
			});
			if (counter == 0) if (comments) console.log('no arguments');
		}
		if (index == 'features') {
			//console.log('features found');
			var counter = 0;
			$.each(data[index], function(featurename) {
				//console.log('----- feature name - '+featurename);
				//console.log('----- feature value - '+data[index][featurename]);
				fs.addFeature(parseInt(featurename),parseInt(data[index][featurename]));
				counter++;
			});
			//if (counter == 0) console.log('no features');
		}
		if (index == 'requirements') {
			//console.log('features found');
			var counter = 0;
			$.each(data[index], function(argumentname) {
				//console.log('-- -- required argument name - '+argumentname);
				//console.log('----- required argument value - '+data[index][argumentname]);
				
				$.each(data[index][argumentname], function(componentindex) {
					var component = data[index][argumentname][componentindex];
					//console.log('-- -- -- required component value - '+component);
					fs.addRequirement(parseInt(argumentname),parseInt(component));
					counter++;
				});
				//fs.addRequirement(parseInt(argumentname),parseInt(data[index][argumentname]));
				//counter++;
			});
			//if (counter == 0) console.log('no features');
		}
		if (index == 'components') {
			//console.log('features found');
			var counter = 0;
			$.each(data[index], function(componentindex) {
				//console.log('----- component - '+componentindex);
				//console.log('----- component value - '+data[index][componentindex]);
				fs.addComponent(parseInt(data[index][componentindex]));
				counter++;
			});
			//if (counter == 0) console.log('no features');
		}
	
	});
	return fs;
}


function loadFeatureStructure(url, formID, wordID, conceptID,callback) {

	console.log('loadfeature - '+formID+', '+wordID+', '+conceptID);
	$.getJSON(url+ '&json=1&lang=1&formID='+formID+'&wordID='+wordID+'&conceptID='+conceptID,'',function(data) {

		console.log('load success');
		var lemma = data['lemma'];
		var wordclassID = data['wordclassID'];
		var wordclassStr = wAbbs.get(parseInt(wordclassID));
		var fs = new FeatureStructure(parseInt(wordclassID),lemma, parseInt(conceptID));
		console.log('first - '+lemma+'-'+wordclass);
		$.each(data, function(index) {


			//console.log('data: '+index+'-'+ data[index]);
			if (index == 'wordclass') {
				//console.log('wordclass found - '+data[index]);
			}
			if (index == 'name') {
				//console.log('name found - '+data[index]);
			}
			if (index == 'arguments') {
				//console.log('arguments found');
				var counter = 0;
				$.each(data[index], function(argumentname) {
					//console.log('argument name - '+argumentname);
					//console.log('argument value - '+data[index][argumentname]);
					counter++;
				});
				if (counter == 0) console.log('no arguments');
			}

			if (index == 'requirements') {
				//console.log('requirements found');
				var counter = 0;
				$.each(data[index], function(requirementname) {
					//console.log('requirements name - '+requirementname);
					//console.log('requirements value - '+data[index][requirementname]);
					fs.addRequirement(parseInt(requirementname),parseInt(data[index][requirementname]));
					counter++;
				});
				if (counter == 0) if (comments) console.log('no requirements');
			}

			if (index == 'features') {
				//console.log('features found');
				var counter = 0;
				$.each(data[index], function(featurename) {
					console.log('feature name - '+featurename);
					console.log('feature value - '+data[index][featurename]);
					fs.addFeature(parseInt(featurename),parseInt(data[index][featurename]));
					counter++;
				});
				//if (counter == 0) console.log('no features');
			}
			if (index == 'components') {
				//console.log('components found');
				$.each(data[index], function(componentindex) {
					//console.log('component value - '+data[index][componentindex]);
					fs.addComponent(parseInt(data[index][componentindex]));
				});
			}
			callback(fs);
		});
	});
}

