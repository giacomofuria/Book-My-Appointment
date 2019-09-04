<?php
	/* Class che permette di leggere e modificare gli appuntamenti di un utente */
	class Appointments{
		private $numeroAppuntamenti;
		private $utente;
		private $datiAppuntamenti; /* Array in cui ogni riga rappresenta un appuntamento */

		public function Appointments($utente, $dataInizio, $dataFine){
			$this->utente = $utente;
			global $bookMyAppointmentDb;
			//echo "dataInizio: $dataInizio, dataFine: $dataFine<br>"; // DEBUG
			$queryText = "SELECT * FROM appuntamento WHERE idRicevente='".$this->utente."' AND dataOra>='".$dataInizio."' AND dataOra<'".$dataFine."';";
			$result = $bookMyAppointmentDb->performQuery($queryText);
			$numRow = mysqli_num_rows($result);
			$this->numeroAppuntamenti = $numRow;
			$bookMyAppointmentDb->closeConnection();

			while($row = $result->fetch_assoc()){
				//echo $row['idAppuntamento']." ".$row['dataOra']."<br>";
				$timestampAppuntamento = $row['dataOra'];
				$this->datiAppuntamenti["$timestampAppuntamento"] = $row;
			}

			
		}
		/* verifica se alla dataOra passata come parametro è stato memorizzato un appuntamento */
		public function booked($dataOra){
			$time = strtotime($dataOra);
			$dataOraMysql = date('Y-m-d G:i:s',$time);
			return isset($this->datiAppuntamenti["$dataOraMysql"]);
		}
		public function stampa(){
			foreach ($this->datiAppuntamenti as $key => $val) {
				echo "$key = ".$val['dataOra']."<br>";}
			}
	}
?>