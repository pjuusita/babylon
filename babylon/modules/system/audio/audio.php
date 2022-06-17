<?php

	
/*
	echo "	<script>";
	echo "		function drawBuffer( width, height, context, data ) {";
	echo "			var step = Math.ceil( data.length / width );";
	echo "			var amp = height / 2;";
	echo "			context.fillStyle = 'silver';";
	echo "			context.clearRect(0,0,width,height);";
	echo "			for(var i=0; i < width; i++){";
	echo "				var min = 1.0;";
	echo "				var max = -1.0;";
	echo "				for (j=0; j<step; j++) {";
	echo "					var datum = data[(i*step)+j];";
	echo "					if (datum < min)";
	echo "						min = datum;";
	echo "					if (datum > max)";
	echo "						max = datum;";
	echo "				}";
	echo "				context.fillRect(i,(1+min)*amp,1,Math.max(1,(max-min)*amp));";
	echo "			}";
	echo "		}";
	echo "</script>";
*/
	
	
	echo "<script>";
	echo "		window.AudioContext = window.AudioContext || window.webkitAudioContext;";
	echo "		var audioContext = new AudioContext();";
	echo "		var audioInput = null,";
	echo "		realAudioInput = null,";
	echo "		inputPoint = null,";
	echo "		audioRecorder = null;";
	echo "		var rafID = null;";
	echo "		var analyserContext = null;";
	echo "		var canvasWidth, canvasHeight;";
	echo "		var recIndex = 0;";
	echo "</script>";
	
	
	echo "<script>";
	
	
	echo "	function updateAnalysers(time) {";
	echo "		if (!analyserContext) {";
	echo "			var canvas = document.getElementById('analyser');";
	echo "			canvasWidth = canvas.width;";
	echo "			canvasHeight = canvas.height;";
	echo "			analyserContext = canvas.getContext('2d');";
	echo "		}";
	
	
	echo "		var SPACING = 3;";
	echo "		var BAR_WIDTH = 3;";
	echo "		var numBars = Math.round(canvasWidth / SPACING);";
	echo "		var freqByteData = new Uint8Array(analyserNode.frequencyBinCount);";
	
	echo "		analyserNode.getByteFrequencyData(freqByteData);";
	
	echo "		analyserContext.clearRect(0, 0, canvasWidth, canvasHeight);";
	echo "		analyserContext.fillStyle = '#F6D565';";
	echo "		analyserContext.lineCap = 'round';";
	echo "		var multiplier = analyserNode.frequencyBinCount / numBars;";
	
			// Draw rectangle for each frequency bin.
	echo "		for (var i = 0; i < numBars; ++i) {";
	echo "			var magnitude = 0;";
	echo "			var offset = Math.floor( i * multiplier );";
				// gotta sum/average the block, or we miss narrow-bandwidth spikes
	echo "			for (var j = 0; j< multiplier; j++)";
	echo "				magnitude += freqByteData[offset + j];";
	echo "				magnitude = magnitude / multiplier;";
	echo "				var magnitude2 = freqByteData[i * multiplier];";
	echo "				analyserContext.fillStyle = 'hsl( ' + Math.round((i*360)/numBars) + ', 100%, 50%)';";
	echo "				analyserContext.fillRect(i * SPACING, canvasHeight, BAR_WIDTH, -magnitude);";
	echo "		}";
	
	echo "		rafID = window.requestAnimationFrame( updateAnalysers );";
	echo "	}";

	
	echo "	function gotStream(stream) {";
	echo "		inputPoint = audioContext.createGain();";
	
		// Create an AudioNode from the stream.
	echo "		realAudioInput = audioContext.createMediaStreamSource(stream);";
	echo "		audioInput = realAudioInput;";
	echo "		audioInput.connect(inputPoint);";
	
		//    audioInput = convertToMono( input );
	
	echo "	analyserNode = audioContext.createAnalyser();";
	echo "	analyserNode.fftSize = 2048;";
	echo "	inputPoint.connect( analyserNode );";
	
	echo "	audioRecorder = new Recorder( inputPoint );";
	
	echo "	zeroGain = audioContext.createGain();";
	echo "	zeroGain.gain.value = 0.0;";
	echo "	inputPoint.connect( zeroGain );";
	echo "	zeroGain.connect( audioContext.destination );";
	echo "	updateAnalysers();";
	echo "}";
	echo "</script>";
	
	
	echo "<script>";
	echo "		function initAudio() {";
	
	/*
	echo "			if (!navigator.getUserMedia)";
	echo "				navigator.getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;";
	echo "			if (!navigator.cancelAnimationFrame)";
	echo "				navigator.cancelAnimationFrame = navigator.webkitCancelAnimationFrame || navigator.mozCancelAnimationFrame;";
	echo "			if (!navigator.requestAnimationFrame)";
	echo "				navigator.requestAnimationFrame = navigator.webkitRequestAnimationFrame || navigator.mozRequestAnimationFrame;";
	*/
	
	echo "			navigator.getUserMedia(";
	echo "				{";
	echo "					'audio': {";
	echo "						'mandatory': {";
	echo "							'googEchoCancellation': 'false',";
	echo "							'googAutoGainControl': 'false',";
	echo "							'googNoiseSuppression': 'false',";
	echo "							'googHighpassFilter': 'false'";
	echo "						},";
	echo "							'optional': []";
	echo "					},";
	echo "				}, gotStream, function(e) {";
	echo "					alert('Error getting audio');";
	echo "					console.log(e);";
	echo "			});";
	echo "		}";
	echo "		window.addEventListener('load', initAudio );";
	echo "</script>";
	
	
	
	echo "<script>";
	echo "	(function(window){
	
		
		var Recorder = function(source, cfg) {
			
			var config = cfg || {};
			var bufferLen = config.bufferLen || 4096;
			this.context = source.context;
			
			if(!this.context.createScriptProcessor){
				this.node = this.context.createJavaScriptNode(bufferLen, 2, 2);
			} else {
				this.node = this.context.createScriptProcessor(bufferLen, 2, 2);
			}
			 
			var worker = new Worker(config.workerPath);
			
			
			worker.postMessage({
				command: 'init',
				config: {
					sampleRate: this.context.sampleRate
				}
			});
			
			
			var recording = false,
			currCallback;
	
			this.node.onaudioprocess = function(e){
				if (!recording) return;
				worker.postMessage({
					command: 'record',
					buffer: [
						e.inputBuffer.getChannelData(0),
						e.inputBuffer.getChannelData(1)
					]
				});
			}
	
			
			this.configure = function(cfg){
				for (var prop in cfg){
					if (cfg.hasOwnProperty(prop)){
						config[prop] = cfg[prop];
					}
				}
			}
	
			
			this.record = function(){
				recording = true;
			}
			
			
			this.stop = function(){
				recording = false;
			}
			
			
			this.clear = function(){
				worker.postMessage({ command: 'clear' });
			}
	
			
			this.getBuffers = function(cb) {
				currCallback = cb || config.callback;
				worker.postMessage({ command: 'getBuffers' })
			}
	
			this.exportWAV = function(cb, type){
				currCallback = cb || config.callback;
				type = type || config.type || 'audio/wav';
				if (!currCallback) throw new Error('Callback not set');
				worker.postMessage({
					command: 'exportWAV',
					type: type
				});
			}
	
			
			this.exportMonoWAV = function(cb, type){
				currCallback = cb || config.callback;
				type = type || config.type || 'audio/wav';
				if (!currCallback) throw new Error('Callback not set');
				worker.postMessage({
					command: 'exportMonoWAV',
					type: type
				});
			}
	
			worker.onmessage = function(e){
				var blob = e.data;
				currCallback(blob);
			}
	
			source.connect(this.node);
			this.node.connect(this.context.destination);   // if the script node is not connected to an output the onaudioprocess event is not triggered in chrome.
		};
	
		window.Recorder = Recorder;
	
	})(window);";
	echo "</script>";
	

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