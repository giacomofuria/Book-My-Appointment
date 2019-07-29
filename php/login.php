<?php
	require_once "./util/BMADbManager.php";// includo la classe per la gestione del database
	require_once "./util/sessionUtil.php"; // includo funzioni per la gestione della sessione 

	$email = $_POST['username'];
	$password = $_POST['password'];

	// echo 'Email: '.$email.', password: '.$password; // DEBUG

	$errorMessage = login($email,$password);
	if($errorMessage === null)
		echo 'login ok';
	else
		echo $errorMessage;

	/*Esegue il login creando l'array associativo $_SESSION.
	  REstituisce null se il login è andato a buon fine altrimenti
	  restituisce un messaggio di errore.
	*/
	function login($email, $password){
		if($email != null && $password != null){
			$userId = authenticate($email, $password);
			if($userId > 0){
				session_start();
				setSession($email, $password);
				return null;
			}
		}else{
			// non è stato inserito uno dei due campi
			return 'Insert something';
		}
		// L'autenticazione non è andata a buon fine
		return 'Email and Password not valid';
	}

	/*  Verifica se l'utente è presente nel database.
		Se l'utente è presente allora restituisce il suo userId
		altrimenti restituisce -1.
	*/
	function authenticate($email, $password){
		global $bookMyAppointmentDb;

		$email = $bookMyAppointmentDb->sqlInjectionFilter($email);
		$password = $bookMyAppointmentDb->sqlInjectionFilter($password);

		$queryText = "SELECT * FROM user WHERE email='".$email."' AND password='".$password."'";

		// echo $queryText; // DEBUG

		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		if($numRow != 1)
			return -1;

		$bookMyAppointmentDb->closeConnection();
		$userRow = $result->fetch_assoc();
		$bookMyAppointmentDb->closeConnection();
		return $userRow['userId'];

	}
?>