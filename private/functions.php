<?php

  function h($string="") {
    return htmlspecialchars($string);
  }

  function u($string="") {
    return urlencode($string);
  }

  function raw_u($string="") {
    return rawurlencode($string);
  }

  function redirect_to($location) {
    header("Location: " . $location);
    exit;
  }

  function url_for($script_path) {
    return DOC_ROOT . $script_path;
  }

  function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
  }

  function is_get_request() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
  }

  function request_is_same_domain() {
    if(!isset($_SERVER['HTTP_REFERER'])) { return false; }
    $referer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    $actual_host = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);
    return ($referer_host === $actual_host);
  }

  function display_errors($errors=array()) {
    $output = '';
    if (!empty($errors)) {
      $output .= "<div class=\"errors\">";
      $output .= "Please fix the following errors:";
      $output .= "<ul>";
      foreach ($errors as $error) {
        $output .= "<li>{$error}</li>";
      }
      $output .= "</ul>";
      $output .= "</div>";
    }
    return $output;
  }

  function random_string($length=22) {
    // random_bytes requires an integer larger than 1
    $length = max(1, (int) $length);
    // generates a longer string than needed
    $rand_str = base64_encode(random_bytes($length));
    // substr cuts it to the correct size
    return substr($rand_str, 0, $length);
  }

  function make_salt() {
    $rand_str = random_string(22);
    $salt = strtr($rand_str, '+', '.');
    return $salt;
  }

  function my_password_hash($password, $cost=10) {
    // Use bcrypt with a "cost" of 10. $salt needs to be 22 letters long
    // Your final var will be something like $2y$10$xxxx(22 xs)$
    // Make sure you have $ at end
    $salt = make_salt();
    $hash_format = '$2y$' . $cost . '$' . $salt . '$';
    return crypt($password, $hash_format);
  }

  function my_password_verify($attempted_password, $hashed_password) {
    $attempted_hash = crypt($attempted_password, $hashed_password);
    return $attempted_hash === $hashed_password;
  }
?>
