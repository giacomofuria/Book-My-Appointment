<?php
	session_start();
	include "./util/sessionUtil.php";
	include_once "./util/Appointments.php";
	require_once "./util/BMADbManager.php";
	if(!isLogged()){
		header('Location: ./../index.php');
		exit;
	}
	$appuntamenti = new Appointments($_SESSION['userId']);
	if(isset($_GET['delAppointment'])){
		$id = $_GET['delAppointment'];
		$esitoCancellazione = $appuntamenti->deleteAppointment($id);
	}
	$listaAppuntamentiPrenotati = $appuntamenti->getBookedAppointments(0,false,"DESC");
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<title>Tuoi appuntamenti - Book My Appointment</title>
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
						<h3>Le tue prenotazioni</h3>
						<p><a href="./clientsAppointments.php">oppure visualizza gli appuntamenti dei tuoi clienti</a></p>
					</div>
					<?php
						if(!$listaAppuntamentiPrenotati){
							echo "<div class='appointment-container'><p>Non hai prenotazioni</div></p>";
						}else{
							$appuntamenti->stampaAppuntamenti("to","myAppointments.php");
						}
					?>
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
		<?php
			include "./layout/footer.php";
		?>
	</div>
	<script>
		// evidenzio il pulsante della pagina
		var btn = document.getElementById("home_button");
		btn.style.backgroundColor="#91DFAA";
		btn.style.color="#383838";
	</script>
</body>
</html>