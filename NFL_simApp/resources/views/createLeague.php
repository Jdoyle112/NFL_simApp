<?php

//	TO-DO
	// add # of teams ability
	// add logic for team player balance (enough healthy players, and not multiple studs per position)
	// cannot create league name that already exists?
	// create schedule with league
	
	require("../../config.php");
	require('../includes/core/generateTeams.php');
	session_start();
	$username = $_SESSION['user'];
	$userId = $_SESSION['userId']; 

	if(isset($username)){
		if ($_SERVER["REQUEST_METHOD"] == "POST") {

		    // gather input data and store in variables
		    $leagueName = trim($_POST["league_name"]);
		    $userTeamName = trim($_POST["team"]);
		    $timestamp = date('Y-m-d');
		    $year = date('Y');

		    // all fields required
		    if ($leagueName == "" OR $userTeamName == "") {
		        $error_message = "You must specify a value for all fields.";
		    }

		    if (!isset($error_message)){
		    	
		    	// insert new league into leagues table
		    	$sql = "INSERT INTO Leagues (name, active, date_created) VALUES ('$leagueName', '1', '$timestamp')";
		    	$db->exec($sql);
		    	$leagueId = $db->lastInsertId();

		   		// insert new season into seasons table
		    	$sql = "INSERT INTO Seasons (year, league_id) VALUES ('$year', '$leagueId')";
		    	$db->exec($sql);
		    	$seasonId = $db->lastInsertId();

		    	// get all players
		    	$sql = "SELECT player_id, position_id FROM Players";
		    	$results = $db->query($sql);
		    	$allPlayers = $results->fetchAll(PDO::FETCH_ASSOC);
		    	$tempPlayers = $allPlayers;
		    	shuffle($tempPlayers);
		    	// loop through each player and put their id's into new arrays grouped by position
		    	foreach($tempPlayers as $value){
		    		$randPlayer[] = array_pop($tempPlayers);
		    		if($value['position_id'] == 0){
						$qbId[] = $value['player_id'];
		    		}
		    		if($value['position_id'] == 1){
						$rbId[] = $value['player_id'];
		    		}
		    		if($value['position_id'] == 2){
						$wrId[] = $value['player_id'];
		    		}	  	 
		    		if($value['position_id'] == 3){
						$teId[] = $value['player_id'];
		    		}		
		    		if($value['position_id'] == 4){
						$otId[] = $value['player_id'];
		    		}
		    		if($value['position_id'] == 5){
						$gId[] = $value['player_id'];
		    		}
		    		if($value['position_id'] == 6){
						$cId[] = $value['player_id'];
		    		}	  
		    		if($value['position_id'] == 7){
						$dlId[] = $value['player_id'];
		    		}
		    		if($value['position_id'] == 8){
						$lbId[] = $value['player_id'];
		    		}
		    		if($value['position_id'] == 9){
						$dbId[] = $value['player_id'];
		    		}  			    			    		    		   			    		
		    	}


		    	// insert new teams into teams table
			    foreach ($teamNames as $key => $teams) {
			    	$teamName = $teams['name'];
			    	$city = $teams['city'];
			    	$abrv = $teams['abrv'];
			    	$divId = $teams['division'];
			    	if($userTeamName == $teamName){
				    	$sql = "INSERT INTO Teams (name, division_id, season_id, city, team_abbrev, user) VALUES ('$teamName', '$divId', '$seasonId', '$city', '$abrv', '1')";
				    	$db->exec($sql);
				    	$teamId = $db->lastInsertId();
				    	$userTeamId = $db->lastInsertId(); 		
			    	}else {
				    	$sql = "INSERT INTO Teams (name, division_id, season_id, city, team_abbrev, user) VALUES ('$teamName', '$divId', '$seasonId', '$city', '$abrv', '0')";
		    			$db->exec($sql);	
		    			$teamId = $db->lastInsertId();	   
			    	}

			    	// For each team, pop off 1 qb, 2 rb's etc and insert into TeamPlayers db
			    		// will need to add logic so teams are somewhat balanced
			    	// qb's
			    	for ($p=0; $p < 1; $p++) { 
			    		$newPlayerId = array_pop($qbId);
			    		$sql = "INSERT INTO TeamPlayers (team_id, player_id) VALUES ('$teamId', '$newPlayerId')";
			    		$db->exec($sql);		    		
			    	}
			    	// rb's
			    	for ($p=0; $p < 2; $p++) { 
			    		$newPlayerId = array_pop($rbId);
			    		$sql = "INSERT INTO TeamPlayers (team_id, player_id) VALUES ('$teamId', '$newPlayerId')";
			    		$db->exec($sql);
			    	}	
			    	// wr's
			    	
			    	for ($p=0; $p<2; $p++){
			    		$newPlayerId = array_pop($wrId);
			    		$sql = "INSERT INTO TeamPlayers (team_id, player_id) VALUES ('$teamId', '$newPlayerId')";
			    		$db->exec($sql);		    		
			    	}
			    	// te's	    	
			    	for ($p=0; $p<1; $p++){
			    		$newPlayerId = array_pop($teId);
			    		$sql = "INSERT INTO TeamPlayers (team_id, player_id) VALUES ('$teamId', '$newPlayerId')";
			    		$db->exec($sql);		    		
			    	}	
			    	// OT's   	
			    	for ($p=0; $p<2; $p++){
			    		$newPlayerId = array_pop($otId);
			    		$sql = "INSERT INTO TeamPlayers (team_id, player_id) VALUES ('$teamId', '$newPlayerId')";
			    		$db->exec($sql);		    		
			    	}
			    	// G's	
			    	for ($p=0; $p<2; $p++){
			    		$newPlayerId = array_pop($gId);
			    		$sql = "INSERT INTO TeamPlayers (team_id, player_id) VALUES ('$teamId', '$newPlayerId')";
			    		$db->exec($sql);		    		
			    	}	
			    	// C's	
			    	for ($p=0; $p<1; $p++){
			    		$newPlayerId = array_pop($cId);
			    		$sql = "INSERT INTO TeamPlayers (team_id, player_id) VALUES ('$teamId', '$newPlayerId')";
			    		$db->exec($sql);		    		
			    	}	
			    	// DL   	
			    	for ($p=0; $p<4; $p++){
			    		$newPlayerId = array_pop($dlId);
			    		$sql = "INSERT INTO TeamPlayers (team_id, player_id) VALUES ('$teamId', '$newPlayerId')";
			    		$db->exec($sql);		    		
			    	}
			    	// LB's	
			    	for ($p=0; $p<3; $p++){
			    		$newPlayerId = array_pop($lbId);
			    		$sql = "INSERT INTO TeamPlayers (team_id, player_id) VALUES ('$teamId', '$newPlayerId')";
			    		$db->exec($sql);		    		
			    	}	
			    	// DB's	
			    	for ($p=0; $p<4; $p++){
			    		$newPlayerId = array_pop($dbId);
			    		$sql = "INSERT INTO TeamPlayers (team_id, player_id) VALUES ('$teamId', '$newPlayerId')";
			    		$db->exec($sql);		    		
			    	}	
			    	

			    } // end foreach teamNames


			    // create new row in userteams table
			    $sql = "INSERT INTO UserTeams (team_id, user_id) VALUES ('$userTeamId', '$userId')";
		    	$db->exec($sql);

		    	// create schedule for all teams
		    	include(ROOT_PATH . 'resources/includes/core/schedule.php');
		    	$newSched = new Schedule();
				$newSched->createSchedule();

			    // re-direct page to new league page
			    //header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . '/NFL_simApp/resources/views/leagueDash.php?team=' . $userTeamName . '&league=' . $leagueName . '&seasId=' . $seasonId);
			    //exit;
		    }
		}
	} else {
		$error_message = "You must be logged in to create a league.";
	}

