<?php
/**
 * Plugin Name: Barebone WP
 * Description: mu-plugin that strips down WordPress and reduces queries and such. Use in addition to https://github.com/wecodemore/WP-Strip-Naked
 * Plugin URI: http://github.com/mpeshev/barebonewp
 * Author: nofearinc
 * Author URI: http://devwp.eu/
 * Version: 0.2
 * License: GPL2
 */

/**
 * List all widget options called without a reason in the entire admin section
 */
$widgets_options = array(
	'widget_pages',
	'widget_calendar',
	'widget_tag_cloud',
	'widget_nav_menu',
);

// Don't call any widget option when not needed
foreach( $widgets_options as $widget_option ) {
	add_filter( 'pre_option_' . $widget_option, function( $option ) {
		if ( ! is_admin ) {
			return $option;
		}
		
		$request_uri = $_SERVER['REQUEST_URI'];
		$last_slash = strrpos( $request_uri , DIRECTORY_SEPARATOR );
		
		if( false !== $last_slash ) {
			// Get the current admin page
			$request_page = substr( $request_uri , $last_slash + 1 );
			
			// Pages that expect widgets
			$widgetized_pages = array(
				'widgets.php',
				'index.php',
				'customize.php',
			);
			
			// Strip query and hash arguments
			$request_page = strtok($request_page, '?');
			$request_page = strtok($request_page, '#');
			
			// Don't strip widget option calls if in authorized page
			if ( in_array( $request_page, $widgetized_pages ) ) {
				return false;
			}
			
			return true;
		}
	} );
}

/**
 * Remove unneeded option calls for enterprise projects
 */
add_filter( 'pre_option_WPLANG', function() { return 'EN'; } );
add_filter( 'pre_option_db_upgraded', function() { return false; } );
add_filter( 'pre_option_dismissed_update_core', function() { return true; } );
add_filter( 'pre_option_strip_feed', function() { return 0; } );
add_filter( 'pre_option_strip_pages', function() { return 0; } );
// add_filter( 'pre_option_uninstall_plugins', function() { return array(); } ); 