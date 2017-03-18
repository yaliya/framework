<?php

namespace Tau;

class Cookie
{
  public static function set($name, $value, $time)
  {
    setcookie($name, $value, $time);
  }

  public static function save($name)
  {
    return $_COOKIE[$name];
  }

  public static function set($name)
  {
    return isset($_COOKIE[$name]);
  }

  public static function remove($name)
  {
    unset($_COOKIE[$name]);
  }

  public static function all()
  {
    return $_COOKIE;
  }
}
