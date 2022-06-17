<?php




echo "	<script>";
echo "		var context = null;";
echo "		var recorder = null;";
echo "		var specto = null;";
echo "		var buffersource = null;";
echo "		var chunks = [];";
echo "		var sentenceID = 0;";
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
echo "		<td id='sentencediv' style='vertical-align:bottom;background-color:yellow;text-align:center;font-weight:bold;font-size:30px;'>";
echo "this is sentence";
echo "		</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td style='width:250px;text-align:left;vertical-align:top;'>";
$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->setID, $this->registry->sets, "worder/audio/showeditaudio", "","setID", "name");
$filterbox->show();
echo "		</td>";
echo "		<td style='vertical-align:bottom;background-color:yellow;text-align:center;'>";
echo "			<audio id='audio' controls></audio>";
echo "		</td>";
echo "	</tr>";
echo "</table>";



echo "<table style='width:1100px;'>";

echo "	<tr>";
echo "		<td style='width:300px;vertical-align:top;'>";
echo "			<div style='background-color:pink;color:black;width:330px;height:600px;overflow-y:scroll;border:thin solid grey;'>";
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
echo "		</td>";

// content
echo "		<td style='width:800px;background-color:yellow;vertical-align:top;'>";
echo "			<div style='background-color:lightblue;color:black;width:100%;height:100px;overflow-y:scroll;border:thin solid grey;'>";
echo "				<table id=availableaudiofiles>";
echo "					<tr>";
echo "						<td>";
echo "select sentence 23";
echo "						</td>";
echo "						<td>";
echo "						</td>";
echo "					</tr>";
echo "				</table>";
echo "			</div>";



echo "			<div id='waveform' style='background-color:white;color:black;width:100%;height:100px;overflow-y:scroll;border:thin solid grey;'>";
echo "			</div>";

echo "			<div id='specto' style='background-color:lightblue;color:black;width:100%;height:100px;overflow-y:scroll;border:thin solid grey;'>";
echo "			</div>";

echo "			<div class='controls'>";
echo "			<button  onclick=\"wavesurfer2play()\">Play3</button>";
echo "			</div>";

echo "			<div class='controls'>";
echo "			<button  onclick=\"wavesurferaddregion()\">Add region</button>";
echo "			</div>";


echo "			<div class='controls'>";
echo "			<button  onclick=\"wavesurferplayregion()\">Play region</button>";
echo "			</div>";


echo "			<div class='controls'>";
echo "			<button  onclick=\"removeregions()\">remove regions</button>";
echo "			</div>";

echo "			<div id='secondwave' style='background-color:white;color:black;width:100%;height:100px;overflow-y:scroll;border:thin solid grey;'>";
echo "			</div>";

echo "			<div>";
echo "			<button  onclick=\"playsecond()\">play second</button>";
echo "			</div>";

echo "		</td>";
echo "	</tr>";

echo "</table>";


echo "	<script>";
echo "		function wavesurfer2play() {";
echo "			wavesurfer2.play();";
echo "		}";
echo "	</script>";


echo "	<script>";
echo "		var activeregion = null;";
echo "	</script>";


echo "	<script>";
echo "		function wavesurferaddregion() {";
echo "			wavesurfer2.addRegion({ start:1, end: 2});";
echo "			wavesurfer2.on('region-click',function(region, e) {";
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

/*
echo "			wavesurfer2.play(activeregion.start, activeregion.end);";
echo "			console.log('reg - '+activeregion.start+'-'+activeregion.end);";

echo "			var copy = copyregion(activeregion, wavesurfer2);";
echo "			console.log('copy success');";

echo "			context = new AudioContext();";
echo "			buffersource = context.createBufferSource();";
echo "			console.log('context length - '+context.sampleRate);";
echo "			buffersource.connect(context.destination);";

echo "			buffersource.buffer = copy;";
*/

echo "			var copy2 = copyregionblob(activeregion, wavesurfer2);";
echo "			var audio = document.getElementById('audio');";
echo "			var url = URL.createObjectURL(copy2);";
echo "			audio.src = url;";
echo "			console.log('load start');";
echo "			audio.load();";
//echo "			console.log('play start');";
//echo "			audio.play();";

