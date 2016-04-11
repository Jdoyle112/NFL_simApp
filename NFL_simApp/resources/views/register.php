<?php

	session_start();
	require_once("../../config.php");
		$username = $_SESSION['user'];
	$userId = $_SESSION['userId']; 

	if(isset($_SESSION['user'])!=""){
		header("Location:" . BASE_URL . "index.php");
	}

	$message = "";
	if(isset($_POST['btn-signup'])){
		$uname = $_POST['uname'];
		$email = $_POST['email'];
		$upass = md5($_POST['pass']);

		$sql = "INSERT INTO Users (username, email, password) VALUES ('$uname', '$email', '$upass')";
		$count = $db->exec($sql);
		if($count == 0){
			$message = "There was an error with your registration. Please try agin.";
		} else {
			header("Location:" . BASE_URL . "index.php");
			exit;
		}
	}

?>
<html>
	<head>
		<link rel="stylesheet" href="style.css" type="./public/css/styles.css" />

	</head>
<body class="login_body">
	<?php include(ROOT_PATH . 'resources/includes/header.php'); ?>
		<form method="post">
			<div class="message"><?php if($message!="") { echo $message; } ?></div>
			<label>Register an Account</label>
			<input class="form-control" type="text" name="uname" placeholder="User Name" required />
				<input class="form-control" type="email" name="email" placeholder="Your Email" required />
	
				<input class="form-control" type="password" name="pass" placeholder="Your Password" required />
				<button type="submit" name="btn-signup" class="btn btn-primary">Register</button>
				<a href="login.php">Sign in Here</a>
		</form>
	</div>
</center>
</body>
</html>