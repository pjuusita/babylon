<?php


echo "<h1>" . $this->registry->sentence->sentence . "</h1>";



$correctselection = array();
$row = new Row();
$row->correctness = 0;
$row->name = "malformed";
$correctselection[0] = $row;
$row = new Row();
$row->correctness = 1;
$row->name = "well-formed";
$correctselection[1] = $row;


$sentencesection = new UISection("Sentence","800px");
$sentencesection->setOpen(true);
$sentencesection->editable(true);
$sentencesection->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/sentences/updatesentence', 'sentenceID');

$field = new UIFixedTextField("SentenceID", $registry->sentence->sentenceID);
$sentencesection->addField($field);

$field = new UITextField("Sentence","sentence","sentence");
$sentencesection->addField($field);

$correctselection = array();
$row = new Row();
$row->correctness = 0;
$row->name = "malformed";
$correctselection[0] = $row;
$row = new Row();
$row->correctness = 1;
$row->name = "well-formed";
$correctselection[1] = $row;

$field = new UISelectField("Correctness","correctness","correctness",$correctselection, "name");
$sentencesection->addField($field);

$field = new UITextAreaField("Kommentti", "comment", 'comment');
$sentencesection->addField($field);


$field = new UISelectField("Language","languageID","languageID",$registry->languages, "name");
$sentencesection->addField($field);

$sentencesection->setData($registry->sentence);
$sentencesection->show();




$addaudiodialog = new UISection('Add word','740px');
$addaudiodialog->setDialog(true);
$addaudiodialog->setMode(UIComponent::MODE_INSERT);

$addaudiodialog->setCustomContent('insertAudioDiv');
$addaudiodialog->setOnOpenFunction('onopenfuction');
$addaudiodialog->show();


echo "	<script>";
echo "		function onopenfuction() {";
echo "			console.log('aaa onopenfuction');";
echo "			console.log('Trying microhone access');";
echo "			context = new AudioContext();";
echo "			navigator.mediaDevices.getUserMedia({ audio: true, video: false }).then(microphoneSuccess);";
echo "			console.log('Ready end.');";
echo "		}";
echo "	</script>";


echo "	<script>";
echo "		function closeAudioDialog() {";
echo "			console.log('closebutton');";
echo "			console.log('TODO: close microphone');";
echo "  		$('#sectiondialog-" . $addaudiodialog->getID() . "').dialog('close');";
echo "		};";
echo "	</script>";


echo "	<script>";
echo "		function microphoneSuccess(stream) {";
echo "			console.log('Microphone access successfull');";
echo "			recorder = new MediaRecorder(stream);";
echo "			source = context.createMediaStreamSource(stream);";
echo "			can1 = document.getElementById('canvas01');";
echo "			createWaveGraph(source,can1);";

echo "			source = context.createMediaStreamSource(stream);";
echo "			can2 = document.getElementById('canvas02');";
echo "			createFrequencyGraph(source,can2);";

echo "			console.log('State = ' + recorder.state);";
echo "			recorder.ondataavailable = handledata;";
echo "			recorder.onstop = function (e) {";
echo "				console.log('recording ended - '+recorder.state);";
echo "				console.log('chunk count - '+chunks.length);";
echo "				var audio = document.getElementById('audio');";
echo "				console.log('chunks count - '+chunks.length);";
echo "				audio.src = URL.createObjectURL(chunks[chunks.length-1]);";
echo "				audio.play();";
echo "			}";
echo "		}";
echo "	</script>";


echo "	<script>";
echo "		function createFrequencyGraph(audioSource, targetCanvas) {";

echo "			var analyser = context.createAnalyser();";
echo "			audioSource.connect(analyser);";
echo "			analyser.fftSize = 128;";
echo "			console.log('fft width - '+analyser.smoothingTimeConstant);";

echo "			var bufferLength = analyser.frequencyBinCount;";
echo "			analyser.smoothingTimeConstant = 0.8;";
echo "			console.log('fft timeslot - '+analyser.smoothingTimeConstant);";
echo "			console.log('bufferlength - '+bufferLength);";
echo "			var dataArray = new Uint8Array(bufferLength);";

