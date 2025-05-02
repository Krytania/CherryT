<?php
require "KULSO/config.php";
if (!isset($_COOKIE['userid'])) {
  header("Location: login.php");
  exit();
}
$userid = (int)$_COOKIE['userid'];

$productid = isset($_GET['productid']) ? (int)$_GET['productid'] : 0;

// Lekérjük a terméket
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND userid = ?");
$stmt->bind_param("ii", $productid, $userid);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
  echo "Nincs jogosultságod szerkeszteni ezt a terméket.";
  exit();
}

// Képek lekérése
$kepek = [];
$img_stmt = $conn->prepare("SELECT id, name FROM images WHERE product_id = ?");
$img_stmt->bind_param("s", $productid);
$img_stmt->execute();
$img_result = $img_stmt->get_result();
while ($sor = $img_result->fetch_assoc()) {
  $kepek[] = $sor;
}

// Kategóriák lekérése
$kategoriak = [];
$katlekeres = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
while ($sor = $katlekeres->fetch_assoc()) {
  $kategoriak[] = $sor;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Teljes törlés
  if (isset($_POST['delete_product']) && $_POST['delete_product'] === '1') {
    // Képek törlése
    $del_images = $conn->prepare("SELECT name FROM images WHERE product_id = ?");
    $del_images->bind_param("s", $productid);
    $del_images->execute();
    $res_images = $del_images->get_result();
    while ($img = $res_images->fetch_assoc()) {
      $file = __DIR__ . "/images/" . $img['name'];
      if (file_exists($file)) unlink($file);
    }

    $del_img_stmt = $conn->prepare("DELETE FROM images WHERE product_id = ?");
    $del_img_stmt->bind_param("s", $productid);
    $del_img_stmt->execute();

    $conn->query("DELETE FROM favorites WHERE product_id = $productid");
    $conn->query("DELETE FROM notifications WHERE productid = '$productid'");
    $conn->query("DELETE FROM products WHERE id = $productid AND userid = $userid");

    header("Location: profile.php");
    exit();
  }

  if (isset($_POST['delete_image_id'])) {
    $img_id = (int)$_POST['delete_image_id'];
    $del_stmt = $conn->prepare("DELETE FROM images WHERE id = ? AND product_id = ?");
    $del_stmt->bind_param("is", $img_id, $productid);
    $del_stmt->execute();
    $conn->query("UPDATE products SET accepted = 'folyamatban' WHERE id = $productid");
    header("Location: products-edit.php?productid=$productid");
    exit();
  }

  $changed = false;
  $fields = ['name', 'description', 'categoryid', 'type', 'itscondition', 'location'];
  foreach ($fields as $field) {
    if (isset($_POST[$field]) && $_POST[$field] != $product[$field]) {
      $product[$field] = trim($_POST[$field]);
      $changed = true;
    }
  }

  if ($changed) {
    $accepted = 'folyamatban';
    $update = $conn->prepare("UPDATE products SET name=?, description=?, categoryid=?, type=?, itscondition=?, location=?, accepted=? WHERE id=? AND userid=?");
    $update->bind_param("ssissssii", $product['name'], $product['description'], $product['categoryid'], $product['type'], $product['itscondition'], $product['location'], $accepted, $productid, $userid);
    $update->execute();
    header("Location: profile.php");
    exit();
  }

  if (!empty($_FILES['new_images']['name'][0])) {
    foreach ($_FILES['new_images']['tmp_name'] as $index => $tmpName) {
      if ($_FILES['new_images']['error'][$index] === 0) {
        $originalName = basename($_FILES['new_images']['name'][$index]);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $imgName = uniqid("img_", true) . "." . $extension;
        $target = __DIR__ . "/images/" . $imgName;

        if (move_uploaded_file($tmpName, $target)) {
          $img_stmt = $conn->prepare("INSERT INTO images (product_id, user_id, name, status) VALUES (?, ?, ?, 'folyamatban')");
          $img_stmt->bind_param("iis", $productid, $userid, $imgName);
          $img_stmt->execute();
        }
      }
    }
    $conn->query("UPDATE products SET accepted = 'folyamatban' WHERE id = $productid");
    header("Location: products-edit.php?productid=$productid");
    exit();
  }
}
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
	<title>CherryT | Termék szerkesztése</title>
		
	<!-- REMIXICONS -->
	<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
		
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/products-edit.css">
</head>
<body>
<?php
$current_page = basename($_SERVER['PHP_SELF']);
$talalt_felhasznalo = $conn->query("SELECT * FROM users WHERE id = $_COOKIE[userid]");
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
			<li><a href="search.php" class="nav-link <?= $current_page == 'search.php' ? 'active' : '' ?>">Felfedezés</a></li>
			<li><a href="contact.php" class="nav-link <?= $current_page == 'contact.php' ? 'active' : '' ?>">Kapcsolat</a></li>
			<li><a href="information.php" class="nav-link <?= $current_page == 'information.php' ? 'active' : '' ?>">Tudnivalók</a></li>
			<li><a href="notifications.php" class="nav-link"><i class="ri-notification-3-line"></i></a></li>
			<li class="dropdown-item">
				<div class="nav-link">
					<?= $felhasznalo['username']; ?> <i class="ri-arrow-down-s-line dropdown-arrow"></i>
				</div>
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
<main class="upload-container">
	<h1>Termék szerkesztése</h1>
	<h2>Feltöltött képek</h2>
	<div class="image-preview">
		<?php foreach ($kepek as $kep): ?>
		<div class="img-wrapper">
			<img src="images/<?= htmlspecialchars($kep['name']) ?>" class="preview-thumb">
			<form method="post" class="delete-img-form">
				<input type="hidden" name="delete_image_id" value="<?= $kep['id'] ?>">
				<button type="submit" class="delete-img-btn">X</button>
			</form>
		</div>
		<?php endforeach; ?>
	</div>
	<form method="post" enctype="multipart/form-data">
		<label>Termék neve:</label>
		<input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
		<label>Leírás:</label>
		<textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
		<label>Kategória:</label>
		<select name="categoryid" required>
			<?php foreach ($kategoriak as $kat): ?>
			<option value="<?= $kat['id'] ?>" <?= $kat['id'] == $product['categoryid'] ? 'selected' : '' ?>><?= 		htmlspecialchars($kat['name']) ?></option>
			<?php endforeach; ?>
		</select>
		<label>Típus:</label>
		<select name="type" required>
			<option value="ingyenes" <?= $product['type'] == 'ingyenes' ? 'selected' : '' ?>>Ingyenes</option>
			<option value="csere" <?= $product['type'] == 'csere' ? 'selected' : '' ?>>Csere</option>
		</select>
		<label>Állapot:</label>
		<select name="itscondition" required>
			<option value="újszerű" <?= $product['itscondition'] == 'újszerű' ? 'selected' : '' ?>>Újszerű</option>
			<option value="használt" <?= $product['itscondition'] == 'használt' ? 'selected' : '' ?>>Használt</option>
			<option value="sérült" <?= $product['itscondition'] == 'sérült' ? 'selected' : '' ?>>Sérült</option>
		</select>
		<label>Helyszín (Budapest kerületei):</label>
		<select name="location" required>
			<?php for ($i = 1; $i <= 23; $i++): ?>
			<option value="Budapest <?= $i ?>. kerület" <?= $product['location'] == "Budapest $i. kerület" ? 'selected' : '' ?>>Budapest <?= $i ?>. kerület</option>
			<?php endfor; ?>
		</select>
		<label>Új képek feltöltése:</label>
		<div class="custom-file-upload" id="drop-zone">
			<p>Kattints vagy húzd ide a képeket</p>
			<input type="file" name="new_images[]" accept="image/*" multiple>
		</div>
		<div id="preview" class="image-preview"></div>
		<button type="submit" class="btn">Mentés</button>
	</form>
	<form method="post" onsubmit="return confirm('Biztosan törlöd a terméket?')">
		<input type="hidden" name="delete_product" value="1">
		<button type="submit" class="btn" style="background-color: #b10000; margin-top: 10px;">Termék törlése</button>
	</form>
</main>
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
  dropZone.classList.remove('dragover');
  const filesArray = Array.from(e.dataTransfer.files);
  const dataTransfer = new DataTransfer();
  filesArray.forEach(f => dataTransfer.items.add(f));
  fileInput.files = dataTransfer.files;
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
