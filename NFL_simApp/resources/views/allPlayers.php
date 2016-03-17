<?php

// TO_DO
	// form returning all players. Need to only display one for position selected
	// sort players by rating

	require("../../config.php");
	session_start();
	$username = $_SESSION['user'];
	$userId = $_SESSION['userId']; 

	$league = $_GET['league'];
	$userTeam = $_GET['team'];
	$seasonId = trim($_GET['seasId']);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$position = trim($_POST["position"]);
	}	

	// links
	$resources = "resources/views/";
	$link = '?team=' . $userTeam . '&league=' . $league . '&seasId=' . $seasonId;


?>
<?php include(ROOT_PATH . 'resources/includes/header.php'); ?>
<?php echo $position; ?>

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
				<h1>List of NFL Players in Sim</h1>
			</div>
			<div class="players_form">
				<form method="post" action="<?php echo "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>/">
	                <table>
	                    <tr>
	                        <th>
	                            <label for="team">Select a Position</label>
	                        </th>
	                        <td>
							  	<select id="position" name="position">
	                          	  	<?php
	                          	  		$sql = "SELECT * FROM Positions";
	                          	  		$results = $db->query($sql);
	                          	  		$positions = $results->fetchAll(PDO::FETCH_ASSOC);

					                	foreach ($positions as $key => $values) { ?>
					                    	<option value="<?php echo $values['position_id']; ?>"><?php echo $values['name']; ?></option>	
					                	<?php	}  ?>                  	
	                            </select>
	                        </td>
	                    </tr> 
	                </table>
	                <input type="submit" value="Send">
				</form>
			</div>
			<div>
				<table class="table table-responsive table-bordered">
					<th>
						<tr>
							<th>Name</th>
							<th>Position</th>
							<th>Age</th>
							<th>Overall</th>
							<th>Health</th>
							<?php
								if($position == 0 OR !isset($position)){
									echo "<th>Completion %</th>";
									echo "<th>YPA</th>";
									echo "<th>Int. %</th>";
									echo "<th>QB Rush</th>";
								} elseif($position == 1){
									echo "<th>YPC</th>";
									echo "<th>REC</th>";
									echo "<th>AVG</th>";
									echo "<th>Pass Blk</th>";
								} elseif($position == 2){
									echo "<th>REC</th>";
									echo "<th>AVG</th>";
									echo "<th>Run Blk</th>";
								} elseif($position == 3){
									echo "<th>REC</th>";
									echo "<th>AVG</th>";
									echo "<th>Pass Blk</th>";
									echo "<th>Run Blk</th>";
								} elseif($position == 4){
									echo "<th>Pass Blk</th>";
									echo "<th>Run Blk</th>";
								} elseif($position == 4){
									echo "<th>Pass Blk</th>";
									echo "<th>Run Blk</th>";
								} elseif($position == 6){
									echo "<th>Pass Blk</th>";
									echo "<th>Run Blk</th>";
								} elseif($position == 7){
									echo "<th>Run D</th>";
									echo "<th>Pass D</th>";
									echo "<th>Rush D</th>";
								} elseif($position == 8){
									echo "<th>Run D</th>";
									echo "<th>Pass D</th>";
									echo "<th>Rush D</th>";
								} elseif($position == 9){
									echo "<th>Run D</th>";
									echo "<th>Pass D</th>";
									echo "<th>Rush D</th>";
								} 
							?>
						</tr>
					</th>
					<tbody class="table_body">				
						<?php		
							$sql = "SELECT * FROM Players";
							$results = $db->query($sql);
						?>
						<?php while( $row = $results->fetch(PDO::FETCH_ASSOC) ) { ?>
						<tr>
						    <?php
						    	if($position == 0 AND $row['pos_abrv'] == 'QB'){
						    		echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
						    		echo '<td>' . $row['pos_abrv'] . '</td>';
						    		echo '<td>' . $row['age'] . '</td>';
						    		echo '<td>' . $row['overall'] . '</td>';
						    		echo '<td>' . $row['health'] . '</td>';
						    		echo '<td>' . $row['comp_pctR'] . '</td>';
						    		echo '<td>' . $row['ypaR'] . '</td>';
						    		echo '<td>' . $row['int_playR'] . '</td>';
						    		echo '<td>' . $row['qb_rushR'] . '</td>';
						    	} elseif($position == 1 AND $row['pos_abrv'] == 'RB'){
						    		echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
						    		echo '<td>' . $row['pos_abrv'] . '</td>';
						    		echo '<td>' . $row['age'] . '</td>';
						    		echo '<td>' . $row['overall'] . '</td>';
						    		echo '<td>' . $row['health'] . '</td>';						    		
						    		echo '<td>' . $row['ypcR'] . '</td>';
						    		echo '<td>' . $row['recR'] . '</td>';
						    		echo '<td>' . $row['avgR'] . '</td>';
						    		echo '<td>' . $row['pass_blockR'] . '</td>';
						    	} elseif($position == 2 AND $row['pos_abrv'] == 'WR'){
						    		echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
						    		echo '<td>' . $row['pos_abrv'] . '</td>';
						    		echo '<td>' . $row['age'] . '</td>';
						    		echo '<td>' . $row['overall'] . '</td>';
						    		echo '<td>' . $row['health'] . '</td>';						    		
						    		echo '<td>' . $row['recR'] . '</td>';
						    		echo '<td>' . $row['avgR'] . '</td>';
						    		echo '<td>' . $row['run_blockR'] . '</td>';
						    	} elseif($position == 3 AND $row['pos_abrv'] == 'TE'){
						    		echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
						    		echo '<td>' . $row['pos_abrv'] . '</td>';
						    		echo '<td>' . $row['age'] . '</td>';
						    		echo '<td>' . $row['overall'] . '</td>';
						    		echo '<td>' . $row['health'] . '</td>';						    		
						    		echo '<td>' . $row['recR'] . '</td>';
						    		echo '<td>' . $row['avgR'] . '</td>';
						    		echo '<td>' . $row['pass_blockR'] . '</td>';
						    		echo '<td>' . $row['run_blockR'] . '</td>';
						    	} elseif($position == 4 AND $row['pos_abrv'] == 'OT'){
						    		echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
						    		echo '<td>' . $row['pos_abrv'] . '</td>';
						    		echo '<td>' . $row['age'] . '</td>';
						    		echo '<td>' . $row['overall'] . '</td>';
						    		echo '<td>' . $row['health'] . '</td>';						    		
						    		echo '<td>' . $row['pass_blockR'] . '</td>';
						    		echo '<td>' . $row['run_blockR'] . '</td>';
						    	} elseif($position == 5 AND $row['pos_abrv'] == 'G'){
						    		echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
						    		echo '<td>' . $row['pos_abrv'] . '</td>';
						    		echo '<td>' . $row['age'] . '</td>';
						    		echo '<td>' . $row['overall'] . '</td>';
						    		echo '<td>' . $row['health'] . '</td>';						    		
						    		echo '<td>' . $row['pass_blockR'] . '</td>';
						    		echo '<td>' . $row['run_blockR'] . '</td>';
						    	} elseif($position == 6 AND $row['pos_abrv'] == 'C'){
						    		echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
						    		echo '<td>' . $row['pos_abrv'] . '</td>';
						    		echo '<td>' . $row['age'] . '</td>';
						    		echo '<td>' . $row['overall'] . '</td>';
						    		echo '<td>' . $row['health'] . '</td>';						    		
						    		echo '<td>' . $row['pass_blockR'] . '</td>';
						    		echo '<td>' . $row['run_blockR'] . '</td>';
						    	} elseif($position == 7 AND $row['pos_abrv'] == 'DL'){
						    		echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
						    		echo '<td>' . $row['pos_abrv'] . '</td>';
						    		echo '<td>' . $row['age'] . '</td>';
						    		echo '<td>' . $row['overall'] . '</td>';
						    		echo '<td>' . $row['health'] . '</td>';						    		
						    		echo '<td>' . $row['run_dR'] . '</td>';
						    		echo '<td>' . $row['pass_dR'] . '</td>';
						    		echo '<td>' . $row['rush_dR'] . '</td>';
						    	} elseif($position == 8 AND $row['pos_abrv'] == 'LB'){
						    		echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
						    		echo '<td>' . $row['pos_abrv'] . '</td>';
						    		echo '<td>' . $row['age'] . '</td>';
						    		echo '<td>' . $row['overall'] . '</td>';
						    		echo '<td>' . $row['health'] . '</td>';						    		
						    		echo '<td>' . $row['run_dR'] . '</td>';
						    		echo '<td>' . $row['pass_dR'] . '</td>';
						    		echo '<td>' . $row['rush_dR'] . '</td>';
						    	} elseif($position == 9 AND $row['pos_abrv'] == 'DB'){
						    		echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
						    		echo '<td>' . $row['pos_abrv'] . '</td>';
						    		echo '<td>' . $row['age'] . '</td>';
						    		echo '<td>' . $row['overall'] . '</td>';
						    		echo '<td>' . $row['health'] . '</td>';						    		
						    		echo '<td>' . $row['run_dR'] . '</td>';
						    		echo '<td>' . $row['pass_dR'] . '</td>';
						    		echo '<td>' . $row['rush_dR'] . '</td>';
						    	} 
						    	
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
