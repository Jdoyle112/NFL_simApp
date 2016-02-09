<html>
<head>
	<title><?php echo $pageTitle; ?></title>
	<link rel="stylesheet" type="text/css" href="./public/css/style.css">
</head>
<body>
	<div class="header">
		<div class="wrapper">
			<ul class="nav">
				<li class="home <?php if ($section == "home") { echo "on"; } ?>"><a href="<?php echo BASE_URL; ?>home/">Home</a></li>
				<li class="hiw <?php if ($section == "hiw") { echo "on"; } ?>"><a href="<?php echo BASE_URL; ?>How it Works/">How it Works</a></li>
				<li class="leagues <?php if ($section == "leagues") { echo "on"; } ?>"><a href="<?php echo BASE_URL; ?>Leagues/">Leagues</a></li>
				<li class="account <?php if ($section == "account") { echo "on"; } ?>"><a href="<?php echo BASE_URL; ?>Account/">My Account</a></li>
			</ul>
		</div>
	</div>