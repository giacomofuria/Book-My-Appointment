var week = new Array("Lun","Mar","Mer","Gio","Ven","Sab","Dom");
function PreviewTable(){
	this.oraInizio = null; // Array con ora (prima posizione), minuti (seconda posizione)
	this.oraFine = null; // Array con ora (prima posizione), minuti (seconda posizione)
	this.numeroAppuntamenti = null;
	this.durataAppuntamenti = 60; // Inizialmente la durata di default è un'ora
	this.righe = 1;
	this.container = null;
	this.table = document.createElement("table"); // riferimento alla tabella che creo
	this.selectedColumns = new Array(false, false, false, false, false, false, false);
}

/* Costruisce la tabella di preview e la appende a elem (crea l'intestazione e 
	la prima riga in attesa delle impostazioni dell'utente)*/
PreviewTable.prototype.buildTable = function(elem){
	this.container=elem;
	this.table.setAttribute("id","preview_table");
	var headRow = document.createElement("tr");
	for(var i=0;i<8; i++){
		var th = document.createElement("th");
		th.className="not-selected";
		if(i>0){
			var txt = document.createTextNode(week[i-1]);
			th.appendChild(txt);
		}
		headRow.appendChild(th);
	}
	this.table.appendChild(headRow);
	// creo la prima riga
	var firstRow = document.createElement("tr");
	for(var i=0; i<8; i++){
		var td = document.createElement("td");
		if(i==0)
			td.className = "selected";
		else
			td.className = "not-selected";
		firstRow.appendChild(td);
	}
	this.table.appendChild(firstRow);
	elem.appendChild(this.table);
}
/* Aggiorna la colonna selezionata o deselezionata */
PreviewTable.prototype.updateTableColumn = function(column, checked){
	var intestazioni = this.table.getElementsByTagName("th");
	this.selectedColumns[column-1] = checked;
	if(checked){
		intestazioni[column].className = "selected";
	}else{
		intestazioni[column].className = "not-selected";
	}

	var rows = this.table.getElementsByTagName("tr");
	for(var i=0;i<rows.length; i++){
		var tds = rows[i].getElementsByTagName("td");
		for(var j=0; j<tds.length; j++){
			if(j == column){
				if(checked){
					tds[j].className = "selected";
				}else{
					tds[j].className = "not-selected";
				}
			}	
		}
	}
}
PreviewTable.prototype.updateStartTime = function(value){
	var splittedTime = estraiOreMinuti(value);
	this.oraInizio = splittedTime;
	if(splittedTime == null)
		return;
	
	var minuti = calcolaMinuti(splittedTime[0],splittedTime[1]);
	console.log("Minuti: "+minuti);

	var flag = this.calcolaNumeroAppuntamenti(); // se il campio oraFine è già presente continua

	if(flag)
		return; // la funzione calcolaNumeroAppuntamenti() ha già aggioranto la tabella

	var tds = this.table.getElementsByTagName("td");
	// se c'è rimuovo l'eventuale nodo testuale figlio
	var text = tds[0].firstChild;
	if(text){
		text.remove();
	}
	var newTextNode = document.createTextNode(value);
	tds[0].appendChild(newTextNode);	
}
/* Funzione che cancella le righe successive alla prima*/
PreviewTable.prototype.deleteRows = function(){
	var rows = this.table.getElementsByTagName("tr");
	//console.log("righe esistenti: "+rows.length); // DEBUG
	for(var i=rows.length-1; i>1; i--){
		var ele = rows[i];
		ele.remove();
		this.righe--;
	}
}
PreviewTable.prototype.addRows = function(value){
	var num = value - 1; // considera che la prima riga (quella con l'orario di apertura è sempre presente)
	this.righe+=num;
	for(var i=0; i<num; i++){
		var tr = document.createElement("tr");
		for(var j=0; j<8;j++){
			var td = document.createElement("td");
			if(j==0)
				td.className = "selected";
			else{
				if(this.selectedColumns[j-1]){
					td.className = "selected";
				}else
					td.className = "not-selected";
			}
			tr.appendChild(td);
		}
		this.table.appendChild(tr);
	}	
}
PreviewTable.prototype.updateCloseTime = function(value){
	var splittedTime = estraiOreMinuti(value);
	this.oraFine = splittedTime;
	if(splittedTime == null)
		return;
	var minuti = calcolaMinuti(splittedTime[0],splittedTime[1]);
	console.log("Minuti: "+minuti);

	this.calcolaNumeroAppuntamenti();

	
	//aggiornaTabellaPreview();
	
}
PreviewTable.prototype.updateAppointmentDuration = function(value){
	this.durataAppuntamenti = parseInt(value);
	this.calcolaNumeroAppuntamenti();
	console.log("Durata: "+this.durataAppuntamenti+" min"); // DEBUG
}
function estraiOreMinuti(value){
	var splittedTime = value.split(":",2);
	if(splittedTime[0] != null && splittedTime[1] != null){
		return splittedTime;
	}else{
		return null;
	}
}
function calcolaMinuti(ore,minuti){
	var h = parseInt(ore);
	var m = parseInt(minuti);
	return ((h*60)+m);
}
PreviewTable.prototype.calcolaNumeroAppuntamenti = function(){
	if(this.oraFine == null || this.oraInizio == null)
		return false;
	var minutiOraFine = calcolaMinuti(this.oraFine[0], this.oraFine[1]);
	var minutiOraInizio = calcolaMinuti(this.oraInizio[0], this.oraInizio[1]);
	var differenza = minutiOraFine-minutiOraInizio;
	if(differenza <= 0)
		return false;
	console.log("Diff: "+differenza);
	this.numeroAppuntamenti = Math.floor(differenza / this.durataAppuntamenti);
	console.log("Num appuntamenti: "+this.numeroAppuntamenti);

	this.deleteRows();
	this.addRows(this.numeroAppuntamenti);
	return true;
}