echo "			var canvas = targetCanvas.getContext('2d');";
echo "			var barWidth = (700 / bufferLength) * 2.5;";
echo "			console.log('barWidth = '+barWidth);";

echo "			var drawCanvas1 = function() {";
echo "				drawVisual = requestAnimationFrame(drawCanvas1);";
echo "				analyser.getByteFrequencyData(dataArray);";
echo "				canvas.fillStyle = '#FFFFFF';";
echo "				canvas.fillRect(0, 0, 700, 70);";
echo "				var barHeight;";
echo "				var x = 0;";
echo "				for(var i = 0; i < bufferLength; i++) {";
echo "					barHeight = dataArray[i]/2;";
echo "					canvas.fillStyle = '#000000';";
echo "					canvas.fillRect(x,70-barHeight/2,barWidth,barHeight);";
echo "					x += barWidth + 1;";
echo "				}";
echo "			};";
echo "			drawCanvas1();";
echo "		}";
echo "	</script>";



echo "	<script>";
echo "		var context = null;";
echo "		var recorder = null;";
echo "		var chunks = [];";
echo "	</script>";

echo "	<script>";
echo "		function handledata(event) {";
echo "			chunks.push(event.data);";
echo "		}";
echo "	</script>";


echo "	<script>";
echo "		function createWaveGraph(audioSource, targetCanvas) {";

echo "			var analyser = context.createAnalyser();";
echo "			audioSource.connect(analyser);";

echo "			analyser.fftSize = 2048;";
echo "			console.log('fft width - '+analyser.smoothingTimeConstant);";

echo "			var bufferLength = analyser.frequencyBinCount;";
echo "			analyser.smoothingTimeConstant = 0.8;";
echo "			console.log('fft timeslot - '+analyser.smoothingTimeConstant);";
echo "			console.log('bufferlength - '+bufferLength);";
echo "			var dataArray = new Uint8Array(bufferLength);";

echo "			var canvas = targetCanvas.getContext('2d');";
echo "			canvas.clearRect(0, 0, 700, 70);";
//echo "			var barWidth = (700 / bufferLength) * 2.5;";
//echo "			console.log('barWidth = '+barWidth);";

echo "			var drawCanvas2 = function() {";
echo "				var drawVisual = requestAnimationFrame(drawCanvas2);";
echo "				analyser.getByteTimeDomainData(dataArray);";
echo "				canvas.fillStyle = '#FFFFFF';";
echo "				canvas.fillRect(0, 0, 700, 70);";

echo "				canvas.lineWidth = 2;";
echo "				canvas.strokeStyle = '#FF0000';";
echo "				canvas.beginPath();";

echo "				var sliceWidth = 700 * 1.0 / bufferLength;";
echo "				var x = 0;";
echo "				for(var i = 0; i < bufferLength; i++) {";
echo "					var v = dataArray[i] / 128.0;";
echo "					var y = v * 70/2;";
echo "					if(i === 0) {";
echo "						canvas.moveTo(x, y);";
echo "					} else {";
echo "						canvas.lineTo(x, y);";
echo "					}";
echo "					x += sliceWidth;";
echo "  			}";
echo "				canvas.lineTo(700, 35);";
echo "  			canvas.stroke();";
echo "			};";
echo "			drawCanvas2();";
echo "		}";
echo "	</script>";





