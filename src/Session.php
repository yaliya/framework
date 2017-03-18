<?php

namespace Tau;

class Session
{
  public static function get($name) {
    session_start();
    return $_SESSION[$name];
  }

  public static function save($name, $value) {
    session_start();
    $_SESSION[$name] = $value;
  }

  public static function set($name) {
    session_start();
    return isset($_SESSION[$name]);
  }

  public static function all() {
    session_start();
    return $_SESSION;
  }

  public static function remove($name) {
    session_start();
    unset($_SESSION[$name]);
  }

  public static function clear() {
    session_start();
    session_destroy();
  }
}
