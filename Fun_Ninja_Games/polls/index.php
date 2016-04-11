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

	// get session poll values
	$question = $_SESSION['question'];
	$a1 = $_SESSION['a1'];
	$a2 = $_SESSION['a2'];
	$a3 = $_SESSION['a3'];
	$a4 = $_SESSION['a4'];
	$res = $_GET['res'];

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

	$page = "polls/";

	// handle poll voting results
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$vote = $_POST['poll'];

		// check if table is empty
		$count=$db->prepare("SELECT id from polls where user_id=:userId AND title = :question");
		$count->bindParam(":userId",$userId);
		$count->bindParam(":question",$question);
		$count->execute();
		$no=$count->rowCount();
		if($no > 0){
			// update record
			if($vote == 'answer1'){
				$sql="UPDATE polls SET answer1 = answer1 + 1 WHERE user_id = $userId AND title = '$question'";
				$db->exec($sql);
			}else if($vote == 'answer2'){
				$sql="UPDATE polls SET answer2 = answer2 + 1 WHERE user_id = $userId AND title = '$question'";
				$db->exec($sql);
			}else if($vote == 'answer3'){
				$sql="UPDATE polls SET answer3 = answer3 + 1 WHERE user_id = $userId AND title = '$question'";
				$db->exec($sql);
			}else if($vote == 'answer4'){
				$sql="UPDATE polls SET answer4 = answer4 + 1 WHERE user_id = $userId AND title = '$question'";
				$db->exec($sql);
			}

		} else {
			// inseert record
			$sql="INSERT INTO polls (user_id, title, $vote) VALUES ('$userId', '$question', 1)";
			$db->exec($sql);
		}

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
			<div class="poll">
				<?php if(!empty($question) AND !isset($res)){ ?>
					<legend><?php echo $question; ?></legend>
					<form method="post" action="index.php" name="vote">
						<div class="radio">
 							<label>							
							<input type="radio" name="poll" value="answer1" class="radio"><?php echo $a1; ?>
							</label>
						</div>							
						<?php if(!empty($a2) && $a2 != ""){ ?>
						<div class="radio">
 							<label>							
							<input type="radio" name="poll" value="answer2" class="radio"><?php echo $a2; ?>
							</label>
						</div>							
						<?php } ?>
						<?php if(!empty($a3) && $a3 != ""){ ?>
						<div class="radio">
 							<label>						
							<input type="radio" name="poll" value="answer3" class="radio"><?php echo $a3; ?>
							</label>
						</div>							
						<?php } ?>
						<?php if(!empty($a4) && $a4 != ""){ ?>
						<div class="radio">
 							<label>
							<input type="radio" name="poll" value="answer4" class="radio"><?php echo $a4; ?>
							</label>
						</div>
						<?php } ?>	
						<input type="submit" value="Send" class="btn btn-primary">				
					</form>
				<?php } else { ?>
				<legend><?php echo $question; ?></legend>
				<?php
					// get total votes
					$sql="SELECT answer1, answer2, answer3, answer4 FROM polls WHERE user_id = $userId AND title = '$question'";
					$results=$db->query($sql);
					$results=$results->fetch(PDO::FETCH_ASSOC);
					
					$tot1 = $results['answer1'];
					$tot2 = $results['answer2'];
					$tot3 = $results['answer3'];
					$tot4 = $results['answer4'];
					foreach ($results as $key => $value) {
						$totVotes += $value;
					}

					echo '<p>'.$a1.' ('.floor($tot1/$totVotes*100).'% - '.$tot1.' votes)'.'</p>';
					echo '<p class="bar" style="width: '.floor($tot1/$totVotes*100).'%;"></p>';
					if(!empty($a2) && $a2 != ""){ 
						echo '<p>'.$a2.' ('.floor($tot2/$totVotes*100).'% - '.$tot2.' votes)'.'</p>';
						echo '<p class="bar" style="width: '.floor($tot2/$totVotes*100).'%;"></p>';
					} 
					if(!empty($a3) && $a3 != ""){ 
						echo '<p>'.$a3.' ('.floor($tot3/$totVotes*100).'% - '.$tot3.' votes)'.'</p>';
						echo '<p class="bar" style="width: '.floor($tot3/$totVotes*100).'%;"></p>';
					}
					if(!empty($a4) && $a4 != ""){ 
						echo '<p>'.$a4.' ('.floor($tot4/$totVotes*100).'% - '.$tot4.' votes)'.'</p>';
						echo '<p class="bar" style="width: '.floor($tot4/$totVotes*100).'%;"></p>';
					} 
					
					echo 'Total Votes: ' . $totVotes . '<br>';
					
				}	
				?>
			</div>
			<div class="row links">
				<div class="col-md-6 col-xs-6 left">
					<a href="createPoll.php">Create Poll</a>
				</div>
				<div class="col-md-6 col-xs-6">
					<?php if(!isset($res)){ ?><a href="index.php?res">View Results</a><?php }else{ ?> <a href="index.php?">Vote</a><?php } ?>
				</div>				
			</div>
		</div>
	</div>
	<script type="text/javascript" src="scripts.js"></script>
	<script type="text/javascript" src="../settings.js"></script>
</body>
</html>