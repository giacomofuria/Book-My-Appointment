<?php
	session_start();
	include "./util/sessionUtil.php";
	include "./layout/AppointmentTable.php";
	require_once "./util/BMADbManager.php";// includo la classe per la gestione del database
	if(!isLogged()){
		header('Location: ./../index.php');
		exit;
	}
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
	function saveUserSettings($dimMax, $userPicPath, $firstName, $lastName, $newEmail, $profession, $address, $newPassword){
		global $bookMyAppointmentDb;
		$sets="first_name='".$firstName."',last_name='".$lastName."',email='".$newEmail."',address='".$address."'";
		if($userPicPath){
			//$data = $bookMyAppointmentDb->sqlInjectionFilter(file_get_contents($userPicPath));
			$data = addslashes(file_get_contents($userPicPath));
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
	function parametriRecensioneRicevuti(){
		if(!isset($_POST['punteggio'])){
			return false;
		}
		return true;
	}
	function saveNewReview($utenteRicevente, $utenteRecensore, $dataOra, $punteggio, $testoRecensione){
		global $bookMyAppointmentDb;
		$queryText = null;
		if($testoRecensione == null){
			$queryText = "INSERT INTO recensione (idRicevente, idRecensore, dataOra, punteggio) 
					  VALUES ($utenteRicevente, $utenteRecensore, \"$dataOra\", $punteggio)";
		}else{
			$queryText = "INSERT INTO recensione (idRicevente, idRecensore, dataOra, punteggio, testoRecensione) 
					  VALUES ($utenteRicevente, $utenteRecensore, \"$dataOra\", $punteggio, \"$testoRecensione\")";
		}
		//echo "$queryText<br>"; // DEBUG
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$bookMyAppointmentDb->closeConnection();
		return $result;
	}
	/* funzione che verifica se l'utente recensore ha già effettuato in passato appuntamenti con l'utente ricevente */
	function findOldAppointments($utenteRecensore, $utenteRicevente){
		global $bookMyAppointmentDb;
		$dataOraAttuale = date('Y-m-d H:i:s',time());
		$queryText = "SELECT * FROM appuntamento 
					  WHERE idRichiedente=$utenteRecensore AND idRicevente=$utenteRicevente AND dataOra<\"$dataOraAttuale\";";
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		if($numRow <= 0){
			return false;
		}else{
			return true;
		}
	}
	/* Funzione che restituisce le recensioni di un utente in ordine cronologico (dalla più recente) */
	function getReviews($utente){
		global $bookMyAppointmentDb;
		$queryText = "SELECT U2.first_name AS nome_recensore,
							 U2.last_name AS cognome_recensore, 
							 U2.profile_image AS img_recensore,
							 R.punteggio AS punteggio, 
							 R.testoRecensione AS testo_recensione,
							 R.dataOra AS dataOra
						FROM user U INNER JOIN recensione R INNER JOIN  user U2 ON U.userId=R.idRicevente AND R.idRecensore=U2.userId
						WHERE U.userId=$utente ORDER BY dataOra DESC;";
		//echo $queryText."<br>"; // DEBUG
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		$bookMyAppointmentDb->closeConnection();
		$recensioni = array();
		while($row = $result->fetch_assoc()){
			//echo $row['idAppuntamento']." ".$row['dataOra']."<br>";
			//echo "punteggio: ".$row['punteggio'].", recensione: ".$row['testoRecensione']."<br>";
			$recensioni[] = $row;
		}
		return $recensioni;
	}
	/* Funzione che calcola il punteggio medio di un utente*/
	function getPunteggioMedio($utente){
		global $bookMyAppointmentDb;
		$queryText = "SELECT AVG(R.punteggio) AS media 
						FROM user U INNER JOIN recensione R ON U.userId=R.idRicevente  
						WHERE U.userId=$utente;";
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		if($numRow != 1) // l'utente non è proprio registrato al sito
			return false;

		$bookMyAppointmentDb->closeConnection();
		$userRow = $result->fetch_assoc();
		$media = $userRow['media'];
		if($media == null)
			return false;
		else
			return $media;
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

	if(parametriProfiloRicevuti()){
		$dimMax = $_POST['MAX_FILE_SIZE'];
		$userPicPath = false;
		$firstName = $_POST['first_name'];
		$lastName = $_POST['last_name'];
		$newEmail = $_POST['email'];
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
		$esitoSalvataggioImpostazioniUtente = saveUserSettings($dimMax, $userPicPath, $firstName, $lastName,$newEmail, $profession, $address, $newPassword);
		//echo "Esito: ".$esitoSalvataggioImpostazioniUtente."<br>";//DEBUG
	}
	if(parametriRecensioneRicevuti()){
		$punteggio = $_POST['punteggio'];
		$testoRecensione = null;
		if(isset($_POST['testo_recensione'])){
			$testoRecensione = $_POST['testo_recensione'];
		}
		$utenteRicevente = $userInfo['userId'];
		$utenteRecensore = $_SESSION['userId'];

		$dataOra = date('Y-m-d H:i:s',time());
		$esitoSalvataggioRecensione = saveNewReview($utenteRicevente, $utenteRecensore, $dataOra, $punteggio, $testoRecensione);
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
				<div class="container">
					<?php
						
					
						echo "<div class='profile-name-container'>";
							echo "<p> ".$userInfo['first_name']." ".$userInfo['last_name'];
								if($flagPaginaPersonale){
									$nome = $userInfo['first_name'];
									$cognome = $userInfo['last_name'];
									$email = $userInfo['email'];
									$professione = $userInfo['profession'];
									$indirizzo = $userInfo['address'];
									echo "<button class='profile-setting-button' onclick=\"openProfileSettings('$nome','$cognome','$email','$professione','$indirizzo');\">";
									echo "<img class='profile-setting-icon' src='./../img/icon/set1/settings-1.png'>";
									echo "</button>";
								}
							echo "</p>";
						echo "</div>";

						$utente = $userInfo['userId'];
						$src = getProfileImage($utente);
						echo "<div class='profile-img-container'>";
							echo "<img class='profile_image' src=$src alt='Profile image'>";
						echo "</div>";
					?>
					<div id='profile-info-container' class='profile-info-container'>
						<div id='profile-info-labels' class='profile-info'>
							<p>Nome</p>
							<p>Cognome</p>
							<p>Email</p>
							<p>Professione</p>
							<p>Indirizzo</p>
							<p>Punteggio medio</p>
						</div>
						<div id='profile-info-fields' class='profile-info'>
							<p><?php echo $userInfo['first_name']; ?></p>
							<p><?php echo $userInfo['last_name']; ?></p>
							<p><?php echo $userInfo['email']; ?></p>
							<p>
								<?php 
									if($userInfo['profession'] == null){
										echo "&nbsp;"; 
									}else
										echo $userInfo['profession']; 
								?>
							</p>
							<p><?php echo $userInfo['address']; ?></p>
							<?php 
								$utente = $userInfo['userId'];
								$media = getPunteggioMedio($utente); 
								echo "<p>$media</p>";
							?>
						</div>
						<div style='clear:both;'></div>
						<?php
							// controllo le l'utente visitatore ha già avuto appuntamenti in passato e in caso positivo gli do la
						    // possivilità di lasciare una recensione cliccando sul bottone
							$utenteRecensore = $_SESSION['userId'];
							$utenteRicevente = $userInfo['userId'];
							$appuntamentiInPassato = findOldAppointments($utenteRecensore, $utenteRicevente);
							if($appuntamentiInPassato){
								echo "<button class='save-button' onclick='openReviewBox()'>Scrivi una recensione</button>";
							}
						?>	
					</div>
				</div>
				<?php
					$utente = $userInfo['userId'];
					$recensioni = getReviews($utente); // restituisce le recensioni, dalla più recente alla più vecchia
					if($recensioni || $recensioni != null){
						echo "<div class='container'> <h2>Recensioni degli utenti</h2> </div>";
					}
					foreach($recensioni as $recensione){
						echo "<div class='container'>";
						$src = "./../img/icon/set1/man.png";
						if($recensione['img_recensore'] != null){
							$img = base64_encode($recensione['img_recensore']);
							$src = "data:image/jpeg;base64,$img";
						}
						echo "<div class='review-header'>";
							echo "<div class='review-header-element'><img class='reviewer_profile_img' src=\"$src\"></div>";
							echo "<div class='review-header-element'><h3 >".$recensione['nome_recensore']." ".$recensione['cognome_recensore']."</h3></div>";
							echo "<p class='review-time'>il ".$recensione['dataOra']."</p>";
							echo "<div style='clear:both;'></div>";
						echo "</div>";
						echo "<div class='review-body'>";
							echo "<p>Punteggio <b>".$recensione['punteggio']."</b>";
							echo "<p> <b>\"</b>".$recensione['testo_recensione']."<b>\"</b>";
						echo "</div>";
						echo "</div>";
					}
				?>
			</div>
			<div id="booking_table"> 
				<div id='booking-table-info'>
				<?php
					$tableConfiguration = loadConfig($userInfo['userId']);
					if(!$tableConfiguration){
						if($_SESSION['userId']==$userInfo['userId']){
							echo "<p>Configura la tabella degli appuntamenti (<a href='./settings.php'>Impostazioni</a>)</p></div>";
						}else{
							$u = $userInfo['first_name'];
							echo "<p>$u non ha ancora configurato la sua tabella degli appuntamenti</p></div>";
						}
						
					}else{
						echo "<p>Tabella degli appuntamenti</p></div>";
						$giorni = explode(',',$tableConfiguration['giorni']);
						$inizio = $tableConfiguration['oraInizio'];
						$fine = $tableConfiguration['oraFine'];
						$durata = $tableConfiguration['durataIntervalli'];
						$pause = explode(',',$tableConfiguration['intervalliPausa']);

						$receiverUser = $userInfo['userId'];
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
		<form id="add_review_form" class="container" method="post" action="./profile.php?user=<?php echo $userInfo['userId'].$parametro;?>">
			<h2>La tua opione</h2>
			<p>Voto</p>
			<p class="sub-header">da 1 (min) a 5 (max)</p>
			<select class="selector" name="punteggio" required>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			</select>
			<p>Scrivi una recensione</p>
			<textarea class="review" placeholder="Scrivi qui" name="testo_recensione"></textarea>
			<button type="submit" class="save-button">Invia</button>
			<button type="button" class="save-button exit-button" onclick="closeReviewBox()">Esci</button>
		</form>
		<form id="confirm-appointment-form" method="POST" action="./profile.php?user=<?php echo $userInfo['userId'].$parametro;?>">
				<p class='confirm-appointment-header'>Conferma prenotazione appuntamento</p>
				<input id="receveir_user" type="hidden" name="appointment_receiver_user" readonly>
				<input id="applying_user" type="hidden" name="appointment_applying_user" readonly>
				<label>Utente ricevente:<br><input class="input-text" value="<?php echo $userInfo['first_name'].' '.$userInfo['last_name'];?>" readonly></label><br>
				<label>Utente richiedente:<br><input class="input-text" value="<?php echo $_SESSION['first_name'].' '.$_SESSION['last_name'];?>" readonly></label><br>

				<label>Data:<br><input id="confirm_data_appointment" name="appointment_data" readonly></label><br>
				<label>Ora:<br><input id="confirm_hour_appointment" name="appointment_hour" readonly></label><br>
				<label>Durata (in minuti):<br><input id="confirm_duration_appointment" name="appointment_duration" readonly></label><br>
				<!-- <label>Note: <input id="confirm_notes_appointment" type="text" placeholder="Aggiungi una nota:" name="appointment_notes"><br></label> -->
				<label>Note: <textarea id="confirm_notes_appointment" placeholder="Aggiungi una nota:" name="appointment_notes"></textarea><br></label>
				<button id="confirm_appointment_button" class="save-button" type="submit">Conferma prenotazione</button><br>
				<button class="save-button exit-button" onclick="closeConfirmAppointmentBox()" type="button">Esci</button>
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