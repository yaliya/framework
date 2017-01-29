<?php

namespace Tau\Router;

class Router
{
  protected static $routes;

  public static function request($method, $pattern, $callback) {
    self::$routes[] = array(
      "method"      => $method,
      "pattern"     => $pattern,
      "callback"    => $callback
    );
  }

  public static function get($pattern, $callback) {
    self::request("GET", $pattern, $callback);
  }

  public static function post($pattern, $callback) {
    self::request("POST", $pattern, $callback);
  }

  public static function routes() {
    return self::$routes;
  }

  public static function route() {
    foreach(self::$routes as $route) {
      $args = array();
      $pattern = "@^" . preg_replace('/\\\:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', preg_quote($route["pattern"])) . "$@D";

      if($_SERVER["REQUEST_METHOD"] == $route["method"] && preg_match($pattern, $_SERVER["REQUEST_URI"], $args)) {
        array_shift($args);

        if(is_callable($route['callback'])) {
          echo call_user_func_array($route['callback'], $args);
        }
        else {
          $class = "Tau\\Controllers\\".explode("@", $route["callback"])[0];
          $method = explode("@", $route["callback"])[1];
          echo call_user_func_array(array(new $class, $method), $args);
        }
      }
    }
  }
};
