<?php
class Calendar{

	private $year_month = null;
	private $timestamp = null;
	private $today = null;
	private $daysInMonth = null;
	private $numeroPrimoGiornoDelMese = null;

	/*costruttore*/
	public function __construct(){
		if(isset($_GET['ym'])){
			$this->year_month = $_GET['ym'];
		}else{
			$this->year_month = date('Y-m');// Anno e mese attuali
		}
		$this->timestamp = strtotime($this->year_month.'-01'); // timestamp relativo al primo giorno del mese attuale

		// controllo la validità del timestamp
		if($this->timestamp === false){
			$this->year_month = date('Y-m');
			$this->timestamp = strtotime($this->year_month.'-01');
		}

		$this->today = date('Y-m-j',time()); // Data corrente

		$this->daysInMonth = date('t',$this->timestamp); // numero di giorni presenti nel mese corrente
		$this->numeroPrimoGiornoDelMese = date('w',$this->timestamp);

		echo 'Year-Month: '.$this->year_month.',<br>today: '.$this->today.',<br>in questo mese ci sono '.$this->daysInMonth.' giorni,<br>il primo giorno del mese e\': '.$this->numeroPrimoGiornoDelMese.'<br>';

	}

	public function show(){
		$timestampPrimoGiornoMesePrecedente = mktime(0,0,0,(date('m',$this->timestamp)-1),1,(date('Y',$this->timestamp)));
		$timestampPrimoGiornoMeseSuccessivo = mktime(0,0,0,(date('m',$this->timestamp)+1),1,(date('Y',$this->timestamp)));

		$prev = date('Y-m', $timestampPrimoGiornoMesePrecedente);
		$next = date('Y-m', $timestampPrimoGiornoMeseSuccessivo);


		$weeks = array();
		$week = '';
	

		echo "<p><a href='?ym=$prev' >mese precedente</a></p>";
		echo '<p><a href=\'?ym='.$next.'\' >mese successivo</a></p>';

		echo '<table>';

		echo '<tr>
		<th>Mon</th>
		<th>Tue</th>
		<th>wed</th>
		<th>Thu</th>
		<th>Fri</th>
		<th>Sat</th>
		<th>Sun</th>
		</tr>';

		if($this->numeroPrimoGiornoDelMese == 0) // se il giorno è domenica, il suo numero è 0
			$this->numeroPrimoGiornoDelMese = 7; // allora assegno alla domenica il giorno 7

		$week = $week.str_repeat('<td></td>',$this->numeroPrimoGiornoDelMese - 1);
		$index = $this->numeroPrimoGiornoDelMese;
		for($day = 1; $day <= $this->daysInMonth; $day++, $index++){
			$date = $this->year_month.'-'.$day; // creo la data

			$week.= '<td><button>'.$day.'</button></td>';

			// Fine della settimana o fine del mese
			if(($index % 7) == 0 || $day == $this->daysInMonth){
				if($day == $this->daysInMonth){ 
					// arrivato alla fine del mese
					$resto = $index % 7;
					if($resto > 0){
						$restanti = 7 - $resto;
						$week.=str_repeat('<td></td>', $restanti);
					}
				}
				$weeks[] = '<tr>'.$week.'</tr>'; // Aggiungo la riga all'array di righe
				$week = ''; // Ripulisco la stringa $week per riempirla con una nuova riga
			}
		}

		foreach($weeks as $week){
			echo $week;
		}

		echo '</table>';
	}

}
?>