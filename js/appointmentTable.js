/* Mostra la schermata di conferma di una prenotazione */
function confirmAppointment(dataAppuntamento, oraAppuntamento, applyingUser, receiverUser, duration){
	var formContainer = document.getElementById("confirm_form_container");
	var confirmAppointmentForm = document.getElementById("confirm-appointment-form");
	confirmAppointmentForm.style.display = 'block';
	formContainer.setAttribute("onclick","closeConfirmAppointmentBox()");
	formContainer.style.display = 'block';

	document.addEventListener('keydown', function(event) {
			if (event.keyCode == 27 || event.which == 27){
		        closeConfirmAppointmentBox();
		    }
    }, false);

	var appyingUserElem = document.getElementById("applying_user");
	var receiverUserElem = document.getElementById("receveir_user");
	var dataInputElem = document.getElementById("confirm_data_appointment");
	var oraInputElem = document.getElementById("confirm_hour_appointment");
	var durataInputElem = document.getElementById("confirm_duration_appointment");
	var confirmNotesAppointment = document.getElementById("confirm_notes_appointment");

	appyingUserElem.value = applyingUser;
	appyingUserElem.className = "input-text";
	receiverUserElem.value = receiverUser;
	receiverUserElem.className = "input-text";
	dataInputElem.value = dataAppuntamento;
	dataInputElem.className = "input-text";
	oraInputElem.value = oraAppuntamento;
	oraInputElem.className = "input-text";
	durataInputElem.value = duration;
	durataInputElem.className = "input-text";
	confirmNotesAppointment.className = "input-text";


}
/* Chiude la schermata di conferma di una prenotazione nel caso in cui venga premuto il tasto 
   exit, venga premuto il tasto esc della tastiera o si clicchi al di fuori della schermata */
function closeConfirmAppointmentBox(){
	var formContainer = document.getElementById("confirm_form_container");
	var confirmAppointmentForm = document.getElementById("confirm-appointment-form");
	confirmAppointmentForm.style.display = 'none';
	formContainer.style.display = 'none';
}