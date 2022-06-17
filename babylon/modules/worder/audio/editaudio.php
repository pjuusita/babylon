<?php




echo "	<script>";
echo "		var context = null;";
echo "		var recorder = null;";
echo "		var specto = null;";
echo "		var buffersource = null;";
echo "		var chunks = [];";
echo "		var sentenceID = 0;";

echo "		var activeClip = null;";
echo "		var activeAudiofileID = 0;";
echo "		var regionstart = 0;";
echo "		var regionend = 0;";
echo "	</script>";

echo "	<script>";
echo "		function handledata(event) {";
echo "			chunks.push(event.data);";
echo "		}";
echo "	</script>";



// Ylävalikko

echo "<table style='width:1100px;'>";
echo "	<tr>";
echo "		<td style='width:250px;text-align:left;'>";
$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->languageID, $this->registry->languages, "worder/audio/showeditaudio", "","languageID", "name");
$filterbox->show();
echo "		</td>";
echo "		<td id='sentencediv' style='vertical-align:bottom;text-align:center;font-weight:bold;font-size:30px;'>";
//echo "this is sentence";
echo "		</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td style='width:250px;text-align:left;vertical-align:top;'>";
$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->setID, $this->registry->sets, "worder/audio/showeditaudio", "","setID", "name");
$filterbox->show();
echo "		</td>";
echo "		<td rowspan=2 style='vertical-align:bottom;background-color:white;text-align:center;'>";
echo "			<audio id='audio' controls></audio>";
echo "		</td>";
echo "	<tr>";
echo "		<td style='width:250px;text-align:left;vertical-align:top;'>";
$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->clipsetID, $this->registry->clipsets, "worder/audio/showeditaudio", "","clipsetID", "name");
$filterbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";



echo "<table style='width:1100px;'>";

echo "	<tr>";
echo "		<td style='width:300px;vertical-align:top;'>";
echo "			<div style='background-color:pink;color:black;width:330px;height:500px;overflow-y:scroll;border:thin solid grey;'>";
echo "				<table class=worderlist style='font-size:14px;' id='analysesentencetable'>";

foreach($this->registry->sentences as $index => $sentence) {
	echo "				<tr>";
	echo "					<td style='width:250px'>";
	echo "<div onclick=\"sentenceclick(" . $sentence->sentenceID . ",'" . $sentence->sentence . "')\">";
	echo "" . $sentence->sentence;
	echo "</div>";
	echo "					</td>";
	echo "					<td style='width:30px;'>";
	if ($sentence->counter > 0) {
		echo "" . $sentence->counter;
	}
	echo "					</td>";
	
	echo "					<td>";
	echo "<button onclick=\"recordbuttonclick(" . $sentence->sentenceID . ",'" . $sentence->sentence . "')\">rec</button>";
	echo "					</td>";
	
	echo "					<td>";
	echo "<button onclick=\"recordstopclick(" . $sentence->sentenceID . ",'" . $sentence->sentence . "')\">stop</button>";
	echo "					</td>";
	
	echo "					<td>";
	echo "<button onclick=\"saveData(" . $sentence->sentenceID . ",'" . $sentence->sentence . "')\">save</button>";
	echo "					</td>";
	
	echo "				</tr>";
}
echo "				</table>";
echo "			</div>";

echo "			<div style='background-color:pink;color:black;width:330px;height:200px;overflow-y:scroll;border:thin solid grey;'>";
echo "				<table  class=worderlist style='font-size:14px;' id='analysesentencetable'>";

