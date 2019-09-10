<?php
	class AjaxResponse{
		public $responseCode; // 0 all ok - 1 some errors - -1 some warning
		public $message;
		public $data;
		
		function AjaxResponse($responseCode = 1, 
								$message = "Somenthing went wrong! Please try later.",
								$data = null){
			$this->responseCode = $responseCode;
			$this->message = $message;
			$this->data = null;
		}
	

	}
	class User{
		public $userId=null;
		public $email=null;
		public $firstName=null;
		public $lastName=null;
		
		public $profileImage=null;
		public $profession=null;
		public $address=null;
		public $admin=null;

		function User($userId, $email, $firstName, $lastName, $profileImage,$professione,$address,$admin){
			$this->userId = $userId;
			$this->email = $email;
			$this->firstName = $firstName;
			$this->lastName = $lastName;
			$this->profileImage = $profileImage;
			$this->profession = $professione;
			$this->address = $address;
			$this->admin = $admin;
		}
	}
	class Review{
		public $idRecensione;
		public $nome_recensore;
		public $cognome_recensore;
		public $punteggio;
		public $testo_recensione;
		public $dataOra;
		function Review($idRecensione,$nome_recensore, $cognome_recensore, $punteggio, $testo_recensione, $dataOra){
			$this->idRecensione = $idRecensione;
			$this->nome_recensore=$nome_recensore;
			$this->cognome_recensore = $cognome_recensore;
			$this->punteggio = $punteggio;
			$this->testo_recensione = $testo_recensione;
			$this->dataOra = $dataOra;
		}
	}	
?>