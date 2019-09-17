<?php
	require_once "./util/BMADbManager.php";// includo la classe per la gestione del database
	require_once "./util/sessionUtil.php"; // includo funzioni per la gestione della sessione 
	require_once "./util/User.php";

	// Verifico lato server la presenza dei parametri passati con metodo post (email e password)
	if(!isset($_POST['email']) || !isset($_POST['password'])){
		$error='Insert something';
		header('location: ./../index.php?errorMessage=' . $error );
	}

	$email = $_POST['email'];
	$password = $_POST['password'];

	$utente = new User();
	$utente->setEmail($email);
	$utente->setPassword($password);

	$errorMessage = $utente->login();
	
	if($errorMessage === null){
		header('location: ./home.php');
	}else{
		header('location: ./../index.php?errorMessage=' . $errorMessage );
	}
?>