foreach($this->registry->clips as $index => $clip) {
	echo "				<tr id='cliprow-" . $clip->clipID . "'>";
	echo "					<td style='width:250px;padding-left:5px;'>";
	if ($clip->name == '') {
		echo "<div onclick=\"playclipclick(" . $clip->clipID . ",'Clip-" . $clip->clipID . "')\">";
		echo "Clip-" . $clip->clipID;
	} else {
		echo "<div onclick=\"playclipclick(" . $clip->clipID . ",'" . $clip->name . "')\">";
		echo "" . $clip->name;
	}
	echo "</div>";
	echo "					</td>";
	
	echo "					<td>";
	echo "<button onclick=\"openclipclick(" . $clip->clipID . ",'" . $clip->name . "')\">edit</button>";
	echo "					</td>";

	echo "					<td>";
	echo "<button onclick=\"removeclipclick(" . $clip->clipID . ",'" . $clip->name . "')\">remove</button>";
	echo "					</td>";
	
	/*
	echo "					<td>";
	echo "<button onclick=\"recordstopclick(" . $sentence->sentenceID . ",'" . $sentence->sentence . "')\">stop</button>";
	echo "					</td>";

	echo "					<td>";
	echo "<button onclick=\"saveData(" . $sentence->sentenceID . ",'" . $sentence->sentence . "')\">save</button>";
	echo "					</td>";
	*/

	echo "				</tr>";
}
echo "				</table>";
echo "			</div>";

echo "		</td>";




echo "<script>";
echo "		function playclipclick(clipID) {";
echo "			console.log('playclip - '+clipID);";

echo "			var url = '" . getUrl("worder/audio/downloadclipfile") . "&clipID='+clipID;";
echo "			console.log(url);";

echo "			var audio = document.getElementById('audio');";
echo "			audio.src = url;";
echo "			audio.load();";
echo "			audio.play();";
echo "		}";
echo "</script>";



echo "<script>";
echo "		function removeclipclick(clipID) {";
echo "			console.log('removeclipclick - '+clipID);";
echo "			console.log('" . getUrl('worder/audio/removeclipJSON') . "&clipID='+clipID);";
echo "			$.getJSON('" . getUrl('worder/audio/removeclipJSON') . "&clipID='+clipID,'',function(data) {";
echo "				console.log('removedata - '+data.success);";
echo "				console.log('removedata - '+data.removed);";
echo "				$('#cliprow-'+clipID).hide();";
echo "			}); ";
echo "		}";
echo "</script>";



// content
echo "		<td style='width:800px;background-color:yellow;vertical-align:top;'>";

echo "			<div style='background-color:lightgreen;color:black;width:100%;height:500px;overflow-y:scroll;border:thin solid grey;'>";

echo "			<div style='background-color:lightblue;color:black;width:100%;height:100px;overflow-y:scroll;border:thin solid grey;'>";
echo "				<table id=availableaudiofiles>";
echo "					<tr>";
echo "						<td style='width:300px;'>";
echo "						</td>";
echo "						<td>";
echo "						</td>";
echo "					</tr>";
echo "				</table>";
echo "			</div>";


echo "				<div id='waveform' style='background-color:white;color:black;width:750px;height:100px;overflow-y:scroll;border:thin solid grey;'>";
echo "				</div>";

echo "				<div id='specto' style='background-color:lightblue;color:black;width:750px;height:256px;overflow-y:scroll;border:thin solid grey;'>";
echo "				</div>";


echo "				<div style='float:left;'>";

echo "					<button  onclick=\"wavesurferplay()\">Play</button>";

echo "					<button  onclick=\"wavesurferaddregion()\">Add region</button>";

echo "					<button  onclick=\"wavesurferplayregion()\">Play region</button>";

echo "					<button  onclick=\"removeregions()\">remove regions</button>";

echo "					<button  onclick=\"saveclip()\">save region</button>";

echo "				</div>";
echo "			</div>";
echo "			<div style='background-color:lightgreen;color:black;width:100%;height:200px;overflow-y:scroll;border:thin solid grey;'>";
echo "			</div>";
echo "		</td>";
echo "	</tr>";
echo "</table>";


echo "	<script>";
echo "		function wavesurferplay() {";
echo "			wavesurfer.play();";
echo "		}";
echo "	</script>";


echo "	<script>";
echo "		var activeregion = null;";
echo "	</script>";


