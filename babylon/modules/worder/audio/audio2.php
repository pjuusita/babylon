<?php




echo "	<script>";
echo "		var context = null;";
echo "		var recorder = null;";
echo "		var specto = null;";
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

/*
echo "	<tr>";
echo "		<td style='width:250px;'></td>";
echo "		<td style='text-align:center;'>";
echo "		<canvas id='canvas01' width='250' height='80' style='display: inline-block; background-color:pink;'></canvas>";

//echo "<div id=canvas01 style='width:250ox;height:100px;background-color:pink'></div>";
echo "		</td>";
echo "	</tr>";
*/
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
echo "select sentence 12";
echo "						</td>";
echo "						<td>";
echo "						</td>";
echo "					</tr>";
echo "				</table>";
echo "			</div>";


echo "			<div id='waveform' style='background-color:white;color:black;width:100%;height:100px;overflow-y:scroll;border:thin solid grey;'>";
echo "			</div>";


echo "			<div id='specto' style='background-color:lightblue;color:black;width:100%;height:300px;overflow-y:scroll;border:thin solid grey;'>";
echo "			</div>";

echo "			<div class='controls'>";
echo "			<button  onclick=\"wavesurfer2play()\">Play2</button>";
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


echo "	var wavesurfer2 = WaveSurfer.create({";

echo "		container: '#waveform',";

echo "		plugins: [";
echo "				WaveSurfer.spectrogram.create({";
echo "					wavesurfer: wavesurfer2,";
echo "					container: '#specto',";
//echo "				colorMap: 'hot-colormap.json',";
echo "					labels: false";
echo "			})";
echo "		]";

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

echo "		$('#createbutton').click(function () {";
echo "			console.log('Trying microhone access');";
echo "			context = new AudioContext();";
echo "			navigator.mediaDevices.getUserMedia({ audio: true, video: false }).then(microphoneSuccess);";
echo "			console.log('Ready end.');";
echo "		});";

echo "		$('#startbutton').click(function () {";
echo "			recorder.start();";
echo "		});";

echo "		$('#endbutton').click(function () {";
echo "			console.log('endbutton');";
echo "			recorder.stop();";
echo "		});";

echo "		$('#playbutton').click(function () {";
echo "			console.log('playbutton');";
echo "		});";

echo "		$('#savebutton').click(function () {";
echo "			saveData();";
echo "		});";
echo "	</script>";


echo "</table>";







echo "	<script>";
echo "		function microphoneSuccess(stream) {";
echo "			console.log('Microphone access successfull');";
//echo "			source = context.createMediaStreamSource(stream);";
//echo "			source.connect(context.destination);";
echo "			recorder = new MediaRecorder(stream);";
echo "			source = context.createMediaStreamSource(stream);";

// Mikrofonilta tuleva ääniaalto...
//echo "			can1 = document.getElementById('canvas01');";
//echo "			createWaveGraph(source,can1);";

echo "			can1 = document.getElementById('canvas01');";
echo "			createFrequencyGraph(source,can1);";


//echo "			can2 = document.getElementById('canvas02');";
//echo "			specto = AudioSpectrogram(source,can2);";



echo "			source = context.createMediaStreamSource(stream);";

// Frequency graph, pylväsgrammi frekvensseistä...
//echo "			can2 = document.getElementById('canvas02');";
//echo "			createFrequencyGraph(source,can2);";

echo "			console.log('State = ' + recorder.state);";


echo "			recorder.ondataavailable = handledata;";


//echo "			analyser.getByteTimeDomainData(dataArray);";
//echo "			source.connect(analyser);";
echo "			recorder.onstop = function (e) {";
echo "				console.log('recording ended - '+recorder.state);";
//echo "				console.log(recorder.state);";

echo "				console.log('chunk count - '+chunks.length);";
//echo "				var blob = new Blob(chunks);";
echo "				var audio = document.getElementById('audio');";
echo "				audio.src = URL.createObjectURL(chunks[chunks.length-1]);";
//echo "				var audio = new Audio(url);";
echo "				audio.play();";
echo "			};";

echo "			console.log('microphoneSuccess');";

//echo "			source.start(0);";
echo "		}";
echo "	</script>";


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
//echo "				console.log(' - on sentence: ','+sentenceID);";

echo "			};";

/*
echo "			return fetch('https://www.babelsoft.fi/demo/index.php?rt=worder/audio/uploadaudio', {";
echo "				method: 'POST',";
echo "				body: formData";
echo "			});";
*/
echo "		}";
echo "	</script>";



echo "	<script>";
echo "		function play() {";
echo "			console.log('play');";
echo "			specto.begin();";
echo "			specto.drawOnScreen();";
echo "		}";


echo "		function microphoneError() {";
echo "			console.log('No microhone access');";
echo "		}";


/*
echo "		$(document).ready(function() {";
echo "			console.log('Trying microhone access');";
echo "			context = new AudioContext();";
echo "			navigator.mediaDevices.getUserMedia({ audio: true, video: false }).then(microphoneSuccess);";
echo "			console.log('Ready end.');";
echo "		});";
*/

echo "	</script>";



// -------------------------------------------------------------------------------
// -------------------------------------------------------------------------------
// -------------------------------------------------------------------------------



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