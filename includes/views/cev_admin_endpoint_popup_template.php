<div class="cev-authorization-grid__visual">
	<div class="cev-authorization-grid__holder ">
		<div class="cev-authorization-grid__inner">
			<div class="cev-authorization" style="background: <?php esc_html_e( get_option( 'cev_verification_popup_background_color', $cev_verification_widget_style->defaults['cev_verification_popup_background_color'] ) ); ?>;">
				<form class="cev_pin_verification_form"  method="post">                    					
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
								$heading = get_option( 'cev_verification_header', $cev_verification_widget_message->defaults['cev_verification_header'] );
								esc_html_e( $heading );
								?>
							</span>
							<span class="cev-authorization__description">
								<?php
								/* translators: %s: search email */
								$message = sprintf( __( 'We sent verification code to <strong>%s</strong>. To verify your email address, please check your inbox and enter the code below.', 'customer-email-verification-for-woocommerce' ), $email );
								$message = apply_filters( 'cev_verification_popup_message', $message, $email );
								esc_html_e( $message );
								?>
							</span>
						</div>
			
						<div class="cev-pin-verification">								
							<div class="cev-pin-verification__row">
								<div class="cev-field cev-field_size_extra-large cev-field_icon_left cev-field_event_right cev-field_text_center">									
									<h5 class="required-filed">
									<?php
									$codelength = apply_filters( 'cev_verification_code_length', __( '4-digits code', 'customer-email-verification-for-woocommerce' ) ); 
									esc_html_e( $codelength ); 
									?>
									*</h5>
									<?php $codelength = apply_filters( 'cev_verification_code_length', __( '4-digits code', 'customer-email-verification-for-woocommerce' ) ); ?>
									<input class="cev_pin_box" id="cev_pin1" name="cev_pin1" type="text" placeholder="Enter <?php esc_html_e( $codelength ); ?>" >
								</div>
							</div>
							<div class="cev-pin-verification__failure js-pincode-invalid" style="display: none;">
								<div class="cev-alert cev-alert_theme_red">										
									<span class="js-pincode-error-message"><?php esc_html_e( 'Verification code does not match', 'customer-email-verification-for-woocommerce' ); ?></span>
								</div>
							</div>
							<div class="cev-pin-verification__events">
								<input type="hidden" name="cev_user_id" value="<?php esc_html_e( get_current_user_id() ); ?>">
								<?php wp_nonce_field( 'cev_verify_user_email_with_pin', 'cev_verify_user_email_with_pin' ); ?>
								<input type="hidden" name="action" value="cev_verify_user_email_with_pin">
								<button class="cev-button  cev-button_size_promo cev-button_type_block cev-pin-verification__button is-disabled" id="SubmitPinButton" type="submit" style="background-color:<?php esc_html_e( $cev_button_color_widget_header ); ?>; color:<?php esc_html_e( $cev_button_text_color_widget_header ); ?>; font-size:<?php esc_html_e( $cev_button_text_size_widget_header ); ?>px;">
									<?php esc_html_e( 'Verify code', 'customer-email-verification-for-woocommerce' ); ?>
								</button>									
							</div>
						</div>
					</section>
					<footer class="cev-authorization__footer">
					<?php
					$cev_verification_widget_message = new cev_verification_widget_message();
					$footer_message = get_option( 'cev_verification_widget_footer', $cev_verification_widget_message->defaults['cev_verification_widget_footer'] );
					$footer_message = WC_customer_email_verification_email_Common()->maybe_parse_merge_tags( $footer_message );			
					echo wp_kses_post( $footer_message );
					?>
					</footer>
				</form>            
			</div>
		</div>
	</div>
</div>
