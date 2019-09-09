<?php
	session_start();
	include "./util/sessionUtil.php";
	include "./util/adminUtil.php";

	require_once "./util/BMADbManager.php";
	if(!isLogged() || !$_SESSION['admin']){
		header('Location: ./../index.php');
		exit;
	}
	addNewUser();
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Home Page - Book My Appointment</title>
	<meta name = "author" content = "Giacomo Furia">
	<script src="./../js/admin.js"></script>
	<link rel="stylesheet" href="./../css/page.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/menu.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/admin.css" type="text/css" media="screen">
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
			<div class='page-header'>
				<h2>Pagina di amministrazione</h2>
				<p>Da questa pagina puoi modificare i dati degli utenti e visualizzare delle statistiche del sito.</p>
			</div>
			<div id='tool_button_container' class='button-container'>
				<button id='new_user_button' value='new_user_form' class='tool-button'><img src='./../img/icon/set1/add.png' class='button-icon'>Aggiungi nuovo utente</button>
				<button id='change_password_button' value='change_user_password_form' class='tool-button'><img src='./../img/icon/set1/profile.png' class='button-icon'>Modificare dati utente</button>
				<button id='remove_review_button' value='remove_review_form' class='tool-button'><img src='./../img/icon/set1/garbage.png' class='button-icon'>Rimuovi recensione</button>
				<button id='new_appointment_button' value='new_appointment_form' class='tool-button'>Aggiungi appuntamento</button>
				<button id='reset_password_button' value='reset_user_password_form' class='tool-button'>Resettare la password di un utente</button>
			</div>
			<div id='new_user_form' class='container tool-form-container'>
				<h2>Inserisci un nuovo utente</h2>
				<form enctype='multipart/form-data' method='POST' action='./admin.php'>
					<input name='email' class='input-text' placeholder='Email' type='email' required>
					<input name='first_name' class='input-text' placeholder='Nome' required>
					<input name='last_name' class='input-text' placeholder='Cognome' required>
					<input name='profession' class='input-text' placeholder='Professione'>
					<input name='address' class='input-text' placeholder='Inidirizzo'>
					<input name='password' class='input-text' placeholder='Password' type='password' required>
					<input name='re_password' class='input-text' placeholder='Ripeti password' type='password' required>
					<p><input name='admin' type='checkbox' > Utente amministratore</p>
					<p><b>Immagine del profilo</b>
					<input type='hidden' name='MAX_FILE_SIZE' value='16777215'>
					<input type='file' name='img_profile'>
					</p>
					<button type='submit' class='save-button'>Crea utente</button>
				</form>
			</div>
			<div id='change_user_password_form' class='container tool-form-container'>
				<h2>Seleziona l'utente che vuoi modificare</h2>
				<form>
					<p>Cerca l'utente che vuoi modificare</p>
					<input type="text" placeholder="Nome utente">
					<button type='submit'>Cerca</button>
				</form>
			</div>
			<div id='new_appointment_form' class='container tool-form-container'>
				Nuovo appuntamento
			</div>
			<div id='remove_review_form' class='container tool-form-container'>
				Rimozione recensione
			</div>
			<div id='add_admin_form' class='container tool-form-container'>
				Aggiungi amministratore
			</div>
			<div id='reset_user_password_form' class='container tool-form-container'>
				Resetta la password di un utente
			</div>
		</div> <!-- fine workspace -->

	</div>
	<script type="text/javascript">
		// evidenzio il pulsante della pagina
		beginAdmin();
		var btn = document.getElementById("admin_button");
		btn.style.backgroundColor="#91DFAA";
		btn.style.color="#383838";

	</script>
</body>
</html>