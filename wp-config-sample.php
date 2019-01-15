<?php
// Simple function to set default value
function default_value(&$var, $default) {
    if (empty($var)) {
        $var = $default;
    }
}

// Database Configuration
define('DB_HOST', default_value($_ENV['WORDPRESS_DB_HOST'], 'value'));
define('DB_NAME', default_value($_ENV['WORDPRESS_DB'], 'value'));
define('DB_USER', default_value($_ENV['WORDPRESS_DB_USER'], 'value'));
define('DB_PASSWORD', default_value($_ENV['WORDPRESS_DB_PASSWORD'], 'value'));
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
$table_prefix  = 'wp_';


// Security Salts, Keys, Etc
define('AUTH_KEY',         'ENTER_YOUR_KEY_HERE');
define('SECURE_AUTH_KEY',  'ENTER_YOUR_KEY_HERE');
define('LOGGED_IN_KEY',    'ENTER_YOUR_KEY_HERE');
define('NONCE_KEY',        'ENTER_YOUR_KEY_HERE');
define('AUTH_SALT',        'ENTER_YOUR_KEY_HERE');
define('SECURE_AUTH_SALT', 'ENTER_YOUR_KEY_HERE');
define('LOGGED_IN_SALT',   'ENTER_YOUR_KEY_HERE');
define('NONCE_SALT',       'ENTER_YOUR_KEY_HERE');


// SSL, Site url, Etc
define('FORCE_SSL_LOGIN', false);
define('WP_POST_REVISIONS', false);
define('WP_TURN_OFF_ADMIN_BAR', false);
define('WP_SITEURL', 'ENTER_YOUR_SITE_URL_HERE');
define('WP_HOME', 'ENTER_YOUR_HOME_URL_HERE');
define('DOMAIN_CURRENT_SITE', 'ENTER_YOUR_SITE_URL_WITHOUT_PROTOCOL_HERE');
define('WPLANG', '');


// API JWT AUTH
define('JWT_AUTH_SECRET_KEY', 'test');
define('JWT_AUTH_CORS_ENABLE', true);
define('API_WHITELIST_IPS', array(
    'ENTER_AN_IP_ADDRESS_HERE'
));


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
    $client = new Raven_Client('ENTER_YOUR_SENTRY_DNS_KEY_HERE', array(
      'environment' => $env,
      'php_version' => phpversion(),
      'app_path' => dirname(__FILE__) . '/'
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