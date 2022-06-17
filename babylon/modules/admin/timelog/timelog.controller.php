<?php


class TimelogController extends AbstractController {


	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','chosen.jquery.js','prism.js');
	}
	
		
	public function indexAction() {
		//$this->filelistAction();
		$this->registry->template->show('system/error','unknown');
	}


	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	

	public function filelistAction () {
	
		$foundfiles = array();
		$dir = "/home/babelsoftf/domains/babelsoft.fi/upload/logs/user-" . $_SESSION['userID'];
		$files = scandir($dir);
				
		if ($files != null) {
				//echo "<br>Linesnull";
	
			foreach($files as $index => $value) {
				//echo "<br>file - " . $value;
				if (($value == '.') || ($value == 'index.php') || ($value == '..')) {
					//echo "<br>" . $value;
				} else {
					//echo "<br>foundfile - " . $value;
					//$foundfiles[] = $value;
						
					$row = new Row();
					$row->name = $value;
						
					$foundfiles[] = $row;
						
					//echo "<br><a href='" . $value.  "'>" . $value . "</a>";
				}
			}
		}
		$this->registry->files = array_reverse($foundfiles);
		
		$this->registry->template->show('admin/timelog','filelist');
	}	

	
	
	public function analysefileAction () {
	
		$filename = $_GET['id'];
		$filepath = "/home/babelsoftf/domains/babelsoft.fi/upload/logs/user-34/" . $filename;
		
		$previous = null;
		$startstr = null;
		
		$starttime = null;
		$endtime = null;
		
		$timediff = 600;
		echo "<br>Timediff - " . ($timediff/60) . " min";
		echo "<br><br>";
		
		$totaltime = 0;
		
		if ($file = fopen($filepath, "r")) {
			$counter = 0;
			while(!feof($file)) {
				$textperline = fgets($file);
				
				if ($textperline != "") {

					$timestr = substr($textperline, 10, 9);
					//echo "<br>" . $counter . ". " . $textperline . " --- " . $timestr . " --- " . strtotime($timestr);
					//echo "<br>" . $counter . ". -- " . $timestr . " --- " . strtotime($timestr);
					//echo "<br> --- " . $timestr;
						
					if ($previous != null) {
					
						if ($starttime == null) {
					
							$starttime = strtotime($timestr);
							$startstr = $timestr;
								
						} else {
					
							$start = strtotime($previous);
							$end = strtotime($timestr);
							$diff = $end-$start;
							//echo "<br> --- " . $start , " --- " . $end . " --- " . ($diff);
					
							if ($diff < $timediff) {
					
							} else {
								$totaldiff = $start - $starttime;
								echo "<br> --- " . $startstr . " - " . $previous . " ... " . $totaldiff . " - " . ($totaldiff/60);
								$totaltime = $totaltime + $totaldiff;
								//echo "<br> --- total time = " . ($totaltime / 60)  ."min";
								$starttime = null;
							}
						}
							
							
						//$timedif = $end->diff($start);
						//echo "<br> ---- --- " . $timedif;
					} else {
						$starttime = strtotime($timestr);
						$startstr = $timestr;
							
					}
					
					$previous = $timestr;
					$counter++;
				}
				
			}
			fclose($file);
			
			if ($starttime == null) {
				//echo "<br>Null";
			} else {
				$end = strtotime($previous);
				$totaldiff = $end - $starttime;
				echo "<br> --- " . $startstr . " - " . $previous . " ... " . $totaldiff . " - " . ($totaldiff/60);
				$totaltime = $totaltime + $totaldiff;
			}
			echo "<br> --- total time = " . ($totaltime / 60)  ."min";
				
			
		}
		
		
		echo "<br><br> --- finnish.";
		echo "<br> --- total time = " . ($totaltime / 3600) ."h";
		
		$this->registry->template->show('admin/timelog','timelist');
	}
	
	
	
	
}

?>
