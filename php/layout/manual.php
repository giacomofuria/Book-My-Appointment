<link rel="stylesheet" href="./<?php echo $path;?>css/manual.css" type="text/css" media="screen">
<div class="doc">
	<div class="doc-header">
		<h1 id="right-side-header">Book my appointments</h1>
		<p class='claim'>Questo sito è rivolto a tutti gli utenti (clienti o liberi professionisti) che vogliono gestire
online il calendario dei propri appuntamenti. Gli utenti, dopo essersi registrati possono cercare 
un altro utente, guardare sul suo calendario degli appuntamenti e 
prenotare un appuntamento disponibile nel giorno e nell'orario che preferiscono.</p>
	</div>
	<div id="doc_container">
		
	<div>
		<img id="pic_registrazione" class="right" src="./<?php echo $path;?>img/screenshot/registrazione.png" alt="screenshot registrazione">
		<h3>Registrazione</h3>
		<p>Per registrarsi, l'utente deve cliccare sul tasto "Registrati" e fornire: nome, cognome, indirizzo email e password. 
			Non è possibile che utenti diversi si registrino con lo stesso indirizzo email. Dopo la registrazione l'utente può accedere 
			al sito utilizzando il suo indirizzo di posta elettronica e la sua password. </p>
		<div class="clear"></div>
	</div>
	<div>
		<br>
		<img id="pic_menu" class="left" src="./<?php echo $path;?>img/screenshot/menu.png" alt="screenshot menu">
		<h3>Organizzazione</h3>
		
		<p>Tutte le pagine del sito sono formate da un menu laterale in cui sono presenti l'immagine del profilo dell'utente e dei link 
			
			che permettono di navigare tra le pagine e da una barra di ricerca in cui l'utente può cercare gli altri utenti sia per 
			nome che per professione.</p>
		<h3>Ricerca</h3>

		<p>Mediante la barra di ricerca è possibile ricercare altri utenti tramite il loro nome, cognome o professione. I risultati più attinenti 
			vengono mostrati nello spazio sottostante. <img id="pic_barra_ricerca" src="./<?php echo $path;?>img/screenshot/barra_ricerca.png" alt="screenshot barra ricerca">Se l'utente non è soddisfatto di quei risultati può effettuare una ricerca più approfondita 
			premendo il tasto invio della tastiera o cliccando sull'icona con la lente d'ingrandimento accanto alla barra di ricerca.</p>
		<div class="clear"></div>
	</div>
	<div>
		<img id="pic_profilo" class="right" src="./<?php echo $path;?>img/screenshot/profilo.gif" alt="gif profilo">
		<br><br>
		<h3>Profilo</h3>

		<p>Ogni utente dopo la registrazione ha un profilo pubblico, visibile anche dagli altri utenti, in cui vengono riportati i propri dati e la propria 
			tabella degli appuntamenti. Può modificare i dati in qualunque momento cliccando sull'icona con l'ingranaggio accanto al suo nome e salvando 
			tutte le modifiche premendo sul tasto "Salva". Oltre a modificare i dati inseriti durante la registrazione può anche aggiungerne di nuovi 
			come una sua foto profilo, il suo indirizzo e la sua professione.</p>
		<div class="clear"></div>
	</div>
	<div>
		<h3>Tabella degli appuntamenti</h3>

		<p>Nella pagina del profillo inoltre è visibile la tabella degli appuntamenti dell'utente. Al momento della registrazione la tabella non è visibile poichè 
			deve essere ancora configurata. L'utente può configurarla nella pagina "Impostazioni". In questa pagina inserisce i giorni lavorativi, gli orari di 
			inizio e di fine, la durata degli appuntamenti e le eventuali pause. L'aspetto della tabella viene mostrato in tempo reale mediante l'anteprima sul lato 
			destro della pagina.</p>
	</div>
	<div>		
		<h3>Prenotazione appuntamenti</h3>
		<img id="pic_prenotazione" class="right" src="./<?php echo $path;?>img/screenshot/prenotazione.gif" alt="gif prenotazione">
		<p>Ogni utente per effettuare una prenotazione deve recarsi sul profilo dell'utente desiderato (ad esempio tramite la barra di ricerca) e 
			selezionare l'appuntamento libero che preferisce nella tabella degli appuntamenti. Ogni casella rappresenta un appuntamento. Se è bianca significa che l'appuntamento 
			è libero, se è grigia significa che non è prenotabile altrimenti se è blu con l'icona del calendario significa che è già stato prenotato. Tramite le frecce può scorrere 
			avanti o indietro nel tempo ed effetuare delle prenotazioni anche nelle settimane successive a quella in cui si trova. Quando si clicca su una casella, si apre una schermata 
			di conferma in cui vengono riepilogati i dati dell'appuntamento ed in cui è possibile aggiungere delle note personali. Cliccando sul tasto "conferma prenotazione" si conferma 
			la prenotazione altrimenti si può annullare premendo sul tasto "Esci", cliccando fuori dal riquadro o premendo il tasto ESC della tastiera.</p>
	<div class="clear"></div>
	</div>
	<div>	
		<h3>Pagina home</h3>
		<p>Nella pagina "Home" vengono ricapitolati i primi tre appuntamenti più imminenti dell'utente suddivisi tra quelli che lui ha prenotato e quelli che sono stati prenotati da altri utenti 
			con lui. In questa sezione l'utente può cancellare l'appuntamento cliccando sull'icona "Rimuovi" oppure può accedere alla lista completa degli appuntamenti cliccando sul link che 
			si trova in fondo alla lista. </p>
		<img id="pic_home" src="./<?php echo $path;?>img/screenshot/home.png" alt="immagine schermata home">
	</div>
	<div>
		<h3>Recensioni</h3>
		<img id="pic_recensione" class="right" src="./<?php echo $path;?>img/screenshot/recensione.png" alt="immagine prenotazione">
		<p>Un utente che ha già effettuato degli appuntamenti con un altro utente può raccontare la sua esperienza (in modo da consigliare gli altri utenti) cliccando sul tasto "Scrivi una recensione". 
			Sì aprirà una schermata in cui l'utente può dare un punteggio (da 1 a 5) e scrivere una sua opinione. Tutte le recensioni che un utente ha ricevuto ed il suo punteggio medio vengono mostrati 
			nella sua pagina "Profilo".</p>
		<div class="clear"></div>
	</div>
	<div>
		<h3> Calendario e notifiche </h3>
		<p> Ogni utente può visualizzare il calendario mensile cliccando sul tasto "Calendario" <img class="icona" src="./<?php echo $path;?>img/icon/set1/calendar.png" alt="icona calendario"> in alto a destra. Nel calendario vengono evidenziati i giorni in cui l'utente ha degli appuntamenti. Per ogni giorno evidenziato è 
			presente un badge che indica il numero totale di prenotazioni presenti. Cliccando su uno dei giorni si accede ad una pagina in cui vengono mostrati gli appuntamenti del giorno.
		</p>
		<p>
			Ogni volta in cui un utente riceve una prenotazione, una recensione oppure un altro utente cancella un appuntamento con lui può restare aggiornato mediante le notifiche.
			Cliccando sul tasto "Notifica" <img class="icona" src="./<?php echo $path;?>img/icon/set1/notification.png" alt="icona calendario"> in alto a destra può vedere le ultime notiche ricevute.
		</p>
	</div>
	<div>
		<h3>Amministrazione</h3>
		<p> Alcuni utenti del sito possono essere anche amministratori. Solo gli utenti amministratori possono accedere alla pagina "Admin" del sito in cui è 
			possibile effettuare le seguenti operazioni di amministrazione: </p>


	   	   <ul>
				<li> Creare nuovi utenti
			    <li> Modificare le informazioni di utenti già esistenti
			    <li> Rimuovere delle recensioni offensive o non gradite
			    <li> Rimuovere degli utenti dal sito
			</ul>
	</div>
	</div>
</div>