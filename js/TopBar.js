function showCalendar(){
	var elem = document.getElementById('calendar-container');
	if(!elem || elem == null){
		return;
	}
	elem.style.display="block";
	slideDown(elem,-30,10);
	var calendarButton = document.getElementById("calendar-button");
	calendarButton.onclick=closeCalendar;
}
function closeCalendar(){
	var elem = document.getElementById('calendar-container');
	if(!elem || elem == null){
		return;
	}
	slideUp(elem,8,-60);
	var calendarButton = document.getElementById("calendar-button");
	calendarButton.onclick = showCalendar;
}
function openNotification(){
	var button = document.getElementById("notification-button");
	button.onclick = closeNotification;
	var notifyContainer = document.getElementById("notify_container");
	notifyContainer.style.display = "block";

	var url = "./ajax/notificationFinder.php?newNotificationsOf=" + button.value;
	var responseFunction = riceviNotifiche;
	
	AjaxManager.performAjaxRequest(SearchBar.DEFAUL_METHOD, 
										url, SearchBar.ASYNC_TYPE, 
										null, responseFunction);
}

function closeNotification(){
	var notifyContainer = document.getElementById("notify_container");
	notifyContainer.style.display = "none";
	var button = document.getElementById("notification-button");
	button.onclick = openNotification;
	button.style.backgroundColor = "#FFFFFF";
}

function riceviNotifiche(response){
	if(response.data != null){
		// console.log(response.data); // stampo i dati ricevuti per debug
		refreshNotifiche(response.data);
	}
}

function refreshNotifiche(data){
	var notifyContainer = document.getElementById("notify_container");
	while(notifyContainer.lastChild){
		notifyContainer.lastChild.remove();
	}
	for(var i=0; i<data.length; i++){
		var div = document.createElement("div");
		div.className="row-container";
		var p = document.createElement("p");
		p.className = "notify-text";
		if(data[i].letta == "0"){
			div.className+=" not-read";
		}
		var img = document.createElement("img");
		img.className='search-result-img';
		imgSource = "./../img/icon/set1/man.png"; // metto sempre l'icona perché trasmettere l'immagine mi genera un problema di lunghezza
		img.setAttribute("src",imgSource);
		div.appendChild(img);
		var txt = document.createTextNode(data[i].testo);

		p.appendChild(txt);
		div.appendChild(p);

		notifyContainer.appendChild(div);
	}
}

function SearchBar(){}

SearchBar.DEFAUL_METHOD = "GET";
//SearchBar.URL_REQUEST = "./ajax/movieLoader.php";
SearchBar.EXPLORE_REQUEST = "./ajax/userFinder.php";
SearchBar.REVIEW_REQUEST = "./ajax/reviewFinder.php";
SearchBar.REMOVE_REQUEST = "./admin.php";
SearchBar.ASYNC_TYPE = true;
SearchBar.resultBox = null;
SearchBar.MODE = "USER_SEARCH";
SearchBar.adminResultBox=null;
SearchBar.choosenUser = null;
SearchBar.bar=null;

SearchBar.search =
	function(elem, pattern){
		//console.log("Cercato: "+pattern); // DEBUG
		if (pattern === null || pattern.length === 0 || pattern==''){
			// chiudo la box e ne cancello il contenuto
			SearchBar.close();
			return;	
		}
		//console.log(elem.id);
		SearchBar.bar = elem;

		switch(elem.id){
			case "search-bar":
				resultBox = document.getElementById("search_results_container");
				SearchBar.MODE = "USER_SEARCH";
				break;
			case "search_user_to_modify":
				resultBox = document.getElementById("user_admin_search");
				SearchBar.adminResultBox = document.getElementById("change_user_password_form");
				SearchBar.MODE = "ADMIN_SEARCH";
				break;
			case "search_user_review":
				resultBox = document.getElementById("user_admin_search_review");
				SearchBar.adminResultBox = document.getElementById("remove_review_form");
				SearchBar.MODE = "ADMIN_SEARCH_REVIEW";
				break;
			case "search_user_remove":
				resultBox = document.getElementById("user_admin_search_remove");
				SearchBar.adminResultBox = document.getElementById("remove_user_form");
				SearchBar.MODE = "ADMIN_SEARCH_REMOVE";
				break;
			default:
				
				break;
		}
		var queryString = "?search=" + pattern;
		var url = SearchBar.EXPLORE_REQUEST + queryString;
		var responseFunction = SearchBar.getAjaxResponse;
	
		AjaxManager.performAjaxRequest(SearchBar.DEFAUL_METHOD, 
										url, SearchBar.ASYNC_TYPE, 
										null, responseFunction);
		
		
	}
