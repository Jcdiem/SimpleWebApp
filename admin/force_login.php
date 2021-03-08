<?php
session_start();

if(!$_SESSION['username']) {
	header("Location: login.php?redirect={$_SERVER['REQUEST_URI']}");
	exit();
}
?>
