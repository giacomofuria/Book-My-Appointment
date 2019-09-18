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

function slideDown(element, startPosition, endPosition){
	var timer = setInterval(function(){
		if(startPosition >= endPosition-0.1){
			clearInterval(timer);
			startPosition = endPosition;
		}
		element.style.top = startPosition+'%';
		startPosition++;
		//element.style.display='block';
	}, 20);
}
//                              0            -30
function slideUp(element, startPosition, endPosition){
	var timer = setInterval(function(){
		if(startPosition <= endPosition){
			clearInterval(timer);
			startPosition = endPosition;
		}
		element.style.top = startPosition+'%';
		startPosition--;
	}, 20);
}
function slideLeft(element, startPosition, endPosition){
	var timer = setInterval(function(){
		if(startPosition >= endPosition-0.1){
			clearInterval(timer);
			startPosition = endPosition;
		}
		element.style.right = startPosition+'%';
		startPosition += (Math.abs(startPosition))*0.1;
		//element.style.display='block';
	}, 20);
}