echo "	<script>";
echo "		function wavesurferaddregion() {";
echo "			wavesurfer.addRegion({ start:1, end: 2});";
echo "			wavesurfer.on('region-click',function(region, e) {";
echo "				console.log('start - '+region.start);";
echo "				console.log('end - '+region.end);";
echo "				region.wavesurfer.play(region.start, region.end);";
echo "				activeregion = region;";
echo "			});";
echo "		}";
echo "	</script>";

echo "	<script>";
echo "		function wavesurferplayregion() {";
echo "			console.log('play region');";
echo "			activeClip = copyregionblob(activeregion, wavesurfer);";
echo "			var audio = document.getElementById('audio');";
echo "			var url = URL.createObjectURL(activeClip);";
echo "			audio.src = url;";
echo "			console.log('load start');";
echo "			audio.load();";
echo "			audio.play();";
echo "		}";
echo "	</script>";




echo "	<script>";
echo "		function saveclip() {";
echo "			console.log('save clip.');";
echo "			sendClipData(sentenceID, activeClip);";
echo "		}";
echo "	</script>";




echo "	<script>";
echo "	 function copyregionblob(region, instance) {";

echo "		console.log('***************************');";
echo "		console.log('copy blob...');";

echo "		var segmentDuration = region.end - region.start;";

echo "		var originalBuffer = instance.backend.buffer;";
echo "		var emptySegment = instance.backend.ac.createBuffer(";
echo "				originalBuffer.numberOfChannels,";
echo "				segmentDuration * originalBuffer.sampleRate +1,";
echo "				originalBuffer.sampleRate";
echo "		);";
echo "		console.log('duration - '+(segmentDuration * originalBuffer.sampleRate));";



echo "		var delta = 0;";
echo "		for (var i = 0; i < originalBuffer.numberOfChannels; i++) {";
echo "			var chanData = originalBuffer.getChannelData(i);";
echo "			var emptySegmentData = emptySegment.getChannelData(i);";
echo "			var mid_data = chanData.subarray( region.start * originalBuffer.sampleRate, region.end * originalBuffer.sampleRate);";
echo "			console.log(' - - start '+(region.start * originalBuffer.sampleRate));";
echo "			console.log(' - - end '+ (region.end * originalBuffer.sampleRate));";
echo "			delta = (region.end * originalBuffer.sampleRate)-(region.start * originalBuffer.sampleRate);";
echo "			emptySegmentData.set(mid_data);";
echo "		}";
echo "		console.log(' - - delta '+Math.floor(delta));";

//echo "			var len=0.2;";
//echo "			console.log('jee-1-len: '+len);";

echo "			var numOfChan = originalBuffer.numberOfChannels;";
//echo "			var numOfChan = 1;";
echo "			console.log('jee-numOfChan - '+numOfChan);";
echo "			var length = Math.floor(delta) * numOfChan * 2 + 44;";
echo "			console.log('jee-length - '+length);";
echo "			var buffer = new ArrayBuffer(length);";
echo "			var view = new DataView(buffer);";
echo "			var channels = [];";
echo "			var i;";
echo "			var sample;";
echo "			var pos = 0;";
echo "			console.log('jee-2');";
// write WAVE header
echo "			setUint32(0x46464952);";
echo "			setUint32(length - 8);";
echo "			setUint32(0x45564157);";

echo "			setUint32(0x20746d66);";
echo "			setUint32(16);";
echo "			setUint16(1);";
echo "			setUint16(numOfChan);";
echo "			setUint32(originalBuffer.sampleRate);";
echo "			setUint32(originalBuffer.sampleRate * 2 * numOfChan);";
echo "			setUint16(numOfChan * 2);";
echo "			setUint16(16);";
echo "			console.log('jee-3');";

echo "			setUint32(0x61746164);";
echo "			setUint32(length - pos - 4);";
echo "			console.log('jee-4');";

