<?php

namespace Tau;

class Session
{
  public static function start() {
    if(session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }
  public static function get($name) {
    self::start();
    return $_SESSION[$name];
  }

  public static function save($name, $value) {
    self::start();
    $_SESSION[$name] = $value;
  }

  public static function set($name) {
    self::start();
    return isset($_SESSION[$name]);
  }

  public static function all() {
    self::start();
    return $_SESSION;
  }

  public static function remove($name) {
    self::start();
    unset($_SESSION[$name]);
  }

  public static function clear() {
    self::start();
    session_destroy();
  }
}
