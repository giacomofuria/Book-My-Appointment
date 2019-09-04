function showConfirmBox(){
	var elem = document.getElementById('confirm-box');
	slideDown(elem,-30,0);
	setTimeout(function(){
		slideUp(elem, 0,-30);

	},3000);
	setTimeout(function(){
		elem.remove();
	},4000);
	
}