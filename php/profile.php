<?php
	session_start();
	include "./util/sessionUtil.php";
	include "./layout/AppointmentTable.php";
	require_once "./util/BMADbManager.php";// includo la classe per la gestione del database
	if(!isLogged()){
		header('Location: ./../index.php');
		exit;
	}
	function getUserInfo($userId){
		global $bookMyAppointmentDb;
		$queryText = "SELECT * FROM USER WHERE userId='".$userId."';";

		$result = $bookMyAppointmentDb->performQuery($queryText);
		if(!$result){
			return false;
		}
		$numRow = mysqli_num_rows($result);
		$bookMyAppointmentDb->closeConnection();
		$userRow = $result->fetch_assoc();
		$bookMyAppointmentDb->closeConnection();
		return $userRow;
	}
	function loadConfig($userId){
		global $bookMyAppointmentDb;
		$queryText = "SELECT * FROM struttura_tabella_appuntamenti WHERE userId='".$userId."';";

		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		if($numRow == 0){
			return false;
		}
		$bookMyAppointmentDb->closeConnection();
		$userRow = $result->fetch_assoc();
		$bookMyAppointmentDb->closeConnection();
		return $userRow;
	}
	function parametriRicevuti(){
		if(!isset($_POST['appointment_receiver_user']) || !isset($_POST['appointment_applying_user']) || !isset($_POST['appointment_data']) || !isset($_POST['appointment_hour']) || !isset($_POST['appointment_duration'])){
			return false;
		}else{
			return true;
		}
	}
	function saveAppointment($receiver, $applier, $date, $hour, $duration, $notes){
		if($notes == null)
			$notes = "";

		global $bookMyAppointmentDb;

		$time = strtotime($date.' '.$hour);
		$dataPerMysql = date('Y-m-d G:i:s',$time);
		//echo $dataPerMysql.'<br>'; // DEBUG

		$queryText = "INSERT 
			              INTO appuntamento (idRichiedente, idRicevente, dataOra, durata, note)
			              VALUES($applier, $receiver,\"$dataPerMysql\",$duration,'".$notes."');";
		//echo "Data: $dataPerMysql, Query: $queryText<br>"; // DEBUG
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$bookMyAppointmentDb->closeConnection();
		return $result; // $result contiene true se la query è andata a buon fine, false in caso contrario
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Profile - Book My Appointment</title>
	<meta name = "author" content = "Giacomo Furia">
	<script src="./../js/appointmentTable.js"></script>
	<link rel="stylesheet" href="./../css/page.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/menu.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/profile.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/AppointmentTable.css" type="text/css" media="screen">
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
			<div id="user_info">
				<?php
					$userInfo = null;
					if(isset($_GET['user'])){
						$userInfo = getUserInfo($_GET['user']);
					}else{
						$userInfo = getUserInfo($_SESSION['userId']);
					}
					
				?>
				<p>Immagine del profilo</p>
				<?php
					/* Verifico se sono arrivati dei dati da una conferma di prenotazione tramite POST */

					if(parametriRicevuti()){
						$note = null;
						if(isset($_POST['appointment_notes'])){
							$note = $_POST['appointment_notes'];
						}
						$res = saveAppointment($_POST['appointment_receiver_user'], 
							$_POST['appointment_applying_user'], 
							$_POST['appointment_data'], 
							$_POST['appointment_hour'], 
							$_POST['appointment_duration'], $note);
						if(!$res){
							//echo "ERRORE<br>";//DEBUG
						}
					}

					if($userInfo['profile_image'] == null){
						// Metto l'immagine di default
						echo "<img class='profile_image' src='./../img/icon/set1/man.png' alt='Profile image'>";
					}else{
						echo "Img dal DB";
					}
				?>
				<p><?php echo $userInfo['first_name']." ".$userInfo['last_name'] ?></p>
				<p><?php echo "Professione: ".$userInfo['profession']?></p>
			</div>
			<div id="booking_table"> 
				<div id='booking-table-info'>
				<?php
					$tableConfiguration = loadConfig($userInfo['userId']);
					if(!$tableConfiguration){
						echo "<p>Configura la tabella degli appuntamenti</p></div>";
					}else{
						echo "<p>Tabella degli appuntamenti</p></div>";
						$giorni = explode(',',$tableConfiguration['giorni']);
						$inizio = $tableConfiguration['oraInizio'];
						$fine = $tableConfiguration['oraFine'];
						$durata = $tableConfiguration['durataIntervalli'];
						$pause = explode(',',$tableConfiguration['intervalliPausa']);

						$receiverUser = $userInfo['userId']; // DA CAMBIARE !!!
						$applyingUser = $_SESSION['userId'];

						$appointmentTable = new AppointmentTable($giorni, $inizio, $fine, $durata, $pause,$applyingUser,$receiverUser);
						$appointmentTable->show();
					}
				?>
			</div>
		</div> <!-- fine workspace -->
		<div style='clear:both;'></div>
		<div id="confirm_form_container" class='form-container'>
			<form id="confirm-appointment-form" method="POST" action="./profile.php">
				<p> Conferma prenotazione appuntamento </p>
				<label>Utente ricevente<br><input id="receveir_user" name="appointment_receiver_user"></label><br>
				<label>Utente richiedente<br><input id="applying_user" name="appointment_applying_user"></label><br>
				<label>Giorno<br><input id="confirm_data_appointment" name="appointment_data"></label><br>
				<label>Ora<br><input id="confirm_hour_appointment" name="appointment_hour"></label><br>
				<label>Durata<br><input id="confirm_duration_appointment" name="appointment_duration"></label><br><br>
				<input type="text" placeholder="Aggiungi una nota:" name="appointment_notes"><br>
				<button>Conferma prenotazione</button>
			</form>
		</div>
	</div>
	<script type="text/javascript">
		// evidenzio il pulsante della pagina
		var btn = document.getElementById("profile_button");
		btn.style.backgroundColor="#91DFAA";
		btn.style.color="#383838";
	</script>
</body>
</html>