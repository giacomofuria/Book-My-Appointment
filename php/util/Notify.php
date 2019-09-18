<?php
	include_once __DIR__."/../config.php";
	include_once DIR_UTIL."BMADbManager.php";
	class Notify{
		public $idDestinatario;
		public $testo="";
		public $letta = 0;

		public function Notify($idDestinatario=null, $testo=""){
			$this->idDestinatario = $idDestinatario;
			$this->testo = $testo;
		}

		public function send(){
			global $bookMyAppointmentDb;
			$queryText = "INSERT INTO 
						  notifica (idDestinatario, testo, letta) 
						  VALUES ($this->idDestinatario,'$this->testo',$this->letta)";
			echo $queryText.'<br>';
			$result = $bookMyAppointmentDb->performQuery($queryText);
			$bookMyAppointmentDb->closeConnection();
			return $result;
		}
	}
?>