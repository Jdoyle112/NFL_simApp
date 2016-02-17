<?php

require_once("schedule.php");

class Game {

// TO-DO

	// save player stats (line up player stats index with $player)
	// add stats to account for reserves and subs
	// update schedule
	// have defense ratings influence play calling % (run vs pass)
	// add kicking


	public $homeTeam;
	public $awayTeam;
	public $score;
	public $receptions = 0;
	public $playerStats;
	public $touchdown;
	public $intercept;
	public $fumbles;
	public $Td;
	public $rushTd;
	public $passTd;
	public $passYrds;
	public $totRecYrds;
	public $rushYrds;
	public $playsNum;
	public $defTD;
	public $tackleOvr;
	public $runPlays;
	public $passPlays;
	public $sack;

	public $OLineRushOvr;
	public $OLineOvr;
	public $OLinePassOvr;
	public $qbOvr;
	public $receiveOvr;
	public $recOvr;
	public $rbOvr;
	public $dOvr;
	public $dRushOvr;
	public $dPassOvr;
	public $dRunOvr;
	public $qbCompPct;
	public $rushAdv;
	public $qbCompNum;
	public $passOvr;
	public $passAdv;
	public $qbYPA;
	public $homeTurnovers;
	public $totTackles;
	public $qbIntRt;
	public $rushOvr;

	public $seasonId;
	public $leagueTeams;
	public $team;
	public $gameId;



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

		// get 2 teams (will change when schedule created)
		echo $this->homeTeam = $leagueTeams[0]["team_id"];	// 0
		echo $this->awayTeam = $leagueTeams[1]["team_id"];	// 1

		$this->team = array();

		$this->playerStats = array(
			'yards' => 0,
			'completions' => 0,
			'td' => 0,
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

		// need to create game id's dynamically (when creating schedule, auto increment game id's and pull from schedule table)
		$this->gameId = 0;

	}	// end getGame


	public function simGame(){
		require("../../../config.php");		

		// need to add overtime if/else

		// call stat functions
		$this->getPlayerStats($this->gameId);
		$this->getTeamStats($this->gameId);

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
				$this->dOvr[$t] += $value[1];
				$this->dRushOvr[$t] += $value[4];
				$this->dPassOvr[$t] += $value[3];
				$this->dRunOvr[$t] += $value[2];
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
			$this->passAdv[] = $this->passOvr[$t] - $this->dPassOvr[$t] - $this->dRushOvr[$t];
			
			$this->rushOvr[] = $this->rbOvr[$t] + $this->OLineRushOvr[$t];
			$this->rushAdv[] = $this->rushOvr[$t] - $this->dRunOvr[$t];

			// determine team total plays, pass attmpts, and rush attmpts.
			$this->playsNum[] = rand(50, 77); 	// avg off plays/ game
			$pct_pass[] = rand(50, 70);
			$this->passPlays[] = round(($pct_pass[$t] / 100) * $this->playsNum[$t]);		// # of pass plays
			$this->runPlays[] = round($this->playsNum[$t] - $this->passPlays[$t]);		// # of run plays

		}

		// call functions to calculate player stats
		$this->advantages($tightEnds);
		$this->passPlayerStats($this->passPlays, $qbs, $this->p);
		$this->recPlayerStats($receivers, $passYrds);
		$this->runPlayerStats($this->runPlays, $runningBacks, $this->p);
		$this->defStats($defPlayers, $this->p, $this->r);
		$this-> fumble();
		// $this->fumble();		call when player yards stats are ready

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

