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
	function parametriAppuntamentoRicevuti(){
		if(!isset($_POST['appointment_receiver_user']) || !isset($_POST['appointment_applying_user']) || !isset($_POST['appointment_data']) || !isset($_POST['appointment_hour']) || !isset($_POST['appointment_duration'])){
			return false;
		}else{
			return true;
		}
	}
	function parametriProfiloRicevuti(){
		if(!isset($_POST['MAX_FILE_SIZE']) || !isset($_POST['first_name']) || !isset($_POST['last_name']) || !isset($_POST['address'])){
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
		$dataPerMysql = date('Y-m-d H:i:s',$time);
		//echo $dataPerMysql.'<br>'; // DEBUG

		$queryText = "INSERT 
			              INTO appuntamento (idRichiedente, idRicevente, dataOra, durata, note)
			              VALUES($applier, $receiver,\"$dataPerMysql\",$duration,'".$notes."');";
		//echo "Data: $dataPerMysql, Query: $queryText<br>"; // DEBUG
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$bookMyAppointmentDb->closeConnection();
		
		return $result; // $result contiene true se la query è andata a buon fine, false in caso contrario
	}
	function saveUserSettings($dimMax, $userPicPath, $firstName, $lastName, $profession, $address, $newPassword){
		global $bookMyAppointmentDb;
		$sets="first_name='".$firstName."',last_name='".$lastName."',address='".$address."'";
		if($userPicPath){
			$data = $bookMyAppointmentDb->sqlInjectionFilter(file_get_contents($userPicPath));
			$sets.=",profile_image='".$data."'";
		}
		if($profession){
			$sets.=",profession='".$profession."'";
		}
		if($newPassword){
			$hash = password_hash($newPassword, PASSWORD_BCRYPT);
			$sets.=",password='".$hash."'";
		}
		$utente = $_SESSION['userId'];
		$queryText = "UPDATE USER 
						SET $sets
						WHERE userId=$utente;";
		//echo "QUERY: $queryText<br>"; //DEBUG
		
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$bookMyAppointmentDb->closeConnection();
		return $result;
		
	}
	function getProfileImage($utente){
		global $bookMyAppointmentDb;
		$queryText = "SELECT profile_image FROM USER WHERE userId=$utente;";
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		if($numRow != 1) // l'utente non è proprio registrato al sito
			return null;

		$bookMyAppointmentDb->closeConnection();
		$userRow = $result->fetch_assoc();

		$img = $userRow['profile_image'];
		return base64_encode($img);
	}
	/* Verifico se sono arrivati dei dati da una conferma di prenotazione tramite POST 
		   e in caso positivo memorizzo la prenotazione nel db chiamando la funzione saveAppointment
		*/
	echo "<script src='./../js/effects.js'></script>
	         <script src='./../js/profile.js'></script>";
	if(parametriAppuntamentoRicevuti()){
		$note = null;
		if(isset($_POST['appointment_notes'])){
			$note = $_POST['appointment_notes'];
		}
		$esitoSalvataggio = saveAppointment($_POST['appointment_receiver_user'], 
			$_POST['appointment_applying_user'], 
			$_POST['appointment_data'], 
			$_POST['appointment_hour'], 
			$_POST['appointment_duration'], $note);

		// testare esitoSalvataggio per verificare se la prenotazione è avvenuta correttamente
	}

	// SPOSTARE SOPRA !!!
	if(parametriProfiloRicevuti()){
		$dimMax = $_POST['MAX_FILE_SIZE'];
		$userPicPath = false;
		$firstName = $_POST['first_name'];
		$lastName = $_POST['last_name'];
		$profession = false;
		$address = $_POST['address'];
		$newPassword=false;
		$reNewPassword=false;
		// verifico la presenza dei parametri non obbligatori (userPic, professione e le password)
		if(isset($_FILES['user_pic']) && is_uploaded_file($_FILES['user_pic']['tmp_name'])){
			//echo $_FILES['user_pic']['tmp_name']."<br>";
			$userPicPath = $_FILES['user_pic']['tmp_name'];
		}
		if(!is_uploaded_file($_FILES['user_pic']['tmp_name'])){
			//echo "Problemi di caricamento <br>";
			//echo $_FILES['user_pic']['error']."<br>";
		}
		if(isset($_POST['profession'])){
			$profession = $_POST['profession'];
		}
		if(isset($_POST['newPassword']) && isset($_POST['reNewPassword'])){
			$newPassword = $_POST['newPassword'];
			$reNewPassword = $_POST['reNewPassword'];
			// controllo se le due password inviate coincidono
			if($newPassword != $reNewPassword){
				// ERRORE !!
			}
		}
		$esitoSalvataggioImpostazioniUtente = saveUserSettings($dimMax, $userPicPath, $firstName, $lastName, $profession, $address, $newPassword);
		//echo "Esito: ".$esitoSalvataggioImpostazioniUtente."<br>";//DEBUG
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
					$flagPaginaPersonale = false; // flag che indica se l'utente che sta visitando il profilo è il proprietario del profilo
					if($_SESSION['userId'] == $userInfo['userId']){
						$flagPaginaPersonale = true;
					}
				
					echo "<div class='profile-name-container'>";
						echo "<p> ".$userInfo['first_name']." ".$userInfo['last_name'];
							if($flagPaginaPersonale){
								$nome = $userInfo['first_name'];
								$cognome = $userInfo['last_name'];
								$professione = $userInfo['profession'];
								$indirizzo = $userInfo['address'];
								echo "<button class='profile-setting-button' onclick=\"openProfileSettings('$nome','$cognome','$professione','$indirizzo');\">";
								echo "<img class='profile-setting-icon' src='./../img/icon/set1/settings-1.png'>";
								echo "</button>";
							}
						echo "</p>";
					echo "</div>";

					if($userInfo['profile_image'] == null){
						// Metto l'immagine di default
						echo "<div class='profile-img-container'>";
							echo "<img class='profile_image' src='./../img/icon/set1/man.png' alt='Profile image'>";
						echo "</div>";
					}else{
						$utente = $userInfo['userId'];
						$immagineProfilo = getProfileImage($utente);
						
						echo "<div class='profile-img-container'>";
							echo "<img class='profile_image' src=\"data:image/jpeg;base64,$immagineProfilo\" alt='Profile image'>";
						echo "</div>";
					}
				?>
				<div id='profile-info-container' class='profile-info-container'>
					<div id='profile-info-labels' class='profile-info'>
						<p>Nome</p>
						<p>Cognome</p>
						<p>Professione</p>
						<p>Indirizzo</p>
					</div>
					<div class='profile-info'>
						<p><?php echo $userInfo['first_name']; ?></p>
						<p><?php echo $userInfo['last_name']; ?></p>
						<p>
							<?php 
								if($userInfo['profession'] == null){
									echo "&nbsp;"; 
								}else
									echo $userInfo['profession']; 
							?>
						</p>
						<p><?php echo $userInfo['address']; ?></p>
					</div>
					<div style='clear:both;'></div>
				</div>
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
			<?php
				// prelevo i parametri GET per mantenere lo stato
				$parametro="";
				if(isset($_GET['week'])){
					$parametro="&week=".$_GET['week'];
				}
			?>
		</div>
		<form id="confirm-appointment-form" method="POST" action="./profile.php?user=<?php echo $userInfo['userId'].$parametro;?>">
				<p> Conferma prenotazione appuntamento </p>
				<label>Utente ricevente<br><input id="receveir_user" name="appointment_receiver_user"></label><br>
				<label>Utente richiedente<br><input id="applying_user" name="appointment_applying_user"></label><br>
				<label>Giorno<br><input id="confirm_data_appointment" name="appointment_data"></label><br>
				<label>Ora<br><input id="confirm_hour_appointment" name="appointment_hour"></label><br>
				<label>Durata<br><input id="confirm_duration_appointment" name="appointment_duration"></label><br><br>
				<input type="text" placeholder="Aggiungi una nota:" name="appointment_notes"><br>
				<button type="submit">Conferma prenotazione</button>
				<button id="exit_button" onclick="closeConfirmAppointmentBox()" type="button">Elimina prenotazione</button>
			</form>
	</div>
	<?php
		if($esitoSalvataggio){
			// Faccio apparire qualcosa sulla pagina che fonferma il savataggio
			echo "<div id='confirm-box' class='confirm-message-box'> 
				<p>Prenotazione avvenuta con successo</p><img class='img-confirm-box' src='./../img/icon/set1/correct.png'></div>"; // DA SPOSTARE QUI ^
				echo "<script type='text/javascript'> showConfirmBox(); </script>";
		}else{
			//echo "ERRORE<br>";//DEBUG
		}
	?>
	<script type="text/javascript">
		// evidenzio il pulsante della pagina
		var btn = document.getElementById("profile_button");
		btn.style.backgroundColor="#91DFAA";
		btn.style.color="#383838";
	</script>
</body>
</html>