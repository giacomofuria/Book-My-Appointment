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
	
	if (!isset($_GET['search'])){
		echo json_encode($response);
		return;
	}		
	
	$pattern = $_GET['search'];
	// Qui chiamo la funzione che esegue la query sulla tabella USER
	$result = searchUsers($pattern);
	
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
			
			$id = $row['userId'];
			$email = $row['email'];
			$nome = $row['first_name'];
			$cognome = $row['last_name'];
			
			if($row['profile_image'] == null || !isset($row['profile_image'])){
				$profileImage = null;
			}else{
				$profileImage = base64_encode($row['profile_image']);
			}
			
			$professione = $row['profession'];
			$address = $row['address'];
			$admin = $row['admin'];
			$password = $row['password'];
			//$user = new User($id, $email, $nome, $cognome, $profileImage, $professione, $address, $admin);
			$user = new User($id, $email, $nome, $cognome, $password,$profileImage, $professione,$address,$admin);
			$response->data[$index] = $user;
			$index++;
		}
		
		return $response;
	}
	function setEmptyResponse(){
		$message = "Nessun risultato";
		return new AjaxResponse("-1", $message);
	}
	function searchUsers($pattern){
		global $bookMyAppointmentDb;
		$queryText = "SELECT * FROM USER WHERE first_name LIKE'%".$pattern."%' OR last_name LIKE '%".$pattern."%' OR profession LIKE '%".$pattern."%' ORDER BY first_name LIMIT 5;";
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
?>