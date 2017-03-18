<?php

namespace Tau;

class Cookie
{
  public static function set($name, $value, $time)
  {
    setcookie($name, $value, $time);
  }

  public static function get($name)
  {
    return $_COOKIE[$name];
  }

  public static function unset($name)
  {
    unset($_COOKIE[$name]);
  }

  public static function all()
  {
    return $_COOKIE;
  }
}
