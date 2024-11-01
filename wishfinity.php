<?php

/*

Plugin Name: Wishfinity
Description: Use our Universal Wishlist and Social Gifting to fix your abandoned carts. Connect with Wishfinity to reclaim your customers and get more sales
Version: 1.0.1
Author: eggtooth
Text Domain: wishfinity
Domain Path: /lang

License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

*/

include 'includes/_safe.php';

define( 'WISHFINITY_PLUGIN_ID', 'wishfinity');
define( 'WISHFINITY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define( 'WISHFINITY_PLUGIN_URL', plugin_dir_url(__FILE__));

include 'includes/wishfinity-settings-storage.php';
include 'includes/wishfinity-settings-ui.php';
include 'includes/wishfinity-scripts.php';
include 'includes/wishfinity-arrayhelper.php';
include 'includes/wishfinity-html.php';
