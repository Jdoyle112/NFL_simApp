<?php
	require_once("config.php");
	session_start();

	$page = $_GET['page'];

	if(!isset($_SESSION['user'])){
		header("Location: " . BASE_URL . $page . "index.php");
	}else if(isset($_SESSION['user'])!=""){
		header("Location: " . BASE_URL . $page . "index.php");
	}

	if(isset($_GET['logout'])){
		session_destroy();
		unset($_SESSION['user']);
		header("Location: " . BASE_URL . $page . "index.php");
	}

?>