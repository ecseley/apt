<?php
/**
 * Plugin Name: AMP Styles
 * Description: Provides styling and UI enhancements for the admin interface. (This should be network activated)
 * Version:     1.0.0
 * Author:      Triton Digital
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-amp-styles.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'AMPStyles', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'AMPStyles', 'deactivate' ) );

AMPStyles::get_instance();

