function Rule(name, wordclassID, index)  {

	this.wordclassID = wordclassID;
	this.name = name;
	this.index = -1;
	this.maintermposition = -1;
	
	this.termclasses = [];
	this.termarguments = [];
	this.termindexes = [];
	this.argumentsallowed = [];
	this.termconcepts= [];
	this.termconceptIDs = [];
		
	this.agreements = [];
	this.constraints = [];
	this.constraintoperators = [];
	this.components = [];
	this.resultfeatures = new Map();
	this.resultpositions = new Map();
	this.termresultpositions = [];
	//this.termresultpositionnegative = [];
	this.unsetarguments = [];
	this.unsetfeatures = [];
	
	this.conceptID = 0;
	this.conceptName = "";
	
	
	this.conceptarguments = [];
	this.conceptcomponents = [];
	
	// Jos rulessa on leftsideconcept, niin sen argumentit pitää asetaa erikseen...
	//this.conceptarguments = [];			

		
	this.addTerm = function(position, argumentID, wordclassID, index, argumentsallowed, conceptID, concept) {
		this.termclasses[position] = wordclassID;
		this.termindexes[position] = index;
		this.termconceptIDs[position] = conceptID;
		this.termconcepts[position] = concept;
		this.argumentsallowed[position] = argumentsallowed;
		//console.log('rulename - '+this.name);
		//console.log('add term - '+position+', '+argument+', '+wordclass+', '+index);
		if (argumentID == 2) {		// 1 = [empty], 2 = Mainterm
			this.index = index;
			this.maintermposition = position;
		}
		this.termarguments[position] = argumentID;
	};

		
	this.getTermCount = function() {
		return this.termindexes.length;
	};
		
	
	this.setConcept = function(conceptID, conceptName) {
		this.conceptID = conceptID;
		this.conceptName = conceptName;
	}
	

	this.addAgreement = function(featureID, position1, position2, index) {
		if (this.agreements[position1] == null) {
			this.agreements[position1] = new Map();
		}
		if (this.agreements[position2] == null) {
			this.agreements[position2] = new Map();
		}
		if (this.agreements[position1].get(featureID) == null) {
			this.agreements[position1].set(featureID, index);
		}
		if (this.agreements[position2].get(featureID) == null) {
			this.agreements[position2].set(featureID, index);
		}
	};
		

	this.addConstraint = function(position, featureID, valueID, operator) {
		//console.log(' -- '+this.name);
		//console.log(' -- add constops['+position+'] - '+featureID+','+valueID);
		if (this.constraints[position] == null) {
			this.constraints[position] = new Map();
			this.constraintoperators[position] = new Map();
		}
		if (this.constraints[position].get(featureID) == null) {
			//console.log(' +++ adding');
			this.constraints[position].set(featureID, valueID);
			this.constraintoperators[position].set(featureID, operator);
		}
	};

	
	this.addConceptArgument = function(argumentID, componentID) {
		this.conceptarguments.push(argumentID);
		this.conceptcomponents.push(componentID);
	};


	this.addComponent = function(position, componentID, presense) {
		if (this.components[position] == null) {
			this.components[position] = new Map();
		}
		if (this.components[position].get(componentID) == null) {
			this.components[position].set(componentID, presense);
		}
	};


	this.addResultFeature = function(featureID, valueID) {
		//console.log('addResultfeature - '+featureID+' - '+valueID);
		this.resultfeatures.set(featureID, valueID);
		//console.log('addResultfeature - '+this.resultfeatures.length);
		/*
		var counter = 0;
		for (var index in this.resultfeatures) {
			var wordclass = this.resultfeatures[index];
			//console.log('--- currentfeatures '+index+' - '+wordclass);
			counter++;
		}
		 */
		//console.log('addResultfeature count - '+counter);
	};
		
		

		this.addResultPosition = function(featureID, sourcefeatureID, position, index) {
			//console.log('addResultPosition - '+feature+' - '+sourcefeature+' - '+position+' - '+index);
			if (this.termresultpositions[position] == null) {
				this.termresultpositions[position] = new Map();
			}
			this.termresultpositions[position].set(sourcefeatureID, index);
			this.resultpositions.set(featureID, index);
			//this.termresultpositionnegative[featureID] = index;
			//console.log('addResultfeature - '+this.resultfeatures.length);
			//console.log('addResultfeature count - '+counter);
		};
		
		
		this.addUnsetArgument = function(position, argumentID) {
			//console.log('addUnsetArgument - '+position+' - '+argumentID+' - '+argument);
			if (this.unsetarguments[position] == null) {
				this.unsetarguments[position] = new Map();
			}
			this.unsetarguments[position].set(argumentID, argumentID);
		};
		
		
		this.addUnsetFeature = function(position, featureID) {
			//console.log('addUnsetFeature - '+position+' - '+featureID+' - '+feature);
			if (this.unsetfeatures[position] == null) {
				this.unsetfeatures[position] = new Map();
			}
			this.unsetfeatures[position].set(featureID, featureID);
		};
		
		
		
		this.getResultPositionCount = function() {
			var counter = 0;
			this.resultpositions.forEach((value, feature) => {
				counter++;
			})
			/*
			for (var index in this.resultpositions) {
			}
			*/
			return counter;
		};
		
		
		this.getResultFeatureCount = function() {
			var counter = 0;
			this.resultfeatures.forEach((value, feature) => {
				counter++;
			})
			/*
			for (var index in this.resultfeatures) {
				counter++;
			}
			*/
			return counter;
		};
		
		this.print = function() {
//			console.log('wordclass - ' + this.wordclassID);
//			console.log(this.name);
//			console.log(this.arguments);
		};


		this.isAnalyseCompatible = function(fs1, fs2) {
			
			var comments = true;
			
			//console.log('--- termconcept1 - ' + this.termconceptIDs[0]);
			if (this.termconceptIDs[0] > 0) {
				console.dir(fs1);
				if (parseInt(fs1.conceptID) != this.termconceptIDs[0]) {
					console.log(' ++++ fs1.conceptID unmatch');
					return false;
				} else {
					//console.log(' -- conceptID match');
				}
			}

			
			if (fs2 == undefined) {
				if (comments) console.log(' - fs2 on null, only one term - '+this.termclasses.length);
				var wordclass = this.termclasses[0];
				if (comments) console.log(' - wordclass - '+wordclass);
				
				if (this.termclasses.length != 1) {
					console.log('++++ termcount not compatible 1');
					return false;
				}
			} else {
				/*
				if (this.termclasses.length != 2) {
					console.log('--- termcount not compatible 2');
					return false;
				}
				*/
				
				//console.log('--- termconcept2 - ' + this.termconceptIDs[1]);
				if (parseInt(this.termconceptIDs[1]) > 0) {
					if (parseInt(fs2.conceptID) != this.termconceptIDs[1]) {
						console.log(' ++++ fs2.conceptID unmatch');
						return false;
					} else {
						console.log(' -- conceptID2 match');
					}
				}
			}
			
			
			
			//for (var index in this.termclasses) {
			//	var wordclass = this.termclasses[index];
				//console.log('---termclass '+index+' - '+wordclass);
			//}
				
			//console.log('--- termcount - '+this.termclasses.length);
			/*
			if (!((this.termclasses.length == 2) || (this.termclasses.length == 1))) {
				console.log('--- termcount not compatible');
				return false;
			}
			*/
			
			
			
			if (fs2 == undefined) {
				//if (comments) console.log('--- isAnalyseCompatible 1 - '+fs1.name);
			} else {
				//if (comments) console.log('--- isAnalyseCompatible 2 - '+fs1.name+' - '+fs2.name);
			}
			
			if (this.termclasses[0] != undefined) {
				//if (comments) console.log('--- fs1.wordclassID - '+fs1.wordclass);
				if (this.termclasses[0] == fs1.wordclassID) {
					//if (comments) console.log('--- fs1 is compatible wordclass');
				} else {
					var wordclassStr = wNames.get(this.termclasses[0]);
					var fswordclassStr = wNames.get(fs1.wordclassID);
					console.log('++++ a1 '+wordclassStr+' != '+fswordclassStr);
					return false;
				}
			} else {
				if (comments) console.log('--- not compatible (no term in index 0)');
				return false;
			}

			if (fs2 == undefined) {
				//if (comments) console.log('--- fs2.wordclassID - not present');
			} else {
				if (this.termclasses[1] != undefined) {
					//if (comments) console.log('--- fs2.wordclassID - '+fs2.wordclassID);
					if (this.termclasses[1] == fs2.wordclassID) {
						//if (comments) console.log('--- fs2 is compatible wordclass');
					} else {
						console.log('++++ incompatible wordclass');
						return false;
					}
				} else {
					//if (comments) console.log('--- not compatible (no term in index 1)');
					//return false;
				}
			}
			
			// Nyt tiedetään, että wordclassit ovat yhteensopivia
			//comments = true;
			
			// argumentit tsekataan vain jos kyseessä on kaksipaikkainen rule
			if (fs2 != undefined) {
				
				
				if (this.maintermposition == 0) {
					
					if (comments) console.log('--- argument maintermposition(a)- '+this.maintermposition);
					
					if (comments) console.log('--- argument xxx index - '+this.maintermposition);
					var argument = this.termarguments[0];
					var argument2 = this.termarguments[1];
					
					if (comments) console.log('--- argumentfound pos0 - '+argument);
					if (comments) console.log('--- argumentfound pos1 - '+argument2);
					
					var settedArgumentCount = 0;
					var flag = true;
					fs1.arguments.forEach((valueID, settedArgument) => {
						if (settedArgument == argument2) {
							console.log('argument already setted TODO');	// mahdollisesti täällä on jotain muita asetuksia 'required' tms.
							flag = false;
							//return false;
						}
						settedArgumentCount++;
					})
					if (flag == false) return false;
					//for (var settedArgument in fs1.arguments) {
					//}
					if (comments) console.log('setted argument count - '+settedArgumentCount);
					if (comments) console.log('-- trying argument- ' + argument);
					
					var requirementForArgument = null;
					//var requirementForArgument = 0;
					fs1.requirements.forEach((componentArray, reqArgumentID) => {
						if (comments) console.log('------ requirements - ' + reqArgumentID);
						if (reqArgumentID == argument2) requirementForArgument = componentArray;
						//if (reqArgument == argument) requirementForArgument = fs1.requirements[reqArgument];
					})
					
					if (comments) console.log('requirement exists - '+requirementForArgument);
					
					if (requirementForArgument == null) {
					//if (requirementForArgument == 0) {
						//if (comments) console.log(' -- Mainterm does not have requirement for x '+argument);
						// tämä toteutuu silloin, kun conceptia / argument requirementteja ei ole -- ei hyväksytä mitään
						
						// tämä tarkistus lisätty myöhemmin, jos kyse on yksipaikkainen rule, niin ei argument empty tarkistusta
						if (argument2 != undefined) {

							if (argument != 1) {  // 1 = [empty], 2 = Mainterm
								//if (comments) console.log('requirements not empty');
								console.log('+++++ Mainterm empty xx '+argument);
								return false;
							} else {
								if (comments) console.log('requirements empty, not needed');
								//if (comments) console.log('test exit');
								//exit;
								//return true;
							}
							//return true;
						}
						
					} else {
						
						// target termillä on olemassa argument-requirement rulen tarvitsemalle argumentille
						// Nyt pitäisi sitten tsekata onko kyseisellä featurella asianomainen componentti olemassa
						var requirementFullfilled = 0;
						fs2.components.forEach((value, componentID) => {
							if (comments) console.log('------ found component r1 - ' + componentID);
							requirementForArgument.forEach((requiredComponentID, componentIndex) => {
								if (requiredComponentID == componentID) {
									if (comments) console.log('----- requirement fullfilled - '+componentID);
									console.log('reqirement ok - '+componentID);
									requirementFullfilled = 1;
								}
							})
						})
						//for (var component in fs2.components) {
						//}
						
						if (requirementFullfilled == 0) {
							if (comments) console.log('Argument does not have required component '+requirementForArgument);
							return false;
						}
					}
					
					//return false;
					
				} else if (this.maintermposition == 1) {
					if (comments) console.log('--- argument maintermposition(b) - '+this.index);
					
					//console.dir(fs2);
					
					var argument = this.termarguments[0];
					var argument2 = this.termarguments[1];
					
					if (comments) console.log('--- argumentfound pos0 - '+argument);
					if (comments) console.log('--- argumentfound pos1 - '+argument2);
					
					var settedArgumentCount = 0;
					var flag = true;
					fs2.arguments.forEach((valueID, settedArgument) => {
						if (settedArgument == argument) {
							console.log('argument already setted TODO');	// mahdollisesti täällä on jotain muita asetuksia 'required' tms.
							flag = false;
							//return false;
						}
						settedArgumentCount++;
					})
					if (flag == false) {
						console.log(' ++++ alrgument already setted TODO2');
						return false;
					}
					//for (var settedArgument in fs2.arguments) {
					//}
					if (comments) console.log('setted argument count - '+settedArgumentCount);

					var requirementForArgument = null;
					fs2.requirements.forEach((componentArray, reqArgumentID) => {
						if (comments) console.log('------ requirements - ' + reqArgumentID);
						if (reqArgumentID == argument) requirementForArgument = componentArray;
						//if (reqArgument == argument) requirementForArgument = fs2.requirements.get(reqArgument);
					})
					
					//for (var reqArgument in fs2.requirements) {
					//}
					if (comments) console.log('requirement exists - '+requirementForArgument);
					if (requirementForArgument == null) {
						//if (comments) console.log(' -- Mainterm does not have requirement for '+argument);
						// tämä toteutuu silloin, kun cocneptia / argument requirementteja ei ole -- hyväksytään kaikki
						if (argument != 1) {  // 1 = [empty], 2 = Mainterm
							var argumentStr = aNames.get(argument);
							console.log('+++++ no argument found - '+argumentStr);
							return false;
						} else {
							if (comments) console.log('requirements empty, not needed');
							if (comments) console.log('text exit');
						}
						
						//return false;
						//return true;
					} else {
						// target termillä on olemassa argument-requirement rulen tarvitsemalle argumentille
						// Nyt pitäisi sitten tsekata onko kyseisellä featurella asianomainen componentti olemassa

						var requirementFullfilled = 0;
						fs1.components.forEach((valueID, componentID) => {
							if (comments) console.log('------ found component r2 - ' + componentID);
							
							requirementForArgument.forEach((requiredComponentID,componentIndex) => {
								if (comments) console.log('test require - '+requiredComponentID);
								if (requiredComponentID == componentID) {
									if (comments) console.log('----- requirement fullfilled - '+componentID);
									if (comments) console.log('reqirement ok - '+componentID);
									requirementFullfilled = 1;
								}
							})
							
							/*
							if (requirementForArgument == componentID) {
								if (comments) console.log('----- requirement fullfilled - '+componentID);
								requirementFullfilled = 1;
							}
							*/
						})
						//for (var component in fs1.components) {
						//}
						
						if (requirementFullfilled == 0) {
							if (comments) console.log('Argument does not have required component '+requirementForArgument);
							console.log('++++ No required argument '+requirementForArgument);
							return false;
						}
					}
					
					
					
				} else {
					if (comments) console.log('--- argument position - '+this.position);
					if (comments) console.log('--- rule argument position out of bounds');
				}
				
			}
			
			
			if (comments) console.log('--- next loop agreementsit');
			
			// seuraavaksi käydään kaikki argreementit lävitse
			// agrementit käydään lävitse vain jos rule on kaksipaikkainen
			if (fs2 != undefined) {
				var foundagreements = new Map();
				
				if (comments) {
					if (this.agreements == null) {
						console.log('--- checking agreements null');
					} else {
						console.log('--- checking agreements size - x');
						//console.log('--- checking agreements size - '+this.agreements.length);
					}
				}
				
				for (var position in this.agreements) {
					if (comments) console.log('--- checking agreements in position - '+position);
					if (position == 0) {
						if (comments) console.log('--- position 0');
						var agreementfeatures = this.agreements[position];
						var flag = true;
						agreementfeatures.forEach((index, feature) => {
							if (comments) console.log('--- --- feature - '+feature);
							//var index = features[feature];
							if (foundagreements.get(index) == undefined) {
								
								
								if (fs1.features.get(feature) == undefined) {
									if (comments) console.log(' --- (a) no feature '+feature+' found in fs1');
									flag = false;
									// return false;
								}  else {
									var value = fs1.features.get(feature);
									if (comments) console.log(' --- value found '+value);
									foundagreements.set(index, value);
									//foundargreements[index] = index;
									if (comments) console.log(' --- '+feature+' - '+index+' undefined, setting '+value);
								}
							} else {
								if (fs1.features.get(feature) == undefined) {
									if (comments) console.log(' --- (b) no feature '+feature+' found in fs1');
									flag = false;
									//return false;
								} else {
									var value = fs1.features.get(feature);
									
									if (foundagreements.get(index) == value) {
										if (comments) console.log(' --- agreement holds - '+value);
									} else {
										if (comments) console.log(' --- agreement missmatch 1- '+foundagreements.get(index)+' - '+value);
										flag = false;
										//return false;
									}
								}
							}
						})
						if (flag == false) return false;
						//for (var feature in features) {
							
						//}
					}
					
					if (position == 1) {
						if (comments) console.log('--- position 1');
						var agreementfeatures = this.agreements[position];
						var flag = true;
						agreementfeatures.forEach((index, feature) => {
							if (comments) console.log('--- --- feature - '+feature);
							//var index = features[feature];
							if (foundagreements.get(index) == undefined) {
								
								if (fs2.features.get(feature) == undefined) {
									if (comments) console.log(' --- (c) no feature '+feature+' found in fs2');
									flag = false;
									//return false;
								} else {
									var value = fs2.features.get(feature);
									if (comments) console.log(' --- value found '+value);
									foundagreements.set(index,value);
									//foundargreements[index] = index;
									if (comments) console.log(' --- '+feature+' - '+index+' undefined, setting '+value);
								}
							} else {
								if (fs2.features.get(feature) == undefined) {
									if (comments) console.log(' --- (d) no feature '+feature+' found in fs2');
									flag = false;
									//return false;
								} else {
									var value = fs2.features.get(feature);
									//console.log(' --- fs2 value - '+value);
									
									// Tässä pitäisi katsoa, onko foundagreementsit parenttien suhteen compatible? Molemminpäin
									
									if (foundagreements.get(index) == value) {
										if (comments) console.log(' --- agreement holds - '+value);
									} else {
										var match = this.isParentMatch(foundagreements.get(index),value);
										if (match == true) {
											if (comments) console.log(' --- agreement match 2 - '+foundagreements.get(index)+' - '+value);
										} else {
											if (comments) console.log(' --- agreement missmatch 2 - '+foundagreements.get(index)+' - '+value);
											flag = false;
											//return false;
										}
									}
								}
							}
						})
						if (flag == false) return false;
						//for (var feature in features) {
							
						//}
					}
				}
				if (comments) console.log('agreements loop end');
			}
			
			
			
			//console.log('--- checking constraints size - '+this.constraints.length);
			for (var position in this.constraints) {
				//if (comments) console.log('--- checking constraints työnalla - '+position);
				//console.log('--- checking constraints size - '+this.constraints.length);
				if (position == 0) {
					//console.dir(this.contraints);
					var features = this.constraints[1];
					//console.dir(features);
					
					var features = this.constraints[position];
					if (comments) console.log('--- position 0');
					console.dir(fs1);
					
					var flag = true;
					features.forEach((value, feature) => {
						if (comments) console.log('--- feature0 constraint found');
						var operator = this.constraintoperators[position].get(feature);
						//var value = features[feature];
						if (operator == 1) {			// ==
							if (fs1.features.get(feature) == undefined) {
								var featureStr = fNames.get(feature);
								if (comments) console.log(' --- (e) no feature '+feature+' found in fs1');
								flag = false;
								//return false;
							}  else {
								if (value == fs1.features.get(feature)) {
									//console.log(' --- feature constraint match0 - '+value+ ' == ' + fs1.features.get(feature) + ' continue');
								} else {
									var feats = fNames.get(feature);
									var valueStr = fNames.get(value);
									var value2Str = fNames.get(fs1.features.get(feature));
									console.log('++++ a2 - '+feats+': '+valueStr+ ' != ' + value2Str);
									flag = false;
									//return false;
								}
							}
						}
						
						if (operator == 2) {			// !=
							if (fs1.features.get(feature) == undefined) {
								// epämäääräinen arvo sallitaan != x
							} else {
								if (value == fs1.features.get(feature)) {
									console.log('++++ no match3- '+value+ ' != ' + fs1.features.get(feature));
									flag = false;
									//return false;
								}
							}
						} 
					})
					if (flag == false) {
						if (comments) console.log('flag t1 false');
						return false;
					}
					//for (var feature in features) {
						
					//}
				}

				if (position == 1) {
					if (comments) console.log('--- position 1');
					var features = this.constraints[position];
					//if (features == undefined) {
					//	console.log(' -- features undefined - ');
					//}
					//console.table(features);
					
					if (fs2 == undefined) return false;
					var flag = true;
					features.forEach((value, feature) => {
						if (comments) console.log('--- feature constraint1 found - '+feature);
						var operator = this.constraintoperators[position].get(feature);
						if (comments) console.log('--- feature constraint1 operator - '+operator);
						
						if (fs2 == undefined) {
							console.log(' -- fs2 undefined - ');
							flag = false;
							//return false;
						}
						//var value = features[feature];
						if (comments) console.log(' -- fs2 undefined after - ');
						
						if (operator == 1) {			// ==
							if (fs2.features.get(feature) == undefined) {
								if (comments) console.log(' --- (f) no feature '+feature+' found in fs2');
								flag = false;
								//return false;
							}  else {
								if (value == fs2.features.get(feature)) {
									if (comments) console.log(' --- feature constraint match1 - '+value+ ' == ' + fs2.features.get(feature) + ' continue');
								} else {
									var valueStr = fNames.get(value);
									var value2Str = fNames.get(fs2.features.get(feature));
									console.log('++++ a3 - '+valueStr+ ' != ' + value2Str);
									//console.log('+++++ incompatible features - '+value+ ' == ' + fs2.features.get(feature));
									flag = false;
									//return false;
								}
							}
						}
						
						if (operator == 2) {			// !=
							if (comments) console.log(' --- operator 2');
							
							if (fs2.features.get(feature) == undefined) {
								console.log(' --- (g) no feature '+feature+' found in fs2');
								// epämäääräinen arvo sallitaan != x
							} else {
								if (value == fs2.features.get(feature)) {
									console.log(' --- feature constraint1 macht - '+value+ ' != ' + fs2.features.get(feature));
									flag = false;
									//return false;
								} else {
									console.log(' --- feature != value - '+value+ ' != ' + fs2.features.get(feature));
								}
							}
						} 
						
					})
					if (flag == false) {
						if (comments) console.log('flag t2 false');
						return false;
					}
					//for (var feature in features) {
						
					//}
				}
			}
			if (comments) console.log('constraints loop end');
			
			//console.log('--- checking components size - '+this.components.length);
			//console.log('--- checking components size - xxx');
			for (var position in this.components) {
			
				
				if (position == 0) {
					console.log('--- components position - 0');
					var components = this.components[position];
					var flag = true;
					components.forEach((presence, componentID) => {
						if (presence == 1) {
							if (fs1.components.get(componentID) == null) {
								if (comments) console.log(' --- no component '+componentID+' found in fs1');
								flag = false;
								//return false;
							}  else {
								if (comments) console.log(' --- component '+componentID+' found in fs1');
							}
						} else {
							
							console.log(' -- fs1 presence = 0');	// component should not exists
							if (fs1.components.get(componentID) == null) {
								var componentStr = cAbbs.get(componentID);
								if (comments) console.log(' --- no component '+componentStr+' found in fs1');
							}  else {
								var componentStr = cAbbs.get(componentID);
								if (comments) console.log(' --- component '+componentStr+' found in fs1');
								flag = false;
							}
							//console.log(' -- fs1 presence != 1 not implemented');
							//flag = false;
							//return false;
						}
					})
					if (flag == false) return false;
					//for (var component in components) {
					//}
				}
				
				if (position == 1) {
					//console.log('--- components position - 1');
					var components = this.components[position];
					var flag = true;
					components.forEach((presence, componentID) => {
						if (presence == 1) {
							console.log(' -- presence is 1');
							if (fs2.components.get(componentID) == null) {
								var componentStr = cAbbs.get(componentID);
								console.log(' ++++ no component '+componentStr+' found in fs2');
								flag = false;
								//return false;
							}  else {
								var componentStr = cAbbs.get(componentID);
								if (comments) console.log(' --- component '+componentStr+' found in fs2');
							}
						} else {
							if (comments) console.log(' -- fs2 presence = 0');	// component should not exists
							if (fs2.components.get(componentID) == null) {
								var componentStr = cAbbs.get(componentID);
								if (comments) console.log(' --- no component '+componentStr+' found in fs2');
							}  else {
								var componentStr = cAbbs.get(componentID);
								console.log(' ++++ component '+componentStr+' found in fs2');
								flag = false;
							}
							//flag = false;
							//return false;
						}
					})	
							
					if (flag == false) {
						//console.log(' -- flag is false');
						return false;
					}
					//for (var component in components) {
					//}
				}
			}
			
			//console.log('--x1');
			//console.dir(fs1);
			//console.log('--x2');
			//console.dir(fs2);
			//console.log('--x3');
			//console.dir(this);
			//console.log('--x4');
			
			
			// constraints on nyt tsekattu, pitäisi tsekata myös component requirements (näitäkin pitää olla + ja -, eli exists ja not exists
			console.log(' ++++ rule accepted');
			return true;
		};

		
		// Tämä funktio palauttaa positionissa olevan feature structuren, eli käytännössä palauttaa
		// featurestructuren, johon on asetettu kiinteät featuren ja componentit, jotka kyseisessä
		// positionissa on. Ilmeisesti tässä tapauksessa voi olla vain featureita. Parametrina pitänee
		// pitänee ilmeisesti antaa featurestructure, josta otetaan constraintin mukaiset featuret
		// ja ne asetetaan mikäli ne on olemassa
		this.createFeatureStructure = function(position, othersideFS) {
			
			var wordclass = this.termclasses[position];
			console.log('-- createFeatureStructure - '+wordclass );
			
		};
		
		
		
		/**
		 * Tällä funktiolla tarkistetaan onko parametrina annettu featurestructure yhteensopiva
		 * annetun säännön kanssa.
		 * 
		 *  - Tarkistetaan constraintit
		 *  - TODO: Pitäisi varmaan tarkistaa myös agreementsit, valuen olemassaolo ja täsmäys...
		 * 
		 */
		this.isGenerateCompatible = function(fs) {

			var comments = false;
			comments = true;
			//console.log(' ---------------- check compatible');
			console.log(this.name);
			
			var argumentfound = false;
			var maintermposition = null;
			var argumentposition = null;
			var argumentitem = null;

			// Ensin etsitään mikä annetun featurestructuren argumenteista on sopii säännön left
			// ja right-siden arvoiksi
			// - Tässä nähdäkseni verrataan string-arvoja fsargumen = argument
			var argumentvalue = null;
			fs.arguments.forEach((argumentFS, fsargument) => {
				if (comments) console.log(' .. trying to find argumen - '+fsargument);
				for (var position in this.termarguments) {
					var argument = this.termarguments[position];
					if (comments) console.log('.. position = '+position);
					if (comments) console.log('.. argument = '+argument);
					if (fsargument == argument) {
						if (comments) console.log(' .. argumentfound is compatible');
						argumentfound = true;
						argumentposition = position;
						argumentitem = fsargument;
					}
					if (argument == 2) { // 1 = [empty], 2 = Mainterm
						if (comments) console.log(' .. mainterm found - '+position);
						maintermposition = position;
					}
				}
			})
			
			//for (var fsargument in fs.arguments) {
			//}
				
			// Tämän jälken on asetettu seuraavat
			//  - argumentfound löytyykö fs:stä rulen tarvitsema argumentti
			//  - argumentposition muuttuja kertoo listan argumentin indeksin
			//  - maintermposition kertoo argument pääsanan indeksin rulessa
			var argumentfs = null;
			var othertermcheck = true;
			if (argumentfound == false) {
				//console.log('arguments not found, false');
				
				// Tsekataan onko rulessakaan yhtään argumenttia...
				var argumentcounter = 0;
				for (var position in this.termarguments) {
					var argument = this.termarguments[position];
					if (comments) console.log('.. position = '+position);
					if (comments) console.log('.. argument = '+argument);
					if (argument == 1) {  // 1 = [empty], 2 = Mainterm
						if (comments) console.log(' .. empty argumentfound');
						argumentfound = true;
						argumentposition = position;
						argumentitem = null;				// TODO: tämä on epäselvä mikä tohon tulee?
					}
					if (argument == 2) { // 1 = [empty], 2 = Mainterm
						if (comments) console.log(' .. mainterm found - '+position);
						maintermposition = position;
					}
					argumentcounter++;
				}
				
				
				if (argumentfound == false) {
					
					if (argumentcounter == 1) {
						if (comments) console.log('.. one argument only');
					} else {
						if (comments) console.log('.. not empty argument found');
						console.log('++++ no arguments found');
						return false;
					}
				}
				
				
				var rulemaintermclass = this.termclasses[maintermposition];
				var fsmaintermclass = fs.wordclassID;
				if (comments) console.log('fs wordclasses1 - '+fsmaintermclass);
				
				if (fsmaintermclass != rulemaintermclass) {
					if (comments) console.log(' .. incompatible mainterm1 wordclasses');
					if (comments) console.log(' .. '+fsmaintermclass+' vs. '+rulemaintermclass);
					
					var str1 = wAbbs.get(fsmaintermclass);
					var str2 = wAbbs.get(rulemaintermclass);
					console.log('++++ g4 - '+str1+' vs. '+str2);
					
					return false;
				}
				
				othertermcheck = false;		// Ei tarvitse tarkistaa toisen argumentin yhteensopivuutta, koska se on Empty
				
				
			} else {

				if (comments) console.log('checking mainterm - '+maintermposition);
				
				var rulemaintermclass = this.termclasses[maintermposition];
				var ruleargumenttermclass = this.termclasses[argumentposition];
				if (comments) console.log('rule wordclasses - '+rulemaintermclass+' - '+ruleargumenttermclass);
				//console.dir(fs);
				argumentfs = fs.getArgumentValue(argumentitem);
				console.log('argumentitem:');
				console.dir(argumentitem);
				console.log('argumentfs:');
				console.dir(argumentfs);
				
				
					
				var fsmaintermclass = fs.wordclassID;
				if (comments) console.log('fs wordclasses - '+fsmaintermclass+' - '+argumentfs.wordclassID);
				
				if (fsmaintermclass != rulemaintermclass) {
					if (comments) console.log(' .. incompatible mainterm wordclasses');
					return false;
				}
				if (ruleargumenttermclass != argumentfs.wordclassID) {
					if (comments) console.log(' .. incompatible argument wordclasses');
					return false;
				}
			}
			
			
			
			// Tarkistetaan ovatko asetetut featuret yhteensopivia rulen kanssa...
			//if (comments) console.log(' .. rule.constraints - '+this.constraints.length);
			if (comments) console.log(' .. rule.constraints - xxx');
			//if (comments) console.log(' .. rule.resultfeatures - '+this.resultfeatures.length);
			var compatible = true;
			//if (this.resultfeatures.length > 0) {
				
				//for(var resultfeature in this.resultfeatures) {
				var flag = true;
				this.resultfeatures.forEach((resultvalue, resultfeature) => {
					
					//var resultvalue = this.resultfeatures[resultfeature];
					//console.log(' ...... ressi - '+resultfeature+' - '+resultvalue);
					var found = false;
					
					fs.features.forEach((fsvalue, fsfeature) => {
						//var fsvalue = fs.features[fsfeature];
						//if (comments) console.log(' .. rr1 fsfeature -'+fsfeature+' - '+fsvalue);
						// [note] Tässä pitäisi mielestäni olla tarkastus onko fsvalue ja value yhteensopivia?
						if (fsfeature == resultfeature) {
							//if (comments) console.log(' .. rryy -- rulefeature found - '+fsfeature);
							if (fsvalue == resultvalue) {
								//console.log(' .. rrzz -- rulefeature compatible');
								found = true;
							} else {
								//console.log(' .. rrzz -- rulefeature is not compatible');
								var ressStr = fNames.get(fsfeature);
								var resultvalueStr = fNames.get(resultvalue);
								var fsvalueStr = fNames.get(fsvalue);
								console.log('++++ g1 - '+ressStr+':'+resultvalueStr+'..'+fsvalueStr);
								
								// Voisi ehkä palauttaa suoraa false...
								compatible = false;
								flag = false;
								//return false;
							}
						}
					})
					//if (flag == false) return false;
					//for (fsfeature in fs.features) {
					//}
					
					if (found == false) {
						console.log('+++++ result feature not found - '+resultfeature);
						flag = false;
						//return false;
					}
				})
				if (flag == false) return false;
				
				// mainfeature tsekki...
				var maintermfeatures = this.constraints[maintermposition];
				if (maintermfeatures != null) {
					//if (comments) console.log(' .. maintermfeatures - '+maintermfeatures.length);
					var flag = true;
					var featurefound = false;
					maintermfeatures.forEach((value, mainfeature) => {

						if (comments) console.log(' .. mainfeature - '+mainfeature+' - '+value);
						var fsStr = fNames.get(mainfeature);
						var fsvalueStr = fNames.get(value);
						if (comments) console.log(' .. .. maintermfeatures -- '+fsStr+' = '+fsvalueStr);
						var operator = this.constraintoperators[maintermposition].get(mainfeature);
						featurefound = false;
						
						fs.features.forEach((fsvalue, fsfeature) => {
						
							if (fsfeature == mainfeature) {
								featurefound = true;
								
								//var operator = this.constraintoperators[maintermposition].get(mainfeature);
								if (comments) console.log(' .. argumentoperator - '+operator);
								
								if (operator == 5) {		// override operator
									if (comments) console.log('override accepts all');
								} else {
									
									if (operator == 2) {
										
										if (comments) console.log('operator not equals... check');
											
										var fsStr = fNames.get(mainfeature);
										var fsvalueStr = fNames.get(value);
										console.log(' .. maintermfeatures -- '+fsStr+' vs. '+fsvalueStr);
										
										var fs2Str = fNames.get(fsfeature);
										var fs2valueStr = fNames.get(fsvalue);
										console.log(' .. fs.features -- '+fs2Str+' != '+fs2valueStr);
										
										if (value == fsvalue) {
											var resultvalueStr = fNames.get(value);
											var fsvalueStr = fNames.get(fsvalue);
											console.log('++++ should not be equal '+resultvalueStr+' vs. '+fsvalueStr);
											compatible = false;
											flag = false;
										}
									} else {
										if (comments) console.log(' .. yy -- rulefeature found - '+fsfeature);
										if (value == fsvalue) {
											if (comments) console.log(' .. zz -- rulefeature compatible');
										} else {
											if (comments) console.log(' .. -- not compatible, checkmore');
											compatible = false;
											this.resultfeatures.forEach((resultvalue, resultfeature) => {
												var resultfeature4 = fNames.get(resultfeature);
												console.log(' -- result feature - '+resultfeature4+' ('+resultfeature+')');
												if (resultfeature == fsfeature) {
													
													var fsvalue3 = fNames.get(fsvalue);
													var resultvalue3 = fNames.get(resultvalue);
													if (comments) console.log(' .. aaa match -- '+fsvalue3+' vs. '+resultvalue3);
													if (fsvalue == resultvalue) {
														if (comments) console.log(' .. aaa -- compatible found');
														compatible = true;
													} else {
														if (comments) console.log(' .. aaa -- incompatible found');
														var resultvalueStr = fNames.get(value);
														var fsvalueStr = fNames.get(fsvalue);
														compatible = false;
														if (comments) console.log(' - set flag is false');
														flag = false;
														//return false;
													}
												} else {
													var parentID = fParents.get(fsvalue);
													if (parentID == fsfeature) {
														console.log('++++ parent match');
													}
												}
											})
											if (compatible == false) {
												var resultvalueStr = fNames.get(value);
												var fsvalueStr = fNames.get(fsvalue);
												console.log('++++ g3 - '+resultvalueStr+' vs. '+fsvalueStr+' ('+fsvalue+')');
												flag = false;
											}
										}
									}
								}
							}
						})
						if (featurefound == false) {
							
							if (operator == 5) {
								
							} else {
								if (comments) console.log(' -- no mainfeaturefound');
								flag = false;
							}
						}
					})
					if (flag == false) {
						if (comments) console.log(' --- flag is false');
						return false;
					}
					
					//for(var mainfeature in maintermfeatures) {
					//}
				} else {
					if (comments) console.log(' .. no maintermfeatures');
				}
				
			if (compatible == false) {
				//console.log(' -- compatible is false 3');
				if (comments) console.log(' --- compatible is false');
				return false;
			}
			
			
			// [note] Pitäisikö tsekata myös onko non-mainterm yhteensopiva? this.constraints[argumentposition]
			//comments = true;
			if (comments) console.log('xxx34 argumentfs:');
			if (comments) console.dir(argumentfs);
			
			
			//comments = true;
			if (othertermcheck == true) {
				if (comments) console.log('checking otherterm - '+argumentposition);
				if (comments) console.log(' .. rule.constraints - xxx');
				//if (comments) console.log(' .. rule.constraints - '+this.constraints.length);
				//if (this.constraints.length > 0) {
				if (this.constraints[argumentposition] != null) {	
					var argumentfeatures = this.constraints[argumentposition];
					if (argumentfeatures != null) {
						//if (comments) console.log(' .. argumentfeatures - '+argumentfeatures.length);
						flag = true;
						argumentfeatures.forEach((value, argumentfeature) => {
							//var value = argumentfeatures[argumentfeature];
							console.log(' .. argumentfeature - '+argumentfeature+' - '+value);
							console.log(' .. argumentposition - '+argumentposition);
							var operator = this.constraintoperators[argumentposition].get(argumentfeature);
							console.log(' .. argumentoperator - '+operator);
							
							if (operator == 5) {		// override operator
								// ei tarvitse tarkistaa, overide hyväksyy mitkä tahansa arvot
								console.log(' - operator idle - '+operator);
								
							} else {
								
								if (operator == 2) {
									// not-equals-operator, ei saa olla samat
									var tempargumentfs = fs.arguments.get(argumentitem);
									if (comments) console.log(' .. checking valuefs - '+argumentitem);
									if (comments) console.dir(fs);
									tempargumentfs.features.forEach((fsvalue, fsfeature) => {
										//var fsvalue = argumentfs.features[fsfeature];
										if (comments) console.log(' .. xx fsfeature -'+fsfeature+' - '+fsvalue);
										if (fsfeature == argumentfeature) {
											if (value != fsvalue) {
												if (comments) console.log(' .. notequals, accept - '+value+' - '+fsvalue);
											} else {
												var valueStr = fNames.get(value);
												var fsValueStr = fNames.get(fsvalue);
												console.log(' +++++ != but equals - '+valueStr+' - '+fsValueStr);
												flag = false;
												//return false;
											}
										}
									})
									
									
								} else {
									
									console.log(' - operator - '+operator);
									var tempargumentfs = fs.arguments.get(argumentitem);
									if (comments) console.log(' .. checking valuefs - '+argumentitem);
									if (comments) console.dir(fs);
									tempargumentfs.features.forEach((fsvalue, fsfeature) => {
										//var fsvalue = argumentfs.features[fsfeature];
										if (comments) console.log(' .. xx fsfeature -'+fsfeature+' - '+fsvalue);
										if (fsfeature == argumentfeature) {
											if (value == fsvalue) {
												if (comments) console.log(' .. match x2 - '+value+' - '+fsvalue);
											} else {
												var valueStr = fNames.get(value);
												var fsValueStr = fNames.get(fsvalue);
												console.log(' +++++ no match - '+valueStr+' - '+fsValueStr);
												flag = false;
												//return false;
											}
										}
									})
								}
								
								//for (fsfeature in argumentfs.features) {
								//}
							}
						})
						if (flag == false) return false;
						//for(var argumentfeature in argumentfeatures) {
							
						//}
					} else {
						if (comments) console.log(' .. no argumenttermfeatures');
					}
				}
				
			} else {
				if (comments) console.log('no otherchecking [Empty]'); // 1 = [empty], 2 = Mainterm
			}
			
			
			//console.log('xxx35 argumentfs:');
			//console.dir(argumentfs);
			
			// TODO: agreementtien tarkistus puuttuu. ehkä ongelma jos source conceptstructure on ristiriitainen, pitää tsekata
			if (comments) console.log(' .. tsekataan argumenttien yhteensopivuus');
			
			for (var agreementposition in this.agreements) {
				
				if (comments) console.log('---------------');
				if (comments) console.log(' -- agreementposition - '+agreementposition);
				if (comments) console.log(' -- maintermposition - '+maintermposition);
				if (comments) console.log(' -- argumentposition - '+argumentposition);
				
				if (agreementposition == maintermposition) {
					
					var agreementlist = this.agreements[agreementposition];
					var flag = true;
					var trueflag = false;
					//comments = true;
					agreementlist.forEach((agreementCounter, agreementfeature) => {
						
						//var agreementCounter = agreementlist[agreementfeature];
						var agreementfeatureStr = fNames.get(agreementfeature);
						console.log(' -- x6 -- '+agreementfeatureStr+' -- '+agreementCounter);
						
						var neededFeatureValue = null;
						//var fsFeaturesList = fs.getFeatures();
						fs.features.forEach((fsFeatureValue, fsFeature) => {
							//var fsFeatureValue = fs.features[fsFeature];
							if (comments) {
								var fsFeatureStr = fNames.get(fsFeature);
								var fsFeatureValueStr = fNames.get(fsFeatureValue);
								console.log(' -- x7 -- -- agr '+fsFeatureStr+' - '+fsFeatureValueStr);
							}
							if (fsFeature == agreementfeature) {
								if (comments) console.log(' -- needed value '+fsFeatureValue);
								neededFeatureValue = fsFeatureValue;
							}
						})
						//for (var fsFeature in fs.features) {
						//}
						console.log(' ++ neede value - '+neededFeatureValue);
						
						// kelataan sitten argumentin featuret, josta pitäisi löytyä sama
						if (neededFeatureValue == null) {
							if (comments) console.log(' - agreementfeature not found - '+agreementfeature);
							flag = false;
							//return false;
						} else {
							if (argumentfs != null) {
								if (comments) console.log('-- -- argumentfs is null');
								if (othertermcheck == false) {
									if (comments) console.log('-- -- othercheck - false');
								} else {
									if (comments) console.log('-- -- othercheck - true');
									argumentfs.features.forEach((argumentFeatureValue, argumentFeature) => {
										//var argumentFeatureValue = argumentfs.features[argumentFeature];
										if (comments) console.log(' -- x8 -- -- arg '+argumentFeature+' - '+argumentFeatureValue);
										if (agreementfeature == argumentFeature) {
											console.log(' -- needed value found '+argumentFeatureValue);
											if (argumentFeatureValue == neededFeatureValue) {
												var fsFeatureStr = fNames.get(neededFeatureValue);
												if (comments) console.log(' ++++ needed matchess - '+fsFeatureStr);
												trueflag = true;
												//return true;
											} else{
												if (comments) console.log(' -- a6 -- -- needed no match ');
												flag = false;
												//return false;
											}
										}
									})
									//for (var argumentFeature in argumentfs.features) {
									//}
								}
							} else {
								console.log('Argument fs is null xx2');
							}
							
						}
					})
					
					
					if (trueflag = true) { 
						console.log('trueflag is true c9');
						return true;
					}
					
					
					if (flag == false) {
						console.log('flag is false');
						return false;
					}
					
					//for (var agreementfeature in agreementlist) {
						
					//}		
					
				} else {
					
				}
				
				//var argumentfs = fs.getArgumentValue(argumentitem);
				
				
				/*
				if (comments) console.log(' .. trying to find argumen - '+fsargument);
				for (var position in this.termarguments) {
					var argument = this.termarguments[position];
					if (comments) console.log('.. position = '+position);
					if (comments) console.log('.. argument = '+argument);
					if (fsargument == argument) {
						if (comments) console.log(' .. argumentfound is compatible');
						argumentfound = true;
						argumentposition = position;
						argumentitem = fsargument;
					}
					if (argument == 2) { // 1 = [empty], 2 = Mainterm
						if (comments) console.log(' .. mainterm found - '+position);
						maintermposition = position;
					}
				}
				*/
			}
			
			
			
			
			//console.log('--- checking components size - '+this.components.length);
			//console.log('--- checking components size - xxx');
			for (var position in this.components) {
			
				//console.log('--- argumentposition - '+argumentposition);
				//console.log('--- maintermposition - '+maintermposition);
				//console.log('--- argumentfound - '+argumentfound);
				
				if (position == maintermposition) {
					//console.log('--- components position - 0');
					var components = this.components[position];
					var flag = true;
					components.forEach((presence, componentID) => {
						if (presence == 1) {
							if (fs.components.get(componentID) == null) {
								if (comments) console.log(' --- no component '+componentID+' found in fs1');
								flag = false;
								//return false;
							}  else {
								if (comments) console.log(' --- component '+componentID+' found in fs1');
							}
						} else {
							//console.log(' -- fs1 presence != 1 not implemented');
							//flag = false;
							//return false;
							
							//console.log(' -- fs1 presence = 0');
							if (fs.components.get(componentID) == null) {
								var componentStr = cAbbs.get(componentID);
								if (comments) console.log(' --- no component '+componentStr+' found in mainfs, true');
							}  else {
								var componentStr = cAbbs.get(componentID);
								if (comments) console.log(' --- component '+componentStr+' found in mainfs, false');
								flag = false;
							}
						}
					})
					if (flag == false) return false;
				}
				
				if (argumentfound == true) {
					
					if (position == argumentposition) {
						//console.log('--- components position - 1');
						var components = this.components[position];
						var flag = true;
						components.forEach((presence, componentID) => {
							if (presence == 1) {
								//console.log(' -- presence is 1');
								
								if (argumentitem.components == null) {
									console.log(' -- components is null');
									console.dir(argumentitem);
									console.dir(argumentfs);
								}
								
								//if (argumentitem.components.get(componentID) == null) {
								if (argumentfs.components.get(componentID) == null) {
									var componentStr = cAbbs.get(componentID);
									if (comments) console.log(' --- no component '+componentStr+' found in fs2');
									flag = false;
									//return false;
								}  else {
									var componentStr = cAbbs.get(componentID);
									if (comments) console.log(' --- component '+componentStr+' found in fs2');
								}
							} else {
								if (comments) console.log(' -- fs2 presence = 0');	// component should not exists
								//if (argumentitem.components.get(componentID) == null) {
								if (argumentfs.components.get(componentID) == null) {
									var componentStr = cAbbs.get(componentID);
									if (comments) console.log(' --- no component '+componentStr+' found in fs2');
								}  else {
									var componentStr = cAbbs.get(componentID);
									console.log(' --- component '+componentStr+' found in fs2');
									flag = false;
								}
								//flag = false;
								//return false;
							}
						})	
								
						if (flag == false) {
							//console.log(' -- flag is false');
							return false;
						}
					}
				}
			}
			
			
			
			// Tsekataan täsmääkö argumenttien olemassaolo...
			
			var maintermargumentsallowed = this.argumentsallowed[argumentposition];
			if (maintermargumentsallowed == '0') {
				//console.log(' ++++ no arguments allowed');
				
				var argumentcount = fs.getArgumentCount();
				if (argumentcount > 0) {
					//console.log(' ***** no arguments allowed - '+argumentcount);
					console.log(' ++++ no arguments allowed');
					return false;
				}
				
			}
			if (maintermargumentsallowed == '1') {
				//console.log(' ++++ arguments allowed');
			}

			console.log('++++ match');
			
			return true;
		};

		
		this.getParentMatch = function(value1, value2) {
			console.log(' --- check parent match x1');
			
			if (value1 == value2) {
				console.log(' --- parent match true');
				return value1;
			}
			
			var parentID = fParents.get(value1);
			console.log('++++ parentID - '+parentID);
			
			if (parentID == value2) {
				console.log(' ++++ matchi xx22');
				return value1;
			}
			
			parentID = fParents.get(value2);
			console.log('++++ parentID - '+parentID);
			
			if (parentID == value1) {
				console.log(' ++++ matchi xx11');
				return value2;
			}
			
			return false;
			
			
			/*
			var valuearray1 = value1.split("|");
			var valuearray2 = value2.split("|");
			
			var index1 = 0;
			var index2 = 0;
			
			for (index1=0;index1<valuearray1.length;index1++) {
			
				for (index2=0;index2<valuearray2.length;index2++) {
					console.log('compare '+valuearray1[index1]+' vs. '+valuearray2[index2]);
					if (valuearray1[index1] == valuearray2[index2]) {
						console.log(' --- compare match');
						return valuearray1[index1];
					}
				}
				
				//for(i = 0;i < 5,i++) {
				//for(var index2 = 0;index2 < valuearray2.length;index2++) {
					//console.log('compare '+valuearray1[index1]+' vs. '+valuearray2[index2]);
				//}
			}
			
			console.log(' --- --- value1 - '+value1);
			
			
			console.log(' --- --- value2 - '+value2);
			return false;
			*/
		};
		
		
		this.isParentMatch = function(value1, value2) {
			console.log(' --- check parent match x2');
			console.log(' --- value1 - '+value1);
			console.log(' --- value2 - '+value2);
			
			if (value1 == value2) {
				console.log(' --- parent match x3 true');
				return true;
			}
			var parentID = fParents.get(value1);
			console.log('++++ parentID - '+parentID);
			
			if (parentID == value2) {
				console.log(' ++++ matchi 22');
				return true;
			}
			
			parentID = fParents.get(value2);
			console.log('++++ parentID - '+parentID);
			
			if (parentID == value1) {
				console.log(' ++++ matchi 11');
				return true;
			}
			
			console.log('++++ parent match x3 failed');
			return false;
			
			// Tätä on kai jälkikäteen muokattu, value1 ja value2 eivät ole enää listoja
			// Ei muistikuvaa milloin tätä on muutettu, korjattu hakemaan fParents-taulusta
			// HUOM: ei toimi useamman hierarkiatason kanssa...

			/*
			var valuearray1 = value1.split("|");
			var valuearray2 = value2.split("|");
			
			var index1 = 0;
			var index2 = 0;
			
			for (index1=0;index1<valuearray1.length;index1++) {
			
				for (index2=0;index2<valuearray2.length;index2++) {
					console.log('compare '+valuearray1[index1]+' vs. '+valuearray2[index2]);
					if (valuearray1[index1] == valuearray2[index2]) {
						console.log(' --- compare match');
						return true;
					}
				}
				
				//for(i = 0;i < 5,i++) {
				//for(var index2 = 0;index2 < valuearray2.length;index2++) {
					//console.log('compare '+valuearray1[index1]+' vs. '+valuearray2[index2]);
				//}
			}
			
			console.log(' --- --- value1 - '+value1);
			
			
			console.log(' --- --- value2 - '+value2);
			*/
		};
		
		
		this.applyGenerateRule = function(fs) {
			
			var comments = false;
			comments = true;
			
			//console.log(' --- applyGenerateRule - '+this.name);
			var counter = 0;
			var maintermPosition = -1;
			var emptyargument = false;
			var emptyposition = -1;
			for (var position in this.termarguments) {
				var argument = this.termarguments[position];
				//console.log(' --- pos:'+position+' - '+argument);
				if (argument == 2) {  // 1 = [empty], 2 = Mainterm
					//console.log(' --- mainterm found - '+position);
					maintermPosition = counter;
				}
				
				if (argument == 1) {     // 1 = [empty], 2 = Mainterm
					//console.log('empty argument present');
					emptyargument = true;
					emptyposition = counter;
				}
				
				counter++;
			}
			
			var mainterm = null;
			
			var leftFS = null;
			var rightFS = null;
			
			if (maintermPosition == -1) {
				console.log(' - maintermPosition -1 applyGenerateRule');
				
				console.log(' - emptypos -'+emptyposition);
				console.log(' - emptypos -'+emptyposition);
				
				console.log('conceptname x33 - '+this.conceptName)
				
				leftFS = new FeatureStructure(this.wordclassID, this.conceptName, this.conceptID, this.conceptName);
				rightFS = new FeatureStructure(this.termclasses[emptyposition], 'empty', this.termconceptIDs[emptyposition]);
				
				
			} else if (maintermPosition == 0) {			// verbi on ennen termiä
				
				if (emptyargument == true) {
					leftFS = fs.getCopy();
					rightFS = new FeatureStructure(this.termclasses[emptyposition], 'empty', this.termconceptIDs[emptyposition]);
					var conceptID = this.termconceptIDs[emptyposition];
					if (comments) console.log(' --- creating conceptID... '+conceptID);
					if (comments) console.log(' - creating empty rightfs - '+this.termclasses[emptyposition]);
					if (comments) console.log(' - creating empty rightfs - '+this.termconcepts[emptyposition]);
				} else {
					
					if (comments) console.log(' -- termcount - '+this.termarguments.length);
					
					if (this.termarguments.length == 1) {
						leftFS = fs.getCopy();
						rightFS = null;
					} else {
						var argumentToProcess = this.termarguments[1];
						//console.log('.. processing0 '+argumentToProcess);
						leftFS = fs.getCopy();
						leftFS.removeArgument(argumentToProcess);
						var rightFSTemp = fs.getArgumentValue(argumentToProcess);
						rightFS = rightFSTemp.getCopy();
					}
				}
			} else {
				
				if (emptyargument == true) {
					var rightFS = fs.getCopy();
					leftFS = new FeatureStructure(this.termclasses[emptyposition], 'empty', this.termconceptIDs[emptyposition]);
					if (comments) console.log(' - creating empty leftfs - '+this.termclasses[emptyposition]);
					if (comments) console.log(' - creating empty leftfs - '+this.termconcepts[emptyposition]);
				} else {
					var argumentToProcess = this.termarguments[0];
					//console.log('.. processing1 '+argumentToProcess);
					var rightFS = fs.getCopy();
					rightFS.removeArgument(argumentToProcess);
					var leftFSTemp = fs.getArgumentValue(argumentToProcess);
					leftFS = leftFSTemp.getCopy();
				}
				
			}
				
			//console.log('--------------------------------------- generate 1');
				
			//comments = true;
			var constraints = this.constraints[0];
			if (constraints != null) {
				constraints.forEach((value, feature) => {
					//var value = constraints.get(feature);
					if (comments) {
						console.log('-- left constaint - '+feature+' = ' +value);
						var featureStr1 = fNames.get(feature);
						var valueStr2 = fNames.get(value);
						console.log('-- left constaint - '+featureStr1+' = ' +valueStr2);
					}
					var found = false;
					leftFS.features.forEach((leftvalue, leftFeature) => {
						if (comments) console.log('-- leftFeature - '+leftFeature);
						if (leftFeature == feature) {
							
							var operator = this.constraintoperators[0].get(leftFeature);
							if (comments) console.log(' .. generate operator - '+operator);
							
							if (operator == 2) {
								if (comments) console.log(' - operator != no override');
								found = true;
							} else {
								if (comments) console.log('feature already found L -- check compapility...TODO');
								if (comments) console.log(' - rule - '+feature+' - '+value);
								//var leftvalue = leftFS.features[leftFeature];
								if (comments) console.log(' - existing - '+leftFeature+' - '+leftvalue);
								leftFS.addFeature(leftFeature,value);
								found = true;
							}
						}
					})
					//for(var leftFeature in leftFS.features) {
					//}
					if (found == false) {
						if (comments) console.log('adding feature 1 - '+feature+' - '+value);
						leftFS.addFeature(feature,value);
						if (comments) console.log(leftFS);
					}
				})
			}
			
			
			
			// Pitää myöskin asettaa result position valuessit. Eli result valuet jotka
			// tulevat variablen perusteella right or left features indeksin mukaan
			if (comments) console.log(' --- generate resultpositions ' + this.getResultFeatureCount());
			if (this.getResultPositionCount() > 0) {
				if (comments) console.log(' --- applyrule resultpositions found');
				this.resultpositions.forEach((index, feature) => {
					if (comments) console.log(' --- applyrule resultposition ' + feature + ' = index: '+index);
					
					if (this.termresultpositions[0] != null) {
						this.termresultpositions[0].forEach((val, sourcefeature) => {
							if (comments) console.log(' -- -- gen0 val:'+val+', sf:'+sourcefeature);
							
							if (fs.features.get(sourcefeature) == undefined) {
								if (comments) console.log(' --- --- no value found');
							} else {
								if (comments) console.log(' --- --- value found - '+fs.features.get(sourcefeature));
								var sourcevalue = fs.features.get(sourcefeature);
								
								if (maintermPosition == 1) {
									if (comments) console.log(' --- --- --- found value should be set right');
									var newvalue = leftFS.features.get(sourcefeature);
									if (comments) console.log(' --- --- --- found value -- '+newvalue);
									rightFS.addFeature(sourcefeature, newvalue);
								}
								//leftFS.addFeature(sourcefeature, sourcevalue);
								
							}
						})
					} else {
						if (comments) console.log('terresultpositions-0 is null');
					}

					if (this.termresultpositions[1] != null) {
						this.termresultpositions[1].forEach((val, sourcefeature) => {
							if (comments) console.log(' -- -- gen1 val:'+val+'sf:'+sourcefeature);
							
							if (fs.features.get(sourcefeature) == undefined) {
								if (comments) console.log(' --- --- no value found');
							} else {
								if (comments) console.log(' --- --- value found - '+fs.features.get(sourcefeature));
								var sourcevalue = fs.features.get(sourcefeature);
								rightFS.addFeature(sourcefeature, sourcevalue);

								if (maintermPosition == 0) {
									if (comments) console.log(' --- --- --- found value should be set left');
									var newvalue = rightFS.features.get(sourcefeature);
									if (comments) console.log(' --- --- --- found value -- '+newvalue);
									leftFS.addFeature(sourcefeature, newvalue);

								}

							}
						})
					} else {
						if (comments) console.log('terresultpositions-1 is null');
					}
				})
			}
			
			
			//for(var feature in this.constraints[0]) {
				
			//}
			if (comments) console.log(' -- xxxx leftcomplete');

			comments = false;
			if (rightFS != null) {
				if (comments) console.log(' -- xxxx rightsfs');
				var constraints = this.constraints[1];
				if (constraints != null) {
					constraints.forEach((value, feature) => {
						//var value = this.constraints[1][feature];
						if (comments) {
							console.log('-- right constaint - '+feature+' = ' +value);
							var featureStr1 = fNames.get(feature);
							var valueStr2 = fNames.get(value);
							console.log('-- right constaint - '+featureStr1+' = ' +valueStr2);
						}
						var found = false;
						rightFS.features.forEach((rightvalue, rightFeature) => {
							if (comments) console.log('-- rightFeature - '+rightFeature);
							if (rightFeature == feature) {
								if (comments) console.log('feature already found R -- check compapility...TODO');
								if (comments) console.log(' - rule - '+feature+' - '+value);
								var rightvalue = rightFS.features.get(rightFeature);
								if (comments) console.log(' - existing - '+rightFeature+' - '+rightvalue);
								rightFS.addFeature(rightFeature,value);
								found = true;
							}
						})
						//for(var rightFeature in rightFS.features) {
						//}
						if (found == false) {
							if (comments) console.log('adding feature 2 - '+feature+' - '+value);
							rightFS.addFeature(feature,value);
						}
					})
				}
				
				//for(var feature in this.constraints[1]) {
				//}
			}
			if (comments) console.log(' -- xxxx right complete');

			
			//console.log('----- agreements');
			// TODO: saattaa olla ongelmia, jos addfeaturet on asetettu jo aiemmin ylhäällä...
			var agreements = this.agreements[0];
			//if (agreements == null) console.log(' -- agreements on nulli');
			if (agreements != null) {
				agreements.forEach((index, feature) => {
					//var value = this.agreements[0][feature];
					//console.log('-- agreement - '+feature+' = ' +value);
					var found = false;
					if (comments) console.log(' -- xxxx agreements 1');
					var foundValue = 0;
					
					//console.log('leftfs feturecount - '+leftFS.features.length);
					leftFS.features.forEach((value, leftFeature) => {
						if (leftFeature == feature) {
							//value = leftFS.features[leftFeature];
							//console.log('---- found - '+value);
							found = true;
							foundValue = value;
							//break;
						}
					})
					
					//for(var leftFeature in leftFS.features) {
						//console.log('-- searching left for '+leftFeature);
					//} 
					if (comments) console.log(' -- xxxx agreements 2');

					if (found == true) {
						//console.log('---- found - '+feature+' - '+foundValue);
						
						rightFS.addFeature(feature,foundValue);
					} else {
						console.log('---- feature not found - '+feature);
						console.dir(rightFS);
						rightFS.features.forEach((rightvalue, rightFeature) => {
							if (rightvalue === undefined) {
								console.log('rightvalue is undefined');
							}
							console.log('-- searching right '+rightFeature);
							if (rightFeature == feature) {
								//value = rightFS.features[rightFeature];
								//console.log('---- found - '+value);
								foundValue = rightvalue;
								found = true;
								//break;
							}
						})
						//for(var rightFeature in rightFS.features) {
						//} 
						if (found == true) {
							console.log('adding agreement f - '+feature+' - '+foundValue);
							leftFS.addFeature(feature,foundValue);
						} else {
							console.log('agreement feature not found');
						}
					}
					if (comments) console.log(' -- xxxx agreements 3');
				})
			}
			
			
			
			//for(var feature in this.agreements[0]) {
			//}
			if (comments) console.log(' -- xxxx agreements complete');

			//console.log('---------------------------------------');
			fs.pushChild(leftFS);
			if (rightFS != null) fs.pushChild(rightFS);
		};
		
		
		// oletetaan tässävaiheessa, että fs1 ja fs2 ovat yhteensopivia, eli isCombatiblea ei tarvitse enää kutsua
		this.applyAnalyseRule = function(fs1, fs2) {
			//console.log('Rule.applyRule not implemented');
			
			
			//console.log('rule.index - '+rule.index);
			var comments = false;
			
			if (comments) console.log('rule.applyrule - '+rule.index);
			
			var fs = null;
			if (comments) console.log('mainterm position - '+rule.maintermposition);

			
			if (rule.maintermposition == -1) {
				console.log(' - - no mainterm pos applyAnalyseRule');
				fs = new FeatureStructure(this.wordclassID, this.conceptName, this.conceptID, this.conceptName);
				
				for (var index in this.conceptarguments) {
					var argumentID = this.conceptarguments[index];
					var componentID = this.conceptcomponents[index];
					console.log(' - setting - '+argumentID+' - '+componentID);
					fs.addRequirement(argumentID, componentID);
				}
				
				
				// TODO... haetaan rulelle argumentit...
				
				for (var position in this.termarguments) {
					
					var argument = this.termarguments[position];
					console.log('x - position --- '+position);
					console.log('x - argument --- '+argument);
					
					fs.addArgument(argument, fs1);
					fs.removeRequirement(argument);
				}
			}
			
			if (rule.maintermposition == 0) {
				fs = fs1.getCopy();
				if (comments) console.log('mainterm index = 0');

				for (var position in this.termarguments) {
					if (this.termarguments[position] != 2) {   // 1 = [empty], 2 = Mainterm
						var argument = this.termarguments[position];
						if (position == 1) {
							if (comments) console.log('setting argument (1), position -'+position+', argument'+argument);
							
							if (argument == 1) {   // 1 = [empty], 2 = Mainterm
								//fs.addArgument(argument, fs2);
								fs.removeRequirement(argument);
							} else {
								fs.addArgument(argument, fs2);
								fs.removeRequirement(argument);
							}
							
						} else {
							console.log('failed term arguments (1), position -'+position+', argument'+argument);
						}
					}
				}
				
			} else if (rule.maintermposition == 1) {
				fs = fs2.getCopy();
				if (comments) console.log('mainterm index = 1');
				
				for (var position in this.termarguments) {
					if (this.termarguments[position] != 2) {    // 1 = [empty], 2 = Mainterm
						var argument = this.termarguments[position];
						if (position == 0) {
							if (comments) console.log('setting argument (2), position -'+position+', argument'+argument);
							//fs.addArgument(argument, fs1);
							//fs.removeRequirement(argument);
							
							if (argument == 1) {    // 1 = [empty], 2 = Mainterm
								//fs.addArgument(argument, fs2);
								fs.removeRequirement(argument);
							} else {
								fs.addArgument(argument, fs1);
								fs.removeRequirement(argument);
							}
							
						} else {
							console.log('failed term arguments (2), position -'+position+', argument'+argument);
						}
					}
				}
				
			} else {
				if (comments) console.log('index = none');
				
			}
			
			if (comments) console.log('--- --- --- applyrule');
			
			// Kelaa agreementit lävitse
			var foundagreements = new Map();
			//comments = true;
			for (var position in this.agreements) {
				if (comments) console.log('--- applyrule agreements in position - '+position);
				if (position == 0) {
					if (comments) console.log('--- applyrule position 0');
					var features = this.agreements[position];
					var flag = true;
					features.forEach((index, feature) => {
						if (comments) console.log('--- --- applyrule feature - '+feature);
						//var index = features[feature];
						if (foundagreements.get(index) == undefined) {
							if (fs1.features.get(feature) == undefined) {
								if (comments) console.log(' --- applyrule no feature '+feature+' found in fs1');
								flag = false;
								//return false;
							} else {
								var value = fs1.features.get(feature);
								if (comments) console.log(' --- applyrule value found '+value);
								foundagreements.set(index,value);
								//foundargreements[index] = index;
								if (comments) console.log(' --- applyrule '+feature+' - '+index+' undefined, setting '+value);
							}
						} else {
							if (fs1.features.get(feature) == undefined) {
								if (comments) console.log(' --- applyrule no feature '+feature+' found in fs1');
								flag = false;
								//return false;
							} else {
								var value = fs1.features.get(feature);
								
								if (foundagreements.get(index) == value) {
									if (comments) console.log(' --- applyrule agreement holds - '+value);
								} else {
									if (comments) console.log(' --- applyrule agreement missmatch 1- '+foundagreements.get(index)+' - '+value);
									flag = false;
									//return false;
								}
							}
						}
					})
					if (flag == false) return false;
					//for (var feature in features) {
					//}
				}
				
				if (position == 1) {
					if (comments) console.log('--- applyrule position 1');
					var features = this.agreements[position];
					var flag = true;
					features.forEach((index, feature) => {
						if (comments) console.log('--- --- applyrule feature - '+feature);
						//var index = features[feature];
						if (foundagreements.get(index) == undefined) {
							
							if (fs2.features.get(feature) == undefined) {
								if (comments) console.log(' --- applyrule no feature '+feature+' found in fs2');
								flag = false;
								//return false;
							} 
							var value = fs2.features.get(feature);
							if (comments) console.log(' --- applyrule value found '+value);
							foundagreements.set(index,value);
							//foundargreements[index] = index;
							if (comments) console.log(' --- applyrule '+feature+' - '+index+' undefined, setting '+value);
						} else {
							if (fs2.features.get(feature) == undefined) {
								if (comments) console.log(' --- applyrule no feature '+feature+' found in fs2');
								flag = false;
								//return false;
							} 
							var value = fs2.features.get(feature);
							//console.log(' --- fs2 value - '+value);
							
							// Tässä pitäisi katsoa, onko foundagreementsit parenttien suhteen compatible? Molemminpäin
							
							if (foundagreements.get(index) == value) {
								if (comments) console.log(' --- applyrule agreement holds - '+value);
							} else {
								
								var match = this.getParentMatch(foundagreements.get(index),value);
								if (comments) console.log(' --- applyrule matched value - '+match);
								fs.features.set(feature, match);
								
								if (match == true) {
									if (comments) console.log(' --- applyrule agreement match 2 - '+foundagreements.get(index)+' - '+value);
								} else {
									if (comments) console.log(' --- applyrule agreement missmatch 2 - '+foundagreements.get(index)+' - '+value);
									
									console.log('TODO: checkthis');
									console.log('TODO: checkthis');
									console.log('TODO: checkthis');
									console.log('TODO: checkthis');
									console.log('TODO: checkthis');
									//TODO: tämä oli jostainsyystä kommentoitu aiemmin pois??
									//flag = false;
									//return false;
								}
							}
						}
					})
					if (flag == false) return false;
					//for (var feature in features) {
					//}
				}
			}
			
			
			if (comments) console.log(' --- applyrule resultfeatures ' + this.getResultFeatureCount());
			if (this.getResultFeatureCount() > 0) {
				if (comments) console.log(' --- applyrule resultfeatures found');
				this.resultfeatures.forEach((resultvalue, resultfeature) => {
					//var featurevalue = this.resultfeatures[feature];
					if (comments) console.log(' --- applyrule addfeature ' + resultfeature + ' = '+resultvalue);
					if (fs == null) {
						if (comments) console.log(' --- fs on null');
					}
					fs.addFeature(resultfeature, resultvalue);
				})
				//for (var feature in this.resultfeatures) {
				//}
			}
			
			//console.dir(fs1);
			//console.dir(fs2);
			//console.dir(this);
			if (comments) console.log(' --- applyrule resultpositions ' + this.getResultFeatureCount());
			if (this.getResultPositionCount() > 0) {
				if (comments) console.log(' --- applyrule resultpositions found');
				this.resultpositions.forEach((index, feature) => {
					//var index = this.resultpositions[feature];
					if (comments) console.log(' --- applyrule resultposition ' + feature + ' = index: '+index);
					//if (comments) console.log(' --- applyrule resultposition ' + feature + ' = position: ?? '+position);
					// example this.termresultpositions[position].set(sourcefeatureID, index);
					
					if (this.termresultpositions[0] != null) {
						this.termresultpositions[0].forEach((val, sourcefeature) => {
							if (comments) console.log(' -- -- check0 val:'+val+', sf:'+sourcefeature);
							//if (sourcefeature == index) {
							if (val == index) {
								if (fs1.features.get(sourcefeature) == undefined) {
									if (comments) console.log(' --- --- no value found');
								} else {
									if (comments) console.log(' --- --- value found - '+fs1.features.get(sourcefeature));
									var sourcevalue = fs1.features.get(sourcefeature);
									
									if (feature != sourcefeature) {
										if (comments) console.log('--- --- this is semantic feature - '+feature);
										var value2 = linkSharedFeature(feature, sourcevalue);
										if (comments) console.log('--- --- this is semantic feature - '+value2);
										if (value2 != null) {
											fs.addFeature(feature, value2);
										} else {
											fs.addFeature(feature, 'n/a');
										}
										// Kyse on semanttisesta featuresta, eli pitää suorittaa porttaus...
									} else {
										console.log('addfeature x1');
										fs.addFeature(feature, sourcevalue);
									}
								}
							}
						})
					} else {
						if (comments) console.log('terresultpositions-0 is null');
					}
					
					
					
					//for (var sourcefeature in this.termresultpositions[0]) {
					//}
					if (this.termresultpositions[1] != null) {
						this.termresultpositions[1].forEach((val, sourcefeature) => {
							if (comments) console.log(' -- -- check1 val:'+val+'sf:'+sourcefeature);
							if (sourcefeature == index) {
								if (fs2.features.get(sourcefeature) == undefined) {
									if (comments) console.log(' --- --- no value found');
								} else {
									if (comments) console.log(' --- --- value found - '+fs2.features.get(sourcefeature));
									var sourcevalue = fs2.features.get(sourcefeature);
									if (comments) console.log(' --- --- value found - '+feature + " vs. " + sourcefeature);
									
									if (feature != sourcefeature) {
										if (comments) console.log('--- --- this is semantic feature - '+feature);
										var value2 = linkSharedFeature(feature, sourcevalue);
										fs.addFeature(feature, sourcevalue);
										console.log('addfeature x1');
										// Kyse on semanttisesta featuresta, eli pitää suorittaa porttaus...
									} else {
										fs.addFeature(feature, sourcevalue);
									}
									
								}
							}
						})
					} else {
						if (comments) console.log('terresultpositions-1 is null');
					}
					
					//for (var sourcefeature in this.termresultpositions[1]) {
					//}
				})
				//for (var feature in this.resultpositions) {
					
				//}
			}
			return fs;
		};
		
		
		this.drawRule = function(context, x, y, fontHeight) {

			//console.log('rule draw');
			
			context.textBaseline = 'top';
			context.lineWidth= 2;
			var topSpacing = 2;
			var leftSpacing = 3;
			var numberTopSpacing = 3;
			var squareSize = 18;
			var extraWidth = context.measureText('=').width;

			//var str = wordclassStr + 'P';
			//console.log('drawing... '+this.index);
			var width = 0;
			var left = 0;

			if (this.index > -1) {
				context.font = '16px Arial';
				context.beginPath();
				context.rect(x,y,squareSize,squareSize);
				context.stroke();
				width = context.measureText(this.index).width;
				left = Math.round((squareSize-width) / 2)-1;
				context.fillText(this.index, x+left, y+numberTopSpacing);
			}
			context.font = '20px Arial';
			var wordclassStr = wAbbs.get(this.wordclassID)+'P';
			context.fillText(wordclassStr, x+22, y);

			var maxWidth = 0;
			var maxResultValueWidth = squareSize+(2*leftSpacing);
			var argumentcount = 0;
			for (var position in this.termarguments) {
				if (this.termarguments[position] != 2) {   // 1 = [empty], 2 = Mainterm
					if (this.termarguments[position]  == 1) {   // 1 = [empty], 2 = Mainterm
						//console.log('empty argument');
					} else {
						var argumentStr = aNames.get(this.termarguments[position]);
						width = context.measureText(argumentStr).width;
						if (width > maxWidth) maxWidth = width;
						argumentcount++;
					}
				}
			}
			this.resultfeatures.forEach((resultvalue, resultfeature) => {
				var featureStr = fNames.get(resultfeature);
				width = context.measureText(featureStr).width;
				if (width > maxWidth) maxWidth = width;
				argumentcount++;
				//var value = this.resultfeatures[feature];
				var valueStr = fNames.get(resultvalue);
				width = context.measureText(valueStr).width;
				if (width > maxResultValueWidth) maxResultValueWidth = width;
			})
			//for (var feature in this.resultfeatures) {
			//}
			this.resultpositions.forEach((index, feature) => {
				var featureStr = fNames.get(feature);
				width = context.measureText(featureStr).width;
				if (width > maxWidth) maxWidth = width;
				argumentcount++;
				//var value = this.resultpositions[feature];
				//var valueStr = fNames.get(value);
				
				width = context.measureText(index).width;
				if (width > maxResultValueWidth) maxResultValueWidth = width;
			})
			
			//for (var feature in this.resultpositions) {
			//}
			var tempy = y + fontHeight + topSpacing;
			
			//console.log('rule draw arguments - '+argumentcount);
			
			//if (argumentcount > 0) {

// Vasemman termin, vasen hakasulku
				var totalheight = this.getHeight(context, fontHeight, topSpacing);
				context.beginPath();
				context.moveTo(x+(1.5*leftSpacing),tempy);
				context.lineTo(x,tempy);
				context.lineTo(x,tempy+totalheight);
				context.lineTo(x+(1.5*leftSpacing),tempy+totalheight);
				context.stroke();

// Vasemman termin, oikea hakasulku
				context.beginPath();
				var squarepos = x+maxWidth+extraWidth+(5*leftSpacing)+maxResultValueWidth;
				context.moveTo(squarepos-(1.5*leftSpacing),tempy);
				context.lineTo(squarepos,tempy);
				context.lineTo(squarepos,tempy+totalheight);
				context.lineTo(squarepos-(1.5*leftSpacing),tempy+totalheight);
				context.stroke();

				var maxIndexWidth = 0;
				for (var position in this.termarguments) {
					if (this.termarguments[position] != 2) {   // 1 = [empty], 2 = Mainterm
						
						if (this.termarguments[position] != 1) {  // 1 = [empty], 2 = Mainterm
							var argumentStr = aNames.get(this.termarguments[position]);
							//var value = this.termindexes[argument];
							context.fillText(argumentStr, x+leftSpacing,tempy);
							//context.fillText(this.termarguments[position], x+leftSpacing,tempy);
							context.fillText('=', x+maxWidth+(2*leftSpacing),tempy);
							context.font = '16px Arial';
							context.beginPath();
							context.rect(x+maxWidth+extraWidth+(4*leftSpacing),tempy+topSpacing,squareSize,squareSize);
							context.stroke();
							context.font = '16px Arial';
							width = context.measureText(this.index).width;
							if (width > maxIndexWidth) maxIndexWidth = width;
							left = Math.round((squareSize-width) / 2);
							context.fillText(this.termindexes[position], x+maxWidth+extraWidth+(4*leftSpacing)+left, tempy+topSpacing+numberTopSpacing);

							tempy = tempy + fontHeight + topSpacing;
						}
					}
				}
				
				this.resultfeatures.forEach((resultvalue, resultfeature) => {
					var featureStr = fNames.get(resultfeature);
					context.font = '20px Arial';
					context.fillText(featureStr, x+leftSpacing,tempy);
					context.fillText('=', x+maxWidth+(2*leftSpacing),tempy);
					var valueStr = fNames.get(resultvalue);
					context.fillText(valueStr, x+maxWidth+extraWidth+(3*leftSpacing), tempy+topSpacing);
					tempy = tempy + fontHeight + topSpacing;
				})
				//for (var feature in this.resultfeatures) {
				//}
				
				this.resultpositions.forEach((index, feature) => {
					//var value = this.resultpositions[feature];
					context.font = '20px Arial';
					var featureStr = fNames.get(feature);
					context.fillText(featureStr, x+leftSpacing,tempy);
					context.fillText('=', x+maxWidth+(2*leftSpacing),tempy);
					context.font = '16px Arial';
					context.beginPath();
					context.rect(x+maxWidth+extraWidth+(4*leftSpacing),tempy+topSpacing,squareSize,squareSize);
					context.stroke();
					context.font = '16px Arial';
					width = context.measureText(index).width;
					//width = context.measureText(this.index).width;
					if (width > maxIndexWidth) maxIndexWidth = width;
					left = Math.round((squareSize-width) / 2);
					
					context.fillText(index, x+maxWidth+extraWidth+(4*leftSpacing)+left, tempy+topSpacing+numberTopSpacing);
					//context.fillText(this.resultpositions[feature], x+maxWidth+extraWidth+(4*leftSpacing)+left, tempy+topSpacing+numberTopSpacing);
					context.font = '20px Arial';
					tempy = tempy + fontHeight + topSpacing;
				})
				
				//for (var feature in this.resultpositions) {
				//}
				
				
				x = x + maxIndexWidth + maxWidth + (extraWidth)+(6*leftSpacing) + maxResultValueWidth;
				context.font = '30px Arial';
				context.fillText('=', x,y+(fontHeight/2));
				context.font = '20px Arial';
				x = x + extraWidth+(5*leftSpacing);

// kelataan lävitse rulen oikeanpuolen termit
				var firstposition = 0;
				for (var position in this.termclasses) {

					if (firstposition == 0) {
						firstposition = 1;
					} else {
						context.font = '30px Arial';
						context.fillText('+', x+2*leftSpacing,y+(fontHeight/2));
						context.font = '20px Arial';
						x = x + extraWidth+(6*leftSpacing);
					}

					var positionIndex = this.termindexes[position];
					var wordclassStr = wAbbs.get(this.termclasses[position]);
					if (this.argumentsallowed[position] == 1) {
						str = wordclassStr + 'P';
					} else {
						str = wordclassStr + '';
					}
					if (this.termconceptIDs[position] > 0) {
						str = str + '(' + this.termconcepts[position] + ')';
					}
					
					if (positionIndex > -1) {
						context.font = '16px Arial';
						context.beginPath();
						context.rect(x,y,squareSize,squareSize);
						context.stroke();
						width = context.measureText(this.index).width;
						left = Math.round((squareSize-width) / 2)-1;
						context.fillText(positionIndex, x+left, y+numberTopSpacing);
					}
					context.font = '20px Arial';
					context.fillText(str, x+22, y);
					var titleWidth = context.measureText(str).width;
					//console.log('titlewidth - '+titleWidth);
					//console.log('titlewidthx - '+str);
					
					var maxWidth = titleWidth;
					var valueWidth = 0;
					var maxValueWidth = 0;
					var argumentcount = 0;
					var totalheight = 0;
					if (this.agreements[position] != null) {
						this.agreements[position].forEach((index, feature) => {
							
							var featureStr = fNames.get(feature);
							width = context.measureText(featureStr).width;
							if (width > maxWidth) maxWidth = width;
							totalheight = totalheight + fontHeight + topSpacing;
							argumentcount ++;
						})
					} else {
						//console.log('position agreements null');
					}
					//for (var agreement in this.agreements[position]) {
					//}
					if (this.constraints[position] != null) {
						this.constraints[position].forEach((value, feature) => {
							featureStr = fNames.get(feature);
							width = context.measureText(featureStr).width;
							if (width > maxWidth) maxWidth = width;
							//width = context.measureText(featureStr).width;
							//width = context.measureText(this.constraints[position][feature]).width;
							valueStr = fNames.get(value);
							width = context.measureText(valueStr).width;
							if (width > maxValueWidth) maxValueWidth = width;
							totalheight = totalheight + fontHeight + topSpacing;
							argumentcount ++;
						})
					}
					
					//for (var feature in this.constraints[position]) {
					//} 
					if (this.termresultpositions[position] != null) {
						this.termresultpositions[position].forEach((index, feature) => {
							var featureStr = fNames.get(feature);
							width = context.measureText(featureStr).width;
							if (width > maxWidth) maxWidth = width;
							width = context.measureText(index).width;
							//width = context.measureText(this.termresultpositions[position][feature]).width;
							if (width > maxValueWidth) maxValueWidth = width;
							totalheight = totalheight + fontHeight + topSpacing;
							argumentcount++;
						})	
					}
					
					//for (var feature in this.termresultpositions[position]) {
					//}
					if (this.components[position] != null) {
						this.components[position].forEach((presence, component) => {
							var componentStr = cAbbs.get(component);
							width = context.measureText(componentStr).width;
							if (width > maxWidth) maxWidth = width;
							width = context.measureText('+').width;
							if (width > maxValueWidth) maxValueWidth = width;
							totalheight = totalheight + fontHeight + topSpacing;
							argumentcount ++;
						})
					}
					//for (var component in this.components[position]) {
					//}
					if (this.unsetarguments[position] != null) {
						this.unsetarguments[position].forEach((valueID, argumentID) => {
							//var argument = this.unsetarguments[position][argumentID];
							var argumentStr = aNames.get(argumentID);
							width = context.measureText(argumentStr).width;
							if (width > maxWidth) maxWidth = width;
							width = context.measureText('-').width;
							if (width > maxValueWidth) maxValueWidth = width;
							totalheight = totalheight + fontHeight + topSpacing;
							argumentcount ++;
						})
					}
					//for (var argumentID in this.unsetarguments[position]) {
					//}
					if (this.unsetfeatures[position] != null) {
						this.unsetfeatures[position].forEach((valueID, featureID) => {
							//var argument = this.unsetfeatures[position][argumentID];
							var featureStr = aNames.get(featureID);
							width = context.measureText(featureStr).width;
							if (width > maxWidth) maxWidth = width;
							width = context.measureText('+').width;
							if (width > maxValueWidth) maxValueWidth = width;
							totalheight = totalheight + fontHeight + topSpacing;
							argumentcount ++;
						})
					}
					
					//for (var argumentID in this.unsetfeatures[position]) {
					//}
					//if (titleWidth > maxWidth) maxWidth = titleWidth;
					
					
					if (squareSize > maxValueWidth) maxValueWidth = squareSize;
					var tempy = y + fontHeight + topSpacing;


					if (argumentcount > 0) {

// oikeanpuolen termin, vasen hakasulku
						context.beginPath();
						context.moveTo(x+(1.5*leftSpacing),tempy);
						context.lineTo(x,tempy);
						context.lineTo(x,tempy+totalheight);
						context.lineTo(x+(1.5*leftSpacing),tempy+totalheight);
						context.stroke();

// oikeanpuolen termin, oikea hakasulku
						context.beginPath();
						var squarepos = x+maxWidth+extraWidth+(7*leftSpacing)+maxValueWidth;
						context.moveTo(squarepos-(1.5*leftSpacing),tempy);
						context.lineTo(squarepos,tempy);
						context.lineTo(squarepos,tempy+totalheight);
						context.lineTo(squarepos-(1.5*leftSpacing),tempy+totalheight);
						context.stroke();


						var maxIndexWidth = 0;
						if (this.agreements[position] != null) {
							this.agreements[position].forEach((findex, feature) => {
								//var findex = this.agreements[position][feature];
								var featureStr = fNames.get(feature);
								context.fillText(featureStr, x+leftSpacing,tempy);
								context.fillText('=', x+maxWidth+(2*leftSpacing),tempy);
								context.font = '16px Arial';
								context.beginPath();
								context.rect(x+maxWidth+extraWidth+(4*leftSpacing),tempy+topSpacing,squareSize,squareSize);
								context.stroke();
								width = context.measureText(findex).width;
								if (width > maxIndexWidth) maxIndexWidth = width;
								left = Math.round((squareSize-width) / 2);
								context.fillText(findex, x+maxWidth+extraWidth+(4*leftSpacing)+left, tempy+topSpacing+numberTopSpacing);
								context.font = '20px Arial';
								tempy = tempy + fontHeight + topSpacing;
							})
						} else {
							//console.log(' position agreements is null');
						}
						/*
						for (var feature in this.agreements[position]) {
							var findex = this.agreements[position][feature];
							context.fillText(feature, x+leftSpacing,tempy);
							context.fillText('=', x+maxWidth+(2*leftSpacing),tempy);
							context.font = '16px Arial';
							context.beginPath();
							context.rect(x+maxWidth+extraWidth+(4*leftSpacing),tempy+topSpacing,squareSize,squareSize);
							context.stroke();
							width = context.measureText(findex).width;
							if (width > maxIndexWidth) maxIndexWidth = width;
							left = Math.round((squareSize-width) / 2);
							context.fillText(findex, x+maxWidth+extraWidth+(4*leftSpacing)+left, tempy+topSpacing+numberTopSpacing);
							context.font = '20px Arial';
							tempy = tempy + fontHeight + topSpacing;
						}
						*/

						if (this.constraints[position] != null) {
							this.constraints[position].forEach((value, feature) => {
								//var value = this.constraints[position][feature];
									
								var featureStr = fNames.get(feature);
								context.fillText(featureStr, x+leftSpacing,tempy);
								
								var operator = this.constraintoperators[position].get(feature);
								if (operator == 1) {
									context.fillText('=', x+maxWidth+(2*leftSpacing),tempy);
								} else if (operator == 5) {
									context.fillText('=>', x+maxWidth+(2*leftSpacing),tempy);
								} else if (operator == 2) {
									context.fillText('!=', x+maxWidth+(2*leftSpacing),tempy);
								} else {
									context.fillText('a', x+maxWidth+(2*leftSpacing),tempy);
								}
								
								var valueStr = fNames.get(value);
								context.fillText(valueStr, x+maxWidth+extraWidth+(4*leftSpacing), tempy);
								tempy = tempy + fontHeight + topSpacing;
							})
						}
						
						//for (var feature in this.constraints[position]) {
						//}
						if (this.termresultpositions[position] != null) {
							this.termresultpositions[position].forEach((findex, feature) => {
								//var findex = this.termresultpositions[position][feature];
								var featureStr = fNames.get(feature);
								context.fillText(featureStr, x+leftSpacing,tempy);
								context.fillText('=', x+maxWidth+(2*leftSpacing),tempy);
								context.font = '16px Arial';
								context.beginPath();
								context.rect(x+maxWidth+extraWidth+(4*leftSpacing),tempy+topSpacing,squareSize,squareSize);
								context.stroke();
								width = context.measureText(findex).width;
								if (width > maxIndexWidth) maxIndexWidth = width;
								left = Math.round((squareSize-width) / 2);
								context.fillText(findex, x+maxWidth+extraWidth+(4*leftSpacing)+left, tempy+topSpacing+numberTopSpacing);
								context.font = '20px Arial';
								tempy = tempy + fontHeight + topSpacing;
							})
						}
						//for (var feature in this.termresultpositions[position]) {
						//}
						
						if (this.components[position] != null) {
							this.components[position].forEach((presence, component) => {
								//var presence = this.components[position][component];
								var componentStr = cAbbs.get(component);
								context.fillText(componentStr, x+leftSpacing,tempy);
								width = context.measureText(componentStr).width;
								
								if (presence == 2) {
									context.fillText('-', x+width+(2*leftSpacing),tempy);
								} else {
									context.fillText('+', x+width+(2*leftSpacing),tempy);
								}
								//context.fillText(value, x+maxWidth+extraWidth+(4*leftSpacing), tempy);
								tempy = tempy + fontHeight + topSpacing;
							})
						}
						//for (var component in this.components[position]) {
						//}
						
						if (this.unsetarguments[position] != null) {
							this.unsetarguments[position].forEach((valueID, argumentID) => {
								//var argument = this.unsetarguments[position][argumentID];
								var argumentStr = aNames.get(argumentID);
								context.fillText(argumentStr, x+leftSpacing,tempy);
								context.fillText('=', x+maxWidth+(2*leftSpacing),tempy);
								context.fillText('o', x+maxWidth+extraWidth+(4*leftSpacing), tempy);
								
								context.beginPath();
								context.moveTo(x+maxWidth+extraWidth+(4*leftSpacing)-1, tempy+16);
								context.lineTo(x+maxWidth+extraWidth+(4*leftSpacing)+12, tempy+5);
								context.stroke();
								
								tempy = tempy + fontHeight + topSpacing;
							})
						}
						
						//for (var argumentID in this.unsetarguments[position]) {
						//}
						if (this.unsetfeatures[position] != null) {
							this.unsetfeatures[position].forEach((valueID, featureID) => {
								//var feature = this.unsetfeatures[position][featureID];
								var featureStr = fNames.get(featureID);
								context.fillText(featureStr+'-'+featureID, x+leftSpacing,tempy);
								context.fillText('=', x+maxWidth+(2*leftSpacing),tempy);
								context.fillText('o', x+maxWidth+extraWidth+(4*leftSpacing), tempy);
								
								context.beginPath();
								context.moveTo(x+maxWidth+extraWidth+(4*leftSpacing)-1, tempy+16);
								context.lineTo(x+maxWidth+extraWidth+(4*leftSpacing)+12, tempy+5);
								context.stroke();
								
								tempy = tempy + fontHeight + topSpacing;
							})
						}
						//for (var featureID in this.unsetfeatures[position]) {
						//}
					}
					
					
					x = x + maxIndexWidth + maxWidth + extraWidth+(6*leftSpacing) + maxValueWidth;
				}
			//}
		};

		this.getHeight = function(context, fontHeight, topSpacing) {
			var totalheight = 0;
			var valueheight = 0;
			var argumentcount = 0;
			for (var position in this.termarguments) {
				if (this.termarguments[position] != 2) {   // 1 = [empty], 2 = Mainterm
					if (this.termarguments[position] != 1) {   // 1 = [empty], 2 = Mainterm
						totalheight = totalheight + fontHeight + topSpacing;
						argumentcount++;
					}
				}
			}
			this.resultfeatures.forEach((resultvalue, resultfeature) => {
				totalheight = totalheight + fontHeight + topSpacing;
				argumentcount++;
			})
			/*
			for (var feature in this.resultfeatures) {
				totalheight = totalheight + fontHeight + topSpacing;
				argumentcount++;
			}
			*/
			this.resultpositions.forEach((index, feature) => {
				totalheight = totalheight + fontHeight + topSpacing;
				argumentcount++;
			})
			//for (var feature in this.resultpositions) {
			//}
			
			
			if (argumentcount == 0) {
				//console.log('argumentcount - 0');
				return fontHeight;
			}
			return totalheight;
		};


		this.getWidth = function(context, leftSpacing) {
		};

	}

