<?php
	include_once __DIR__."/../config.php";
	include_once DIR_UTIL."BMADbManager.php";
	class Notify{
		public $idDestinatario;
		public $testo="";
		public $letta = 0;

		public function Notify($idDestinatario=null, $testo="",$letta=0){
			$this->idDestinatario = $idDestinatario;
			$this->testo = $testo;
			$this->letta = $letta;
		}

		public function send(){
			global $bookMyAppointmentDb;
			$this->idDestinatario = $bookMyAppointmentDb->sqlInjectionFilter($this->idDestinatario);
			$this->testo = $bookMyAppointmentDb->sqlInjectionFilter($this->testo);
			$this->letta = $bookMyAppointmentDb->sqlInjectionFilter($this->letta);
			$queryText = "INSERT INTO 
						  notifica (idDestinatario, testo, letta) 
						  VALUES ($this->idDestinatario,'$this->testo',$this->letta)";
			//echo $queryText.'<br>';
			$result = $bookMyAppointmentDb->performQuery($queryText);
			$bookMyAppointmentDb->closeConnection();
			return $result;
		}
	}
?>