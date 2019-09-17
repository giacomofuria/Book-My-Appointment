<?php
	/* Class che permette di leggere e modificare gli appuntamenti di un utente */
	class Appointments{
		private $utente;
		private $datiAppuntamentiRicevuti; /* Array in cui ogni riga rappresenta un appuntamento */
		private $datiAppuntamentiPrenotati; /* Array in cui ogni elemento rappresenta un appuntamento prenotato dall'utente verso un altro utente */

		/* Costruttore della classe (specifica solo l'utente)*/
		public function Appointments($utente){
			$this->utente = $utente;
		}

		/* Preleva dal DB i $limit appuntamenti prenotati da $this->user 
		- se limit=0 restituisce tutti gli appuntamenti
        - new = true preleva solo gli appuntamenti non ancora effettuati, altrimenti li preleva tutti
        - order può essere ASC o DESC a seconda di come si vogliono ordinare i risultati (ASC preleva prima i più imminenti)
		 */
		public function getBookedAppointments($limit, $new, $order,$dataInizio=null,$dataFine=null){
			$this->datiAppuntamentiPrenotati = $this->getAppointments("to",$limit, $new, $order,$dataInizio,$dataFine);
			return $this->datiAppuntamentiPrenotati;
		}

		public function getReceivedAppointments($limit, $new, $order,$dataInizio=null,$dataFine=null){
			$this->datiAppuntamentiRicevuti = $this->getAppointments("from",$limit, $new, $order,$dataInizio,$dataFine);
			return $this->datiAppuntamentiRicevuti;
		}

		private function getAppointments($userType,$limit, $new, $order,$dataInizio=null,$dataFine=null){
			$selectedUser="";
			$joinUser="";
			if($userType == "to"){ // prelevo le prenotazioni effettuate dall'utente
				$selectedUser="A.idRichiedente";
				$joinUser = "A.idRicevente";
			}else{ // prelevo le prenotazioni ricevute dall'utente
				$selectedUser="A.idRicevente";
				$joinUser = "A.idRichiedente";
			}

			global $bookMyAppointmentDb;
			$limiter="";
			if($limit>0){
				$limiter="LIMIT ".$limit;
			}
			$period="";
			if($new){
				$dataOraAttuale = date('Y-m-d H:i:s',time());
				$period = "AND A.dataOra >= \"$dataOraAttuale\"";
			}
			$intervallo="";
			if($dataInizio != null && $dataFine != null){
				$intervallo = "AND A.dataOra>='".$dataInizio."' AND A.dataOra<'".$dataFine."' ";
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
						  FROM appuntamento A INNER JOIN USER U ON $joinUser=U.userId
						  WHERE $selectedUser = $this->utente $period
						  ORDER BY A.dataOra $order $limiter;";
			$result = $bookMyAppointmentDb->performQuery($queryText);
			$numRow = mysqli_num_rows($result);
			$bookMyAppointmentDb->closeConnection();
			if($numRow == 0){
				return false;
			}
			$appuntamenti = array();
			while($row = $result->fetch_assoc()){
				$timestampAppuntamento = $row['dataOra'];
				$appuntamenti["$timestampAppuntamento"] = $row;
			}
			return $appuntamenti;
		}
		/* Funzione che rimuove un appuntamento tra quelli prenotati */
		public function deleteAppointment($id){
			global $bookMyAppointmentDb;
			$queryText = "DELETE FROM appuntamento WHERE idAppuntamento=$id;";
			$result = $bookMyAppointmentDb->performQuery($queryText);
			$bookMyAppointmentDb->closeConnection();
			return $result;
		}
		/* verifica se alla dataOra passata come parametro è stato memorizzato un appuntamento */
		public function booked($dataOra){
			
			$time = strtotime($dataOra);
			$dataOraMysql = date('Y-m-d H:i:s',$time);
			return isset($this->datiAppuntamentiRicevuti["$dataOraMysql"]);
		}

		/* Funzione di debug che stampa chiave->valore tutti gli appuntamenti memorizzati nell'oggetto */
		public function stampa(){
			if($this->numeroAppuntamenti == 0)
				return;
			foreach ($this->datiAppuntamentiRicevuti as $key => $val) {
				echo "$key = ".$val['dataOra']."<br>";
			}
		}
	}
?>