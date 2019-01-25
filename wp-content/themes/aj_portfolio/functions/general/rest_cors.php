<?php
/**
 * @author Aj
 * @version 1.0
 * @since 2019-01-25
 *
 * FUNCTION: Define rest cors
 */
function rest_cors() {
  remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
  add_filter('rest_pre_serve_request', function($value) {
          header('Access-Control-Allow-Origin: *');
          header('Access-Control-Allow-Methods: GET');
          header('Access-Control-Allow-Credentials: true');
          header('Access-Control-Expose-Headers: Link', false);
          return $value;
  });
}
add_action('rest_api_init', 'rest_cors', 15);

