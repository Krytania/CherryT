<?php
require "KULSO/config.php";
require_once "KULSO/functions.php";

if (isset($_POST['send-contact-btn'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));
    $topic = htmlspecialchars(trim($_POST['topic']));

    // Spam védelem - honeypot mező
	if (!empty($_POST['website'])) {
	   die('Robot gyanús aktivitás.'); 
	}

    if (strlen($message) > 1000) {
        die('Az üzenet túl hosszú. Maximum 1000 karakter engedélyezett.');
    }

    // Email a CherryT adminnak
    $toAdmin = "cherryt.noreply@gmail.com";
    $subjectAdmin = "CherryT | Kapcsolatfelvétel ($topic) - $name";
    $bodyAdmin = "
        <h2>Kapcsolatfelvétel a CherryT oldalról</h2>
        <p><strong>Név:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Téma:</strong> {$topic}</p>
        <p><strong>Üzenet:</strong><br>{$message}</p>
        <br><hr>
        <small>Ez az üzenet a weboldal kapcsolatfelvételi űrlapján keresztül érkezett.</small>
    ";

    // Email visszaigazolás a usernek
    $subjectUser = "CherryT - Üzeneted megérkezett";
    $bodyUser = "
        <h2>Kedves {$name}!</h2>
        <p>Köszönjük, hogy felvetted velünk a kapcsolatot.</p>
        <p>Hamarosan válaszolunk az üzenetedre!</p>
        <br>
        <p><strong>Beküldött adatok:</strong></p>
        <p><strong>Téma:</strong> {$topic}</p>
        <p><strong>Üzenet:</strong> {$message}</p>
        <br>
        <p>Üdvözlettel,<br><strong>CherryT Csapat</strong></p>
    ";

    $sendAdmin = sendEmail($toAdmin, "CherryT Admin", $subjectAdmin, $bodyAdmin);
    $sendUser = sendEmail($email, $name, $subjectUser, $bodyUser);

    if ($sendAdmin === true && $sendUser === true) {
        echo "<script>alert('Sikeresen elküldted az üzeneted!'); window.location.href='contact.php';</script>";
    } else {
        echo "<script>alert('Hiba történt az üzenetküldés során.'); window.location.href='contact.php';</script>";
    }
}

	$current_page = basename($_SERVER['PHP_SELF']);
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
		<title>CherryT | Kapcsolat</title>
		
		<!-- REMIXICONS -->
		<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
		
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">

		<!-- NAV/ALAPOK CSS -->
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/contact.css">	
	</head>
	<body>
	<?php
		if(isset($_COOKIE['userid'])){
			
			$talalt_felhasznalo = $conn->query("SELECT * FROM users WHERE id=$_COOKIE[userid]");
			$felhasznalo = $talalt_felhasznalo->fetch_assoc();
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
							<div class="nav-link">
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
		<?php }else{?>
		
		<!-- HEADER WITHOUT LOGIN -->
		<header class="header">
			<nav class="nav container">
				<div class="nav-data">
					<a href="#" class="nav-logo">
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
						
						<li><a href="login.php" class="nav-link">Bejelentkezés</a></li>
					</ul>
				</div>
			</nav>
		</header>
	<?php }?>
	<!-- CONTACT -->
	<main class="contact-main">
		<section class="contact-container">
			<h1>Kapcsolatfelvétel</h1>

			<form action="contact.php" method="POST" class="contact-form">
				<input type="text" name="name" placeholder="Név" required>
				<input type="email" name="email" placeholder="pelda@email.com" required>

				<select name="topic" required>
					<option value="" disabled selected>Válassz témát</option>
					<option value="Panasz">Panasz</option>
					<option value="Kérdés">Kérdés</option>
					<option value="Egyéb">Egyéb</option>
				</select>

				<textarea name="message" rows="6" maxlength="1000" placeholder="Üzenet (max 1000 karakter)" required></textarea>

				<div class="hidden-honeypot">
					<label for="website">Ne töltsd ki ezt a mezőt!</label>
					<input type="text" name="website" id="website" autocomplete="off">
				</div>

				<input type="submit" name="send-contact-btn" value="Üzenet küldése">
			</form>

			<div class="contact-info">
				<p><i class="ri-map-pin-line"></i> Budapest, Magyarország</p>
				<p><i class="ri-mail-line"></i> cherryt.noreply@gmail.com</p>
				<p><i class="ri-time-line"></i> Hétfő - Péntek: 9:00 - 17:00</p>
			</div>

			<div class="contact-map">
				<iframe 
					src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10768.731651799842!2d19.040235!3d47.497913!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4741dcfa9b0f0a2f%3A0x4fc8b418dd7a36b!2sBudapest!5e0!3m2!1shu!2shu!4v1615994870476!5m2!1shu!2shu" 
					width="100%" 
					height="300" 
					style="border:0;" 
					allowfullscreen="" 
					loading="lazy">
				</iframe>
			</div>
		</section>
	</main>
	<!-- FOOTER -->
		<footer class="footer">
			<div class="footer-container">
				<div class="footer-links">
					<a href="index.php" class="footer-link">Kezdőlap</a>
					<a href="search.php" class="footer-link">Felfedezés</a>
					<a href="contact.php" class="footer-link active">Kapcsolat</a>
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
	
	<!-- MAIN JS -->
	<script src="js/main.js"></script>
	
	</body>
</html>
