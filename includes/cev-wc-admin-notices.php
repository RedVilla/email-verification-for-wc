<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_CEV_Admin_Notices_Under_WC_Admin {

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
	 * @return WC_CEV_Admin_Notices_Under_WC_Admin
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
		
		add_action( 'admin_init', array( $this, 'cev_settings_admin_notice_ignore' ) );
	}

	
	public function cev_settings_admin_notice_ignore() {
		if ( isset( $_GET['cev-pro-settings-ignore-notice'] ) ) {
			set_transient( 'cev_settings_admin_notice_ignore', 'yes', 2592000 );
		}
	}
			
}

/**
 * Returns an instance of WC_CEV_Admin_Notices_Under_WC_Admin.
 *
 * @since 1.6.5
 * @version 1.6.5
 *
 * @return WC_CEV_Admin_Notices_Under_WC_Admin
*/
function WC_CEV_Admin_Notices_Under_WC_Admin() {
	static $instance;

	if ( ! isset( $instance ) ) {		
		$instance = new WC_CEV_Admin_Notices_Under_WC_Admin();
	}

	return $instance;
}

/**
 * Register this class globally.
 *
 * Backward compatibility.
*/
WC_CEV_Admin_Notices_Under_WC_Admin();
