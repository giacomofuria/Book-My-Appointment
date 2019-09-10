<?php
	/* libreria di funzioni di utilità per l'amministrazione del sito */
	
	/* Funzione che verifica se i dati sono stati passati (almeno i campi obbligatori) ed in caso positivo li memorizza */
	function addNewUser(){
		global $bookMyAppointmentDb;
		$email = null;
		$nome =null;
		$cognome = null;
		$professione=null;
		$indirizzo=null;
		$password=null;
		$admin=0;
		$imgProfilo=null;
		$values='email, first_name, last_name,password';
		$elements=null;
		if(!isset($_POST['email']) || !isset($_POST['first_name']) || !isset($_POST['last_name']) ||!isset($_POST['password'])){
			return false;
		}
		$email=$_POST['email']; $nome=$_POST['first_name']; $cognome=$_POST['last_name']; $password=password_hash($_POST['password'], PASSWORD_BCRYPT);
		// immagine del profilo
		$userPicPath=null;
		if(isset($_FILES['img_profile']) && is_uploaded_file($_FILES['img_profile']['tmp_name'])){
			
			$userPicPath = $_FILES['img_profile']['tmp_name'];
			$imgProfilo = "'".addslashes(file_get_contents($userPicPath))."'";
			$values.=',profile_image';
			$elements.=",".$imgProfilo;
		}
		if(isset($_POST['profession']) && $_POST['profession']!=''){
			$professione="'".$_POST['profession']."'";
			$values.=',profession';
			$elements.=",".$professione;
		}
		if(isset($_POST['address']) && $_POST['address']!=''){
			$indirizzo="'".$_POST['address']."'";
			$values.=',address';
			$elements.=",".$indirizzo;
		}

		if(isset($_POST['admin']) && $_POST['admin']=='on'){
			$admin=1;
		}

		
		
		$queryText = "INSERT INTO 
					  USER ($values,admin) 
					  VALUES ('$email','$nome','$cognome','$password' $elements,$admin)";

		//echo "$queryText<br>"; // DEBUG
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$bookMyAppointmentDb->closeConnection();
		return $result;
	}
	function parametriProfiloRicevuti(){
		if(!isset($_POST['email']) || !isset($_POST['first_name']) || !isset($_POST['last_name']) || !isset($_POST['address'])){
			return false;
		}else{
			return true;
		}
	}
	function saveUserSettings($utente,$dimMax, $userPicPath, $firstName, $lastName, $newEmail, $profession, $address, $newPassword,$newAdmin){
		global $bookMyAppointmentDb;
		$sets="first_name='".$firstName."',last_name='".$lastName."',email='".$newEmail."',address='".$address."', admin='".$newAdmin."'";
		if($userPicPath){
			//$data = $bookMyAppointmentDb->sqlInjectionFilter(file_get_contents($userPicPath));
			$data = addslashes(file_get_contents($userPicPath));
			$sets.=",profile_image='".$data."'";
		}
		if($profession){
			$sets.=",profession='".$profession."'";
		}
		if($newPassword){
			$hash = password_hash($newPassword, PASSWORD_BCRYPT);
			$sets.=",password='".$hash."'";
		}
		$queryText = "UPDATE USER 
						SET $sets
						WHERE userId=$utente;";
		//echo "QUERY: $queryText<br>"; //DEBUG
		
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$bookMyAppointmentDb->closeConnection();
		return $result;
		
	}
	
?>