<?php
	session_start();
?>
<!DOCTYPE html>

<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bundle.js"></script>
		<link rel="stylesheet" type="text/css" href="css/web.css">
	</head>

	<body onload="init()">
		<h1>#Elies: <span class="sub">A study advisor dreaming of electric sheep</span></h1>
			<div id="output"></div>
			<input id="input" type="text" name="spind" value="" placeholder="ask and press enter" onkeydown="if (event.keyCode == 13) { processInput(this.value); return false; }">
		<footer>
			Elise is great in helping new students. Currently she uses this <a href="./python/aiml/uni_regensburg.aiml" class="file_link" target="_new">AIML database</a> and these <a href="./prolog/facts/mi_iw.pl" class="file_link" target="_new">prolog facts</a> to answer your questions concering the study of media informatics and information sciene at the <a href="http://www.uni-regensburg.de" class="web_link" target="_new">University Regensburg</a>.
		</footer>
		<div id="course_info" class="hidden">
			<h1><span class="course_nr">36554</span> <span class="title">Prolog</span></h1>
			<span class="info">Lorem ipsum dolor sit</span>
		</div>
	</body>

</html>