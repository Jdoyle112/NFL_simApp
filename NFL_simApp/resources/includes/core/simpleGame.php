<?php

class Game {

// TO-DO

	// save player stats (line up player stats index with $player)
	// add stats to account for reserves and subs
	// update schedule
	// have defense ratings influence play calling % (run vs pass)
	// add kicking
	// add injuries
	// rb stats
	// qb rush stats
	// overtime
	// sack multiplier for pass rushers


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
	public $qbYPAr;
	public $homeTurnovers;
	public $totTackles;
	public $qbIntRt;
	public $rushOvr;

	public $seasonId;
	public $leagueTeams;
	public $team;
	public $key;
	public $playerId;

	public function getGame($gameId){
		require(ROOT_PATH . "config.php");
		
		// get game
		$sql = "SELECT * FROM Schedule WHERE game_id = $gameId";
		$results = $db->query($sql);
		$game = $results->fetchAll(PDO::FETCH_ASSOC);
		foreach ($game as $value) {
			$this->homeTeam = $value['home_team'];
			$this->awayTeam = $value['away_team'];
			$this->seasonId = $value['season_id'];
		}

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
	
		$sql = "SELECT Players.player_id, Players.first_name, Players.last_name, Players.pos_abrv, Players.overall, Players.comp_pctR, Players.ypaR, Players.int_playR, Players.qb_rushR, Players.ypcR, Players.recR, Players.avgR, Players.pass_blockR, Players.run_blockR, Players.run_dR, Players.pass_dR, Players.rush_dR FROM Players INNER JOIN TeamPlayers ON Players.player_id = TeamPlayers.player_id WHERE TeamPlayers.team_id = $this->homeTeam";
		$results = $db->query($sql);
		$this->team[0] = $results->fetchAll(PDO::FETCH_ASSOC);
						
		$sql = "SELECT Players.player_id, Players.first_name, Players.last_name, Players.pos_abrv, Players.overall, Players.comp_pctR, Players.ypaR, Players.int_playR, Players.qb_rushR, Players.ypcR, Players.recR, Players.avgR, Players.pass_blockR, Players.run_blockR, Players.run_dR, Players.pass_dR, Players.rush_dR FROM Players INNER JOIN TeamPlayers ON Players.player_id = TeamPlayers.player_id WHERE TeamPlayers.team_id = $this->awayTeam";
		$results = $db->query($sql);
		$this->team[1] = $results->fetchAll(PDO::FETCH_ASSOC);
		
		for($t = 0; $t < 2; $t++){
			foreach ($this->team[$t] as $key => $value) {
				//echo 'key: ' . $key . ' value: ' . $value . '<br>';
				array_push($this->team[$t][$key], $this->playerStats);
			}	
		}


		//var_dump($this->team[0]);

	}	// end getGame


