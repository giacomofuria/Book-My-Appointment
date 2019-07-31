<?php
	session_start();
	include "./php/util/sessionUtil.php";

	if(isLogged()){
		header('Location: ./php/home.php');
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name = "author" content = "Giacomo Furia">
		<link rel="stylesheet" href="./css/login.css" type="text/css" media="screen">
		<script src="./js/bma.js"></script>
		<script src="./js/effects.js"></script>
		<title>Book My Appointment</title>
	</head>
<body onload="begin()">
<div id="container">
	<div id="left-side">
		<div id="sign" class="sign">
			<div id="sign_header" class="sign_header">

			</div>
			<div class="sign_box" id="sign_second_header">
				
			</div>
			<div class="sign_box" id="form_container">

			</div>
		</div>
	</div>
	<div id="right-side">
		<div id="doc">
			<div id="doc_header">
				<h1 id="right-side-header">Book My Appointment</h1>
			</div>
			<div id="doc_container">
				<p>Prenota il tuo appuntamento online oppure, se sei un'azienda o un piccolo professionista, puoi gestire <br>
					qui le prenotazioni dei tuoi clienti.</p>
			</div>
		</div>
	</div>
</div>
</body>
</html>