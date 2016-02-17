<?php

//	TO-DO
	// store fake teams in an array
	// assign players to teams randomly (within position rules)
// add divisions

		global $teamNames;

		// 32 teams
		$teamNames = array(

			array("name" => "New York Nightmare", "city" => "New York", "abrv" => "NYN", "division" => 1),
			array("name" => "Mexico City Aztecs", "city" => "Mexico City", "abrv" => "MEC", "division" => 1),
			array("name" => "New England Volunteers", "city" => "New England", "abrv" => "NE", "division" => 1),
			array("name" => "Reno Reapers", "city" => "Reno", "abrv" => "REN", "division" => 1),

			array("name" => "San Antonio Outlaws", "city" => "San Antonio", "abrv" => "SAN", "division" => 2),
			array("name" => "Seattle Surge", "city" => "Seattle", "abrv" => "SEA", "division" => 2),
			array("name" => "Erie Express", "city" => "Erie", "abrv" => "EE", "division" => 2),
			array("name" => "LA Riot", "city" => "Los Angeles", "abrv" => "LAR", "division" => 2),

			array("name" => "Kansas City Regiment", "city" => "Kansas City", "abrv" => "KC", "division" => 3),
			array("name" => "Baltimore Steamers", "city" => "Baltimore", "abrv" => "BAL", "division" => 3),
			array("name" => "Houston Venom", "city" => "Houston", "abrv" => "HOU", "division" => 3),
			array("name" => "Philadelphia Freedom", "city" => "Philadelphia", "abrv" => "PHI", "division" => 3),

			array("name" => "Golden State Gladiators", "city" => "Golden State", "abrv" => "GS", "division" => 4),
			array("name" => "New Jersey Rebels", "city" => "New Jersey", "abrv" => "NJR", "division" => 4),
			array("name" => "Alabama Rum Runners", "city" => "Alabama", "abrv" => "ALB", "division" => 4),
			array("name" => "Washington Sentinels", "city" => "Washington", "abrv" => "WSH", "division" => 4),

			array("name" => "Portland Skyscrappers", "city" => "Portland", "abrv" => "POR", "division" => 5),
			array("name" => "Louisiana Coperheads", "city" => "Louisiana", "abrv" => "LAC", "division" => 5),
			array("name" => "San Diego Crusaders", "city" => "San Diego", "abrv" => "SD", "division" => 5),
			array("name" => "Cincinnati Boilers", "city" => "Cincinnati", "abrv" => "CIN", "division" => 5),

			array("name" => "Maryland Redhawks", "city" => "Maryland", "abrv" => "MAR", "division" => 6),
			array("name" => "Oklahoma Comanches", "city" => "Oklahoma", "abrv" => "OKC", "division" => 6),
			array("name" => "Michigan Banshee", "city" => "Michigan", "abrv" => "MI", "division" => 6),
			array("name" => "Milwaukee Hounds", "city" => "Milwaukee", "abrv" => "MIL", "division" => 6),

			array("name" => "Denver Rockets", "city" => "Denver", "abrv" => "DEN", "division" => 7),
			array("name" => "Sin City Assassins", "city" => "Sin City", "abrv" => "SCA", "division" => 7),
			array("name" => "Oakland Marauders", "city" => "Oakland", "abrv" => "OAK", "division" => 7),
			array("name" => "Atlantic City Aces", "city" => "Atlantic City", "abrv" => "ATC", "division" => 7),

			array("name" => "San Francisco Rogues", "city" => "San Francisco", "abrv" => "SF", "division" => 8),
			array("name" => "Miami Sharks", "city" => "Miami", "abrv" => "MIA", "division" => 8),
			array("name" => "Charlestown Chiefs", "city" => "Charlestown", "abrv" => "CHR", "division" => 8),
			array("name" => "Boston Brawlers", "city" => "Boston", "abrv" => "BOS", "division" => 8)

		);


?>