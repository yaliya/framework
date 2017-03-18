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

  public static function save($name, $value) {
    $_SESSION[$name] = $value;
  }

  public static function set($name) {
    return isset($_SESSION[$name]);
  }

  public static function all() {
    return $_SESSION;
  }

  public static function delete($name) {
    unset($_SESSION[$name]);
  }
}
