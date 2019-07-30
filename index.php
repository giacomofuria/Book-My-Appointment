<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name = "author" content = "Giacomo Furia">
	<link rel="stylesheet" href="./css/login.css" type="text/css" media="screen">
	<script type="text/javascript" src="./js/bma.js"></script>
	<script type="text/javascript" src="./js/effects.js"></script>
	<title>Book My Appointment</title>
</head>
<body onload="begin()">
<div id="container"> 
	<div id="left-side">
		<div id="sign">
			<div id="sign_header">

			</div>
			<div class="sign_box" id="sign_second_header">
				
			</div>
			<div class="sign_box" id="sign_in_fields">

				<form action="./php/login.php" method="post">
					<input class="input-text" placeholder="Email" type="email" name="username" required><br><br>
					<input class="input-text" placeholder="Password" type="password" name="password" required><br><br>
					<div class="error_message" id="sign_in_error_msg"></div>
					<button id="sign_in_button">Sign in</button>
				</form>

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
				<br><br><br><br><br><br><br><br><br><br><br><br>
				<br><br><br><br><br><br><br><br><br><br><br><br>
				<br><br><br><br><br><br><br><br><br><br><br><br>
			</div>
		</div>
	</div>
</div>
</body>
</html>