function insertAudioDiv() {

	global $registry;


	echo "	<table style='width:100%'>";
	
	echo "		<tr>";
	echo "			<td colspan=2 style='padding-right:5px;'>";
	echo "<h3>" . $registry->sentence->sentence . "</h3>";
	echo "			</td>";
	echo "		</tr>";
	
	
	echo "		<tr>";
	echo "			<td colspan=2 style='padding-right:5px;'>";
	
	echo "	<div id='viz' style='background-color:white;'>";
	echo "		<canvas id='canvas01' width='700' height='70' style='display: inline-block; background-color:white; border: 2px solid black;border-bottom: 0px;'></canvas>";
	echo "		<canvas id='canvas02' width='700' height='70' style='display: inline-block; background-color:white; border: 2px solid black;border-bottom: 0px;'></canvas>";
	echo "		<canvas id='canvas03' width='700' height='70' style='display: inline-block; background-color:white; border: 2px solid black;'></canvas>";
	echo "	</div>";
	echo "			</td>";
	echo "		</tr>";
	
	echo "		<tr>";
	echo "			<td colspan=2 style='padding-right:5px;text-align:center;'>";
	echo "<audio id='audio' controls></audio>";
	echo "			</td>";
	echo "		</tr>";
	
	
	echo " 	<tr>";
	echo " 		<td class='iu-middle-block field-value' style='padding-top:10px;text-align:right;'>";
	echo "			<button id=startbutton class=section-button>Start</button>";
	echo "			<button id=endbutton class=section-button>End</button>";
	echo "			<button id=playbutton class=section-button>Play</button>";
	echo "			<button id=resetbutton class=section-button>Reset</button>";
	echo "			<button id=savebutton class=section-button>Save</button>";
	echo "		</td>";
	echo " 		<td class='iu-middle-block field-value' style='padding-top:10px;text-align:right;'>";
	echo "			<button class=section-button onclick=\"closeAudioDialog()\">Sulje</button>";
	echo "		</td>";
	echo " </tr>";
	

	
	
	echo "	</table>";

	
	echo "	<script>";
	echo "		$('#startbutton').click(function () {";
	echo "			console.log('startbutton');";
	echo "			recorder.start();";
	echo "		});";
	echo "	</script>";
	
	
	echo "	<script>";
	echo "		$('#endbutton').click(function () {";
	echo "			console.log('endbutton');";
	echo "			recorder.stop();";
	echo "		});";
	echo "	</script>";
	
	echo "	<script>";
	echo "		$('#playbutton').click(function () {";
	echo "			console.log('playbutton');";
	echo "			audio.play();";
	echo "		});";
	echo "	</script>";
	

	echo "	<script>";
	echo "		$('#resetbutton').click(function () {";
	echo "			console.log('resetbutton');";
	echo "		});";
	echo "	</script>";
	

	echo "	<script>";
	echo "		$('#savebutton').click(function () {";
	echo "			console.log('savebutton');";
	echo "			saveData();";
	echo "		});";
	echo "	</script>";
	
	


	echo "	<script>";
	echo "		function saveData() {";
	//echo "			var audio = document.getElementById('audio');";
	//echo "			audio.src = URL.createObjectURL(chunks[chunks.length-1]);";
	//echo "			audio.play();";
	echo "			sendAudioData(chunks[chunks.length-1]);";
	echo "		}";
	echo "	</script>";
	
	
	echo "	<script>";
	echo "		function sendAudioData(data) {";
	
	echo "			console.log('send sentenceID-" . $registry->sentence->sentenceID . "');";
	
	echo "			var xhr=new XMLHttpRequest();";
	echo "			xhr.onload=function(e) {";
	echo "				if(this.readyState === 4) {";
	echo "					console.log('Server returned: ',e.target.responseText);";
	echo "				}";
	echo "			};";
	echo "			var fd=new FormData();";
	echo "			fd.append('audio_data',data, 'jee.php');";
	echo "			fd.append('sentenceID'," . $registry->sentence->sentenceID . ");";
	echo "			xhr.open('POST','https://www.babelsoft.fi/demo/index.php?rt=worder/audio/uploadaudio',true);";
	echo "			xhr.send(fd);";
	
	/*
	 echo "			return fetch('https://www.babelsoft.fi/demo/index.php?rt=worder/audio/uploadaudio', {";
	 echo "				method: 'POST',";
	 echo "				body: formData";
	 echo "			});";
	 */
	echo "		}";
	echo "	</script>";
	
	
	
	/*
	echo "	<table style='width:100%'>";
	echo "					<tr>";
	echo "						<td style='padding-right:5px;'>";
	echo "<input class=uitextfield  id=conceptsearchfield type='text' style='width:100%;' type='text' value=''>";
	echo " 						</td>";

	echo "					<td style='padding-right:5px'>";
	echo "						<div>";
	echo "							<button  class=section-button  onclick='conceptsearchbuttonpressed()'>Etsi</button>";
	echo "						</div>";
	echo "					</td>";

	echo "	<script>";
	echo "		$('#conceptsearchfield').keypress(function (e) {";
	echo "			if (e.which == 13) {";
	echo "				conceptsearchbuttonpressed();";
	echo "			};";
	echo "		})";
	echo "	</script>";

	echo "		</tr>";
	echo "		<tr>";
	echo "			<td colspan=2>";

	echo "				<div id=conceptsearchloadingdiv style='display:none;height:100%;width:100%;'>";
	echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
	echo "				</div>";

	echo "				<div id=conceptsearchloadeddiv style='display:none;height:100%;width:570px;overflow:hidden'>";
	echo "					<div style='overflow-y:scroll;max-height:200px;width:570px;'>";
	//echo "				<div id=resultpanel style='width:100%;height:50px;background-color:pink;'>result</div>";
	echo "					<table id=conceptsearchresulttable style='width:550px;height:50px;table-layout:fixed;'>";
	echo "						<tr><td>Empty</td></tr>";
	echo "					</table>";
	echo "					</div>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	echo "	</table>";

	echo "	<script>";
	echo "		function addItem(conceptID) {";
	echo "			alert('addconcept button pressed');";
	//	echo "			window.location = '" . getUrl("worder/groups/insertconcept") . "&groupid=" . $registry->group->wordgroupID . "&conceptID='+conceptID;";
	echo "		}";
	echo "	</script>";



	echo "	<script>";
	echo "		function conceptsearchbuttonpressed() {";
	//echo "			console.log('search button pressed');";
	echo "			var searh = $('#conceptsearchfield').val();";
	echo "			if (searh == '') {";
	echo "				alert('ei saa olla tyhjä');";
	echo "			}";
	echo "			$('#conceptsearchloadingdiv').show();";
	echo "			$('#conceptsearchloadeddiv').hide();";

	echo "			$.getJSON('" . getUrl('worder/groups/searchwords') . "&search='+searh,'',function(data) {";
	//echo "					console.log('data.length - '+data.length);";
	echo "					$('#conceptsearchloadingdiv').hide();";
	echo "					$('#conceptsearchloadeddiv').show();";
	echo "					$('#conceptsearchresulttable tr').remove();";
	echo "					$.each(data, function(index) {";
	//echo "						console.log('row - '+data[index].conceptID+' - '+data[index].name);";
	echo "						var row = '<tr>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordclassID+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].conceptID+'</td>'";
	echo "							+ '<td style=\"width:300px;overflow:hidden;\" title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/concepts/showconcept') . "&id='+data[index].conceptID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td><button onclick=\"addItem(\''+data[index].conceptID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#conceptsearchresulttable').append(row);";
	echo "					});";
	echo "			}); ";
	//echo " 			console.log('finish');";
	echo "		}";
	echo "	</script>";
	*/
}



