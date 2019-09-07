<!-- sezione menu -->
<?php
	require_once "./util/BMADbManager.php";// includo la classe per la gestione del database
	// se è stato caricato significa che la sessione è stata già verificata
	// quindi posso caricare l'immagine di login
	function getProfileImage($utente){
		global $bookMyAppointmentDb;
		$queryText = "SELECT profile_image FROM USER WHERE userId=$utente;";
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		$bookMyAppointmentDb->closeConnection();
		$userRow = $result->fetch_assoc();

		$img = $userRow['profile_image'];
		$src = null;
		if($img == null){
			$src = "./../img/icon/set1/man.png";
			//return false;
		}else{
			$imgProfilo = base64_encode($img);
			$src="data:image/jpeg;base64,$imgProfilo";
		}
		return $src;
	}
	
	$utente = $_SESSION['userId'];
	$immagineProfilo = getProfileImage($utente);
	$src = $immagineProfilo;
?>
<div  class="side-menu-box" onclick='location.href="./profile.php"'>
	<img id="profile-img" src="<?php echo $src;?>" style="width: 100%;">
</div>
<div class="side-menu-box">
	<button id="home_button" class="menu_buttons" onclick="window.location.href='./home.php'">			Home 		</button>
	<button id="profile_button" class="menu_buttons" onclick="window.location.href='./profile.php'">	Profilo     </button>
	<button id="settings_button" class="menu_buttons" onclick="window.location.href='./settings.php'">	Impostazioni    </button>
	<button id="signout_button" class="menu_buttons" onclick="window.location.href='./logout.php'">		Disconnetti     </button>
</div>
<!-- Fine sezione menu -->