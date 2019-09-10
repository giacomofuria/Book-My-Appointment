<?php
	session_start();
	/*
	require_once __DIR__ . "/../config.php";
	require_once DIR_UTIL . "movieManagerDb.php";
	require_once DIR_AJAX_UTIL . "AjaxResponse.php";
	*/
	require_once "./../util/BMADbManager.php";
	include "./AjaxResponse.php";

	$response = new AjaxResponse();
	
	if (!isset($_GET['reviewsReceiver'])){
		echo json_encode($response);
		return;
	}		
	
	$user = $_GET['reviewsReceiver'];
	// Qui chiamo la funzione che esegue la query sulla tabella USER
	$result = getReviews($user);
	
	if (!$result || $result==null){
		$response = setEmptyResponse();
		echo json_encode($response);
		return;
	}
	
	$message = "OK";	
	$response = setResponse($result, $message);
	echo json_encode($response);
	return;
	
	function setResponse($result, $message){
		$response = new AjaxResponse("0", $message);
			
		$index = 0;
		while ($row = $result->fetch_assoc()){
			
			$idRecensione = $row['idRecensione'];
			$nome_recensore = $row['nome_recensore'];
			$cognome_recensore = $row['cognome_recensore'];
			$punteggio = $row['punteggio'];

			
			$testo_recensione = $row['testo_recensione'];
			$dataOra = $row['dataOra'];

			//$user = new User($id, $email, $nome, $cognome, $profileImage, $professione, $address, $admin);
			$recensione = new Review($idRecensione,$nome_recensore,$cognome_recensore,$punteggio,$testo_recensione,$dataOra);
			$response->data[$index] = $recensione;
			$index++;
		}
		
		return $response;
	}
	function setEmptyResponse(){
		$message = "Nessun risultato";
		return new AjaxResponse("-1", $message);
	}
	/* funzione che preleva le recensioni */
	function getReviews($utente){
		global $bookMyAppointmentDb;
		$queryText = "SELECT R.idRecensione AS idRecensione,
							 U2.first_name AS nome_recensore,
							 U2.last_name AS cognome_recensore, 
							 
							 R.punteggio AS punteggio, 
							 R.testoRecensione AS testo_recensione,
							 R.dataOra AS dataOra
						FROM user U INNER JOIN recensione R INNER JOIN  user U2 ON U.userId=R.idRicevente AND R.idRecensore=U2.userId
						WHERE U.userId=$utente ORDER BY dataOra DESC;";
		//echo $queryText."<br>"; // DEBUG
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$numRow = mysqli_num_rows($result);
		$bookMyAppointmentDb->closeConnection();
		return $result;

	}

?>
