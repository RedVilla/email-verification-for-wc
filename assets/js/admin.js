/* zorem_snackbar jquery */
(function( $ ){
	$.fn.zorem_snackbar = function(msg) {
		var zorem_snackbar = $("<div></div>").addClass('zorem_snackbar show_snackbar').text( msg );
		$("body").append(zorem_snackbar);

		setTimeout(function(){ zorem_snackbar.remove(); }, 3000);

		return this;
	};
})( jQuery );

jQuery(document).on("click", ".accordion-label, .accordion-open", function(){
	var accordion = jQuery(this).closest('.accordion');	
	toggle_accordion(accordion);	
});

function toggle_accordion( accordion ){
	if ( accordion.hasClass( 'active' ) ) {				
		accordion.removeClass( 'active' );
		accordion.siblings( '.panel' ).removeClass( 'active' );
		accordion.siblings( '.panel' ).slideUp( 'slow' );
		jQuery( '.accordion' ).find('span.dashicons').addClass('dashicons-arrow-right-alt2');
		jQuery( '.accordion' ).find('.cev_settings_save').hide();		
	} else {		
		jQuery( '.accordion' ).removeClass( 'active' );
		jQuery(".accordion").find('.cev_settings_save').hide();
		jQuery(".accordion").find('span.dashicons').addClass('dashicons-arrow-right-alt2');	
		jQuery( '.panel' ).slideUp('slow');
		accordion.addClass( 'active' );
		accordion.siblings( '.panel' ).addClass( 'active' );
		accordion.find('span.dashicons').removeClass('dashicons-arrow-right-alt2');
		accordion.find('.cev_settings_save').show();				
		accordion.siblings( '.panel' ).slideDown( 'slow', function() {
			var visible = accordion.isInViewport();
			if ( !visible ) {
				jQuery('html, body').animate({
					scrollTop: accordion.prev().offset().top - 35
				}, 1000);	
			}
		} );		
		/**/		
	}
}

jQuery(document).on("change", "#cev_enable_email_verification", function(){
	
	var accordion = jQuery(this).closest('.accordion');			
	var form = jQuery("#cev_settings_form");	

	jQuery.ajax({
		url: ajaxurl,
		data: form.serialize(),
		type: 'POST',
		dataType:"json",	
		success: function() {	
			jQuery("#cev_settings_form").zorem_snackbar( 'Your Settings have been successfully saved.' );		
		},
		error: function(response) {
			console.log(response);			
		}
	});

	if (jQuery(this).is(':checked')) {
		accordion.find('.accordion-open').removeClass( 'disable_toggle' );		
		toggle_accordion(accordion);
	} else {
		if ( accordion.hasClass( 'active' ) ) {	
			toggle_accordion(accordion);
		}
		accordion.find('.accordion-open').addClass( 'disable_toggle' );		
	}
});

(function( $ ){
	$.fn.isInViewport = function( element ) {
		var win = $(window);
		var viewport = {
			top : win.scrollTop()			
		};
		viewport.bottom = viewport.top + win.height();
		
		var bounds = this.offset();		
		bounds.bottom = bounds.top + this.outerHeight();

		if( bounds.top >= 0 && bounds.bottom <= window.innerHeight) {
			return true;
		} else {
			return false;	
		}		
	};
})( jQuery );

