<?php
		session_start();
		require 'config.php';
		require 'aiml.php';
		require 'prolog.php';
		require 'database.php';
		require 'xml.php';


		$request = $_POST['request'];
		$query = $_POST['query'];


		switch ($request) {
				case 'aiml':
					echo queryAIML($query, $_POST['file']);
					break;
				case 'database':
					echo queryDatabase($query, $_POST['value']);
					break;
				case 'xml':
					echo getAimlXML();
					break;
				case 'update':
					echo updateAiml($_POST['categories']);
				 	break;
				echo $request;
					echo 'Error [Invalid request]';
					break;
		}

?>
