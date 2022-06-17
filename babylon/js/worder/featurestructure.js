

var globalFeatureIDcounter = 0;

function FeatureStructure(wordclassID, name, conceptID, conceptName) {
	
	globalFeatureIDcounter++;
	this.ID = globalFeatureIDcounter;
	this.wordclassID = wordclassID;
	this.name = name;
	this.conceptID = conceptID;
	this.conceptName = conceptName;
	this.arguments = new Map();
	this.features = new Map();
	this.components = new Map();
	this.requirements = new Map();
	this.childs = new Array();
		
	this.topSpacing = 3;
	this.leftSpacing = 3;
	this.fontHeight = 20;
	
	
	
	
	this.setFontHeight = function(size) {
		this.fontHeight = size;
	};
		
		
	// FeatureStucture.getCopy();
	this.getCopy = function() {
		var copy = new FeatureStructure(this.wordclassID, this.name, this.conceptID, this.conceptName);
		
		this.arguments.forEach((argumentFS, argumentID) => {
			copy.addArgument(argumentID,argumentFS.getCopy());
		})
		this.features.forEach((value, feature) => {
			copy.addFeature(feature,value);
		})
		this.requirements.forEach((componentArray, argument) => {
			for (var componentIndex in componentArray) {
				var componentID = componentArray[componentIndex];
				copy.addRequirement(argument,componentID);
			}
		})
		this.components.forEach((value, componentID) => {
			copy.addComponent(componentID);
		})
		return copy;
	};
	

	// FeatureStructrue.getRecursiveCopy ... kopioi myös lapset
	this.getRecursiveCopy = function() {
		var copy = new FeatureStructure(this.wordclassID, this.name, this.conceptID, this.conceptName);
		
		this.arguments.forEach((argumentFS, argumentID) => {
			//var argument = this.arguments[argumentname];
			//var argumentfs = null;
			//if (argument != null)  argumentfs = argument.getCopy();
			copy.addArgument(argumentID,argumentFS.getRecursiveCopy());
		})
		/*
		for (var argumentname in this.arguments) {
			var argument = this.arguments[argumentname];
			var argumentfs = null;
			if (argument != null)  argumentfs = argument.getCopy();
			copy.addArgument(argumentname,argumentfs);
		}
		*/
		/*
		for (var feature in this.features) {
			var value = this.features[feature];
			copy.addFeature(feature, value);
		}
		*/
		this.features.forEach((value, feature) => {
			copy.addFeature(feature,value);
		})
		
		this.requirements.forEach((componentArray, argument) => {
			for (var componentIndex in componentArray) {
				var componentID = componentArray[componentIndex];
				copy.addRequirement(argument, componentID);
			}
		})
		/*
		for (var argument in this.requirements) {
			var component = this.requirements[argument];
			copy.addRequirement(argument, component);
		}
		*/
		this.components.forEach((value, componentID) => {
			copy.addComponent(componentID);
		})
		/*
		for (var component in this.components) {
			copy.addComponent(component);
		}
		*/
		
		for (var childindex in this.childs) {
			var child = this.childs[childindex];
			var childcopy = child.getRecursiveCopy();
			copy.pushChild(childcopy);
		}
		return copy;
	};
		
		
		
		
	this.getRecursiveSemanticCopy = function() {
		var copy = new FeatureStructure(this.wordclassID, this.name, this.conceptID, this.conceptName);
		
		this.arguments.forEach((argumentFS, argumentID) => {
			//var argument = this.arguments[argumentname];
			var newArgumentFS = null;
			if (argumentFS != null)  newArgumentFS = argumentFS.getRecursiveSemanticCopy();
			//console.log('-- arguments -'+argumentID);
			copy.addArgument(argumentID,newArgumentFS);
		})
		/*
		for (var argumentname in this.arguments) {
			var argument = this.arguments[argumentname];
			var argumentfs = null;
			if (argument != null)  argumentfs = argument.getRecursiveSemanticCopy();
			copy.addArgument(argumentname,argumentfs);
		}
		*/
		/*
		for (var feature in this.features) {
			var shared = getSharedFeature(feature);
			if (shared != null) {
				var value = this.features[feature];
				var sharedValue = getSharedValue(value);
				copy.addFeature(shared, sharedValue);
			}
		}
		*/
		this.features.forEach((value, feature) => {
			console.log(' -- -- feature:'+feature+', value:'+value);
			var shared = getSharedFeature(feature);
			//console.log(' -- -- shared:'+shared);
			if (shared > 0) {
				var sharedValue = getSharedValue(value);
				copy.addFeature(shared, sharedValue);
			}
		})
		
		
		this.components.forEach((value, component) => {
			//console.log(' -- -- component:'+component+', value:'+value);
			copy.addComponent(value);
			/*
			var shared = getSharedFeature(feature);
			if (shared > 0) {
				var sharedValue = getSharedValue(value);
				copy.addFeature(shared, sharedValue);
			}
			*/
		})
		
		
		return copy;
	};
	
	
	this.getRecursiveSemanticCopyWithoutComponents = function() {
		
		var copy = new FeatureStructure(this.wordclassID, this.name, this.conceptID, this.conceptName);
		
		this.arguments.forEach((argumentFS, argumentID) => {
			//var argument = this.arguments[argumentname];
			var newArgumentFS = null;
			if (argumentFS != null)  newArgumentFS = argumentFS.getRecursiveSemanticCopyWithoutComponents();
			//console.log('-- arguments -'+argumentID);
			copy.addArgument(argumentID,newArgumentFS);
		})

		this.features.forEach((value, feature) => {
			console.log(' -- -- feature:'+feature+', value:'+value);
			var shared = getSharedFeature(feature);
			//console.log(' -- -- shared:'+shared);
			if (shared > 0) {
				var sharedValue = getSharedValue(value);
				copy.addFeature(shared, sharedValue);
			}
		})
		
		return copy;
	};
	
	
	
	
	this.getRecursiveTargetCopy = function() {
		var copy = new FeatureStructure(this.wordclassID, this.name, this.conceptID, this.conceptName);
		this.arguments.forEach((argumentFS, argumentID) => {
			var newArgumentFS = null;
			if (argumentFS != null)  newArgumentFS = argumentFS.getRecursiveTargetCopy();
			copy.addArgument(argumentID,newArgumentFS);
		})
		/*
		for (var argumentname in this.arguments) {
			var argument = this.arguments[argumentname];
			var argumentfs = null;
			if (argument != null)  argumentfs = argument.getRecursiveTargetCopy();
			copy.addArgument(argumentname,argumentfs);
		}
		*/
		/*
		for (var feature in this.features) {
			var shared = getTargetFeature(feature);
			if (shared != null) {
				var value = this.features[feature];
				var sharedValue = getTargetValue(value);
				copy.addFeature(shared, sharedValue);
			}
		}
		*/
		this.features.forEach((value, feature) => {
			var shared = getTargetFeature(parseInt(feature));
			if (shared > 0) {
				var sharedValue = getTargetValue(value);
				//console.log(' -- shared value - '+sharedValue);
				copy.addFeature(shared, sharedValue);
			}
		});
		
		this.components.forEach((value, component) => {
			//console.log(' -- -- component:'+component+', value:'+value);
			copy.addComponent(value);
		})
		
		return copy;
	};
	
	
	this.getConceptsRecursively = function(conceptarray) {
		
		conceptarray.push(this.conceptID);
		this.arguments.forEach((argumentFS, argumentID) => {
			if (argumentFS != null)  argumentFS.getConceptsRecursively(conceptarray);
		})
		/*
		for (var argumentname in this.arguments) {
			var argument = this.arguments[argumentname];
			if (argument != null)  argumentfs = argument.getConceptsRecursively(conceptarray);
		}
		*/
		return;
	};
	
	
	
	
	// TODO: pitäisi toimia rekursiivisesti, en tiedä palauttaako analysewordsJSON kaikki tarpeelliset, todnäk kyllä
	this.setWordFeaturesRecursively = function(globalwordconceptlinks, globalwordfeatures, features) {
		//console.log(' --- this concept - '+this.conceptID);
		//console.dir(globalwordconceptlinks);
		console.log('globalwordfeatures');
		console.dir(globalwordfeatures);
		var wordID = globalwordconceptlinks.get(parseInt(this.conceptID));
		//console.log(' --- wordID found - '+wordID);
		var wordclassfeatures = globalwordfeatures.get(wordID);
		
		for (var feat in this.features) {
			//console.log(' --- ffff - '+feat);
		}
		
		wordclassfeatures.forEach((valueID, featureID) => {
			//var valueID = wordclassfeatures[featureID];
			//var featureStr = features[featureID];
			//var valueStr = features[valueID];
			
			if (this.features.get(featureID)) {
				//console.log(' --- wordfeature already setted');
			} else {
				this.addFeature(featureID, valueID);
			}
			
			////console.log(' --- wordfeature - '+featureStr);
			////console.log(' --- wordfeature - '+this.features[featureStr]);
			
			//if (this.features[featureStr] == undefined) {
				////console.log(' --- wordfeature undefined');
				////console.log(' --- wordfeature not setted - '+featureID+' - '+valueID);
				//this.addFeature(featureStr, valueStr);
			//} else {
				////console.log(' --- wordfeature already setted');
			//}
		})
		//for (var featureID in wordclassfeatures) {
		//}
		
		this.arguments.forEach((argumentFS, argumentID) => {
			argumentFS.setWordFeaturesRecursively(globalwordconceptlinks, globalwordfeatures, features);
		})
		/*
		for (var argument in this.arguments) {
			var fs = this.arguments[argument];
			fs.setWordFeaturesRecursively(globalwordconceptlinks, globalwordfeatures, features);
		}
		*/
		return;
	};
	
	
	
	
	this.pushChild = function(featurestructure) {
			this.childs.push(featurestructure);
	};
		

	this.popChild = function() {
			return this.childs.pop();
	};
	
	
	this.removeAllChilds = function()  {
		while(this.childs.length > 0) {
			this.childs.pop();
		}
	}
	
	
	this.getChild = function(index) {
		//console.log('childcount - '+this.childs.length);
		var counter = 0;
		for (var key in this.childs) {
			//console.log('childindex - '+counter+' - ' + index);
			if (counter == index) return this.childs[key];
			counter++;
		}
		return null;
	};

		
	this.childCount = function() {
			
			var counter = 0;
			for (var item in this.childs) {
				counter++;
			}
			
			if (this.childs.length != counter) {
				console.log('!!! Array child length != counter');
				alert('Array child length != counter');
				return;
			} else {
				return counter;
			}			
		};
		
		
	this.addArgument = function(argumentID, argumentFS) {
		//console.log('xxx add argument - '+argumentname+' - '+(typeof argument));
		//this.arguments[argumentname] = argument;
		this.arguments.set(argumentID, argumentFS);
	};

	
		
	this.removeArgument = function(argumentID) {
		//console.log('xxx remove argument - '+argumentname);
		this.arguments.delete(argumentID);
		/*
		var newArguments = new Array();
		for (var loopname in this.arguments) {
			var argumentvalue = this.arguments[loopname];
			if (argumentname == loopname) {
			} else {
				newArguments[loopname] = argumentvalue;
			}
		}
		*/
		//this.arguments = newArguments;
	};
		
		
	this.addFeature = function(feature, value) {
		//console.log(' --- fs addFeature - '+feature+' - '+value);
		this.features.set(feature, value);
	};

	
	this.addComponent = function(componentID) {
		this.components.set(componentID, componentID);
		//this.components[component] = component;
	};

	this.addRequirement = function(argument, component) {
		if (this.requirements.has(argument)) {
			//console.log(' - addrequirement - has - '+this.ID+' - '+argument+' - '+component);
			var componentArray = this.requirements.get(argument);
			componentArray.push(component);
		} else {
			//console.log(' - addrequirement - new - '+this.ID+' - '+argument+' - '+component);
			var componentArray = new Array();
			componentArray.push(component);
			this.requirements.set(argument, componentArray);
		}
	};

		
	this.removeRequirement = function(argumentID) {
		this.requirements.delete(argumentID);
		//delete this.requirements[argument];
	};
		
	
	this.drawRecursive = function(context, x, y, fontHeight, background, selectedID) {
			
		//console.log('drawrecursive - '+fontHeight);
		var height = this.getHeight(context, fontHeight);
		//console.log('draw recursive height - '+height);
		var totalWidth = this.getRecursiveWidth(context);
		var width = this.getWidth(context);
		
		//console.log('draw selectedID = '+selectedID);
		this.drawFeatureStructureFull(context, x + (totalWidth-width)/2, y, fontHeight, background, selectedID);
		//this.drawRecursive(context, x + (totalWidth-width)/2, y, fontHeight, background, selectedID);
		var counter = 0;
		var xpos = x;
			
		for (var item in this.childs) {
			var child = this.childs[item];
			//console.log('childi - '+child.name);
			var childTotalWidth = child.getRecursiveWidth(context);
			var childWidth = child.getWidth(context);
			child.drawRecursive(context, xpos, y+height+40,fontHeight, background, selectedID);
			
			context.beginPath();
			context.moveTo(x+totalWidth/2,y+height+5);
			context.lineTo(xpos+childTotalWidth/2-childWidth/2+40,y+height+35);
			context.stroke();
			xpos = xpos + childTotalWidth + 20;
			counter++;
		}
	};


	this.getArgumentCount = function() {
		//return this.arguments.length;
		
		var counter = 0;
		this.arguments.forEach((argumentFS, argumentID) => {
			counter++;
		})
		/*
		for (var key in this.arguments) {
			counter++;
		}
		*/
		return counter;
	};

	
		
	this.getArgumentValue = function(argumentID) {
		//console.log('getArgumentValue - trying to found '+argumentname);
		return this.arguments.get(argumentID);
		/*
		for (var argument in this.arguments) {
			var argumentvalue = this.arguments[argument];
			//console.log('getArgumentValue - '+argumentname+' - '+argument);
			if (argumentname == argument) return argumentvalue;
		}
		*/
	};
		
	
		
	this.drawRecursiveOnlyArguments = function(context, x, y, fontHeight, background) {
			
			//console.log('drawRecursiveOnlyArguments - '+fontHeight);
			
			var height = this.getHeight(context, fontHeight);
			//console.log('draw recursive height - '+height);
			var totalWidth = this.getRecursiveWidth(context);
			var width = this.getWidth(context);
			
			this.drawWithOnlyArguments(context, x + (totalWidth-width)/2, y, fontHeight, background);
			/*
			var counter = 0;
			var xpos = x;
			for (var item in this.childs) {
				var child = this.childs[item];
				var childTotalWidth = child.getRecursiveWidth(context);
				var childWidth = child.getWidth(context);
				child.drawRecursive(context, xpos, y+height+40,fontHeight);
				
				context.beginPath();
				context.moveTo(x+totalWidth/2,y+height+5);
				context.lineTo(xpos+childTotalWidth/2-childWidth/2+40,y+height+35);
				context.stroke();
				xpos = xpos + childTotalWidth + 20;
				counter++;
			}
			*/
	};

		
	this.drawWithOnlyArguments = function(context, x, y, fontHeight, background) {

		
			//console.log('drawWithOnlyArguments - xxxxx');
			
			context.textBaseline = 'top';
			context.lineWidth= 2;
			
			var wordclassStr = wAbbs.get(this.wordclassID);
			var str =  wordclassStr + 'P (' + this.name + ')';
			//var str = this.wordclass + 'P (' + this.name + ')';
			//console.log('drawing... '+this.name);

			context.fillText(str, x, y);
			y = y + fontHeight + this.topSpacing;	
			var maxWidth = 0;
			var argumentcount = 0;
			var width = 0;
			this.arguments.forEach((argumentFS, argumentID) => {
				var argumentStr = aNames.get(argumentID);
				width = context.measureText(argumentStr).width;
				if (width > maxWidth) maxWidth = width;
				argumentcount ++;
			})
			/*
			for (var argument in this.arguments) {
				width = context.measureText(argument).width;
				if (width > maxWidth) maxWidth = width;
				argumentcount ++;
			}
			*/
			var totalheight = this.getArgumentHeight(context, fontHeight);
			//console.log('totalheight - '+totalheight);
			//console.log('argumentcount - '+argumentcount);

			if (argumentcount > 0) {

				//console.log('fontHeight - '+fontHeight);

				var height = this.getArgumentHeight(context, fontHeight);
				context.beginPath();
				context.moveTo(x+(2*this.leftSpacing),y);
				context.lineTo(x,y);
				context.lineTo(x,y+totalheight-fontHeight-this.topSpacing);
				context.lineTo(x+(2*this.leftSpacing),y+totalheight-fontHeight-this.topSpacing);
				context.stroke();

				var width = this.getArgumentWidth(context);
				context.beginPath();
				context.moveTo(x+width-(2*this.leftSpacing),y);
				context.lineTo(x+width,y);
				context.lineTo(x+width,y+totalheight-fontHeight-this.topSpacing);
				context.lineTo(x+width-(2*this.leftSpacing),y+totalheight-fontHeight-this.topSpacing);
				context.stroke();

				var extraWidth = context.measureText('=').width;
				this.arguments.forEach((argumentFS, argumentID) => {
					var argumentStr = aNames.get(argumentID);
					y = y + this.topSpacing;
					//var value = this.arguments[argument];
					context.fillText(argumentStr, x+this.leftSpacing, y);
					context.fillText('=', x+maxWidth+(2*this.leftSpacing), y);
					argumentFS.drawWithOnlyArguments(context, x+maxWidth+extraWidth+(4*this.leftSpacing), y,fontHeight);
					//value.drawWithOnlyArguments(context, x+maxWidth+extraWidth+(4*this.leftSpacing), y,fontHeight);
					y = y + value.getHeight(context, fontHeight);
				})
				/*
				for (var argument in this.arguments) {
					y = y + this.topSpacing;
					var value = this.arguments[argument];
					context.fillText(argument, x+this.leftSpacing, y);
					context.fillText('=', x+maxWidth+(2*this.leftSpacing), y);
					value.drawWithOnlyArguments(context, x+maxWidth+extraWidth+(4*this.leftSpacing), y,fontHeight);
					y = y + value.getHeight(context, fontHeight);
				}
				*/
			}
	};
		
		
		
		
	this.drawFeatureStructureFull = function(context, x, y, fontHeight, background, selectedID) {

			//console.log('drawFeatureStructure - '+fontHeight);

			var equalSignWidth = context.measureText('=').width;
			
			this.fontHeight = fontHeight;
			//console.log('background - '+background);
			
			var width = this.getWidthFull(context, fontHeight);
			var height = this.getHeightFull(context, fontHeight);
			
			
			//console.log('--- height - '+height);
			
			if (this.ID == selectedID) {
				context.fillStyle = '#33FFC7'; 
				context.fillRect(x,y,width,height);
				context.fillStyle = '#000';
			} else {
				context.fillStyle = '#fff';
				context.fillRect(x,y,width,height);
				context.fillStyle = '#000';
				
				/*
				if (background != null) {
					context.fillStyle = '#98FB98';
					context.fillRect(x,y,width,height);
					context.fillStyle = '#000';
				}
				*/
			}
			
			context.textBaseline = 'top';
			context.lineWidth= 2;
			
			var wordclassStr = wAbbs.get(this.wordclassID);
			var str = wordclassStr + 'P (' + this.name + '/'+this.ID + ','+this.conceptID+'/'+this.conceptName+')';
			//var str = this.wordclass + 'P (' + this.name + '-'+this.conceptID+'/'+this.ID+')';
			context.fillText(str, x, y);
			y = y + fontHeight;	

// piirrä requirementit
			var maxWidth = 0;
			var argumentcount = 0;
			var width = 0;
			var argumentsHeight = 0;
			this.arguments.forEach((argumentFS, argumentID) => {
				var argumentStr = aNames.get(argumentID);
				//console.log(' --- arg - '+argumentID +' - '+argumentStr);
				width = context.measureText(argumentStr).width;
				if (width > maxWidth) maxWidth = width;
				//var value = this.arguments[argumentID];
				var valueheight = argumentFS.getHeightFull(context, fontHeight);
				argumentsHeight = argumentsHeight + valueheight + this.topSpacing;
				argumentcount ++;
			})
			/*
			for (var argumentID in this.arguments) {
				argument = fNames.get(argumentID);
				console.log(' --- arg - '+argumentID +' - '+argument);
				width = context.measureText(argument).width;
				if (width > maxWidth) maxWidth = width;
				var value = this.arguments[argumentID];
				var valueheight = value.getHeightFull(context, fontHeight);
				argumentsHeight = argumentsHeight + valueheight + this.topSpacing;
				argumentcount ++;
			}
			*/
			
			//var totalheight = this.getArgumentHeight(context, fontHeight);
			//totalheight = 20;
			//console.log('totalheight - '+totalheight);
			//console.log('argumentcount - '+argumentcount);
			//console.log('argumentsHeight - '+argumentsHeight);
		
			if (argumentcount > 0) {

				//console.log('fontHeight - '+fontHeight);

				//var height = this.getArgumentHeight(context, fontHeight);
				context.beginPath();
				context.moveTo(x+(2*this.leftSpacing),y);
				context.lineTo(x,y);
				context.lineTo(x,y+argumentsHeight);
				context.lineTo(x+(2*this.leftSpacing),y+argumentsHeight);
				//context.lineTo(x,y+totalheight-fontHeight-this.topSpacing);
				//context.lineTo(x+(2*this.leftSpacing),y+totalheight-fontHeight-this.topSpacing);
				context.stroke();

				var width = this.getWidthFull(context) + equalSignWidth;
				//console.log('arguments width - '+width);
				context.beginPath();
				context.moveTo(x+width-(2*this.leftSpacing),y);
				context.lineTo(x+width,y);
				context.lineTo(x+width,y+argumentsHeight);
				context.lineTo(x+width-(2*this.leftSpacing),y+argumentsHeight);
				//context.lineTo(x+width,y+totalheight-fontHeight-this.topSpacing);
				//context.lineTo(x+width-(2*this.leftSpacing),y+totalheight-fontHeight-this.topSpacing);
				context.stroke();

				
				this.arguments.forEach((argumentFS, argumentID) => {
					y = y + this.topSpacing;
					//var value = fNames.get(this.arguments[argument]);
					var argumentStr = aNames.get(argumentID);
					context.fillText(argumentStr, x+this.leftSpacing, y);
					context.fillText('=', x+maxWidth+(2*this.leftSpacing), y);
					if (selectedID > 0) {
						//console.log('selectedID -'+selectedID+'-');
						//console.log('this.ID -'+this.ID+'-');
						//console.log('argumentFS.ID -'+argumentFS.ID+'-');
						if (selectedID == this.ID) {
							//console.log('equals...');
							argumentFS.drawFeatureStructureFull(context, x+maxWidth+equalSignWidth+(2*this.leftSpacing), y,fontHeight, background, argumentFS.ID);
						} else {
							//console.log('not equals...');
							argumentFS.drawFeatureStructureFull(context, x+maxWidth+equalSignWidth+(2*this.leftSpacing), y,fontHeight);
						}
					} else {
						argumentFS.drawFeatureStructureFull(context, x+maxWidth+equalSignWidth+(2*this.leftSpacing), y,fontHeight);
					}
					y = y + argumentFS.getHeightFull(context, fontHeight);
				})
				
				/*
				for (var argument in this.arguments) {
					y = y + this.topSpacing;
					var value = fNames.get(this.arguments[argument]);
					context.fillText(argument, x+this.leftSpacing, y);
					context.fillText('=', x+maxWidth+(2*this.leftSpacing), y);
					if (selectedID > 0) {
						if (selectedID == this.ID) {
							value.drawFeatureStructureFull(context, x+maxWidth+equalSignWidth+(2*this.leftSpacing), y,fontHeight, background, value.ID);
						} else {
							value.drawFeatureStructureFull(context, x+maxWidth+equalSignWidth+(2*this.leftSpacing), y,fontHeight);
						}
					} else {
						value.drawFeatureStructureFull(context, x+maxWidth+equalSignWidth+(2*this.leftSpacing), y,fontHeight);
					}
					//value.drawFeatureStructure(context, x+maxWidth+equalSignWidth+(2*this.leftSpacing), y,fontHeight);
					//value.drawWithOnlyArguments(context, x+maxWidth+equalSignWidth+(2*this.leftSpacing), y,fontHeight);
					y = y + value.getHeightFull(context, fontHeight);
					//y = y + value.getArgumentHeight(context, fontHeight);
					
					// 
					//y = y + fontHeight;
				}
				*/
				y = y + this.topSpacing;
			}

// piirrä requirementit
			var maxleftwidth = 0;
			var requirementcount = 0;
			var requirementsHeight = 0;
			var leftwidth = 0;
			var value = 0;
			var righttwidth = 0;
			var rightmaxwidth = 0;
			var totalheight = 0;
			var totalwidth = 0;
			
			//console.log(' - ---- xxx');
			this.requirements.forEach((componentArray, argumentID) => {
				//console.log(' - argumentID - '+argumentID);
				for (var componentIndex in componentArray) {
					var componentID = componentArray[componentIndex];
					//console.log(' - - compoenentID - '+componentID);
					var argumentStr = aNames.get(argumentID);
					leftwidth = context.measureText(argumentStr).width;
					var componentStr = cAbbs.get(componentID);
					righttwidth = context.measureText(componentStr).width;
					if (leftwidth > maxleftwidth) maxleftwidth = leftwidth;
					if (righttwidth > rightmaxwidth) rightmaxwidth = righttwidth;
					totalheight = totalheight + fontHeight + this.topSpacing;
					requirementsHeight = requirementsHeight + fontHeight + this.topSpacing;
					requirementcount ++;
				}
			})
			totalwidth = leftwidth + righttwidth + 8 * this.leftSpacing;
			//console.log(' - requirementcount - '+requirementcount);
			
			if (requirementcount > 0) {

				context.beginPath();
				context.moveTo(x+(2*this.leftSpacing),y);
				context.lineTo(x,y);
				context.lineTo(x,y+totalheight);
				context.lineTo(x+(2*this.leftSpacing),y+totalheight);
				context.stroke();

				context.beginPath();
				context.moveTo(x+totalwidth-(2*this.leftSpacing),y);
				context.lineTo(x+totalwidth,y);
				context.lineTo(x+totalwidth,y+requirementsHeight);
				context.lineTo(x+totalwidth-(2*this.leftSpacing),y+requirementsHeight);
				context.stroke();
				
				var extraWidth = context.measureText('=').width;
				this.requirements.forEach((componentArray, argumentID) => {
					for (var componentIndex in componentArray) {
						var componentID = componentArray[componentIndex];
						y = y + this.topSpacing;
						var componentStr = cAbbs.get(componentID);
						var argumentStr = aNames.get(argumentID);
						context.fillText(argumentStr, x+this.leftSpacing, y);
						context.fillText('=', x+maxleftwidth+(2*this.leftSpacing), y);
						context.fillText(componentStr, x+maxleftwidth+(7*this.leftSpacing), y);
						y = y + fontHeight;
					}
				})
				y = y + this.topSpacing;
			}

// piirrä featuret
			//y = y + this.topSpacing;
			var maxleftwidth = 0;
			var featurecount = 0;
			var leftwidth = 0;
			var value = 0;
			var rightWidth = 0;
			var rightmaxwidth = 0;
			var totalheight = 0;
			var totalwidth = 0;
			
			this.features.forEach((value, feature) => {
				var featureStr = fNames.get(feature);
				leftwidth = context.measureText(featureStr).width;
				valueStr = fNames.get(value);
				rightWidth = context.measureText(valueStr).width;
				if (leftwidth > maxleftwidth) maxleftwidth = leftwidth;
				if (rightWidth > rightmaxwidth) rightmaxwidth = rightWidth;
				totalheight = totalheight + fontHeight + this.topSpacing;
				featurecount ++;
			});
			/*
			for (var featureID in this.features) {
				var feature = fNames.get(this.features[featureID]);
				leftwidth = context.measureText(feature).width;
				value = fNames.get(this.features[featureID]);
				rightWidth = context.measureText(value).width;
				if (leftwidth > maxleftwidth) maxleftwidth = leftwidth;
				if (rightWidth > rightmaxwidth) rightmaxwidth = rightWidth;
				totalheight = totalheight + fontHeight + this.topSpacing;
				featurecount ++;
			}
			*/
			totalwidth = maxleftwidth + rightmaxwidth + equalSignWidth + 6*this.topSpacing;

			if (featurecount > 0) {

				context.beginPath();
				context.moveTo(x+(2*this.leftSpacing),y);
				context.lineTo(x,y);
				context.lineTo(x,y+totalheight);
				context.lineTo(x+(2*this.leftSpacing),y+totalheight);
				context.stroke();

				context.beginPath();
				context.moveTo(x+totalwidth-(2*this.leftSpacing),y);
				context.lineTo(x+totalwidth,y);
				context.lineTo(x+totalwidth,y+totalheight);
				context.lineTo(x+totalwidth-(2*this.leftSpacing),y+totalheight);
				context.stroke();

				var extraWidth = context.measureText('=').width;
				this.features.forEach((value, feature) => {
					y = y + this.topSpacing;
					var valueStr = fAbbs.get(value);
					var featurestr = fNames.get(feature);
					context.fillText(featurestr, x+this.leftSpacing, y);
					context.fillText('=', x+maxleftwidth+(2*this.leftSpacing), y);
					context.fillText(valueStr, x+maxleftwidth+(7*this.leftSpacing), y);
					y = y + fontHeight;
				});
				/*
				for (var feature in this.features) {
					y = y + this.topSpacing;
					var value = fAbbs.get(this.features[feature]);
					var featurestr = fNames.get(parseInt(feature));
					context.fillText(featurestr, x+this.leftSpacing, y);
					context.fillText('=', x+maxleftwidth+(2*this.leftSpacing), y);
					context.fillText(value, x+maxleftwidth+(7*this.leftSpacing), y);
					y = y + fontHeight;
				}
				*/
			}

// piirrä componentit
			y = y + this.topSpacing;
			var width = 0;
			var componentcount = 0;
			var totalwidth = 0;
			var totalheight = 0;
			this.components.forEach((value, componentID) => {
				var componentStr = cAbbs.get(componentID);
				width = context.measureText(componentStr).width;
				if (width > totalwidth) totalwidth = width;
				totalheight = totalheight + fontHeight + this.topSpacing;
				componentcount ++;
			})
			/*
			for (var component in this.components) {
				width = context.measureText(component).width;
				if (width > totalwidth) totalwidth = width;
				totalheight = totalheight + fontHeight + this.topSpacing;
				componentcount ++;
			}
			*/
			totalwidth = totalwidth + 2 * this.leftSpacing;
			if (componentcount > 0) {

				context.beginPath();
				context.moveTo(x+(2*this.leftSpacing),y);
				context.lineTo(x,y);
				context.lineTo(x,y+totalheight);
				context.lineTo(x+(2*this.leftSpacing),y+totalheight);
				context.stroke();

				context.beginPath();
				context.moveTo(x+totalwidth-(2*this.leftSpacing),y);
				context.lineTo(x+totalwidth,y);
				context.lineTo(x+totalwidth,y+totalheight);
				context.lineTo(x+totalwidth-(2*this.leftSpacing),y+totalheight);
				context.stroke();

				var extraWidth = context.measureText('=').width;
				this.components.forEach((value, componentID) => {
					var componentStr = cAbbs.get(componentID);
					y = y + this.topSpacing;
					context.fillText(componentStr, x+this.leftSpacing, y);
					y = y + fontHeight;
				})
				/*
				for (var component in this.components) {
					y = y + this.topSpacing;
					context.fillText(component, x+this.leftSpacing, y);
					y = y + fontHeight;
				}
				*/
			}
	};

		
		// FeatureStructure.getHeight
	this.getHeightFull = function(context, fontHeight) {
			var totalheight = 0;
			var valueheight = 0;
			var itemcount = 0;
			
			var argumentcount = 0;
			this.arguments.forEach((argumentFS, argumentID) => {
				valueheight = argumentFS.getHeightFull(context, fontHeight);
				totalheight = totalheight + valueheight + this.topSpacing;
				argumentcount++;
				itemcount++;
			})
			if (argumentcount > 0) totalheight = totalheight + this.topSpacing;
			
			var requirementcount = 0;
			this.requirements.forEach((componentArray, argumentID) => {
				for (var componentIndex in componentArray) {
					var componentID = componentArray[componentIndex];	// turha
					totalheight = totalheight + fontHeight + this.topSpacing;
					requirementcount++;
				}
			})
			if (requirementcount > 0) totalheight = totalheight + this.topSpacing;
			
			
			var featurecount = 0;
			
			this.features.forEach((value, feature) => {
				totalheight = totalheight + fontHeight + this.topSpacing;
				itemcount++;
				featurecount++;
			});
			/*
			for (var index in this.features) {
				totalheight = totalheight + fontHeight + this.topSpacing;
				itemcount++;
				featurecount++;
			}
			*/
			if (featurecount > 0) totalheight = totalheight + this.topSpacing;
			
			var componentcount = 0;
			this.components.forEach((value, componentID) => {
				totalheight = totalheight + fontHeight + this.topSpacing;
				itemcount++;
				componentcount++;
			})
			/*
			for (var index in this.components) {
				totalheight = totalheight + fontHeight + this.topSpacing;
				//console.log(' -- getHeight components - '+index);
				//console.log(' -- height - '+argument+' - '+totalheight);
				itemcount++;
				componentcount++;
			}
			*/
			if (componentcount > 0) totalheight = totalheight + this.topSpacing;
			
			if (itemcount == 0) return fontHeight;
//			console.log('topSpacing - '+this.topSpacing);
			return totalheight+fontHeight;
	};
		
		
	this.getWidthFull = function(context) {
//			this.getWidthRecursive(context, this, this.leftSpacing);
//			console.log('getwidthi...');
			
			var equalSignWidth = context.measureText('=').width + (2*this.leftSpacing);
			
			
			var totalwidth = 0;
			var maxnamewidth = 0;
			var width = 0;
			
			
			var value = null;
			var nameWidth = 0;
			var valueWidth = 0;
			
			var argumentcount = 0;
			var maxArgumetWidth = 0;
			var maxValueWidth = 0;
			var maxNameWidth = 0;
			
			var wordclassStr = wAbbs.get(this.wordclassID);
			var str = wordclassStr + ' (' + this.name + ')';
			//var str = this.wordclass + ' (' + this.name + ')';
			var totalWidth = context.measureText(str).width + (this.leftSpacing);
			
			this.arguments.forEach((argumentFS, argumentID) => {
				var argumentStr = aNames.get(argumentID);
				nameWidth = context.measureText(argumentStr).width + equalSignWidth + (2*this.leftSpacing);
				valueWidth = argumentFS.getWidthFull(context, this.leftSpacing);
				if (nameWidth > maxNameWidth) maxNameWidth = nameWidth;
				if (maxValueWidth < valueWidth) maxValueWidth = valueWidth;
			})
			if ((maxValueWidth + maxNameWidth) > totalWidth) totalWidth = maxValueWidth + maxNameWidth;
			
			maxValueWidth = 0;
			maxNameWidth = 0;
			this.requirements.forEach((componentArray, argumentID) => {
				for (var componentIndex in componentArray) {
					var componentID = componentArray[componentIndex];
					var argumentStr = aNames.get(argumentID);
					nameWidth = context.measureText(argumentID).width + equalSignWidth;
					var componentStr = cAbbs.get(componentID);
					valueWidth = context.measureText(componentStr).width;
					if (nameWidth > maxNameWidth) maxNameWidth = nameWidth;
					if (maxValueWidth < valueWidth) maxValueWidth = valueWidth;
				}
			})
			if ((maxValueWidth + maxNameWidth) > totalWidth) totalWidth = maxValueWidth + maxNameWidth;
			
			maxValueWidth = 0;
			maxNameWidth = 0;
			this.features.forEach((value, feature) => {
				var featureStr = fNames.get(feature);
				nameWidth = context.measureText(featureStr).width + equalSignWidth + (2 * this.leftSpacing);
				var valueStr = fAbbs.get(value); 
				valueWidth = context.measureText(valueStr).width;
				if (nameWidth > maxNameWidth) maxNameWidth = nameWidth;
				if (maxValueWidth < valueWidth) maxValueWidth = valueWidth;
			});
			/*
			for (var index in this.features) {
				nameWidth = context.measureText(index).width + equalSignWidth + (2 * this.leftSpacing);
				var value = this.features[index];
				valueWidth = context.measureText(value).width;
				if (nameWidth > maxNameWidth) maxNameWidth = nameWidth;
				if (maxValueWidth < valueWidth) maxValueWidth = valueWidth;
			}
			*/
			if ((maxValueWidth + maxNameWidth) > totalWidth) totalWidth = maxValueWidth + maxNameWidth;
			
			maxValueWidth = 0;
			maxNameWidth = 0;
			this.components.forEach((value, componentID) => {
				var componentStr = cAbbs.get(componentID);
				nameWidth = context.measureText(componentStr).width;
				if (nameWidth > maxNameWidth) maxNameWidth = nameWidth;
			})
			/*
			for (var index in this.components) {
				nameWidth = context.measureText(index).width;
				if (nameWidth > maxNameWidth) maxNameWidth = nameWidth;
			}
			*/
			if (maxNameWidth > totalWidth) totalWidth = maxNameWidth;
			
			return totalWidth+(3*this.leftSpacing);
	};

		
		// featurestructure.draw
		this.drawFeatureStructure = function(context, x, y, fontHeight, background) {

			//console.log('drawFeatureStructure - '+fontHeight);

			var equalSignWidth = context.measureText('=').width;
			
			this.fontHeight = fontHeight;
			//console.log('background - '+background);
			
			var height = this.getHeight(context, fontHeight);
			var width = this.getWidth(context, fontHeight);
			//console.log('--- height - '+height);
			
			if (background != null) {
				context.fillStyle = '#98FB98';
				context.fillRect(x,y,width,height);
				context.fillStyle = '#000';
			}
			
			context.textBaseline = 'top';
			context.lineWidth= 2;
			
			var wordclassStr = wAbbs.get(this.wordclassID);
			var str = wordclassStr + 'P (' + this.name + ')';
			//var str = this.wordclass + 'P (' + this.name + ')';
			context.fillText(str, x, y);
			y = y + fontHeight;	

// piirrä requirementit
			var maxWidth = 0;
			var argumentcount = 0;
			var width = 0;
			var argumentsHeight = 0;
			this.arguments.forEach((argumentFS, argumentID) => {
				var argumentStr = aNames.get(argumentID);
				width = context.measureText(argumentStr).width;
				if (width > maxWidth) maxWidth = width;
				argumentsHeight = argumentsHeight + fontHeight + this.topSpacing;
				argumentcount ++;
			})
			/*
			for (var argument in this.arguments) {
				width = context.measureText(argument).width;
				if (width > maxWidth) maxWidth = width;
				argumentsHeight = argumentsHeight + fontHeight + this.topSpacing;
				argumentcount ++;
			}
			*/
			//var totalheight = this.getArgumentHeight(context, fontHeight);
			//totalheight = 20;
			//console.log('totalheight - '+totalheight);
			//console.log('argumentcount - '+argumentcount);
			//console.log('argumentsHeight - '+argumentsHeight);
		
			if (argumentcount > 0) {

				//console.log('fontHeight - '+fontHeight);

				//var height = this.getArgumentHeight(context, fontHeight);
				context.beginPath();
				context.moveTo(x+(2*this.leftSpacing),y);
				context.lineTo(x,y);
				context.lineTo(x,y+argumentsHeight);
				context.lineTo(x+(2*this.leftSpacing),y+argumentsHeight);
				//context.lineTo(x,y+totalheight-fontHeight-this.topSpacing);
				//context.lineTo(x+(2*this.leftSpacing),y+totalheight-fontHeight-this.topSpacing);
				context.stroke();

				var width = this.getArgumentWidth(context) + equalSignWidth;
				//console.log('arguments width - '+width);
				context.beginPath();
				context.moveTo(x+width-(2*this.leftSpacing),y);
				context.lineTo(x+width,y);
				context.lineTo(x+width,y+argumentsHeight);
				context.lineTo(x+width-(2*this.leftSpacing),y+argumentsHeight);
				//context.lineTo(x+width,y+totalheight-fontHeight-this.topSpacing);
				//context.lineTo(x+width-(2*this.leftSpacing),y+totalheight-fontHeight-this.topSpacing);
				context.stroke();
				
				this.arguments.forEach((argumentFS, argumentID) => {
					y = y + this.topSpacing;
					var argumentStr = aNames.get(argumentID);
					context.fillText(argumentStr, x+this.leftSpacing, y);
					context.fillText('=', x+maxWidth+(2*this.leftSpacing), y);
					argumentFS.drawWithOnlyArguments(context, x+maxWidth+equalSignWidth+(2*this.leftSpacing), y,fontHeight);
					y = y + argumentFS.getArgumentHeight(context, fontHeight);
				})
				/*
				for (var argument in this.arguments) {
					y = y + this.topSpacing;
					var value = this.arguments[argument];
					context.fillText(argument, x+this.leftSpacing, y);
					context.fillText('=', x+maxWidth+(2*this.leftSpacing), y);
					//value.drawFeatureStructure(context, x+maxWidth+equalSignWidth+(2*this.leftSpacing), y,fontHeight);
					value.drawWithOnlyArguments(context, x+maxWidth+equalSignWidth+(2*this.leftSpacing), y,fontHeight);
					//y = y + value.getHeight(context, fontHeight);
					y = y + value.getArgumentHeight(context, fontHeight);
					
					// 
					//y = y + fontHeight;
				}
				*/
				y = y + this.topSpacing;
			}

// piirrä requirementit
			var maxleftwidth = 0;
			var requirementcount = 0;
			var requirementsHeight = 0;
			var leftwidth = 0;
			var value = 0;
			var righttwidth = 0;
			var rightmaxwidth = 0;
			var totalheight = 0;
			var totalwidth = 0;
			
			this.requirements.forEach((componentArray, argumentID) => {
				for (var componentIndex in componentArray) {
					var componentID = componentArray[componentIndex];
					var argumentStr = aNames.get(argumentID);
					leftwidth = context.measureText(argumentStr).width;
					var componentStr = cAbbs.get(componentID);
					righttwidth = context.measureText(componentStr).width;
					if (leftwidth > maxleftwidth) maxleftwidth = leftwidth;
					if (righttwidth > rightmaxwidth) rightmaxwidth = righttwidth;
					totalheight = totalheight + fontHeight + this.topSpacing;
					requirementsHeight = requirementsHeight + fontHeight + this.topSpacing;
					requirementcount ++;
				}
			})
			totalwidth = leftwidth + righttwidth + 8 * this.leftSpacing;
		
			if (requirementcount > 0) {

				context.beginPath();
				context.moveTo(x+(2*this.leftSpacing),y);
				context.lineTo(x,y);
				context.lineTo(x,y+totalheight);
				context.lineTo(x+(2*this.leftSpacing),y+totalheight);
				context.stroke();

				context.beginPath();
				context.moveTo(x+totalwidth-(2*this.leftSpacing),y);
				context.lineTo(x+totalwidth,y);
				context.lineTo(x+totalwidth,y+requirementsHeight);
				context.lineTo(x+totalwidth-(2*this.leftSpacing),y+requirementsHeight);
				context.stroke();

				var extraWidth = context.measureText('=').width;

				this.requirements.forEach((componentArray, argumentID) => {
					for (var componentIndex in componentArray) {
						var componentID = componentArray[componentIndex];
						var argumentStr = aNames.get(argumentID);
						var componentStr = cAbbs.get(componentID);
						y = y + this.topSpacing;
						context.fillText(argumentStr, x+this.leftSpacing, y);
						context.fillText('=', x+maxleftwidth+(2*this.leftSpacing), y);
						context.fillText(componentStr, x+maxleftwidth+(7*this.leftSpacing), y);
						y = y + fontHeight;
					}
				})
				y = y + this.topSpacing;
			}

// piirrä featuret
			//y = y + this.topSpacing;
			var maxleftwidth = 0;
			var featurecount = 0;
			var leftwidth = 0;
			var value = 0;
			var rightWidth = 0;
			var rightmaxwidth = 0;
			var totalheight = 0;
			var totalwidth = 0;
			this.features.forEach((value, feature) => {
				var featureStr = fNames.get(feature);
				leftwidth = context.measureText(featureStr).width;
				var valueStr = fAbbs.get(feature);
				rightWidth = context.measureText(valueStr).width;
				if (leftwidth > maxleftwidth) maxleftwidth = leftwidth;
				if (rightWidth > rightmaxwidth) rightmaxwidth = rightWidth;
				totalheight = totalheight + fontHeight + this.topSpacing;
				featurecount ++;
			});
			/*
			for (var feature in this.features) {
				leftwidth = context.measureText(feature).width;
				value = this.features[feature];
				rightWidth = context.measureText(value).width;
				if (leftwidth > maxleftwidth) maxleftwidth = leftwidth;
				if (rightWidth > rightmaxwidth) rightmaxwidth = rightWidth;
				totalheight = totalheight + fontHeight + this.topSpacing;
				featurecount ++;
			}
			*/
			totalwidth = maxleftwidth + rightmaxwidth + equalSignWidth + 6*this.topSpacing;

			if (featurecount > 0) {

				context.beginPath();
				context.moveTo(x+(2*this.leftSpacing),y);
				context.lineTo(x,y);
				context.lineTo(x,y+totalheight);
				context.lineTo(x+(2*this.leftSpacing),y+totalheight);
				context.stroke();

				context.beginPath();
				context.moveTo(x+totalwidth-(2*this.leftSpacing),y);
				context.lineTo(x+totalwidth,y);
				context.lineTo(x+totalwidth,y+totalheight);
				context.lineTo(x+totalwidth-(2*this.leftSpacing),y+totalheight);
				context.stroke();

				var extraWidth = context.measureText('=').width;
				this.features.forEach((value, feature) => {
					var featureStr = fNames.get(feature);
					y = y + this.topSpacing;
					var valueStr = fAbbs.get(feature);
					context.fillText(featureStr, x+this.leftSpacing, y);
					context.fillText('=', x+maxleftwidth+(2*this.leftSpacing), y);
					context.fillText('xxxx3', x+maxleftwidth+(7*this.leftSpacing), y);
					y = y + fontHeight;
				});
				/*
				for (var feature in this.features) {
					y = y + this.topSpacing;
					var value = this.features[feature];
					context.fillText(feature, x+this.leftSpacing, y);
					context.fillText('=', x+maxleftwidth+(2*this.leftSpacing), y);
					context.fillText(value, x+maxleftwidth+(7*this.leftSpacing), y);
					y = y + fontHeight;
				}
				*/
			}

// piirrä componentit
			y = y + this.topSpacing;
			var width = 0;
			var componentcount = 0;
			var totalwidth = 0;
			var totalheight = 0;
			this.components.forEach((value, componentID) => {
				var componentStr = cAbbs.get(componentID);
				width = context.measureText(componentStr).width;
				if (width > totalwidth) totalwidth = width;
				totalheight = totalheight + fontHeight + this.topSpacing;
				componentcount ++;
			})
			/*
			for (var component in this.components) {
				width = context.measureText(component).width;
				if (width > totalwidth) totalwidth = width;
				totalheight = totalheight + fontHeight + this.topSpacing;
				componentcount ++;
			}
			*/
			totalwidth = totalwidth + 2 * this.leftSpacing;
			if (componentcount > 0) {

				context.beginPath();
				context.moveTo(x+(2*this.leftSpacing),y);
				context.lineTo(x,y);
				context.lineTo(x,y+totalheight);
				context.lineTo(x+(2*this.leftSpacing),y+totalheight);
				context.stroke();

				context.beginPath();
				context.moveTo(x+totalwidth-(2*this.leftSpacing),y);
				context.lineTo(x+totalwidth,y);
				context.lineTo(x+totalwidth,y+totalheight);
				context.lineTo(x+totalwidth-(2*this.leftSpacing),y+totalheight);
				context.stroke();
				
				var extraWidth = context.measureText('=').width;
				this.components.forEach((value, componentID) => {
					var componentStr = cAbbs.get(componentID);
					y = y + this.topSpacing;
					context.fillText(componentStr, x+this.leftSpacing, y);
					y = y + fontHeight;
				})
				/*
				for (var component in this.components) {
					y = y + this.topSpacing;
					context.fillText(component, x+this.leftSpacing, y);
					y = y + fontHeight;
				}
				*/
			}
		};

		
		// FeatureStructure.getHeight
	this.getHeight = function(context, fontHeight) {
			var totalheight = 0;
			var valueheight = 0;
			var itemcount = 0;
			
			var argumentcount = 0;
			this.arguments.forEach((argumentFS, argumentID) => {
				valueheight = argumentFS.getHeight(context, fontHeight);
				totalheight = totalheight + valueheight + this.topSpacing;
				argumentcount++;
				itemcount++;
			})
			if (argumentcount > 0) totalheight = totalheight + this.topSpacing;
			
			
			var requirementcount = 0;
			this.requirements.forEach((componentArray, argumentID) => {
				for (var componentIndex in componentArray) {
					var componentID = componentArray[componentIndex];
					totalheight = totalheight + fontHeight + this.topSpacing;
					requirementcount++;
				}
			})
			if (requirementcount > 0) totalheight = totalheight + this.topSpacing;
			
			
			var featurecount = 0;
			this.features.forEach((value, feature) => {
				totalheight = totalheight + fontHeight + this.topSpacing;
				itemcount++;
				featurecount++;
			});
			if (featurecount > 0) totalheight = totalheight + this.topSpacing;
			
			var componentcount = 0;
			this.components.forEach((value, componentID) => {
				totalheight = totalheight + fontHeight + this.topSpacing;
				itemcount++;
				componentcount++;
			})
			if (componentcount > 0) totalheight = totalheight + this.topSpacing;
			
			if (itemcount == 0) return fontHeight;
			return totalheight+fontHeight;
		};
		
		
	this.getArgumentHeight = function(context, fontHeight) {
			var totalHeight = 0;
			var argumentcount = 0;
			this.arguments.forEach((argumentFS, argumentID) => {
				var valueHeight = argumentFS.getArgumentHeight(context, fontHeight);
				totalHeight = totalHeight + valueHeight + this.topSpacing;
				argumentcount++;
			})
			/*
			for (var argument in this.arguments) {
				var value = this.arguments[argument];
				var valueHeight = value.getArgumentHeight(context, fontHeight);
				totalHeight = totalHeight + valueHeight + this.topSpacing;
				argumentcount++;
			}
			*/
			if (argumentcount == 0) return fontHeight;			
			return fontHeight + this.topSpacing +  totalHeight;
	};
		
		// FeatureStructure.getArgumentWidth ...
	this.getArgumentWidth = function(context) {
//		this.getWidthRecursive(context, this, this.leftSpacing);
			
			var equalSignWidth = context.measureText('=').width;
			
			var totalwidth = 0;
			var maxnamewidth = 0;
			var maxvaluewidth = 0;
			var width = 0;
			var namewidth = 0;
			var extraWidth = 0;
			var valueWidth = 0;
			var argumentcount = 0;

			this.arguments.forEach((argumentFS, argumentID) => {
				var argumentStr = argumentID;
				width = context.measureText(argumentStr).width;
				extraWidth = equalSignWidth + (2*this.leftSpacing);
				//var value = this.arguments[argument];
				valueWidth = argumentFS.getArgumentWidth(context, this.leftSpacing);
				namewidth = width+extraWidth;
				if (namewidth > maxnamewidth) maxnamewidth = namewidth;
				if (maxvaluewidth < valueWidth) maxvaluewidth = valueWidth;
				argumentcount++;
			})
			
			/*
			for (var argument in this.arguments) {
				width = context.measureText(argument).width;
				extraWidth = equalSignWidth + (2*this.leftSpacing);
				var value = this.arguments[argument];
				valueWidth = value.getArgumentWidth(context, this.leftSpacing);
				namewidth = width+extraWidth;
				if (namewidth > maxnamewidth) maxnamewidth = namewidth;
				if (maxvaluewidth < valueWidth) maxvaluewidth = valueWidth;
//				console.log('namewidth - '+namewidth+', valueWidth - '+valueWidth);
//				console.log('maxvaluewidth - '+maxvaluewidth+', maxnamewidth - '+maxnamewidth);
				argumentcount++;
			}
			*/
			if (argumentcount == 0) {
				var wordclassStr = wAbbs.get(this.wordclassID);
				var str = wordclassStr + ' (' + this.name + ')';
				//var str = this.wordclass + ' (' + this.name + ')';
				return context.measureText(str).width+(this.leftSpacing);
			}
			return maxnamewidth+maxvaluewidth+this.leftSpacing;
	};
		
		
	this.getRecursiveWidth = function(context) {
			
			var width = this.getWidth(context);
			var childWidth = 0;
			for (var index in this.childs) {
				//console.log('getRecursiveWidth child - '+index);
				var child = this.childs[index];
				childWidth = childWidth + child.getRecursiveWidth(context) + 20;
			}
			if (childWidth > width) return childWidth;
			return width;
	};
		
		
		// FeatureStructure.getWidth ...
	this.getWidth = function(context) {
//			this.getWidthRecursive(context, this, this.leftSpacing);
//			console.log('getwidthi...');
			
			var equalSignWidth = context.measureText('=').width + (2*this.leftSpacing);
			
			
			var totalwidth = 0;
			var maxnamewidth = 0;
			var width = 0;
			
			
			var value = null;
			var nameWidth = 0;
			var valueWidth = 0;
			
			var argumentcount = 0;
			var maxArgumetWidth = 0;
			var maxValueWidth = 0;
			var maxNameWidth = 0;
			
			var wordclassStr = wAbbs.get(this.wordclassID);
			var str = wordclassStr + ' (' + this.name + ')';
			//var str = this.wordclass + ' (' + this.name + ')';
			var totalWidth = context.measureText(str).width + (this.leftSpacing);
			
			this.arguments.forEach((argumentFS, argumentID) => {
				var argumentStr = aNames.get(argumentID);
				nameWidth = context.measureText(argumentStr).width + equalSignWidth + (2*this.leftSpacing);
				valueWidth = argumentFS.getArgumentWidth(context, this.leftSpacing);
				if (nameWidth > maxNameWidth) maxNameWidth = nameWidth;
				if (maxValueWidth < valueWidth) maxValueWidth = valueWidth;
			})
			
			if ((maxValueWidth + maxNameWidth) > totalWidth) totalWidth = maxValueWidth + maxNameWidth;
			
			maxValueWidth = 0;
			maxNameWidth = 0;
			this.requirements.forEach((componentArray, argumentID) => {
				for (var componentIndex in componentArray) {
					var componentID = componentArray[componentIndex];
					var argumentStr = aNames.get(argumentID);
					var componentStr = cAbbs.get(componentID);
					nameWidth = context.measureText(argumentStr).width + equalSignWidth;
					valueWidth = context.measureText(componentStr).width;
					if (nameWidth > maxNameWidth) maxNameWidth = nameWidth;
					if (maxValueWidth < valueWidth) maxValueWidth = valueWidth;
				}
			})
			if ((maxValueWidth + maxNameWidth) > totalWidth) totalWidth = maxValueWidth + maxNameWidth;
			
			maxValueWidth = 0;
			maxNameWidth = 0;
			this.features.forEach((value, feature) => {
				var featureStr = fNames.get(feature);
				nameWidth = context.measureText(featureStr).width + equalSignWidth + (2 * this.leftSpacing);
				var valueStr = fAbbs.get(feature);
				valueWidth = context.measureText(valueStr).width;
				if (nameWidth > maxNameWidth) maxNameWidth = nameWidth;
				if (maxValueWidth < valueWidth) maxValueWidth = valueWidth;
			});
			/*
			for (var index in this.features) {
				nameWidth = context.measureText(index).width + equalSignWidth + (2 * this.leftSpacing);
				var value = this.features[index];
				valueWidth = context.measureText(value).width;
				if (nameWidth > maxNameWidth) maxNameWidth = nameWidth;
				if (maxValueWidth < valueWidth) maxValueWidth = valueWidth;
			}
			*/
			if ((maxValueWidth + maxNameWidth) > totalWidth) totalWidth = maxValueWidth + maxNameWidth;
			
			maxValueWidth = 0;
			maxNameWidth = 0;
			this.components.forEach((value, componentID) => {
				var componentStr = cAbbs.get(componentID);
				nameWidth = context.measureText(componentStr).width;
				if (nameWidth > maxNameWidth) maxNameWidth = nameWidth;
			})
			/*
			for (var index in this.components) {
				nameWidth = context.measureText(index).width;
				if (nameWidth > maxNameWidth) maxNameWidth = nameWidth;
			}
			*/
			if (maxNameWidth > totalWidth) totalWidth = maxNameWidth;
			
			return totalWidth+(3*this.leftSpacing);
	};

		
		
	this.printContent = function() {
		console.log('++++++ name -' + name);
		var counter = 0;
		this.features.forEach((value, feature) => {
			var featureStr = fNames.get(feature);
			console.log('++++++ feature - ' + featureStr);
			counter++;
		});
		/*
		for (var feature in this.features) {
			console.log('++++++ feature - ' + feature);
		}
		*/
		//console.log('++++++ featurecount - ' + this.features.size);
		console.log('++++++ featurecount - ' + counter);
		this.toJSON();
	};
		
	
	
	this.toJSONString = function() {
			var str = "{";
			//console.log('fs to json');
			//console.log('feature to json');
			//console.log('-----------------')
			
			//console.log('name - '+this.name);
			str = str + '"name"="'+this.name+'",';
			str = str + '"wordclassID"="'+this.wordclassID+'",';
			str = str + '"conceptID"="'+this.conceptID+'",';
			//console.log('pos - '+this.wordclass);
			//console.log('conceptID - '+this.conceptID);
			str = str + '"features": [';
			this.features.forEach((value, feature) => {
				if (isSharedFeature(feature)) {
					//var value = this.features[feature];
					var valueStr = fAbbs.get(feature);
					str = str + '"feature":"' + valueStr + '"';
				} else {
					//var value = this.features[feature];
				}
			});
			/*
			for (var feature in this.features) {
				if (isSharedFeature(feature)) {
					var value = this.features[feature];
					str = str + '"feature":"' + value + '"';
				} else {
					var value = this.features[feature];
				}
			}
			*/
			str = str + '],';
			str = str + '"arguments": [';
			this.arguments.forEach((argumentFS, argumentID) => {
				var argumentStr = aNames.get(argumentID);
				str = str + '"' + argumentStr + '":';
				//var fs = this.arguments[argument];
				var fsstr = argumentFS.toJSONString();
				str = str + fsstr;
			})
			/*
			for (var argument in this.arguments) {
				//console.log('++ argument - ' + argument);
				str = str + '"' + argument + '":';
				var fs = this.arguments[argument];
				var fsstr = fs.toJSONString();
				str = str + fsstr;
			}
			*/
			str = str + '] }';
			return str;
		};
		
		
	this.toJSON = function() {
			var str = this.toJSONString();
			return str;
	};
		
}