/*Implementa lo scorrimento da sinistra verso destra di un elemento del dom*/
function slide(element, startPosition, endPosition){
	var timer = setInterval(function(){
		if(startPosition >= endPosition-0.1){
			clearInterval(timer);
			startPosition = endPosition;
		}
		element.style.left = startPosition+'%';
		startPosition += (Math.abs(startPosition))*0.1;
		//element.style.display='block';
	}, 20);

}