//echo "			audioElement.src = url;";
echo "		}";
echo "	</script>";



echo "	<script>";
echo "		function playsecond() {";
//echo "			buffersource.stop();";
//echo "			buffersource.start();";
//echo "			";

echo "			console.log('sample length - '+buffersource.buffer.sampleRate);";
echo "			var blob = bufferToWave(buffersource, 0, buffersource.buffer.sampleRate*0.2);";

echo "			var audio = document.getElementById('audio');";
echo "			audio.src = url;";
echo "			console.log('load start');";
echo "			audio.load();";
echo "			console.log('play start');";
echo "			audio.play();";

echo "		}";
echo "	</script>";



// Convert a audio-buffer segment to a Blob using WAVE representation
echo "	<script>";
echo "		function bufferToWave(abuffer, offset, len) {";

echo "			console.log('jee-1-len: '+len);";
echo "			var numOfChan = abuffer.numberOfChannels;";
echo "			var numOfChan = 1;";
echo "			console.log('jee-numOfChan - '+numOfChan);";
echo "			var length = len * numOfChan * 2 + 44;";
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
echo "			setUint32(abuffer.sampleRate);";
echo "			setUint32(abuffer.sampleRate * 2 * numOfChan);";
echo "			setUint16(numOfChan * 2);";
echo "			setUint16(16);";
echo "			console.log('jee-3');";

echo "			setUint32(0x61746164);";
echo "			setUint32(length - pos - 4);";
echo "			console.log('jee-4');";

echo "			for(i = 0; i < numOfChan; i++)";
echo "				channels.push(abuffer.getChannelData(i));";
echo "			console.log('jee-channel-'+i);";

echo "				while(pos < length) {";
echo "				for(i = 0; i < numOfChan; i++) {";
echo "					sample = Math.max(-1, Math.min(1, channels[i][offset]));";
echo "					sample = (0.5 + sample < 0 ? sample * 32768 : sample * 32767)|0;";
echo "					view.setInt16(pos, sample, true);";
echo "					pos += 2;";
echo "				}";
echo "				offset++";
echo "				}";

		// create Blob
echo "				return new Blob([buffer], {type: 'audio/wav'});";

echo "		  function setUint16(data) {";
echo "		  view.setUint16(pos, data, true);";
echo "		  pos += 2;";
echo "		  }";

echo "		  function setUint32(data) {";
echo "		  view.setUint32(pos, data, true);";
echo "		  pos += 4;";
echo "		  }";
echo "		}";
echo "	</script>";

// Palauttaa audiobufferin...
echo "	<script>";
echo "	 function copyregion(region, instance){";

echo "		var segmentDuration = region.end - region.start;";

echo "		var originalBuffer = instance.backend.buffer;";
echo "		var emptySegment = instance.backend.ac.createBuffer(";
echo "				originalBuffer.numberOfChannels,";
echo "				segmentDuration * originalBuffer.sampleRate +1,";
echo "				originalBuffer.sampleRate";
echo "		);";
echo "		console.log('emptycreated.'+segmentDuration * originalBuffer.sampleRate);";

echo "		for (var i = 0; i < originalBuffer.numberOfChannels; i++) {";
echo "			var chanData = originalBuffer.getChannelData(i);";
echo "			var emptySegmentData = emptySegment.getChannelData(i);";
echo "			var mid_data = chanData.subarray( region.start * originalBuffer.sampleRate, region.end * originalBuffer.sampleRate);";
echo "			console.log(' - - start '+(region.start * originalBuffer.sampleRate));";
echo "			console.log(' - - end '+ (region.end * originalBuffer.sampleRate));";
echo "			console.log(' - - delta '+(region.end * originalBuffer.sampleRate)-(region.start * originalBuffer.sampleRate));";
echo "			emptySegmentData.set(mid_data);";
echo "		}";

echo "		return emptySegment;";
echo "	}";
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

