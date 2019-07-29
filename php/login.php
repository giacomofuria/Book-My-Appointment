<?php
	require_once "./util/BMADbManager.php";// includo la classe per la gestione del database
	require_once "./util/sessionUtil.php"; // includo funzioni per la gestione della sessione 

	$username = $_POST['username'];
	$password = $_POST['password'];

	echo 'Username: '.$username.', password: '.$password;
?>