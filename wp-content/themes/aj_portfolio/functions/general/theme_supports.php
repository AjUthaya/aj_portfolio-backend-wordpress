<?php
/**
 * @author Aj
 * @version 1.0
 * @since 2018-11-28
 *
 * FUNCTION: Init WP supports
 */
function theme_supports()
{
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // Let WP manage title
    add_theme_support('title-tag');

    // Switch core HTML to HTML5
    add_theme_support(
        'html5', array(
        'gallery',
        'caption',
        )
    );

    // Post formats
    add_theme_support(
        'post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'audio',
        )
    );

    // Add support to WP menues
    add_theme_support('menus');
}

add_action('after_setup_theme', 'theme_supports');