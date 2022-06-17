<?php


class AudioController extends AbstractController {

	
	public function getCSSFiles() {
		return array('testcss.php','menu.css','chosen.css','style2.css','prism.css');
		//return array('menu.css','testcss.php','chosen.css');
		return array();		
	}
	
	
	
	public function getJSFiles() {
		return array('spectogram.js');
		//return array();
		//return array('jquery-3.2.1.min.js','jquery-ui.js','chosen.jquery.js','init.js');
	}
	
	
	public function indexAction() {
		//$this->showeditaudioAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	
	

	public function showwordcaptureAction() {
		
		updateActionPath("Wordcapture");
		$this->registry->languageID = getSessionVar('languageID', 0);
		$this->registry->wordclassID = getSessionVar('wordclassID', 0);
		
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->wordclasses = Table::load('worder_wordclasses',"WHERE GrammarID=" . $_SESSION['grammarID']);
		
		if ($this->registry->languageID == 0) {
			foreach($this->registry->languages as $index => $language) {
				$this->registry->languageID = $language->languageID;
				break;
			}
		}
		if ($this->registry->wordclassID == 0) {
			foreach($this->registry->wordclasses as $index => $wordclass) {
				$this->registry->wordclassID = $wordclass->wordclassID;
				break;
			}
		}
		
		
		// Ladataan wordformsit
		if (($this->registry->languageID > 0) && ($this->registry->wordclassID > 0)) {
			
			$words = Table::load("worder_words", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID . " AND WordclassID=" . $this->registry->wordclassID, false);

			$form = Table::loadRow("worder_wordbaseforms", "WHERE WordclassID=" . $this->registry->wordclassID . " AND GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
			echo "<br>Foundform - " . $form->featurepairs;
			$pairs = explode("|", $form->featurepairs);
			$searchfeature = null;
			foreach($pairs as $index => $pairstr) {
				$pair = explode(":", $pairstr);
				if ($searchfeature == null) {
					$searchfeature = $pair[1];
				} else {
					$searchfeature = $searchfeature . ":" . $pair[1];
				}
			}
			echo "<br>Search - " . $searchfeature;
			if ($searchfeature != null) {
					
				$forms = Table::load("worder_wordforms", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID . " AND WordclassID=" . $this->registry->wordclassID . " AND Features='" . $searchfeature . "'", false);
				foreach($forms as $index => $form) {
					//echo "<br>Form - " . $form->lemma . " - " . $form->wordID;
					if (isset($words[$form->wordID])) {
						$word = $words[$form->wordID];
						$word->formID = $form->rowID;
						$word->form = $form->wordform;
					}
				}
			}
			
			// Ladataan audiofilessit
			$audiofiles = Table::load("worder_audiofiles", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID . " AND WordclassID=" . $this->registry->wordclassID, false);
			foreach($audiofiles as $index => $audiofile) {
				//echo "<br>Audiofile found - " . $audiofile->audiofileID;
				$word = $words[$audiofile->wordID];
				$word->audiofileID = $audiofile->audiofileID;
			}				
			
		} else {
			$words = array();
		}
		
		
		
		
		$this->registry->words = $words;				
		$this->registry->template->show('worder/audio','wordlist');
	}
	
	
	
	
	public function showaudiocaptureAction() {

		if (!isset($_SESSION['languageID'])) $_SESSION['languageID'] = 0;
		if (isset($_GET['languageID'])) {
			$_SESSION['languageID'] = $_GET['languageID'];
		}
		$this->registry->languageID = $_SESSION['languageID'];
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		
		$this->registry->sets = Table::load("worder_sentencesets", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID . " ORDER BY Name");
		if (isset($_SESSION['sentencesetID'])) {
			$this->registry->setID = $_SESSION['sentencesetID'];
			
		} else {
			$this->registry->sentencelinks = Table::load("worder_audiofiles", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
			if (count($this->registry->sentencelinks) == 0) {
			}
		}
				
		if (!isset($_SESSION['sentencesetID'])) $_SESSION['sentencesetID'] = 0;
		$this->registry->setID = $_SESSION['sentencesetID'];
		if (isset($_GET['setID'])) {
			$this->registry->setID = $_GET['setID'];
			$_SESSION['sentencesetID'] = $_GET['setID'];
		}
		
		if ($this->registry->setID == 0) {
			//$this->registry->sentences = Table::load("worder_sentences", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
			$this->registry->sentences = array();
			$finalsentences = array();
		} else {
			$links = Table::load("worder_sentencesetlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $this->registry->setID, false);
			foreach($links as $index => $link) {
				$sentencelist[$link->sentenceID] = $link->sentenceID;
			}
			$tempsentences = Table::loadWhereInArray("worder_sentences", "sentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
			$finalsentences = array();
			foreach($tempsentences as $index => $sentence) {
				$sentence->counter = 0;
				if ($sentence->correctness == 1) {
					$finalsentences[$sentence->sentenceID] = $sentence;
				}
			}
			
			$links = Table::loadWhereInArray("worder_audiofiles", "sentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);

			foreach($links as $index => $link) {
				if (isset($finalsentences[$link->sentenceID])) {
					$sentence = $finalsentences[$link->sentenceID];
					$sentence->counter = $sentence->counter+1;
				}
			}			
			
		}
		
		$this->registry->set = null;
		if ($this->registry->setID > 0) {
			$this->registry->set = $this->registry->sets[$this->registry->setID];
		}
		$this->registry->sentences = $finalsentences;
		$this->registry->template->show('worder/audio','capture');
	}
	
	

	public function showeditaudioAction() {
	
		if (!isset($_SESSION['languageID'])) $_SESSION['languageID'] = 0;
		if (isset($_GET['languageID'])) {
			$_SESSION['languageID'] = $_GET['languageID'];
		}
		$this->registry->languageID = $_SESSION['languageID'];
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
	
		if (!isset($_SESSION['clipsetID'])) $_SESSION['clipsetID'] = 0;
		if (isset($_GET['clipsetID'])) {
			$_SESSION['clipsetID'] = $_GET['clipsetID'];
		}
		$this->registry->clipsetID = $_SESSION['clipsetID'];
		$this->registry->clipsets = Table::load("worder_clipsets", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		$this->registry->sets = Table::load("worder_sentencesets", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID . " ORDER BY Name");
		if (isset($_SESSION['sentencesetID'])) {
			$this->registry->setID = $_SESSION['sentencesetID'];
				
		} else {
			$this->registry->sentencelinks = Table::load("worder_audiofiles", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
			if (count($this->registry->sentencelinks) == 0) {
			}
		}
	
		if (!isset($_SESSION['sentencesetID'])) $_SESSION['sentencesetID'] = 0;
		$this->registry->setID = $_SESSION['sentencesetID'];
		if (isset($_GET['setID'])) {
			$this->registry->setID = $_GET['setID'];
			$_SESSION['sentencesetID'] = $_GET['setID'];
		}
	
		if ($this->registry->setID == 0) {
			//$this->registry->sentences = Table::load("worder_sentences", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
			$this->registry->sentences = array();
			$finalsentences = array();
		} else {
			$links = Table::load("worder_sentencesetlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $this->registry->setID, false);
			foreach($links as $index => $link) {
				$sentencelist[$link->sentenceID] = $link->sentenceID;
			}
			$tempsentences = Table::loadWhereInArray("worder_sentences", "sentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
			$finalsentences = array();
			foreach($tempsentences as $index => $sentence) {
				$sentence->counter = 0;
				if ($sentence->correctness == 1) {
					$finalsentences[$sentence->sentenceID] = $sentence;
				}
			}
				
			$links = Table::loadWhereInArray("worder_audiofiles", "sentenceID", $sentencelist, "WHERE GrammarID=" . $_SESSION['grammarID']);
	
			foreach($links as $index => $link) {
				if (isset($finalsentences[$link->sentenceID])) {
					$sentence = $finalsentences[$link->sentenceID];
					$sentence->counter = $sentence->counter+1;
				}
			}
		}
		
		
		$clips = array();
		if ($this->registry->clipsetID == 0) {
			//$this->registry->sentences = Table::load("worder_sentences", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID);
			$this->registry->clips = array();
			$finalsentences = array();
		} else {
			$links = Table::load("worder_clipsetlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND SetID=" . $this->registry->clipsetID, false);
			$cliplist = array();
			foreach($links as $index => $link) {
				$cliplist[$link->clipID] = $link->clipID;
			}
			$clips = Table::loadWhereInArray("worder_audioclips", "clipID", $cliplist, "WHERE GrammarID=" . $_SESSION['grammarID']);
		}
		$this->registry->clips = $clips;
		
		$this->registry->set = null;
		if ($this->registry->setID > 0) {
			$this->registry->set = $this->registry->sets[$this->registry->setID];
		}
		$this->registry->sentences = $finalsentences;
		$this->registry->template->show('worder/audio','editaudio');
	}
	
	
	public function showsynthesizerAction() {
		echo "<br>Not implemented";
	}
	
	
	public function index2Action() {
	
		$this->registry->template->show('worder/audio','audio2');
	
	}
	
	
	
	public function showsentencesAction() {
		
		echo "<br>ShowSentences not implemented";
	}
	
	
	public function showclipsAction() {
		echo "<br>showclips not implemented";
	}
	
	
	
	public function showclipsetsAction() {
		
		if (!isModuleSessionVarSetted('languageID')) {
			$languageID = 0;
			foreach($this->registry->languages as $index => $language) {
				$languageID = $language->languageID;
				break;
			}
			setModuleSessionVar('languageID',$languageID);
			$this->registry->languageID = $languageID;
		} else {
			$this->registry->languageID = getModuleSessionVar('languageID', 0);
		}
		updateActionPath("ClipSets");
		
		$this->registry->languages = Table::load("worder_languages", "WHERE GrammarID=" . $_SESSION['grammarID']);
		$this->registry->clipsets = Table::load("worder_clipsets", "WHERE GrammarID=" . $_SESSION['grammarID']);
		
		if ($this->registry->languageID == 0) {
			$this->registry->sets = Table::load("worder_clipsets", "WHERE GrammarID=" . $_SESSION['grammarID']);
		} else {
			$this->registry->sets = Table::loadHierarchy('worder_clipsets','parentID', "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND LanguageID=" . $this->registry->languageID . " ORDER BY Name DESC", false, true);
			//$this->registry->sets = Table::load("worder_sentencesets", "WHERE LanguageID=" . $this->registry->languageID . " AND GrammarID=" . $_SESSION['grammarID']);
		}
		$this->registry->template->show('worder/audio','clipsets');
	}



	public function insertclipsetAction() {
	
		$languageID = $_GET['languageID'];
		$name =  $_GET['name'];
		
		if (isset($_GET['userID'])) {
			$userID = $_GET['userID'];
		} else {
			$userID = $_SESSION['userID'];
		}
		
		$values = array();
		$values['Name'] = $name;
		$values['GrammarID'] = $_SESSION['grammarID'];
		$values['LanguageID'] = $languageID;
		$values['UserID'] = $languageID;
		$setID = Table::addRow("worder_clipsets", $values, false);
	
		redirecttotal('worder/audio/showclipsets', null);
	}
	
	
	

	public function getaudiofilesJSONAction() {
	
		$grammarID = $_SESSION['grammarID'];
		$sentenceID = $_GET['sentenceID'];
	
		$links = Table::load("worder_audiofiles", "WHERE SentenceID=" . $sentenceID . " AND GrammarID=" . $_SESSION['grammarID']);
		
	
		echo " [";
		
		$first = true;
		foreach($links as $index => $link) {
			if ($first == true) {
				$first = false;
			} else {
				echo ",";
			}
			
			echo "{";
			echo "\"linkID\":\"" . $link->audiofileID . "\",";
			echo "\"sentenceID\":\"" . $link->sentenceID . "\",";
			echo "\"userID\":\"" . $link->userID . "\",";
			echo "\"fileID\":\"" . $link->fileID . "\"";
			echo "}";
		}
		echo "]";
	}
	
	
	
	public function downloadaudiofileAction() {
	
		$comments = false;
		$systemID = $_SESSION['systemID'];
			
		if (isset($_GET['linkID'])) {
			$linkID = $_GET['linkID'];
		} else {
			echo "<br>No audiofileID";
			exit;
		}
		$grammarID = $_SESSION['grammarID'];
		$audiolink = Table::loadRow('worder_audiofiles',$linkID);
		$path = SAVEROOT . "worder/grammar-" . $grammarID . "/words/" . $audiolink->fileID . ".wav";
		header("Content-type:application/wav");
		header("Content-Disposition:inline;filename=" . $audiolink->fileID. ".wav");
		readfile($path);
	}
	
	


	public function downloadwordaudiofileAction() {
	
		$comments = false;
		$systemID = $_SESSION['systemID'];
			
		if (isset($_GET['wordID'])) {
			$wordID = $_GET['wordID'];
		} else {
			echo "<br>No audiofile wordID";
			exit;
		}
		if (isset($_GET['formID'])) {
			$fromID = $_GET['formID'];
		} else {
			echo "<br>No audiofile formID";
			exit;
		}
		$grammarID = $_SESSION['grammarID'];
		$audiolink = Table::loadRow('worder_audiofiles',"WHERE");
		$path = SAVEROOT . "worder/grammar-" . $grammarID . "/sentences/" . $audiolink->fileID . ".wav";
		header("Content-type:application/wav");
		header("Content-Disposition:inline;filename=" . $audiolink->fileID. ".wav");
		readfile($path);
	}
	
	
	// Tsekataan onko parametrina annetulla sentenceID:llä linkitettynä yhteenkään clippiin, mikäli on
	// niin palautetaan clipin nimi ja clippisetti.
	public function hasclipsAction() {
		
	}
	
	
	
	public function removeclipJSONAction() {
		
		$clipID = $_GET['clipID'];
		
		
		//echo "[";
		/*
		echo " {";
		echo "	  \"removesuccess\":\"1\",";
		echo "	  \"removed\":\"" . $clipID . "\"";
		echo " }\n";
		*/
		//echo "]";
		
		$link = Table::loadRow("worder_audioclips", $clipID);
			
		$success = Table::deleteRowsWhere("worder_clipsetlinks", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ClipID=" . $clipID);
		$success = Table::deleteRow("worder_audioclips", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ClipID=" . $clipID);
		
		$grammarID = $_SESSION['grammarID'];
		$filename = SAVEROOT . "worder/grammar-" . $grammarID . "/clips/" . $link->fileID . ".wav";
			
		if(file_exists($filename)){
			//echo "<br>File: " . $filename;
			$success = unlink($filename);
			if ($success) {
				echo " {";
				echo "	  \"success\":\"1\",";
				echo "	  \"removed\":\"" . $clipID . "\"";
				echo " }\n";
			} else {
				echo " {";
				echo "	  \"success\":\"0\",";
				echo "	  \"message\":\"remove file failed.\"";
				echo " }\n";
			}
		}
	}
	
	
	
	
	
	public function removesentencefileJSONAction() {
	
		$audiofileID = $_GET['audiofileID'];
	
		$clips = Table::load("worder_audioclips", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND AudiofileID=" . $audiofileID);

		if (count($clips)>0) {
			
			$sets = Table::load("worder_clipsets", "WHERE GrammarID=" . $_SESSION['grammarID']);

			$cliplist = array();
			foreach($clips as $index => $clip) {
				$cliplist[$clip->clipID] = $clip->clipID;
			}
			$links = Table::loadWhereInArray("worder_clipsetlinks", "clipID", $cliplist, "WHERE GrammarID=" . $_SESSION['grammarID']);
			
			echo " {";
			echo "	  \"success\":\"0\",";
			echo "	  \"links\": [";
			
			$first = true;
			foreach($links as $index => $link) {
				if ($first) $first = false;
				else echo ",";
				
				$set = $sets[$link->setID];
				$clip = $clips[$link->clipID];
				echo " {";
				echo "	  \"setID\":\"" . $link->setID . "\",";
				echo "	  \"setname\":\"" . $set->name . "\",";
				echo "	  \"clipID\":\"" . $link->clipID . "\"";
				echo "	  \"clipfile\":\"" . $clip->fileID . "\"";
				echo " }\n";
			}
			echo " ]\n";
			echo " }\n";
				
		} else {
			
			// Remove worder_audiofiles row
			// Remove file from server
			
			$link = Table::loadRow("worder_audiofiles", $audiofileID);
			
			$success = Table::deleteRow("worder_audiofiles", $audiofileID);
				
			if ($success == 1) {
				
				$grammarID = $_SESSION['grammarID'];
				$filename = SAVEROOT . "worder/grammar-" . $grammarID . "/sentences/" . $link->fileID . ".wav";
					
				if(file_exists($filename)){
					//echo "<br>File: " . $filename;
					$success = unlink($filename);
					if ($success) {
						echo " {";
						echo "	  \"success\":\"1\",";
						echo "	  \"removed\":\"" . $audiofileID . "\"";
						echo " }\n";
					} else {
						echo " {";
						echo "	  \"success\":\"0\",";
						echo "	  \"message\":\"remove file failed.\"";
						echo " }\n";
					}
				} else {
					echo " {";
					echo "	  \"success\":\"0\",";
					echo "	  \"message\":\"remove file failed, file not exists. (" . $filename . ")\"";
					echo " }\n";
				}
			} else {
				echo " {";
				echo "	  \"success\":\"0\",";
				echo "	  \"message\":\"remove file failed. DB remove failed.\"";
				echo " }\n";
			}
		}
	}
	
	
	


	public function removewordaudiofileAction() {
	
		$audiofileID = $_GET['audiofileID'];
	
		$clips = Table::load("worder_audioclips", "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND AudiofileID=" . $audiofileID);
	
		if (count($clips) > 0) {
				
			// Ilmeisesti tämä blocki on sitävarten, että audiofileä, josta on tehty clippejä
			// ei voida poistaa lainkaan. Ei nyt oikein hyvin muistikuvaa miten noi klipit toimii
			// ja miksi tämä esto tarvitana.
			
			$sets = Table::load("worder_clipsets", "WHERE GrammarID=" . $_SESSION['grammarID']);
	
			$cliplist = array();
			foreach($clips as $index => $clip) {
				$cliplist[$clip->clipID] = $clip->clipID;
			}
			$links = Table::loadWhereInArray("worder_clipsetlinks", "clipID", $cliplist, "WHERE GrammarID=" . $_SESSION['grammarID']);
				
			echo " {";
			echo "	  \"success\":\"0\",";
			echo "	  \"links\": [";
				
			$first = true;
			foreach($links as $index => $link) {
				if ($first) $first = false;
				else echo ",";
	
				$set = $sets[$link->setID];
				$clip = $clips[$link->clipID];
				echo " {";
				echo "	  \"setID\":\"" . $link->setID . "\",";
				echo "	  \"setname\":\"" . $set->name . "\",";
				echo "	  \"clipID\":\"" . $link->clipID . "\"";
				echo "	  \"clipfile\":\"" . $clip->fileID . "\"";
				echo " }\n";
			}
			echo " ]\n";
			echo " }\n";
	
		} else {
				
			// Remove worder_audiofiles row
			// Remove file from server
				
			$link = Table::loadRow("worder_audiofiles", $audiofileID);
				
			$success = Table::deleteRow("worder_audiofiles", $audiofileID);
	
			if ($success == 1) {
	
				$grammarID = $_SESSION['grammarID'];
				$filename = SAVEROOT . "worder/grammar-" . $grammarID . "/words/" . $link->fileID . ".wav";
					
				if(file_exists($filename)){
					//echo "<br>File: " . $filename;
					$success = unlink($filename);
					if ($success) {
						echo " {";
						echo "	  \"success\":\"1\",";
						echo "	  \"removed\":\"" . $audiofileID . "\"";
						echo " }\n";
					} else {
						echo " {";
						echo "	  \"success\":\"0\",";
						echo "	  \"message\":\"remove file failed d2 - " . $link->fileID .  ".\"";
						echo " }\n";
					}
				} else {
					echo " {";
					echo "	  \"success\":\"0\",";
					echo "	  \"message\":\"remove file failed, file not exists. (" . $filename . ")\"";
					echo " }\n";
				}
			} else {
				echo " {";
				echo "	  \"success\":\"0\",";
				echo "	  \"message\":\"remove file failed. DB remove failed.\"";
				echo " }\n";
			}
		}
	}
	
	
	
	
	public function downloadclipfileAction() {
	
		$comments = false;
		$systemID = $_SESSION['systemID'];
			
		$clipID = $_GET['clipID'];
		$grammarID = $_SESSION['grammarID'];
	
		$cliplink = Table::loadRow('worder_audioclips',$clipID);
		$path = SAVEROOT . "worder/grammar-" . $grammarID . "/clips/" . $cliplink->fileID . ".wav";
		header("Content-type:application/wav");
		header("Content-Disposition:inline;filename=" . $cliplink->fileID. ".wav");
		readfile($path);
	}
	
	
	
	public function uploadaudioAction() {
		
		$randi =  mt_rand(10000000,99999999);
		
		$sentenceID = $_POST['sentenceID'];
		//echo "--SentenceID - " . $sentenceID;
		//echo "--";
		$sentence = Table::loadRow('worder_sentences',$sentenceID);
		
		$grammarID = $_SESSION['grammarID'];
		$randi = $this->getNextFileNumber($grammarID, $sentence->languageID);
		
		$path = SAVEROOT . "worder/grammar-" . $grammarID . "/sentences/" . $randi . ".wav";
		
		//print_r($_FILES);
		//this will print out the received name, temp name, type, size, etc.
		$input = $_FILES['audio_data']['tmp_name']; //get the temporary name that PHP gave to the uploaded file
		$output = $_FILES['audio_data']['name'].".wav"; //letting the client control the filename is a rather bad idea
		//move the file from temp name to local folder using $output name
		move_uploaded_file($input, $path);
		
		$values = array();
		$values['SentenceID'] = $sentenceID;
		$values['UserID'] = $_SESSION['userID'];
		$values['GrammarID'] = $grammarID;
		$values['FileID'] = $randi;
		$values['Filepath'] = "";
		$values['LanguageID'] = $sentence->languageID;
		$success = Table::addRow("worder_audiofiles", $values, false);
		
		echo "" . $randi;

		return;
	}
	
	

	public function uploadwordaudioAction() {
	
		$randi =  mt_rand(10000000,99999999);
	
		$wordID = $_POST['wordID'];
		$formID = $_POST['formID'];
		$word = Table::loadRow('worder_words',$wordID);
		
		
		$grammarID = $_SESSION['grammarID'];
		$randi = $this->getNextFileNumber($grammarID, $word->languageID);
		$path = SAVEROOT . "worder/grammar-" . $grammarID . "/words/" . $randi . ".wav";
		
		//print_r($_FILES);
		//this will print out the received name, temp name, type, size, etc.
		$input = $_FILES['audio_data']['tmp_name']; //get the temporary name that PHP gave to the uploaded file
		$output = $_FILES['audio_data']['name'].".wav"; //letting the client control the filename is a rather bad idea
		//move the file from temp name to local folder using $output name
		move_uploaded_file($input, $path);
	
		$values = array();
		$values['UserID'] = $_SESSION['userID'];
		$values['GrammarID'] = $grammarID;
		$values['FileID'] = $randi;
		$values['Filepath'] = "";
		$values['LanguageID'] = $word->languageID;
		$values['SentenceID'] = 0;
		$values['WordformID'] = $formID;
		$values['WordID'] = $word->wordID;
		$values['WordclassID'] = $word->wordclassID;
		$success = Table::addRow("worder_audiofiles", $values, false);
	
		echo "" . $success;
	
		return;
	}
	
	
	
	public function updateclipJSONAction() {
		
		$clipID = $_GET['clipID'];
		
		$values = array();
		if (isset($_GET['name'])) {
			$values['Name'] = $_GET['name'];
		}
		if (isset($_GET['endpos'])) {
			$values['Endpos'] = $_GET['endpos'];
		}
		if (isset($_GET['startpos'])) {
			$values['Startpos'] = $_GET['startpos'];
		}
		
		if (count($values) > 0) {
			$success = Table::updateRow('worder_audioclips', $values, "WHERE GrammarID=" . $_SESSION['grammarID'] . " AND ClipID=" . $clipID);
			echo "5";
		} else {
			echo "4";
		}
	}
	

	public function uploadclipAction() {
	
		$randi =  mt_rand(10000000,99999999);
	
		$sentenceID = $_POST['sentenceID'];
		$sentence = Table::loadRow('worder_sentences',$sentenceID);
	
		$grammarID = $_SESSION['grammarID'];
		$start = 0;
		if (isset($_POST['startpos'])) $start = $_POST['startpos'];
		$end = 0;
		if (isset($_POST['endpos'])) $end = $_POST['endpos'];
		$audiofileID = 0;
		if (isset($_POST['audiofileID'])) $audiofileID = $_POST['audiofileID'];
		
		$fileID = $this->getNextClipNumber($grammarID, $sentence->languageID);
		$path = SAVEROOT . "worder/grammar-" . $grammarID . "/clips/" . $fileID . ".wav";
		
		//print_r($_FILES);
		//this will print out the received name, temp name, type, size, etc.
		$input = $_FILES['audio_data']['tmp_name']; //get the temporary name that PHP gave to the uploaded file
		$output = $_FILES['audio_data']['name'].".wav"; //letting the client control the filename is a rather bad idea
		//move the file from temp name to local folder using $output name
		move_uploaded_file($input, $path);
	
		$values = array();
		$values['SentenceID'] = $sentenceID;
		$values['UserID'] = $_SESSION['userID'];
		$values['GrammarID'] = $grammarID;
		$values['FileID'] = $fileID;
		$values['AudiofileID'] = $audiofileID;
		$values['Filepath'] = "";
		$values['LanguageID'] = $sentence->languageID;
		$clipID = Table::addRow("worder_audioclips", $values, false);
	
		$clipsetID = $_SESSION['clipsetID'];
		$values = array();
		$values['SetID'] = $clipsetID;
		$values['ClipID'] = $clipID;
		$values['GrammarID'] = $grammarID;
		$values['LanguageID'] = $sentence->languageID;
		$values['UserID'] = $_SESSION['userID'];
		$linkID = Table::addRow("worder_clipsetlinks", $values, false);
		
		echo "" . $clipID;
		return;
	}
	
	
	private function getNextFileNumber($grammarID, $languageID) {
	
		global $mysqli;
	
		$sql = "SELECT * FROM worder_audiofiles WHERE FileID=(SELECT max(FileID) FROM worder_audiofiles WHERE GrammarID=" . $grammarID . " AND LanguageID=" . $languageID . ")";
		//echo "<br>sql - " . $sql;
		$result = $mysqli->query($sql);
		
		$row = $result->fetch_array();
		if ($row == null) {
			return "1000000";
		}
		
		$fileID = $row['FileID'] + 1;
		if ($fileID < 1000000) {
			$fileID = "1000000";
		}
		return $fileID;
	}
	
	
	private function getNextClipNumber($grammarID, $languageID) {
	
		global $mysqli;
	
		$sql = "SELECT max(FileID) as Maxi FROM worder_audioclips WHERE GrammarID=" . $grammarID;
		$result = $mysqli->query($sql);
	
		$row = $result->fetch_array();
		if ($row == null) {
			return "1000000";
		}
	
		$fileID = $row['Maxi'] + 1;
		if ($fileID < 1000000) {
			$fileID = "1000000";
		}
		return $fileID;
	}
	
	
	

}
