<?php
	require_once "./util/BMADbManager.php";// includo la classe per la gestione del database


	function register($email, $firstName, $lastName, $password){
		global $bookMyAppointmentDb; // Recupero l'oggetto globale definito nel file php/util/BMADbManager.php
		$email = $bookMyAppointmentDb->sqlInjectionFilter($email);
		$firstName = $bookMyAppointmentDb->sqlInjectionFilter($firstName);
		$lastName = $bookMyAppointmentDb->sqlInjectionFilter($lastName);
		$password = $bookMyAppointmentDb->sqlInjectionFilter($password);

		// cripto la password
		$hash = password_hash($password, PASSWORD_BCRYPT);

		//$queryText ="INSERT INTO USER (email, first_name, last_name, password) VALUES ('".$email."','".$firstName."','".$lastName."','".$password."')"; // vecchio senza hash
		$queryText ="INSERT INTO USER (email, first_name, last_name, password) VALUES ('".$email."','".$firstName."','".$lastName."','".$hash."')"; // vecchio senza hash

		//echo "Query di inserimento: ".$queryText." <br>";// DEBUG
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$bookMyAppointmentDb->closeConnection();
		return $result; // $result contiene true se la query è andata a buon fine, false in caso contrario
		
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Register - Book My Appointment</title>
	<meta name = "author" content = "Giacomo Furia">
	<link rel="stylesheet" href="./../css/login.css" type="text/css" media="screen">
    <link rel="stylesheet" href="./../css/register.css" type="text/css" media="screen">
</head>
<body>
	<section class="central-container">
		<div id="register-box-container" class="sign">
			<div id="register-box-header" class="sign_header">
				<?php
				$errorMessage = 'there is a problem with your registration';
				if(!isset($_POST['first_name']) || !isset($_POST['last_name']) || !isset($_POST['email']) || !isset($_POST['sign_up_password'])){
					$errorMessage.=', please insert all fileds';
					$result = false;
				}else{
					$firstName = $_POST['first_name'];
					$lastName = $_POST['last_name'];
					$email = $_POST['email'];
					$signUpPassword = $_POST['sign_up_password'];
					if($firstName == null || $lastName == null || $email == null || $signUpPassword == null){
						$errorMessage.=', please insert all fileds';
						$result = false;
					}else{
						$result = register($email, $firstName, $lastName, $signUpPassword);
						$errorMessage = 'there is alredy an account with this email: '.$_POST['email']; // nel caso in cui $result sia false (query non eseguita) questo sarà il msg di errore
					}
				}
				if($result){
					 echo "<h2>Welcome $firstName, registration completed successfully</h2>";
				}else{
					echo "<h3>Sorry, $errorMessage</h3>";
				}
				?>
			</div>
			<div class="register_box">
				<p>Where are redirecting you to the login page ...</p>
				<?php header("refresh:15; url=./../index.php");?>
				<p>If it doesn't work <a href="./../index.php">click here</a></p>
			</div>
		</div>
	</section>
</body>
</html>