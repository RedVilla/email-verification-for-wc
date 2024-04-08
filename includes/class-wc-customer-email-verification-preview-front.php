<?php
/**
 * CEV admin preview 
 *
 * @class WC_Customer_Email_Verification_Preview
 * @package WooCommerce/Classes
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Customer_Email_Verification_Preview class.
 */
class WC_Customer_Email_Verification_Preview {

	/**
	 * Get the class instance
	 *
	 * @since  1.0.0
	 * @return customer-email-verification-for-woocommerce
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	*/
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	 * 
	 * @since  1.0.0
	*/
	public function __construct() {		
	}
	
	/*
	 * init function	 	
	*/
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'cev_pro_front_styles' ));		
		add_action( 'template_redirect', array( $this, 'preview_cev_page') );
		add_filter( 'cev_verification_popup_message', array( $this, 'cev_verification_popup_message_callback'), 10, 2 );	
	}
	
	/**
	 * Include front js and css
	*/
	public function cev_pro_front_styles() {				
		
		$action = ( isset( $_REQUEST[ 'action' ] ) ? wc_clean( $_REQUEST[ 'action' ] ) : '' );
		
		if ( 'preview_cev_verification_lightbox' == $action ) {
			wp_enqueue_style( 'cev_front_style' );								
		}		
	}
	
	/*
	* CEV Page preview
	*/
	public function preview_cev_page() {
		
		$action = ( isset( $_REQUEST[ 'action' ] ) ? wc_clean( $_REQUEST[ 'action' ] ) : '' );
		
		if ( 'preview_cev_verification_lightbox' != $action ) {
			return;
		}
		
		wp_head();				
		include 'views/front/preview_cev_popup_page.php';
		get_footer();
		exit;
	}
	
	/**
	 * Return Email verification widget message
	 * 
	 * @since  1.0.0
	*/
	public function cev_verification_popup_message_callback( $message, $email ) {
		
		$cev_verification_widget_message = new cev_verification_widget_message();
		$message_text = get_option( 'cev_verification_message', $cev_verification_widget_message->defaults['cev_verification_message'] );
		$message_text = str_replace( '{customer_email}', $email, $message_text );		
		
		if ( '' != $message_text ) {
			return $message_text;
		}
		
		return $message;
	}	
}
