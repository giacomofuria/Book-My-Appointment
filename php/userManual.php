<?php
	session_start();
	include "./util/sessionUtil.php";
	require_once "./util/BMADbManager.php";
	
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<title>Manuale utente - Book My Appointment</title>
	<meta name = "author" content = "Giacomo Furia">
	<link rel="stylesheet" href="./../css/page.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/menu.css" type="text/css" media="screen">
	
	<link rel="stylesheet" href="./../css/calendar.css" type="text/css" media="screen">
</head>
<body>
	
	<?php
		if(!isLogged()){
			//header('Location: ./../index.php');
			//exit;
			echo "<p><a href='../index.php' class='user-manual-link'>Torna al login</a></p>";
			$path = "../";
			include "./layout/manual.php";
		}else{
			echo "<div id='left-side'>";
			include './layout/menu.php';
			echo "</div>";

			echo "<div id='right-side'>";
			include './layout/top_bar.php';

			echo "<div id='workspace'>";
			$path = "../";
			include "./layout/manual.php";
			echo "</div>"; // fine workspace
			include "./layout/footer.php";
			echo "</div>";
		}
	?>
	

</body>
</html>
