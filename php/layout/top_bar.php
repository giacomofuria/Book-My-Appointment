<!-- Inizio sezione barra superiore -->
		<div id="top-bar-container">
			<div class="top-bar-box">
				<?php echo '<u>Welcome:</u> '.$_SESSION['email']; ?>
			</div>
			<div id="search-bar-container" class="top-bar-box">
				<input id="search-bar" placeholder="Search for a name or a job">
				<button id="search-button" title="search"><img src="./../img/icon/set1/search.png" style="width:40%; height:35%;"></button>

			</div>
			<div class="top-bar-box">
				<button id="calendar-button" title="Calendar"><img src="./../img/icon/set1/calendar.png" style="width:100%; height:65%;"></button>
			</div>	
		</div>
		<!-- Fine sezione barra superiore -->