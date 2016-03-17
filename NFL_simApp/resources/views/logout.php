<?php
	require_once("../../config.php");
	session_start();

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