$section = new UITableSection("Äänitiedostot", "800px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);

$button1 = new UIButton(UIComponent::ACTION_OPENDIALOG, $addaudiodialog->getID(), 'Lisää ääninäyte');
$section->addButton($button1);

$column = new UISortColumn("File", "fileID", "fileID");
$section->addColumn($column);

// TODO: Lisää play-nappula

// TODO: Lisää remove-nappula

$section->setData($registry->audiofiles);
$section->show();





$addworddialog = new UISection('Add word','500px');
$addworddialog->setDialog(true);
$addworddialog->setMode(UIComponent::MODE_INSERT);

$addworddialog->setInsertAction(UIComponent::ACTION_FORWARD, "worder/sentences/insertwordTODO&lang=" . $this->registry->languageID . "&setID=" . $this->registry->setID);

$field = new UITextField("Sanaluokka", "wordclass", 'wordclass');
$addworddialog->addField($field);

$field = new UITextField("Perusmuoto", "lemma", 'lemma');
$addworddialog->addField($field);

$addworddialog->show();




function conceptSearchDiv() {

	global $registry;

	echo "	<table style='width:100%'>";
	echo "					<tr>";
	echo "						<td style='padding-right:5px;'>";
	echo "<input class=uitextfield  id=conceptsearchfield type='text' style='width:100%;' type='text' value=''>";
	echo " 						</td>";

	echo "					<td style='padding-right:5px'>";
	echo "						<div>";
	echo "							<button  class=section-button  onclick='conceptsearchbuttonpressed()'>Etsi</button>";
	echo "						</div>";
	echo "					</td>";

	echo "	<script>";
	echo "		$('#conceptsearchfield').keypress(function (e) {";
	echo "			if (e.which == 13) {";
	echo "				conceptsearchbuttonpressed();";
	echo "			};";
	echo "		})";
	echo "	</script>";

	echo "		</tr>";
	echo "		<tr>";
	echo "			<td colspan=2>";

	echo "				<div id=conceptsearchloadingdiv style='display:none;height:100%;width:100%;'>";
	echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
	echo "				</div>";

	echo "				<div id=conceptsearchloadeddiv style='display:none;height:100%;width:570px;overflow:hidden'>";
	echo "					<div style='overflow-y:scroll;max-height:200px;width:570px;'>";
	//echo "				<div id=resultpanel style='width:100%;height:50px;background-color:pink;'>result</div>";
	echo "					<table id=conceptsearchresulttable style='width:550px;height:50px;table-layout:fixed;'>";
	echo "						<tr><td>Empty</td></tr>";
	echo "					</table>";
	echo "					</div>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	echo "	</table>";

	echo "	<script>";
	echo "		function addItem(conceptID) {";
	echo "			alert('addconcept button pressed');";
//	echo "			window.location = '" . getUrl("worder/groups/insertconcept") . "&groupid=" . $registry->group->wordgroupID . "&conceptID='+conceptID;";
	echo "		}";
	echo "	</script>";



	echo "	<script>";
	echo "		function conceptsearchbuttonpressed() {";
	//echo "			console.log('search button pressed');";
	echo "			var searh = $('#conceptsearchfield').val();";
	echo "			if (searh == '') {";
	echo "				alert('ei saa olla tyhjä');";
	echo "			}";
	echo "			$('#conceptsearchloadingdiv').show();";
	echo "			$('#conceptsearchloadeddiv').hide();";

	echo "			$.getJSON('" . getUrl('worder/groups/searchwords') . "&search='+searh,'',function(data) {";
	//echo "					console.log('data.length - '+data.length);";
	echo "					$('#conceptsearchloadingdiv').hide();";
	echo "					$('#conceptsearchloadeddiv').show();";
	echo "					$('#conceptsearchresulttable tr').remove();";
	echo "					$.each(data, function(index) {";
	//echo "						console.log('row - '+data[index].conceptID+' - '+data[index].name);";
	echo "						var row = '<tr>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordclassID+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].conceptID+'</td>'";
	echo "							+ '<td style=\"width:300px;overflow:hidden;\" title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/concepts/showconcept') . "&id='+data[index].conceptID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td><button onclick=\"addItem(\''+data[index].conceptID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#conceptsearchresulttable').append(row);";
	echo "					});";
	echo "			}); ";
	//echo " 			console.log('finish');";
	echo "		}";
	echo "	</script>";

}


function wordSearchDiv() {

	global $registry;

	echo "	<table style='width:100%'>";
	echo "					<tr>";
	echo "						<td style='padding-right:5px;'>";
	echo "							<input class=uitextfield  id=wordsearchfield type='text' style='width:100%;' type='text' value=''>";
	echo " 						</td>";

	echo "					<td style='padding-right:5px'>";
	echo "						<div>";
	echo "							<button  class=section-button  onclick='wordsearchbuttonpressed()'>Etsi</button>";
	echo "						</div>";
	echo "					</td>";

	echo "	<script>";
	echo "		$('#wordsearchfield').keypress(function (e) {";
	echo "			if (e.which == 13) {";
	echo "				wordsearchbuttonpressed();";
	echo "			};";
	echo "		})";
	echo "	</script>";

	echo "		</tr>";
	echo "		<tr>";
	echo "			<td colspan=2>";

	echo "				<div id=wordsearchloadingdiv style='display:none;height:100%;width:100%;'>";
	echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
	echo "				</div>";

	echo "				<div id=wordsearchloadeddiv style='display:none;height:100%;width:570px;overflow:hidden'>";
	echo "					<div style='overflow-y:scroll;max-height:200px;width:570px;'>";
	echo "					<table id=wordsearchresulttable style='width:550px;height:50px;table-layout:fixed;'>";
	echo "						<tr><td>Empty</td></tr>";
	echo "					</table>";
	echo "					</div>";
	echo "				</div>";
	echo "			</td>";
	echo "		</tr>";
	echo "	</table>";

	echo "	<script>";
	echo "		function addItem(conceptID) {";
	echo "			alert('addconcept button pressed');";
	echo "		}";
	echo "	</script>";



	echo "	<script>";
	echo "		function wordsearchbuttonpressed() {";
	echo "			var searh = $('#wordsearchfield').val();";
	echo "			if (searh == '') {";
	echo "				alert('ei saa olla tyhjä');";
	echo "			}";
	echo "			$('#wordsearchloadingdiv').show();";
	echo "			$('#wordsearchloadeddiv').hide();";
	echo "			console.log('" . getUrl('worder/words/searchwordsJSON') . "&lang=" . $registry->languageID . "&search='+searh);";
	
	echo "			$.getJSON('" . getUrl('worder/words/searchwordsJSON') . "&lang=" . $registry->languageID . "&search='+searh,'',function(data) {";
	echo "					$('#wordsearchloadingdiv').hide();";
	echo "					$('#wordsearchloadeddiv').show();";
	echo "					$('#wordsearchresulttable tr').remove();";
	echo "					$.each(data, function(index) {";
	echo "						var row = '<tr>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordclassID+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].wordID+'</td>'";
	echo "							+ '<td style=\"width:300px;overflow:hidden;\" title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/concepts/showconcept') . "&id='+data[index].wordID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td><button onclick=\"addItem(\''+data[index].wordID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#wordsearchresulttable').append(row);";
	echo "					});";
	echo "			}); ";
	echo "		}";
	echo "	</script>";

}


$searchconceptdialog = new UISection("Etsi käsite","600px");
$searchconceptdialog->setDialog(true);

$searchconceptdialog->setCustomContent('conceptSearchDiv');
$searchconceptdialog->show();



$searchworddialog = new UISection("Etsi sana","600px");
$searchworddialog->setDialog(true);

$searchworddialog->setCustomContent('wordSearchDiv');
$searchworddialog->show();



$section = new UITableSection("Lauseen sanat", "800px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);

$column = new UISimpleColumn("Form", 0);
$section->addColumn($column);

$column = new UISimpleColumn("Word", 1);
$section->addColumn($column);

$column = new UISimpleColumn("Concept", 2);
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_OPENDIALOG, $searchconceptdialog->getID(), 3);
$column->setTitle("Concept");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_OPENDIALOG, $searchworddialog->getID(), 4);
$column->setTitle("Word");
$section->addColumn($column);

