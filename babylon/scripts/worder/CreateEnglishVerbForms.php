<?php

	// Excelissa on lista, josta voidaan napata verbien kaikki muodot, sekä wordID
	// - tehdään funktio, joka lisää kaikki muodot kantaan, hardkoodatuilla arvoilla...
	



	function InsertFormsToDatabase($wordID, $base, $thirdsg, $progressive, $imperfect, $perfect) {
		
		echo "<br>Insert - " . $base;
		
		$comments = false;
		$languageID = 2;	// english
		$wordclassID = 2;	// verb
		
		$values = array();
		$values['WordID'] = $wordID;
		$values['Wordform'] = $base;
		$values['Features'] =  "127";
		$values['GrammarID'] =  $_SESSION['grammarID'];
		$values['LanguageID'] =  $languageID;
		$values['WordclassID'] = $wordclassID;
		$values['Grammatical'] = 1;
		$values['Defaultform'] = 1;
		$rowID = Table::addRow('worder_wordforms', $values, $comments);
		
		$values = array();
		$values['WordID'] = $wordID;
		$values['Wordform'] = $thirdsg;
		$values['Features'] =  "127:132:136:268";
		$values['GrammarID'] =  $_SESSION['grammarID'];
		$values['LanguageID'] =  $languageID;
		$values['WordclassID'] = $wordclassID;
		$values['Grammatical'] = 1;
		$values['Defaultform'] = 1;
		$rowID = Table::addRow('worder_wordforms', $values, $comments);
		
		$values = array();
		$values['WordID'] = $wordID;
		$values['Wordform'] = $progressive;
		$values['Features'] =  "130";
		$values['GrammarID'] =  $_SESSION['grammarID'];
		$values['LanguageID'] =  $languageID;
		$values['WordclassID'] = $wordclassID;
		$values['Grammatical'] = 1;
		$values['Defaultform'] = 1;
		$rowID = Table::addRow('worder_wordforms', $values, $comments);
		
		$values = array();
		$values['WordID'] = $wordID;
		$values['Wordform'] = $imperfect;
		$values['Features'] =  "128";
		$values['GrammarID'] =  $_SESSION['grammarID'];
		$values['LanguageID'] =  $languageID;
		$values['WordclassID'] = $wordclassID;
		$values['Grammatical'] = 1;
		$values['Defaultform'] = 1;
		$rowID = Table::addRow('worder_wordforms', $values, $comments);
		
		$values = array();
		$values['WordID'] = $wordID;
		$values['Wordform'] = $perfect;
		$values['Features'] =  "129";
		$values['GrammarID'] =  $_SESSION['grammarID'];
		$values['LanguageID'] =  $languageID;
		$values['WordclassID'] = $wordclassID;
		$values['Grammatical'] = 1;
		$values['Defaultform'] = 1;
		$rowID = Table::addRow('worder_wordforms', $values, $comments);
		
	}
	
	
	/*
	//InsertFormsToDatabase(683,'drink','drinks','drinking','drank','drunk');
	//InsertFormsToDatabase(684,'sing','sings','singing','sang','sung');
	//InsertFormsToDatabase(685,'play','plays','playing','played','played');
	//InsertFormsToDatabase(691,'build','builds','building','built','built');
	InsertFormsToDatabase(692,'bake','bakes','bakeing','baked','baked');
	InsertFormsToDatabase(693,'amplify','amplifies','amplifying','amplified','amplified');
	InsertFormsToDatabase(694,'reduce','reduces','reducing','reduced','reduced');
	InsertFormsToDatabase(695,'clean','cleans','cleaning','cleaned','cleaned');
	InsertFormsToDatabase(696,'kill','kills','killing','killed','killed');
	InsertFormsToDatabase(697,'lecture','lectures','lecturing','lectured','lectured');
	InsertFormsToDatabase(698,'tell','tells','telling','told','told');
	InsertFormsToDatabase(699,'send','sends','sending','sent','sent');
	InsertFormsToDatabase(700,'throw','throws','throwing','threw','thrown');
	InsertFormsToDatabase(702,'buy','buys','buying','bought','bought');
	InsertFormsToDatabase(703,'sell','sells','selling','sold','sold');
	InsertFormsToDatabase(704,'see','sees','seeing','saw','seen');
	InsertFormsToDatabase(705,'hear','hears','hearing','heard','heard');
	InsertFormsToDatabase(706,'report','reports','reporting','reported','reported');
	InsertFormsToDatabase(707,'write','writes','writing','wrote','written');
	InsertFormsToDatabase(708,'read','reads','reading','read','read');
	InsertFormsToDatabase(709,'watch','watchs','watching','watched','watched');
	InsertFormsToDatabase(710,'transport','transports','transporting','transported','transported');
	InsertFormsToDatabase(711,'listen','listens','listening','listened','listened');
	InsertFormsToDatabase(712,'dress','dresses','dressing','dressed','dressed');
	InsertFormsToDatabase(714,'leave','leaves','leaveing','left','left');
	InsertFormsToDatabase(715,'travel','travels','traveling','traveled','traveled');
	InsertFormsToDatabase(716,'say','says','saying','said','said');
	InsertFormsToDatabase(719,'think','thinks','thinking','thought','thought');
	InsertFormsToDatabase(720,'remember','remembers','remembering','remembered','remembered');
	InsertFormsToDatabase(721,'love','loves','loving','loved','loved');
	InsertFormsToDatabase(722,'hate','hates','hating','hated','hated');
	InsertFormsToDatabase(723,'like','likes','liking','liked','liked');
	InsertFormsToDatabase(724,'forget','forgets','forgetting','forgot','forgotten');
	InsertFormsToDatabase(725,'shout','shouts','shouting','shouted','shouted');
	InsertFormsToDatabase(726,'row','rows','rowing','rowed','rowed');
	InsertFormsToDatabase(727,'dig','digs','digging','dug','dug');
	InsertFormsToDatabase(728,'pour','pours','pouring','poured','poured');
	InsertFormsToDatabase(729,'count','counts','counting','counted','counted');
	InsertFormsToDatabase(730,'feel','feels','feeling','felt','felt');
	InsertFormsToDatabase(731,'dance','dances','dancing','danced','danced');
	InsertFormsToDatabase(732,'act','acts','acting','acted','acted');
	InsertFormsToDatabase(733,'allow','allows','allowing','allowed','allowed');
	InsertFormsToDatabase(734,'let','lets','letting','let','let');
	InsertFormsToDatabase(735,'forbid','forbids','forbidding','forbid','forbidden');
	InsertFormsToDatabase(737,'know','knows','knowing','knew','known');
	InsertFormsToDatabase(742,'bring','brings','bringing','brought','brought');
	InsertFormsToDatabase(743,'take','takes','taking','took','taken');
	InsertFormsToDatabase(744,'visit','visits','visiting','visited','visited');
	InsertFormsToDatabase(745,'stand up','stands up','standing up','stood up','stood up');
	InsertFormsToDatabase(746,'sit','sits','sitting','sat','sat');
	InsertFormsToDatabase(747,'come','comes','coming','came','come');
	InsertFormsToDatabase(748,'smoke','smokes','smoking','smoked','smoked');
	InsertFormsToDatabase(749,'choose','chooses','choosing','chose','chosen');
	InsertFormsToDatabase(750,'open','opens','opening','opened','opened');
	InsertFormsToDatabase(751,'close','closes','closing','closed','closed');
	InsertFormsToDatabase(752,'climb','climbs','climbing','climbed','climbed');
	InsertFormsToDatabase(753,'fall','falls','falling','fell','fallen');
	InsertFormsToDatabase(754,'want','wants','wanting','wanted','wanted');
	InsertFormsToDatabase(755,'make','makes','making','made','made');
	InsertFormsToDatabase(756,'go','goes','going','went','gone');
	InsertFormsToDatabase(757,'get','gets','getting','got','gotten');
	InsertFormsToDatabase(758,'use','uses','using','used','used');
	InsertFormsToDatabase(759,'find','finds','finding','found','found');
	InsertFormsToDatabase(760,'work','works','working','worked','worked');
	InsertFormsToDatabase(761,'call','calls','calling','called','called');
	InsertFormsToDatabase(762,'try','tries','trying','tried','tried');
	InsertFormsToDatabase(763,'ask','asks','asking','asked','asked');
	InsertFormsToDatabase(764,'become','becomes','becoming','became','become');
	InsertFormsToDatabase(765,'put','puts','putting','put','put');
	InsertFormsToDatabase(766,'mean','means','meaning','meant','meant');
	InsertFormsToDatabase(767,'keep','keeps','keeping','kept','kept');
	InsertFormsToDatabase(768,'begin','begins','beginning','began','begun');
	InsertFormsToDatabase(769,'start','starts','starting','started','started');
	InsertFormsToDatabase(770,'seem','seems','seeming','seemed','seemed');
	InsertFormsToDatabase(771,'appear','appears','appearing','appeared','appeared');
	InsertFormsToDatabase(772,'help','helps','helping','helped','helped');
	InsertFormsToDatabase(773,'show','shows','showing','showed','shown');
	InsertFormsToDatabase(774,'talk','talks','talking','talked','talked');
	InsertFormsToDatabase(775,'speak','speaks','speaking','spoke','spoken');
	InsertFormsToDatabase(776,'turn','turns','turning','turned','turned');
	InsertFormsToDatabase(777,'move','moves','moving','moved','moved');
	InsertFormsToDatabase(778,'believe','believes','believing','believed','believed');
	InsertFormsToDatabase(779,'live','lives','living','lived','lived');
	InsertFormsToDatabase(780,'hold','holds','holding','held','held');
	InsertFormsToDatabase(784,'happen','happens','happening','happened','happened');
	InsertFormsToDatabase(785,'deliver','delivers','delivering','delivered','delivered');
	InsertFormsToDatabase(786,'provide','provides','providing','provided','provided');
	InsertFormsToDatabase(787,'acquire','acquires','acquiring','acquired','acquired');
	InsertFormsToDatabase(788,'stand','stands','standing','stood','stood');
	InsertFormsToDatabase(789,'win','wins','winning','won','won');
	InsertFormsToDatabase(790,'lose','loses','losing','lost','lost');
	InsertFormsToDatabase(791,'pay','pays','paying','paid','paid');
	InsertFormsToDatabase(792,'meet','meets','meeting','met','met');
	InsertFormsToDatabase(793,'include','includes','including','included','included');
	InsertFormsToDatabase(794,'continue','continues','continuing','continued','continued');
	InsertFormsToDatabase(795,'change','changes','changing','changed','changed');
	InsertFormsToDatabase(796,'lead','leads','leading','leaded','leaded');
	InsertFormsToDatabase(797,'set','sets','setting','set','set');
	InsertFormsToDatabase(798,'learn','learns','learning','learned','learned');
	InsertFormsToDatabase(799,'understand','understands','understanding','understood','understood');
	InsertFormsToDatabase(800,'follow','follows','following','followed','followed');
	InsertFormsToDatabase(801,'stop','stops','stopping','stopped','stopped');
	InsertFormsToDatabase(802,'create','creates','creating','created','created');
	InsertFormsToDatabase(803,'add','adds','adding','added','added');
	InsertFormsToDatabase(804,'increase','increases','increasing','increased','increased');
	InsertFormsToDatabase(805,'spend','spends','spending','spent','spent');
	InsertFormsToDatabase(806,'grow','grows','growing','grew','grown');
	InsertFormsToDatabase(807,'offer','offers','offering','offered','offered');
	InsertFormsToDatabase(808,'suggest','suggests','suggesting','suggested','suggested');
	InsertFormsToDatabase(809,'consider','considers','considering','considered','considered');
	InsertFormsToDatabase(810,'wait','waits','waiting','waited','waited');
	InsertFormsToDatabase(811,'serve','serves','serving','served','served');
	InsertFormsToDatabase(812,'die','dies','dying','died','died');
	InsertFormsToDatabase(813,'expect','expects','expecting','expected','expected');
	InsertFormsToDatabase(814,'stay','stays','staying','stayed','stayed');
	InsertFormsToDatabase(815,'drop','drops','dropping','dropped','dropped');
	InsertFormsToDatabase(816,'remain','remains','remaining','remained','remained');
	InsertFormsToDatabase(817,'cut','cuts','cutting','cut','cut');
	InsertFormsToDatabase(818,'reach','reaches','reaching','reached','reached');
	InsertFormsToDatabase(819,'raise','raises','raising','raised','raised');
	InsertFormsToDatabase(820,'pass','passes','passing','passed','passed');
	InsertFormsToDatabase(821,'decide','decides','deciding','decided','decided');
	InsertFormsToDatabase(822,'finish','finishes','finishing','finished','finished');
	InsertFormsToDatabase(823,'end','ends','ending','ended','ended');
	InsertFormsToDatabase(824,'require','requires','requiring','required','required');
	InsertFormsToDatabase(825,'pull','pulls','pulling','pulled','pulled');
	InsertFormsToDatabase(826,'breathe','breathes','breathing','breathed','breathed');
	InsertFormsToDatabase(827,'cry','cries','crying','cried','cried');
	InsertFormsToDatabase(828,'smile','smiles','smiling','smiled','smiled');
	InsertFormsToDatabase(829,'arrive','arrives','arriving','arrived','arrived');
	InsertFormsToDatabase(892,'sleep','sleeps','sleeping','slept','slept');
	InsertFormsToDatabase(931,'swim','swims','swimming','swam','swum');
	InsertFormsToDatabase(945,'fly','flies','flying','flew','flown');
	InsertFormsToDatabase(957,'hunt','hunts','hunting','hunted','hunted');
	InsertFormsToDatabase(1053,'wash','washes','washing','washed','washed');
	InsertFormsToDatabase(2259,'sum','sums','summing','summed','summed');
	InsertFormsToDatabase(2906,'laugh','laughs','laughing','laughed','laughed');
	InsertFormsToDatabase(2909,'catch','catches','catching','caught','caught');
	InsertFormsToDatabase(2911,'break','breaks','breaking','broke','broken');
	InsertFormsToDatabase(2913,'paint','paints','painting','painted','painted');
	InsertFormsToDatabase(2916,'chop','chops','chopping','chopped','chopped');
	InsertFormsToDatabase(2918,'carry','carries','carrying','carried','carried');
	InsertFormsToDatabase(2920,'jump','jumps','jumping','jumped','jumped');
	InsertFormsToDatabase(2922,'crawl','crawls','crawling','crawled','crawled');
	InsertFormsToDatabase(2925,'knit','knits','knitting','knitted','knitted');
	InsertFormsToDatabase(2928,'fight','fights','fighting','fought','fought');
	InsertFormsToDatabase(2930,'sew','sews','sewing','sewed','sewed');
	InsertFormsToDatabase(2933,'skip','skips','skipping','skipped','skipped');
	InsertFormsToDatabase(2936,'cook','cooks','cooking','cooked','cooked');
	InsertFormsToDatabase(2938,'hide','hides','hiding','hid','hidden');
	InsertFormsToDatabase(2943,'push','pushes','pushing','pushed','pushed');
	InsertFormsToDatabase(2945,'blow','blows','blowing','blew','blown');
	InsertFormsToDatabase(2947,'blow up','blows up','blowing up','blew up','blown up');
	InsertFormsToDatabase(2949,'explode','explodes','exploding','exploded','exploded');
	InsertFormsToDatabase(2952,'pick','picks','picking','picked','picked');
	InsertFormsToDatabase(3511,'study','studies','studying','studied','studied');
	InsertFormsToDatabase(3545,'fail','fails','failing','failed','failed');
	InsertFormsToDatabase(3651,'plant','plants','planting','planted','planted');
	InsertFormsToDatabase(3731,'drive','drives','driving','drove','driven');
	InsertFormsToDatabase(3733,'repair','repairs','repairing','repaired','repaired');
	InsertFormsToDatabase(3743,'take care','takes care','taking care','took care','taken care');
	InsertFormsToDatabase(3750,'exercise','exercises','exercising','exercised','exercised');
	InsertFormsToDatabase(3755,'lose weight','loses weight','losing weight','lost weight','lost weight');
	InsertFormsToDatabase(3863,'lie','lies','lying','lay','lain');
	InsertFormsToDatabase(3868,'order','orders','ordering','ordered','ordered');
	InsertFormsToDatabase(3886,'is located','is locateds','is locateding','is locateded','is locateded');
	InsertFormsToDatabase(3895,'shoot','shoots','shooting','shot','shot');
	InsertFormsToDatabase(3905,'feed','feeds','feeding','fed','fed');
	InsertFormsToDatabase(3907,'taste','tastes','tasting','tasted','tasted');
	InsertFormsToDatabase(3934,'recommend','recommends','recommending','recommended','recommended');
	InsertFormsToDatabase(3953,'sunbathe','sunbathes','sunbathing','sunbathed','sunbathed');
	InsertFormsToDatabase(3969,'cure','cures','curing','cured','cured');
	InsertFormsToDatabase(3978,'rain','rains','raining','rained','rained');
	InsertFormsToDatabase(3984,'research','researches','researching','researched','researched');
	InsertFormsToDatabase(4021,'water','waters','watering','watered','watered');
	InsertFormsToDatabase(4030,'hike','hikes','hiking','hiked','hiked');
	InsertFormsToDatabase(4032,'paddle','paddles','paddling','paddled','paddled');
	InsertFormsToDatabase(4034,'hit','hits','hitting','hit','hit');
	InsertFormsToDatabase(4036,'saw','saws','sawing','sawed','sawed');
	InsertFormsToDatabase(4051,'brake','brakes','braking','braked','braked');
	InsertFormsToDatabase(4053,'accelerate','accelerates','accelerating','accelerated','accelerated');
	InsertFormsToDatabase(4059,'defend','defends','defending','defended','defended');
	InsertFormsToDatabase(4069,'command','commands','commanding','commanded','commanded');
	InsertFormsToDatabase(4234,'It is','It iss','It ising','It ised','It ised');
	InsertFormsToDatabase(4241,'would like','would likes','would likeing','would likeed','would likeed');
	InsertFormsToDatabase(4348,'book','books','booking','booked','booked');
	*/
	
	
?>