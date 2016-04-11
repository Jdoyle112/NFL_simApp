<?php
	
	session_start();
	require("../config.php");
	$username = $_SESSION['user'];
	$userId = $_SESSION['userId']; 
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		// get values
		$_SESSION['question'] = $_POST['title'];
		$_SESSION['a1'] = $_POST['a1'];
		$_SESSION['a2'] = $_POST['a2'];
		$_SESSION['a3'] = $_POST['a3'];
		$_SESSION['a4'] = $_POST['a4'];

		header('Location: index.php');
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Poll Generator</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="container">
		<div class="create">
			<h2>Create your Poll</h2>
			<form class="form" name="createPoll" method="post" action="<?php echo 'http://localhost:8888/Fun_Ninja_Games/polls/createPoll.php'; ?>">
				<input type="text" name="title" placeholder="Poll Question">
				<input type="text" name="a1" placeholder="Answer 1">
				<input type="text" name="a2" placeholder="Answer 2">
				<input type="text" name="a3" placeholder="Answer 3">
				<input type="text" name="a4" placeholder="Answer 4">
				<input type="submit" value="Send">
			</form>
		</div>
	</div>
</body>
</html>