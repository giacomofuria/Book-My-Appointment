function begin(){
	var slideFlag;
	var parameters = getParameters(); // recupero i parametri passati con il metodo GET


	showSignFields('login',parameters);
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
function showSignFields(type, getParameters){
	var signHeaderButtonID;
	var signHeaderButtonFunction;
	var signHeaderButtonText;

	if(type == 'login'){
		signHeaderButtonID = 'signUpButton';
		signHeaderButtonFunction = 'showSignFields(\'register\')';
		signHeaderButtonText = 'Sign Up';
	}else if(type == 'register'){
		signHeaderButtonID = 'signInButton';
		signHeaderButtonFunction = 'showSignFields(\'login\')';
		signHeaderButtonText = 'Sign In';
	}else
		return;

	var sign_header = document.getElementById("sign_header");
	removeAllChildren(sign_header);
	var signButton = createButton(signHeaderButtonID, "sign_header_buttons", signHeaderButtonFunction,signHeaderButtonText);
	var userGuideButton = createButton("user_manual_button","sign_header_buttons","","User guide");
	sign_header.appendChild(signButton);
	sign_header.appendChild(userGuideButton);
	showSecondHeader(type);
	showSignForm(type);

	if(getParameters != null && getParameters.errorMessage != null){ 
		showErrorMessage(getParameters.errorMessage);
	}else{
		var left_side = document.getElementById("left-side");
		slide(left_side, -30, 0);
	}
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
/* Visualizza il form di login o di registrazione in base al parametro type*/
function showSignForm(type){
	var action;
	var method = 'post';
	var signButtonText;

	var form_container = document.getElementById("form_container");
	removeAllChildren(form_container);
	var form = document.createElement('form');
	form.setAttribute('method',method);

	var email = document.createElement("input");
	email.setAttribute('class','input-text');
	email.setAttribute('placeholder','Email:');
	email.setAttribute('type','email');
	email.setAttribute('name','email');
	email.required= true;
	var loginPassword = document.createElement("input");
	var loginButton = document.createElement("button");
	loginButton.setAttribute('class','sign_button');

	if(type=='login'){
		action='./php/login.php';

		loginPassword.setAttribute('class','input-text');
		loginPassword.setAttribute('placeholder','Password:');
		loginPassword.setAttribute('type','password');
		loginPassword.setAttribute('name','password');
		loginPassword.required = true;
		form.appendChild(email);
		form.appendChild(loginPassword);

		var errorMessageBox = document.createElement("div");
		errorMessageBox.setAttribute('class','error_message');
		errorMessageBox.setAttribute('id','sign_in_error_msg');
		form.appendChild(errorMessageBox);

		signButtonText = 'Sign In';

	}else if(type=='register'){
		action='./php/register.php';
		signButtonText = 'Sign Up';
		// Creo tutti i restanti campi di registrazione
		var firstName = document.createElement("input");
		firstName.setAttribute('class','input-text');
		firstName.setAttribute('placeholder','First name: ');
		firstName.setAttribute('name','first_name');
		firstName.required= true;
		form.appendChild(firstName);

		var lastName = document.createElement("input");
		lastName.setAttribute('class','input-text');
		lastName.setAttribute('placeholder','Last name: ');
		lastName.setAttribute('name','last_name');
		lastName.required = true;
		form.appendChild(lastName);
		form.appendChild(email);
		var signUpPassword = document.createElement("input");
		signUpPassword.setAttribute('class','input-text');
		signUpPassword.setAttribute('placeholder','Password:');
		signUpPassword.setAttribute('type','password');
		signUpPassword.setAttribute('name','sign_up_password');
		signUpPassword.required = true;
		form.appendChild(signUpPassword);
		var reSignUpPassword = document.createElement("input");
		reSignUpPassword.setAttribute('class','input-text');
		reSignUpPassword.setAttribute('placeholder','Confirm password:');
		reSignUpPassword.setAttribute('type','password');
		reSignUpPassword.setAttribute('name','re-sign_up_password');
		reSignUpPassword.required = true;
		form.appendChild(reSignUpPassword);
	}else
		return;

	form.setAttribute('action',action);
	
	var txt = document.createTextNode(signButtonText);
	loginButton.appendChild(txt);
	form.appendChild(loginButton);
	form_container.appendChild(form);
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