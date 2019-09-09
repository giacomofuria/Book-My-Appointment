function TopBar(){}

TopBar.DEFAUL_METHOD = "GET";
//TopBar.URL_REQUEST = "./ajax/movieLoader.php";
TopBar.EXPLORE_REQUEST = "./ajax/userFinder.php";
TopBar.ASYNC_TYPE = true;

TopBar.search =
	function(pattern){
		//console.log("Cercato: "+pattern); // DEBUG
		if (pattern === null || pattern.length === 0 || pattern==''){
			// chiudo la box e ne cancello il contenuto
			TopBar.close();
			return;	
		}
		
		var queryString = "?search=" + pattern;
		var url = TopBar.EXPLORE_REQUEST + queryString;
		var responseFunction = TopBar.getAjaxResponse;
	
		AjaxManager.performAjaxRequest(TopBar.DEFAUL_METHOD, 
										url, TopBar.ASYNC_TYPE, 
										null, responseFunction);
		
		
	}
TopBar.getAjaxResponse = 
	function(response){
		if(response.data != null){
			console.log(response.data);
			TopBar.refresh(response.data);
		}

	}
TopBar.refresh = 
	function(data){
		var searchBox = document.getElementById("search_results_container");
		while(searchBox.lastChild){
			searchBox.lastChild.remove();
		}
		searchBox.style.display="block";
		for(var i=0; i<data.length; i++){
			TopBar.addRow(data[i],searchBox);
		}
	}
TopBar.addRow = 
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
		
		var a = document.createElement("a");
		a.setAttribute("href","./profile.php?user="+row.userId);

		var txt = document.createTextNode(row.firstName+" "+row.lastName);
		p.appendChild(txt);

		a.appendChild(p);
		div.appendChild(a);
		if(row.profession!=null && row.profession != "null"){
			var subP = document.createElement("p");
			subP.className='profession-paragraph';
			txt = document.createTextNode(row.profession);
			subP.appendChild(txt);
			div.appendChild(subP);
		}

		elem.appendChild(div);
	}
TopBar.close = 
	function(){
		var searchBox = document.getElementById("search_results_container");
		searchBox.style.display="none";
		while(searchBox.lastChild){
			searchBox.lastChild.remove();
		}
	}