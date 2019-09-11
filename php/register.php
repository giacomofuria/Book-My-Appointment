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
	<title>Registrazione - Book My Appointment</title>
	<meta name = "author" content = "Giacomo Furia">
	<link rel="stylesheet" href="./../css/login.css" type="text/css" media="screen">
    <link rel="stylesheet" href="./../css/register.css" type="text/css" media="screen">
</head>
<body>
	<section class="central-container">
		<div id="register-box-container" class="sign">
			<div id="register-box-header" class="sign_header">
				<?php
				$errorMessage = "c'è un problema nella tua registrazione";
				if(!isset($_POST['first_name']) || !isset($_POST['last_name']) || !isset($_POST['email']) || !isset($_POST['sign_up_password'])){
					$errorMessage.=', per favore inserisci tutti i campi del form';
					$result = false;
				}else{
					$firstName = $_POST['first_name'];
					$lastName = $_POST['last_name'];
					$email = $_POST['email'];
					$signUpPassword = $_POST['sign_up_password'];
					if($firstName == null || $lastName == null || $email == null || $signUpPassword == null){
						$errorMessage.=', per favore inserisci tutti i campi del form';
						$result = false;
					}else{
						$result = register($email, $firstName, $lastName, $signUpPassword);
						$errorMessage = "c'è già un account con questa email: ".$_POST['email']; // nel caso in cui $result sia false (query non eseguita) questo sarà il msg di errore
					}
				}
				if($result){
					 echo "<h2>Benvenuto $firstName, registrazione completata con successo</h2>";
				}else{
					echo "<h3>Scusa, $errorMessage</h3>";
				}
				?>
			</div>
			<div class="register_box">
				<p>Ti stiamo indirizzando verso la pagina di login ...</p>
				<?php header("refresh:15; url=./../index.php");?>
				<p>Se non vuoi attendere <a href="./../index.php">clicca qui</a></p>
			</div>
		</div>
	</section>
</body>
</html>