echo "		 	function setUint16(data) {";
echo "		  		view.setUint16(pos, data, true);";
echo "		 		pos += 2;";
echo "		  	}";

echo "		  	function setUint32(data) {";
echo "		  		view.setUint32(pos, data, true);";
echo "		  		pos += 4;";
echo "		  	}";


// create Blob
echo "				return new Blob([buffer], {type: 'audio/wav'});";

echo "	}";
echo "	</script>";


/*
echo "	<script>";
echo "		 function paste(instance,cutSelection){";
echo "			var offlineAudioContext = instance.backend.ac;";
echo "			var originalAudioBuffer = instance.backend.buffer;";

echo "			let cursorPosition = instance.getCurrentTime()";
echo "			var newAudioBuffer = offlineAudioContext.createBuffer(";
echo "					originalAudioBuffer.numberOfChannels,";
echo "					originalAudioBuffer.length + cutSelection.length,";
echo "					originalAudioBuffer.sampleRate);";

echo "			for (var channel = 0; channel < originalAudioBuffer.numberOfChannels;channel++) {";

echo "				var new_channel_data = newAudioBuffer.getChannelData(channel);";
echo "				var empty_segment_data = cutSelection.getChannelData(channel);";
echo "				var original_channel_data = originalAudioBuffer.getChannelData(channel);";

echo "				var before_data = original_channel_data.subarray(0, cursorPosition * originalAudioBuffer.sampleRate);";
echo "				var mid_data = empty_segment_data";
echo "				var after_data = original_channel_data.subarray(Math.floor(cursorPosition * originalAudioBuffer.sampleRate), (originalAudioBuffer.length * originalAudioBuffer.sampleRate));";

echo "				new_channel_data.set(before_data);";
echo "				new_channel_data.set(mid_data,(cursorPosition * newAudioBuffer.sampleRate));";
echo "				new_channel_data.set(after_data,(cursorPosition + cutSelection.duration)* newAudioBuffer.sampleRate);";
echo "			}";
echo "			return newAudioBuffer";
echo "		}";
echo "	</script>";
*/


/*
echo "	<script>";
echo "		function cuttemp region() {";
echo "			console.log('reg - '+activeregion.start+'-'+activeregion.end);";

echo "			var parms = activeregion;";
//export function cut(params,instance){
	/*
	 ---------------------------------------------
	 The function will take the buffer used to create the waveform and will
	 create
	 a new blob with the selected area from the original blob using the
	 offlineAudioContext
	 * /

	// var self = this;
echo "				var start = activeregion.start;";
echo "				var end = activeregion.end;";

echo "					var originalAudioBuffer = instance.backend.buffer;";

echo "					var lengthInSamples = Math.floor( (end - start) * originalAudioBuffer.sampleRate );";
echo "					if (! window.OfflineAudioContext) {";
echo "						if (! window.webkitOfflineAudioContext) {";
			// $('#output').append('failed : no audiocontext found, change browser');
echo "							alert('webkit context not found')";
echo "						}";
echo "						window.OfflineAudioContext = window.webkitOfflineAudioContext;";
echo "					}";
	// var offlineAudioContext = new OfflineAudioContext(1, 2,originalAudioBuffer.sampleRate );
echo "					var offlineAudioContext = instance.backend.ac";

echo "					var emptySegment = offlineAudioContext.createBuffer(";
echo "							originalAudioBuffer.numberOfChannels,";
echo "							lengthInSamples,";
echo "							originalAudioBuffer.sampleRate );";

echo "					var newAudioBuffer = offlineAudioContext.createBuffer(";
echo "							originalAudioBuffer.numberOfChannels,";
echo "							(start === 0 ? (originalAudioBuffer.length - emptySegment.length) :originalAudioBuffer.length),";
echo "							originalAudioBuffer.sampleRate);";

echo "					for (var channel = 0; channel < originalAudioBuffer.numberOfChannels;channel++) {";

echo "						var new_channel_data = newAudioBuffer.getChannelData(channel);";
echo "						var empty_segment_data = emptySegment.getChannelData(channel);";
echo "						var original_channel_data = originalAudioBuffer.getChannelData(channel);";

echo "						var before_data = original_channel_data.subarray(0, start * originalAudioBuffer.sampleRate);";
echo "						var mid_data = original_channel_data.subarray( start * originalAudioBuffer.sampleRate, end * originalAudioBuffer.sampleRate);";
echo "						var after_data = original_channel_data.subarray(Math.floor(end * originalAudioBuffer.sampleRate), (originalAudioBuffer.length * originalAudioBuffer.sampleRate));";

echo "						empty_segment_data.set(mid_data);";
echo "						if(start > 0){";
echo "							new_channel_data.set(before_data);";
echo "							new_channel_data.set(after_data,(start * newAudioBuffer.sampleRate));";
echo "						} else {";
echo "							new_channel_data.set(after_data);";
echo "						}";
echo "					}";
echo "					return {";
echo "						newAudioBuffer,";
echo "						cutSelection:emptySegment";
echo "					}";
echo "				}";

echo "		}";
echo "	</script>";
*/




