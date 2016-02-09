<?php

class Game {

// TO-DO

	// sim team and player stats
	// save team and player stats (line up player stats index with $player)
	// add stats to account for reserves and subs
	// update schedule
	// have defense ratings influence play calling % (run vs pass)
	// add kicking
	// condense code (if statements, queries, home and away, ect.)
	// convert player arrays kets to strings and values to int's
	// condense queries to array and and statements using query values to use array values (or put in loop)


	public $homeTeam;
	public $awayTeam;
	public $homeScore;
	public $awayScore;
	public $receptions = 0;
	public $playerStats;
	public $touchdown;
	public $intercept;
	public $homeFumble = 0;
	public $homeTd;
	public $homeRushTd;
	public $homePassTd;
	public $passYrds;
	public $totRecYrds;
	public $homeTotYrds;
	public $rushYrds;
	public $playsNum;
	public $defTD;

	public $OLineRushOvr;
	public $OLineOvr;
	public $OLinePassOvr;
	public $qbOvr;
	public $receiveOvr;
	public $recOvr;
	public $rbOvr;
	public $awayRBOvr;
	public $awayDOvr;
	public $homeDOvr;
	public $awayDRushOvr;
	public $awayDPassOvr;
	public $awayDRunOvr;
	public $qbCompPct;
	public $rushAdv;
	public $qbCompNum;
	public $passOvr;
	public $passAdv;
	public $qbYPA;
	public $homeTurnovers;
	public $totTackles;
	public $runPlays;
	public $passPlays;
	public $awayTackleOvr;
	public $qbIntRt;
	public $rushOvr;

	public $seasonId;
	public $week;
	public $weeksGames;
	public $leagueTeams;
	public $homeRoster;
	public $awayRoster;
	public $team;



	public function getGame($seasonId, $week){
		require("../../../config.php");
		
		// get game
		$sql = "SELECT * FROM Schedule WHERE season_id = $seasonId AND week = $week AND completed = 0";
		$results = $db->query($sql);
		$weeksGames = $results->fetchAll(PDO::FETCH_ASSOC);

		// get teams
		$sql = "SELECT * FROM Teams INNER JOIN Schedule ON Schedule.season_id = Teams.season_id";
		$results = $db->query($sql);
		$leagueTeams = $results->fetchAll(PDO::FETCH_ASSOC);

		// get 2 teams (team_id)
		echo $this->homeTeam = $leagueTeams[0]["team_id"];	// 0
		echo $this->awayTeam = $leagueTeams[1]["team_id"];	// 1

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
	
		$sql = "SELECT DISTINCT Players.player_id, Players.first_name, Players.last_name, Players.pos_abrv, Players.overall, Players.comp_pctR, Players.ypaR, Players.int_playR, Players.qb_rushR, Players.ypcR, Players.recR, Players.avgR, Players.pass_blockR, Players.run_blockR, Players.run_dR, Players.pass_dR, Players.rush_dR FROM Players, TeamPlayers WHERE TeamPlayers.team_id = 0";
		$results = $db->query($sql);
		$this->team[0] = $results->fetchAll(PDO::FETCH_ASSOC);
						
		$sql = "SELECT DISTINCT Players.player_id, Players.first_name, Players.last_name, Players.pos_abrv, Players.overall, Players.comp_pctR, Players.ypaR, Players.int_playR, Players.qb_rushR, Players.ypcR, Players.recR, Players.avgR, Players.pass_blockR, Players.run_blockR, Players.run_dR, Players.pass_dR, Players.rush_dR FROM Players, TeamPlayers WHERE TeamPlayers.team_id = 1";
		$results = $db->query($sql);
		$this->team[1] = $results->fetchAll(PDO::FETCH_ASSOC);
		

		foreach ($this->team as $key => $value) {
			array_push($this->team[$key]['playerStats'] = $this->playerStats);
		}
		
		//echo '<pre>';
		//var_dump($this->team);
	}


