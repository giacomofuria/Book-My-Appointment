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
	<script src="./../js/PreviewTable.js"></script>
	<script src="./../js/appointmentTableCreator.js"></script>
	<link rel="stylesheet" href="./../css/page.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/menu.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/appointmentTableCreator.css" type="text/css" media="screen">
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
						<p>Inserisci gli orari di apertura e di chiusura</p>
						<label>Orario di apertura <input type="time" name="opening_time" required><label>
						<label>Orario di chiusura <input type="time" name="closing_time" required><label>
					</div>
					<div id="appointment_duration">
						<p>Durata media di ogni appuntamento</p>
  						<select name="select_duration" id="select_duration">
  							<option value="10">10 min</option>
  							<option value="30">30 min</option>
  							<option value="60" selected>1 h</option>
  							<option value="120">2 h</option>
  							<option value="variabile">variabile</option>
  						</select>
					</div>
					<div id="pauses">

						<p>Seleziona uno o più intervalli che non vuoi rendere prenotabili:</p>
						<select id="pauses_selector" name="pauses_selector" multiple>

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
		var btn = document.getElementById("add-button");
		//btn.style.backgroundColor="#91DFAA";
		btn.style.backgroundColor="#91DFAA";
	</script>
</body>
</html>