?>
<?php include(ROOT_PATH . 'resources/includes/header.php'); ?>
	<div class="container create_container">
		<h1>Create a New League</h1>
		    <?php
                if (!isset($error_message)) {
                    echo '<p>Please fill out all the fields below to create your league, and start playing!</p>';
                } else {
                    echo '<p class="message">' . $error_message . '</p>';
                }
            ?>
		<div>
			<form method="post" action="<?php echo "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>/">
                <label for="name">League Name</label>
                <input class="form-control" type="text" name="league_name" id="name">
               
                <label for="team">Select a Team</label>
                <select class="form-control" id="team" name="team">
                <?php
                    include('../includes/core/generateTeams.php');
				    foreach ($teamNames as $key => $teams) { ?>
				    	<option value="<?php echo $teams['name']; ?>"><?php echo $teams['name']; ?></option>	
				    <?php	}  ?>                  	
                </select>
                <input class="btn btn-primary" type="submit" value="Send">
			</form>
		</div>
		<?php if(!isset($username)){ 
			//include 'login.php';
			echo '<p>You must be logged in to view your teams!</p>';
			echo '<a href="login.php">Log in Here</a>';
		} else{ ?>
			<div class="myLeagues">
				<h1>My Leagues</h1>
					<?php
						// get team id's from userTeams
						$sql = "SELECT team_id FROM UserTeams WHERE user_id = $userId";
						$results = $db->query($sql);
						$userTeamId = $results->fetchAll(PDO::FETCH_ASSOC);

						// get user team names and season id's
						foreach ($userTeamId as $value) {
							$userTeamId = $value['team_id'];
							$sql = "SELECT name, season_id FROM Teams WHERE team_id = $userTeamId";
							$results = $db->query($sql);
							$userTeams[] = $results->fetchAll(PDO::FETCH_ASSOC);
						}

						foreach ($userTeams as $value) {
							foreach ($value as $val) { 		
									$seasId = $val['season_id'];
									$sql = "SELECT league_id FROM Seasons WHERE season_id = $seasId";
									$results = $db->query($sql);
									$lgsId = $results->fetchAll(PDO::FETCH_ASSOC);
									//var_dump($lgsId);
									
									$lgsId = $lgsId[0]['league_id'];
									//echo 'league id: ' . $lgsId . '<br>';
									$sql = "SELECT name FROM Leagues WHERE league_id = $lgsId";
									$results = $db->query($sql);
									$lgName = $results->fetchAll(PDO::FETCH_ASSOC);		
									//var_dump($lgName);
									$lgName = $lgName[0]['name'];							
								?>
								<div class="league">
								<a href="<?php echo BASE_URL . 'resources/views/leagueDash.php?team=' . $val['name'] . '&league=' . $lgName . '&seasId=' . $seasId; ?>">
									<h4><?php echo $val['name']; ?></h4>
									<p><?php echo $lgName; ?></p>
								</a>
								</div>
							<?php } 
						}

					?>
			</div>
		<?php } ?>	
		</div>
	</body>
</html>





