<?php

// IF: Envirement is development
if (get_env() === 'dev') {
    // Enable on screen error messages
    ini_set('display_errors', true);
    ini_set('display_startup_errors', true);
    error_reporting(E_ALL);
}

// IMPORT: Timber setup
require_once 'functions/timber/activate_timber_plugin.php';
require_once 'functions/timber/validate_plugin_activation.php';
require_once __DIR__ . '/functions/general/theme_supports.php';
require_once __DIR__ . '/functions/dev/get_env.php';

// IMPORT: CPT setup
require_once __DIR__ . '/functions/cpt/projects.php';
require_once __DIR__ . '/functions/cpt/experiences.php';
require_once __DIR__ . '/functions/cpt/skills.php';
require_once __DIR__ . '/functions/cpt/categories.php';
require_once __DIR__ . '/functions/cpt/remove_default_post_type.php';

// IMPORT: Mime support for uploads
require_once __DIR__ . '/functions/general/mime_upload_support.php';

// IMPORT: Enqueue assets
require_once __DIR__ . '/functions/general/enqueue_assets.php';

// IMPORT: Rest
require_once __DIR__ . '/functions/general/rest_restrict.php';
require_once __DIR__ . '/functions/general/rest_cors.php';

/*
  ------------------------------------------------------------------------------
    START: TIMBER SETUP
  ------------------------------------------------------------------------------
*/

// Set directory to look for .twig files
Timber::$dirname = array('./');
// Auto escape all characthers
Timber::$autoescape = true;

class AjPortfolio extends Timber\Site
{
    public function __construct()
    {
        // INIT CONTEXT API
        add_filter('timber_context', array($this, 'add_to_context'));
        // INIT CONTEXT API FOR ADMIN
        add_action('admin_init', array($this, 'add_to_context'));
        parent::__construct();
    }
    
    public function add_to_context($context)
    {
        // Logged In
        $context['logged_in'] = false;
        $logged_in_user =  wp_get_current_user();
        if (!empty((array) $logged_in_user->data)) {
            $context['logged_in'] = true;
        }
        // Site
        $context['site'] = $this;
        $context['page'] = new TimberPost();
        // Logo
        $svg_logo = file_get_contents(__DIR__ . '/assets/images/logo_icon.svg');
        $context['logo_svg'] = $svg_logo;
        // Menus
        $context['menu_top_nav'] = new Timber\Menu('menu_top_nav');
        // Dev
        $context['env'] = get_env();
        // RETURN
        return $context;
    }
}

new AjPortfolio();

/*
  ------------------------------------------------------------------------------
    END: TIMBER SETUP
  ------------------------------------------------------------------------------
*/