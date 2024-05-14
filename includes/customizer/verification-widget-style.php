<?php
/**
 * Customizer Setup and Custom Controls
 *
 */

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class evfwr_Verification_Widget_Style {
	// Get our default values	
	private static $order_ids  = null;
	
	public function __construct() {
		
	}			
		
	
	/**
	 * Checks to see if we are opening our custom customizer preview
	 *	 
	 * @return bool
	 */
	public static function is_own_preview_request() {
		return isset( $_REQUEST['action'] ) && 'preview_evfwr_verification_lightbox' === $_REQUEST['action'];
	}
	
	/**
	 * Checks to see if we are opening our custom customizer controls
	 *	 
	 * @return bool
	 */
	public static function is_own_customizer_request() {
		return isset( $_REQUEST['section'] ) && 'evfwr_verification_widget_style' === $_REQUEST['section'];
	}		
}

/**
 * Initialise our Customizer settings
 */

$evfwr_verification_widget_style = new evfwr_Verification_Widget_Style();
