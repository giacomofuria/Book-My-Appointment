<!-- Inizio sezione barra superiore -->
<div id="top-bar-container">
	<div class="top-bar-box">
		<?php echo ' '.$_SESSION['first_name'].' '.$_SESSION['last_name']; ?>
	</div>
	<div id="search-bar-container" class="top-bar-box">
		<input id="search-bar" placeholder="Search for a name or a job">
		<button id="search-button" title="search"><img src="./../img/icon/set1/search.png" style="width:40%; height:35%;"></button>
	</div>
	<div class="top-bar-box">
		<button id="calendar-button" class="command-buttons" title="Calendar">
			<img src="./../img/icon/set1/calendar.png" style="width:100%; height:65%;">
		</button>
	</div>
	<div class="top-bar-box">
		<button id="notification-button" class="command-buttons" title="Notifications">
			<img src="./../img/icon/set1/notification.png" style="width:100%; height:62%;">
		</button>
	</div>	
	<div class="top-bar-box">
		<button id="add-button" class="command-buttons" title="Add new appointment schedule">
			<img src="./../img/icon/set1/add.png" style="width:100%; height:62%;">
		</button>
	</div>
</div>
<!-- Fine sezione barra superiore -->