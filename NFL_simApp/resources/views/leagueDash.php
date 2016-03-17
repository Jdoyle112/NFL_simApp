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
	$link = '?team=' . $userTeam . '&league=' . $league . '&seasId=' . $seasonId;

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
				<h2><?php echo $userTeam; ?></h2>
			</div>
		</div>
	</div>
</body>
</html>



