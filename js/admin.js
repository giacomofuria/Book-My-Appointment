var openHandler = new Function("open(this)");
var closeHandler = new Function("close(this)");
var openedFormContainer =  null;
var pushedButton=null;
function beginAdmin(){
	var buttonContainer = document.getElementById("tool_button_container");
	//var newUserButton = document.getElementById("new_user_button");
	
	var buttons = buttonContainer.getElementsByTagName("button");
	for(var i=0;i<buttons.length; i++){
		buttons[i].onclick=openHandler;
	}
}
function open(elem){
	if(openedFormContainer!=null){
		openedFormContainer.style.display="none";
		pushedButton.style.backgroundColor="#91DFAA";
	}
	elem.style.backgroundColor="#91DF00";
	elem.onclick=closeHandler;
	var toolContainer = document.getElementById(elem.value);
	openedFormContainer=toolContainer;
	pushedButton = elem;
	toolContainer.style.display = "block";
}
function close(elem){
	elem.style.backgroundColor="#91DFAA";
	var toolContainer = document.getElementById(elem.value);
	toolContainer.style.display = "none";
	elem.onclick=openHandler;
}
