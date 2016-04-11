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
					<h1><?php echo $league; ?> Standings</h1>
				</div>
				<div>
					<h3>Eastern Conference</h3>
					<table class="table table-responsive table-bordered">
						<thead>
							<tr>
								<th>North</th>
								<th>W</th>
								<th>L</th>
								<th>Pct</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT Teams.name, TeamStats.games_won, TeamStats.games_lost FROM TeamStats INNER JOIN Teams ON TeamStats.team_id = Teams.team_id WHERE Teams.season_id = $seasonId AND Teams.division_id = 1";
							$results = $db->query($sql);
							while( $row = $results->fetch(PDO::FETCH_ASSOC) ) { ?>
								<tr>
								<?php	
								 	$wins = $row['games_won'];
								 	$loss = $row['games_lost'];
								 	if($loss == 0){
								 		$pct = 1.00;
								 	}else {
								 		$pct = $wins / ($wins + $loss);	
								 	}		
								 	echo '<td>' . $row['name'] . '</td>';
								 	echo '<td>' . $wins . '</td>';
								 	echo '<td>' . $loss . '</td>';	
								 	echo '<td>' . round($pct, 2) . '</td>';								 							
																
								?>
								</tr>
							<?php } ?>
						</tbody>	
					</table>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>South</th>
								<th>W</th>
								<th>L</th>
								<th>Pct</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT Teams.name, TeamStats.games_won, TeamStats.games_lost FROM TeamStats INNER JOIN Teams ON TeamStats.team_id = Teams.team_id WHERE Teams.season_id = $seasonId AND Teams.division_id = 2";
							$results = $db->query($sql);
							while( $row = $results->fetch(PDO::FETCH_ASSOC) ) { ?>
								<tr>
								<?php	
								 	$wins = $row['games_won'];
								 	$loss = $row['games_lost'];
								 	if($loss == 0){
								 		$pct = 1.00;
								 	}else {
								 		$pct = $wins / ($wins + $loss);	
								 	}		
								 	echo '<td>' . $row['name'] . '</td>';
								 	echo '<td>' . $wins . '</td>';
								 	echo '<td>' . $loss . '</td>';	
								 	echo '<td>' . round($pct, 2). '</td>';								 							
																
								?>
								</tr>
							<?php } ?>
						</tbody>							
					</table>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>East</th>
								<th>W</th>
								<th>L</th>
								<th>Pct</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT Teams.name, TeamStats.games_won, TeamStats.games_lost FROM TeamStats INNER JOIN Teams ON TeamStats.team_id = Teams.team_id WHERE Teams.season_id = $seasonId AND Teams.division_id = 3";
							$results = $db->query($sql);
							while( $row = $results->fetch(PDO::FETCH_ASSOC) ) { ?>
								<tr>
								<?php	
								 	$wins = $row['games_won'];
								 	$loss = $row['games_lost'];
								 	if($loss == 0){
								 		$pct = 1.00;
								 	}else {
								 		$pct = $wins / ($wins + $loss);	
								 	}		
								 	echo '<td>' . $row['name'] . '</td>';
								 	echo '<td>' . $wins . '</td>';
								 	echo '<td>' . $loss . '</td>';	
								 	echo '<td>' . round($pct, 2) . '</td>';								 							
																
								?>
								</tr>
							<?php } ?>
						</tbody>						
					</table>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>West</th>
								<th>W</th>
								<th>L</th>
								<th>Pct</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT Teams.name, TeamStats.games_won, TeamStats.games_lost FROM TeamStats INNER JOIN Teams ON TeamStats.team_id = Teams.team_id WHERE Teams.season_id = $seasonId AND Teams.division_id = 4";
							$results = $db->query($sql);
							while( $row = $results->fetch(PDO::FETCH_ASSOC) ) { ?>
								<tr>
								<?php	
								 	$wins = $row['games_won'];
								 	$loss = $row['games_lost'];
								 	if($loss == 0){
								 		$pct = 1.00;
								 	}else {
								 		$pct = $wins / ($wins + $loss);	
								 	}		
								 	echo '<td>' . $row['name'] . '</td>';
								 	echo '<td>' . $wins . '</td>';
								 	echo '<td>' . $loss . '</td>';	
								 	echo '<td>' . round($pct, 2) . '</td>';								 							
																
								?>
								</tr>
							<?php } ?>
						</tbody>						
					</table>					
				</div>
				<div>
					<h3>Western Conference</h3>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>North</th>
								<th>W</th>
								<th>L</th>
								<th>Pct</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT Teams.name, TeamStats.games_won, TeamStats.games_lost FROM TeamStats INNER JOIN Teams ON TeamStats.team_id = Teams.team_id WHERE Teams.season_id = $seasonId AND Teams.division_id = 5";
							$results = $db->query($sql);
							while( $row = $results->fetch(PDO::FETCH_ASSOC) ) { ?>
								<tr>
								<?php	
								 	$wins = $row['games_won'];
								 	$loss = $row['games_lost'];
								 	if($loss == 0){
								 		$pct = 1.00;
								 	}else {
								 		$pct = $wins / ($wins + $loss);	
								 	}		
								 	echo '<td>' . $row['name'] . '</td>';
								 	echo '<td>' . $wins . '</td>';
								 	echo '<td>' . $loss . '</td>';	
								 	echo '<td>' . round($pct, 2) . '</td>';								 							
																
								?>
								</tr>
							<?php } ?>
						</tbody>							
					</table>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>South</th>
								<th>W</th>
								<th>L</th>
								<th>Pct</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT Teams.name, TeamStats.games_won, TeamStats.games_lost FROM TeamStats INNER JOIN Teams ON TeamStats.team_id = Teams.team_id WHERE Teams.season_id = $seasonId AND Teams.division_id = 6";
							$results = $db->query($sql);
							while( $row = $results->fetch(PDO::FETCH_ASSOC) ) { ?>
								<tr>
								<?php	
								 	$wins = $row['games_won'];
								 	$loss = $row['games_lost'];
								 	if($loss == 0){
								 		$pct = 1.00;
								 	}else {
								 		$pct = $wins / ($wins + $loss);	
								 	}		
								 	echo '<td>' . $row['name'] . '</td>';
								 	echo '<td>' . $wins . '</td>';
								 	echo '<td>' . $loss . '</td>';	
								 	echo '<td>' . round($pct, 2) . '</td>';								 							
																
								?>
								</tr>
							<?php } ?>
						</tbody>					
					</table>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>East</th>
								<th>W</th>
								<th>L</th>
								<th>Pct</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT Teams.name, TeamStats.games_won, TeamStats.games_lost FROM TeamStats INNER JOIN Teams ON TeamStats.team_id = Teams.team_id WHERE Teams.season_id = $seasonId AND Teams.division_id = 7";
							$results = $db->query($sql);
							while( $row = $results->fetch(PDO::FETCH_ASSOC) ) { ?>
								<tr>
								<?php	
								 	$wins = $row['games_won'];
								 	$loss = $row['games_lost'];
								 	if($loss == 0){
								 		$pct = 1.00;
								 	}else {
								 		$pct = $wins / ($wins + $loss);	
								 	}		
								 	echo '<td>' . $row['name'] . '</td>';
								 	echo '<td>' . $wins . '</td>';
								 	echo '<td>' . $loss . '</td>';	
								 	echo '<td>' . round($pct, 2) . '</td>';								 							
																
								?>
								</tr>
							<?php } ?>
						</tbody>						
					</table>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>West</th>
								<th>W</th>
								<th>L</th>
								<th>Pct</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT Teams.name, TeamStats.games_won, TeamStats.games_lost FROM TeamStats INNER JOIN Teams ON TeamStats.team_id = Teams.team_id WHERE Teams.season_id = $seasonId AND Teams.division_id = 8";
							$results = $db->query($sql);
							while( $row = $results->fetch(PDO::FETCH_ASSOC) ) { ?>
								<tr>
								<?php	
								 	$wins = $row['games_won'];
								 	$loss = $row['games_lost'];
								 	if($loss == 0){
								 		$pct = 1.00;
								 	}else {
								 		$pct = $wins / ($wins + $loss);	
								 	}		
								 	echo '<td>' . $row['name'] . '</td>';
								 	echo '<td>' . $wins . '</td>';
								 	echo '<td>' . $loss . '</td>';	
								 	echo '<td>' . round($pct, 2) . '</td>';								 							
																
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
