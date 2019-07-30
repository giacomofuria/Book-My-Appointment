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
	showSignFields('login',slideFlag);
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



/* Viasualizza il box di login o di registrazione a seconda del parametro type ("login" or "register")
*/
function showSignFields(type, slideFlag){
	var signHeaderButtonID;
	var signHeaderButtonFunction;
	var signHeaderButtonText;

	if(type == 'login'){
		signHeaderButtonID = 'signUpButton';
		signHeaderButtonFunction = 'showSignFields(\'register\',true)';
		signHeaderButtonText = 'Sign Up';
	}else if(type == 'register'){
		signHeaderButtonID = 'signInButton';
		signHeaderButtonFunction = 'showSignFields(\'login\',true)';
		signHeaderButtonText = 'Sign In';
	}else
		return;

	if(slideFlag){
		var left_side = document.getElementById("left-side");
		slide(left_side, -30, 0);
	}

	var sign_header = document.getElementById("sign_header");
	removeAllChildren(sign_header);
	var signButton = createButton(signHeaderButtonID, "sign_header_buttons", signHeaderButtonFunction,signHeaderButtonText);
	var userGuideButton = createButton("user_manual_button","sign_header_buttons","","User guide");
	sign_header.appendChild(signButton);
	sign_header.appendChild(userGuideButton);
	showSecondHeader(type);
	
}
/* Visualizza il secondo header del box di login o di registrazione. In base a type mostra uno o l'altro */
function showSecondHeader(type){
	var title;
	var subTitle;
	if(type == 'login'){
		title = 'Sign In';
		subTitle = 'create an account';
	}else if(type == 'register'){
		title = 'Sign Up';
		subTitle = 'sign in to your account';
	}else
		return;
	var sign_second_header = document.getElementById("sign_second_header");
	removeAllChildren(sign_second_header);
	var titleH3 = document.createElement("h3");
	var txt = document.createTextNode(title);
	titleH3.appendChild(txt);
	sign_second_header.appendChild(titleH3);

	var subTitleH3 = document.createElement("p");
	txt = document.createTextNode('or ');
	subTitleH3.appendChild(txt);
	var link = document.createElement("a");
	link.setAttribute('href','');
	link.appendChild(document.createTextNode(subTitle));
	subTitleH3.appendChild(link);

	sign_second_header.appendChild(subTitleH3);
}
/* Rimuove tutti i figlio del nodo passato come parametro */
function removeAllChildren(elem){
	if(elem == null)
		return;
	var child = elem.firstChild;
	while(child){
		elem.removeChild(child);
		child = elem.firstChild;
	}
}