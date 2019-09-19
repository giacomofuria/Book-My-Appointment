<?php
	session_start();
	include_once __DIR__."/config.php";
	include_once DIR_UTIL."sessionUtil.php";
	include_once DIR_UTIL."User.php";
	include_once DIR_UTIL."Notify.php";
	//include "./util/sessionUtil.php";
	include "./layout/AppointmentTable.php";
	require_once "./util/BMADbManager.php";// includo la classe per la gestione del database
	if(!isLogged()){
		header('Location: ./../index.php');
		exit;
	}

	$userInfo = new User();

	if(isset($_GET['user'])){
		$userInfo->getUserInfo($_GET['user']);
	}else{
		$userInfo->getUserInfo($_SESSION['userId']);
	}

	$flagPaginaPersonale = false; // flag che indica se l'utente che sta visitando il profilo è il proprietario del profilo
	if($_SESSION['userId'] == $userInfo->userId){
		$flagPaginaPersonale = true;
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
		
		$utenteRichiedente = new User();
		$utenteRichiedente->getUserInfo($applier);
		$data = date('d-m-Y',$time);
		$ora = date('H:i',$time);
		$testoNotifica = "$utenteRichiedente->firstName $utenteRichiedente->lastName ($utenteRichiedente->email) ha prenotato un appuntamento per il giorno $data alle ore $ora";
		$notifica = new Notify($receiver, $testoNotifica);
		$notifica->send();

		return $result; // $result contiene true se la query è andata a buon fine, false in caso contrario
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

		$InfoUtenteRecensore = new User();
		$InfoUtenteRecensore->getUserInfo($utenteRecensore);
		$testoNotifica = "$InfoUtenteRecensore->firstName $InfoUtenteRecensore->lastName ha appena scritto una recensione su di te";
		$notifica = new Notify($utenteRicevente, $testoNotifica);
		$notifica->send();

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
		$queryText = "SELECT R.idRecensore AS idRecensore,
						     U2.first_name AS nome_recensore,
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
	$esitoSalvataggio=false;
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
	
	if($userInfo->receiveProfileParameters()){
		$userInfo->updateUserSettings();
	}
	if(parametriRecensioneRicevuti()){
		$punteggio = $_POST['punteggio'];
		$testoRecensione = null;
		if(isset($_POST['testo_recensione'])){
			$testoRecensione = $_POST['testo_recensione'];
		}
		$utenteRicevente = $userInfo->userId;
		$utenteRecensore = $_SESSION['userId'];

		$dataOra = date('Y-m-d H:i:s',time());
		$esitoSalvataggioRecensione = saveNewReview($utenteRicevente, $utenteRecensore, $dataOra, $punteggio, $testoRecensione);
	}
	
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<title>Profile - Book My Appointment</title>
	<meta name = "author" content = "Giacomo Furia">
	<script src='./../js/profile.js'></script>
	<script src='./../js/effects.js'></script>
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
							echo "<p> ".$userInfo->firstName." ".$userInfo->lastName;
								if($flagPaginaPersonale){
									$nome = $userInfo->firstName;
									$cognome = $userInfo->lastName;
									$email = $userInfo->email;
									$professione = $userInfo->profession;
									$indirizzo = $userInfo->address;
									echo "<button class='profile-setting-button' onclick=\"openProfileSettings('$nome','$cognome','$email','$professione','$indirizzo');\">";
									echo "<img class='profile-setting-icon' src='./../img/icon/set1/settings-1.png' alt='img profilo'>";
									echo "</button>";
								}
							echo "</p>";
						echo "</div>";

						$utente = $userInfo->userId;
						$src = getProfileImage($utente);
						echo "<div class='profile-img-container'>";
							echo "<img class='profile_image' src='$src' alt='Profile image'>";
						echo "</div>";
					?>
					
					<div id='profile-info-container' class='profile-info-container'>
						
						<table id="profile-info-table" class="user-info">
							<tr><td class="left">Nome</td><td class="right"><?php echo $userInfo->firstName; ?></td></tr>
							<tr><td class="left">Cognome</td><td class="right"><?php echo $userInfo->lastName; ?></td></tr>
							<tr><td class="left">Email</td><td class="right"><a class="email" href="mailto:<?php echo $userInfo->email; ?>"><?php echo $userInfo->email; ?></a></td></tr>
							<tr><td class="left">Professione</td>
								<td class="right">
								<?php 
									if($userInfo->profession == null){
										echo "&nbsp;"; 
									}else
										echo $userInfo->profession; 
								?>
								</td>
							</tr>
							<tr><td class="left">Indirizzo</td><td class="right address"><?php echo $userInfo->address; ?></td></tr>
							<tr><td class="left">Punteggio medio</td>
								<td class="right">
									<?php 
										$utente = $userInfo->userId;
										$media = round(getPunteggioMedio($utente),1); 
										echo "$media / 5";
									?>
								</td>
							</tr>
						</table>
						<?php
							// controllo le l'utente visitatore ha già avuto appuntamenti in passato e in caso positivo gli do la
						    // possivilità di lasciare una recensione cliccando sul bottone
							$utenteRecensore = $_SESSION['userId'];
							$utenteRicevente = $userInfo->userId;
							$appuntamentiInPassato = findOldAppointments($utenteRecensore, $utenteRicevente);
							if($appuntamentiInPassato){
								echo "<button class='save-button' onclick='openReviewBox()'>Scrivi una recensione</button>";
							}
						?>	
					</div>
				</div>
				<?php
					$utente = $userInfo->userId;
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
							echo "<div class='review-header-element'><a href='./profile.php?user=".$recensione['idRecensore']."'><img class='reviewer_profile_img' src=\"$src\" alt='img profilo'></a></div>";
							echo "<div class='review-header-element'><a href='./profile.php?user=".$recensione['idRecensore']."'><h3 >".$recensione['nome_recensore']." ".$recensione['cognome_recensore']."</h3></a></div>";
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
					$tableConfiguration = loadConfig($userInfo->userId);
					if(!$tableConfiguration){
						if($_SESSION['userId']==$userInfo->userId){
							echo "<p>Configura la tabella degli appuntamenti (<a href='./settings.php'>Impostazioni</a>)</p></div>";
						}else{
							$u = $userInfo->firstName;
							echo "<p>$u non ha ancora configurato la sua tabella degli appuntamenti</p></div>";
						}
						
					}else{
						echo "<p>Tabella degli appuntamenti</p></div>";
						$giorni = explode(',',$tableConfiguration['giorni']);
						$inizio = $tableConfiguration['oraInizio'];
						$fine = $tableConfiguration['oraFine'];
						$durata = $tableConfiguration['durataIntervalli'];
						$pause = explode(',',$tableConfiguration['intervalliPausa']);

						$receiverUser = $userInfo->userId;
						$applyingUser = $_SESSION['userId'];

						$appointmentTable = new AppointmentTable($giorni, $inizio, $fine, $durata, $pause,$applyingUser,$receiverUser);
						$appointmentTable->show();
					}
					if($esitoSalvataggio){
						// Faccio apparire qualcosa sulla pagina che confermi il savataggio
						echo "<div id='confirm-box' class='confirm-message-box'> 
							<p>Prenotazione avvenuta con successo</p><img class='img-confirm-box' src='./../img/icon/set1/correct.png' alt='correct'></div>"; // DA SPOSTARE QUI ^
							echo "<script type='text/javascript'> showConfirmBox(); </script>";
					}else{
						//echo "ERRORE<br>";//DEBUG
					}
				?>
			</div>
			
			<div style='clear:both;'></div>
			<?php
				include "./layout/footer.php";
		    ?>
		</div> <!-- fine workspace -->
		
		<div id="confirm_form_container" class='form-container'>
			<?php
				// prelevo i parametri GET per mantenere lo stato
				$parametro="";
				if(isset($_GET['week'])){
					$parametro="&week=".$_GET['week'];
				}
			?>
		</div>
		<form id="add_review_form" class="container" method="post" action="./profile.php?user=<?php echo $userInfo->userId.$parametro;?>">
			<h2>La tua opione</h2>
			<p>Voto</p>
			<p class="sub-header">da 1 (min) a 5 (max)</p>
			<select class="selector" name="punteggio" required>
				<option value="">Punteggio</option>
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
		<form id="confirm-appointment-form" method="POST" action="./profile.php?user=<?php echo $userInfo->userId.$parametro;?>">
				<p class='confirm-appointment-header'>Conferma prenotazione appuntamento</p>
				<input id="receveir_user" type="hidden" name="appointment_receiver_user" >
				<input id="applying_user" type="hidden" name="appointment_applying_user" >
				<label>Utente ricevente:<br><input class="input-text" value="<?php echo $userInfo->firstName.' '.$userInfo->lastName;?>" ></label><br>
				<label>Utente richiedente:<br><input class="input-text" value="<?php echo $_SESSION['first_name'].' '.$_SESSION['last_name'];?>" ></label><br>

				<label>Data:<br><input id="confirm_data_appointment" name="appointment_data" ></label><br>
				<label>Ora:<br><input id="confirm_hour_appointment" name="appointment_hour" ></label><br>
				<label>Durata (in minuti):<br><input id="confirm_duration_appointment" name="appointment_duration" ></label><br>
				<!-- <label>Note: <input id="confirm_notes_appointment" type="text" placeholder="Aggiungi una nota:" name="appointment_notes"><br></label> -->
				<label>Note: <textarea id="confirm_notes_appointment" placeholder="Aggiungi una nota:" name="appointment_notes"></textarea><br></label>
				<button id="confirm_appointment_button" class="save-button" type="submit">Conferma prenotazione</button><br>
				<button class="save-button exit-button" onclick="closeConfirmAppointmentBox()" type="button">Esci</button>
			</form>

	</div>
	<?php
		
	?>
	<script>
		// evidenzio il pulsante della pagina
		var btn = document.getElementById("profile_button");
		btn.style.backgroundColor="#91DFAA";
		btn.style.color="#383838";
	</script>
</body>
</html>