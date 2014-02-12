<?php
	
	function queryAIML($query, $file) {
		$result = ask_aiml($query, $file);
		return processAimlResult($result);
	}
	
	function ask_aiml($request, $aiml_file)
	{
		$result = shell_exec('../python/ask.py -q "'.$request.'" -a "../python/aiml/'.$aiml_file.'" -s '.session_id().' 2>&1');
		$pos = strpos($result, 'WARNING');
		if($pos === false) {
			return $result;
		} else {
			return "Daran hat der Autor meiner AIML-Datei nicht gedacht. Tut mir leid.";
		}
		
	}
	
	function processAimlResult($result) {
		$prologrequest = strpos($result, 'prolog');
		
		if($prologrequest === false) {
			echo $result;
		} else {
			$result = ask_prolog($result);
			if($result == '' || $result == 'FALSE') {
				return 'Diese Frage kann ich so leider nicht beantworten. Tut mir leid.';
			} else {
				return $result;
			}
		}
	}

	
?>