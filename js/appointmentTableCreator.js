/*Uso il terzo approccio descritto nelle slide per associare eventi agli oggetti del DOM*/

var oraInizio;
var oraFine;
var numeroAppuntamenti;

function begin(){
	var inputs = document.getElementsByTagName("input");
	var gestore = new Function("cambia(this)");
	for(var i=0; i<inputs.length; i++){
		var elem = inputs[i];
		
		switch(elem.type){
			case "time":
				elem.onblur = gestore;
				break;
			default:
				elem.onchange = gestore;
				break;
		}
	}
	var selectDuration = document.getElementById("select_duration");
	selectDuration.onchange = gestore;
}
function cambia(elem){
	switch(elem.name){
		case "work_days":
			aggiornaGiorniLavoroTabellaPreview(elem);
			break;
		case "opening_time":
			aggiornaOrarioApertura(elem.value);	
			break;
		case "closing_time":
			aggiornaOrararioChiusura(elem.value);
			break;
		case "select_duration":
			console.log(elem.value);
			break;
		default:
			break;
	}
}
function aggiornaGiorniLavoroTabellaPreview(elem){
	
	var col_index = parseInt(elem.value);
	var previewTable = document.getElementById("preview_table");
	var intestazioni = previewTable.getElementsByTagName("th");
	if(elem.checked){
		intestazioni[col_index].style.backgroundColor = '#284255';
		intestazioni[col_index].style.opacity = 1;
	}else{
		intestazioni[col_index].style.backgroundColor = '#c5c8c9';
		intestazioni[col_index].style.opacity = 0.4;
	}
	aggiornaColonna(previewTable, col_index, elem.checked);
}
function aggiornaColonna(table, col, check){
	var rows = table.getElementsByTagName("tr");
	for(var i=0;i<rows.length; i++){
		var tds = rows[i].getElementsByTagName("td");
		for(var j=0; j<tds.length; j++){
			if(j == col){
				if(check){
					tds[j].style.backgroundColor='#FFFFFF';
					tds[j].style.opacity=1;
				}else{
					tds[j].style.backgroundColor='#c5c8c9';
					tds[j].style.opacity=0.1;
				}
			}	
		}
	}
}
function aggiornaOrarioApertura(value){
	oraInizio=value;
	var previewTable = document.getElementById("preview_table");
	var tds = previewTable.getElementsByTagName("td");
	// se c'è rimuovo l'eventuale nodo testuale figlio
	var text = tds[0].firstChild;
	if(text){
		text.remove();
	}
	var openingTimeTextNode = document.createTextNode(value);
	tds[0].appendChild(openingTimeTextNode);
}
function aggiornaOrararioChiusura(value){
	oraFine = value;
	// Conosco orario di apertura e di chiusura e, con appuntamenti di 1h, posso calcolare il numero degli appuntamenti.
	numeroAppuntamenti = parseInt(oraFine)-parseInt(oraInizio);
	aggiornaTabellaPreview();
	console.log("numero appuntamenti: "+numeroAppuntamenti);
}
function aggiornaTabellaPreview(){
	var previewTable = document.getElementById("preview_table");
	var inizio = oraInizio;
	var fine = oraFine;
	for(var i=0;i<numeroAppuntamenti-1; i++){
		var row = document.createElement("tr");
		for(var j=0;j<8;j++){
			var td = document.createElement("td");
			row.appendChild(td);
		}
		previewTable.appendChild(row);
	}
}