SearchBar.getAjaxResponse = 
	function(response){
		if(response.data != null){
			//console.log(response.data); // stampo i dati ricevuti per debug
			SearchBar.refresh(response.data);
		}

	}
SearchBar.refresh = 
	function(data){
		//var searchBox = document.getElementById("search_results_container");
		var searchBox = resultBox;
		while(searchBox.lastChild){
			searchBox.lastChild.remove();
		}
		searchBox.style.display="block";
		document.addEventListener('keydown', function(event) {
			if (event.keyCode == 27 || event.which == 27){
		        SearchBar.close();
		        SearchBar.bar.value="";
		    }
   		 }, false);
		for(var i=0; i<data.length; i++){
			SearchBar.addRow(data[i],searchBox);
		}
	}
SearchBar.addRow = 
	function(row, elem){
		var div = document.createElement("div");
		div.className="row-container";
		var p = document.createElement("p");

		var img = document.createElement("img");
		img.className='search-result-img';
		var imgSource = row.profileImage;
		imgSource = "./../img/icon/set1/man.png"; // metto sempre l'icona perché trasmettere l'immagine mi genera un problema di lunghezza
		img.setAttribute("src",imgSource);
		div.appendChild(img);
	
		var href=null;
		var a = document.createElement("a");
		
		switch(SearchBar.MODE){
			case "USER_SEARCH":
				href="./profile.php?user="+row.userId;
				a.setAttribute("href",href);
				break;
			case "ADMIN_SEARCH":
				var utente = new User(row.userId,row.email,row.firstName,row.lastName,row.profileImage,row.profession,row.address,row.admin);
				SearchBar.choosenUser = utente;
				a.style.cursor="pointer";
				a.addEventListener('click', function() {
				    SearchBar.showUserSettingsForm(utente);
				}, false);
				break;
			case "ADMIN_SEARCH_REVIEW":
				var utente = new User(row.userId,row.email,row.firstName,row.lastName,row.profileImage,row.profession,row.address,row.admin);
				SearchBar.choosenUser = utente;
				//href="javascript:SearchBar.showDeleteReviewForm()";
				a.style.cursor="pointer";
				a.addEventListener('click', function() {
				    SearchBar.showDeleteReviewForm(utente);
				}, false);
				break;
			case "ADMIN_SEARCH_REMOVE":
				var utente = new User(row.userId,row.email,row.firstName,row.lastName,row.profileImage,row.profession,row.address,row.admin);
				SearchBar.choosenUser = utente;
				//href="javascript:SearchBar.showDeleteReviewForm()";
				a.style.cursor="pointer";
				a.addEventListener('click', function() {
				    SearchBar.showRemoveUserForm(utente);
				}, false);
				break;
			default:
				break;
		}

		var txt = document.createTextNode(row.firstName+" "+row.lastName);
		a.appendChild(txt);

		p.appendChild(a);
		div.appendChild(p);
		if(row.profession!=null && row.profession != "null"){
			var subP = document.createElement("p");
			subP.className='profession-paragraph';
			txt = document.createTextNode(row.profession);
			subP.appendChild(txt);
			div.appendChild(subP);
		}

		elem.appendChild(div);
	}
