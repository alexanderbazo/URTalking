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
		$tmp = ask_prolog_politely("coursetitle(".$course_nr."),halt");
		return $tmp;
	
	}
	
	function getCourseModules($course_nr)
	{
		$tmp = ask_prolog_politely("findAllModulesForCourse(".$course_nr.",X),halt");
		return $tmp;
	}
	
	
?>