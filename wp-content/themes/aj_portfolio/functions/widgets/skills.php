<?php
/**
 * @author Aj
 * @version 1.0
 * @since 2018-11-28
 *
 * CLASS: Skills widget
 */
class WidgetSkills extends WP_Widget {
    function __construct() {
        $widget_ops = array( 
			'classname' => 'widget__skills',
			'description' => 'Skills description',
		);
		parent::__construct('skills', 'Skills', $widget_ops);
    }
 
    // FUNCTION: HTML output
    function widget($args, $instance) {}
 
    // FUNCTION: Update handler
    function update($new_instance, $old_instance) {
        return $new_instance;
    }
 
    // FUNCTION: Admin HTML output
    function form($instance) {
        return '';
    }
}
 
/**
 * @author Aj
 * @version 1.0
 * @since 2018-11-28
 *
 * FUNCTION: Register widget
 */
function widget_skills() {
    register_widget('WidgetSkills');
}

add_action('widgets_init', 'widget_skills');