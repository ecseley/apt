<?php

// Faster than @import
function my_assets() {
	wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array( 'apt-styles' ) );
	wp_enqueue_style( 'apt-styles', get_stylesheet_directory_uri() . '/assets/css/main.min.css' );
}

add_action( 'wp_enqueue_scripts', 'my_assets' );