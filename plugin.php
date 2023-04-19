<?php
/**
 * Plugin Name: Elementor Fixed Nav Menu
 * Description: Fix for the Elementor nav Menu Widget
 * Plugin URI:  https://github.com/rloes/elementor-fixed-nav-menu
 * Version:     1.0.0
 * Author:      Robin - Westsite
 * Author URI:  https://westsite-webdesign.de/
 * Text Domain: ws-elementor-fixed-nav-menu
 *
 * Elementor tested up to: 3.12.1
 * Elementor Pro tested up to: 3.12.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action('wp_enqueue_scripts', function(){
	wp_enqueue_script('ws-extend-smartmenus.min.js', plugin_dir_url( __FILE__ ) . 'assets/ws-extend-smartmenus.min.js', array('smartmenus'), '1.0.0', true);
});

?>
