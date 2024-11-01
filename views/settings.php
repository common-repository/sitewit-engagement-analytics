<?php
/**
 * Settings page
 *
 * @package Search Engine Marketing
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

?>

<?php if ( isset( $data['reload'] ) && true === $data['reload'] ) : ?>
	<script type="text/javascript">location.reload(true);</script>
<?php endif; ?>

<div class="wrap sw-config-page">
	<h2>
		<?php esc_html_e( 'SiteWit Dashboard', 'sitewit-engagement-analytics' ); ?>
	</h2>

	<div class="sw-report-shortcuts">
		<div class="shortcut banner" id="sw-link-newcamp"  style="background-image: url(<?php echo esc_attr( SW_PLUGIN_URL ) . 'assets/banner.png'; ?>);">
			<div class="banner-text container">
				<span class="banner-text heading"><?php esc_html_e( 'SiteWit helps you drive quality customers to your website', 'sitewit-engagement-analytics' ); ?></span>
				<span class="banner-text sub-heading"><?php esc_html_e( 'Easy setup. Go live on Google and Bing in 5 mins.', 'sitewit-engagement-analytics' ); ?></span>
			</div>
		</div>

		<div class="shortcut shortcut-tile" id="sw-link-marketing">
			<span class="dashicons dashicons-welcome-widgets-menus"></span>
			<h1><?php esc_html_e( 'Marketing', 'sitewit-engagement-analytics' ); ?></php></h1>
		</div>
		<div class="shortcut shortcut-tile" id="sw-link-leads">
			<span class="dashicons dashicons-groups"></span>
			<h1><?php esc_html_e( 'Leads', 'sitewit-engagement-analytics' ); ?></php></h1>
		</div>
		<div class="shortcut shortcut-tile" id="sw-link-stats">
			<span class="dashicons dashicons-chart-line"></span>
			<h1><?php esc_html_e( 'Stats', 'sitewit-engagement-analytics' ); ?></php></h1>
		</div>
	</div>

	<h3><?php esc_html_e( 'Change account', 'sitewit-engagement-analytics' ); ?></h3>
	<div class="sw-message">
		<?php
			echo wp_kses(
				__( 'If you want to link this WordPress site to another SiteWit account, please click <a id="reset-link" href="#">here</a>', 'sitewit-engagement-analytics' ),
				array(
					'a' => array(
						'id'   => array(),
						'href' => array(),
					),
				)
			);
		?>
	</div>


	<h3><?php esc_html_e( 'Contact Us', 'sitewit-engagement-analytics' ); ?></h3>
	<div class="sw-contact">
		<?php esc_html_e( 'Call us: 1-877-474-8394 (Monday to Friday: 9:00 AM - 6:00 PM EST)', 'sitewit-engagement-analytics' ); ?><br/>
		<?php esc_html_e( 'Email: ', 'sitewit-engagement-analytics' ); ?>
			<a href="mailto:support@sitewit.com">support@sitewit.com</a><br/>
		<?php
			printf(
				wp_kses(
					/* translators: %s: A hyperlink */
					__( 'Create a support <a target="_blank" rel="noopener noreferrer" href="%s">ticket</a>', 'sitewit-engagement-analytics' ),
					array(
						'a' => array(
							'target' => array(),
							'rel'    => array(),
							'href'   => array(),
						),
					)
				), esc_url( 'http://support.sitewit.com/hc/en-us/requests/new' )
			);
		?>
		<br/>
		<?php esc_html_e( 'Or find us', 'sitewit-engagement-analytics' ); ?>
			<a target="_blank" rel="noopener noreferrer" href="https://www.facebook.com/SiteWit"><span class="dashicons dashicons-facebook"></span></a>
			<a target="_blank" rel="noopener noreferrer" href="https://twitter.com/SiteWit"><span class="dashicons dashicons-twitter"></span></a>
			<a target="_blank" rel="noopener noreferrer" href="https://plus.google.com/115202446868642776828"><span class="dashicons dashicons-googleplus"></span></a>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#sw-banner").on("load", function() {
			jQuery(this).css("display", "block");
		});

		jQuery("#reset-link").on("click", function() {
			if (confirm("<?php esc_html_e( 'Are you sure you want to re-link your account? Current information will be lost!', 'sitewit-engagement-analytics' ); ?>")) {
				// Request to clear associated data of the account being linked
				var data = {
					action: "sw_reset_account",
					<?php echo esc_attr( SW_AJAX_NONCE_NAME ); ?>: <?php echo wp_json_encode( wp_create_nonce( SW_AJAX_NONCE_RESET_ACCOUNT ) ); ?>
				};

				// Make ajax request, expecting JSON response. "ajaxurl" is a global JS variable from WordPress
				jQuery.post(ajaxurl, data, function (response) {
					if (response === -1 || response === null) {
						alert("<?php esc_html_e( 'Request failed, please try again!', 'sitewit-engagement-analytics' ); ?>");
					} else {
						// Refresh the page (with no cache) and user will be presented with the config page
						location.reload(true);
					}
				}, "json");
			}
		});

		jQuery("div.shortcut").on("click", function() {
			var baseUrl = <?php echo wp_json_encode( SW_HOST ); ?>;

			var elId = jQuery(this).attr("id").split("-");
			switch(elId[2]) {
				case "newcamp":
					baseUrl += "smb/campaigns/new/Default.aspx?load=new";
					break;
				case "marketing":
					baseUrl += "smb/campaigns/new/Default.aspx?load=new";
					break;
				case "leads":
					baseUrl += "smb/connect/dashboard";
					break;
				case "stats":
					baseUrl += "smb/analytics";
					break;
			}

			var win = window.open(baseUrl, "_blank");
			win.opener = null;
			win.focus();
		});
	});
</script>
