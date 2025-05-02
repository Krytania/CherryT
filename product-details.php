<?php

	require "KULSO/config.php";
	$current_page = basename($_SERVER['PHP_SELF']);
	
	if (!isset($_GET['productid']) || !is_numeric($_GET['productid'])) {
		header("Location: index.php");
		exit();
	}

	$productid = (int)$_GET['productid'];
	$logged_in = isset($_COOKIE['userid']);
	$userid = $logged_in ? (int)$_COOKIE['userid'] : null;

	$product_query = $conn->prepare("SELECT p.*, u.username, c.name AS category FROM products p JOIN users u ON p.userid = u.id JOIN categories c ON p.categoryid = c.id WHERE p.id = ? AND p.accepted = 'elfogadva'");
	$product_query->bind_param("i", $productid);
	$product_query->execute();
	$product = $product_query->get_result()->fetch_assoc();

	if (!$product) {
		echo "<p>Termék nem található.</p>";
		exit();
	}

	//Képek
	$img_query = $conn->prepare("SELECT name FROM images WHERE product_id = ? AND status = 'elfogadva'");
	$img_query->bind_param("s", $productid);
	$img_query->execute();
	$images = $img_query->get_result();
	
	//Feltételezzük, hogy a $userid és $productid elérhető
	$fav_query = $conn->prepare("SELECT 1 FROM favorites WHERE user_id = ? AND product_id = ?");
	$fav_query->bind_param("ii", $userid, $productid);
	$fav_query->execute();
	$is_favorite = $fav_query->get_result()->num_rows > 0;

	$icon_class = $is_favorite ? 'ri-heart-fill' : 'ri-heart-line';
	
	
	$favcount = $conn->prepare("SELECT COUNT(*) AS total FROM favorites WHERE product_id = ?");
	$favcount->bind_param("i", $productid);
	$favcount->execute();
	$favcount_result = $favcount->get_result();
	$favcount_row = $favcount_result->fetch_assoc();
	$fav_count = $favcount_row['total'];

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

		<!-- SWIPER CSS -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

		<!-- NAV/ALAPOK CSS -->
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/product-details.css">
		
		<!-- FONT AWESOME -->
		<script src="https://kit.fontawesome.com/3970e10885.js" crossorigin="anonymous"></script>

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
		<a href="search.php#product-<?= $productid ?>" id="indexgomb" title="Vissza">
			<i class="fa-solid fa-circle-arrow-left"></i>
		</a>
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
	<main class="product-details-container">
		<div class="product-gallery">
		  <div class="swiper">
			<div class="swiper-wrapper">
			  <?php while($img = $images->fetch_assoc()): ?>
				<div class="swiper-slide">
				  <img src="images/<?= htmlspecialchars($img['name']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
				</div>
			  <?php endwhile; ?>
			</div>
			<div class="swiper-pagination"></div>
			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>
		  </div>
		</div>

		<div class="product-info">
			<h1><?= htmlspecialchars($product['name']) ?></h1>
			<p class="product-desc"><?= htmlspecialchars($product['description']) ?></p>
			<p><strong>Feltöltő:</strong> <?= htmlspecialchars($product['username']) ?></p>
			<p><strong>Helyszín:</strong> <?= htmlspecialchars($product['location']) ?></p>
			<p><strong>Feltöltve:</strong> <?= date('Y.m.d H:i', strtotime($product['created_at'])) ?></p>

		  <div class="product-tags">
				<span class="tag">Kategória: <?= htmlspecialchars($product['category']) ?></span>
				<span class="tag">Állapot: <?= htmlspecialchars($product['itscondition']) ?></span>
				<span class="tag">Típus: <?= htmlspecialchars($product['type']) ?></span>
		  </div>

			<?php if ($logged_in): ?>
			<div class="product-actions">
				<div class="left-actions">
					<?php if ($product['type'] === 'csere' || $product['type'] === 'ingyenes'): ?>
					<a href="directmessage.php?productid=<?= $productid ?>&userid=<?= $product['userid'] ?>" class="btn">Üzenet küldése</a>
			<?php endif; ?>
			<?php if ($product['type'] === 'ingyenes'): ?>
					<a href="request-reserve.php?productid=<?= $productid ?>" class="btn">Lefoglalás</a>
			<?php endif; ?>
				</div>
				<div class="right-actions">
					<form method="post" action="add-favorite.php?product_id=<?= $productid; ?>">
						<input type="hidden" name="product_id" value="<?= $productid ?>">
						<button type="submit" class="favorite-btn" title="Kedvencekhez">
							<i class="<?= $icon_class ?>"></i>
							<span class="fav-count"><?= $fav_count ?></span>
						</button>
					</form>
				</div>
			</div>
			<?php endif; ?>
		</div>
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
	<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
	<script>
	  document.addEventListener('DOMContentLoaded', function () {
		const swiper = new Swiper('.swiper', {
		  loop: true,
		  pagination: {
			el: '.swiper-pagination',
			clickable: true,
		  },
		  navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		  },
		});
	  });
	</script>
	<script>
	  document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.favorite-btn').forEach(btn => {
		  const icon = btn.querySelector('i');

		  btn.addEventListener('mouseenter', () => {
			icon.classList.toggle('ri-heart-line');
			icon.classList.toggle('ri-heart-fill');
		  });

		  btn.addEventListener('mouseleave', () => {
			icon.classList.toggle('ri-heart-line');
			icon.classList.toggle('ri-heart-fill');
		  });
		});
	  });
	</script>
	</body>
</html>