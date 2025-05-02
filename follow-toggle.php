<?php

	require "KULSO/config.php";
	header("Content-Type: application/json");

	if (!isset($_COOKIE['userid']) || !isset($_POST['id'])) {
		echo json_encode(["success" => false, "error" => "Missing data"]);
		exit();
	}

	$follower_id = (int)$_COOKIE['userid'];
	$followed_id = (int)$_POST['id'];

	if ($follower_id === $followed_id) {
		echo json_encode(["success" => false, "error" => "Cannot follow yourself"]);
		exit();
	}

	//Ellenőrizzük, követi-e már
	$stmt = $conn->prepare("SELECT 1 FROM follows WHERE follower_id = ? AND followed_id = ?");
	$stmt->bind_param("ii", $follower_id, $followed_id);
	$stmt->execute();
	$stmt->store_result();

	if ($stmt->num_rows > 0) {
		//Már követi = törlöm
		$stmt->close();
		$stmt = $conn->prepare("DELETE FROM follows WHERE follower_id = ? AND followed_id = ?");
		$stmt->bind_param("ii", $follower_id, $followed_id);
		$stmt->execute();
		echo json_encode(["success" => true, "status" => "unfollowed"]);
	} else {
		//Még nem követi = beszúrom
		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO follows (follower_id, followed_id) VALUES (?, ?)");
		$stmt->bind_param("ii", $follower_id, $followed_id);
		$stmt->execute();
		echo json_encode(["success" => true, "status" => "followed"]);
	}
?>
