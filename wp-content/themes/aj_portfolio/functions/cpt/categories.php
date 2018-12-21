<?php
/**
 * @author  Aj <ajanth@levelup.no>
 * @version 2.0
 * @since   2018-12-20
 *
 * POST TYPE: Categories
 */
function init_cpt_categories()
{
    // 1. Define: Labels
    $labels = array(
    'name' => 'Categories',
    'singular_name' => 'Category',
    'menu_name' => 'Categories'
    );
    // 2. Define: Supports
    $supports = array('title', 'editor', 'revisions');
    // REGISTER NEW POST TYPE
    register_post_type(
        'categories', array(
            'labels' => $labels,
            'description' => '',
            'supports' => $supports,
            'public' => true,
            'menu_icon' => 'dashicons-tag'
        )
    );
}

add_action('init', 'init_cpt_categories');