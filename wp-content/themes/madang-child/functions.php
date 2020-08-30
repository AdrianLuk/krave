<?php
function madang_child_scripts() {
	
	$theme = wp_get_theme('madang');
  	$version = $theme['Version'];
	wp_enqueue_style( 'madang-child-style', get_stylesheet_directory_uri() .'/style.css' );
}
add_action('wp_print_styles', 'madang_child_scripts');
