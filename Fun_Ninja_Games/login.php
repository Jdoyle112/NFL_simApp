<?php

// TO_DO
	// add remember password
	// add minimum requirments for password
	// add mo duplicate usernames, or emails

	session_start();

	$url = $_SERVER['REQUEST_URI'];
	$str = "login";
	$str2 = 'create';
	$page = $_GET['page'];

	// check which page is accessing
	if(strpos($url, $str) !== false){
		require("config.php");
	} 

	if(isset($_SESSION['user'])!=""){
		header("Location:" . BASE_URL . $page . "index.php");
	}

	$message="";
	if(isset($_POST['submit'])){
		$uname = trim($_POST['uname']);
		$upass = trim($_POST['pass']);
		$sql = "SELECT * FROM users WHERE username = '$uname'";	
		$result = $db->query($sql);
		$results = $result->fetchAll(PDO::FETCH_ASSOC);
		// authenticate username by matching password
		foreach ($results as $value) {
			if($value['password'] == md5($upass)){
				$message = "You are successfully logged-in!";
				$_SESSION['userId'] = $value['user_id'];
				$_SESSION['user'] = $value['username'];
				
			}else {
				$message = "Invalid Username or Password!";
			}
		}
		// if on login page, re-direct to home page after successful login
		if(strpos($url, $str) !== false){
			header("Location: " . BASE_URL . $page . "index.php");
			exit;	
		} 
		// refresh createLeague page forcing my leagues to be displayed instead of login
		/*if(strpos($url, $str2) !== false){
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=createLeague.php">';
		}*/
	}

?>

<html>
<head>
	<title>User Login</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
	<body>
		<form name="frmUser" method="post" action="<?php echo "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>/">
			<div class="message"><?php if($message!="") { echo $message; } ?></div>
			<table border="0" cellpadding="10" cellspacing="1" width="500" align="center">
				<tr class="tableheader">
					<td align="center" colspan="2">Enter Login Details</td>
				</tr>
				<tr class="tablerow">
					<td align="right">Username</td>
					<td><input type="text" name="uname"></td>
				</tr>
				<tr class="tablerow">
					<td align="right">Password</td>
					<td><input type="password" name="pass"></td>
				</tr>
				<tr class="tableheader">
					<td align="center" colspan="2"><input type="submit" name="submit" value="Submit"></td>
				</tr>
			</table>
		</form>

	</body>
</html>