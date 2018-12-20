<?php
/**
 * @author  Aj <ajanth@levelup.no>
 * @version 2.0
 * @since   2018-12-19
 *
 * POST TYPE: Skills
 */
function init_cpt_skills()
{
    // 1. Define: Labels
    $labels = array(
    'name' => 'Skills',
    'singular_name' => 'Skill',
    'menu_name' => 'Skills'
    );
    // 2. Define: Supports
    $supports = array('title', 'editor', 'revisions');
    // REGISTER NEW POST TYPE
    register_post_type(
        'skills', array(
            'labels' => $labels,
            'description' => '',
            'supports' => $supports,
            'public' => true,
            'menu_icon' => 'dashicons-tag'
        )
    );
}

add_action('init', 'init_cpt_skills');