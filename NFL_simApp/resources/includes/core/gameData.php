<?php	

//class DBClass {

// TO-DO
	// set home/away = a teamId
	// create teamStats array in another file


	public function test(){
		echo "test";
	}

/*
	// game features

	public function getGame($seasonId, $gameId){
		$sql = $db->query("SELECT * FROM Schedule WHERE season_id = '$seasonId' AND game_id = '$gameId'");
	}

	public function getSchedule($seasonId){
		$sql = "SELECT * FROM Schedule WHERE season_id = '$seasonId'";
		return ($this->getResults($sql));
	}

	public function getTeamSchedule($teamId){
		$sql = "SELECT * FROM Schedule WHERE home_team = '$teamId' OR away_team = '$teamId'";
		return ($this->getResults($sql));
	}

	public function getWeeksGames($seasonId, $week){
		$sql = "SELECT * FROM Schedule WHERE season_id = '$seasonId' AND week = '$week'";
		return ($this->getResults($sql));
	}

	public function getTeams($seasonId){
		$sql = "SELECT * FROM Teams WHERE season_id = '$leagueId'";
		return ($this->getResults($sql));
	}

	public function getConferences(){
		$sql = "SELECT * FROM Conferences";
		return ($this->getResults($sql));
	}

	public function getDivisions(){
		$sql = "SELECT * FROM Divisions";
		return ($this->getResults($sql));
	}

	public function getPlayers(){

		require(ROOT_PATH . "config.php");

		$sql = "SELECT * FROM Players";
		$results = $db->query($sql);
		$results = $results->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}

	public function getTeamPlayers($teamId){
		$sql = "SELECT * FROM TeamPlayers WHERE team_id = '$teamId'";
		return ($this->getResults($sql));
	}

	public function getLeagueUsers($leagueId){
		$sql = "SELECT * FROM Users WHERE league_id = '$leagueId'";
		return ($this->getResults($sql));
	}

	public function getPositions(){
		$sql = "SELECT * FROM Positions";
		return ($this->getResults($sql));
	}

	public function getPlayerRatings($playerId){		// individ. player
		$sql = "SELECT * FROM Players WHERE player_id = '$playerId'";
		return ($this->getResults($sql));
	}

	public function insertSchedule($homeId, $visitorId, $seasonId, $week, $time){
		$sql = "INSERT INTO Schedule (home_team, away_team, season_id, week, time) VALUES ('$homeId', '$awayId', '$season', '$week', '$time')";
		return ($this->runQuery($sql));
	}

	public function saveGameResults($gameId){
		$sql = "UPDATE Schedule SET completed='1' WHERE game_id='$gameId'";
		return ($this->runQuery($sql));
	}

	public function saveGameStats($gameId, $gameStats){
		$columns = implode(", ",array_keys($gameStats));
		$escaped_values = array_map('mysql_real_escape_string', array_values($gameStats));
		$values  = implode("', '", $escaped_values);
		$sql = "INSERT INTO GameStats (game_id, $columns) VALUES ($gameId, '$values')";
		return ($this->runQuery($sql));
	}

	public function savePlayerGameStatsOffense($gameId, $playerGameStatsO, $teamId){
		$columns = implode(", ",array_keys($playerGameStatsO));
		$escaped_values = array_map('mysql_real_escape_string', array_values($playerGameStatsO));
		$values  = implode("', '", $escaped_values);
		$sql = "INSERT INTO PlayerStatsOffense (game_id, team_id, $columns) VALUES ($gameId, $teamId, '$values')";
		return ($this->runQuery($sql));
	}

	public function savePlayerGameStatsDefense($gameId, $playerGameStatsD, $teamId){
		$columns = implode(", ",array_keys($playerGameStatsD));
		$escaped_values = array_map('mysql_real_escape_string', array_values($playerGameStatsD));
		$values  = implode("', '", $escaped_values);
		$sql = "INSERT INTO PlayerStatsDefense (game_id, team_id, $columns) VALUES ($gameId, $teamId, '$values')";
		return ($this->runQuery($sql));
	}

	public function updateTeamStats($teamStats, $teamId){
		$columns = implode(", ",array_keys($teamStats));
		$escaped_values = array_map('mysql_real_escape_string', array_values($playerGameStatsD));
		$values  = implode("', '", $escaped_values);
		$sql = "UPDATE TeamStats SET $columns = '$values' WHERE team_id='$teamId'";		// check this syntax
		return ($this->runQuery($sql));
	}




	// front-office features

	public function tradePlayers($teamTrade1, $teamTrade2, $players_to1, $players_to2){
		$sql = "UPDATE TeamPlayers SET team_id = '$teamTrade1' WHERE team_player_id = '$players_to1'";
		$sql = "UPDATE TeamPlayers SET team_id = '$teamTrade2' WHERE team_player_id = '$players_to2'";
		return ($this->runQuery($sql));
	}





	public function getResults($sql){
		
	}


	public function runQuery($sql){
		$upd = $db->prepare($sql);
		$upd = $db->exec($sql);
		return $upd;
	}


//}	// end dbclass
*/

	echo "hi";

?>






