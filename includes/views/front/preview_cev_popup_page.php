<?php
$email = __( 'johny@example.com', 'customer-email-verification-for-woocommerce' );
$Try_again = __( 'Try Again', 'customer-email-verification-for-woocommerce' );

$cev_verification_widget_style = new cev_verification_widget_message();
$cev_button_color_widget_header =  get_option( 'cev_button_color_widget_header', '#212121' );
$cev_button_text_color_widget_header =  get_option( 'cev_button_text_color_widget_header', '#ffffff' );
$cev_button_text_size_widget_header =  get_option( 'cev_button_text_size_widget_header', '14' );
$cev_widget_header_image_width =  get_option( 'cev_widget_header_image_width', '150' );
$cev_button_text_header_font_size = get_option( 'cev_button_text_header_font_size', '22' );	
?>

<div class="cev-authorization-grid__visual">
	<div class="cev-authorization-grid__holder">
		<div class="cev-authorization-grid__inner">
			<div class="cev-authorization" style="background: <?php esc_html_e( get_option( 'cev_verification_popup_background_color', $cev_verification_widget_style->defaults['cev_verification_popup_background_color'] ) ); ?>;">				
				<form class="cev_pin_verification_form" method="post">    
					<section class="cev-authorization__holder">
						<div class="popup_image" style="width:<?php esc_html_e( $cev_widget_header_image_width ); ?>px;">	                                 
							<?php 
							$image = get_option( 'cev_verification_image', woo_customer_email_verification()->plugin_dir_url() . 'assets/css/images/email-verification-icon.svg' );
							if ( !empty( $image ) ) {
								?>
								<img src="<?php echo esc_url( $image ); ?>">
							<?php } ?>
						</div>
						<div class="cev-authorization__heading">
							<span class="cev-authorization__title" style="font-size: <?php esc_html_e( $cev_button_text_header_font_size ); ?>px;">
								<?php 
								$cev_verification_widget_message = new cev_verification_widget_message();
								$heading_default = __( 'Verify its you.', 'customer-email-verification-for-woocommerce' );
								$heading = get_option( 'cev_verification_header', $cev_verification_widget_message->defaults['cev_verification_header'] );
								if ( !empty( $heading ) ) {
									echo wp_kses_post( $heading );
								} else {
									echo wp_kses_post( $heading_default );
								}
								?>
							</span>
							<span class="cev-authorization__description">
								<?php
								/* translators: %s: search with $email */
								$message = sprintf(__( 'We sent verification code to <strong>%s</strong>. To verify your email address, please check your inbox and enter the code below.', 'customer-email-verification-for-woocommerce' ), $email); 
								$message = apply_filters( 'cev_verification_popup_message', $message, $email );
								echo wp_kses_post( $message );
								?>
							</span>
						</div>
						<div class="cev-pin-verification">								
							<div class="cev-pin-verification__row">
								<div class="cev-field cev-field_size_extra-large cev-field_icon_left cev-field_event_right cev-field_text_center">
									<h5 class="required-filed"><?php esc_html_e( apply_filters( 'cev_verification_code_length', __( '4-digits code', 'customer-email-verification-for-woocommerce' ) ) ); ?>*</h5>
									<input class="cev_pin_box" id="cev_pin1" name="cev_pin1" type="text" placeholder="Enter <?php esc_html_e( apply_filters( 'cev_verification_code_length', __( '4-digits code', 'customer-email-verification-for-woocommerce' ) ) ); ?>">
								</div>
							</div>
							<div class="cev-pin-verification__failure js-pincode-invalid" style="display: none;">
								<div class="cev-alert cev-alert_theme_red">										
									<span class="js-pincode-error-message"><?php esc_html_e( 'Verification code does not match', 'customer-email-verification-for-woocommerce' ); ?></span>
								</div>
							</div>
							<div class="cev-pin-verification__events">
								<input type="hidden" name="cev_user_id" value="8">
								<input type="hidden" name="action" value="cev_verify_user_email_with_pin">
								<button class="cev-button  cev-button_size_promo cev-button_type_block cev-pin-verification__button is-disabled" type="submit" style="background-color:<?php esc_html_e(  $cev_button_color_widget_header ); ?>; color:<?php esc_html_e( $cev_button_text_color_widget_header ); ?>; font-size:<?php esc_html_e( $cev_button_text_size_widget_header ); ?>px;" >
									<?php esc_html_e( 'Verify Code', 'customer-email-verification-for-woocommerce' ); ?><i class="cev-icon cev-icon_size_medium dmi-continue_arrow_24 cev-button__visual cev-button__visual_type_fixed"></i>
								</button>									
							</div>
						</div>
					</section>
					<footer class="cev-authorization__footer">
						
						<?php 
						$footer_message_default = __( 'Didn’t receive an email? Try Again', 'customer-email-verification-for-woocommerce' );
						/* translators: %s: search with Try_again */
						$footer_message = sprintf(__( 'Didn’t receive an email? <strong>%s</strong>', 'customer-email-verification-for-woocommerce'), $Try_again );
						$footer_message = get_option( 'cev_verification_widget_footer', $footer_message, $Try_again );
						$footer_message = str_replace( '{cev_resend_verification}', $Try_again, $footer_message );
						if ( !empty( $footer_message ) ) {
							echo wp_kses_post(  $footer_message ); 
						} else {
							echo wp_kses_post( $footer_message_default );
						}
						?>
					</footer>
				</form>            
			</div>
		</div>
	</div>
</div>
<?php 

$cev_verification_overlay_color = get_option( 'cev_verification_popup_overlay_background_color', $cev_verification_widget_style->defaults['cev_verification_popup_overlay_background_color'] );
?>
<style>
	.cev-authorization-grid__visual{
		background-color: <?php esc_html_e( woo_customer_email_verification()->hex2rgba( $cev_verification_overlay_color, '0.7' ) ); ?>;	
	}	
	html { 
		background: none;
	}
	footer#footer {
		display: none;
	}
	.customize-partial-edit-shortcut-button {
		display: none;
	}
</style>
