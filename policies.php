<?php
    require "KULSO/config.php";
	$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="hu">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>CherryT | Szabályzatok</title>
		<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/policies.css">
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
	<div style="height: 80px;"></div>
	<main class="policies-container">
		<h1>Szabályzatok</h1>

		<section id="adatvedelem">
			<h2>Adatvédelem</h2>
			<p>A CherryT kiemelt figyelmet fordít a felhasználói adatok biztonságára. 
			   Az általad megadott személyes adatokat (pl. név, e-mail cím) kizárólag a szolgáltatás működtetéséhez használjuk fel, harmadik fél számára nem adjuk át. 
			   Kérjük, olvasd el részletes <a href="privacy-policy.php" target="_blank">Adatvédelmi Szabályzatunkat</a>, hogy megértsd, hogyan kezeljük adataidat.</p>
		</section>

		<section id="felhasznalas">
			<h2>Felhasználási feltételek</h2>
			<p>A CherryT közösségi csereplatformként működik, ahol a felhasználók ingyenesen kínálhatják fel termékeiket. 
			   A platformot kizárólag jogszerű célokra lehet használni. 
			   Tilos bármilyen illegális, sértő vagy félrevezető tartalom feltöltése. 
			   A szabályok megszegése a fiók felfüggesztését vagy törlését vonhatja maga után.</p>
		</section>

		<section id="cookies">
			<h2>Cookie szabályzat</h2>
			<p>Weboldalunk sütiket (cookie-kat) használ az oldal megfelelő működéséhez és a felhasználói élmény javításához. 
			   Ezek a sütik nem tartalmaznak személyes adatokat, és bármikor törölhetők vagy blokkolhatók a böngésződ beállításaiban. 
			   A CherryT használatával hozzájárulsz a sütik használatához.</p>
		</section>

		<section id="moderacio">
			<h2>Moderációs szabályok</h2>
			<p>A CherryT csapata fenntartja a jogot arra, hogy a feltöltött tartalmakat ellenőrizze és szükség esetén eltávolítsa. 
			   A nem megfelelő tartalmak (pl. sértő szöveg, hamis hirdetések, szabályszegések) figyelmeztetés nélkül törölhetők. 
			   Moderációval kapcsolatos kérdésekkel bátran fordulj ügyfélszolgálatunkhoz.</p>
		</section>
	</main>
	<!-- FOOTER -->
	<footer class="footer">
		<div class="footer-container">
			<div class="footer-links">
				<a href="index.php" class="footer-link">Kezdőlap</a>
				<a href="search.php" class="footer-link">Felfedezés</a>
				<a href="contact.php" class="footer-link">Kapcsolat</a>
				<a href="information.php" class="footer-link">Tudnivalók</a>
				<a href="privacy-policy.php" class="footer-link">Adatvédelem</a>
				<a href="policies.php" class="footer-link active">Felhasználási feltételek</a>
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