			$TE_runBlk[$t] = $tightEnds[$t][0][2];	// added TE blocking
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
			$this->qbIntRt[] = $qbs[$t][0][3];
			$this->qbCompNum[] = round((($this->qbCompPct[$t] * $p[$t] + rand(-10, 10)) / 100) * $passPlays[$t]); 	// # of completions
			$chance = rand(-5, 5) / 10;
			$this->qbYPAr[$t] = ($this->qbYPA[$t] * $p[$t]) + $chance;		// yards per attempt
			for($x = 0; $x < $passPlays[$t]; $x++){
				$this->interception($this->qbIntRt, $t, $this->p);
				$this->td('pass', $t, $this->p, $this->r);
			}
		}
		
		// need to add sacks, rush yrds and push to array

	}	// end passPlayerStats



	public function recPlayerStats($receivers, $passYrds){
		
		// loop through each receiver adding his rec rating to a total # and assign receptions based on % of total team rec each indiv player holds
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

		// chooses rand player with min 1 catch and assigns TD (need to to make more accurate for # of receptions and ovr)
		$player = 0;
		for($t = 0; $t < 2; $t++){
			for($i = 0; $i < $this->passTd[$t]; $i++){ 
				do{
					$player = array_rand($this->team[$t], 1);
				}while($this->team[$t][$player]['playerStats']['receptions'] < 1);
				$this->team[$t][$player]['playerStats']['td'] += 1;
			}
		}


		// push qb stats to array
		for($t = 0; $t < 2; $t++){
			$this->team[$t][0]['playerStats']['yards'] = $this->totRecYrds[$t];		// pass yards pushed to qb stats array
			$this->team[$t][0]['playerStats']['completions'] = $this->qbCompNum[$t];		// completions pushed to qb stats array
			$this->team[$t][0]['playerStats']['interceptions'] = $this->intercept[$t];		// pass interceptions pushed to qb stats array
			$this->team[$t][0]['playerStats']['td'] = $this->passTd[$t];		// pass tds pushed to qb stats array
		}

		// need to add td's

	}	// end recPlayerStats



	public function runPlayerStats($runPlays, $runningBacks, $r){

		for($t = 0; $t < 2; $t++){
			$rbYPC[] = $runningBacks[$t][0][2];
			$chance = rand(-10, 10) / 10;
			$rbYPCr[] = round(($rbYPC[$t] * $r[$t]) + $chance);

			for($x = 0; $x < $runPlays[$t]; $x++){
				$this->rushYrds[$t] += $rbYPCr[$t];
				$this->td('rush', $t, $this->p, $this->r);
			}
		}

	}	// end runPlayerStats


	public function interception($qbIntRt, $t, $p){
		
		$chance = rand(-5, 5) / 10;
		$qbIntRt = round(($qbIntRt[$t] * $p[$t]) + $chance);
		$chance = rand(0, 100);
		if($qbIntRt >= $chance){
			$this->intercept[$t] += 1;
		}

	}	// end interception


	public function fumble(){

		// need to simulate on a per play basis

		// calculates odds of a fumble occuring (60%) and assign fumble to random player with minimum 1 yard in stat line
		for($t = 0; $t < 2; $t++){
			$chance = rand(1, 100);
			$c = rand(-10, 10);
			if($chance > (40 + $c)){
				do{
					$player = $this->team[$t][array_rand($this->team[$t])];
				}while($player['playerStats']['yards'] == 0);
				$player['playerStats']['fumbles'] = 1;

				do{
					$dPlayer = $this->team[$t][array_rand($this->team[$t])];
				}while($dPlayer['playerStats']['tackles'] == 0);
				$dPlayer['playerStats']['fumbles'] = 1;
				$this->fumbles[$t] += 1;
				// need to add fumble for rand defense player and update team stats		
			}
		}

	}	// end fumble


	public function td($play, $t, $p, $r){

		$chance = rand(1, 100);
		$c = rand(-5, 5) / 10;
		if($play == 'rush'){
			if($chance <= (3 / $r[$t] + $c)){	// 3% change of rushing TD/ attmpt factoring rush adv.
				$this->rushTd[$t] += 1;
				$this->Td[$t] += 1;
			}

		} else if($play == 'pass'){
			
			if($chance <= (4.5 * $p[$t] + $c)){	  // 4.5% chance pass td/ attmpt factoring adv.
				$this->passTd[$t] += 1;
				$this->Td[$t] += 1;
			}
		}
		// creat td's based on team adv's and assign to players with min stat line (Qb's and receivers share TD)
	}		// end td




	public function defStats($defPlayers, $p, $r){

		// tackles
			// db and lb 1.75x dl
			// use overall and pick rand player for each play giving db and lb 1.75x more than dl
		for($t = 0; $t < 2; $t++){
			$this->totTackles[$t] = ($this->qbCompNum[$t] + $this->runPlays[$t]) - $this->Td[$t] - $this->intercept[$t] - $this->homeFumbles;	// each play must be a tackle if not TD or TO (need to account for out of bounds plays in future)
			foreach ($defPlayers[$t] as $val) {
				if($val[5] == "LB" OR $val[5] == 'DB'){
					$this->tackleOvr[$t] += $val[1] * 1.75;
				} else {
					$this->tackleOvr[$t] += $val[1];				
				}
			}
		}
		
		// each def players ovr is added up and tackles are divied out to each player using random # (chance) 
		for($t = 0; $t < 2; $t++){	
			for($x = 0; $x < $this->totTackles[$t]; $x++){
				$a = rand(1, $this->tackleOvr[$t]);
				$oldVal = 0;
			    $player = 0;
				foreach ($defPlayers[$t] as $value) {
					if($value[5] == "LB" OR $value[5] == 'DB'){
						$newVal = ($value[1] * 1.75) + $oldVal;
					} else {
						$newVal = $value[1] + $oldVal;
					}
					if($newVal >= $a){
						$this->team[$t][10 + $player]['playerStats']['tackles'] += 1;	
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
		}	

		// sacks
			// loop through each pass attmpt. and multiply pass adv. by 6.7% + rand #
		// # of team sacks
		for ($t=0; $t < 2; $t++){ 
			for($s = 0; $s < $this->passPlays[$t]; $s++){
				$chance = rand(1, 100);
				if($chance < 6 / $r[$t]){		// divide to get def adv. 
					$this->sack[$t] += 1;
				}
			}
		}

		// for each sack, loop through each def player and assign them a sack
		for ($t=0; $t < 2; $t++) { 
			for($s = 0; $s < $this->sack[$t]; $s++){
				$a = rand(1, $this->dRushOvr[$t]);
				$oldVal = 0;
			    $player = 0;
				foreach ($defPlayers[$t] as $v) {
					$newVal = $v[4] + $oldVal;
					if($newVal >= $a){
						$this->team[$t][10 + $player]['playerStats']['sacks'] += 1;	
						$player = 0;
					break;
					} else {
						$oldVal += $v[4];
						$player++;
					}
				}
			}
		}

		// interceptions
		for ($t = 0; $t < 2; $t++){
			for($i = 0; $i < $this->intercept[$t]; $i++){
				$x = rand(1, $this->dPassOvr[$t]);	
				$oldVal = 0;
			    $player = 0;
				foreach ($defPlayers[$t] as $valu) {
					$newVal = $valu[3] + $oldVal;
					if($newVal >= $x){
						$this->team[$t][10 + $player]['playerStats']['interceptions'] += 1;	
						$player = 0;
					break;
					} else {
						$oldVal += $valu[3];
						$player++;
					}
				}
			}
		}

		// defensive TD's (add to box score)
		for ($t=0; $t < 2; $t++) { 
			$chance = rand(1, 100);
			if($chance < 17){
				$this->defTD[$t] = 1;
			}
		}


	}	// end defStats


	public function getPlayerStats($gameId){
		require("../../../config.php");	
		$this->simPlayerStats();

		// passing offense (push to array)
		echo '<br>' . "Number of home pass plays: " . $this->passPlays[0] . "<br>";
		echo "Number of away pass plays: " . $this->passPlays[1] . "<br>";
		echo "home interceptions: " . $this->intercept[0] . '<br>';
		echo "away interceptions: " . $this->intercept[1] . '<br>';
		echo "home pass td: " . $this->passTd[0] . '<br>';
		echo "away pass td: " . $this->passTd[1] . '<br>';
		echo 'home total pass yrds: ' . $this->totRecYrds[0] . '<br>';
		echo 'away total pass yrds: ' . $this->totRecYrds[1] . '<br>';
		echo 'home completions' . $this->qbCompNum[0] . '<br>';
		echo 'away completions' . $this->qbCompNum[1] . '<br>';


		// rushing offense
		echo "Number of home run plays: " . $this->runPlays[0] . "<br>";
		echo "Number of away run plays: " . $this->runPlays[1] . "<br>";
		echo "home rush td: " . $this->rushTd[0] . '<br>';
		echo "away rush td: " . $this->rushTd[1] . '<br>';
		echo "home total rush yards: " . $this->rushYrds[0] . "<br>";
		echo "away total rush yards: " . $this->rushYrds[1] . "<br>";


		// receiving offense
		echo 'home total rec yrds: ' . $this->totRecYrds[0] . '<br>';
		echo $this->team[0][1]['first_name'] . " has " . $this->team[0][1]['playerStats']['receptions'] . " receptions and " . $this->team[0][1]['playerStats']['yards'] . " yards" . "<br>";
		echo $this->team[0][2]['first_name'] . " has " . $this->team[0][2]['playerStats']['receptions'] . " receptions and " . $this->team[0][2]['playerStats']['yards'] . " yards" . "<br>";
		echo $this->team[0][3]['first_name'] . " has " . $this->team[0][3]['playerStats']['receptions'] . " receptions and " . $this->team[0][3]['playerStats']['yards'] . " yards" . "<br>";
		echo $this->team[0][4]['first_name'] . " has " . $this->team[0][4]['playerStats']['receptions'] . " receptions and " . $this->team[0][4]['playerStats']['yards'] . " yards" . "<br>";

		echo 'away total rec yrds: ' . $this->totRecYrds[1] . '<br>';
		echo $this->team[1][1]['first_name'] . " has " . $this->team[1][1]['playerStats']['receptions'] . " receptions and " . $this->team[1][1]['playerStats']['yards'] . " yards" . "<br>";
		echo $this->team[1][2]['first_name'] . " has " . $this->team[1][2]['playerStats']['receptions'] . " receptions and " . $this->team[1][2]['playerStats']['yards'] . " yards" . "<br>";
		echo $this->team[1][3]['first_name'] . " has " . $this->team[1][3]['playerStats']['receptions'] . " receptions and " . $this->team[1][3]['playerStats']['yards'] . " yards" . "<br>";
		echo $this->team[1][4]['first_name'] . " has " . $this->team[1][4]['playerStats']['receptions'] . " receptions and " . $this->team[1][4]['playerStats']['yards'] . " yards" . "<br>";


		// tackles
		echo "Home Total Team Tackles: " . $this->totTackles[0] . "<br>";
		echo $this->team[0][10]['first_name'] . " has " . $this->team[0][10]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][11]['first_name'] . " has " . $this->team[0][11]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][12]['first_name'] . " has " . $this->team[0][12]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][13]['first_name'] . " has " . $this->team[0][13]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][14]['first_name'] . " has " . $this->team[0][14]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][15]['first_name'] . " has " . $this->team[0][15]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][16]['first_name'] . " has " . $this->team[0][16]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][17]['first_name'] . " has " . $this->team[0][17]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][18]['first_name'] . " has " . $this->team[0][18]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][19]['first_name'] . " has " . $this->team[0][19]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][20]['first_name'] . " has " . $this->team[0][20]['playerStats']['tackles'] . " tackles" . "<br>";

		echo "Away Total Team Tackles: " . $this->totTackles[1] . "<br>";
		echo $this->team[1][10]['first_name'] . " has " . $this->team[1][10]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][11]['first_name'] . " has " . $this->team[1][11]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][12]['first_name'] . " has " . $this->team[1][12]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][13]['first_name'] . " has " . $this->team[1][13]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][14]['first_name'] . " has " . $this->team[1][14]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][15]['first_name'] . " has " . $this->team[1][15]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][16]['first_name'] . " has " . $this->team[1][16]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][17]['first_name'] . " has " . $this->team[1][17]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][18]['first_name'] . " has " . $this->team[1][18]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][19]['first_name'] . " has " . $this->team[1][19]['playerStats']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][20]['first_name'] . " has " . $this->team[1][20]['playerStats']['tackles'] . " tackles" . "<br>";


		// interceptions
		echo "home interceptions: " . $this->intercept[0] . '<br>';
		echo $this->team[0][13]['first_name'] . " has " . $this->team[0][13]['playerStats']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[0][14]['first_name'] . " has " . $this->team[0][14]['playerStats']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[0][15]['first_name'] . " has " . $this->team[0][15]['playerStats']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[0][16]['first_name'] . " has " . $this->team[0][16]['playerStats']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[0][17]['first_name'] . " has " . $this->team[0][17]['playerStats']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[0][18]['first_name'] . " has " . $this->team[0][18]['playerStats']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[0][19]['first_name'] . " has " . $this->team[0][19]['playerStats']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[0][20]['first_name'] . " has " . $this->team[0][20]['playerStats']['interceptions'] . " interceptions" . "<br>";

		echo "away interceptions: " . $this->intercept[1] . '<br>';
		echo $this->team[1][13]['first_name'] . " has " . $this->team[1][13]['playerStats']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[1][14]['first_name'] . " has " . $this->team[1][14]['playerStats']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[1][15]['first_name'] . " has " . $this->team[1][15]['playerStats']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[1][16]['first_name'] . " has " . $this->team[1][16]['playerStats']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[1][17]['first_name'] . " has " . $this->team[1][17]['playerStats']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[1][18]['first_name'] . " has " . $this->team[1][18]['playerStats']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[1][19]['first_name'] . " has " . $this->team[1][19]['playerStats']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[1][20]['first_name'] . " has " . $this->team[1][20]['playerStats']['interceptions'] . " interceptions" . "<br>";
		

		// sacks	
		echo "home sacks: " . $this->sack[0] . '<br>';		
		echo $this->team[0][10]['first_name'] . " has " . $this->team[0][10]['playerStats']['sacks'] . "<br>";
		echo $this->team[0][11]['first_name'] . " has " . $this->team[0][11]['playerStats']['sacks'] . "<br>";
		echo $this->team[0][12]['first_name'] . " has " . $this->team[0][12]['playerStats']['sacks'] . "<br>";
		echo $this->team[0][13]['first_name'] . " has " . $this->team[0][13]['playerStats']['sacks'] . "<br>";
		echo $this->team[0][14]['first_name'] . " has " . $this->team[0][14]['playerStats']['sacks'] . "<br>";
		echo $this->team[0][15]['first_name'] . " has " . $this->team[0][15]['playerStats']['sacks'] . "<br>";
		echo $this->team[0][16]['first_name'] . " has " . $this->team[0][16]['playerStats']['sacks'] . "<br>";
		echo $this->team[0][17]['first_name'] . " has " . $this->team[0][17]['playerStats']['sacks'] . "<br>";

		echo "away sacks: " . $this->sack[1] . '<br>';		
		echo $this->team[1][10]['first_name'] . " has " . $this->team[1][10]['playerStats']['sacks'] . "<br>";
		echo $this->team[1][11]['first_name'] . " has " . $this->team[1][11]['playerStats']['sacks'] . "<br>";
		echo $this->team[1][12]['first_name'] . " has " . $this->team[1][12]['playerStats']['sacks'] . "<br>";
		echo $this->team[1][13]['first_name'] . " has " . $this->team[1][13]['playerStats']['sacks'] . "<br>";
		echo $this->team[1][14]['first_name'] . " has " . $this->team[1][14]['playerStats']['sacks'] . "<br>";
		echo $this->team[1][15]['first_name'] . " has " . $this->team[1][15]['playerStats']['sacks'] . "<br>";
		echo $this->team[1][16]['first_name'] . " has " . $this->team[1][16]['playerStats']['sacks'] . "<br>";
		echo $this->team[1][17]['first_name'] . " has " . $this->team[1][17]['playerStats']['sacks'] . "<br>";
		echo $this->team[1][18]['first_name'] . " has " . $this->team[1][18]['playerStats']['sacks'] . "<br>";
		echo $this->team[1][19]['first_name'] . " has " . $this->team[1][19]['playerStats']['sacks'] . "<br>";
		echo $this->team[1][20]['first_name'] . " has " . $this->team[1][20]['playerStats']['sacks'] . "<br>";


		echo $this->team[1][20]['first_name'] . " has " . $this->team[1][20]['playerStats']['tackles'] . " tackles" . "<br>";

		// insert into PlayerStatsGame db (loop through each player, and insert their stats to db)
		for ($t=0; $t < 2; $t++) { 
			foreach ($this->team[$t] as $key1 => $value) {
				foreach ($value as $playerAttrs => $statVals) {
					echo '<pre>';
					// set the played id to be pushed to db
					if($playerAttrs == "player_id"){
						$playerId = $statVals;
					}
					
					$stKeys = "".implode(", ", array_keys($statVals))."";
					$stVals = "'".implode("', '", array_values($statVals))."'";	
					// if stat exists, push to db for each player
					if($stKeys !== ''){
						$sql="INSERT INTO PlayerStatsGame (game_id, player_id, $stKeys) VALUES ('$gameId', '$playerId', $stVals)";
						print $sql . "<br>";
						$db->exec($sql);
						echo "new record success" . '<br>';
					}
				}
			}
		}


	}	// end getPlayerStats



	public function getTeamStats($gameId, $homeTeam, $awayTeam){
		require("../../../config.php");	

		// need to add fg's 

		// home and away score
		for($t = 0; $t < 2; $t++){
			if($this->defTD[$t] == 1){
				$this->score[$t] = ($this->Td[$t] * 7) + 7;
			} else {
				$this->score[$t] = ($this->Td[$t] * 7);
			}	
			$totYrds[$t] = round(($this->totRecYrds[$t] + $this->rushYrds[$t]));
			$turnovers[$t] = ($this->intercept[$t] + $this->fumbles[$t]);
		}

		$homeScore = $this->score[0];
		$awayScore = $this->score[1];
		$homeTotYrds = $totYrds[0];
		$awayTotYrds = $totYrds[1];
		$homeTurnovers = $turnovers[0];
		$awayTurnovers = $turnovers[1];
		$homeRushYrds = $this->rushYrds[0];
		$awayRushYrds = $this->rushYrds[1];
		$homePassYrds = $this->totRecYrds[0];
		$awayPassYrds = $this->totRecYrds[1];
		$homeTd = $this->Td[0];
		$awayTd = $this->Td[1];
		$homeSack = $this->sack[0];
		$awaySack = $this->sack[1];
		$homeTotPlays = $this->playsNum[0];
		$awayTotPlays = $this->playsNum[1];


		echo "Home Team final score: " . $this->score[0] . "<br>";
		echo "Away Team final score: " . $this->score[1] . "<br>";		
		echo "Home Total Yards: " . $homeTotYrds . "<br>";
		echo "Away Total Yards: " . $awayTotYrds . "<br>";
		echo "Home Turnovers: " . $homeTurnovers . "<br>";
		echo "Away Turnovers: " . $awayTurnovers . "<br>";
		echo "Home Rush Yards: " . $homeRushYrds . "<br>";
		echo "Away Rush Yards: " . $awayRushYrds . "<br>";
		echo "Home Pass Yards: " . $homePassYrds . "<br>";
		echo "Away Pass Yards: " . $awayPassYrds . "<br>";
		echo "Home TD's: " . $homeTd . "<br>";
		echo "Away TD's: " . $awayTd . "<br>";
		echo "Home Sacks: " . $homeSack . "<br>";
		echo "Away Sacks: " . $awaySack . "<br>";
		echo "Home Total Plays: " . $homeTotPlays . "<br>";
		echo "Away Total Plays: " . $awayTotPlays . "<br>";


		// push to db (add home and away team id)
		$sql = "INSERT INTO GameStats (game_id, home_score, away_score, home_yards, away_yards, home_turnovers, away_turnovers, home_total_plays, away_total_plays, home_rushing_yards, away_rushing_yards, home_passing_yards, away_passing_yards, home_tds, away_tds, home_sacks, away_sacks) VALUES ('$gameId', '$homeScore', '$awayScore', '$homeTotYrds', '$awayTotYrds', '$homeTurnovers', '$awayTurnovers', '$homeTotPlays', '$awayTotPlays', '$homeRushYrds', '$awayRushYrds', '$homePassYrds', '$awayPassYrds', '$homeTd', '$awayTd', '$homeSack', '$awaySack')";
		$db->exec($sql);
		

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



