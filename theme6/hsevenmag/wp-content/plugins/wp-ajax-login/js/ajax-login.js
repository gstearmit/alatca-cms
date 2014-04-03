var slCurrentTab	= '';

function slRecaptchaCreate( tab ) {

	jQuery( '.ajax-div-captcha' ).html( '' );
	jQuery( '#ajax-' + tab + '-recaptcha' ).html( 
		'<div id="ajax-recaptcha-container">' + 
			'<div id="recaptcha_image"></div>' +
			'<div>' +
				'<a href="javascript:Recaptcha.reload()">' + ajaxwp_loginl_data.captcha_get + '</a>' +
			'</div>' +
			'<!--[if IE ]><label for="recaptcha_response_field">' + ajaxwp_loginl_data.captcha_enter + ':</label><![endif]-->' +
			'<input type="text" id="recaptcha_response_field" tabindex="423" name="recaptcha_response_field" placeholder="' + ajaxwp_loginl_data.captcha_enter + '..." required/>' + 
		'</div>'
	);
	
	Recaptcha.create(
		ajaxwp_loginl_data.public_key,
		'ajax-recaptcha-container',
		{
			theme: "custom"
		}
	);
	
}

function slTabChange( tab ) {
	
	if ( slCurrentTab == tab )
		return false;
	else
		slCurrentTab	= tab;
		
	jQuery( '.ajax-login-form-wrap' ).hide();
	jQuery( '#ajax-login-' + tab + '-form' ).show();
	jQuery( '.ajax-login-tab' ).removeClass( 'selected' );
	jQuery( 'a[name=al-tab-' + tab + ']' ).addClass( 'selected' );
	
	Recaptcha.destroy();
	
	if ( tab != 'lostpwd' &&  jQuery( '#ajax-' + tab + '-recaptcha' ).css( 'display' ) == 'block' )
		slRecaptchaCreate( tab );
		
	jQuery( '#al-first-input-' + tab ).focus();
	jQuery( '.al-input' ).val('');
	
}

function slBlockTab( errorDiv, wrapDiv ) {
	
	errorDiv.slideUp('fast');
	wrapDiv.block({ 
		message: '<img src="' + ajaxwp_loginl_data.loadingurl + '" />', 
		overlayCSS: { 
			backgroundColor: '#fff', 
			opacity:         0.6 
		},
		css: {
			padding:    0,
			margin:     0,
			width:      '30%',
			left:       '35%',
			textAlign:  'center',
			color:      '#000',
			border:     'none',
			cursor:     'wait'
		}
	});	

}

jQuery(	function() {

	var wrapDiv			= jQuery( '#ajax-login-wrap' );
	var errorDiv		= jQuery( '#ajax-login-show-error' );
 
	if ( jQuery( '#ajax-login-recaptcha' ).css( 'display' ) == 'block' )
		slTabChange( 'login' );
	
	jQuery('#ajax-login-wrap a[name=al-tab-login]').click( function(e) {
		
		e.preventDefault();
		slTabChange( 'login' );
		
	});
	
	jQuery('#ajax-login-wrap a[name=al-tab-lostpwd]').click( function(e) {
		
		e.preventDefault();
		slTabChange( 'lostpwd' );
		
	});
	
	jQuery('#ajax-login-wrap a[name=al-tab-registration]').click( function(e) {
		
		e.preventDefault();
		slTabChange( 'registration' );
		
	});

	jQuery('#ajax-login-wrap #al-login-form').submit( function( e ) {
				
		e.preventDefault();
		
		var captchaDiv	= jQuery( '#ajax-login-recaptcha' );
		
		jQuery.ajax({
		
			url:		ajaxwp_loginl_data.ajaxurl,
			data:		jQuery( this ).serialize() + '&action=ajaxwp_login_hook',
			type:		'POST',
			dataType:	'json',
			
			beforeSend: function() {
			
				slBlockTab( errorDiv, wrapDiv );
		
			},
			
			success: function( result ) {
				
				if ( result.success == 1 ) {
				
					window.location = result.redirect;
					
				} else {
				
					wrapDiv.unblock();
					errorDiv.html(result.error);

					if ( result.captcha ) {
						
						if ( jQuery( '#ajax-login-recaptcha' ).css( 'display' ) == 'none' ) {
							
							jQuery( '#ajax-login-recaptcha' ).show();
							slRecaptchaCreate( 'login' );							
							
						} else {
							Recaptcha.reload();
						}
						
					}
					
					errorDiv.slideDown('fast');
						
				}
				
			},
			
			error: function( xhr, textStatus, errorThrown ) {
				
				wrapDiv.html( 'ERROR' );
				
			}
			
		});
	
	}); // END al-login-form submit.
	
	jQuery('#ajax-login-wrap #al-registration-form').submit( function( e ) {
				
		e.preventDefault();
		
		jQuery.ajax({
		
			url:		ajaxwp_loginl_data.ajaxurl,
			data:		jQuery( this ).serialize() + '&action=ajax_registration_hook',
			type:		'POST',
			dataType:	'json',
			
			beforeSend: function() {
				
				slBlockTab( errorDiv, wrapDiv );
				
			},
			
			success: function( result ) {
				
				if ( result.success == 1 ) {
				
					wrapDiv.unblock();
					errorDiv.html(result.message);
					errorDiv.slideDown('fast');
					slTabChange( 'login' );
					
				} else {
					
					wrapDiv.unblock();
					
					errorDiv.html(result.error);
					
					if ( jQuery( '#ajax-registration-recaptcha' ).css( 'display' ) == 'block' )	
						Recaptcha.reload();
						
					errorDiv.slideDown('fast');	
						
				}
				
			},
			
			error: function( xhr, textStatus, errorThrown ) {
				
				wrapDiv.html( 'ERROR' );
				
			}
			
		});
	
	}); // END al-registration-form
	
	jQuery('#ajax-login-wrap  #al-lostpwd-form').submit( function( e ) {
				
		e.preventDefault();
		
		jQuery.ajax({
		
			url:		ajaxwp_loginl_data.ajaxurl,
			data:		jQuery( this ).serialize() + '&action=ajax_lostpwd_hook',
			type:		'POST',
			dataType:	'json',
			
			beforeSend: function() {
			
				slBlockTab( errorDiv, wrapDiv );
		
			},
			
			success: function( result ) {
				
				if ( result.success == 1 ) {
				
					wrapDiv.unblock();
					errorDiv.html(result.message);
					errorDiv.slideDown('fast');
					slTabChange( 'login' );
					
				} else {
					
					wrapDiv.unblock();
					errorDiv.html(result.error);
					errorDiv.slideDown('fast');
						
				}
				
			},
			
			error: function( xhr, textStatus, errorThrown ) {
				
				wrapDiv.html( 'ERROR' );
				
			}
			
		});
	
	}); // END al-lostpwd-form submit
	
}); // END on document ready.
