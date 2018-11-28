<?php
/**
 * @author  Aj <ajanth@levelup.no>
 * @version 2.0
 * @since   2018-11-28
 *
 * POST TYPE: Projects
 */
function init_cpt_projects()
{
    // 1. Define: Labels
    $labels = array(
    'name' => 'Projects',
    'singular_name' => 'Project',
    'menu_name' => 'Projects'
    );
    // 2. Define: Supports
    $supports = array('title', 'editor', 'revisions');
    // REGISTER NEW POST TYPE
    register_post_type(
        'projects', array(
            'labels' => $labels,
            'description' => '',
            'supports' => $supports,
            'public' => true,
            'menu_icon' => 'dashicons-welcome-widgets-menus'
        )
    );
}

add_action('init', 'init_cpt_projects');