	public function simGame(){
		require("../../../config.php");		

		// need to add overtime if/else

		// call stat functions
		$this->simPlayerStats();
		$this->getTeamStats();

		// update TeamStats W/L
		if($this->homeScore > $this->awayScore){	// wrap in if statement to check if isset (1st game of season)
			$sql = "UPDATE TeamStats SET games_won = games_won + 1 WHERE team_id = $this->homeTeam";
			$results = $db->exec($sql);
			$sql = "UPDATE TeamStats SET games_lost = games_lost + 1 WHERE team_id = $this->awayTeam";
			$results = $db->exec($sql);
		} else if($this->homeScore > $this->awayScore){
			$sql = "UPDATE TeamStats SET games_won = games_won + 1 WHERE team_id = $this->awayTeam";
			$results = $db->exec($sql);
			$sql = "UPDATE TeamStats SET games_lost = games_lost + 1 WHERE team_id = $this->homeTeam";
			$results = $db->exec($sql);
		}

	} 	// end simGame




	public function simPlayerStats(){

		for($t = 0; $t < 2; $t++){
			foreach ($this->team[$t] as $val) {
				if($val['recR'] > 0){
					$receivers[$t][] = array($val['player_id'], $val['recR'], $val['avgR'], $val['overall']);
				}
				if($val['ypcR'] > 0){
					$runningBacks[$t][] = array($val['player_id'], $val['overall'], $val['ypcR']);
				}
				if($val['pos_abrv'] == "TE"){
					$tightEnds[$t][] = array($val['player_id'], $val['overall'], $val['run_blockR'], $val['pass_blockR']);
				}
				if($val['pos_abrv'] == "QB"){
					$qbs[$t][] = array($val['player_id'], $val['comp_pctR'], $val['ypaR'], $val['int_playR'], $val['overall']);
				}
				if($val['pos_abrv'] == "DL" || $val['pos_abrv'] == "LB" || $val['pos_abrv'] == "DB"){
					$defPlayers[$t][] = array($val['player_id'], $val['overall'], $val['run_dR'], $val['pass_dR'], $val['rush_dR'], $val['pos_abrv']);
				}	
				if($val['pos_abrv'] == "OT" || $val['pos_abrv'] == 'G' || $val['pos_abrv'] == "C"){
					$oLine[$t][] = array($val['player_id'], $val['overall'], $val['run_blockR'], $val['pass_blockR']);
				}
			}
		}

		for($t = 0; $t < 2; $t++){
			foreach ($defPlayers[$t] as $value) {
				$this->awayDOvr[$t] += $value[1];
				$this->awayDRushOvr[$t] += $value[4];
				$this->awayDPassOvr[$t] += $value[3];
				$this->awayDRunOvr[$t] += $value[2];
			}

			foreach ($receivers[$t] as  $v) {
				$this->receiveOvr[$t] += $v[3];
				$this->recOvr[$t] += $v[1];
			}

			foreach ($oLine[$t] as $value) {
				$this->OLineOvr[$t] += $value[1];
				$this->OLinePassOvr[$t] += $value[3];
				$this->OLineRushOvr[$t] += $value[2];
			}	
		}

		for($t = 0; $t < 2; $t++){

			$this->qbOvr[] = $qbs[$t][0][4];		// qb overall
			$this->qbYPA[] = $qbs[$t][0][2];
			$this->qbCompPct[] = $qbs[$t][0][1];
			$this->rbOvr[] = $runningBacks[$t][0][1];
			$this->qbIntRt[] = $qbs[$t][0][3]; 
			
			// calculate offense and defense advantages
			$this->passOvr[] = $this->qbOvr[$t] + $this->OLinePassOvr[$t]  + $this->receiveOvr[$t];
			$this->passAdv[] = $this->passOvr[$t] - $this->awayDPassOvr[$t] - $this->awayDRushOvr[$t];
			
			$this->rushOvr[] = $this->rbOvr[$t] + $this->OLineRushOvr[$t];
			$this->rushAdv[] = $this->rushOvr[$t] - $this->awayDRunOvr[$t];
			/*
			$homeOffOvr = $this->homeQBOvr + $this->homeRBOvr + $this->homeRecieveOvr + $this->homeOLineOvr;
			$homeOffAdv = $homeOffOvr - $this->awayDOvr;
			*/
			$this->awayPassAdv[] = $this->awayQBOvr[$t] + $this->awayOLinePassOvr[$t]  + $this->awayRecieveOvr[$t];
			$this->awayPassAdv[] -= $this->homeDPassOvr[$t] + $this->homeDRushOvr[$t];

			// determine team total plays, pass attmpts, and rush attmpts.
			$this->playsNum[] = rand(50, 77); 	// avg off plays/ game
			$pct_pass[] = rand(50, 70);
			$this->passPlays[] = round(($pct_pass[$t] / 100) * $this->playsNum[$t]);		// # of pass plays
			$this->runPlays[] = round($this->playsNum[$t] - $this->passPlays[$t]);		// # of run plays

		}

		echo "qb ovr: " . $this->qbOvr[1] . "<br>";
		echo "qb pass ypa: " . $this->qbYPA[0] . "<br>";
		echo "pass adv: " . $this->passAdv[1] . "<br>";
		echo "pass ovr: " . $this->passOvr[1] . "<br>";

		echo "Number of home pass plays: " . $this->passPlays[$t] . "<br>";
		echo "Number of home run plays: " . $this->runPlays[$t] . "<br>";


		// call functions to calculate player stats
		$this->advantages($tightEnds);
		$this->passPlayerStats($this->passPlays, $qbs, $this->p);
		$this->recPlayerStats($receivers, $passYrds);
		$this->runPlayerStats($this->runPlays, $runningBacks, $this->p);
		//$this->defStats($defPlayers, $this->p, $this->r);
		// $this->fumble();		call when player yards stats are ready
		echo "home interceptions: " . $this->intercept[0] . '<br>';
		echo "away interceptions: " . $this->intercept[1] . '<br>';

		if($this->homeRushTd > 0){
			echo "Rushing Td's: " . $this->homeRushTd . "<br>";
		}
		if($this->homePassTd > 0){
			echo "Home pass td's: " . $this->homePassTd . "<br>";
		}
		

	}	// end simPlayerStats


