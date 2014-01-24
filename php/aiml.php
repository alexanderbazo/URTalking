<?php
	
	function ask_aiml($request, $aiml_file)
	{
		$result = shell_exec('../python/ask.pl -q "'.$request.'" -a "../python/aiml/'.$aiml_file.'" -s '.session_id().' 2>&1');
		$pos = strpos($result, 'WARNING');
		if($pos === false) {
			return $result;
		} else {
			return "Daran hat der Autor meiner AIML-Datei nicht gedacht. Tut mir leid.";
		}
		
	}
	
?>