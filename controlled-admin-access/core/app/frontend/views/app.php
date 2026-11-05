<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap caa-wrap">
	<?php // WordPress moves admin notices after .wp-header-end, keeping them out of the app markup. ?>
	<hr class="wp-header-end">
	<div id="caa-app">
		<noscript>
			<div class="notice notice-error inline"><p><?php esc_html_e( 'Controlled Admin Access requires JavaScript to be enabled.', 'controlled-admin-access' ); ?></p></div>
		</noscript>
	</div>
</div>
