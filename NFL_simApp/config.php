<?php

// cennect to db

$servername = "localhost";
$username = "root";
$password = "root";

try {
    $db = new PDO("mysql:host=$servername;dbname=nfl_sim", $username, $password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; 
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }

?>