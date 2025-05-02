<?php
	require "KULSO/config.php";

	if (!isset($_COOKIE['userid']) || !isset($_POST['target_id'])) {
		exit('hiba');
	}

	$userid = (int)$_COOKIE['userid'];
	$targetid = (int)$_POST['target_id'];

	if ($userid === $targetid) exit('hiba');

	$action = $_POST['action'] ?? '';

	if ($action === 'follow') {
		$conn->query("INSERT IGNORE INTO follows (follower_id, followed_id) VALUES ($userid, $targetid)");
		echo 'success';
	} elseif ($action === 'unfollow') {
		$conn->query("DELETE FROM follows WHERE follower_id = $userid AND followed_id = $targetid");
		echo 'success';
	} else {
		echo 'hiba';
	}
?>