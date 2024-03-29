/* Questo file contiene le funzioni che si occupano di validare i campi di input.
   Vengono richiamate nelle pagine che contengono dei form.
 */
var validateHandler = new Function("validate(this)");

function validate(elem){
	if(elem.type=="email"){
		if(!validateEmail(elem.value)){
			elem.className="input-text-error";
		}else{
			elem.className="input-text";
		}
	}else{
		if(!validateName(elem.value)){
			elem.className="input-text-error";
		}else{
			elem.className="input-text";
		}
	}
}
function validateName(name){
	var letters = /^([a-zA-Z]+[,.]?[ ]?|[a-zA-Z]+['-]?)+$/;
	if(name.match(letters)){
		return true;
	}else{
		return false;
	}
}function validateEmail(email){
	var letters = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/;
	if(email.match(letters)){
		return true;
	}else{
		return false;
	}
}