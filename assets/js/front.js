jQuery(document).on("submit", ".cev_pin_verification_form", function(){
	var form = jQuery(this);
	var error;	
	var cev_pin1 = form.find("#cev_pin1");
	
	if( cev_pin1.val() === '' ){
		jQuery('.required-filed').html('<span class="cev-error-display">4-digits code*</span>');		
		showerror( cev_pin1 );error = true;
	} else{
		hideerror(cev_pin1);
	}
	
	if(error == true){
		return false;
	}
	jQuery(".cev_pin_verification_form ").block({
		message: null,
		overlayCSS: {
			background: "#fff",
			opacity: .6
		}	
    });

	
	jQuery.ajax({
		url: cev_ajax_object.ajax_url,		
		data: form.serialize(),
		type: 'POST',
		dataType: "json",
		success: function(response) {
			if(response.success == 'true'){
				window.location.href = response.url;
			} else{
				jQuery('.cev-pin-verification__failure').show();
			}
			jQuery(".cev_pin_verification_form ").unblock();				
		},
		error: function(jqXHR, exception) {
			console.log(jqXHR.status);						
		}
	});
	return false;
});
function showerror(element){
	element.css("border-color","red");
}
function hideerror(element){
	element.css("border-color","");
}
function getCodeBoxElement(index) {
  return document.getElementById('cev_pin' + index);
}
function onKeyUpEvent(index, event) {
  const eventCode = event.which || event.keyCode;
  if (getCodeBoxElement(index).value.length === 1) {
	 if (index !== 4) {
		getCodeBoxElement(index+ 1).focus();
	 } else {
		getCodeBoxElement(index).blur();
		// Submit code		
	 }
  }
  if (eventCode === 8 && index !== 1) {
	 getCodeBoxElement(index - 1).focus();
  }
}
function onFocusEvent(index) {
  for (item = 1; item < index; item++) {
	 const currentElement = getCodeBoxElement(item);
	 if (!currentElement.value) {
		  currentElement.focus();
		  break;
	 }
  }
}
