<?php
/**
 * @author Aj
 * @version 1.0
 * @since 2018-11-28
 *
 * FUNCTION: If timber plugin is disabled,
 * it activates the plugin
 */
function run_activate_plugin($plugin)
{
    $current = get_option('active_plugins');
    $plugin = plugin_basename(trim($plugin));
    if (!in_array($plugin, $current)) {
        $current[] = $plugin;
        sort($current);
        do_action('activate_plugin', trim($plugin));
        update_option('active_plugins', $current);
        do_action('activate_' . trim($plugin));
        do_action('activated_plugin', trim($plugin));
    }
    return null;
}
run_activate_plugin('timber-library/timber.php');