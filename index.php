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
		<div id="logo"></div>
		<h1><span class="sub">#</span>Elise<span class="sub">: <span class="highlight">El</span>ektronisches <span class="highlight">I</span>nformations<span class="highlight">s</span>ystem für <span class="highlight">E</span>rstsemester</span></h1>
			<div id="output"></div>
			<input id="input" type="text" name="spind" value="" placeholder="Stelle eine Frage und drücke die Eingabetaste" onkeydown="if (event.keyCode == 13) { processInput(this.value); return false; }">
		<footer>
			Elise ist ein Chatbot, der Studieninteressierte und Studierende bei ihrem Studium an der Universität Regensburg unterstützt. Elise benutzt eine AIML-Dialogstruktur und Prolog-Fakten, um Fragen rund um das Studium der Fächer <a href="http://www.iw.uni-regensburg.de" class="web_link" target="_new">Informationswissenschaft</a> und <a href="http://www.mi.uni-regensburg.de" class="web_link" target="_new">Medieninformatik</a> an der <a href="http://www.uni-regensburg.de" class="web_link" target="_new">Universität Regensburg</a> zu beantworten.<br /><br />
				Die Dokumentation zu diesem Projekt finden Sie <a href="./documentation.php">hier</a>. Bei Fragen zu diesem System können Sie sich an <br /><a href="mailto:alexander.bazo@ur.de">Alexander Bazo</a>, <a href="mailto:jessica.rak@stud.uni-regensburg.de">Jessica Rak</a>, <a href="mailto:michaela.schlesinger@stud.uni.regensburg.de">Michaela Schlesinger</a> und <a href="mailto:juergen.reischer@ur.de">Jürgen Reischer</a> wenden.
		</footer>

		<a href="admin.php" id="edit_aiml">Datenbasis bearbeiten</a>

		<div id="course_info" class="hidden">
			<h1><span class="course_nr">36554</span> <span class="course_title">Prolog</span></h1>
			<span class="course_info">Kursbeschreibung</span>
			<span class="course_modules">Module</span>

		</div>

		<div class="onoffswitch">
    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" >
    <label class="onoffswitch-label" for="myonoffswitch">
        <span class="onoffswitch-inner"></span>
        <span class="onoffswitch-switch"></span>
    </label>
</div>
	</body>

</html>
