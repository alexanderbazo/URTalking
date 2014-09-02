<?php

	function queryAIML($query, $file) {
		$result = ask_aiml($query, $file);
		$result = str_replace("NEWLINE", "<br />", $result);
		return processAimlResult($result);
	}

	function ask_aiml($request, $aiml_file)
	{
 		$PYTHON_PATH = "python.exe";
		logDialog(time(), session_id(), "human", $request);
		$result = shell_exec($PYTHON_PATH.' ../python/ask.py -q "'.$request.'" -a "../python/aiml/'.$aiml_file.'" -s '.session_id().' -d "../python/dict/" -l "../python/stopwords/german.stop"  2>&1');
		$pos = strpos($result, 'WARNING');
		if($pos === false) {
			logDialog(time(), session_id(), "bot", $result);
			return $result;
		} else {
			$tmp = explode("<span class='debug'>", $result);
			logDialog(time(), session_id(), "bot", "Daran hat der Autor meiner AIML-Datei nicht gedacht. Tut mir leid.");
			return "Daran hat der Autor meiner AIML-Datei nicht gedacht. Tut mir leid. <span class='debug'>".$tmp[1];
		}

	}

	function processAimlResult($result) {
		$prologrequest = strpos($result, 'prolog');

		if($prologrequest === false) {
			return $result;
		} else {
			$pos = strpos($result, '(aimlized query:');
			$tmp = substr($result, $pos, strlen($result)-1);
			$result = substr($result, 0, $pos);
			$query = substr($result, $pos, strlen($result)-1);
			$result = ask_prolog($result);
			if($result == '' || $result == 'FALSE') {
				return 'Diese Frage kann ich so leider nicht beantworten. Tut mir leid. <span class="debug">'.$tmp.'</span>';
			} else {
				$pos = strpos($result, '<span');
				$result = substr($result, $pos, strlen($result)-1);
				return $result.' <span class="debug">'.$tmp.'</span>';
			}
		}
	}

	function logDialog($timestamp, $session, $speaker, $msg) {
		$file = fopen('../logs/'.$session.'.log', "a");
		fwrite($file, $timestamp.",".$session.",".$speaker.",".$msg."\n");
		fclose($file);
	}


?>