echo "			var offset = Math.floor((region.start * originalBuffer.sampleRate));";
echo "			console.log(' -- start: '+offset);";
echo "			console.log(' -- length: '+length);";
echo "			regionstart = offset;";
echo "			for(i = 0; i < numOfChan; i++) {";
echo "				channels.push(originalBuffer.getChannelData(i));";
echo "				console.log('jee-channel-'+i);";
echo "				while(pos < length) {";
echo "					for(i = 0; i < numOfChan; i++) {";
echo "						sample = Math.max(-1, Math.min(1, channels[i][offset]));";
echo "						sample = (0.5 + sample < 0 ? sample * 32768 : sample * 32767)|0;";
echo "						view.setInt16(pos, sample, true);";
echo "						pos += 2;";
echo "					}";
echo "					offset++";
echo "				}";
echo "			}";
echo "			console.log(' -- endpos: '+(pos+offset));";
echo "			regionend = (pos+offset);";
echo "		 	function setUint16(data) {";
echo "		  		view.setUint16(pos, data, true);";
echo "		 		pos += 2;";
echo "		  	}";

echo "		  	function setUint32(data) {";
echo "		  		view.setUint32(pos, data, true);";
echo "		  		pos += 4;";
echo "		  	}";

echo "			return new Blob([buffer], {type: 'audio/wav'});";
echo "	}";
echo "	</script>";


echo "	<script>";
echo "		function removeregions() {";
echo "			wavesurfer.regions.clear();";
echo "		}";
echo "	</script>";


echo "	<script>";
echo "		function saveData(sentenceID, str) {";
echo "			sendAudioData(sentenceID, chunks[chunks.length-1]);";
echo "		}";
echo "	</script>";


echo "<script>";
echo "	var wavesurfer = WaveSurfer.create({";
echo "		container: '#waveform',";
echo "		height: 100,";
echo "		plugins: [";
echo "			WaveSurfer.regions.create(),";
echo "			WaveSurfer.spectrogram.create({";
echo "				fftSamples: 512,";
echo "				container: '#specto',";
echo "				labels: false";
echo "			})";
echo "		]";
echo "	});";
echo "</script>";


echo "<script>";
echo "		function sentenceclick(tempsentenceID, str) {";
echo "			sentenceID = tempsentenceID;";
echo "			console.log('sentenceID - '+sentenceID);";
echo "			$('#sentencediv').html(str);";
echo "			$.getJSON('" . getUrl('worder/audio/getaudiofilesJSON') . "&sentenceID='+sentenceID,'',function(data) {";
echo "				$('#availableaudiofiles').empty();";
echo "				$.each(data, function(index) {";
echo "					console.log('index - '+index);";
echo "					var fileID = data[index]['fileID'];";
echo "					console.log(' -- fileID - '+fileID);";
echo "					var linkID = data[index]['linkID'];";
echo "					console.log(' -- linkID - '+linkID);";
echo "					addTableRow(linkID,fileID,str);";
echo "				});";
echo "			}); ";
echo "		}";
echo "</script>";





$section = new UISection('Clipin muokkaus','500px');
$section->setDialog(true);
$section->setMode(UIComponent::MODE_EDIT);
$section->setInsertAction(UIComponent::ACTION_JAVASCRIPT, 'updateclipdialogclosed');

$clipIDfield = new UIFixedTextField("ClipID", 0);
$section->addField($clipIDfield);
	
$namefield = new UITextField("Name", "Name", 'name');
$section->addField($namefield);

//$field = new UITextField("Description", "Description", 'description');
//$section->addField($field);

$section->show();


echo "<script>";
echo "	function opennewtagdialog() {";
echo "	 	" . $namefield->setValueJSFunction()."('');";
echo "  	$('#sectiondialog-" . $section->getID() . "').dialog('open');";
echo "	}";
echo "</script>";

