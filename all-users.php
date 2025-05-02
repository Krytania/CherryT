<?php

	require "KULSO/config.php";

	if(!isset($_COOKIE['userid'])){
		header("Location: login.php");
		exit();
	}

	$userid = (int)$_COOKIE['userid'];

	$users_result = $conn->query("SELECT id, username FROM users WHERE id != $userid");

	$kovetek_result = $conn->query("SELECT followed_id FROM follows WHERE follower_id = $userid");
	$kovetek = [];
	if($kovetek_result && $kovetek_result->num_rows > 0){
		while ($row = $kovetek_result->fetch_assoc()) {
			$kovetek[] = $row['followed_id'];
		}
	}

	$ratings_result = $conn->query("SELECT reviewed_id, AVG(rating) AS avg_rating FROM reviews GROUP BY reviewed_id");
	$ratings = [];
	if($ratings_result){
		while($row = $ratings_result->fetch_assoc()){
			$ratings[(int)$row['reviewed_id']] = round($row['avg_rating'], 1);
		}
	}

	$messages_result = $conn->query("SELECT DISTINCT receiverid FROM messages WHERE senderid = $userid UNION SELECT DISTINCT senderid FROM messages WHERE receiverid = $userid");
	$messaged_users = [];
	if($messages_result){
		while($row = $messages_result->fetch_assoc()){
			foreach ($row as $val) {
				$messaged_users[] = $val;
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Felhasználók</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/all-users.css">
</head>
<body>
    <h1>Felhasználók</h1>
    <?php while($row = $users_result->fetch_assoc()):
        $uid = $row['id'];
        $username = htmlspecialchars($row['username']);

        $rating_html = "";
        if(isset($ratings[$uid])){
            $rating = $ratings[$uid];
            $full = floor($rating);
            $half = ($rating - $full) >= 0.5 ? 1 : 0;
            for($i = 0; $i < $full; $i++) $rating_html .= "⭐";
            if($half) $rating_html .= "⭐️";
            for($i = $full + $half; $i < 5; $i++) $rating_html .= "☆";
        } else {
            $rating_html = "Nincs értékelés";
        }

        $kovet = in_array($uid, $kovetek);
        $follow_class = $kovet ? "unfollow" : "follow";
        $follow_text = $kovet ? "Követés leállítása" : "Követés";

        $review_button = "";
        if(in_array($uid, $messaged_users)){
            $review_button = "<a href='send-review.php?user=$uid' class='btn-review'>Vélemény írása</a>";
        }
    ?>
        <div class="user-card">
            <h3><?= $username ?></h3>
            <div class="rating"><?= $rating_html ?></div>
            <div class="button-row">
                <button class="follow-btn follow-toggle <?= $follow_class ?>" data-userid="<?= $uid ?>">
                    <?= $follow_text ?>
                </button>
                <?= $review_button ?>
            </div>
        </div>
    <?php endwhile; ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".follow-toggle").forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            const userId = this.dataset.userid;
            const formData = new FormData();
            formData.append("id", userId);

            fetch("follow-toggle.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.status === "followed") {
                        this.classList.remove("follow");
                        this.classList.add("unfollow");
                        this.textContent = "Követés leállítása";
                        this.style.backgroundColor = "#fff";
                        this.style.color = "#840d46";
                        this.style.border = "2px solid #840d46";
                    } else {
                        this.classList.remove("unfollow");
                        this.classList.add("follow");
                        this.textContent = "Követés";
                        this.style.backgroundColor = "#840d46";
                        this.style.color = "#fff";
                        this.style.border = "2px solid #840d46";
                    }
                }
            })
            .catch(err => {
                console.error("Hiba történt:", err);
            });
        });
    });
});
</script>
	<a href="javascript:history.back()" title="Vissza" style="
		position: absolute;
		top: 20px;
		left: 20px;
		width: 40px;
		height: 40px;
		background-color: #840d46;
		color: white;
		font-size: 20px;
		text-align: center;
		line-height: 40px;
		border-radius: 50%;
		text-decoration: none;
		box-shadow: 0 2px 6px rgba(0,0,0,0.2);
		transition: background 0.3s ease;
		z-index: 1000;
	">
		&#8592;
	</a>
</body>
</html>
