<!DOCTYPE html>
<html lang="en">
<head>
	<title>My Calendar</title>
	<style type="text/css">
		table{
			/*
			border-style: solid;
			border-width: 1px;
			border-color: black;
			*/
		}
		th, td{
			border-style: solid;
			border-width: 1px;
			border-color: black;
			text-align: center;
			padding: 0.5em;
		}
		button{
			background-color: #FFFFFF;
			width: 100%;
			height: 100%;
			font-size: 18px;
			border-style: solid;
			border-width: 1px;
			border-color: black;
			cursor: pointer;
		}
		button:hover{
			background-color: #F3F7FD;
		}
	</style>
</head>
<body>

<?php
	if(isset($_GET['ym'])){
		$year_month = $_GET['ym'];
	}else{
		$year_month = date('Y-m');// Anno e mese attuali
	}
	$timestamp = strtotime($year_month.'-01'); // timestamp relativo al primo giorno del mese attuale

	// controllo la validità del timestamp
	if($timestamp === false){
		$year_month = date('Y-m');
		$timestamp = strtotime($year_month.'-01');
	}

	$today = date('Y-m-j',time()); // Data corrente

	$daysInMonth = date('t',$timestamp); // numero di giorni presenti nel mese corrente

	$numeroPrimoGiornoDelMese = date('w',$timestamp);

	$timestampPrimoGiornoMesePrecedente = mktime(0,0,0,(date('m',$timestamp)-1),1,(date('Y',$timestamp)));
	$timestampPrimoGiornoMeseSuccessivo = mktime(0,0,0,(date('m',$timestamp)+1),1,(date('Y',$timestamp)));

	$prev = date('Y-m', $timestampPrimoGiornoMesePrecedente);
	$next = date('Y-m', $timestampPrimoGiornoMeseSuccessivo);

	echo 'Year-Month: '.$year_month.',<br>today: '.$today.',<br>in questo mese ci sono '.$daysInMonth.' giorni,<br>il primo giorno del mese e\': '.$numeroPrimoGiornoDelMese.'<br>';

	$weeks = array();
	$week = '';

	// Inserisco i link al giorno precedente e a quello successivo

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

	if($numeroPrimoGiornoDelMese == 0)
		$numeroPrimoGiornoDelMese = 7;

	$week = $week.str_repeat('<td></td>',$numeroPrimoGiornoDelMese - 1);
	$index = $numeroPrimoGiornoDelMese;
	for($day = 1; $day <= $daysInMonth; $day++, $index++){
		$date = $year_month.'-'.$day; // creo la data

		$week.= '<td><button>'.$day.'<sub>'.$index.'</sub></button></td>';

		// Fine della settimana o fine del mese
		if(($index % 7) == 0 || $day == $daysInMonth){
			if($day == $daysInMonth){ 
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
?>

</body>
</html> 
