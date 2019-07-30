<?php
	session_start();
	echo '<u>Benvenuto:</u> '.$_SESSION['email'].', <u>userId:</u> '.$_SESSION['userId'];
?>