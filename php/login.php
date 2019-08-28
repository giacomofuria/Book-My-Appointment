<?php
	require_once "./util/BMADbManager.php";// includo la classe per la gestione del database
	require_once "./util/sessionUtil.php"; // includo funzioni per la gestione della sessione 

	$email = $_POST['email'];
	$password = $_POST['password'];

	// echo 'Email: '.$email.', password: '.$password; // DEBUG

	$errorMessage = login($email,$password);
	
	if($errorMessage === null)
		header('location: ./home.php');
	else
		header('location: ./../index.php?errorMessage=' . $errorMessage );
	

	/*Esegue il login creando l'array associativo $_SESSION.
	  Restituisce null se il login è andato a buon fine altrimenti
	  restituisce un messaggio di errore.
	*/
	function login($email, $password){
		if($email != null && $password != null){
			$userId = authenticate($email, $password);
			if($userId > 0){
				session_start();
				$firstName = getFirstName($userId);
				$lastName = getLastName($userId);
				setSession($email, $userId, $firstName, $lastName);
				return null;
			}else{
				switch ($userId) {
					case -1:
						// L'autenticazione non è andata a buon fine
						return 'Email or Password not valid';
						break;
					
					case -2:
						return 'Not valid password';
						break;

					default:
						# code...
						break;
				}
			}
		}else{
			// non è stato inserito uno dei due campi
			return 'Insert something';
		}
		// L'autenticazione non è andata a buon fine
		return 'Email or Password not valid';
	}

	/*  Verifica se l'utente è presente nel database.
		Se l'utente è presente allora restituisce il suo userId
		altrimenti restituisce -1.
	*/
	function authenticate($email, $password){
		global $bookMyAppointmentDb; // Recupero l'oggetto globale definito nel file php/util/BMADbManager.php

		$email = $bookMyAppointmentDb->sqlInjectionFilter($email);
		$password = $bookMyAppointmentDb->sqlInjectionFilter($password);
		//$queryText = "SELECT * FROM user WHERE email='".$email."' AND password='".$password."'"; // Vecchia versione prima che criptassi le pwd

		$queryText = "SELECT * FROM user WHERE email='".$email."';";
		// echo $queryText; // DEBUG

		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		if($numRow != 1) // l'utente non è proprio registrato al sito
			return -1;

		$bookMyAppointmentDb->closeConnection();
		$userRow = $result->fetch_assoc();
		$bookMyAppointmentDb->closeConnection();

		$hash = $userRow['password']; // prelevo l'hash della password che è salvata nel db
		$esito = password_verify($password,$hash); //confronta la pwd inserita dall'utente con quella memorizzata nel db
		echo '<p>Password inserita: '.$password.'</p>';
		echo '<p>Hash nel db: '.$hash.'</p>';
		echo '<p>Esito: '.$esito.'</p>';
		if($esito)
			return $userRow['userId'];
		else
			return -2; // Errore: l'utente è registrato nel sito ma la pwd è errata

	}
	/* Restituisce first name dell'utente con un certo id */

	function getFirstName($userId){
		global $bookMyAppointmentDb; // Recupero l'oggetto globale definito nel file php/util/BMADbManager.php
		$queryText = "SELECT first_name FROM user WHERE userId='".$userId."'";
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		if($numRow != 1)
			return -1;
		$bookMyAppointmentDb->closeConnection();
		$userRow = $result->fetch_assoc();
		$bookMyAppointmentDb->closeConnection();
		return $userRow['first_name'];
	}

	/* Restituisce last name dell'utente con un certo id */

	function getLastName($userId){
		global $bookMyAppointmentDb; // Recupero l'oggetto globale definito nel file php/util/BMADbManager.php
		$queryText = "SELECT last_name FROM user WHERE userId='".$userId."'";
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		if($numRow != 1)
			return -1;
		$bookMyAppointmentDb->closeConnection();
		$userRow = $result->fetch_assoc();
		$bookMyAppointmentDb->closeConnection();
		return $userRow['last_name'];
	}

?>