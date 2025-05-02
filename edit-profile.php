<?php

	require "KULSO/config.php";
	require "KULSO/functions.php";
	$current_page = basename($_SERVER['PHP_SELF']);

	if (isset($_COOKIE['userid'])) {
		$userid = (int) $_COOKIE['userid'];

		if (isset($_POST['upload-username'])) {
			$username = $conn->real_escape_string($_POST['username']);

			$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
			$stmt->bind_param("si", $username, $userid);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows == 0) {
				$update = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
				$update->bind_param("si", $username, $userid);
				$update->execute();
			} else {
				echo "<script>alert('Foglalt felhasználónév!')</script>";
			}
		}

		if (isset($_POST['upload-aboutme'])) {
			$aboutme = $conn->real_escape_string($_POST['aboutme']);
			$update = $conn->prepare("UPDATE users SET aboutme = ? WHERE id = ?");
			$update->bind_param("si", $aboutme, $userid);
			$update->execute();
		}

		if (isset($_POST['delete-profile'])) {
			$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
			$stmt->bind_param("i", $userid);
			$stmt->execute();
			$result = $stmt->get_result();
			$user = $result->fetch_assoc();

			$code = generateUniqueCode($conn);
			$datum = date('Y-m-d H:i:s');
			$confirmLink = "https://team08.project.scholaeu.hu/confirm-delete.php?code=$code";

			$insert = $conn->prepare("INSERT INTO codes (userid, code, datum) VALUES (?, ?, ?)");
			$insert->bind_param("iss", $user['id'], $code, $datum);
			$insert->execute();

			$body = '
				<p>Tisztelt ' . htmlspecialchars($user['username']) . '!</p>

				<p>Sajnáljuk, hogy törölni szeretné a profilját. A törlés véglegesítéséhez kérjük, erősítse meg az alábbi gombra kattintva:</p>

				<p style="margin: 20px 0;">
					<a href="' . $confirmLink . '" style="background-color:#6c5ce7; color:white; padding:10px 20px; border-radius:5px; text-decoration:none;">
						Törlés megerősítése
					</a>
				</p>

				<p>Amennyiben nem Ön kezdeményezte a profilja törlését, kérjük, hagyja figyelmen kívül ezt az üzenetet. A fiók törlése csak a megerősítés után történik meg.</p>

				<p>További szép napot kívánunk!<br>
				Üdvözlettel:<br>
				<strong>A CherryT csapata</strong></p>
			';

			sendEmail(
				$user['email'],
				$user['username'],
				'CherryT - Profil törlése',
				$body
			);

			echo "<script>alert('Küldtünk egy megerősítő emailt!')</script>";
			header("Location: login.php");
			exit();
		}

		if (isset($_POST['upload-btn']) && isset($_FILES['uploaded-file'])) {
			// EGYEDI fájlnév létrehozása
			$fajl_eredeti = basename($_FILES['uploaded-file']['name']);
			$fajl_kiterjesztes = pathinfo($fajl_eredeti, PATHINFO_EXTENSION);
			$fajl_neve = time() . "_" . uniqid() . "." . $fajl_kiterjesztes;

			$fajl_tmp_neve = $_FILES['uploaded-file']['tmp_name'];
			$eleresiut = __DIR__ . "/images/" . $fajl_neve;

			if (move_uploaded_file($fajl_tmp_neve, $eleresiut)) {
				$product_id = "-"; // profilkép mindig '-'
				$status = "folyamatban"; // új státusz

				// CSAK UPDATE szükséges
				$update = $conn->prepare("UPDATE images SET name = ?, status = ? WHERE user_id = ? AND product_id = ?");
				$update->bind_param("ssis", $fajl_neve, $status, $userid, $product_id);
				$update->execute();

			} else {
				echo "<script>alert('Hiba a kép feltöltése közben!')</script>";
			}
		}

	} else {
		header("Location: index.php");
		exit();
	}
	
	if(isset($_POST['del-img'])){
		$name = 'def_user_prof.png';
		$productid = '-';
		$status = 'elfogadva';
		
		$update = $conn->prepare("UPDATE images SET name= ? WHERE user_id= ? AND product_id= ?");
        $update->bind_param("sis", $name, $userid, $productid);
        $update->execute();
		
		$update = $conn->prepare("UPDATE images SET status= ? WHERE user_id= ? AND product_id= ?");
        $update->bind_param("sis", $status, $userid, $productid);
        $update->execute();
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
		<title>CherryT | Profilod szerkesztése</title>
		
		<!-- REMIXICONS -->
		<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
		
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">

		<!-- NAV/ALAPOK CSS -->
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/edit-profile.css">
      
</head>
<body> 
	<?php
		if(isset($_COOKIE['userid'])){
			
			/* USER */
			$talalt_felhasznalo = $conn->query("SELECT * FROM users WHERE id=$_COOKIE[userid]");
			$felhasznalo = $talalt_felhasznalo->fetch_assoc();
			
			/* IMAGES */
			$talalt_kep = $conn->query("SELECT * FROM images WHERE user_id=$_COOKIE[userid] AND product_id='-'");
			$kep = $talalt_kep->fetch_assoc();
	?>
	 <header class="header">
			<nav class="nav container">
				<div class="nav-data">
					<a href="index.php" class="nav-logo">
						<img src="images/logo.png" alt="CherryT logó" class="logo-img">
						<span class="logo-text">CherryT</span>
					</a>

					<div class="nav-toggle" id="nav-toggle">
						<i class="ri-menu-line nav-burger"></i>
						<i class="ri-close-line nav-close"></i>
					</div>
				</div>

				<!-- NAV MENU -->
				<div class="nav-menu" id="nav-menu">
					<ul class="nav-list">
						<li><a href="index.php" class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>">Kezdőlap</a></li>
						<li><a href="search.php" class="nav-link <?= $current_page == 'search.php' ? 'active' : '' ?>">Felfedezés</a></li>
						<li><a href="contact.php" class="nav-link <?= $current_page == 'contact.php' ? 'active' : '' ?>">Kapcsolat</a></li>
						<li><a href="information.php" class="nav-link <?= $current_page == 'information.php' ? 'active' : '' ?>">Tudnivalók</a></li>
						<li>
							<a href="notifications.php" class="nav-link">
								<i class="ri-notification-3-line"></i>
							</a>
						</li>

						<!-- PROFILE DROPDOWN -->
						<li class="dropdown-item">
							<div class="nav-link active">
								<?= $felhasznalo['username']; ?> <i class="ri-arrow-down-s-line dropdown-arrow"></i>
							</div>

							<ul class="dropdown-menu">
								<li>
									<a href="profile.php" class="dropdown-link">
										<i class="ri-user-line"></i> Profilod
									</a>
								</li>
								<li>
									<a href="messages.php" class="dropdown-link">
										<i class="ri-message-3-line"></i> Üzeneteid
									</a>
								</li>
								<li>
									<a href="edit-profile.php" class="dropdown-link">
										<i class="ri-settings-5-line"></i> Szerkesztés
									</a>
								</li>
								<li>
									<a href="favorites.php" class="dropdown-link">
										<i class="ri-heart-line"></i> Kedvenceid
									</a>
								</li>
								<li>
									<a href="logout.php" class="dropdown-link">
										<i class="ri-logout-box-r-line"></i> Kijelentkezés
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
		</header>
		<?php }else{
			//WITHOUT LOGIN
			header("Location: index.php");
		}
		?>

		<div class="edit-container">
			<?php
					if($kep['status'] != 'elfogadva'){
				?>
			<div class="profile-section profile-img-container">
				<img src="images/def_user_prof.png" alt="Profilkép">
				<form method="post" action="edit-profile.php" enctype="multipart/form-data">
					<input type="file" name="uploaded-file" required>
					<input type="submit" name="upload-btn" class="btn" value="Kép feltöltése">
				</form>
				<form method="post" action="edit-profile.php">
					<input type="submit" name="del-img" class="btn delete" value="Kép törlése">
				</form>
			</div>
			<?php } else {?>
			<div class="profile-section profile-img-container">
				<img src="images/<?= $kep['name']; ?>" alt="Profilkép">
				<form method="post" action="edit-profile.php" enctype="multipart/form-data">
					<input type="file" name="uploaded-file" required>
					<input type="submit" name="upload-btn" class="btn" value="Kép feltöltése">
				</form>
				<form method="post">
					<input type="submit" name="del-img" class="btn delete" value="Kép törlése">
				</form>
			</div>
			<?php } ?>

			<div class="profile-section">
				<div class="section-title">Felhasználónév</div>
				<form method="post" action="edit-profile.php">
					<input type="text" name="username" value="<?= $felhasznalo['username']; ?>" class="input-field" required>
					<input type="submit" name="upload-username" class="btn" value="Név módosítása">
				</form>
			</div>

			<div class="profile-section">
				<div class="section-title">Bemutatkozás</div>
				<form method="post" action="edit-profile.php">
					<textarea name="aboutme" class="input-field" rows="4" required><?= $felhasznalo['aboutme']; ?></textarea>
					<input type="submit" name="upload-aboutme" class="btn" value="Szerkesztés mentése">
				</form>
			</div>

			<div class="profile-section">
				<form method="post" action="edit-profile.php">
					<input type="submit" name="delete-profile" class="btn delete" value="Profil törlése">
				</form>
			</div>
		</div>
		<!-- FOOTER -->
		<footer class="footer">
			<div class="footer-container">
				<div class="footer-links">
					<a href="index.php" class="footer-link">Kezdőlap</a>
					<a href="search.php" class="footer-link">Felfedezés</a>
					<a href="contact.php" class="footer-link">Kapcsolat</a>
					<a href="information.php" class="footer-link">Tudnivalók</a>
					<a href="privacy-policy.php" class="footer-link">Adatvédelem</a>
					<a href="policies.php" class="footer-link">Felhasználási feltételek</a>
				</div>
				
				<!-- Social media ikonok -->
				<div class="footer-socials">
					<a href="#"><i class="ri-instagram-line"></i></a>
					<a href="#"><i class="ri-facebook-circle-line"></i></a>
				</div>
			</div>
			
			<div class="footer-bottom">
				&copy; <?= date('Y') ?> CherryT - Minden jog fenntartva.
			</div>
		</footer>
		<script src="js/main.js"></script>
</body>
</html>