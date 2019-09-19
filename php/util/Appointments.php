<?php
	include_once __DIR__."/../config.php";
	include_once DIR_UTIL."BMADbManager.php";
	include_once DIR_UTIL."User.php";
	include_once DIR_UTIL."Notify.php";
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
		/*
		 userType = "to" => Prenotazioni effettuate dall'utente
		 userType = "from" => Prenotazioni ricevute dall'utente
		*/
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
			                     A.idRichiedente AS idRichiedente, 
			                     A.idRicevente AS idRicevente,
			                     U.first_name AS nome, 
			                     U.last_name AS cognome, 
			                     U.email AS email, 
			                     U.profile_image AS profileImage, 
			                     A.note AS note, 
			                     U.profession AS professione, 
			                     U.address AS indirizzo
						  FROM appuntamento A INNER JOIN USER U ON $joinUser=U.userId
						  WHERE $selectedUser = $this->utente $period $intervallo
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
			/* Verifico che l'appuntamento che si vuole eliminare coinvolga l'utente loggato */
			$flag=false;
			$idAltroUtente=null;
			$dataOra = null;
			$this->getBookedAppointments(0,false,"ASC");
			$this->getReceivedAppointments(0,false,"ASC");
			foreach($this->datiAppuntamentiRicevuti as $app){
				if($app['idAppuntamento']==$id){
					$flag = true;
					$idAltroUtente = $app['idRichiedente'];
					$dataOra = $app['dataOra'];
					break;
				}
			}
			if(!$flag){
				foreach($this->datiAppuntamentiPrenotati as $app){
					if($app['idAppuntamento']==$id){
						$flag = true;
						$idAltroUtente = $app['idRicevente'];
						$dataOra = $app['dataOra'];
						break;
					}
				}
			}
			if(!$flag){
				return false;
			}
			global $bookMyAppointmentDb;
			$queryText = "DELETE FROM appuntamento WHERE idAppuntamento=$id;";
			$result = $bookMyAppointmentDb->performQuery($queryText);
			$bookMyAppointmentDb->closeConnection();
			/*
			$destinatario = new User();
			$destinatario->getUserInfo($idAltroUtente);
			*/
			$mittente = new User();
			$mittente->getUserInfo($this->utente);

			echo "Altro: $idAltroUtente<br>";


			$testoNotifica = "$mittente->firstName $mittente->lastName ha cancellato l\'appuntamento del $dataOra";
			echo "notifica: $testoNotifica<br>";
			$notifica = new Notify($idAltroUtente, $testoNotifica);
			$notifica->send();

			return $result;
		}
		/* verifica se alla dataOra passata come parametro è stato memorizzato un appuntamento */
		public function booked($dataOra){
			
			$time = strtotime($dataOra);
			$dataOraMysql = date('Y-m-d H:i:s',$time);
			return isset($this->datiAppuntamentiRicevuti["$dataOraMysql"]);
		}

		/* Fuzione che costruisce graficamente la lista degli appuntamenti */
		public function stampaAppuntamenti($type,$page){
			$userType="";
			if($type=="to"){
				$appuntamenti = $this->datiAppuntamentiPrenotati;
				$userType = "idRicevente";
			}else{
				if($type=="from"){
					$appuntamenti = $this->datiAppuntamentiRicevuti;
					$userType = "idRichiedente";
				}else{
					return;
				}
			}
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
				echo "<div class='appointment-element appointment-element-img'><img src=$src class='img-ricevente' alt='img profilo'></div>";
				echo "<div class='appointment-element'><p>".$data."</p><p>".$ora."</p></div>";
				echo "<div class='appointment-element appointment-element-info'><p><b><a href='./profile.php?user=".$appuntamento[$userType]."'>".$appuntamento['nome']." ".$appuntamento['cognome']."</a></b></p>";
				echo "<p>".$appuntamento['professione']."</p>";
				echo "<p><a href='mailto:".$appuntamento['email']."'><img src='./../img/icon/set1/envelope.png' class='icon-email' alt='email'></a></p></div>";
				echo "<div class='appointment-element appointment-element-position'><p><b>Dove</b></p><p>".$appuntamento['indirizzo']."</p></div>";
				echo "<div class='appointment-element appointment-element-notes'><p><b>Note</b></p><p>".$appuntamento['note']."</p></div>";
				if($appuntamento['dataOra']<$dataOraAttuale){
					echo "<div class='appointment-element appointment-element-img'><img src='./../img/icon/set1/correct.png' class='delete-icon' alt='passato' title='Appuntamento effettuato'></div>";
				}else{
					echo "<div class='appointment-element appointment-element-img'><button onclick=\"location.href='./$page?delAppointment=$idAppuntamento'\" title='Cancella appuntamento'><img src='./../img/icon/set1/garbage.png' class='delete-icon' alt='rimuovi appuntamento'></button></div>";
				}
				echo "<div style='clear:both;'></div>";
				echo "</div>";
			}
		}
		public function getNumberOfBookedAppointments(){
			return $this->getNumberOfAppointments("booked");
		}
		public function getNumberOfReceivedAppointments(){
			return $this->getNumberOfAppointments("received");
		}
		/* Funzione che mi restituisce il numero di appuntamenti presenti */
		private function getNumberOfAppointments($type){
			$numeroAppuntamenti = 0;
			if($type=="booked"){
				if($this->datiAppuntamentiPrenotati && $this->datiAppuntamentiPrenotati!= null){
					$numeroAppuntamenti = count($this->datiAppuntamentiPrenotati);	
				}
			}else if($type == "received"){
				if($this->datiAppuntamentiRicevuti && $this->datiAppuntamentiRicevuti != null){
					$numeroAppuntamenti = count($this->datiAppuntamentiRicevuti);	
				}
			}
			return $numeroAppuntamenti;
		}
		
		/* Funzione di debug che stampa chiave->valore tutti gli appuntamenti memorizzati nell'oggetto */
		public function stampa(){
			if($this->datiAppuntamentiRicevuti  == null)
				return;
			foreach ($this->datiAppuntamentiRicevuti as $key => $val) {
				echo "$key = ".$val['dataOra']."<br>";
			}
		}
	}
?>