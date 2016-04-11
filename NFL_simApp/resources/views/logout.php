<?php
	require_once("../../config.php");
	session_start();
		$username = $_SESSION['user'];
	$userId = $_SESSION['userId']; 

	if(!isset($_SESSION['user'])){
		header("Location: " . BASE_URL . "index.php");
	}else if(isset($_SESSION['user'])!=""){
		header("Location: " . BASE_URL . "index.php");
	}

	if(isset($_GET['logout'])){
		session_destroy();
		unset($_SESSION['user']);
		header("Location: " . BASE_URL . "index.php");
	}

?>