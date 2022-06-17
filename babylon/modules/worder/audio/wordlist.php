<?php

	
$languageselector = new UIFilterBox();
$languageselector->addSelectFilter($registry->languageID, $registry->languages, "worder/audio/showwordcapture", "Language", "languageID", "name");
$languageselector->addSelectFilter($registry->wordclassID, $registry->wordclasses, "worder/audio/showwordcapture", "Parts of speech", "wordclassID", "name");
$languageselector->setEmptySelect(false);

//$wordclassselector = new UIFilterBox();
//$wordclassselector->addSelectFilter($registry->wordclassID, $registry->wordclasses, "worder/words/showwords", "Parts of speech", "wordclassID", "name");
//$wordclassselector->addTextFilter("worder/words/showwords", $registry->search, "Search", "search");


echo "<table style='width:600px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$languageselector->show();
echo "		</td>";
echo "	</tr>";
/*
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$wordclassselector->show();
echo "		</td>";
echo "	</tr>";
*/
// TODO: ehkä form selector vielä, oletuksena on perusmuoto +SG +NOM, riippuu sanaluokasta
echo "</table>";
	



echo "<div style='background-color:lightgreen;width:1000px;height:320px;'>";
echo "			<audio id='audio' controls></audio>";
echo "<button onclick=\"startmicrophone(0,0,0)\">microphone start</button>";
echo "<button onclick=\"stopstream()\">stop</button>";
echo "<button onclick=\"playclip()\">play</button>";
echo "<br>";
echo "<canvas id=canvas01 width='300' height='100' style='background-color:pink'></canvas>";
echo "<br>";
echo "<canvas id=canvas02 width='1000' height='100' style='background-color:lightblue'></canvas>";
echo "</div>";


echo "	<script>";
echo "		var context = null;";
echo "		var recorder = null;";
echo "		var microphoneStream = null;";

echo "		var analyser = null;";
echo "		var currentstream = null;";
echo "		var waveGraphAnimationWorkerID = 0;";
echo "		var chunks = [];";

echo "		var currentwordID = 0;";
echo "		var currentformID = 0;";
echo "		var currentrowID = 0;";
echo "		var mutex = 0;";

echo "	</script>";


echo "	<script>";
echo "		function processMicrophoneData(event) {";
echo "			console.log(' - handledata');";
echo "			chunks.push(event.data);";
echo "			playclip();";
echo "		}";
echo "	</script>";


// Lopetetaan wavecanvasin päivittäminen..
echo "<script>";
echo "		function stopstream() {";
echo "			analyser.disconnect();";
echo "			if (waveGraphAnimationWorkerID > 0) {";
echo "				window.cancelAnimationFrame(waveGraphAnimationWorkerID);";
echo "			}";
echo "			recorder.stop();";

echo "			$('#recordaudiodiv-'+currentrowID).hide();";
echo "			$('#playaudiodiv-'+currentrowID).show();";
echo "			$('#removeaudiodiv-'+currentrowID).show();";

echo "		}";
echo "</script>";


echo "<script>";
echo "		function playclip() {";
echo "			console.log('chunks length - '+chunks.length);";
echo "			var audio = document.getElementById('audio');";
echo "			audio.src = URL.createObjectURL(chunks[chunks.length-1]);";
echo "			audio.play();";
echo "			console.log('audioplay');";

echo "			if ((currentwordID > 0) && (currentformID > 0)) {";
echo "				console.log('savedata');";
echo "				sendAudioData(currentwordID, currentformID, chunks[chunks.length-1]);";
echo "			}";


echo "		}";
echo "</script>";


echo "<script>";
echo "		function startmicrophone(wordID, formID, rowID) {";

echo "			if (mutex == 1) {";
echo "				console.log('reserved - '+rowID);";
echo "				return;";
echo "			}";
echo "			mutex = 1;";

