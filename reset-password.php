<?php
	require 'KULSO/config.php';

	$code = $_GET['code'] ?? '';
	$hiba = '';
	$siker = false;

	if (!$code) {
		header("Location: index.php");
		exit();
	}

	// Kód ellenőrzése
	$stmt = $conn->prepare("SELECT * FROM codes WHERE code = ?");
	$stmt->bind_param("s", $code);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows === 1) {
		$row = $result->fetch_assoc();
		$userid = $row['userid'];
		$datum = new DateTime($row['datum']);
		$now = new DateTime();
		$kulonbseg = $datum->diff($now)->days;

		if ($kulonbseg > 1) {
			$hiba = "A jelszó-visszaállító link lejárt.";
		} elseif (isset($_POST['set-new-password'])) {
			$pass1 = $_POST['pass1'];
			$pass2 = $_POST['pass2'];

			// Ellenőrzések
			if ($pass1 !== $pass2) {
				echo "<script>alert('A két jelszó nem egyezik meg.')</script>";
			} elseif (strlen($pass1) < 8 || 
					  !preg_match('/[a-z]/', $pass1) ||
					  !preg_match('/[A-Z]/', $pass1) ||
					  !preg_match('/[0-9]/', $pass1) ||
					  !preg_match('/[\W]/', $pass1)) {
				 echo "<script>alert('A jelszónak legalább 8 karakter hosszúnak kell lennie, tartalmaznia kell kis- és nagybetűt, számot és speciális karaktert is.')</script>";
			} else {
				$hashed = password_hash($pass1, PASSWORD_DEFAULT);
				$update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
				$update->bind_param("si", $hashed, $userid);
				$update->execute();

				// Töröljük a kódot
				$delete = $conn->prepare("DELETE FROM codes WHERE code = ?");
				$delete->bind_param("s", $code);
				$delete->execute();

				$siker = true;
			}
		}

	} else {
		echo "<script>alert('Érvénytelen vagy már felhasznált kód.')</script>";
		header("Location:login.php");
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
            <h2>Jelszó visszaállítása</h2>

            <?php if ($siker): ?>
				<p class="success">Sikeresen beállította új jelszavát. <a href="login.php">Bejelentkezés</a></p>
			<?php elseif ($hiba): ?>
				<p class="error"><?= htmlspecialchars($hiba) ?></p>
			<?php else: ?>
				<form method="post" action="reset-password.php?code=<?= $code;?>" id="reset-form">
					<label>Új jelszó:</label>
					<input type="password" name="pass1" id="pass1" required>
					<div id="pass1-feedback" style="font-size: 15px; margin-bottom: 5px;"></div>

					<label>Új jelszó újra:</label>
					<input type="password" name="pass2" id="pass2" required>
					<div id="pass2-feedback" style="font-size: 15px; margin-bottom: 5px;"></div>

					<input type="submit" name="set-new-password" id="reset-btn" value="Jelszó mentése" disabled>
				</form>
			<?php endif; ?>

        </div>
    </div>
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pass1 = document.getElementById('pass1');
    const pass2 = document.getElementById('pass2');
    const pass1Feedback = document.getElementById('pass1-feedback');
    const pass2Feedback = document.getElementById('pass2-feedback');
    const resetButton = document.getElementById('reset-btn');

    function validatePassword(password) {
        const conditions = [
            { condition: password.length >= 8, message: 'A jelszónak legalább 8 karakter hosszúnak kell lennie!' },
            { condition: /[a-z]/.test(password), message: 'Tartalmaznia kell legalább egy kisbetűt!' },
            { condition: /[A-Z]/.test(password), message: 'Tartalmaznia kell legalább egy nagybetűt!' },
            { condition: /[0-9]/.test(password), message: 'Tartalmaznia kell legalább egy számot!' },
            { condition: /[\W_]/.test(password), message: 'Tartalmaznia kell legalább egy speciális karaktert!' }
        ];

        pass1Feedback.innerHTML = '';
        conditions.forEach(({ condition, message }) => {
            const div = document.createElement('div');
            div.textContent = message;
            div.style.color = condition ? 'green' : 'red';
            pass1Feedback.appendChild(div);
        });
    }

    function checkPasswordsMatch() {
        const pass1Value = pass1.value;
        const pass2Value = pass2.value;

        if (!pass1Value || !pass2Value) {
            pass2Feedback.innerHTML = '';
            return false;
        }

        if (pass1Value !== pass2Value) {
            pass2Feedback.innerHTML = 'A két jelszó nem egyezik!';
            pass2Feedback.style.color = 'red';
            return false;
        } else {
            pass2Feedback.innerHTML = 'A két jelszó megegyezik.';
            pass2Feedback.style.color = 'green';
            return true;
        }
    }

    function isPasswordValid(password) {
        return (
            password.length >= 8 &&
            /[a-z]/.test(password) &&
            /[A-Z]/.test(password) &&
            /[0-9]/.test(password) &&
            /[\W_]/.test(password)
        );
    }

    function checkFormValidity() {
        const pass1Val = pass1.value;
        const pass2Val = pass2.value;
        const match = checkPasswordsMatch();
        const valid = isPasswordValid(pass1Val);
        resetButton.disabled = !(pass1Val && pass2Val && match && valid);
    }

    pass1.addEventListener('input', () => {
        validatePassword(pass1.value);
        checkFormValidity();
    });

    pass2.addEventListener('input', checkFormValidity);

    checkFormValidity(); // első betöltéskor lefut
});
</script>

