<?php
/**
 * Plugin Name:     Time inc coding test
 * Plugin URI:      github.com/digitales/timeinc_rst
 * Description:     A plugin to fulfill the coding test for TIme Inc - create a widget to load results from a JSON API and display via a widget
 * Author:          Ross Tweedie
 * Author URI:      https://github.com/digitales
 * Text Domain:     timeinc_rst
 * Domain Path:     /languages
 * Version:         1.0.0
 * License: GPLv2 or later
 *
 * @package         Timeinc_rst
 */

// For composer dependencies.
require 'vendor/autoload.php';

define( 'TIRSTPATH', trailingslashit( dirname( __FILE__ ) ) );
define( 'TIRSTDIR', trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) );
define( 'TIRSTURL', plugin_dir_url( dirname( __FILE__ ) ) . TIRSTDIR );

/*
// Set maximum execution time to 5 minutes - won't affect safe mode.
$safe_mode = array( 'On', 'ON', 'on', 1 );
if ( ! in_array( ini_get( 'safe_mode' ), $safe_mode, true ) && ini_get( 'max_execution_time' ) < 300 ) {
	@ini_set( 'max_execution_time', 300 );
}
*/
load_plugin_textdomain( 'timeinc_rst', false, basename( dirname( __FILE__ ) ) . '/languages' );

// Now setup the plugin classes.
global $time_inc_rst;

/**
 * Now let's load the main classes, depending upon if we are in the administration system.
 */
require_once( TIRSTPATH . '/classes/core.php' );
if ( is_admin() ) {
	require_once( TIRSTPATH . '/classes/admin.php' );
	$time_inc_rst = new TimeIncRstAdmin();
} else {
	require_once( TIRSTPATH . 'classes/frontend.php' );
	$time_inc_rst = new TimeIncRstFrontend();
}

$time_inc_rst->init();

// Load the widget.
require_once( TIRSTPATH . '/classes/widget.php' );