echo "			currentwordID = wordID;";
echo "			currentformID = formID;";
echo "			currentrowID = rowID;";
echo "			console.log('currentrowID - '+rowID);";
echo "			if (context == null) {";
echo "				context = new AudioContext();";
echo "				navigator.mediaDevices.getUserMedia({ audio: true, video: false }).then(microphoneSuccess);";
echo "				console.log('context created');";
echo "			} else {";
echo "				microphoneSuccess(null);";
echo "			}";
echo "		}";
echo "</script>";


echo "	<script>";
echo "		function microphoneSuccess(stream) {";

echo "			if (stream != null) {";
echo "				console.log('Microphone access successfull');";
echo "				recorder = new MediaRecorder(stream);";
echo "				recorder.ondataavailable = processMicrophoneData;";
echo "				recorder.start();";

echo "				currentstream = stream;";
echo "				microphoneStream = context.createMediaStreamSource(stream);";
echo "				canvas = document.getElementById('canvas01');";
echo "				createWaveGraph(microphoneStream, canvas, 0);";

echo "			} else {";
echo "				console.log('restart graph');";
echo "				recorder.start();";
echo "				canvas = document.getElementById('canvas01');";
echo "				createWaveGraph(microphoneStream,canvas, 1);";
echo "			}";

echo "		}";
echo "	</script>";



echo "	<script>";
echo "		function sendAudioData(wordID, formID, data) {";
echo "			console.log('sendaudio - '+wordID+' - '+formID);";
echo "			console.log('sendaudio rowID - '+currentrowID);";
echo "			var xhr=new XMLHttpRequest();";
echo "			xhr.onload=function(e) {";
echo "				if(this.readyState === 4) {";
echo "					console.log('Server returned: ',e.target.responseText);";
echo "				}";
echo "			};";
echo "			var fd=new FormData();";
echo "			fd.append('audio_data',data, 'jee.php');";
echo "			fd.append('wordID',wordID);";
echo "			fd.append('formID',formID);";
echo "			xhr.open('POST','https://www.babelsoft.fi/demo/index.php?rt=worder/audio/uploadwordaudio',true);";
echo "			xhr.send(fd);";

echo "			xhr.onload = function() {";
//echo "				console.log('Loaded: '+xhr.status+','+xhr.response);";
//echo "				console.log('Loaded2: '+wordID);";
echo "				var respo = xhr.response;";
echo "				console.log('senddata new id - '+respo);";
echo "				console.log('senddata rowID - '+currentrowID);";
//					xhr.response sisältää uuden audiofilen ID:n
//echo "				$('#playaudiodiv-'+currentrowID).unbind('click');";
echo "				$('#playaudiobutton-'+currentrowID).prop('onclick', null);";
echo "				$('#playaudiobutton-'+currentrowID).click(function() { playaudiofile(respo); });";
echo "				$('#playaudiobutton-'+currentrowID).html('play-'+currentrowID+'-'+respo);";
echo "				console.log('delupdate 0');";
echo "				$('#deleteaudiobutton-'+currentrowID).html('delete-'+currentrowID+'-'+respo);";

echo "				console.log('delupdate 1');";
echo "				$('#deleteaudiobutton-'+currentrowID).prop('onclick', null);";
echo "				console.log('delupdate 2');";
echo "				$('#deleteaudiobutton-'+currentrowID).unbind('click');";
echo "				console.log('delupdate 3');";
echo "				var temprow = currentrowID;";
echo "				$('#deleteaudiobutton-'+currentrowID).click(function() { deleteaudiofile(respo, temprow); });";
echo "				console.log('delupdate 4');";
echo "				$('#deleteaudiobutton-'+currentrowID).html('delete-'+currentrowID+'-'+respo);";
echo "				console.log('delupdate 5');";
echo "				mutex = 0;";
echo "			};";
echo "		}";
echo "	</script>";




echo "<script>";
echo "		function playaudiofile(linkID) {";
echo "			console.log('play - '+linkID);";

echo "			var url = '" . getUrl("worder/audio/downloadaudiofile") . "&linkID='+linkID;";
echo "			console.log(url);";

