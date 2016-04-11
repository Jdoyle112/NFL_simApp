<?php

	require("../../config.php");
	session_start();

	$league = $_GET['league'];
	$userTeam = $_GET['team'];
	$seasonId = $_GET['seasId'];
	$team = $_POST['team'];

	// links
	$resources = "resources/views/";
	$link = '?team=' . $userTeam . '&league=' . $league . '&seasId=' . $seasonId . '&team=' . $userTeam;


	if(!isset($team)){
		// get user team id
		$sql = "SELECT team_id FROM Teams WHERE user = 1 AND season_id = $seasonId";
		$results = $db->query($sql);
		$teamId = $results->fetch(PDO::FETCH_ASSOC);
		$teamId = $teamId['team_id'];
	} else {
		// get cpu team id
		$sql = "SELECT team_id FROM Teams WHERE season_id = $seasonId AND name = '". $team ."'";
		$results = $db->query($sql);
		$teamId = $results->fetch(PDO::FETCH_ASSOC);
		$teamId = $teamId['team_id'];	
	}

	// get team players id's
	$sql = "SELECT * FROM TeamPlayers WHERE team_id = $teamId";
	$results = $db->query($sql);
	$tmPlayers = $results->fetchAll(PDO::FETCH_ASSOC);
	foreach ($tmPlayers as $value) {
		$teamPlayersId[] = $value['player_id'];
	}

	// get all players on user team using player id
	foreach ($teamPlayersId as $value) {
		$sql = "SELECT * FROM Players WHERE player_id = $value";
		$results = $db->query($sql);
		$teamPlayers[] = $results->fetchAll(PDO::FETCH_ASSOC);
	}

?>
<?php include(ROOT_PATH . 'resources/includes/header.php'); ?>

	<div class="container">
		<div class="sidebar">
			<div class="side_items">
				<ul>
					<br>
	
					<li><a href="<?php echo BASE_URL . $resources . 'leagueDash.php' . $link; ?>">Dashboard</a></li>
					<br><br>
					<p><strong>LEAGUE</strong></p>
					<li><a href="<?php echo BASE_URL . $resources . 'standings.php' . $link; ?>">Standings</a></li>
					<li><a href="<?php echo BASE_URL . $resources . 'transactions.php' . $link; ?>">Transactions</a></li>
					<br><br>
					<p><strong>TEAM</strong></p>					
					<li><a href="<?php echo BASE_URL . $resources . 'rosters.php' . $link; ?>">Rosters</a></li>
					<li><a href="<?php echo BASE_URL . $resources . 'leagueSchedule.php' . $link; ?>">Schedule</a></li>
					<li><a href="<?php echo BASE_URL . $resources . 'allPlayers.php' . $link; ?>">All Players</a></li>
					<br><br>
					<p><strong>STATS</strong></p>
					<li><a href="<?php echo BASE_URL . $resources . 'playerStats.php' . $link; ?>">Player Stats</a></li>
					<li><a href="<?php echo BASE_URL . $resources . 'teamStats.php' . $link; ?>">Team Stats</a></li>
					<li><a href="<?php echo BASE_URL . $resources . 'gameStats.php' . $link; ?>">Game Stats</a></li>
				</ul>
			</div>
		</div>
		<div class="content">
			<div class="heading">
				<h1><?php if(isset($team)){echo $team;}else{echo $userTeam;}; ?> Roster</h1>
			</div>

			<div class="players_form">
				<form method="post" action="<?php echo "http://" . $_SERVER['HTTP_HOST'] . '/NFL_simApp/resources/views/rosters.php' . $link; ?>">
					<select id="team" name="team">
		            <?php
		                include('../includes/core/generateTeams.php');
						    foreach ($teamNames as $key => $teams) { ?>
						        <option value="<?php echo $teams['name']; ?>"><?php echo $teams['name']; ?></option>	
					<?php	}  ?>                  	
		            </select>
		            <input type="submit" value="Send">
				</form>
			</div>
				<table class="table table-responsive table-bordered">
					<thead>
						<tr>
							<th>Name</th>
							<th>Position</th>
							<th>Overall</th>
							<th>Health</th>
						</tr>
					</thead>
					<tbody>		
						<?php foreach ($teamPlayers as $key => $value){ ?>
							<?php foreach ($value as $row){ ?>
								<tr>
								    <td><?php echo $row['first_name'] . " "; ?><?php echo $row['last_name']; ?></td>
								    <td><?php echo $row['pos_abrv']; ?></td>
								    <td><?php echo $row['overall']; ?></td>
								    <td><?php echo $row['health']; ?></td>
								</tr>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>				
		</div>
	</div>
	

</body>
</html>