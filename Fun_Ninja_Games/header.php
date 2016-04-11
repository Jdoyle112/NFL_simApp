<?php 	

	session_start();
	//require("config.php");
	$username = $_SESSION['user'];
	$userId = $_SESSION['userId'];  

?>
	<div class="container">
		<div class="header row">
			<div class="col-md-2">
				<?php 
					if($logo != ""){
						/*$sql="SELECT image FROM images WHERE user_id = $userId AND image = '$logo' AND type = 'logo'";
						$results=$db->query($sql);
						$results=$results->fetch(PDO::FETCH_ASSOC);*/
						echo '<img src="' . '../' . $logo . '">';
					} else if($selLogo != ""){
						$sql="SELECT image FROM images WHERE user_id = $userId AND image = '$selLogo' AND type = 'logo'";
						$results=$db->query($sql);
						$results=$results->fetch(PDO::FETCH_ASSOC);

						echo '<img src="' . '../' . $selLogo . '">';						
					}
				?>
			</div>
			<div class="col-md-6 col-md-offset-1">
				<h1 class="title"><?php if($title != ""){echo $title;} ?></h1>
			</div>
			<div class="col-md-2">
				
			</div>
			<div class="col-md-1">	
				<div class="dropdown">
					<button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-cog fa-2x"></i></button>
					
					<div class="dropdown-menu dropdown-menu-right">
						<div class="accounts"><ul><li><a href="<?php if(isset($username)){ echo "#"; } else{ echo BASE_URL . "login.php?page=" . $page; } ?>"><?php if(isset($username)){echo $username;} else{ echo "Login"; } ?></a></li><li><a href="<?php if(isset($username)){echo BASE_URL . "logout.php?logout&page=" . $page;}else {echo BASE_URL . "register.php?page=" . $page;}  ?>"><?php if(isset($username)){ echo "Logout";}else{ echo 'Register';} ?></a></li></ul></div>
						<form class="form settings" method="post" action="<?php echo '../upload.php'; ?>" enctype="multipart/form-data">
							<label for="title">Title:</label>
							<br>
							<input type-"text" name="title" id="title">
							<br><br>
							<label for="bg-color">Background Color:</label>
							<br>
							<input type="color" name="bg-color" id="bg-color" value="<?php echo $color; ?>">
							<br><br>	
							<label for="logo">Upload Logo:</label>
							<input type="file" name="logo" id="logo">
							<br>
							<p>- OR -</p>
							<br>
							<?php
								$sql = "SELECT image FROM images WHERE user_id = $userId AND type = 'logo'";
								$results = $db->query($sql);
								$results = $results->fetchAll(PDO::FETCH_ASSOC);

								foreach ($results as $value) {
									echo '<label class="label_thumbs">';
									echo '<input type="radio" value="'.$value['image'].'" name="selecLogo" id="selecLogo">';
									echo '<span class="close">&times;</span>';
									echo '<img class="thumbnail" src="'.'../' . $value['image'].'">';
									echo '</label>';
								}
							?>
							<br>
							<label for="bg">Upload Background:</label>
							<input type="file" name="bg" id="bg" value="<?php if($background != ""){echo $background;} ?>">
							<br>
							<p>- OR -</p>
							<br>
							<?php
								$sql="SELECT image FROM images WHERE user_id = $userId AND type='background'";
								$results = $db->query($sql);
								$results = $results->fetchAll(PDO::FETCH_ASSOC);

								foreach ($results as $value) {
									echo '<label class="label_thumbs">';
									echo '<input type="radio" value="'.$value['image'].'" name="selecBg" id="selecBg">';
									echo '<span class="close">&times;</span>';
									echo '<img class="thumbnail" src="'.'../' . $value['image'].'">';
									echo '</label>';
								}
							?>
							<br>
							<?php if(isset($message) AND $message != ""){echo '<p style="color: red">' . $message . '</p><br>';} ?>
							<span><input type="submit" name="submit" value="Submit"><input type="reset" name="reset" value="Reset" id="reset"></span>
							<input type="hidden" name="page" value="<?php echo $page; ?>">
						</form>
						<form class="form" method="post" action="<?php echo '../upload.php?reset=yes'; ?>">
							<input type="hidden" name="page" value="<?php echo $page; ?>">
							<input type="submit" name="reset" value="Erase">
						</form>
					</div>
				</div>	
			</div>
		</div>