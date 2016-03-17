<?php

// TO-DO
	// add links to teams
	// redirect with error
	// add carries to player stats

	require("../../config.php");
	session_start();
	$username = $_SESSION['user'];
	$userId = $_SESSION['userId']; 

	$gameId = $_GET['id'];
	$league = $_GET['league'];
	$userTeam = $_GET['team'];
	$seasonId = trim($_GET['seasId']);

	// links
	$resources = "resources/views/";
	$link = '?team=' . $userTeam . '&league=' . $league . '&seasId=' . $seasonId;


	if(isset($gameId)){

		// get team names, and score
		$sql = "SELECT Teams.team_id, Teams.name, Teams.team_abbrev, GameStats.home_team, GameStats.away_team, GameStats.home_score, GameStats.away_score, GameStats.home_yards, GameStats.away_yards, GameStats.home_turnovers, GameStats.away_turnovers, GameStats.home_total_plays, GameStats.away_total_plays, GameStats.home_rushing_yards, GameStats.away_rushing_yards, GameStats.home_passing_yards, GameStats.away_passing_yards, GameStats.home_tds, GameStats.away_tds, GameStats.home_sacks, GameStats.away_sacks FROM GameStats INNER JOIN Teams ON Teams.team_id = GameStats.home_team OR Teams.team_id = GameStats.away_team WHERE game_id = $gameId";
		$results = $db->query($sql);
		$teams = $results->fetchAll(PDO::FETCH_ASSOC);

		foreach ($teams as $key => $value) {
			$tmId[] = $value['team_id'];
			$teamName[] = $value['name'];
			$teamAbrv[] = $value['team_abbrev'];
			$homeId = $value['home_team'];
			$awayId = $value['away_team'];
			$homeScore = $value['home_score'];
			$awayScore = $value['away_score'];
			$homeYards = $value['home_yards'];
			$awayYards = $value['away_yards'];
			$homeTO = $value['home_turnovers'];
			$awayTO = $value['away_turnovers'];
			$homeTot = $value['home_total_plays'];
			$awayTot = $value['away_total_plays'];
			$homeRush = $value['home_rushing_yards'];
			$awayRush = $value['away_rushing_yards'];
			$homePass = $value['home_passing_yards'];
			$awayPass = $value['away_passing_yards'];
			$homeTd = $value['home_tds'];
			$awayTd = $value['away_tds'];
			$homeSacks = $value['home_sacks'];
			$awaySacks = $value['away_sacks'];
		}

	} else {
		// redirect with error pop up
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
				<h2 style="text-align:center; margin-top: 30px"><?php echo $teamName[0] . " " . $homeScore . " - " . $teamName[1] . " " . $awayScore; ?></h2>
			</div>
			<div>
				<table class="table table-responsive table-bordered boxscore">
					<thead>
						<th>Team</th>
						<th>Yards</th>
						<th>Turnovers</th>
						<th>Plays</th>
						<th>Rush Yards</th>
						<th>Pass Yards</th>
						<th>TD's</th>
						<th>Sacks</th>
					</thead>
					<tbody>
						<?php
							// home
							echo '<tr>';
							echo '<td>' . $teamAbrv[0] . '</td>';
							echo '<td>' . $homeYards . '</td>';
							echo '<td>' . $homeTO . '</td>';
							echo '<td>' . $homeTot . '</td>';
							echo '<td>' . $homeRush . '</td>';
							echo '<td>' . $homePass . '</td>';
							echo '<td>' . $homeTd . '</td>';
							echo '<td>' . $homeSacks . '</td>';
							echo '<tr>';
							
							// away
							echo '<tr>';
							echo '<td>' . $teamAbrv[1] . '</td>';
							echo '<td>' . $awayYards . '</td>';
							echo '<td>' . $awayTO . '</td>';
							echo '<td>' . $awayTot . '</td>';
							echo '<td>' . $awayRush . '</td>';
							echo '<td>' . $awayPass . '</td>';
							echo '<td>' . $awayTd . '</td>';
							echo '<td>' . $awaySacks . '</td>';
							echo '<tr>';
						?>
					</tbody>
				</table>
			</div>

			<?php 

				for($t = 0; $t < 2; $t++){
				$name = $teamName[$t];
				$teamId = $tmId[$t];
			?>
			<div class="gamePlayerStats">
				<h3><?php echo $name; ?></h3>
				<table class="table table-responsive table-bordered boxplayers">
					<thead>
						<th>Name</th>
						<th>Pos</th>
						<th>Comp.</th>
						<th>Td's</th>
						<th>Int</th>
						<th>Yards</th>
						<th>Rec</th>
						<th>Fum</th>
						<th>Tackle</th>
						<th>Sack</th>
					</thead>
					<tbody>
						<?php

						$sql = "SELECT TeamPlayers.player_id, PlayerStatsGame.completions, PlayerStatsGame.td, PlayerStatsGame.interceptions, PlayerStatsGame.yards, PlayerStatsGame.receptions, PlayerStatsGame.fumbles, PlayerStatsGame.tackles, PlayerStatsGame.sacks FROM TeamPlayers INNER JOIN PlayerStatsGame ON TeamPlayers.team_player_id = PlayerStatsGame.team_player_id WHERE TeamPlayers.team_id = $teamId AND PlayerStatsGame.game_id = $gameId";
						$results = $db->query($sql);
						$results = $results->fetchAll(PDO::FETCH_ASSOC);
						foreach ($results as $key => $value) { 
							echo '<tr>';							
							$id = $value['player_id'];
							$comp = $value['completions'];
						 	$td = $value['td'];
						 	$inter = $value['interceptions'];
							$yrd = $value['yards'];
						 	$rec = $value['receptions'];
						 	$fum = $value['fumbles'];
						 	$tck = $value['tackles'];
						 	$sck = $value['sacks'];
								
						 	$sql = "SELECT pos_abrv, first_name, last_name FROM Players WHERE player_id = $id";
							$result = $db->query($sql);
							$players = $result->fetch(PDO::FETCH_ASSOC);
						 	echo '<td>' . $players['first_name'] . " " . $players['last_name'] . '</td>';
						 	echo '<td>' . $players['pos_abrv'] . '</td>';
						 	echo '<td>' . $comp . '</td>';	
						 	echo '<td>' . $td . '</td>';
						 	echo '<td>' . $inter . '</td>';
						 	echo '<td>' . $yrd . '</td>';
						 	echo '<td>' . $rec . '</td>';
							echo '<td>' . $fum . '</td>';	
							echo '<td>' . $tck . '</td>';
							echo '<td>' . $sck . '</td>';							 							
							echo '</tr>';										
						} 
						?>						
					</tbody>
				</table>
			</div>
			<?php } ?>
		</div>
	</div>
</body>
</html>