	public function advantages($tightEnds){

		for($t = 0; $t < 2; $t++){

			// pass adv
			if($this->passAdv[$t] > 0 and $this->passAdv[$t] <= 4 ){
				$this->p[] = 1.1;
			} else if($this->passAdv[$t] > 4 and $this->passAdv[$t] < 9){
				$this->p[] = 1.2;
			} else if($this->passAdv[$t] >= 9){
				$this->p[] = 1.3;
			} else if ($this->passAdv[$t] <= 0 and $this->passAdv[$t] >= -4){
				$this->p[] = .9;
			} else if ($this->passAdv[$t] < -4 and $this->passAdv[$t] >= -9){
				$this->p[] = .8;
			} else if ($this->passAdv[$t] < -9){
				$this->p[] = .7;
			} else {
				echo "error";
			}


			$TE_runBlk[$t] = $tightEnds[$t][0][2];
			$this->rushAdv[$t] += $TE_runBlk[$t];
			// run adv
			if($this->rushAdv[$t] > 0 and $this->rushAdv[$t] <= 4 ){
				$this->r[] = 1.1;
			} else if($this->rushAdv[$t] > 4 and $this->rushAdv[$t] < 9){
				$this->r[] = 1.2;
			} else if($this->rushAdv[$t] >= 9){
				$this->r[] = 1.35;
			} else if ($this->rushAdv[$t] <= 0 and $this->rushAdv[$t] >= -4){
				$this->r[] = .95;
			} else if ($this->rushAdv[$t] < -4 and $this->rushAdv[$t] >= -9){
				$this->r[] = .85;
			} else if ($this->rushAdv[$t] < -9){
				$this->r[] = .75;
			} else {
				echo "error";
			}
		}

	}	// end advantages



