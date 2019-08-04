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
			<button id="home_button" class="menu_buttons" onclick="window.location.href='./home.php'">Home</button>
			<button id="profile_button" class="menu_buttons" onclick="window.location.href='./profile.php'">Profile</button>
			<button id="settings_button" class="menu_buttons" onclick="window.location.href='./settings.php'">Settings</button>
			<button id="signout_button" class="menu_buttons" onclick="window.location.href='./logout.php'">Sign out</button>
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
		<div id="workspace">
			<div id="appointments-viewer">
				<div id="my-appointments">
					<div class="appointment-header">
						Booked appointments for you
					</div>
					<div class="appointment-container">
						Appuntamento 1 
					</div>
					<div class="appointment-container">
						Appuntamento 2   
					</div>
				</div>

				<div id="clients-appointments">
					<div class="appointment-header">
						Booked appointments by your clients
					</div>
					<div class="appointment-container">
						Appuntamento 1
					</div>
					<div class="appointment-container">
						Appuntamento 2
					</div>
					<div class="appointment-container">
						Appuntamento 3
					</div>
					<div class="appointment-container">
						Appuntamento 4
					</div>
					<div class="appointment-container">
						Appuntamento 5
					</div>
				</div>
			</div>
			<div id="calendar-container">
				Calendario<br><br>
			</div>
			<div style="clear:both;"></div>
		</div> <!-- fine workspace -->

	</div>
	<script type="text/javascript">
		// evidenzio il pulsante della pagina
		var btn = document.getElementById("home_button");
		btn.style.backgroundColor="#91DFAA";
		btn.style.color="#000000";
	</script>
</body>
</html>