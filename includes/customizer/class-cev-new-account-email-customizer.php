<?php
/**
 * Customizer Setup and Custom Controls
 *
 */

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class Cev_New_Account_Email_Customizer {
	// Get our default values	
	private static $order_ids  = null;
	public $defaults;
	public function __construct() {
		// Get our Customizer defaults
		$this->defaults = $this->cev_generate_defaults();		
		
		// Register our sample default controls
		add_action( 'customize_register', array( $this, 'cev_my_account_customizer_options' ) );
		
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
		
		add_action( 'parse_request', array( $this, 'set_up_preview' ) );	

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
		return isset( $_REQUEST['cev-new-account-email-preview'] ) && '1' === $_REQUEST['cev-new-account-email-preview'];
	}
	
	/**
	 * Checks to see if we are opening our custom customizer controls
	 *	 
	 * @return bool
	 */
	public static function is_own_customizer_request() {
		return isset( $_REQUEST['section'] ) && 'cev_main_controls_section' === $_REQUEST['section'];
	}
	
	/**
	 * Get Customizer URL
	 *
	 */
	public static function get_customizer_url( $section ) {
		
		$customizer_url = add_query_arg( array(
			'cev-customizer' => '1',
			'section' => $section,
			'url'     => urlencode( add_query_arg( array( 'cev-new-account-email-preview' => '1' ), home_url( '/' ) ) ),
		), admin_url( 'customize.php' ) );		
		return $customizer_url;
	}
	
	/**
	 * Code for initialize default value for customizer
	*/	
	public function cev_generate_defaults() {
		$customizer_defaults = array(
			'cev_new_acoount_email_heading' => __( 'Please verify your email address', 'customer-email-verification-for-woocommerce' ),
			'cev_new_verification_email_body' => __( 'Your Verification Code: {cev_user_verification_pin} 
Or, verify your account by clicking on the verification link: ', 'customer-email-verification-for-woocommerce' ),
		);
		return $customizer_defaults;
	}						
	
	/**
	 * Register our sample default controls
	 */
	public function cev_my_account_customizer_options( $wp_customize ) {	
	
		/**
		* Load all our Customizer Custom Controls
		*/
		require_once trailingslashit( dirname(__FILE__) ) . 'custom-controls.php';		
													
		
		// Email heading	
		$wp_customize->add_setting( 'cev_new_acoount_email_heading',
			array(
				'default' => $this->defaults['cev_new_acoount_email_heading'],
				'transport' => 'refresh',
				'type' => 'option',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( 'cev_new_acoount_email_heading',
			array(
				'label' => __( 'Verification Heading', 'woocommerce' ),
				'description' => esc_html__( 'Only for a New Account verification email', 'customer-email-verification-for-woocommerce' ),
				'section' => 'cev_new_account_email_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => '',
					'style' => '',
					'placeholder' => __( $this->defaults['cev_new_acoount_email_heading'], 'customer-email-verification-for-woocommerce' ),
				),
			)
		);	
		
		// Email Body	
		$wp_customize->add_setting( 'cev_new_verification_email_body',
			array(
				'default' => $this->defaults['cev_new_verification_email_body'],
				'transport' => 'refresh',
				'type' => 'option',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( 'cev_new_verification_email_body',
			array(
				'label' => __( 'Verification Message', 'customer-email-verification-for-woocommerce' ),
				'description' => '',
				'section' => 'cev_new_account_email_section',
				'type' => 'textarea',
				'input_attrs' => array(
					'class' => '',
					'style' => '',
					'placeholder' => __( $this->defaults['cev_new_verification_email_body'], 'customer-email-verification-for-woocommerce' ),
				),
			)
		);
		
		$wp_customize->add_setting( 'cev_new_email_code_block',
			array(
				'default' => '',
				'transport' => 'postMessage',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( new WP_Customize_cev_codeinfoblock_Control( $wp_customize, 'cev_new_email_code_block',
			array(
				'label' => __( 'Available variables', 'customer-email-verification-for-woocommerce' ),
				'description' => '<code>{cev_user_verification_pin}<br>{cev_user_verification_link}</code>',
				'section' => 'cev_new_account_email_section',				
			)
		) );							
	}	
	
	/**
	 * Set up preview
	 *	 
	 * @return void
	 */
	public function set_up_preview() {
		
		// Make sure this is own preview request.
		if ( ! self::is_own_preview_request() ) {
			return;
		}
		include woo_customer_email_verification()->get_plugin_path() . '/includes/customizer/preview/preview_new.php';		
		exit;			
	}	

	/**
	 * Code for preview of tracking info in email
	*/	
	public function preview_new_account_email() {
		// Load WooCommerce emails.
		
		$wc_emails      = WC_Emails::instance();
		$emails         = $wc_emails->get_emails();
		WC_customer_email_verification_email_Common()->wuev_user_id  = 1;
		$email_heading     = get_option( 'cev_new_acoount_email_heading', $this->defaults['cev_new_acoount_email_heading'] );
		$email_heading 	   = WC_customer_email_verification_email_Common()->maybe_parse_merge_tags( $email_heading );
		$email_content     = get_option( 'cev_new_verification_email_body', $this->defaults['cev_new_verification_email_body'] );
		$email_type = 'WC_Email_Customer_New_Account';
		if ( false === $email_type ) {
			return false;
		}	
		
		$mailer = WC()->mailer();
		
		// Reference email.
		if ( isset( $emails[ $email_type ] ) && is_object( $emails[ $email_type ] ) ) {
			$email = $emails[ $email_type ];
		}
		$user_id = get_current_user_id();
		$user = get_user_by( 'id', $user_id );
		
		$email->object             = $user;
		$email->user_pass          = '{user_pass}';
		$email->user_login         = stripslashes( $email->object->user_login );
		$email->user_email         = stripslashes( $email->object->user_email );
		$email->recipient          = $email->user_email;
		$email->password_generated = true;
		
		// Get email content and apply styles.
		$content = $email->get_content();
		$content = $email->style_inline( $content );
		$content = apply_filters( 'woocommerce_mail_content', $content );
		
		if ( 'plain' === $email->email_type ) {
			$content = '<div style="padding: 35px 40px; background-color: white;">' . str_replace( "\n", '<br/>', $content ) . '</div>';
		}
		
		echo wp_kses_post( $content );
		
	}	
}
/**
 * Initialise our Customizer settings
 */

$cev_new_account_email_customizer = new cev_new_account_email_customizer();
