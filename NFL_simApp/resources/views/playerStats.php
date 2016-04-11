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
						<h1><?php echo $league; ?> Player Stats</h1>
					</div>
					<div class="col-md-4">
						<form method="post" action="<?php echo "http://" . $_SERVER['HTTP_HOST'] . '/NFL_simApp/resources/views/playerStats.php?league=' . $league . '&seasId=' . $seasonId; ?>">
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
						<tr>
							<th>Name</th>
							<th>Pos</th>
							<th>Comp.</th>
							<th>Td's</th>
							<th>Int</th>
							<th>Yards</th>
							<th>Carries</th>
							<th>Rec</th>
							<th>Fum</th>
							<th>Tackle</th>
							<th>Sack</th>
						</tr>
					</thead>
					<tbody>
						<?php

						// add option for all players in league
						//if(isset($team)){
							$sql = "SELECT team_id FROM Teams WHERE name = '$team' AND season_id = $seasonId";
							$results = $db->query($sql);
							$results = $results->fetch(PDO::FETCH_ASSOC);
							$teamId = $results['team_id'];
						//} 

						$sql = "SELECT TeamPlayers.player_id, PlayerStats.completions, PlayerStats.td, PlayerStats.interceptions, PlayerStats.yards, PlayerStats.carries, PlayerStats.receptions, PlayerStats.fumbles, PlayerStats.tackles, PlayerStats.sacks FROM TeamPlayers INNER JOIN PlayerStats ON TeamPlayers.team_player_id = PlayerStats.team_player_id WHERE TeamPlayers.team_id = $teamId";
						$results = $db->query($sql);
						$results = $results->fetchAll(PDO::FETCH_ASSOC);
						//var_dump($results);
						foreach ($results as $key => $value) { ?>
							<tr>
								<?php	
									$id = $value['player_id'];
									$comp = $value['completions'];
								 	$td = $value['td'];
								 	$inter = $value['interceptions'];
									$yrd = $value['yards'];
									$car = $value['carries'];
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
								 	echo '<td>' . $car . '</td>';
								 	echo '<td>' . $rec . '</td>';
								 	echo '<td>' . $fum . '</td>';	
								 	echo '<td>' . $tck . '</td>';
								 	echo '<td>' . $sck . '</td>';							 							
																	
								?>
							</tr>
						<?php } ?>
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>