	public function passPlayerStats($passPlays, $qbs, $p){

		// need to multiple things by $p

		for($t = 0; $t < 2; $t++){
			$this->qbYPA[] = $qbs[$t][0][2];
			echo "home ypa: " . $this->qbYPA[1] . "<br>";
			$this->qbIntRt[] = $qbs[$t][0][3];
			$this->qbCompNum[] = round((($this->qbCompPct[$t] + rand(-10, 10)) / 100) * $passPlays[$t]); 	// # of completions
			$chance = rand(-5, 5) / 10;
			$this->qbYPAr[$t] = ($this->qbYPA[$t] * $p[$t]) + $chance;
			for($x = 0; $x < $passPlays[$t]; $x++){
				$this->interception($this->qbIntRt, $t, $this->p);
				$this->td('pass', $t, $this->p, $this->r);
			}
		}
		
		// need to add sacks, rush yrds and push to array

	}	// end passPlayerStats



	public function recPlayerStats($receivers, $passYrds){

		// need to multiple things by $p
		
		for($t = 0; $t < 2; $t++){	
			for($x = 0; $x < $this->qbCompNum[$t]; $x++){
				$a = rand(1, $this->recOvr[$t]);
				$oldVal = 0;
			    $player = 0;
				foreach ($receivers[$t] as $value) {
					$newVal = $value[1] + $oldVal;
					if($newVal >= $a){
						$this->team[$t][1 + $player]['playerStats']['receptions'] += 1;	
						$player = 0;
					break;
					} else {
						$oldVal += $value[1];
						$player++;
					}
				}	
			}
		}	

		for($t = 0; $t < 2; $t++){	
			// need to get each players catches and multiply by ypc
			$player = 1;
			foreach ($receivers[$t] as $val) {
				$avgRecYrds[$t] = ($val[2] * ($this->qbYPAr[$t] / 7.3));
				$playerRec[$t] = $this->team[$t][$player]['playerStats']['receptions'];
				$recYrds[$t] = round($avgRecYrds[$t] * $playerRec[$t]);
				$this->team[$t][$player]['playerStats']['yards'] = $recYrds[$t];
				$player++;
				$this->totRecYrds[$t] += $recYrds[$t];
			}
		}

		echo 'total rec yrds: ' . $this->totRecYrds[0] . '<br>';
		echo 'total rec yrds: ' . $this->totRecYrds[1] . '<br>';
		echo $this->team[0][1]['first_name'] . " has " . $this->team[0][1]['playerStats']['receptions'] . " receptions and " . $this->team[0][1]['playerStats']['yards'] . " yards" . "<br>";
		echo $this->team[0][2]['first_name'] . " has " . $this->team[0][2]['playerStats']['receptions'] . " receptions and " . $this->team[0][2]['playerStats']['yards'] . " yards" . "<br>";
		echo $this->team[0][3]['first_name'] . " has " . $this->team[0][3]['playerStats']['receptions'] . " receptions and " . $this->team[0][3]['playerStats']['yards'] . " yards" . "<br>";
		echo $this->team[0][4]['first_name'] . " has " . $this->team[0][4]['playerStats']['receptions'] . " receptions and " . $this->team[0][4]['playerStats']['yards'] . " yards" . "<br>";
		echo $this->team[1][1]['first_name'] . " has " . $this->team[1][1]['playerStats']['receptions'] . " receptions and " . $this->team[1][1]['playerStats']['yards'] . " yards" . "<br>";
		echo $this->team[1][2]['first_name'] . " has " . $this->team[1][2]['playerStats']['receptions'] . " receptions and " . $this->team[1][2]['playerStats']['yards'] . " yards" . "<br>";
		echo $this->team[1][3]['first_name'] . " has " . $this->team[1][3]['playerStats']['receptions'] . " receptions and " . $this->team[1][3]['playerStats']['yards'] . " yards" . "<br>";
		echo $this->team[1][4]['first_name'] . " has " . $this->team[1][4]['playerStats']['receptions'] . " receptions and " . $this->team[1][4]['playerStats']['yards'] . " yards" . "<br>";
		
		
		// need to add td's


	}	// end recPlayerStats



