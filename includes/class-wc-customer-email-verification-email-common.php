<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Customer_Email_Verification_Email_Common {	

	public $wuev_user_id = null;
	public $wuev_myaccount_page_id = null;
	
	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
				
	}
	
	public function init() {	
		add_filter( 'wc_cev_decode_html_content', array( $this, 'wc_cev_decode_html_content' ), 1 );
		add_filter( 'verification_email_email_body', array( $this, 'content_do_shortcode' ) );
	}

	public function code_mail_sender( $email ) {
				
		$verification_pin = $this->generate_verification_pin();
		$cev_initialise_customizer_settings = new cev_initialise_customizer_settings();		
		
		$user_id = $this->wuev_user_id;	
		
		$expire_time =  get_option('cev_verification_code_expiration', 'never');
		
		if ( empty( $expire_time ) ) {
			$expire_time = 'never';
		}
		
		$verification_data = array(
			'pin' => $verification_pin, 
			'startdate' => time(),
			'enddate' => time() + (int) $expire_time,
		);		

		update_user_meta( $user_id, 'cev_email_verification_pin', $verification_data );		
		
		$result = false;		
		
		$email_subject = get_option( 'cev_verification_email_subject', $cev_initialise_customizer_settings->defaults['cev_verification_email_subject'] );
		$email_subject = $this->maybe_parse_merge_tags( $email_subject );
		
		$email_heading = get_option( 'cev_verification_email_heading', $cev_initialise_customizer_settings->defaults['cev_verification_email_heading'] );
		
		$mailer = WC()->mailer();
		ob_start();
	
		//do_action( 'woocommerce_email_header',  $email_heading,  $email ); 	
		$mailer->email_header( $email_heading, $email );		
		$email_body = get_option( 'cev_verification_email_body', $cev_initialise_customizer_settings->defaults['cev_verification_email_body'] );
		$email_body = $this->maybe_parse_merge_tags( $email_body );
		$email_body = apply_filters( 'cev_verification_email_content', $email_body );
		$email_body = wpautop( $email_body );
		$email_body = wp_kses_post( $email_body );
		echo wp_kses_post( $email_body );
		
		$mailer->email_footer( $email );
		
		$email_body = ob_get_clean();
		$email_abstract_object = new WC_Email();
		
		
		$email_body = apply_filters( 'woocommerce_mail_content', $email_abstract_object->style_inline( wptexturize( $email_body ) ) );		
			
		$email_body = apply_filters( 'wc_cev_decode_html_content', $email_body );		
		
		$result = $mailer->send( $email, $email_subject, $email_body );

		return $result;
	}

	public function content_do_shortcode( $content ) {
		return do_shortcode( $content );
	}	
	
	/*
	 * This function removes backslashes from the textfields and textareas of the plugin settings.
	 */
	public function wc_cev_decode_html_content( $content ) {
		if ( empty( $content ) ) {
			return '';
		}
		$content = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $content );

		return html_entity_decode( stripslashes( $content ) );
	}
	/**
	 * Maybe try and parse content to found the xlwuev merge tags
	 * And converts them to the standard wp shortcode way
	 * So that it can be used as do_shortcode in future
	 *
	 * @param string $content
	 *
	 * @return mixed|string
	 */
	public function maybe_parse_merge_tags( $content = '' ) {
		$get_all      = $this->get_all_tags();
		$get_all_tags = wp_list_pluck( $get_all, 'tag' );

		//iterating over all the merge tags
		if ( $get_all_tags && is_array( $get_all_tags ) && count( $get_all_tags ) > 0 ) {
			foreach ( $get_all_tags as $tag ) {
				$matches = array();
				$re      = sprintf( '/\{{%s(.*?)\}}/', $tag );
				$str     = $content;

				//trying to find match w.r.t current tag
				preg_match_all( $re, $str, $matches );

				//if match found
				if ( $matches && is_array( $matches ) && count( $matches ) > 0 ) {

					//iterate over the found matches
					foreach ( $matches[0] as $exact_match ) {

						//preserve old match
						$old_match        = $exact_match;
						$single           = str_replace( '{{', '', $old_match );
						$single           = str_replace( '}}', '', $single );
						$get_parsed_value = call_user_func( array( __CLASS__, $single ) );
						$content          = str_replace( $old_match, $get_parsed_value, $content );
					}
				}
			}
		}
		if ( $get_all_tags && is_array( $get_all_tags ) && count( $get_all_tags ) > 0 ) {
			foreach ( $get_all_tags as $tag ) {
				$matches = array();
				$re      = sprintf( '/\{%s(.*?)\}/', $tag );
				$str     = $content;

				//trying to find match w.r.t current tag
				preg_match_all( $re, $str, $matches );

				//if match found
				if ( $matches && is_array( $matches ) && count( $matches ) > 0 ) {

					//iterate over the found matches
					foreach ( $matches[0] as $exact_match ) {

						//preserve old match
						$old_match        = $exact_match;
						$single           = str_replace( '{', '', $old_match );
						$single           = str_replace( '}', '', $single );
						$get_parsed_value = call_user_func( array( __CLASS__, $single ) );
						$content          = str_replace( $old_match, $get_parsed_value, $content );
					}
				}
			}
		}
		return $content;
	}

	/*
	 * Mergetag callback for showing sitename.
	 */

	public function get_all_tags() {
		$tags = array(
			array(
				'name' => __( 'User login', 'customer-email-verification-for-woocommerce' ),
				'tag'  => 'cev_user_login',
			),
			array(
				'name' => __( 'User display name', 'customer-email-verification-for-woocommerce' ),
				'tag'  => 'cev_display_name',
			),
			array(
				'name' => __( 'User email', 'customer-email-verification-for-woocommerce' ),
				'tag'  => 'cev_user_email',
			),
			array(
				'name' => __( 'Email Verification Link', 'customer-email-verification-for-woocommerce' ),
				'tag'  => 'cev_user_verification_link',
			),			
			array(
				'name' => __( 'Verification link', 'customer-email-verification-for-woocommerce' ),
				'tag'  => 'customer_email_verification_code',
			),
			array(
				'name' => __( 'Resend Confirmation Email', 'customer-email-verification-for-woocommerce' ),
				'tag'  => 'cev_resend_email_link',
			),	
			array(
				'name' => __( 'Verification Pin', 'customer-email-verification-for-woocommerce' ),
				'tag'  => 'cev_user_verification_pin',
			),
			array(
				'name' => __( 'Site Title', 'customer-email-verification-for-woocommerce' ),
				'tag'  => 'site_title',
			),
			array(
				'name' => __( 'Try Again', 'customer-email-verification-for-woocommerce' ),
				'tag'  => 'cev_resend_verification',
			),	
		);

		return $tags;
	}
	
	public function customer_email_verification_code() {
		$secret      = get_user_meta( $this->wuev_user_id, 'customer_email_verification_code', true );		
		return $secret;
	}
	
	public function cev_user_login() {
		$user = get_userdata( $this->wuev_user_id );		
		$user_login = $user->user_login ;	
		return $user_login;
	}
	
	public function cev_user_email() {
		$user = get_userdata( $this->wuev_user_id );		
		$user_email = $user->user_email ;	
		return $user_email;
	}
	
	public function cev_display_name() {		
		$user = get_userdata( $this->wuev_user_id );		
		$display_name = $user->display_name;

		return $display_name;
	}
	
	public function cev_user_verification_link() {		
		$cev_verification_selection = get_option('cev_verification_selection');	
		$secret      = get_user_meta( $this->wuev_user_id, 'customer_email_verification_code', true );
		$create_link = $secret . '@' . $this->wuev_user_id;
		$hyperlink   = add_query_arg( array(
			'cusomer_email_verify' => base64_encode( $create_link ),
		), get_the_permalink( $this->wuev_myaccount_page_id ) );	
		
		$style = 'text-decoration:  none ';
		$style = apply_filters( 'cev_user_verification_link_style', $style );
		
		if ( 'button' == $cev_verification_selection ) {
			$link = '<p style="display:inline-block;"><a style="' . $style . '" href="' . $hyperlink . '">' . get_option( 'cev_new_acoount_button_text', __( 'Verify your email', 'customer-email-verification-for-woocommerce' ) ) . '</a></p>';	
		} else {
			$link = '<p><a style="' . $style . '" href="' . $hyperlink . '">' . get_option( 'cev_new_acoount_link_text', __( 'Verify your email', 'customer-email-verification-for-woocommerce' ) ) . '</a></p>';	
		}
		
		return $link;
		
	}
	
	public function cev_resend_email_link() {
		$link = add_query_arg( array(
			'cev_redirect_limit_resend' => base64_encode( $this->wuev_user_id ),
		), get_the_permalink( $this->wuev_myaccount_page_id ) );
		$resend_confirmation_text = __( 'Resend confirmation email', 'customer-email-verification-for-woocommerce' );
		$cev_resend_link          = '<a href="' . $link . '">' . $resend_confirmation_text . '</a>';

		return $cev_resend_link;
	}
	
	public function cev_user_verification_pin() {
		
		$user_id = $this->wuev_user_id;			
		
		$cev_email_verification_pin = get_user_meta( $user_id, 'cev_email_verification_pin', true );
		
		$verification_pin = $this->generate_verification_pin();
		
		if ( empty( $cev_email_verification_pin ) ) {
			$cev_email_verification_pin = array();
			$cev_email_verification_pin['pin'] = $verification_pin;
		}
		
		if ( !is_array( $cev_email_verification_pin ) ) {
			return '<span>' . $cev_email_verification_pin . '</span>';
		}
				
		return '<span>' . $cev_email_verification_pin['pin'] . '</span>';
	}
	
	public function generate_verification_pin() {
		$digits = apply_filters( 'cev_verification_code_length', __( 4, 'customer-email-verification-for-woocommerce' ) );
		$i = 0; //counter
		$pin = ''; //our default pin is blank.
		while ( $i < $digits ) {
			//generate a random number between 0 and 9.
			$pin .= mt_rand(0, 9);
			$i++;
		}
		return $pin;
	}
	
	public function site_title() {
		return get_bloginfo( 'name' );
	}
	
	public function cev_resend_verification() {	
		$resend_limit_reached = apply_filters( 'cev_resend_email_limit', false, get_current_user_id() );
		$resend_email_link = add_query_arg( array('cev_redirect_limit_resend' => base64_encode( get_current_user_id() ),), get_the_permalink( $this->wuev_myaccount_page_id ) ); 
		ob_start(); ?>
		<a href="<?php echo esc_url( $resend_email_link ); ?>" class="cev-link-try-again <?php echo ( $resend_limit_reached ) ? 'cev-try-again-disable' : ''; ?>"><?php esc_html_e( 'Try Again', 'customer-email-verification-for-woocommerce' ); ?></a>
		<?php
		$try_again_url = ob_get_clean();
		return $try_again_url;
	}
}
/**
 * Returns an instance of zorem_woo_il_post.
 *
 * @since 1.0
 * @version 1.0
 *
 * @return zorem_woo_il_post
*/
function WC_customer_email_verification_email_Common() {
	static $instance;

	if ( ! isset( $instance ) ) {		
		$instance = new WC_Customer_Email_Verification_Email_Common();
	}

	return $instance;
}

/**
 * Register this class globally.
 *
 * Backward compatibility.
*/
WC_customer_email_verification_email_Common();