echo "<script>";
echo "	function openclipclick(clipID, name) {";
echo "	 	" . $clipIDfield->setValueJSFunction()."(clipID);";
echo "	 	" . $namefield->setValueJSFunction()."(name);";
echo "  	$('#sectiondialog-" . $section->getID() . "').dialog('open');";

echo "	}";
echo "</script>";

echo "<script>";
echo "	function updateclipdialogclosed() {";
echo "  	var name = $('#editfield-" . $namefield->getID() . "').val();";
echo "  	var clipID = $('#editfield-" . $clipIDfield->getID() . "').html();";
echo "		console.log('dialog closed '+name);";
echo "		console.log('dialog closed '+clipID);";
echo "		$.getJSON('" . getUrl('worder/audio/updateclipJSON') . "&clipID='+clipID+'&name='+name,'',function(data) {";
echo "			console.log('updateclipJSON - '+data);";
echo "		}); ";
echo "	}";
echo "</script>";

/*
echo "	<script>";
echo "		function setValue_" . $editdialog->getID(). "(fieldname, value) {";
echo "			if (fieldname == '0') {";
echo "	 			" . $languagefield->setValueJSFunction()."(value);";
//echo "				console.log('setting languagefield- ' + fieldname + '='+value);";
echo "			}";
echo "		}";
echo "	</script>";
*/



echo "<script>";
echo "		function addTableRow(audiofileID, fileID, str) {";
echo "			var tr = document.createElement('tr');";
echo "			tr.id = 'audiolinkrow-'+audiofileID;";

echo "			var td = document.createElement('td');";
echo "			td.style.width = '160px';";
echo "			td.style.maxWidth = '160px';";
echo "			td.style.textOverflow = 'hidden';";
echo "			td.style.overflow = 'hidden';";
echo "			td.style.whiteSpace = 'nowrap';";
echo "			td.style.cursor = 'pointer';";
echo "			td.innerHTML = fileID + ' - ' + str;";
echo "			td.onclick = function() {";
echo "				playsentencefile(audiofileID);";
echo "			};";
echo "			td.id = 'audiofile-'+audiofileID;";
echo "			td.className += ' myclass';";
echo "			td.className += ' myclass';";
echo "			tr.append(td);";

echo "			var td = document.createElement('td');";
echo "			var playbutton = document.createElement('button');";
echo "			playbutton.textContent = 'play';";
echo "			playbutton.onclick = function() {";
echo "				playsentencefile(audiofileID);";
echo "			};";
echo "			td.className += ' myclass';";
echo "			td.className += ' myclass';";
echo "			td.append(playbutton);";
echo "			tr.append(td);";

echo "			var td = document.createElement('td');";
echo "			var deletebutton = document.createElement('button');";
echo "			deletebutton.id = 'deletebutton-'+audiofileID;";
echo "			deletebutton.textContent = 'del';";
echo "			deletebutton.onclick = function() {";
echo "				deletesentencefile(audiofileID);";
echo "			};";
echo "			td.className += ' myclass';";
echo "			td.className += ' myclass';";
echo "			td.append(deletebutton);";
echo "			tr.append(td);";

echo "			$('#availableaudiofiles').append(tr);";
echo "		}";
echo "</script>";



echo "<script>";
echo "		function deletesentencefile(audiofileID) {";
echo "			console.log('delete sentencefile');";
// ladataan sentenceID:n perusteella lista uudelleen...
echo "			console.log('removeaudiolink - '+audiofileID);";
echo "			console.log('" . getUrl('worder/audio/removesentencefileJSON') . "&audiofileID='+audiofileID);";
echo "			$.getJSON('" . getUrl('worder/audio/removesentencefileJSON') . "&audiofileID='+audiofileID,'',function(data) {";
echo "				console.log('removedata - '+data.success);";
//echo "				console.log('removedata - '+data.removed);";
//echo "				$('#cliprow-'+clipID).hide();";

echo "				if (data.success == 0) {";
echo "					console.log('remove failed');";
echo "				} else {";
echo "					console.log('remove success');";
echo "					$('#audiolinkrow-'+audiofileID).hide();";
echo "				}";

