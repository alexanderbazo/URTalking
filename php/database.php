<?php
	
	function queryDatabase($query, $value) {
		switch ($query) {
			case 'COURSE_TITLE':
				$tmp = getCourseTitle($_POST['value']);
				return $tmp;
				break;
			case 'COURSE_MODULE':
				$tmp = getCourseModules($_POST['value']);
				return $tmp;
				break;

			default;
				break;
		}
	}
	
	function getCourseTitle($course_nr)
	{
		echo "coursetitle('".$course_nr."'),halt";
		return ask_prolog("coursetitle('".$course_nr."'),halt");
	
	}
	
	function getCourseModules($course_nr)
	{
		return "XX";
	}
	
	
?>