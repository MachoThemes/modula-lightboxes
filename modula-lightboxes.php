<?php
/**
 * Plugin Name: Modula Lightboxes
 * Plugin URI: https://wp-modula.com/
 * Description: Add other lightboxes
 * Author: Macho Themes
 * Version: 1.0.0
 * URI: https://www.machothemes.com/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'MODULA_LIGHTBOXES_VERSION', '1.0.0' );
define( 'MODULA_LIGHTBOXES_PATH', plugin_dir_path( __FILE__ ) );
define( 'MODULA_LIGHTBOXES_URL', plugin_dir_url( __FILE__ ) );
define( 'MODULA_LIGHTBOXES_FILE', __FILE__ );

require_once MODULA_LIGHTBOXES_PATH . 'includes/class-modula-lightboxes.php';

// Load the main plugin class.
$modula_lightboxes = Modula_Lightboxes::get_instance();
