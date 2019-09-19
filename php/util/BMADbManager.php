<?php
	require "dbConfig.php";
	$bookMyAppointmentDb = new BMADbManager(); // Definisco un istanza globale della classe BMADbManager
	/* classe per la gestione del database */

	class BMADbManager {
		private $mysqli_conn = null;

		// costruttore della classe
		function BMADbManager(){
			$this->openConnection();
		}

		// Apre una connessione con il database
		function openConnection(){
			if(!$this->isOpened()){
				global $dbHostname;
				global $dbUsername;
				global $dbPassword;
				global $dbName;

				$this->mysqli_conn = new mysqli($dbHostname, $dbUsername, $dbPassword);

				if($this->mysqli_conn->connect_error)
					die('Connect Error ('.$this->mysqli_conn->connect_errno.')'.$this->mysqli_conn->connect_error);

				$this->mysqli_conn->select_db($dbName) or 
					die('Can\'t use '.$dbName.' database. '.mysqli_error());
			}
		}

		// controlla se la connessione con il database è già aperta o meno
		function isOpened(){
			return ($this->mysqli_conn != null);
		}

		// Esegue una query e restituisce il risultato
		function performQuery($queryText){
			if(!$this->isOpened()){
				// se la connessione non è aperta la apro
				$this->openConnection();
			}
			return $this->mysqli_conn->query($queryText);
		}

		// Filtro sql injection
		function sqlInjectionFilter($parameter){
			if(!$this->isOpened())
				$this->openConnection();

			return $this->mysqli_conn->real_escape_string($parameter);
		}

		// Chiude la connessione
		function closeConnection(){
			if($this->mysqli_conn !== null)
				$this->mysqli_conn->close();

			$this->mysqli_conn = null;
		}
	}
?>