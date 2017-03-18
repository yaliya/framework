<?php

namespace Tau;

class Session
{
  public function __construct() {
    session_start();
  }

  public static function get($name) {
    return $_SESSION[$name];
  }

  public static function set($name, $value) {
    $_SESSION[$name] = $value;
  }

  public static function exists($name) {
    return isset($_SESSION[$name]);
  }

  public static function all() {
    return $_SESSION;
  }

  public static function unset($name) {
    unset($_SESSION[$name]);
  }
}
