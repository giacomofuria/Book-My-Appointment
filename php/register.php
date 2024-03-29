<?php
	require_once "./util/User.php";
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
				$result = "C'è un problema nella tua registrazione";
				if(!isset($_POST['first_name']) || !isset($_POST['last_name']) || !isset($_POST['email']) || !isset($_POST['sign_up_password'])){
					$result.=', per favore inserisci tutti i campi del form';
				}else{
					$firstName = $_POST['first_name'];
					$lastName = $_POST['last_name'];
					$email = $_POST['email'];
					$signUpPassword = $_POST['sign_up_password'];

					$utente = new User();
					$utente->setEmail($email);
					$utente->setFirstName($firstName);
					$utente->setLastName($lastName);
					$utente->setPassword($signUpPassword);

					$result=$utente->register();
				}
				echo "<h2>$result</h2>";
				?>
			</div>
			<div class="register_box">
				<p>Ti stiamo indirizzando verso la pagina di login ...</p>
				<?php header("refresh:10; url=./../index.php");?>
				<p>Se non vuoi attendere <a href="./../index.php">clicca qui</a></p>
			</div>
		</div>
	</section>
</body>
</html>