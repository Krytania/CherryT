<?php 
	require "KULSO/config.php";
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require_once 'src/Exception.php';
	require_once 'src/PHPMailer.php';
	require_once 'src/SMTP.php';

	function generateUniqueCode($conn) {
		do {
			$code = bin2hex(random_bytes(16));
			$stmt = $conn->prepare("SELECT id FROM codes WHERE code = ?");
			$stmt->bind_param("s", $code);
			$stmt->execute();
			$result = $stmt->get_result();
		} while ($result->num_rows > 0);
		
		return $code;
		
	}
	
	function sendEmail($to, $toName, $subject, $body) {
		$mail = new PHPMailer(true);

		try {
			$mail->CharSet = 'UTF-8';
			$mail->isSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = 'cherryt.noreply@gmail.com';
			$mail->Password = 'kcym xjql utjh lsnx';
			$mail->SMTPSecure = 'tls';
			$mail->Port = 587;

			$mail->setFrom('cherryt.noreply@gmail.com', 'CherryT');
			$mail->addAddress($to, $toName);
			$mail->addReplyTo('cherryt.noreply@gmail.com', 'CherryT');

			$mail->isHTML(true);
			$mail->Subject = $subject;
			$mail->Body    = $body;
			$mail->AltBody = strip_tags($body);

			$mail->send();
			return true;
		} catch (Exception $e) {
			return "Hiba: {$mail->ErrorInfo}";
		}
	}
?>