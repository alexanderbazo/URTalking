<?php
	
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
	
	function getCourseTitle($course_nr)
	{
		return ask_prolog("coursetitle(".$course_nr."),halt");
	
	}
	
	
?>