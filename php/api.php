<?php
		session_start();
		require 'aiml.php';
		require 'prolog.php';
		require 'database.php';


		$request = $_POST['request'];
		$query = $_POST['query'];
				
		
		switch ($request) {
				case 'aiml':
					echo queryAIML($query, $_POST['file']);
					break;
				case 'database':
					echo queryDatabase($query, $_POST['value']);
					break;
				case 'xml':
					echo getAimlXML();
					break;
				case 'update':
					echo updateAiml($_POST['categories']);
				 	break;
				echo $request;
					echo 'Error [Invalid request]';
					break;
		}
		
		
		function queryAIML($query, $file) {
			$result = ask_aiml($query, $file);
			return processAimlResult($result);
		}
		
		function queryDatabase($query, $value) {
			switch ($query) {
				case 'COURSE_TITLE':
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
				if($result == '' || $result == 'FALSE') {
					return 'Diese Frage kann ich so leider nicht beantworten. Tut mir leid.';
				} else {
					return $result;
				}
			}
		}
		
		function getAimlXML() {
			$xml = file_get_contents('../python/aiml/uni_regensburg.aiml');
			return $xml;
		}
		
		function updateAiml($json) {
			$old_file_path = '../python/aiml/uni_regensburg.aiml';
			$backup_file_path = '../python/aiml/bak_'.time().'_uni_regensburg.aiml';
			$new_file_path = '../python/aiml/'.uniqid().'.aiml';
			
			copy($old_file_path, $backup_file_path);
			
			$file = fopen($new_file_path, 'w');
			fwrite($file, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<aiml>\n");
			echo $xml_header;
			$aiml = $xml_header;
			foreach (json_decode($json, true) as $value) {
				$pattern = strtoupper($value['pattern']);
				preg_replace('/[-?!.,;:]/', '', $pattern);
				
				$that = strtoupper($value['that']);
				preg_replace('/[-?!.,;:]/', '', $that);
				
				
				fwrite($file, "<category topic=\"".$value['topic']."\">\n");
				fwrite($file, "<pattern>".$pattern."</pattern>\n");
				if($value['that'] != '') {
					fwrite($file, "<that>".$that."</that>\n");
				}
				
				
				
				if(count($value['templates']) == 1) {
					fwrite($file, "<template>".$value['templates'][0]."</template>\n");
				} else {
					fwrite($file, "<template>\n<random>\n");
					foreach($value['templates'] as $template) {
						fwrite($file, "<li>".$template."</li>\n");
					}
					fwrite($file, "</random>\n</template>\n");
				}
				fwrite($file, "</category>\n\n");
			}
			fwrite($file, "</aiml>\n");
			fclose($file);

			copy($new_file_path, $old_file_path);
			unlink($new_file_path);

			return 1;
		}

?>