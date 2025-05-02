<?php
	require "KULSO/config.php";
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
		<title>CherryT | Tudnivalók</title>
	  
		<!-- REMIXICONS -->
		<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
		
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">

		<!-- NAV/ALAPOK CSS -->
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/information.css">
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
	<main class="info-main">

		<section class="info-hero">
			<h1>Üdvözlünk a CherryT világában!</h1>
			<p>Fedezd fel, hogyan ajándékozhatsz, cserélhetsz, és építhetsz közösséget velünk.</p>
		</section>

		<section class="info-how">
			<h2>Hogyan működik?</h2>
			<div class="info-steps">
				<div class="step">
					<i class="ri-user-add-line"></i>
					<h3>1. Regisztrálj</h3>
					<p>Csatlakozz közösségünkhöz egy gyors regisztrációval.</p>
				</div>
				<div class="step">
					<i class="ri-upload-line"></i>
					<h3>2. Tölts fel</h3>
					<p>Adj hozzá termékeket, amiket szívesen továbbajándékoznál.</p>
				</div>
				<div class="step">
					<i class="ri-gift-line"></i>
					<h3>3. Ajándékozz</h3>
					<p>Találd meg az új gazdát felesleges tárgyaidnak.</p>
				</div>
				<div class="step">
					<i class="ri-hand-heart-line"></i>
					<h3>4. Cserélj</h3>
					<p>Válogass mások ajánlatai közül, és cseréljetek!</p>
				</div>
			</div>
		</section>

		<section class="info-faq">
		<h2>Gyakori kérdések</h2>
		<div class="faq-item">
		  <button class="faq-question">Mennyibe kerül a CherryT használata?</button>
		  <div class="faq-answer">Semmibe! A CherryT teljesen ingyenes platform mindenkinek.</div>
		</div>
		<div class="faq-item">
		  <button class="faq-question">Mit tehetek, ha problémám adódik?</button>
		  <div class="faq-answer">Vedd fel velünk a kapcsolatot a kapcsolatfelvételi űrlapunkon keresztül, segítünk!</div>
		</div>
		<div class="faq-item">
		  <button class="faq-question">Csak adományozni lehet, vagy cserélni is?</button>
		  <div class="faq-answer">Mindkettőt! Döntsd el te, hogy elajándékozol vagy cserét ajánlasz fel.</div>
		</div>
		</section>

		<section class="info-security-rules-container">
  
		  <div class="info-security">
			<h2>Biztonsági tanácsok</h2>
			<ul>
				<li>Soha ne oszd meg a jelszavad senkivel.</li>
				<li>Csak biztonságos helyen találkozz átadáskor.</li>
				<li>Ha valaki gyanús viselkedést mutat, jelentsd nekünk.</li>
			</ul>
		  </div>

		  <div class="info-rules">
			<h2>Közösségi szabályok</h2>
			<ul>
				<li>Tartsd tiszteletben másokat!</li>
				<li>Ne kínálj eladással pénzt kérő termékeket.</li>
				<li>Tilos jogsértő, veszélyes tárgyakat feltölteni.</li>
				<li>Együtt építünk egy pozitív, biztonságos közösséget!</li>
			</ul>
		  </div>

		</section>

	</main>

	<!-- FOOTER -->
	<footer class="footer">
		<div class="footer-container">
			<div class="footer-links">
				<a href="index.php" class="footer-link">Kezdőlap</a>
				<a href="search.php" class="footer-link">Felfedezés</a>
				<a href="contact.php" class="footer-link">Kapcsolat</a>
				<a href="information.php" class="footer-link active">Tudnivalók</a>
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
	<script>
	document.querySelectorAll('.faq-question').forEach(button => {
	  button.addEventListener('click', () => {
		const answer = button.nextElementSibling;

		// Először minden másik választ becsukunk
		document.querySelectorAll('.faq-answer').forEach(item => {
		  if (item !== answer) {
			item.style.display = 'none';
		  }
		});

		// Az aktuális válasz megjelenítése vagy elrejtése
		if (answer.style.display === 'block') {
		  answer.style.display = 'none';
		} else {
		  answer.style.display = 'block';
		}
	  });
	});
	</script>
	</body>
</html>