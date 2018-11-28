<?php
/**
 * @author  Aj <ajanth@levelup.no>
 * @version 2.0
 * @since   2018-11-28
 *
 * POST TYPE: Posts
 */
function remove_default_post_type() {
	remove_menu_page('edit.php');
}

add_action('admin_menu','remove_default_post_type');