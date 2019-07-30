<?php
	session_start();
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Home Page - Book My Appointment</title>
</head>
<body>
	<?php echo '<u>Benvenuto:</u> '.$_SESSION['email'].', <u>userId:</u> '.$_SESSION['userId']; ?>
	<span><p><a href="./logout.php">Sign out</a></p><span>
</body>
</html>