<?php

// TO_DO
	// link to a user profile

	require("config.php");
	session_start();
	$username = $_SESSION['user'];
	$userId = $_SESSION['userId']; 
	
	$pageTitle = "Home";
	$section = "home";
	//include 'resources/includes/header.php';

?>
<?php include(ROOT_PATH . 'resources/includes/header.php'); ?>
	<a href="<?php if(isset($username)){ echo "#"; } else{ echo BASE_URL . "resources/views/login.php"; } ?>"><?php if(isset($username)){echo $username;} else{ echo "Login"; } ?></a><a href="<?php if(isset($username)){echo BASE_URL . "resources/views/logout.php?logout";}else {echo BASE_URL . "resources/views/register.php";}  ?>"><?php if(isset($username)){ echo "Logout";}else{ echo 'Register';} ?></a>
	<h1>HEllo!</h1>
	<a href="<?php echo BASE_URL . "resources/views/createLeague.php"; ?>">Leagues</a>


	</body>

</html>