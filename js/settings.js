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
		//switch(elem.type){
			//case "text":
				elem.onblur = gestore;
				//break;
			//default:
				elem.onchange = gestore;
			//	break;
		//}
	}

	var selectDuration = document.getElementById("select_duration");
	selectDuration.onchange = gestore;
	var pausesSelector = document.getElementById("pauses_selector");
	pausesSelector.onchange = gestore;

	var gestoreTimePicker = new Function("createTimePicker(this)");

	var openTimeInput = document.getElementById("open_time_input");
	var closeTimeInput = document.getElementById("close_time_input");
	openTimeInput.onclick = gestoreTimePicker;
	closeTimeInput.onclick = gestoreTimePicker;

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
			//tabellaDiPreview.updateStartTime(elem.value,false);	
			break;
		case "closing_time":
			//aggiornaOrararioChiusura(elem.value);
			//tabellaDiPreview.updateCloseTime(elem.value,false);
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

function createTimePicker(elem){
	var timePicker = new TimePicker(elem);
}

function TimePicker(elem){
	this.container = null;
	this.valore = elem.value;
	this.selectedInput = elem;
	this.selectHour=null;
	this.selectMinutes=null;
	switch(elem.id){
		case "open_time_input":
			this.container = document.getElementById("open_time_selector_container");
			//this.selectedInput = document.getElementById("open_time_input");
			break;
		case "close_time_input":
			this.container = document.getElementById("close_time_selector_container");
			//this.selectedInput = document.getElementById("close_time_input");
			break;
		default:
			break;
	}
	
	

	var splitted = elem.value.split(":");
	this.ora = splitted[0];
	this.minuti = splitted[1];
	this.createPicker();
}
TimePicker.prototype.createPicker = function(){

	while(this.container.lastChild){
		this.container.lastChild.remove();
	}

	var div1 = document.createElement("div");
	div1.className="time-selector-components";
	this.createHourList(div1);
	this.container.appendChild(div1);

	var div2 = document.createElement("div");
	div2.className="time-selector-components";
	this.createMinuteList(div2);
	this.container.appendChild(div2);

	var div3 = document.createElement("div");
	div3.className="clear";
	this.container.appendChild(div3);
}
TimePicker.prototype.createHourList =function (elem){
	this.selectHour = document.createElement("select");
	this.selectHour.id="hour_selector";
	this.selectHour.setAttribute("multiple","true");
	for(var i=0;i<24;i++){
		var opt = document.createElement("option");
		opt.value = i;

		var txt = document.createTextNode(i);
		opt.appendChild(txt);
		this.selectHour.appendChild(opt);
	}
	var inputElem = this.selectedInput;
	var selector = this.selectHour;
	var idContainer = this.container.id;
	this.selectHour.addEventListener("click",function(){
		update(inputElem,"hour",selector,idContainer);
	});
	elem.appendChild(this.selectHour);
}
TimePicker.prototype.createMinuteList = function(elem){
	this.selectMinutes = document.createElement("select");
	this.selectMinutes.setAttribute("multiple","true");
	this.selectMinutes.id = "minutes_selector";
	for(var i=0;i<60;i++){
		var opt = document.createElement("option");
		opt.value = i;
		
		var txt = document.createTextNode(i);
		opt.appendChild(txt);
		this.selectMinutes.appendChild(opt);
	}
	var inputElem = this.selectedInput;
	var selector = this.selectMinutes;
	var idContainer = this.container.id;
	this.selectMinutes.addEventListener("click",function(){
		update(inputElem,"minutes",selector,idContainer);
	});
	var container = this.container;
	this.selectMinutes.addEventListener("blur",function(){
		while(container.lastChild){
			container.lastChild.remove();
		}
	});
	elem.appendChild(this.selectMinutes);
}
function update(elem,type,selector,idContainer){
	console.log(elem.id);
	if(type=="hour"){
		elem.value = updateHour(elem.value, takeSelected(selector));
	}else{
		elem.value = updateMinutes(elem.value, takeSelected(selector));
		
	}
	if(idContainer=="open_time_selector_container"){
		tabellaDiPreview.updateStartTime(elem.value,false);
	}else{
		tabellaDiPreview.updateCloseTime(elem.value,false);
	}
	
}
function takeSelected(selector){
	var opts = selector.getElementsByTagName("option");
	for(var i=0; i<opts.length; i++){
		if(opts[i].selected){
			if(i>=0 && i<=9){
				return "0"+i;
			}else{
				return i;
			}
		}
	}
	return "00";
}
function updateHour(old, value){
	var min = old.split(":")[1];
	var h = value+":"+min;
	return h;
}
function updateMinutes(old,value){
	var h = old.split(":")[0];
	var ret = h+":"+value;
	return ret;
}