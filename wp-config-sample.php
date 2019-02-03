<?php

// Database Configuration
define('DB_HOST', 'ENTER_YOUR_VALUE_HERE');
define('DB_NAME', 'ENTER_YOUR_VALUE_HERE');
define('DB_USER', 'ENTER_YOUR_VALUE_HERE');
define('DB_PASSWORD', 'ENTER_YOUR_VALUE_HERE');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
$table_prefix  = 'wp_';


// Security Salts, Keys, Etc
define('AUTH_KEY',         'ENTER_YOUR_VALUE_HERE');
define('SECURE_AUTH_KEY',  'ENTER_YOUR_VALUE_HERE');
define('LOGGED_IN_KEY',    'ENTER_YOUR_VALUE_HERE');
define('NONCE_KEY',        'ENTER_YOUR_VALUE_HERE');
define('AUTH_SALT',        'ENTER_YOUR_VALUE_HERE');
define('SECURE_AUTH_SALT', 'ENTER_YOUR_VALUE_HERE');
define('LOGGED_IN_SALT',   'ENTER_YOUR_VALUE_HERE');
define('NONCE_SALT',       'ENTER_YOUR_VALUE_HERE');


// SSL, Site url, Etc
define('FORCE_SSL_LOGIN', false);
define('WP_TURN_OFF_ADMIN_BAR', false);
define('WP_SITEURL', 'http://127.0.0.1:3000');
define('WP_HOME',    'http://127.0.0.1:3000');
define('DOMAIN_CURRENT_SITE', '127.0.0.1:3000');
define('WP_POST_REVISIONS', 5);
define('WPLANG', '');


// API JWT AUTH
define('JWT_AUTH_SECRET_KEY', 'test');
define('JWT_AUTH_CORS_ENABLE', false);
define('API_WHITELIST_IPS', array(
    '127.0.0.1:30000',
    'www.aj-portfolio.com'
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
    // SERVER: Turn on https
    $_SERVER['HTTPS'] = 'on';

    // Setup (https://docs.sentry.io/clients/php/config/#sentry-php-request-context)
    include_once __DIR__ .  '/wp-content/plugins/Raven/Autoloader.php';
    Raven_Autoloader::register();
    $client = new Raven_Client('ENTER_YOUR_VALUE_HERE', array(
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
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);

// Display debug
if ($env === 'prod') {
    define('WP_DEBUG_DISPLAY', false);
} else {
    define('WP_DEBUG_DISPLAY', true);
}

// That's It. Pencils down
if (!defined('ABSPATH') ) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

require_once ABSPATH . 'wp-settings.php';