echo "			var audio = document.getElementById('audio');";
echo "			audio.src = url;";
echo "			audio.load();";
echo "			audio.play();";

/*
echo "			wavesurfer2.load(url);";
echo "			wavesurfer2.on('ready', function () {";
echo "				wavesurfer2.play();";
echo "			});";
*/

echo "		}";
echo "</script>";



echo "<script>";
echo "		function dynaclick(linkID,rowID) {";
echo "			console.log('dynamic click - '+linkID);";
echo "			console.log('dynamic rowID  - '+rowID);";
echo "		}";
echo "</script>";





echo "<script>";
echo "		function deleteaudiofile(audiofileID,rowID) {";
//echo "			currentrowID = rowID;";
echo "			console.log('deleteaudiofile - '+audiofileID);";
echo "			console.log('deleteaudiofile - rowID - '+rowID);";
echo "			$.getJSON('" . getUrl('worder/audio/removewordaudiofile') . "&audiofileID='+audiofileID,'',function(data) {";
echo "				console.log('removedata - '+data.success);";
echo "				if (data.success == 0) {";
echo "					console.log('remove failed');";
echo "					console.log(' - '+data.message);";
echo "				} else {";
echo "					console.log('remove success');";

echo "					$('#recordaudiodiv-'+rowID).show();";
echo "					$('#recordbutton-'+rowID).html('record');";

echo "					$('#playaudiodiv-'+rowID).hide();";
echo "					$('#deleteaudiobutton-'+rowID).hide();";

echo "				}";
echo "			}); ";


/*
echo "			console.log('play - '+linkID);";

echo "			var url = '" . getUrl("worder/audio/downloadaudiofile") . "&linkID='+linkID;";
echo "			console.log(url);";

echo "			var audio = document.getElementById('audio');";
echo "			audio.src = url;";
echo "			audio.load();";
echo "			audio.play();";

/*
 echo "			wavesurfer2.load(url);";
 echo "			wavesurfer2.on('ready', function () {";
 echo "				wavesurfer2.play();";
 echo "			});";
 */

echo "		}";
echo "</script>";




echo "	<script>";
echo "		function createWaveGraph(audioSource, targetCanvas, restart) {";

echo "			analyser = context.createAnalyser();";
echo "			audioSource.connect(analyser);";

echo "			analyser.fftSize = 2048;";
echo "			analyser.smoothingTimeConstant = 0.8;";

echo "			var bufferLength = analyser.frequencyBinCount;";
echo "			var dataArray = new Uint8Array(bufferLength);";

echo "			var canvas = targetCanvas.getContext('2d');";
echo "			canvas.clearRect(0, 0, 290, 80);";			// hardkoodattuja

echo "			var idletime = 0;";
echo "			var started = 0;";							// Tämä on yksi, jos ääntä on alkanut tulla
echo "			var lastupdate = performance.now();";
echo "			var recmute = 1;";
echo "			var curretID = currentrowID;";

echo "			var waveGraphCanvasUpdate = function() {";
echo "				waveGraphAnimationWorkerID = window.requestAnimationFrame(waveGraphCanvasUpdate);";
echo "				analyser.getByteTimeDomainData(dataArray);";

echo "				canvas.fillStyle = '#FFFF33';";
echo "				canvas.fillRect(0, 0, 290, 80);";		// hardkoodattuja

echo "				canvas.lineWidth = 1;";
echo "				canvas.strokeStyle = '#000000';";
echo "				canvas.beginPath();";

echo "				var sliceWidth = 200 / bufferLength;";		// hardkoodattuja
echo "				var x = 0;";
echo "				var zerocounter = 0;";
echo "				for(var i = 0; i < bufferLength; i++) {";
echo "					var v = dataArray[i] / 128.0;";			// hardkoodattua arvo...
echo "					var y = v * 70/2;";
echo "					if (i === 0) {";
echo "						canvas.moveTo(x, y);";
echo "					} else {";
echo "						canvas.lineTo(x, y);";
echo "					}";
echo "					x += sliceWidth;";