$section->setData($this->registry->parts);
$section->show();



function sentenceSearchDiv() {

	global $registry;

	echo "	<table style='width:100%'>";

	echo "		<tr>";
	echo "			<td class=field-text style='width:150px;'>Kieli</td>";
	echo "			<td style='width:250px;'>";
	echo "				<select id=searchwordlanguage class=field-select style='width:200px;'>";
	echo "					<option value='0' selected></option>";
	foreach ($registry->languages as $index => $language) {
		if ($registry->defaultlanguageID == $language->languageID) {
			echo "				<option selected='selected' value=" . $language->languageID . ">" . $language->name . "</option>";
		} else {
			echo "				<option value=" . $language->languageID . ">" . $language->name . "</option>";
		}
	}
	echo "				</select>";
	echo " 			</td>";
	echo "			<td id='messagefield-zz-1 style='width:100px;'></td>";
	echo "		</tr>";
	
	
	echo "		<tr>";

	echo "			<td class=field-text style='width:150px;'>Lause</td>";
	
	echo "			<td style='padding-right:5px;'>";
	echo "				<input class=uitextfield  id=searchsentencefield type='text' style='width:100%;' type='text' value=''>";
	echo " 			</td>";

	echo "			<td style='padding-right:5px'>";
	echo "				<div>";
	echo "					<button  class=section-button  onclick='searchsentencebuttonpressed()'>Etsi</button>";
	echo "				</div>";
	echo "			</td>";

	echo "	<script>";
	echo "		$('#searchsentencefield').keypress(function (e) {";
	echo "			if (e.which == 13) {";
	echo "				searchsentencebuttonpressed();";
	echo "			};";
	echo "		})";
	echo "	</script>";

	echo "		</tr>";


	echo "		<tr>";
	echo "			<td colspan=2>";


	echo "				<div id=searchsentenceloadingdiv style='display:none;height:100%;width:100%;'>";
	echo "					<img width=50 height=50 src='" .  getImageUrl('loading2.gif')  . "'>";
	echo "				</div>";

	echo "				<div id=searchsentenceloadeddiv style='display:none;height:200px;width:100%;overflow:hidden'>";
	echo "					<div style='overflow-y:scroll;height:200px;width:100%'>";
	//echo "				<div id=resultpanel style='width:100%;height:50px;background-color:pink;'>result</div>";
	echo "					<table id=searchsentenceresulttable style='width:100%;height:50px;'>";
	echo "						<tr><td>Empty</td></tr>";
	echo "					</table>";
	echo "					</div>";
	echo "				</div>";
	
	
	echo "			</td>";
	echo "		</tr>";
	
	echo "		<tr>";
	echo "			<td colspan=2 style='text-align:right;'>";
	echo "					<button onclick=\"insertTranslationItem()\">Lisää lause</button>";
	echo "			</td>";
	echo "		</tr>";
	echo "	</table>";


	echo "	<script>";
	echo "		function addSentence(sentenceID,conceptID) {";
	echo "			console.log('addSentence - '+sentenceID+','+conceptID);";
	//echo "			window.location = '" . getUrl("worder/sentences/addexistingsentence") . "&sentenceID='+sentenceID+'&setID=" . $registry->languageID . "';";
	echo "		}";
	echo "	</script>";


	echo "	<script>";
	echo "		function searchsentencebuttonpressed() {";
	echo "			console.log('search button pressed');";

	echo "			var conceptID = $('#searchsentenceconceptfield').val();";
	echo "			if (conceptID == 0) {";
	echo "				alert('ei saa olla tyhjä 2');";
	echo "				return;";
	echo "			}";

	echo "			var languageID = " . $registry->sentence->languageID . ";";
	echo "			var searh = $('#searchsentencefield').val();";
	echo "			if (searh == '') {";
	echo "				alert('ei saa olla tyhjä 2');";
	echo "				return;";
	echo "			}";
	echo "			$('#searchsentenceloadingdiv').show();";
	echo "			$('#searchsentenceloadeddiv').hide();";
	//echo "			var languageID = $('#languagefield').val();";
	//echo "			var languageID = " . $registry->rule->languageID . ";";
	echo "			console.log('languageid -'+languageID+'-');";
	echo "			console.log('" . getUrl('worder/sentences/searchsentences') . "&search='+searh+'&languageID='+languageID);";

	echo "			$.getJSON('" . getUrl('worder/sentences/searchsentences') . "&search='+searh+'&languageID='+languageID,'',function(data) {";
	echo "					console.log('data.length aa - '+data.length);";
	echo "					$('#searchsentenceloadingdiv').hide();";
	echo "					$('#searchsentenceloadeddiv').show();";
	echo "					$('#searchsentenceresulttable tr').remove();";
	echo "					var conceptID = $('#searchsentenceconceptfield').val();";
	echo "					var counter = 0;";

	echo "					$.each(data, function(index) {";
	echo "						counter++;";
	echo "						console.log('row - '+data[index].sentenceID+' - '+data[index].sentence);";
	echo "						var row = '<tr>'";
	//echo "							+ '<td style=\"padding-right:10px;\">'+data[index].frequency+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].sentenceID+'</td>'";
	echo "							+ '<td style=\"padding-right:10px;\">'+data[index].sentence+'</td>'";
	//echo "							+ '<td title=\''+data[index].gloss+'\'><a href=\"" . getUrl('worder/words/showword') . "&lang='+languageID+'&id='+data[index].wordID+'\">'+data[index].name+'</a></td>'";
	echo "							+ '<td><button onclick=\"addSentence(\''+data[index].sentenceID+'\',\''+conceptID+'\')\">lisää</button></td>'";
	echo "							+ '</tr>';";
	echo "						$('#searchsentenceresulttable').append(row);";
	echo "					});";

	echo "					if (counter == 0) {";
	echo "						var row = '<tr>'";
	echo "							+ '<td style=\"padding-right:10px;\">Ei löytynyt</td>'";
	echo "							+ '</tr>';";
	echo "						$('#searchsentenceresulttable').append(row);";
	echo "					}";

	echo "			}); ";
	echo " 			console.log('finish');";
	echo "		}";
	echo "	</script>";
	
	

	echo "	<script>";
	echo "		function insertTranslationItem() {";
	
	echo "			var sentence = $('#searchsentencefield').val();";
	echo "			if (sentence == '') {";
	echo "				alert('search saa olla tyhjä 2');";
	echo "				return;";
	echo "			}";
	
	echo "			var languageID = $('#searchwordlanguage').val();";
	echo "			if (languageID == 0) {";
	echo "				alert('language pitää olla valittu');";
	echo "				return;";
	echo "			}";
	echo "			console.log('sentence - '+sentence);";	
	echo "			console.log('languageID - '+languageID);";	
	
	echo "			window.location = '" . getUrl("worder/sentences/insertsentence") . "&source=sentences&sentenceID=" . $registry->sentence->sentenceID . "&languageID='+languageID+'&sentence='+sentence;";
	
	echo "		}";
	echo "	</script>";
	

}

