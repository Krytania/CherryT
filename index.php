<?php
	
	require "KULSO/config.php";
	
	if (isset($_POST['follow-btn'])) {
		$follow_id = (int)$_POST['follow_id'];
		$userid = (int)$_COOKIE['userid'];

		// Ellenőrzés: követi?
		$check_follow = $conn->prepare("SELECT id FROM follows WHERE follower_id = ? AND followed_id = ?");
		$check_follow->bind_param("ii", $userid, $follow_id);
		$check_follow->execute();
		$check_result = $check_follow->get_result();

		if ($check_result->num_rows === 0) {
			
			// follows
			$insert_follow = $conn->prepare("INSERT INTO follows (follower_id, followed_id) VALUES (?, ?)");
			$insert_follow->bind_param("ii", $userid, $follow_id);
			$insert_follow->execute();

			// notifications
			$user_query = $conn->prepare("SELECT username FROM users WHERE id = ?");
			$user_query->bind_param("i", $userid);
			$user_query->execute();
			$user_result = $user_query->get_result();
			$user_data = $user_result->fetch_assoc();
			$follower_name = $user_data['username'];

			$notification_text = $follower_name . " bekövetett.";

			$insert_notification = $conn->prepare("
				INSERT INTO notifications (fromid, toid, productid, type, message, created_at, seen)
				VALUES (?, ?, '-', 'follow', ?, NOW(), 0)
			");
			$insert_notification->bind_param("iis", $userid, $follow_id, $notification_text);
			$insert_notification->execute();
		}

		header("Location: index.php");
		exit();
	}
	
	if (isset($_POST['unfollow-btn'])) {
		$follow_id = (int)$_POST['follow_id'];
		$userid = (int)$_COOKIE['userid'];

		// Kikövetés a follows táblából
		$delete_follow = $conn->prepare("DELETE FROM follows WHERE follower_id = ? AND followed_id = ?");
		$delete_follow->bind_param("ii", $userid, $follow_id);
		$delete_follow->execute();

		// Törlés a notifications táblából
		$delete_notification = $conn->prepare("DELETE FROM notifications WHERE fromid = ? AND toid = ? AND type = 'follow'");
		$delete_notification->bind_param("ii", $userid, $follow_id);
		$delete_notification->execute();

		header("Location: index.php");
		exit();
	}
	
	
	$unread_notifications = 0;
	if (isset($_COOKIE['userid'])) {
		$userid = (int)$_COOKIE['userid'];
		$noti_query = $conn->prepare("SELECT COUNT(*) AS count FROM notifications WHERE toid = ? AND seen = 0");
		$noti_query->bind_param("i", $userid);
		$noti_query->execute();
		$noti_result = $noti_query->get_result()->fetch_assoc();
		$unread_notifications = $noti_result['count'];
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
		<title>CherryT | Kezdőlap</title>
		
		<!-- REMIXICONS -->
		<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
		
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">

		<!-- NAV/ALAPOK CSS -->
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/index.css">
	  
		<!-- Swiper CSS -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />    
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
		<!--=============== HERO CAROUSEL ===============-->
		<section class="hero-carousel">
			<div class="swiper mySwiper">
				<div class="swiper-wrapper">
					<!-- Slide 1 -->
					<div class="swiper-slide">
						<img src="images/hero1.jpg" alt="Hero 1">
						<div class="hero-text">
							<h1>Üdvözlünk a CherryT világában!</h1>
							<p>Csere, ajándékozás, közösség.</p>
							<a href="information.php" class="btn">Tudj meg többet</a>
						</div>

					</div>
					<!-- Slide 2 -->
					<div class="swiper-slide">
						<img src="images/hero2.jpg" alt="Hero 2">
						<div class="hero-text">
							<h1>Találd meg, amire szükséged van!</h1>
							<p>Adj, fogadj el és cserélj szívből a CherryT közösségében.</p>
							<a href="search.php" class="btn">Fedezd fel</a>
						</div>
					</div>
				</div>

				<!-- Navigáció gombok -->
				<div class="swiper-button-next"></div>
				<div class="swiper-button-prev"></div>

				<!-- Lapozó pöttyök -->
				<div class="swiper-pagination"></div>
			</div>
		</section>
		
		<!--============== TARTALOM ====================-->
		<div class="container fadeInSection">
			<h2>Legfrissebb termékek</h2>
			<div class="products-grid">
			<?php
			$products = $conn->query("SELECT id, name FROM products WHERE accepted = 'elfogadva' ORDER BY created_at DESC LIMIT 12");
			if ($products && $products->num_rows > 0):
				while ($product = $products->fetch_assoc()):
					$img_stmt = $conn->prepare("SELECT name FROM images WHERE product_id = ? LIMIT 1");
					$img_stmt->bind_param("i", $product['id']);
					$img_stmt->execute();
					$img_res = $img_stmt->get_result()->fetch_assoc();
					$product_pic = $img_res ? $img_res['name'] : 'no-image.png';
			?>
			<a href="product-details.php?productid=<?= $product['id'] ?>" class="product-card">
				<img src="images/<?= htmlspecialchars($product_pic) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
				<h3><?= htmlspecialchars($product['name']) ?></h3>
			</a>
			<?php endwhile; endif; ?>
			</div>
			<div class="more-products-button">
				<a href="search.php" class="btn">
					További termékek <i class="ri-arrow-right-line"></i>
				</a>
			</div>
		</div>
		<?php
			// Felhasználók száma (megerősített regisztrációval)
			$user_count_query = $conn->query("SELECT COUNT(*) AS total FROM users WHERE confirmed = 1");
			$user_count = $user_count_query->fetch_assoc()['total'];

			// Termékek száma (elfogadott státuszban)
			$product_count_query = $conn->query("SELECT COUNT(*) AS total FROM products WHERE accepted = 'elfogadva'");
			$product_count = $product_count_query->fetch_assoc()['total'];

			// Elcserélt termékek száma --> igazából nem megtörtént cserék...
			$traded_product_query = $conn->query("SELECT COUNT(*) AS total FROM products WHERE type = 'csere' AND accepted = 'elfogadva'");
			$traded_product_count = $traded_product_query->fetch_assoc()['total'];

			// Adományozott termékek száma 
			$donated_product_query = $conn->query("SELECT COUNT(*) AS total FROM products WHERE type = 'ingyenes' AND accepted = 'elfogadva'");
			$donated_product_count = $donated_product_query->fetch_assoc()['total'];

		?>
		<!-- STATS SECTION -->
		<section class="stats-cherryt fadeInSection">
			<div class="container stats-container">
				<div class="stat-card">
					<i class="ri-box-3-line"></i>
					<h3><?= $product_count; ?>+</h3>
					<p>Feltöltött termék</p>
				</div>
				<div class="stat-card">
					<i class="ri-refresh-line"></i>
					<h3><?= $traded_product_count; ?>+</h3> <!-- át kell írni -->
					<p>Elcserélt termék</p>
				</div>
				<div class="stat-card">
					<i class="ri-gift-line"></i>
					<h3><?= $donated_product_count; ?>+</h3> <!-- át kell írni -->
					<p>Adományozott termék</p>
				</div>
				<div class="stat-card">
					<i class="ri-user-3-line"></i>
					<h3><?= $user_count; ?>+</h3>
					<p>Regisztrált felhasználó</p>
				</div>
			</div>
		</section>

		
		<!-- RECOMMENDED -->
		<section class="recommended-users fadeInSection">
			<div class="container">
				<div style="text-align: center; margin-bottom: 30px;">
					<a href="all-users.php" class="discover-users-link">
						Felfedezésre váró felhasználók <i class="ri-arrow-right-line"></i>
					</a>
				</div>

				<div class="user-cards">
				<?php
				if (isset($_COOKIE['userid'])) {
					$userid = (int)$_COOKIE['userid'];

					$users = $conn->query("
						SELECT id, username 
						FROM users 
						WHERE id != $userid 
						ORDER BY RAND()
						LIMIT 5
					");
				} else {
					$users = $conn->query("SELECT id, username FROM users ORDER BY RAND() LIMIT 5");
				}

				if ($users && $users->num_rows > 0):
					while ($user = $users->fetch_assoc()):
						// Profilkép lekérése
						$img_stmt = $conn->prepare("SELECT name FROM images WHERE user_id = ? AND product_id = '-' AND status = 'elfogadva' LIMIT 1");
						$img_stmt->bind_param("i", $user['id']);
						$img_stmt->execute();
						$img_res = $img_stmt->get_result()->fetch_assoc();
						$profile_pic = $img_res ? $img_res['name'] : 'def_user_prof.png';

						// Megnézzük, hogy követi-e már
						$follow_check = $conn->prepare("SELECT id FROM follows WHERE follower_id = ? AND followed_id = ?");
						$follow_check->bind_param("ii", $userid, $user['id']);
						$follow_check->execute();
						$follow_check_result = $follow_check->get_result();
						$is_following = $follow_check_result->num_rows > 0;
				?>
					<div class="user-card">
						<a href="user-profile.php?userid=<?= $user['id'] ?>">
							<img src="images/<?= htmlspecialchars($profile_pic) ?>" alt="<?= htmlspecialchars($user['username']) ?> profilképe">
							<h3><?= htmlspecialchars($user['username']) ?></h3>
						</a>

						<?php if (isset($_COOKIE['userid'])): ?>
							<form method="post" action="index.php">
								<input type="hidden" name="follow_id" value="<?= $user['id'] ?>">
								<?php if ($is_following): ?>
									<input type="submit" name="unfollow-btn" class="btn-small unfollow" value="Kikövetés">
								<?php else: ?>
									<input type="submit" name="follow-btn" class="btn-small" value="Követés">
								<?php endif; ?>
							</form>
						<?php else: ?>
							<p class="info-text">Nézd meg a profilját!</p>
						<?php endif; ?>
					</div>
				<?php endwhile; endif; ?>
				</div>
			</div>
		</section>

		<!-- FOOTER -->
		<footer class="footer">
			<div class="footer-container">
				<div class="footer-links">
					<a href="index.php" class="footer-link active">Kezdőlap</a>
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


		<!-- Swiper JS -->
		<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
		<script src="js/swipper.js"></script>
		
		<!-- MAIN JS -->
		<script src="js/main.js"></script>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				const counters = document.querySelectorAll('.stat-card h3');
				const speed = 100;
				let started = false; // hogy csak egyszer induljon el

				function countUp(counter) {
					const target = +counter.innerText.replace('+', '');
					let count = 0;

					const updateCount = () => {
						const increment = Math.max(Math.ceil(target / speed), 1);

						if (count < target) {
							count += increment;
							counter.innerText = count + '+';
							setTimeout(updateCount, 15);
						} else {
							counter.innerText = target + '+';
						}
					};

					updateCount();
				}

				function isInViewport(element) {
					const rect = element.getBoundingClientRect();
					return (
						rect.top <= (window.innerHeight - 100) // akkor indul ha kb. 100px-re van a nézőképernyőtől
					);
				}

				window.addEventListener('scroll', function() {
					const statsSection = document.querySelector('.stats-cherryt');
					if (!started && isInViewport(statsSection)) {
						counters.forEach(countUp);
						started = true;
					}
				});
			});
		</script>
	</body>
</html>