	public function runPlayerStats($runPlays, $runningBacks, $r){

		for($t = 0; $t < 2; $t++){
			$rbYPC[] = $runningBacks[$t][0][2];
			$chance = rand(-10, 10) / 10;
			$rbYPCr[] = ($rbYPC[$t] * $r[$t]) + $chance;
			echo "total rush plays: " . $runPlays[$t] . "<br>";

			for($x = 0; $x < $runPlays[$t]; $x++){
				$this->rushYrds[$t] += $rbYPCr[$t];
				$this->td('rush', $t, $this->p, $this->r);
			}
		}

		echo "total rush yards: " . $this->rushYrds[0] . "<br>";
		echo "total rush yards: " . $this->rushYrds[1] . "<br>";

		// need to add td's and push stats to array

	}	// end runPlayerStats


	public function interception($qbIntRt, $t, $p){
		
		$chance = rand(-5, 5) / 10;
		$qbIntRt = round(($qbIntRt[$t] * $p[$t]) + $chance);
		$chance = rand(0, 100);
		if($qbIntRt >= $chance){
			$this->intercept[] += 1;
		}

	}	// end interception


	public function fumble($t){

		// calculates odds of a fumble occuring (60%) and assign fumble to random player with minimum 1 yard in stat line
		$chance = rand(1, 100);
		if($chance > 40){
			do{
				$player = $this->homeRoster[array_rand($this->homeRoster)];
			}while($player['playerStats']['yards'] == 0);
			$player['playerStats']['fumbles'] = 1;
			/*
			do{
				$dPlayer = $this->awayRoster[array_rand($this->awayRoster)];
			}while($dPlayer['playerStats']['tackles'] == 0);
			$dPlayer['playerStats']['fumbles'] = 1;
			*/
			$this->homeFumbles++;

			// need to add fumble for rand defense player and update team stats		
		}

	}	// end fumble


	public function td($play, $t, $p, $r){

		if($this->homePassAdv > 0 and $this->homePassAdv <= 4 ){
			$i = .9;
		} else if($this->homePassAdv > 4 and $this->homePassAdv < 9){
			$i = .8;
		} else if($this->homePassAdv >= 9){
			$i = .7;
		} else if ($this->homePassAdv <= 0 and $this->homePassAdv >= -4){
			$i = 1.1;
		} else if ($this->homePassAdv < -4 and $this->homePassAdv >= -9){
			$i = 1.2;
		} else if ($this->homePassAdv < -9){
			$i = 1.3;
		} else {
			echo "error";
		}

		if($this->homeRushAdv > 0 and $this->homeRushAdv <= 4 ){
			$r = 1.1;
		} else if($this->homeRushAdv > 4 and $this->homeRushAdv < 9){
			$r = 1.2;
		} else if($this->homeRushAdv >= 9){
			$r = 1.35;
		} else if ($this->homeRushAdv <= 0 and $this->homeRushAdv >= -4){
			$r = .90;
		} else if ($this->homeRushAdv < -4 and $this->homeRushAdv >= -9){
			$r = .80;
		} else if ($this->homeRushAdv < -9){
			$r = .70;
		} else {
			echo "error";
		}

		$chance = rand(1, 100);
		if($play == 'rush'){

			if($chance <= (3 / $r)){	// 3% change of rushing TD/ attmpt factoring rush adv.
				$this->homeRushTd++;
				$this->homeTd++;
			}

		} else if($play == 'pass'){
			
			if($chance <= (4.5 * $i)){	  // 4.5% chance pass td/ attmpt factoring adv.
				$this->homePassTd++;
				$this->homeTd++;
			}
		}

		// creat td's based on team adv's and assign to players with min stat line (Qb's and receivers share TD)
	}		// end td




