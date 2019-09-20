<?php
	session_start();
	require_once "./../util/BMADbManager.php";
	include "./AjaxResponse.php";
	$response = new AjaxResponse();
	if (!isset($_GET['newNotificationsOf'])){
		echo json_encode($response);
		return;
	}		
	$user = $_GET['newNotificationsOf'];

	// Qui chiamo la funzione che esegue la query sulla tabella USER
	$result = getNotifications($user);
	if (!$result || $result==null){
		$response = setEmptyResponse();
		echo json_encode($response);
		return;
	}
	$message = "OK";	
	$response = setResponse($result, $message);
	echo json_encode($response);
	return;
	
	/* Funzione che setta la risposta (oggetto di classe Ajax Response) */
	function setResponse($result, $message){
		$response = new AjaxResponse("0", $message);
			
		$index = 0;
		while ($row = $result->fetch_assoc()){
			
			$idDestinatario = $row['idDestinatario'];
			$testo= $row['testo'];
			$letta = $row['letta'];

			$notifica = new Notify($idDestinatario, $testo, $letta);
			$response->data[$index] = $notifica;
			$index++;
		}
		
		return $response;
	}
	/* Setta una risposta vuota */
	function setEmptyResponse(){
		$message = "Nessun risultato";
		return new AjaxResponse("-1", $message);
	}

	/* funzione che preleva le recensioni */
	function getNotifications($utente){
		global $bookMyAppointmentDb;
		$queryText = "SELECT * FROM notifica WHERE idDestinatario=$utente ORDER BY idNotifica DESC LIMIT 6;";
		//echo $queryText."<br>"; // DEBUG
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);

		$queryText = "UPDATE notifica set letta=1 WHERE idDestinatario=$utente AND letta=0;";
		$resultUpdate = $bookMyAppointmentDb->performQuery($queryText);

		$bookMyAppointmentDb->closeConnection();
		return $result;

	}

?>
