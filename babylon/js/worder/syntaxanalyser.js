

var ANALYSE_START = 1;
var ANALYSE_FINISHED = 2;
var ANALYSE_APPLYRULE = 3;
var ANALYSE_CHECKRULE = 4;
var ANALYSE_REVERSERULE = 5;
var ANALYSE_FOUND = 6;
var ANALYSE_CHECKPREVIOUSRULE = 7;



// 		Nimi voitaisiin ehkä vaihtaa SyntaxTree
function SyntaxAnalyser(mainElement, selectedRules, resultrules, width, height)  {


	this.width = width;
	this.height = height;
	this.structurecount = 0;
	this.rootarray = Array();
	this.currentindex = 0;
	this.currentFeatureID = 0;
	
	this.rules = null;
	this.resultrules = null;
	this.ruleindex = 0;
	//this.previousRuleIndex = 0;
	
	this.ruleindexcounter = 0;		// käytetään rule-divin scrollaukseen
	this.sentenceindex = 0;
	this.stepCounter = 0;
	this.sentences = [];
	
	//this.rulestack = [];
	this.ruleindexstack = [];			// Tätä tarvitaan
	//this.activerule = null;
	this.currentindexstack = [];
	
	this.foundstack = [];
	
	
	this.rulesTableElement = null;
	this.rulesTableElementContainer = null;
	
	this.analyseSentencesTableElement = null;
	this.foundAnalysesTableElement = null;
	this.ruleCanvasElement = null;
	this.analyseCanvasElement = null;
	this.context = null;
	this.canvasName = null;
	this.analysesentences = [];
	this.conceptstrings = [];
	
	
	this.pauseOnReverseRule = true;
	this.pauseOnCheckingRule = true;
	this.pauseOnApplyRule = true;
	this.pauseOnResultFound = true;
	this.pauseOnStart = true;
	this.ruleSectionID = 0;
	this.activeFS = null;
	this.comments = false;
	
	this.initialize = function(mainElement, rules, resultrules, width, height) {
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
		this.resultrules = resultrules;
		
		this.canvasName = mainElement;
	}
	this.initialize(mainElement, selectedRules, resultrules, width, height);		// constructor
	
	this.setRuleSectionID = function(sectionID) {
		this.ruleSectionID = sectionID;
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
	
	this.addFeatureStructure = function(sentenceindex, fs) {
		if (sentenceindex == 0) {
			var copy = fs.getCopy();
			this.rootarray[this.structurecount] = copy;
			this.structurecount++;
		}
	
		if (typeof this.sentences[sentenceindex] === 'undefined') {
			this.sentences[sentenceindex] = Array();
			var copy = fs.getCopy();
			this.sentences[sentenceindex][this.sentences[sentenceindex].length] = copy;
		} else {
			var copy = fs.getCopy();
			this.sentences[sentenceindex][this.sentences[sentenceindex].length] = copy;
		}
	}
	
	
	this.setRulesTableElement = function(elementname, elementcontainer) {
		this.rulesTableElement = elementname;
		this.rulesTableElementContainer = elementcontainer;
	}
	

	this.setAnalyseSentencesTableElement = function(elementname) {
		this.analyseSentencesTableElement = elementname;
	}
	

	this.setFoundAnalysesTableElement = function(elementname) {
		this.foundAnalysesTableElement = elementname;
	}
	
	
	this.reDrawSentences = function(sentenceindex) {
		//console.log('reDraw sentences - '+this.analysesentences.length);
		$('#'+this.analyseSentencesTableElement).empty();
		for (var index in this.analysesentences) {
			var sentence = this.analysesentences[index];
			var conceptstr = this.conceptstrings[index];
			if (index == sentenceindex) {
				this.addAnalyseSourceSentence(index,sentence,conceptstr, 1);
			} else {
				this.addAnalyseSourceSentence(index,sentence,conceptstr, 0);
			}
		}
	}
	
	
	this.addAnalyseSourceSentence = function(index, sentence, conceptstring, active) {
		//console.log('addSentence - '+sentence+' - '+index);
		this.analysesentences[index] = sentence;
		this.conceptstrings[index] = conceptstring;
		
		var tr = document.createElement('tr');
	
		td = document.createElement('td');
		td.style.width = '30px';
		td.style.maxWidth = '30px';
		td.style.minWidth = '30px';
		td.style.textOverflow = 'hidden';
		td.style.overflow = 'hidden';
		td.style.whiteSpace = 'nowrap';
		td.id = 'sentenceactive-'+index;
		if (active == 0) {
			td.innerHTML = ''+ index;
		} else {
			td.innerHTML = '' + index;
			td.className += 'wselected';
		}
		td.className += ' myclass';
		tr.append(td);
	
		var td = document.createElement('td');
		td.style.width = '160px';
		td.style.maxWidth = '160px';
		td.style.textOverflow = 'hidden';
		td.style.overflow = 'hidden';
		td.style.whiteSpace = 'nowrap';
		td.innerHTML = sentence;
		td.onclick = function() {
			//console.log('show sentence - '+index);
			window.analyser.showSentence(index,'analysecanvas');
		};
		td.id = 'sentencename-'+index;
		if (active == 0) {
			td.className += ' myclass';
		} else {
			td.className += 'wselected';
		}
		tr.append(td);
		
		var td = document.createElement('td');
		td.style.width = '120px';
		td.style.maxWidth = '120px';
		td.style.textOverflow = 'hidden';
		td.style.overflow = 'hidden';
		td.style.whiteSpace = 'nowrap';
		td.innerHTML = conceptstring;
		td.onclick = function() {
			//console.log('show sentence - '+index);
			window.analyser.showSentence(index,'analysecanvas');
		};
		td.id = 'sentencename-'+index;
		if (active == 0) {
			td.className += ' myclass';
		} else {
			td.className += 'wselected';
		}
		tr.append(td);
		
		$('#'+this.analyseSentencesTableElement).append(tr);
	}
	
	
	
	
	this.drawfoundstack = function() {
	
		$('#'+this.foundAnalysesTableElement).empty();
		for (var index in this.foundstack) {
			var fs = this.foundstack[index];
			var tr = document.createElement('tr');
			var td = document.createElement('td');
			td.style.width = '160px';
			td.style.maxWidth = '160px';
			td.style.textOverflow = 'hidden';
			td.style.overflow = 'hidden';
			td.style.whiteSpace = 'nowrap';
			td.className += ' myclass';
			td.innerHTML = fs.name+'-'+index;
			td.setAttribute('onclick','drawFoundAnalyse('+index+','+this.canvasName+','+this.width+','+this.height+')');
			tr.append(td);
			$('#'+this.foundAnalysesTableElement).append(tr);
		}
	}
	
	
	this.setActiveFS = function(fs) {
		//console.log('active setted...');
		this.activeFS = fs;
	}
	
	
	this.getActiveFS = function() {
		//console.log('active fetched...');
		return this.activeFS;
	}
	
	
	this.reDrawRuleTables = function(ruleindex) {
		$('#'+this.rulesTableElement).empty();
		for (var index in this.rules) {
			var rule = this.rules[index];
			if (index == ruleindex) {
				this.addRuleTableRow(index,rule,1);
			} else {
				this.addRuleTableRow(index,rule,0);
			}
		}
		$('#'+this.rulesTableElementContainer).scrollTop(ruleindex*19-50);
	}

	
	this.addRuleTableRow = function(index, rule, active) {
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
	
	
	
	this.applyRule = function(rule, pos1, pos2) {
	
		//console.log('applyrule...'+rule.getTermCount());
		if (rule.getTermCount() == 1) {
			//console.log(' - termvount = 1');
			var pos1N = parseInt(pos1);
			addConsoleMessage('Applying \''+rule.name+'\'');
			var newrootarray = new Array();
			var index = 0;
			var newindex = 0;
		
			var item1 = null;
			var item2 = null;
			for (var key in this.rootarray) {
				var found = false;
				if (index == pos1) {
					item1 = this.rootarray[key];
					found = true;
					var newfs = rule.applyAnalyseRule(item1,null);  // nyt tiedetään, että sekä pos1 ja pos2 on asetettu muuttujiin
					//console.log('applyrule newfs');
					newfs.pushChild(item1);
					newrootarray[newindex] = newfs;
					newindex++;
					found = true;
				}
				if (found == false) {
					newrootarray[newindex] = this.rootarray[key];
					newindex++;
					index++;
				} else {
					index++;
				}
			}
			this.rootarray = newrootarray;
		
			//this.rulestack.push(rule);
			//this.drawrulestack();
			this.currentindexstack.push(pos1);
			this.ruleindexstack.push(this.ruleindex);
			//this.previousRuleIndex = -1;
			this.ruleindex = 0;	
			return;
		}
		
		var pos1N = parseInt(pos1);
		var pos2N = parseInt(pos2)-1;
		addConsoleMessage('Applying \''+rule.name+'\'');
		//console.log('applying rule - posN - '+pos1N+' - '+pos2N);
		if (parseInt(pos1) != (parseInt(pos2)-1)) {
			console.log('Rule must apply only contiguous indexes, applyrule failed ('+pos1+','+pos2+')');
			return;
		}
		//console.log('applyrule...x1');
		var newrootarray = new Array();
		var index = 0;
		var newindex = 0;
	
		var item1 = null;
		var item2 = null;
		//console.log('applyrule...x2');
		for (var key in this.rootarray) {
			var found = false;
			if (index == pos1) {
				item1 = this.rootarray[key];
				found = true;
			}
			if (index == pos2) {
				item2 = this.rootarray[key];
				found = true;
				//console.log('applyrule...x2.1');
				var newfs = rule.applyAnalyseRule(item1,item2);  // nyt tiedetään, että sekä pos1 ja pos2 on asetettu muuttujiin
				//console.log('applyrule...x2.2');
				
				//console.log('applyrule newfs');
				newfs.pushChild(item1);
				newfs.pushChild(item2);
				newrootarray[newindex] = newfs;
				newindex++;
				found = true;
			}
			if (found == false) {
				newrootarray[newindex] = this.rootarray[key];
				newindex++;
				index++;
			} else {
				index++;
			}
		}
		//console.log('applyrule...x3');
		//console.dir(this.rootarray);
		//console.dir(newrootarray);
		//console.log('applyrule...x4');
		this.rootarray = newrootarray;
	
		//this.rulestack.push(rule);
		//this.drawrulestack();
		this.currentindexstack.push(pos1);
		this.ruleindexstack.push(this.ruleindex);
		//this.previousRuleIndex = -1;
		this.ruleindex = 0;
		//this.reDrawRuleTables(this.ruleindex);
	}
	
	
	// tämä palauttaa treen tilaan ennen kyseiseen positioon sovellettua sääntöä
	// nyt on vain epäselvää miten tiedetään mihin positioon kyseinen rule on asetettu
	// tätä positiota pitäisi mahdollisesti pitää jossain pinossa (push/pop arrayssä)
	this.reverseRule = function(position) {
		console.log('reverseRule not implemented');
		return false;
	}
	

	// TODO: palautetaan tilanne ennen rulen soveltamista  
	//		- otetaan stackistä yksi rule pois
	//		- palautetaan rootarrayhyn currentindexin childit
	//		- piirretään tree ja rulestack uudelleen
	//		- palautetaan ilmeisesti ruleindeksi stackista
	//		- epäselvää on otetaanko stakista myös currentindex, mitä ilmeisimmin
	this.reverseLastRule = function() {

		if (this.currentindexstack.length == 0) {
			return false;
		}
		var lastappliedruleindex = this.currentindexstack.pop();
		var newrootarray = new Array();
		var index = 0;
		var newindex = 0;
		addConsoleMessage('Reversing rule');

		for (var key in this.rootarray) {
			var fs = this.rootarray[key];
			if (index == lastappliedruleindex) {
				var childcount = fs.childCount();
				for (var childindex in fs.childs) {
					var child = fs.childs[childindex];
					newrootarray[newindex] = child;
					newindex++;
				}
			} else {
				newrootarray[newindex] = fs;
				newindex++;
			}
			index++;
		}
		this.rootarray = newrootarray;
	
	
		var fontHeight = 20;
		var ctx = this.context;
		this.drawAnalyse(ctx,0,0,fontHeight);
		this.currentindex = lastappliedruleindex;
		//this.rulestack.pop();
		this.ruleindex = this.ruleindexstack.pop();
		//this.previousRuleIndex = this.ruleindex;
		this.ruleindex++;
		//this.reDrawRuleTables(this.ruleindex);
		return true;
	}
	
	
	this.updateScreen = function() {
		//console.log('.draw.');
		var fontHeight = 20;
		var ctx = this.context;
		this.drawAnalyse(ctx,0,0, fontHeight);
		//this.reDrawRuleTables(this.ruleindex);
		//this.drawrulestack();
		this.drawfoundstack();
	}
	
	
	this.startAnalyse = function() {
		var counter = 0;
		this.currentindex = -5;
	
		//this.rulestack = [];
		this.ruleindexstack = [];
		this.currentindexstack = [];
		this.foundstack = [];
		
		this.updateScreen();
		this.state = ANALYSE_START;
		this.stepCounter = 0;
		addConsoleMessage('State = ANALYSE_START');
	}
	
	
	this.activateNextSentenceIndex = function() {
		this.structurecount = 0;
		this.rootarray = Array();
		//console.log('sentenceindex - '+this.sentenceindex);
		
		for (var index in this.sentences[this.sentenceindex]) {
	
			var fs = this.sentences[this.sentenceindex][index];
			var copy = fs.getCopy();
			this.rootarray[this.structurecount] = copy;
			this.structurecount++;
		}
	
		this.currentindex = -5;
	
		//this.rulestack = [];
		this.ruleindexstack = [];
		this.currentindexstack = [];
		//this.foundstack = [];
	
		this.reDrawSentences(this.sentenceindex);
		this.updateScreen();
		this.state = ANALYSE_START;
		addConsoleMessage('State = ANALYSE_START');
	
	}
	
	
	
	
	this.nextStep = function() {
	
		var comments = false;
		//console.log('------------------------------ '+this.stepCounter);
		this.stepCounter++;
		/*
		if (this.stepCounter > 300) {
			console.log('maype loop, stepcounter MAX');
		}
		*/
		
		
		var oldstate = this.state;
		this.privateNextStep();
		//this.updateScreen();
		var pause = true;
	
		if (oldstate == ANALYSE_CHECKRULE) {
			if (this.pauseOnCheckingRule == true) {
				console.log('ANALYSE_CHECKRULE - pause');
			} else {
				//console.log('ANALYSE_CHECKRULE - no pause');
				pause = false;
			}
		}
		
		if (oldstate == ANALYSE_CHECKPREVIOUSRULE) {
			if (this.pauseOnCheckingRule == true) {
				console.log('ANALYSE_CHECKRULE - pause');
			} else {
				//console.log('ANALYSE_CHECKRULE - no pause');
				pause = false;
			}
		}
	
		if (oldstate == ANALYSE_REVERSERULE) {
			if (this.pauseOnReverseRule == true) {
				console.log('ANALYSE_REVERSERULE - pause');
			} else {
				//console.log('ANALYSE_REVERSERULE - no pause');
				pause = false;
			}
		}
	
		if (oldstate == ANALYSE_START) {
			if (this.pauseOnStart == true) {
				console.log('ANALYSE_START - pause');
			} else {
				//console.log('ANALYSE_START - no pause');
				pause = false;
			}
		}
		
		if (oldstate == ANALYSE_APPLYRULE) {
			if (this.pauseOnApplyRule == true) {
				console.log('ANALYSE_APPLYRULE - pause');
			} else {
				//console.log('ANALYSE_APPLYRULE - no pause');
				pause = false;
			}
		}
	
		if (oldstate == ANALYSE_FOUND) {
			if (this.pauseOnResultFound == true) {
				console.log('ANALYSE_FOUND - pause');
			} else {
				//console.log('ANALYSE_FOUND - no pause');
				pause = false;
			}
		}
		if (comments) console.log(' root array currentIndex - '+this.currentindex);
		if (comments) console.dir(this.rootarray);
		if (comments) console.log(' rulendex - '+this.ruleindex);
		if (comments) console.dir(this.ruleindexstack);
		
		if (pause == true) {
			this.updateScreen();
			this.reDrawRuleTables(this.ruleindex);
			//this.reDrawRuleTables(this.ruleindex);
			
			
		} else {
			//	this.wait(1000);
			this.nextStep();
			//	console.log('settimeout');
			//	setTimeout(analysenextstep, 1000);
			return;
		}
	}
	
	
	this.wait = function(ms){
		var start = new Date().getTime();
		var end = start;
		while(end < start + ms) {
			end = new Date().getTime();
		}
	}
	
	
	this.privateNextStep = function() {
		
		var comments = false;
		
		//console.log('------------------------ ');
		if (this.state == ANALYSE_CHECKRULE) console.log(this.stepCounter + ' - ANALYSE_CHECKRULE');
		if (this.state == ANALYSE_START) console.log(this.stepCounter + '  - ANALYSE_START');
		if (this.state == ANALYSE_APPLYRULE) console.log(this.stepCounter + ' - ANALYSE_APPLYRULE');
		if (this.state == ANALYSE_FOUND) console.log(this.stepCounter + ' - ANALYSE_FOUND');
		if (this.state == ANALYSE_REVERSERULE) console.log(this.stepCounter + ' - ANALYSE_REVERSERULE');
		if (this.state == ANALYSE_CHECKPREVIOUSRULE) console.log(this.stepCounter + ' - ANALYSE_CHECKPREVIOUSRULE');
		//if (this.state == ANALYSE_CHECKRULE) console.log('' + stepcounter + ' - ANALYSE_CHECKRULE');
		//if (this.state == ANALYSE_START) console.log('-- state - ANALYSE_START');
		//if (this.state == ANALYSE_APPLYRULE) console.log('-- state - ANALYSE_APPLYRULE');
		//if (this.state == ANALYSE_FOUND) console.log('-- state - ANALYSE_FOUND');
		//if (this.state == ANALYSE_REVERSERULE) console.log('-- state - ANALYSE_REVERSERULE');
		//this.reDrawRuleTables(this.ruleindex);
		//console.log(' - currentindex - '+ this.currentindex);
		//console.log(' - rulestack - '+ this.ruleindexstack.length);
		
		if (this.state == ANALYSE_START) {
			this.currentindex = 0;
			//this.previousRuleIndex = this.ruleindex;
			this.ruleindex = 0;
			this.state = ANALYSE_CHECKRULE;
			return;
		}
	
		if (this.state == ANALYSE_APPLYRULE) {
			if (comments) console.log('analysing applyrule');
			var currentindex = this.currentindex;
			rule = this.rules[this.ruleindex];
			console.log(rule.name);
			this.applyRule(rule,currentindex,currentindex+1);
			this.index = -5;
			//console.log(' -- currentindex - '+this.currentindex);
			if (this.rootarray.length == 1) {
				if (comments) console.log('rootarray length = 1');
				// TODO: pitäisikö tämän palauttaa 1, jos löytyi, sitten ei tarvitisisi enää käydä ruleja läpi (estää ehkä looppeja)
				this.checkResultRules(this.rootarray[0]);	// Tsekataan onko yhteensopiva resultrulesien kanssa...
			} 
			
			var newversion = 1;
			if (newversion == 1) {
				this.currentindex = currentindex-1;
				if (this.currentindex < 0) {
					this.currentindex = 0;
					this.state = ANALYSE_CHECKRULE;
				} else {
					// Tämä ei ehkä toimi, koska jos tullaan takaisin reverserulella, niin palataankin checkruleen, 
					// eikä check previous ruleen. Lyhentää tämä ainakin looppia yleensä vähintään kolmanneksella
					/// ja poistaa ainakin osan tuplista.
					//this.state = ANALYSE_CHECKRULE;
					this.state = ANALYSE_CHECKPREVIOUSRULE;
				}
				return;
			} else {
				this.currentindex = 0;
				this.state = ANALYSE_CHECKRULE;
				return;
			}
		}
		
		// Tätä rulea tsekkia käytetään silloin kun halutaan tsekata rulet, jotka yhdistää nykyisen elementin
		// currentindexissa edelliseen elementtiin. Näin ei tarvitse käydä lävitse toiseen kertaan edellisiä
		// rakenteita ja homma on tehokkaampaa... Tämä on kopioitu ANALYSE_CHECKRULE:sta
		if (this.state == ANALYSE_CHECKPREVIOUSRULE) {   
			var currentstructures = this.rootarray;
			var currentindex = this.currentindex;
			if (this.ruleindex == this.rules.length) {
				console.log('++++ all rules traversed');
				addConsoleMessage('All rules traversed');
				
				if (comments) console.log(' - currentindex - '+ this.currentindex);
				if (comments) console.log(' - ruleindex - '+ this.ruleindex);
				if (comments) console.log(' - rootarray.length - '+ this.rootarray.length);
				if (this.sentences[this.sentenceindex].length == 1) {
					if (comments) console.log('only one word, kokeillaan analysoida tämä');
				}
				
				this.currentindex++;
				this.ruleindex = 0;
				this.state = ANALYSE_CHECKRULE;
				
				if (this.currentindex < this.rootarray.length) {
					console.log('++++ next index - '+this.currentindex);
					return;
				} else {
					var success = this.reverseLastRule();
					if (comments) console.log('reversesuccess - '+success);
					if (success == true) {
						
						if (this.ruleindex < this.rules.length) {
							this.state = ANALYSE_CHECKRULE;
						} else {
							if (comments) console.log('no more rules - add index');
							if (comments) console.log(' -- currentindex - '+this.currentindex);
							if (comments) console.log(' -- rootarraylen - '+this.rootarray.length);
				
							if (this.currentindex < this.rootarray.length) {
								console.log(' -- adding inde ');
								this.currentindex++;
								this.ruleindex = 0;
								this.state = ANALYSE_CHECKRULE;
							} else {
								console.log(' -- more reverse');
								this.state = ANALYSE_REVERSERULE;
							}
						}
						return;
					} else {
						// Jos analysoidaan vain yhtä sanaa, niin se saattaa olla suoraa lopullinen rakenne...
						if (this.sentences[this.sentenceindex].length == 1) {
							if (comments) console.log('all rules traversed, checking root...'+this.sentences[this.sentenceindex].length);
							this.checkResultRules(this.sentences[this.sentenceindex][0]);	// Tsekataan onko yhteensopiva resultrulesien kanssa...
						}
						
						if (this.currentindex < (this.rootarray.length)) {
							if (comments) console.log(' -- adding index ');
							this.ruleindex = 0;
							this.state = ANALYSE_CHECKRULE;
							return;
						}
						
						this.sentenceindex++;
						if (this.sentenceindex < this.sentences.length) {
							this.activateNextSentenceIndex();
						} else {
							this.state = ANALYSE_FINISHED;
						}
					}
					return;
				}
			}
			rule = this.rules[this.ruleindex];
			console.log(rule.name);

			console.log(' - rule term count ...'+rule.getTermCount());
			if (rule.getTermCount() == 1) {
				console.log(' - only one term previous rule...');
				this.ruleindex++;
				this.state = ANALYSE_CHECKPREVIOUSRULE;
				return;
			}
			
			if (comments) console.log('currentindex = '+currentindex);
			if (comments) console.log('termcount = '+rule.getTermCount());
			
			if ((currentindex) < currentstructures.length) {
				var tempfs = currentstructures[currentindex];
			} else {
				if (comments) console.log(' - not enough structure length...');
				this.ruleindex++;
				this.state = ANALYSE_CHECKPREVIOUSRULE;
				return;
			}
	
			
			var compatible = rule.isAnalyseCompatible(currentstructures[currentindex], currentstructures[currentindex+1]);
			if (compatible == true) {
				this.state = ANALYSE_APPLYRULE;
				addConsoleMessage('Compatible \''+rule.name+'\'');
				addConsoleMessage('Rule is compatible \''+rule.name+'\', applying rule');
				return;
	
			} else {
				this.ruleindex++;
				addConsoleMessage('Incompatible x1 \''+rule.name+'\'');
				addConsoleMessage('Rule not compatible \''+rule.name+'\', advancing to next rule');
				return;
			}
		}

		
		
		if (this.state == ANALYSE_CHECKRULE) {   // tsekataan mitkä rulet ovat yhteensopivia
			var currentstructures = this.rootarray;
			var currentindex = this.currentindex;
			if (this.ruleindex == this.rules.length) {
				console.log('++++ all rules traversed');
				addConsoleMessage('All rules traversed');
				
				if (comments) console.log(' - currentindex - '+ this.currentindex);
				if (comments) console.log(' - ruleindex - '+ this.ruleindex);
				if (comments) console.log(' - rootarray.length - '+ this.rootarray.length);
				if (this.sentences[this.sentenceindex].length == 1) {
					if (comments) console.log('only one word, kokeillaan analysoida tämä');
				}
				
				this.currentindex++;
				this.ruleindex = 0;
				this.state = ANALYSE_CHECKRULE;
				
				if (this.currentindex < this.rootarray.length) {
					console.log('++++ next index - '+this.currentindex);
					return;
				} else {
					var success = this.reverseLastRule();
					if (comments) console.log('reversesuccess - '+success);
					if (success == true) {
						
						if (this.ruleindex < this.rules.length) {
							this.state = ANALYSE_CHECKRULE;
						} else {
							if (comments) console.log('no more rules - add index');
							if (comments) console.log(' -- currentindex - '+this.currentindex);
							if (comments) console.log(' -- rootarraylen - '+this.rootarray.length);
				
							if (this.currentindex < this.rootarray.length) {
								console.log(' -- adding inde ');
								this.currentindex++;
								this.ruleindex = 0;
								this.state = ANALYSE_CHECKRULE;
							} else {
								console.log(' -- more reverse');
								this.state = ANALYSE_REVERSERULE;
							}
						}
						return;
					} else {
						// Jos analysoidaan vain yhtä sanaa, niin se saattaa olla suoraa lopullinen rakenne...
						if (this.sentences[this.sentenceindex].length == 1) {
							if (comments) console.log('all rules traversed, checking root...'+this.sentences[this.sentenceindex].length);
							this.checkResultRules(this.sentences[this.sentenceindex][0]);	// Tsekataan onko yhteensopiva resultrulesien kanssa...
						}
						
						if (this.currentindex < (this.rootarray.length)) {
							if (comments) console.log(' -- adding index ');
							this.ruleindex = 0;
							this.state = ANALYSE_CHECKRULE;
							return;
						}
						
						this.sentenceindex++;
						if (this.sentenceindex < this.sentences.length) {
							this.activateNextSentenceIndex();
						} else {
							this.state = ANALYSE_FINISHED;
						}
					}
					return;
				}
			}
			rule = this.rules[this.ruleindex];
			console.log(rule.name);

			if (comments) console.log('currentindex = '+currentindex);
			if (comments) console.log('termcount = '+rule.getTermCount());
			
			if ((currentindex) < currentstructures.length) {
				var tempfs = currentstructures[currentindex];
			} else {
				if (comments) console.log(' - not enough structure length...');
				this.ruleindex++;
				this.state = ANALYSE_CHECKRULE;
				return;
			}
	
			
			var compatible = rule.isAnalyseCompatible(currentstructures[currentindex], currentstructures[currentindex+1]);
			if (compatible == true) {
				this.state = ANALYSE_APPLYRULE;
				addConsoleMessage('Compatible \''+rule.name+'\'');
				addConsoleMessage('Rule is compatible \''+rule.name+'\', applying rule');
				return;
	
			} else {
				this.ruleindex++;
				addConsoleMessage('Incompatible x1 \''+rule.name+'\'');
				addConsoleMessage('Rule not compatible \''+rule.name+'\', advancing to next rule');
				return;
			}
		}
	

		if (this.state == ANALYSE_FINISHED) {
			addConsoleMessage('Analyse finished.');
			
			
			
			console.log('finished...');
			return;
		}

		
		// reverse last rule, tai mikäli ollaan lopussa, niin tulostetaan finished, ja tulostetaan kaikki valmiit.
		if (this.state == ANALYSE_REVERSERULE) {
			var success = this.reverseLastRule();
			console.log('++++++++++ reveerserule');
			
			if (success == true) {
				if (this.ruleindex < this.rules.length) {
					console.log('more rules available');
					this.state = ANALYSE_CHECKRULE;
				} else {
					console.log('no more rules - add index');
					
					console.log('rootarray length - '+this.rootarray.length);
					var counter1 = 0;
					for (var fsindex in this.rootarray) {
						counter1++;
					}
					console.log('rootarray counter - '+counter1);
					
					if (this.currentindex == this.rootarray.length) {
						//console.log(' -- adding inde ');
						this.currentindex++;
						//this.previousRuleIndex = this.ruleindex;
						this.ruleindex = 0;
						this.state = ANALYSE_CHECKRULE;
					} else {
						//console.log(' -- more reverse');
						this.state = ANALYSE_REVERSERULE;
					}
				}
				
				return;
			} else {
				this.sentenceindex++;
				if (this.sentenceindex < this.sentences.length) {
					//console.log('Sentence analysed, more sentences available');
					this.activateNextSentenceIndex();
				} else {
					//console.log('next state ANALYSE_FINISHED');
					this.state = ANALYSE_FINISHED;
				}
				return;
			}
			return;
		}

	
		// TODO: tässä pitää tsekata onko muodostunut structure oikeellinen
		//			- kaikki tarpeelliset featuret käsitelty
		//			- pakolliset argumentit asetettu
		//			- ei ole jo aiemmin löydetty tupla
		if (this.state == ANALYSE_FOUND) {
			var fs = this.rootarray[0];
			//console.log('found - '+fs.name);
			addConsoleMessage('Analyse found');
			
			console.log('----------------------------------- ');
			console.log('Analyse found');
			//console.dir(this.resultrules);
			//console.dir(fs);
			this.checkResultRules(fs);	// Tsekataan onko yhteensopiva resultrulesien kanssa...
			
			
			console.log('state - ANALYSE_FOUND -- no result');
			this.state = ANALYSE_REVERSERULE;
			return;
		}
	}

	
	// TODO: kelataan kaikki resultrules läpi ja tsekataan täsmääkö currentfs niihin...
	//console.log('resultrulecount - '+this.resultrules.length);
	this.checkResultRules = function(fs) {
		
		var comments = false;
		if (comments) console.dir(this.resultrules);
		for (var rindex in this.resultrules) {
			
			if (comments) console.log('----------------------------------- ');
			var resultrule = this.resultrules[rindex];
			if (comments) console.log('resultrule - '+resultrule.name);
			
			var allfound = true;
			resultrule.resultfeatures.forEach((rulefeaturevalue, rulefeature) => {
				//var rulefeaturevalue = resultrule.resultfeatures[rulefeature];
				if (comments) console.log(' -- resulfeature - '+rulefeature+' - '+rulefeaturevalue);
				var featurefound = false;
				fs.features.forEach((value, feature) => {
					//var value = fs.features[feature];
					if (rulefeature == feature) {
						if (comments) console.log(' -- g1 -- found - '+feature+'-'+value);
						if (value == rulefeaturevalue) {
							if (comments) console.log(' -- g2 -- match');
							featurefound = false;
						} else {
							if (comments) console.log(' -- g3-- unmatch');
							allfound = false;
						}
					}
				})
				//for (var feature in fs.features) {
				//}
				if (featurefound = false) {
					allfound = false;
				}
			})
			//for (var rulefeature in resultrule.resultfeatures) {
			//}
			
			
			if (allfound == true) {
				//console.log(' -- -- allfound true');
				this.foundstack.push(fs.getRecursiveCopy());
				this.drawfoundstack();
				console.log('state - ANALYSE_FOUND');
				this.state = ANALYSE_REVERSERULE;
				return;
			} else {
				console.log(' -- g4 -- allfound false');
			}
		}
		console.log(' -- checkResultRules complete');
		
	}
	
	
	
	this.showSentence = function(index, elementID) {
		//console.log('show sentence ... '+elementID);
		
		this.sentenceindex = index;
		this.activateNextSentenceIndex();
		
		// TODO: pitäisi myös analyysi aloittaa uudelleen...
		
		var canvas = document.getElementById(elementID);
		var ctx = canvas.getContext('2d');
		ctx.fillStyle = '#fff';
		ctx.fillRect(0,0,this.width,this.height);
		var fontHeight = 20;
		ctx.fillStyle = '#000';
		var widthpos = 20;
		for (var fsindex in this.sentences[index]) {
			var fs = this.sentences[index][fsindex];
			var width = fs.getWidth(ctx);
			fs.setFontHeight(fontHeight);
			//fs.drawFeatureStructure(ctx,widthpos,2, fontHeight);
			fs.drawRecursive(ctx,widthpos,2, fontHeight);
			widthpos = widthpos + width + 20;
		}
	}
	
	
	// Piirretään syntax tree, juurisolmut löytyy rootarraysta ja puurakenne löytyy featurestructuren childeista.
	// Ja koko lauseen semanttinan analyysi löytyy tavallaan juuri featurestructuren argumenteista (joka hierarkinen myös)
	
	// - Laske leveydet, aluksi kiinteä, määritä puun korkeus
	// - Asemoi structuret sopivasti, ja piirrä ihan normaalilla fs.draw():illa oikeaan positioon
	// - Tämän ei pitäisi olla hirveän paha, tosin tämä pitäisi toteuttaa jo ihan ensimmäiseksi
	this.drawAnalyse = function(context, x, y, fontHeight) {
	
		//console.log('analyse.drawAnalyse...');
		//console.log('currentindex - '+this.currentindex);
		
		context.fillStyle = '#fff';
		context.fillRect(0,0,this.width,this.height);
		context.fillStyle = '#000';
		var structs = this.rootarray;
		var counter = 0;
		var currentindex = this.currentindex;
	
		var xpos = x;
		for (var index in this.rootarray) {
			var fs = this.rootarray[index];
			if ((currentindex == counter) || (counter == (currentindex+1))) {
				if (counter == (currentindex+1)) {
					var curfs = this.rootarray[this.currentindex+1];
					//console.log('drawanalyse fs2');
					//console.dir(fs);
					fs.drawRecursive(context, xpos, y, fontHeight, 'blue', curfs.ID);
				} else {
					//console.log('drawanalyse fs1');
					//console.dir(fs);
					var curfs = this.rootarray[this.currentindex];
					fs.drawRecursive(context, xpos, y, fontHeight, 'blue', curfs.ID);
				}
				//fs.drawRecursive(context,xpos, y, fontHeight, 'blue');
			} else {
				//console.dir(fs);
				fs.drawRecursive(context,xpos, y, fontHeight);
			}
			xpos = xpos + fs.getRecursiveWidth(context) + 20;
			counter++;
		}
	}
}
	
