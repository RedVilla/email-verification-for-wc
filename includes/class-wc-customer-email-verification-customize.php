<?php
/**
 * CEV  admin 
 *
 * @class   cev_admin
 * @package WooCommerce/Classes
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cev_admin class.
 */
class WC_Customer_Email_Verification_Customize {

	/**
	 * Get the class instance
	 *
	 * @since  1.0.0
	 * @return customer-email-verification-pro
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
		$this->init();
	}
	
	/*
	 * init function
	 *
	 * @since  1.0
	*/
	public function init() {				
		add_filter( 'cev_verification_popup_heading', array( $this, 'cev_verification_popup_heading_callback' ) );
		add_filter( 'cev_verification_popup_message', array( $this, 'cev_verification_popup_message_callback' ), 10, 2 );
	}
	
	/**	
	 * Return Email verification widget Heading
	 * 
	 * @since  1.0.0
	*/
	public function cev_verification_popup_heading_callback( $heading ) {
		
		$heading_text = get_option( 'cev_verification_header', $heading );
		
		if ( '' != $heading_text ) {
			return $heading_text;
		}
		
		return $heading;
	}
	
	/**
	 * Return Email verification widget message
	 * 
	 * @since  1.0.0
	*/
	public function cev_verification_popup_message_callback( $message, $email ) {
		$message_text = get_option( 'cev_verification_message', $message );
		$message_text = str_replace( '{customer_email}', $email, $message_text );
		
		if ( '' != $message_text ) {
			return $message_text;
		}
		
		return $message;
	}	
}
