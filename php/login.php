<?php
	require_once "./util/BMADbManager.php";// includo la classe per la gestione del database
	require_once "./util/sessionUtil.php"; // includo funzioni per la gestione della sessione 

	$username = $_POST['username'];
	$password = $_POST['password'];

	echo 'Username: '.$username.', password: '.$password;

	/* Funzione che verifica se l'utente è presente nel database.
		Se l'utente è presente allora restituisce il suo userId
		altrimenti restituisce -1.
	*/
	function authenticate($username, $password){
		global $bookMyAppointmentDb;

		$username = $bookMyAppointmentDb->sqlInjectionFilter($username);
		$password = $bookMyAppointmentDb->sqlInjectionFilter($password);

		$queryText = "SELECT * FROM user WHERE username='".$username."' AND password='".$password."'";

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