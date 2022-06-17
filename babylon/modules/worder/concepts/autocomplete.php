<?php


	echo "<br>";
	echo "<input id='myinput' list='hugelist'>";
	echo "<datalist id='hugelist'></datalist>";	
	
	echo "<button onclick='jepjep()'>jeejee</button>";
	echo "<script>";
	echo "	function jepjep(event) {";
	echo "		var inputti = document.getElementById('myinput');";
	echo "		alert('jeejee - '+inputti.getAttribute('data'));";
	echo "	}";
	echo "</script>";
	
	echo "<script>";
	echo "	var firsttime = 2;";
	echo "	window.addEventListener('load', function(){";
	echo "		var inputti = document.getElementById('myinput');";
	echo "		inputti.addEventListener('keyup',function(event) {";
	echo "			if (firsttime == 1) {";
	echo "				firsttime = 2;";
	//echo "				console.log('firsttime');";
	echo "			} else {";
	echo "				hinter(event);";
	echo "			}";
	echo "		});";
	echo "		inputti.addEventListener('change',function(event) {";
	echo "			var inputti = document.getElementById('myinput');";
	echo "			var selectedvalue = inputti.value;";
	//echo "			console.log('-- '+selectedvalue);";
	//echo "			console.log('change - '+event);";
	echo "			var hugelist = document.getElementById('hugelist').options;";
	//echo "			console.log('-- '+hugelist.length);";
	echo "			for(var x=0;x<(hugelist.length-1);x++) {";
	echo "				if (hugelist[x].value === selectedvalue) {";
	//echo "					console.log('--'+hugelist[x].getAttribute(\"data\")+' -- selected');";
	//echo "					console.log('--'+hugelist[x].value+' -- selected');";
	echo "					inputti.setAttribute(\"data\",hugelist[x].getAttribute(\"data\"));";
	echo "				} else {";
	//echo "					console.log('--'+hugelist[x].value);";
	echo "				}";
	echo "			}";
	//echo "			hugelist.innerHTML = '';";
	echo "			firsttime = 1;";
	//echo "			var hugelist = this.list;";
	
	echo "		});";
	echo "		window.hinterXHR = new XMLHttpRequest();";
	echo "	});";
	
	echo "	function hinter(event) {";
	//echo "		console.log('eventti');";
	echo "		inputti = event.target;";
	echo "		var hugelist = document.getElementById('hugelist');";
	echo "		var min_characters = 3;";
	echo "		if (inputti.value.length < min_characters) {";
	echo "			return;";
	echo "		} else {";
	echo "			window.hinterXHR.abort();";
	echo "			window.hinterXHR.onreadystatechange = function() {";
	echo "				if (this.readyState == 4 && this.status == 200) {";
	//echo "					console.log('returni ok - '+this.responseText );";
	echo "					hugelist.innerHTML = '';";
	echo "					JSON.parse(this.responseText, (key,value) => {";
	echo "						var option = document.createElement('option');";
	echo "						option.setAttribute('data', key);";
	echo "						option.value = value;";
	echo "						hugelist.appendChild(option);";
	echo "					});";
	echo "				}";
	echo "			};";
	
	//echo "			console.log('ROOTPHP korjattava');";
	// TODO: http osoite väärin, ei varmaan käytetä missään
	echo "			window.hinterXHR.open('GET', 'http://localhost/babylon/" . ROOTPHP . "?rt=worder/concepts/conceptautocomplete&prefix='+inputti.value, true);";
	echo "			window.hinterXHR.send();";
	echo "		}";
	echo "	}";
	echo "</script>";
	
	
	
	
?>