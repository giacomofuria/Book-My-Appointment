<?php
	class User{
		public $userId=null;
		public $email=null;
		public $firstName=null;
		public $lastName=null;
		public $password=null;
		public $profileImage=null;
		public $profession=null;
		public $address=null;
		public $admin=null;

		public function User($userId=null, $email=null, $firstName=null, $lastName=null, $password=null, $profileImage=null,$professione=null,$address=null,$admin=null){
			$this->userId = $userId;
			$this->email = $email;
			$this->firstName = $firstName;
			$this->lastName = $lastName;
			$this->password = $password;
			$this->profileImage = $profileImage;
			$this->profession = $professione;
			$this->address = $address;
			$this->admin = $admin;
		}

		public function setEmail($email){
			$this->email = $email;
		}
		public function setPassword($password){
			$this->password=$password;
		}

		public function login(){
			if($this->email != null && $this->password != null){
				$userRow = $this->authenticate();
				if($userRow != null && $userRow != -1 && $userRow!=-2){
					session_start();
					setSession($userRow['email'], $userRow['userId'], $userRow['first_name'], $userRow['last_name'],$userRow['admin']);
					return null;
				}else{
					switch ($userRow) {
						case -1:
							// L'utente non è registrato
							return 'Email or Password not valid';
							break;
						
						case -2:
							// L'utente è registrato ma la pwd inserita è errata
							return 'Not valid password';
							break;

						default:
							# qui non dovrebbe mai andare ma per sicurezza restituisco un messaggio di errore generico
							return 'Error';
							break;
					}
				}
			}else{
				// non è stato inserito uno dei due campi
				return 'Insert something';
			}
			// L'autenticazione non è andata a buon fine
			//return 'Email or Password not valid';
		}
		/* Verifica la presenza di un utente registrato con l'email inserita e se presenta confronta la password digitata nel login. 
		   Se i valori sono corretti restituisce la riga della tabella USER corrispondete all'utente
		 */
		private function authenticate(){
			global $bookMyAppointmentDb; // Recupero l'oggetto globale definito nel file php/util/BMADbManager.php

			$this->email = $bookMyAppointmentDb->sqlInjectionFilter($this->email);
			$this->password = $bookMyAppointmentDb->sqlInjectionFilter($this->password);
			$queryText = "SELECT * FROM user WHERE email='".$this->email."';";
			$result = $bookMyAppointmentDb->performQuery($queryText);
			$numRow = mysqli_num_rows($result);
			if($numRow != 1) // l'utente non è proprio registrato al sito
				return -1;

			$bookMyAppointmentDb->closeConnection();
			$userRow = $result->fetch_assoc();

			$hash = $userRow['password']; // prelevo l'hash della password che è salvata nel db
			$esito = password_verify($this->password,$hash); //confronta la pwd inserita dall'utente con quella memorizzata nel db
			/*
			echo '<p>Password inserita: '.$this->password.'</p>';
			echo '<p>Hash nel db: '.$hash.'</p>';
			echo '<p>Esito: '.$esito.'</p>';
			*/
			if($esito)
				return $userRow;
			else
				return -2; // Errore: l'utente è registrato nel sito ma la pwd è errata
		}
	}
?>