<?php

	session_start();
	$filterId = $_GET["filter_id"];
	$value = $_GET["filter_value"];
	$_SESSION['filter_collapse'][$filterId] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
	
?>