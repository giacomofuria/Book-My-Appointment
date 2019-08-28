<?php
	echo '<h3> Test hashing password </h3>';

	$mia_password = 'giacomo';
	$hash = password_hash($mia_password, PASSWORD_BCRYPT);
	echo '<p>Password in chiaro: '.$mia_password.', Hash: '.$hash.'</p>';

	$esito = password_verify('giacomo', $hash); // esegue il confronto

	if($esito){
		echo 'SI';
	}else{
		echo 'NO';
	}

?>
<html>
<head>
	<title>Examples</title>
</head>
<body>
	<form method="post" action="./login.php">
		<p>Email: <input name="email"></p>
		<p>Password: <input name="password"></p>
		<p><button>Invia</button></p>
	</form>

	<form method="post" action="./register.php">
		<p>Nome: <input name="first_name"></p>
		<p>Cognome: <input name="last_name"></p>
		<p>Email: <input name="email"></p>
		<p>Password: <input name="sign_up_password"></p>
		<p><button>Invia</button></p>
	</form>
</body>
</html>