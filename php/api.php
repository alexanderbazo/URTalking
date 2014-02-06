<?php
		session_start();
		require 'aiml.php';
		require 'prolog.php';
		require 'database.php';


		$request = $_POST['request'];
		$query = $_POST['query'];
		
		
		switch ($request) {
				case "aiml":
					echo queryAIML($query, $_POST['file']);
					break;
				case "database":
					echo queryDatabase($query, $_POST['value']);
					break;
				case "xml":
					echo getAimlXML();
					break;
				default:
					echo "Error [Invalid request]";
					break;
		}
		
		
		function queryAIML($query, $file) {
			$result = ask_aiml($query, $file);
			return processAimlResult($result);
		}
		
		function queryDatabase($query, $value) {
			switch ($query) {
				case "COURSE_TITLE":
					$tmp = getCourseTitle($_POST['value']);
					return $tmp;
					break;
				default;
					break;
			}
		}
		
		
		function processAimlResult($result) {
			$prologrequest = strpos($result, 'prolog');
			
			if($prologrequest === false) {
				echo $result;
			} else {
				$result = ask_prolog($result);
				if($result == "" || $result == "FALSE") {
					return "Diese Frage kann ich so leider nicht beantworten. Tut mir leid.";
				} else {
					return $result;
				}
			}
		}
		
		function getAimlXML() {
			$xml = file_get_contents("../python/aiml/uni_regensburg.aiml");
			return $xml;
		}

?>