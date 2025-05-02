<?php
	require "KULSO/config.php";
	require "KULSO/functions.php";
	$current_page = basename($_SERVER['PHP_SELF']);

	if (!isset($_COOKIE['userid'])) {
		header("Location: login.php");
		exit();
	}

	$userid = (int)$_COOKIE['userid'];

	//Felhasználói adatok
	$user_stmt = $conn->prepare("SELECT username, email, aboutme FROM users WHERE id = ?");
	$user_stmt->bind_param("i", $userid);
	$user_stmt->execute();
	$user = $user_stmt->get_result()->fetch_assoc();

	//Profilkép 
	$img_stmt = $conn->prepare("SELECT name FROM images WHERE user_id = ? AND product_id = '-' AND status = 'elfogadva' LIMIT 1");
	$img_stmt->bind_param("i", $userid);
	$img_stmt->execute();
	$img = $img_stmt->get_result()->fetch_assoc();
	$profile_img = $img ? $img['name'] : 'def_user_prof.png';

	//Követők, követések száma
	$followers = $conn->query("SELECT COUNT(*) AS count FROM follows WHERE followed_id = $userid")->fetch_assoc()['count'];
	$following = $conn->query("SELECT COUNT(*) AS count FROM follows WHERE follower_id = $userid")->fetch_assoc()['count'];

	//Követők
	$follower_stmt = $conn->prepare("SELECT  u.id, u.username FROM follows f JOIN users u ON f.follower_id = u.id WHERE f.followed_id = ?");
	$follower_stmt->bind_param("i", $userid);
	$follower_stmt->execute();
	$follower_result = $follower_stmt->get_result();

	//Követettek
	$following_stmt = $conn->prepare("SELECT u.id, u.username FROM follows f JOIN users u ON f.followed_id = u.id WHERE f.follower_id = ?");
	$following_stmt->bind_param("i", $userid);
	$following_stmt->execute();
	$following_result = $following_stmt->get_result();

	//Feltöltött termékek
	$products_stmt = $conn->prepare("SELECT * FROM products WHERE userid = ? ORDER BY id DESC");
	$products_stmt->bind_param("i", $userid);
	$products_stmt->execute();
	$products = $products_stmt->get_result();
	$has_products = $products->num_rows > 0;
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
    <title>CherryT | Profilom</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/profile.css">
    <style>
        .modal-overlay {
			position: fixed;
			top: 0; left: 0; right: 0; bottom: 0;
			background: rgba(0,0,0,0.6);
			display: none;
			justify-content: center;
			align-items: center;
			z-index: 1000;
			animation: fadeIn 0.3s ease forwards;
		}

		.modal {
			background: white;
			padding: 20px;
			border-radius: 10px;
			max-width: 400px;
			width: 90%;
			max-height: 80vh;
			overflow-y: auto;
			transform: scale(0.95);
			opacity: 0;
			animation: zoomIn 0.3s ease forwards;
		}

		@keyframes fadeIn {
			from { background: rgba(0,0,0,0); }
			to { background: rgba(0,0,0,0.6); }
		}

		@keyframes zoomIn {
			to {
				transform: scale(1);
				opacity: 1;
			}
		}
        .modal h3 { margin-top: 0; }
        .modal ul { list-style: none; padding: 0; }
        .modal li { margin: 8px 0; display: flex; justify-content: space-between; align-items: center; }
        .modal .btn { font-size: 0.85rem; padding: 4px 8px; }
	</style>
