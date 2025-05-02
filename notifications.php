<?php
require "KULSO/config.php";

if (!isset($_COOKIE['userid'])) {
    header("Location: login.php");
    exit();
}

$userid = intval($_COOKIE['userid']);
$current_page = basename($_SERVER['PHP_SELF']);

function formatHungarianDate($timestamp) {
    $date = new DateTime($timestamp);
    $now = new DateTime();
    $ev = $date->format('Y');
    $honapok = ['jan.', 'febr.', 'márc.', 'ápr.', 'máj.', 'jún.', 'júl.', 'aug.', 'szept.', 'okt.', 'nov.', 'dec.'];
    $honap = $honapok[(int)$date->format('n') - 1];
    return $ev == $now->format('Y')
        ? sprintf("%s %d. %s", $honap, $date->format('j'), $date->format('H:i'))
        : sprintf("%d. %s %d. %s", $ev, $honap, $date->format('j'), $date->format('H:i'));
}

$ertesitesek = [];

$stmt = $conn->prepare("SELECT n.message, n.created_at, n.type, n.fromid, n.productid, n.seen, u.username, p.name AS product_name FROM notifications n LEFT JOIN users u ON u.id = n.fromid LEFT JOIN products p ON p.id = n.productid WHERE n.toid = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$res1 = $stmt->get_result();
while ($row = $res1->fetch_assoc()) {
    $ertesitesek[] = [
        'type' => $row['type'],
        'message' => $row['message'],
        'created_at' => $row['created_at'],
        'fromid' => $row['fromid'],
        'productid' => $row['productid'],
        'username' => $row['username'],
        'product_name' => $row['product_name'],
        'seen' => $row['seen']
    ];
}

$stmt2 = $conn->prepare("SELECT m.senderid, m.productid, MAX(m.created_at) AS created_at, u.username, p.name AS product_name FROM messages m JOIN users u ON u.id = m.senderid JOIN products p ON p.id = m.productid WHERE m.receiverid = ? GROUP BY m.senderid, m.productid");
$stmt2->bind_param("i", $userid);
$stmt2->execute();
$res2 = $stmt2->get_result();
while ($row = $res2->fetch_assoc()) {
    $ertesitesek[] = [
        'type' => 'message',
        'message' => $row['username'] . " új üzenetet küldött Önnek a(z) '{$row['product_name']}' termékkel kapcsolatban.",
        'created_at' => $row['created_at'],
        'fromid' => $row['senderid'],
        'productid' => $row['productid'],
        'username' => $row['username'],
        'product_name' => $row['product_name'],
        'seen' => 0
    ];
}

usort($ertesitesek, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>CherryT | Értesítések</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/notifications.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            padding-top: 100px;
        }
    </style>
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
                <li><a href="search.php" class="nav-link <?= $current_page == 'search.php' ? 'active' : '' ?>">Felfedezés</a></li>
                <li><a href="contact.php" class="nav-link">Kapcsolat</a></li>
                <li><a href="information.php" class="nav-link">Tudnivalók</a></li>
                <li><a href="notifications.php" class="nav-link active"><i class="ri-notification-3-line"></i></a></li>
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
<?php } ?>

<div class="container fadeInSection">
    <h2>Értesítéseid</h2>
    <div class="notifications-box">
        <?php if (empty($ertesitesek)): ?>
            <p>Nincsenek értesítéseid.</p>
        <?php else: ?>
            <?php foreach ($ertesitesek as $ert): ?>
                <div class="notification <?= $ert['seen'] == 0 ? 'unseen' : '' ?>">
                    <?php if ($ert['type'] === 'message'): ?>
                        <i class="fa-solid fa-envelope"></i>
                    <?php elseif ($ert['type'] === 'favorite'): ?>
                        <i class="fa-solid fa-heart"></i>
                    <?php elseif ($ert['type'] === 'follow'): ?>
                        <i class="fa-solid fa-user-plus"></i>
                    <?php else: ?>
                        <i class="fa-solid fa-bell"></i>
                    <?php endif; ?>

                    <div class="text-content">
                        <p><?= preg_replace('/^([^\s]+)/u', '<strong>$1</strong>', htmlspecialchars($ert['message'])) ?></p>
                        <small title="<?= $ert['created_at'] ?>"><?= formatHungarianDate($ert['created_at']) ?></small>
                    </div>

                    <?php if ($ert['type'] === 'follow'):
                        $check = $conn->prepare("SELECT * FROM follows WHERE follower_id = ? AND followed_id = ?");
                        $check->bind_param("ii", $userid, $ert['fromid']);
                        $check->execute();
                        $isFollowing = $check->get_result()->num_rows > 0;
                    ?>
                        <button class="follow-btn <?= $isFollowing ? 'unfollow' : 'follow' ?>" data-userid="<?= $ert['fromid'] ?>">
                            <?= $isFollowing ? 'Követés leállítása' : 'Követés viszonzása' ?>
                        </button>
                    <?php elseif ($ert['type'] === 'message'): ?>
                        <a href="directmessage.php?userid=<?= $ert['fromid'] ?>&productid=<?= $ert['productid'] ?>" class="view-btn">Megtekintem</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.querySelectorAll('.follow-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const userId = this.dataset.userid;
        const self = this;

        fetch('follow-handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'userid=' + encodeURIComponent(userId)
        })
        .then(res => res.text())
        .then(text => {
            if (text === 'followed') {
                self.classList.remove('unfollow');
                self.classList.add('follow');
                self.textContent = 'Követés viszonzása';
            } else if (text === 'unfollowed') {
                self.classList.remove('follow');
                self.classList.add('unfollow');
                self.textContent = 'Követés leállítása';
            }
        });
    });
});

window.addEventListener('load', () => {
    fetch('mark-notifications-read.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    });
});
</script>
</body>
</html>