jQuery(document).on("click", ".cev_settings_save", function(){
	
	var form = jQuery("#cev_settings_form");	
	var accordion = jQuery(this).closest('.accordion');
	accordion.find(".spinner").addClass("active");	
	
	jQuery.ajax({
		url: ajaxurl,
		data: form.serialize(),
		type: 'POST',
		dataType:"json",	
		success: function() {	
			form.find(".spinner").removeClass("active");
			jQuery("#cev_settings_form").zorem_snackbar( 'Your Settings have been successfully saved.' );
			jQuery( '.accordion' ).removeClass( 'active' );
			jQuery( '.accordion' ).find( '.cev_settings_save' ).hide();
			jQuery( '.accordion' ).find( 'span.dashicons' ).addClass( 'dashicons-arrow-right-alt2' );
			jQuery( '.panel' ).slideUp( 'slow' );		
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;
});


jQuery( document ).ready(function() {
	jQuery('#cev_verification_popup_overlay_background_color').wpColorPicker({
		change: function(e, ui) {
			jQuery('.cev_verification_widget_preview').prop("disabled", true);
		},
	});	
	jQuery(".woocommerce-help-tip").tipTip();
	
	jQuery('#cev_verification_popup_background_color').wpColorPicker({
		change: function(e, ui) {
			jQuery('.cev_verification_widget_preview').prop("disabled", true);
		},
	});
	
});


jQuery(document).on("click", ".cev_tab_input", function(){
	"use strict";
	var tab = jQuery(this).data('tab');
	var url = window.location.protocol + "//" + window.location.host + window.location.pathname+"?page=customer-email-verification-for-woocommerce&tab="+tab;
	window.history.pushState({path:url},'',url);	
});

jQuery(document).click(function(){
	var $trigger = jQuery(".cev_dropdown");
    if($trigger !== event.target && !$trigger.has(event.target).length){
		jQuery(".cev-dropdown-content").hide();
    }   
});

jQuery(document).on("click", ".cev-dropdown-menu", function(){	
	jQuery('.cev-dropdown-content').show();
});


jQuery(document).on("click", ".cev-dropdown-content li a", function(){
	var tab = jQuery(this).data('tab');
	var label = jQuery(this).data('label');
	var section = jQuery(this).data('section');
	jQuery('.inner_tab_section').hide();
	jQuery('.cev_nav_div').find("[data-tab='" + tab + "']").prop('checked', true); 
	jQuery('#'+section).show();
	jQuery('.zorem-layout-cev__header-breadcrumbs .header-breadcrumbs-last-cev').text(label);
	var url = window.location.protocol + "//" + window.location.host + window.location.pathname+"?page=customer-email-verification-for-woocommerce&tab="+tab;
	window.history.pushState({path:url},'',url);
	jQuery(".cev-dropdown-content").hide();
});

( function( $, data, wp, ajaxurl ) {
	"use strict";
		
	var $cev_verification_widget_settings_form = $("#cev_verification_widget_settings_form");	
			
	var cev_settings_pro_init = {
		
		init: function() {									
			$cev_verification_widget_settings_form.on( 'click', '.cev_verification_widget_settings_save', this.save_wc_cev_verification_widget_settings_form );					
		},

		save_wc_cev_verification_widget_settings_form: function( event ) {
			
			event.preventDefault();
			
			$cev_verification_widget_settings_form.find(".spinner").addClass("active");
			var ajax_data = $cev_verification_widget_settings_form.serialize();
			
			$.post( ajaxurl, ajax_data, function(response) {
					jQuery('.cev_verification_widget_preview').prop("disabled", false);

				$cev_verification_widget_settings_form.find(".spinner").removeClass("active");
				jQuery("#cev_verification_widget_settings_form").zorem_snackbar( 'Your Settings have been successfully saved.' );		
			});
			
		}
	};		
	
	$(window).load(function(e) {
        cev_settings_pro_init.init();
    });	
})( jQuery, customer_email_verification_script, wp, ajaxurl );

jQuery(document).on("click", ".cev_verification_widget_preview", function(){
	"use strict";	
	document.getElementById('cev_preview_iframe').contentDocument.location.reload(true);
	jQuery('#cev_preview_iframe').load(function(){
		jQuery('.cev_page_preview_popup').show();	
		var iframe = document.getElementById("cev_preview_iframe");
	});	
});

jQuery(document).on("click", ".cev-popup-close", function(){	
	"use strict";
	jQuery('.cev_page_preview_popup').hide();
});

jQuery(document).on("click", ".cev_popup_close_icon", function(){	
	jQuery('.cev_page_preview_popup').hide();	
});

jQuery( document ).on( "click", "#activity-panel-tab-help", function() {
	jQuery(this).addClass( 'is-active' );
	jQuery( '.woocommerce-layout__activity-panel-wrapper' ).addClass( 'is-open is-switching' );
});

jQuery(document).click(function(){
	var $trigger = jQuery(".woocommerce-layout__activity-panel");
    if($trigger !== event.target && !$trigger.has(event.target).length){
		jQuery('#activity-panel-tab-help').removeClass( 'is-active' );
		jQuery( '.woocommerce-layout__activity-panel-wrapper' ).removeClass( 'is-open is-switching' );
    }   
});
jQuery( document ).on( "click", ".close_btn", function() {
	jQuery( '.cev_pro_banner' ).hide();
});
// jQuery(document).ready(function() {

//     checkHideData();
	
//     // Handle button click
//     jQuery("#btn_dismiss").click(function() {
//       // Store the current timestamp in localStorage
//       var now = new Date().getTime();
//       localStorage.setItem("hideDataTimestamp", now);

//       // Hide the data
//       jQuery("#dataToDisplay").hide();
//     });
//   });

//   function checkHideData() {
//     // Retrieve the stored timestamp from localStorage
//     var storedTimestamp = localStorage.getItem("hideDataTimestamp");

//     // If the timestamp is not present or if it's older than 30 days, show the data
//     if (!storedTimestamp || isTimestampOlderThan30Days(storedTimestamp)) {
//       jQuery("#dataToDisplay").show();
//     } else {
//       jQuery("#dataToDisplay").hide();
//     }
//   }

//   function isTimestampOlderThan30Days(timestamp) {
//     var thirtyDaysAgo = new Date().getTime() - 30 * 24 * 60 * 60 * 1000; // 30 days in milliseconds
//     return timestamp < thirtyDaysAgo;
//   }
  // Get the current URL

