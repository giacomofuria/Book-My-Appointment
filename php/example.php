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