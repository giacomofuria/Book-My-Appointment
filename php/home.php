<?php
	session_start();
	include "./util/sessionUtil.php";
	if(!isLogged()){
		header('Location: ./../index.php');
		exit;
	}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Home Page - Book My Appointment</title>
	<meta name = "author" content = "Giacomo Furia">
	<link rel="stylesheet" href="./../css/home.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/menu.css" type="text/css" media="screen">
</head>
<body>
	<div class="container">
		<div id="left-side">
			<div class="menu">
				
			</div>
		</div>
		<div id="right-side">
			<?php echo '<u>Benvenuto:</u> '.$_SESSION['email'].', <u>userId:</u> '.$_SESSION['userId'];  ?>
			<span><p><a href="./logout.php">Sign out</a></p></span>
			
		</div>
	</div>
</body>
</html>