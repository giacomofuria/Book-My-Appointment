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
	<div id="left-side">
		<div id="profile-img" class="side-menu-box">
			<!-- <img id="profile-img" src="./../img/man.png" alt="Profile image">-->
		</div>
		<div class="side-menu-box">
			<button class="menu_buttons" onclick="window.location.href='./home.php'">Home</button>
			<button class="menu_buttons" onclick="window.location.href='./profile.php'">Profile</button>
			<button class="menu_buttons" onclick="window.location.href='./settings.php'">Settings</button>
			<button class="menu_buttons" onclick="window.location.href='./logout.php'">Sign out</button>
		</div>
	</div>
	<div id="right-side">
		<div id="top-bar-container">
			<div class="top-bar-box">
				<?php echo '<u>Welcome:</u> '.$_SESSION['email']; ?>
			</div>
			<div id="search-bar-container" class="top-bar-box">
				<input id="search-bar" placeholder="Search">
				<button id="search-button"><img src="./../img/icon/set1/search.png" style="width:40%; height:35%;"></button>
			</div>
			<div class="top-bar-box">
				altri comandi
			</div>
			
		</div>
		<div id="first-workspace-container" class="workspace-header">
			Booked appointments for you
		</div>
		<div class="workspace-container">
			<br><br><br><br>Appuntamento 1<br><br><br><br>     
		</div>
		<div class="workspace-container">
			<br><br><br><br>Appuntamento 2<br><br><br><br>     
		</div>
		<div class="workspace-header">
			Booked appointments by your clients
		</div>
		<div class="workspace-container">
			
					 <br><br><br><br><br><br><br><br><br><br>b
			         <br><br><br><br><br><br><br><br><br><br>c
			         <br><br><br><br><br><br><br><br><br><br>d
			         <br><br><br><br><br><br><br><br><br><br>e
			         <br><br><br><br><br><br><br><br><br><br>f
		</div>
	</div>
</body>
</html>