	public function defStats($defPlayers){

		// tackles
			// db and lb 1.75x dl
			// use overall and pick rand player for each play giving db and lb 1.75x more than dl
		$this->totTackles = ($this->qbCompNum + $this->runPlays) - $this->homeTd - $this->intercept - $this->homeFumbles;	// each play must be a tackle if not TD or TO
		
		foreach ($defPlayers as $value) {
			if($value[5] == "LB" OR $value[5] == 'DB'){
				$this->awayTackleOvr += $value[1] * 1.75;
			} else {
				$this->awayTackleOvr += $value[1];
			}
		}
		for($t = 0; $t < $this->totTackles; $t++){
			$a = rand(1, $this->awayTackleOvr);
			$oldVal = 0;
		    $player = 0;
			foreach ($defPlayers as $value) {
				if($value[5] == "LB" OR $value[5] == 'DB'){
					$newVal = ($value[1] * 1.75) + $oldVal;
				} else {
					$newVal = $value[1] + $oldVal;
				}
				if($newVal >= $a){
					$this->homeRoster[10 + $player]['playerStats']['tackles'] += 1;	
					$player = 0;
				break;
				} else {
					if($value[5] == "LB" OR $value[5] == 'DB'){
						$oldVal += ($value[1] * 1.75);
						$player++;
					}else {
						$oldVal += $value[1];
						$player++;
					}
				}
			}
		}

		echo "Total Team Tackles: " . $this->totTackles . "<br>";
		echo $this->homeRoster[10]['first_name'] . " has " . $this->homeRoster[10]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->homeRoster[11]['first_name'] . " has " . $this->homeRoster[11]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->homeRoster[12]['first_name'] . " has " . $this->homeRoster[12]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->homeRoster[13]['first_name'] . " has " . $this->homeRoster[13]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->homeRoster[14]['first_name'] . " has " . $this->homeRoster[14]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->homeRoster[15]['first_name'] . " has " . $this->homeRoster[15]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->homeRoster[16]['first_name'] . " has " . $this->homeRoster[16]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->homeRoster[17]['first_name'] . " has " . $this->homeRoster[17]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->homeRoster[18]['first_name'] . " has " . $this->homeRoster[18]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->homeRoster[19]['first_name'] . " has " . $this->homeRoster[19]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->homeRoster[20]['first_name'] . " has " . $this->homeRoster[20]['playerStats']['tackles'] . " tackles" . "<br>";
		

		// sacks
			// loop through each pass attmpt. and multiply pass adv. by 6.7% + rand #
		if($this->awayPassAdv > 0 and $this->awayPassAdv <= 4 ){
			$r = 1.1;
		} else if($this->awayPassAdv > 4 and $this->awayPassAdv < 9){
			$r = 1.2;
		} else if($this->awayPassAdv >= 9){
			$r = 1.35;
		} else if ($this->awayPassAdv <= 0 and $this->awayPassAdv >= -4){
			$r = .90;
		} else if ($this->awayPassAdv < -4 and $this->awayPassAdv >= -9){
			$r = .80;
		} else if ($this->awayPassAdv < -9){
			$r = .70;
		} else {
			echo "error";
		}

		echo "away pass adv: " . $this->awayPassAdv . "<br>";

		// # of team sacks
		$sack = 0;
		for($s = 0; $s < $this->passPlays[$t]; $s++){
			$chance = rand(1, 100);
			if($chance < 6 / $r){		// divide to get def adv.
				$sack++;
			}
		}

		// for each sack, loop through each def player and assign them a sack
		for($s = 0; $s < $sack; $s++){
			$a = rand(1, $this->awayDRushOvr);
			echo "rand #: " . $a . "<br>";
			$oldVal = 0;
		    $player = 0;
			foreach ($defPlayers as $value) {
				$newVal = $value[4] + $oldVal;
				if($newVal >= $a){
					$this->homeRoster[10 + $player]['playerStats']['sacks'] += 1;	
					$player = 0;
				break;
				} else {
					$oldVal += $value[4];
					$player++;
				}
			}
		}

		echo "Total Team Sacks: " . $sack . "<br>";
		echo $this->homeRoster[10]['first_name'] . " has " . $this->homeRoster[10]['playerStats']['sacks'] . "<br>";
		echo $this->homeRoster[11]['first_name'] . " has " . $this->homeRoster[11]['playerStats']['sacks'] . "<br>";
		echo $this->homeRoster[12]['first_name'] . " has " . $this->homeRoster[12]['playerStats']['sacks'] . "<br>";
		echo $this->homeRoster[13]['first_name'] . " has " . $this->homeRoster[13]['playerStats']['sacks'] . "<br>";


		// interceptions
		echo "away d pass ovr: " . $this->awayDPassOvr . "<br>";
		for($i = 0; $i < $this->intercept; $i++){
			$x = rand(1, $this->awayDPassOvr);	
			$oldVal = 0;
		    $player = 0;
			foreach ($defPlayers as $value) {
				$newVal = $value[3] + $oldVal;
				if($newVal >= $x){
					$this->homeRoster[10 + $player]['playerStats']['interceptions'] += 1;	
					$player = 0;
				break;
				} else {
					$oldVal += $value[3];
					$player++;
				}
			}
		}

		echo $this->homeRoster[17]['first_name'] . " has " . $this->homeRoster[17]['playerStats']['interceptions'] . " interceptions and " . $this->homeRoster[10]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->homeRoster[18]['first_name'] . " has " . $this->homeRoster[18]['playerStats']['interceptions'] . " interceptions and " . $this->homeRoster[11]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->homeRoster[19]['first_name'] . " has " . $this->homeRoster[19]['playerStats']['interceptions'] . " interceptions and " . $this->homeRoster[12]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->homeRoster[20]['first_name'] . " has " . $this->homeRoster[20]['playerStats']['interceptions'] . " interceptions and " . $this->homeRoster[13]['playerStats']['tackles'] . " tackles" . "<br>";


		// defensive TD's (add to box score)
		$chance = rand(1, 100);
		if($chance < 17){
			$this->defTD = 1;
		}


	}	// end defStats



