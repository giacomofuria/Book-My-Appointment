function begin(){
	var left_side = document.getElementById("left-side");
	var parameters = getParameters(); // recupero i parametri passati con il metodo GET
	if(parameters != null){ 
		/* se ci messaggi di errore da visualizzare li mostro senza far apparire 
		 il campo di login con lo slide
		 */
		showErrorMessage(parameters.errorMessage);
	}else{
		/* Se non ci sono messaggi di errore da visualizzare faccio apperire
			il campo di login con l'effetto di slide
		 */
		slide(left_side, -30, 0);
	}
	
}
/* Funzione che restitisce i parametri get presenti all'interno dell'url */
function getParameters(){
	var url = document.URL;
	var vett = url.split('?');
	var rawParameters = vett[1]; // contiene tutti i parametri get separati dal carattere '&'
	if(rawParameters == null){
		return null;
	}
	var rawParametersCouples = rawParameters.split('&');
	var parameters = new Array();
	for(var i=0;i<rawParametersCouples.length; i++){
		var elemento = rawParametersCouples[i].split('=');
		var chiave = elemento[0];
		var valore = elemento[1];
		valore = valore.replace(/%20+/g," "); // rimpiazzo i %20 con degli spazi
		parameters[chiave] = valore; // le inserisco dentro un array associativo
		//console.log("Chiave: "+chiave+", valore: "+parameters[chiave]); // DEBUG
	}
	return parameters;
}
/* Visualizza il messaggio di errore*/
function showErrorMessage(msg){
	var sign_in_error_msg= document.getElementById("sign_in_error_msg");
	sign_in_error_msg.style.display="block";
	var err_msg = document.createElement("p");
	var txt = document.createTextNode(msg);
	err_msg.appendChild(txt);
	sign_in_error_msg.appendChild(err_msg);
}