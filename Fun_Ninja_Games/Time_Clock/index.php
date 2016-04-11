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

	// retrieve form values
	if($_POST['teams'] && !empty($_POST['teams'])){
		$_SESSION['teams'] = $_POST['teams'];
	} else if($_POST['timer'] && !empty($_POST['timer'])){
		$_SESSION['timer'] = $_POST['timer'];
	}

	// set page
	$page = "time_clock/";

?>
<!DOCTYPE html>
<html>
<head>
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
			echo 'background: url("' . $background . '") no-repeat center center;';
			echo 'background-size: cover;';
			echo '}';			
		} else if($selBg != ""){

			echo 'body {';
			echo 'background: url("' . $selBg . '") no-repeat center center;';
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
		<div class="content">
			<form class="form-inline" method="post" action="<?php echo 'http://localhost:8888/Fun_Ninja_Games/Time_Clock/index.php'; ?>">
				<select class="c-select" name="timer" id="timer">
					<option value="stop" selected>Stopwatch</option>
					<option value="timer">Timer</option>
				</select>
				<input type="submit" value="Send">
			</form>
			<div id="controls">
				<button id="startPause" onclick="startPause()">Start</button>
				<button onclick="reset()">Reset</button>
			</div>
			<div class="output">
				<?php
					// set timer start
					if(isset($_SESSION['timer']) && !empty($_SESSION['timer'])){
						if($_SESSION['timer'] == 'timer'){
							echo '<div class="row">';
							echo '<div class="buttons">';
							echo '<button type="button" id="up"><i class="fa fa-chevron-up"></i></button>';
							echo '<button type="button" id="down"><i class="fa fa-chevron-down"></i></button>';
							echo '</div>';
							echo '<div class="time">';
							echo '<p id="output" class="timer">00:00:00</p>';
							echo '</div>';
							echo '</div>';
						}else {
							echo '<p id="output" class="stop">00:00:00</p>';
						}
					}
				?>
			</div>
		</div>

		<div class="teams">
			<h3>Number of Teams</h3>
			<form class="form-inline" method="post" action="<?php echo 'http://localhost:8888/Fun_Ninja_Games/Time_Clock/index.php'; ?>">
				<select class="c-select" name="teams" id="teams">
					<option value="1">1</option>
					<option value="2" selected>2</option>
					<option value="3">3</option>
					<option value="4">4</option>
				</select>
				<input type="submit" value="Send">
			</form>
		</div>

		<div class="points">
			<?php
				if(isset($_SESSION['teams']) && !empty($_SESSION['teams'])){
					$cols = 12 / $_SESSION['teams'];
					echo '<div class="row">';
					for($i = 1; $i <= $_SESSION['teams']; $i++){ ?>
						<div class="col-md-<?php echo $cols; ?>">
							<button type="button" id="b<?php echo $i; ?>">Team <?php echo $i; ?></button>
							<p class="score" id="p<?php echo $i; ?>"></p>
						</div>
					<?php } ?>
					</div>
				<?php } ?>
		</div>	
	</div>
	<script type="text/javascript" src="scripts.js"></script>

</body>
</html>



