<?php
/**
 *
 * @package   FluidVids for WordPress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/fluidvids/
 * @copyright 2013 Ulrich Pogson
 *
 * @wordpress-plugin
 * Plugin Name: FluidVids for WordPress
 * Plugin URI:  http://wordpress.org/plugins/fluidvids/
 * Description: A WordPress plugin for FluidVids. FluidVids is a raw JavaScript solution for responsive and fluid YouTube and Vimeo video embeds. It's extremely lightweight.
 * Version:     1.0.0
 * Author:      Ulrich Pogson
 * Author URI:  http://ulrich.pogson.ch/
 * Text Domain: fluidvids
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-fluidvids.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'Fluidvids', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Fluidvids', 'deactivate' ) );

FluidVids::get_instance();