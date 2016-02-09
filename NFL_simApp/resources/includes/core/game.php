
<?php
/*
class Game {

	public $score;
	public $yards;
	public $field_position;
	public $yardsUntil1st = 10;
	public $offense_score;
	public $defense_score;
	public $position;
	public $down = 1;
	public $home_team;
	public $away_team;
	public $home_teamScore = 0;
	public $away_teamScore = 0;

	public $qTime = 15;  // Each # represents 1 min
	public $posTime = 0;
	public $play;
	public $playTime = 0;
	public $time = 60;

	// Play Outcomes
	public $fg;
	public $xp;
	public $turnover;


//	TO-DO

	// handle quarter and game time
	// convert to oop
	// add player ratings and stats
	// penalties
	// overtime
	// yards after TO
	// Choose random team to start, and flip after 2nd half
	// Create $gameStats assoc. array 
	// Create $gamePlayerStatsOffense assoc. array 
	// Create $gamePlayerStatsDefense assoc. array 

public function simRegulation(){

	public $team = array($team1, $team2);	// home team = 0
	public $quarter = 1;
	public $o = 0; 
	public $d = 1;

	kickoff(); 		// opening kickoff

	for($quarter = 1; $quarter > 4; $quarter++){
		$qTime = 15;
		do {
			simPossession();
		} while ($qTime > 0);
	}

	if($home_teamScore == $away_teamScore && $time == 0){
		overtime();
	}

}	  // end simRegulation


public function simPossession (){

	// flip possession	

	do {
		simPlay();
		$posTime += $playTime;	// time elapsed per possession
		$qTime -= $posTime;			// subtract poss. time from quarter time
	} while ($down != 4 && $turnover == false && $score == false);

	if($down == 4){ 	// 4th down procedure
		fourth_down();
		$posTime += $playTime;
		$qTime -= $posTime;	
	} else if($score == true){ 		// check if scored a TD
		// kickoff
	} else if ($turnover == true){		// may not reset to next possession?
		$turnover = false;
	}

} 	// End simPossesion


public function simPlay (){

	// need to write function to check if yards gained > yards until 1st

	$playTime = rand(.10, .47); //avg nfl play = 27 sec

	turnover(); 	// checks if turnover occurs (need to add yards for rushes)

	private $y = rand(1, 100);
	if($yardsUntil1st > 4 && $down >= 3){	// pass on 3rd and more than 4 or 4th and more than 4
		passingPlay();
	} else if($y < 59){		// passing play avg. 58% of the time
		passingPlay();
	} else {		// running play 41% 
		runningPlay();
	}

	$down++; 	// increment the down
	$field_position += $yards;		// determine field position
	$yardsUntilTD -= $field_position; 	// determine yards until TD
	$yardsUntil1st -= $yards;		// determine yards until 1st down

	if($yardsUntil1st <= 0){		// reset down and yardsUntil1st when 1st down is reached
		$yardsUntil1st = 10;
		$down = 1;
	}

} 	// End simPlay


public function runningPlay(){

	// need to write fumble % 
	// compute if run block o line > rush d of defense total and factor RB ratings (make D line ratings more important)
	
	$play = "run";

	private $z = rand(1, 100);
	if($z < 11){		// rush for a loss 10%
		public $rushLoss = rand(-4, -1); 	// can go for negative 1-4
		return $yards = $rushLoss;
	} else if ($z >= 11 && $z < 50){
		public $rushMin = rand(0, 3);	  // can go for 0-3 yards
		return $yards = $rushMin;
	} else if($z >= 50 && $z < 90){
		public $rushMed = rand(4, 8);	  // can go for 4-8 yards
		return $yards = $rushMed;
	} else if ($z >= 90 && $z < 98){
		public $rushHigh = rand(9, 19);	  // can go for 9-19 yards
		return $yards = $rushHigh;	
	} else {		// 3% chance 20+ yrds
		public $yardsUntilTD = 100 - $field_position;
		public $rushMax = rand(20, 100);
		return $yards = $rushMax;
	}
	
}		// end runningPlay

public function passingPlay(){

	// outcomes to consider... int, fum, sack, comp, incomp, qb rush

	$play = "pass";

	public $comp = rand(1, 100);
	public $qb_comp = // real qb comp %

	if($qb_comp >= $comp){		// complete pass
		$comp = true;
	} else {			// incomplete pass
		$comp = false;
	}

	if($comp == true){
		private $j = rand(1, 100);
		if($j < 6){								// 5% chance -3 - 2 yards
			public $passLoss = rand(-3, 2);
			return $yards = $passLoss;
		} else if($j >= 6 && $j < 50) {			// 45% chance 3-9 yards
			public $passMin = rand(3, 9);
			return $yards = $passMin;
		} else if($j >= 50 && $j < 85) {		// 35% chance 10 - 17 yards
			public $passMed = rand(10, 17);
			return $yards = $passMed;
		} else if($j >= 85 && $j < 98) {		// 13% chance 18 - 35 yards
			public $passHigh = rand(18, 35);
			return $yards = $passHigh;
		} else {								// 2% chance 40+ yards
			public $passMax = rand(36, 100);
			return $yards = $passMax;
		}

	} else {
		return $yards = 0;
	}

}	    // end passingPlay



public function fourth_down(){  //fourth down
	if($field_position > 63){
		fieldGoal();
	} else if ($yardsUntil1st <= 2 && $field_position > 50 && $offense_score < $defense_score){
		fourth_down_play();
	} else if (($offense_score + 7) < $defense_score && $quarter == 4){
		fourth_down_play();
	} else {
		punt();
	} 

}	// end 4th down

public function fourth_down_play(){
	$playTime = rand(.10, .47);
	if($yardsUntil1st <= 2){
		runningPlay();
	} else {
		passingPlay();
	}
}	// end 4th down play

public function extraPoint (){
	if ($time < 4 && $offense_score < $defense_score){	// if losing and under 4 min left, go for 2
		$xp = rand(1, 2);	// 50% chance of converting
		if($xp == 1){
			$xp = 2;
		} else {
			$xp = 0;
		}
	} else {
		$xp = rand(1, 100);		// attmpt. xtra point
		if($xp < 94){		// 93% chance of making it
			$xp = 1;
		}  else {
			$xp = 0;
		}
	}
	return $xp;
} 	// End XP

public function fieldGoal (){
	$playTime = rand(.10, .47);
	private $x = rand(1, 100);
	if ($field_position > 95 && $x < 99){	// 1-20 yrds
		$fg = 3;
		return $fg;
	} else if ($field_position > 86 && $x < 97){	// 20-30 yrds
		$fg = 3;
		return $fg;
	} else if ($field_position > 77 && $x < 91){	 // 30-40 yrds
		$fg = 3;
		return $fg;
	} else if ($field_position >= 68 && $x < 81){  	// 40-50 yrds
		$fg = 3;
		return $fg;
	} else if ($field_position < 68 && $x < 60){	// 50+ yrds
		$fg = 3;
		return $fg;
	} else {  		// missed fg
		$fg = 0;
		changePoss();
		return $fg;
	}
} 	// End FG

public function touchDown (){
	if($team[$o] == 0){		// if home team has the ball
		extraPoint();
		$home_teamScore +=  6 + $xp;
	} else {  				// if away has ball
		extraPoint();
		$away_teamScore += 6 + $xp;
	}

	kickoff();
} 	// End TD



public function fumble(){

	$turnover = true;

	if(){

	}

	changePoss();

}		// end fumble

public function interception(){

	$turnover = true;

	changePoss();

}		// end interception

public function punt(){

	changePoss();
	
	// field position

}		// end punt

public function turnover(){		// calculate odds of a TO

	private $a = rand(1, 100);
	private $b = rand(1, 200);
	private $i = rand(1, 100);
	public $int_ratio = ;		// get QB int %

	if ($play == "run"){
		
		if($a == 1){		// 1% chance of fumble on rushing play
			fumble();
			// add fumbl tendency to influence frequency
		} 

	} else if($play = "pass"){
		
		if($b == 1){				
			fumble(/* Pass in Player (QB));		// .5% chance QB fumbles/ attmpt.
		} else if ($b > 1 && $b < 4){				
			fumble(/* Pass in Player (receiver));		// 1% chance receiver fumbles/ attmpt.
		} else if ($int_ratio < $i){
			interception(/* Pass in Player (QB));	
		}

	}

}		// end turnover



public function changePoss(){
	
	// change possession
	// correlate to team using $this->team[$this->o] to record offensive stats
	if($o == 0){
		$o = 1;
		$d = 0;
	} else {
		$o = 0;
		$d = 1;
	}

	// reset yards, downs, and calculate field position
	$yardsUntil1st = 10;
	$down = 1;
	$yardsUntilTD = 100 - $yardsUntilTD;

}  		// end changePoss

public function overtime(){

}

public function kickoff(){

	// change possession each kickoff
	changePoss();

	private $k = rand(1, 100);
	if($k == 1){						// 1% chance TD
		$kickoff_yards = 100;
		touchDown();
	} else if($k > 1 && $k < 20){		//20% chance 7-19 yards
		public $kickoff_yards = rand(7, 19);
	} else if($k >= 20 && $k < 70){		// 50% chance touchback
		$kickoff_yards = 20;
	} else if($k >= 70 & $k < 96){		// 25% chance 21-30 yards
		$kickoff_yards = rand(21, 30);
	} else {							// 4% chance 31-60 yards
		$kickoff_yards = rand(31, 60);
	}

	// return starting field position
	$field_position = 100 - $kickoff_yards;
	return $field_position;	

}		// end kickoff

public function recordPlay(){
	// reference bball sim
}



} 	// End Class
*/
?>