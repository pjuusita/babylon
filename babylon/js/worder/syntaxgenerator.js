

var GENERATE_START = 1;
var GENERATE_FINISHED = 2;
var GENERATE_APPLYRULE = 3;
var GENERATE_CHECKRULE = 4;
var GENERATE_REVERSERULE = 5;
var GENERATE_RESULTFOUND = 6;


function _generateCallback(success, data) {
	if (success == 0) {
		console.log('erroreita 0')
		window.generator.drawgenerateresults();
	} else {
		console.log('erroreita 1');
		console.log(data.message);
		window.generator.generateResults.pop();
		window.generator.drawgenerateresults();
	}
}

	
function SyntaxGenerator(mainElement, selectedRules, width, height) {

	
	this.stepCounter = 0;
	this.currentFS = null;
	this.loadedStructures = [];
	this.originFS = null;
	this.rootFS = null;
	
	
	this.rules = null;
	this.ruleIndex = 0;				// Tämä ilmaisee mitä rulea ollaan käymässä lävitse, rulelistin > merkin sijainti

	//this.pathStack = [];
	//this.childIndexStack = [];		// Tällä tsekataan missä lapsinumerossa ollaan
	
	this.nodeStack = [];
	
	this.generateResults = [];
	this.generateResultStrings = [];

	
	this.width = width;
	this.height = height;
	this.rulesTableElement = null;
	this.context = null;
	this.canvasName = null;
	
	this.rulesTableElement = null;
	this.rulesTableElementContainer = null;	
	this.generateResultTableElement = null;
	this.pauseOnReverseRule = true;
	this.pauseOnCheckingRule = true;
	this.pauseOnApplyRule = true;
	this.pauseOnResultFound = true;
	this.pauseOnStart = true;
	this.ruleSectionID = 0;
	
	this.languageID = 0;
	
	
	this.initialize = function(mainElement, rules, width, height) {
		var canvas = document.getElementById(mainElement);
		var ctx = canvas.getContext('2d');
		ctx.fillStyle = '#fff';
		ctx.fillRect(0,0,width,height);
		var fontHeight = 20;
		ctx.fillStyle = '#000';
		ctx.font = '20px Arial';
		
		this.context = ctx;
		this.width = width;
		this.height = height;
		this.rules = rules;
		this.canvasName = mainElement;
		this.loadedStructures = [];
	}
	this.initialize(mainElement, selectedRules, width, height);

	
	this.setLanguageID = function(langID) {
		this.languageID = langID;
	}
	
	this.setRuleSectionID = function(sectionID) {
		//console.log('setting rulesectionID-'+sectionID);
		this.ruleSectionID = sectionID;
	}
	
	this.setConceptStructure = function(fs) {
		this.currentFS = fs;
		this.rootFS = fs;
	}
	

	this.setRulesTableElement = function(elementname, elementcontainer) {
		this.rulesTableElement = elementname;
		this.rulesTableElementContainer = elementcontainer;
	}
	
	
	
	this.setPauseOnCheckingRule = function(boole) {
		this.pauseOnCheckingRule = boole;
	}
	
	this.setPauseOnApplyRule = function(boole) {
		this.pauseOnApplyRule = boole;
	}
	
	this.setPauseOnReverseRule = function(boole) {
		this.pauseOnReverseRule = boole;
	}
	
	this.setPauseOnResultFound = function(boole) {
		this.pauseOnResultFound = boole;
	}
	

	this.setPauseOnStart = function(boole) {
		this.pauseOnStart = boole;
	}
	

	this.setGenerateResultTableElement = function(elementname) {
		this.generateResultTableElement = elementname;
	}
	


	
	this.drawgenerateresults = function() {
		//console.log('drawgenerateresults...');
		$('#'+this.generateResultTableElement).empty();
		for (var index in this.generateResults) {
			var fs = this.generateResults[index];
			var str = this.generateResultStrings[index];
			var tr = document.createElement('tr');
			var td = document.createElement('td');
			td.style.paddingLeft = '15px';
			td.style.width = '160px';
			td.style.maxWidth = '160px';
			td.style.textOverflow = 'hidden';
			td.style.overflow = 'hidden';
			td.style.whiteSpace = 'nowrap';
			//td.className += ' myclass';
			td.innerHTML = str+'-'+index;
			td.title =  str+'-'+index;
			td.setAttribute('onclick','drawFoundGenerate('+index+','+this.canvasName+','+this.width+','+this.height+')');
			//td.setAttribute('onclick','drawFound('+index+')');
			tr.append(td);
			$('#'+this.generateResultTableElement).append(tr);
		}
	}
	
	
	
	
	this.isGenerateComplete = function(fs) {
		//console.log('tsekataan onko generointi valmis');
		var counter = 0;
		for (var argument in fs.arguments) {
			counter++;
		}
		//console.log('arguments.length - '+counter);
		//if (counter == 0) return true;
		return false;
	}
	
	
	this.updateScreen = function() {
		var fontHeight = 20;
		var ctx = this.context;
		this.drawGenerate(ctx,0,0, fontHeight);
		this.drawgenerateresults();
	}

	
	this.startGenerate = function() {
		
		var counter = 0;
		this.originFS = this.currentFS;
		
		this.nodeStack = [];				// läpiäkäymättömät rulet, ei sisällä currenttia
		this.nodeStack.push(this.currentFS);
		this.nodeIndex = 0;
		
		this.ruleIndexStack = [];		// tänne taulukkoon tallennetaan missä indexissä ollaan menossa
		this.ruleIndexStack.push(0);
		this.termCountStack = [];
		this.ruleParentStack = [];
		
		this.generateResults = [];
		this.updateScreen();
		this.ruleIndex = 0;
		this.state = GENERATE_CHECKRULE;
		
		this.stepCounter = 0;
		addConsoleMessage('Start generate');
		this.reDrawRules(this.ruleIndex);
	}
	
	

	this.reDrawRules = function(ruleIndex) {
		$('#'+this.rulesTableElement).empty();
		for (var index in this.rules) {
			var rule = this.rules[index];
			if (index == ruleIndex) {
				this.addGenerateRule(index,rule,1);
			} else {
				this.addGenerateRule(index,rule,0);
			}
		}
		$('#'+this.rulesTableElementContainer).scrollTop(ruleIndex*19-50);
	}
	
	
	this.addGenerateRule = function(index, rule, active) {
		this.rules[index] = rule;
		var tr = document.createElement('tr');
		td = document.createElement('td');
		td.style.width = '10px';
		td.style.maxWidth = '10px';
		td.style.minWidth = '10px';
		td.style.textOverflow = 'hidden';
		td.style.overflow = 'hidden';
		td.style.whiteSpace = 'nowrap';
		td.id = 'ruleactive-'+index;
		if (active == 0) {
			td.innerHTML = ' ';
		} else {
			td.innerHTML = '>';
			td.className += 'wselected';
		}
		tr.append(td);
	
		var td = document.createElement('td');
		if (active == 1) {
			td.className += 'wselected';
		}
		td.style.width = '160px';
		td.style.maxWidth = '160px';
		td.style.textOverflow = 'hidden';
		td.style.overflow = 'hidden';
		td.style.whiteSpace = 'nowrap';
		td.innerHTML = rule.name;
		var rSectionID = this.ruleSectionID;
		td.onclick = function() {
			//console.log('show rule - '+index);
			//console.log('ruleSectionID - '+ rSectionID);
			showRule(rule,'rulecanvas', rSectionID);
		};
		td.id = 'rulename-'+index;
		if (active == 0) {
			td.className += ' myclass';
		} else {
			td.className += ' myclass';
		}
		tr.append(td);
		$('#'+this.rulesTableElement).append(tr);
	}
	
	
	this.nextStep = function() {
		
		var comments = false;
		//console.log('------------------------------ '+this.stepCounter);
		this.stepCounter++;
		if (this.stepCounter > 1000) {
			console.log('maype loop, stepcounter MAX');
		}
		var oldstate = this.state;
		this.privateNextStep();
		//this.updateScreen();
		var pause = true;

		if (oldstate == GENERATE_CHECKRULE) {
			if (this.pauseOnCheckingRule == true) {
				console.log(this.stepCounter+' GENERATE_CHECKRULE - pause');
			} else {
				console.log('GENERATE_CHECKRULE - no pause');
				pause = false;
			}
		}

		if (oldstate == GENERATE_FINISHED) {
			return;
		}
	
		if (oldstate == GENERATE_REVERSERULE) {
			if (this.pauseOnReverseRule == true) {
				console.log('GENERATE_REVERSERULE - pause');
			} else {
				console.log('GENERATE_REVERSERULE - no pause');
				pause = false;
			}
		}

		if (oldstate == GENERATE_START) {
			if (this.pauseOnStart == true) {
				console.log('GENERATE_START - pause 1');
			} else {
				console.log('GENERATE_START - no pause');
				pause = false;
			}
		}

		if (oldstate == GENERATE_APPLYRULE) {
			if (this.pauseOnApplyRule == true) {
				console.log('GENERATE_APPLYRULE - pause');
			} else {
				console.log('GENERATE_APPLYRULE - no pause');
				pause = false;
			}
		}
	
		if (oldstate == GENERATE_RESULTFOUND) {
			if (this.pauseOnResultFound == true) {
				console.log('GENERATE_RESULTFOUND - pause');
			} else {
				console.log('GENERATE_RESULTFOUND - no pause');
				pause = false;
			}
		}
	
		if (pause == true) {
			this.updateScreen();
			this.reDrawRules(this.ruleIndex);

			if (comments == true) {
				console.log('------ pause --------');
				console.log('currentfs - '+this.currentFS.name+'-'+this.currentFS.ID);
				console.log('nodeIndex - '+this.nodeIndex);
				console.log('ruleIndex - '+this.ruleIndex);
				console.log('nodestack:');
				console.dir(this.nodeStack);
				console.log('termCountStack');
				console.dir(this.termCountStack);
				console.log('ruleIndexStack:');
				console.dir(this.ruleIndexStack);
				console.log('ruleParentStack:');
				console.dir(this.ruleParentStack);
			}
			
		} else {
			// echo " this.wait(1000);
			if (comments) console.log('apply next step');
			this.nextStep();
			// echo " console.log('settimeout');
			// echo " setTimeout(generatenextstep, 1000);
			return;
		}
	}
	

	
	this.privateNextStep = function() {
		
		var comments = false;
		this.reDrawRules(this.ruleIndex);
		console.log('--------------------');
		
		//if (this.state == GENERATE_CHECKRULE) console.log('-- GENERATE_CHECKRULE '+this.stepCounter);
		//if (this.state == GENERATE_START) console.log('-- GENERATE_START '+this.stepCounter);
		//if (this.state == GENERATE_APPLYRULE) console.log('-- GENERATE_APPLYRULE '+this.stepCounter);
		//if (this.state == GENERATE_RESULTFOUND) console.log('-- GENERATE_RESULTFOUND '+this.stepCounter);
		//if (this.state == GENERATE_REVERSERULE) console.log('-- GENERATE_REVERSERULE '+this.stepCounter);
		//if (this.state == GENERATE_FINISHED) console.log('-- GENERATE_FINISHED '+this.stepCounter);
		
		
		if (this.state == GENERATE_START) {
			//this.currentindex = 0;
			this.ruleIndex = 0;
			this.state = GENERATE_CHECKRULE;
			return;
		}
	
		if (this.state == GENERATE_APPLYRULE) {
			
			console.log('applyrule - nodeIndex'+this.nodeIndex);
			
			rule.applyGenerateRule(this.currentFS);
			
			var childCount = this.currentFS.childCount();
			if (childCount == 0) {
				console.log(' - no applyrule childs');
				return;
			}
			for(index = 0;index<childCount;index++) {
				var childFS = this.currentFS.getChild(index);
				this.nodeStack.push(childFS);
				this.ruleIndexStack.push(0);
			}
			
			this.termCountStack.push(childCount);
			this.ruleParentStack.push(this.currentFS);
			
			this.ruleIndexStack[this.nodeIndex] = this.ruleIndex;
			this.nodeIndex++;
			this.currentFS = this.nodeStack[this.nodeIndex];
			
			this.ruleIndex = 0;
			this.state = GENERATE_CHECKRULE;
			return;
		}
	
		
		if (this.state == GENERATE_CHECKRULE) {	// tsekataan mitkä rulet ovat yhteensopivia
			
			if (this.ruleIndex >= this.rules.length) {
				if (comments) console.log(' - ruleindex over...');
				
				if (this.nodeIndex == this.nodeStack.length-1) {	// ollaan viimeisessä nodeindeksissä
					if (this.currentFS.getArgumentCount() == 0) {
						if (comments) console.log(' - all rules and all nodes checked - generate result...');
						this.state = GENERATE_RESULTFOUND;
						return;
					} else {
						if (comments) console.log(' - argument not processed reverse ...');
						this.state = GENERATE_REVERSERULE;
						return;
					}
				} else {
					if (this.currentFS.getArgumentCount() == 0) {
						if (comments) console.log(' - all arguments ok, next ...');
						this.nodeIndex++;
						this.currentFS = this.nodeStack[this.nodeIndex];
						this.ruleIndex = 0;
						this.state = GENERATE_CHECKRULE;
						return;
					} else {
						if (comments) console.log(' - argument not processed reverse ...');
						this.state = GENERATE_REVERSERULE;
						return;
					}
					
				}
				
				if (comments) console.log(' - not here 439 ...');
				return;		// ei pitäisi tulla tänne
			}
			
			rule = this.rules[this.ruleIndex];
			var compatible = rule.isGenerateCompatible(this.currentFS);
			if (compatible == true) {
				console.log(' ---- rule compatible')
				this.state = GENERATE_APPLYRULE;
				return;
			} else {
				console.log(' ---- not compatible')
				this.ruleIndex++;
				return;
			}
		}
		
		
		if (this.state == GENERATE_RESULTFOUND) {
			addConsoleMessage('Generate result found.');
			console.log('generate result found...');
			var resultFS = this.rootFS.getRecursiveCopy();
			this.processResult(resultFS);
			this.state = GENERATE_REVERSERULE;
			return;
		}

		
		if (this.state == GENERATE_FINISHED) {
			addConsoleMessage('Generate finished.');
			console.log('finished...');
			return;
		}

		if (this.state == GENERATE_REVERSERULE) {
			
			// currentFS on käyty kaikkien rulejen osalta lävitse, pitää peruuttaa edellinen rule
			var currentExists = true;
			var parentFS = null;
			var termCount = 0;
			
			while (currentExists == true) {
				
				termCount = this.termCountStack.pop();
				
				parentFS = this.ruleParentStack.pop();
				if (parentFS == null) {
					if (comments) console.log('parentFS is null');
					console.log('finished.');
					this.state = GENERATE_FINISHED;
					return;
				}
				this.ruleIndex = this.ruleIndexStack[this.nodeIndex];
				this.nodeIndex--;
				if (comments) console.log(' - removeparentchilds - '+parentFS.removeAllChilds());
				parentFS.removeAllChilds();
				
				if (termCount == 2) {
					if (comments) console.log(' - rule two terms');
					var removeFS = this.nodeStack.pop();	// eli poistetaan toinenkin termi, edellinen on currentFS:ssä..
					this.ruleIndexStack.pop();
					removeFS.removeAllChilds();
					removeFS = this.nodeStack.pop();
					this.ruleIndexStack.pop();
					removeFS.removeAllChilds();
				} else {
					if (termCount == 1) {
						if (comments) console.log(' - rule one term');
						var removeFS = this.nodeStack.pop();
						if (comments) console.log(' - removechilds - '+removeFS.ID);
						removeFS.removeAllChilds();
						this.ruleIndexStack.pop();
					} else {
						if (comments) console.log('undefined termcount...');
						return;
					}
				}
				
				if (this.nodeIndex > this.nodeStack.length-1) {
					this.nodeIndex = this.nodeStack.length-1;
					if (comments) console.log(' - new nodeIndex - '+this.nodeIndex);
					this.ruleIndex = this.ruleIndexStack[this.nodeIndex];
					if (comments) console.log(' - new ruleIndex - '+this.ruleIndex);
				}
				
				currentExists = false;
				
				for(var index3 = 0;index3 < this.nodeStack.length;index3++) {
					var tempNode = this.nodeStack[index3];
					if (comments) console.log(' - test - '+tempNode.ID+', '+this.currentFS.ID);
					if (tempNode.ID == this.currentFS.ID) {
						if (comments) console.log(' - current - '+this.currentFS.ID+', feature - '+tempNode.ID);
						this.nodeIndex = index3;
						currentExists = true;
						break;
					}
				}
				
				if (currentExists == false) {
					if (comments) console.log('currentexists = false');
				} else {
					if (comments) console.log('currentexists = true');
				}
			}
			
			this.currentFS = parentFS;
			if (comments) console.log(' - xx nodeIndex - '+this.nodeIndex);
			if (comments) console.log(' - xx ruleIndex - '+this.ruleIndex);
			for(var index3 = 0;index3 < this.nodeStack.length;index3++) {
				var tempNode = this.nodeStack[index3];
				if (comments) console.log(' - test - '+tempNode.ID+', '+this.currentFS.ID);
				if (tempNode.ID == this.currentFS.ID) {
					if (comments) console.log(' - current - '+this.currentFS.ID+', feature - '+tempNode.ID);
					this.nodeIndex = index3;
					break;
				}
			}
			this.ruleIndex = this.ruleIndexStack[this.nodeIndex];
			this.ruleIndex++;
			
			
			
			
			this.state = GENERATE_CHECKRULE;
			return;
		}
	};
	
	
	this.processResult = function(fs) {
		//console.log('-------- result');
		var leafArray = Array();
		this.processResultRecursive(fs, leafArray);
		//console.log('-------- result end');
		//console.log('-- leafarray count - '+leafArray.length);
		var str = '';
		var first = false;
		for(leafIndex in leafArray) {
			leaf = leafArray[leafIndex];
			//console.log('leafIndex = '+leafIndex);
			//console.dir(leaf);
			//console.log('leaf = '+leaf.conceptID);
			
			if (first == false) {
				str += leaf.conceptID;
				first = true;
			} else {
				str += "|" + leaf.conceptID;
			}
			if (leaf.features != null) {
				leaf.features.forEach((value, feature) => {
					//console.log(' -- features = '+feature+'-'+value);
					str += ',' + feature + ':' + value;
				})
			}
			/*
			for(var feature in leaf.features) {
				var value = leaf.features[feature];
				//console.log('value = '+value);
				//console.dir(value);
				//console.log('-- feature - '+feature+'-'+value);
				str += ',' + feature + ':' + value;
			}
			*/
		}
		//console.log(''+str);
		
		//console.log(getUrl('worder/translate/fetchwordformsJSON') + '&languageID=1');
		this.generateResults.push(fs);
		var tempresults = this.generateResultStrings;
		
		var url = 'https://www.babelsoft.fi/demo/' + getUrl('worder/translate/fetchwordformsJSON') + '&languageID='+this.languageID+'&list=' + str;
		console.log(url);
		$.getJSON('index.php?rt=worder/translate/fetchwordformsJSON&languageID='+this.languageID+'&list=' + str,'',function(data) {
			//$.getJSON(url,'',function(data) {
			//console.log('fetch leaves ok');
			//generateResults[data.result] = fs;
			if (data.error == 1) {
				_generateCallback(1, data);
			} else {
				//console.log('fetch leaves ok - generate callback');
				tempresults.push(data.result);
				//console.dir(tempresults);
				//console.log(data.result);
				_generateCallback(0, data);
			}
		});
		//console.log('-------- result end');
	};
	
	
	this.processResultRecursive = function(fs, leafArray) {

		//console.log('recursive process - '+fs.name+'-'+fs.ID);
		
		// pitää loopata kaikki lehdet lävitse, ja hakea kyseinen sana backendistä... saattaa palauttaa useampiakin?
		if (fs.childs == null) {
			//console.log('fs on null **********');
			//console.log('leafnode - '+fs.name+'-'+fs.ID);
			//for(var feature in fs.features) {
				//var value = fs.features[feature];
				//console.log('-- feature - '+feature+'-'+value);
			//}
			leafArray.push(fs);
			//console.log('-- leafarray count - '+leafArray.length);
			
			//return leafArray;
		}
		if (fs.childs.length == 0) {
			//console.log('leafnode - '+fs.name+'-'+fs.ID);
			//for(var feature in fs.features) {
			//	var value = fs.features[feature];
				//console.log('-- feature - '+feature+'-'+value);
			//}
			leafArray.push(fs);
			//console.log('-- leafarray count - '+leafArray.length);
			//return leafArray;
		} else {
			for(var child in fs.childs) {
				childvalue = fs.childs[child];
				this.processResultRecursive(childvalue,leafArray);
			}
		}
	};
	
	
	// TODO: fillRect width ja height pitäisi tulla php:ltä
	this.drawGenerate = function(context, xpos, y, fontHeight) {
		//console.log('analyse.drawGenerate...');
		context.fillStyle = '#fff';
		context.fillRect(0,0,this.width,this.height);
		context.fillStyle = '#000';
		var fs = this.rootFS;
		//console.log('drawGenerate - '+this.currentFS.ID);
		fs.drawRecursive(context, xpos, y, fontHeight, 'blue', this.currentFS.ID);
	};
}

