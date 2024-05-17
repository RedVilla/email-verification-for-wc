<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WooCommerce_EVFWR_Admin_Notices_Under_WooCommerce_Admin {

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		$this->init();
	}
	
	/**
	 * Get the class instance
	 *
	 * @return WooCommerce_EVFWR_Admin_Notices_Under_WooCommerce_Admin
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/*
	* init from parent mail class
	*/
	public function init() {
		
		add_action( 'admin_init', array( $this, 'evfwr_settings_admin_notice_ignore' ) );
	}
			
}

/**
 * Returns an instance of WooCommerce_EVFWR_Admin_Notices_Under_WooCommerce_Admin.
 *
 * @since 1.6.5
 * @version 1.6.5
 *
 * @return WooCommerce_EVFWR_Admin_Notices_Under_WooCommerce_Admin
*/
function WooCommerce_EVFWR_Admin_Notices_Under_WooCommerce_Admin() {
	static $instance;

	if ( ! isset( $instance ) ) {		
		$instance = new WooCommerce_EVFWR_Admin_Notices_Under_WooCommerce_Admin();
	}

	return $instance;
}

/**
 * Register this class globally.
 *
 * Backward compatibility.
*/
WooCommerce_EVFWR_Admin_Notices_Under_WooCommerce_Admin();
