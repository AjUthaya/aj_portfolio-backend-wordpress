<?php
/**
 * @author  Aj <ajanth@levelup.no>
 * @version 2.0
 * @since   2018-11-28
 *
 * POST TYPE: Experiences
 */
function init_cpt_experiences()
{
    // 1. Define: Labels
    $labels = array(
    'name' => 'Experiences',
    'singular_name' => 'Experience',
    'menu_name' => 'Experiences'
    );
    // 2. Define: Supports
    $supports = array('title', 'editor', 'revisions');
    // REGISTER NEW POST TYPE
    register_post_type(
        'experiences', array(
            'labels' => $labels,
            'description' => '',
            'supports' => $supports,
            'public' => true,
            'menu_icon' => 'dashicons-id'
        )
    );
}

add_action('init', 'init_cpt_experiences');