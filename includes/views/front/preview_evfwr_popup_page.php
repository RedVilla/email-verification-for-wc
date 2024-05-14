<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
$email = __( 'johny@example.com', 'customer-email-verification-for-woocommerce' );
$Try_again = __( 'Try Again', 'customer-email-verification-for-woocommerce' );

$evfwr_verification_widget_style = new evfwr_verification_widget_message();
$evfwr_button_color_widget_header =  get_option( 'evfwr_button_color_widget_header', '#212121' );
$evfwr_button_text_color_widget_header =  get_option( 'evfwr_button_text_color_widget_header', '#ffffff' );
$evfwr_button_text_size_widget_header =  get_option( 'evfwr_button_text_size_widget_header', '14' );
$evfwr_widget_header_image_width =  get_option( 'evfwr_widget_header_image_width', '150' );
$evfwr_button_text_header_font_size = get_option( 'evfwr_button_text_header_font_size', '22' );	
?>

<div class="evfwr-authorization-grid__visual">
	<div class="evfwr-authorization-grid__holder">
		<div class="evfwr-authorization-grid__inner">
			<div class="evfwr-authorization" style="background: <?php esc_html_e( get_option( 'evfwr_verification_popup_background_color', $evfwr_verification_widget_style->defaults['evfwr_verification_popup_background_color'] ) ); ?>;">				
				<form class="evfwr_pin_verification_form" method="post">    
					<section class="evfwr-authorization__holder">
						<div class="popup_image" style="width:<?php esc_html_e( $evfwr_widget_header_image_width ); ?>px;">	                                 
							<?php 
							$image = get_option( 'evfwr_verification_image', woo_customer_email_verification()->plugin_dir_url() . 'assets/css/images/email-verification-icon.svg' );
							if ( !empty( $image ) ) {
								?>
								<img src="<?php echo esc_url( $image ); ?>">
							<?php } ?>
						</div>
						<div class="evfwr-authorization__heading">
							<span class="evfwr-authorization__title" style="font-size: <?php esc_html_e( $evfwr_button_text_header_font_size ); ?>px;">
								<?php 
								$evfwr_verification_widget_message = new evfwr_verification_widget_message();
								$heading_default = __( 'Verify its you.', 'customer-email-verification-for-woocommerce' );
								$heading = get_option( 'evfwr_verification_header', $evfwr_verification_widget_message->defaults['evfwr_verification_header'] );
								if ( !empty( $heading ) ) {
									echo wp_kses_post( $heading );
								} else {
									echo wp_kses_post( $heading_default );
								}
								?>
							</span>
							<span class="evfwr-authorization__description">
								<?php
								/* translators: %s: search with $email */
								$message = sprintf(__( 'We sent verification code to <strong>%s</strong>. To verify your email address, please check your inbox and enter the code below.', 'customer-email-verification-for-woocommerce' ), $email); 
								$message = apply_filters( 'evfwr_verification_popup_message', $message, $email );
								echo wp_kses_post( $message );
								?>
							</span>
						</div>
						<div class="evfwr-pin-verification">								
							<div class="evfwr-pin-verification__row">
								<div class="evfwr-field evfwr-field_size_extra-large evfwr-field_icon_left evfwr-field_event_right evfwr-field_text_center">
									<h5 class="required-filed"><?php esc_html_e( apply_filters( 'evfwr_verification_code_length', __( '4-digits code', 'customer-email-verification-for-woocommerce' ) ) ); ?>*</h5>
									<input class="evfwr_pin_box" id="evfwr_pin1" name="evfwr_pin1" type="text" placeholder="Enter <?php esc_html_e( apply_filters( 'evfwr_verification_code_length', __( '4-digits code', 'customer-email-verification-for-woocommerce' ) ) ); ?>">
								</div>
							</div>
							<div class="evfwr-pin-verification__failure js-pincode-invalid" style="display: none;">
								<div class="evfwr-alert evfwr-alert_theme_red">										
									<span class="js-pincode-error-message"><?php esc_html_e( 'Verification code does not match', 'customer-email-verification-for-woocommerce' ); ?></span>
								</div>
							</div>
							<div class="evfwr-pin-verification__events">
								<input type="hidden" name="evfwr_user_id" value="8">
								<input type="hidden" name="action" value="evfwr_verify_user_email_with_pin">
								<button class="evfwr-button  evfwr-button_size_promo evfwr-button_type_block evfwr-pin-verification__button is-disabled" type="submit" style="background-color:<?php esc_html_e(  $evfwr_button_color_widget_header ); ?>; color:<?php esc_html_e( $evfwr_button_text_color_widget_header ); ?>; font-size:<?php esc_html_e( $evfwr_button_text_size_widget_header ); ?>px;" >
									<?php esc_html_e( 'Verify Code', 'customer-email-verification-for-woocommerce' ); ?><i class="evfwr-icon evfwr-icon_size_medium dmi-continue_arrow_24 evfwr-button__visual evfwr-button__visual_type_fixed"></i>
								</button>									
							</div>
						</div>
					</section>
					<footer class="evfwr-authorization__footer">
						
						<?php 
						$footer_message_default = __( 'Didn’t receive an email? Try Again', 'customer-email-verification-for-woocommerce' );
						/* translators: %s: search with Try_again */
						$footer_message = sprintf(__( 'Didn’t receive an email? <strong>%s</strong>', 'customer-email-verification-for-woocommerce'), $Try_again );
						$footer_message = get_option( 'evfwr_verification_widget_footer', $footer_message, $Try_again );
						$footer_message = str_replace( '{evfwr_resend_verification}', $Try_again, $footer_message );
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

$evfwr_verification_overlay_color = get_option( 'evfwr_verification_popup_overlay_background_color', $evfwr_verification_widget_style->defaults['evfwr_verification_popup_overlay_background_color'] );
?>
<style>
	.evfwr-authorization-grid__visual{
		background-color: <?php esc_html_e( woo_customer_email_verification()->hex2rgba( $evfwr_verification_overlay_color, '0.7' ) ); ?>;	
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
