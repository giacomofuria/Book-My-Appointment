<?php
	session_start();
	include "./util/sessionUtil.php";
	if(!isLogged()){
		header('Location: ./../index.php');
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Home Page - Book My Appointment</title>
	<meta name = "author" content = "Giacomo Furia">
	<link rel="stylesheet" href="./../css/page.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/menu.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/home.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/calendar.css" type="text/css" media="screen">
</head>
<body>
	<div id="left-side">
		<?php
			include "./layout/menu.php";
		?>
	</div>
	<div id="right-side">
		<?php
			include "./layout/top_bar.php";
		?>
		<div id="workspace">
			<div id="appointments-viewer">
				<div id="my-appointments">
					<div class="appointment-header">
						<h3>I tuoi prossimi appuntamenti</h3>
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
						<h3>I prossimi appuntamenti con i tuoi clienti</h3>
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
				<?php 
					include './layout/calendar.php';
					$calendar = new Calendar();
					$calendar->show();
				?>
			</div>
			<div style="clear:both;"></div>
		</div> <!-- fine workspace -->

	</div>
	<script type="text/javascript">
		// evidenzio il pulsante della pagina
		var btn = document.getElementById("home_button");
		btn.style.backgroundColor="#91DFAA";
		btn.style.color="#383838";

	</script>
</body>
</html>