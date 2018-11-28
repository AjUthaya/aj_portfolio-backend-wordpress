<?php
/**
 * @author Aj
 * @version 1.0
 * @since 2018-11-28
 *
 * FUNCTION: Validates that timber plugin is activated
 */
function validate_timber_plugin_activation()
{
  if ( ! class_exists( 'Timber' ) ) {
  	add_action( 'admin_notices', function() {
  		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
  	});
  	add_filter('template_include', function( $template ) {
  		return get_stylesheet_directory() . '/static/no-timber.html';
  	});
  	return;
  }
}
validate_timber_plugin_activation();