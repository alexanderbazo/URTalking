<?php

	
	$result = ask_aiml($_POST['request'], $_POST['aiml']);
	
	$pos = strpos($result, 'prolog');
	
	
	if($pos === false) {
		echo $result;
	} else {
		$result = ask_prolog($result);
		if($result == "" || $result == "FALSE") {
			echo "Diese Frage kann ich leider nicht beantworten.";
		} else {
			echo $result;
		}
	}
	
	
	function ask_aiml($request, $aiml_file)
	{
		$result = shell_exec('./python/ask.pl -q "'.$request.'" -a "./python/aiml/'.$aiml_file.'" 2>&1');
		$pos = strpos($result, 'WARNING');
		if($pos === false) {
			return $result;
		} else {
			return "The author of my aiml file did not think of that.";
		}
	
	}
	
	function ask_prolog($request)
	{
		$parts = explode(" ", $request);
		$result = shell_exec('/opt/local/bin/swipl -f ./prolog/facts/mi_iw.pl -g '.$parts[1].' 2>&1');
		return $result;
	}
	
?>