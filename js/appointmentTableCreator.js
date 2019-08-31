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
			tabellaDiPreview.updateAppointmentDuration(elem.value);
			//console.log(elem.value);
			break;
		default:
			break;
	}
}



