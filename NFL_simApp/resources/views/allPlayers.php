<!DOCTYPE html>
<html>


	<body>
		<h1>NFL List of Players</h1>

		<div class="players_body">
			<table class="players_table">
				<th>
					<tr>
						<th>Name</th>
						<th>Position</th>
						<th>Overall</th>
						<th>Health</th>
					</tr>
				</th>
				<tbody>				
					<?php
						require("../../config.php");
						
						$sql = "SELECT * FROM Players";
						$results = $db->query($sql);
					?>
					<?php while( $row = $results->fetch(PDO::FETCH_ASSOC) ) { ?>
					<tr>
					    <td><?php echo $row['first_name'] . " "; ?><?php echo $row['last_name']; ?></td>
					    <td><?php echo $row['pos_abrv']; ?></td>
					    <td><?php echo $row['overall']; ?></td>
					    <td><?php echo $row['health']; ?></td>
					</tr>
					<?php } ?>

					<?php

					class teams {


						public $team;
						public $playerStats;

					public function getTms(){
							require("../../config.php");
						$this->team = array();


						$this->playerStats = array(
							'yards' => 0,
							'completions' => 0,
							'TD' => 0,
							'interceptions' => 0,
							'fumbles' => 0,
							'receptions' => 0,
							'carries' => 0,
							'sacks' => 0,
							'tackles' => 0,
						);
						echo "hi";
						$sql = "SELECT DISTINCT Players.player_id, Players.first_name, Players.last_name, Players.pos_abrv, Players.overall, Players.comp_pctR, Players.ypaR, Players.int_playR, Players.qb_rushR, Players.ypcR, Players.recR, Players.avgR, Players.pass_blockR, Players.run_blockR, Players.run_dR, Players.pass_dR, Players.rush_dR FROM Players, TeamPlayers WHERE TeamPlayers.team_id = 0";
						$results = $db->query($sql);
						$this->team[0] = $results->fetchAll(PDO::FETCH_ASSOC);
						// add stats array the each player created
						
						$sql = "SELECT DISTINCT Players.player_id, Players.first_name, Players.last_name, Players.pos_abrv, Players.overall, Players.comp_pctR, Players.ypaR, Players.int_playR, Players.qb_rushR, Players.ypcR, Players.recR, Players.avgR, Players.pass_blockR, Players.run_blockR, Players.run_dR, Players.pass_dR, Players.rush_dR FROM Players, TeamPlayers WHERE TeamPlayers.team_id = 1";
						$results = $db->query($sql);
						$this->team[1] = $results->fetchAll(PDO::FETCH_ASSOC);
						// add stats array the each player created

						foreach ($this->team as $key => $value) {
							array_push($this->team[$key]['playerStats'] = $this->playerStats);
						}
		
						echo '<pre>';
						var_dump($this->team[0]);

					}
				}

					$obj = new teams();
					$obj->getTms();
					?>
				</tbody>
			</table>
		</div>



	</body>

</html>