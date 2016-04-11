<?php

	require("../../config.php");
	session_start();
	$username = $_SESSION['user'];
	$userId = $_SESSION['userId']; 

	$league = $_GET['league'];
	$userTeam = $_GET['team'];
	$seasonId = trim($_GET['seasId']);
	$team = $_POST['team'];

	if(isset($team)){

		// get team id
		$sql = "SELECT team_id FROM Teams WHERE name = '$team' AND season_id = $seasonId";
		$results = $db->query($sql);
		$results = $results->fetch(PDO::FETCH_ASSOC);
		$teamId = $results['team_id'];

		// get games from schedule
		$sql = "SELECT game_id FROM Schedule WHERE home_team = $teamId OR away_team = $teamId AND season_id = $seasonId AND completed = 1";
		$results = $db->query($sql);
		$results = $results->fetchAll(PDO::FETCH_ASSOC);
		$gameId = $results['game_id'];

		foreach ($results as $value) {
			$gameId[] = $value['game_id'];
		}	
	}

	// links
	$resources = "resources/views/";
	$link = '?team=' . $userTeam . '&league=' . $league . '&seasId=' . $seasonId . '&team=' . $userTeam;

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
				<div class="row">
					<div class="col-md-8">
						<h1><?php echo $league; ?> Game Stats</h1>
					</div>
					<div class="col-md-4">
						<form method="post" action="<?php echo "http://" . $_SERVER['HTTP_HOST'] . '/NFL_simApp/resources/views/gameStats.php?league=' . $league . '&seasId=' . $seasonId; ?>">
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
			</div>

			<div>
				<table class="table table-responsive table-bordered">
					<thead>
						<th>Opponent</th>
						<th>W/L</th>
						<th>Score</th>
					</thead>
					<tbody>
						<?php

							// get game outcomes and teams from GameStats
							foreach ($gameId as $value) {
								
								$sql = "SELECT home_team, away_team, home_score, away_score FROM GameStats WHERE game_id = $value";
								$results = $db->query($sql);
								$games = $results->fetch(PDO::FETCH_ASSOC);
								$homeTeam = $games['home_team'];
								$awayTeam = $games['away_team'];
								$homeScore = $games['home_score'];
								$awayScore = $games['away_score'];
								$box = "<a href='".BASE_URL . $resources.'boxScore.php?id='.$value.'&league='.$league.'&seasId='.$seasonId.'&team='.$userTeam."'>";
								echo '<tr>';
								if($homeTeam == $teamId){
									// select away
									$sql = "SELECT name FROM Teams WHERE team_id = $awayTeam";
									$results = $db->query($sql);
									$name = $results->fetch(PDO::FETCH_ASSOC);
									echo '<td>' . $box . $name['name'] . '</a>' . '</td>';
									// determine w/l
									if($homeScore > $awayScore){
										echo '<td>' . $box . 'W' . '</a>' . '</td>';
									}else{
										echo '<td>' . $box . 'L' . '</a>' . '</td>';
									}
									echo '<td>' . $box . $homeScore . '-' . $awayScore . '</a>' . '</td>';

								}else {
									// seelct home
									$sql = "SELECT name FROM Teams WHERE team_id = $homeTeam";
									$results = $db->query($sql);
									$name = $results->fetch(PDO::FETCH_ASSOC);
									echo '<td>' . $box . $name['name'] . '</a>' . '</td>';
									// determine w/l
									if($homeScore < $awayScore){
										echo  '<td>' . $box . 'W' . '</a>' . '</td>';
									}else{
										echo '<td>' . $box . 'L' . '</a>' . '</td>';
									}
									echo '<td>' . $box . $homeScore . '-' . $awayScore . '</a>' . '</td>';
								}

								echo '</tr>';
							}

						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>
