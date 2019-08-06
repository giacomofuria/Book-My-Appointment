<?php
	/* Classe php che permette di creare una tabella html con un calendario */

	if(isset($_GET['ym']))
		$ym = $_GET['ym']; // prende anno e mese passati tramite get
	else
		$ym = date('Y-m'); // Prende anno e mese attuali

	echo $ym.'<br>';

	// controllo il formato
	$timestamp = strtotime($ym.'-01');
	if($timestamp === false){
		/* Se in $ym non c'è una data nel formato 'Y-m' allora
		   assegno ad $ym la data attuale 
		 */
		$ym = date('Y-m');
		$timestamp = strtotime($ym.'-01');
	}
	echo 'Timestamp: '.$timestamp.'<br>';
	$today = date('Y-m-j', time());
	echo 'Today: '.$today.'<br>';

	$htmlTitle = date('Y / m',$timestamp);
	echo '<h3>'.$htmlTitle.'</h3>';

	/* Creo dei link al mese precedente e a quello successivo 
	   la funzione mktime ha la sintassi:
	    mktime(hour, minute, second, month, day, year);
	*/
	$timestampMesePrecedente = mktime(0,0,0,date('m',$timestamp)-1, 1, date('Y',$timestamp)); // timestamp del primo giorno del mese precedente
	$timestampMeseSuccessivo = mktime(0,0,0,date('m',$timestamp)+1, 1, date('Y',$timestamp)); // timestamp del primo giorno del mese successivo

	$prev = date('Y-m',$timestampMesePrecedente);
	$next = date('Y-m',$timestampMeseSuccessivo);

	echo 'Mese precedente: '.$prev.' , mese successivo: '.$next.'<br>';

	$day_count = date('t',$timestamp); // numero di giorni del mese presente nel timestamp

?>