<?php 

session_start(); 


if (!isset($_SESSION['id'])){
	header("Location: index.php");
	die();
}

unset($_SESSION['id']);
unset($_SESSION['login']);

session_destroy();
header("Location: index.php");
?>
