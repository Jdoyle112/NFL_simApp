<?php
	session_start();
	require("../config.php");
	$username = $_SESSION['user'];
	$userId = $_SESSION['userId']; 

	// retreive custom styles and uploads
	$title = $_SESSION['title'];
	$color = "#" . $_SESSION['color'];
	$background = $_SESSION['background'];
	$logo = $_SESSION['logo'];
	$message = $_GET['msg'];
	$reset = $_GET['reset'];
	$selLogo = $_SESSION['selLogo'];
	$selBg = $_SESSION['selBg'];

	// reset custom settings
	if(isset($reset) AND $reset != ""){
		$title = "";
		$color = "";
		$background = "";
		$logo = "";
		$selLogo = "";
		$selBg = "";
	}

	$reset = NULL;

	$page = "dice/";

	// find form submitted
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['num']) AND !empty($_POST['num'])){
			$_SESSION['num'] = $_POST['num'];
			
		}
		if(isset($_POST['words'])){
			$_SESSION['words'] = $_POST['words'];

			// insert into DB
			$words = explode(',', $_SESSION['words']);
			$cnt = count($words);
			for($i = 0; $i < $cnt; $i++){
				$sql = $db->prepare("INSERT INTO words (user_id, word) VALUES (:userId, :words)");
				$sql->bindParam(':userId', $userId);
				$sql->bindParam(':words', $words[$i]);
				$sql->execute();
			}
		}

		if(isset($_POST['dice'])){
			$_SESSION['numDice'] = $_POST['dice'];
		}	

		if(isset($_POST['reset'])){
			$_SESSION['num'] = NULL;
			$_SESSION['words'] = NULL;
		}
	}	

?>
<!DOCTYPE html>
<html>
<head>
	<title>Dice Machine</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<style type="text/css">
	<?php
		// change background img
		if($background != ""){

			echo 'body {';
			echo 'background: url("' . '../' . $background . '") no-repeat center center;';
			echo 'background-size: cover;';
			echo '}';			
		} else if($selBg != ""){

			echo 'body {';
			echo 'background: url("' . '../' . $selBg . '") no-repeat center center;';
			echo 'background-size: cover;';
			echo '}';			
		}

		// change background color
		if($color != "" AND $background == "" AND $selBg == ""){
			echo 'body {';
			echo 'background-color: ' . $color . ';';
			echo '}';
		}

	?>
	</style>
</head>
<body>
	<?php include '../header.php'; ?>
	<div class="container">
		<div class="header">
		</div>
		<div class="content">
			<form class="form-inline" method="post" action="<?php echo 'http://localhost:8888/Fun_Ninja_Games/dice/index.php'; ?>">
				<select class="c-select" name="dice" id="numDice">
					<option value="1" selected>1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
				</select>
				<input type="submit" value="Send">
			</form>
			<button type="button" id="roll">Roll Dice</button>
			<div id="output" class="dice">
				<?php 
					if(!empty($_SESSION['numDice'])){
						$cols = floor(12 / $_SESSION['numDice']);
						echo '<div class="row">';
						for($i = 1; $i <= $_SESSION['numDice']; $i++){ ?>
							<div class="col-md-<?php echo $cols; ?>">
								<p class="numDice" id="p<?php echo $i; ?>"></p>
							</div>
						<?php } ?>
						</div>
					<?php } ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<form method="post" action="<?php echo 'http://localhost:8888/Fun_Ninja_Games/dice/index.php'; ?>">
					<p>Enter Words for Random Word Generator</p>
					<textarea name="words" id="words" placeholder="Enter Words separated by commas"></textarea>
					<p>Enter Max Number for Rnadom # Picker</p>
					<input type="number" name="num" id="num" placeholder="Enter #">
					<input type="submit" value="Send">
				</form>
			</div>
			<div class="col-xs-12 col-md-6">
				<p>Random Number: </p>
				<?php 
					if(!empty($_SESSION['num'])){
						$randNum = mt_rand(1, $_SESSION['num']);
						echo '<p class="randP">' . $randNum . '</p>';
					}
				?>
				<p>Random Word: </p>
				<?php 
					if(!empty($_SESSION['words'])){
						$sql = "SELECT word FROM words WHERE user_id = $userId";
						$results = $db->query($sql);
						$results = $results->fetchAll(PDO::FETCH_ASSOC);
						$cnt = count($results);
						$randWord = mt_rand(0, $cnt - 1);
						$randWord = $results[$randWord]['word'];
						// print random word
						echo '<p class="randP">'.$randWord.'</p>';
					}
				?>	
				<form method="post" action="<?php echo 'http://localhost:8888/Fun_Ninja_Games/dice/index.php'; ?>">
					<input type="submit" name="reset" value="Reset">
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="scripts.js"></script>
	<script type="text/javascript" src="../settings.js"></script>
</body>
</html>


