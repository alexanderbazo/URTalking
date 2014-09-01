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

	<a id ="logo" href="./index.php"></a>

		<div id="navigation">
		<ul>
			<li><a href="./index.php">Startseite</a></li></li>
			<li><a href="./documentation.php">Dokumentation</a></li>
				<li><a href="./chatbots.php">Chatbot-Übersicht</a></li>
			<li><a href="./literatur.php">Literatur</a></li>
						</br>
			<li><a href="./admin.php">Datenbasis bearbeiten</a></li>
		</br>
			<li><a href="./kontakt.php">Kontakt</a></li>
			<li><a href="./impressum.php">Impressum</a></li>
		</ul>
		</div>

		<h1><span class="sub">#</span>Elise<span class="sub">: 
			<span class="highlight">E</span>lectronic
			<span class="highlight">L</span>inguistic 
			<span class="highlight">I</span>nformation
			<span class="highlight">S</span>ystem for 
			<span class="highlight">E</span>nrollees
			</span>
		</h1>
			

			<div id="output"></div>
		<input id="input" type="text" name="spind" value="" 
		placeholder="Stelle eine Frage und drücke die Eingabetaste" 
		onkeydown="if (event.keyCode == 13) { processInput(this.value); return false; }">
		
		<footer>
			Elise ist ein Chatbot, der Studieninteressierte und Studierende bei ihrem Studium an der Universität Regensburg 
			unterstützt. Elise benutzt eine AIML-Dialogstruktur und Prolog-Fakten, um Fragen rund um das Studium 
			der Fächer <a href="http://www.iw.uni-regensburg.de" class="web_link" target="_new">Informationswissenschaft</a> 
			und <a href="http://www.mi.uni-regensburg.de" class="web_link" target="_new">Medieninformatik</a> an 
			der <a href="http://www.uni-regensburg.de" class="web_link" target="_new">Universität Regensburg</a> 
			zu beantworten.<br /><br />
			

		</footer>

		<a href="admin.php" id="edit_aiml">Datenbasis bearbeiten</a>

		<div id="course_info" class="hidden">
			<h1><span class="course_nr">36554</span> <span class="course_title">Prolog</span></h1>
			<span class="course_info">Kursbeschreibung</span>
			<span class="course_modules">Module</span>

		</div>
	</body>

</html>