echo "					if ((y < 33) || (y > 37)) {";			// Hardkoodattuja arvoja
echo "						zerocounter++;";
echo "					}";
echo "  			}";

echo "				if (recmute == 1) {";
echo "					$('#recordbutton-'+curretID).html('...');";
echo "				}"; 
echo "				if (recmute == 20) {";
echo "					$('#recordbutton-'+curretID).html('.....');";
echo "				}";
echo "				if (recmute == 40) {";
echo "					recmute = 0;";
echo "				}";
echo "				recmute++;";

echo "				var currentTime = performance.now();";
echo "				var difference = currentTime - lastupdate;";
echo "				console.log(difference.toFixed(2)+' activity - '+zerocounter);";
echo "				lastupdate = currentTime;";

echo "				console.log(difference.toFixed(2)+' activity - '+zerocounter);";
echo "				lastupdate = currentTime;";

echo "				if (zerocounter > 5) {";
echo "					console.log('idletime - '+idletime);";

echo "					if (started == 0) {";
echo "						started = 1;";
//echo "						recorder.start();";
echo "					} else {";
echo "					}";
echo "					idletime = 0;";
echo "				} else {";
echo "					idletime = idletime + difference;";
echo "					if (started == 1) {";
echo "						if (idletime > 1000) {";
echo "							stopstream();";
echo "						}";
echo "					}";
//echo "					console.log('activity - 0');";
echo "					";
echo "				}";

echo "  			canvas.stroke();";
echo "			};";
echo "			waveGraphCanvasUpdate();";
echo "		}";
echo "	</script>";






echo "<table class='listtable' id='sectiontable43' style='width:900px;padding:0px 0px 0px 0px;margin:0px;'>";
echo " 	<tr class='listtable-row'>";
echo "		<td class='listtable-header' style=''>WordID</td>";
echo "		<td class='listtable-header' style=''>Word</td>";
echo "		<td class='listtable-header' style=''>Form</td>";
echo "		<td class='listtable-header' style=''>Audiofile</td>";
echo "		<td class='listtable-header' style=''>Del</td>";
echo "	</tr>";
$rownumber = 0;

