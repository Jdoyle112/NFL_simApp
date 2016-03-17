<?php

	session_start();
	require_once("../../config.php");

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
<body>
<center>
	<div id="login-form">
		<form method="post">
			<div class="message"><?php if($message!="") { echo $message; } ?></div>
			<table align="center" width="30%" border="0">
				<tr>
					<td><input type="text" name="uname" placeholder="User Name" required /></td>
				</tr>
				<tr>
					<td><input type="email" name="email" placeholder="Your Email" required /></td>
				</tr>
				<tr>
					<td><input type="password" name="pass" placeholder="Your Password" required /></td>
				</tr>
				<tr>
					<td><button type="submit" name="btn-signup">Register</button></td>
				</tr>
				<tr>
					<td><a href="index.php">Sign In Here</a></td>
				</tr>
			</table>
		</form>
	</div>
</center>
</body>
</html>