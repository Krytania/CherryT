<?php
	require "KULSO/config.php";
	$current_page = basename($_SERVER['PHP_SELF']);
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
    <title>CherryT | Felfedezés</title>

    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/search.css">
</head>
<body>
<?php
	if (isset($_COOKIE['userid'])) {
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
            <div class="nav-menu" id="nav-menu">
                <ul class="nav-list">
                    <li><a href="index.php" class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>">Kezdőlap</a></li>
                    <li><a href="search.php" class="nav-link active">Felfedezés</a></li>
                    <li><a href="contact.php" class="nav-link">Kapcsolat</a></li>
                    <li><a href="information.php" class="nav-link">Tudnivalók</a></li>
                    <li><a href="notifications.php" class="nav-link"><i class="ri-notification-3-line"></i></a></li>
                    <li class="dropdown-item">
                        <div class="nav-link"><?= $felhasznalo['username']; ?> <i class="ri-arrow-down-s-line dropdown-arrow"></i></div>
                        <ul class="dropdown-menu">
                            <li><a href="profile.php" class="dropdown-link"><i class="ri-user-line"></i> Profilod</a></li>
                            <li><a href="messages.php" class="dropdown-link"><i class="ri-message-3-line"></i> Üzeneteid</a></li>
                            <li><a href="edit-profile.php" class="dropdown-link"><i class="ri-settings-5-line"></i> Szerkesztés</a></li>
                            <li><a href="favorites.php" class="dropdown-link"><i class="ri-heart-line"></i> Kedvenceid</a></li>
                            <li><a href="logout.php" class="dropdown-link"><i class="ri-logout-box-r-line"></i> Kijelentkezés</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
<?php } else { ?>
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
            <div class="nav-menu" id="nav-menu">
                <ul class="nav-list">
                    <li><a href="index.php" class="nav-link">Kezdőlap</a></li>
                    <li><a href="search.php" class="nav-link active">Felfedezés</a></li>
                    <li><a href="contact.php" class="nav-link">Kapcsolat</a></li>
                    <li><a href="information.php" class="nav-link">Tudnivalók</a></li>
                    <li><a href="login.php" class="nav-link">Bejelentkezés</a></li>
                </ul>
            </div>
        </nav>
    </header>
<?php } ?>

<div class="container">
    <h2>Felfedezhető termékek</h2>

    <!-- SZŰRŐ ŰRLAP -->
    <form method="GET" class="filter-form">
        <div class="filter-group">
            <select name="condition">
                <option value="">Állapot szerint</option>
                <option value="újszerű" <?= isset($_GET['condition']) && $_GET['condition'] == 'újszerű' ? 'selected' : '' ?>>Újszerű</option>
                <option value="használt" <?= isset($_GET['condition']) && $_GET['condition'] == 'használt' ? 'selected' : '' ?>>Használt</option>
                <option value="sérült" <?= isset($_GET['condition']) && $_GET['condition'] == 'sérült' ? 'selected' : '' ?>>Sérült</option>
            </select>

            <input type="text" name="location" placeholder="Helyszín" value="<?= htmlspecialchars($_GET['location'] ?? '') ?>">

            <select name="category">
                <option value="">Kategória szerint</option>
                <?php
					$kat_result = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
					while ($kat = $kat_result->fetch_assoc()):
                ?>
                    <option value="<?= $kat['id'] ?>" <?= isset($_GET['category']) && $_GET['category'] == $kat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($kat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Szűrés</button>
        </div>
    </form>

    <!-- TERMÉKEK LISTÁZÁSA -->
    <div class="products-grid">
    <?php
		$where = ["accepted = 'elfogadva'"];

		if(isset($_COOKIE['userid'])){
			$userid = intval($_COOKIE['userid']);
			$where[] = "userid != $userid";
		}
		if(!empty($_GET['condition'])){
			$cond = $conn->real_escape_string($_GET['condition']);
			$where[] = "itscondition = '$cond'";
		}
		if(!empty($_GET['location'])){
			$loc = $conn->real_escape_string($_GET['location']);
			$where[] = "location LIKE '%$loc%'";
		}
		if(!empty($_GET['category'])){
			$cat = intval($_GET['category']);
			$where[] = "categoryid = $cat";
		}

		$lekerdezes = "SELECT * FROM products WHERE " . implode(" AND ", $where);
		$talalt_termekek = $conn->query($lekerdezes);

		if($talalt_termekek && $talalt_termekek->num_rows > 0):
			while($termekek = $talalt_termekek->fetch_assoc()):
				$termekid = $termekek['id'];
				$termeknev = $termekek['name'];

				$img_stmt = $conn->prepare("SELECT name FROM images WHERE product_id = ? LIMIT 1");
				$img_stmt->bind_param("i", $termekid);
				$img_stmt->execute();
				$img_res = $img_stmt->get_result()->fetch_assoc();
				$product_pic = $img_res ? $img_res['name'] : 'no-image.png';
    ?>
        <a href="product-details.php?productid=<?= $termekid ?>#product-<?= $termekid ?>" class="product-card" id="product-<?= $termekid ?>">
            <img src="images/<?= htmlspecialchars($product_pic) ?>" alt="<?= htmlspecialchars($termeknev) ?>">
            <h3><?= htmlspecialchars($termeknev) ?></h3>
        </a>
    <?php endwhile; else: ?>
        <p>Nincs találat a megadott szűrőfeltételekkel.</p>
    <?php endif; ?>
    </div>
</div>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-links">
            <a href="index.php" class="footer-link">Kezdőlap</a>
            <a href="search.php" class="footer-link active">Felfedezés</a>
            <a href="contact.php" class="footer-link">Kapcsolat</a>
            <a href="information.php" class="footer-link">Tudnivalók</a>
            <a href="privacy-policy.php" class="footer-link">Adatvédelem</a>
            <a href="policies.php" class="footer-link">Felhasználási feltételek</a>
        </div>
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
