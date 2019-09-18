<?php
	/* libreria di funzioni di utilità per l'amministrazione del sito */
	
	function deleteUserReview($id){
		global $bookMyAppointmentDb;
		$queryText = "DELETE FROM recensione WHERE idRecensione=$id";
		$result = $bookMyAppointmentDb->performQuery($queryText);
		$bookMyAppointmentDb->closeConnection();
		return $result;
	}
?>