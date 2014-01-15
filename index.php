<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">

		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/elies.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Exo+2:200,400' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="web.css">
	</head>

	<body>
		<h1>#Elies: <span class="sub">A study adviser dreaming of electric sheep</span></h1>
			<div id="output"></div>
			<input id="input" type="text" name="spind" value="" placeholder="ask and press enter" onkeydown="if (event.keyCode == 13) { processInput(this.value); return false; }">
		<footer>
			Elise is great in helping new students. Currently she uses this <a href="./python/aiml/uni_regensburg.aiml" class="file_link" target="_new">AIML database</a> and these <a href="./prolog/facts/mi_iw.pl" class="file_link" target="_new">prolog facts</a> to answer your questions concering the study of media informatics and information sciene at the <a href="http://www.uni-regensburg.de" class="web_link" target="_new">University Regensburg</a>.
		</footer>
	</body>

</html>