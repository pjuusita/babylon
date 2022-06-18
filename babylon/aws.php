<?php

	error_reporting(E_ALL ^ E_DEPRECATED);


	require 'aws/aws-autoloader.php';
	//require 'aws/aws-test.php';
	echo "Hello world!" . PHP_EOL;
	
	/*
	$mTurk = new Aws\MTurk\MTurkClient([
			'credentials' => [
					'key'    => 'AKIAUCWJOV3BQCZA5N7X',
					'secret' => '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'
			],
			'version' => 'latest',
			'region'  => 'us-east-1',
			'endpoint' => 'https://mturk-requester-sandbox.us-east-1.amazonaws.com'
			
	]);
	
	$balance = $mTurk->getAccountBalance()->get('AvailableBalance');
	echo "<br>Your available balance is: $" . $balance . PHP_EOL;
	*/
	
	echo "<br>End..." . PHP_EOL;
	
	
?>