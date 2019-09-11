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
		<link rel="stylesheet" href="./css/manual.css" type="text/css" media="screen">
		<script src="./js/login.js"></script>
		<script src="./js/effects.js"></script>
		<title>Prenotazione appuntamenti online</title>
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
		<?php
			include "./php/layout/manual.php";
		?>
		
	</div>
</div>
</body>
</html>