<?php




/*
echo "		var analyzer = audioCtx.createAnalyser();";

echo "		source = audioCtx.createMediaStreamSource(stream);";
echo "		source.connect(analyser);";
echo "		analyser.connect(distortion);";
echo "		distortion.connect(audioCtx.destination);";

*/


/*
echo "	<script>";

echo "		function init() {";
echo "			var context = new AudioContext();";
echo "			console.log(context.currentTime);";
echo "			console.log(context.destination);";
echo "		}";


echo "		window.addEventListener('load', init );";

echo "	</script>";
*/



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
echo "		function microphoneSuccess(stream) {";
echo "			console.log('Microphone access successfull');";
//echo "			source = context.createMediaStreamSource(stream);";
//echo "			source.connect(context.destination);";
//echo "			recorder = new MediaRecorder(stream);";
echo "			source = context.createMediaStreamSource(stream);";
echo "			can1 = document.getElementById('canvas01');";
echo "			createWaveGraph(source,can1);";

echo "			source = context.createMediaStreamSource(stream);";
echo "			can2 = document.getElementById('canvas02');";
echo "			createFrequencyGraph(source,can2);";

//echo "			recorder.ondataavailable = handledata;";


//echo "			analyser.getByteTimeDomainData(dataArray);";
/*
echo "			source.connect(analyser);";
echo "			recorder.onstop = function (e) {";
echo "				console.log('recording ended');";
echo "				console.log(recorder.state);";
echo "				console.log('chunk count - '+chunks.length);";
//echo "				var blob = new Blob(chunks);";
echo "				var audio = document.getElementById('audio');";
echo "				audio.src = URL.createObjectURL(chunks[0]);";
//echo "				var audio = new Audio(url);";
echo "				audio.play();";
echo "			}";
*/

//echo "			source.start(0);";
echo "		}";
echo "	</script>";


echo "	<script>";
echo "		function play() {";
echo "			console.log('play');";
echo "		}";


echo "		function microphoneError() {";
echo "			console.log('No microhone access');";
echo "		}";


echo "		$(document).ready(function() {";
echo "			console.log('Trying microhone access');";
echo "			context = new AudioContext();";
echo "			MediaDevices.getUserMedia({ audio: true, video: false }).then(microphoneSuccess);";
echo "			console.log('Ready end.');";
echo "		});";
		
echo "	</script>";


/*
echo "	<script>";
echo "		function jee() {";
echo "			alert('jee');";
//echo "			console.log('jeejee');";
echo "		}";
echo "	</script>";
*/



echo "	<div id='viz' style='background-color:white;'>";
echo "<br><br>";
echo "		<canvas id='canvas01' width='700' height='70' style='display: inline-block; background-color:white; border: 2px solid black;border-bottom: 0px;'></canvas>";
echo "		<canvas id='canvas02' width='700' height='70' style='display: inline-block; background-color:white; border: 2px solid black;border-bottom: 0px;'></canvas>";
echo "		<canvas id='canvas03' width='700' height='70' style='display: inline-block; background-color:white; border: 2px solid black;'></canvas>";
//echo "		<canvas id='wavedisplay' width='1024' height='500' style='display: inline-block; background: #202020; '></canvas>";
echo "	</div>";
echo "	<div id='controls'>";
//echo "		<img id='record' src='img/mic128.png' onclick='toggleRecording(this);'>";
//echo "		<a id='save' href='#'><img src='img/save.svg'></a>";
echo "";
echo "		<button id=\"startbutton\">Start</button>";
echo "		<button id=\"endbutton\">End</button>";
echo "		<button id=\"playbutton\">Play</button>";

echo "		<audio id='audio' controls></audio>";
echo "	<script>";

echo "		$('#startbutton').click(function () {";
echo "			console.log('startbutton');";
echo "			recorder.start();";
echo "		});";

echo "		$('#endbutton').click(function () {";
echo "			console.log('endbutton');";
echo "			recorder.stop();";
echo "		});";


echo "		$('#playbutton').click(function () {";
echo "			console.log('playbutton');";
echo "			recorder.stop();";
echo "		});";


echo "	</script>";


echo "	</div>";


?>