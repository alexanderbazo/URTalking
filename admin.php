<?php
	session_start();
	?>
<!DOCTYPE html>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/admin.js"></script>
<link rel="stylesheet" type="text/css" href="css/admin.css">
</head>

<body onload="init()">
<h1>#Elies: Datenansicht</h1>

<div id="file_status" class="file_open"></div>
<div id="save_changes"></div>

<div id="topic_list" class="listbox">
 <h2><span class="heading_prefix">#</span>Themen</h2>
 <ul>
 </ul>
</div>

<div id="category_list" class="listbox">
 <h2><span class="heading_prefix">#</span>Kategorien</h2>
 <ul>
 </ul>
</div>

<div id="editor" class="listbox">
	<h2><span class="heading_prefix">#</span>Editor</h2>
	<input id="pattern" class="single_line" type="text" />
	<span class="input_label">Pattern</span>
	<input id="that" class="single_line" type="text" />
	<span class="input_label">That</span>
</div>

<a href="index.php" id="back_to_chat">Zurück zum Chat</a>

</body>

</html>