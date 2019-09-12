<?php
	session_start();
	include "./util/sessionUtil.php";
	require_once "./util/BMADbManager.php";
	if(!isLogged()){
		header('Location: ./../index.php');
		exit;
	}
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<title>Cerca - Book My Appointment</title>
	<meta name = "author" content = "Giacomo Furia">
	<link rel="stylesheet" href="./../css/page.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/menu.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/calendar.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/search.css" type="text/css" media="screen">
</head>
<body>
	<div id="left-side">
		<?php
			include "./layout/menu.php";
		?>
	</div>
	<div id="right-side">
		<?php
			include "./layout/top_bar.php";
		?>
		<div id="workspace">
			<div class='results-container'>
				<?php
					if(isset($_GET['pattern']) && $_GET['pattern'] != ''){
						$pattern = $_GET['pattern'];
						$result = searchUsers($pattern);
						$numRow=0;
						if(!$result || $result===null){
							echo "Nessun risultato";
						}else{
							//echo $result;
							$numRow = mysqli_num_rows($result);
							echo "Hai cercato: <b>\"</b> $pattern <b>\"</b>, numero di risultati: <b>$numRow</b><br>";
							printSearchResult($result);
						}
						
					}else{
						echo "Non hai cercato niente";
					}
				?>
			</div>
			<div id="calendar-container">
				<?php 
					include './layout/calendar.php';
					$calendar = new Calendar();
					$calendar->show();
				?>
			</div>
			<div style='clear:both;'></div>
			<?php
				include "./layout/footer.php";
		    ?>
		</div> <!-- fine workspace -->
	</div>
	<script>
		// evidenzio il pulsante della pagina
		/*
		var btn = document.getElementById("home_button");
		btn.style.backgroundColor="#91DFAA";
		btn.style.color="#383838";
		*/
	</script>
</body>
</html>
<?php

	function searchUsers($pattern){
		global $bookMyAppointmentDb;
		$queryText = "SELECT * FROM USER WHERE first_name LIKE'%".$pattern."%' OR last_name LIKE '%".$pattern."%' OR profession LIKE '%".$pattern."%' ;";


		$result = $bookMyAppointmentDb->performQuery($queryText);
		if(!$result){
			return false;
		}
		$numRow = mysqli_num_rows($result);
		if($numRow == 0){
			return false;
		}
		$bookMyAppointmentDb->closeConnection();
		return $result;
	}
	function printSearchResult($result){
		while ($row = $result->fetch_assoc()){
			
			$id = $row['userId'];
			$email = $row['email'];
			$nome = $row['first_name'];
			$cognome = $row['last_name'];
			
			$profileImage = "./../img/icon/set1/man.png";
			
			
			$professione = $row['profession'];
			$address = $row['address'];
			$admin = $row['admin'];

			echo "<div class='row-container'>";
			echo "<img src='$profileImage' class='search-result-img' alt='icona profilo'>";
			echo "<p><a href='./profile.php?user=$id'>$nome $cognome $email</a></p>";
			echo "<p class='profession-paragraph'>$professione</p>";
			echo "</div>";

		}
	}
?>