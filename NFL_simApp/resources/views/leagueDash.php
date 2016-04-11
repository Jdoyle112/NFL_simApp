<?php

//TO-DO
	// can only perform actions if signed in
	// refresh allows game to be played

	require("../../config.php");
	session_start();
	$username = $_SESSION['user'];
	$userId = $_SESSION['userId']; 

	$league = $_GET['league'];
	$userTeam = $_GET['team'];
	$seasonId = trim($_GET['seasId']);
	$play = $_GET['play'];

	$sql = "SELECT week FROM Schedule WHERE season_id = $seasonId AND completed = 0 LIMIT 1";
	$results = $db->query($sql);
	$result = $results->fetch(PDO::FETCH_ASSOC);
	$week = $result['week'];

	// check if play button was pressed
	if(isset($play)){
		// get all games from schedule where season and week match up (do not allow duplicates must add)
		$sql = "SELECT game_id, week FROM Schedule WHERE season_id = $seasonId AND completed = 0 LIMIT 16";
		$results = $db->query($sql);
		$games = $results->fetchAll(PDO::FETCH_ASSOC);
		include(ROOT_PATH . 'resources/includes/core/simpleGame.php');
		foreach ($games as $value) {
			// get gameId
			$gameId = $value['game_id'];

			// sim games
			$sim = new Game();
			$sim->simGame($gameId);

			//update completed schedule
			$sql = "UPDATE Schedule SET completed = 1 WHERE game_id = $gameId";
			$db->exec($sql);
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
					<li><a href='<?php echo "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '&play=yes&week=' . $week; ?>'>Play Week</a></li>
					<p>Week: <?php echo $week; ?></p>
					<br><br>					
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
				<h1><?php echo $league; ?> League Dashboard</h1>
				<h4><?php echo $userTeam; ?></h4>
			</div>
			<div class="row">
				<div class="col-md-3">
					<table class="table table-bordered table-responsive">
						<thead>
							<th>Team</th>
							<th>W</th>
							<th>L</th>
						</thead>
						<tbody>
							<?php
							// get all teams in league
							$sql = "SELECT team_id, name FROM Teams WHERE season_id = $seasonId";
							$results = $db->query($sql);
							$results = $results->fetchAll(PDO::FETCH_ASSOC);
							
							foreach ($results as $key => $value) {
								$teamId = $value['team_id'];
								$name = $value['name'];	

								// get W/L
								$sql = "SELECT games_won, games_lost FROM TeamStats WHERE team_id = $teamId";
								$results = $db->query($sql);
								$results = $results->fetch(PDO::FETCH_ASSOC);
								$wins = $results['games_won'];
								$loss = $results['games_lost'];	

								echo '<tr>';
								echo '<td>' . $name . '</td>';
								echo '<td>' . $wins . '</td>';
								echo '<td>' . $loss . '</td>';
								echo '</tr>';
							}	

							?>
						</tbody>
					</table>
				</div>
				<div class="col-md-5">
					<h2 style="text-align:center">
						<?php
							// get all teams in league
							$sql = "SELECT Teams.team_id, TeamStats.games_won, TeamStats.games_lost FROM Teams INNER JOIN TeamStats ON TeamStats.team_id = Teams.team_id WHERE Teams.season_id = $seasonId AND Teams.name = '$userTeam'";
							$results = $db->query($sql);
							$results = $results->fetch(PDO::FETCH_ASSOC);
							$usrId = $results['team_id'];
							echo $results['games_won'] . " - " . $results['games_lost'];
						?>
					</h2>

					<div class="row">
						<div class="col-md-6">
							<h4>Team Leaders</h4>
							<?php
								// select stat leaders
								
								/*$sql = "SELECT TeamPlayers.player_id, MAX(PlayerStats.yards), MAX(PlayerStats.receptions), MAX(PlayerStats.tackles), MAX(PlayerStats.sacks) FROM PlayerStats INNER JOIN TeamPlayers ON PlayerStats.team_player_id = TeamPlayers.team_player_id WHERE TeamPlayers.team_id = $usrId";*/
								
								// get player id of most yards
								$sql = "SELECT PlayerStats.yards, TeamPlayers.player_id FROM PlayerStats INNER JOIN TeamPlayers ON PlayerStats.team_player_id = TeamPlayers.team_player_id WHERE TeamPlayers.team_id = $usrId ORDER BY PlayerStats.yards DESC LIMIT 1";
								$results = $db->query($sql);
								$results = $results->fetch(PDO::FETCH_ASSOC);
								$playerId = $results['player_id'];
								$yards = $results['yards'];

								// get name
								$sql = "SELECT first_name, last_name FROM Players WHERE player_id = $playerId";
								$results = $db->query($sql);
								$results = $results->fetch(PDO::FETCH_ASSOC);
								$name = $results['first_name'] . ' ' . $results['last_name'];								
								echo '<p>' . '<strong>' . $name . '</strong>' . ' - ' . $yards . ' yrds' . '</p>';

								// get player id of most receptions
								$sql = "SELECT PlayerStats.receptions, TeamPlayers.player_id FROM PlayerStats INNER JOIN TeamPlayers ON PlayerStats.team_player_id = TeamPlayers.team_player_id WHERE TeamPlayers.team_id = $usrId ORDER BY PlayerStats.receptions DESC LIMIT 1";
								$results = $db->query($sql);
								$results = $results->fetch(PDO::FETCH_ASSOC);
								$playerId = $results['player_id'];
								$rec = $results['receptions'];

								// get name
								$sql = "SELECT first_name, last_name FROM Players WHERE player_id = $playerId";
								$results = $db->query($sql);
								$results = $results->fetch(PDO::FETCH_ASSOC);
								$name = $results['first_name'] . ' ' . $results['last_name'];								
								echo '<p>' . '<strong>' . $name . '</strong>' . ' - ' . $rec . ' rec' . '</p>';


								// get player id of most tackles
								$sql = "SELECT PlayerStats.tackles, TeamPlayers.player_id FROM PlayerStats INNER JOIN TeamPlayers ON PlayerStats.team_player_id = TeamPlayers.team_player_id WHERE TeamPlayers.team_id = $usrId ORDER BY PlayerStats.tackles DESC LIMIT 1";
								$results = $db->query($sql);
								$results = $results->fetch(PDO::FETCH_ASSOC);
								$playerId = $results['player_id'];
								$tackles = $results['tackles'];

								// get name
								$sql = "SELECT first_name, last_name FROM Players WHERE player_id = $playerId";
								$results = $db->query($sql);
								$results = $results->fetch(PDO::FETCH_ASSOC);
								$name = $results['first_name'] . ' ' . $results['last_name'];								
								echo '<p>' . '<strong>' . $name . '</strong>' . ' - ' . $tackles . ' tackles' . '</p>';	


								// get player id of most sacks
								$sql = "SELECT PlayerStats.sacks, TeamPlayers.player_id FROM PlayerStats INNER JOIN TeamPlayers ON PlayerStats.team_player_id = TeamPlayers.team_player_id WHERE TeamPlayers.team_id = $usrId ORDER BY PlayerStats.sacks DESC LIMIT 1";
								$results = $db->query($sql);
								$results = $results->fetch(PDO::FETCH_ASSOC);
								$playerId = $results['player_id'];
								$sacks = $results['sacks'];

								// get name
								$sql = "SELECT first_name, last_name FROM Players WHERE player_id = $playerId";
								$results = $db->query($sql);
								$results = $results->fetch(PDO::FETCH_ASSOC);
								$name = $results['first_name'] . ' ' . $results['last_name'];								
								echo '<p>' . '<strong>' . $name . '</strong>' . ' - ' . $sacks . ' sacks' . '</p>';															

							?>
						</div>
						<div class="col-md-6">
							<h4>Team Stats</h4>
							<?php

								// ppg
								// get stats
								$sql = "SELECT * FROM GameStats WHERE home_team = $usrId OR away_team = $usrId";
								$results = $db->query($sql);
								$stats = $results->fetchAll(PDO::FETCH_ASSOC);	

								foreach ($stats as $k => $value) {

									// home
									if($usrId == $value['home_team']){
										$homeScore += $value['home_score'];
										$homeYards += $value['home_yards'];
										$homeTO += $value['home_turnovers'];
										$homeRush += $value['home_rushing_yards'];
										$homePass += $value['home_passing_yards'];
										$homeTd += $value['home_tds'];
										$homeSacks += $value['home_sacks'];

									}else{
										// away
										$awayScore += $value['away_score'];
										$awayYards += $value['away_yards'];
										$awayTO += $value['away_turnovers'];
										$awayRush += $value['away_rushing_yards'];
										$awayPass += $value['away_passing_yards'];
										$awayTd += $value['away_tds'];
										$awaySacks += $value['away_sacks'];			
									}

								}


								$ppg = ($homeScore + $awayScore) / ($wins + $loss);
								$TOpg = ($homeTO + $awayTO) / ($wins + $loss);
								$passYPG = ($homePass + $awayPass) / ($wins + $loss);
								$rushYPG = ($homeRush + $awayRush) / ($wins + $loss);

								echo '<p>' . 'PPG: ' . '<strong>' . $ppg . '</strong>' . '</p>';	
								echo '<p>' . 'TO/ G: ' . '<strong>' . $TOpg . '</strong>' . '</p>';	
								echo '<p>' . 'Pass YPG: ' . '<strong>' . $passYPG . '</strong>' . '</p>';	
								echo '<p>' . 'Rush YPG: ' . '<strong>' . $rushYPG . '</strong>' . '</p>';	

							?>
						</div>						
					</div>

				</div>
				<div class="col-md-4">
					<h3>Upcoming Games</h3>
					<ul class="list-group">
					<?php
					
						$sql = "SELECT home_team, away_team FROM Schedule WHERE completed = 0 AND (home_team = $usrId OR away_team = $usrId) ORDER BY week ASC LIMIT 3";
						$results = $db->query($sql);
						$results = $results->fetchAll(PDO::FETCH_ASSOC);
						foreach ($results as $value) {
							$home = $value['home_team'];
							$away = $value['away_team'];
							$sql = "SELECT name FROM Teams WHERE team_id = $home";
							$results = $db->query($sql);
							$home = $results->fetch(PDO::FETCH_ASSOC);
							$sql = "SELECT name FROM Teams WHERE team_id = $away";
							$results = $db->query($sql);
							$away = $results->fetch(PDO::FETCH_ASSOC);
							echo '<li class="list-group-item">' . $away['name'] . ' @ ' . $home['name'] . '</li>';
						}
	
					?>
					</ul>

					<h3>Completed Games</h3>

					<ul class="list-group">
					<?php
					
						$sql = "SELECT home_team, away_team FROM Schedule WHERE completed = 1 AND (home_team = $usrId OR away_team = $usrId) ORDER BY week DESC LIMIT 3";
						$results = $db->query($sql);
						$results = $results->fetchAll(PDO::FETCH_ASSOC);
						foreach ($results as $value) {
							$homeId = $value['home_team'];
							$awayId = $value['away_team'];
							$sql = "SELECT home_score, away_score FROM GameStats WHERE home_team = $homeId AND away_team = $awayId";
							$results = $db->query($sql);
							$scores = $results->fetch(PDO::FETCH_ASSOC);
							$homeScore = $scores['home_score'];
							$awayScore = $scores['away_score'];
							$sql = "SELECT name FROM Teams WHERE team_id = $homeId";
							$results = $db->query($sql);
							$home = $results->fetch(PDO::FETCH_ASSOC);
							$sql = "SELECT name FROM Teams WHERE team_id = $awayId";
							$results = $db->query($sql);
							$away = $results->fetch(PDO::FETCH_ASSOC);
							if($usrId == $homeId && $homeScore > $awayScore){
								echo '<li class="list-group-item green">' . $away['name'] . ' @ ' . $home['name'] . '</li>';
							}elseif($usrId == $homeId && $homeScore <= $awayScore){
								echo '<li class="list-group-item red">' . $away['name'] . ' @ ' . $home['name'] . '</li>';
							}elseif($usrId == $awayId AND $homeScore > $awayScore){
								echo '<li class="list-group-item red">' . $away['name'] . ' @ ' . $home['name'] . '</li>';
							}elseif($usrId == $awayId AND $homeScore < $awayScore){
								echo '<li class="list-group-item green">' . $away['name'] . ' @ ' . $home['name'] . '</li>';
							}else{
								echo "error";
							}
						}
					?>
					</ul>

				</div>	
					
			</div>
			<?php

						// get team players id's
						$sql = "SELECT * FROM TeamPlayers WHERE team_id = $usrId";
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
				<div class='usrRoster'>
					<h3>Roster</h3>
					<table class="table table-responsive table-bordered">
						<th>
							<tr>
								<th>Name</th>
								<th>Position</th>
								<th>Overall</th>
								<th>Health</th>
							</tr>
						</th>
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
	</div>
</body>
</html>



