<?php
	require 'KULSO/config.php'; //

	if (!isset($_GET['code'])) {
		header("Location: index.php");
		exit();
	}

	$code = $_GET['code'];
	$now = new DateTime();

	$stmt = $conn->prepare("SELECT * FROM codes WHERE code = ?");
	$stmt->bind_param("s", $code);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows === 1) {
		$row = $result->fetch_assoc();
		$userid = $row['userid'];
		$datum = new DateTime($row['datum']);

		$kulonbseg = $datum->diff($now)->days;

		if ($kulonbseg > 14) {
			$deleteCode = $conn->prepare("DELETE FROM codes WHERE code = ?");
			$deleteCode->bind_param("s", $code);
			$deleteCode->execute();

			$deleteUser = $conn->prepare("DELETE FROM users WHERE id = ?");
			$deleteUser->bind_param("i", $userid);
			$deleteUser->execute();

			echo "<script>alert('A megerősítő kód lejárt, a regisztráció törölve lett!'); window.location.href='index.php';</script>";
			exit();
		}

		$updateUser = $conn->prepare("UPDATE users SET confirmed = 1 WHERE id = ?");
		$updateUser->bind_param("i", $userid);
		$updateUser->execute();
		
		
		$deleteCode = $conn->prepare("DELETE FROM codes WHERE code = ?");
		$deleteCode->bind_param("s", $code);
		$deleteCode->execute();
		
		$product_id = "-"; // profilképnél -
		$status = "elfogadva";
		$fajl_neve = "def_user_prof.png";
		
		
		//DEFAULT PROFILKEP
		$stmt = $conn->prepare("INSERT INTO images (product_id, user_id, name, status) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("siss", $product_id, $userid, $fajl_neve, $status);
		$stmt->execute();
		
		setcookie("userid", $userid, time() + 3600, "/");

		header("Location: index.php");
		exit();

	} else {
		echo "<script>alert('Érvénytelen vagy már használt megerősítő kód!'); window.location.href='index.php';</script>";
		exit();
	}
?>