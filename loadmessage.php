<?php
	require "KULSO/config.php";

	if(!isset($_COOKIE['userid'])){
		exit();
	}

	$felhasznaloid = $_COOKIE['userid'];
	$partner_userid = $_GET['userid'] ?? null;
	$productid = $_GET['productid'] ?? null;

	if(!$partner_userid || !$productid){
		exit();
	}

	//Partner neve
	$partner_result = $conn->query("SELECT username FROM users WHERE id = '$partner_userid'");
	$partner_name = ($partner_result && $partner_result->num_rows > 0) ? $partner_result->fetch_assoc()['username'] : "Ismeretlen";

	//Üzenetek lekérése
	$messages_result = $conn->query("
		SELECT * FROM messages 
		WHERE productid = '$productid' 
		AND (
			(senderid = '$felhasznaloid' AND receiverid = '$partner_userid') OR 
			(senderid = '$partner_userid' AND receiverid = '$felhasznaloid')
		)
		ORDER BY created_at ASC
	");
?>

<?php while($msg = $messages_result->fetch_assoc()):
    $is_me = ($msg['senderid'] == $felhasznaloid);
    $from = $is_me ? "Te" : $partner_name;
?>
    <div class="chat-message <?= $is_me ? 'me' : 'partner' ?>">
        <div class="chat-bubble">
            <span class="sender"><?= $from ?>:</span>
            <?= nl2br(htmlspecialchars($msg['message'])) ?>
            <div class="timestamp"><?= $msg['created_at'] ?></div>
        </div>
    </div>
<?php endwhile; ?>
