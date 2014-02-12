<?php
	session_start();
	?>
<!DOCTYPE html>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/admin.js"></script>
<script type="text/javascript" src="js/jquery.md5.js"></script>

<link rel="stylesheet" type="text/css" href="css/admin.css">
</head>

<body onload="init()">
<h1>#Elise: Datenansicht</h1>

<input type="password" id="password" placeholder="Password"/>

<div id="save_changes"></div>

<div id="topic_list" class="listbox">
 <h2><span class="heading_prefix">#</span>Themen<span class="add" /></h2>
 <ul>
 </ul>
</div>

<div id="category_list" class="listbox">
 <h2><span class="heading_prefix">#</span>Fragen<span class="add" /></h2>
 <ul>
 </ul>
</div>

<div id="editor" class="listbox">
	<h2><span class="heading_prefix">#</span>Editor</h2>
	<input id="pattern" class="single_line" type="text" />
	<span class="input_label">Pattern</span>
	<input id="that" class="single_line" type="text" />
	<span class="input_label">That</span>
	<input type="button" class="button newtemplate" name="delete" value="Neues Template" onclick="addNewTemplateToEditor()"/>
	<input type="button" class="button delete" name="delete" value="Frage löschen" onclick="removeCurrentCategory()"/>
</div>

<div id="new_entry">
	<h2><span class="heading_prefix">#</span><span class="type">Thema</span></h2>

<input id="entry" class="single_line" type="text" />
<input type="button" class="button add" name="add" value="Hinzufügen" />
<input type="button" class="button cancel" name="cancel" value="Abbrechen" />
</div>

<div id="upload">
	<h2><span class="heading_prefix">#</span><span class="text">Daten werden aktualisiert und gespeichert ...</span></h2>
	<input type="button" class="button ok" name="ok" value="Zurück zur Datenansicht" onclick="discardUploadDialog()" />
</div>

<div id="loading"></div>

<a href="index.php" id="back_to_chat">Zurück zum Chat</a>
</body>

</html>