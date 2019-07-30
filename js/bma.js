function begin(){
	var slideFlag;
	var parameters = getParameters(); // recupero i parametri passati con il metodo GET
	if(parameters != null){ 
		/* se ci messaggi di errore da visualizzare li mostro senza far apparire 
		 il campo di login con lo slide
		 */
		slideFlag = false;
		showErrorMessage(parameters.errorMessage);
	}else{
		/* Se non ci sono messaggi di errore da visualizzare faccio apperire
			il campo di login con l'effetto di slide
		 */
		 slideFlag = true;
	}
	showSignInFields(slideFlag);
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
/* Crea un bottone con un certo id, classe e onclick*/
function createButton(buttonId, buttonClass, buttonOnclickFunction,text){
	var b = document.createElement("button");
	b.setAttribute("id", buttonId);
	b.setAttribute("class", buttonClass);
	b.setAttribute("onclick", buttonOnclickFunction);
	var txt = document.createTextNode(text);
	b.appendChild(txt);
	return b;
}	
/* Visualizza il box di login*/
function showSignInFields(slideFlag){
	console.log("login"); // DEBUG
	var sign_header = document.getElementById("sign_header");
	removeAllChildren(sign_header);
	var signUpButton = createButton("signUpButton", "sign_header_buttons", "showSignUpFields(true)","Sign Up");
	var userGuideButton = createButton("user_manual_button","sign_header_buttons","","User guide");
	sign_header.appendChild(signUpButton);
	sign_header.appendChild(userGuideButton);
	if(slideFlag){
		var left_side = document.getElementById("left-side");
		slide(left_side, -30, 0);
	}
	
}
/* Visualizza il box di registrazione */
function showSignUpFields(slideFlag){
	console.log("Registration"); // DEBUG
	var sign_header = document.getElementById("sign_header");
	removeAllChildren(sign_header);
	var signInButton = createButton("signInButton", "sign_header_buttons", "showSignInFields(true)","Sign In");
	var userGuideButton = createButton("user_manual_button","sign_header_buttons","","User guide");
	sign_header.appendChild(signInButton);
	sign_header.appendChild(userGuideButton);
	if(slideFlag){
		var left_side = document.getElementById("left-side");
		slide(left_side, -30, 0);
	}
}
/* Rimuove tutti i figlio del nodo passato come parametro*/
function removeAllChildren(elem){
	if(elem == null)
		return;
	var child = elem.firstChild;
	while(child){
		elem.removeChild(child);
		child = elem.firstChild;
	}
}