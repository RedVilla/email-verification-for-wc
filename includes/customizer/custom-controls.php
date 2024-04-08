<?php
/**
 * Skyrocket Customizer Custom Controls
 *
 */
if ( class_exists( 'WP_Customize_Control' ) ) {	
		
	class WP_Customize_Cev_Codeinfoblock_Control extends WP_Customize_Control {		

		public function render_content() {
			?>
			<label>
				<h3 class="customize-control-title"><?php esc_html_e( $this->label, 'customer-email-verification-for-woocommerce' ); ?></h3>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
				<?php endif; ?>
			</label>
			<?php
		}
	}	
}
