<?php

	require("../../config.php");
	session_start();
	$username = $_SESSION['user'];
	$userId = $_SESSION['userId']; 

	$league = $_GET['league'];
	$userTeam = $_GET['team'];
	$seasonId = trim($_GET['seasId']);
	$team = $_POST['team'];

	// links
	$resources = "resources/views/";
	$link = '?team=' . $userTeam . '&league=' . $league . '&seasId=' . $seasonId;

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
			<div class="container">
				<div class="row">				
					<div class="heading col-md-6">
						<h1><?php echo $league; ?> Schedule</h1>
					</div>
					<div class="col-md-6">
						<form method="post" action="<?php echo "http://" . $_SERVER['HTTP_HOST'] . '/NFL_simApp/resources/views/leagueSchedule.php?league=' . $league . '&seasId=' . $seasonId; ?>">
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
				</div>
				<div>
					<ul class="list-group">
						<?php
							// get team id of selected
							$sql = "SELECT team_id FROM Teams WHERE name = '$team' AND season_id = $seasonId";
							$result = $db->query($sql);
							$result = $result->fetch(PDO::FETCH_ASSOC);
							$teamId = $result["team_id"];

							// get teams from schedule
							$sql = "SELECT home_team, away_team, week FROM Schedule WHERE home_team = $teamId OR away_team = $teamId";
							$result = $db->query($sql);

							//  Could be simplified with a Join?
							while($row = $result->fetch(PDO::FETCH_ASSOC)) {
								$home = $row['home_team'];
								$away = $row['away_team'];
								$wk = $row['week'];
								$sql = "SELECT name FROM Teams WHERE team_id = $home";
								$results = $db->query($sql);
								$home = $results->fetch(PDO::FETCH_ASSOC);
								$sql = "SELECT name FROM Teams WHERE team_id = $away";
								$results = $db->query($sql);
								$away = $results->fetch(PDO::FETCH_ASSOC);
								echo '<li class="list-group-item">' . 'Week ' . $wk . ': ' . $away['name'] . ' @ ' . $home['name'] . '</li>';

							}
						?>

					</ul>			
				</div>				
			</div>
		</div>
	</div>
</body>
</html>
