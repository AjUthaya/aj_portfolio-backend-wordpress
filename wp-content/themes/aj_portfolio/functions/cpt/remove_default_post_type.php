<?php
/**
 * @author  Aj <ajanth@levelup.no>
 * @version 2.0
 * @since   2018-11-28
 *
 * FUNCTION: Remove posts item from admin menu
 */
function remove_menu_posts() {
	remove_menu_page('edit.php');
}

/**
 * @author  Aj <ajanth@levelup.no>
 * @version 2.0
 * @since   2018-11-28
 *
 * FUNCTION: Remove anything related to posts from admin bar
 */
function remove_admin_bar_posts() 
{
    global $wp_admin_bar;   
    $wp_admin_bar->remove_node('new-post');
}

add_action('admin_menu','remove_menu_posts');
add_action('wp_before_admin_bar_render', 'remove_admin_bar_posts');