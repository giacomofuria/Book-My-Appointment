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
		
		/*
		function User($userId, $email, $firstName, $lastName, $profileImage=null, $profession=null,$address=null,$admin=0){
			$this->userId = $userId;
			$this->email = $email;
			$this->firstName = $firstName;
			$this->lastName = $lastName;
			$this->profileImage = $profileImage;
			$this->profession = $profession;
			$this->address = $address;
			$this->admin = $admin;
		}
		*/
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
?>