echo "			}); ";
echo "		}";
echo "</script>";


echo "<script>";
echo "		function playsentencefile(audiofileID) {";
echo "			console.log('play - '+audiofileID);";
echo "			activeAudiofileID = audiofileID;";

echo "			var url = '" . getUrl("worder/audio/downloadaudiofile") . "&linkID='+audiofileID;";
echo "			console.log(url);";

echo "			var audio = document.getElementById('audio');";
echo "			audio.src = url;";
echo "			audio.load();";

echo "			wavesurfer.load(url);";
echo "			wavesurfer.on('ready', function () {";
echo "				wavesurfer.play();";
echo "			});";
echo "		}";
echo "</script>";






echo "<script>";
echo "		function recordbuttonclick(audiofileID, str) {";
echo "			console.log('record - '+audiofileID);";
echo "			if (context == null) {";
echo "				context = new AudioContext();";
echo "				navigator.mediaDevices.getUserMedia({ audio: true, video: false }).then(microphoneSuccess);";
echo "				console.log('context created');";
echo "			}";

echo "			if (recorder != null) {";
echo "				recorder.start();";
echo "				console.log('record start');";
echo "			} else {";

echo "			}";
echo "		}";
echo "</script>";


echo "<script>";
echo "		function recordstopclick(audiofileID, str) {";
echo "			console.log('record stop - '+audiofileID);";

echo "			if (recorder != null) {";
echo "				recorder.stop();";
echo "			}";

echo "		}";
echo "</script>";




echo "	<script>";
echo "		function sendAudioData(sentenceID, data) {";
echo "			console.log('sentenceID - '+sentenceID);";
echo "			var xhr=new XMLHttpRequest();";
echo "			xhr.onload=function(e) {";
echo "				if(this.readyState === 4) {";
echo "					console.log('Server returned: ',e.target.responseText);";
echo "				}";
echo "			};";
echo "			var fd=new FormData();";
echo "			fd.append('audio_data',data, 'jee.php');";
echo "			fd.append('lessonID',34);";
echo "			fd.append('sentenceID',sentenceID);";
echo "			xhr.open('POST','https://www.babelsoft.fi/demo/index.php?rt=worder/audio/uploadaudio',true);";
echo "			xhr.send(fd);";

echo "			xhr.onload = function() {";
echo "				console.log('Loaded: '+xhr.status+','+xhr.response);";
echo "				console.log('Loaded2: '+sentenceID);";
echo "			};";
echo "		}";
echo "	</script>";




echo "	<script>";
echo "		function sendClipData(sentenceID, data) {";
echo "			console.log('sentenceID - '+sentenceID);";
echo "			var xhr=new XMLHttpRequest();";
echo "			xhr.onload=function(e) {";
echo "				if(this.readyState === 4) {";
echo "					console.log('Server returned: ',e.target.responseText);";
echo "				}";
echo "			};";
echo "			var fd=new FormData();";
echo "			fd.append('audio_data',data, 'jee.php');";
echo "			fd.append('lessonID',34);";
echo "			fd.append('sentenceID',sentenceID);";
echo "			fd.append('audiofileID',activeAudiofileID);";
echo "			fd.append('startpos',regionstart);";

echo "			console.log(' - saving audiofileID: '+activeAudiofileID);";
echo "			console.log(' - saving startpos: '+regionstart);";
echo "			console.log(' - saving endpos: '+regionend);";

echo "			fd.append('endpos',regionend);";
echo "			xhr.open('POST','https://www.babelsoft.fi/demo/index.php?rt=worder/audio/uploadclip',true);";
echo "			xhr.send(fd);";

echo "			xhr.onload = function() {";
echo "				console.log('Clipsaved: '+xhr.status+','+xhr.response);";
echo "				console.log('Clipsaved: '+sentenceID);";
echo "			};";
echo "		}";
echo "	</script>";




?>