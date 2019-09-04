<?php 
	include "./util/Appointments.php";
	class AppointmentTable{
		private $giorniSettimana =  array('Lun','Mar','Mer','Gio','Ven','Sab','Dom');
		private $mesiAnno = array('Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre');
		private $giorni;
		private $inizio;
		private $fine;
		private $durata;
		private $pause;

		private $dataCorrente;
		private $timestampCorrente;
		private $giornoDellaSettimana;
		private $timestampPrimoGiornoSettimana;
		private $dataPrimoGiornoSettimanaPrecedente;
		private $dataPrimoGiornoSettimanaSuccessiva;

		private $applyingUser;
		private $receiverUser;

		public function AppointmentTable($giorni, $inizio, $fine, $durata, $pause, $applyingUser, $receiverUser){
			$this->giorni = $giorni;
			$this->inizio = $inizio;
			$this->fine = $fine;
			$this->durata = $durata;
			$this->pause = $pause;

			$this->numeroAppuntamenti = getNumeroAppuntamenti($inizio,$fine,$durata);
			$this->inizioInSecondi =strtotime($inizio);

			$this->applyingUser = $applyingUser;
			$this->receiverUser = $receiverUser;

			//echo "Numero appuntamenti: ".$this->numeroAppuntamenti.'<br>'; // DEBUG
			//echo "Inizio in secondi: ".$this->inizioInSecondi.'<br>'; // DEBUG

			if(isset($_GET['week'])){
				$this->dataCorrente = $_GET['week'];
			}else{
				$this->dataCorrente = date('Y-m-j',time()); // Data corrente
			}

			$this->timestampCorrente = strtotime($this->dataCorrente);

			if($this->timestampCorrente === false){ // se i valori passati con GET non erano validi calcolo comunque la data corrente
				$this->dataCorrente = date('Y-m-j',time()); // Data corrente
				$this->timestampCorrente = strtotime($this->dataCorrente);
			}

			$this->giornoDellaSettimana = getNumeroGiornoSettimana(strtotime($this->dataCorrente));

			$this->timestampPrimoGiornoSettimana = ($this->timestampCorrente - ($this->giornoDellaSettimana*86400)); // Sottraggo al timestamp corrente (in secondi) - i secondi passati dal lunedì della settimana corrente

			$dataPrimoGiornoSettimana = date('Y-m-j', $this->timestampPrimoGiornoSettimana);

			$this->timestampPrimoGiornoSettimana = strtotime($dataPrimoGiornoSettimana.' '.$this->inizio); // timestamp completo del primo giorno della settimana comprende anche l'ra di inizio degli appuntamenti


			
			$dataPrimoGiornoSettimana = date('Y-m-j',$this->timestampPrimoGiornoSettimana);

			$timestampPrimoGiornoSettimanaSuccessiva = ($this->timestampPrimoGiornoSettimana + (7*86400));
			$this->dataPrimoGiornoSettimanaSuccessiva = date('Y-m-j',$timestampPrimoGiornoSettimanaSuccessiva);

			$timestampPrimoGiornoSettimanaPrecedente = ($this->timestampPrimoGiornoSettimana - (7*86400));
			$this->dataPrimoGiornoSettimanaPrecedente = date('Y-m-j',$timestampPrimoGiornoSettimanaPrecedente);
			/*
			echo "Data primo giorno settimana: $dataPrimoGiornoSettimana <br><br>";
			echo "Data primo giorno settimana successiva: $this->dataPrimoGiornoSettimanaSuccessiva <br><br>";
			echo "Data primo giorno settimana precedente: $this->dataPrimoGiornoSettimanaPrecedente <br><br>";
			
			// stampo per dubug tutte le date della settimana
			for($i=0; $i<7; $i++){
				$timestampGiorno = ($this->timestampPrimoGiornoSettimana + ($i*86400)); // 86400 = numero di secondi in un giorno
				$data = date('Y-m-j H:i:s',$timestampGiorno);
				echo "$data<br>";
			}
			*/
		}

		public function show(){	
			//inizio table header
			echo "<div class='appointment-table-container'>";
				$timestampPrimoAppuntamentoSettimana = $this->timestampPrimoGiornoSettimana;
				$timestampUltimoGiornoSettimana = ($timestampPrimoAppuntamentoSettimana + (6*86400));
				$numeroMeseInizio = date('n',$timestampPrimoAppuntamentoSettimana);
				$numeroMeseFine = date('n',$timestampUltimoGiornoSettimana);
				$tableTitle="Mese";
				if($numeroMeseInizio != $numeroMeseFine){
					$tableTitle = $this->mesiAnno[$numeroMeseInizio-1]." / ".$this->mesiAnno[$numeroMeseFine-1];
				}else{
					$tableTitle = $this->mesiAnno[$numeroMeseInizio-1];
				}
				echo "<div class='appointment-table-header'>";
					echo "<div id='left-arrow' class='table-header-components'><button class='table-header-buttons' onclick=window.location.href=\"?user=$this->receiverUser&week=$this->dataPrimoGiornoSettimanaPrecedente\"><img width='100%' src='./../img/icon/set1/left-arrow-1.png'></button></div>";
					echo "<div id='table-header-title' class='table-header-components'> <p >$tableTitle</p> </div>";
					echo "<div id='right-arrow' class='table-header-components'> <button class='table-header-buttons' onclick=window.location.href=\"?user=$this->receiverUser&week=$this->dataPrimoGiornoSettimanaSuccessiva\"><img width='100%' src='./../img/icon/set1/right-arrow-1.png'></button> </div>";
				echo "<div style='clear:both;'></div></div>";
				// fine table header

				$dataPrimoGiornoSettimana = date('Y-m-d G:i:s',$timestampPrimoAppuntamentoSettimana);
				$timestampPrimoGiornoSettimanaSuccessiva = ($this->timestampPrimoGiornoSettimana + (7*86400));
				$dataPrimoGiornoSettimanaSuccessiva = date('Y-m-d G:i:s',$timestampPrimoGiornoSettimanaSuccessiva);
				$appointmentList = new Appointments($this->receiverUser, $dataPrimoGiornoSettimana, $dataPrimoGiornoSettimanaSuccessiva);

				echo "<table class='appointment-table'>";
				echo "<tr>";
				echo "<th></th>";
				// Creo le colonne
				for($i=1; $i<8; $i++){
					$class = 'not-selected';
					$timestampGiorno = ($timestampPrimoAppuntamentoSettimana + (($i-1)*86400));
					$numeroGiornoDelMese = date('j',$timestampGiorno);
					if($this->findValue($this->giorni, $i)){
						$class='selected';
					}
					echo "<th class='".$class."''>".$this->giorniSettimana[$i-1]."<br>".$numeroGiornoDelMese."</th>";
				}
				echo "</tr>";


				// Creo tante righe quanti sono gli appuntamenti
				$start = $this->inizioInSecondi;

				$timestampPrimoAppuntamentoSettimana = $this->timestampPrimoGiornoSettimana;

				for($i=0; $i<$this->numeroAppuntamenti; $i++){
					$inizioIntervalloInSecondi = $start;
					$fineIntervalloInSecondi = ($start+(intval($this->durata)*60));
					$start+=(intval($this->durata)*60);

					$inizioLeggibile = date('H:i',$inizioIntervalloInSecondi);
					$fineLeggibile = date('H:i',$fineIntervalloInSecondi);

					echo "<tr>";
					// Primo elemento con gli orari
					echo "<td><p class='start-time'>".$inizioLeggibile."</p><p class='end-time'>".$fineLeggibile."</p></td>";
					// Restanti elementi della tabella
					for($j=1; $j<8; $j++){
						$tdClassname=null;
						$buttonClassname='';
						$button='';

						// Calcolo il timestamp del singolo appuntamento
						$timestampAppuntamento = ($timestampPrimoAppuntamentoSettimana + (($j-1)*86400));
						$dataOraAppuntamento = date('Y-m-j H:i',$timestampAppuntamento);

						// verifico se l'appuntamento è già stato prenotato
						$prenotato = $appointmentList->booked(date('Y-m-d G:i:s',$timestampAppuntamento));

						if($this->findValue($this->pause,$i)){
							$tdClassname='not-selected';
						}else{
							if($this->findValue($this->giorni,$j)){
								$tdClassname='selected';
								$dataAppuntamento = date('j-m-Y',$timestampAppuntamento);
								$oraAppuntamento = date('H:i',$timestampAppuntamento);
								$dataOraAppuntamento = date('j-m-Y H:i',$timestampAppuntamento);
								$imgBooked="<img class='booked-icon' src='./../img/icon/set1/calendar.png'>";
								if(!$prenotato){
									$button="<button class='appointment-button free' title='$dataOraAppuntamento disponibile' onclick='confirmAppointment(\"$dataAppuntamento\",\"$oraAppuntamento\",\"$this->applyingUser\",\"$this->receiverUser\",\"$this->durata\")'></button>";
								}else{
									// appuntamento prenotato
									if($this->applyingUser == $this->receiverUser){
										// l'utente loggato sta guardando la sua tabelle
										$button="<button class='appointment-button booked owner' title='$dataOraAppuntamento prenotato' onclick='console.log(\"tua\")'>$imgBooked</button>";
									}else{
										// Un utente sta guardando la tabella di un altro utente
										$button="<button class='appointment-button booked viewer' title='$dataOraAppuntamento prenotato' onclick='console.log(\"non tua\")'>$imgBooked</button>";
									}
								}
							}else{
								$tdClassname='not-selected';
							}
						}
						echo "<td class=$tdClassname>$button</td>";
						
					}
					$timestampPrimoAppuntamentoSettimana+=($this->durata*60);
					echo "</tr>";
				}
			echo "</table></div>";
		}

		/* Funzione che cerca il valore $value all'interno dell'array $vett */
		private function findValue($vett, $value){
			if($vett == null || !isset($vett))
				return false;
			foreach($vett as $elem){
				if($elem == $value)
					return true;
			}
			return false;
		}
		/* Effettua stampe di debug per vedere il contenuto delle variabili. */
		private function stampaDebug(){
			foreach($this->giorni as $giorno){
				echo $giorno.'<br>';
			}
			echo "Orario di apertura: ".$this->inizio.'<br>';
			echo 'Orario di chiusura: '.$this->fine.'<br>';
			echo 'Durata degli appuntamenti: '.$this->durata.'<br>';
			foreach($this->pause as $pausa){
				echo $pausa.'<br>';
			}
		}

		/* Funzione che dato un certo timestamp cerca se l'utente receiver ha un appuntamento prenotato con quel timestamp */
		private function cercaAppuntamenti($timestamp){

		}
	}
	function getNumeroAppuntamenti($inizio, $fine,$durata){
		if($inizio != null && $fine != null){ 
			$dataInizio = strtotime($inizio); // timestamp ora inizio (in secondi)
			$dataFine = strtotime($fine);     // timestamp ora fine (in secondi)
			$differenza = ($dataFine - $dataInizio)/60;
			if($differenza < 0){
				echo "Errore: inserisci correttamente gli orari di inizio e di fine<br>"; 
			}
			$numeroAppuntamenti = floor($differenza / $durata); 
			//echo "Date: ".$dataInizio." ".$dataFine.",durata: $differenza minuti, numeroAppuntamenti: $numeroAppuntamenti<br>"; // DEBUG
			return $numeroAppuntamenti;
		}else{
			return 0;
		}
	}
	function getNumeroGiornoSettimana($timestamp){
		$giornoInFormatoUS = date('w',$timestamp);
		if($giornoInFormatoUS == 0)
			return 6;
		else{
			if($giornoInFormatoUS >= 1 && $giornoInFormatoUS <= 6){
				return $giornoInFormatoUS - 1;
			}else{
				return null;
			}
		}
	}
?>