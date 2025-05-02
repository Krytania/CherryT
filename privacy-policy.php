<?php
	require "KULSO/config.php";
?>
<!DOCTYPE html>
<html lang="hu">
	<head>
		<meta charset="utf-8">
		<meta name="robots" content="noindex, nofollow">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Greguricz Korinna, Bodnár Dorina">
		<meta name="description" content="CherryT">
		<meta name="keywords" content="Cserebere, Ingyen elvihető, Ajándék">
		<title>CherryT | Adatvédelmi szabályzat</title>
		<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/privacy.css">
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
		<?php } ?>
		<main class="policies-container">
		<h1>Adatvédelmi Szabályzat</h1>
		<p><strong>Hatályos: 2025. május 1.</strong></p>
		<hr>
		
		<h2>1. Bevezetés</h2>
		<p>
			A CherryT (továbbiakban: „Szolgáltató”) kiemelten fontosnak tartja a felhasználók személyes adatainak védelmét.
			Ez az Adatvédelmi Szabályzat részletesen ismerteti, hogyan kezeljük, védjük és tároljuk a felhasználói adatokat a <a href="https://team08.project.scholaeu.hu/" target="_blank">https://team08.project.scholaeu.hu</a> weboldalon keresztül.
		</p>

		<h2>2. Az adatkezelő adatai</h2>
		<ul>
			<li><strong>Név:</strong> CherryT</li>
			<li><strong>E-mail:</strong> <a href="mailto:cherryt.noreply@gmail.com">cherryt.noreply@gmail.com</a></li>
			<li><strong>Weboldal:</strong> <a href="https://team08.project.scholaeu.hu/" target="_blank">team08.project.scholaeu.hu</a></li>
		</ul>

		<h2>3. Milyen adatokat gyűjtünk?</h2>
		<ul>
			<li>Felhasználónév, e-mail cím, jelszó (titkosítva)</li>
			<li>Profilkép</li>
			<li>Bemutatkozás („Rólam”)</li>
			<li>IP-cím, böngészőinformációk</li>
			<li>Tevékenységi adatok (követések, kedvencek, üzenetek)</li>
			<li>Cookie adatok</li>
		</ul>

		<h2>4. Az adatkezelés célja</h2>
		<ul>
			<li>Felhasználói fiókok kezelése</li>
			<li>Funkcionalitás biztosítása (pl. követés, üzenetek)</li>
			<li>Felhasználói élmény javítása</li>
			<li>Jogszabályi kötelezettségek teljesítése</li>
			<li>Biztonsági incidensek megelőzése</li>
		</ul>

		<h2>5. Az adatok megőrzésének időtartama</h2>
		<p>Az adatokat mindaddig tároljuk, amíg:</p>
		<ul>
			<li>a felhasználó fiókja aktív,</li>
			<li>a felhasználó nem kéri az adatai törlését,</li>
			<li>vagy a jogi kötelezettség fennáll (pl. számviteli okokból).</li>
		</ul>

		<h2>6. Hozzáférés az adatokhoz</h2>
		<ul>
			<li>Szolgáltató (adminisztrátorok)</li>
			<li>Tárhelyszolgáltató</li>
			<li>Hatóságok (jogszabályi kötelezettség esetén)</li>
		</ul>
		<p>Az adatok nem kerülnek értékesítésre és nem használjuk őket reklámozásra harmadik fél számára.</p>

		<h2>7. Felhasználói jogok</h2>
		<p>A felhasználó jogosult:</p>
		<ul>
			<li>adataihoz hozzáférni,</li>
			<li>azokat helyesbíteni,</li>
			<li>törölni (elfeledtetés joga),</li>
			<li>tiltakozni az adatkezelés ellen,</li>
			<li>adathordozhatóságra.</li>
		</ul>
		<p>Ezek a jogok az <a href="mailto:cherryt.noreply@gmail.com">cherryt.noreply@gmail.com</a> címen gyakorolhatók.</p>

		<h2>8. Cookie-k</h2>
		<p>
			Az oldal cookie-kat használ technikai, analitikai és felhasználói kényelmi célokra.
			A cookie-k használatához hozzájárulást kérünk az első látogatáskor.
		</p>

		<h2>9. Adatbiztonság</h2>
		<p>Az adatok védelmére külső és belső technikai és szervezési intézkedéseket alkalmazunk:</p>
		<ul>
			<li>HTTPS kapcsolat</li>
			<li>Titkosított jelszavak (bcrypt)</li>
			<li>Jogosultságkezelés</li>
		</ul>

		<h2>10. Kapcsolat</h2>
		<p>Ha kérdésed van vagy szeretnéd gyakorolni jogaidat, fordulj hozzánk bizalommal:</p>
		<p><strong>E-mail:</strong> <a href="mailto:cherryt.noreply@gmail.com">cherryt.noreply@gmail.com</a></p>

		<hr>
		<p><strong>CherryT csapata</strong></p>
	</main>
		<!-- FOOTER -->
		<footer class="footer">
			<div class="footer-container">
				<div class="footer-links">
					<a href="index.php" class="footer-link">Kezdőlap</a>
					<a href="search.php" class="footer-link">Felfedezés</a>
					<a href="contact.php" class="footer-link">Kapcsolat</a>
					<a href="information.php" class="footer-link">Tudnivalók</a>
					<a href="privacy-policy.php" class="footer-link active">Adatvédelem</a>
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
