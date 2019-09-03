<?php 
	class AppointmentTable{
		private $giorniSettimana =  array('Lun','Mar','Mer','Gio','Ven','Sab','Dom');
		private $giorni;
		private $inizio;
		private $fine;
		private $durata;
		private $pause;

		public function AppointmentTable($giorni, $inizio, $fine, $durata, $pause){
			$this->giorni = $giorni;
			$this->inizio = $inizio;
			$this->fine = $fine;
			$this->durata = $durata;
			$this->pause = $pause;

			$this->numeroAppuntamenti = getNumeroAppuntamenti($inizio,$fine,$durata);
			$this->inizioInSecondi =strtotime($inizio);

			//echo "Numero appuntamenti: ".$this->numeroAppuntamenti.'<br>'; // DEBUG
			//echo "Inizio in secondi: ".$this->inizioInSecondi.'<br>'; // DEBUG

		}

		public function show(){	
			//inizio table header
			echo "<div class='appointment-table-container'>";

				echo "<div class='appointment-table-header'>";
					echo "<div id='left-arrow' class='table-header-components'><button class='table-header-buttons'><img width='100%' src='./../img/icon/set1/left-arrow-1.png'></button></div>";
					echo "<div id='table-header-title' class='table-header-components'> <p > Mese / Settimana</p> </div>";
					echo "<div id='right-arrow' class='table-header-components'> <button class='table-header-buttons'><img width='100%' src='./../img/icon/set1/right-arrow-1.png'></button> </div>";
				echo "<div style='clear:both;''></div></div>";
				// fine table header
				echo "<table class='appointment-table'>";
				echo "<tr>";
				echo "<th></th>";
				// Creo le colonne
				for($i=1; $i<8; $i++){
					$class = 'not-selected';
					if($this->findValue($this->giorni, $i)){
						$class='selected';
					}
					echo "<th class='".$class."''>".$this->giorniSettimana[$i-1]."</th>";
				}
				echo "</tr>";
				// Creo le righe tante righe quanti sono gli appuntamenti
				$start = $this->inizioInSecondi;
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
						$classname=null;
						if($this->findValue($this->pause,$i)){
							$classname='not-selected';
						}else{
							if($this->findValue($this->giorni,$j)){
								$classname='selected';
							}else{
								$classname='not-selected';
							}
						}
						echo "<td class=$classname><button>giorno</button></td>";
						
					}
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
	}
	function getNumeroAppuntamenti($inizio, $fine,$durata){
		if($inizio != null && $fine != null){
			$dataInizio = strtotime($inizio); // timestamp ora inizio (in secondi)
			$dataFine = strtotime($fine); // timestamp ora fine (in secondi)
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
?>