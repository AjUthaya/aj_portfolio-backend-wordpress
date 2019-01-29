<?php
/**
 * @author Aj
 * @version 1.0
 * @since 2019-01-28
 *
 * FUNCTION: Register different image sizes
 */
function add_image_sizes() {
    add_image_size('company_icon', 50, 50, false);
    add_image_size('skill_icon', 80, 80, false);
    add_image_size('project_preview', 800, 800, false);
}

add_action('init', 'add_image_sizes');