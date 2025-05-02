<?php
	setcookie("userid", '', time()+3600, "/");
	header("Location: index.php");
?>