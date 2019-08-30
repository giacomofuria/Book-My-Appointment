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
	<title>New appointment table creator - Book My Appointment</title>
	<meta name = "author" content = "Giacomo Furia">
	<script src="./../js/appointmentTableCreator.js"></script>
	<link rel="stylesheet" href="./../css/page.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/menu.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/appointmentTableCreator.css" type="text/css" media="screen">
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
				<form method="post" action="">
					
					<div id="work_days">
						<p>Seleziona i giorni di lavoro</p>
						<label><input type="checkbox" name="work_days" value="1">Lun</label>
						<label><input type="checkbox" name="work_days" value="2">Mar</label>
						<label><input type="checkbox" name="work_days" value="3">Mer</label>
						<label><input type="checkbox" name="work_days" value="4">Gio</label>
						<label><input type="checkbox" name="work_days" value="5">Ven</label>
						<label><input type="checkbox" name="work_days" value="6">Sab</label>
						<label><input type="checkbox" name="work_days" value="7">Dom</label>
					</div>
					<div id="open_close_times">
						<label>Orario di apertura <input type="time" name="opening_time" required><label>
						<label>Orario di chiusura <input type="time" name="closing_time" required><label>
					</div>
					<div id="appointment_duration">
						<p>Durata media di ogni appuntamento</p>
  						<input type="range" min="10" max="300" value="30" class="slider" id="myRange" name="duration" required>
					</div>
					<div id="pauses">
						<label>Orario inizio pausa <input type="time" name="start_pause"><label>
						<label>Orario fine pausa <input type="time" name="finish_pause"><label>
						<label><input type="checkbox" name="pause">Non faccio pause</label>
					</div>
					<div id="type">
						<label><input type="radio" name="tipo_inserimento" value="comune" required>Usa questa organizzazione per tutti i giorni</label>
						<label><input type="radio" name="tipo_inserimento" value="singolo"required>Voglio impostare ogni giorno in modo diverso</label>
					</div>
					<button>Salva</button>
				</form>
			</div>
			<div id="preview_container">
				<table id="preview_table">
					<tr>
						<th></th>
						<th>Lun</th>
						<th>Mar</th>
						<th>Mer</th>
						<th>Gio</th>
						<th>Ven</th>
						<th>Sab</th>
						<th>Dom</th>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</div>
		</div> <!-- fine workspace -->
	</div>
	<script type="text/javascript">
		begin();
		// evidenzio il pulsante della pagina
		var btn = document.getElementById("add-button");
		//btn.style.backgroundColor="#91DFAA";
		btn.style.backgroundColor="#91DFAA";
	</script>
</body>
</html>