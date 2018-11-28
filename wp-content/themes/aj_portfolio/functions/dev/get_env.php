<?php
/**
 * @author  Aj <ajanth@levelup.no>
 * @version 1.0
 * @since   2018-11-27
 *
 * Get envirement mode based off HTTP or HTTPS host
 */
function get_env()
{
    // 1. Get host url
    $host_url = $_SERVER['HTTP_HOST'];
    // 2. Define array of local addresses
    $dev_urls = array(
    'wordpress.test',
    'localhost',
    'localhost:8888'
    );
    // 4. Define a default envirement variable of production
    $env = 'prod';
    // 5. Loop through the dev urls defined above
    foreach ($dev_urls as $url) {
        // A. Run regex to see & get array of matches back
        preg_match("/$url/", $host_url, $matches);
        // B. Check if the regex defined above matches any of the dev url's
        if ($matches) {
            // I. Set env variable to dev
            $env = 'dev';
        }
    }
    
    return $env;
}