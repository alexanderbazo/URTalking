<?php

	function ask_prolog($request)
	{
		$parts = explode(" ", $request);
		$result = shell_exec('/opt/local/bin/swipl -f ../prolog/facts/mi_iw.pl -g '.$parts[2].' 2>&1');
		if($parts[1] == "courses") {
			return "Folgende Kurse werden angeboten: ".substr($result, 0, -2).".";
		} else if($parts[1] == "modules") {
			return "Folgende Module musst du absolvieren: ".substr($result, 0, -2).".";
		} else {
			return $result;
		}
	}
	
?>