echo "	<script>";
echo "		function removeregions() {";
echo "			wavesurfer2.regions.clear();";
echo "		}";
echo "	</script>";


echo "	<script>";
echo "		function saveData(sentenceID, str) {";
//echo "			var audio = document.getElementById('audio');";
//echo "			audio.src = URL.createObjectURL(chunks[0]);";
//echo "			audio.play();";
echo "			sendAudioData(sentenceID, chunks[chunks.length-1]);";
echo "		}";
echo "	</script>";

/*
echo "<script>";
echo "	var wavesurfer1 = WaveSurfer.create({";
echo "		container: '#waveform',";
echo "		waveColor: 'violet',";
echo "		progressColor: 'purple'";

echo "	});";
echo "</script>";
	*/



echo "<script>";
echo "	var secondwavesurfer = WaveSurfer.create({";
echo "		container: '#secondwave',";
echo "		plugins: [";
echo "			WaveSurfer.regions.create(),";
echo "			WaveSurfer.minimap.create({";
echo "				height: 30,";
echo "				waveColor: '#ddd',";
echo "				progressColor: '#999',";
echo "				cursorColor: '#999'";
echo "			})";
echo "		]";
echo "	});";
echo "</script>";


echo "<script>";


echo "	var wavesurfer2 = WaveSurfer.create({";

echo "		container: '#waveform',";

echo "		plugins: [";
echo "			WaveSurfer.regions.create(),";
echo "			WaveSurfer.minimap.create({";
echo "				height: 30,";
echo "				waveColor: '#ddd',";
echo "				progressColor: '#999',";
echo "				cursorColor: '#999'";
echo "			})";
echo "		]";



/*
echo "		plugins: [";
echo "				WaveSurfer.regions.create(),";
echo "				WaveSurfer.minimap.create({";
echo "			height: 30,";
echo "			waveColor: '#ddd',";
echo "			progressColor: '#999',";
echo "			cursorColor: '#999'";
echo "		}),";
echo "		WaveSurfer.timeline.create({";
echo "			container: '#wave-timeline'";
echo "		})";
echo "		]";
*/

/*
echo "		plugins: [";
echo "				WaveSurfer.spectrogram.create({";
echo "					wavesurfer: wavesurfer2,";
echo "					container: '#specto',";
//echo "				colorMap: 'hot-colormap.json',";
echo "					labels: false";
echo "			})";
echo "		]";
*/

echo "	});";

/*
echo "	const colormap = require('colormap');";
echo "	const colors = colormap({";
echo "	    colormap: 'hot',";
echo "	    nshades: 256,";
echo "	    format: 'float'";
echo "	});";
echo "	const fs = require('fs');";
echo "	fs.writeFile('hot-colormap.json', JSON.stringify(colors));";
*/
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
echo "					addTableRow(linkID,fileID);";
echo "				});";
echo "			}); ";
echo "		}";
echo "</script>";




echo "<script>";
echo "		function playaudiofile(audiofileID) {";
echo "			console.log('play - '+audiofileID);";

