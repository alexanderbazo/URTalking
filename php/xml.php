<?php
function getAimlXML() {
	$xml = file_get_contents('../python/aiml/uni_regensburg.aiml');
	return $xml;
}

function updateAiml($json) {
	$passwordhash = $_POST['passwordhash'];
	//if($passwordhash != "d60be3131110017d89bb3b089028a226") {
		//return -1;
	//}

	$old_file_path = '../python/aiml/uni_regensburg.aiml';
	$backup_file_path = '../python/aiml/bak_'.time().'_uni_regensburg.aiml';
	$new_file_path = '../python/aiml/'.uniqid().'.aiml';

	copy($old_file_path, $backup_file_path);

	$file = fopen($new_file_path, 'w');
	fwrite($file, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<aiml>\n");
	#echo $xml_header;
	#$aiml = $xml_header;
	foreach (json_decode($json, true) as $value) {
		$pattern = strtoupper($value['pattern']);
		$pattern = preg_replace('/[-!.,;:]/', '', $pattern);

		$that = strtoupper($value['that']);
		$that = preg_replace('/[-!?.,;:]/', '', $that);

		fwrite($file, "<category topic=\"".$value['topic']."\">\n");
		fwrite($file, "<pattern>".$pattern."</pattern>\n");
		if($value['that'] != '') {
			fwrite($file, "<that>".$that."</that>\n");
		}



		if(count($value['templates']) == 1) {
			$template = $value['templates'][0];
			$pos = strpos($template,'</a>');
			if($pos === false) {
				#$template = htmlspecialchars($template, ENT_QUOTES);
			} else {
				echo "link<br/>";
				$template = htmlspecialchars($template);
				#$template = html_entity_decode($template);
			}
			fwrite($file, "<template>".$template."</template>\n");
		} else {
			fwrite($file, "<template>\n<random>\n");
			foreach($value['templates'] as $template) {
				$pos = strpos($template,'</a>');
				if($pos === false) {
					#$template = htmlspecialchars($template, ENT_QUOTES);
				} else {
					echo "link<br/>";
					$template = htmlspecialchars($template);
					#$template = html_entity_decode($template);
				}
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
