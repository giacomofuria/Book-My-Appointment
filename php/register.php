<?php
	require_once "./util/BMADbManager.php";// includo la classe per la gestione del database
	$firstName = $_POST['first_name'];
	$lastName = $_POST['last_name'];
	$email = $_POST['email'];
	$signUpPassword = $_POST['sign_up_password'];

	echo "First name: $firstName, Last name: $lastName, Email: $email, Password: $signUpPassword <br>";

	userSignUp($email, $firstName, $lastName, $signUpPassword);

	function userSignUp($email, $firstName, $lastName, $password){
		global $bookMyAppointmentDb; // Recupero l'oggetto globale definito nel file php/util/BMADbManager.php
		$email = $bookMyAppointmentDb->sqlInjectionFilter($email);
		$firstName = $bookMyAppointmentDb->sqlInjectionFilter($firstName);
		$lastName = $bookMyAppointmentDb->sqlInjectionFilter($lastName);
		$password = $bookMyAppointmentDb->sqlInjectionFilter($password);
		$queryText ="INSERT INTO USER (email, first_name, last_name, password) VALUES ('".$email."','".$firstName."','".$lastName."','".$password."')";
		//echo "Query di inserimento: ".$queryText." <br>";// DEBUG
		$result = $bookMyAppointmentDb->performQuery($queryText);
		if($result){
			echo "Registrazione ok".$result."<br>";//DEBUG
		}else{
			echo "Registrazione fallita".$result."<br>";//DEBUG
		}
		$bookMyAppointmentDb->closeConnection();
	}
?>