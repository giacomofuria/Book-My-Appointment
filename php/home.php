<?php
	session_start();
	include "./util/sessionUtil.php";
	require_once "./util/BMADbManager.php";
	include "./util/Appointments.php";
	if(!isLogged()){
		header('Location: ./../index.php');
		exit;
	}
	$appuntamenti = new Appointments($_SESSION['userId']);
	if(isset($_GET['delAppointment'])){
		$id = $_GET['delAppointment'];
		$esitoCancellazione = $appuntamenti->deleteAppointment($id);
	}
	function stampaAppuntamenti($appuntamenti){
		foreach($appuntamenti as $appuntamento){
			echo "<div class='appointment-container'>";
			$src = "./../img/icon/set1/man.png";
			if($appuntamento['profileImage'] != null){
				$img = base64_encode($appuntamento['profileImage']);
				$src = "data:image/jpeg;base64,$img";
			}
			$time = strtotime($appuntamento['dataOra']);
			$data = date('d-m-Y',$time);
			$ora = date('H:i',$time);
			$idAppuntamento = $appuntamento['idAppuntamento'];
			echo "<div class='appointment-element appointment-element-img'><a href='./profile.php?user=".$appuntamento['id']."'><img src=\"$src\" class='img-ricevente' alt='user image'></a></div>";
			echo "<div class='appointment-element'><p>".$data."</p><p>".$ora."</p></div>";
			echo "<div class='appointment-element appointment-element-info'><p><b><a href='./profile.php?user=".$appuntamento['id']."'>".$appuntamento['nome']." ".$appuntamento['cognome']."</a></b></p>";
			echo "<p>".$appuntamento['professione']."</p>";
			echo "<p><a href='mailto:".$appuntamento['email']."'><img src='./../img/icon/set1/envelope.png' class='icon-email' alt='Email'></a></p></div>";
			echo "<div class='appointment-element appointment-element-position'><p><b>Dove</b></p><p>".$appuntamento['indirizzo']."</p></div>";
			echo "<div class='appointment-element appointment-element-notes'><p><b>Note</b></p><p>".$appuntamento['note']."</p></div>";
			echo "<div class='appointment-element appointment-element-img'><button onclick=\"location.href='./home.php?delAppointment=$idAppuntamento'\"><img src='./../img/icon/set1/garbage.png' class='delete-icon' alt='Cancella'></button></div>";
			echo "<div style='clear:both;'></div>";
			//echo ." ".$appuntamento['emailRicevente']." ".$appuntamento['nomeRicevente']."<br>";
			echo "</div>";
		}
	}
?>
<!DOCTYPE html>
<html lang="it">
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
					<?php

						//$appuntamenti = getMyAppointments($_SESSION['userId'],3);
						
						$listaAppuntamentiPrenotati = $appuntamenti->getBookedAppointments(3,true,"ASC");
						if(!$listaAppuntamentiPrenotati){
							echo "<div class='appointment-container'><p>Non hai appuntamenti</p></div>";
						}else{
							stampaAppuntamenti($listaAppuntamentiPrenotati);
							echo "<div class='link-container'><p><a href='./myAppointments.php'>tutti i tuoi appuntamenti</a></p></div>";
						}
					?>
				</div>

				<div id="clients-appointments">
					<div class="appointment-header">
						<h3>I prossimi appuntamenti con i tuoi clienti</h3>
					</div>
					<?php
						//$appuntamenti = getMyClientAppointments($_SESSION['userId'], 3);
						$listaPrenotazioniRicevute = $appuntamenti->getReceivedAppointments(3,true,"ASC");
						if(!$listaPrenotazioniRicevute){
							echo "<div class='appointment-container'><p>Non hai appuntamenti</p></div>";
						}else{
							stampaAppuntamenti($listaPrenotazioniRicevute);
							echo "<div class='link-container'><p><a href='./clientsAppointments.php'>tutti gli appuntamenti dei clienti</a></p></div>";
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