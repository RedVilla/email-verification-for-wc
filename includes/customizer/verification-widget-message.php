<?php
/**
 * Customizer Setup and Custom Controls
 *
 */

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class Cev_Verification_Widget_Message {
	// Get our default values	
	private static $order_ids  = null;
	public $defaults;
	public function __construct() {
		// Get our Customizer defaults
		$this->defaults = $this->cev_generate_defaults();		
		
		// Register our sample default controls
		add_action( 'customize_register', array( $this, 'cev_my_verification_widget_message' ) );
		
		// Only proceed if this is own request.				
		if ( ! self::is_own_customizer_request() && ! self::is_own_preview_request()) {
			return;
		}			
		
		// Register our sections
		add_action( 'customize_register', array( wc_cev_customizer(), 'cev_add_customizer_sections' ) );	
		
		// Remove unrelated components.
		add_filter( 'customize_loaded_components', array( wc_cev_customizer(), 'remove_unrelated_components' ), 99, 2 );
		
		// Remove unrelated sections.
		add_filter( 'customize_section_active', array( wc_cev_customizer(), 'remove_unrelated_sections' ), 10, 2 );	
		
		// Unhook divi front end.
		add_action( 'woomail_footer', array( wc_cev_customizer(), 'unhook_divi' ), 10 );
		
		// Unhook Flatsome js
		add_action( 'customize_preview_init', array( wc_cev_customizer(), 'unhook_flatsome' ), 50  );	
		

		add_filter( 'customize_controls_enqueue_scripts', array( wc_cev_customizer(), 'enqueue_customizer_scripts' ) );	
		
		//add_action( 'parse_request', array( $this, 'set_up_preview' ) );	

		add_action( 'customize_preview_init', array( $this, 'enqueue_preview_scripts' ) );		
	}			
		
	/**
	 * Add css and js for preview
	*/	
	public function enqueue_preview_scripts() {		 
		 wp_enqueue_style('cev-pro-preview-styles', woo_customer_email_verification()->plugin_dir_url() . 'assets/css/preview-styles.css', array(), woo_customer_email_verification()->version  );		 
	}	
	
	/**
	 * Checks to see if we are opening our custom customizer preview
	 *	 
	 * @return bool
	 */
	public static function is_own_preview_request() {
		return isset( $_REQUEST['action'] ) && ( 'preview_cev_verification_lightbox' === $_REQUEST['action'] || 'guest_user_preview_cev_verification_lightbox' === $_REQUEST['action'] );
	}
	
	/**
	 * Checks to see if we are opening our custom customizer controls
	 *	 
	 * @return bool
	 */
	public static function is_own_customizer_request() {
		return isset( $_REQUEST['section'] ) && 'cev_verification_widget_messages' === $_REQUEST['section'];
	}
	
	/**
	 * Get Customizer URL	 
	 */
	public static function get_customizer_url( $section ) {	
		$customizer_url = add_query_arg( array(
				'cev-customizer' => '1',
				'section' => $section,						
				'autofocus[section]' => 'cev_verification_widget_messages',
				'url'                  => urlencode( add_query_arg( array( 'action' => 'preview_cev_verification_lightbox' ), home_url( '/' ) ) ),
				'return'               => urlencode( self::get_cev_widget_message_page_url( $return_tab ) ),								
			), admin_url( 'customize.php' ) );		

		return $customizer_url;
	}
	
	/**
	 * Get WooCommerce email settings page URL
	 *	 
	 * @return string
	 */
	public static function get_cev_widget_message_page_url( $return_tab ) {
		return admin_url( 'admin.php?page=customer-email-verification-for-woocommerce&tab=' . $return_tab );
	}
	/**
	 * Code for initialize default value for customizer
	*/	
	public function cev_generate_defaults() {
		$customizer_defaults = array(
			'cev_verification_popup_background_color'	=> '#f5f5f5',
			'cev_verification_popup_overlay_background_color' => '#ffffff',
			'cev_verification_header' => __( 'Verify its you.', 'customer-email-verification-for-woocommerce' ),
			'cev_verification_message'	=> __( 'We sent verification code to  {customer_email}. To verify your email address, please check your inbox and enter the code below.', 'customer-email-verification-for-woocommerce' ),
			'cev_verification_widget_footer'  =>__( "Didn't receive an email? {cev_resend_verification}", 'customer-email-verification-for-woocommerce'),
		);
		return $customizer_defaults;
	}						
	
	/**
	 * Register our sample default controls
	 */
	public function cev_my_verification_widget_message( $wp_customize ) {	
	
		/**
		* Load all our Customizer Custom Controls
		*/
		require_once trailingslashit( dirname(__FILE__) ) . 'custom-controls.php';		
													
		// Table Background color
		$wp_customize->add_setting( 'cev_verification_popup_overlay_background_color',
			array(
				'default' => $this->defaults['cev_verification_popup_overlay_background_color'],
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
				'type' => 'option',
			)
		);
		$wp_customize->add_control( 'cev_verification_popup_overlay_background_color',
			array(
				'label' => __( 'Overlay background Color', 'customer-email-verification-for-woocommerce' ),
				'section' => 'cev_verification_widget_messages',
				'priority' => 1, // Optional. Order priority to load the control. Default: 10
				'type' => 'color',
			)
		);
		
		// email button color overly
		$wp_customize->add_setting( 'cev_verification_popup_background_color',
			array(
				'default' => $this->defaults['cev_verification_popup_background_color'],
				'transport' => 'refresh',
				'sanitize_callback' => '',
				'type' => 'option',
			)
		);
		$wp_customize->add_control( 'cev_verification_popup_background_color',
			array(
				'label' => __( 'Widget background Color', 'customer-email-verification-for-woocommerce' ),
				'description' => '',
				'section' => 'cev_verification_widget_messages',
				'priority' => 2, // Optional. Order priority to load the control. Default: 10
				'type' => 'color',
			)		
		);	
		
		// Header text	
		$wp_customize->add_setting( 'cev_verification_header',
			array(
				'default' => $this->defaults['cev_verification_header'],
				'transport' => 'refresh',
				'type' => 'option',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( 'cev_verification_header',
			array(
				'label' => __( 'Header text', 'customer-email-verification-for-woocommerce' ),
				'description' => '',
				'section' => 'cev_verification_widget_messages',
				'type' => 'text',
				'input_attrs' => array(
					'class' => '',
					'style' => '',
					'placeholder' => __( $this->defaults['cev_verification_header'], 'customer-email-verification-for-woocommerce' ),			
				),
			)
			
		);	
		
		// message content	
		$wp_customize->add_setting( 'cev_verification_message',
			array(
				'default' => $this->defaults['cev_verification_message'],
				'transport' => 'refresh',
				'type' => 'option',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( 'cev_verification_message',
			array(
				'label' => __( 'Message', 'customer-email-verification-pro' ),
				'description' => '',
				'section' => 'cev_verification_widget_messages',
				'type' => 'textarea',
				'input_attrs' => array(
					'class' => '',
					'style' => '',
					'placeholder' => __( $this->defaults['cev_verification_message'], 'customer-email-verification-for-woocommerce' ),
				),	
			)
		);
		
		// Footer content	
		$wp_customize->add_setting( 'cev_verification_widget_footer',
			array(
				'default' => $this->defaults['cev_verification_widget_footer'],
				'transport' => 'refresh',
				'type' => 'option',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( 'cev_verification_widget_footer',
			array(
				'label' => __( 'Footer content', 'customer-email-verification-for-woocommerce' ),
				'description' => '',
				'section' => 'cev_verification_widget_messages',
				'type' => 'textarea',
				'input_attrs' => array(
					'class' => '',
					'style' => '',
					'placeholder' => __( $this->defaults['cev_verification_widget_footer'], 'customer-email-verification-for-woocommerce' ),
				),
			)
		);
		
		$wp_customize->add_setting( 'cev_widzet_code_block',
			array(
				'default' => '',
				'transport' => 'postMessage',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( new WP_Customize_cev_codeinfoblock_Control( $wp_customize, 'cev_widzet_code_block',
			array(
				'label' => __( 'Available variables', 'customer-email-verification-for-woocommerce' ),
				'description' => '<code>{customer_email}<br>{cev_resend_verification}</code><br>You can use HTML tag : &lt;strong&gt;, &lt;i&gt;',
				'section' => 'cev_verification_widget_messages',			
			)		
		)
		 );
	
									
	}	
	
}
/**
 * Initialise our Customizer settings
 */

$cev_verification_widget_message = new Cev_Verification_Widget_Message();
