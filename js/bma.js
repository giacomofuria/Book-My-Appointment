function begin(){
	var left_side = document.getElementById("left-side");
	getParameters();
	slide(left_side, -30, 0);
}
/* Funzione che restitisce i parametri get presenti all'interno dell'url */
function getParameters(){
	var url = document.URL;
	var vett = url.split('?');
	var rawParameters = vett[1]; // contiene tutti i parametri get separati dal carattere '&'
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
}