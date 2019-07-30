<?php
	$firstName = $_POST['first_name'];
	$lastName = $_POST['last_name'];
	$email = $_POST['email'];
	$signUpPassword = $_POST['sign_up_password'];

	echo "First name: $firstName, Last name: $lastName, Email: $email, Password: $signUpPassword";
?>