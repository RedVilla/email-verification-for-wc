<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="evfwr-authorization-grid__visual">
	<div class="evfwr-authorization-grid__holder ">
		<div class="evfwr-authorization-grid__inner">
			<div class="evfwr-authorization" style="background: <?php esc_html_e( get_option( 'evfwr_verification_popup_background_color', $evfwr_verification_widget_style->defaults['evfwr_verification_popup_background_color'] ) ); ?>;">
				<form class="evfwr_pin_verification_form"  method="post">                    					
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
								$heading = get_option( 'evfwr_verification_header', $evfwr_verification_widget_message->defaults['evfwr_verification_header'] );
								esc_html_e( $heading );
								?>
							</span>
							<span class="evfwr-authorization__description">
								<?php
								/* translators: %s: search email */
								$message = sprintf( __( 'We sent verification code to <strong>%s</strong>. To verify your email address, please check your inbox and enter the code below.', 'customer-email-verification-for-woocommerce' ), $email );
								$message = apply_filters( 'evfwr_verification_popup_message', $message, $email );
								esc_html_e( $message );
								?>
							</span>
						</div>
			
						<div class="evfwr-pin-verification">								
							<div class="evfwr-pin-verification__row">
								<div class="evfwr-field evfwr-field_size_extra-large evfwr-field_icon_left evfwr-field_event_right evfwr-field_text_center">									
									<h5 class="required-filed">
									<?php
									$codelength = apply_filters( 'evfwr_verification_code_length', __( '4-digits code', 'customer-email-verification-for-woocommerce' ) ); 
									esc_html_e( $codelength ); 
									?>
									*</h5>
									<?php $codelength = apply_filters( 'evfwr_verification_code_length', __( '4-digits code', 'customer-email-verification-for-woocommerce' ) ); ?>
									<input class="evfwr_pin_box" id="evfwr_pin1" name="evfwr_pin1" type="text" placeholder="Enter <?php esc_html_e( $codelength ); ?>" >
								</div>
							</div>
							<div class="evfwr-pin-verification__failure js-pincode-invalid" style="display: none;">
								<div class="evfwr-alert evfwr-alert_theme_red">										
									<span class="js-pincode-error-message"><?php esc_html_e( 'Verification code does not match', 'customer-email-verification-for-woocommerce' ); ?></span>
								</div>
							</div>
							<div class="evfwr-pin-verification__events">
								<input type="hidden" name="evfwr_user_id" value="<?php esc_html_e( get_current_user_id() ); ?>">
								<?php wp_nonce_field( 'evfwr_verify_user_email_with_pin', 'evfwr_verify_user_email_with_pin' ); ?>
								<input type="hidden" name="action" value="evfwr_verify_user_email_with_pin">
								<button class="evfwr-button  evfwr-button_size_promo evfwr-button_type_block evfwr-pin-verification__button is-disabled" id="SubmitPinButton" type="submit" style="background-color:<?php esc_html_e( $evfwr_button_color_widget_header ); ?>; color:<?php esc_html_e( $evfwr_button_text_color_widget_header ); ?>; font-size:<?php esc_html_e( $evfwr_button_text_size_widget_header ); ?>px;">
									<?php esc_html_e( 'Verify code', 'customer-email-verification-for-woocommerce' ); ?>
								</button>									
							</div>
						</div>
					</section>
					<footer class="evfwr-authorization__footer">
					<?php
					$evfwr_verification_widget_message = new evfwr_verification_widget_message();
					$footer_message = get_option( 'evfwr_verification_widget_footer', $evfwr_verification_widget_message->defaults['evfwr_verification_widget_footer'] );
					$footer_message = WC_customer_email_verification_email_Common()->maybe_parse_merge_tags( $footer_message );			
					echo wp_kses_post( $footer_message );
					?>
					</footer>
				</form>            
			</div>
		</div>
	</div>
</div>
