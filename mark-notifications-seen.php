<?php

	require "KULSO/config.php";
	if (!isset($_COOKIE['userid'])) exit();
	$userid = intval($_COOKIE['userid']);

	$conn->query("UPDATE notifications SET seen = 1 WHERE toid = $userid AND seen = 0");
?>