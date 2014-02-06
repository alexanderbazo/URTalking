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
 <h2>Themen</h2>
 <ul>
 </ul>
</div>

<div id="category_list" class="listbox">
 <h2>Kategorien</h2>
 <ul>
 </ul>
</div>

<div id="editor">
	<h2>Editor</h2>
</div>

<a href="index.php" id="back_to_chat">Zurück zum Chat</a>

</body>

</html>