	public function getTeamStats(){

		// home and away score
		if($this->defTD == 1){
			$this->homeScore = ($this->homeTd * 7) + 7;
		} else {
			$this->homeScore = ($this->homeTd * 7);
		}
		
		//$this->awayScore = $this->awayTd;
		echo "Home Team final score: " . $this->homeScore . "<br>";

		// home and away yards
		$this->homeTotYrds = ($this->totRecYrds + $this->rushYrds);
		echo "Home Total Yards: " . round($this->homeTotYrds) . "<br>";

		// home and away turnovers
		$this->homeTurnovers = ($this->intercept + $this->homeFumbles);
		echo "Home Turnovers: " . $this->homeTurnovers . "<br>";

		// home aand away total plays
		$this->playsNum[$t];
		echo "Home Total Plays: " . $this->playsNum[$t] . "<br>";		

		// home and away rush and pass yards
		$this->totRecYrds;
		echo "Home Passing Yards: " . round($this->totRecYrds) . "<br>";
		$this->rushYrds;
		echo "Total Rush Yards: " . round($this->rushYrds) . "<br>";

		// home and away td's
		echo "Home Touchdowns: " . $this->homeTd . "<br>";

		// home and away sacks


		// push to db


	} 	// end getTeamStats



	public function sims(){
		for($x = 0; $x <= 8; $x++){
		$this->simPlayerStats();
		}

	}



}	// end class

?>

<p><?php 

	$obj = new Game();
	$obj->getGame(0, 1);
	$obj->simGame();
	//$obj->sims();

 ?></p>



