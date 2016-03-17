<?php 

class Schedule {
	// create games based on # of teams

	public $leagueTeamsArr;
	public $leagueTeamsId;
	public $week;
	public $homeTeam;
	public $awayTeam;
	public $game;
	public $schedule;
	public $weeks;

	
	public function getTeams(){
		require(ROOT_PATH . "config.php");

		//$sql = "SELECT * FROM Teams INNER JOIN Schedule ON Schedule.season_id = Teams.season_id";
		global $seasonId;
		$sql = "SELECT * FROM Teams WHERE season_id = $seasonId";
		$results = $db->query($sql);
		$this->leagueTeamsArr = $results->fetchAll(PDO::FETCH_ASSOC);

		// store all league team id's in an array
		foreach ($this->leagueTeamsArr as $key => $value) {
			$this->leagueTeamsId[] = $value['team_id'];
			$seasonId = $value['season_id'];
		}
	}	// getTeams


	public function createSchedule(){
		require(ROOT_PATH . "config.php");

		global $seasonId;
		// call get teams func
		$this->getTeams();

		$this->week = array();
		$this->games = array();

		$temp = $this->leagueTeamsId;
		shuffle($temp);
		$numTeams = count($this->leagueTeamsId);
		
		// loop through every week of season (16)
		for ($w=0; $w < 17; $w++) { 
			$this->week[] =  array($this->games);
			// loop through # of games each week (number of teams divided by 2)
			for ($x = 0; $x < $numTeams / 2; $x++) { 
				$val1 = array_pop($temp);
				$val2 = array_pop($temp);
				if(is_null($val1) OR is_null($val2)){
					$temp = $this->leagueTeamsId;
					shuffle($temp);
					$val1 = array_pop($temp);
					$val2 = array_pop($temp);
				}

				$this->games[$x]['homeTeam'] = $val1;
				$this->games[$x]['awayTeam'] = $val2;
			}
		}

		// call insertSchedule func
		$this->insertSchedule($seasonId);

	}	// end createSchedule


	public function insertSchedule($seasonId){
		require(ROOT_PATH . "config.php");
		// home_team and away_team are referencing the team_id

		foreach ($this->week as $wk => $wkgame) {
			$this->weeks = $wk;
			foreach ($wkgame as $key => $value) {
				foreach ($value as $game => $team) {
					$this->homeTeam = $team['homeTeam'];
					$this->awayTeam = $team['awayTeam'];
					$sql = "INSERT INTO Schedule (season_id, home_team, away_team, week) VALUES ('$seasonId', '$this->homeTeam', '$this->awayTeam', '$this->weeks')";
					$db->exec($sql);
				}
			}
		}

	}	  // end insertSchedule 


} 	// end class

?>



