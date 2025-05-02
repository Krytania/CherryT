<?php
	require "KULSO/config.php";
	if(!isset($_COOKIE['userid']) || !isset($_GET['user'])){
		header("Location: all-users.php");
		exit();
	}

	$reviewer_id = (int)$_COOKIE['userid'];
	$reviewed_id = (int)$_GET['user'];
	$errors = [];

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		$rating = (int)$_POST['rating'];
		$comment = trim($_POST['comment']);

		if($rating < 1 || $rating > 5){
			$errors[] = "Érvénytelen értékelés.";
		}

		if(strlen($comment) > 100){
			$errors[] = "A megjegyzés legfeljebb 100 karakter lehet.";
		}

		if(empty($errors)){
			$requestid = rand(1000, 9999);
			$stmt = $conn->prepare("INSERT INTO reviews (requestid, reviewer_id, reviewed_id, rating, comment, created_at)
									VALUES (?, ?, ?, ?, ?, NOW())");
			$stmt->bind_param("iiiis", $requestid, $reviewer_id, $reviewed_id, $rating, $comment);
			$stmt->execute();
			header("Location: all-users.php");
			exit();
		}
	}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="utf-8"/>
<title>Vélemény írása</title>
<link href="css/contact.css" rel="stylesheet"/>
<style>
    .back-button {
        position: absolute;
        top: 20px;
        left: 20px;
        width: 40px;
        height: 40px;
        background-color: #840d46;
        color: white;
        font-size: 20px;
        text-align: center;
        line-height: 40px;
        border-radius: 50%;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        transition: background 0.3s ease;
    }
    .back-button:hover {
        background-color: #6a0c3a;
    }
</style>
</head>
<body>
	<a href="javascript:history.back()" title="Vissza" style="
		position: absolute;
		top: 20px;
		left: 20px;
		width: 40px;
		height: 40px;
		background-color: #840d46;
		color: white;
		font-size: 20px;
		text-align: center;
		line-height: 40px;
		border-radius: 50%;
		text-decoration: none;
		box-shadow: 0 2px 6px rgba(0,0,0,0.2);
		transition: background 0.3s ease;
		z-index: 1000;
	">
		&#8592;
	</a>

<main class="contact-main">
<div class="contact-container">
<h1>Vélemény írása</h1>
<?php if (!empty($errors)): ?>
<div style="color: red; margin-bottom: 20px;">
<?php foreach ($errors as $e): ?>
<p><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>
</div>
<?php endif; ?>
<form class="contact-form" method="POST">
<label for="rating">Értékelés (1-5):</label>
<select id="rating" name="rating" required>
<option value="">Válassz...</option>
<option value="5">⭐⭐⭐⭐⭐</option>
<option value="4">⭐⭐⭐⭐</option>
<option value="3">⭐⭐⭐</option>
<option value="2">⭐⭐</option>
<option value="1">⭐</option>
</select>
<label for="comment">Megjegyzés (max. 100 karakter):</label>
<textarea id="comment" maxlength="100" name="comment" required style="width: 100%; box-sizing: border-box;"></textarea>
<input type="submit" value="Vélemény elküldése"/>
</form>
</div>
</main>
</body>
</html>
