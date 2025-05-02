<?php
	require "KULSO/config.php";

	if(!isset($_COOKIE['userid'])){
		header("Location: index.php");
		exit();
	}

	$felhasznaloid = $_COOKIE['userid'];
	$partner_userid = $_GET['userid'] ?? null;
	$productid = $_GET['productid'] ?? null;

	if(!$partner_userid || !$productid){
		die("Hiányzó adatok.");
	}

	date_default_timezone_set('Europe/Budapest');
	$ido = date("Y-m-d H:i:s");

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
		$message = $conn->real_escape_string($_POST['message']);
		$conn->query("
			INSERT INTO messages (senderid, receiverid, productid, message, created_at)
			VALUES ('$felhasznaloid', '$partner_userid', '$productid', '$message', '$ido')
		");
		header("Location: directmessage.php?productid=$productid&userid=$partner_userid");
		exit();
	}

	$partner_result = $conn->query("SELECT username FROM users WHERE id = '$partner_userid'");
	$partner_name = ($partner_result && $partner_result->num_rows > 0) ? $partner_result->fetch_assoc()['username'] : "Ismeretlen";

	$product_result = $conn->query("SELECT name FROM products WHERE id = '$productid'");
	$product_name = ($product_result && $product_result->num_rows > 0) ? $product_result->fetch_assoc()['name'] : "Ismeretlen termék";
?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<title>Üzenet <?= htmlspecialchars($partner_name) ?> felhasználóval</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/directmessage.css">
	<script src="https://kit.fontawesome.com/3970e10885.js" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
	<div class="chat-header">
		<button id="indexgomb" type="button" class="btn btn-link">
			<a href="messages.php"><i class="fa-solid fa-circle-arrow-left" aria-hidden="true"></i></a>
		</button>
		<div class="chat-title">
			<span>Beszélgetés <?= htmlspecialchars($partner_name) ?> felhasználóval</span><br>
			<small><?= htmlspecialchars($product_name) ?></small>
		</div>
	</div>

	<div class="chat-box" id="chat-box">
		<!-- Az üzenetek ide fognak betöltődni AJAX-szel -->
	</div>

	<form method="POST" class="chat-form">
		<textarea name="message" placeholder="Írd be az üzenetet..." required></textarea>
		<button type="submit">Küldés</button>
	</form>

	<!-- Automatikus üzenet betöltés -->
	<script>
		function loadMessages() {
			$.get("loadmessage.php", {
				userid: <?= json_encode($partner_userid) ?>,
				productid: <?= json_encode($productid) ?>
			}, function(data) {
				const chatBox = $("#chat-box");
				const wasScrolledToBottom = chatBox[0].scrollTop + chatBox[0].clientHeight >= chatBox[0].scrollHeight - 10;

				chatBox.html(data);

				if (wasScrolledToBottom) {
					chatBox.scrollTop(chatBox[0].scrollHeight);
				}
			});
		}

		$(document).ready(function() {
			loadMessages();
			setInterval(loadMessages, 3000);
		});
	</script>
</body>
</html>
