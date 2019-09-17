<?php
	include_once __DIR__."/../config.php";
	include_once DIR_UTIL."BMADbManager.php";
	include_once DIR_UTIL."sessionUtil.php";
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
		public function setFirstName($firstName){
			$this->firstName =$firstName;
		}
		public function setLastName($lastName){
			$this->lastName = $lastName;
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

		/* Funzione che registra l'utente */
		public function register(){
			global $bookMyAppointmentDb; // Recupero l'oggetto globale definito nel file php/util/BMADbManager.php
			$this->email = $bookMyAppointmentDb->sqlInjectionFilter($this->email);
			$this->firstName = $bookMyAppointmentDb->sqlInjectionFilter($this->firstName);
			$this->lastName = $bookMyAppointmentDb->sqlInjectionFilter($this->lastName);
			$this->password = $bookMyAppointmentDb->sqlInjectionFilter($this->password);

			// Qui posso validare i campi e restituire un messaggio di errore personalizzato
			if(!validateName($this->firstName)){
				return "Siamo spiacenti, inserisci un nome valido";
			}
			if(!validateName($this->lastName)){
				return "Siamo spiacenti, inserisci un cognome valido";
			}
			if(!validateEmail($this->email)){
				return "Siamo spiacenti, inserisci un indirizzo email valido";
			}

			// cripto la password
			$hash = password_hash($this->password, PASSWORD_BCRYPT);
			$queryText ="INSERT INTO USER (email, first_name, last_name, password) VALUES ('".$this->email."','".$this->firstName."','".$this->lastName."','".$hash."')"; // vecchio senza hash

			//echo "Query di inserimento: ".$queryText." <br>";// DEBUG
			$result = $bookMyAppointmentDb->performQuery($queryText);
			$bookMyAppointmentDb->closeConnection();
			
			if($result === TRUE){
				return "Benvenuto $this->firstName, registrazione completata con successo";
			}else{
				return "Siamo spiacenti c'è stato un errore durante la fase di registrazione";
			}
		}
		public function getUserInfo($userId){
			global $bookMyAppointmentDb;
			$queryText = "SELECT * FROM USER WHERE userId='".$userId."';";

			$result = $bookMyAppointmentDb->performQuery($queryText);
			if($result === FALSE){
				return FALSE;
			}
			$numRow = mysqli_num_rows($result);
			$bookMyAppointmentDb->closeConnection();
			$userRow = $result->fetch_assoc();
			$this->userId = $userRow['userId'];
			$this->email = $userRow['email'];
			$this->firstName = $userRow['first_name'];
			$this->lastName = $userRow['last_name'];
			$this->password = $userRow['password'];
			$this->profileImage = $userRow['profile_image'];
			$this->profession = $userRow['profession'];
			$this->address = $userRow['address'];
			$this->admin = $userRow['admin'];
		}
		public function updateUserSettings(){
			global $bookMyAppointmentDb;
			$sets="first_name='".$this->firstName."',last_name='".$this->lastName."',email='".$this->email."',address='".$this->address."'";
			if($this->profileImage){
				$sets.=",profile_image='".$this->profileImage."'";
			}
			if($this->profession){
				$sets.=",profession='".$this->profession."'";
			}
			if($this->admin){
				$sets.=",admin='".$this->admin."'";
			}
			if($this->password){
				$hash = password_hash($this->password, PASSWORD_BCRYPT);
				$sets.=",password='".$hash."'";
			}
			$utente = $_SESSION['userId'];
			$queryText = "UPDATE USER 
							SET $sets
							WHERE userId=$this->userId;";
			//echo "QUERY: $queryText<br>"; //DEBUG
			
			$result = $bookMyAppointmentDb->performQuery($queryText);
			$bookMyAppointmentDb->closeConnection();
			return $result;
		}
		public function receiveProfileParameters(){
			$ret = false;
			$this->profileImage  =false;
			if(isset($_FILES['user_pic']) && is_uploaded_file($_FILES['user_pic']['tmp_name'])){
				//echo "Nome file: ".$_FILES['user_pic']['tmp_name']."<br>";
				$userPicPath = $_FILES['user_pic']['tmp_name'];
				$this->profileImage = addslashes(file_get_contents($userPicPath));
			}
			if(isset($_POST['userId'])){
				$this->userId = $_POST['userId'];
			}
			if(isset($_POST['email'])){
				$this->email = $_POST['email'];
				$ret = true;
			}
			if(isset($_POST['first_name'])){
				$this->firstName = $_POST['first_name'];
			}
			if(isset($_POST['last_name'])){
				$this->lastName = $_POST['last_name'];
			}
			if(isset($_POST['profession'])){
				$this->profession = $_POST['profession'];
			}
			if(isset($_POST['address'])){
				$this->address = $_POST['address'];
			}
			if(isset($_POST['admin']) && $_POST['admin']=='on'){
				$this->admin=1;
			}else{
				$this->admin=0;
			}
			if(isset($_POST['newPassword']) && isset($_POST['reNewPassword'])){
				$newPassword = $_POST['newPassword'];
				$reNewPassword = $_POST['reNewPassword'];
				// controllo se le due password inviate coincidono
				if($newPassword != $reNewPassword){
					// ERRORE !!
				}
				$this->password = $_POST['newPassword'];
			}
			return $ret;
		}
	}
	function validateName($name){
		if (preg_match("/^[a-zA-Z0-9._-]/", $name)){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	function validateEmail($email){
		if (preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/", $email)){
			return TRUE;
		}else{
			return FALSE;
		}
	}
?>