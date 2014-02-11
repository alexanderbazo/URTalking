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
		<h1>#Elise: <span class="sub">Ein Chatbot als Studienberater</span></h1>
			<div id="output"></div>
			<input id="input" type="text" name="spind" value="" placeholder="ask and press enter" onkeydown="if (event.keyCode == 13) { processInput(this.value); return false; }">
		<footer>
			Elise ist ein Chatbot, der Studieninteressierte und Studierende bei ihrem Studium an der Universität Regensburg unterstützt. Elise benutzt diese <a href="./python/aiml/uni_regensburg.aiml" class="file_link" target="_new">AIML-Dialogstruktur</a> und diese <a href="./prolog/facts/mi_iw.pl" class="file_link" target="_new">Prolog-Fakten</a>, um Fragen rund um das Studium der Fächer <a href="http://www.iw.uni-regensburg.de" class="web_link" target="_new">Informationswissenschaft</a> und <a href="http://www.mi.uni-regensburg.de" class="web_link" target="_new">Medieninformatik</a> an der <a href="http://www.uni-regensburg.de" class="web_link" target="_new">Universität Regensburg</a> zu beantworten.<br /><br />
				Bei Fragen zu diesem System können Sie sich an <br /><a href="mailto:alexander.bazo@ur.de">Alexander Bazo</a>, <a href="mailto:michaela.schlesinger@stud.uni.regensburg.de">Michaela Schlesinger</a>, <a href="mailto:jessica.rak@stud.uni-regensburg.de">Jessica Rak</a> und <a href="mailto:juergen.reischer@ur.de">Jürgen Reischer</a> wenden.
		</footer>

		<a href="admin.php" id="edit_aiml">Datenbasis bearbeiten</a>
		<a href="about.php" id="beta"></a>

		<div id="course_info" class="hidden">
			<h1><span class="course_nr">36554</span> <span class="title">Prolog</span></h1>
			<span class="info">Lorem ipsum dolor sit</span>
		</div>
	</body>

</html>