</head>
<body>
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
							<?= $user['username']; ?> <i class="ri-arrow-down-s-line dropdown-arrow"></i>
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
	<main class="profile-container">
		<section class="profile-header">
			<img src="images/<?= htmlspecialchars($profile_img) ?>" alt="Profilkép" class="profile-pic">
			<div class="profile-info">
				<h2><?= htmlspecialchars($user['username']) ?></h2>
				<p><?= htmlspecialchars($user['email']) ?></p>
				<p>Bemutatkozás: <?= htmlspecialchars($user['aboutme']) ?></p>
				<p>
					<span style="cursor:pointer; color:#840d46;" onclick="document.getElementById('followers-modal').style.display='flex'">
						Követők: <?= $followers ?>
					</span>
					|
					<span style="cursor:pointer; color:#840d46;" onclick="document.getElementById('following-modal').style.display='flex'">
						Követések: <?= $following ?>
					</span>
				</p>
			</div>
		</section>

		<section class="product-upload">
			<h3>Új termék feltöltése</h3>
			<a href="upload-product.php" class="btn btn-primary">Tovább a feltöltéshez</a>
		</section>

		<section class="product-list">
			<h3>Feltöltött termékeid</h3>
			<div class="products">
				<?php if ($has_products): ?>
					<?php while($row = $products->fetch_assoc()): ?>
						<?php
							$img_stmt = $conn->prepare("SELECT name FROM images WHERE product_id = ? LIMIT 1");
							$img_stmt->bind_param("i", $row['id']);
							$img_stmt->execute();
							$img_res = $img_stmt->get_result()->fetch_assoc();
							$product_img = $img_res ? $img_res['name'] : 'no-image.png';
						?>

						<?php if ($row['accepted'] === 'elfogadva'): ?>
							<a href="products-edit.php?productid=<?= $row['id']; ?>" class="product-item">
								<img src="images/<?= htmlspecialchars($product_img) ?>" alt="Termék">
								<p><?= htmlspecialchars($row['name']) ?></p>
							</a>
						<?php else: ?>
							<a href="products-edit.php?productid=<?= $row['id']; ?>" class="product-item pending">
								<div class="product-img-wrapper">
									<img src="images/<?= htmlspecialchars($product_img) ?>" alt="Termék">
									<span class="badge">Folyamatban</span>
								</div>
								<p><?= htmlspecialchars($row['name']) ?></p>
							</a>
						<?php endif; ?>
					<?php endwhile; ?>
				<?php else: ?>
					<p class="no-products">Nincsenek feltöltött termékek...</p>
				<?php endif; ?>
			</div>
		</section>
		<section class="reviews">
		<h3>Értékelések</h3>
		
		<div class="review-item">
			<div class="review-stars">
				★★★★☆
			</div>
			<div class="review-text">
				Nagyon kedves eladó, gyorsan át tudtuk venni a terméket!
			</div>
		</div>

		<div class="review-item">
			<div class="review-stars">
				★★★★★
			</div>
			<div class="review-text">
				Minden tökéletesen ment, csak ajánlani tudom!
			</div>
		</div>

		<div class="review-item">
			<div class="review-stars">
				★★★☆☆
			</div>
			<div class="review-text">
				A termék kissé eltért a leírástól, de korrekt volt az egyeztetés.
			</div>
		</div>

	</section>
	</main>

	<!-- MODALS -->
	<div id="followers-modal" class="modal-overlay" onclick="this.style.display='none'">
		<div class="modal" onclick="event.stopPropagation()">
			<h3>Követő</h3>
			<ul>
				<?php if ($follower_result->num_rows > 0): ?>
				<?php while($follower = $follower_result->fetch_assoc()): ?>
					<li><a href="user-profile.php?userid=<?= $follower['id'] ?>"><?= htmlspecialchars($follower['username']) ?></a></li>
				<?php endwhile; ?>
				<?php else: ?>
				<li>Nincsenek követőid.</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>

	<div id="following-modal" class="modal-overlay" onclick="this.style.display='none'">
		<div class="modal" onclick="event.stopPropagation()">
			<h3>Követés</h3>
			<ul>
				<?php if ($following_result->num_rows > 0): ?>
					<?php while($followed = $following_result->fetch_assoc()): ?>
						<li>
						  <a href="user-profile.php?userid=<?= $followed['id'] ?>"><?= htmlspecialchars($followed['username']) ?></a>
						  <form method="post" action="unfollow.php" style="display:inline">
							  <input type="hidden" name="unfollow_id" value="<?= $followed['id'] ?>">
							  <button type="submit" class="btn btn-small">Kikövetés</button>
						  </form>
						</li>
					<?php endwhile; ?>
				<?php else: ?>
				<li>Nem követsz senkit.</li>
				<?php endif; ?>
			</ul>
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


