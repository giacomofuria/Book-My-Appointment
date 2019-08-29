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
					<p>Seleziona i giorni di lavoro</p>
					<p>
						<label><input type="checkbox">Lun</label>
						<label><input type="checkbox">Mar</label>
						<label><input type="checkbox">Mer</label>
						<label><input type="checkbox">Gio</label>
						<label><input type="checkbox">Ven</label>
						<label><input type="checkbox">Sab</label>
						<label><input type="checkbox">Dom</label>
					</p>
					<p>
						<label>Orario di apertura <input type="time" name="opening_time"><label>
						<label>Orario di chiusura <input type="time" name="closing_time"><label>
					</p>
					<p>
						<label>Durata media di ogni appuntamento</label>
  						<input type="range" min="10" max="300" value="30" class="slider" id="myRange">
					</p>
					<p>
						<label>Orario inizio pausa <input type="time" name="start_pause"><label>
						<label>Orario fine pausa <input type="time" name="finish_pause"><label>
					</p>
					<p>
						<label><input type="radio" name="tipo_inserimento" value="comune" required>Usa questa organizzazione per tutti i giorni</label>
						<label><input type="radio" name="tipo_inserimento" value="singolo"required>Voglio impostare ogni giorno in modo diverso</label>
					</p>
					<button onclick="prova()">Salva</button>
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
						<td>8.30</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>9.30</td>
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
		// evidenzio il pulsante della pagina
		var btn = document.getElementById("add-button");
		//btn.style.backgroundColor="#91DFAA";
		btn.style.backgroundColor="#91DFAA";
	</script>
</body>
</html>