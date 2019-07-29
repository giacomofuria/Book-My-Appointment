<?php
	session_start();
	echo 'Benvenuto: '.$_SESSION['email'].', userId: '.$_SESSION['userId'];
?>