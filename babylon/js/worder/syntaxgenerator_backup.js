

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
	this.ruleindex = 0;				// Tämä ilmaisee mitä rulea ollaan käymässä lävitse, rulelistin > merkin sijainti

	//this.pathStack = [];
	//this.childIndexStack = [];		// Tällä tsekataan missä lapsinumerossa ollaan
	
	this.nodeStack = [];
	this.nodeIndex = -1;
	
	this.ruleStack = [];
	
	this.nodeRuleIndexes = [];
	this.nodeRuleIndex = -1;
	
	this.ruleIndexStack = [];		// Tällä träkätään nykyhetken rulen läpikäyntiä...
	
	this.generateResults = [];
	this.generateResultStrings = [];
		
	
	//this.structurecount = 0;
	//this.rootarray = Array();		// Generaten tapauksessa tämän pitäisi periaatteessa sisältää eri concept structuret, jos kyse on käännöksestä
	//this.currentindex = 0;
	//this.sentenceindex = 0;
	
	//this.currentindexstack = [];
	//this.rulestack = [];
	//this.ruleindexstack = [];
	//this.activerule = null;
	
	//this.sentences = [];
	//this.foundstack = [];
	
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
		
		//console.log('startgenerate');
		var counter = 0;
		
		
		this.originFS = this.currentFS;
		
		//console.log('current concept');
		//console.dir(this.currentFS);
		
		
		
		
		
		//this.pathStack = [];
		this.nodeStack = [];
		this.nodeStack.push(this.currentFS);
		this.nodeIndex = 0;
		
		this.ruleStack = [];
		this.ruleIndexStack = [];
		
		//this.currentindex = -5;
	
		//this.rulestack = [];
		//this.ruleindexstack = [];
		//this.currentindexstack = [];
		//this.foundstack = [];
		this.generateResults = [];
	
		this.updateScreen();
		this.ruleindex = 0;
		this.state = GENERATE_CHECKRULE;
		
		this.stepCounter = 0;
		addConsoleMessage('Start generate');
		this.reDrawRules(this.ruleindex);
	}
	
	

	this.reDrawRules = function(ruleindex) {
		$('#'+this.rulesTableElement).empty();
		for (var index in this.rules) {
			var rule = this.rules[index];
			if (index == ruleindex) {
				this.addGenerateRule(index,rule,1);
			} else {
				this.addGenerateRule(index,rule,0);
			}
		}
		$('#'+this.rulesTableElementContainer).scrollTop(ruleindex*19-50);
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
				if (comments) console.log('-- ruleIndexStack - '+this.ruleindex);
				if (comments) console.dir(this.ruleIndexStack);
				if (comments) console.log('-- nodeStack - '+this.nodeIndex);
				if (comments) console.dir(this.nodeStack);
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
				if (comments) console.log('-- ruleIndexStack - '+this.ruleindex);
				if (comments) console.dir(this.ruleIndexStack);
				if (comments) console.log('-- nodeStack - '+this.nodeIndex);
				if (comments) console.dir(this.nodeStack);
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
				if (comments) console.log('-- ruleIndexStack - '+this.ruleindex);
				if (comments) console.dir(this.ruleIndexStack);
				if (comments) console.log('-- nodeStack - '+this.nodeIndex);
				if (comments) console.dir(this.nodeStack);
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
			this.reDrawRules(this.ruleindex);

		} else {
			// echo " this.wait(1000);
			if (comments) console.log('apply next step');
			this.nextStep();
			// echo " console.log('settimeout');
			// echo " setTimeout(generatenextstep, 1000);
			return;
		}
	}
	
	this.printState = function(){
		//console.log('currentFS - '+this.currentFS.name+'-'+this.currentFS.ID);
		console.log('ruleindex - '+this.ruleindex);
		console.log('nodeIndex: '+this.nodeIndex);
		console.log('nodeStack:');
		console.dir(this.nodeStack);
		console.log('ruleStack:');
		console.dir(this.ruleStack);
		console.log('ruleIndexStack:');
		console.dir(this.ruleIndexStack);
	};

	
	this.privateNextStep = function() {
		
		var comments = true;
		this.reDrawRules(this.ruleindex);
		console.log('--------------------');
		
		//if (this.state == GENERATE_CHECKRULE) console.log('-- GENERATE_CHECKRULE '+this.stepCounter);
		//if (this.state == GENERATE_START) console.log('-- GENERATE_START '+this.stepCounter);
		//if (this.state == GENERATE_APPLYRULE) console.log('-- GENERATE_APPLYRULE '+this.stepCounter);
		//if (this.state == GENERATE_RESULTFOUND) console.log('-- GENERATE_RESULTFOUND '+this.stepCounter);
		//if (this.state == GENERATE_REVERSERULE) console.log('-- GENERATE_REVERSERULE '+this.stepCounter);
		//if (this.state == GENERATE_FINISHED) console.log('-- GENERATE_FINISHED '+this.stepCounter);
		
		//console.log('currentfs - '+this.currentFS.name+'-'+this.currentFS.ID);

		if (this.state == GENERATE_START) {
			//this.currentindex = 0;
			this.ruleindex = 0;
			this.state = GENERATE_CHECKRULE;
			return;
		}
	
		if (this.state == GENERATE_APPLYRULE) {
			
			rule.applyGenerateRule(this.currentFS);
			var childCount = this.currentFS.childCount();
			var allChildsComplete = true;
			for(index = 0;index<childCount;index++) {
				var childFS = this.currentFS.getChild(index);
				if (this.isGenerateComplete(childFS) == false) {
					//console.log('applyrule - child not complete');
					//console.log('applyrule - adding to nodestack '+childFS.ID);
					allChildsComplete = false;
					this.nodeStack.push(childFS);
				} else {
					//console.log('applyrule - child complete');
				}
			}
			
			this.nodeIndex++;
			//console.log(' -- lengthi- '+this.nodeIndex+' - '+this.nodeStack.length+')');
			if (this.nodeIndex == this.nodeStack.length) {
				//var resultFS = this.rootFS.getRecursiveCopy();
				////console.log('result found - '+resultFS.name+'-'+resultFS.ID);
				////console.log('result found - '+resultFS.name+'-'+resultFS.ID);
				//this.processResult(resultFS);
				console.log('all nodes complete');
				addConsoleMessage('Applying rule \''+rule.name+'\', all nodes complete, result found');
				//this.state = GENERATE_REVERSERULE;
				this.state = GENERATE_RESULTFOUND;
			} else {
				if (allChildsComplete == false) {
					addConsoleMessage('Applying rule \''+rule.name+'\', uncomplete nodes added, moving to next node');
				} else {
					addConsoleMessage('Applying rule \''+rule.name+'\', all nodes complete - processing next node');
				}
				console.log(' -- applying rule stack '+this.nodeIndex);
				console.dir(this.nodeStack);
				this.currentFS = this.nodeStack[this.nodeIndex];
				this.ruleIndexStack.push(0);
				this.ruleindex = 0;
				this.state = GENERATE_CHECKRULE;
			}
			return;
		}
	
		if (this.state == GENERATE_CHECKRULE) {	// tsekataan mitkä rulet ovat yhteensopivia
			
			this.ruleIndexStack[this.nodeIndex] = this.ruleindex;
			if (comments) console.log('.. ruleindex - '+this.ruleindex + ' ('+this.rules.length+')');
			if (comments) console.log('.. nodeindex - '+this.nodeIndex);
			if (comments) console.log(' -- ruleIndexStack');
			if (comments) console.dir(this.ruleIndexStack);
			if (comments) console.log(' -- nodeStack');
			if (comments) console.dir(this.nodeStack);
			
			if (this.ruleindex >= this.rules.length) {
				//console.log('-- all rules traversed');
				if (comments) console.log(' -- All rules '+this.ruleindex+' - ruleIndexStack');
				if (comments) console.dir(this.ruleIndexStack);
				addConsoleMessage('All rules checked, reversing rule... ');
				this.nodeIndex--;
				this.nodeStack.pop();
				if (this.nodeIndex < 0) {
					console.log('finished.');
					this.state = GENERATE_FINISHED;
					return;
				}
				var previousFS = this.nodeStack[this.nodeIndex];
				var temp = this.ruleIndexStack.pop();
				var previousRuleIndex = this.ruleIndexStack[this.nodeIndex];
				for(var childIndex=previousFS.childCount()-1;childIndex>-1;childIndex--) {
					if (comments) console.log(' -- pop children');
					var tempChild = previousFS.getChild(childIndex);
					if (this.isGenerateComplete(tempChild) == false) {
						if (comments) console.log(' -- -- pop yes');
						//this.nodeStack.pop();
					}
				}
				previousFS.removeAllChilds();
				previousRuleIndex++;
				this.ruleindex = previousRuleIndex;
				//this.ruleIndexStack[this.nodeIndex] = previousRuleIndex;
				this.currentFS = previousFS;
				this.state = GENERATE_CHECKRULE;
				return;
			}
			rule = this.rules[this.ruleindex];
			
			//console.log('tsekataan onko current fs täysin generoitu, miten?');
			var complete = this.isGenerateComplete(this.currentFS);
			if (complete == false) {
				if (comments) console.log(' -- generatecomplete is false');
				//console.log('-- trying rule -'+rule.name);
				var compatible = rule.isGenerateCompatible(this.currentFS);
				
				if (compatible == true) {
					addConsoleMessage('Rule \''+rule.name+'\' --> compatible');
					//console.log('compatible rule found - '+rule.name);
					this.ruleIndexStack[this.nodeIndex] = this.ruleindex;
					this.state = GENERATE_APPLYRULE;
					return;
				} else {
					addConsoleMessage('Rule \''+rule.name+'\' --> not compatible');
					//console.log(' -- rule is not compatible');
					
					if (this.ruleindex == (this.rules.length-1)) {
						
						var argumentcount = this.currentFS.getArgumentCount();
						if (comments) console.log(' -- arg count fs - '+this.currentFS.name+'-'+this.currentFS.ID);
						if (comments) console.log(' -- argumentcount - '+argumentcount);
						//var argumentcount2 = this.currentFS.arguments.length;
						//if (comments) console.log(' -- argumentcount2 - '+argumentcount2);
						
						if (argumentcount == 0) {
							if (comments) console.log(' -- argumentcount on nolla, accept');
							if (this.nodeIndex < (this.nodeStack.length-1)) {
								this.ruleIndexStack[this.nodeIndex] = (this.ruleindex+1);
								
								this.nodeIndex++;
								this.currentFS = this.nodeStack[this.nodeIndex];
								if (comments) console.log('-- xx nodeindex - '+this.nodeIndex);
								//console.dir(this.nodeStack);
								this.ruleindex++
								this.ruleIndexStack.push(this.ruleindex);
								this.ruleindex = 0;
								this.state = GENERATE_CHECKRULE;
								return;
							} else {
								// pitäisi ilmeisesti hyväksyä lopputulos???
								if (comments) console.log('all arguments processed full...');
								if (comments) console.log('TODO: tsekkaa onko yksi fs...');
								
								
								//this.state = GENERATE_REVERSERULE;
								this.state = GENERATE_RESULTFOUND;
								return;
							}
						} else {
							console.log(' -- last rule but arguments exists');
						}
					}
					
					
					this.ruleindex++;
					return;
				}
				
				
				
			} else {
				//console.log('-- complete- '+this.currentFS.name+' ('+this.currentFS.ID+')');
				if (this.nodeIndex < (this.nodeStack.length-1)) {
					this.nodeIndex++;
					this.currentFS = this.nodeStack[this.nodeIndex];
					//console.log('-- nodeindex - '+this.nodeIndex);
					//console.dir(this.nodeStack);
					this.state = GENERATE_CHECKRULE;
					return;
				} else {
					console.log('all arguments processed');
					this.state = GENERATE_REVERSERULE;
					return;
				}
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
			
			addConsoleMessage('Reversing rule \''+rule.name+'\'... ');
			if (comments) console.log(' -- rulestack '+this.ruleindex);
			if (comments) console.dir(this.ruleIndexStack);
			
			this.nodeIndex--;
			var previousFS = this.nodeStack[this.nodeIndex];
			var previousRuleIndex = this.ruleIndexStack[this.nodeIndex];
			var temp = this.ruleIndexStack.pop();
			this.nodeStack.pop();
			//var temp2 = this.nodeStack.pop();
			
			if (previousFS != null) {
				console.log('previousfs not null');
				console.log('previousfs ruleindex - '+previousRuleIndex);
				previousFS.removeAllChilds();
				previousRuleIndex++;
				this.ruleindex = previousRuleIndex;
				this.currentFS = previousFS;
				this.state = GENERATE_CHECKRULE;
			} else {
				console.log('previousfs is null');
				
				//previousRuleIndex++;
				//this.ruleindex = previousRuleIndex;
				////this.currentFS = null;
				this.state = GENERATE_FINISHED;
			}
			//console.log(' -- test 611');
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