	public function simGame($gameId){
		require(ROOT_PATH . 'config.php');	

		// call getGame
		$this->getGame($gameId);
		//$this->simPlayerStats();

		// need to add overtime if/else

		// call stat functions
		$this->getPlayerStats($gameId);
		$this->getTeamStats($gameId);

		// update TeamStats W/L
		/*
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
		}*/

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
		$this->recPlayerStats($receivers);
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



	public function recPlayerStats($receivers){
		
		// loop through each receiver adding his rec rating to a total # and assign receptions based on % of total team rec each indiv player holds
		for($t = 0; $t < 2; $t++){		// run once for each team
			for($x = 0; $x < $this->qbCompNum[$t]; $x++){	// run once for each completion
				$a = rand(1, $this->recOvr[$t]);	// generate random # between 1 and the total added rec ovr.
				$oldVal = 0;
			    $player = 0;
				foreach ($receivers[$t] as $value) {	// for each receiver 
					$newVal = $value[1] + $oldVal; 
					$player = $value[0];
					if($newVal >= $a){
						$this->findPlayerIndex($this->team[$t], $player);
						$this->team[$t][$this->key]['0']['receptions'] += 1;	
						break;
					} else {
						$oldVal += $value[1];
					}
				}	
			}
		}	

		for($t = 0; $t < 2; $t++){	
			// need to get each players catches and multiply by ypc
			//var_dump($receivers[$t]);
			foreach ($receivers[$t] as $val) {
				$player = $val[0];
				$this->findPlayerIndex($this->team[$t], $player);
				$avgRecYrds[$t] = ($val[2] * ($this->qbYPAr[$t] / 7.3));
				//echo 'avg rec yards: ' . $avgRecYrds[$t] . '<br>';
				$playerRec[$t] = $this->team[$t][$this->key]['0']['receptions'];
				$recYrds[$t] = round($avgRecYrds[$t] * $playerRec[$t]);
				//echo 'rec yrds: ' . $recYrds[$t] . '<br>';
				$this->team[$t][$this->key]['0']['yards'] = $recYrds[$t];
				$this->totRecYrds[$t] += $recYrds[$t];

			}
		}

		// chooses rand player with min 1 catch and assigns TD (need to to make more accurate for # of receptions and ovr)
		$player = 0;
		for($t = 0; $t < 2; $t++){
			for($i = 0; $i < $this->passTd[$t]; $i++){ 
				do{
					$player = array_rand($this->team[$t], 1);
				}while($this->team[$t][$player]['0']['receptions'] < 1);
				$this->team[$t][$player]['0']['td'] += 1;
			}
		}


		// push qb stats to array
		for($t = 0; $t < 2; $t++){
			$this->team[$t][0]['0']['yards'] = $this->totRecYrds[$t];		// pass yards pushed to qb stats array
			$this->team[$t][0]['0']['completions'] = $this->qbCompNum[$t];		// completions pushed to qb stats array
			$this->team[$t][0]['0']['interceptions'] = $this->intercept[$t];		// pass interceptions pushed to qb stats array
			$this->team[$t][0]['0']['td'] = $this->passTd[$t];		// pass tds pushed to qb stats array
		}

		// need to add td's

	}	// end recPlayerStats



	public function runPlayerStats($runPlays, $runningBacks, $r){

		for($t = 0; $t < 2; $t++){
			$rbYPC[] = $runningBacks[$t][0][2];		// rb YPC from starter
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
				}while($player['0']['yards'] == 0);
				$player['0']['fumbles'] = 1;

				do{
					$dPlayer = $this->team[$t][array_rand($this->team[$t])];
				}while($dPlayer['0']['tackles'] == 0);
				$dPlayer['0']['fumbles'] = 1;
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
			$this->totTackles[$t] = ($this->qbCompNum[$t] + $this->runPlays[$t]) - $this->Td[$t] - $this->intercept[$t] - $this->fumbles[$t];	// each play must be a tackle if not TD or TO (need to account for out of bounds plays in future)
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
				foreach ($defPlayers[$t] as $value) {
					$player = $value[0];
					if($value[5] == "LB" OR $value[5] == 'DB'){
						$newVal = ($value[1] * 1.75) + $oldVal;
					} else {
						$newVal = $value[1] + $oldVal;
					}

					if($newVal >= $a){
						$this->findPlayerIndex($this->team[$t], $player);
						$this->team[$t][$this->key]['0']['tackles'] += 1;	
					break;
					} else {
						if($value[5] == "LB" OR $value[5] == 'DB'){
							$oldVal += ($value[1] * 1.75);
							$player++;
						}else {
							$oldVal += $value[1];
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
				foreach ($defPlayers[$t] as $v) {
					$player = $v[0];
					$newVal = $v[4] + $oldVal;
					if($newVal >= $a){
						$this->findPlayerIndex($this->team[$t], $player);
						$this->team[$t][$this->key]['0']['sacks'] += 1;	
					break;
					} else {
						$oldVal += $v[4];
					}
				}
			}
		}

		// interceptions
		for ($t = 0; $t < 2; $t++){
			for($i = 0; $i < $this->intercept[$t]; $i++){
				$x = rand(1, $this->dPassOvr[$t]);	
				$oldVal = 0;
				foreach ($defPlayers[$t] as $valu) {
					$player = $valu[0];
					$newVal = $valu[3] + $oldVal;
					if($newVal >= $x){
						$this->findPlayerIndex($this->team[$t], $player);
						$this->team[$t][$this->key]['0']['interceptions'] += 1;	
					break;
					} else {
						$oldVal += $valu[3];
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


	// use to find the index key of a player using player id
	public function findPlayerIndex($arr, $player){
		//var_dump($arr);
		//echo $player;
		foreach ($arr as $k => $id) {
			if($id['player_id'] == $player){
				$this->key = $k;
				return $this->key;
				
			}
		}
	}


	public function getPlayerStats($gameId){
		require(ROOT_PATH . "config.php");
		$this->simPlayerStats();

		// passing offense (push to array)
		/*echo '<br>' . "Number of home pass plays: " . $this->passPlays[0] . "<br>";
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
		echo $this->team[0][1]['first_name'] . " has " . $this->team[0][1]['0']['receptions'] . " receptions and " . $this->team[0][1]['0']['yards'] . " yards" . "<br>";
		echo $this->team[0][2]['first_name'] . " has " . $this->team[0][2]['0']['receptions'] . " receptions and " . $this->team[0][2]['0']['yards'] . " yards" . "<br>";
		echo $this->team[0][3]['first_name'] . " has " . $this->team[0][3]['0']['receptions'] . " receptions and " . $this->team[0][3]['0']['yards'] . " yards" . "<br>";
		echo $this->team[0][4]['first_name'] . " has " . $this->team[0][4]['0']['receptions'] . " receptions and " . $this->team[0][4]['0']['yards'] . " yards" . "<br>";

		echo 'away total rec yrds: ' . $this->totRecYrds[1] . '<br>';
		echo $this->team[1][1]['first_name'] . " has " . $this->team[1][1]['0']['receptions'] . " receptions and " . $this->team[1][1]['0']['yards'] . " yards" . "<br>";
		echo $this->team[1][2]['first_name'] . " has " . $this->team[1][2]['0']['receptions'] . " receptions and " . $this->team[1][2]['0']['yards'] . " yards" . "<br>";
		echo $this->team[1][3]['first_name'] . " has " . $this->team[1][3]['0']['receptions'] . " receptions and " . $this->team[1][3]['0']['yards'] . " yards" . "<br>";
		echo $this->team[1][4]['first_name'] . " has " . $this->team[1][4]['0']['receptions'] . " receptions and " . $this->team[1][4]['0']['yards'] . " yards" . "<br>";


		// tackles
		echo "Home Total Team Tackles: " . $this->totTackles[0] . "<br>";
		echo $this->team[0][10]['first_name'] . " has " . $this->team[0][10]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][11]['first_name'] . " has " . $this->team[0][11]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][12]['first_name'] . " has " . $this->team[0][12]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][13]['first_name'] . " has " . $this->team[0][13]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][14]['first_name'] . " has " . $this->team[0][14]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][15]['first_name'] . " has " . $this->team[0][15]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][16]['first_name'] . " has " . $this->team[0][16]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][17]['first_name'] . " has " . $this->team[0][17]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][18]['first_name'] . " has " . $this->team[0][18]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][19]['first_name'] . " has " . $this->team[0][19]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[0][20]['first_name'] . " has " . $this->team[0][20]['0']['tackles'] . " tackles" . "<br>";

		echo "Away Total Team Tackles: " . $this->totTackles[1] . "<br>";
		echo $this->team[1][10]['first_name'] . " has " . $this->team[1][10]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][11]['first_name'] . " has " . $this->team[1][11]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][12]['first_name'] . " has " . $this->team[1][12]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][13]['first_name'] . " has " . $this->team[1][13]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][14]['first_name'] . " has " . $this->team[1][14]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][15]['first_name'] . " has " . $this->team[1][15]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][16]['first_name'] . " has " . $this->team[1][16]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][17]['first_name'] . " has " . $this->team[1][17]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][18]['first_name'] . " has " . $this->team[1][18]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][19]['first_name'] . " has " . $this->team[1][19]['0']['tackles'] . " tackles" . "<br>";
		echo $this->team[1][20]['first_name'] . " has " . $this->team[1][20]['0']['tackles'] . " tackles" . "<br>";


		// interceptions
		echo "home interceptions: " . $this->intercept[0] . '<br>';
		echo $this->team[0][13]['first_name'] . " has " . $this->team[0][13]['0']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[0][14]['first_name'] . " has " . $this->team[0][14]['0']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[0][15]['first_name'] . " has " . $this->team[0][15]['0']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[0][16]['first_name'] . " has " . $this->team[0][16]['0']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[0][17]['first_name'] . " has " . $this->team[0][17]['0']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[0][18]['first_name'] . " has " . $this->team[0][18]['0']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[0][19]['first_name'] . " has " . $this->team[0][19]['0']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[0][20]['first_name'] . " has " . $this->team[0][20]['0']['interceptions'] . " interceptions" . "<br>";

		echo "away interceptions: " . $this->intercept[1] . '<br>';
		echo $this->team[1][13]['first_name'] . " has " . $this->team[1][13]['0']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[1][14]['first_name'] . " has " . $this->team[1][14]['0']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[1][15]['first_name'] . " has " . $this->team[1][15]['0']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[1][16]['first_name'] . " has " . $this->team[1][16]['0']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[1][17]['first_name'] . " has " . $this->team[1][17]['0']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[1][18]['first_name'] . " has " . $this->team[1][18]['0']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[1][19]['first_name'] . " has " . $this->team[1][19]['0']['interceptions'] . " interceptions" . "<br>";
		echo $this->team[1][20]['first_name'] . " has " . $this->team[1][20]['0']['interceptions'] . " interceptions" . "<br>";
		

		// sacks	
		echo "home sacks: " . $this->sack[0] . '<br>';		
		echo $this->team[0][10]['first_name'] . " has " . $this->team[0][10]['0']['sacks'] . "<br>";
		echo $this->team[0][11]['first_name'] . " has " . $this->team[0][11]['0']['sacks'] . "<br>";
		echo $this->team[0][12]['first_name'] . " has " . $this->team[0][12]['0']['sacks'] . "<br>";
		echo $this->team[0][13]['first_name'] . " has " . $this->team[0][13]['0']['sacks'] . "<br>";
		echo $this->team[0][14]['first_name'] . " has " . $this->team[0][14]['0']['sacks'] . "<br>";
		echo $this->team[0][15]['first_name'] . " has " . $this->team[0][15]['0']['sacks'] . "<br>";
		echo $this->team[0][16]['first_name'] . " has " . $this->team[0][16]['0']['sacks'] . "<br>";
		echo $this->team[0][17]['first_name'] . " has " . $this->team[0][17]['0']['sacks'] . "<br>";

		echo "away sacks: " . $this->sack[1] . '<br>';		
		echo $this->team[1][10]['first_name'] . " has " . $this->team[1][10]['0']['sacks'] . "<br>";
		echo $this->team[1][11]['first_name'] . " has " . $this->team[1][11]['0']['sacks'] . "<br>";
		echo $this->team[1][12]['first_name'] . " has " . $this->team[1][12]['0']['sacks'] . "<br>";
		echo $this->team[1][13]['first_name'] . " has " . $this->team[1][13]['0']['sacks'] . "<br>";
		echo $this->team[1][14]['first_name'] . " has " . $this->team[1][14]['0']['sacks'] . "<br>";
		echo $this->team[1][15]['first_name'] . " has " . $this->team[1][15]['0']['sacks'] . "<br>";
		echo $this->team[1][16]['first_name'] . " has " . $this->team[1][16]['0']['sacks'] . "<br>";
		echo $this->team[1][17]['first_name'] . " has " . $this->team[1][17]['0']['sacks'] . "<br>";
		echo $this->team[1][18]['first_name'] . " has " . $this->team[1][18]['0']['sacks'] . "<br>";
		echo $this->team[1][19]['first_name'] . " has " . $this->team[1][19]['0']['sacks'] . "<br>";
		echo $this->team[1][20]['first_name'] . " has " . $this->team[1][20]['0']['sacks'] . "<br>";*/


		// insert into PlayerStatsGame db (loop through each player, and insert their stats to db)
		for ($t=0; $t < 2; $t++) { 
			foreach ($this->team[$t] as $key1 => $value) {
				// get team player id
				$this->playerId = $value['player_id'];
				$sql = "SELECT team_player_id FROM TeamPlayers WHERE player_id = $this->playerId";
				$result = $db->query($sql);
				$result = $result->fetch(PDO::FETCH_ASSOC);
				$tmPlrId = $result['team_player_id'];
				//echo 'team player id: ' . $tmPlrId . '<br>';
				//echo '<pre>';
				//var_dump($result);
				foreach ($value as $playerAttrs => $statVals) {	
					$stKeys = "".implode(", ", array_keys($statVals))."";
					$stVals = "'".implode("', '", array_values($statVals))."'";	

					$comp = $value[0]['completions'];
					$yrd = $value[0]['yards'];
					$td1 = $value[0]['td'];
					$int1 = $value[0]['interceptions'];
					$fum = $value[0]['fumbles'];
					$sck = $value[0]['sacks'];
					$rec1 = $value[0]['receptions'];
					$tck = $value[0]['tackles'];

					// if stat exists, push to db for each player
					if($stKeys !== ''){
						$this->playerId;
						$sql="INSERT INTO PlayerStatsGame (game_id, team_player_id, $stKeys) VALUES ('$gameId', '$tmPlrId', $stVals)";
						//print $sql . "<br>";
						$db->exec($sql);
						//echo "new record success" . '<br>';
						//echo 'statVals: ' . $statVals . ' player attrs: ' . $playerAttrs . ' value: ' . $value . '<br>';

						// update PlayerStats

						$stmt = $db->prepare("SELECT * FROM PlayerStats WHERE team_player_id = '$tmPlrId'");
						$stmt->execute();
						$record = $stmt->fetch();
						//echo 'record: ' . $record . '<br>';

						if($record == false){
							$sql = "INSERT INTO PlayerStats (team_player_id, $stKeys) VALUES ('$tmPlrId', $stVals)";
							//print $sql . "<br>";
							$db->exec($sql);
						}else { 
							$sql = "UPDATE PlayerStats SET ".
							"completions = completions + :comp,".
							"td = td + :tds,".
							"interceptions = interceptions + :int,".
							"fumbles = fumbles + :fum,".
							"yards = yards + :yrds,".
							"tackles = tackles + :tckls,".
							"sacks = sacks + :sacks,".
							"receptions = receptions + :rec ".
							"WHERE team_player_id = :Id";

							$result = $db->prepare($sql);
							$result->bindParam(":comp", $comp, PDO::PARAM_INT);
							$result->bindParam(":tds", $td1, PDO::PARAM_INT);
							$result->bindParam(":int", $int1, PDO::PARAM_INT);
							$result->bindParam(":fum", $fum, PDO::PARAM_INT);
							$result->bindParam(":yrds", $yrd, PDO::PARAM_INT);
							$result->bindParam(":tckls", $tck, PDO::PARAM_INT);
							$result->bindParam(":sacks", $sck, PDO::PARAM_INT);
							$result->bindParam(":rec", $rec1, PDO::PARAM_INT);
							$result->bindParam(":Id", $tmPlrId, PDO::PARAM_INT);
							//print $sql . "<br>";
							$result->execute();

						}

					}
				}
			}
		}

	}	// end getPlayerStats



	public function getTeamStats($gameId, $homeTeam, $awayTeam){
		require(ROOT_PATH . "config.php");

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


		/*echo "Home Team final score: " . $this->score[0] . "<br>";
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
		echo "Away Total Plays: " . $awayTotPlays . "<br>";*/

		// push to db (add home and away team id)
		$sql = "INSERT INTO GameStats (game_id, home_team, away_team, home_score, away_score, home_yards, away_yards, home_turnovers, away_turnovers, home_total_plays, away_total_plays, home_rushing_yards, away_rushing_yards, home_passing_yards, away_passing_yards, home_tds, away_tds, home_sacks, away_sacks) VALUES ($gameId, '$this->homeTeam', '$this->awayTeam', '$homeScore', '$awayScore', '$homeTotYrds', '$awayTotYrds', '$homeTurnovers', '$awayTurnovers', '$homeTotPlays', '$awayTotPlays', '$homeRushYrds', '$awayRushYrds', '$homePassYrds', '$awayPassYrds', '$homeTd', '$awayTd', '$homeSack', '$awaySack')";
		$db->exec($sql);

		// push to TeamStats
		if($homeScore > $awayScore){

			// push to team stats for home
			$stmt = $db->prepare("SELECT * FROM TeamStats WHERE team_id = '$this->homeTeam'");
			$stmt->execute();
			$record = $stmt->fetch();
			
			if($record == false){
				$sql = "INSERT INTO TeamStats (team_id, games_won, games_lost, season_id) VALUES ('$this->homeTeam', 1, 0, '$this->seasonId')";
				$db->exec($sql);
			} else {
				$sql = "UPDATE TeamStats SET games_won = games_won + 1 WHERE team_id = $this->homeTeam";

			}

			// push to team stats for away
			$stmt = $db->prepare("SELECT * FROM TeamStats WHERE team_id = '$this->awayTeam'");
			$stmt->execute();
			$record = $stmt->fetch();
			
			if($record == false){
				$sql = "INSERT INTO TeamStats (team_id, games_won, games_lost, season_id) VALUES ('$this->awayTeam', 0, 1, $this->seasonId)";
				//print $sql . '<br>';
				$db->exec($sql);
			} else {
				$sql = "UPDATE TeamStats SET games_lost = games_lost + 1 WHERE team_id = $this->awayTeam";
				//print $sql . '<br>';
				$db->exec($sql);
			}

		} else {

			// push to team stats for home
			$stmt = $db->prepare("SELECT * FROM TeamStats WHERE team_id = '$this->homeTeam'");
			$stmt->execute();
			$record = $stmt->fetch();
			
			if($record == false){
				$sql = "INSERT INTO TeamStats (team_id, games_won, games_lost, season_id) VALUES ('$this->homeTeam', 0, 1, $this->seasonId)";
				//print $sql . '<br>';
				$db->exec($sql);
			} else {
				$sql = "UPDATE TeamStats SET games_lost = games_lost + 1 WHERE team_id = $this->homeTeam";
				//print $sql . '<br>';
				$db->exec($sql);
			}

			// push to team stats for away
			$stmt = $db->prepare("SELECT * FROM TeamStats WHERE team_id = '$this->awayTeam'");
			$stmt->execute();
			$record = $stmt->fetch();
			
			if($record == false){
				$sql = "INSERT INTO TeamStats (team_id, games_won, games_lost, season_id) VALUES ('$this->awayTeam', 1, 0, $this->seasonId)";
				//print $sql . '<br>';
				$db->exec($sql);
			} else {
				$sql = "UPDATE TeamStats SET games_won = games_won + 1 WHERE team_id = $this->awayTeam";
				//print $sql . '<br>';
				$db->exec($sql);
			}

		}

		

	} 	// end getTeamStats



	public function sims(){
		for($x = 0; $x <= 8; $x++){
		$this->simPlayerStats();
		}

	}



}	// end class

?>



