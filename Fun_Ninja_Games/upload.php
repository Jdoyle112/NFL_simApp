<?php

// TO_DO
	// add ability to delete files drom DB

	session_start();
	require("config.php");
	$username = $_SESSION['user'];
	$userId = $_SESSION['userId']; 

	// get values
	$message = "";
	$reset = $_POST['reset'];
	$hidden = $_POST['hidden'];
	$page = $_POST['page']; 

	// title set 
	if(isset($_POST['title']) AND !empty($_POST['title'])){
		$_SESSION['title'] = $_POST['title'];
	} 

	// logo select
	if(isset($_POST['selecLogo']) AND !empty($_POST['selecLogo'])){
		$_SESSION['selLogo'] = $_POST['selecLogo'];
		$_SESSION['logo'] = "";
	}	

	// background select
	if(isset($_POST['selecBg']) AND !empty($_POST['selecBg'])){
		$_SESSION['selBg'] = $_POST['selecBg'];
		$_SESSION['background'] = "";
	}	

	// delete 
	foreach ($hidden as $value) {
		$sql = "DELETE FROM images WHERE user_id = $userId AND image = '$value'";
		$db->exec($sql); 

		unlink($value);
	}

	// logo file upload
	$target_dir = "logos/";
	$target_file = $target_dir . basename($_FILES["logo"]["name"]);
	$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	$uploadGo = 1;

	if($target_file != "logos/"){
		// check if img file is real
		if(isset($_POST["submit"])){
			$check =  getimagesize($_FILES["logo"]["tmp_name"]);
			if($check !== false) {
	        	$uploadGo = 1;
	   		 } else {
	        	$message = "File is not an image.";
	        	$uploadGo = 0;
	    	}
		}

		// check if file exists
		if (file_exists($target_file)) {
		    $message = "Sorry, file already exists.";
		    $uploadGo = 0;
		}

		// Check file size
		if ($_FILES["logo"]["size"] > 500000) {
		    $message = "Sorry, your file is too large.";
		    $uploadGo = 0;
		}	

		// Check if $uploadOk is set to 0 by an error
		if ($uploadGo == 0) {
		    //$message = "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
		    if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
		        //echo "The file ". basename( $_FILES["logo"]["name"]). " has been uploaded.";
		        $logo = $target_file;
		        $_SESSION['logo'] = $logo;
		        // insert into DB
		        $sql = "INSERT INTO images (user_id, image, type) VALUES ($userId, '$logo', 'logo')";
		        $db->exec($sql);    
		    } else {
		        $message = "Sorry, there was an error uploading your file.";
		    }
		}
	}	

	
	// file upload
	$target_dir = "backgrounds/";
	$target_file = $target_dir . basename($_FILES["bg"]["name"]);
	$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	$uploadGo = 1;

	if($target_file != "backgrounds/"){
		// check if img file is real
		if(isset($_POST["submit"])){
			$check =  getimagesize($_FILES["bg"]["tmp_name"]);
			if($check !== false) {
	        	//echo "File is an image - " . $check["mime"] . ".";
	        	$uploadGo = 1;
	   		 } else {
	        	$message = "File is not an image.";
	        	$uploadGo = 0;
	    	}
		}

		// check if file exists
		if (file_exists($target_file)) {
		    $message = "Sorry, file already exists.";
		    $uploadGo = 0;
		}

		// Check file size
		if ($_FILES["bg"]["size"] > 500000) {
		    $message = "Sorry, your file is too large.";
		    $uploadGo = 0;
		}	

		// Check if $uploadOk is set to 0 by an error
		if ($uploadGo == 0) {
		    //$message = "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
		    if (move_uploaded_file($_FILES["bg"]["tmp_name"], $target_file)) {
		       // echo "The file ". basename( $_FILES["bg"]["name"]). " has been uploaded.";
		       $background = $target_file;
		       $_SESSION['background'] = $background;
		        // insert into DB
		        $sql = "INSERT INTO images (user_id, image, type) VALUES ($userId, '$background', 'background')";
		        $db->exec($sql);	       
		    } else {
		        $message = "Sorry, there was an error uploading your file.";
		    }
		}
	}	

		// color set
	if(isset($_POST['bg-color']) AND !empty($_POST['bg-color'])){
		$_SESSION['color'] = substr($_POST['bg-color'], 1);
		if(!isset($_POST['selecBg']) OR empty($_POST['selecBg'])){
			$_SESSION['selBg'] = "";

		}
		if(!isset($background) OR $background == ""){
			$_SESSION['background'] = "";
		}
	}


	header('Location: ' . $page . 'index.php?reset=' . $reset . '&msg=' . $message);

?>






