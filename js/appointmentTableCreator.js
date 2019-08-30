/*Uso il terzo approccio descritto nelle slide per associare eventi agli oggetti del DOM*/

function begin(){
	var inputs = document.getElementsByTagName("input");
	for(var i=0; i<inputs.length; i++){
		var elem = inputs[i];
		var gestore = new Function("cambia(this)");
		switch(elem.type){
			case "time":
				elem.onblur = gestore;
				break;
			default:
				elem.onchange = gestore;
				break;
		}
	}
}
function cambia(elem){
	switch(elem.name){
		case "work_days":
			aggiornaTabellaPreview(elem);
			break;
		case "opening_time":
			console.log("opening_time: "+elem.value);
			aggiornaOrarioApertura(elem.value);	
			break;
		default:
			break;
	}
}
function aggiornaTabellaPreview(elem){
	
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


