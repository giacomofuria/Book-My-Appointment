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
			<div class='button-container'>
				<button class='save-button'>Aggiungi nuovo utente</button>
				<button class='save-button'>Aggiungi appuntamento</button>
				<button class='save-button'>Rimuovi recensione</button>
				<button class='save-button'>Aggiungi amministratore</button>
			</div>
			<div class='container'></div>
		</div> <!-- fine workspace -->

	</div>
	<script type="text/javascript">
		// evidenzio il pulsante della pagina
		var btn = document.getElementById("admin_button");
		btn.style.backgroundColor="#91DFAA";
		btn.style.color="#383838";

	</script>
</body>
</html>