SearchBar.close = 
	function(){
		//var searchBox = document.getElementById("search_results_container");
		var searchBox = resultBox;
		searchBox.style.display="none";
		while(searchBox.lastChild){
			searchBox.lastChild.remove();
		}
	}
SearchBar.showUserSettingsForm = 
	function(utente){
		SearchBar.adminResultBox.style.display = "block";
		var inputs = SearchBar.adminResultBox.getElementsByTagName("input");

		inputs[0].value = utente.id;
		inputs[1].value = utente.email;
		inputs[2].value = utente.nome;
		inputs[3].value = utente.cognome;
		inputs[4].value = utente.professione;
		inputs[5].value = utente.indirizzo;
		if(utente.admin==1 || utente.admin == "1"){
			inputs[5].checked = true;
		}else{
			inputs[5].checked = false;
		}
		
		
	}
SearchBar.showDeleteReviewForm = 
	function(utente){
		SearchBar.adminResultBox.style.display = "block";
		var queryString = "?reviewsReceiver=" + utente.id;
		var url = SearchBar.REVIEW_REQUEST + queryString;
		//console.log(url); // debug
		var responseFunction = SearchBar.getAjaxReviewResponse;
	
		AjaxManager.performAjaxRequest(SearchBar.DEFAUL_METHOD, 
										url, SearchBar.ASYNC_TYPE, 
										null, responseFunction);
	}
SearchBar.getAjaxReviewResponse = 
	function(response){
		if(response.data != null){
			//console.log(response.data); // stampo i dati ricevuti per debug
			SearchBar.refreshReviewList(response.data);
		}
	}
SearchBar.refreshReviewList = 
	function(data){
		var container = SearchBar.adminResultBox;
		while(container.lastChild){
			container.lastChild.remove();
		}
		for(var i=0; i<data.length; i++){
			var div = document.createElement("div");
			div.className="review-container";

			var div1 = document.createElement("div");
			div1.className="review-elem";
			var txt = document.createTextNode(data[i].nome_recensore+" "+data[i].cognome_recensore);
			div1.appendChild(txt);

			var div2 = document.createElement("div");
			div2.className="review-elem";
			txt = document.createTextNode(data[i].testo_recensione);
			div2.appendChild(txt);

			var div3 = document.createElement("div");
			div3.className="review-elem";

			var a = document.createElement("a");
			a.setAttribute("href","./admin.php?delReview="+data[i].idRecensione);
			var img = document.createElement("img");
			img.className='button-icon remove-icon';
			img.setAttribute("src","./../img/icon/set1/garbage.png");
			a.appendChild(img);
			div3.appendChild(a);

			div.appendChild(div1);
			div.appendChild(div2);
			div.appendChild(div3);

			var endDiv = document.createElement("div");
			endDiv.className="end-div";
			div.appendChild(endDiv);
			container.appendChild(div);
		}	
	}
SearchBar.showRemoveUserForm = 
	function(utente){
		SearchBar.adminResultBox.style.display = "block";
		var queryString = "?removeUser=" + utente.id;
		var url = SearchBar.REMOVE_REQUEST + queryString;
		//console.log(url); // debug
		var container = SearchBar.adminResultBox;
		while(container.lastChild){
			container.lastChild.remove();
		}
		
		var p = document.createElement("p");
		var txt = document.createTextNode("Clicca per rimuovere: "+utente.nome+" "+utente.cognome+", Email: "+utente.email+", tutte le sue recensioni e i suoi appuntamenti verranno rimossi");
		p.appendChild(txt);
		var a = document.createElement("a");
		a.setAttribute("href","./admin.php?removeUser="+utente.id);
		var img = document.createElement("img");
		img.className='button-icon remove-icon';
		img.setAttribute("src","./../img/icon/set1/garbage.png");
		a.appendChild(img);
		p.appendChild(a);
		container.appendChild(p);
		
	}
