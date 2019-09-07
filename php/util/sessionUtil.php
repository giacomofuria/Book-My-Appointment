<?php
	// Setta l'array associativo $_SESSION con i dati username e userId
	function setSession($email, $userId, $firstName, $lastName, $admin){
		$_SESSION['userId'] = $userId;
		$_SESSION['email'] = $email;
		$_SESSION['first_name'] = $firstName;
		$_SESSION['last_name'] = $lastName;
		$_SESSION['admin'] = $admin;
	}

	// Verifica che un utente sia loggato
	function isLogged(){
		if(isset($_SESSION['userId'])){
			return $_SESSION['userId']; // in caso positivo restituisce lo userId dell'utente
		}else{
			return false; // nel caso nessun utente sia loggato
		}
	}

?>