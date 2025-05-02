<?php

	require "KULSO/config.php";

	if(!isset($_COOKIE['userid'])){
		header("Location: index.php");
		exit();
	}

	$userid = $_COOKIE['userid'];

	$lekerdezes = "
		SELECT m1.*
		FROM messages m1
		INNER JOIN (
			SELECT MAX(id) as max_id
			FROM messages
			WHERE senderid = '$userid' OR receiverid = '$userid'
			GROUP BY 
				LEAST(senderid, receiverid), 
				GREATEST(senderid, receiverid), 
				productid
		) m2 ON m1.id = m2.max_id
		ORDER BY m1.created_at DESC
	";

	$talalt_uzenetek = $conn->query($lekerdezes);

	if (!$talalt_uzenetek) {
		die("Hiba az üzenetek lekérdezésekor: " . $conn->error);
	}

	echo "<!DOCTYPE html>
	<html lang='hu'>
	<head>
		<meta charset='UTF-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'>
		<title>Üzeneteid | CherryT</title>
		<link rel='stylesheet' href='css/styles.css'>
		<link rel='stylesheet' href='css/messages.css'>
		<style>
			.message-text {
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;
				max-width: 100%;
				display: block;
			}
			.back-button {
				display: inline-block;
				margin: 10px;
				padding: 6px 12px;
				background-color: #eee;
				border-radius: 8px;
				text-decoration: none;
				color: #333;
				font-size: 14px;
				transition: background-color 0.2s ease;
			}
			.back-button:hover {
				background-color: #ddd;
			}
		</style>
	</head>
	<body>
		<div style='text-align:center; margin-top: 20px;'>
			<a href='index.php' class='back-button'>⬅ Vissza a főoldalra</a>
		</div>
		<h2 style='text-align:center; margin: 20px 0;'>Üzeneteid</h2>
		<div class='messages-container'>
	";

	while ($uzenet = $talalt_uzenetek->fetch_assoc()) {
		$termekid = $uzenet['productid'];
		$feladoid = $uzenet['senderid'];
		$cimzettid = $uzenet['receiverid'];
		$ido = $uzenet['created_at'];

		$en_vagyok_a_felado = ($feladoid == $userid);
		$partnerid = $en_vagyok_a_felado ? $cimzettid : $feladoid;

		$partner_lekerdezes = "SELECT username FROM users WHERE id = '$partnerid'";
		$partner_eredmeny = $conn->query($partner_lekerdezes);
		$partnernev = ($partner_eredmeny && $partner_eredmeny->num_rows > 0) ?
			$partner_eredmeny->fetch_assoc()['username'] : "Ismeretlen";

		$termek_lekerdezes = "SELECT name FROM products WHERE id = '$termekid'";
		$termek_eredmeny = $conn->query($termek_lekerdezes);
		$termeknev = ($termek_eredmeny && $termek_eredmeny->num_rows > 0) ?
			$termek_eredmeny->fetch_assoc()['name'] : "Ismeretlen termék";

		echo "
		<div class='message-card'>
			<a href='directmessage.php?productid=$termekid&userid=$partnerid'>
				<div class='message-header'>
					<span class='username'>$partnernev</span>
					<em class='time'>$ido</em>
				</div>
				<div class='message-product'>$termeknev</div>
				<div class='message-text'>";
					if ($en_vagyok_a_felado){
						echo "<strong>Te:</strong> " . htmlspecialchars($uzenet['message']);
					}
					else{
						echo "<strong>$partnernev:</strong> " . htmlspecialchars($uzenet['message']);
					}
		echo "	</div>
			</a>
		</div>";
	}

	echo "</div>
	</body>
	</html>";
?>
