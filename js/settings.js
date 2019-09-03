/*Uso il terzo approccio descritto nelle slide per associare eventi agli oggetti del DOM*/

var oraInizio;
var oraFine;
var numeroAppuntamenti;
var flagPrimoAvvio = true;
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
	var pausesSelector = document.getElementById("pauses_selector");
	pausesSelector.onchange = gestore;

	if(flagPrimoAvvio){
		flagPrimoAvvio=false;
		var workDaysDiv = document.getElementById("work_days");
		var workDays = workDaysDiv.getElementsByTagName("input");
		var openCloseTimesDiv = document.getElementById("open_close_times");
		var openCloseTimes = openCloseTimesDiv.getElementsByTagName("input");
		var selectDurationElement = document.getElementById("select_duration");
		var pausesSelectorElement = document.getElementById("pauses_selector");
		tabellaDiPreview.updateTable(workDays,openCloseTimes,selectDurationElement,pausesSelectorElement);
	}

}
function cambia(elem){
	switch(elem.name){
		case "work_days[]":
			//aggiornaGiorniLavoroTabellaPreview(elem);
			tabellaDiPreview.updateTableColumn(parseInt(elem.value), elem.checked);
			break;
		case "opening_time":
			//aggiornaOrarioApertura(elem.value);
			tabellaDiPreview.updateStartTime(elem.value,false);	
			break;
		case "closing_time":
			//aggiornaOrararioChiusura(elem.value);
			tabellaDiPreview.updateCloseTime(elem.value,false);
			break;
		case "select_duration":
			tabellaDiPreview.updateAppointmentDuration(elem.value);
			//console.log(elem.value);
			break;
		case "pauses_selector[]":
			tabellaDiPreview.updateDisabledAppointments(elem);
			break;
		default:
			break;
	}
}



