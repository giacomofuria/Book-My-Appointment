<?php
	require_once "./util/BMADbManager.php";// includo la classe per la gestione del database


	function register($email, $firstName, $lastName, $password){
		global $bookMyAppointmentDb; // Recupero l'oggetto globale definito nel file php/util/BMADbManager.php
		$email = $bookMyAppointmentDb->sqlInjectionFilter($email);
		$firstName = $bookMyAppointmentDb->sqlInjectionFilter($firstName);
		$lastName = $bookMyAppointmentDb->sqlInjectionFilter($lastName);
		$password = $bookMyAppointmentDb->sqlInjectionFilter($password);
		$queryText ="INSERT INTO USER (email, first_name, last_name, password) VALUES ('".$email."','".$firstName."','".$lastName."','".$password."')";
		//echo "Query di inserimento: ".$queryText." <br>";// DEBUG
		$result = $bookMyAppointmentDb->performQuery($queryText);
		return $result; // $result contiene true se la query è andata a buon fine, false in caso contrario
		$bookMyAppointmentDb->closeConnection();
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
				if(!isset($_POST['first_name']) || !isset($_POST['last_name']) || !isset($_POST['email']) || !isset($_POST['sign_up_password'])){
					$result = false;
				}else{
					$firstName = $_POST['first_name'];
					$lastName = $_POST['last_name'];
					$email = $_POST['email'];
					$signUpPassword = $_POST['sign_up_password'];
					$result = register($email, $firstName, $lastName, $signUpPassword);
				}
				if($result){
					 echo "<h2>Welcome $firstName, registration completed successfully</h2>";
				}else{
					echo "<h3>Sorry, there is a problem with your registration</h3>";
				}
				?>
			</div>
			<div class="register_box">
				<p>Where are redirecting you to the login page ...</p>
				<?php header("refresh:5; url=./../index.php");?>
				<p>If it doesn't work <a href="./../index.php">click here</a></p>
			</div>
		</div>
	</section>
</body>
</html>