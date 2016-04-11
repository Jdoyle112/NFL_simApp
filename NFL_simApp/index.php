<?php

// TO_DO
	// link to a user profile

	require("config.php");
	session_start();
	$username = $_SESSION['user'];
	$userId = $_SESSION['userId']; 
	
	$pageTitle = "Home";
	$section = "home";

?>
<?php include(ROOT_PATH . 'resources/includes/header.php'); ?>
		<div class="main" style="background: url(public/imgs/tournoi.jpg) no-repeat center center; background-size: cover">
			<div class="container">
				<div class="main_content">
					<h1>GO BEYOND FANTASY FOOTBALL</h1>
					<p><strong>Sim Football</strong> is a fantasy football simulation game offering users a unique opportunity to act as a real NFL General Manager. Choose a team, manage your roster, and simulate your games in the realistic sim engine.</p>
					<div class="row">
						<div class="col-xs-6 col-md-6">
							<a class="btn btn-primary" style="float: right" href="resources/views/createLeague.php">Create a League</a>
						</div>
						<div class="col-xs-6 col-md-6">
							<a class="btn btn-primary" href="resources/views/register.php">Join Now!</a>
						</div>						
					</div>
				</div>	
			</div>
		</div>
	</body>
</html>