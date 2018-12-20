<?php
/**
 * @author Aj
 * @version 1.0
 * @since 2018-12-20
 *
 * FUNCTION: Function to enqueue both scripts and styles
 */
function enqueue_assets() {
    // Styles
    wp_enqueue_style('main_style', get_template_directory_uri() . '/assets/css/styles.min.css'); 

    // Scripts
    wp_enqueue_script('main_script', get_template_directory_uri() . '/assets/js/app.min.js');
}

add_action('wp_enqueue_scripts', 'enqueue_assets');