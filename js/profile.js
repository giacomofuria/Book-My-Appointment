function showConfirmBox(){
	var elem = document.getElementById('confirm-box');
	slideDown(elem,-30,0);
	setTimeout(function(){
		slideUp(elem, 0,-30);

	},3000);
	setTimeout(function(){
		elem.remove();
	},4000);
	
}
function openProfileSettings(nome,cognome,professione,indirizzo){
	console.log("nome: "+nome+", cognome: "+cognome+", professione: "+professione+", indirizzo: "+indirizzo);
	var profileInfoContainer = document.getElementById("profile-info-container");
	// cancello gli elementi che ci sono
	while(profileInfoContainer.lastChild){
		profileInfoContainer.lastChild.remove();
	}
	// Creo il form di modifica
	var form = document.createElement("form");

	form.setAttribute("enctype","multipart/form-data");
	form.setAttribute("method","POST");
	form.setAttribute("action","./profile.php");

	var dim = document.createElement("input");
	dim.setAttribute("type","hidden");
	dim.setAttribute("name","MAX_FILE_SIZE");
	dim.setAttribute("value","16777215");
	form.appendChild(dim);

	var inputFile = document.createElement("input");
	inputFile.setAttribute("name","user_pic");
	inputFile.setAttribute("type","file");
	form.appendChild(inputFile);

	var inputFirstName = document.createElement("input");
	inputFirstName.setAttribute("name","first_name");
	inputFirstName.value = nome;
	inputFirstName.className='input-text';
	form.appendChild(inputFirstName);

	var inputLastName = document.createElement("input");
	inputLastName.setAttribute("name","last_name");
	inputLastName.value = cognome;
	inputLastName.className='input-text';
	form.appendChild(inputLastName);

	var inputProfessione = document.createElement("input");
	inputProfessione.setAttribute("name","profession");
	inputProfessione.value = professione;
	inputProfessione.className='input-text';
	inputProfessione.setAttribute("placeholder","professione");
	form.appendChild(inputProfessione);

	var inputIndirizzo = document.createElement("input");
	inputIndirizzo.setAttribute("name","address");
	inputIndirizzo.setAttribute("required","true");
	inputIndirizzo.value = indirizzo;
	inputIndirizzo.className='input-text';
	inputIndirizzo.setAttribute("placeholder","indirizzo");
	form.appendChild(inputIndirizzo);

	var password = document.createElement("input");
	password.setAttribute("name","newPassword");
	password.setAttribute("type","password");
	password.className='input-text';
	password.setAttribute("placeholder","Nuova password");
	form.appendChild(password);

	var rePassword = document.createElement("input");
	rePassword.setAttribute("name","reNewPassword");
	rePassword.setAttribute("type","password");
	rePassword.className='input-text';
	rePassword.setAttribute("placeholder","Ripeti password");
	form.appendChild(rePassword);

	var bottone = document.createElement("button");
	bottone.className = 'save-button';
	bottone.setAttribute("onclick","return checkPasswords(form)");
	var txt = document.createTextNode("Salva");
	bottone.appendChild(txt);
	form.appendChild(bottone);
	profileInfoContainer.appendChild(form);
}
function checkPasswords(form){
	var password = form.newPassword;
	var repassword = form.reNewPassword;
	//console.log("password: "+password.value+", repassword: "+repassword.value);//DEBUG
	if(password.value != repassword.value){
		password.setAttribute('class','input-text-error');
		repassword.setAttribute('class','input-text-error');
		return false;
	}
	return true;
}