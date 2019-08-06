<!DOCTYPE html>
<html lang="en">
<head>
	<title>My Calendar</title>
	<style type="text/css">
		table{
			border-style: solid;
			border-width: 1px;
			border-color: black;
		}
		th, td{
			border-style: solid;
			border-width: 1px;
			border-color: black;
			text-align: center;
			padding: 1em;
		}
	</style>
</head>
<body>

<?php
	$year_month = date('Y-m');// Anno e mese attuali

	$timestamp = strtotime($year_month.'-01'); // timestamp relativo al primo giorno del mese attuale

	$today = date('Y-m-j',time()); // Data corrente

	$daysInMonth = date('t',$timestamp); // numero di giorni presenti nel mese corrente

	$numeroPrimoGiornoDelMese = date('w',$timestamp);

	echo 'Year-Month: '.$year_month.',<br>today: '.$today.',<br>in questo mese ci sono '.$daysInMonth.' giorni,<br>il primo giorno del mese e\': '.$numeroPrimoGiornoDelMese.'<br>';

	$weeks = array();
	$week = '';

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

	$week = $week.str_repeat('<td></td>',$numeroPrimoGiornoDelMese - 1);
	$index = $numeroPrimoGiornoDelMese;
	for($day = 1; $day <= $daysInMonth; $day++, $index++){
		$date = $year_month.'-'.$day; // creo la data

		$week.= '<td>'.$day.'</td>';

		// Fine della settimana o fine del mese
		if(($index % 7) == 0 || $day == $daysInMonth){
			if($day == $daysInMonth){ 
				// arrivato alla fine del mese
				$week.=str_repeat('<td></td>', 7 - ($index % 7));
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