foreach($registry->words as $index => $word) {
	
	$trclass = "listtable-evenrow";
	$trbackgroundcolor = "white";
	if ($rownumber % 2 == 0) {
		$trclass = "listtable-oddrow";
		$trbackgroundcolor = "#e2eff8";
	}
	
	echo " 	<tr  class='" . $trclass . "'>";
	//echo "<td style='padding-left:10px;" . $align . ";padding-bottom:0px;padding-top:3px;padding-bottom:0px;max-width:" . $width . ";width:" . $width . ";overflow-x:hidden;'>";
	echo "		<td style='padding-left:10px;'>";
	echo "			<span style='font-size:17px;'>";
	echo "				" . $word->wordID;
	echo "			</span>";
	echo "		</td>";
	
	echo "		<td style='padding-left:10px;'>";
	echo "			<span style='font-size:17px;'>";
	echo "				" . $word->lemma;
	echo "			</span>";
	echo "		</td>";
	
	echo "		<td style='padding-left:10px;'>";
	echo "			<span style='font-size:17px;'>";
	echo "" . $word->formID . "-" . $word->form;
	echo "			</span>";
	echo "		</td>";
	
	echo "		<td style='padding-left:10px;'>";
	echo "(" . $rownumber . ")";
	echo "		</td>";
	
	
	if ($word->audiofileID != "") {
		echo "		<td style='padding-left:10px;width:150px;'>";
		echo "<div id=recordaudiodiv-" . $rownumber . " style='display:none'>";
		echo "<button id=recordbutton-" . $rownumber . " onclick=\"startmicrophone(" . $word->wordID . "," . $word->formID . "," . $rownumber . ")\">record</button>";
		echo "</div>";
			
		echo "<div id=playaudiodiv-" . $rownumber . ">";
		echo "<button id=playaudiobutton-" . $rownumber . " onclick=\"playaudiofile(" . $word->audiofileID . ")\">play-" . $rownumber . "-" . $word->audiofileID . "</button>";
		echo "</div>";
		echo "		</td>";
		
		echo "		<td style='padding-left:10px;width:150px;'>";
		echo "<div id=removeaudiodiv-" . $rownumber . ">";
		echo "<button id=deleteaudiobutton-" . $rownumber . "   onclick=\"deleteaudiofile(" . $word->audiofileID . "," . $rownumber. ")\">del-" . $rownumber . "-" . $word->audiofileID . "</button>";
		echo "</div>";
		echo "		</td>";
		
	} else {
		if ($word->form != "") {
			echo "		<td style='padding-left:10px;width:150px;'>";
			echo "<div id=recordaudiodiv-" . $rownumber . ">";
			echo "<button id=recordbutton-" . $rownumber . "  onclick=\"startmicrophone(" . $word->wordID . "," . $word->formID . "," . $rownumber . ")\">record</button>";
			echo "</div>";
			//echo "create";
			echo "<div id=playaudiodiv-" . $rownumber . " style='display:none'>";
			echo "<button id=playaudiobutton-" . $rownumber . " onclick=\"playaudiofile(" . $word->audiofileID . ")\">play</button>";
			echo "</div>";
			
			echo "		</td>";
			

			echo "		<td style='padding-left:10px;width:150px;'>";
			echo "<div id=removeaudiodiv-" . $rownumber . " style='display:none'>";
			echo "<button id=deleteaudiobutton-" . $rownumber . "  onclick=\"deleteaudiofile(" . $word->audiofileID . "," . $rownumber. ")\">del</button>";
			echo "</div>";
			echo "		</td>";
			
		} else {
			echo "		<td style='padding-left:10px;width:150px;'>";
			echo "-";
			echo "		</td>";
			
			echo "		<td style='padding-left:10px;width:150px;'>";
			echo "-";
			echo "		</td>";
				
			
		}
	}
	
	//echo "			<td style='padding-left:10px;'>" . $word->lemma . "</td>";
	echo "	</tr>";
	
	$rownumber++;
}
echo "</table>";

echo "<br>Totalcount: " . count($registry->words);






/*
 echo "			console.log('chunk size - '+chunks[chunks.length-1].size);";
 echo "			var bufferPromise = chunks[chunks.length-1].arrayBuffer().then(arrayBuffer => {";
 echo "				console.log('bufferlength - '+arrayBuffer.byteLength);";
 echo "				console.log('buffername - '+typeof arrayBuffer);";

 echo "				context.decodeAudioData(arrayBuffer, (audioBuffer) => {";
 echo "					console.log(audioBuffer);";
 echo "					var data = audioBuffer.getChannelData(0);";
 echo "					console.log('data - '+data.length);";
 echo "					console.log(data);";
 echo "					var min = 0;";
 echo "					var max = 0;";
 echo "					var startpos = -1;";
 echo "					var notemptycounter = 0;";
 echo "					for(i=0;i<data.length;i++) {";
 echo "						if (data[i] < min) min = data[i];";
 echo "						if (data[i] > max) max = data[i];";
 echo "						if (startpos == -1) {";
 echo "							if ((data[i] > -0.10) && (data[i] < 0.10)) {";
 echo "								notemptycounter = 0;";
 echo "							} else {";
 echo "								notemptycounter++;";
 echo "							}";
 echo "							if (notemptycounter > 10) {";
 echo "								startpos = i;";
 echo "							}";
 echo "						}";
 echo "					}";
 echo "					console.log('min - '+min);";
 echo "					console.log('max - '+max);";
 echo "					console.log('startpos - '+startpos);";
 echo "				});";
 echo "			});";
 */


?>