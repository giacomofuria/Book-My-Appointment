<?php
	session_start();
	include "./util/sessionUtil.php";
	require_once "./util/BMADbManager.php";
	if(!isLogged() || !$_SESSION['admin']){
		header('Location: ./../index.php');
		exit;
	}
	
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
			</div>
			<div id='tool_button_container' class='button-container'>
				<button id='new_user_button' value='new_user_form' class='tool-button'>Aggiungi nuovo utente</button>
				<button id='new_appointment_button' value='new_appointment_form' class='tool-button'>Aggiungi appuntamento</button>
				<button id='remove_review_button' value='remove_review_form' class='tool-button'>Rimuovi recensione</button>
				<button id='new_admin_button' value='add_admin_form' class='tool-button'>Aggiungi amministratore</button>
				<button id='reset_password_button' value='reset_user_password_form' class='tool-button'>Resettare la password di un utente</button>
				<button id='change_password_button' value='change_user_password_form' class='tool-button'>Cambiare la password di un utente</button>
			</div>
			<div id='new_user_form' class='container tool-form-container'>
				Nuovo utente
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
			<div id='change_user_password_form' class='container tool-form-container'>
				Cambia la password di un utente
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