$translationsearchsection = new UISection("Etsi käännös","800px");
$translationsearchsection->setDialog(true);
$translationsearchsection->setMode(UIComponent::MODE_INSERT);

$translationsearchsection->setCustomContent('sentenceSearchDiv');
$translationsearchsection->show();


//var_dump($registry->translations);


$section = new UITableSection("Käännökset", "800px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);

$button1 = new UIButton(UIComponent::ACTION_OPENDIALOG, $translationsearchsection->getID(), 'Etsi käännös');
$section->addButton($button1);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/sentences/removetranslation&sentenceID=' . $registry->sentence->sentenceID, 'sentenceID');


$column = new UISortColumn("#", "sentenceID");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UISelectColumn("Language", "name", "languageID", $registry->languages);
$section->addColumn($column);

$column = new UISortColumn("Sentence", "sentence");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$section->addColumn($column);

$column = new UISelectColumn("Correctness", "name", "correctness", $correctselection);
$section->addColumn($column);

$section->setData($registry->translations);
$section->show();




// ---------------------------------------------------------------------------------------------------
// Hallinta
// ---------------------------------------------------------------------------------------------------

$managementSection = new UISection("Hallinta", "800px");
$managementSection->editable(false);
$managementSection->setDebug(true);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/sentences/removesentence&id=".$registry->sentence->getID(), "Poista lause");
$managementSection->addButton($button);

$managementSection->show();




?>