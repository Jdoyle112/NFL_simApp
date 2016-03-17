<html>
<head>
	<title><?php echo $pageTitle; ?></title>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>public/css/style.css">
</head>
<body>
	<div class="header">
		<div class="wrapper">
			<ul class="nav">
				<li class="home <?php if ($section == "home") { echo "on"; } ?>"><a href="<?php echo BASE_URL; ?>index.php">Home</a></li>
				<li class="hiw <?php if ($section == "hiw") { echo "on"; } ?>"><a href="<?php echo BASE_URL; ?>How it Works/">How it Works</a></li>
				<li class="leagues <?php if ($section == "leagues") { echo "on"; } ?>"><a href="<?php echo BASE_URL; ?>resources/views/createLeague.php/">Leagues</a></li>
				<li class="account <?php if ($section == "account") { echo "on"; } ?>"><a href="<?php if(isset($username)){ echo "#"; } else{ echo BASE_URL . "resources/views/login.php"; } ?>"><?php if(isset($username)){echo $username;} else{ echo "Login"; } ?></a><a href="<?php if(isset($username)){echo BASE_URL . "resources/views/logout.php?logout";}else {echo BASE_URL . "resources/views/register.php";}  ?>"><?php if(isset($username)){ echo "Logout";}else{ echo 'Register';} ?></a></li>
			</ul>
		</div>
	</div>