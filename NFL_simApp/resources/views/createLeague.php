<?php

//	TO-DO
	// add # of teams ability
	// add logic for team player balance (enough healthy players, and not multiple studs per position)
	
	require("../../config.php");
	require('../includes/core/generateTeams.php');
	

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
		    	}else {
			    	$sql = "INSERT INTO Teams (name, season_id, city, team_abbrev, user) VALUES ('$teamName', '$seasonId', '$city', '$abrv', '0')";
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

		    } // end foreach teamNames


		    // re-direct page to new league page
		   // header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . '/NFL_simApp/resources/views/leagueDash.php?team=' . $userTeamName . '&league=' . $leagueName);
		   // exit;
	    }
	}

?>

<html>
	<body>
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

                <table>
                    <tr>
                        <th>
                            <label for="name">League Name</label>
                        </th>
                        <td>
                            <input type="text" name="league_name" id="name">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="team">Select a Team</label>
                        </th>
                        <td>
                          	  	<select id="team" name="team">
                          	  	<?php
                            		include('../includes/core/generateTeams.php');
				                	foreach ($teamNames as $key => $teams) { ?>
				                    	<option value="<?php echo $teams['name']; ?>"><?php echo $teams['name']; ?></option>	
				                	<?php	}  ?>                  	
                            	</select>
                        </td>
                    </tr> 


                </table>
                <input type="submit" value="Send">

			</form>
		</div>
	</body>
</html>
