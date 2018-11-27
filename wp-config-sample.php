<?php
// Database Configuration
define('DB_HOST', 'put your database host here');
define('DB_NAME', 'put your database name here');
define('DB_USER', 'put your database username here');
define('DB_PASSWORD', 'put your database password here');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
$table_prefix  = 'wp_';


// Security Salts, Keys, Etc
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');


// SSL, Site url, Etc
define('FORCE_SSL_LOGIN', false);
define('WP_POST_REVISIONS', false);
define('WP_TURN_OFF_ADMIN_BAR', false);
define('WP_SITEURL', 'put your dev your here');
define('WP_HOME', 'put your dev your here');
define('DOMAIN_CURRENT_SITE', 'put your dev url here without protocol');
define('WPLANG', '');


// Multisite settings
define('WP_ALLOW_MULTISITE', false);
define('WP_FALSE', true);
$base = '/';


// Envirement
require_once __DIR__ . '/wp-content/themes/aj_portfolio/functions/dev/get_env.php';
$env = get_env();


// Sentry settings
if ($env === 'prod') {
    // Setup (https://docs.sentry.io/clients/php/config/#sentry-php-request-context)
    include_once __DIR__ .  '/wp-content/plugins/Raven/Autoloader.php';
    Raven_Autoloader::register();
    $client = new Raven_Client('put your sentry dns url here', array(
      'environment' => $env,
      'php_version' => phpversion(),
      'release' => 'put the version of the app here',
      'app_path' => dirname(__FILE__) . '/',
      'sample_rate' => 1
    ));

    // Hook into errors
    $error_handler = new Raven_ErrorHandler($client);
    $error_handler->registerExceptionHandler();
    $error_handler->registerErrorHandler();
    $error_handler->registerShutdownFunction();
}


// Debug
if ($env === 'prod') {
    define('WP_DEBUG', false);
    define('WP_DEBUG_DISPLAY', false);
    define('WP_DEBUG_LOG', false);
} else {
    define('WP_DEBUG', true);
    define('WP_DEBUG_DISPLAY', true);
    define('WP_DEBUG_LOG', true);
}


// That's It. Pencils down
if (!defined('ABSPATH') ) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

require_once ABSPATH . 'wp-settings.php';