<?php
	
	function getCourseTitle($course_nr)
	{
		return ask_prolog("coursetitle(".$course_nr."),halt");
	
	}
	
	
?>