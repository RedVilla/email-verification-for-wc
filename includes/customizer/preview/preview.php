<?php 
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

?>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>" />
		<meta name="viewport" content="width=device-width" />
			<style type="text/css" id="evfwr_designer_custom_css">.woocommerce-store-notice.demo_store, .mfp-hide {display: none;}</style>
	</head>
	<body class="evfwr_preview_body">
		<div id="overlay"></div>
		<div id="evfwr_preview_wrapper" style="display: block;">
			<?php evfwr_initialise_customizer_settings::preview_account_email(); ?>
		</div>
		<?php
		do_action( 'woomail_footer' );
		wp_footer();
		?>
	</body>
</html>
