<?php
	require "KULSO/config.php";
	$current_page = basename($_SERVER['PHP_SELF']);

	if (!isset($_COOKIE['userid'])) {
		header("Location: login.php");
		exit();
	}

	$userid = (int)$_COOKIE['userid'];
	$errors = [];

	// Felhasználói adatok lekérése
	$user_stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
	$user_stmt->bind_param("i", $userid);
	$user_stmt->execute();
	$user = $user_stmt->get_result()->fetch_assoc();

	$kategoriak = [];
	$katlekeres = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
	if ($katlekeres) {
		while ($sor = $katlekeres->fetch_assoc()) {
			$kategoriak[] = $sor;
		}
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$name = trim($_POST['name'] ?? '');
		$description = trim($_POST['description'] ?? '');
		$categoryid = $_POST['category_id'] ?? null;
		$type = $_POST['type'] ?? null;
		$itscondition = $_POST['itscondition'] ?? null;
		$location = $_POST['location'] ?? null;

		if (empty($name) || empty($description) || empty($categoryid) || empty($type) || empty($itscondition) || empty($location)) {
			$errors[] = "Minden mező kitöltése kötelező.";
		}

		if (!isset($_FILES['images']) || count(array_filter($_FILES['images']['name'])) < 3) {
			$errors[] = "Legalább 3 képet fel kell tölteni.";
		}

		if (empty($errors)) {
			$accepted = 'folyamatban';
			$stmt = $conn->prepare("INSERT INTO products (userid, name, description, categoryid, type, itscondition, location, created_at, accepted) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
			$stmt->bind_param("ississss", $userid, $name, $description, $categoryid, $type, $itscondition, $location, $accepted);
			$stmt->execute();
			$product_id = $stmt->insert_id;

			foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
				if ($_FILES['images']['error'][$index] === 0) {
					$originalName = basename($_FILES['images']['name'][$index]);
					$extension = pathinfo($originalName, PATHINFO_EXTENSION);
					$imgName = uniqid("img_", true) . "." . $extension;
					$target = __DIR__ . "/images/" . $imgName;

					if (move_uploaded_file($tmpName, $target)) {
						$img_stmt = $conn->prepare("INSERT INTO images (product_id, user_id, name, status) VALUES (?, ?, ?, 'folyamatban')");
						$img_stmt->bind_param("iis", $product_id, $userid, $imgName);
						$img_stmt->execute();
					}
				}
			}

			header("Location: profile.php");
			exit();
		}
	}
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
    <title>CherryT | Termék feltöltés</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/upload-product.css">

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
	<main class="upload-container">
    <h1>Új termék feltöltése</h1>

    <?php if (!empty($errors)): ?>
      <ul class="error-list">
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <label>Termék neve:</label>
      <input type="text" name="name" required>

      <label>Leírás:</label>
      <textarea name="description" required></textarea>

      <label>Kategória:</label>
      <select name="category_id" required>
        <option value="">-- Válassz --</option>
        <?php foreach ($kategoriak as $kat): ?>
          <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['name']) ?></option>
        <?php endforeach; ?>
      </select>

      <label>Típus:</label>
      <select name="type" required>
        <option value="">-- Válassz --</option>
        <option value="ingyenes">Ingyenes</option>
        <option value="csere">Csere</option>
      </select>

      <label>Állapot:</label>
      <select name="itscondition" required>
        <option value="">-- Válassz --</option>
        <option value="újszerű">Újszerű</option>
        <option value="használt">Használt</option>
        <option value="sérült">Sérült</option>
      </select>

      <label>Helyszín (Budapest kerületei):</label>
      <select name="location" required>
        <option value="">-- Válassz kerületet --</option>
        <?php for ($i = 1; $i <= 23; $i++): ?>
          <option value="Budapest <?= $i ?>. kerület">Budapest <?= $i ?>. kerület</option>
        <?php endfor; ?>
      </select>

       <label>Képek (legalább 3):</label>
		 <div class="custom-file-upload" id="drop-zone">
			<p>Kattints vagy húzd ide a képeket (min. 3)</p>
			<input type="file" name="images[]" accept="image/*" multiple required>
		</div>

		<div id="preview" class="image-preview"></div>

		<button type="submit">Feltöltés</button>
	</form>
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
   <script>
    const dropZone = document.getElementById('drop-zone');
    const fileInput = dropZone.querySelector('input');
    const previewContainer = document.getElementById('preview');

    dropZone.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('dragover', (e) => {
      e.preventDefault();
      dropZone.classList.add('dragover');
    });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', (e) => {
      e.preventDefault();
      const filesArray = Array.from(e.dataTransfer.files);
      const dataTransfer = new DataTransfer();
      filesArray.forEach(f => dataTransfer.items.add(f));
      fileInput.files = dataTransfer.files;
      dropZone.classList.remove('dragover');
      updatePreview(filesArray);
    });

    fileInput.addEventListener('change', () => {
      updatePreview(Array.from(fileInput.files));
    });

    function updatePreview(files) {
      previewContainer.innerHTML = '';
      files.forEach((file) => {
        if (file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = (e) => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('preview-thumb');
            previewContainer.appendChild(img);
          };
          reader.readAsDataURL(file);
        }
      });
    }
  </script>
</body>
</html>
