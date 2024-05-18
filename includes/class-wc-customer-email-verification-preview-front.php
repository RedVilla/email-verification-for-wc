<?php
/**
 * EVFWR admin preview 
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
class WooCommerce_Customer_Email_Verification_Preview {

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
		add_action( 'wp_enqueue_scripts', array( $this, 'evfwr_pro_front_styles' ));		
		add_action( 'template_redirect', array( $this, 'preview_evfwr_page') );
		add_filter( 'evfwr_verification_popup_message', array( $this, 'evfwr_verification_popup_message_callback'), 10, 2 );	
	}
	
	/**
	 * Include front js and css
	*/
	public function evfwr_pro_front_styles() {

  		$action = isset( $_REQUEST['action'] ) ? sanitize_key( $_REQUEST['action'] ) : '';

  		// Check for presence of a valid nonce for the specific action
  		if ( 'preview_evfwr_verification_lightbox' === $action && wp_verify_nonce( $_REQUEST['_wpnonce'], 'evfwr_verification_preview' ) ) {
    		wp_enqueue_style( 'evfwr_front_style' );
  		}
	}

	
	/*
	* EVFWR Page preview
	*/
	public function preview_evfwr_page() {
		
		$action = ( isset( $_REQUEST[ 'action' ] ) ? wc_clean( $_REQUEST[ 'action' ] ) : '' );
		
		if ( 'preview_evfwr_verification_lightbox' != $action ) {
			return;
		}
		
		wp_head();				
		include 'views/front/preview_evfwr_popup_page.php';
		get_footer();
		exit;
	}
	
	/**
	 * Return Email verification widget message
	 * 
	 * @since  1.0.0
	*/
	public function evfwr_verification_popup_message_callback( $message, $email ) {
		
		$evfwr_verification_widget_message = new evfwr_verification_widget_message();
		$message_text = get_option( 'evfwr_verification_message', $evfwr_verification_widget_message->defaults['evfwr_verification_message'] );
		$message_text = str_replace( '{customer_email}', $email, $message_text );		
		
		if ( '' != $message_text ) {
			return $message_text;
		}
		
		return $message;
	}	
}
