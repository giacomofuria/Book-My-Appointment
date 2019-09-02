<?php 
	class AppointmentTable{
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
		}
		public function show(){	
			//$this->stampaDebug();
			echo "<div class='appointment-table-container'>";
			echo "<div class='appointment-table-header'>";
			echo "table header";
			echo "<table class='appointment-table'>";
			echo "<tr><th> </th><th>Lun</th><th>Mar</th></tr>";
			echo "</table>";
			echo "</div>";
			echo "</div>";
		}
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
?>