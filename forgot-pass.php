<?php
require 'KULSO/config.php';
require 'KULSO/functions.php';

if (isset($_POST['reset-btn'])) {
    $identifier = $_POST['email']; // lehet e-mail vagy username

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($user['confirmed'] == 1) {
            $userid = $user['id'];
            $username = $user['username'];
            $email = $user['email'];

            $code = generateUniqueCode($conn);
            $datum = date('Y-m-d H:i:s');

            // Token mentése
            $insert = $conn->prepare("INSERT INTO codes (userid, code, datum) VALUES (?, ?, ?)");
            $insert->bind_param("iss", $userid, $code, $datum);
            $insert->execute();

            // E-mail küldés
            $resetLink = "https://team08.project.scholaeu.hu/reset-password.php?code=$code";

            $subject = "CherryT - Jelszó visszaállítása";
            $body = "
                <p>Tisztelt $username!</p>
                <p>Jelszó-visszaállítást kezdeményezett a CherryT fiókjához.</p>
                <p>Ha valóban Ön volt, kérjük, kattintson az alábbi gombra:</p>
                <p style='margin: 20px 0;'>
                    <a href='$resetLink' style='background-color:#6c5ce7; color:white; padding:10px 20px; border-radius:5px; text-decoration:none;'>
                        Jelszó visszaállítása
                    </a>
                </p>
                <p>Ha nem Ön volt, kérjük, hagyja figyelmen kívül ezt az üzenetet.</p>
                <p>Üdvözlettel,<br>CherryT csapata</p>
            ";

            sendEmail($email, $username, $subject, $body);
        }
    }

    // Minden esetben ezt mutatjuk a biztonság érdekében:
    echo "<script>alert('Ha létezik ilyen fiók, elküldtük a jelszó-visszaállító e-mailt.'); window.location='login.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
		<meta charset="utf-8">

		<!-- NE TALALJAK MEG -->
		<meta name="robots" content="noindex, nofollow">

		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Greguricz Korinna, Bodnár Dorina">
		<meta name="description" content="CherryT">
		<meta name="keywords" content="Cserebere, Ingyen elvihető, Ajándék">
		<title>CherryT | Jelszó visszaállítása</title>
		
		<!-- REMIXICONS -->
		<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
		
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">

		<!-- NAV/ALAPOK CSS -->
		<link rel="stylesheet" href="css/styles.css">
		
		<link rel="stylesheet" href="css/auth.css">

      
</head>
	<body> 
	<div class="auth-wrapper">
        <div class="auth-box">
            <h2>Elfelejtett jelszó</h2>
            <form method="post" action="forgot-pass.php">
                <label for="email">Felhasználónév vagy e-mail cím:</label>
                <input type="text" name="email" required>
                <input type="submit" name="reset-btn" value="Jelszó visszaállítása">
            </form>
            <p><a href="login.php">Vissza a bejelentkezéshez</a></p>
        </div>
    </div>
	</body>
</html>