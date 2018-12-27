<?php
/**
 * @author Aj
 * @version 1.0
 * @since 2018-12-23
 *
 * FUNCTION: Restrict allowed IP addresses
 */
function restrict_rest_allowed_ips($errors) {

  // IF: Request IP is not in the allowed IP array
  if (!in_array($_SERVER['REMOTE_ADDR'], API_WHITELIST_IPS)) {
    return new WP_Error('forbidden_access', 'Request origin of ' . $_SERVER['REMOTE_ADDR'] . ' is not whitelisted', array(
      'status' => 403
    ));
  }

  return $errors;
}

add_filter('rest_authentication_errors', 'restrict_rest_allowed_ips');