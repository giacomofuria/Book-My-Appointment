function TopBar(){}

TopBar.DEFAUL_METHOD = "GET";
//TopBar.URL_REQUEST = "./ajax/movieLoader.php";
TopBar.EXPLORE_REQUEST = "./ajax/userFinder.php";
TopBar.ASYNC_TYPE = true;

TopBar.search =
	function(pattern){
		console.log("Cercato: "+pattern); // DEBUG
		if (pattern === null || pattern.length === 0 || pattern==''){
			console.log("Campo vuoto");
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
		//console.log(response.data);

	}