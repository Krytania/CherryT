<?php
	require "KULSO/config.php";

	if (!isset($_GET['code'])) {
		die('Hiányzó kód.');
	}

	$code = $_GET['code'];

	// Kód ellenőrzése
	$stmt = $conn->prepare("SELECT * FROM codes WHERE code = ?");
	$stmt->bind_param("s", $code);
	$stmt->execute();
	$result = $stmt->get_result();
	$codeData = $result->fetch_assoc();

	if (!$codeData) {
		die('Érvénytelen vagy nem létező kód.');
	}

	$now = new DateTime();
	$createdAt = new DateTime($codeData['datum']);
	$elapsed = $now->getTimestamp() - $createdAt->getTimestamp();

	if ($elapsed > 3600) {
		$delete = $conn->prepare("DELETE FROM codes WHERE id = ?");
		$delete->bind_param("i", $codeData['id']);
		$delete->execute();

		die('Ez a törlési kód lejárt. Kérlek, kérj új törlési lehetőséget.');
	}

	// Ha érvényes a kód
	$userid = (int)$codeData['userid'];

	//Kapcsolódó adatok törlése
	$conn->query("DELETE FROM images WHERE user_id = $userid");
	$conn->query("DELETE FROM follows WHERE follower_id = $userid OR followed_id = $userid");
	$conn->query("DELETE FROM reviews WHERE reviewer_id = $userid OR reviewed_id = $userid");
	$conn->query("DELETE FROM requests WHERE requester_id = $userid OR requested_id = $userid");
	$conn->query("DELETE FROM messages WHERE senderid = $userid OR receiverid = $userid");
	$conn->query("DELETE FROM favorites WHERE user_id = $userid");
	$conn->query("DELETE FROM notifications WHERE fromid = $userid OR toid = $userid");
	$conn->query("DELETE FROM products WHERE userid = $userid");

	//Felhasználó törlése
	$conn->query("DELETE FROM users WHERE id = $userid");

	//Kód törlése
	$delete = $conn->prepare("DELETE FROM codes WHERE id = ?");
	$delete->bind_param("i", $codeData['id']);
	$delete->execute();

	//Cookie törlése
	setcookie('userid', '', time() - 3600, "/");

	//Sikeres üzenet
	echo "<script>alert('A fiókod sikeresen törölve lett!');</script>";
	echo '<meta http-equiv="refresh" content="0;url=login.php">';
	exit();
?>