<?php

// Faster than @import
function my_assets() {
	wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array( 'apt-styles' ) );
	wp_enqueue_style( 'apt-styles', get_stylesheet_directory_uri() . '/assets/css/main.min.css' );
}

add_action( 'wp_enqueue_scripts', 'my_assets' );

add_action( 'wp_enqueue_scripts', 'prefix_enqueue_awesome' );
/**
 * Register and load font awesome CSS files using a CDN.
 *
 * @link   http://www.bootstrapcdn.com/#fontawesome
 * @author FAT Media
 */
function prefix_enqueue_awesome() {
	wp_enqueue_style( 'prefix-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css', array(), '4.0.3' );
}

/**
 * Enqueue Animate.css
 */
// function animateCSS() {
// 	wp_enqueue_style( 'animate', get_stylesheet_uri() . '/assets/css/animate.css/animate.min.css' );
// }

// add_action( 'wp_enqueue_scripts', 'animateCSS' );