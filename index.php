<?php
/*
    Plugin Name: Total Users Lite
    Plugin URI: http://zourbuth.com/total-users-lite
    Description: A powerful and easy to use plugin for displaying the total users 
    Version: 1.0.1
    Author: zourbuth
    Author URI: http://zourbuth.com
    License: GPL2

	Copyright 2012  zourbuth.com  (email : zourbuth@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Launch the plugin
add_action( 'plugins_loaded', 'total_users_lite_plugin_loaded' );

// Initializes the plugin and it's features
function total_users_lite_plugin_loaded() {

	// Set constant
	define( 'TOTAL_USERS_LITE_VERSION', '1.0' );
	define( 'TOTAL_USERS_LITE_DIR', plugin_dir_path( __FILE__ ) );
	define( 'TOTAL_USERS_LITE_URL', plugin_dir_url( __FILE__ ) );
	
	// Load require file
	require_once( TOTAL_USERS_LITE_DIR . 'total-users-lite.php' );

	// Loads and registers the widgets
	add_action( 'widgets_init', 'total_users_lite_load_widgets' );	
}

function total_users_lite_load_widgets($atts) {
	// Load widget and register
	require_once( TOTAL_USERS_LITE_DIR . 'total-users-lite-widget.php' );
	register_widget( 'Total_Users_Lite_Widget' );
}
?>