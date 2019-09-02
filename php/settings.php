<?php
	session_start();
	include "./util/sessionUtil.php";
	require_once "./util/BMADbManager.php";// includo la classe per la gestione del database
	if(!isLogged()){
		header('Location: ./../index.php');
		exit;
	}
	function parametriRicevuti(){
		if(isset($_POST['work_days']) && isset($_POST['opening_time']) && 
			isset($_POST['closing_time']) && isset($_POST['select_duration']) 
			/*&& isset($_POST['pauses_selector'])*/){
			return true;
		}
		return false;
	}
	function isSaved($userId){
		global $bookMyAppointmentDb; // Recupero l'oggetto globale definito nel file php/util/BMADbManager.php
		$queryText = "SELECT * FROM struttura_tabella_appuntamenti WHERE userId='".$userId."';";
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		//echo "riga presente: ".$numRow.'<br>'; // debug
		$returnFlag = null;
		if($numRow == 0)
			$returnFlag = false;
		else
			$returnFlag = true;
		$bookMyAppointmentDb->closeConnection();
		return $returnFlag;
	}
	function isDaySelected($days, $day){ // day = 1,2,3,4,5,6,7
		foreach($days as $elem){
			if($elem == $day){
				return 'TRUE';
			}
		}
		return 'FALSE';
	}
	function getPausesString($vett){
		$stringa = null;
		foreach ($vett as $value) {
			$stringa=$stringa.$value." ";
		}
		return $stringa;
	}
	function saveConfig($userId, $giorni, $inizio, $fine, $durata, $pause){
		global $bookMyAppointmentDb; // Recupero l'oggetto globale definito nel file php/util/BMADbManager.php

		//$giorni = $bookMyAppointmentDb->sqlInjectionFilter($giorni);
		$inizio = $bookMyAppointmentDb->sqlInjectionFilter($inizio);
		$fine = $bookMyAppointmentDb->sqlInjectionFilter($fine);
		$durata = $bookMyAppointmentDb->sqlInjectionFilter($durata);
		//$pause = $bookMyAppointmentDb->sqlInjectionFilter($pause);
		$queryText = null;
		$utente = $_SESSION['userId'];
		$stringaConGiorni = implode(',',$giorni);
		$stringaConPause = " ";
		if($pause != null){
			$stringaConPause = implode(',',$pause);
		}
		
		if(!isSaved($utente)){
			// faccio un insert
			
			$queryText = "INSERT 
			              INTO struttura_tabella_appuntamenti 
			              VALUES($utente, '".$stringaConGiorni."','".$inizio."','".$fine."',$durata,'".$stringaConPause."');";
		}else{
			//faccio un update
			$queryText = "UPDATE struttura_tabella_appuntamenti
						  SET giorni='".$stringaConGiorni."',oraInizio='".$inizio."',
						  oraFine='".$fine."',durataIntervalli=$durata,intervalliPausa='".$stringaConPause."'
						  WHERE userId=$utente;";
		}
		//echo isDaySelected($giorni,'1').'<br>'.isDaySelected($giorni,'3'); //DEBUG
		//echo "Query di inserimento: ".$queryText." <br>";// DEBUG
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$bookMyAppointmentDb->closeConnection();
		return $result; // $result contiene true se la query è andata a buon fine, false in caso contrario
	}
	function loadConfig($userId){
		global $bookMyAppointmentDb;
		$queryText = "SELECT * FROM struttura_tabella_appuntamenti WHERE userId='".$userId."';";

		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		$bookMyAppointmentDb->closeConnection();
		$userRow = $result->fetch_assoc();
		$bookMyAppointmentDb->closeConnection();
		return $userRow;
	}
	function findValue($vett, $value){
		if($vett == null || !isset($vett))
			return false;
		foreach($vett as $elem){
			if($elem == $value)
				return true;
		}
		return false;
	}
	function getNumeroAppuntamenti($inizio, $fine,$durata){
		if($inizio != null && $fine != null){
			$dataInizio = strtotime($inizio); // timestamp ora inizio (in secondi)
			$dataFine = strtotime($fine); // timestamp ora fine (in secondi)
			$differenza = ($dataFine - $dataInizio)/60;
			if($differenza < 0){
				echo "Errore: inserisci correttamente gli orari di inizio e di fine<br>"; 
			}
			$numeroAppuntamenti = floor($differenza / $durata); 
			//echo "Date: ".$dataInizio." ".$dataFine.",durata: $differenza minuti, numeroAppuntamenti: $numeroAppuntamenti<br>"; // DEBUG
			return $numeroAppuntamenti;
		}else{
			return 0;
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>New appointment table creator - Book My Appointment</title>
	<meta name = "author" content = "Giacomo Furia">
	<script src="./../js/PreviewTable.js"></script>
	<script src="./../js/appointmentTableCreator.js"></script>
	<link rel="stylesheet" href="./../css/page.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/menu.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/settings.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/tablePreview.css" type="text/css" media="screen">
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
			<div id="creator_container">
				<?php
					$giorni = null;
					$inizio = null;
					$fine = null;
					$durata = null;
					$pause = null;

					if(parametriRicevuti()){
						// prendo i dati dai parametri ricevuti con il metodo post
						$giorni = $_POST['work_days'];
						$inizio = $_POST['opening_time'];
						$fine = $_POST['closing_time'];
						$durata = $_POST['select_duration'];
						if(isset($_POST['pauses_selector'])){
							$pause = $_POST['pauses_selector'];
						}else{
							$pause = null;
						}
						

						// stampa dati per debug (inizio)
						/*
						foreach($giorni as $giorno){
							echo $giorno.'<br>';
						}
						echo "Orario di apertura: ".$inizio.'<br>';
						echo 'Orario di chiusura: '.$fine.'<br>';
						echo 'Durata degli appuntamenti: '.$durata.'<br>';
						foreach($pause as $pausa){
							echo $pausa.'<br>';
						}
						*/
						// stampa dei dati per debug (fine)

						// qui salvo i dati ricevuti, nel db
						$result = saveConfig($_SESSION['userId'], $giorni, $inizio, $fine, $durata, $pause);
					}else{
						// non ci sono parametri passati con post
						if(isSaved($_SESSION['userId'])){
							// carico i dati dal database
							$dati = loadConfig($_SESSION['userId']);
							$giorni = explode(',',$dati['giorni']);
							$inizio = $dati['oraInizio'];
							$fine = $dati['oraFine'];
							$durata = $dati['durataIntervalli'];
							$pause = explode(',',$dati['intervalliPausa']);

						}
					}

					// calcolo il numero degli appuntamenti

					$numeroAppuntamenti = getNumeroAppuntamenti($inizio, $fine,$durata); // inizialmente, quando il form è vuoto

					

				?>
				<form method="post" action="./settings.php">
					<div id="work_days">
						<p>Seleziona i giorni di lavoro</p>
						<label><input type="checkbox" name="work_days[]" value="1" <?php if(findValue($giorni,'1')) echo 'checked';?>>Lun</label>
						<label><input type="checkbox" name="work_days[]" value="2" <?php if(findValue($giorni,'2')) echo 'checked';?> >Mar</label>
						<label><input type="checkbox" name="work_days[]" value="3" <?php if(findValue($giorni,'3')) echo 'checked';?> >Mer</label>
						<label><input type="checkbox" name="work_days[]" value="4" <?php if(findValue($giorni,'4')) echo 'checked';?> >Gio</label>
						<label><input type="checkbox" name="work_days[]" value="5" <?php if(findValue($giorni,'5')) echo 'checked';?> >Ven</label>
						<label><input type="checkbox" name="work_days[]" value="6" <?php if(findValue($giorni,'6')) echo 'checked';?> >Sab</label>
						<label><input type="checkbox" name="work_days[]" value="7" <?php if(findValue($giorni,'7')) echo 'checked';?> >Dom</label>
					</div>
					<div id="open_close_times">
						<p>Inserisci gli orari di apertura e di chiusura</p>
						<label>Orario di apertura 
							<input type="time" name="opening_time" value="<?php if($inizio!=null) echo $inizio; else echo '00:00'; ?>" required>
						<label>
						<label>Orario di chiusura 
							<input type="time" name="closing_time" value="<?php if($fine!=null) echo $fine; else echo '00:00'; ?>" required>
						<label>
					</div>
					<div id="appointment_duration">
						<p>Durata media di ogni appuntamento</p>
  						<select name="select_duration" id="select_duration">
  							<option value="10" <?php if($durata==10)echo 'selected';?>>10 min</option>
  							<option value="30" <?php if($durata==30)echo 'selected';?>>30 min</option>
  							<option value="60" <?php if($durata==60)echo 'selected';?>>1 h</option>
  							<option value="120" <?php if($durata==120)echo 'selected';?>>2 h</option>
  							<!-- <option value="variabile">variabile</option> -->
  						</select>
					</div>
					<div id="pauses">

						<p>Seleziona uno o più intervalli che non vuoi rendere prenotabili:</p>
						<select id="pauses_selector" name="pauses_selector[]" multiple>
							<?php
								for($i = 0; $i < $numeroAppuntamenti; $i++){
									if(findValue($pause,$i)){
										echo "<option value='$i' selected></option>";
									}else{
										echo "<option value='$i'></option>";
									}
									
								}
							?>
						</select>
					</div>
					<button>Salva</button>
				</form>
			</div>
			<div id="preview_container">

			</div>
		</div> <!-- fine workspace -->
	</div>
	<script type="text/javascript">
		begin();
		// evidenzio il pulsante della pagina
		var btn = document.getElementById("settings_button");
		btn.style.backgroundColor="#91DFAA";
		btn.style.color="#383838"
	</script>
</body>
</html>