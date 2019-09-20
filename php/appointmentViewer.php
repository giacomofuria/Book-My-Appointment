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

	$dataInizio=false;
	$dataFine=false;
	$listaAppuntamentiPrenotati=null;
	$listaPrenotazioniRicevute=null;
	if(isset($_GET['from']) && isset($_GET['to'])){
		$dataInizio = date('Y-m-d H:i:s',strtotime($_GET['from']));
		$dataFine = date('Y-m-d H:i:s',strtotime($_GET['to']));
		$listaAppuntamentiPrenotati = $appuntamenti->getBookedAppointments(0,false,"DESC",$dataInizio,$dataFine);
		$listaPrenotazioniRicevute = $appuntamenti->getReceivedAppointments(0,false,"DESC",$dataInizio,$dataFine);
	}
	
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<title>Appointment Viewer - Book My Appointment</title>
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
						<h3>Le tue prenotazioni 
							<?php
								if($dataInizio){
									echo " del giorno ".date('d-m-Y',strtotime($_GET['from']));;
								}
							?>
						</h3>
					</div>
					<?php
						if(!$listaAppuntamentiPrenotati){
							echo "<div class='appointment-container'><p>Non hai appuntamenti</p></div>";
						}else{
							//stampaAppuntamenti($listaAppuntamentiPrenotati);
							$appuntamenti->stampaAppuntamenti("to","appointmentViewer.php");		
						}
					?>
				</div>

				<div id="clients-appointments">
					<div class="appointment-header">
						<h3>Appuntamenti dei tuoi clienti
							<?php
								if($dataInizio){
									echo " del giorno ".date('d-m-Y',strtotime($_GET['from']));;
								}
							?>
						</h3>
					</div>
					<?php
						if(!$listaPrenotazioniRicevute){
							echo "<div class='appointment-container'><p>Non hai appuntamenti</p></div>";
						}else{
							$appuntamenti->stampaAppuntamenti("from","appointmentViewer.php");
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
</body>
</html>