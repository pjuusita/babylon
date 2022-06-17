<?php



echo "	<script>";

echo "		const audiosourcetype = {";
echo "			'audio': true";
echo "		};";

/*

echo "		function hasGetUserMedia() {";
echo "			return !!(navigator.mediaDevices &&";
echo "					navigator.mediaDevices.getUserMedia);";
echo "		}";
*/

echo "		function audioError() {";
echo "			console.log(arguments);";
echo "		}";


echo "		function processSound(stream) {";
echo "			console.log('process sound ');";


echo "		}";


echo "		function init() {";

echo "			console.log('init');";
//echo "			window.AudioContext = window.AudioContext || window.webkitAudioContext;";
//echo "			var audioContext = new AudioContext();";
echo "			window.AudioContext = window.AudioContext || window.webkitAudioContext;";
echo "			audioContext = new AudioContext();";

echo "			var analyser = audioContext.createAnalyser();";
echo "			analyser.smoothingTimeConstant = 0.2;";
echo "			analyser.fftSize = 1024;";

echo "			var node = context.createScriptProcessor(2048,1,1);";
echo "			node.onaudioprocess = function () {";
echo "				self.spectrum = new Uint8Array(analyser.frequencyBinCount);";
echo "				analyser.getByteFrequencyData(self.spectrum);";
echo "				self.vol = self.getRMS(self.spectrum);";

echo "			if (!navigator.mediaDevices) {";
echo "				console.log('no mediaDevices');";
echo "				return;";
echo "			} ";
echo "			console.log('yes mediaDevices');";
echo "			try {";
echo "				navigator.mediaDevices.getUserMedia(audiosourcetype, processSound, audioError);";
echo "				console.log('success');";
echo "			} catch (e) {";
echo "				console.error(e);";
echo "			}";

echo "			var input = context.createMediaStreamSource(stream);";

echo "		}";


echo "		window.addEventListener('load', init );";
echo "	</script>";


echo "	<div id='viz' style='background-color:pink;'>";
echo "		<canvas id='analyser' width='1024' height='500' style='display: inline-block; background: #202020; '></canvas>";
echo "<br><br>";
echo "		<canvas id='wavedisplay' width='1024' height='500' style='display: inline-block; background: #202020; '></canvas>";
echo "	</div>";
echo "	<div id='controls'>";
//echo "		<img id='record' src='img/mic128.png' onclick='toggleRecording(this);'>";
//echo "		<a id='save' href='#'><img src='img/save.svg'></a>";
echo "	</div>";


?>