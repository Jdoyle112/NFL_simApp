<?php

// cennect to db

	$servername = "localhost";
	$username = "root";
	$password = "root";

	try {
	    $db = new PDO("mysql:host=$servername;dbname=fun_ninja", $username, $password);
	    // set the PDO error mode to exception
	    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    //echo "Connected successfully"; 
	    }
	catch(PDOException $e)
	    {
	    echo "Connection failed: " . $e->getMessage();
	}
   	define("BASE_URL","http://localhost:8888/Fun_Ninja_Games/");
	define("ROOT_PATH",dirname( __FILE__ ) . DIRECTORY_SEPARATOR);
?>