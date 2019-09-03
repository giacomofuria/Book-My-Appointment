function confirmAppointment(dataAppuntamento, oraAppuntamento, applyingUser, receiverUser, duration){
	var formContainer = document.getElementById("confirm_form_container");
	formContainer.style.display = 'block';
	var appyingUserElem = document.getElementById("applying_user");
	var receiverUserElem = document.getElementById("receveir_user");
	var dataInputElem = document.getElementById("confirm_data_appointment");
	var oraInputElem = document.getElementById("confirm_hour_appointment");
	var durataInputElem = document.getElementById("confirm_duration_appointment");
	appyingUserElem.value = applyingUser;
	receiverUserElem.value = receiverUser;
	dataInputElem.value = dataAppuntamento;
	oraInputElem.value = oraAppuntamento;
	durataInputElem.value = duration;
}