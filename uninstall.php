<?php
/**
 * Uninstall functions
 *
 * @package   FluidVids for WordPress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/fluidvids/
 * @copyright 2013 Ulrich Pogson
 */

//if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

global $wpdb;

if ( is_multisite() ) {

	$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );

		delete_option('fluidvids');

	if ( $blogs ) {

	 	foreach ( $blogs as $blog ) {
			switch_to_blog( $blog['blog_id'] );

			delete_option('fluidvids');

			restore_current_blog();
		}
	}

} else {
	delete_option('fluidvids');
}
