<?php
	session_start();
	include_once "./util/sessionUtil.php";
	include "./util/User.php";

	require_once "./util/BMADbManager.php";
	if(!isLogged() || !$_SESSION['admin']){
		header('Location: ./../index.php');
		exit;
	}
	$userInfo = new User();
	if($userInfo->receiveProfileParameters()){
		$userInfo->updateUserSettings();
	}
	if($userInfo->receiveNewUserParameters()){
		$userInfo->addNewUser();
	}
	if(isset($_GET['delReview'])){
		$id = $_GET['delReview'];
		deleteUserReview($id);
	}
	if(isset($_GET['removeUser'])){
		$userInfo->userId=$_GET['removeUser'];
		echo "Result: ".$userInfo->removeUser();
	}
	function deleteUserReview($id){
		global $bookMyAppointmentDb;
		$queryText = "DELETE FROM recensione WHERE idRecensione=$id";
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$bookMyAppointmentDb->closeConnection();
		return $result;
	}
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<title>Home Page - Book My Appointment</title>
	<meta name = "author" content = "Giacomo Furia">
	<script src="./../js/admin.js"></script>
	<script src="./../js/ajaxManager.js"></script>
	<script src="./../js/TopBar.js"></script>
	<script src="./../js/validators.js"></script>
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
				<button id='new_user_button' value='new_user_form' class='tool-button'><img src='./../img/icon/set1/add.png' class='button-icon' alt='nuovo utente'>Aggiungi nuovo utente</button>
				<button id='change_password_button' value='search_user_form' class='tool-button'><img src='./../img/icon/set1/profile.png' class='button-icon' alt='modifica dati utente'>Modificare dati utente</button>
				<button id='remove_review_button' value='search_user_form_review' class='tool-button'><img src='./../img/icon/set1/garbage.png' class='button-icon' alt='rimuovi recensione'>Rimuovi recensione</button>
				<button id='remove_user_button' value='search_user_form_remove' class='tool-button'><img src='./../img/icon/set1/garbage.png' class='button-icon' alt='rimuovi utente'>Rimuovi utente</button>
			<div id='new_user_form' class='container tool-form-container'>
				<h2>Inserisci un nuovo utente</h2>
				<form enctype='multipart/form-data' method='POST' action='./admin.php'>
					<input name='new_user_email' class='input-text' placeholder='Email' type='email' onblur="validate(this)" required>
					<input name='new_user_first_name' class='input-text' placeholder='Nome' onblur="validate(this)" required>
					<input name='new_user_last_name' class='input-text' placeholder='Cognome' onblur="validate(this)" required>
					<input name='new_user_profession' class='input-text' placeholder='Professione'>
					<input name='new_user_address' class='input-text' placeholder='Inidirizzo'>
					<input name='new_user_password' class='input-text' placeholder='Password' type='password' required>
					<input name='new_user_re_password' class='input-text' placeholder='Ripeti password' type='password' required>
					<p><input name='new_user_admin' type='checkbox' > Utente amministratore</p>
					<p><b>Immagine del profilo</b>
					<input type='hidden' name='MAX_FILE_SIZE' value='16777215'>
					<input type='file' name='new_user_pic'>
					</p>
					<button type='submit' class='save-button'>Crea utente</button>
				</form>
			</div>
			<div id='search_user_form' class='container tool-form-container '>
				<h3>Cerca e seleziona l'utente che vuoi modificare</h3>
					<input id='search_user_to_modify' type="text" placeholder="Nome utente" class="input-text" onkeyup="SearchBar.search(this,this.value)">
					<div id="user_admin_search">
						<!-- Risultati: -->
					</div>
			</div>
			<div id='change_user_password_form' class='container tool-form-container'>
				<b>Modifica le informazioni dell' utente</b><br><br>
				<form enctype='multipart/form-data' method='POST' action='./admin.php'>
					<input name='userId' type='hidden'>
					<input name='email' class='input-text' placeholder='Email' type='email' onblur="validate(this)" required>
					<input name='first_name' class='input-text' placeholder='Nome' onblur="validate(this)" required>
					<input name='last_name' class='input-text' placeholder='Cognome' onblur="validate(this)" required>
					<input name='profession' class='input-text' placeholder='Professione'>
					<input name='address' class='input-text' placeholder='Inidirizzo'>
					<input name='admin' type='checkbox' > Utente amministratore<br>
					<input name='newPassword' class='input-text' placeholder='Password' type='password' >
					<input name='reNewPassword' class='input-text' placeholder='Ripeti password' type='password' >
					
					<p><b>Immagine del profilo</b>
					<input type='hidden' name='MAX_FILE_SIZE' value='16777215'>
					<input type='file' name='user_pic'>
					</p>
					<button type='submit' class='save-button'>Salva</button>
				</form>				
			</div>
			<div id='search_user_form_review' class='container tool-form-container '>
				<h3>Cerca e seleziona l'utente che ha ricevuto la recensione che vuoi eliminare</h3>
					<input id='search_user_review' type="text" placeholder="Nome utente" class="input-text" onkeyup="SearchBar.search(this,this.value)">
					<div id="user_admin_search_review">
						<!-- Risultati: -->
					</div>
			</div>
			<div id='remove_review_form' class='container tool-form-container'>
				<p>Clicca sull'icona per rimuovere la recensione</p>
			</div>
			<div id='search_user_form_remove' class='container tool-form-container '>
				<h3>Cerca e seleziona l'utente che vuoi eliminare</h3>
					<input id="search_user_remove" type="text" placeholder="Nome utente" class="input-text" onkeyup="SearchBar.search(this,this.value)">
					<div id="user_admin_search_remove">
						<!-- Risultati: -->
					</div>
			</div>
			<div id='remove_user_form' class='container tool-form-container'>
				<p>Clicca sull'icona per confermare la rimozione dell'utente</p>
			</div>
			<div style="clear:both;"></div>
			</div>
			
		</div> <!-- fine workspace -->
		<?php
			include "./layout/footer.php";
		?>
	</div>
	<script>
		// evidenzio il pulsante della pagina
		beginAdmin();
		var btn = document.getElementById("admin_button");
		btn.style.backgroundColor="#91DFAA";
		btn.style.color="#383838";

	</script>
</body>
</html>