/*Uso il terzo approccio descritto nelle slide per associare eventi agli oggetti del DOM*/

var oraInizio;
var oraFine;
var numeroAppuntamenti;

var tabellaDiPreview = new PreviewTable();

function begin(){

	var previewContainer = document.getElementById("preview_container");
	tabellaDiPreview.buildTable(previewContainer);

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
			//aggiornaGiorniLavoroTabellaPreview(elem);
			tabellaDiPreview.updateTableColumn(parseInt(elem.value), elem.checked);
			break;
		case "opening_time":
			//aggiornaOrarioApertura(elem.value);
			tabellaDiPreview.updateStartTime(elem.value);	
			break;
		case "closing_time":
			//aggiornaOrararioChiusura(elem.value);
			tabellaDiPreview.updateCloseTime(elem.value);
			break;
		case "select_duration":
			console.log(elem.value);
			break;
		default:
			break;
	}
}

function aggiornaOrararioChiusura(value){
	oraFine = value;
	// Conosco orario di apertura e di chiusura e, con appuntamenti di 1h, posso calcolare il numero degli appuntamenti.
	numeroAppuntamenti = parseInt(oraFine)-parseInt(oraInizio);
	aggiornaTabellaPreview();
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