echo "			var url = '" . getUrl("worder/audio/downloadaudiofile") . "&linkID='+audiofileID;";
echo "			console.log(url);";

echo "			var audio = document.getElementById('audio');";
echo "			audio.src = url;";
echo "			audio.load();";



//echo "			wavesurfer1.load(url);";
echo "			wavesurfer2.load(url);";

//echo "			wavesurfer2.play();";

echo "			wavesurfer2.on('ready', function () {";
echo "				wavesurfer2.play();";
echo "			});";


// ********************************************
// Tähän spectogrammin näyttäminen lauseesta.





//echo "			var audiofile = new Audio(url);";
//echo "			var source = context.createMediaElementSource(audiofile);";

//echo "			source.connect(context.destination);";
//echo "			audio.play();";
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


echo "<script>";
echo "		function addTableRow(audiofileID, fileID) {";
echo "			var tr = document.createElement('tr');";

echo "			var td = document.createElement('td');";
echo "			td.style.width = '160px';";
echo "			td.style.maxWidth = '160px';";
echo "			td.style.textOverflow = 'hidden';";
echo "			td.style.overflow = 'hidden';";
echo "			td.style.whiteSpace = 'nowrap';";
echo "			td.style.cursor = 'pointer';";
echo "			td.innerHTML = fileID;";
echo "			td.onclick = function() {";
echo "				playaudiofile(audiofileID);";
echo "			};";
echo "			td.id = 'audiofile-'+audiofileID;";
echo "			td.className += ' myclass';";
echo "			td.className += ' myclass';";
echo "			tr.append(td);";


/*
echo "			var td = document.createElement('td');";
echo "			var recbutton = document.createElement('button');";
echo "			recbutton.data = 'rec';";

//echo "			td.style.width = '160px';";
//echo "			td.style.maxWidth = '160px';";
//echo "			td.style.textOverflow = 'hidden';";
//echo "			td.style.overflow = 'hidden';";
//echo "			td.style.whiteSpace = 'nowrap';";
//echo "			td.style.cursor = 'pointer';";
//echo "			td.innerHTML = fileID;";
echo "			recbutton.onclick = function() {";
echo "				recordaudiofile(audiofileID);";
echo "			};";
echo "			td.id = 'recordfile-'+audiofileID;";
echo "			td.className += ' myclass';";
echo "			td.className += ' myclass';";
echo "			td.append(recbutton);";
echo "			tr.append(td);";
*/

echo "			$('#availableaudiofiles').append(tr);";
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



echo "	<script>";
echo "	function AudioSpectrogram(audioSource, canvas)";
echo "	{";

echo "		var analyserNode = context.createAnalyser();";
echo "		audioSource.connect(analyserNode);";
//echo "		analyser.fftSize = 128;";
//echo "		console.log('fft width - '+analyser.smoothingTimeConstant);";


echo "		const frqBuf = new Uint8Array(analyserNode.frequencyBinCount);"; // 1024
echo "		const wfNumPts = 50*analyserNode.frequencyBinCount/128;"; // 400 +ve freq bins
echo "		const wfBufAry = {buffer: frqBuf};";
echo "		const wf = new Waterfall(wfBufAry, wfNumPts, wfNumPts, 'right', {});";

//echo "		const canvas = document.getElementById('canvas02');";
echo "		const ctx = canvas.getContext('2d');";

echo "		this.playing = true;";
echo "		this.begin = ()=>{";
echo "			wf.start();";
echo "			this.playing = true;";
echo "			this.drawOnScreen();";
echo "		};";

echo "		this.halt = ()=>{";
echo "			wf.stop();";
echo "			this.playing = false;";
echo "		};";

echo "		this.drawOnScreen = ()=>{";
echo "			analyserNode.getByteFrequencyData(frqBuf, 0);";
echo "			ctx.drawImage(wf.offScreenCvs, 0, 0);";
echo "			if (this.playing) requestAnimationFrame(this.drawOnScreen);";
echo "		};";
echo "		this.drawOnScreen();";
echo "		this.begin();";
echo "	}";

echo "	</script>";






?>