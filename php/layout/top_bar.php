<!-- Inizio sezione barra superiore -->
<script src="./../js/ajaxManager.js"></script>
<script src="./../js/TopBar.js"></script>
<script src="./../js/user.js"></script>
<script src="./../js/Notify.js"></script>
<script src="./../js/effects.js"></script>
<div id="top-bar-container">
	<div class="top-bar-box">
		<div class='top-bar-header'><?php echo $_SESSION['first_name'].' '.$_SESSION['last_name']; ?></div>
	</div>
	<div id="search-bar-container" class="top-bar-box">
		<form method="GET" action="./search.php">
			<input id="search-bar" name='pattern' placeholder="Cerca un nome o una professione" onkeyup="SearchBar.search(this,this.value)">
			<button id="search-button" title="search" type="submit"><img src="./../img/icon/set1/search.png" style="width:40%; height:35%;" alt="Cerca"></button>
		</form>
		<div id='search_results_container'>Risultati:<br></div>
	</div>
	<div class="top-bar-box">
		<button id="calendar-button" class="command-buttons" title="Calendario" onclick="closeCalendar()">
			<img src="./../img/icon/set1/calendar.png" style="width:100%; height:65%;" alt="Calendario">
		</button>
	</div>
	<div class="top-bar-box">
		<button id="notification-button" class="command-buttons" value="<?php echo $_SESSION['userId'] ?>" title="Notifiche" onclick="openNotification()">
			<img src="./../img/icon/set1/notification.png" style="width:100%; height:62%;" alt="Notifiche">
		</button>
		<div id='notify_container'>Notifiche:<br></div>
	</div>
</div>
<!-- Fine sezione barra superiore -->