<?php
	// Setta l'array associativo $_SESSION con i dati username e userId
	function setSession($username, $userId){
		$_SESSION['userId'] = $userId;
		$_SESSION['username'] = $username;
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