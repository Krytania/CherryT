<?php
	require "KULSO/config.php";
	require "KULSO/functions.php";

	if (!isset($_COOKIE['userid'])) {
		header("Location: login.php");
		exit();
	}

	$userid = (int)$_COOKIE['userid'];
	$status = 'elfogadva';

	// Kedvencek lekérdezése
	$fav_query = $conn->prepare("SELECT p.* FROM favorites f JOIN products p ON f.product_id = p.id WHERE f.user_id = ? AND p.accepted = ? ORDER BY f.id DESC");
	$fav_query->bind_param("is", $userid, $status);
	$fav_query->execute();
	$favorites = $fav_query->get_result();
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
	
    <title>CherryT | Kedvenceid </title>
	
	<!-- REMIXICONS -->
	<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
	
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
	
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/favorites.css">
</head>
<body>
	<?php
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
    <main class="favorites-container">
	<h1 class="favorites-header">Kedvenceid</h1>
	<p class="motivational-text">Nézd át a kedvenceidet, és csapj le rájuk, mielőtt más megteszi!</p>

	<?php if ($favorites->num_rows > 0): ?>
		<div class="products">
		<?php while($row = $favorites->fetch_assoc()): ?>
			<?php
			// képek
			$img_stmt = $conn->prepare("SELECT name FROM images WHERE product_id = ? AND status = 'elfogadva' ORDER BY id ASC");
			$img_stmt->bind_param("i", $row['id']);
			$img_stmt->execute();
			$img_res = $img_stmt->get_result();
			$images = [];
			while ($img = $img_res->fetch_assoc()) {
				$images[] = $img['name'];
			}

			// dátum
			$notif_stmt = $conn->prepare("SELECT created_at FROM notifications WHERE type = 'favorite' AND fromid = ? AND productid = ? ORDER BY id DESC LIMIT 1");
			$notif_stmt->bind_param("ii", $userid, $row['id']);
			$notif_stmt->execute();
			$notif = $notif_stmt->get_result()->fetch_assoc();
			$added_at = $notif ? date('Y. F j.', strtotime($notif['created_at'])) : 'Ismeretlen időpontban';
			?>
			<div class="product-item">
				<a href="product-details.php?productid=<?= $row['id'] ?>">
					<img src="images/<?= htmlspecialchars($images[0] ?? 'no-image.png') ?>" alt="Termék">
					<p><?= htmlspecialchars($row['name']) ?></p>
				</a>
				<p class="added-date">Hozzáadva: <?= $added_at ?></p>
				<form method="post" action="unfavorite.php" class="unfavorite-form">
					<input type="hidden" name="productid" value="<?= $row['id'] ?>">
					<button type="submit" class="unfavorite-btn" title="Eltávolítás kedvencekből">
						<i class="ri-heart-fill" data-toggle-icon></i>
					</button>
				</form>
			</div>
		<?php endwhile; ?>
		</div>
	<?php else: ?>
		<div class="no-favorites">
			<p>Nincsenek még kedvenceid.</p>
		</div>
	<?php endif; ?>

	<div class="discover-container">
		<a href="search.php" class="discover-btn"><i class="ri-search-line"></i> Fedezd fel a termékeket</a>
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
	<script src="js/main.js"></script>
	<script src="js/main.js"></script>
<script>
  document.querySelectorAll('[data-toggle-icon]').forEach(icon => {
    icon.addEventListener('mouseenter', () => {
      icon.classList.remove('ri-heart-fill');
      icon.classList.add('ri-heart-line');
    });
    icon.addEventListener('mouseleave', () => {
      icon.classList.remove('ri-heart-line');
      icon.classList.add('ri-heart-fill');
    });
  });
</script>
</body>
</html>
