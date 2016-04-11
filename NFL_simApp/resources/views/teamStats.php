<?php

	require("../../config.php");
	session_start();
	$username = $_SESSION['user'];
	$userId = $_SESSION['userId']; 

	$league = $_GET['league'];
	$userTeam = $_GET['team'];
	$seasonId = trim($_GET['seasId']);
	
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
				<h1><?php echo $league; ?> Team Stats</h1>	
			</div>

			<div class="teamStats">
				<table class="table table-bordered table-responsive">
					<thead>
						<th>Team</th>
						<th>W</th>
						<th>L</th>
						<th>PPG</th>
						<th>YPG</th>
						<th>TO/ G</th>
						<!--<tr>TO +/-</tr>
						<th>Plays</tr>
						<th>DEF PPG</tr>
						<th>DEF YPG</tr>
						<th>DEF TO</tr>-->
						<th>Pass YPG</th>
						<th>Rush YPG</th>
						<th>DEF Sacks</th>
					</thead>
					<tbody>
						<?php

							// get all teams
							$sql = "SELECT team_id, team_abbrev FROM Teams WHERE season_id = $seasonId";
							$results = $db->query($sql);
							$results = $results->fetchAll(PDO::FETCH_ASSOC);
							
							foreach ($results as $key => $value) {
								$teamId = $value['team_id'];
								$teamAbrv = $value['team_abbrev'];	

								// get W/L
								$sql = "SELECT games_won, games_lost FROM TeamStats WHERE team_id = $teamId";
								$results = $db->query($sql);
								$results = $results->fetch(PDO::FETCH_ASSOC);
								$wins = $results['games_won'];
								$loss = $results['games_lost'];	

								// get stats
								$sql = "SELECT * FROM GameStats WHERE home_team = $teamId OR away_team = $teamId";
								$results = $db->query($sql);
								$stats = $results->fetchAll(PDO::FETCH_ASSOC);	

								foreach ($stats as $k => $value) {
									// reset stats vars
									$homeScore = 0;
									$awayScore = 0;
									$homeYards = 0;
									$awayYards = 0;
									$homeTO = 0;
									$awayTO = 0;
									$homeRush = 0;
									$awayRush = 0;
									$homePass = 0;
									$awayPass = 0;
									$homeSacks = 0;
									$awaySacks = 0;

									// home
									if($teamId == $value['home_team']){
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

									$ppg = ($homeScore + $awayScore) / ($wins + $loss);
									$ypg = ($homeYards + $awayYards) / ($wins + $loss);
									$TOpg = ($homeTO + $awayTO) / ($wins + $loss);
									$passYPG = ($homePass + $awayPass) / ($wins + $loss);
									$rushYPG = ($homeRush + $awayRush) / ($wins + $loss);
									$sacks = $homeSacks + $awaySacks;
								}

								echo '<tr>';
								echo '<td>' . $teamAbrv . '</td>';
								echo '<td>' . $wins . '</td>';
								echo '<td>' . $loss . '</td>';
								echo '<td>' . $ppg . '</td>';
								echo '<td>' . $ypg . '</td>';
								echo '<td>' . $TOpg . '</td>';
								echo '<td>' . $passYPG . '</td>';
								echo '<td>' . $rushYPG . '</td>';
								echo '<td>' . $sacks . '</td>';
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
