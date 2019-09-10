<?php
	session_start();
	include "./util/sessionUtil.php";
	require_once "./util/BMADbManager.php";
	if(!isLogged()){
		header('Location: ./../index.php');
		exit;
	}
	if(isset($_GET['delAppointment'])){
		$id = $_GET['delAppointment'];
		$esitoCancellazione = deleteAppointment($id);
	}
	/* Restitusce i $limit appuntamenti prenotati da $user (se limit=0 restituisce tutti gli appuntamenti) */
	function getMyAppointments($user, $limit){
		global $bookMyAppointmentDb;
		$limiter="";
		if($limit>0){
			$limiter="LIMIT ".$limit;
		}
		//$dataOraAttuale = date('Y-m-d H:i:s',time());
		$queryText = "SELECT A.idAppuntamento AS idAppuntamento,
							 A.dataOra AS dataOra, 
		                     A.idRicevente AS id, 
		                     U.first_name AS nome, 
		                     U.last_name AS cognome, 
		                     U.email AS email, 
		                     U.profile_image AS profileImage, 
		                     A.note AS note, 
		                     U.profession AS professione, 
		                     U.address AS indirizzo
					  FROM appuntamento A INNER JOIN USER U ON A.idRicevente=U.userId
					  WHERE A.idRichiedente  = $user
					  ORDER BY A.dataOra DESC $limiter;";
		//echo $queryText."<br>"; // DEBUG
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		$bookMyAppointmentDb->closeConnection();
		if($numRow == 0){
			return false;
		}
		$appuntamenti = array();
		while($row = $result->fetch_assoc()){
			$appuntamenti[] = $row;
		}
		return $appuntamenti;
	}
	function getMyClientAppointments($user, $limit){
		global $bookMyAppointmentDb;
		$limiter="";
		if($limit>0){
			$limiter="LIMIT ".$limit;
		}
		
		$queryText = "SELECT A.idAppuntamento AS idAppuntamento,
							 A.dataOra AS dataOra, 
		                     A.idRichiedente AS id, 
		                     U.first_name AS nome, 
		                     U.last_name AS cognome, 
		                     U.email AS email, 
		                     U.profile_image AS profileImage, 
		                     A.note AS note, 
		                     U.profession AS professione, 
		                     U.address AS indirizzo
					  FROM appuntamento A INNER JOIN USER U ON A.idRichiedente=U.userId
					  WHERE A.idRicevente  = $user 
					  ORDER BY A.dataOra DESC $limiter;";
		//echo $queryText."<br>"; // DEBUG
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		$bookMyAppointmentDb->closeConnection();
		if($numRow == 0){
			return false;
		}
		$appuntamenti = array();
		while($row = $result->fetch_assoc()){
			$appuntamenti[] = $row;
		}
		return $appuntamenti;
	}
	function deleteAppointment($id){
		global $bookMyAppointmentDb;
		$queryText = "DELETE FROM appuntamento WHERE idAppuntamento=$id;";
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$bookMyAppointmentDb->closeConnection();
		return $result;
	}
	function stampaAppuntamenti($appuntamenti){
		foreach($appuntamenti as $appuntamento){
			echo "<div class='appointment-container'>";
			$src = "./../img/icon/set1/man.png";
			if($appuntamento['profileImage'] != null){
				$img = base64_encode($appuntamento['profileImage']);
				$src = "data:image/jpeg;base64,$img";
			}
			$time = strtotime($appuntamento['dataOra']);
			$data = date('d-m-Y',$time);
			$ora = date('H:i',$time);
			$dataOraAttuale = date('Y-m-d H:i:s',time());
			$idAppuntamento = $appuntamento['idAppuntamento'];
			echo "<div class='appointment-element appointment-element-img'><img src=$src class='img-ricevente'></div>";
			echo "<div class='appointment-element'><p>".$data."</p><p>".$ora."</p></div>";
			echo "<div class='appointment-element appointment-element-info'><p><b><a href='./profile.php?user=".$appuntamento['id']."'>".$appuntamento['nome']." ".$appuntamento['cognome']."</a></b></p>";
			echo "<p>".$appuntamento['professione']."</p>";
			echo "<p><a href='mailto:".$appuntamento['email']."''><img src='./../img/icon/set1/envelope.png' class='icon-email'></a></p></div>";
			echo "<div class='appointment-element appointment-element-position'><p><b>Dove</b></p><p>".$appuntamento['indirizzo']."</p></div>";
			echo "<div class='appointment-element appointment-element-notes'><p><b>Note</b></p><p>".$appuntamento['note']."</p></div>";
			if($appuntamento['dataOra']<$dataOraAttuale){
				echo "<div class='appointment-element appointment-element-img'><img src='./../img/icon/set1/correct.png' class='delete-icon'></div>";
			}else{
				echo "<div class='appointment-element appointment-element-img'><button onclick=location.href='./home.php?delAppointment=$idAppuntamento'><img src='./../img/icon/set1/garbage.png' class='delete-icon'></button></div>";
			}
			echo "<div style='clear:both;'></div>";
			//echo ." ".$appuntamento['emailRicevente']." ".$appuntamento['nomeRicevente']."<br>";
			echo "</div>";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Appuntamenti clienti - Book My Appointment</title>
	<meta name = "author" content = "Giacomo Furia">
	<link rel="stylesheet" href="./../css/page.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/menu.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/home.css" type="text/css" media="screen">
	<link rel="stylesheet" href="./../css/calendar.css" type="text/css" media="screen">
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
			<div id="appointments-viewer">
				<div id="my-appointments">
					<div class="appointment-header">
						<h3>Gli appuntamenti dei tuoi clienti</h3>
					</div>
					<?php
						$appuntamenti = getMyClientAppointments($_SESSION['userId'],0);
						if(!$appuntamenti){
							echo "<div class='appointment-container'><p>Non hai appuntamenti</div></p>";
						}else{
							stampaAppuntamenti($appuntamenti);
							
						}
					?>
				</div>
			</div>
			<div id="calendar-container">
				<?php 
					include './layout/calendar.php';
					$calendar = new Calendar();
					$calendar->show();
				?>
			</div>
			<div style="clear:both;"></div>
		</div> <!-- fine workspace -->
		<?php
			include "./layout/footer.php";
		?>
	</div>
	<script type="text/javascript">
		// evidenzio il pulsante della pagina
		var btn = document.getElementById("home_button");
		btn.style.backgroundColor="#91DFAA";
		btn.style.color="#383838";

	</script>
</body>
</html>